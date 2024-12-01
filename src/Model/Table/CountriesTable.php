<?php

namespace DhcrCore\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Core\Configure;

class CountriesTable extends Table
{
    public $query = array();

    public $allowedParameters = [
        'course_count',
        'sort_count',
        'count_recent'
    ];

    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->addBehavior('DhcrCore.CounterSort');

        $this->setTable('countries');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('DhcrCore.Cities', [
            'foreignKey' => 'country_id'
        ]);
        $this->hasMany('DhcrCore.Courses', [
            'foreignKey' => 'country_id'
        ]);
        $this->hasMany('DhcrCore.Institutions', [
            'foreignKey' => 'country_id'
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
            ->notEmptyString('name')
            ->add('name', 'unique', [
                'rule' => 'validateUnique',
                'provider' => 'table',
                'message' => 'The country already exists.'
            ]);

        $validator
            ->scalar('domain_name')
            ->maxLength('domain_name', 255)
            ->allowEmptyString('domain_name');

        $validator
            ->scalar('stop_words')
            ->maxLength('stop_words', 255)
            ->allowEmptyString('stop_words');

        return $validator;
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
                    if ($value == true || $value === '')
                        $this->query[$key] = true;
                    if (($key == 'sort_count' or $key == 'count_recent') and $this->query[$key])
                        $this->query['course_count'] = true;
            }
        }
        return $this->query;
    }

    public function getCountry($id = null)
    {
        $country = $this->get($id, [
            'contain' => [],
            'fields' => ['id', 'name']
        ]);
        if (!empty($this->query['count_recent'])) {
            // override with more detailed conditions
            $this->hasMany('DhcrCore.Courses', [
                'foreignKey' => 'country_id',
                'conditions' => [
                    'Courses.active' => true,
                    'Courses.approved' => true,
                    'Courses.deleted' => false,
                    'Courses.updated >' => date('Y-m-d H:i:s', time() - Configure::read('dhcr.expirationPeriod'))
                ]
            ]);
        }
        $country->setVirtual(['course_count']);
        return $country;
    }

    /*
	 * Due to iterative post-processing, method returns either array of entities or array of arrays!
	 */
    public function getCountries()
    {
        if (!empty($this->query['count_recent'])) {
            $this->hasMany('DhcrCore.Courses', [
                'foreignKey' => 'country_id',
                'conditions' => [
                    'Courses.active' => true,
                    'Courses.approved' => true,
                    'Courses.deleted' => false,
                    'Courses.updated >' => date('Y-m-d H:i:s', time() - Configure::read('dhcr.expirationPeriod'))
                ]
            ]);
        }
        $countries = $this->find()
            ->select(['id', 'name'])
            ->contain([])
            ->order(['Countries.name' => 'ASC'])
            ->toArray();

        if (!empty($this->query['course_count']))
            foreach ($countries as &$country) $country->setVirtual(['course_count']);
        // sort by course_count descending, using CounterSortBehavior
        if (!empty($this->query['sort_count']))
            $countries = $this->sortByCourseCount($countries);
        return $countries;
    }
}
