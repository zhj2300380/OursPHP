<?php
namespace OursPHP\Core\Lib\Db\mysql;

use OursPHP\Init\ConfigManage;
use OursPHP\Core\Common\DataAssert;
use OursPHP\Core\Common\BizException;


//abstract class BaseDao implements ConnectionInterface 
abstract class BaseDao{

    use \OursPHP\Core\Lib\AppendTrait\WithCache;
	const SLAVE = true;
	const MASTER = false;
	protected $_pdo = null;
	
	/**
	 * @param string  $pdoconn_or_conntype 传入参数为一个链接或指明链接类型，自动生成链接
	 * @throws Exception
	 */
	public function __construct($pdoconn_or_conntype=self::SLAVE){
		if ($pdoconn_or_conntype == self::SLAVE) {
			$this->_pdo = PDOManager::getConnect($this->getSdbCfgName());
		} else if ($pdoconn_or_conntype == self::MASTER) {
			$this->_pdo = PDOManager::getConnect($this->getMdbCfgName());
		} else if (is_object($pdoconn_or_conntype)) {
			$this->_pdo = $pdoconn_or_conntype;
		} else {
			throw new BizException('参数不是有效的链接对象，也不是有效的链接类型');
		}
	}
	
	
	/**
	 * 取得子类的一个链接从库的实例
	 * 调用此方法的子类上必需有静态属性::$_slave
	 */
	public static function getSlaveInstance() {
		$daoName = get_called_class();		
		if (empty(static::$_slave))
			static::$_slave = new $daoName(self::SLAVE);
		
		return static::$_slave;
	}
	
	
	/**
	 * 取得子类的一个链接从库的实例
	 * 调用此方法的子类上必需有静态属性::$_master
	 */
	public static function getMasterInstance() {
		$daoName = get_called_class();		
		if (empty(static::$_master))
		static::$_master = new $daoName(self::MASTER);
		
		return static::$_master;
	}
	
	/**
	 * 启动当前连接的事务
	 */
	public function beginTransaction(){
		return $this->_pdo->beginTransaction();
	}
	
	/**
	 * 提交当前已经启动的事务
	 */
	public function commit(){
		return $this->_pdo->commit();
	}
	
	/**
	 * 
	 * 回滚事务
	 */
	public function rollBack(){
		return $this->_pdo->rollBack();
	}
	

	//取得数据库主库配置，由子类实现
	protected abstract function getMdbCfgName();
	
	//取得数据库从库配置，由子类实现
	protected abstract function getSdbCfgName();
	
	//dao对应表名，由子类实现
	protected abstract function getTableName();
	
	//dao对应表主键名，由子类实现
	protected abstract function getPKey();

	
	/**
	 * 根据主键查找一条记录
	 * @param string $pk_value 主键的值
	 */
	public function findOne($pk_value) {
		//$sql = "select * from {$this->getTableName()} where {$this->getPkeyWhere($pk_value)} limit 1";
		$sql = "select * from {$this->getTableName()} where {$this->getPkeyWhereEx()} limit 1";
		$binds=$this->getPkeyBind($pk_value);
		//增加方注入
		return $this->_pdo->getRow($sql,$binds);
	}
	
	/**
	 * @param unknown_type $fieldName 字段名
	 * @param unknown_type $value 字段值
	 */
	public function findByField($fieldName, $value) {
		$sql = "select * from {$this->getTableName()} where $fieldName=?";
		return $this->_pdo->getRows($sql, array($value));
	}
	
	public function exec($sql){
		return $this->_pdo->exec($sql);
	}
	
	public function query($query){
		return $this->_pdo->query($query);
	}
	
	/**
	 * 根据条件统计
	 * @param unknown $where
	 */
	public function countBy(array $binds,$where)
	{
		$sql = "select count(1) as num from {$this->getTableName()} where $where";
		return $this->_pdo->getOne($sql,$binds);
	}
	
	/**
	 * 求和
	 * @param string $fieldName 需要求和的字段
	 * @param array $binds 
	 * @param unknown $where
	 */
	public function sumBy($fieldName,array $binds,$where)
	{
		$sql = "select sum($fieldName) as $fieldName from {$this->getTableName()} where $where";
		return $this->_pdo->getOne($sql,$binds);
	}
	/**
	 * 新增一条记录
	 * @param array $vars 行记录数组
	 */
	public function add(array $vars) {
		DataAssert::assertNotEmpty($vars, new BizException('插入内容为空'));
		return $this->_pdo->insert($this->getTableName(), $vars);
	}
	
	
	/**
	 * 新增一条记录
	 * @param array $vars 行记录数组
	 */
	public function adds(array $varslist) {
		DataAssert::assertNotEmpty($varslist, new BizException('插入内容为空'));
		return $this->_pdo->inserts($this->getTableName(), $varslist);
	}

	/**
	 * 修改一条记录
	 * @param unknown_type $pk_value 主键
	 * @param array $vars 修改行记录数组
	 */
	public function edit($pk_value, array $vars) {
		DataAssert::assertNotEmpty($pk_value, new BizException('主键为空'));
		return $this->_pdo->update($this->getTableName(), $vars, $this->getPkeyWhere($pk_value));
		
	}
	
	/**
	 * 根据条件更新
	 * @param array $vars
	 * @param string $where
	 * e.g. 
	 * 	editByWhere(array('name'=>'hi'), 'id=1') == sql: update xxx set name='hi' where id=1;
	 */
	public function editByWhere(array $vars, $where) {
		DataAssert::assertNotEmpty($where, new BizException('where条件为空'));
		return $this->_pdo->update($this->getTableName(), $vars, $where);
	}
	
	
	/**
	 * 根据数据表里的 unique index 替换
	 * @param array $vars 修改行记录数组
	 */
	public function replace(array $vars) {
		return $this->_pdo->replace($this->getTableName(), $vars);
	
	}
	
	/**
	 * 按主键删除一条记录
	 * @param unknown_type $pk_value 主键值
	 */
	public function delete($pk_value) {
		DataAssert::assertNotEmpty($pk_value, new BizException('主键为空'));
		return $this->_pdo->delete($this->getTableName(), $this->getPkeyWhere($pk_value));
	}
	
	/**
	 * 按条件删除一条记录
	 * @param unknown_type $where 条件
	 */
	public function deleteByWhere($where) {
		DataAssert::assertNotEmpty($where, new BizException('where条件为空'));
		return $this->_pdo->delete($this->getTableName(), $where);
	}
	protected function getPkeyWhereEx()
	{
		$pkname = $this->getPKey();
		return " $pkname=:$pkname";
	}
	protected function getPkeyBind($pk_value)
	{
		$pkname = $this->getPKey();
		return [$pkname=>$pk_value];
	}
	protected function getPkeyWhere($pk_value) {
		/*if ( is_array($pk_value) != is_array($this->pk() )  ){
			 throw new Exception("当前dao的pk()定义的键与查询key使用的key不相同，请检查");
		}*/
		
		if (is_array($pk_value)) {
			$tmp = array();
			foreach ($pk_value as $key=>$field) {
				$tmp[] = "$key='$field'";
			}
			return implode(' and ', $tmp);
		}			
		else{
		    $pkname = $this->getPKey();
			return " $pkname='$pk_value' ";
		}
	}
	
	public function getLastError() {
		return $this->_pdo->lastError();
	}
	
	/**
	 * 根据主键查找一条记录
	 * @param string $pk_value 主键的值
	 */
	public function findObj($className, $pk_value) {
		$sql = "select * from {$this->getTableName()} where {$this->getPkeyWhere($pk_value)} limit 1";
		return $this->_pdo->findObj($className, $sql);
	}
	
	/**
	 * @param unknown_type $fieldName 字段名
	 * @param unknown_type $value 字段值
	 */
	public function findObjs($className, $fieldName, $value) {
		$sql = "select * from {$this->getTableName()} where $fieldName=?";
		return $this->_pdo->finObjs($className, $sql, array($value));
	}

	/**
	 * 查询
	 * @param array $feild
	 * @param string $where
	 * @param string $group
	 * @param string $having
	 * @param string $order
	 * @param string $limit
	 */
	public function findAll(array $binds=[], array $feild=[],$where='',$group='',$having='',$order='',$limit=100)
	{
		$feildstr='*';
		if(!empty($feild)) {
			$feildstr=implode(',', $feild);
		}
		$sql = "select $feildstr from {$this->getTableName()} ";
		if(!empty($where)) {
			$sql.="where $where ";
		}
		if(!empty($group)) {
			$sql.="group by $group ";
			if(!empty($having)) {
				$sql.="having $having  ";
			}
		}
		if(!empty($order)) {
			$sql.="order by $order ";
		}
		if(strstr($limit,',')) {
			$sql.="limit $limit";
		}elseif($limit!=0) {
			$sql.="limit $limit";
		}
		return $this->_pdo->getRows($sql, $binds);
	}

}
