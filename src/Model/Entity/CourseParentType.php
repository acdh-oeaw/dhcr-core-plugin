<?php

namespace DhcrCore\Model\Entity;

use Cake\ORM\Entity;
use JeremyHarris\LazyLoad\ORM\LazyLoadEntityTrait;

class CourseParentType extends Entity
{
    use LazyLoadEntityTrait;

    protected $_accessible = [
        'name' => true,
        'course_types' => true,
        'courses' => true
    ];

    protected $_hidden = [
        'courses'
    ];

    protected function _getCourseCount()
    {
        return count($this->courses);
    }
}
