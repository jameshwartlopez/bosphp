# BOS PHP

This little framework consist 
1. application  - where the application code should resides.This folder consist config, controller, model, view.
2. public	- where the public files like css, javascript and images location
3. framework    - location of the framework files


Mysql Configuration

config/db_config.php

<?php
/*
	Database connection
 */
$config['db']['host'] = 'localhost';
$config['db']['username'] = 'root';
$config['db']['password'] = '';
/*
	Database Name
 */
$config['db']['dbname'] = 'bos';

Default Controller

config/routes.php

The default controller  is set to home by default but can also be change to any controllers file name that should be located in application/controller/filename.php.
This Controller class is the entry point of the whole framework.Note Controller class by default will call index method inside it.

How to use model and create CRUD operation? 

See the example below.
application/controller/home.php
<?php 
//Default Controller class
class HomeController extends Controller{
	
	//Default method that will be called by the framework
	public function index(){
		$data = array(
			'username'=>'user@gmail.com',
			'password'=>'password'
		);

		//This is how we use the homes model, located in application/controller/model
		
		//Example saving data
		$this->model->save($data);
		
		//Example select and passing the result into the view
		$data['users'] = $this->model->select();
		//Loading the home view and passing the $data variable to the view
		$this->load_view('home',$data);

		//Example update
		$this->->model->update($user);
		
		//Example delete
		$this->model->delete(1);
		/*
			We can also use another model in this class by using load_model and passing the file name of the model.

			Example:
			$product = $this->load_model('product');
                	$product->show_products();
		*/
    
		
	}
}

application/model/home.php
<?php
class HomeModel extends Model{
	
	private $tableName = 'tbl_user';

	public function save($user){
     		return $this->db->insert($this->tableName,$user)->execute();
  	}

	public function select(){
		// $b = $this->db->select($this->tableName)->
		// 				where('id = ',2)->
		// 				or_where('id =',1)->
		// 				or_where('id= ',3)->execute();

		// $c = $this->db->raw_query('SELECT * FROM '.$this->tableName.' where id = 1')->execute();
		// print_array($c);
		
		//$this->db->select($this->tableName)->where('id = ',1)->and_where('username= ','admin' )->and_where('password= ','admin2' )->execute();

		//USING PDO OBJECT
		// $d = $this->db->pdo->prepare('SELECT * FROM '.$this->tableName.' where id = ?');
		// $d->execute(array(1));

		return $this->db->select($this->tableName)->execute();
	}

	public function update($user,$id){
		return $this->db->update($this->tableName,$user)->where('id=',$id)->execute();
	}

	public function delete($id){
		return $this->db->delete($this->tableName)->where('id = ',$id)->execute();
	}


}

application/model/product.php
<?php 
class ProductModel extends Model{
	public function show_products(){
	  echo 'Tshirt,Polo';
	}
}

