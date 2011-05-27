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
