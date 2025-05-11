<?php

declare(strict_types=1);

namespace DhcrCore\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class ExternalResourcesFixture extends TestFixture
{
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'course_id' => 1,
                'label' => 'Lorem ipsum dolor sit amet',
                'url' => 'Lorem ipsum dolor sit amet',
                'type' => 'Lorem ipsum dolor sit amet',
                'affiliation' => 'Lorem ipsum dolor sit amet',
                'visible' => 1,
                'created' => '2025-05-11 19:11:26',
                'updated' => '2025-05-11 19:11:26',
            ],
        ];
        parent::init();
    }
}
