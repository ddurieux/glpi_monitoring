<?php

/*
   ------------------------------------------------------------------------
   Plugin Monitoring for GLPI
   Copyright (C) 2011-2013 by the Plugin Monitoring for GLPI Development Team.

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
   along with Monitoring. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   Plugin Monitoring for GLPI
   @author    David Durieux
   @co-author 
   @comment   
   @copyright Copyright (c) 2011-2013 Plugin Monitoring for GLPI team
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
      if ($DB->numrows($result) > 0) {
         $data = $DB->fetch_assoc($result);
         if (is_null($data['version'])
                 || $data['version'] == '') {
            $data['version'] = '0.80+1.0';
         }
         if ($data['version'] != $version) {
            return $data['version'];
         }
      }else {
         return "0.80+1.0";
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
   if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/monitoring/weathermapbg')) {
      mkdir(GLPI_PLUGIN_DOC_DIR."/monitoring/weathermapbg");
   }
   
   $unavaibility_recalculate = 0;
   if (!TableExists("glpi_plugin_monitoring_unavaibilities")
           || !FieldExists("glpi_plugin_monitoring_unavaibilities", "duration")) {
      $unavaibility_recalculate = 1;
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
         $migration->addField($newTable,
                              'is_acknowledged', 
                              "tinyint(1) NOT NULL DEFAULT '0'");         
         $migration->addField($newTable,
                              'is_acknowledgeconfirmed', 
                              "tinyint(1) NOT NULL DEFAULT '0'");   
         $migration->addField($newTable, 
                              'acknowledge_comment', 
                              "text DEFAULT NULL COLLATE utf8_unicode_ci");
         $migration->addField($newTable, 
                              'acknowledge_users_id', 
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
         $migration->changeField($newTable, 
                                 'notification_interval', 
                                 'notification_interval', 
                                 "int(4) NOT NULL DEFAULT '30'");
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
                              'notification_interval', 
                              "int(4) NOT NULL DEFAULT '30'");
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
         $migration->changeField($newTable, 
                                 'is_weathermap', 
                                 'is_weathermap', 
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'weathermap_regex', 
                                 'weathermap_regex_in', 
                                 "text DEFAULT NULL COLLATE utf8_unicode_ci");
         $migration->changeField($newTable, 
                                 'perfname', 
                                 'perfname', 
                                 "text DEFAULT NULL COLLATE utf8_unicode_ci");
         $migration->changeField($newTable, 
                                 'perfnameinvert', 
                                 'perfnameinvert', 
                                 "text DEFAULT NULL COLLATE utf8_unicode_ci");
         $migration->changeField($newTable, 
                                 'perfnamecolor', 
                                 'perfnamecolor', 
                                 "text DEFAULT NULL COLLATE utf8_unicode_ci");
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
         $migration->addField($newTable, 
                                 'is_weathermap', 
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                                 'weathermap_regex_in', 
                                 "text DEFAULT NULL COLLATE utf8_unicode_ci");
         $migration->addField($newTable, 
                                 'weathermap_regex_out', 
                                 "text DEFAULT NULL COLLATE utf8_unicode_ci");
         $migration->addField($newTable, 
                              'perfname', 
                              "text DEFAULT NULL COLLATE utf8_unicode_ci");
         $migration->addField($newTable, 
                              'perfnameinvert', 
                              "text DEFAULT NULL COLLATE utf8_unicode_ci");
         $migration->addField($newTable, 
                              'perfnamecolor', 
                              "text DEFAULT NULL COLLATE utf8_unicode_ci");
         $migration->addField($newTable, 
                              'plugin_monitoring_eventhandlers_id', 
                              "int(11) NOT NULL DEFAULT '0'");
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
                                 'entities_id', 
                                 'entities_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
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
                                 'networkports_id', 
                                 'networkports_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->dropField($newTable,
                               'alias_command');
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable, 
                              'entities_id',
                              "int(11) NOT NULL DEFAULT '0'");
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
                              'networkports_id', 
                              "int(11) NOT NULL DEFAULT '0'");         
         $migration->addField($newTable,
                              'is_acknowledged', 
                              "tinyint(1) NOT NULL DEFAULT '0'");         
         $migration->addField($newTable,
                              'is_acknowledgeconfirmed', 
                              "tinyint(1) NOT NULL DEFAULT '0'");   
         $migration->addField($newTable, 
                              'acknowledge_comment', 
                              "text DEFAULT NULL COLLATE utf8_unicode_ci");
         $migration->addField($newTable, 
                              'acknowledge_users_id', 
                              "int(11) NOT NULL DEFAULT '0'");  
         $migration->addKey($newTable,
                            array('state',
                                  'state_type'),
                            'state');
         $migration->addKey($newTable,
                            'plugin_monitoring_componentscatalogs_hosts_id');
      $migration->migrationOneTable($newTable);


      
    /*
    * Table glpi_plugin_monitoring_servicegraphs
    */
      $newTable = "glpi_plugin_monitoring_servicegraphs";
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
                                 'data', 
                                 'data', 
                                 "text DEFAULT NULL COLLATE utf8_unicode_ci"); 
         $migration->changeField($newTable,
                                 'type', 
                                 'type', 
                                 "varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL");
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable, 
                              'plugin_monitoring_services_id', 
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                              'date', 
                              "datetime DEFAULT NULL");
         $migration->addField($newTable, 
                              'data', 
                              "text DEFAULT NULL COLLATE utf8_unicode_ci"); 
         $migration->addField($newTable,
                              'type', 
                              "varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addKey($newTable,
                            array('plugin_monitoring_services_id',
                                  'type'),
                            'plugin_monitoring_services_id');
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
         $migration->changeField($newTable, 
                                 'entities_id', 
                                 'entities_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
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
         $migration->addField($newTable, 
                              'entities_id', 
                              "int(11) NOT NULL DEFAULT '0'");
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
                                 'timezones', 
                                 'timezones', 
                                 "varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '[\"0\"]'");
         $migration->changeField($newTable, 
                                 'version', 
                                 'version', 
                                 "varchar(255) DEFAULT NULL");
         $migration->changeField($newTable, 
                                 'logretention', 
                                 'logretention', 
                                 "int(5) NOT NULL DEFAULT '30'");         
         $migration->dropField($newTable, 
                              'phppath');
         $migration->dropField($newTable, 
                              'rrdtoolpath');
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable, 
                              'timezones', 
                              "varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '[\"0\"]'");
         $migration->addField($newTable, 
                              'version', 
                              "varchar(255) DEFAULT NULL");
         $migration->addField($newTable, 
                              'logretention', 
                              "int(5) NOT NULL DEFAULT '30'");
      $migration->migrationOneTable($newTable);

      
      

    /*
    * Table glpi_plugin_monitoring_displayviews
    */
      $newTable = "glpi_plugin_monitoring_displayviews";
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
                                 'is_active', 
                                 'is_active', 
                                 "tinyint(1) NOT NULL DEFAULT '0'");        
         $migration->changeField($newTable, 
                                 'users_id', 
                                 'users_id', 
                                 "int(11) NOT NULL DEFAULT '0'");         
         $migration->changeField($newTable, 
                                 'counter', 
                                 'counter', 
                                 "varchar(255) DEFAULT NULL");         
         $migration->changeField($newTable, 
                                 'in_central', 
                                 'in_central', 
                                 "tinyint(1) NOT NULL DEFAULT '0'");         
         $migration->changeField($newTable, 
                                 'width', 
                                 'width', 
                                 "int(5) NOT NULL DEFAULT '950'");         
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
                              'is_active', 
                              "tinyint(1) NOT NULL DEFAULT '0'");        
         $migration->addField($newTable, 
                              'users_id', 
                              "int(11) NOT NULL DEFAULT '0'");         
         $migration->addField($newTable, 
                              'counter', 
                              "varchar(255) DEFAULT NULL");         
         $migration->addField($newTable, 
                              'in_central', 
                              "tinyint(1) NOT NULL DEFAULT '0'");         
         $migration->addField($newTable, 
                              'width', 
                              "int(5) NOT NULL DEFAULT '950'");          
         $migration->addField($newTable, 
                              'is_frontview', 
                              "tinyint(1) NOT NULL DEFAULT '0'"); 
         $migration->addField($newTable, 
                              'comment', 
                              "text DEFAULT NULL COLLATE utf8_unicode_ci");
      $migration->migrationOneTable($newTable);
      
      
      
    /*
    * Table glpi_plugin_monitoring_displayviews_groups
    */
      $newTable = "glpi_plugin_monitoring_displayviews_groups";
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                     ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
         $DB->query($query);
      }             
         $migration->addField($newTable, 
                              'pluginmonitoringdisplayviews_id', 
                              "int(11) NOT NULL DEFAULT '0'");      
         $migration->addField($newTable, 
                              'groups_id', 
                              "int(11) NOT NULL DEFAULT '0'");   
         $migration->addField($newTable, 
                              'entities_id', 
                              "int(11) NOT NULL DEFAULT '0'");   
         $migration->addField($newTable, 
                              'is_recursive', 
                              "tinyint(1) NOT NULL DEFAULT '0'");   
         $migration->addKey($newTable, 
                            "pluginmonitoringdisplayviews_id");  
         $migration->addKey($newTable, 
                            "groups_id");  
         $migration->addKey($newTable, 
                            "entities_id");  
         $migration->addKey($newTable, 
                            "is_recursive");
      $migration->migrationOneTable($newTable);
      
      
      
    /*
    * Table glpi_plugin_monitoring_displayviews_users
    */
      $newTable = "glpi_plugin_monitoring_displayviews_users";
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                     ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
         $DB->query($query);
      }             
         $migration->addField($newTable, 
                              'pluginmonitoringdisplayviews_id', 
                              "int(11) NOT NULL DEFAULT '0'");      
         $migration->addField($newTable, 
                              'users_id', 
                              "int(11) NOT NULL DEFAULT '0'");   
         $migration->addKey($newTable, 
                            "pluginmonitoringdisplayviews_id");  
         $migration->addKey($newTable, 
                            "users_id");
      $migration->migrationOneTable($newTable);
      
      
      
    /*
    * Table glpi_plugin_monitoring_displayviews_items
    */
      $newTable = "glpi_plugin_monitoring_displayviews_items";
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
                                 'plugin_monitoring_displayviews_id', 
                                 'plugin_monitoring_displayviews_id', 
                                 "int(11) NOT NULL DEFAULT '0'");      
         $migration->changeField($newTable, 
                                 'x', 
                                 'x', 
                                 "int(5) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'y', 
                                 'y', 
                                 "int(5) NOT NULL DEFAULT '0'");   
         $migration->changeField($newTable, 
                                 'items_id', 
                                 'items_id', 
                                 "int(11) NOT NULL DEFAULT '0'");   
         $migration->changeField($newTable, 
                                 'itemtype', 
                                 'itemtype', 
                                 "varchar(100) DEFAULT NULL");   
         $migration->changeField($newTable, 
                                 'extra_infos', 
                                 'extra_infos', 
                                 "varchar(255) DEFAULT NULL");              
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable, 
                              'plugin_monitoring_displayviews_id', 
                              "int(11) NOT NULL DEFAULT '0'");      
         $migration->addField($newTable, 
                              'x', 
                              "int(5) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                              'y', 
                              "int(5) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                              'items_id', 
                              "int(11) NOT NULL DEFAULT '0'");   
         $migration->addField($newTable, 
                              'itemtype', 
                              "varchar(100) DEFAULT NULL");   
         $migration->addField($newTable, 
                              'extra_infos', 
                              "varchar(255) DEFAULT NULL"); 
         $migration->addKey($newTable, 
                            "plugin_monitoring_displayviews_id");
      $migration->migrationOneTable($newTable);
      
      
      
    /*
    * Table glpi_plugin_monitoring_displayviews_rules
    */
      $newTable = "glpi_plugin_monitoring_displayviews_rules";
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                     ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
         $DB->query($query);
      }
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable, 
                              'plugin_monitoring_displayviews_id', 
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                              'name', 
                              "varchar(255) DEFAULT NULL");
         $migration->addField($newTable,
                              'itemtype', 
                              "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable,
                              'type', 
                              "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable,
                              'condition', 
                              "text DEFAULT NULL COLLATE utf8_unicode_ci");
         $migration->addKey($newTable,
                            'plugin_monitoring_displayviews_id');
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
         $migration->changeField($newTable, 
                                 'ipaddresses_id', 
                                 'ipaddresses_id', 
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
         $migration->addField($newTable, 
                              'ipaddresses_id', 
                              "int(11) NOT NULL DEFAULT '0'");
      $migration->migrationOneTable($newTable);

      
      
    /*
    * Table glpi_plugin_monitoring_hostconfigs
    */
      $newTable = "glpi_plugin_monitoring_hostconfigs";
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
                                 'plugin_monitoring_commands_id', 
                                 'plugin_monitoring_commands_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'plugin_monitoring_checks_id', 
                                 'plugin_monitoring_checks_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'calendars_id', 
                                 'calendars_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'plugin_monitoring_realms_id', 
                                 'plugin_monitoring_realms_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'computers_id', 
                                 'computers_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable, 
                              'items_id', 
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                              'itemtype', 
                              "varchar(100) DEFAULT NULL");
         $migration->addField($newTable, 
                              'plugin_monitoring_commands_id', 
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                              'plugin_monitoring_checks_id', 
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                              'calendars_id', 
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                              'plugin_monitoring_realms_id', 
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                              'computers_id', 
                              "int(11) NOT NULL DEFAULT '0'");
      $migration->migrationOneTable($newTable);
      
      
      
    /*
    * Table glpi_plugin_monitoring_hosts
    */
      $newTable = "glpi_plugin_monitoring_hosts";
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
                                 'dependencies', 
                                 'dependencies', 
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL"); 
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable, 
                              'items_id', 
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                              'itemtype', 
                              "varchar(100) DEFAULT NULL");
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
                              'dependencies', 
                              "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
      $migration->migrationOneTable($newTable);

      
      
    /*
    * Table glpi_plugin_monitoring_logs
    */
      $newTable = "glpi_plugin_monitoring_logs";
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
                                 'date_mod', 
                                 'date_mod', 
                                 "datetime DEFAULT NULL");
         $migration->changeField($newTable, 
                                 'user_name', 
                                 'user_name', 
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable, 
                                 'itemtype', 
                                 'itemtype', 
                                 "varchar(100) DEFAULT NULL");         
         $migration->changeField($newTable, 
                                 'items_id', 
                                 'items_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'action', 
                                 'action', 
                                 "varchar(100) DEFAULT NULL");         
         $migration->changeField($newTable, 
                                 'value', 
                                 'value', 
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable, 
                              'date_mod', 
                              "datetime DEFAULT NULL");
         $migration->addField($newTable, 
                              'user_name', 
                              "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable, 
                              'itemtype', 
                              "varchar(100) DEFAULT NULL");         
         $migration->addField($newTable, 
                              'items_id', 
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                              'action', 
                              "varchar(100) DEFAULT NULL");         
         $migration->addField($newTable, 
                              'value', 
                              "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
      $migration->migrationOneTable($newTable);
 

      
    /*
    * Table glpi_plugin_monitoring_networkports
    */
      $newTable = "glpi_plugin_monitoring_networkports";
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
    * Table glpi_plugin_monitoring_realms
    */
      $newTable = "glpi_plugin_monitoring_realms";
      $insertrealm = 0;
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                     ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
         $DB->query($query);
         $insertrealm = 1;
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
                                 'comment', 
                                 'comment', 
                                 "text DEFAULT NULL COLLATE utf8_unicode_ci");
         $migration->changeField($newTable, 
                                 'date_mod', 
                                 'date_mod', 
                                 "datetime DEFAULT NULL");
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable, 
                              'name', 
                              "varchar(255) DEFAULT NULL");
         $migration->addField($newTable, 
                              'comment', 
                              "text DEFAULT NULL COLLATE utf8_unicode_ci");
         $migration->addField($newTable, 
                              'date_mod', 
                              "datetime DEFAULT NULL");
      $migration->migrationOneTable($newTable);
      if ($insertrealm == '1') {
         $query = "INSERT INTO `glpi_plugin_monitoring_realms` 
            (`id` ,`name` ,`comment` ,`date_mod`) VALUES (NULL , 'All', NULL , NULL)";
         $DB->query($query);
      }
         
      
      
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
         $migration->changeField($newTable, 
                                 'unavailability', 
                                 'unavailability', 
                                 "tinyint(1) NOT NULL DEFAULT '0'");
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
         $migration->addField($newTable, 
                              'unavailability', 
                              "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->addKey($newTable, 
                            "plugin_monitoring_services_id");
         $migration->addKey($newTable,
                            array('plugin_monitoring_services_id',
                                  'date'),
                            "plugin_monitoring_services_id_2");
         $migration->addKey($newTable,
                            array('unavailability',
                                  'state_type',
                                  'plugin_monitoring_services_id'),
                            "unavailability");
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
    * Table glpi_plugin_monitoring_eventhandlers
    */
      $newTable = "glpi_plugin_monitoring_eventhandlers";
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
         $migration->addKey($newTable, 
                            "command_name");
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
    * Table glpi_plugin_monitoring_profiles
    */
      $newTable = "glpi_plugin_monitoring_profiles";
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                        `profiles_id` int(11) NOT NULL DEFAULT '0'
                     ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
         $DB->query($query);
      }
         $migration->changeField($newTable, 
                                 'profiles_id', 
                                 'profiles_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'dashboard', 
                                 'dashboard', 
                                 "char(1) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable, 
                                 'servicescatalog', 
                                 'servicescatalog', 
                                 "char(1) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable, 
                                 'view', 
                                 'view', 
                                 "char(1) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable, 
                                 'componentscatalog', 
                                 'componentscatalog', 
                                 "char(1) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable, 
                                 'viewshomepage', 
                                 'viewshomepage', 
                                 "char(1) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable, 
                                 'weathermap', 
                                 'weathermap', 
                                 "char(1) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable, 
                                 'component', 
                                 'component', 
                                 "char(1) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable, 
                                 'command', 
                                 'command', 
                                 "char(1) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable, 
                                 'config', 
                                 'config', 
                                 "char(1) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable, 
                                 'check', 
                                 'check', 
                                 "char(1) COLLATE utf8_unicode_ci DEFAULT NULL");
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable, 
                              'profiles_id', 
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                              'dashboard', 
                              "char(1) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable, 
                              'servicescatalog', 
                              "char(1) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable, 
                              'view', 
                              "char(1) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable, 
                              'componentscatalog', 
                              "char(1) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable, 
                              'viewshomepage', 
                              "char(1) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable, 
                              'weathermap', 
                              "char(1) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable, 
                              'component', 
                              "char(1) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable, 
                              'command', 
                              "char(1) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable, 
                              'config', 
                              "char(1) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable, 
                              'check', 
                              "char(1) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable, 
                              'allressources', 
                              "char(1) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable, 
                              'restartshinken', 
                              "char(1) COLLATE utf8_unicode_ci DEFAULT NULL");
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
    * Table glpi_plugin_monitoring_unavaibilities
    */
      $newTable = "glpi_plugin_monitoring_unavaibilities";
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
         if (!FieldExists($newTable, "plugin_monitoring_services_id")) {
            $migration->changeField($newTable, 
                                    'items_id', 
                                    'plugin_monitoring_services_id', 
                                    "int(11) NOT NULL DEFAULT '0'");
         }
      $migration->migrationOneTable($newTable);
         $migration->changeField($newTable, 
                                 'plugin_monitoring_services_id', 
                                 'plugin_monitoring_services_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'begin_date', 
                                 'begin_date', 
                                 "datetime DEFAULT NULL");
         $migration->changeField($newTable, 
                                 'end_date', 
                                 'end_date', 
                                 "datetime DEFAULT NULL");
         $migration->dropField($newTable, 
                                 'itemtype');
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable, 
                              'plugin_monitoring_services_id', 
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                              'begin_date', 
                              "datetime DEFAULT NULL");
         $migration->addField($newTable, 
                              'end_date', 
                              "datetime DEFAULT NULL");
         $migration->addField($newTable, 
                              'duration', 
                              "int(15) NOT NULL DEFAULT '0'");
      $migration->migrationOneTable($newTable);
      
      
      
    /*
    * Table glpi_plugin_monitoring_weathermaps
    */
      $newTable = "glpi_plugin_monitoring_weathermaps";
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
                                 'width', 
                                 'width', 
                                 "smallint(6) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'height', 
                                 'height', 
                                 "smallint(6) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'background', 
                                 'background', 
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable, 
                              'name', 
                              "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable, 
                              'width', 
                              "smallint(6) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                              'height', 
                              "smallint(6) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                              'background', 
                              "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
      $migration->migrationOneTable($newTable);
      
      
      
    /*
    * Table glpi_plugin_monitoring_weathermapnodes
    */
      $newTable = "glpi_plugin_monitoring_weathermapnodes";
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
                                 'plugin_monitoring_weathermaps_id', 
                                 'plugin_monitoring_weathermaps_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'items_id', 
                                 'items_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'itemtype', 
                                 'itemtype', 
                                 "varchar(100) DEFAULT NULL");
         $migration->changeField($newTable, 
                                 'x', 
                                 'x', 
                                 "smallint(6) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'y', 
                                 'y', 
                                 "smallint(6) NOT NULL DEFAULT '0'");
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable, 
                              'name', 
                              "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable, 
                              'plugin_monitoring_weathermaps_id', 
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                              'items_id', 
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                              'itemtype', 
                              "varchar(100) DEFAULT NULL");
         $migration->addField($newTable, 
                              'x', 
                              "smallint(6) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                              'y', 
                              "smallint(6) NOT NULL DEFAULT '0'");
      $migration->migrationOneTable($newTable);
      
      

    /*
    * Table glpi_plugin_monitoring_weathermaplinks
    */
      $newTable = "glpi_plugin_monitoring_weathermaplinks";
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
                                 'plugin_monitoring_weathermapnodes_id_1', 
                                 'plugin_monitoring_weathermapnodes_id_1', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'plugin_monitoring_weathermapnodes_id_2', 
                                 'plugin_monitoring_weathermapnodes_id_2', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'plugin_monitoring_services_id', 
                                 'plugin_monitoring_services_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'bandwidth_in', 
                                 'bandwidth_in', 
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable, 
                                 'bandwidth_out', 
                                 'bandwidth_out', 
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable, 
                              'plugin_monitoring_weathermapnodes_id_1', 
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                              'plugin_monitoring_weathermapnodes_id_2', 
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                              'plugin_monitoring_services_id', 
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                              'bandwidth_in', 
                              "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable, 
                              'bandwidth_out', 
                              "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
      $migration->migrationOneTable($newTable);
      
      
      
    /*
    * Table glpi_plugin_monitoring_shinkenwebservices
    */
      $newTable = "glpi_plugin_monitoring_shinkenwebservices";
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                     ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
         $DB->query($query);
      }
         $migration->addField($newTable, 
                              'url', 
                              "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable, 
                              'action', 
                              "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable, 
                              'cnt', 
                              "tinyint(2) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                              'fields_string', 
                              "text DEFAULT NULL COLLATE utf8_unicode_ci");
      $migration->migrationOneTable($newTable);  
      
      
      
    /*
    * Table glpi_plugin_monitoring_tags
    */
      $newTable = "glpi_plugin_monitoring_tags";
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                     ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
         $DB->query($query);
      }
         $migration->addField($newTable, 
                              'tag', 
                              "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable, 
                              'ip', 
                              "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable, 
                              'username', 
                              "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable, 
                              'password', 
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
   
   
   $query = "SELECT * FROM `glpi_calendars`
      WHERE `name`='24x7'
      LIMIT 1";
   $result=$DB->query($query);
   if ($DB->numrows($result) == 0) {
      $calendar = new Calendar();
      $input = array();
      $input['name'] = '24x7';
      $input['is_recursive'] = 1;
      $calendars_id = $calendar->add($input);
      
      $calendarSegment = new CalendarSegment();
      $input = array();
      $input['calendars_id'] = $calendars_id;
      $input['is_recursive'] = 1;
      $input['begin'] = '00:00:00';
      $input['end'] = '24:00:00';
      $input['day'] = '0';
      $calendarSegment->add($input);
      $input['day'] = '1';
      $calendarSegment->add($input);
      $input['day'] = '2';
      $calendarSegment->add($input);
      $input['day'] = '3';
      $calendarSegment->add($input);
      $input['day'] = '4';
      $calendarSegment->add($input);
      $input['day'] = '5';
      $calendarSegment->add($input);
      $input['day'] = '6';
      $calendarSegment->add($input);
   }

      
      
   $crontask = new CronTask();
   if (!$crontask->getFromDBbyName('PluginMonitoringServiceevent', 'updaterrd')) {
      CronTask::Register('PluginMonitoringServiceevent', 'updaterrd', '300', 
                   array('mode' => 2, 'allowmode' => 3, 'logs_lifetime'=> 30));
   }
   if (!$crontask->getFromDBbyName('PluginMonitoringLog', 'cleanlogs')) {
      CronTask::Register('PluginMonitoringLog', 'cleanlogs', '96400', 
                      array('mode' => 2, 'allowmode' => 3, 'logs_lifetime'=> 30));
   }
   if (!$crontask->getFromDBbyName('PluginMonitoringUnavaibility', 'unavaibility')) {
      CronTask::Register('PluginMonitoringUnavaibility', 'unavaibility', '300', 
                      array('mode' => 2, 'allowmode' => 3, 'logs_lifetime'=> 30));
   }
   if (!$crontask->getFromDBbyName('PluginMonitoringDisplayview_rule', 'replayallviewrules')) {
      CronTask::Register('PluginMonitoringDisplayview_rule', 'replayallviewrules', '1200', 
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
   
   include (GLPI_ROOT . "/plugins/monitoring/inc/hostconfig.class.php");
   $pmHostconfig = new PluginMonitoringHostconfig();
   $pmHostconfig->initConfig();
   
   include (GLPI_ROOT . "/plugins/monitoring/inc/host.class.php");
   $pmHost = new PluginMonitoringHost();
   $pmHost->verifyHosts();
   
   
   if ($insertrealm == '1') {
      // Insert into hostconfigs
      $query = "UPDATE `glpi_plugin_monitoring_hostconfigs` 
         SET `plugin_monitoring_realms_id` = '1'
         WHERE `items_id` = '0'
            AND `itemtype` = 'Entity'";
      $DB->query($query);         
   }
   
   include (GLPI_ROOT . "/plugins/monitoring/inc/config.class.php");
   $pmConfig = new PluginMonitoringConfig();
   $pmConfig->initConfig();
   
   
   // * Recalculate unavaibility
      if ($unavaibility_recalculate == 1) {
         $query = "SELECT * FROM `glpi_plugin_monitoring_unavaibilities`
            WHERE `end_date` IS NOT NULL";
         $result = $DB->query($query);
         while ($data=$DB->fetch_array($result)) {
            $time = strtotime($data['end_date']) - strtotime($data['begin_date']);
            $queryd = "UPDATE `glpi_plugin_monitoring_unavaibilities`
               SET `duration`='".$time."'
               WHERE `id`='".$data['id']."'";
            $DB->query($queryd);
         }
      }

   
   $query = "UPDATE `glpi_plugin_monitoring_configs`
      SET `version`='".PLUGIN_MONITORING_VERSION."'
         WHERE `id`='1'";
   $DB->query($query);
}

?>