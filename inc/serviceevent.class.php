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
   
   
   
   function parseToRrdtool($items_id, $itemtype) {
      
      $pluginMonitoringHost = new PluginMonitoringHost();
      $pluginMonitoringRrdtool = new PluginMonitoringRrdtool();
      $pluginMonitoringCommand = new PluginMonitoringCommand();
      $pMonitoringHost_Service = new PluginMonitoringHost_Service();
      $pMonitoringService = new PluginMonitoringService();
      
      $a_hosts = $pluginMonitoringHost->find("`items_id`='".$items_id."'
               AND `itemtype`='".$itemtype."'", "", 1);
      foreach ($a_hosts as $hdata) {
         $a_hostservice = $pMonitoringHost_Service->find("`plugin_monitoring_hosts_id`='".$hdata['id']."'");
         foreach ($a_hostservice as $datahs) {

            $a_events = $this->find("`plugin_monitoring_hosts_services_id`='".$datahs['id']."'", 
                           "date");
            $i = 0;
            foreach ($a_events as $edata) {
               $i++;
               if ($i < count($a_events)) {
                  $pMonitoringHost_Service->getFromDB($edata['plugin_monitoring_hosts_services_id']);
                  $pMonitoringService->getFromDB($pMonitoringHost_Service->fields['plugin_monitoring_services_id']);

                  $pluginMonitoringCommand->getFromDB($pMonitoringService->fields['plugin_monitoring_commands_id']);
                  if ($pluginMonitoringCommand->fields['legend'] != '') {
                     $perf_data = $edata['perf_data'];
                     if ($edata['perf_data'] == '') {
                        $perf_data = $edata['output'];                     
                     }
                     $pluginMonitoringRrdtool->addData($pMonitoringService->fields['plugin_monitoring_commands_id'], 
                                                    'PluginMonitoringHost_Service', 
                                                    $edata['plugin_monitoring_hosts_services_id'], 
                                                    $this->convert_datetime_timestamp($edata['date']), 
                                                    $perf_data);
   //                  $this->delete($edata);
                  }
               }

            }
         }
      }
   }

}

?>