<?php
App::import('Model', 'Users.User');
class AppUser extends User {
    public $useTable = 'users';
	public $alias = 'User';
	public function beforeSave() {
		return true;
	}
/**
 * Validates the user token
 * (overidden to include required username field for sluggable behavior)
 *
 * @param string $token Token
 * @param boolean $reset Reset boolean
 * @param boolean $now time() value
 * @return mixed false or user data
 */
	public function validateToken($token = null, $reset = false, $now = null) {
		if (!$now) {
			$now = time();
		}

		$this->recursive = -1;
		$data = false;
		$match = $this->find('first', array(
			'conditions' => array($this->alias . '.email_token' => $token),
			'fields' => array('id', 'username', 'email', 'email_token_expiry', 'role')
		));

		if (!empty($match)) {
			$expires = strtotime($match[$this->alias]['email_token_expiry']);
			if ($expires > $now) {
				$data[$this->alias]['id'] = $match[$this->alias]['id'];
				$data[$this->alias]['username'] = $match[$this->alias]['username'];
				$data[$this->alias]['email'] = $match[$this->alias]['email'];
				$data[$this->alias]['email_verified'] = '1';
				$data[$this->alias]['role'] = $match[$this->alias]['role'];

				if ($reset === true) {
					$data[$this->alias]['password'] = $this->generatePassword();
					$data[$this->alias]['password_token'] = null;
				}

				$data[$this->alias]['email_token'] = null;
				$data[$this->alias]['email_token_expiry'] = null;
			}
		}
		return $data;
	}

/**
 * Updates the last activity field of a user
 *
 * @param string $user User ID
 * @return boolean True on success
 */
	public function updateLastActivity($userId = null) {
		if (!empty($userId)) {
			$this->id = $userId;
		}
		if ($this->exists()) {
			return $this->saveField('last_action', date('Y-m-d H:i:s', time()));
		}
		return false;
	}
/* */
}