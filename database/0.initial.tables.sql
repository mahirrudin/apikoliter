CREATE TABLE IF NOT EXISTS `users` (
  `users_id` int(11) NOT NULL AUTO_INCREMENT,
  `users_email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `users_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `users_pass` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `users_type` set('System Administrator','IT Manager') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'System Administrator',
  `users_registered` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `users_delete` int(1) NOT NULL DEFAULT '0' COMMENT '0 = not delete, 1 = deleted',
  PRIMARY KEY (`users_id`)
);

INSERT INTO `users` (`users_id`, `users_email`, `users_name`, `users_pass`, `users_type`, `users_registered`, `users_delete`) VALUES
	(1, 'it.manager@prosperita.co.id', 'it.manager', 'password', 'IT Manager', '2016-08-17 08:15:47', 0),
	(2, 'it.sysadmin@prosperita.co.id', 'it.sysadmin', 'password', 'System Administrator', '2016-08-17 08:16:37', 0);

CREATE TABLE IF NOT EXISTS `distros` (
  `distro_id` int(3) NOT NULL AUTO_INCREMENT,
  `distro_name` varchar(10) DEFAULT NULL,
  `distro_version` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`distro_id`)
);

INSERT INTO `distros` (`distro_id`, `distro_name`, `distro_version`) VALUES
	(1, 'Ubuntu', '16.04 LTS'),
	(2, 'CentOS', '7 Final');

CREATE TABLE IF NOT EXISTS `servers` (
  `servers_id` int(11) NOT NULL AUTO_INCREMENT,
  `servers_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `servers_hostname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `servers_login` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'root',
  `servers_password` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `servers_port` int(6) NOT NULL DEFAULT '22',
  `servers_distroid` int(3) DEFAULT NULL,
  `servers_kernel` text COLLATE utf8_unicode_ci,
  `servers_procie` text COLLATE utf8_unicode_ci,
  `servers_date` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `servers_delete` int(1) DEFAULT '0' COMMENT '0 = not, 1 = deleted',
  PRIMARY KEY (`servers_id`),
  KEY `FKSVR_DISTROID` (`servers_distroid`),
  CONSTRAINT `FKSVR_DISTROID` FOREIGN KEY (`servers_distroid`) REFERENCES `distros` (`distro_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
);

CREATE TABLE IF NOT EXISTS `services` (
  `services_id` int(11) NOT NULL AUTO_INCREMENT,
  `services_name` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `services_distroid` int(3) DEFAULT NULL,
  `services_pkg` text COLLATE utf8_unicode_ci,
  `services_ctl` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `services_configname` text COLLATE utf8_unicode_ci,
  `services_configdir` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`services_id`),
  KEY `FKSVC_DISTRO` (`services_distroid`),
  CONSTRAINT `FKSVC_DISTRO` FOREIGN KEY (`services_distroid`) REFERENCES `distros` (`distro_id`)
);

INSERT INTO `services` (`services_id`, `services_name`, `services_distroid`, `services_pkg`, `services_ctl`, `services_configname`, `services_configdir`) VALUES
	(1, 'apache', 1, 'apache2', 'apache2', 'apache2.conf', '/etc/apache2/'),
	(2, 'apache', 2, 'httpd', 'httpd', 'httpd.conf', '/etc/httpd/conf/'),
	(3, 'mysql', 1, 'mysql-server', 'mysql', 'my.cnf', '/etc/mysql/'),
	(4, 'mysql', 2, 'mysql', 'mysqld', 'my.cnf', '/etc/'),
	(5, 'postfix', 1, 'postfix', 'postfix', 'main.cf', '/etc/postfix/'),
	(6, 'postfix', 2, 'postfix', 'postfix', 'main.cf', '/etc/postfix/'),
	(7, 'ntp', 1, 'ntp', 'ntp', 'ntp.conf', '/etc/'),
	(8, 'ntp', 2, 'ntp', 'ntpd', 'ntp.conf', '/etc/'),
	(9, 'snmp', 1, 'snmpd', 'snmpd', 'snmpd.conf', '/etc/snmp/'),
	(10, 'snmp', 2, 'net-snmp', 'snmpd', 'snmpd.conf', '/etc/snmp/');

CREATE TABLE IF NOT EXISTS `svcstatus` (
  `svcstatus_id` int(11) NOT NULL AUTO_INCREMENT,
  `svcstatus_serverid` int(11) DEFAULT NULL,
  `svcstatus_servicesid` int(11) DEFAULT NULL,
  `svcstatus_installed` int(1) DEFAULT '0' COMMENT '0 = uninstalled 1 = installed',
  `svcstatus_startup` int(1) DEFAULT '0' COMMENT '0 = disabled 1 = disabled',
  `svcstatus_daemon` int(1) DEFAULT '0' COMMENT '0 = stoped 1 = started',
  PRIMARY KEY (`svcstatus_id`),
  KEY `FKSVCSTATUS_SERVERID` (`svcstatus_serverid`),
  KEY `FKSVCSTATUS_SERVICESID` (`svcstatus_servicesid`),
  CONSTRAINT `FKSVCSTATUS_SERVERID` FOREIGN KEY (`svcstatus_serverid`) REFERENCES `servers` (`servers_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FKSVCSTATUS_SERVICESID` FOREIGN KEY (`svcstatus_servicesid`) REFERENCES `services` (`services_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
);

CREATE TABLE IF NOT EXISTS `configurations` (
  `configurations_id` int(11) NOT NULL AUTO_INCREMENT,
  `configurations_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `configurations_svcstatus` int(11) DEFAULT NULL,
  `configurations_user` int(5) DEFAULT NULL,
  `configurations_localfile` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`configurations_id`),
  UNIQUE KEY `configurations_localfile` (`configurations_localfile`),
  KEY `FKCFG_SVCSTATUS` (`configurations_svcstatus`),
  KEY `FKCFG_USER` (`configurations_user`),
  CONSTRAINT `FKCFG_SVCSTATUS` FOREIGN KEY (`configurations_svcstatus`) REFERENCES `svcstatus` (`svcstatus_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FKCFG_USER` FOREIGN KEY (`configurations_user`) REFERENCES `users` (`users_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
);

CREATE TABLE IF NOT EXISTS `svrstatus` (
  `svrstatus_serverid` int(11) NOT NULL,
  `svrstatus_date` datetime NOT NULL,
  `svrstatus_down` int(1) NOT NULL DEFAULT '0' COMMENT '1 = down, 0 = up',
  `svrstatus_cpuusage` decimal(5,2) NOT NULL DEFAULT '0.00',
  `svrstatus_ramtotal` int(20) NOT NULL DEFAULT '0',
  `svrstatus_ramusage` int(20) NOT NULL DEFAULT '0',
  `svrstatus_hddtotal` int(20) NOT NULL DEFAULT '0',
  `svrstatus_hddusage` int(20) NOT NULL DEFAULT '0',
  KEY `FKSTATS_SERVER` (`svrstatus_serverid`),
  CONSTRAINT `FKSTATS_SERVER` FOREIGN KEY (`svrstatus_serverid`) REFERENCES `servers` (`servers_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
);

CREATE TABLE `activities` (
	`usrmail` VARCHAR(50) NULL COLLATE 'utf8_unicode_ci',
	`usrname` VARCHAR(50) NULL COLLATE 'utf8_unicode_ci',
	`actdate` DATETIME NULL,
	`actname` TEXT NULL COLLATE 'utf8_unicode_ci',
	`svrname` VARCHAR(50) NULL COLLATE 'utf8_unicode_ci',
	`svrip` VARCHAR(50) NULL COLLATE 'utf8_unicode_ci',
	`svcname` VARCHAR(20) NULL COLLATE 'utf8_unicode_ci',
	`cfgid` INT(11) NULL,
	`cfgdir` TEXT NULL COLLATE 'utf8_unicode_ci',
	`cfgname` TEXT NULL COLLATE 'utf8_unicode_ci'
);

CREATE TABLE `configrecord` (
	`txtidsvcs` INT(11) NOT NULL,
	`txtidcfg` INT(11) NOT NULL,
	`txtnmsvr` VARCHAR(50) NOT NULL COLLATE 'utf8_unicode_ci',
	`txtipsvr` VARCHAR(50) NOT NULL COLLATE 'utf8_unicode_ci',
	`txtloginsvr` VARCHAR(50) NOT NULL COLLATE 'utf8_unicode_ci',
	`txtpswdsvr` VARCHAR(50) NOT NULL COLLATE 'utf8_unicode_ci',
	`txtportsvr` INT(6) NOT NULL,
	`txtnmsvc` VARCHAR(20) NULL COLLATE 'utf8_unicode_ci',
	`txtdircfg` TEXT NULL COLLATE 'utf8_unicode_ci',
	`txtnmcfg` TEXT NULL COLLATE 'utf8_unicode_ci',
	`txtlocalcfg` VARCHAR(255) NULL COLLATE 'utf8_unicode_ci',
	`txtnmusr` VARCHAR(50) NOT NULL COLLATE 'utf8_unicode_ci',
	`txtdatecfg` DATETIME NULL
);

CREATE TABLE `monitoring` (
	`svrid` INT(11) NOT NULL,
	`svrname` VARCHAR(50) NULL COLLATE 'utf8_unicode_ci',
	`svrip` VARCHAR(50) NULL COLLATE 'utf8_unicode_ci',
	`svrkernel` TEXT NULL COLLATE 'utf8_unicode_ci',
	`svrprocie` TEXT NULL COLLATE 'utf8_unicode_ci',
	`svrdistro` VARCHAR(10) NULL COLLATE 'utf8_general_ci',
	`svrdistrover` VARCHAR(10) NULL COLLATE 'utf8_general_ci',
	`statsdate` DATETIME NOT NULL,
	`statsdown` INT(1) NOT NULL COMMENT '1 = down, 0 = up',
	`cpuusage` DECIMAL(5,2) NOT NULL,
	`ramtotal` INT(20) NOT NULL,
	`ramusage` INT(20) NOT NULL,
	`hddtotal` INT(20) NOT NULL,
	`hddusage` INT(20) NOT NULL
);

CREATE TABLE `servicesinstalled` (
	`txtidsvcs` INT(11) NOT NULL,
	`txtnmsvr` VARCHAR(50) NOT NULL COLLATE 'utf8_unicode_ci',
	`txtnmsvc` VARCHAR(20) NULL COLLATE 'utf8_unicode_ci',
	`txtstartup` INT(1) NULL COMMENT '0 = disabled 1 = disabled',
	`txtdaemon` INT(1) NULL COMMENT '0 = stoped 1 = started',
	`txtidsvr` INT(11) NOT NULL,
	`txtipsvr` VARCHAR(50) NOT NULL COLLATE 'utf8_unicode_ci',
	`txtloginsvr` VARCHAR(50) NOT NULL COLLATE 'utf8_unicode_ci',
	`txtpswdsvr` VARCHAR(50) NOT NULL COLLATE 'utf8_unicode_ci',
	`txtportsvr` INT(6) NOT NULL,
	`txtidsvc` INT(11) NOT NULL,
	`txtpkgsvc` TEXT NULL COLLATE 'utf8_unicode_ci',
	`txtctlsvc` VARCHAR(10) NULL COLLATE 'utf8_unicode_ci',
	`txtnmcfg` TEXT NULL COLLATE 'utf8_unicode_ci',
	`txtdircfg` TEXT NULL COLLATE 'utf8_unicode_ci',
	`txtdistroid` INT(3) NULL
);

CREATE TABLE IF NOT EXISTS `activity` (
  `activity_id` int(11) NOT NULL AUTO_INCREMENT,
  `activity_name` text COLLATE utf8_unicode_ci,
  `activity_serviceid` int(11) DEFAULT NULL,
  `activity_configid` int(11) DEFAULT NULL,
  `activity_serverid` int(11) DEFAULT NULL,
  `activity_userid` int(5) DEFAULT NULL,
  `activity_date` datetime DEFAULT NULL,
  PRIMARY KEY (`activity_id`),
  KEY `FKACT_SERVICES` (`activity_serviceid`),
  KEY `FKACT_CONFIG` (`activity_configid`),
  KEY `FKACT_SERVER` (`activity_serverid`),
  KEY `FKACT_USER` (`activity_userid`),
  CONSTRAINT `FKACT_CONFIG` FOREIGN KEY (`activity_configid`) REFERENCES `configurations` (`configurations_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FKACT_SERVER` FOREIGN KEY (`activity_serverid`) REFERENCES `servers` (`servers_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FKACT_SERVICES` FOREIGN KEY (`activity_serviceid`) REFERENCES `services` (`services_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FKACT_USER` FOREIGN KEY (`activity_userid`) REFERENCES `users` (`users_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
);
