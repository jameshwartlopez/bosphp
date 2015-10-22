<?php

class HomeController extends Controller{

	public function index(){
		$data = array(
			'username'=>'user@gmail.com',
			'password'=>'password'
		);
		$this->model->save($data);
		$this->load_view('home');
	}
}