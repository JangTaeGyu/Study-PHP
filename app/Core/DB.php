<?php
/**
 * Database abstraction class, make very easy to work with databases.
 *
 * @author Gombos Lorand (glorand@gmail.com)
 * @name simpleSQL - PDO
 * @version 1.0/PDO
 */

/**
 * Database specific class - mySQL
 *
 */

namespace App\Core;

class DB
{
	private $hostname	=	'';
	private $username	=	'';
	private $password	=	'';
	private $dbname	=	'';

	private static $instance = false;
	public $db;
	public $fetch_mode = \PDO::FETCH_ASSOC;
	public $sth;

	 /*********************************************************************
	 * Cached parameters												  *
	 **********************************************************************/
	private $last_query		= null;
	private $last_statement = null;
	private $last_result	= null;
	private $row_count		= null;
	private $affected_row	= null;

	/**
	 * Constructor.
	 * Implements the Singleton design pattern.
	 *
	 * @return object DB
	 * @access public
	 */
	public function __construct($host, $name, $user, $password, $singleton = false)
	{
		ini_set('track_errors',1);

		$this->hostname = $host;
		$this->dbname = $name;
		$this->username = $user;
		$this->password = $password;

		if ($singleton == false || !self::$instance){
			$this->connect();
		}

		$this->internalQuery('SET NAMES utf8');

		return self::$instance;
	}

	/**
	 * Connect to the database and set the error mode to Exception.
	 *
	 * @return void
	 * @access private
	 */
	private function connect()
	{
		$dns = 'mysql:host='.$this->hostname.';dbname='.$this->dbname;
		try {
			self::$instance = new \PDO($dns, $this->username, $this->password);
		} catch (\Exception $e) {

		}
		//self::$instance->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		$this->db = self::$instance;
	}

	/**
	 * Execute a query.
	 * This function can be used from external.
	 * The function separate the simple queryes and the INSERT, UPADTE, DELETE queries.
	 *
	 * @param string $query
	 * @access public
	 *
	 * @todo Validate $query
	 */
	public function query($query = null)
	{
		$this->flush();
		$query = trim($query);
		$this->last_query = $query;
		// Query was an insert, delete, update, replace
		if ( preg_match("/^(insert|delete|update|replace|drop|create)\s+/i",$query) ){
			$this->affected_row = $this->db->exec($query);
			if ( $this->catch_error() ) return false;
			else return $this->affected_row;
		}
		else {
			//Query was an simple query.
			$stmt = $this->db->query($query);
			if ( $this->catch_error() ) return false;
			else {
				if($stmt == null) return null;
				$stmt->setFetchMode($this->fetch_mode);
				$this->last_statement = $stmt;
				$this->last_result = $this->last_statement->fetchAll();
				return $this->last_result;
			}
		}
	}

	// 전천후 쿼리기 ㅋ
	public function prepare($query = null, $param = null, $fetch_mode = null)
	{
		$this->flush();
		$query = trim($query);
		$this->last_query = $query;

		$this->sth = $this->db->prepare($query);

		if($fetch_mode) $this->sth->setFetchMode($fetch_mode);
		else $this->sth->setFetchMode($this->fetch_mode);

		if(is_array($param)) $this->sth->execute($param);
		else $this->sth->execute();

		$this->last_statement = $this->sth;
		return $this->sth;
	}

	public function getOne($query = null)
	{
		$stmt = $this->db->query($query);
		$result = $stmt->fetch(\PDO::FETCH_NUM);
		return $result[0];
	}

	/**
	 * Execute a query.
	 * This function can be used from DB class methods.
	 *
	 * @param string $query
	 * @return bool
	 * @access private
	 *
	 * @todo Validate $query
	 */
	private function internalQuery($query = null)
	{
		$this->flush();
		$query = trim($query);
		$this->last_query = $query;

		$stmt = $this->db->query($query);
		if ( $this->catch_error() ) return false;
		if($stmt == null) return false;
		$stmt->setFetchMode($this->fetch_mode);
		$this->last_statement = $stmt;
		return TRUE;
	}

	/**
	 * Execute a query (INSERT, UPDATE, DELETE).
	 *
	 * @param string $query
	 * @return int
	 * @access private
	 *
	 * @todo Validate $query
	 */
	public function execute($query = null)
	{
		$this->flush();
		$query = trim($query);
		$this->last_query = $query;
		$this->affected_row = $this->db->exec($query);
		if ( $this->catch_error() ) return false;
		return $this->affected_row;
	}

	/**
	 * Return the the query as a result set.
	 *
	 * @param string $query
	 * @return result set
	 * @access public
	 *
	 * @todo Validate $query.
	 */
	public function getResults($query = null)
	{
		$this->internalQuery($query);
		$result = $this->last_statement->fetchAll();
		$this->last_result = $result;

		return $result;
	}

	/**
	 * Get one row from the DB.
	 *
	 * @param string $query
	 * @return reulst set
	 * @access public
	 *
	 * @todo Validate $query.
	 */
	public function getRow($query = null)
	{
		$this->internalQuery($query);

		if ($this->last_statement == null) return null;

		$result = $this->last_statement->fetch();
		$this->last_result = $result;

		return $result;
	}

	/**
	 * Helper function, walk the array, and modify the values.
	 *
	 * @param pointer $item
	 * @return void
	 * @access private
	 */
	private static function prepareDbValues(&$item)
	{
		$item = "'".self::escape($item)."'";
	}



	public function pdo_prepare($query)
	{
		$this->flush();
		$query = trim($query);
		$sth = $this->db->prepare($query);
	}


	/**
	 * Insert a value into a table.
	 *
	 * @param string $table
	 * @param array $data
	 * @return void
	 * @access public
	 *
	 * @todo Validate if $table or $data is null.
	 */
	public function insert($db = null, $table = null, $data = null)
	{
		@array_walk($data,'DB::prepareDbValues');

		foreach ($data as $key => $val){
			$valstr[] = sprintf("`%s` = '%s'",$key,addslashes($val));
		}

		if($db == null) $db = $this->dbname;

		$query = "INSERT INTO `".$db."`.`".$table."` SET ".implode(', ', $valstr);

		$ret_row = $this->execute($query);

		return $ret_row;
	}


	public function replace($db = null, $table = null, $data = null)
	{
		@array_walk($data,'DB::prepareDbValues');

		foreach ($data as $key => $val){
			$valstr[] = sprintf("`%s` = '%s'",$key,$val);
		}
		if($db == null) $db = $this->dbname;

		$query = "REPLACE INTO `".$db."`.`".$table."` SET ".implode(', ', $valstr);
		$ret_row = $this->execute($query);

		return $ret_row;
	}


	/**
	 * Update a value(s) in a table
	 * Ex:
	 * $table = 'tableName';
	 * $data = array('text'=> 'value', 'date'=> '2009-12-01');
	 * $where = array('id=12','AND name="John"'); OR $where = 'id = 12';
	 *
	 * @param string $table
	 * @param array $data
	 * @param array/string $where
	 * @return void
	 * @access public
	 *
	 * @todo Validate the $table, $data, $where variables.
	 */
	public function update($db = null, $table = null, $data = null, $where = null)
	{
//		array_walk($data,'DB::prepareDbValues');

		foreach ($data as $key => $val){
			$valstr[] = sprintf("`%s` = '%s'",$key,addslashes($val));
		}

		if($db == null) $db = $this->dbname;

		$query = "UPDATE `".$db."`.`".$table."` SET ".implode(', ', $valstr);
		if (is_array($where)){
			$query.= " WHERE ".implode(" ",$where);
		}
		else {
			$query.= " WHERE ".$where;
		}

		$ret_row = $this->execute($query);

		return $ret_row;
	}

	/**
	 * Delete a record from a table.
	 * Ex.
	 * $table = 'tableName';
	 * $where = array('id = 12','AND name = "John"'); OR $where = 'id = 12';
	 *
	 * @param string $table
	 * @param array/string $where
	 * @return void
	 * @access public
	 *
	 * @todo Validate the $table, $where variables.
	 */
	public function delete($db = null, $table = null, $where = null)
	{
		if($db == null) $db = $this->dbname;

		$query = "DELETE FROM `".$db."`.`".$table."` WHERE ";
		if (is_array($where)){
			$query.= implode(" ",$where);
		}
		else{
			$query.= $where;
		}

		$ret_row = $this->execute($query);

		return $ret_row;
	}

	/**
	 * Return the last insert id.
	 *
	 * @return integer
	 * @access public
	 */
	public function getLastInsertId()
	{
		return $this->db->lastInsertId();
	}

	/**
	 * Return the last executed query.
	 *
	 * @return string
	 * @access public
	 */
	public function getLastQuesry()
	{
		return $this->last_query;
	}

	/**
	 * Returns the number of rows affected by the last SQL statement.
	 *
	 * @return int
	 * @access public
	 */
	public function rowCount()
	{
		if (!is_null($this->last_statement)){
			return $this->last_statement->rowCount();
		}
		else {
			return 0;
		}
	}

	/**
	 * Set the PDO fetch mode.
	 *
	 * @param string $fetch_mode
	 * @return void
	 * @access public
	 */
	public function setFetchMode($fetch_mode)
	{
		$this->fetch_mode = $fetch_mode;
	}

	/**
	 * Kill cached data.
	 *
	 * @return void
	 * @access private
	 */
	private function flush()
	{
		$this->last_query		= null;
		$this->last_statement 	= null;
		$this->last_result		= null;
		$this->row_count		= null;
		$this->affected_row		= null;
	}

	/**
	*  Format a mySQL string correctly for safe mySQL insert
	*  (no mater if magic quotes are on or not)
	*
	* @param string $str
	* @return string
	* @access public
	*/
	public function escape($str)
	{
		return mysql_escape_string(stripslashes($str));
	}

	private function catch_error()
	{
		$err_array = $this->db->errorInfo();
		// Note: Ignoring error - bind or column index out of range
		if (isset($err_array[1]) && $err_array[1] != 25)
		{
			try {
				throw new \Exception();
			}
			catch (\Exception $e){

				print "<div style='background-color:#D8D8D8; color:#000000; padding:10px; border:2px red solid;>";
				print "<p style='font-size:25px; color:#7F0000'>DATABASE ERROR</p>";
				print "<p style='font-size:20px; color:#7F0000'>Query:<br /><span style='font-size:15px; color:#000000;'>{$this->getLastQuesry()}</span></p>";
				print "<p style='font-size:20px; color:#7F0000'>Message:<br /><span style='font-size:15px; color:#000000;'>{$err_array[2]}</span></p>";
				print "</div>";

				die();
			}
		}
	}
}