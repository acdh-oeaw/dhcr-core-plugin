<?php

namespace DhcrCore\Model\Table;

use Cake\Core\Configure;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class LanguagesTable extends Table
{
    public $allowedParameters = [
        'course_count',
        'sort_count',
        'count_recent'
    ];
    public $query = array();

    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->addBehavior('DhcrCore.CounterSort');

        $this->setTable('languages');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('Courses', [
            'foreignKey' => 'language_id'
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 100)
            ->requirePresence('name', 'create')
            ->allowEmptyString('name', false);
        return $validator;
    }

    // entry point for querystring evaluation
    public function evaluateQuery($requestQuery = array())
    {
        $this->getCleanQuery($requestQuery);
        $this->getFilter();
    }

    public function getCleanQuery($query = array())
    {
        foreach ($query as $key => $value) {
            if (!in_array($key, $this->allowedParameters)) {
                unset($query[$key]);
                continue;
            }
        }
        $this->query = $query;
        return $this->query;
    }

    public function getFilter()
    {
        foreach ($this->query as $key => $value) {
            switch ($key) {
                case 'sort_count':
                case 'course_count':
                case 'count_recent':
                    if ($value == true || $value === '')
                        $this->query[$key] = true;
                    if (($key == 'sort_count' or $key == 'count_recent') and $this->query[$key])
                        $this->query['course_count'] = true;
                    break;
            }
        }
        return $this->query;
    }

    public function getLanguage($id = null)
    {
        if (!empty($this->query['count_recent'])) {
            $this->hasMany('DhcrCore.Courses', [
                'foreignKey' => 'language_id',
                'conditions' => [
                    'Courses.active' => true,
                    'Courses.approved' => true,
                    'Courses.deleted' => false,
                    'Courses.updated >' => date('Y-m-d H:i:s', time() - Configure::read('dhcr.expirationPeriod'))
                ]
            ]);
        }
        $record = $this->get($id, [
            'contain' => [],
            'fields' => ['id', 'name']
        ]);
        $record->setVirtual(['course_count']);
        return $record;
    }

    /*
     * Due to iterative post-processing, method returns either array of entities or array of arrays!
     */
    public function getLanguages()
    {
        if (!empty($this->query['count_recent'])) {
            $this->hasMany('DhcrCore.Courses', [
                'foreignKey' => 'language_id',
                'conditions' => [
                    'Courses.active' => true,
                    'Courses.approved' => true,
                    'Courses.deleted' => false,
                    'Courses.updated >' => date('Y-m-d H:i:s', time() - Configure::read('dhcr.expirationPeriod'))
                ]
            ]);
        }
        $records = $this->find()
            ->select(['id', 'name'])
            ->contain([])
            ->order(['Languages.name' => 'ASC'])
            ->toArray();

        if (!empty($this->query['course_count']))
            foreach ($records as &$record) $record->setVirtual(['course_count']);
        // sort by course_count descending, using CounterSortBehavior
        if (!empty($this->query['sort_count']))
            $records = $this->sortByCourseCount($records);
        return $records;
    }
}
