DROP TABLE IF EXISTS `glpi_plugin_monitoring_servicescatalogs`;

CREATE TABLE `glpi_plugin_monitoring_servicescatalogs` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `name` varchar(255) DEFAULT NULL,
   `entities_id` int(11) NOT NULL DEFAULT '0',
   `is_recursive` tinyint(1) NOT NULL DEFAULT '0',
   `comment` text COLLATE utf8_unicode_ci,
   `last_check` datetime DEFAULT NULL,
   `state` varchar(255) COLLATE utf8_unicode_ci DEFAULT '0',
   `state_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT '0',
   PRIMARY KEY (`id`),
   KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_monitoring_componentscatalogs`;

CREATE TABLE `glpi_plugin_monitoring_componentscatalogs` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `name` varchar(255) DEFAULT NULL,
   `entities_id` int(11) NOT NULL DEFAULT '0',
   `is_recursive` tinyint(1) NOT NULL DEFAULT '0',
   `comment` text COLLATE utf8_unicode_ci,
   PRIMARY KEY (`id`),
   KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `glpi_plugin_monitoring_components`;

CREATE TABLE `glpi_plugin_monitoring_components` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `plugin_monitoring_commands_id` int(11) NOT NULL DEFAULT '0',
  `arguments` text COLLATE utf8_unicode_ci,
  `plugin_monitoring_checks_id` int(11) NOT NULL DEFAULT '0',
  `active_checks_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `passive_checks_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `calendars_id`  int(11) NOT NULL DEFAULT '0',
  `remotesystem` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_arguments` tinyint(1) NOT NULL DEFAULT '0',
  `alias_command` text COLLATE utf8_unicode_ci,
  `aliasperfdata_commands_id` int(11) NOT NULL DEFAULT '0',
  `link` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_monitoring_componentscatalogs_components`;

CREATE TABLE `glpi_plugin_monitoring_componentscatalogs_components` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin_monitoring_componentscalalog_id` int(11) NOT NULL DEFAULT '0',
  `plugin_monitoring_components_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unicity` (`plugin_monitoring_componentscalalog_id`,`plugin_monitoring_components_id`),
  KEY `plugin_monitoring_componentscalalog_id` (`plugin_monitoring_componentscalalog_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_monitoring_componentscatalogs_hosts`;

CREATE TABLE `glpi_plugin_monitoring_componentscatalogs_hosts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin_monitoring_componentscalalog_id` int(11) NOT NULL DEFAULT '0',
  `is_static` tinyint(1) NOT NULL DEFAULT '1',
  `items_id` int(11) NOT NULL DEFAULT '0',
  `itemtype` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_monitoring_componentscatalogs_rules`;

CREATE TABLE `glpi_plugin_monitoring_componentscatalogs_rules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin_monitoring_componentscalalog_id` int(11) NOT NULL DEFAULT '0',
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `is_recursive` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `itemtype` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `condition` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_monitoring_services`;

CREATE TABLE `glpi_plugin_monitoring_services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `plugin_monitoring_components_id` int(11) NOT NULL DEFAULT '0',
  `plugin_monitoring_componentscatalogs_hosts_id` int(11) NOT NULL DEFAULT '0',
  `event` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8_unicode_ci DEFAULT '0',
  `state_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT '0',
  `last_check` datetime DEFAULT NULL,
  `arguments` text COLLATE utf8_unicode_ci,
  `alias_command` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_monitoring_contacttemplates`;

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



DROP TABLE IF EXISTS `glpi_plugin_monitoring_contacts`;

CREATE TABLE `glpi_plugin_monitoring_contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `users_id` int(11) NOT NULL DEFAULT '0',
  `plugin_monitoring_contacttemplates_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_monitoring_contacts_items`;

CREATE TABLE `glpi_plugin_monitoring_contacts_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `users_id` int(11) NOT NULL DEFAULT '0',
  `groups_id` int(11) NOT NULL DEFAULT '0',
  `items_id` int(11) NOT NULL DEFAULT '0',
  `itemtype` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_monitoring_commandtemplates`;

CREATE TABLE `glpi_plugin_monitoring_commandtemplates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin_monitoring_commands_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) DEFAULT NULL,
  `key` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_monitoring_rrdtooltemplates`;

CREATE TABLE `glpi_plugin_monitoring_commandtemplates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin_monitoring_commands_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) DEFAULT NULL,
  `key` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_monitoring_configs`;

CREATE TABLE `glpi_plugin_monitoring_configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rrdtoolpath` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
































DROP TABLE IF EXISTS `glpi_plugin_monitoring_businessrules`;

CREATE TABLE `glpi_plugin_monitoring_businessrules` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `plugin_monitoring_businessrulegroups_id` int(11) NOT NULL DEFAULT '0',
   `plugin_monitoring_services_id` int(11) NOT NULL DEFAULT '0',
   PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_monitoring_businessrulegroups`;

CREATE TABLE `glpi_plugin_monitoring_businessrulegroups` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `name` varchar(255) DEFAULT NULL,
   `plugin_monitoring_servicescatalogs_id` int(11) NOT NULL DEFAULT '0',
   `operator` varchar(255) DEFAULT NULL,
   PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_monitoring_commands`;

CREATE TABLE `glpi_plugin_monitoring_commands` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `is_active` tinyint(1) NOT NULL DEFAULT '1',
   `name` varchar(255) DEFAULT NULL,
   `command_name` varchar(255) DEFAULT NULL,
   `command_line` text COLLATE utf8_unicode_ci,
   `poller_tag` varchar(255) DEFAULT NULL,
   `module_type` varchar(255) DEFAULT NULL,
   `regex` text COLLATE utf8_unicode_ci,
   `legend` text COLLATE utf8_unicode_ci,
   `unit` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `arguments` text COLLATE utf8_unicode_ci,
   PRIMARY KEY (`id`),
   KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_monitoring_notificationcommands`;

CREATE TABLE `glpi_plugin_monitoring_notificationcommands` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `is_active` tinyint(1) NOT NULL DEFAULT '1',
   `name` varchar(255) DEFAULT NULL,
   `command_name` varchar(255) DEFAULT NULL,
   `command_line` text COLLATE utf8_unicode_ci,
   PRIMARY KEY (`id`),
   KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;






DROP TABLE IF EXISTS `glpi_plugin_monitoring_contactgroups`;

CREATE TABLE `glpi_plugin_monitoring_contactgroups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_monitoring_contacts_contactgroups`;

CREATE TABLE `glpi_plugin_monitoring_contacts_contactgroups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin_monitoring_contacts_id` int(11) NOT NULL DEFAULT '0',
  `plugin_monitoring_contactgroups_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unicity` (`plugin_monitoring_contacts_id`,`plugin_monitoring_contactgroups_id`),
  KEY `plugin_monitoring_contactgroups_id` (`plugin_monitoring_contactgroups_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_monitoring_contactgroups_contactgroups`;

CREATE TABLE `glpi_plugin_monitoring_contactgroups_contactgroups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin_monitoring_contactgroups_id_1` int(11) NOT NULL DEFAULT '0',
  `plugin_monitoring_contactgroups_id_2` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unicity` (`plugin_monitoring_contactgroups_id_1`,`plugin_monitoring_contactgroups_id_2`),
  KEY `plugin_monitoring_contactgroups_id_2` (`plugin_monitoring_contactgroups_id_2`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;




DROP TABLE IF EXISTS `glpi_plugin_monitoring_checks`;

CREATE TABLE `glpi_plugin_monitoring_checks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `max_check_attempts` int(2) NOT NULL DEFAULT '1',
  `check_interval` int(5) NOT NULL DEFAULT '1',
  `retry_interval` int(5) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `glpi_plugin_monitoring_serviceevents`;

CREATE TABLE `glpi_plugin_monitoring_serviceevents` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `plugin_monitoring_services_id` int(11) NOT NULL DEFAULT '0',
  `date` datetime DEFAULT NULL,
  `event` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `perf_data` text COLLATE utf8_unicode_ci,
  `output` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8_unicode_ci DEFAULT '0',
  `state_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT '0',
  `latency` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `execution_time` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `plugin_monitoring_services_id` (`plugin_monitoring_services_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_monitoring_servicedefs`;

CREATE TABLE `glpi_plugin_monitoring_servicedefs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `plugin_monitoring_commands_id` int(11) NOT NULL DEFAULT '0',
  `arguments` text COLLATE utf8_unicode_ci,
  `plugin_monitoring_checks_id` int(11) NOT NULL DEFAULT '0',
  `active_checks_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `passive_checks_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `calendars_id`  int(11) NOT NULL DEFAULT '0',
  `remotesystem` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_arguments` tinyint(1) NOT NULL DEFAULT '0',
  `alias_command` text COLLATE utf8_unicode_ci,
  `aliasperfdata_commands_id` int(11) NOT NULL DEFAULT '0',
  `link` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `glpi_plugin_monitoring_servicedefs`;

CREATE TABLE `glpi_plugin_monitoring_servicedefs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `is_template` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `template_link` tinyint(1) NOT NULL DEFAULT '0',
  `plugin_monitoring_commands_id` int(11) NOT NULL DEFAULT '0',
  `arguments` text COLLATE utf8_unicode_ci,
  `plugin_monitoring_checks_id` int(11) NOT NULL DEFAULT '0',
  `active_checks_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `passive_checks_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `calendars_id`  int(11) NOT NULL DEFAULT '0',
  `remotesystem` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_arguments` tinyint(1) NOT NULL DEFAULT '0',
  `alias_command` text COLLATE utf8_unicode_ci,
  `aliasperfdata_commands_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;




DROP TABLE IF EXISTS `glpi_plugin_monitoring_servicesuggests`;

CREATE TABLE `glpi_plugin_monitoring_servicesuggests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `plugin_monitoring_commands_id` int(11) NOT NULL DEFAULT '0',
  `softwares_name` text COLLATE utf8_unicode_ci,
  `computers_services` text COLLATE utf8_unicode_ci,
  `link` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
