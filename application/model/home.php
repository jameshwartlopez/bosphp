<?php

class HomeModel extends Model{
	private $tableName = 'tbl_user';

	public function save($data){
		$insert = $this->db->insert($this->tableName,$data)->execute();

		//$delete = $this->db->delete($this->tableName)->where('id = ',29)->execute();

		//$update = $this->db->update($this->tableName,$data)->where('id=',31)->execute();
		// print_array($this);
		// $a = $this->db->select($this->tableName)->where('id = ',1)->and_where('username= ','admin' )->and_where('password= ','admin2' )->execute();
		
		// print_array($this->db);
		// echo '<br/>Added new<br/>';
		// $b = $this->db->select($this->tableName)->
		// 				where('id = ',2)->
		// 				or_where('id =',1)->
		// 				or_where('id= ',3)->execute();
		// print_array($this->db);

		// $c = $this->db->raw_query('SELECT * FROM '.$this->tableName.' where id = 1')->execute();
		// echo '<br/>ang c';
		// print_array($c);

		// $d = $this->db->pdo->prepare('SELECT * FROM '.$this->tableName.' where id = ?');
		// $d->execute(array(1));

		// echo '<br/> ang d';
		// print_array($d->fetchAll());
	}
}