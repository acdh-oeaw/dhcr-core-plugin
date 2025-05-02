<?php

namespace DhcrCore\Model\Entity;

use Cake\ORM\Entity;

class Course extends Entity
{
    protected $_accessible = [
        'user_id' => true,
        'active' => true,
        'deleted' => false,
        'deletion_reason_id' => true,
        'approved' => false,
        'approval_token' => false,
        'mod_mailed' => false,
        'created' => false,
        'updated' => true,
        'last_reminder' => true,
        'name' => true,
        'description' => true,
        'country_id' => false,
        'city_id' => false,
        'institution_id' => true,
        'department' => true,
        'course_parent_type_id' => false,
        'course_type_id' => true,
        'language_id' => true,
        'access_requirements' => true,
        'start_date' => true,
        'duration' => true,
        'course_duration_unit_id' => true,
        'online_course' => true,
        'recurring' => true,
        'info_url' => true,
        'guide_url' => false,
        'skip_info_url' => false,
        'skip_guide_url' => false,
        'ects' => true,
        'contact_mail' => true,
        'contact_name' => true,
        'lon' => true,
        'lat' => true,
        'user' => false,
        'deletion_reason' => true,
        'country' => false,
        'city' => false,
        'institution' => false,
        'course_parent_type' => false,
        'course_type' => false,
        'language' => false,
        'course_duration_unit' => false,
        'disciplines' => true,
        'tadirah_objects' => true,
        'tadirah_techniques' => true,
        'external_resource' => true,
    ];

    protected $_hidden = [
        'user_id',
        'approval_token',
        'mod_mailed',
        'guide_url',
        'skip_info_url',
        'skip_guide_url',
        'user'
    ];
}
