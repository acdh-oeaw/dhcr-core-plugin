<?php

namespace DhcrCore\Model\Entity;

use Cake\ORM\Entity;
use JeremyHarris\LazyLoad\ORM\LazyLoadEntityTrait;

class TadirahTechnique extends Entity
{
    use LazyLoadEntityTrait;

    protected $_accessible = [
        'name' => true,
        'description' => true,
        'courses' => true,
        'tadirah_activities' => true
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
