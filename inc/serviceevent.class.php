<?php

/*
   ----------------------------------------------------------------------
   Monitoring plugin for GLPI
   Copyright (C) 2010-2011 by the GLPI plugin monitoring Team.

   https://forge.indepnet.net/projects/monitoring/
   ----------------------------------------------------------------------

   LICENSE

   This file is part of Monitoring plugin for GLPI.

   Monitoring plugin for GLPI is free software: you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation, either version 2 of the License, or
   any later version.

   Monitoring plugin for GLPI is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with Monitoring plugin for GLPI.  If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------
   Original Author of file: David DURIEUX
   Co-authors of file:
   Purpose of file:
   ----------------------------------------------------------------------
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
      
      $pluginMonitoringRrdtool = new PluginMonitoringRrdtool();
      $pluginMonitoringCommand = new PluginMonitoringCommand();
      $pMonitoringService = new PluginMonitoringService();
      $pmComponent = new PluginMonitoringComponent();
      
      $pMonitoringService->getFromDB($plugin_monitoring_services_id);
      $pmComponent->getFromDB($pMonitoringService->fields['plugin_monitoring_components_id']);
      if (!isset($pmComponent->fields['plugin_monitoring_commands_id'])) {
         return;
      }
      if ($pmComponent->fields['aliasperfdata_commands_id'] > 0) {
         $pluginMonitoringCommand->getFromDB($pmComponent->fields['aliasperfdata_commands_id']);
      } else {
         $pluginMonitoringCommand->getFromDB($pmComponent->fields['plugin_monitoring_commands_id']);
      }   
      
      $query = "SELECT * FROM `".$this->getTable()."`
         WHERE `plugin_monitoring_services_id`='".$plugin_monitoring_services_id."'
         ORDER BY `date`";
      $result = $DB->query($query);
               
      $i = 0;
      while ($edata=$DB->fetch_array($result)) {
         $i++;
         if ($i < $DB->numrows($result)) {

            if (isset($pluginMonitoringCommand->fields['legend'])
                    AND $pluginMonitoringCommand->fields['legend'] != '') {
               $perf_data = $edata['perf_data'];
               if ($edata['perf_data'] == '') {
                  $perf_data = $edata['output'];                     
               }
               $pluginMonitoringRrdtool->addData($pluginMonitoringCommand->getID(), 
                                              $plugin_monitoring_services_id, 
                                              $this->convert_datetime_timestamp($edata['date']), 
                                              $perf_data);
            }
            $this->delete($edata);
         }
      }
   }
   
   
   
   static function cronUpdaterrd() {

      $pMonitoringServiceevent = new PluginMonitoringServiceevent();
      $pMonitoringService = new PluginMonitoringService();
      
      $a_lisths = $pMonitoringService->find();
      foreach ($a_lisths as $data) {
         $pMonitoringServiceevent->parseToRrdtool($data['id']);
      }
      return true;
   }
}

?>