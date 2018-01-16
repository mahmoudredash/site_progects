<?php
/**
 * This class Contunue for  CRUD any Database 
 *@copyright  2018 Mahmoud Reda Sahaban
 *@version    1.0
 *@author   Mahmoud Reda Sahaban
 */
class ConnectDB{

    private $userDB;
    private $passDB;
    private $host;
    private $namedb;
    private $nameMangDB;
	private $port;
	public  $db;



   /**
	* Constractor Classs using  add info data base and set opject $db from Databas 
	* @param  $userDB,
	* @param  $passDB,
	* @param  $host,
	* @param  $namedb,
	* @param  $nameMangDB,
	* @param  $port="3306"    defualt mysql using port 3306 is using DB other set port
	* @see  add to $db Object from DataBase type PDO Class
    */
    function __construct($userDB,$passDB,$host,$namedb,$nameMangDB,$port="3306")
    {
    //set data in constractor	
    	$this->userDB = $userDB;
    	$this->passDB = $passDB;
    	$this->host = $host;
    	$this->namedb = $namedb;
    	$this->nameMangDB = $nameMangDB;
    	$this->port = $port;
		$this->db=getObj_db();
	}
	
	/**
	 * this function get Object from DB type PDO Class
	 * @return db Object  from DB type PDO Class
	 */
    public function getObj_db()
    { 
    	try{ //create Object from Database 
    		$db = new PDO("$nameMangDB:host=$host;dbname=$namedb;port=$port",$userDB,$passDB); 
    		$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    		$db->exec("SET NAMES 'utf8';");
    		return $db;
    	}catch(PDOException $e ){
    		echo "ERROR ON CONNECT DB :) ! ".$e.getMassage()."<br/>";
			exit;
			return null;
    	}
    }
	/**
	 * This function get  data from tables 
	 * @param $db This Object from DataBase type PDO Class 
	 * @param $table_name table name 
	 * @param $columns ==array("id,name,age...") OR not using ==array("*")
	 * @param $where  defalt "" if empty return all data from table  OR !empty return select data
	 * @return data type array()
	 */
    public function getdata_DB_array($db,$table_name,$where="",$columns=array("*"))
    {	$columns=get_Query("",$columns,"select"); //sicurty from hakers ues result all data
	    try{ //cheack  for where 
		    	if(!empty($where)){
		    		$data=$db->query("SELECT $columns[0] FROM $table_name WHERE $where;");
		    		return $data->fetch(PDO::FETCH_ASSOC);
		    	}
		    	if(empty($where)){
		    		$data=$db->query("SELECT $columns[0] FROM $table_name;");
		    		return $data->fetchAll(PDO::FETCH_ASSOC);
		    	}
    		}catch (PDOException $e) {
    			print "Error In Select Data From Table: ! " . $e->getMessage() . "<br/>"; 
    			exit();
    			return null;
			 }
    }
	/**
	 * This function get  data from tables 
	 * @param $db This Object from DataBase type PDO Class 
	 * @param $table_name table name 
	 * @param $where  defalt "" if empty return all data from table  OR !empty return select data
	 * @param $columns ==array("id,name,age...") OR not using ==array("*")
	 * @return data type object
	 */
    public function getdata_DB_objct($db,$table_name,$where="",$columns=array("*"))
    { $columns=get_Query("",$columns,"select"); //sicurty from hakers ues result all data
    	  try{ //cheack  for where 
		    	if(!empty($where)){
		    		$data=$db->query("SELECT $columns[0] FROM $table_name WHERE $where;");
		    		return $data->fetch(PDO::FETCH_OBJ);
		    	}
		    	if(empty($where)){
		    		$data=$db->query("SELECT $columns[0] FROM $table_name;");
		    		return $data->fetchAll(PDO::FETCH_OBJ);
		    	}
    		}catch (PDOException $e) {
    			print "Error In Select Data From Table: ! " . $e->getMessage() . "<br/>"; 
    			exit();
    			return null;
			 }
	}
	

	/**
	 * This function  Delete Data from tables 
	 * @param $db This Object from DataBase type PDO Class 
	 * @param $table_name table name 
	 * @param $where  defalt "" if empty delet all data in table OR !empty delet select data
	 * @return   true  OR   false
	 */
	public function deletData_fromTabel($db,$name_tab,$where="")
	{
		try{
		    	if(!empty($where)){
		    		$data=$db->query("DELETE * FROM $table_name WHERE $where;");
		    		return $data;
		    	}
		    	if(empty($where)){
		    		$data=$db->query("DELETE * FROM $table_name;");
		    		return $data;
		    	}
    		}catch (PDOException $e) {
    			print "Error In DELETE Data From Table: ! " . $e->getMessage() . "<br/>"; 
    			exit();
    			return false;
			 }
	}
	/**
	 * This function Insert  data from tables 
	 * @param $db This Object from DataBase type PDO Class 
	 * @param $table_name table name 
	 * @param $arr_data ==array("column_name"=>"value","column_name"=>"value")
	 * @return  true OR False
	 */
	public function insertData_fromDB($db,$name_tab,$arr_data){
		try{
			$cont=1;
			$datalest=get_Query($name_tab,$arr_data,"insert");
			$result=$db->prepare("INSERT INTO $datalest[0]");//
			foreach($arr_data as $kay=>$value){//
				$result->bindValue($cont,$value);
				$cont++;
			}
			$result->execute();
			return true;
		}catch (PDOException $e) {
			print "Error In Insert Data From Table: ! " . $e->getMessage() . "<br/>"; 
			exit();
			return false;
		 }
	}
	/**
	 * This function UPDATE  data from tables 
	 * @param $db This Object from DataBase type PDO Class 
	 * @param $table_name table name 
	 * @param $arr_data ==array("column_name"=>"value","column_name"=>"value")
	 * @param $where  condection  exm : id = 1 more..
	 * @return  true OR False
	 */
	public function updateData_fromDB($db,$name_tab,$arr_data,$where){
		try{
			$cont=1;
			$datalest=get_Query($name_tab,$arr_data,"update");
			$result=$db->prepare("INSERT INTO $datalest[0] WHERE $where");
			foreach($arr_data as $kay=>$value){
				$result->bindValue($cont,$value);
				$cont++;
			}
			$result->execute();
			return true;
		}catch (PDOException $e) {
			print "Error In Update Data From Table: ! " . $e->getMessage() . "<br/>"; 
			exit();
			return false;
		 }
	}

	/**
	 * This function  format data To  Query or generat Query 
	 * @param $table_name table name 
	 * @param $arr_data ==array("column_name"=>"value","column_name"=>"value")
	 * @param select format  update  OR insert OR select[name_column]
	 * @return  array(txt Query To Insert Or Update ,count columns using)
	 */
	public function get_Query($name_tab,$arr_data,$typeQ)
	{$sqlcount=0; // count columns in array
		if($typeQ=="update") //cheack prosesor format insert or  update 
		{
			$sql_kay=" `$name_tab` SET  ";

			foreach($arr_data as $kay=>$value)
				{
					if($sqlcount < 1 )
					{
						$sql_kay.="`".$kay."`=`?`";
					}else
					{
						$sql_kay.=",`".$kay."`=`?`" ;
					}$sqlcount++;
				}$sql=$sql_kay;
		}

		if($typeQ=="insert")//cheack prosesor format insert or  update
		{	
			$sql_kay=" `$name_tab`(";
			$sql_value ="VALUES (";
			foreach($arr_data as $kay=>$value)
				{
					if($sqlcount < 1 )
					{
						$sql_kay.="`".$kay."`";
						$sql_value.="`?`";
					}else
					{
						$sql_kay.=",`".$kay."`";
						$sql_value.=",`?`";
					}$sqlcount++;
				}
		$sql_kay.=") ";
		$sql_value.=");";
		$sql = $sql_kay.$sql_value;
		}
		if($typeQ=="select")
		{$sql_kay="";
			foreach($arr_data as $kay)
				{
					if($sqlcount < 1 )
					{
						$sql_kay.="`".$kay."`";
					}else
					{
						$sql_kay.=",`".$kay."`";
                    }$sqlcount++;
                }	
			$sql=$sql_kay;
		}
		return array($sql,$sqlcount); // return array countune for query and count colamn
	}


	
}

?>