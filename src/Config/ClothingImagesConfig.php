<?php

namespace App\Config;


class ClothingImagesConfig
{
    public $apiQuery;
    public $apiCategory;

    public function __construct($apiQuery = 'dress', $apiCategory = 'fashion')
    {
        $this->apiQuery = $apiQuery;
        $this->apiCategory = $apiCategory;
    }

}
