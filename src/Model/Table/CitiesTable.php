<?php
namespace DhcrCore\Model\Table;

use Cake\Core\Configure;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Cities Model
 *
 * @property \App\Model\Table\CountriesTable|\Cake\ORM\Association\BelongsTo $Countries
 * @property \App\Model\Table\CoursesTable|\Cake\ORM\Association\HasMany $Courses
 * @property \App\Model\Table\InstitutionsTable|\Cake\ORM\Association\HasMany $Institutions
 *
 * @method \App\Model\Entity\City get($primaryKey, $options = [])
 * @method \App\Model\Entity\City newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\City[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\City|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\City saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\City patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\City[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\City findOrCreate($search, callable $callback = null, $options = [])
 */
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


	/**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) : void
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

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator) : Validator
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

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules) : RulesChecker
    {
        $rules->add($rules->existsIn(['country_id'], 'Countries'));

        return $rules;
    }


	// entrance point for querystring evaluation
	public function evaluateQuery($requestQuery = array()) {
		$this->getCleanQuery($requestQuery);
		$this->getFilter();
	}


	public function getCleanQuery($query = array()) {
		foreach($query as $key => $value) {
			if(!in_array($key, $this->allowedParameters)) {
				unset($query[$key]);
				continue;
			}
		}
		return $this->query = $query;
	}


	public function getFilter() {
		foreach($this->query as $key => $value) {
			switch($key) {
				case 'sort_count':
				case 'course_count':
                case 'count_recent':
				case 'group':
					if($value == true || $value === '')
						$this->query[$key] = true;
                    if(($key == 'sort_count' OR $key == 'count_recent') AND $this->query[$key])
						$this->query['course_count'] = true;
					break;
				case 'country_id':
					if(ctype_digit($value)) {
						$this->query['country_id'] = $value;
					}else{
						unset($this->query['country_id']);
					}
			}
		}
		return $this->query;
	}


	public function getCity($id = null) {
        if(!empty($this->query['count_recent'])) {
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
			'fields' => ['id','name','country_id','Countries.id','Countries.name']
		]);
		$city->setVirtual(['course_count']);
		return $city;
	}

	/*
	 * Due to iterative post-processing, method returns either array of entities or array of arrays!
	 */
	public function getCities() {
        if(!empty($this->query['count_recent'])) {
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
			->select(['id','name','country_id','Countries.id','Countries.name'])
			->contain(['Countries'])
			->order(['Cities.name' => 'ASC']);
		if(!empty($this->query['country_id']))
			$cities->where(['country_id' => $this->query['country_id']]);

		// calling toArray directly does not change the object by reference - assignment required
		$cities = $cities->toArray();

		if(!empty($this->query['course_count']))
            foreach($cities as &$city) $city->setVirtual(['course_count']);
        // sort by course_count descending, using DhcrCore.CounterSortBehavior
        if(!empty($this->query['sort_count']))
            $cities = $this->sortByCourseCount($cities);

		// mapReduce does not work on result array: $cities->mapReduce($mapper, $reducer);
		if(!empty($this->query['group'])) {
			$result = [];
			foreach($cities as $key => $city) {
				$result[$city['country']['name']][] = $city;
			}
			$cities = $result;
			ksort($cities, SORT_STRING);
		}

		return $cities;
	}



}
