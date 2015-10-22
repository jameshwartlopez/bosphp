<?php
// Ensure we have session
if(session_id() === ""){
    session_start();
}


define('ROOT', dirname(dirname(__FILE__)));
define('DS',DIRECTORY_SEPARATOR);

/*
	Defines the application path folder
 */
define('APP_PATH',ROOT.DS.'application'.DS);

/*
	Defines the CONTROLLER path inside the APP_PATH
 */
define('CONTROLLERS_PATH', APP_PATH.'controller'.DS);

/*
	Defines the MODEL path inside the APP_PATH
 */
define('MODELS_PATH',APP_PATH.'model'.DS);

/*
	Defines the VIEW path inside the APP_PATH
 */
define('VIEWS_PATH',APP_PATH.'view'.DS);

/*
	Defines the PUBLIC path and its subfolder
 */
define('PUBLIC_PATH', ROOT.DS.'public'.DS);
define('STYLES_PATH',PUBLIC_PATH.'css'.DS);
define('SCRIPTS_PATH',PUBLIC_PATH.'js'.DS);
define('FONTS_PATH',PUBLIC_PATH.'fonts'.DS);


define('CONFIG_PATH',APP_PATH.'config'.DS);
define('FRAMEWORK_PATH',ROOT.DS.'framework'.DS);

define('ERRORS_PATH',FRAMEWORK_PATH.'errors'.DS);

require_once(FRAMEWORK_PATH.'Functions.php');

//load database related file
$database = array(CONFIG_PATH.'db_config.php',FRAMEWORK_PATH.'DB.php');
foreach ($database as $file_path) {
	require_once($file_path);
}

//load routes file inside config folder
require_once(CONFIG_PATH.'routes.php');

//load base model
load_file(FRAMEWORK_PATH.'Model.php');

//load base controller
load_file(FRAMEWORK_PATH.'Controller.php');


//load all Models Created by the programmer
load_dir_files(MODELS_PATH);

//load all Controllers Created by the programmer
load_dir_files(CONTROLLERS_PATH);


$_route = isset($_GET['_route']) ? preg_replace('/^_route=(.*)/','$1',$_SERVER['QUERY_STRING']) : '';

load_file(FRAMEWORK_PATH.'Router.php');

new Router($_route);


