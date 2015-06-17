-- phpMyAdmin SQL Dump
-- version 4.0.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 09, 2013 at 10:26 AM
-- Server version: 5.6.14-log
-- PHP Version: 5.4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `glpi084_phpunit`
--

-- --------------------------------------------------------

--
-- Table structure for table `glpi_plugin_monitoring_businessrulegroups`
--

CREATE TABLE IF NOT EXISTS `glpi_plugin_monitoring_businessrulegroups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `plugin_monitoring_servicescatalogs_id` int(11) NOT NULL DEFAULT '0',
  `operator` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `glpi_plugin_monitoring_businessrules`
--

CREATE TABLE IF NOT EXISTS `glpi_plugin_monitoring_businessrules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin_monitoring_businessrulegroups_id` int(11) NOT NULL DEFAULT '0',
  `plugin_monitoring_services_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `glpi_plugin_monitoring_checks`
--

CREATE TABLE IF NOT EXISTS `glpi_plugin_monitoring_checks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `max_check_attempts` int(2) NOT NULL DEFAULT '1',
  `check_interval` int(5) NOT NULL DEFAULT '1',
  `retry_interval` int(5) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `glpi_plugin_monitoring_checks`
--

INSERT INTO `glpi_plugin_monitoring_checks` (`id`, `name`, `max_check_attempts`, `check_interval`, `retry_interval`) VALUES
(1, '5 minutes / 5 retry', 5, 5, 1),
(2, '5 minutes / 3 retry', 3, 5, 1),
(3, '15 minutes / 3 retry', 3, 15, 1);

-- --------------------------------------------------------

--
-- Table structure for table `glpi_plugin_monitoring_commands`
--

CREATE TABLE IF NOT EXISTS `glpi_plugin_monitoring_commands` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=30 ;

--
-- Dumping data for table `glpi_plugin_monitoring_commands`
--

INSERT INTO `glpi_plugin_monitoring_commands` (`id`, `is_active`, `name`, `command_name`, `command_line`, `poller_tag`, `module_type`, `arguments`) VALUES
(1, 1, 'Simple tcp port check', 'check_tcp', '$PLUGINSDIR$/check_tcp  -H $HOSTADDRESS$ -p $ARG1$', NULL, NULL, NULL),
(2, 1, 'Simple web check', 'check_http', '$PLUGINSDIR$/check_http -H $HOSTADDRESS$', NULL, NULL, NULL),
(3, 1, 'Simple web check with SSL', 'check_https', '$PLUGINSDIR$/check_http -H $HOSTADDRESS$ -S', NULL, NULL, NULL),
(4, 1, 'Check a DNS entry', 'check_dig', '$PLUGINSDIR$/check_dig -H $HOSTADDRESS$ -l $ARG1$', NULL, NULL, '{"ARG1":"Machine name to lookup"}'),
(5, 1, 'Check a FTP service', 'check_ftp', '$PLUGINSDIR$/check_ftp -H $HOSTADDRESS$', NULL, NULL, NULL),
(6, 1, 'Ask a nrpe agent', 'check_nrpe', '$PLUGINSDIR$/check_nrpe -H $HOSTADDRESS$ -t 9 -u -c $ARG1$', NULL, NULL, NULL),
(7, 1, 'Simple ping command', 'check_ping', '$PLUGINSDIR$/check_ping -H $HOSTADDRESS$ -w 3000,100% -c 5000,100% -p 1', NULL, NULL, NULL),
(8, 1, 'Look at good ssh launch', 'check_ssh', '$PLUGINSDIR$/check_ssh -H $HOSTADDRESS$', NULL, NULL, NULL),
(9, 1, 'Look for good SMTP connexion', 'check_smtp', '$PLUGINSDIR$/check_smtp -H $HOSTADDRESS$', NULL, NULL, NULL),
(10, 1, 'Look for good SMTPS connexion', 'check_smtps', '$PLUGINSDIR$/check_smtp -H $HOSTADDRESS$ -S', NULL, NULL, NULL),
(11, 1, 'Look at a SSL certificate', 'check_https_certificate', '$PLUGINSDIR$/check_http -H $HOSTADDRESS$ -C 30', NULL, NULL, NULL),
(12, 1, 'Look at an HP printer state', 'check_hpjd', '$PLUGINSDIR$/check_hpjd -H $HOSTADDRESS$ -C $SNMPCOMMUNITYREAD$', NULL, NULL, NULL),
(13, 1, 'Look at Oracle connexion', 'check_oracle_listener', '$PLUGINSDIR$/check_oracle --tns $HOSTADDRESS$', NULL, NULL, NULL),
(14, 1, 'Look at MSSQL connexion', 'check_mssql_connexion', '$PLUGINSDIR$/check_mssql_health --hostname $HOSTADDRESS$ --username "$MSSQLUSER$" --password "$MSSQLPASSWORD$" --mode connection-time', NULL, NULL, NULL),
(15, 1, 'Ldap query', 'check_ldap', '$PLUGINSDIR$/check_ldap -H $HOSTADDRESS$ -b "$LDAPBASE$" -D $DOMAINUSER$ -P "$DOMAINPASSWORD$"', NULL, NULL, NULL),
(16, 1, 'Ldaps query', 'check_ldaps', '$PLUGINSDIR$/check_ldaps -H $HOSTADDRESS$ -b "$LDAPBASE$" -D $DOMAINUSER$ -P "$DOMAINPASSWORD$"', NULL, NULL, NULL),
(17, 1, 'Distant mysql check', 'check_mysql_connexion', '$PLUGINSDIR$/check_mysql -H $HOSTADDRESS$ -u $MYSQLUSER$ -p $MYSQLPASSWORD$', NULL, NULL, NULL),
(18, 1, 'ESX hosts checks', 'check_esx_host', '$PLUGINSDIR$/check_esx3.pl -D $VCENTER$ -H $HOSTADDRESS$ -u $VCENTERLOGIN$ -p $VCENTERPASSWORD$ l $ARG1$', NULL, NULL, NULL),
(19, 1, 'ESX VM checks', 'check_esx_vm', '$PLUGINSDIR$/check_esx3.pl -D $VCENTER$ -N $HOSTALIAS$ -u $VCENTERLOGIN$ -p $VCENTERLOGIN$ -l $ARG1$', NULL, NULL, NULL),
(20, 1, 'Check Linux host alive', 'check_linux_host_alive', '$PLUGINSDIR$/check_tcp -H $HOSTADDRESS$ -p 22 -t 3', NULL, NULL, NULL),
(21, 1, 'Check host alive', 'check_host_alive', '$PLUGINSDIR$/check_ping -H $HOSTADDRESS$ -w 1,50% -c 2,70% -p 1', NULL, NULL, NULL),
(22, 1, 'Check Windows host alive', 'check_windows_host_alive', '$PLUGINSDIR$/check_tcp -H $HOSTADDRESS$ -p 139 -t 3', NULL, NULL, NULL),
(23, 1, 'Check disk', 'check_disk', '$PLUGINSDIR$/check_disk -w $ARG1$ -c $ARG2$ -p $ARG3$', NULL, NULL, '{"ARG1":"INTEGER: WARNING status if less than INTEGER units of disk are free\\n\n         PERCENT%: WARNING status if less than PERCENT of disk space is free","ARG2":"INTEGER: CRITICAL status if less than INTEGER units of disk are free\\n\n         PERCENT%: CRITICAL status if less than PERCENT of disk space is free","ARG3":"Path or partition"}'),
(24, 1, 'Check local disk', 'check-host-alive', '$PLUGINSDIR$/check.sh $HOSTADDRESS$ -c $ARG1$ SERVICE $USER1$', NULL, NULL, NULL),
(25, 1, 'Business rules', 'bp_rule', '', NULL, NULL, NULL),
(26, 1, 'Check local cpu', 'check_cpu_usage', '$PLUGINSDIR$/check_cpu_usage -w $ARG1$ -c $ARG2$', NULL, NULL, '{"ARG1":"Percentage of CPU for warning","ARG2":"Percentage of CPU for critical"}'),
(27, 1, 'Check load', 'check_load', '$PLUGINSDIR$/check_load -r -w $ARG1$ -c $ARG2$', NULL, NULL, '{"ARG1":"WARNING status if load average exceeds WLOADn (WLOAD1,WLOAD5,WLOAD15)","ARG2":"CRITICAL status if load average exceed CLOADn (CLOAD1,CLOAD5,CLOAD15)"}'),
(28, 1, 'Check snmp', 'check_snmp', '$PLUGINSDIR$/check_snmp -H $HOSTADDRESS$ -P $ARG1$ -C $ARG2$ -o $ARG3$,$ARG4$,$ARG5$,$ARG6$,$ARG7$,$ARG8$,$ARG9$,$ARG10$', NULL, NULL, '{"ARG1":"SNMP protocol version (1|2c|3) [SNMP:version]","ARG2":"Community string for SNMP communication [SNMP:authentication]","ARG3":"oid [OID:ifinoctets]","ARG4":"oid [OID:ifoutoctets]","ARG5":"oid [OID:ifinerrors]","ARG6":"oid [OID:ifouterrors]","ARG7":"oid","ARG8":"oid","ARG9":"oid","ARG10":"oid"}'),
(29, 1, 'Check users connected', 'check_users', '$PLUGINSDIR$/check_users -w $ARG1$ -c $ARG2$', NULL, NULL, '{"ARG1":"Set WARNING status if more than INTEGER users are logged in","ARG2":"Set CRITICAL status if more than INTEGER users are logged in"}');

-- --------------------------------------------------------

--
-- Table structure for table `glpi_plugin_monitoring_commandtemplates`
--

CREATE TABLE IF NOT EXISTS `glpi_plugin_monitoring_commandtemplates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin_monitoring_commands_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `glpi_plugin_monitoring_components`
--

CREATE TABLE IF NOT EXISTS `glpi_plugin_monitoring_components` (
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
  `is_weathermap` tinyint(1) NOT NULL DEFAULT '0',
  `weathermap_regex_in` text COLLATE utf8_unicode_ci,
  `weathermap_regex_out` text COLLATE utf8_unicode_ci,
  `perfname` text COLLATE utf8_unicode_ci,
  `perfnameinvert` text COLLATE utf8_unicode_ci,
  `perfnamecolor` text COLLATE utf8_unicode_ci,
  `plugin_monitoring_eventhandlers_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `plugin_monitoring_commands_id` (`plugin_monitoring_commands_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `glpi_plugin_monitoring_components`
--

INSERT INTO `glpi_plugin_monitoring_components` (`id`, `name`, `plugin_monitoring_commands_id`, `arguments`, `plugin_monitoring_checks_id`, `active_checks_enabled`, `passive_checks_enabled`, `calendars_id`, `remotesystem`, `is_arguments`, `alias_command`, `graph_template`, `link`, `is_weathermap`, `weathermap_regex_in`, `weathermap_regex_out`, `perfname`, `perfnameinvert`, `perfnamecolor`, `plugin_monitoring_eventhandlers_id`) VALUES
(1, 'cpu', 26, NULL, 2, 1, 1, 2, '', 0, '', 'check_cpu_usage', NULL, 0, '', '', '{"0":"usage","1":"usage_warning","2":"usage_critical","3":"user","4":"cpu_system","usage":1,"usage_warning":1,"usage_critical":1,"user":1,"cpu_system":1}', NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `glpi_plugin_monitoring_componentscatalogs`
--

CREATE TABLE IF NOT EXISTS `glpi_plugin_monitoring_componentscatalogs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `is_recursive` tinyint(1) NOT NULL DEFAULT '0',
  `comment` text COLLATE utf8_unicode_ci,
  `notification_interval` int(4) NOT NULL DEFAULT '30',
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `glpi_plugin_monitoring_componentscatalogs`
--

INSERT INTO `glpi_plugin_monitoring_componentscatalogs` (`id`, `name`, `entities_id`, `is_recursive`, `comment`, `notification_interval`) VALUES
(1, 'cpu', 0, 0, '', 30);

-- --------------------------------------------------------

--
-- Table structure for table `glpi_plugin_monitoring_componentscatalogs_components`
--

CREATE TABLE IF NOT EXISTS `glpi_plugin_monitoring_componentscatalogs_components` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin_monitoring_componentscalalog_id` int(11) NOT NULL DEFAULT '0',
  `plugin_monitoring_components_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unicity` (`plugin_monitoring_componentscalalog_id`,`plugin_monitoring_components_id`),
  KEY `plugin_monitoring_componentscalalog_id` (`plugin_monitoring_componentscalalog_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `glpi_plugin_monitoring_componentscatalogs_components`
--

INSERT INTO `glpi_plugin_monitoring_componentscatalogs_components` (`id`, `plugin_monitoring_componentscalalog_id`, `plugin_monitoring_components_id`) VALUES
(1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `glpi_plugin_monitoring_componentscatalogs_hosts`
--

CREATE TABLE IF NOT EXISTS `glpi_plugin_monitoring_componentscatalogs_hosts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin_monitoring_componentscalalog_id` int(11) NOT NULL DEFAULT '0',
  `is_static` tinyint(1) NOT NULL DEFAULT '1',
  `items_id` int(11) NOT NULL DEFAULT '0',
  `itemtype` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `itemtype` (`itemtype`,`items_id`),
  KEY `plugin_monitoring_componentscalalog_id` (`plugin_monitoring_componentscalalog_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `glpi_plugin_monitoring_componentscatalogs_hosts`
--

INSERT INTO `glpi_plugin_monitoring_componentscatalogs_hosts` (`id`, `plugin_monitoring_componentscalalog_id`, `is_static`, `items_id`, `itemtype`) VALUES
(1, 1, 0, 1, 'Computer');

-- --------------------------------------------------------

--
-- Table structure for table `glpi_plugin_monitoring_componentscatalogs_rules`
--

CREATE TABLE IF NOT EXISTS `glpi_plugin_monitoring_componentscatalogs_rules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin_monitoring_componentscalalog_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `itemtype` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `condition` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `plugin_monitoring_componentscalalog_id` (`plugin_monitoring_componentscalalog_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `glpi_plugin_monitoring_componentscatalogs_rules`
--

INSERT INTO `glpi_plugin_monitoring_componentscatalogs_rules` (`id`, `plugin_monitoring_componentscalalog_id`, `name`, `itemtype`, `condition`) VALUES
(1, 1, 'pc', 'Computer', '{"field":["view"],"searchtype":["contains"],"contains":[""],"itemtype":"Computer","start":"0","_glpi_csrf_token":"0a10341f54b5350d3eb2ec4a3371ee1e"}');

-- --------------------------------------------------------

--
-- Table structure for table `glpi_plugin_monitoring_configs`
--

CREATE TABLE IF NOT EXISTS `glpi_plugin_monitoring_configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timezones` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '["0"]',
  `version` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `logretention` int(5) NOT NULL DEFAULT '30',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `glpi_plugin_monitoring_configs`
--

INSERT INTO `glpi_plugin_monitoring_configs` (`id`, `timezones`, `version`, `logretention`) VALUES
(1, '["0"]', '0.84+1.0', 30);

-- --------------------------------------------------------

--
-- Table structure for table `glpi_plugin_monitoring_contactgroups`
--

CREATE TABLE IF NOT EXISTS `glpi_plugin_monitoring_contactgroups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `glpi_plugin_monitoring_contactgroups_contactgroups`
--

CREATE TABLE IF NOT EXISTS `glpi_plugin_monitoring_contactgroups_contactgroups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin_monitoring_contactgroups_id_1` int(11) NOT NULL DEFAULT '0',
  `plugin_monitoring_contactgroups_id_2` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unicity` (`plugin_monitoring_contactgroups_id_1`,`plugin_monitoring_contactgroups_id_2`),
  KEY `plugin_monitoring_contactgroups_id_2` (`plugin_monitoring_contactgroups_id_2`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `glpi_plugin_monitoring_contacts`
--

CREATE TABLE IF NOT EXISTS `glpi_plugin_monitoring_contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `users_id` int(11) NOT NULL DEFAULT '0',
  `plugin_monitoring_contacttemplates_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `glpi_plugin_monitoring_contacts_contactgroups`
--

CREATE TABLE IF NOT EXISTS `glpi_plugin_monitoring_contacts_contactgroups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin_monitoring_contacts_id` int(11) NOT NULL DEFAULT '0',
  `plugin_monitoring_contactgroups_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unicity` (`plugin_monitoring_contacts_id`,`plugin_monitoring_contactgroups_id`),
  KEY `plugin_monitoring_contactgroups_id` (`plugin_monitoring_contactgroups_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `glpi_plugin_monitoring_contacts_items`
--

CREATE TABLE IF NOT EXISTS `glpi_plugin_monitoring_contacts_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `users_id` int(11) NOT NULL DEFAULT '0',
  `groups_id` int(11) NOT NULL DEFAULT '0',
  `items_id` int(11) NOT NULL DEFAULT '0',
  `itemtype` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `glpi_plugin_monitoring_contacttemplates`
--

CREATE TABLE IF NOT EXISTS `glpi_plugin_monitoring_contacttemplates` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `glpi_plugin_monitoring_displayviews`
--

CREATE TABLE IF NOT EXISTS `glpi_plugin_monitoring_displayviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `is_recursive` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `users_id` int(11) NOT NULL DEFAULT '0',
  `counter` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `in_central` tinyint(1) NOT NULL DEFAULT '0',
  `width` int(5) NOT NULL DEFAULT '950',
  `is_frontview` tinyint(1) NOT NULL DEFAULT '0',
  `comment` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `glpi_plugin_monitoring_displayviews_groups`
--

CREATE TABLE IF NOT EXISTS `glpi_plugin_monitoring_displayviews_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pluginmonitoringdisplayviews_id` int(11) NOT NULL DEFAULT '0',
  `groups_id` int(11) NOT NULL DEFAULT '0',
  `entities_id` int(11) NOT NULL DEFAULT '-1',
  `is_recursive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `pluginmonitoringdisplayviews_id` (`pluginmonitoringdisplayviews_id`),
  KEY `groups_id` (`groups_id`),
  KEY `entities_id` (`entities_id`),
  KEY `is_recursive` (`is_recursive`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `glpi_plugin_monitoring_displayviews_items`
--

CREATE TABLE IF NOT EXISTS `glpi_plugin_monitoring_displayviews_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin_monitoring_displayviews_id` int(11) NOT NULL DEFAULT '0',
  `x` int(5) NOT NULL DEFAULT '0',
  `y` int(5) NOT NULL DEFAULT '0',
  `items_id` int(11) NOT NULL DEFAULT '0',
  `itemtype` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `extra_infos` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `plugin_monitoring_displayviews_id` (`plugin_monitoring_displayviews_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `glpi_plugin_monitoring_displayviews_rules`
--

CREATE TABLE IF NOT EXISTS `glpi_plugin_monitoring_displayviews_rules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin_monitoring_displayviews_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `itemtype` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `condition` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `plugin_monitoring_displayviews_id` (`plugin_monitoring_displayviews_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `glpi_plugin_monitoring_displayviews_users`
--

CREATE TABLE IF NOT EXISTS `glpi_plugin_monitoring_displayviews_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pluginmonitoringdisplayviews_id` int(11) NOT NULL DEFAULT '0',
  `users_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `pluginmonitoringdisplayviews_id` (`pluginmonitoringdisplayviews_id`),
  KEY `groups_id` (`users_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `glpi_plugin_monitoring_entities`
--

CREATE TABLE IF NOT EXISTS `glpi_plugin_monitoring_entities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `tag` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `entities_id` (`entities_id`),
  KEY `tag` (`tag`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `glpi_plugin_monitoring_eventhandlers`
--

CREATE TABLE IF NOT EXISTS `glpi_plugin_monitoring_eventhandlers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `command_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `command_line` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `command_name` (`command_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `glpi_plugin_monitoring_hostaddresses`
--

CREATE TABLE IF NOT EXISTS `glpi_plugin_monitoring_hostaddresses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `items_id` int(11) NOT NULL DEFAULT '0',
  `itemtype` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `networkports_id` int(11) NOT NULL DEFAULT '0',
  `ipaddresses_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `glpi_plugin_monitoring_hostconfigs`
--

CREATE TABLE IF NOT EXISTS `glpi_plugin_monitoring_hostconfigs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `items_id` int(11) NOT NULL DEFAULT '0',
  `itemtype` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `plugin_monitoring_commands_id` int(11) NOT NULL DEFAULT '0',
  `plugin_monitoring_checks_id` int(11) NOT NULL DEFAULT '0',
  `calendars_id` int(11) NOT NULL DEFAULT '0',
  `plugin_monitoring_realms_id` int(11) NOT NULL DEFAULT '0',
  `computers_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `glpi_plugin_monitoring_hostconfigs`
--

INSERT INTO `glpi_plugin_monitoring_hostconfigs` (`id`, `items_id`, `itemtype`, `plugin_monitoring_commands_id`, `plugin_monitoring_checks_id`, `calendars_id`, `plugin_monitoring_realms_id`, `computers_id`) VALUES
(1, 0, 'Entity', 21, 1, 1, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `glpi_plugin_monitoring_hosts`
--

CREATE TABLE IF NOT EXISTS `glpi_plugin_monitoring_hosts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `items_id` int(11) NOT NULL DEFAULT '0',
  `itemtype` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `event` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_check` datetime DEFAULT NULL,
  `dependencies` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `glpi_plugin_monitoring_hosts`
--

INSERT INTO `glpi_plugin_monitoring_hosts` (`id`, `items_id`, `itemtype`, `event`, `state`, `state_type`, `last_check`, `dependencies`) VALUES
(1, 1, 'Computer', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `glpi_plugin_monitoring_logs`
--

CREATE TABLE IF NOT EXISTS `glpi_plugin_monitoring_logs` (
  `id` bigint(30) NOT NULL AUTO_INCREMENT,
  `date_mod` datetime DEFAULT NULL,
  `user_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `itemtype` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `items_id` int(11) NOT NULL DEFAULT '0',
  `action` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `glpi_plugin_monitoring_logs`
--

INSERT INTO `glpi_plugin_monitoring_logs` (`id`, `date_mod`, `user_name`, `itemtype`, `items_id`, `action`, `value`) VALUES
(1, '2013-12-09 10:22:48', NULL, 'PluginMonitoringService', 1, 'add', 'New service cpu for Ordinateur pc1');

-- --------------------------------------------------------

--
-- Table structure for table `glpi_plugin_monitoring_networkports`
--

CREATE TABLE IF NOT EXISTS `glpi_plugin_monitoring_networkports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `items_id` int(11) NOT NULL DEFAULT '0',
  `itemtype` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `networkports_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `glpi_plugin_monitoring_notificationcommands`
--

CREATE TABLE IF NOT EXISTS `glpi_plugin_monitoring_notificationcommands` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `command_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `command_line` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `glpi_plugin_monitoring_notificationcommands`
--

INSERT INTO `glpi_plugin_monitoring_notificationcommands` (`id`, `is_active`, `name`, `command_name`, `command_line`) VALUES
(1, 1, 'Host : notify by mail', 'notify-host-by-email', '$PLUGINSDIR$/sendmailhost.pl "$NOTIFICATIONTYPE$" "$HOSTNAME$" "$HOSTSTATE$" "$HOSTADDRESS$" "$HOSTOUTPUT$" "$SHORTDATETIME$" "$CONTACTEMAIL$"'),
(2, 1, 'Service : notify by mail (perl)', 'notify-service-by-email-perl', '$PLUGINSDIR$/sendmailservices.pl "$NOTIFICATIONTYPE$" "$SERVICEDESC$" "$HOSTALIAS$" "$HOSTADDRESS$" "$SERVICESTATE$" "$SHORTDATETIME$" "$SERVICEOUTPUT$" "$CONTACTEMAIL$" "$SERVICENOTESURL$"'),
(3, 1, 'Service : notify by mail (python)', 'notify-service-by-email-py', '$PLUGINSDIR$/sendmailservice.py -s "$SERVICEDESC$" -n "$SERVICESTATE$" -H "$HOSTALIAS$" -a "$HOSTADDRESS$" -i "$SHORTDATETIME$" -o "$SERVICEOUTPUT$" -t "$CONTACTEMAIL$" -r "$SERVICESTATE$"');

-- --------------------------------------------------------

--
-- Table structure for table `glpi_plugin_monitoring_profiles`
--

CREATE TABLE IF NOT EXISTS `glpi_plugin_monitoring_profiles` (
  `profiles_id` int(11) NOT NULL DEFAULT '0',
  `dashboard` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `servicescatalog` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `view` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `componentscatalog` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `viewshomepage` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `weathermap` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `component` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `command` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `config` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `check` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `allressources` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `restartshinken` char(1) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `glpi_plugin_monitoring_profiles`
--

INSERT INTO `glpi_plugin_monitoring_profiles` (`profiles_id`, `dashboard`, `servicescatalog`, `view`, `componentscatalog`, `viewshomepage`, `weathermap`, `component`, `command`, `config`, `check`, `allressources`, `restartshinken`) VALUES
(4, 'w', 'w', 'w', 'w', 'r', 'w', 'w', 'w', 'w', 'w', 'w', 'w');

-- --------------------------------------------------------

--
-- Table structure for table `glpi_plugin_monitoring_realms`
--

CREATE TABLE IF NOT EXISTS `glpi_plugin_monitoring_realms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  `date_mod` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `glpi_plugin_monitoring_realms`
--

INSERT INTO `glpi_plugin_monitoring_realms` (`id`, `name`, `comment`, `date_mod`) VALUES
(1, 'All', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `glpi_plugin_monitoring_rrdtooltemplates`
--

CREATE TABLE IF NOT EXISTS `glpi_plugin_monitoring_rrdtooltemplates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin_monitoring_commands_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `glpi_plugin_monitoring_servicedefs`
--

CREATE TABLE IF NOT EXISTS `glpi_plugin_monitoring_servicedefs` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `glpi_plugin_monitoring_serviceevents`
--

CREATE TABLE IF NOT EXISTS `glpi_plugin_monitoring_serviceevents` (
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
  `unavailability` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `plugin_monitoring_services_id` (`plugin_monitoring_services_id`),
  KEY `plugin_monitoring_services_id_2` (`plugin_monitoring_services_id`,`date`),
  KEY `unavailability` (`unavailability`,`state_type`,`plugin_monitoring_services_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `glpi_plugin_monitoring_servicegraphs`
--

CREATE TABLE IF NOT EXISTS `glpi_plugin_monitoring_servicegraphs` (
  `id` bigint(30) NOT NULL AUTO_INCREMENT,
  `plugin_monitoring_services_id` int(11) NOT NULL DEFAULT '0',
  `date` datetime DEFAULT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `type` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `plugin_monitoring_services_id` (`plugin_monitoring_services_id`,`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `glpi_plugin_monitoring_services`
--

CREATE TABLE IF NOT EXISTS `glpi_plugin_monitoring_services` (
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
  `networkports_id` int(11) NOT NULL DEFAULT '0',
  `is_acknowledged` tinyint(1) NOT NULL DEFAULT '0',
  `is_acknowledgeconfirmed` tinyint(1) NOT NULL DEFAULT '0',
  `acknowledge_comment` text COLLATE utf8_unicode_ci,
  `acknowledge_users_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `state` (`state`(50),`state_type`(50)),
  KEY `plugin_monitoring_componentscatalogs_hosts_id` (`plugin_monitoring_componentscatalogs_hosts_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `glpi_plugin_monitoring_services`
--

INSERT INTO `glpi_plugin_monitoring_services` (`id`, `entities_id`, `name`, `plugin_monitoring_components_id`, `plugin_monitoring_componentscatalogs_hosts_id`, `event`, `state`, `state_type`, `last_check`, `arguments`, `networkports_id`, `is_acknowledged`, `is_acknowledgeconfirmed`, `acknowledge_comment`, `acknowledge_users_id`) VALUES
(1, 0, 'cpu', 1, 1, NULL, 'WARNING', 'HARD', NULL, NULL, 0, 0, 0, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `glpi_plugin_monitoring_servicescatalogs`
--

CREATE TABLE IF NOT EXISTS `glpi_plugin_monitoring_servicescatalogs` (
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
  `is_acknowledged` tinyint(1) NOT NULL DEFAULT '0',
  `is_acknowledgeconfirmed` tinyint(1) NOT NULL DEFAULT '0',
  `acknowledge_comment` text COLLATE utf8_unicode_ci,
  `acknowledge_users_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `glpi_plugin_monitoring_shinkenwebservices`
--

CREATE TABLE IF NOT EXISTS `glpi_plugin_monitoring_shinkenwebservices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `action` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cnt` tinyint(2) NOT NULL DEFAULT '0',
  `fields_string` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `glpi_plugin_monitoring_tags`
--

CREATE TABLE IF NOT EXISTS `glpi_plugin_monitoring_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ip` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `glpi_plugin_monitoring_unavaibilities`
--

CREATE TABLE IF NOT EXISTS `glpi_plugin_monitoring_unavaibilities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin_monitoring_services_id` int(11) NOT NULL DEFAULT '0',
  `begin_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `duration` int(15) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `plugin_monitoring_services_id` (`plugin_monitoring_services_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `glpi_plugin_monitoring_weathermaplinks`
--

CREATE TABLE IF NOT EXISTS `glpi_plugin_monitoring_weathermaplinks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin_monitoring_weathermapnodes_id_1` int(11) NOT NULL DEFAULT '0',
  `plugin_monitoring_weathermapnodes_id_2` int(11) NOT NULL DEFAULT '0',
  `plugin_monitoring_services_id` int(11) NOT NULL DEFAULT '0',
  `bandwidth_in` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bandwidth_out` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `glpi_plugin_monitoring_weathermapnodes`
--

CREATE TABLE IF NOT EXISTS `glpi_plugin_monitoring_weathermapnodes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `plugin_monitoring_weathermaps_id` int(11) NOT NULL DEFAULT '0',
  `items_id` int(11) NOT NULL DEFAULT '0',
  `itemtype` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `x` smallint(6) NOT NULL DEFAULT '0',
  `y` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `glpi_plugin_monitoring_weathermaps`
--

CREATE TABLE IF NOT EXISTS `glpi_plugin_monitoring_weathermaps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `width` smallint(6) NOT NULL DEFAULT '0',
  `height` smallint(6) NOT NULL DEFAULT '0',
  `background` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
