<?php

namespace DhcrCore\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class DeletionReasonsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('deletion_reasons');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('Courses', [
            'foreignKey' => 'deletion_reason_id'
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->allowEmptyString('name', false);
        return $validator;
    }

    public function getDeletionReason($id = null)
    {
        $record = $this->get($id, [
            'contain' => [],
            'fields' => ['id', 'name']
        ]);
        return $record;
    }

    public function getDeletionReasons()
    {
        $records = $this->find()
            ->select(['id', 'name'])
            ->contain([])
            ->toArray();
        return $records;
    }
}
