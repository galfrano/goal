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
	function isAvailable($section){
		parent::isAvailable($section);
		$this->entity = new Entity($this->table = $section);
		$section === 'users' && empty(Path::getParam('action')) && $this->entity->setColumns('email, role, language, id');
	}
	protected function post($post){
		$post['passwd'] = self::validatePassword($post['passwd']);
		parent::post($post);
	}
	protected function validatePassword($passwd){
		(!empty($passwd[0]) && !empty($passwd[1]) && $passwd[0] === $passwd[1] && strlen($passwd[0]) >= 6) || self::kill('Password too short or not matching');
		return User::encrypt($passwd[0]);
	}
}
