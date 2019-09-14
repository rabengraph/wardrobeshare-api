<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Mockaroo\Mockaroo;
use App\Entity\User;
use App\Entity\PixabayImage;
use App\Entity\Clothing;
use Doctrine\Common\Collections\ArrayCollection;
use App\Pixabay\Pixabay;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;


class MockarooFixtures extends Fixture
{
    private $mockaroo;
    private $pixabay;
    private $denormalizer;

    private $users = [];
    private $clothings = [];
    private $dressImages = [];
    private $profileImages = [];

    public function __construct(Mockaroo $mockaroo, Pixabay $pixabay, DenormalizerInterface $denormalizer)
    {
        $this->mockaroo = $mockaroo;
        $this->pixabay = $pixabay;
        $this->denormalizer = $denormalizer;
    }

    public function load(ObjectManager $manager)
    {
        $mockaroo = $this->mockaroo;

        $this->createDressImages($manager, $this->pixabay, $this->denormalizer);
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
        $users = $userGenerator->generate(30);
        foreach ($users as $user) {
            $manager->persist($user);
        }
        $this->users = $users;

        // clothings
        $clothingGenerator = $mockaroo->makeGenerator(Clothing::class);
        $clothings = $clothingGenerator->generate(150);
        foreach ($clothings as $clothing) {
            $manager->persist($clothing);
        }
        $this->clothings = $clothings;
    }

    private function loadEntityRelations(ObjectManager $manager)
    {
        foreach ($this->users as $user) {

            // pick one media files
            $images = $this->popRandom($this->profileImages, 1,1);
            if (!$images->isEmpty()) {
                $user->setAvatar($images->first());
                $manager->persist($user);
            }
        }

        foreach ($this->clothings as $clothing) {

            $images = $this->popRandom($this->dressImages, 1,1);
            if (!$images->isEmpty()) {
                $clothing->setImage($images->first());
                $manager->persist($clothing);
            }

            $users = $this->pickRandom($this->users, 1,1);
            if (!$users->isEmpty()) {
                $clothing->setPerson($users->first());
                $manager->persist($clothing);
            }
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


    private function createDressImages(ObjectManager $manager, Pixabay $pixabay, DenormalizerInterface $denormalizer)
    {
        $result = $pixabay->request([
            'q' => 'dress',
            'image_type' => 'photo',
            'category' => 'fashion',
            'orientation' => 'vertical',
            'per_page' => 200
        ]);

        $denormalizedImages = [];
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
        $this->dressImages = $denormalizedImages;
    }

    private function createProfileImages(ObjectManager $manager, Pixabay $pixabay, DenormalizerInterface $denormalizer)
    {
        $result = $pixabay->request([
            'q' => 'face model female',
            'image_type' => 'photo',
            'category' => 'people',
            'orientation' => 'horizontal',
            'order' => 'latest',
            'per_page' => 200
        ]);

        $denormalizedImages = [];
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
        $this->profileImages = $denormalizedImages;
    }
}
