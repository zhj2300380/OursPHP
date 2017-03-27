<?php
/**
 * Created by PhpStorm.
 * User: Jack
 * Date: 2017/3/19
 * Time: 23:50
 * Doc:
 */
namespace OursPHP\Core\Lib\Db\mysql;

use OursPHP\Init\ConfigManage;
use OursPHP\Core\Common\DataAssert;
use OursPHP\Core\Common\BizException;

abstract class Model{

    use \OursPHP\Core\Lib\AppendTrait\WithCache;
    const SLAVE = true;
    const MASTER = false;
    protected $_pdo = null;
    protected $_binds=[];
    protected $_feild=[];
    protected $_where=' 1=1';
    protected $_group='';
    protected $_having='';
    protected $_order='';
    protected $_limit=0;
    protected $_table='';
    protected $_pkey='';

    /**
     * 取得数据库主库配置，由子类实现
     * @return mixed
     */
    protected abstract function getMdbCfgName();

    /**
     * 取得数据库从库配置，由子类实现
     * @return mixed
     */
    protected abstract function getSdbCfgName();

    /**
     * dao对应表名，由子类实现
     * @return mixed
     */
    protected abstract function getTableName();

    /**
     * dao对应表名，由子类实现
     * @return mixed
     */
    protected abstract function getPKey();



    /**
     * 设置表名
     * @param $table
     * @return mixed
     */
    public function setTable($table)
    {
        if(is_string($table) && !empty($table))
        {
            $this->_table=$table;
        }
        return this;
    }
    /**
     * 设置主键
     * @param $pkey
     * @return mixed
     */
    public function setPkey($pkey)
    {
        if(is_string($pkey) && !empty($pkey))
        {
            $this->_pkey=$pkey;
        }
        return this;
    }

    /**
     * 设置绑定项
     * @param array $binds
     * @return $this
     */
    public function setBinds(array $binds)
    {
        if (!empty($binds))
        {
            $this->_binds=$binds;
            self::getWereByBinds();
        }
        return $this;
    }
    public function getWereByBinds()
    {
        if(!empty($this->_binds) && $this->_where===' 1=1')
        {
            foreach (array_keys($this->_binds) as $key)
            {
                $this->_where.=" and $key=:$key";
            }
        }
    }

    /**
     * 设置条件
     * @param $where
     * @return $this
     */
    public function setWhere($where)
    {
        if (is_string($where) && !empty($where))
        {
            $this->_where=' 1=1 ';
            $this->_where.=' and '.$where;
        }
        return $this;
    }

    /**
     * 设置offset
     * @param $limit
     * @return $this
     */
    public function setLimit($limit)
    {
        if (is_string($limit) || is_int($limit))
        {
            $this->_limit=$limit;
        }
        return $this;
    }

    /**
     * 设置需要的字段
     * @param array $feild
     * @return $this
     */
    public function setFeild(array $feild)
    {
        if (!empty($feild))
        {
            $this->_feild=$feild;
        }
        return $this;
    }

    /**
     * 设置分组信息
     * @param $group
     * @return $this
     */
    public function setGroup($group)
    {
        if (is_string($group) && !empty($group))
        {
            $this->_group=$group;
        }
        return $this;
    }

    /**
     * having
     * @param $having
     * @return $this
     */
    public function setHaving($having)
    {
        if (is_string($having) && !empty($having))
        {
            $this->_having=$having;
        }
        return $this;
    }

    /**
     * 设置排序
     * @param $order
     * @return $this
     */
    public function setOrder($order)
    {
        if (is_string($order) && !empty($order))
        {
            $this->_order=$order;
        }
        return $this;
    }
    /**
     * 查数据
     * @param array $binds
     * @param string $where
     * @param int $limit
     * @param array $feild
     * @param string $group
     * @param string $having
     * @param string $order
     * @return mixed
     */
    public function findAll(array $binds=[], $where='',$limit=100,array $feild=[],$group='',$having='',$order='')
    {
        if (!empty($binds))
        {
            $this->_binds=$binds;
        }
        if (is_string($where) && !empty($where))
        {
            $this->_where=' 1=1 ';
            $this->_where.=' and '.$where;
        }
        if (!empty($feild))
        {
            $this->_feild=$feild;
        }
        $this->_limit=$limit;

        if (is_string($group) && !empty($group))
        {
            $this->_group=$group;
        }
        if (is_string($having) && !empty($having))
        {
            $this->_having=$having;
        }
        if (is_string($order) && !empty($order))
        {
            $this->_order=$order;
        }

        $feildstr='*';
        if(!empty($this->_feild)) {
            $feildstr=implode(',', $this->_feild);
        }
        $sql = "select $feildstr from $this->_table ";
        if(!empty($this->_where)) {
            $sql.="where $this->_where ";
        }
        if(!empty($this->_group)) {
            $sql.="group by $this->_group ";
            if(!empty($this->_having)) {
                $sql.="having $this->_having  ";
            }
        }
        if(!empty($this->_order)) {
            $sql.="order by $this->_order ";
        }
        if($this->_limit!=0 || is_string($this->_limit)) {
            $sql.="limit $this->_limit";
        }
        return $this->_pdo->getRows($sql, $this->_binds);
    }
    public function find($pk_value) {
        DataAssert::assertNotEmpty($pk_value, new BizException('主键为空'));
        $binds[$this->_pkey]=$pk_value;
        $where="{$this->_pkey}=:{$this->_pkey}";
        $this->_binds=$binds;
        $this->_where=$where;
        $sql = "select * from {$this->_table} where {$this->getPkeyWhereEx()} limit 1";


        $binds=$this->getPkeyBind($pk_value);
        return $this->_pdo->getRow($sql,$binds);
    }

    /**
     * 通过主键删除
     * @param $pk_value
     * @return mixed
     */
    public function delete($pk_value) {
        DataAssert::assertNotEmpty($pk_value, new BizException('主键为空'));
        $binds[$this->_pkey]=$pk_value;
        $where="{$this->_pkey}=:{$this->_pkey}";
        $this->_binds=$binds;
        $this->_where=$where;
        return $this->_pdo->delete($this->_table,$this->_where,$this->_binds);
    }
    /**
     * 删除数据
     * @param array $binds
     * @param string $where
     * @return mixed
     */
    public function deleteByWhere(array $binds=[], $where='') {
        DataAssert::assertNotEmpty($where, new BizException('where条件不能为空'));
        if (!empty($binds))
        {
            $this->_binds=$binds;
        }
        if (is_string($where) && !empty($where))
        {
            $this->_where=$where;
        }
        return $this->_pdo->delete($this->_table, $this->_where,$this->_binds);
    }

    public function getLastError() {
        return $this->_pdo->lastError();
    }
}