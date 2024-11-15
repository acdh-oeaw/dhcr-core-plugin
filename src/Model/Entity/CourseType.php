<?php

namespace DhcrCore\Model\Entity;

use Cake\ORM\Entity;
use JeremyHarris\LazyLoad\ORM\LazyLoadEntityTrait;

class CourseType extends Entity
{

    use LazyLoadEntityTrait;

    protected $_accessible = [
        'course_parent_type_id' => true,
        'name' => true,
        'course_parent_type' => true,
        'courses' => true
    ];

    protected $_hidden = [
        'courses'
    ];

    // make virtual fields visible for JSON serialization
    //protected $_virtual = ['full_name'];

    protected function _getCourseCount()
    {
        return count($this->courses);
    }

    protected function _getFullName()
    {
        return $this->course_parent_type->name . ' - ' . $this->name;
    }
}
