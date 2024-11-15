<?php

namespace DhcrCore\Model\Entity;

use Cake\ORM\Entity;
use JeremyHarris\LazyLoad\ORM\LazyLoadEntityTrait;

class Institution extends Entity
{
    use LazyLoadEntityTrait;

    protected $_accessible = [
        'city_id' => true,
        'country_id' => true,
        'name' => true,
        'description' => true,
        'url' => true,
        'lon' => true,
        'lat' => true,
        'created' => false,
        'updated' => false,
        'city' => true,
        'country' => true,
        'courses' => true,
        'users' => true
    ];

    protected $_hidden = [
        'lon',
        'lat',
        'users',
        'courses'
    ];

    // make virtual fields visible for JSON serialization
    //protected $_virtual = ['course_count'];

    protected function _getCourseCount()
    {
        return count($this->courses);
    }
}
