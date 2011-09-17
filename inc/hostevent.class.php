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

class PluginMonitoringHostevent extends CommonDBTM {
   

   /**
   * Display form for agent configuration
   *
   * @param $items_id integer ID 
   * @param $options array
   *
   *@return bool true if form is ok
   *
   **/
   function showForm($item) {
      global $DB,$CFG_GLPI,$LANG;

      $pluginMonitoringHost = new PluginMonitoringHost();
      $a_list = $pluginMonitoringHost->find("`itemtype`='".get_class($item)."'
         AND `items_id`='".$item->fields['id']."'", "", 1);
      $a_host = array();
      if (count($a_list) == '0') {
         return true;
      } else {
         $a_host = current($a_list);
      }

      echo "<table class='tab_cadre_fixe'>";
      echo "<tr class='tab_bg_1'>";
      echo "<th></th>";
      echo "<th>".$LANG['plugin_monitoring']['host'][13]." (%)</th>";
      echo "<th>".$LANG['plugin_monitoring']['host'][14]." (%)</th>";
      echo "<th>".$LANG['plugin_monitoring']['host'][13]." (".$LANG['plugin_monitoring']['host'][17].")</th>";
      echo "<th>".$LANG['plugin_monitoring']['host'][14]." (".$LANG['plugin_monitoring']['host'][17].")</th>";
      echo "</tr>";

      $time_list = array();
      $time_list[] = "day";
      $time_list[] = "week";
      $time_list[] = "month";
      $time_list[] = "6 months";
      $time_list[] = "year";

      foreach ($time_list as $time) {
         echo "<tr class='tab_bg_1'>";
         $now = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
         switch ($time) {

            case 'day':
               echo "<th>".$LANG['plugin_monitoring']['host'][15]."</th>";
               $startDate = mktime(date('H'),date('i'),date('s'),date('m'),date('d')-1,date('Y'));
               break;

            case 'week':
               echo "<th>".$LANG['plugin_monitoring']['host'][16]."</th>";
               $startDate = mktime(date('H'),date('i'),date('s'),date('m'),date('d')-7,date('Y'));
               break;

            case 'month':
               echo "<th>".$LANG['plugin_monitoring']['host'][10]."</th>";
               $startDate = mktime(date('H'),date('i'),date('s'),date('m')-1,date('d'),date('Y'));
               break;

            case '6 months':
               echo "<th>".$LANG['plugin_monitoring']['host'][11]."</th>";
               $startDate = mktime(date('H'),date('i'),date('s'),date('m')-6,date('d'),date('Y'));
               break;

            case 'year':
               echo "<th>".$LANG['plugin_monitoring']['host'][12]."</th>";
               $startDate = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y')-1);
               break;

         }
         $array = $this->calculateUptime($a_host['id'],$startDate,$now);
         echo "<td align='center'>".$array['ok_p']."</td>";
         echo "<td align='center'>".$array['critical_p']."</td>";
         echo "<td align='center'>".$array['ok_t']."</td>";
         echo "<td align='center'>".$array['critical_t']."</td>";
         echo "</tr>";
      }

      echo "</table>";
      

      return true;
   }



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
      
      $a_hosts = $pluginMonitoringHost->find("`items_id`='".$items_id."'
               AND `itemtype`='".$itemtype."'", "", 1);
      foreach ($a_hosts as $hdata) {
         $a_events = $this->find("`plugin_monitoring_hosts_id`='".$hdata['id']."'", 
                        "date");
         $i = 0;
         foreach ($a_events as $edata) {
            $i++;
            if ($i < count($a_events)) {
               $pluginMonitoringCommand->getFromDB($hdata['plugin_monitoring_commands_id']);
               if ($pluginMonitoringCommand->fields['legend'] != '') {
                  $perf_data = $edata['perf_data'];
                  if ($edata['perf_data'] == '') {
                     $perf_data = $edata['output'];                     
                  }
                  $pluginMonitoringRrdtool->addData($hdata['plugin_monitoring_commands_id'], 
                                                 $hdata['itemtype'], 
                                                 $hdata['items_id'], 
                                                 $this->convert_datetime_timestamp($edata['date']), 
                                                 $perf_data);
//                  $this->delete($edata);
               }
            }
            
         }
      }
   }

}

?>