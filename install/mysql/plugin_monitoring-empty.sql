DROP TABLE IF EXISTS `glpi_plugin_monitoring_servicescatalogs`;

CREATE TABLE `glpi_plugin_monitoring_servicescatalogs` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `name` varchar(255) DEFAULT NULL,
   `entities_id` int(11) NOT NULL DEFAULT '0',
   `is_recursive` tinyint(1) NOT NULL DEFAULT '0',
   `comment` text DEFAULT NULL COLLATE utf8_unicode_ci,
   `last_check` datetime DEFAULT NULL,
   `event` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `state` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
   `state_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
   `plugin_monitoring_checks_id` int(11) NOT NULL DEFAULT '0',
   `calendars_id` int(11) NOT NULL DEFAULT '0',
   `is_acknowledged` tinyint(1) NOT NULL DEFAULT '0',
   `is_acknowledgeconfirmed` tinyint(1) NOT NULL DEFAULT '0',
   `acknowledge_comment` text DEFAULT NULL COLLATE utf8_unicode_ci,
   `acknowledge_users_id` int(11) NOT NULL DEFAULT '0',
   `notification_interval` int(4) NOT NULL DEFAULT '30',
   `business_priority` tinyint(1) NOT NULL DEFAULT '1',
   PRIMARY KEY (`id`),
   KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_monitoring_componentscatalogs`;

CREATE TABLE `glpi_plugin_monitoring_componentscatalogs` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `name` varchar(255) DEFAULT NULL,
   `entities_id` int(11) NOT NULL DEFAULT '0',
   `is_recursive` tinyint(1) NOT NULL DEFAULT '0',
   `comment` text DEFAULT NULL COLLATE utf8_unicode_ci,
   `notification_interval` int(4) NOT NULL DEFAULT '30',
   PRIMARY KEY (`id`),
   KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_monitoring_components`;

CREATE TABLE `glpi_plugin_monitoring_components` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `plugin_monitoring_commands_id` int(11) NOT NULL DEFAULT '0',
   `arguments` text DEFAULT NULL COLLATE utf8_unicode_ci,
   `plugin_monitoring_checks_id` int(11) NOT NULL DEFAULT '0',
   `active_checks_enabled` tinyint(1) NOT NULL DEFAULT '1',
   `passive_checks_enabled` tinyint(1) NOT NULL DEFAULT '1',
   `calendars_id` int(11) NOT NULL DEFAULT '0',
   `remotesystem` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `is_arguments` tinyint(1) NOT NULL DEFAULT '0',
   `alias_command` text DEFAULT NULL COLLATE utf8_unicode_ci,
   `graph_template` int(11) NOT NULL DEFAULT '0',
   `link` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
   `is_weathermap` tinyint(1) NOT NULL DEFAULT '0',
   `weathermap_regex_in` text DEFAULT NULL COLLATE utf8_unicode_ci,
   `weathermap_regex_out` text DEFAULT NULL COLLATE utf8_unicode_ci,
   `perfname` text DEFAULT NULL COLLATE utf8_unicode_ci,
   `perfnameinvert` text DEFAULT NULL COLLATE utf8_unicode_ci,
   `perfnamecolor` text DEFAULT NULL COLLATE utf8_unicode_ci,
   `plugin_monitoring_eventhandlers_id` int(11) NOT NULL DEFAULT '0',
   `freshness_count` int(6) NOT NULL DEFAULT '0',
   `freshness_type` varchar(255) DEFAULT 'seconds',
   PRIMARY KEY (`id`),
   KEY `plugin_monitoring_commands_id` (`plugin_monitoring_commands_id`)
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
  PRIMARY KEY (`id`),
  KEY `itemtype` (`itemtype`,`items_id`),
  KEY `plugin_monitoring_componentscalalog_id` (`plugin_monitoring_componentscalalog_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_monitoring_componentscatalogs_rules`;

CREATE TABLE `glpi_plugin_monitoring_componentscatalogs_rules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin_monitoring_componentscalalog_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `itemtype` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `condition` text DEFAULT NULL COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `plugin_monitoring_componentscalalog_id` (`plugin_monitoring_componentscalalog_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_monitoring_services`;

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
  `arguments` text DEFAULT NULL COLLATE utf8_unicode_ci,
  `networkports_id` int(11) NOT NULL DEFAULT '0',
  `is_acknowledged` tinyint(1) NOT NULL DEFAULT '0',
  `is_acknowledgeconfirmed` tinyint(1) NOT NULL DEFAULT '0',
  `acknowledge_comment` text DEFAULT NULL COLLATE utf8_unicode_ci,
  `acknowledge_users_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `state` (`state`(50),`state_type`(50)),
  KEY `plugin_monitoring_componentscatalogs_hosts_id` (`plugin_monitoring_componentscatalogs_hosts_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_monitoring_servicegraphs`;

CREATE TABLE IF NOT EXISTS `glpi_plugin_monitoring_servicegraphs` (
  `id` bigint(30) NOT NULL AUTO_INCREMENT,
  `plugin_monitoring_services_id` int(11) NOT NULL DEFAULT '0',
  `date` datetime DEFAULT NULL,
  `data` text DEFAULT NULL COLLATE utf8_unicode_ci,
  `type` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `plugin_monitoring_services_id` (`plugin_monitoring_services_id`,`type`)
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
  `entities_id` int(11) NOT NULL DEFAULT '0',
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

CREATE TABLE `glpi_plugin_monitoring_rrdtooltemplates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin_monitoring_commands_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) DEFAULT NULL,
  `key` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_monitoring_configs`;

CREATE TABLE `glpi_plugin_monitoring_configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timezones` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '["0"]',
  `version` varchar(255) DEFAULT NULL,
  `logretention` int(5) NOT NULL DEFAULT '30',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_monitoring_displayviews`;

CREATE TABLE `glpi_plugin_monitoring_displayviews` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `name` varchar(255) DEFAULT NULL,
   `entities_id` int(11) NOT NULL DEFAULT '0',
   `is_recursive` tinyint(1) NOT NULL DEFAULT '0',
   `is_active` tinyint(1) NOT NULL DEFAULT '0',
   `users_id` int(11) NOT NULL DEFAULT '0',
   `counter` varchar(255) DEFAULT NULL,
   `in_central` tinyint(1) NOT NULL DEFAULT '0',
   `width` int(5) NOT NULL DEFAULT '950',
   `is_frontview` tinyint(1) NOT NULL DEFAULT '0',
   `comment` text DEFAULT NULL COLLATE utf8_unicode_ci,
   PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_monitoring_displayviews_groups`;

CREATE TABLE `glpi_plugin_monitoring_displayviews_groups` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_monitoring_displayviews_users`;

CREATE TABLE `glpi_plugin_monitoring_displayviews_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pluginmonitoringdisplayviews_id` int(11) NOT NULL DEFAULT '0',
  `users_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `pluginmonitoringdisplayviews_id` (`pluginmonitoringdisplayviews_id`),
  KEY `groups_id` (`users_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_monitoring_displayviews_items`;

CREATE TABLE `glpi_plugin_monitoring_displayviews_items` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `plugin_monitoring_displayviews_id` int(11) NOT NULL DEFAULT '0',
   `x` int(5) NOT NULL DEFAULT '0',
   `y` int(5) NOT NULL DEFAULT '0',
   `items_id` int(11) NOT NULL DEFAULT '0',
   `itemtype` varchar(100) DEFAULT NULL,
   `extra_infos` varchar(255) DEFAULT NULL,
   PRIMARY KEY (`id`),
   KEY `plugin_monitoring_displayviews_id` (`plugin_monitoring_displayviews_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_monitoring_displayviews_rules`;

CREATE TABLE `glpi_plugin_monitoring_displayviews_rules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin_monitoring_displayviews_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `itemtype` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `condition` text DEFAULT NULL COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `plugin_monitoring_displayviews_id` (`plugin_monitoring_displayviews_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_monitoring_entities`;

CREATE TABLE `glpi_plugin_monitoring_entities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `tag` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `entities_id` (`entities_id`),
  KEY `tag` (`tag`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_monitoring_hostaddresses`;

CREATE TABLE `glpi_plugin_monitoring_hostaddresses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `items_id` int(11) NOT NULL DEFAULT '0',
  `itemtype` varchar(100) DEFAULT NULL,
  `networkports_id` int(11) NOT NULL DEFAULT '0',
  `ipaddresses_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_monitoring_hostconfigs`;

CREATE TABLE `glpi_plugin_monitoring_hostconfigs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `items_id` int(11) NOT NULL DEFAULT '0',
  `itemtype` varchar(100) DEFAULT NULL,
  `plugin_monitoring_components_id` int(11) NOT NULL DEFAULT '0',
  `plugin_monitoring_realms_id` int(11) NOT NULL DEFAULT '0',
  `computers_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_monitoring_hosts`;

CREATE TABLE `glpi_plugin_monitoring_hosts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `items_id` int(11) NOT NULL DEFAULT '0',
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `itemtype` varchar(100) DEFAULT NULL,
  `event` varchar(512) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_check` datetime DEFAULT NULL,
  `dependencies` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `perf_data` text DEFAULT NULL COLLATE utf8_unicode_ci,
  `output` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `latency` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `execution_time` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_monitoring_logs`;

CREATE TABLE `glpi_plugin_monitoring_logs` (
  `id` bigint(30) NOT NULL AUTO_INCREMENT,
  `date_mod` datetime DEFAULT NULL,
  `user_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `itemtype` varchar(100) DEFAULT NULL,
  `items_id` int(11) NOT NULL DEFAULT '0',
  `action` varchar(100) DEFAULT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_monitoring_networkports`;

CREATE TABLE `glpi_plugin_monitoring_networkports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `items_id` int(11) NOT NULL DEFAULT '0',
  `itemtype` varchar(100) DEFAULT NULL,
  `networkports_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_monitoring_realms`;

CREATE TABLE  `glpi_plugin_monitoring_realms` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `name` varchar(255) DEFAULT NULL,
   `comment` text DEFAULT NULL COLLATE utf8_unicode_ci,
   `date_mod` datetime DEFAULT NULL,
   PRIMARY KEY (`id`)
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1;



DROP TABLE IF EXISTS `glpi_plugin_monitoring_serviceevents`;

CREATE TABLE `glpi_plugin_monitoring_serviceevents` (
  `id` bigint(30) NOT NULL AUTO_INCREMENT,
  `plugin_monitoring_services_id` int(11) NOT NULL DEFAULT '0',
  `date` datetime DEFAULT NULL,
  `event` varchar(512) COLLATE utf8_unicode_ci DEFAULT NULL,
  `perf_data` text DEFAULT NULL COLLATE utf8_unicode_ci,
  `output` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `state_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `latency` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `execution_time` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `unavailability` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `plugin_monitoring_services_id` (`plugin_monitoring_services_id`),
  KEY `plugin_monitoring_services_id_2` (`plugin_monitoring_services_id`,`date`),
  KEY `unavailability` (`unavailability`,`state_type`,`plugin_monitoring_services_id`),
  KEY `plugin_monitoring_services_id_3` (`plugin_monitoring_services_id`,`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_monitoring_commands`;

CREATE TABLE `glpi_plugin_monitoring_commands` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `is_active` tinyint(1) NOT NULL DEFAULT '1',
   `name` varchar(255) DEFAULT NULL,
   `command_name` varchar(255) DEFAULT NULL,
   `command_line` text DEFAULT NULL COLLATE utf8_unicode_ci,
   `poller_tag` varchar(255) DEFAULT NULL,
   `module_type` varchar(255) DEFAULT NULL,
   `arguments` text DEFAULT NULL COLLATE utf8_unicode_ci,
   PRIMARY KEY (`id`),
   KEY `name` (`name`),
   KEY `command_name` (`command_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_monitoring_checks`;

CREATE TABLE `glpi_plugin_monitoring_checks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `max_check_attempts` int(2) NOT NULL DEFAULT '1',
  `check_interval` int(5) NOT NULL DEFAULT '1',
  `retry_interval` int(5) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_monitoring_businessrules`;

CREATE TABLE `glpi_plugin_monitoring_businessrules` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `plugin_monitoring_businessrulegroups_id` int(11) NOT NULL DEFAULT '0',
   `plugin_monitoring_services_id` int(11) NOT NULL DEFAULT '0',
   `is_dynamic` tinyint(1) NOT NULL DEFAULT '0',
   PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_monitoring_businessrules_components`;

CREATE TABLE `glpi_plugin_monitoring_businessrules_components` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `plugin_monitoring_businessrulegroups_id` int(11) NOT NULL DEFAULT '0',
   `plugin_monitoring_componentscatalogs_components_id` int(11) NOT NULL DEFAULT '0',
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



DROP TABLE IF EXISTS `glpi_plugin_monitoring_eventhandlers`;

CREATE TABLE `glpi_plugin_monitoring_eventhandlers` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `is_active` tinyint(1) NOT NULL DEFAULT '1',
   `name` varchar(255) DEFAULT NULL,
   `command_name` varchar(255) DEFAULT NULL,
   `command_line` text DEFAULT NULL COLLATE utf8_unicode_ci,
   PRIMARY KEY (`id`),
   KEY `name` (`name`),
   KEY `command_name` (`command_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_monitoring_notificationcommands`;

CREATE TABLE `glpi_plugin_monitoring_notificationcommands` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `is_active` tinyint(1) NOT NULL DEFAULT '1',
   `name` varchar(255) DEFAULT NULL,
   `command_name` varchar(255) DEFAULT NULL,
   `command_line` text DEFAULT NULL COLLATE utf8_unicode_ci,
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



DROP TABLE IF EXISTS `glpi_plugin_monitoring_profiles`;

CREATE TABLE `glpi_plugin_monitoring_profiles` (
  `profiles_id` int(11) NOT NULL DEFAULT '0',
  `config` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `config_views` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `config_services_catalogs` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `config_components_catalogs` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `config_weathermap` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dashboard` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dashboard_system_status` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dashboard_hosts_status` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dashboard_all_ressources` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dashboard_views` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dashboard_services_catalogs` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dashboard_components_catalogs` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `homepage` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `homepage_views` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `homepage_services_catalogs` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `homepage_components_catalogs` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `homepage_system_status` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `homepage_hosts_status` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `homepage_all_ressources` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `acknowledge` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `restartshinken` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `host_command` char(1) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_monitoring_servicedefs`;

CREATE TABLE `glpi_plugin_monitoring_servicedefs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `plugin_monitoring_commands_id` int(11) NOT NULL DEFAULT '0',
  `arguments` text DEFAULT NULL COLLATE utf8_unicode_ci,
  `plugin_monitoring_checks_id` int(11) NOT NULL DEFAULT '0',
  `active_checks_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `passive_checks_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `calendars_id` int(11) NOT NULL DEFAULT '0',
  `remotesystem` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_arguments` tinyint(1) NOT NULL DEFAULT '0',
  `alias_command` text DEFAULT NULL COLLATE utf8_unicode_ci,
  `aliasperfdata_commands_id` int(11) NOT NULL DEFAULT '0',
  `link` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_monitoring_unavaibilities`;

CREATE TABLE `glpi_plugin_monitoring_unavaibilities` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `plugin_monitoring_services_id` int(11) NOT NULL DEFAULT '0',
   `begin_date` datetime DEFAULT NULL,
   `end_date` datetime DEFAULT NULL,
   `duration` int(15) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `plugin_monitoring_services_id` (`plugin_monitoring_services_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_monitoring_weathermaps`;

CREATE TABLE `glpi_plugin_monitoring_weathermaps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `width` smallint(6) NOT NULL DEFAULT '0',
  `height` smallint(6) NOT NULL DEFAULT '0',
  `background` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_monitoring_weathermapnodes`;

CREATE TABLE `glpi_plugin_monitoring_weathermapnodes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `plugin_monitoring_weathermaps_id` int(11) NOT NULL DEFAULT '0',
  `items_id` int(11) NOT NULL DEFAULT '0',
  `itemtype` varchar(100) DEFAULT NULL,
  `x` smallint(6) NOT NULL DEFAULT '0',
  `y` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_monitoring_weathermaplinks`;

CREATE TABLE `glpi_plugin_monitoring_weathermaplinks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin_monitoring_weathermapnodes_id_1` int(11) NOT NULL DEFAULT '0',
  `plugin_monitoring_weathermapnodes_id_2` int(11) NOT NULL DEFAULT '0',
  `plugin_monitoring_services_id` int(11) NOT NULL DEFAULT '0',
  `bandwidth_in` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bandwidth_out` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_plugin_monitoring_realms`
   (`id` ,`name` ,`comment` ,`date_mod`) VALUES (NULL , 'All', NULL , NULL);



DROP TABLE IF EXISTS `glpi_plugin_monitoring_shinkenwebservices`;

CREATE TABLE `glpi_plugin_monitoring_shinkenwebservices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `action` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cnt` tinyint(2) NOT NULL DEFAULT '0',
  `fields_string` text DEFAULT NULL COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_monitoring_tags`;

CREATE TABLE `glpi_plugin_monitoring_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ip` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `iplock` tinyint(1) NOT NULL DEFAULT '0',
  `username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_monitoring_perfdatas`;

CREATE TABLE `glpi_plugin_monitoring_perfdatas` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `name` varchar(255) DEFAULT NULL,
   `perfdata` text DEFAULT NULL COLLATE utf8_unicode_ci,
   PRIMARY KEY (`id`),
   KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_monitoring_perfdatadetails`;

CREATE TABLE `glpi_plugin_monitoring_perfdatadetails` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `name` varchar(255) DEFAULT NULL,
   `dynamic_name` tinyint(1) NOT NULL DEFAULT '0',
   `plugin_monitoring_perfdatas_id` int(11) NOT NULL DEFAULT '0',
   `position` int(2) NOT NULL DEFAULT '0',
   `dsname_num` tinyint(1) NOT NULL DEFAULT '1',
   `dsname1` varchar(255) DEFAULT NULL,
   `dsname2` varchar(255) DEFAULT NULL,
   `dsname3` varchar(255) DEFAULT NULL,
   `dsname4` varchar(255) DEFAULT NULL,
   `dsname5` varchar(255) DEFAULT NULL,
   `dsname6` varchar(255) DEFAULT NULL,
   `dsname7` varchar(255) DEFAULT NULL,
   `dsname8` varchar(255) DEFAULT NULL,
   `dsname9` varchar(255) DEFAULT NULL,
   `dsname10` varchar(255) DEFAULT NULL,
   `dsname11` varchar(255) DEFAULT NULL,
   `dsname12` varchar(255) DEFAULT NULL,
   `dsname13` varchar(255) DEFAULT NULL,
   `dsname14` varchar(255) DEFAULT NULL,
   `dsname15` varchar(255) DEFAULT NULL,
   `dsnameincr1` tinyint(1) NOT NULL DEFAULT '0',
   `dsnameincr2` tinyint(1) NOT NULL DEFAULT '0',
   `dsnameincr3` tinyint(1) NOT NULL DEFAULT '0',
   `dsnameincr4` tinyint(1) NOT NULL DEFAULT '0',
   `dsnameincr5` tinyint(1) NOT NULL DEFAULT '0',
   `dsnameincr6` tinyint(1) NOT NULL DEFAULT '0',
   `dsnameincr7` tinyint(1) NOT NULL DEFAULT '0',
   `dsnameincr8` tinyint(1) NOT NULL DEFAULT '0',
   `dsnameincr9` tinyint(1) NOT NULL DEFAULT '0',
   `dsnameincr10` tinyint(1) NOT NULL DEFAULT '0',
   `dsnameincr11` tinyint(1) NOT NULL DEFAULT '0',
   `dsnameincr12` tinyint(1) NOT NULL DEFAULT '0',
   `dsnameincr13` tinyint(1) NOT NULL DEFAULT '0',
   `dsnameincr14` tinyint(1) NOT NULL DEFAULT '0',
   `dsnameincr15` tinyint(1) NOT NULL DEFAULT '0',
   PRIMARY KEY (`id`),
   KEY `plugin_monitoring_perfdatas_id` (`plugin_monitoring_perfdatas_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
