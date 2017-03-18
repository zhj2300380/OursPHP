-- --------------------------------------------------------
-- 服务器版本:                        5.7.15 - Source distribution
-- 服务器操作系统:                      Linux
-- HeidiSQL 版本:                  9.4.0.5125
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- 导出  表 oursphp_db.system_function 结构
DROP TABLE IF EXISTS `system_function`;
CREATE TABLE IF NOT EXISTS `system_function` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT '名称',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '父ID 0顶级',
  `uri` varchar(100) NOT NULL,
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '类型：0controller 1action',
  `ismenu` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否是菜单：0不是1是',
  `icon` varchar(100) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL COMMENT '介绍',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态：0无效1有效-1软删除',
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastmodified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COMMENT='权限列表';

-- 正在导出表  oursphp_db.system_function 的数据：~17 rows (大约)
/*!40000 ALTER TABLE `system_function` DISABLE KEYS */;
INSERT INTO `system_function` (`id`, `name`, `pid`, `uri`, `type`, `ismenu`, `icon`, `description`, `status`, `createtime`, `lastmodified`) VALUES
	(1, '系统设置', 0, 'system', 0, 1, 'fa fa-cog', '系统相关参数设置11111', 1, '2017-03-14 01:28:12', '2017-03-14 04:17:21'),
	(2, '管理员管理', 1, 'managerlist', 1, 1, 'fa fa-users', '添加、删除、编辑系统管理员的权限。', 1, '2017-03-14 01:51:41', '2017-03-14 02:14:36'),
	(3, '系统功能添加', 1, 'functionadd', 1, 0, 'glyphicon glyphicon-th', '系统功能添加', 1, '2017-03-14 02:01:14', '2017-03-17 14:27:40'),
	(5, '功能管理', 1, 'functionlist', 1, 1, '', '功能列表', 1, '2017-03-14 02:03:39', '2017-03-17 14:27:33'),
	(6, '系统功能删除', 1, 'functiondel', 1, 0, '', '系统功能删除', 1, '2017-03-14 02:04:20', '2017-03-14 02:04:41'),
	(7, '添加管理员', 1, 'manageradd', 1, 0, 'glyphicon glyphicon-user', '添加管理员', 1, '2017-03-14 02:15:48', '2017-03-17 14:27:46'),
	(9, '管理员删除', 1, 'managerdel', 1, 0, '', '管理员删除', 1, '2017-03-14 02:17:31', '2017-03-14 02:17:31'),
	(11, '重置管理员密码', 1, 'repassword', 1, 0, '', '重置管理员密码', 1, '2017-03-14 02:23:05', '2017-03-14 02:23:05'),
	(12, '锁定管理员', 1, 'managerlock', 1, 0, '', '锁定管理员', 1, '2017-03-14 02:23:59', '2017-03-14 02:23:59'),
	(13, '系统功能锁定', 1, 'functionlock', 1, 0, '', '系统功能锁定', 1, '2017-03-14 02:24:54', '2017-03-14 02:24:54'),
	(14, '角色管理', 1, 'rolelist', 1, 1, 'fa fa-users', '系统功能锁定', 1, '2017-03-14 02:26:19', '2017-03-14 02:26:19'),
	(15, '添加角色', 1, 'roleadd', 1, 0, 'fa fa-users', '添加角色', 1, '2017-03-14 02:26:56', '2017-03-14 03:40:42'),
	(17, '删除角色', 1, 'roledel', 1, 0, 'fa fa-users', '删除角色', 1, '2017-03-14 02:28:03', '2017-03-14 03:40:45'),
	(18, '锁定角色', 1, 'rolelock', 1, 0, 'fa fa-users', '锁定角色', 1, '2017-03-14 02:28:34', '2017-03-14 03:40:47'),
	(19, '功能设置菜单', 1, 'functionsetmenu', 1, 0, '', '功能设置菜单', 1, '2017-03-14 02:50:05', '2017-03-14 03:56:49'),
	(20, '控制台', 0, 'index', 0, 0, '', '网站后台首页', 1, '2017-03-17 01:53:31', '2017-03-17 01:53:31'),
	(21, '首页', 20, 'index', 1, 0, '', '后台首页', 1, '2017-03-17 01:55:11', '2017-03-17 01:55:11');
/*!40000 ALTER TABLE `system_function` ENABLE KEYS */;

-- 导出  表 oursphp_db.system_manager 结构
DROP TABLE IF EXISTS `system_manager`;
CREATE TABLE IF NOT EXISTS `system_manager` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(150) NOT NULL COMMENT '登录名',
  `password` varchar(32) NOT NULL COMMENT '密码',
  `nickname` varchar(50) DEFAULT NULL COMMENT '昵称',
  `sex` tinyint(4) DEFAULT '0' COMMENT '1男0女',
  `mail` varchar(150) DEFAULT NULL COMMENT '邮箱',
  `telphone` varchar(11) DEFAULT NULL COMMENT '手机号',
  `roleid` int(11) DEFAULT '0' COMMENT '所属角色',
  `status` tinyint(4) DEFAULT '1' COMMENT '状体1有效0无效',
  `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `lastmodified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='管理员';

-- 正在导出表  oursphp_db.system_manager 的数据：~1 rows (大约)
/*!40000 ALTER TABLE `system_manager` DISABLE KEYS */;
INSERT INTO `system_manager` (`id`, `username`, `password`, `nickname`, `sex`, `mail`, `telphone`, `roleid`, `status`, `createtime`, `lastmodified`) VALUES
	(3, 'admin', '21232f297a57a5a743894a0e4a801fc3', '管理员', 1, 'admin@localhost', '13000000000', 1, 1, '2017-03-18 09:22:25', '2017-03-18 09:22:25');
/*!40000 ALTER TABLE `system_manager` ENABLE KEYS */;

-- 导出  表 oursphp_db.system_role 结构
DROP TABLE IF EXISTS `system_role`;
CREATE TABLE IF NOT EXISTS `system_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '角色名称',
  `description` varchar(200) DEFAULT NULL COMMENT '角色介绍',
  `functionlist` text NOT NULL COMMENT '权限列表JSON',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态1有效0无效',
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastmodified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='管理员权限表';

-- 正在导出表  oursphp_db.system_role 的数据：~1 rows (大约)
/*!40000 ALTER TABLE `system_role` DISABLE KEYS */;
INSERT INTO `system_role` (`id`, `name`, `description`, `functionlist`, `status`, `createtime`, `lastmodified`) VALUES
	(1, '系统管理员', '系统总管理员', '{"system":["managerlist","functionadd","functionlist","functiondel","manageradd","managerdel","repassword","managerlock","functionlock","rolelist","roleadd","roledel","rolelock","functionsetmenu"],"index":["index"]}', 1, '2017-03-15 15:39:20', '2017-03-17 03:07:22');
/*!40000 ALTER TABLE `system_role` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
