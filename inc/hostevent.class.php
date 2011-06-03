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
      if (count($a_list) == '0') {
         return true;
      } else {
         $a_host = current($a_list);
      }

      $a_list = $this->find("`plugin_monitoring_hosts_id`='".$a_host['id']."'", "date");
      $count = array();
      $last_datetime= '';
      foreach($a_list as $data) {
         if ($last_datetime == '') {
            $last_datetime = $data['date'];
         } else {
            if (strstr($data['event'], ' OK -')) {
               $count['critical'] = $this->convert_datetime($data['date']) - $this->convert_datetime($last_datetime);
            } else {
               $count['ok'] = $this->convert_datetime($data['date']) - $this->convert_datetime($last_datetime);
            }
            $last_datetime = $data['date'];
         }
      }
      if (strstr($data['event'], ' OK -')) {
         $count['ok'] = date('U') - $this->convert_datetime($last_datetime);
      } else {
         $count['critical'] = date('U') - $this->convert_datetime($last_datetime);
      }
      $total = $count['ok'] + $count['critical'];
      echo "OK : ".$count['ok']." seconds (".round(($count['ok'] * 100) / $total, 3)." %)<br/>";
      echo "CRITICAL : ".$count['critical']." seconds (".round(($count['critical'] * 100) / $total, 3)." %)<br/>";
      

      return true;
   }


function convert_datetime($str) {

    list($date, $time) = explode(' ', $str);
    list($year, $month, $day) = explode('-', $date);
    list($hour, $minute, $second) = explode(':', $time);

    $timestamp = mktime($hour, $minute, $second, $month, $day, $year);

    return $timestamp;
} 

}

?>