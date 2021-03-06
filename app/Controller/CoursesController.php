<?php
App::uses('AppController', 'Controller');
/**
 * Courses Controller
 *
 * @property Course $Course
 * @property PaginatorComponent $Paginator
 */
class CoursesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

/**
 * index method
 *
 * @throws AjaxImplementedException
 * @return void
 */
	public function index() {
        if($this->request->is('ajax')) {
        	$this->layout = 'ajax';

			$fields = array("Course.name", 'Course.id');
			$courses = $this->Course->find('all', array('fields' => $fields));
			$this->set(compact('courses'));
    	} else
		{
			throw new AjaxImplementedException;
		}
	}
	/**
	 * indexElement method
	 *
	 * @throws AjaxImplementedException, NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function indexElement($id=null) {
		if($this->request->is('ajax')) {
			$this->layout = 'ajax';
			if(is_null($id)) {
				throw new NotFoundException;
			}

			$fields = array("Course.name", 'Course.id');
			$conditions = array('Course.id' => $id);
			$this->set('course', $this->Course->find('first', array('conditions'=>$conditions, 'fields' => $fields)));
		} else
		{
			throw new AjaxImplementedException;
		}
	}

    /**
 * view method
 *
 * @throws AjaxImplementedException, NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if($this->request->is('ajax')) {
			$this->layout = 'ajax';
			if(!$this->Course->exists($id)) {
				throw new NotFoundException;
			}
			$this->Course->Behaviors->load('Containable');

			$contain = array(
					'Date' => array(
						'Trainer' => array (
							'Person'
						),
						'Room' => array(),
						'Account' => array(),
						'order' => array('Date.begin' => 'DESC')
					),
					'Tariff'
			);
			if($this->Auth->user('role') == 0) {
				$contain['Date']['conditions'] = array(
					'Date.begin >=' => date('Y-m-d')
				);
			}

			$conditions = array(
				'Course.'.$this->Course->primaryKey => $id,
			);
			$course = $this->Course->find('first', array('conditions'=>$conditions, 'contain'=>$contain));
			$this->set(compact('course'));
		} else
		{
			throw new AjaxImplementedException;
		}
	}

	/**
	 * plan method
	 *
	 * @throws AjaxImplementedException, NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function plan($id = null)
	{
		if($this->request->is('ajax')) {
			if($this->Auth->user('role') == 0) {
				throw new ForbiddenException;
			}
			if(!$this->Course->exists($id))
			{
				throw new NotFoundException;
			}
			$this->layout = 'ajax';
			$this->Course->Behaviors->load('Containable');

			$contain = array(
				'Date' => array(
					'Trainer' => array (
						'Person'
					),
					'Room' => array(),
					'Account' => array(),
					'order' => array('Date.begin' => 'DESC')
				),
				'Tariff'
			);

			$conditions = array(
				'Course.'.$this->Course->primaryKey => $id,
			);
			$course = $this->Course->find('first', array('conditions'=>$conditions, 'contain'=>$contain));
			$this->loadModel('Room');
			$rooms = $this->Room->find('list', array('fields' => array('Room.id', 'Room.name')));
			$this->loadModel('Account');
			$directors = $this->Account->find('all', array(
				'conditions' => array('role >' => '0'),
				'fields' => array('Account.id', 'Account.username', 'Person.name', 'Person.surname'),
				'contain' => array('Account'=>array('Person'=>array())),
				'order' => array('Person.name' => 'ASC')
			));
			$tariffs = $this->Course->Tariff->find('list');
			$this->set(compact('course', 'rooms', 'directors', 'tariffs'));
		} else {
			throw new AjaxImplementedException;
		}
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if($this->request->is('ajax')) {
			if($this->Auth->user('role') == 0) {
				throw new ForbiddenException;
			}
			$this->layout = 'ajax';
			$this->set('tariffs', $this->Course->Tariff->find('all', array(
				'fields' => array('Tariff.id', 'Tariff.amount', 'Tariff.description')
			)));
			if ($this->request->is('post', 'put')) {
				$this->autoRender = false;
				$this->layout = null;
				$this->response->type('json');
				$answer = array();

				if ($this->Course->save($this->request->data)) {
					$answer['success'] = true;
					$answer['message'] = 'Der Kurs wurde erstellt';
					$answer['courseId'] = $this->Course->id;
				} else {
					$answer['success'] = false;
					$answer['message'] = 'Der Kurs konnte nicht erstellt werden';
					$answer['errors']['Course'] = $this->Course->validationErrors;
				}
				echo json_encode($answer);
			} else
			{}
		} else {
			throw new AjaxImplementedException;
		}
	}


/**
 * edit method
 *
 * @throws AjaxImplementedException, NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if($this->request->is('ajax')) {
			if($this->Auth->user('role') == 0) {
				throw new ForbiddenException;
			}
			$this->layout = 'ajax';
			if ($this->request->is('post')) {
				if(!$this->Course->exists($id))
				{
					throw new NotFoundException;
				}
				$this->request->data['Course']['id'] = $id;
				$this->autoRender = false;
				$this->layout = null;
				$this->response->type('json');
				$answer = array();

				if ($this->Course->save($this->request->data)) {
					$answer['success'] = true;
					$answer['message'] = 'Der Kurs wurde bearbeitet';
				} else {
					$answer['success'] = false;
					$answer['message'] = 'Der Kurs konnte nicht bearbeitet werden';
					$answer['errors']['Course'] = $this->Course->validationErrors;
				}
				echo json_encode($answer);
			} else
			{
				$options = array('conditions' => array('Course.' . $this->Course->primaryKey => $id));
				$this->set('course', $this->Course->find('first', $options));
				$this->set('tariffs', $this->Course->Tariff->find('all', array(
					'fields' => array('Tariff.id', 'Tariff.amount', 'Tariff.description')
				)));
			}
		} else {
			throw new AjaxImplementedException;
		}
	}

/**
 * delete method
 *
 * @throws AjaxImplementedException, MethodNotAllowedException, NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		if($this->request->is('ajax')) {
			if($this->Auth->user('role') == 0) {
				throw new ForbiddenException;
			}
			$this->layout = 'ajax';
			if ($this->request->is('post', 'delete')) {
				if(!$this->Course->exists($id))
				{
					throw new NotFoundException;
				}
				$this->autoRender = false;
				$this->layout = null;
				$this->response->type('json');
				$answer = array();

				$course = $this->Course->findById($id);
				if(count($course['Date']) == 0) {

					if ($this->Course->delete($id)) {
						$answer['success'] = true;
						$answer['message'] = 'Der Kurs wurde gelöscht';
					} else {
						$answer['success'] = false;
						$answer['message'] = 'Der Kurs konnte nicht gelöscht werden';
					}
				} else {
					$answer['success'] = false;
					$answer['message'] = 'Der Kurs hat noch Termine. Bitte erst alle Termine löschen.';
				}
				echo json_encode($answer);
			} else
			{
				throw new MethodNotAllowedException;
			}
		} else {
			throw new AjaxImplementedException;
		}
	}
}
