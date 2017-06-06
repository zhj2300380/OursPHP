<?php
namespace OursPHP\Core\Lib\Db\mysql;

use OursPHP\Init\ConfigManage;
use OursPHP\Core\Common\Roll;
use \PDOException;

class PDOManager{
	private static $_pdomap = array();
	
	//singleton connection 
	public static function getConnect($cfgName){
		$conn = '';
		if (isset(self::$_pdomap[$cfgName]))
			$conn = self::$_pdomap[$cfgName];
		
		if (empty($conn) || !self::isValid($conn))
			self::$_pdomap[$cfgName]  = self::getNewConnect($cfgName);
		
		return self::$_pdomap[$cfgName];
	}


    /**
     * 根据配置段取一个数据库链接
     * @param $sectionName
     * @return PDOext
     */
	public static function getNewConnect($sectionName) {
		$cfg=ConfigManage::getConfig('mysql',$sectionName);

        if($cfg===false)
        {
            echo 'mysql config error';exit;
        }
		/**
		 * 单节点
		 */
		if(!empty($cfg) && isset($cfg['options']['host']))
		{
			$cfg['dsn']="{$cfg['driver']}:host={$cfg['options']['host']};port={$cfg['options']['port']};dbname={$cfg['options']['database']}";
			return new PDOext($cfg['dsn'], $cfg['options']['username'], $cfg['options']['password']);
		}
		/**
		 * 多节点
		 */
		if (!empty($cfg) && !isset($cfg['options']['host']) ) 
		{ 
			$option = Roll::select($cfg['options']);
			$cfg['dsn']="{$cfg['driver']}:host={$option['host']};port={$option['port']};dbname={$option['database']}";
			return new PDOExt($cfg['dsn'], $option['username'], $option['password']);
		}
	}
	
	
	public static function isValid($conn) {
		try {
			$conn->ping();
		} catch (PDOException $e) {
			return false;
		}
		return true;
	}
	
}