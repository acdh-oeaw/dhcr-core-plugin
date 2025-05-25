<?php

declare(strict_types=1);

namespace DhcrCore\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class ExternalResourcesTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('external_resources');
        $this->setDisplayField('label');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('DhcrCore.Courses', [
            'foreignKey' => 'course_id',
            'joinType' => 'INNER',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('course_id')
            ->notEmptyString('course_id');

        $validator
            ->scalar('label')
            ->maxLength('label', 255)
            ->allowEmptyString('label');

        $validator
            ->scalar('url')
            ->maxLength('url', 255)
            ->requirePresence('url', 'create')
            ->notEmptyString('url')
            ->add('url', 'valid-url', ['rule' => 'url', 'message' => 'Provide a valid URL'])
            ->add('url', 'startsWithHttp', [
                'rule' => 'startsWithHttp',
                'message' => 'Start with https:// or http://',
                'provider' => 'table',
            ]);;

        $validator
            ->scalar('type')
            ->maxLength('type', 100)
            ->allowEmptyString('type');

        $validator
            ->scalar('affiliation')
            ->maxLength('affiliation', 100)
            ->allowEmptyString('affiliation');

        return $validator;
    }

    public function startsWithHttp($url): bool
    {
        if (str_starts_with($url, 'https://') || str_starts_with($url, 'http://')) {
            return true;
        }
        return false;
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn('course_id', 'Courses'), ['errorField' => 'course_id']);

        return $rules;
    }
}
