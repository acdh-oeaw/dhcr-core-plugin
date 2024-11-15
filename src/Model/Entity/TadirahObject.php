<?php

namespace DhcrCore\Model\Entity;

use Cake\ORM\Entity;
use JeremyHarris\LazyLoad\ORM\LazyLoadEntityTrait;

class TadirahObject extends Entity
{
    use LazyLoadEntityTrait;

    protected $_accessible = [
        'name' => true,
        'description' => true,
        'courses' => true
    ];

    protected $_hidden = [
        '_joinData',
        'courses'
    ];

    protected function _getCourseCount()
    {
        return count($this->courses);
    }
}
