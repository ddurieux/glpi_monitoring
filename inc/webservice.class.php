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

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}

class PluginMonitoringWebservice {


   static function methodShinkenGetConffiles($params, $protocol) {
      global $LANG, $CFG_GLPI;

      if (isset ($params['help'])) {
         return array('file'  => 'config filename to get : commands.cfg, hosts.cfg',
                      'help'    => 'bool,optional');
      }

      ini_set("max_execution_time", "0");
      ini_set("memory_limit", "-1");
      $pluginMonitoringShinken = new PluginMonitoringShinken();
      switch ($params['file']) {

         case 'commands.cfg':
            $array = $pluginMonitoringShinken->generateCommandsCfg(1);
            return array($array[0]=>$array[1]);
            break;

         case 'hosts.cfg':
            $array = $pluginMonitoringShinken->generateHostsCfg(1);
            return array($array[0]=>$array[1]);
            break;

         case 'contacts.cfg':
            $array = $pluginMonitoringShinken->generateContactsCfg(1);
            return array($array[0]=>$array[1]);
            break;

         case 'timeperiods.cfg':
            $array = $pluginMonitoringShinken->generateTimeperiodsCfg(1);
            return array($array[0]=>$array[1]);
            break;
         
         case 'services.cfg':
            $array = $pluginMonitoringShinken->generateServicesCfg(1);
            return array($array[0]=>$array[1]);
            break;
         
         case 'templates.cfg':
            $array = $pluginMonitoringShinken->generateTemplatesCfg(1);
            return array($array[0]=>$array[1]);
            break;

         case 'all':
            $output = array();
            $array = $pluginMonitoringShinken->generateCommandsCfg(1);
            $output[$array[0]] = $array[1];
            $array = $pluginMonitoringShinken->generateHostsCfg(1);
            $output[$array[0]] = $array[1];
            $array = $pluginMonitoringShinken->generateContactsCfg(1);
            $output[$array[0]] = $array[1];
            $array = $pluginMonitoringShinken->generateTimeperiodsCfg(1);
            $output[$array[0]] = $array[1];
            $array = $pluginMonitoringShinken->generateTemplatesCfg(1);
            $output[$array[0]] = $array[1];
            $array = $pluginMonitoringShinken->generateServicesCfg(1);
            $output[$array[0]] = $array[1];
            return $output;
            break;

      }
   }


   static function methodShinkenCommands($params, $protocol) {
      global $LANG, $CFG_GLPI;

      $pluginMonitoringShinken = new PluginMonitoringShinken();
      $array = $pluginMonitoringShinken->generateCommandsCfg();
      return $array;
   }

   
   
   static function methodShinkenHosts($params, $protocol) {
      global $LANG, $CFG_GLPI;

      $pluginMonitoringShinken = new PluginMonitoringShinken();
      $array = $pluginMonitoringShinken->generateHostsCfg();
      return $array;
   }
   
   
   
   static function methodShinkenServices($params, $protocol) {
      global $LANG, $CFG_GLPI;

      $pluginMonitoringShinken = new PluginMonitoringShinken();
      $array = $pluginMonitoringShinken->generateServicesCfg();
      return $array;
   }



   static function methodShinkenTemplates($params, $protocol) {
      global $LANG, $CFG_GLPI;

      $pluginMonitoringShinken = new PluginMonitoringShinken();
      $array = $pluginMonitoringShinken->generateTemplatesCfg();
      return $array;
   }
   
   
   
   static function methodShinkenContacts($params, $protocol) {
      global $LANG, $CFG_GLPI;

      $pluginMonitoringShinken = new PluginMonitoringShinken();
      $array = $pluginMonitoringShinken->generateContactsCfg();
      return $array;
   }



   static function methodShinkenTimeperiods($params, $protocol) {
      global $LANG, $CFG_GLPI;

      $pluginMonitoringShinken = new PluginMonitoringShinken();
      $array = $pluginMonitoringShinken->generateTimeperiodsCfg();
      return $array;
   }
   
   
   
   static function methodDashboard($params, $protocol) {
      $array = array();
      
      if (!isset($params['view'])) {
         return array();
      }
      
      $pm = new PluginMonitoringDisplay();
      $array = $pm->displayCounters($params['view'], 0);
            
      return $array;
   }
   
   
   
   static function methodGetServicesList($params, $protocol) {
      
      $array = PluginMonitoringWebservice::getServicesList($params['statetype'], $params['view']);
      
      return $array;
   }
   
   
   
   static function getServicesList($statetype, $view) {
      global $DB;
      
      $services = array();
      
      if ($view == 'Ressources') {
         
         switch ($statetype) {
            
            case "ok":
               $query = "SELECT * FROM `glpi_plugin_monitoring_services`
                  LEFT JOIN `glpi_plugin_monitoring_componentscatalogs_hosts` 
                     ON `plugin_monitoring_componentscatalogs_hosts_id`= 
                        `glpi_plugin_monitoring_componentscatalogs_hosts`.`id`
                  WHERE (`state`='OK' OR `state`='UP') AND `state_type`='HARD'";
               $result = $DB->query($query);
               while ($data=$DB->fetch_array($result)) {
                  $itemtype = $data['itemtype'];
                  $item = new $itemtype();
                  $item->getFromDB($data['items_id']);
                  
                  $services[] = "(".$itemtype.") ".$item->getName()."\n=> ".$data['name'];
               }
               break;

            case "warning":
               $query = "SELECT * FROM `glpi_plugin_monitoring_services`
                  LEFT JOIN `glpi_plugin_monitoring_componentscatalogs_hosts` 
                     ON `plugin_monitoring_componentscatalogs_hosts_id`= 
                        `glpi_plugin_monitoring_componentscatalogs_hosts`.`id`
                  WHERE (`state`='WARNING' OR `state`='UNKNOWN' OR `state`='RECOVERY' OR `state`='FLAPPING' OR `state` IS NULL)
                    AND `state_type`='HARD'";
               $result = $DB->query($query);
               while ($data=$DB->fetch_array($result)) {
                  $itemtype = $data['itemtype'];
                  $item = new $itemtype();
                  $item->getFromDB($data['items_id']);
                  
                  $services[] = "(".$itemtype.") ".$item->getName()."\n=> ".$data['name'];
               }
               break;
            
            case "critical":
               $query = "SELECT * FROM `glpi_plugin_monitoring_services`
                  LEFT JOIN `glpi_plugin_monitoring_componentscatalogs_hosts` 
                     ON `plugin_monitoring_componentscatalogs_hosts_id`= 
                        `glpi_plugin_monitoring_componentscatalogs_hosts`.`id`
                  WHERE (`state`='DOWN' OR `state`='UNREACHABLE' OR `state`='CRITICAL' OR `state`='DOWNTIME')
                    AND `state_type`='HARD'";
               $result = $DB->query($query);
               while ($data=$DB->fetch_array($result)) {
                  $itemtype = $data['itemtype'];
                  $item = new $itemtype();
                  $item->getFromDB($data['items_id']);
                  
                  $services[] = "(".$itemtype.") ".$item->getName()."\n=> ".$data['name'];
               }
               break;
         }
         
      } else if ($view == 'Componentscatalog') {
         $pmComponentscatalog_Host = new PluginMonitoringComponentscatalog_Host();
         $queryCat = "SELECT * FROM `glpi_plugin_monitoring_componentscatalogs`";
         $resultCat = $DB->query($queryCat);
         while ($data=$DB->fetch_array($resultCat)) { 

            $query = "SELECT * FROM `".$pmComponentscatalog_Host->getTable()."`
               WHERE `plugin_monitoring_componentscalalog_id`='".$data['id']."'";
            $result = $DB->query($query);
            $state = array();
            $state['ok'] = 0;
            $state['warning'] = 0;
            $state['critical'] = 0;
            while ($dataComponentscatalog_Host=$DB->fetch_array($result)) {            

               $state['ok'] += countElementsInTable("glpi_plugin_monitoring_services", 
                       "(`state`='OK' OR `state`='UP') AND `state_type`='HARD'
                          AND `plugin_monitoring_componentscatalogs_hosts_id`='".$dataComponentscatalog_Host['id']."'");


               $state['warning'] += countElementsInTable("glpi_plugin_monitoring_services", 
                       "(`state`='WARNING' OR `state`='UNKNOWN' OR `state`='RECOVERY' OR `state`='FLAPPING' OR `state` IS NULL)
                          AND `state_type`='HARD'
                          AND `plugin_monitoring_componentscatalogs_hosts_id`='".$dataComponentscatalog_Host['id']."'");

               $state['critical'] += countElementsInTable("glpi_plugin_monitoring_services", 
                       "(`state`='DOWN' OR `state`='UNREACHABLE' OR `state`='CRITICAL' OR `state`='DOWNTIME')
                          AND `state_type`='HARD'
                          AND `plugin_monitoring_componentscatalogs_hosts_id`='".$dataComponentscatalog_Host['id']."'");

            }
            if ($state['critical'] > 0) {
               if ($statetype == 'critical') {
                  $services[] = "(Catalog) ".$data['name'];
               }
            } else if ($state['warning'] > 0) {
               if ($statetype == 'warning') {
                  $services[] = "(Catalog) ".$data['name'];
               }
            } else if ($state['ok'] > 0) {
               if ($statetype == 'ok') {
                  $services[] = "(Catalog) ".$data['name'];
               }
            }
         }
      } else if ($view == 'Businessrules') {
         
      }
      return $services;
   }
}

?>
