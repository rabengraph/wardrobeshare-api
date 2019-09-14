<?php

namespace App\Mockaroo;

use Doctrine\Common\Annotations\AnnotationReader;
use ReflectionClass;
use GuzzleHttp\Client;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;


class Generator
{
    private $entityClass;
    private $apiKey;
    private $httpClient;
    private $tempFileDir;
    private $uploadedFileProperty;
    private $denormalizer;
    private $fileDenormalizer;

    public function __construct($entityClass, $apiKey, Client $httpClient, DenormalizerInterface $denormalizer, $tempFileDir)
    {
        $this->entityClass = $entityClass;
        $this->apiKey = $apiKey;
        $this->httpClient = $httpClient;
        $this->tempFileDir = $tempFileDir;

        $encoders = [ new JsonEncoder()];
        $normalizers = [ new ObjectNormalizer()];

        $this->fileDenormalizer = new Serializer($normalizers, $encoders);
        $this->denormalizer = $denormalizer;
    }

    public function generate($count = 1)
    {
        $annotationReader = new AnnotationReader;
        $reflector = new ReflectionClass($this->entityClass);
        $properties = $reflector->getProperties();

        $parametersNormalized = []; // Parameter[]

        // class annotations
        $classAnnotations =  $annotationReader->getClassAnnotations($reflector);
        foreach ($classAnnotations as $classAnnotation) {
            if ($classAnnotation instanceof Parameter) {
                if ($classAnnotation->value && is_array($classAnnotation->value) && isset($classAnnotation->value['name'])) {
                    $parametersNormalized[] = $classAnnotation->value;
                }
            }
        }

        // property annotations
        foreach ($properties as $property) {
            $propertyAnnotations =  $annotationReader->getPropertyAnnotations($property);
            foreach ($propertyAnnotations as $propertyAnnotation) {
                if ($propertyAnnotation instanceof Parameter) {
                    if ($propertyAnnotation->value && is_array($propertyAnnotation->value)) {
                        $parametersNormalized[] = array_merge($propertyAnnotation->value, ['name' => $property->getName()]);
                    }
                }
                if ($propertyAnnotation instanceof UploadedFile) {
                    $this->uploadedFileProperty = $property->getName();
                }
            }
        }

        $response = $this->httpClient->request('GET', 'api/generate.json',
            [
                'query' => [
                    'key'    => $this->apiKey,
                    'fields' => json_encode($parametersNormalized),
                    'count'  => $count,
                ]
            ]
        );

        $normalizedItems = json_decode((string) $response->getBody(), true);

        // handle file uploads
        if ($this->uploadedFileProperty) {
            foreach ($normalizedItems as $key => $normalizedItem) {
                $fileUrl = $normalizedItem[$this->uploadedFileProperty];
                $uploadedFile = $this->urlToUploadedFile($fileUrl);
                $normalizedItem[$this->uploadedFileProperty] = $uploadedFile;
                $normalizedItems[$key] = $normalizedItem;
            }
        }

        $entities = [];
        foreach ($normalizedItems as $key => $normalizedItem) {

            // denormalize, but skip an uploaded file
            $entity = $this->denormalizer->denormalize($normalizedItem, $this->entityClass, null, ['ignored_attributes' => [$this->uploadedFileProperty]]);

            // denormalize, the uploaded file
            if ($this->uploadedFileProperty) {
                $entity = $this->fileDenormalizer->denormalize($normalizedItem, $this->entityClass, null, ['object_to_populate' => $entity,  'attributes' => [$this->uploadedFileProperty]]);
            }

            $entities[] = $entity;
        }
        return $entities;
    }

    private function urlToUploadedFile($url)
    {
        $path = $this->tempFileDir . md5($url);
        $contextOptions = array(
            'ssl' => array(
                'verify_peer'   => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
                'verify_depth'      => 0
            )
        );
        $sslContext = stream_context_create($contextOptions);
        copy($url, $path, $sslContext);
        $imageFile = new \Symfony\Component\HttpFoundation\File\UploadedFile($path, md5($url),  null, null, null, true);
        return $imageFile;
    }
}
