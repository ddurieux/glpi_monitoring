--
-- Table structure for table `glpi_plugin_monitoring_businessrulegroups`
--

DROP TABLE IF EXISTS `glpi_plugin_monitoring_businessrulegroups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_plugin_monitoring_businessrulegroups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `plugin_monitoring_servicescatalogs_id` int(11) NOT NULL DEFAULT '0',
  `operator` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_plugin_monitoring_businessrulegroups`
--

LOCK TABLES `glpi_plugin_monitoring_businessrulegroups` WRITE;
/*!40000 ALTER TABLE `glpi_plugin_monitoring_businessrulegroups` DISABLE KEYS */;
/*!40000 ALTER TABLE `glpi_plugin_monitoring_businessrulegroups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_plugin_monitoring_businessrules`
--

DROP TABLE IF EXISTS `glpi_plugin_monitoring_businessrules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_plugin_monitoring_businessrules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin_monitoring_businessrulegroups_id` int(11) NOT NULL DEFAULT '0',
  `plugin_monitoring_services_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_plugin_monitoring_businessrules`
--

LOCK TABLES `glpi_plugin_monitoring_businessrules` WRITE;
/*!40000 ALTER TABLE `glpi_plugin_monitoring_businessrules` DISABLE KEYS */;
/*!40000 ALTER TABLE `glpi_plugin_monitoring_businessrules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_plugin_monitoring_checks`
--

DROP TABLE IF EXISTS `glpi_plugin_monitoring_checks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_plugin_monitoring_checks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `max_check_attempts` int(2) NOT NULL DEFAULT '1',
  `check_interval` int(5) NOT NULL DEFAULT '1',
  `retry_interval` int(5) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_plugin_monitoring_checks`
--

LOCK TABLES `glpi_plugin_monitoring_checks` WRITE;
/*!40000 ALTER TABLE `glpi_plugin_monitoring_checks` DISABLE KEYS */;
INSERT INTO `glpi_plugin_monitoring_checks` VALUES (1,'5 minutes / 5 retry',5,5,1),(2,'5 minutes / 3 retry',3,5,1),(3,'15 minutes / 3 retry',3,15,1);
/*!40000 ALTER TABLE `glpi_plugin_monitoring_checks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_plugin_monitoring_commands`
--

DROP TABLE IF EXISTS `glpi_plugin_monitoring_commands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_plugin_monitoring_commands` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `command_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `command_line` text COLLATE utf8_unicode_ci,
  `poller_tag` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `module_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `arguments` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `command_name` (`command_name`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_plugin_monitoring_commands`
--

LOCK TABLES `glpi_plugin_monitoring_commands` WRITE;
/*!40000 ALTER TABLE `glpi_plugin_monitoring_commands` DISABLE KEYS */;
INSERT INTO `glpi_plugin_monitoring_commands` VALUES (1,1,'Simple tcp port check','check_tcp','$PLUGINSDIR$/check_tcp  -H $HOSTADDRESS$ -p $ARG1$',NULL,NULL,NULL),(2,1,'Simple web check','check_http','$PLUGINSDIR$/check_http -H $HOSTADDRESS$',NULL,NULL,NULL),(3,1,'Simple web check with SSL','check_https','$PLUGINSDIR$/check_http -H $HOSTADDRESS$ -S',NULL,NULL,NULL),(4,1,'Check a DNS entry','check_dig','$PLUGINSDIR$/check_dig -H $HOSTADDRESS$ -l $ARG1$',NULL,NULL,'{\"ARG1\":\"Machine name to lookup\"}'),(5,1,'Check a FTP service','check_ftp','$PLUGINSDIR$/check_ftp -H $HOSTADDRESS$',NULL,NULL,NULL),(6,1,'Ask a nrpe agent','check_nrpe','$PLUGINSDIR$/check_nrpe -H $HOSTADDRESS$ -t 9 -u -c $ARG1$',NULL,NULL,NULL),(7,1,'Simple ping command','check_ping','$PLUGINSDIR$/check_ping -H $HOSTADDRESS$ -w 3000,100% -c 5000,100% -p 1',NULL,NULL,NULL),(8,1,'Look at good ssh launch','check_ssh','$PLUGINSDIR$/check_ssh -H $HOSTADDRESS$',NULL,NULL,NULL),(9,1,'Look for good SMTP connexion','check_smtp','$PLUGINSDIR$/check_smtp -H $HOSTADDRESS$',NULL,NULL,NULL),(10,1,'Look for good SMTPS connexion','check_smtps','$PLUGINSDIR$/check_smtp -H $HOSTADDRESS$ -S',NULL,NULL,NULL),(11,1,'Look at a SSL certificate','check_https_certificate','$PLUGINSDIR$/check_http -H $HOSTADDRESS$ -C 30',NULL,NULL,NULL),(12,1,'Look at an HP printer state','check_hpjd','$PLUGINSDIR$/check_hpjd -H $HOSTADDRESS$ -C $SNMPCOMMUNITYREAD$',NULL,NULL,NULL),(13,1,'Look at Oracle connexion','check_oracle_listener','$PLUGINSDIR$/check_oracle --tns $HOSTADDRESS$',NULL,NULL,NULL),(14,1,'Look at MSSQL connexion','check_mssql_connexion','$PLUGINSDIR$/check_mssql_health --hostname $HOSTADDRESS$ --username \"$MSSQLUSER$\" --password \"$MSSQLPASSWORD$\" --mode connection-time',NULL,NULL,NULL),(15,1,'Ldap query','check_ldap','$PLUGINSDIR$/check_ldap -H $HOSTADDRESS$ -b \"$LDAPBASE$\" -D $DOMAINUSER$ -P \"$DOMAINPASSWORD$\"',NULL,NULL,NULL),(16,1,'Ldaps query','check_ldaps','$PLUGINSDIR$/check_ldaps -H $HOSTADDRESS$ -b \"$LDAPBASE$\" -D $DOMAINUSER$ -P \"$DOMAINPASSWORD$\"',NULL,NULL,NULL),(17,1,'Distant mysql check','check_mysql_connexion','$PLUGINSDIR$/check_mysql -H $HOSTADDRESS$ -u $MYSQLUSER$ -p $MYSQLPASSWORD$',NULL,NULL,NULL),(18,1,'ESX hosts checks','check_esx_host','$PLUGINSDIR$/check_esx3.pl -D $VCENTER$ -H $HOSTADDRESS$ -u $VCENTERLOGIN$ -p $VCENTERPASSWORD$ l $ARG1$',NULL,NULL,NULL),(19,1,'ESX VM checks','check_esx_vm','$PLUGINSDIR$/check_esx3.pl -D $VCENTER$ -N $HOSTALIAS$ -u $VCENTERLOGIN$ -p $VCENTERLOGIN$ -l $ARG1$',NULL,NULL,NULL),(20,1,'Check Linux host alive','check_linux_host_alive','$PLUGINSDIR$/check_tcp -H $HOSTADDRESS$ -p 22 -t 3',NULL,NULL,NULL),(21,1,'Check host alive','check_host_alive','$PLUGINSDIR$/check_ping -H $HOSTADDRESS$ -w 1,50% -c 2,70% -p 1',NULL,NULL,NULL),(22,1,'Check Windows host alive','check_windows_host_alive','$PLUGINSDIR$/check_tcp -H $HOSTADDRESS$ -p 139 -t 3',NULL,NULL,NULL),(23,1,'Check disk','check_disk','$PLUGINSDIR$/check_disk -w $ARG1$ -c $ARG2$ -p $ARG3$',NULL,NULL,'{\"ARG1\":\"INTEGER: WARNING status if less than INTEGER units of disk are free\\n\n         PERCENT%: WARNING status if less than PERCENT of disk space is free\",\"ARG2\":\"INTEGER: CRITICAL status if less than INTEGER units of disk are free\\n\n         PERCENT%: CRITICAL status if less than PERCENT of disk space is free\",\"ARG3\":\"Path or partition\"}'),(24,1,'Check local disk','check-host-alive','$PLUGINSDIR$/check.sh $HOSTADDRESS$ -c $ARG1$ SERVICE $USER1$',NULL,NULL,NULL),(25,1,'Business rules','bp_rule','',NULL,NULL,NULL),(26,1,'Check local cpu','check_cpu_usage','$PLUGINSDIR$/check_cpu_usage -w $ARG1$ -c $ARG2$',NULL,NULL,'{\"ARG1\":\"Percentage of CPU for warning\",\"ARG2\":\"Percentage of CPU for critical\"}'),(27,1,'Check load','check_load','$PLUGINSDIR$/check_load -r -w $ARG1$ -c $ARG2$',NULL,NULL,'{\"ARG1\":\"WARNING status if load average exceeds WLOADn (WLOAD1,WLOAD5,WLOAD15)\",\"ARG2\":\"CRITICAL status if load average exceed CLOADn (CLOAD1,CLOAD5,CLOAD15)\"}'),(28,1,'Check snmp','check_snmp','$PLUGINSDIR$/check_snmp -H $HOSTADDRESS$ -P $ARG1$ -C $ARG2$ -o $ARG3$,$ARG4$,$ARG5$,$ARG6$,$ARG7$,$ARG8$,$ARG9$,$ARG10$',NULL,NULL,'{\"ARG1\":\"SNMP protocol version (1|2c|3) [SNMP:version]\",\"ARG2\":\"Community string for SNMP communication [SNMP:authentication]\",\"ARG3\":\"oid [OID:ifinoctets]\",\"ARG4\":\"oid [OID:ifoutoctets]\",\"ARG5\":\"oid [OID:ifinerrors]\",\"ARG6\":\"oid [OID:ifouterrors]\",\"ARG7\":\"oid\",\"ARG8\":\"oid\",\"ARG9\":\"oid\",\"ARG10\":\"oid\"}'),(29,1,'Check users connected','check_users','$PLUGINSDIR$/check_users -w $ARG1$ -c $ARG2$',NULL,NULL,'{\"ARG1\":\"Set WARNING status if more than INTEGER users are logged in\",\"ARG2\":\"Set CRITICAL status if more than INTEGER users are logged in\"}');
/*!40000 ALTER TABLE `glpi_plugin_monitoring_commands` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_plugin_monitoring_commandtemplates`
--

DROP TABLE IF EXISTS `glpi_plugin_monitoring_commandtemplates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_plugin_monitoring_commandtemplates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin_monitoring_commands_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_plugin_monitoring_commandtemplates`
--

LOCK TABLES `glpi_plugin_monitoring_commandtemplates` WRITE;
/*!40000 ALTER TABLE `glpi_plugin_monitoring_commandtemplates` DISABLE KEYS */;
/*!40000 ALTER TABLE `glpi_plugin_monitoring_commandtemplates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_plugin_monitoring_components`
--

DROP TABLE IF EXISTS `glpi_plugin_monitoring_components`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_plugin_monitoring_components` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `plugin_monitoring_commands_id` int(11) NOT NULL DEFAULT '0',
  `arguments` text COLLATE utf8_unicode_ci,
  `plugin_monitoring_checks_id` int(11) NOT NULL DEFAULT '0',
  `active_checks_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `passive_checks_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `calendars_id` int(11) NOT NULL DEFAULT '0',
  `remotesystem` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_arguments` tinyint(1) NOT NULL DEFAULT '0',
  `alias_command` text COLLATE utf8_unicode_ci,
  `graph_template` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `link` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `plugin_monitoring_commands_id` (`plugin_monitoring_commands_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_plugin_monitoring_components`
--

LOCK TABLES `glpi_plugin_monitoring_components` WRITE;
/*!40000 ALTER TABLE `glpi_plugin_monitoring_components` DISABLE KEYS */;
/*!40000 ALTER TABLE `glpi_plugin_monitoring_components` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_plugin_monitoring_componentscatalogs`
--

DROP TABLE IF EXISTS `glpi_plugin_monitoring_componentscatalogs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_plugin_monitoring_componentscatalogs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `is_recursive` tinyint(1) NOT NULL DEFAULT '0',
  `comment` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_plugin_monitoring_componentscatalogs`
--

LOCK TABLES `glpi_plugin_monitoring_componentscatalogs` WRITE;
/*!40000 ALTER TABLE `glpi_plugin_monitoring_componentscatalogs` DISABLE KEYS */;
/*!40000 ALTER TABLE `glpi_plugin_monitoring_componentscatalogs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_plugin_monitoring_componentscatalogs_components`
--

DROP TABLE IF EXISTS `glpi_plugin_monitoring_componentscatalogs_components`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_plugin_monitoring_componentscatalogs_components` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin_monitoring_componentscalalog_id` int(11) NOT NULL DEFAULT '0',
  `plugin_monitoring_components_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unicity` (`plugin_monitoring_componentscalalog_id`,`plugin_monitoring_components_id`),
  KEY `plugin_monitoring_componentscalalog_id` (`plugin_monitoring_componentscalalog_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_plugin_monitoring_componentscatalogs_components`
--

LOCK TABLES `glpi_plugin_monitoring_componentscatalogs_components` WRITE;
/*!40000 ALTER TABLE `glpi_plugin_monitoring_componentscatalogs_components` DISABLE KEYS */;
/*!40000 ALTER TABLE `glpi_plugin_monitoring_componentscatalogs_components` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_plugin_monitoring_componentscatalogs_hosts`
--

DROP TABLE IF EXISTS `glpi_plugin_monitoring_componentscatalogs_hosts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_plugin_monitoring_componentscatalogs_hosts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin_monitoring_componentscalalog_id` int(11) NOT NULL DEFAULT '0',
  `is_static` tinyint(1) NOT NULL DEFAULT '1',
  `items_id` int(11) NOT NULL DEFAULT '0',
  `itemtype` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `itemtype` (`itemtype`,`items_id`),
  KEY `plugin_monitoring_componentscalalog_id` (`plugin_monitoring_componentscalalog_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_plugin_monitoring_componentscatalogs_hosts`
--

LOCK TABLES `glpi_plugin_monitoring_componentscatalogs_hosts` WRITE;
/*!40000 ALTER TABLE `glpi_plugin_monitoring_componentscatalogs_hosts` DISABLE KEYS */;
/*!40000 ALTER TABLE `glpi_plugin_monitoring_componentscatalogs_hosts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_plugin_monitoring_componentscatalogs_rules`
--

DROP TABLE IF EXISTS `glpi_plugin_monitoring_componentscatalogs_rules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_plugin_monitoring_componentscatalogs_rules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin_monitoring_componentscalalog_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `itemtype` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `condition` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `plugin_monitoring_componentscalalog_id` (`plugin_monitoring_componentscalalog_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_plugin_monitoring_componentscatalogs_rules`
--

LOCK TABLES `glpi_plugin_monitoring_componentscatalogs_rules` WRITE;
/*!40000 ALTER TABLE `glpi_plugin_monitoring_componentscatalogs_rules` DISABLE KEYS */;
/*!40000 ALTER TABLE `glpi_plugin_monitoring_componentscatalogs_rules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_plugin_monitoring_configs`
--

DROP TABLE IF EXISTS `glpi_plugin_monitoring_configs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_plugin_monitoring_configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rrdtoolpath` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `timezones` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '["0"]',
  `version` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_plugin_monitoring_configs`
--

LOCK TABLES `glpi_plugin_monitoring_configs` WRITE;
/*!40000 ALTER TABLE `glpi_plugin_monitoring_configs` DISABLE KEYS */;
/*!40000 ALTER TABLE `glpi_plugin_monitoring_configs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_plugin_monitoring_contactgroups`
--

DROP TABLE IF EXISTS `glpi_plugin_monitoring_contactgroups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_plugin_monitoring_contactgroups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_plugin_monitoring_contactgroups`
--

LOCK TABLES `glpi_plugin_monitoring_contactgroups` WRITE;
/*!40000 ALTER TABLE `glpi_plugin_monitoring_contactgroups` DISABLE KEYS */;
/*!40000 ALTER TABLE `glpi_plugin_monitoring_contactgroups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_plugin_monitoring_contactgroups_contactgroups`
--

DROP TABLE IF EXISTS `glpi_plugin_monitoring_contactgroups_contactgroups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_plugin_monitoring_contactgroups_contactgroups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin_monitoring_contactgroups_id_1` int(11) NOT NULL DEFAULT '0',
  `plugin_monitoring_contactgroups_id_2` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unicity` (`plugin_monitoring_contactgroups_id_1`,`plugin_monitoring_contactgroups_id_2`),
  KEY `plugin_monitoring_contactgroups_id_2` (`plugin_monitoring_contactgroups_id_2`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_plugin_monitoring_contactgroups_contactgroups`
--

LOCK TABLES `glpi_plugin_monitoring_contactgroups_contactgroups` WRITE;
/*!40000 ALTER TABLE `glpi_plugin_monitoring_contactgroups_contactgroups` DISABLE KEYS */;
/*!40000 ALTER TABLE `glpi_plugin_monitoring_contactgroups_contactgroups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_plugin_monitoring_contacts`
--

DROP TABLE IF EXISTS `glpi_plugin_monitoring_contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_plugin_monitoring_contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `users_id` int(11) NOT NULL DEFAULT '0',
  `plugin_monitoring_contacttemplates_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_plugin_monitoring_contacts`
--

LOCK TABLES `glpi_plugin_monitoring_contacts` WRITE;
/*!40000 ALTER TABLE `glpi_plugin_monitoring_contacts` DISABLE KEYS */;
/*!40000 ALTER TABLE `glpi_plugin_monitoring_contacts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_plugin_monitoring_contacts_contactgroups`
--

DROP TABLE IF EXISTS `glpi_plugin_monitoring_contacts_contactgroups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_plugin_monitoring_contacts_contactgroups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin_monitoring_contacts_id` int(11) NOT NULL DEFAULT '0',
  `plugin_monitoring_contactgroups_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unicity` (`plugin_monitoring_contacts_id`,`plugin_monitoring_contactgroups_id`),
  KEY `plugin_monitoring_contactgroups_id` (`plugin_monitoring_contactgroups_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_plugin_monitoring_contacts_contactgroups`
--

LOCK TABLES `glpi_plugin_monitoring_contacts_contactgroups` WRITE;
/*!40000 ALTER TABLE `glpi_plugin_monitoring_contacts_contactgroups` DISABLE KEYS */;
/*!40000 ALTER TABLE `glpi_plugin_monitoring_contacts_contactgroups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_plugin_monitoring_contacts_items`
--

DROP TABLE IF EXISTS `glpi_plugin_monitoring_contacts_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_plugin_monitoring_contacts_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `users_id` int(11) NOT NULL DEFAULT '0',
  `groups_id` int(11) NOT NULL DEFAULT '0',
  `items_id` int(11) NOT NULL DEFAULT '0',
  `itemtype` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_plugin_monitoring_contacts_items`
--

LOCK TABLES `glpi_plugin_monitoring_contacts_items` WRITE;
/*!40000 ALTER TABLE `glpi_plugin_monitoring_contacts_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `glpi_plugin_monitoring_contacts_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_plugin_monitoring_contacttemplates`
--

DROP TABLE IF EXISTS `glpi_plugin_monitoring_contacttemplates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_plugin_monitoring_contacttemplates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `host_notifications_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `service_notifications_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `service_notification_period` int(11) NOT NULL DEFAULT '0',
  `host_notification_period` int(11) NOT NULL DEFAULT '0',
  `service_notification_options_w` tinyint(1) NOT NULL DEFAULT '1',
  `service_notification_options_u` tinyint(1) NOT NULL DEFAULT '1',
  `service_notification_options_c` tinyint(1) NOT NULL DEFAULT '1',
  `service_notification_options_r` tinyint(1) NOT NULL DEFAULT '1',
  `service_notification_options_f` tinyint(1) NOT NULL DEFAULT '0',
  `service_notification_options_n` tinyint(1) NOT NULL DEFAULT '0',
  `host_notification_options_d` tinyint(1) NOT NULL DEFAULT '1',
  `host_notification_options_u` tinyint(1) NOT NULL DEFAULT '1',
  `host_notification_options_r` tinyint(1) NOT NULL DEFAULT '1',
  `host_notification_options_f` tinyint(1) NOT NULL DEFAULT '0',
  `host_notification_options_s` tinyint(1) NOT NULL DEFAULT '0',
  `host_notification_options_n` tinyint(1) NOT NULL DEFAULT '0',
  `service_notification_commands` int(11) NOT NULL DEFAULT '0',
  `host_notification_commands` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_plugin_monitoring_contacttemplates`
--

LOCK TABLES `glpi_plugin_monitoring_contacttemplates` WRITE;
/*!40000 ALTER TABLE `glpi_plugin_monitoring_contacttemplates` DISABLE KEYS */;
/*!40000 ALTER TABLE `glpi_plugin_monitoring_contacttemplates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_plugin_monitoring_entities`
--

DROP TABLE IF EXISTS `glpi_plugin_monitoring_entities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_plugin_monitoring_entities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `tag` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `entities_id` (`entities_id`),
  KEY `tag` (`tag`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_plugin_monitoring_entities`
--

LOCK TABLES `glpi_plugin_monitoring_entities` WRITE;
/*!40000 ALTER TABLE `glpi_plugin_monitoring_entities` DISABLE KEYS */;
/*!40000 ALTER TABLE `glpi_plugin_monitoring_entities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_plugin_monitoring_hostaddresses`
--

DROP TABLE IF EXISTS `glpi_plugin_monitoring_hostaddresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_plugin_monitoring_hostaddresses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `items_id` int(11) NOT NULL DEFAULT '0',
  `itemtype` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `networkports_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_plugin_monitoring_hostaddresses`
--

LOCK TABLES `glpi_plugin_monitoring_hostaddresses` WRITE;
/*!40000 ALTER TABLE `glpi_plugin_monitoring_hostaddresses` DISABLE KEYS */;
/*!40000 ALTER TABLE `glpi_plugin_monitoring_hostaddresses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_plugin_monitoring_hostconfigs`
--

DROP TABLE IF EXISTS `glpi_plugin_monitoring_hostconfigs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_plugin_monitoring_hostconfigs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `items_id` int(11) NOT NULL DEFAULT '0',
  `itemtype` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `plugin_monitoring_commands_id` int(11) NOT NULL DEFAULT '0',
  `plugin_monitoring_checks_id` int(11) NOT NULL DEFAULT '0',
  `calendars_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_plugin_monitoring_hostconfigs`
--

LOCK TABLES `glpi_plugin_monitoring_hostconfigs` WRITE;
/*!40000 ALTER TABLE `glpi_plugin_monitoring_hostconfigs` DISABLE KEYS */;
INSERT INTO `glpi_plugin_monitoring_hostconfigs` VALUES (1,0,'Entity',21,1,1);
/*!40000 ALTER TABLE `glpi_plugin_monitoring_hostconfigs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_plugin_monitoring_notificationcommands`
--

DROP TABLE IF EXISTS `glpi_plugin_monitoring_notificationcommands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_plugin_monitoring_notificationcommands` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `command_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `command_line` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_plugin_monitoring_notificationcommands`
--

LOCK TABLES `glpi_plugin_monitoring_notificationcommands` WRITE;
/*!40000 ALTER TABLE `glpi_plugin_monitoring_notificationcommands` DISABLE KEYS */;
INSERT INTO `glpi_plugin_monitoring_notificationcommands` VALUES (1,1,'Host : notify by mail','notify-host-by-email','$PLUGINSDIR$/sendmailhost.pl \"$NOTIFICATIONTYPE$\" \"$HOSTNAME$\" \"$HOSTSTATE$\" \"$HOSTADDRESS$\" \"$HOSTOUTPUT$\" \"$SHORTDATETIME$\" \"$CONTACTEMAIL$\"'),(2,1,'Service : notify by mail','notify-service-by-email','$PLUGINSDIR$/sendmailservices.pl \"$NOTIFICATIONTYPE$\" \"$SERVICEDESC$\" \"$HOSTALIAS$\" \"$HOSTADDRESS$\" \"$SERVICESTATE$\" \"$SHORTDATETIME$\" \"$SERVICEOUTPUT$\" \"$CONTACTEMAIL$\" \"$SERVICENOTESURL$\"');
/*!40000 ALTER TABLE `glpi_plugin_monitoring_notificationcommands` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_plugin_monitoring_rrdtooltemplates`
--

DROP TABLE IF EXISTS `glpi_plugin_monitoring_rrdtooltemplates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_plugin_monitoring_rrdtooltemplates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin_monitoring_commands_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_plugin_monitoring_rrdtooltemplates`
--

LOCK TABLES `glpi_plugin_monitoring_rrdtooltemplates` WRITE;
/*!40000 ALTER TABLE `glpi_plugin_monitoring_rrdtooltemplates` DISABLE KEYS */;
/*!40000 ALTER TABLE `glpi_plugin_monitoring_rrdtooltemplates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_plugin_monitoring_servicedefs`
--

DROP TABLE IF EXISTS `glpi_plugin_monitoring_servicedefs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_plugin_monitoring_servicedefs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `plugin_monitoring_commands_id` int(11) NOT NULL DEFAULT '0',
  `arguments` text COLLATE utf8_unicode_ci,
  `plugin_monitoring_checks_id` int(11) NOT NULL DEFAULT '0',
  `active_checks_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `passive_checks_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `calendars_id` int(11) NOT NULL DEFAULT '0',
  `remotesystem` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_arguments` tinyint(1) NOT NULL DEFAULT '0',
  `alias_command` text COLLATE utf8_unicode_ci,
  `aliasperfdata_commands_id` int(11) NOT NULL DEFAULT '0',
  `link` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_plugin_monitoring_servicedefs`
--

LOCK TABLES `glpi_plugin_monitoring_servicedefs` WRITE;
/*!40000 ALTER TABLE `glpi_plugin_monitoring_servicedefs` DISABLE KEYS */;
/*!40000 ALTER TABLE `glpi_plugin_monitoring_servicedefs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_plugin_monitoring_serviceevents`
--

DROP TABLE IF EXISTS `glpi_plugin_monitoring_serviceevents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_plugin_monitoring_serviceevents` (
  `id` bigint(30) NOT NULL AUTO_INCREMENT,
  `plugin_monitoring_services_id` int(11) NOT NULL DEFAULT '0',
  `date` datetime DEFAULT NULL,
  `event` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `perf_data` text COLLATE utf8_unicode_ci,
  `output` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `state_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `latency` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `execution_time` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `plugin_monitoring_services_id` (`plugin_monitoring_services_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_plugin_monitoring_serviceevents`
--

LOCK TABLES `glpi_plugin_monitoring_serviceevents` WRITE;
/*!40000 ALTER TABLE `glpi_plugin_monitoring_serviceevents` DISABLE KEYS */;
/*!40000 ALTER TABLE `glpi_plugin_monitoring_serviceevents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_plugin_monitoring_services`
--

DROP TABLE IF EXISTS `glpi_plugin_monitoring_services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_plugin_monitoring_services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `plugin_monitoring_components_id` int(11) NOT NULL DEFAULT '0',
  `plugin_monitoring_componentscatalogs_hosts_id` int(11) NOT NULL DEFAULT '0',
  `event` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_check` datetime DEFAULT NULL,
  `arguments` text COLLATE utf8_unicode_ci,
  `alias_command` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `state` (`state`(50),`state_type`(50)),
  KEY `plugin_monitoring_componentscatalogs_hosts_id` (`plugin_monitoring_componentscatalogs_hosts_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_plugin_monitoring_services`
--

LOCK TABLES `glpi_plugin_monitoring_services` WRITE;
/*!40000 ALTER TABLE `glpi_plugin_monitoring_services` DISABLE KEYS */;
/*!40000 ALTER TABLE `glpi_plugin_monitoring_services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_plugin_monitoring_servicescatalogs`
--

DROP TABLE IF EXISTS `glpi_plugin_monitoring_servicescatalogs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_plugin_monitoring_servicescatalogs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `is_recursive` tinyint(1) NOT NULL DEFAULT '0',
  `comment` text COLLATE utf8_unicode_ci,
  `last_check` datetime DEFAULT NULL,
  `state` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `state_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `plugin_monitoring_checks_id` int(11) NOT NULL DEFAULT '0',
  `calendars_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_plugin_monitoring_servicescatalogs`
--

LOCK TABLES `glpi_plugin_monitoring_servicescatalogs` WRITE;
/*!40000 ALTER TABLE `glpi_plugin_monitoring_servicescatalogs` DISABLE KEYS */;
/*!40000 ALTER TABLE `glpi_plugin_monitoring_servicescatalogs` ENABLE KEYS */;
UNLOCK TABLES;
