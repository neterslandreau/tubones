<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       Cake.Console.Templates.skel.Controller
 */
class AppController extends Controller {
/**
 * Components
 *
 * @var array
 */
	public $components = array(
		'Session',
		'Cookie',
		'Email',
		'Auth',
	);
/**
 * Helpers
 *
 * @var array
 */
	public $helpers = array(
		'Session',
		'Html',
		'Js',
		'Form',
	);
	public $publicControllers = array('pages');

/**
 * Constructor
 *
 * @param mixed $request
 * @param mixed $response 
 */
	public function __construct($request = null, $response = null) {
		parent::__construct($request, $response);
		if (Configure::read('debug')) {
			$this->components[] = 'DebugKit.Toolbar';
		}
	}
/**
 * beforeFilter
 * 
 */
	public function beforeFilter() {
		$this->Auth->authenticate = array(
			'Form' => array(
				'userModel' => 'User',
				'fields' => array(
					'username' => 'email',
				)
			)
		);
		if (in_array(strtolower($this->params['controller']), $this->publicControllers)) {
            $this->Auth->allow('*');
        }
	}
/**
 * isAuthorized
 *
 * @return boolean
 */
	public function isAuthorized() {
		if ($this->Auth->user() && $this->params['prefix'] != 'admin') {
			return true;
		}
		if ($this->params['prefix'] == 'admin' && $this->Auth->user('is_admin')) {
			return true;
		}
		return false;
	}
}
