<?php

namespace DhcrCore\Model\Entity;

use Cake\ORM\Entity;

class DeletionReason extends Entity
{
    protected $_accessible = [
        'name' => true,
        'courses' => true
    ];
}
