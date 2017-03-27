<?php
namespace OursPHP\Core\Lib\Db\mysql;

use \PDO;
use \PDOStatement;

/**
 * 扩展了的pdo
 *
 */
class PDOext extends PDO {
	private $_lastErrorInfo = '';
	private $_queryTime = '';
	private $_sth = '';
	private $_dsn = '';
	private $_userName = '';
	private $_passowrd = '';
	private $_charSet = '';
	private $_debug = OURS_DEBUG;
	
	public static $keys = array('key', 'type', 'condition', 'div', 'int1', 'int2', 'int3', 'int4', 'int8', 'status'); //mysql 常用关键字
	
	/**
	 * 取得一个数据库链接
	 * @param string $dsn
	 * @param string $userName
	 * @param string $passowrd
	 * @param string $charSet
	 */
	public function __construct($dsn, $userName, $passowrd, $charSet='utf8') {
		$this->_dsn = $dsn;
		$this->_userName = $userName;
		$this->_passowrd = $passowrd;
		$this->_charSet = $charSet;

		$this->connect($dsn, $userName, $passowrd, $charSet);
	}

	/**
	 * @param string $dsn
	 * @param string $userName
	 * @param string $passowrd
	 * @param string $charSet
	 */
	public function connect($dsn, $userName, $passowrd, $charSet='utf8') {
		parent::__construct($dsn, $userName, $passowrd);
		$this->query("set names '$charSet'");
		$this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	/**
	 * 重新链接
	 */
	public function reconnect() {
		$this->connect($this->_dsn, $this->_userName, $this->_passowrd, $this->_charSet);
	}

	/**
	 * connect ping
	 */
	public function ping() {
		return $this->query('select 1');
	}
	
	
	/**
	 * 取查询结果的一列
	 * @param string $sql
	 * @param array $binds
	 * @return multitype:string 
	 */
	public function getCol($sql, array $binds=array()) {
		$rows = array();
		$sth = $this->prepare($sql);
		self::bindValue($sth, $binds);
		$this->execute($sth);
		$this->_lastErrorInfo = $sth->errorInfo();
		while ($row = $sth->fetchColumn())
			$rows[] = $row;
		$sth->closeCursor();
		return $rows;
	}
	
	
	/**
	 * 取查询结果一个元素 如 [select count(1) as cnt]的 cnt
	 * @param string $sql
	 * @param array $binds
	 * @return string
	 */
	public function getOne($sql, array $binds=array()) {
		return $this->getScaler($sql, $binds);
	}
	
	
	/**
	 * 同getOne
	 * @param string $sql
	 * @param array $binds
	 * @return string
	 */
	public function getScaler($sql, array $binds=array()) {
		$sth = $this->prepare($sql);
		self::bindValue($sth, $binds);
		$this->execute($sth);
		$this->_lastErrorInfo = $sth->errorInfo();
		$out = $sth->fetchColumn();
		$sth->closeCursor();
		return $out;
	}
	
	/**
	 * 取查询结果中的一行
	 * @param unknown_type $sql
	 * @param array $binds
	 * @return mixed
	 */
	public function getRow($sql, array $binds=array()) {
		$sth = $this->prepare($sql);
		self::bindValue($sth, $binds);
		//dump($sth);
		$this->execute($sth);
		$this->_lastErrorInfo = $sth->errorInfo();
		$out = $sth->fetch();
		$sth->closeCursor();
		return $out;
	}
	

	/**
	 * 取查询结果集
	 * @param unknown_type $sql
	 * @param array $binds
	 * @return multitype:
	 */
	public function getRows($sql, array $binds=array()) {
		$sth = $this->prepare($sql);
		self::bindValue($sth, $binds);
        //dump($sth);
		$this->execute($sth);
		$this->_lastErrorInfo = $sth->errorInfo();
		$out = $sth->fetchAll();
		$sth->closeCursor();
		return $out;
	}
	
	/**
	 * 插入一条数据
	 * @param string $table
	 * @param array $data
	 * @return boolean
	 */
	public function insert($table, array $data) {
		$ks = array();
		foreach (array_keys($data) as $k) {
			if (in_array($k, self::$keys))
				$k = "`$k`";
			$ks[] = $k;
		}
		$sqlK = implode(', ', $ks);
		$sqlV = ':'.implode(', :', array_keys($data));
		
		$sql = "insert into $table ($sqlK) values ($sqlV)";
		$sth = $this->prepare($sql);
		self::bindValue($sth, $data);
		$out = $this->execute($sth)?$this->lastInsertId():false;
		$sth->closeCursor();
		return $out;
	}
	
	
	
	/**
	 * 插入多条数据 
	 * @param string $table
	 * @param array $datas
	 * @return boolean
	 */
	public function inserts($table, array $datas) {
		$ks = array();
		foreach (array_keys($datas[0]) as $k) {
			if (in_array($k, self::$keys))
				$k = "`$k`";
			$ks[] = $k;
		}
		$sqlK = implode(', ', $ks);
		$i=0;
		$sqlV='';
		$newdata=[];
		foreach ($datas as &$item)
		{
			$keys=array_keys($item);
			$_sqlV = ':'.implode($i.', :', $keys).$i;
			$sqlV=$sqlV.(',('.$_sqlV.')');
			$sqlV=ltrim($sqlV, ",");
			
			foreach ($keys as $key)
			{
				$newdata[$key.$i]=$item[$key];
			}
			
			$i++;
		}
		$sql = "insert into $table ($sqlK) values $sqlV";
		
		$sth = $this->prepare($sql);
		self::bindValue($sth, $newdata);

		$out = $this->execute($sth)?$this->lastInsertId():false;
		$sth->closeCursor();
		return $out;
	}
	
	/**
	 * 按条件更新数据
	 * @param string $table
	 * @param array $data
	 * @param string $where
	 * @return boolean
	 */
	public function update($table, array $data, $where) {
		if (strlen($where) == 0)
			return false;
			
		$sqlU = 'set ';
		foreach ($data as $v=>$v2) {
			if ($v[0] == ':')
				$v[0] = '';
			if (in_array($v, self::$keys))
				$k = "`$v`";
			else
				$k = $v;
			$sqlU .= "$k=:$v, ";
		}
		$sqlU = trim(trim($sqlU, ' '), ',');
		$sql = "update $table $sqlU where $where";
		$sth = $this->prepare($sql);
		self::bindValue($sth, $data);
		$out = $this->execute($sth);
		$sth->closeCursor();
		return $out;
	}
	
	/**
	 * 按条件更新数据
	 * @param string $table
	 * @param array $data
	 * @return boolean
	 */
	public function replace($table, array $data) {
		$ks = array();
		foreach (array_keys($data) as $k) {
			if (in_array($k, self::$keys))
				$k = "`$k`";
			$ks[] = $k;
		}
		$sqlK = implode(', ', $ks);
		$sqlV = ':'.implode(', :', array_keys($data));
		
		$sql = "replace into $table ($sqlK) values ($sqlV)";
		$sth = $this->prepare($sql);
		self::bindValue($sth, $data);
		$out = $this->execute($sth)?$this->lastInsertId():false;
		$sth->closeCursor();
		return $out;
	}
	
	
	/**
	 * 按条件删除数据
	 * @param string $table
	 * @param string $where
	 * @return boolean
	 */
	public function delete($table, $where,array $data=[]) {
		$sql = "delete from $table where $where";
		$sth = $this->prepare($sql);
        self::bindValue($sth, $data);
		$out = $this->execute($sth);
		$sth->closeCursor();
		return $out;
	}
	
	/**
	 * 占位符bind
	 * @param PDOStatement $sth
	 * @param array $binds
	 */
	public static function bindValue(PDOStatement &$sth, array $binds) {
		foreach ($binds as $k=>$v) {
			if (is_int($k)) {
				$sth->bindValue($k+1, $v);
				continue;
			}
			if ($k[0] != ':')
				$k = ':'.$k;
			$sth->bindValue($k, $v);
		}
	}
	
	/**
	 * 执行build好的PDOStatement
	 * @param PDOStatement $sth
	 * @param boolean $setFetchAssoc
	 * @return array
	 */
	public function execute(&$sth, $setFetchAssoc=true) {
		if ($setFetchAssoc)
			$sth->setFetchMode(PDO::FETCH_ASSOC);
		
		if('MySQL server has gone away' == $this->getAttribute(PDO::ATTR_SERVER_INFO))	{
			/* 进行PDO连接 */
			$this->reconnect();
		}
		$out = $sth->execute();
		$this->debug($sth);
		
		return $out;
	}

	/**
	 * @return string
	 */
	public function lastErrorCode() {
		return $this->_lastErrorInfo? $this->_lastErrorInfo[0]:'';
	}
	
	/**
	 * @return string
	 */
	public function lastError() {
		return $this->_lastErrorInfo? $this->_lastErrorInfo[2]:'';
	}
	
	/**
	 * 调试执行时间
	 */
	public function debugTime() {
		$this->_queryTime = microtime(true);
	}
	
	/**
	 * 调试
	 * @param PDOStatement $sth
	 */
	public function debug($sth) {
		if ($this->_debug) {
			$queryTime = (microtime(true) - $this->_queryTime);
			echo '<li style="border:1px solid #f66;background:#FFFBD9; padding:5px; margin-bottom:-1px;">';
			$sth->debugDumpParams();
			printf("cost:[%.4f s]", $queryTime);
			echo '</li>';
		}
		if (@$_GET['trace']) {
			debug_print_backtrace();
		}
			
	}
	
	
	/**
	 * 获取一个行对就到一个对象里 
	 * @param string class name
	 * @param unknown_type $sql
	 * @param array $binds
	 * @return mixed
	 */
	public function findObj($className, $sql, array $binds=array()) {
		$sth = $this->prepare($sql);
		self::bindValue($sth, $binds);
		$this->execute($sth);
		$this->_lastErrorInfo = $sth->errorInfo();
		$out = $sth->fetch(\PDO::FETCH_CLASS, $className);
		$sth->closeCursor();
		return $out;
	}

	/**
	 * 获取一个行对就到一个对象里 
	 * @param string class name
	 * @param unknown_type $sql
	 * @param array $binds
	 * @return mixed
	 */
	public function findObjs($className, $sql, array $binds=array()) {
		$sth = $this->prepare($sql);
		self::bindValue($sth, $binds);
		$this->execute($sth);
		$this->_lastErrorInfo = $sth->errorInfo();
		$out = $sth->fetchAll(\PDO::FETCH_CLASS, $className);
		$sth->closeCursor();
		return $out;
	}

}