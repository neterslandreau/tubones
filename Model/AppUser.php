<?php
App::import('Model', 'Users.User');
class AppUser extends User {
    public $useTable = 'users';
	public $alias = 'User';
	
}