<?php
App::uses('UsersController', 'Users.Controller');

class AppUsersController extends UsersController {
	public $viewPath = 'AppUsers';
	
	public function __construct($request = null, $response = null) {
		parent::__construct($request, $response);
		$this->User = ClassRegistry::init('AppUser');
	}
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('register', 'index');
	}

	public function render($action = null, $layout = null) {
		if (is_null($action)) {
			$action = $this->action;
		}
		$view = ROOT . DS . APP_DIR . DS . 'View' . DS . $this->viewPath . DS . $action . '.ctp';
		if (!file_exists(ROOT . DS . APP_DIR . DS . 'View' . DS . $this->viewPath . DS . $action . '.ctp')) {
			$view = App::pluginPath('Users') . 'View' . DS . 'Users' . DS . $action . '.ctp';
		}

		return parent::render($view, $layout);
	}
/**
 * User register action
 *
 * @return void
 */
	public function register() {
		if ($this->Auth->user()) {
			$this->Session->setFlash(__d('users', 'You are already registered and logged in!', true));
			$this->redirect('/');
		}

		if (!empty($this->data)) {
			$user = $this->User->register($this->data);
			if ($user !== false) {
				$this->set('user', $user);
				$this->_sendVerificationEmail($user[$this->modelClass]['email']);
				$this->Session->setFlash(__d('users', 'Your account has been created. You should receive an e-mail shortly to authenticate your account. Once validated you will be able to login.', true));
				$this->redirect(array('action'=> 'login'));
			} else {
				unset($this->data[$this->modelClass]['passwd']);
				unset($this->data[$this->modelClass]['temppassword']);
				$this->Session->setFlash(__d('users', 'Your account could not be created. Please, try again.', true), 'default', array('class' => 'message warning'));
			}
		}

		$this->_setLanguages();
	}

/**
 * Common login action
 *
 * @return void
 */
	public function login() {
		$this->request->is('post') && $this->Auth->login();

		if ($this->Auth->user()) {
			$this->User->id = $this->Auth->user('id');
			$this->User->saveField('last_login', date('Y-m-d H:i:s'));

			if ($this->here == $this->Auth->loginRedirect) {
				$this->Auth->loginRedirect = '/';
			}
			$this->Session->setFlash(sprintf('%s you have successfully logged in', $this->Auth->user('username')));
			if (!empty($this->data)) {
				$data = $this->data[$this->modelClass];
				$this->_setCookie();
			}

			if (empty($data['return_to'])) {
				$data['return_to'] = null;
			}
			$this->redirect($this->Auth->redirect($data['return_to']));
		}

		if (isset($this->params['named']['return_to'])) {
			$this->set('return_to', urldecode($this->params['named']['return_to']));
		} else {
			$this->set('return_to', false);
		}
	}


/**
 * Sends the verification email
 *
 * This method is protected and not private so that classes that inherit this
 * controller can override this method to change the varification mail sending
 * in any possible way.
 *
 * @param string $to Receiver email address
 * @param array $options EmailComponent options
 * @return boolean Success
 */
	protected function _sendVerificationEmail($to = null, $options = array()) {
		$defaults = array(
			'from' => 'noreply@' . env('HTTP_HOST'),
			'subject' => __d('users', 'Account verification'),
			'template' => 'Users.account_verification');
		$options['from'] = 'noreply@tubones.com';

		$options = array_merge($defaults, $options);

		$email = new CakeEmail();
		$email->to = $to;
		$email->from($options['from']);
		$email->subject($options['subject']);
		$email->template($options['template']);

		return $email->deliver($to, $options['subject'], $options['template'], array('from' => $options['from']));
	}

/**
 * Shows a users profile
 *
 * @param string $slug User Slug
 * @return void
 */
	public function view($slug = null) {
		try {
			$this->set('user', $this->User->view($slug));
		} catch (Exception $e) {
			$this->Session->setFlash($e->getMessage());
			$this->redirect('/');
		}
	}


}