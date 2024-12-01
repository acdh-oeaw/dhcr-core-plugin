<?php

namespace DhcrCore\Model\Table;

use Cake\Core\Configure;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class CitiesTable extends Table
{
    public $query = array();

    public $allowedParameters = [
        'course_count',
        'sort_count',
        'group',
        'country_id',
        'count_recent'
    ];

    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->addBehavior('DhcrCore.CounterSort');

        $this->setTable('cities');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('DhcrCore.Countries', [
            'foreignKey' => 'country_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('DhcrCore.Courses', [
            'foreignKey' => 'city_id'
        ]);
        $this->hasMany('DhcrCore.Institutions', [
            'foreignKey' => 'city_id'
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
        $rules->add($rules->existsIn(['country_id'], 'Countries'));

        return $rules;
    }

    // entrance point for querystring evaluation
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
                case 'group':
                    if ($value == true || $value === '')
                        $this->query[$key] = true;
                    if (($key == 'sort_count' or $key == 'count_recent') and $this->query[$key])
                        $this->query['course_count'] = true;
                    break;
                case 'country_id':
                    if (ctype_digit($value)) {
                        $this->query['country_id'] = $value;
                    } else {
                        unset($this->query['country_id']);
                    }
            }
        }
        return $this->query;
    }

    public function getCity($id = null)
    {
        if (!empty($this->query['count_recent'])) {
            $this->hasMany('DhcrCore.Courses', [
                'foreignKey' => 'city_id',
                'conditions' => [
                    'Courses.active' => true,
                    'Courses.approved' => true,
                    'Courses.deleted' => false,
                    'Courses.updated >' => date('Y-m-d H:i:s', time() - Configure::read('dhcr.expirationPeriod'))
                ]
            ]);
        }
        $city = $this->get($id, [
            'contain' => ['Countries'],
            'fields' => ['id', 'name', 'country_id', 'Countries.id', 'Countries.name']
        ]);
        $city->setVirtual(['course_count']);
        return $city;
    }

    /*
	 * Due to iterative post-processing, method returns either array of entities or array of arrays!
	 */
    public function getCities()
    {
        if (!empty($this->query['count_recent'])) {
            $this->hasMany('DhcrCore.Courses', [
                'foreignKey' => 'city_id',
                'conditions' => [
                    'Courses.active' => true,
                    'Courses.approved' => true,
                    'Courses.deleted' => false,
                    'Courses.updated >' => date('Y-m-d H:i:s', time() - Configure::read('dhcr.expirationPeriod'))
                ]
            ]);
        }
        $cities = $this->find()
            ->select(['id', 'name', 'country_id', 'Countries.id', 'Countries.name'])
            ->contain(['Countries'])
            ->order(['Cities.name' => 'ASC']);
        if (!empty($this->query['country_id']))
            $cities->where(['country_id' => $this->query['country_id']]);

        // calling toArray directly does not change the object by reference - assignment required
        $cities = $cities->toArray();

        if (!empty($this->query['course_count']))
            foreach ($cities as &$city) $city->setVirtual(['course_count']);
        // sort by course_count descending, using DhcrCore.CounterSortBehavior
        if (!empty($this->query['sort_count']))
            $cities = $this->sortByCourseCount($cities);

        // mapReduce does not work on result array: $cities->mapReduce($mapper, $reducer);
        if (!empty($this->query['group'])) {
            $result = [];
            foreach ($cities as $key => $city) {
                $result[$city['country']['name']][] = $city;
            }
            $cities = $result;
            ksort($cities, SORT_STRING);
        }
        return $cities;
    }
}
