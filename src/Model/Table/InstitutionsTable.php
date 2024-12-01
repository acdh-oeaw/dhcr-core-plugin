<?php

namespace DhcrCore\Model\Table;

use Cake\Core\Configure;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class InstitutionsTable extends Table
{
    public $query = array();

    public $allowedParameters = [
        'course_count',
        'sort_count',
        'count_recent',
        'group',
        'country_id',
        'city_id'
    ];

    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->addBehavior('DhcrCore.CounterSort');

        $this->setTable('institutions');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Cities', [
            'foreignKey' => 'city_id'
        ]);
        $this->belongsTo('Countries', [
            'foreignKey' => 'country_id'
        ]);
        $this->hasMany('Courses', [
            'foreignKey' => 'institution_id'
        ]);
        $this->hasMany('Users', [
            'foreignKey' => 'institution_id'
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

        $validator
            ->scalar('description')
            ->allowEmptyString('description');

        $validator
            ->scalar('url')
            ->maxLength('url', 255)
            ->allowEmptyString('url');

        $validator
            ->decimal('lon')
            ->allowEmptyString('lon');

        $validator
            ->decimal('lat')
            ->allowEmptyString('lat');

        return $validator;
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['institution_id'], 'Institutions'));
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
                    break;
                case 'city_id':
                    if (ctype_digit($value)) {
                        $this->query['city_id'] = $value;
                        unset($this->query['country_id']);
                    } else {
                        unset($this->query['city_id']);
                    }
            }
        }
        return $this->query;
    }

    public function getInstitution($id = null)
    {
        if (!empty($this->query['count_recent'])) {
            $this->hasMany('DhcrCore.Courses', [
                'foreignKey' => 'institution_id',
                'conditions' => [
                    'Courses.active' => true,
                    'Courses.approved' => true,
                    'Courses.deleted' => false,
                    'Courses.updated >' => date('Y-m-d H:i:s', time() - Configure::read('dhcr.expirationPeriod'))
                ]
            ]);
        }
        $institution = $this->get($id, [
            'contain' => ['Countries', 'Cities'],
            'fields' => ['id', 'name', 'country_id', 'city_id', 'Countries.id', 'Countries.name', 'Cities.id', 'Cities.name']
        ]);
        $institution->setVirtual(['course_count']);
        return $institution;
    }

    /*
	 * Due to iterative post-processing, method returns either array of entities or array of arrays!
	 */
    public function getInstitutions()
    {
        if (!empty($this->query['count_recent'])) {
            $this->hasMany('DhcrCore.Courses', [
                'foreignKey' => 'institution_id',
                'conditions' => [
                    'Courses.active' => true,
                    'Courses.approved' => true,
                    'Courses.deleted' => false,
                    'Courses.updated >' => date('Y-m-d H:i:s', time() - Configure::read('dhcr.expirationPeriod'))
                ]
            ]);
        }
        $institutions = $this->find()
            ->select(['id', 'name', 'city_id', 'country_id', 'Cities.id', 'Cities.name'])
            ->contain(['Countries', 'Cities'])
            ->order(['Institutions.name' => 'ASC']);
        if (!empty($this->query['country_id']) and empty($this->query['city_id']))
            $institutions->where(['Institutions.country_id' => $this->query['country_id']]);
        if (!empty($this->query['city_id']) and empty($this->query['country_id']))
            $institutions->where(['Institutions.city_id' => $this->query['city_id']]);

        // calling toArray directly does not change the object by reference - assignment required
        $institutions = $institutions->toArray();

        if (!empty($this->query['course_count']))
            foreach ($institutions as &$institution) $institution->setVirtual(['course_count']);
        // sort by course_count descending, using CounterSortBehavior
        if (!empty($this->query['sort_count']))
            $institutions = $this->sortByCourseCount($institutions);

        // mapReduce does not work on result array: $institution->mapReduce($mapper, $reducer);
        if (!empty($this->query['group'])) {
            $result = [];
            foreach ($institutions as $key => $institution) {
                $result[$institution['country']['name']][] = $institution;
            }
            $institutions = $result;
            ksort($institutions, SORT_STRING);
        }
        return $institutions;
    }
}
