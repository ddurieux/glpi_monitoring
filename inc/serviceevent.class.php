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

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginMonitoringServiceevent extends CommonDBTM {

   static function convert_datetime_timestamp($str) {

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
 
   
   
   static function cronUpdaterrd() {
      ini_set("max_execution_time", "0");
//      $pmServiceevent = new PluginMonitoringServiceevent();
      $pmService = new PluginMonitoringService();
      $pmServicegraph = new PluginMonitoringServicegraph();
      
      $a_lisths = $pmService->find();
      foreach ($a_lisths as $data) {
         $pmServicegraph->parseToDB($data['id']);
      }
      return true;
   }
   
   
   
   function getData($result, $rrdtool_template, $ret=array()) {
      global $DB;
      
      if (empty($ret)) {
         $ret = $this->getRef($rrdtool_template);
      }
      $a_ref = $ret[0];
      $a_convert = $ret[1];
      
      $mydatat = array();
      $a_labels = array();
      $func = "perfdata_".$rrdtool_template;
      if (!method_exists('PluginMonitoringPerfdata', $func)) {
         return array($mydatat, $a_labels, $a_ref, $a_convert);
      }

      $a_json = json_decode(PluginMonitoringPerfdata::$func());
      
      while ($edata=$DB->fetch_array($result)) {
         $a_perfdata = explode(" ", trim($edata['perf_data']));
         $a_time = explode(" ", $edata['date']);
         $a_time2 = explode(":", $a_time[1]);
         $day = explode("-", $a_time[0]);
         array_push($a_labels, "(".$day[2].")".$a_time2[0].":".$a_time2[1]);
         foreach ($a_json->parseperfdata as $num=>$data) {
            if (isset($a_perfdata[$num])) {
               $a_perfdata[$num] = trim($a_perfdata[$num], ", ");
               $a_a_perfdata = explode("=", $a_perfdata[$num]);
               $regex = 0;
               if (strstr($data->name, "*")) {
                  $datanameregex = str_replace("*", "(.*)", $data->name);
                  $regex = 1;
               }
               if (($a_a_perfdata[0] == $data->name
                       OR $data->name == ''
                       OR ($regex == 1
                               AND preg_match("/".$datanameregex."/", $data->name))
                    )
                       AND isset($a_a_perfdata[1])) {
                     
                  $a_perfdata_final = explode(";", $a_a_perfdata[1]);
                  //New perfdata row, no unity knew.
                  $unity = '';
                  foreach ($a_perfdata_final as $nb_val=>$val) {

                        //No value, no graph
                        if ($val == '')
                           continue;
//                     if (isset($a_ref[$data->DS[$nb_val]->dsname])) {
                        $matches = array();
                        preg_match("/^([\d-\.]*)(.*)/",$val,$matches);
                        //Numeric part is data value
                        $val = (float)$matches[1];
                        //Maintain for a same perfdata row, unity data. If set it's normally a new perfdata row.
                        if ($matches[2]) {
                           $unity = $matches[2];
                        }
                        switch ($unity) {                           
                           case 'ms':
                           case 'bps':
                           case 'B' :
                           case "Bits/s" :
                              $val = round($val, 0);
                              break;
                           case '%' :
                              $val = round($val, 2);
                              break;
                           case 'KB' :
                              $val = $val * 1000; // Have in B
                              break;                              
                           case 'MB' :
                              $val = $val * 1000000; // Have in B
                              break;                              
                           case 'TB':
                              $val = $val * 1000000000; // Have in B
                              break;                              
                           case 's' :
                              $val = round($val * 1000, 0);
                              break;                             
                           case 'timeout' :  
                              if ($val > 2) {
                                 $val = round($val);
                              } else {
                                 $val = round($val, 2);
                              }
                              break;                              
                           default :
                              if (!is_numeric($val)) {
                                 $val = 0;
                              } else {
                                 $val = round($val, 2);
                              }
                              break;                                                   
                        }                                                

                        if (!isset($mydatat[$data->DS[$nb_val]->dsname])) {
                           $mydatat[$data->DS[$nb_val]->dsname] = array();
                        }
                        array_push($mydatat[$data->DS[$nb_val]->dsname], $val);
//                     }
                  }
               } else {
                  for ($nb_val=0; $nb_val < count($data->DS); $nb_val++) {
                     if (!isset($mydatat[$data->DS[$nb_val]->dsname])) {
                        $mydatat[$data->DS[$nb_val]->dsname] = array();
                     }
                     array_push($mydatat[$data->DS[$nb_val]->dsname], 0);                     
                  }                  
               }
            } else {
               for ($nb_val=0; $nb_val < count($data->DS); $nb_val++) {
                  if (!isset($mydatat[$data->DS[$nb_val]->dsname])) {
                     $mydatat[$data->DS[$nb_val]->dsname] = array();
                  }
                  array_push($mydatat[$data->DS[$nb_val]->dsname], 0);                     
               } 
            }        
         }
      }
      return array($mydatat, $a_labels, $a_ref, $a_convert);
   }
   
   
   
   function getRef($rrdtool_template) {
      
      $a_convert = array();
      $a_ref = array();
      return array($a_ref, $a_convert);
      
      
      
      $func = "perfdata_".$rrdtool_template;
      $a_jsong = json_decode(PluginMonitoringPerfdata::$func());
      // Get data 
      $a_convert = array();
      $a_ref = array();
      foreach ($a_jsong->data[0]->data as $data) {
         $data = str_replace("'", "", $data);
         if (strstr($data, "DEF")
                 AND !strstr($data, "CDEF")) {
            $a_explode = explode(":", $data);
            $a_name = explode("=", $a_explode[1]);
            if ($a_name[0] == 'outboundtmp') {
               $a_name[0] = 'outbound';
            }
            $a_convert[$a_name[0]] = $a_explode[2];
         }
         if (strstr($data, "AREA")) {
            $a_explode = explode(":", $data);
            $a_split = explode("#", $a_explode[1]);
            $a_ref[$a_convert[$a_split[0]]] = $a_split[1];
         } 
      }
      return array($a_ref, $a_convert);
   }
}

?>
