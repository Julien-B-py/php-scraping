<?php

class Car
{
    public $model;
    public $version;
    public $price;
    public $goodDeal;
    public $location;
    public $km;
    public $year;
    public $url;

    public function __construct($model, $version, $price, $goodDeal, $location, $km, $year, $url)
    {
        $this->model = $model;
        $this->version = $version;
        $this->price = $price;
        $this->goodDeal = $goodDeal;
        $this->location = $location;
        $this->km = $km;
        $this->year = $year;
        $this->url = $url;
    }
}
