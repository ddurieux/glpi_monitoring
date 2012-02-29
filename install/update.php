<?php

/*
   ------------------------------------------------------------------------
   Plugin Monitoring for GLPI
   Copyright (C) 2011-2012 by the Plugin Monitoring for GLPI Development Team.

   https://forge.indepnet.net/projects/monitoring/
   ------------------------------------------------------------------------

   LICENSE

   This file is part of Plugin Monitoring project.

   Plugin Monitoring for GLPI is free software: you can redistribute it and/or modify
   it under the terms of the GNU Affero General Public License as published by
   the Free Software Foundation, either version 3 of the License, or
   (at your option) any later version.

   Plugin Monitoring for GLPI is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
   GNU Affero General Public License for more details.

   You should have received a copy of the GNU Affero General Public License
   along with Behaviors. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   Plugin Monitoring for GLPI
   @author    David Durieux
   @co-author 
   @comment   
   @copyright Copyright (c) 2011-2012 Plugin Monitoring for GLPI team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      https://forge.indepnet.net/projects/monitoring/
   @since     2011
 
   ------------------------------------------------------------------------
 */

function pluginMonitoringGetCurrentVersion($version) {
   global $DB;
   
   if ((!TableExists("glpi_plugin_monitoring_configs"))) {
      return '0';
   } else if (!FieldExists("glpi_plugin_monitoring_configs", "timezones")) {
      // Version before 0.80+1.0 (test version)
      return "1.0.0";
   } else if (!FieldExists("glpi_plugin_monitoring_configs", "version")) {
      return "0.80+1.0";
   } else if (FieldExists("glpi_plugin_monitoring_configs", "version")) {
      
      $query = "SELECT `version`
          FROM `glpi_plugin_monitoring_configs`
          WHERE `id` = '1'";
      $result = $DB->query($query);
      $data = $DB->fetch_assoc($result);
      if ($data['version'] != $version) {
         return $data['version'];
      }
   } else {
      return $version;
   }
   return $version;
}



function pluginMonitoringUpdate($current_version, $migrationname='Migration') {
   global $DB;
  
   $migration = new $migrationname($current_version);

   if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/monitoring')) {
      mkdir(GLPI_PLUGIN_DOC_DIR."/monitoring");
   }
   if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/monitoring/templates')) {
      mkdir(GLPI_PLUGIN_DOC_DIR."/monitoring/templates");
   }
   

    /*
    * Table glpi_plugin_monitoring_servicescatalogs
    */
      $newTable = "glpi_plugin_monitoring_servicescatalogs";
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                     ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
         $DB->query($query);
      }
         $migration->changeField($newTable, 
                                 'id', 
                                 'id', 
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable, 
                                 'name', 
                                 'name', 
                                 "varchar(255) DEFAULT NULL");
         $migration->changeField($newTable, 
                                 'entities_id', 
                                 'entities_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'is_recursive', 
                                 'is_recursive', 
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'comment', 
                                 'comment', 
                                 "text DEFAULT NULL COLLATE utf8_unicode_ci");
         $migration->changeField($newTable, 
                                 'last_check', 
                                 'last_check', 
                                 "datetime DEFAULT NULL");
         $migration->changeField($newTable, 
                                 'state', 
                                 'state', 
                                 "varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'state_type', 
                                 'state_type', 
                                 "varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'plugin_monitoring_checks_id', 
                                 'plugin_monitoring_checks_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'calendars_id', 
                                 'calendars_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
      $migration->migrationOneTable($newTable);
      
         $migration->addField($newTable, 
                                 'name', 
                                 "varchar(255) DEFAULT NULL");
         $migration->addField($newTable, 
                                 'entities_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                                 'is_recursive', 
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                                 'comment', 
                                 "text DEFAULT NULL COLLATE utf8_unicode_ci");
         $migration->addField($newTable, 
                                 'last_check', 
                                 "datetime DEFAULT NULL");
         $migration->addField($newTable, 
                                 'state', 
                                 "varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                                 'state_type', 
                                 "varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                              'plugin_monitoring_checks_id', 
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                              'calendars_id', 
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addKey($newTable, 
                            "name");
      $migration->migrationOneTable($newTable);

      
      
    /*
    * Table glpi_plugin_monitoring_componentscatalogs
    */
      $newTable = "glpi_plugin_monitoring_componentscatalogs";
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                     ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
         $DB->query($query);
      }
         $migration->changeField($newTable, 
                                 'id', 
                                 'id', 
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable, 
                                 'name', 
                                 'name', 
                                 "varchar(255) DEFAULT NULL");
         $migration->changeField($newTable, 
                                 'entities_id', 
                                 'entities_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'is_recursive', 
                                 'is_recursive', 
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'comment', 
                                 'comment', 
                                 "text DEFAULT NULL COLLATE utf8_unicode_ci");
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable, 
                                 'name', 
                                 "varchar(255) DEFAULT NULL");
         $migration->addField($newTable, 
                                 'entities_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                                 'is_recursive', 
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                                 'comment', 
                                 "text DEFAULT NULL COLLATE utf8_unicode_ci");
         $migration->addKey($newTable, 
                            "name");
      $migration->migrationOneTable($newTable);

      
      
    /*
    * Table glpi_plugin_monitoring_components
    */
      $newTable = "glpi_plugin_monitoring_components";
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                     ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
         $DB->query($query);
      }
         $migration->changeField($newTable, 
                                 'id', 
                                 'id', 
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable, 
                                 'name', 
                                 'name', 
                                 "varchar(255) DEFAULT NULL");
         $migration->changeField($newTable, 
                                 'plugin_monitoring_commands_id', 
                                 'plugin_monitoring_commands_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'arguments', 
                                 'arguments', 
                                 "text DEFAULT NULL COLLATE utf8_unicode_ci");
         $migration->changeField($newTable, 
                                 'plugin_monitoring_checks_id', 
                                 'plugin_monitoring_checks_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'active_checks_enabled', 
                                 'active_checks_enabled', 
                                 "tinyint(1) NOT NULL DEFAULT '1'");
         $migration->changeField($newTable, 
                                 'passive_checks_enabled', 
                                 'passive_checks_enabled', 
                                 "tinyint(1) NOT NULL DEFAULT '1'");
         $migration->changeField($newTable, 
                                 'calendars_id', 
                                 'calendars_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'remotesystem', 
                                 'remotesystem', 
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable, 
                                 'is_arguments', 
                                 'is_arguments', 
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'alias_command', 
                                 'alias_command', 
                                 "text DEFAULT NULL COLLATE utf8_unicode_ci");
         $migration->changeField($newTable, 
                                 'graph_template', 
                                 'graph_template', 
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable, 
                                 'link', 
                                 'link', 
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable, 
                                 'id', 
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->addField($newTable, 
                                 'name', 
                                 "varchar(255) DEFAULT NULL");
         $migration->addField($newTable, 
                                 'plugin_monitoring_commands_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                                 'arguments', 
                                 "text DEFAULT NULL COLLATE utf8_unicode_ci");
         $migration->addField($newTable, 
                                 'plugin_monitoring_checks_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                                 'active_checks_enabled', 
                                 "tinyint(1) NOT NULL DEFAULT '1'");
         $migration->addField($newTable, 
                                 'passive_checks_enabled', 
                                 "tinyint(1) NOT NULL DEFAULT '1'");
         $migration->addField($newTable, 
                                 'calendars_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                                 'remotesystem', 
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable, 
                                 'is_arguments', 
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                                 'alias_command', 
                                 "text DEFAULT NULL COLLATE utf8_unicode_ci");
         $migration->addField($newTable, 
                                 'graph_template', 
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable, 
                                 'link', 
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addKey($newTable, 
                            "plugin_monitoring_commands_id");
      $migration->migrationOneTable($newTable);
   

      
    /*
    * Table glpi_plugin_monitoring_componentscatalogs_components
    */
      $newTable = "glpi_plugin_monitoring_componentscatalogs_components";
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                     ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
         $DB->query($query);
      }
         $migration->changeField($newTable, 
                                 'id', 
                                 'id', 
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable, 
                                 'plugin_monitoring_componentscalalog_id', 
                                 'plugin_monitoring_componentscalalog_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'plugin_monitoring_components_id', 
                                 'plugin_monitoring_components_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable, 
                                 'plugin_monitoring_componentscalalog_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                                 'plugin_monitoring_components_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addKey($newTable,
                            array('plugin_monitoring_componentscalalog_id',
                                  'plugin_monitoring_components_id'),
                            "unicity",
                            "UNIQUE");
         $migration->addKey($newTable,
                            "plugin_monitoring_componentscalalog_id");         
      $migration->migrationOneTable($newTable);

      
      
    /*
    * Table glpi_plugin_monitoring_componentscatalogs_hosts
    */
      $newTable = "glpi_plugin_monitoring_componentscatalogs_hosts";
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                     ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
         $DB->query($query);
      }
         $migration->changeField($newTable, 
                                 'id', 
                                 'id', 
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable, 
                                 'plugin_monitoring_componentscalalog_id', 
                                 'plugin_monitoring_componentscalalog_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'is_static', 
                                 'is_static', 
                                 "tinyint(1) NOT NULL DEFAULT '1'");
         $migration->changeField($newTable, 
                                 'items_id', 
                                 'items_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'itemtype', 
                                 'itemtype', 
                                 "varchar(100) DEFAULT NULL");
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable, 
                                 'plugin_monitoring_componentscalalog_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                                 'is_static', 
                                 "tinyint(1) NOT NULL DEFAULT '1'");
         $migration->addField($newTable, 
                                 'items_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                                 'itemtype', 
                                 "varchar(100) DEFAULT NULL");
         $migration->addKey($newTable,
                            array('itemtype','items_id'),
                            'itemtype');     
         $migration->addKey($newTable,
                            'plugin_monitoring_componentscalalog_id');
      $migration->migrationOneTable($newTable);

      

    /*
    * Table glpi_plugin_monitoring_componentscatalogs_rules
    */
      $newTable = "glpi_plugin_monitoring_componentscatalogs_rules";
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                     ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
         $DB->query($query);
      }
         $migration->changeField($newTable, 
                                 'id', 
                                 'id', 
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable, 
                                 'plugin_monitoring_componentscalalog_id', 
                                 'plugin_monitoring_componentscalalog_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 'name', 
                                 'name', 
                                 "varchar(255) DEFAULT NULL");
         $migration->changeField($newTable,
                                 'itemtype', 
                                 'itemtype', 
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable,
                                 'condition', 
                                 'condition', 
                                 "text DEFAULT NULL COLLATE utf8_unicode_ci");
         $migration->dropField($newTable, 
                               'entities_id');
         $migration->dropField($newTable, 
                               'is_recursive');
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable, 
                                 'plugin_monitoring_componentscalalog_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 'name', 
                                 "varchar(255) DEFAULT NULL");
         $migration->addField($newTable,
                                 'itemtype', 
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable,
                                 'condition', 
                                 "text DEFAULT NULL COLLATE utf8_unicode_ci");
         $migration->addKey($newTable,
                            'plugin_monitoring_componentscalalog_id');
      $migration->migrationOneTable($newTable);

      
      
    /*
    * Table glpi_plugin_monitoring_services
    */
      $newTable = "glpi_plugin_monitoring_services";
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                     ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
         $DB->query($query);
      }
         $migration->changeField($newTable, 
                                 'id', 
                                 'id', 
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 'name', 
                                 'name', 
                                 "varchar(255) DEFAULT NULL");         
         $migration->changeField($newTable, 
                                 'plugin_monitoring_components_id', 
                                 'plugin_monitoring_components_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'plugin_monitoring_componentscatalogs_hosts_id', 
                                 'plugin_monitoring_componentscatalogs_hosts_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'event', 
                                 'event', 
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable, 
                                 'state', 
                                 'state', 
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");                 
         $migration->changeField($newTable, 
                                 'state_type', 
                                 'state_type', 
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable, 
                                 'last_check', 
                                 'last_check', 
                                 "datetime DEFAULT NULL"); 
         $migration->changeField($newTable, 
                                 'arguments', 
                                 'arguments', 
                                 "text DEFAULT NULL COLLATE utf8_unicode_ci"); 
         $migration->changeField($newTable, 
                                 'alias_command', 
                                 'alias_command', 
                                 "text DEFAULT NULL COLLATE utf8_unicode_ci");
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable,
                                 'name', 
                                 "varchar(255) DEFAULT NULL");         
         $migration->addField($newTable, 
                                 'plugin_monitoring_components_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                                 'plugin_monitoring_componentscatalogs_hosts_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                                 'event', 
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable, 
                                 'state', 
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");                 
         $migration->addField($newTable, 
                                 'state_type', 
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable, 
                                 'last_check', 
                                 "datetime DEFAULT NULL"); 
         $migration->addField($newTable, 
                                 'arguments', 
                                 "text DEFAULT NULL COLLATE utf8_unicode_ci"); 
         $migration->addField($newTable, 
                                 'alias_command', 
                                 "text DEFAULT NULL COLLATE utf8_unicode_ci");
         $migration->addKey($newTable,
                            array('state',
                                  'state_type'),
                            'state');
         $migration->addKey($newTable,
                            'plugin_monitoring_componentscatalogs_hosts_id');
      $migration->migrationOneTable($newTable);


      
    /*
    * Table glpi_plugin_monitoring_contacttemplates
    */
      $newTable = "glpi_plugin_monitoring_contacttemplates";
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                     ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
         $DB->query($query);
      }
         $migration->changeField($newTable, 
                                 'id', 
                                 'id', 
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 'name', 
                                 'name', 
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable,
                                 'is_default', 
                                 'is_default', 
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 'host_notifications_enabled', 
                                 'host_notifications_enabled', 
                                 "tinyint(1) NOT NULL DEFAULT '1'");
         $migration->changeField($newTable,
                                 'service_notifications_enabled', 
                                 'service_notifications_enabled', 
                                 "tinyint(1) NOT NULL DEFAULT '1'");
         $migration->changeField($newTable,
                                 'service_notification_period', 
                                 'service_notification_period', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 'host_notification_period', 
                                 'host_notification_period', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 'service_notification_options_w', 
                                 'service_notification_options_w', 
                                 "tinyint(1) NOT NULL DEFAULT '1'");
         $migration->changeField($newTable,
                                 'service_notification_options_u', 
                                 'service_notification_options_u', 
                                 "tinyint(1) NOT NULL DEFAULT '1'");
         $migration->changeField($newTable,
                                 'service_notification_options_c', 
                                 'service_notification_options_c', 
                                 "tinyint(1) NOT NULL DEFAULT '1'");
         $migration->changeField($newTable,
                                 'service_notification_options_r', 
                                 'service_notification_options_r', 
                                 "tinyint(1) NOT NULL DEFAULT '1'");
         $migration->changeField($newTable,
                                 'service_notification_options_f', 
                                 'service_notification_options_f', 
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 'service_notification_options_n', 
                                 'service_notification_options_n', 
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 'host_notification_options_d', 
                                 'host_notification_options_d', 
                                 "tinyint(1) NOT NULL DEFAULT '1'");
         $migration->changeField($newTable,
                                 'host_notification_options_u', 
                                 'host_notification_options_u', 
                                 "tinyint(1) NOT NULL DEFAULT '1'");
         $migration->changeField($newTable,
                                 'host_notification_options_r', 
                                 'host_notification_options_r', 
                                 "tinyint(1) NOT NULL DEFAULT '1'");
         $migration->changeField($newTable,
                                 'host_notification_options_f', 
                                 'host_notification_options_f', 
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 'host_notification_options_s', 
                                 'host_notification_options_s', 
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 'host_notification_options_n', 
                                 'host_notification_options_n', 
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 'service_notification_commands', 
                                 'service_notification_commands', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 'host_notification_commands', 
                                 'host_notification_commands', 
                                 "int(11) NOT NULL DEFAULT '0'");
       $migration->migrationOneTable($newTable);
         $migration->addField($newTable,
                                 'name', 
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable,
                                 'is_default', 
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 'host_notifications_enabled', 
                                 "tinyint(1) NOT NULL DEFAULT '1'");
         $migration->addField($newTable,
                                 'service_notifications_enabled', 
                                 "tinyint(1) NOT NULL DEFAULT '1'");
         $migration->addField($newTable,
                                 'service_notification_period', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 'host_notification_period', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 'service_notification_options_w', 
                                 "tinyint(1) NOT NULL DEFAULT '1'");
         $migration->addField($newTable,
                                 'service_notification_options_u', 
                                 "tinyint(1) NOT NULL DEFAULT '1'");
         $migration->addField($newTable,
                                 'service_notification_options_c', 
                                 "tinyint(1) NOT NULL DEFAULT '1'");
         $migration->addField($newTable,
                                 'service_notification_options_r', 
                                 "tinyint(1) NOT NULL DEFAULT '1'");
         $migration->addField($newTable,
                                 'service_notification_options_f', 
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 'service_notification_options_n', 
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 'host_notification_options_d', 
                                 "tinyint(1) NOT NULL DEFAULT '1'");
         $migration->addField($newTable,
                                 'host_notification_options_u', 
                                 "tinyint(1) NOT NULL DEFAULT '1'");
         $migration->addField($newTable,
                                 'host_notification_options_r', 
                                 "tinyint(1) NOT NULL DEFAULT '1'");
         $migration->addField($newTable,
                                 'host_notification_options_f', 
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 'host_notification_options_s', 
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 'host_notification_options_n', 
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 'service_notification_commands', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 'host_notification_commands', 
                                 "int(11) NOT NULL DEFAULT '0'");
      $migration->migrationOneTable($newTable);


      
    /*
    * Table glpi_plugin_monitoring_contacts
    */
      $newTable = "glpi_plugin_monitoring_contacts";
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                     ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
         $DB->query($query);
      }
         $migration->changeField($newTable, 
                                 'id', 
                                 'id', 
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable, 
                                 'users_id', 
                                 'users_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'plugin_monitoring_contacttemplates_id', 
                                 'plugin_monitoring_contacttemplates_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable, 
                                 'users_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                                 'plugin_monitoring_contacttemplates_id', 
                                 "int(11) NOT NULL DEFAULT '0'"); 
      $migration->migrationOneTable($newTable);

      
      
    /*
    * Table glpi_plugin_monitoring_contacts_items
    */
      $newTable = "glpi_plugin_monitoring_contacts_items";
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                     ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
         $DB->query($query);
      }
         $migration->changeField($newTable, 
                                 'id', 
                                 'id', 
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable, 
                                 'users_id', 
                                 'users_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'groups_id', 
                                 'groups_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'items_id', 
                                 'items_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'itemtype', 
                                 'itemtype', 
                                 "varchar(100) DEFAULT NULL");
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable, 
                                 'users_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                                 'groups_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                                 'items_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                                 'itemtype', 
                                 "varchar(100) DEFAULT NULL");
      $migration->migrationOneTable($newTable);


      
    /*
    * Table glpi_plugin_monitoring_commandtemplates
    */
      $newTable = "glpi_plugin_monitoring_commandtemplates";
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                     ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
         $DB->query($query);
      }
         $migration->changeField($newTable, 
                                 'id', 
                                 'id', 
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable, 
                                 'plugin_monitoring_commands_id', 
                                 'plugin_monitoring_commands_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'name', 
                                 'name', 
                                 "varchar(255) DEFAULT NULL");
         $migration->changeField($newTable, 
                                 'key', 
                                 'key', 
                                 "varchar(255) DEFAULT NULL");
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable, 
                                 'plugin_monitoring_commands_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                                 'name', 
                                 "varchar(255) DEFAULT NULL");
         $migration->addField($newTable, 
                                 'key', 
                                 "varchar(255) DEFAULT NULL");
      $migration->migrationOneTable($newTable);
      
      

    /*
    * Table glpi_plugin_monitoring_rrdtooltemplates
    */
      $newTable = "glpi_plugin_monitoring_rrdtooltemplates";
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                     ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
         $DB->query($query);
      }
         $migration->changeField($newTable, 
                                 'id', 
                                 'id', 
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable, 
                                 'plugin_monitoring_commands_id', 
                                 'plugin_monitoring_commands_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'name', 
                                 'name', 
                                 "varchar(255) DEFAULT NULL");
         $migration->changeField($newTable, 
                                 'key', 
                                 'key', 
                                 "varchar(255) DEFAULT NULL");
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable, 
                                 'plugin_monitoring_commands_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                                 'name', 
                                 "varchar(255) DEFAULT NULL");
         $migration->addField($newTable, 
                                 'key', 
                                 "varchar(255) DEFAULT NULL");
      $migration->migrationOneTable($newTable);

      

    /*
    * Table glpi_plugin_monitoring_configs
    */
      $newTable = "glpi_plugin_monitoring_configs";
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                     ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
         $DB->query($query);
      }
         $migration->changeField($newTable, 
                                 'id', 
                                 'id', 
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable, 
                                 'rrdtoolpath', 
                                 'rrdtoolpath', 
                                 "varchar(255) DEFAULT NULL");
         $migration->changeField($newTable, 
                                 'timezones', 
                                 'timezones', 
                                 "varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '[\"0\"]'");
         $migration->changeField($newTable, 
                                 'version', 
                                 'version', 
                                 "varchar(255) DEFAULT NULL");
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable, 
                              'rrdtoolpath', 
                              "varchar(255) DEFAULT NULL");
         $migration->addField($newTable, 
                              'timezones', 
                              "varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '[\"0\"]'");
         $migration->addField($newTable, 
                              'version', 
                              "varchar(255) DEFAULT NULL");
      $migration->migrationOneTable($newTable);

      
      
   /*
    * Table glpi_plugin_monitoring_entities
    */
      $newTable = "glpi_plugin_monitoring_entities";
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                     ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
         $DB->query($query);
      }
         $migration->changeField($newTable, 
                                 'id', 
                                 'id', 
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable, 
                                 'entities_id', 
                                 'entities_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'tag', 
                                 'tag', 
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable, 
                              'entities_id', 
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                              'tag', 
                              "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addKey($newTable, 
                            "entities_id");
         $migration->addKey($newTable, 
                            "tag");
      $migration->migrationOneTable($newTable);
         
      
    /*
    * Table glpi_plugin_monitoring_hostaddresses
    */
      $newTable = "glpi_plugin_monitoring_hostaddresses";
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                     ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
         $DB->query($query);
      }
         $migration->changeField($newTable, 
                                 'id', 
                                 'id', 
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable, 
                                 'items_id', 
                                 'items_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'itemtype', 
                                 'itemtype', 
                                 "varchar(100) DEFAULT NULL");
         $migration->changeField($newTable, 
                                 'networkports_id', 
                                 'networkports_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable, 
                                 'items_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                                 'itemtype', 
                                 "varchar(100) DEFAULT NULL");
         $migration->addField($newTable, 
                                 'networkports_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
      $migration->migrationOneTable($newTable);

      
    /*
    * Table glpi_plugin_monitoring_serviceevents
    */
      $newTable = "glpi_plugin_monitoring_serviceevents";
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                        `id` bigint(30) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                     ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
         $DB->query($query);
      }
         $migration->changeField($newTable, 
                                 'id', 
                                 'id', 
                                 "bigint(30) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable, 
                                 'plugin_monitoring_services_id', 
                                 'plugin_monitoring_services_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'date', 
                                 'date', 
                                 "datetime DEFAULT NULL");
         $migration->changeField($newTable, 
                                 'event', 
                                 'event', 
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable, 
                                 'perf_data', 
                                 'perf_data', 
                                 "text DEFAULT NULL COLLATE utf8_unicode_ci");
         $migration->changeField($newTable, 
                                 'output', 
                                 'output', 
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable, 
                                 'state', 
                                 'state', 
                                 "varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'state_type', 
                                 'state_type', 
                                 "varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'latency', 
                                 'latency', 
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable, 
                                 'execution_time', 
                                 'execution_time', 
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable, 
                                 'plugin_monitoring_services_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                                 'date', 
                                 "datetime DEFAULT NULL");
         $migration->addField($newTable, 
                                 'event', 
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable, 
                                 'perf_data', 
                                 "text DEFAULT NULL COLLATE utf8_unicode_ci");
         $migration->addField($newTable, 
                                 'output', 
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable, 
                                 'state', 
                                 "varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                                 'state_type', 
                                 "varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                                 'latency', 
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable, 
                                 'execution_time', 
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addKey($newTable, 
                            "plugin_monitoring_services_id");
      $migration->migrationOneTable($newTable);

      
      
    /*
    * Table glpi_plugin_monitoring_commands
    */
      $newTable = "glpi_plugin_monitoring_commands";
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                     ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
         $DB->query($query);
      }
         $migration->changeField($newTable, 
                                 'id', 
                                 'id', 
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable, 
                                 'is_active', 
                                 'is_active', 
                                 "tinyint(1) NOT NULL DEFAULT '1'");
         $migration->changeField($newTable, 
                                 'name', 
                                 'name', 
                                 "varchar(255) DEFAULT NULL");
         $migration->changeField($newTable, 
                                 'command_name', 
                                 'command_name', 
                                 "varchar(255) DEFAULT NULL");
         $migration->changeField($newTable, 
                                 'command_line', 
                                 'command_line', 
                                 "text DEFAULT NULL COLLATE utf8_unicode_ci");
         $migration->changeField($newTable, 
                                 'poller_tag', 
                                 'poller_tag', 
                                 "varchar(255) DEFAULT NULL");
         $migration->changeField($newTable, 
                                 'module_type', 
                                 'module_type', 
                                 "varchar(255) DEFAULT NULL");
         $migration->changeField($newTable, 
                                 'arguments', 
                                 'arguments', 
                                 "text DEFAULT NULL COLLATE utf8_unicode_ci");
         $migration->dropField($newTable, 
                                 'regex');
         $migration->dropField($newTable, 
                                 'legend');
         $migration->dropField($newTable, 
                                 'unit');
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable, 
                                 'is_active', 
                                 "tinyint(1) NOT NULL DEFAULT '1'");
         $migration->addField($newTable, 
                                 'name', 
                                 "varchar(255) DEFAULT NULL");
         $migration->addField($newTable, 
                                 'command_name', 
                                 "varchar(255) DEFAULT NULL");
         $migration->addField($newTable, 
                                 'command_line', 
                                 "text DEFAULT NULL COLLATE utf8_unicode_ci");
         $migration->addField($newTable, 
                                 'poller_tag', 
                                 "varchar(255) DEFAULT NULL");
         $migration->addField($newTable, 
                                 'module_type', 
                                 "varchar(255) DEFAULT NULL");
         $migration->addField($newTable, 
                                 'arguments', 
                                 "text DEFAULT NULL COLLATE utf8_unicode_ci");
         $migration->addKey($newTable, 
                            "name");
         $migration->addKey($newTable, 
                            "command_name");
      $migration->migrationOneTable($newTable);

      
      
    /*
    * Table glpi_plugin_monitoring_checks
    */
      $newTable = "glpi_plugin_monitoring_checks";
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                     ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
         $DB->query($query);
      }
         $migration->changeField($newTable, 
                                 'id', 
                                 'id', 
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable, 
                                 'name', 
                                 'name', 
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable, 
                                 'max_check_attempts', 
                                 'max_check_attempts', 
                                 "int(2) NOT NULL DEFAULT '1'");
         $migration->changeField($newTable, 
                                 'check_interval', 
                                 'check_interval', 
                                 "int(5) NOT NULL DEFAULT '1'");
         $migration->changeField($newTable, 
                                 'retry_interval', 
                                 'retry_interval', 
                                 "int(5) NOT NULL DEFAULT '1'");
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable, 
                                 'name', 
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable, 
                                 'max_check_attempts', 
                                 "int(2) NOT NULL DEFAULT '1'");
         $migration->addField($newTable, 
                                 'check_interval', 
                                 "int(5) NOT NULL DEFAULT '1'");
         $migration->addField($newTable, 
                                 'retry_interval', 
                                 "int(5) NOT NULL DEFAULT '1'");
      $migration->migrationOneTable($newTable);

      

    /*
    * Table glpi_plugin_monitoring_businessrules
    */
      $newTable = "glpi_plugin_monitoring_businessrules";
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                     ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
         $DB->query($query);
      }
         $migration->changeField($newTable, 
                                 'id', 
                                 'id', 
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable, 
                                 'plugin_monitoring_businessrulegroups_id', 
                                 'plugin_monitoring_businessrulegroups_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'plugin_monitoring_services_id', 
                                 'plugin_monitoring_services_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable, 
                                 'plugin_monitoring_businessrulegroups_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                                 'plugin_monitoring_services_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
      $migration->migrationOneTable($newTable);


      
    /*
    * Table glpi_plugin_monitoring_businessrulegroups
    */
      $newTable = "glpi_plugin_monitoring_businessrulegroups";
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                     ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
         $DB->query($query);
      }
         $migration->changeField($newTable, 
                                 'id', 
                                 'id', 
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable, 
                                 'name', 
                                 'name', 
                                 "varchar(255) DEFAULT NULL");
         $migration->changeField($newTable, 
                                 'plugin_monitoring_servicescatalogs_id', 
                                 'plugin_monitoring_servicescatalogs_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'operator', 
                                 'operator', 
                                 "varchar(255) DEFAULT NULL");
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable, 
                                 'name', 
                                 "varchar(255) DEFAULT NULL");
         $migration->addField($newTable, 
                                 'plugin_monitoring_servicescatalogs_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                                 'operator', 
                                 "varchar(255) DEFAULT NULL");
      $migration->migrationOneTable($newTable);


      
    /*
    * Table glpi_plugin_monitoring_notificationcommands
    */
      $newTable = "glpi_plugin_monitoring_notificationcommands";
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                     ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
         $DB->query($query);
      }
         $migration->changeField($newTable, 
                                 'id', 
                                 'id', 
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable, 
                                 'is_active', 
                                 'is_active', 
                                 "tinyint(1) NOT NULL DEFAULT '1'");
         $migration->changeField($newTable, 
                                 'name', 
                                 'name', 
                                 "varchar(255) DEFAULT NULL");
         $migration->changeField($newTable, 
                                 'command_name', 
                                 'command_name', 
                                 "varchar(255) DEFAULT NULL");
         $migration->changeField($newTable, 
                                 'command_line', 
                                 'command_line', 
                                 "text DEFAULT NULL COLLATE utf8_unicode_ci");
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable, 
                                 'is_active', 
                                 "tinyint(1) NOT NULL DEFAULT '1'");
         $migration->addField($newTable, 
                                 'name', 
                                 "varchar(255) DEFAULT NULL");
         $migration->addField($newTable, 
                                 'command_name', 
                                 "varchar(255) DEFAULT NULL");
         $migration->addField($newTable, 
                                 'command_line', 
                                 "text DEFAULT NULL COLLATE utf8_unicode_ci");
         $migration->addKey($newTable, 
                            "name");
      $migration->migrationOneTable($newTable);
      
      

    /*
    * Table glpi_plugin_monitoring_contactgroups
    */
      $newTable = "glpi_plugin_monitoring_contactgroups";
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                     ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
         $DB->query($query);
      }
         $migration->changeField($newTable, 
                                 'id', 
                                 'id', 
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable, 
                                 'name', 
                                 'name', 
                                 "varchar(255) DEFAULT NULL");
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable, 
                                 'name', 
                                 "varchar(255) DEFAULT NULL");
      $migration->migrationOneTable($newTable);


      
    /*
    * Table glpi_plugin_monitoring_contacts_contactgroups
    */
      $newTable = "glpi_plugin_monitoring_contacts_contactgroups";
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                     ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
         $DB->query($query);
      }
         $migration->changeField($newTable, 
                                 'id', 
                                 'id', 
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable, 
                                 'plugin_monitoring_contacts_id', 
                                 'plugin_monitoring_contacts_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'plugin_monitoring_contactgroups_id', 
                                 'plugin_monitoring_contactgroups_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
      $migration->migrationOneTable($newTable);

         $migration->addField($newTable, 
                                 'plugin_monitoring_contacts_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                                 'plugin_monitoring_contactgroups_id', 
                                 "int(11) NOT NULL DEFAULT '0'");         
         $migration->addKey($newTable,
                            array('plugin_monitoring_contacts_id',
                                  'plugin_monitoring_contactgroups_id'),
                            "unicity",
                            "UNIQUE");
         $migration->addKey($newTable,
                            "plugin_monitoring_contactgroups_id"); 
      $migration->migrationOneTable($newTable);

      
      
    /*
    * Table glpi_plugin_monitoring_contactgroups_contactgroups
    */
      $newTable = "glpi_plugin_monitoring_contactgroups_contactgroups";
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                     ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
         $DB->query($query);
      }
         $migration->changeField($newTable, 
                                 'id', 
                                 'id', 
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable, 
                                 'plugin_monitoring_contactgroups_id_1', 
                                 'plugin_monitoring_contactgroups_id_1', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'plugin_monitoring_contactgroups_id_2', 
                                 'plugin_monitoring_contactgroups_id_2', 
                                 "int(11) NOT NULL DEFAULT '0'");
      $migration->migrationOneTable($newTable);

         $migration->addField($newTable, 
                                 'plugin_monitoring_contactgroups_id_1', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                                 'plugin_monitoring_contactgroups_id_2', 
                                 "int(11) NOT NULL DEFAULT '0'");         
         $migration->addKey($newTable,
                            array('plugin_monitoring_contactgroups_id_1',
                                  'plugin_monitoring_contactgroups_id_2'),
                            "unicity",
                            "UNIQUE");
         $migration->addKey($newTable,
                            "plugin_monitoring_contactgroups_id_2"); 
      $migration->migrationOneTable($newTable);

      
      
    /*
    * Table glpi_plugin_monitoring_servicedefs
    */
      $newTable = "glpi_plugin_monitoring_servicedefs";
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                     ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
         $DB->query($query);
      }
         $migration->changeField($newTable, 
                                 'id', 
                                 'id', 
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable, 
                                 'name', 
                                 'name', 
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable, 
                                 'plugin_monitoring_commands_id', 
                                 'plugin_monitoring_commands_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'arguments', 
                                 'arguments', 
                                 "text DEFAULT NULL COLLATE utf8_unicode_ci");
         $migration->changeField($newTable, 
                                 'plugin_monitoring_checks_id', 
                                 'plugin_monitoring_checks_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'active_checks_enabled', 
                                 'active_checks_enabled', 
                                 "tinyint(1) NOT NULL DEFAULT '1'");
         $migration->changeField($newTable, 
                                 'passive_checks_enabled', 
                                 'passive_checks_enabled', 
                                 "tinyint(1) NOT NULL DEFAULT '1'");
         $migration->changeField($newTable, 
                                 'calendars_id', 
                                 'calendars_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'remotesystem', 
                                 'remotesystem', 
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable, 
                                 'is_arguments', 
                                 'is_arguments', 
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'alias_command', 
                                 'alias_command', 
                                 "text DEFAULT NULL COLLATE utf8_unicode_ci");
         $migration->changeField($newTable, 
                                 'aliasperfdata_commands_id', 
                                 'aliasperfdata_commands_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'link', 
                                 'link', 
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable, 
                                 'name', 
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable, 
                                 'plugin_monitoring_commands_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                                 'arguments', 
                                 "text DEFAULT NULL COLLATE utf8_unicode_ci");
         $migration->addField($newTable, 
                                 'plugin_monitoring_checks_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                                 'active_checks_enabled', 
                                 "tinyint(1) NOT NULL DEFAULT '1'");
         $migration->addField($newTable, 
                                 'passive_checks_enabled', 
                                 "tinyint(1) NOT NULL DEFAULT '1'");
         $migration->addField($newTable, 
                                 'calendars_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                                 'remotesystem', 
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable, 
                                 'is_arguments', 
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                                 'alias_command', 
                                 "text DEFAULT NULL COLLATE utf8_unicode_ci");
         $migration->addField($newTable, 
                                 'aliasperfdata_commands_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                                 'link', 
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL"); 
      $migration->migrationOneTable($newTable);
      
      
         
   /*
    * Table Delete old table not used
    */
      if (TableExists("glpi_plugin_monitoring_servicesuggests")) {
         $DB->query("DROP TABLE `glpi_plugin_monitoring_servicesuggests`");
      }
      
      
      
      
   if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/monitoring')) {
      mkdir(GLPI_PLUGIN_DOC_DIR."/monitoring");
   }
   if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/monitoring/templates')) {
      mkdir(GLPI_PLUGIN_DOC_DIR."/monitoring/templates");
   }
      
      
   $crontask = new CronTask();
   if (!$crontask->getFromDBbyName('PluginMonitoringServiceevent', 'updaterrd')) {
      CronTask::Register('PluginMonitoringServiceevent', 'updaterrd', '300', 
                   array('mode' => 2, 'allowmode' => 3, 'logs_lifetime'=> 30));
   }

   
   /*
    * Clean services not have host
    */
   $query = "SELECT `glpi_plugin_monitoring_services`.* FROM `glpi_plugin_monitoring_services`
      LEFT JOIN `glpi_plugin_monitoring_componentscatalogs_hosts`
         ON `glpi_plugin_monitoring_componentscatalogs_hosts`.`id` = `plugin_monitoring_componentscatalogs_hosts_id`
   WHERE `is_static` IS NULL";
   $result = $DB->query($query);
   while ($data=$DB->fetch_array($result)) {
      $queryd = "DELETE FROM `glpi_plugin_monitoring_services`
         WHERE `id`='".$data['id']."'";
      $DB->query($queryd);
   }
   
   $query = "UPDATE `glpi_plugin_monitoring_configs`
      SET `version`='".PLUGIN_MONITORING_VERSION."'
         WHERE `id`='1'";
   $DB->query($query);
}

?>