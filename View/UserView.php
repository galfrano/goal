<?php
namespace View;
class UserView extends AbstractView{

	function showSignUpForm(){
		$tpl = self::template('login');
		$tpl->get(['id'=>'submit'])->button(['class'=>'btn-success'])->say('sign_up');

		$this->html->get('body')->text($tpl);
		return $this;}

	function showLoginForm(){
		$tpl = self::template('login');
		$tpl->get(['id'=>'submit'])->button(['class'=>'btn-success'])->say('login');
//var_dump($this->html);die;
		$this->html->get('body')->text($tpl);
		return $this;}
}
