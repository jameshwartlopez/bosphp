<?php

class Controller{
	 protected $model;
	 private   $controller,
               $action;
	
	function __construct($model="Model",$controller="Controller",$action='index'){
		$this->model = new $model;
		$this->controller = $controller;
		$this->action = $action;
	}

	public function load_template($template_name,$data= array(),$viewname=''){
		$template_header = VIEWS_PATH.$template_name.'_template'.DS.'header.php';
		$template_footer = VIEWS_PATH.$template_name.'_template'.DS.'footer.php';

		if(!empty($viewname)){
			
			if($template_name != $viewname){
				$template_view = VIEWS_PATH.$viewname.'.php';	
			}else{
				$template_view = VIEWS_PATH.$template_name.'.php';	
			}

		}else if(empty($viewname)){
			$template_view = VIEWS_PATH.$template_name.'.php';	
		}


		if(!file_exists($template_header)){
			throw new Exception("header.php Could not be found in ".$template_header, 1);
		}else if(!file_exists($template_footer)){
			throw new Exception("footer.php Could not be found in ".$template_footer, 1);
		}else if(!file_exists($template_view)){
			throw new Exception("The main content view ".$viewname.".php for the template is missing in".$template_view, 1);
		}else{
			extract($data);
			require_once($template_header);
			require_once($template_view);
			require_once($template_footer);
		}
	}

	public function load_view($viewname,$data = array()){
		$view = VIEWS_PATH.$viewname.'.php';
		if(file_exists($view)){
			extract($data);
			require_once($view);
		}else {
			$title='404 file not found';
			$error_msg = 'View file '.$viewname.'.php Could not be found in '.$view;
			show_error($title,$error_msg);
		}
	}

	protected function load_model($model_name){
		$model_location = MODELS_PATH.$model_name.".php";
		if(file_exists($model_location)){
			
			$model = $model_name."Model";
			return new $model();

		}else {
			$title='404 file not found';
			$error_msg = 'Model file '.$model_location.'Could not be found in '.$model_location;
			show_error($title,$error_msg);
		}
		
	}

	public function show_404(){
		
		require_once(ERRORS_PATH.'404.php');
	}

}