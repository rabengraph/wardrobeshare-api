<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Mockaroo\Mockaroo;
use App\Entity\User;
use App\Entity\PixabayImage;
use App\Entity\Clothing;
use App\Entity\Location;
use App\Entity\Color;
use App\Entity\Manufacturer;
use App\Entity\Culture;
use App\Entity\Event;
use App\Entity\Occasion;
use Doctrine\Common\Collections\ArrayCollection;
use App\Pixabay\Pixabay;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use App\Config\ClothingImagesConfig;


class MockarooFixtures extends Fixture
{
    private $mockaroo;
    private $pixabay;
    private $denormalizer;
    private $clothingImagesPixabayApiQuery = 'dress';
    private $clothingImagesPixabayApiCategory = 'fashion';

    private $users = [];
    private $locations = [];
    private $colors = [];
    private $manufacturers = [];
    private $clothings = [];
    private $dressImages = [];
    private $profileImages = [];
    private $occasions = [];
    private $events = [];
    private $cultures = [];

    public function __construct(Mockaroo $mockaroo, Pixabay $pixabay, DenormalizerInterface $denormalizer, ClothingImagesConfig $clothingImagesConfig)
    {
        $this->mockaroo = $mockaroo;
        $this->pixabay = $pixabay;
        $this->denormalizer = $denormalizer;
        $this->clothingImagesPixabayApiQuery = $clothingImagesConfig->apiQuery;
        $this->clothingImagesPixabayApiCategory = $clothingImagesConfig->apiCategory;
    }


    public function load(ObjectManager $manager)
    {
        $mockaroo = $this->mockaroo;

        $this->createDressImages(600, $manager, $this->pixabay, $this->denormalizer);
        $this->createProfileImages(200, $manager, $this->pixabay, $this->denormalizer);

        // create entities
        $this->loadEntities($manager);

        // create relations
        $this->loadEntityRelations($manager);

        // remove orpahns
        $this->removeOrphans($manager);

        $manager->flush();

    }

    private function loadEntities(ObjectManager $manager)
    {
        $mockaroo = $this->mockaroo;
        $pixabay = $this->pixabay;


        // user
        $userGenerator = $mockaroo->makeGenerator(User::class);
        $users = $userGenerator->generate(60);
        foreach ($users as $user) {
            $manager->persist($user);
        }
        $this->users = $users;

        // clothings
        $clothingGenerator = $mockaroo->makeGenerator(Clothing::class);
        $clothings = $clothingGenerator->generate(600);
        foreach ($clothings as $clothing) {
            $manager->persist($clothing);
        }
        $this->clothings = $clothings;

        // locations
        $clothingGenerator = $mockaroo->makeGenerator(Location::class);
        $locations = $clothingGenerator->generate(50);
        foreach ($locations as $location) {
            $manager->persist($location);
        }
        $this->locations = $locations;

        // colors
        $colorsGenerator = $mockaroo->makeGenerator(Color::class);
        $colors = $colorsGenerator->generate(10);
        foreach ($colors as $color) {
            $manager->persist($color);
        }
        $this->colors = $colors;

        // manufacturers
        $manufacturersGenerator = $mockaroo->makeGenerator(Manufacturer::class);
        $manufacturers = $manufacturersGenerator->generate(20);
        foreach ($manufacturers as $manufacturer) {
            $manager->persist($manufacturer);
        }
        $this->manufacturers = $manufacturers;

        // cultures
        $generator = $mockaroo->makeGenerator(Culture::class);
        $entities = $generator->generate(10);
        foreach ($entities as $entity) {
            $manager->persist($entity);
        }
        $this->cultures = $entities;

        // events
        $generator = $mockaroo->makeGenerator(Event::class);
        $entities = $generator->generate(1000);
        foreach ($entities as $entity) {
            $manager->persist($entity);
        }
        $this->events = $entities;

        // occasions
        $occasionNames = ['Cocktail Party', 'Wedding', 'Job Interview', 'Opera', 'Funeral'];
        foreach ($occasionNames as $occasionName) {
            $occasion = new Occasion;
            $occasion->setName($occasionName);
            $manager->persist($occasion);
            $this->occasions[] = $occasion;
        }
    }

    private function loadEntityRelations(ObjectManager $manager)
    {
        foreach ($this->events as $event) {

            // pick one occasion
            $occasions = $this->pickRandom($this->occasions, 1,1);
            if (!$occasions->isEmpty()) {
                $event->setOccasion($occasions->first());
            }

            $manager->persist($event);
        }

        foreach ($this->users as $user) {

            // pick one media files
            $images = $this->popRandom($this->profileImages, 1,1);
            if (!$images->isEmpty()) {
                $user->setAvatar($images->first());
            }

            // pick one locations
            $locations = $this->pickRandom($this->locations, 1,1);
            if (!$locations->isEmpty()) {
                $user->setLocation($locations->first());
            }

            $manager->persist($user);
        }

        foreach ($this->clothings as $clothing) {

            $images = $this->popRandom($this->dressImages, 1,1);
            if (!$images->isEmpty()) {
                $clothing->setImage($images->first());
            }

            $users = $this->pickRandom($this->users, 1,1);
            if (!$users->isEmpty()) {
                $clothing->setPerson($users->first());
            }

            $colors = $this->pickRandom($this->colors, 1,3);
            foreach($colors as $color) {
                $clothing->addColor($color);
            }

            $manufacturers = $this->pickRandom($this->manufacturers, 1,1);
            if (!$manufacturers->isEmpty()) {
                $clothing->setManufacturer($manufacturers->first());
            }

            $cultures = $this->pickRandom($this->cultures, 1, 2);
            foreach($cultures as $culture) {
                $clothing->addCulture($culture);
            }

            $events = $this->popRandom($this->events, 0, 4);
            foreach($events as $event) {
                $clothing->addEventsWorn($event);
            }


            $manager->persist($clothing);
        }
    }

    private function removeOrphans(ObjectManager $manager)
    {
        // foreach ($this->comments as $comment) {
        //     if (!$comment->visit) {
        //         $manager->remove($comment);
        //     }
        // }
    }

    private function pickRandom(array $pool, $min = 1, $max = 1)
    {
        $count = rand($min, $max);
        $randKeys = array_rand($pool, $count);
        if (!is_array($randKeys)) {
            $randKeys = [$randKeys];
        }
        $return = new ArrayCollection();
        foreach ($randKeys as $randKey) {
            $return->add($pool[$randKey]);
        }
        return $return;
    }

    private function popRandom(array &$pool, $min = 0, $max = 5)
    {
        $count = rand($min, $max);
        $popped = new ArrayCollection();
        for ($i=0; $i < $count; $i++) {
            if (count($pool)) {
                $popped->add(array_pop($pool));
            }
        }
        return $popped;
    }


    private function createDressImages($count, ObjectManager $manager, Pixabay $pixabay, DenormalizerInterface $denormalizer)
    {
        $pages = ceil($count / 200);
        $denormalizedImages = [];

        while ($pages) {
            $result = $pixabay->request([
                'q' => $this->clothingImagesPixabayApiQuery,
                'image_type' => 'photo',
                'category' => $this->clothingImagesPixabayApiCategory,
                'orientation' => 'vertical',
                'per_page' => 200,
                'page' => $pages
            ]);

            foreach ($result['hits'] as $one) {
                $normalizedImage = [
                    'type' => PixabayImage::TYPE_DRESS,
                    'url' => $one['webformatURL'],
                    'width' => $one['webformatWidth'],
                    'height' => $one['webformatHeight'],
                    'previewUrl' => $one['previewURL'],
                    'previewWidth' => $one['previewWidth'],
                    'previewHeight' => $one['previewHeight'],
                ];
                $image = $this->denormalizer->denormalize($normalizedImage, PixabayImage::class);
                $manager->persist($image);

                $denormalizedImages[] = $image;
            }
            $pages--;
        }

        $this->dressImages = $denormalizedImages;
    }

    private function createProfileImages($count, ObjectManager $manager, Pixabay $pixabay, DenormalizerInterface $denormalizer)
    {
        $pages = ceil($count / 200);
        $denormalizedImages = [];

        while ($pages) {
            $result = $pixabay->request([
                'q' => 'face model female',
                'image_type' => 'photo',
                'category' => 'people',
                'orientation' => 'horizontal',
                'order' => 'latest',
                'per_page' => 200,
                'page' => $pages
            ]);

            foreach ($result['hits'] as $one) {
                $normalizedImage = [
                    'type' => PixabayImage::TYPE_PROFILE,
                    'url' => $one['webformatURL'],
                    'width' => $one['webformatWidth'],
                    'height' => $one['webformatHeight'],
                    'previewUrl' => $one['previewURL'],
                    'previewWidth' => $one['previewWidth'],
                    'previewHeight' => $one['previewHeight'],
                ];
                $image = $this->denormalizer->denormalize($normalizedImage, PixabayImage::class);
                $manager->persist($image);

                $denormalizedImages[] = $image;
            }
            $pages--;
        }
        $this->profileImages = $denormalizedImages;
    }
}
