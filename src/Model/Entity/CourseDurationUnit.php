<?php

namespace DhcrCore\Model\Entity;

use Cake\ORM\Entity;

class CourseDurationUnit extends Entity
{
    protected $_accessible = [
        'name' => true,
        'courses' => true
    ];
}
