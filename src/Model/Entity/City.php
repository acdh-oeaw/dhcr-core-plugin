<?php

namespace DhcrCore\Model\Entity;

use Cake\ORM\Entity;
use JeremyHarris\LazyLoad\ORM\LazyLoadEntityTrait;

class City extends Entity
{
    use LazyLoadEntityTrait;

    protected $_accessible = [
        'country_id' => true,
        'name' => true,
        'country' => true,
        'courses' => true,
        'institutions' => true
    ];

    protected $_hidden = [
        'courses'
    ];

    // make virtual fields visible for JSON serialization
    //protected $_virtual = ['course_count'];

    protected function _getCourseCount()
    {
        return count($this->courses);
    }
}
