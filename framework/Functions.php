<?php

/*
	This will be the general functions file available in the framework
*/


/*
	Load specific files and require it if it does exist
*/
if(! function_exists('load_file')){

	function load_file($file_path){
		
		$file_info = pathinfo($file_path);
		
		if(file_exists($file_path)){
			require_once($file_path);
		}else{
			$title = '404 File not found!';
			$error_msg = 'The file <strong>'.$file_info['basename'].'</strong> could not be found in <strong>'.$file_info['dirname'].'</strong>';
			show_error($title,$error_msg);	
		}

	}

}

/*
	Show GLOBAL error file
 */

if(! function_exists('show_error')){

	function show_error($title,$error_msg){
		require_once(ERRORS_PATH.'error.php');
		exit();
	}
}



/*
	Load all files in a certain dir this uses load_file 
*/

if(! function_exists('load_dir_files')){

	function load_dir_files($dir_path){
		$files = scandir($dir_path);
		$dir_path = preg_match("/\/\z/",$dir_path)?$dir_path:$dir_path.DS;
		
		foreach ($files as $file_name) {
		
			if(is_file($dir_path.$file_name)){
				load_file($dir_path.$file_name);
			}
		}
		
	}

}
/*
	Home url
 */
if(! function_exists('home_url')){

	function home_url(){
		if($_SERVER['SERVER_NAME'] == 'localhost'){
		
			$homeFolder = pathinfo(dirname(dirname(__FILE__)));
			return sprintf(
			    "%s://%s/%s",
			    isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
			    $_SERVER['SERVER_NAME'],
			    $homeFolder['filename']
			  );		
		}else{
			return sprintf(
		    "%s://%s",
		    isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
		    $_SERVER['SERVER_NAME']
		  );

		}	
	}
	
}

/*
	Redirect function
*/

if(!function_exists('redirect_to')){

	function redirect_to($url){
		ob_start();
		header('Location: '.$url);
		exit;
	}

}

/*
	Programmers Usages only
*/

if(!function_exists('print_array')){
	function print_array($data){
		echo '<pre>';
		print_r($data);
		echo '</pre>';
	}
}
