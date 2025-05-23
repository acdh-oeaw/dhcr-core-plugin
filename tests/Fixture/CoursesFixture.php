<?php

namespace DhcrCore\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class CoursesFixture extends TestFixture
{
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'user_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'active' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '1', 'comment' => '', 'precision' => null],
        'deleted' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null],
        'deletion_reason_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'approved' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null],
        'approval_token' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'mod_mailed' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'updated' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'last_reminder' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'name' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'description' => ['type' => 'text', 'length' => null, 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => '', 'precision' => null],
        'country_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'city_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'institution_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'department' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'course_parent_type_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'course_type_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'language_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'access_requirements' => ['type' => 'text', 'length' => null, 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => '', 'precision' => null],
        'start_date' => ['type' => 'string', 'length' => 100, 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'duration' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'course_duration_unit_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'recurring' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '1', 'comment' => '', 'precision' => null],
        'online_course' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null],
        'info_url' => ['type' => 'text', 'length' => null, 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => '', 'precision' => null],
        'guide_url' => ['type' => 'text', 'length' => null, 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => '', 'precision' => null],
        'skip_info_url' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'skip_guide_url' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'ects' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'contact_mail' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => 'as opposed to the former \'contact name\' colums, the lecturer properties are supposed to contain only a single contact', 'precision' => null, 'fixed' => null],
        'contact_name' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => 'enter a single email address only', 'precision' => null, 'fixed' => null],
        'lon' => ['type' => 'decimal', 'length' => 10, 'precision' => 6, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'lat' => ['type' => 'decimal', 'length' => 10, 'precision' => 6, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        '_indexes' => [
            'institution_id' => ['type' => 'index', 'columns' => ['institution_id'], 'length' => []],
            'active' => ['type' => 'index', 'columns' => ['active'], 'length' => []],
            'FK_courses_course_parent_types' => ['type' => 'index', 'columns' => ['course_parent_type_id'], 'length' => []],
            'FK_courses_languages' => ['type' => 'index', 'columns' => ['language_id'], 'length' => []],
            'country_id' => ['type' => 'index', 'columns' => ['country_id'], 'length' => []],
            'city_id' => ['type' => 'index', 'columns' => ['city_id'], 'length' => []],
            'course_type_id' => ['type' => 'index', 'columns' => ['course_type_id'], 'length' => []],
            'lon' => ['type' => 'index', 'columns' => ['lon'], 'length' => []],
            'lat' => ['type' => 'index', 'columns' => ['lat'], 'length' => []],
            'FK_courses_users' => ['type' => 'index', 'columns' => ['user_id'], 'length' => []],
            'FK_courses_course_duration_units' => ['type' => 'index', 'columns' => ['course_duration_unit_id'], 'length' => []],
            'FK_courses_deletion_reasons' => ['type' => 'index', 'columns' => ['deletion_reason_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'FK_courses_cities' => ['type' => 'foreign', 'columns' => ['city_id'], 'references' => ['cities', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'FK_courses_countries' => ['type' => 'foreign', 'columns' => ['country_id'], 'references' => ['countries', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'FK_courses_course_duration_units' => ['type' => 'foreign', 'columns' => ['course_duration_unit_id'], 'references' => ['course_duration_units', 'id'], 'update' => 'restrict', 'delete' => 'setNull', 'length' => []],
            'FK_courses_course_parent_types' => ['type' => 'foreign', 'columns' => ['course_parent_type_id'], 'references' => ['course_parent_types', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'FK_courses_course_types' => ['type' => 'foreign', 'columns' => ['course_type_id'], 'references' => ['course_types', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'FK_courses_deletion_reasons' => ['type' => 'foreign', 'columns' => ['deletion_reason_id'], 'references' => ['deletion_reasons', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'FK_courses_institutions' => ['type' => 'foreign', 'columns' => ['institution_id'], 'references' => ['institutions', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'FK_courses_languages' => ['type' => 'foreign', 'columns' => ['language_id'], 'references' => ['languages', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'FK_courses_users' => ['type' => 'foreign', 'columns' => ['user_id'], 'references' => ['users', 'id'], 'update' => 'restrict', 'delete' => 'setNull', 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_unicode_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd

    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'user_id' => 1,
                'active' => 1,
                'deleted' => 1,
                'deletion_reason_id' => 1,
                'approved' => 1,
                'approval_token' => 'Lorem ipsum dolor sit amet',
                'mod_mailed' => 1,
                'created' => '2019-05-29 11:44:50',
                'updated' => '2019-05-29 11:44:50',
                'last_reminder' => '2019-05-29 11:44:50',
                'name' => 'Lorem ipsum dolor sit amet',
                'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'country_id' => 1,
                'city_id' => 1,
                'institution_id' => 1,
                'department' => 'Lorem ipsum dolor sit amet',
                'course_parent_type_id' => 1,
                'course_type_id' => 1,
                'language_id' => 1,
                'access_requirements' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'start_date' => 'Lorem ipsum dolor sit amet',
                'duration' => 1,
                'course_duration_unit_id' => 1,
                'recurring' => 1,
                'info_url' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'guide_url' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'skip_info_url' => '2019-05-29 11:44:50',
                'skip_guide_url' => '2019-05-29 11:44:50',
                'ects' => 1,
                'contact_mail' => 'Lorem ipsum dolor sit amet',
                'contact_name' => 'Lorem ipsum dolor sit amet',
                'lon' => 1.5,
                'lat' => 1.5
            ],
        ];
        parent::init();
    }
}
