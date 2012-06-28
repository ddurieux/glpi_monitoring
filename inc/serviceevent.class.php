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
   
   
   
   function parseToRrdtool($plugin_monitoring_services_id) {
      global $DB;

      $pmRrdtool = new PluginMonitoringRrdtool();
      $pmCommand = new PluginMonitoringCommand();
      $pmService = new PluginMonitoringService();
      $pmComponent = new PluginMonitoringComponent();
      
      if ($pmService->getFromDB($plugin_monitoring_services_id)) {
         $pmComponent->getFromDB($pmService->fields['plugin_monitoring_components_id']);
         if (!isset($pmComponent->fields['plugin_monitoring_commands_id'])) {
            return;
         }
         if (is_null($pmComponent->fields['graph_template'])) {
            return;
         }
         $pmCommand->getFromDB($pmComponent->fields['plugin_monitoring_commands_id']);

         $pmUnavaibility = new PluginMonitoringUnavaibility();
         $pmUnavaibility->runUnavaibility($plugin_monitoring_services_id);

         $query = "SELECT * FROM `".$this->getTable()."`
            WHERE `plugin_monitoring_services_id`='".$plugin_monitoring_services_id."'
            ORDER BY `date`";
         $result = $DB->query($query);

         $i = 0;
         $nb_rows = $DB->numrows($result);
         $rrdtool_value = '';
         $last_date = '';
         while ($edata=$DB->fetch_array($result)) {
            $i++;
            if ($edata['unavailability'] == '0') {
               if ($last_date != '') {
                  $pmRrdtool->addData($pmComponent->fields['graph_template'], 
                                      $plugin_monitoring_services_id, 
                                      0, 
                                      '',
                                      $rrdtool_value,
                                      1);
               }
               break;
            }
            
            $perf_data = $edata['perf_data'];
            if ($edata['perf_data'] == '') {
               $perf_data = $edata['output'];                     
            }
            if ($edata['unavailability'] != '2'
                    AND $i < $nb_rows) {
               $rrdtool_value = $pmRrdtool->addData($pmComponent->fields['graph_template'], 
                                              $plugin_monitoring_services_id, 
                                              $this->convert_datetime_timestamp($edata['date']), 
                                              $perf_data,
                                              $rrdtool_value,
                                              0);
            }
            $last_date = $edata['date'];
            if ($i == $nb_rows) {
               if ($edata['unavailability'] != '2') {
                  $input = array();
                  $input['id'] = $edata['id'];
                  $input['unavailability'] = 2;
                  $this->update($input);
                  
                  $pmRrdtool->addData($pmComponent->fields['graph_template'], 
                                      $plugin_monitoring_services_id, 
                                      $this->convert_datetime_timestamp($edata['date']), 
                                      $perf_data,
                                      $rrdtool_value,
                                      1);
                  
                  $queryd = "DELETE FROM `".$this->getTable()."`
                     WHERE `plugin_monitoring_services_id`='".$plugin_monitoring_services_id."'
                        AND `date`<'".$edata['date']."'";
                  $DB->query($queryd);
               }
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
      } else {
         $query = "DELETE FROM `".$this->getTable()."`
            WHERE `plugin_monitoring_services_id`='".$plugin_monitoring_services_id."'";
         $DB->query($query);
      }
   }
   
   
   
   static function cronUpdaterrd() {
      ini_set("max_execution_time", "0");
//      $pmServiceevent = new PluginMonitoringServiceevent();
      $pmService = new PluginMonitoringService();
      $pmServicegraph = new PluginMonitoringServicegraph();
      
      $a_lisths = $pmService->find();
      foreach ($a_lisths as $data) {
         $pmServicegraph->parseToDB($data['id']);
         //$pmServiceevent->parseToRrdtool($data['id']);
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
      $a_filenamej = explode("-", $rrdtool_template);
      $filenamej = GLPI_PLUGIN_DOC_DIR."/monitoring/templates/".$a_filenamej[0]."-perfdata.json";
      if (!file_exists($filenamej)) {
         return;
      }
      $a_json = json_decode(file_get_contents($filenamej));
      
      while ($edata=$DB->fetch_array($result)) {
         $a_perfdata = explode(" ", $edata['perf_data']);
         $a_time = explode(" ", $edata['date']);
         $a_time2 = explode(":", $a_time[1]);
         array_push($a_labels, $a_time2[0].":".$a_time2[1]);
         foreach ($a_json->parseperfdata as $num=>$data) {
            if (isset($a_perfdata[$num])) {
               $a_a_perfdata = explode("=", $a_perfdata[$num]);
               if ($a_a_perfdata[0] == $data->name) {
                  $a_perfdata_final = explode(";", $a_a_perfdata[1]);
                  foreach ($a_perfdata_final as $nb_val=>$val) {
                     if (isset($a_ref[$data->DS[$nb_val]->dsname])) {
                        if ($val != '') {
                           if (strstr($val, "ms")) {
                              $val = round(str_replace("ms", "", $val),0);
                           } else if (strstr($val, "bps")) {
                              $val = round(str_replace("bps", "", $val),0);
                           } else if (strstr($val, "s")) {
                              $val = round((str_replace("s", "", $val) * 1000),0);
                           } else if (strstr($val, "%")) {
                              $val = round(str_replace("%", "", $val),0);
                           } else if (!strstr($val, "timeout")){
                              $val = round($val);
                           } else {
                              $val = 0;
                           }
                           if (!isset($mydatat[$data->DS[$nb_val]->dsname])) {
                              $mydatat[$data->DS[$nb_val]->dsname] = array();
                           }
                           array_push($mydatat[$data->DS[$nb_val]->dsname], $val);
                        }
                     }
                  }
               }
            }         
         }
      }
      return array($mydatat, $a_labels, $a_ref, $a_convert);
   }
   
   
   
   function getRef($rrdtool_template) {

      $filename = GLPI_PLUGIN_DOC_DIR."/monitoring/templates/".$rrdtool_template."_graph.json";
      
      $a_jsong = json_decode(file_get_contents($filename));
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