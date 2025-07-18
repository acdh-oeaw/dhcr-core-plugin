<?php

declare(strict_types=1);

namespace DhcrCore\Model\Entity;

use Cake\ORM\Entity;

class ExternalResource extends Entity
{
    protected $_accessible = [
        'course_id' => true,
        'label' => true,
        'url' => true,
        'type' => true,
        'affiliation' => true,
        'visible' => true,
        'created' => true,
        'modified' => true,
        'course' => true,
    ];
}
