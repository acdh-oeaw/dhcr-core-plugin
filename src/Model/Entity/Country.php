<?php

namespace DhcrCore\Model\Entity;

use Cake\ORM\Entity;
use JeremyHarris\LazyLoad\ORM\LazyLoadEntityTrait;

class Country extends Entity
{
    use LazyLoadEntityTrait;

    protected $_accessible = [
        'name' => true,
        'domain_name' => true,
        'stop_words' => true,
        'cities' => true,
        'courses' => true,
        'institutions' => true,
        'users' => false
    ];

    protected $_hidden = [
        'domain_name',
        'stop_words',
        'courses'
    ];

    // make virtual fields visible for JSON serialization
    //protected $_virtual = ['course_count'];

    protected function _getCourseCount()
    {
        return count($this->courses);
    }
}
