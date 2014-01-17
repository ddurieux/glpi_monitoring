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
   
   
   
   function getSpecificData($rrdtool_template, $items_id, $which='last') { 
      global $DB;      
   
      // ** Get in table serviceevents
      $mydatat = array();
      $a_labels = array();
      $a_ref = array();
      $pmService = new PluginMonitoringService();
      $pmService->getFromDB($items_id);
      
      $_SESSION['plugin_monitoring_checkinterval'] = PluginMonitoringComponent::getTimeBetween2Checks($pmService->fields['plugin_monitoring_components_id']);
      
      $enddate = date('U');
      $begin = date('Y-m-d H:i:s', $enddate);
      $counters = array();
      
      switch ($which) {
      case 'first': 
         $query = "SELECT * FROM `glpi_plugin_monitoring_serviceevents`
            WHERE `plugin_monitoring_services_id`='".$items_id."'
               AND `state` = 'OK'
            ORDER BY `date` ASC
            LIMIT 1";
         break;
         
      case 'last': 
         $query = "SELECT * FROM `glpi_plugin_monitoring_serviceevents`
            WHERE `plugin_monitoring_services_id`='".$items_id."'
               AND `state` = 'OK'
            ORDER BY `date` DESC
            LIMIT 1";
         break;

      default: 
         return $counters;
         break;
      }
      
      $result = $DB->query($query);
      $ret = $this->getData(
              $result, 
              $rrdtool_template,
              date('Y-m-d H:i:s', $enddate),
              date('Y-m-d H:i:s', $enddate));

      if (is_array($ret) && is_array($ret[0]) && is_array($ret[4])) {
         foreach ($ret[4] as $name=>$data) {
            // Toolbox::logInFile("pm", "$name -> $data = ".$ret[0][$data][0]."\n");
            $counter = array();
            $counter['id'] = preg_replace("/[^A-Za-z0-9\-_]/","",$name);
            $counter['name'] = $data;
            $counter['value'] = $ret[0][$data][0];
            $counters[] = $counter;
         }
      }
   
      return $counters;
   }
      
      
      
   function getData($result, $rrdtool_template, $start_date, $end_date, $ret=array()) {
      global $DB;
      
      if (empty($ret)) {
         $ret = $this->getRef($rrdtool_template);
      }
      $a_ref = $ret[0];
      $a_convert = $ret[1];
      
      $mydatat = array();
      $a_labels = array();
      $a_perfdata_name = array();

      $a_perf = PluginMonitoringPerfdata::getArrayPerfdata($rrdtool_template);
      $previous_timestamp = strtotime($start_date);
      $query_data = array();
      $cnt = 0;
      while ($edata=$DB->fetch_array($result)) {
         $current_timestamp = strtotime($edata['date']);
         $cnt++;
                              
         // Timeup = time between 2 checks + 20%
         $timeup = $_SESSION['plugin_monitoring_checkinterval'] * 1.2;
         while (($previous_timestamp + $timeup) < $current_timestamp) {
            $previous_timestamp += $_SESSION['plugin_monitoring_checkinterval'];
            if ($previous_timestamp < $current_timestamp) {
               $query_data[] = array(
                   'date'      => date('Y-m-d H:i:s', $previous_timestamp),
                   'perf_data' => ''
               );
            }
         }
         $previous_timestamp = $current_timestamp;
         $query_data[] = $edata;
      }

      $timeup = $_SESSION['plugin_monitoring_checkinterval'] * 1.2;
      $current_timestamp = strtotime($end_date);
      while (($previous_timestamp + $timeup) < $current_timestamp) {
         $previous_timestamp += $_SESSION['plugin_monitoring_checkinterval'];
         if ($previous_timestamp < $current_timestamp) {
            $query_data[] = array(
                'date'      => date('Y-m-d H:i:s', $previous_timestamp),
                'perf_data' => ''
            );
         }
      }
      
      foreach ($query_data as $edata) {
         $current_timestamp = strtotime($edata['date']);
         if ($previous_timestamp == '') {
            $previous_timestamp = $current_timestamp;
         }
         $a_perfdata = PluginMonitoringPerfdata::splitPerfdata($edata['perf_data']);
         $a_time = explode(" ", $edata['date']);
         $a_time2 = explode(":", $a_time[1]);
         $day = explode("-", $a_time[0]);
         $a_labels[] = "(".$day[2].")".$a_time2[0].":".$a_time2[1];
         foreach ($a_perf['parseperfdata'] as $num=>$data) {
            // Toolbox::logInFile("pm", "perfdata : $num, ".serialize($data)."\n");
            if (isset($a_perfdata[$num])) {
               $a_perfdata[$num] = trim($a_perfdata[$num], ", ");
               $a_a_perfdata = explode("=", $a_perfdata[$num]);
               $a_a_perfdata[0] = trim($a_a_perfdata[0], "'");
               $regex = 0;
               if (strstr($data['name'], "*")) {
                  $datanameregex = str_replace("*", "(.*)", $data['name']);
                  $regex = 1;
               }
               // $a_perfdata_name[] = $data['name'];
               if (($a_a_perfdata[0] == $data['name']
                       OR $data['name'] == ''
                       OR ($regex == 1
                               AND preg_match("/".$datanameregex."/", $data['name']))
                    )
                       AND isset($a_a_perfdata[1])) {
                     
                  $a_perfdata_final = explode(";", $a_a_perfdata[1]);
                  // New perfdata row, no unit knew.
                  $unity = '';
                  foreach ($a_perfdata_final as $nb_val=>$val) {

                        //No value, no graph
                        if ($val == '') {
                           if ($nb_val >=(count($a_perfdata_final) - 1)) {
                              continue;
                           } else {
                              $val = 0;
                           }
                        }
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

                        $a_perfdata_name[$data['name']] = $data['DS'][$nb_val]['dsname'];
                        
                        if (!isset($mydatat[$data['DS'][$nb_val]['dsname']])) {
                           $mydatat[$data['DS'][$nb_val]['dsname']] = array();
                        }
                        array_push($mydatat[$data['DS'][$nb_val]['dsname']], $val);
                        if ($data['incremental'][$nb_val] == 1) {
                           if (!isset($mydatat[$data['DS'][$nb_val]['dsname']." | diff"])) {
                              $mydatat[$data['DS'][$nb_val]['dsname']." | diff"] = array();
                           }
                           array_push($mydatat[$data['DS'][$nb_val]['dsname']." | diff"], $val);                           
                        }
//                     }
                  }
               } else {
                  for ($nb_val=0; $nb_val < count($data['DS']); $nb_val++) {
                     $a_perfdata_name[$data['name']] = $data['DS'][$nb_val]['dsname'];
                     
                     if (!isset($mydatat[$data['DS'][$nb_val]['dsname']])) {
                        $mydatat[$data['DS'][$nb_val]['dsname']] = array();
                     }
                     array_push($mydatat[$data['DS'][$nb_val]['dsname']], 0);                     
                     if ($data['incremental'][$nb_val] == 1) {
                        if (!isset($mydatat[$data['DS'][$nb_val]['dsname']." | diff"])) {
                           $mydatat[$data['DS'][$nb_val]['dsname']." | diff"] = array();
                        }
                        array_push($mydatat[$data['DS'][$nb_val]['dsname']." | diff"], 0);                           
                     }
                  }                  
               }
            } else {
               for ($nb_val=0; $nb_val < count($data['DS']); $nb_val++) {
                  $a_perfdata_name[$data['name']] = $data['DS'][$nb_val]['dsname'];
                  
                  if (!isset($mydatat[$data['DS'][$nb_val]['dsname']])) {
                     $mydatat[$data['DS'][$nb_val]['dsname']] = array();
                  }
                  array_push($mydatat[$data['DS'][$nb_val]['dsname']], 0);                     
                  if ($data['incremental'][$nb_val] == 1) {
                     if (!isset($mydatat[$data['DS'][$nb_val]['dsname']." | diff"])) {
                        $mydatat[$data['DS'][$nb_val]['dsname']." | diff"] = array();
                     }
                     array_push($mydatat[$data['DS'][$nb_val]['dsname']." | diff"], 0);                           
                  }
               } 
            }        
         }
      }
      foreach ($mydatat as $name=>$data) {
         if (strstr($name, " | diff")) {
            $old_val = -1;
            foreach ($data as $num=>$val) {
               if ($old_val == -1) {
                  $data[$num] = '###';
               } else if ($val < $old_val) {
                  $data[$num] = 0;
               } else {
                  $data[$num] = $val - $old_val;
               }
               if ($data[0] == '###') {
                  $data[0] = $data[$num];
               }
               $old_val = $val;
            }
            $mydatat[$name] = $data;
         }
      }

      $a_perfdata_name = array_unique($a_perfdata_name);
      // Toolbox::logInFile("pm", "a_perfdata_name : ".serialize($a_perfdata_name)."\n");
      // Toolbox::logInFile("pm", "mydatat : ".serialize($mydatat)."\n");
      return array($mydatat, $a_labels, $a_ref, $a_convert, $a_perfdata_name);
   }
   
   
   
   function getRef($rrdtool_template) {
      
      $a_convert = array();
      $a_ref = array();
      return array($a_ref, $a_convert);
      
      $a_perfg = PluginMonitoringPerfdata::getArrayPerfdata($rrdtool_template);
      // Get data 
      $a_convert = array();
      $a_ref = array();
      foreach ($a_perfg['data'][0]['data'] as $data) {
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
