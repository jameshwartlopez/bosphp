<?php
class Router {

    protected $controller,
              $_action,
              $_params,
              $default_controller,
              $_route;

    public function __construct($_route){
        global $config;
        $this->default_controller = $config['routes']['default_controller'];
        $this->_route = $_route;
        $this->controller = 'Controller';
        $this->_action = 'index';
        $this->_params = array(); 
        $this->dispatch();
    }
	
	private function parseRoute(){
		$id = false;
        // parse path info
        if (isset($this->_route)){
            // the request path
            $path = $this->_route;
         

            // the rules to route
            $cai =  '/^([\w]+)\/([\w]+)\/([\d]+).*$/';  //  controller/action/id
            $ci =   '/^([\w]+)\/([\d]+).*$/';           //  controller/id
            $ca =   '/^([\w]+)\/([\w]+).*$/';           //  controller/action
            $c =    '/^([\w]+).*$/';                    //  action
            
            // initialize the matches
            $matches = array();

            // if this is home page route
            if (empty($path)){

                if(isset($this->default_controller)){
                    $this->controller  = $this->default_controller;
                }else{
                    $this->controller = 'index';
                    $this->_action = 'index'; 
                }
               
            } else if (preg_match($cai, $path, $matches)){
                $this->controller = ucfirst($matches[1]);//make sure that the first character of the controller is capitalize
                $this->_action = $matches[2];
                $id = $matches[3];
               
            } else if (preg_match($ci, $path, $matches)){
                $this->controller = ucfirst($matches[1]);
                $id = $matches[2];

            } else if (preg_match($ca, $path, $matches)){
                $this->controller = ucfirst($matches[1]);
                $this->_action = $matches[2];

            } else if (preg_match($c, $path, $matches)){
            	$this->controller = ucfirst($matches[1]);
                $this->_action = 'index';	
            }
            
            // get query string from url        
            $query = array();
            $parse = parse_url($path);
            // if we have query string
            if(!empty($parse['query'])){
                // parse query string
                parse_str($parse['query'], $query);
                // if query paramater is parsed
                if(!empty($query)){
                    // merge the query paramaters to $_GET variables
                    $_GET = array_merge($_GET, $query);

                    // merge the query paramaters to $_REQUEST variables
                    $_REQUEST = array_merge($_REQUEST, $query);
                }
            }
        }
        
        // gets the request method
        $method = $_SERVER["REQUEST_METHOD"];   
     
        // assign params by methods  
        switch($method){
            case "GET": // view
                // we need to remove _route in the $_GET params
                unset($_GET['_route']);
                // merege the params
                $this->_params = array_merge($this->_params, $_GET);               
            break;
            case "POST": // create
            case "PUT":  // update
            case "DELETE": // delete
            {
                // ignore the file upload
                if(!array_key_exists('HTTP_X_FILE_NAME',$_SERVER))
                {
                    if($method == "POST"){
                        $this->_params = array_merge($this->_params, $_POST); 
                    }else{           
                        // temp params 
                        $p = array();
                        // the request payload
                        $content = file_get_contents("php://input");
                        // parse the content string to check we have [data] field or not
                        parse_str($content, $p);
                        // if we have data field
                        $p = json_decode($content, true);
                        // merge the data to existing params
                        $this->_params = array_merge($this->_params, $p);
                    }   
                }          
            }
            break;                
        }
		// set param id to the id we have
		if(!empty($id)){		 
		 $this->_params['id']=$id;
		}

        if($this->controller == 'index'){
            $this->_params = array($this->_params);
        }  					
	}
    
    public function dispatch() {
    	// call to parse routes
   		$this->parseRoute();
        
        // set model name
        $model = $this->controller.'Model';
        
        // if we have extended model
        $model = class_exists($model) ? $model : 'Model';
        
        // assign controller full name
        $this->controller .= 'Controller';

        // if we have extended controller
        $this->controller = class_exists($this->controller) ? $this->controller : 'Controller';

        // construct the controller class
        $dispatch = new $this->controller($model, $this->controller, $this->_action);

        // if we have action function in controller
        $hasActionFunction = method_exists($this->controller, $this->_action);
      

        // we need to reference the parameters to a correct order in order to match the arguments order 
        // of the calling function
        $c = new ReflectionClass($this->controller);
		$m = $hasActionFunction ? $this->_action : 'show_404';
        $f = $c->getMethod($m);
        $p = $f->getParameters();   

        
        $params_new = array();
        $params_old = $this->_params;
        
     	
        for($i = 0; $i < count($p); $i++){
            $key = $p[$i]->getName();
            if(array_key_exists($key,$params_old)){
                $params_new[$i] = $params_old[$key];
                unset($params_old[$key]);
            }
        }
        
        // after reorder, merge the leftovers
        $params_new = array_merge($params_new, $params_old);
        
        call_user_func_array(array($dispatch, $m), $params_new);		
       
    }
}