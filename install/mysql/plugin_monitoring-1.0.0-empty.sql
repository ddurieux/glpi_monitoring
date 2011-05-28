DROP TABLE IF EXISTS `glpi_plugin_monitoring_commands`;

CREATE TABLE `glpi_plugin_monitoring_commands` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `is_active` tinyint(1) NOT NULL DEFAULT '1',
   `name` varchar(255) DEFAULT NULL,
   `command_name` varchar(255) DEFAULT NULL,
   `command_line` text COLLATE utf8_unicode_ci,
   `poller_tag` varchar(255) DEFAULT NULL,
   `module_type` varchar(255) DEFAULT NULL,
   PRIMARY KEY (`id`),
   KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_monitoring_hosts`;

CREATE TABLE `glpi_plugin_monitoring_hosts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `items_id` int(11) NOT NULL DEFAULT '0',
  `itemtype` varchar(100) DEFAULT NULL,
  `parenttype` int(1) NOT NULL DEFAULT '0',
  `parents` text COLLATE utf8_unicode_ci,
  `plugin_monitoring_hostgroups_id` int(11) NOT NULL DEFAULT '0',
  `plugin_monitoring_commands_id` int(11) NOT NULL DEFAULT '0',
  `plugin_monitoring_checks_id` int(11) NOT NULL DEFAULT '0',
  `active_checks_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `passive_checks_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `plugin_monitoring_timeperiods_id`  int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `glpi_plugin_monitoring_hostgroups`;

CREATE TABLE `glpi_plugin_monitoring_hostgroups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `plugin_monitoring_hosts` text COLLATE utf8_unicode_ci,
  `plugin_monitoring_hostgroups` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
