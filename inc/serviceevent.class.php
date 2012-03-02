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

class PluginMonitoringServiceevent extends CommonDBTM {
   

   function convert_datetime_timestamp($str) {

      list($date, $time) = explode(' ', $str);
      list($year, $month, $day) = explode('-', $date);
      list($hour, $minute, $second) = explode(':', $time);

      $timestamp = mktime($hour, $minute, $second, $month, $day, $year);

      return $timestamp;
   }
   

   function calculateUptime($hosts_id, $startDate, $endDate) {
      $a_list = $this->find("`plugin_monitoring_hosts_id`='".$hosts_id."'
         AND `date` > '".date("Y-m-d H:i:s", $startDate)."'
         AND `date` < '".date("Y-m-d H:i:s", $endDate)."'", "date");

      $a_list_before = $this->find("`plugin_monitoring_hosts_id`='".$hosts_id."'
         AND `date` < '".date("Y-m-d H:i:s", $startDate)."'", "date DESC", 1);

      $state_before = '';
      if (count($a_list_before) == '0') {
         $state_before = 'OK';
      } else {
         $datat = current($a_list_before);
         if (strstr($datat['event'], ' OK -')) {
            $state_before = 'OK';
         } else {
            $state_before = 'CRITICAL';
         }
      }

      $count = array();
      $count['critical'] = 0;
      $count['ok'] = 0;
      $last_datetime= date("Y-m-d H:i:s", $startDate);

      foreach($a_list as $data) {
         if (strstr($data['event'], ' OK -')) {
            if ($state_before == "OK") {
               $count['ok'] += $this->convert_datetime_timestamp($data['date']) -
                        $this->convert_datetime_timestamp($last_datetime);
            } else {
               $count['critical'] += $this->convert_datetime_timestamp($data['date']) -
                        $this->convert_datetime_timestamp($last_datetime);
            }
            $state_before = '';
         } else {
            if ($state_before == "CRITICAL") {
               $count['critical'] += $this->convert_datetime_timestamp($data['date']) -
                        $this->convert_datetime_timestamp($last_datetime);
            } else {
               $count['ok'] += $this->convert_datetime_timestamp($data['date']) -
                       $this->convert_datetime_timestamp($last_datetime);
            }
            $state_before = '';
         }
         $last_datetime = $data['date'];

      }
      if (!isset($data['event']) OR strstr($data['event'], ' OK -')) {
         $count['ok'] += date('U') - $this->convert_datetime_timestamp($last_datetime);
      } else {
         $count['critical'] += date('U') - $this->convert_datetime_timestamp($last_datetime);
      }
      $total = $count['ok'] + $count['critical'];
      return array('ok_t'      => $count['ok']." seconds",
                   'critical_t'=> $count['critical']." seconds",
                   'ok_p'      => round(($count['ok'] * 100) / $total, 3),
                   'critical_p'=> round(($count['critical'] * 100) / $total, 3));
      
   }
   
   
   
   function parseToRrdtool($plugin_monitoring_services_id) {
      global $DB;
      
      $pmRrdtool = new PluginMonitoringRrdtool();
      $pmCommand = new PluginMonitoringCommand();
      $pmService = new PluginMonitoringService();
      $pmComponent = new PluginMonitoringComponent();
      
      $pmService->getFromDB($plugin_monitoring_services_id);
      $pmComponent->getFromDB($pmService->fields['plugin_monitoring_components_id']);
      if (!isset($pmComponent->fields['plugin_monitoring_commands_id'])) {
         return;
      }
      if (is_null($pmComponent->fields['graph_template'])) {
         return;
      }
      $pmCommand->getFromDB($pmComponent->fields['plugin_monitoring_commands_id']);
      
      $query = "SELECT * FROM `".$this->getTable()."`
         WHERE `plugin_monitoring_services_id`='".$plugin_monitoring_services_id."'
         ORDER BY `date`";
      $result = $DB->query($query);
               
      $i = 0;
      while ($edata=$DB->fetch_array($result)) {
         $i++;
         if ($i < $DB->numrows($result)) {

            if (!is_null($pmComponent->fields['graph_template'])) {
               $perf_data = $edata['perf_data'];
               if ($edata['perf_data'] == '') {
                  $perf_data = $edata['output'];                     
               }
               $pmRrdtool->addData($pmComponent->fields['graph_template'], 
                                              $plugin_monitoring_services_id, 
                                              $this->convert_datetime_timestamp($edata['date']), 
                                              $perf_data);

            }
            $this->delete($edata);
         }
      }
      $a_list = array();
      $a_list[] = "2h";
      $a_list[] = "12h";
      $a_list[] = "1d";
      $a_list[] = "1w";
      $a_list[] = "1m";
      $a_list[] = "0y6m";
      $a_list[] = "1y";
      
      $pmConfig = new PluginMonitoringConfig();
      $pmConfig->getFromDB(1);
      $a_timezones = importArrayFromDB($pmConfig->fields['timezones']);
      
      foreach ($a_list as $time) {
         foreach ($a_timezones as $timezone) {
            $pmRrdtool->displayGLPIGraph($pmComponent->fields['graph_template'],
                                                       "PluginMonitoringService", 
                                                       $plugin_monitoring_services_id, 
                                                       $timezone,
                                                       $time);
         }
      }
   }
   
   
   
   static function cronUpdaterrd() {

      $pmServiceevent = new PluginMonitoringServiceevent();
      $pmService = new PluginMonitoringService();
      
      $a_lisths = $pmService->find();
      foreach ($a_lisths as $data) {
         $pmServiceevent->parseToRrdtool($data['id']);
      }
      return true;
   }
}

?>