<?php

namespace DhcrCore\Model\Table;

use Cake\Core\Configure;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class CourseTypesTable extends Table
{
    public $query = array();

    public $allowedParameters = [
        'course_count',
        'sort_count',
        'course_parent_type_id',
        'count_recent'
    ];

    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->addBehavior('DhcrCore.CounterSort');

        $this->setTable('course_types');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('CourseParentTypes', [
            'foreignKey' => 'course_parent_type_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('Courses', [
            'foreignKey' => 'course_type_id'
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

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['course_parent_type_id'], 'CourseParentTypes'));
        return $rules;
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
        return $this->query = $query;
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
                case 'course_parent_type_id':
                    if (ctype_digit($value)) {
                        $this->query['course_parent_type_id'] = $value;
                    } else {
                        unset($this->query['course_parent_type_id']);
                    }
            }
        }
        return $this->query;
    }

    public function getCourseType($id = null)
    {
        if (!empty($this->query['count_recent'])) {
            $this->hasMany('DhcrCore.Courses', [
                'foreignKey' => 'course_type_id',
                'conditions' => [
                    'Courses.active' => true,
                    'Courses.approved' => true,
                    'Courses.deleted' => false,
                    'Courses.updated >' => date('Y-m-d H:i:s', time() - Configure::read('dhcr.expirationPeriod'))
                ]
            ]);
        }
        $record = $this->get($id, [
            'contain' => ['CourseParentTypes'],
            'fields' => ['id', 'name', 'course_parent_type_id', 'CourseParentTypes.id', 'CourseParentTypes.name']
        ]);
        $record->setVirtual(['course_count', 'full_name']);
        return $record;
    }

    /*
	 * Due to iterative post-processing, method returns either array of entities or array of arrays!
	 */
    public function getCourseTypes()
    {
        if (!empty($this->query['count_recent'])) {
            $this->hasMany('DhcrCore.Courses', [
                'foreignKey' => 'course_type_id',
                'conditions' => [
                    'Courses.active' => true,
                    'Courses.approved' => true,
                    'Courses.deleted' => false,
                    'Courses.updated >' => date('Y-m-d H:i:s', time() - Configure::read('dhcr.expirationPeriod'))
                ]
            ]);
        }
        $records = $this->find()
            ->select(['id', 'name', 'course_parent_type_id', 'CourseParentTypes.id', 'CourseParentTypes.name'])
            ->contain(['CourseParentTypes'])
            ->toArray();
        foreach ($records as &$record) {
            $record->setVirtual(['full_name']);
            if (!empty($this->query['course_count']))
                $record->setVirtual(['course_count', 'full_name']);
        }
        // sort by course_count descending, using CounterSortBehavior
        if (!empty($this->query['sort_count']))
            $records = $this->sortByCourseCount($records);
        return $records;
    }
}
