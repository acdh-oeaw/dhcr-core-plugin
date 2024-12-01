<?php

namespace DhcrCore\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class CourseDurationUnitsTable extends Table
{
    public function init(): void
    {
        parent::initialize($config);

        $this->setTable('course_duration_units');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('DhcrCore.Courses', [
            'foreignKey' => 'course_duration_unit_id'
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 50)
            ->requirePresence('name', 'create')
            ->allowEmptyString('name', false);

        return $validator;
    }

    public function getCourseDurationUnit($id = null)
    {
        $record = $this->get($id, [
            'contain' => [],
            'fields' => ['id', 'name']
        ]);
        return $record;
    }

    public function getCourseDurationUnits()
    {
        $records = $this->find()
            ->select(['id', 'name'])
            ->contain([])
            ->toArray();
        return $records;
    }
}
