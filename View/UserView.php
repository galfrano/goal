<?php
namespace View;
class UserView extends AbstractView{

	function showSignUpForm(){
		$tpl = self::template('login');
		$tpl->get(['id'=>'submit'])->button(['class'=>'btn-success'])->say('sign_up');
		$this->html->get('body')->text($tpl);
		return $this;}

	function showLoginForm($error){
		$tpl = self::template('login');
		$error && $tpl->get(['id'=>'loginError'])->say('login_error');
		$tpl->get(['id'=>'submit'])->button(['class'=>'btn-success'])->say('login');
		$this->html->get('body')->text($tpl);
		return $this;}
}
