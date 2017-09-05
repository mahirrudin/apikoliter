DROP TABLE IF EXISTS `activities`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` VIEW `activities` AS select `users`.`users_email` AS `usrmail`,`users`.`users_name` AS `usrname`,`activity`.`activity_date` AS `actdate`,`activity`.`activity_name` AS `actname`,`servers`.`servers_name` AS `svrname`,`servers`.`servers_hostname` AS `svrip`,`services`.`services_name` AS `svcname`,`configurations`.`configurations_id` AS `cfgid`,`services`.`services_configdir` AS `cfgdir`,`services`.`services_configname` AS `cfgname` from ((((`activity` left join `users` on((`activity`.`activity_userid` = `users`.`users_id`))) left join `servers` on((`activity`.`activity_serverid` = `servers`.`servers_id`))) left join `services` on((`activity`.`activity_serviceid` = `services`.`services_id`))) left join `configurations` on((`activity`.`activity_configid` = `configurations`.`configurations_id`))) ;

DROP TABLE IF EXISTS `configrecord`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` VIEW `configrecord` AS select `svcstatus`.`svcstatus_id` AS `txtidsvcs`,`configurations`.`configurations_id` AS `txtidcfg`,`servers`.`servers_name` AS `txtnmsvr`,`servers`.`servers_hostname` AS `txtipsvr`,`servers`.`servers_login` AS `txtloginsvr`,`servers`.`servers_password` AS `txtpswdsvr`,`servers`.`servers_port` AS `txtportsvr`,`services`.`services_name` AS `txtnmsvc`,`services`.`services_configdir` AS `txtdircfg`,`services`.`services_configname` AS `txtnmcfg`,`configurations`.`configurations_localfile` AS `txtlocalcfg`,`users`.`users_name` AS `txtnmusr`,`configurations`.`configurations_date` AS `txtdatecfg` from ((((`configurations` join `svcstatus` on((`configurations`.`configurations_svcstatus` = `svcstatus`.`svcstatus_id`))) join `services` on((`svcstatus`.`svcstatus_servicesid` = `services`.`services_id`))) join `servers` on((`svcstatus`.`svcstatus_serverid` = `servers`.`servers_id`))) join `users` on((`configurations`.`configurations_user` = `users`.`users_id`))) ;

DROP TABLE IF EXISTS `monitoring`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` VIEW `monitoring` AS select `svrstatus`.`svrstatus_serverid` AS `svrid`,`servers`.`servers_name` AS `svrname`,`servers`.`servers_hostname` AS `svrip`,`servers`.`servers_kernel` AS `svrkernel`,`servers`.`servers_procie` AS `svrprocie`,`distros`.`distro_name` AS `svrdistro`,`distros`.`distro_version` AS `svrdistrover`,`svrstatus`.`svrstatus_date` AS `statsdate`,`svrstatus`.`svrstatus_down` AS `statsdown`,`svrstatus`.`svrstatus_cpuusage` AS `cpuusage`,`svrstatus`.`svrstatus_ramtotal` AS `ramtotal`,`svrstatus`.`svrstatus_ramusage` AS `ramusage`,`svrstatus`.`svrstatus_hddtotal` AS `hddtotal`,`svrstatus`.`svrstatus_hddusage` AS `hddusage` from ((`svrstatus` left join `servers` on((`svrstatus`.`svrstatus_serverid` = `servers`.`servers_id`))) left join `distros` on((`servers`.`servers_distroid` = `distros`.`distro_id`))) where (`servers`.`servers_delete` = '0') order by `svrstatus`.`svrstatus_date` ;

DROP TABLE IF EXISTS `servicesinstalled`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` VIEW `servicesinstalled` AS select `svcstatus`.`svcstatus_id` AS `txtidsvcs`,`servers`.`servers_name` AS `txtnmsvr`,`services`.`services_name` AS `txtnmsvc`,`svcstatus`.`svcstatus_startup` AS `txtstartup`,`svcstatus`.`svcstatus_daemon` AS `txtdaemon`,`servers`.`servers_id` AS `txtidsvr`,`servers`.`servers_hostname` AS `txtipsvr`,`servers`.`servers_login` AS `txtloginsvr`,`servers`.`servers_password` AS `txtpswdsvr`,`servers`.`servers_port` AS `txtportsvr`,`services`.`services_id` AS `txtidsvc`,`services`.`services_pkg` AS `txtpkgsvc`,`services`.`services_ctl` AS `txtctlsvc`,`services`.`services_configname` AS `txtnmcfg`,`services`.`services_configdir` AS `txtdircfg`,`services`.`services_distroid` AS `txtdistroid` from ((`svcstatus` join `servers` on((`svcstatus`.`svcstatus_serverid` = `servers`.`servers_id`))) join `services` on((`svcstatus`.`svcstatus_servicesid` = `services`.`services_id`))) where ((`servers`.`servers_delete` = '0') and (`svcstatus`.`svcstatus_installed` = '1')) ;