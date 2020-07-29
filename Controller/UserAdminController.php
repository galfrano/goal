<?php

namespace Controller;

use \Service\User;
use \Model\Entity;
use \Service\Path;


//TODO: FIXME: Security!!! add filter to remaining post-get

class UserAdminController extends CrudController{

	use \Utility\Killable;

	static $sections = ['users', 'groups'];
	protected static $children = ['users'=>['user_groups']];

/*	private $userKey = 'user', $pk = 'id', $session;
*/
	function preRoute(){
		Path::getParam('section') === 'users' && empty(Path::getParam('action')) && $this->entity->setColumns('email, role, language, id');
	}
	static function getSubMenu(){
		return self::$sections;
	}
	protected function post($post){
		if(!empty($post['passwd'])){
			if(empty($post['passwd'][0]) && empty($post['passwd'][1])){
				unset($post['passwd']);
			}
			else{
				$post['passwd'] = self::validatePassword($post['passwd']);
			}
//			var_dump($post); die;
		}
		parent::post($post);
	}
	protected function validatePassword($passwd){
		($passwd[0] === $passwd[1] && strlen($passwd[0]) >= 6) || self::kill('Password too short or not matching');
		return User::encrypt($passwd[0]);
	}
}
