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

class PluginMonitoringServicegraph extends CommonDBTM {
   private $jsongraph_a_ref = array();
   private $jsongraph_a_convert = array();

   
   function displayGraph($rrdtool_template, $itemtype, $items_id, $timezone, $time='1d', $part='', $width='900') {
      global $CFG_GLPI;

      $pmComponent = new PluginMonitoringComponent();
//      if (isset($_GET['itemtype'])) {
//         $itemtype = $_GET['itemtype'];
//         $items_id = $_GET['items_id'];
//      }
      $item = new $itemtype();
      if ($item->getFromDB($items_id)) {
         $pmComponent->getFromDB($item->fields['plugin_monitoring_components_id']);
         if ($part == ''
                 OR $part == 'div') {
            echo '<div id="chart'.$items_id.$time.'">'.
                '<svg style="height: 300px; width: '.$width.'px;"></svg>'.
              '</div>';

            echo "<div id=\"updategraph".$items_id.$time."\"></div>";
         }
         if ($part == ''
                 OR $part == 'js') {
            echo "<script type=\"text/javascript\">

            var el".$items_id.$time." = Ext.get(\"updategraph".$items_id.$time."\");
            var mgr".$items_id.$time." = el".$items_id.$time.".getUpdateManager();
            mgr".$items_id.$time.".loadScripts=true;
            mgr".$items_id.$time.".showLoadIndicator=false;
               ";
            $this->startAutoRefresh($rrdtool_template, $itemtype, $items_id, $timezone, $time,$pmComponent->fields['id']);
            echo "
            </script>";
         }
      }
      return;
   }
   
   
   
   function startAutoRefresh($rrdtool_template, $itemtype, $items_id, $timezone, $time, $pmComponents_id) {
      global $CFG_GLPI;
      
      echo "mgr".$items_id.$time.".startAutoRefresh(50, \"".$CFG_GLPI["root_doc"].
                 "/plugins/monitoring/ajax/updateChart.php\", ".
                 "\"rrdtool_template=".$rrdtool_template.
                 "&itemtype=".$itemtype.
                 "&items_id=".$items_id.
                 "&timezone=".$timezone.
                 "&time=".$time.
                 "&customdate=\" + document.getElementById('custom_date').textContent + \"".
                 "&customtime=\" + document.getElementById('custom_time').textContent + \"".
                 "&components_id=".$pmComponents_id."\", \"\", true);
                    ";
   }
      
      
      
   function generateData($rrdtool_template, $itemtype, $items_id, $timezone, $time, $enddate='') { 
      global $DB;      

      if ($enddate == '') {
         $enddate = date('U');
      }
      
      // Manage timezones
      $converttimezone = '0';
      if (strstr($timezone, '-')) {
         $timezone_temp = str_replace("-", "", $timezone);
         $converttimezone = ($timezone_temp * 3600);
         $timezone = str_replace("-", "+", $timezone);
      } else if (strstr($timezone, '+')) {
         $timezone_temp = str_replace("+", "", $timezone);
         $converttimezone = ($timezone_temp * 3600);
         $timezone = str_replace("+", "-", $timezone);
      }

      // ** Get in table serviceevents
      $mydatat = array();
      $a_labels = array();
      $a_ref = array();
      $pmServiceevent = new PluginMonitoringServiceevent();
      $pmService = new PluginMonitoringService();
      $pmService->getFromDB($items_id);
      
      $dateformat = "%Y-%m-%d %Hh";
      
      $begin = '';
      switch ($time) {
         
         case '2h':
            $begin = date('Y-m-d H:i:s', $enddate - (2 * 3600));
            $dateformat = "(%d)%H:%M";
            
            $query = "SELECT * FROM `glpi_plugin_monitoring_serviceevents`
               WHERE `plugin_monitoring_services_id`='".$items_id."'
                  AND `date` > '".$begin."'
                  AND `date` <= '".date('Y-m-d H:i:s', $enddate)."'
               ORDER BY `date`";
            $result = $DB->query($query);
            $ret = array();
            if (isset($this->jsongraph_a_ref[$rrdtool_template])) {
               $ret = $pmServiceevent->getData($result, $rrdtool_template,
                       array($this->jsongraph_a_ref[$rrdtool_template], 
                             $this->jsongraph_a_convert[$rrdtool_template]));
            } else {
               $ret = $pmServiceevent->getData($result, $rrdtool_template);
            }
            if (is_array($ret)) {
               $mydatat  = $ret[0];
               $a_labels = $ret[1];
               $a_ref    = $ret[2];
               if (!isset($this->jsongraph_a_ref[$rrdtool_template])) {
                  $this->jsongraph_a_ref[$rrdtool_template] = $ret[2];
                  $this->jsongraph_a_convert[$rrdtool_template] = $ret[3];
               }
            }
            break;
         
         case '12h':
            $begin = date('Y-m-d H:i:s', $enddate - (12 * 3600));
            $dateformat = "(%d)%H:%M";
            
            $query = "SELECT * FROM `glpi_plugin_monitoring_serviceevents`
               WHERE `plugin_monitoring_services_id`='".$items_id."'
                  AND `date` > '".$begin."'
                  AND `date` <= '".date('Y-m-d H:i:s', $enddate)."'
               ORDER BY `date`";
            $result = $DB->query($query);
            $ret = $pmServiceevent->getData($result, $rrdtool_template);
            if (is_array($ret)) {
               $mydatat  = $ret[0];
               $a_labels = $ret[1];
               $a_ref    = $ret[2];
            }
            break;
         
         case '1d':
            $begin = date('Y-m-d H:i:s', $enddate - (24 * 3600));
            $dateformat = "(%d)%H:%M";
            
            $query = "SELECT * FROM `glpi_plugin_monitoring_serviceevents`
               WHERE `plugin_monitoring_services_id`='".$items_id."'
                  AND `date` > '".$begin."'
                  AND `date` <= '".date('Y-m-d H:i:s', $enddate)."'
               ORDER BY `date`";
            $result = $DB->query($query);
            $ret = $pmServiceevent->getData($result, $rrdtool_template);
            if (is_array($ret)) {
               $mydatat  = $ret[0];
               $a_labels = $ret[1];
               $a_ref    = $ret[2];
            }
            break;
         
         case '1w':
            $begin = date('Y-m-d H:i:s', date('U') - (7 * 24 * 3600));
            $display_month = 0;
            $dateformat = "(%d) %Hh";
            if (date('m', date('U') - (7 * 24 * 3600)) != date('m', date('U'))) {
               $display_month = 1;
               $dateformat = "%m-%d %Hh";
            }
            
            $query = "SELECT * FROM `".$this->getTable()."`
               WHERE `plugin_monitoring_services_id`='".$items_id."'
                  AND `type`='6h'
               ORDER BY `date`";
            $result = $DB->query($query);
            while ($edata=$DB->fetch_array($result)) {
               $dat = importArrayFromDB($edata['data']);
               $datemod = $edata['date'];
               $daynum = Calendar::getDayNumberInWeek(PluginMonitoringServiceevent::convert_datetime_timestamp($edata['date']));
               $split = explode(' ', $datemod);
               $split2 = explode(':', $split[1]);
               $splitymd = explode('-', $split[0]);
               $dateymd = "(".$splitymd[2].")";
               if ($display_month == 1) {
                  $dateymd = $splitymd[1]."-".$splitymd[2];
               }
               array_push($a_labels, $dateymd." ".$split2[0].'h');
               if (count($dat) == 0) {
                  $a_perfnames = PluginMonitoringServicegraph::getperfdataNames($rrdtool_template);
                  foreach ($a_perfnames as $name) {                     
                     if (!isset($mydatat[$name])) {
                        $mydatat[$name] = array();
                     }
                     array_push($mydatat[$name], '');
                  }
                  
               } else {
                  foreach ($dat as $name=>$value) {
                     if (!isset($mydatat[$name])) {
                        $mydatat[$name] = array();
                     }
                     array_push($mydatat[$name], $value);
                  }
               }
            }
            $ret = $pmServiceevent->getRef($rrdtool_template);
            break;

         case '1m':
            $begin = date('Y-m-d H:i:s', date('U') - (30 * 24 * 3600));
            $display_year = 0;
            $dateformat = "%m-%d %Hh";
            if (date('Y', date('U') - (7 * 24 * 3600)) != date('Y', date('U'))) {
               $display_year = 1;
               $dateformat = "%Y-%m-%d %Hh";
            }
            
            $query = "SELECT * FROM `".$this->getTable()."`
               WHERE `plugin_monitoring_services_id`='".$items_id."'
                  AND `type`='1d'
               ORDER BY `date`";
            $result = $DB->query($query);
            while ($edata=$DB->fetch_array($result)) {
               $dat = importArrayFromDB($edata['data']);
               $datemod = $edata['date'];
//               $daynum = Calendar::getDayNumberInWeek(PluginMonitoringServiceevent::convert_datetime_timestamp($edata['date']));
               $split = explode(' ', $datemod);
               $split2 = explode(':', $split[1]);
               $day = explode("-", $split[0]);
               $dateymd = $day[1]."-".$day[2];
               if ($display_year == 1) {
                  $dateymd = $split[0];
               }
               array_push($a_labels, $dateymd." ".$split2[0].'h');
               if (count($dat) == 0) {
                  $a_perfnames = PluginMonitoringServicegraph::getperfdataNames($rrdtool_template);
                  foreach ($a_perfnames as $name) {                     
                     if (!isset($mydatat[$name])) {
                        $mydatat[$name] = array();
                     }
                     array_push($mydatat[$name], '');
                  }
                  
               } else {
                  foreach ($dat as $name=>$value) {
                     if (!isset($mydatat[$name])) {
                        $mydatat[$name] = array();
                     }
                     array_push($mydatat[$name], $value);
                  }
               }
            }
            $ret = $pmServiceevent->getRef($rrdtool_template);
            break;
         
         case '0y6m':
            $begin = date('Y-m-d H:i:s', date('U') - ((364 / 2) * 24 * 3600));

            $query = "SELECT * FROM `".$this->getTable()."`
               WHERE `plugin_monitoring_services_id`='".$items_id."'
                  AND `type`='5d'
               ORDER BY `date`";
            $result = $DB->query($query);
            while ($edata=$DB->fetch_array($result)) {
               $dat = importArrayFromDB($edata['data']);
               $datemod = $edata['date'];
               $daynum = date('m', PluginMonitoringServiceevent::convert_datetime_timestamp($edata['date']));
               $daynum = $daynum - 1;
               $split = explode(' ', $datemod);
               $split2 = explode(':', $split[1]);
               $day = explode("-", $split[0]);
               array_push($a_labels, $split[0]." ".$split2[0].'h');
               if (count($dat) == 0) {
                  $a_perfnames = PluginMonitoringServicegraph::getperfdataNames($rrdtool_template);
                  foreach ($a_perfnames as $name) {                     
                     if (!isset($mydatat[$name])) {
                        $mydatat[$name] = array();
                     }
                     array_push($mydatat[$name], '');
                  }
                  
               } else {
                  foreach ($dat as $name=>$value) {
                     if (!isset($mydatat[$name])) {
                        $mydatat[$name] = array();
                     }
                     array_push($mydatat[$name], $value);
                  }
               }
            }
            $ret = $pmServiceevent->getRef($rrdtool_template);
            $a_ref = $ret[0];
            break;
         
         case '1y':
            $begin = date('Y-m-d H:i:s', date('U') - (365 * 24 * 3600));
            
            $query = "SELECT * FROM `".$this->getTable()."`
               WHERE `plugin_monitoring_services_id`='".$items_id."'
                  AND `type`='10d'
               ORDER BY `date`";
            $result = $DB->query($query);
            while ($edata=$DB->fetch_array($result)) {
               $dat = importArrayFromDB($edata['data']);
               $datemod = $edata['date'];
               $daynum = date('m', PluginMonitoringServiceevent::convert_datetime_timestamp($edata['date']));
               $daynum = $daynum - 1;
               $split = explode(' ', $datemod);
               $split2 = explode(':', $split[1]);
               $day = explode("-", $split[0]);
               array_push($a_labels, $split[0]." ".$split2[0].'h');
               if (count($dat) == 0) {
                  $a_perfnames = PluginMonitoringServicegraph::getperfdataNames($rrdtool_template);
                  foreach ($a_perfnames as $name) {                     
                     if (!isset($mydatat[$name])) {
                        $mydatat[$name] = array();
                     }
                     array_push($mydatat[$name], '');
                  }
                  
               } else {
                  foreach ($dat as $name=>$value) {
                     if (!isset($mydatat[$name])) {
                        $mydatat[$name] = array();
                     }
                     array_push($mydatat[$name], $value);
                  }
               }
            }
            $ret = $pmServiceevent->getRef($rrdtool_template);
            break;
         
      }       
      return array($mydatat, $a_labels, $dateformat);      
   }
   
   
   
   function parseToDB($plugin_monitoring_services_id) {
      global $DB;

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

         $pmUnavaibility = new PluginMonitoringUnavaibility();
         $pmUnavaibility->runUnavaibility($plugin_monitoring_services_id);

         $a_dateold = array();
         
         // *** 1 day (x 30 min)
            $dateold = date('Y-m-d H:i:s',mktime(date('H'),
                                                 date('i'),
                                                 date('s'),
                                                 date('m'),
                                                 (date('d') - 1),
                                                 date('Y')));
            $a_dateold['30m'] = $dateold;
            // Gest last value, and see if we must calculate new values
            $query = "SELECT * FROM `".$this->getTable()."`
               WHERE `plugin_monitoring_services_id`='".$plugin_monitoring_services_id."'
                  AND `type`='30m'
               ORDER BY `date` DESC
               LIMIT 1";
            $result = $DB->query($query);
            $lastdate = $dateold;
            $new_date = '';
            if ($DB->numrows($result) == '1') {
               $data = $DB->fetch_assoc($result);
               $new_date = PluginMonitoringServiceevent::convert_datetime_timestamp($data['date']);
               $new_date += (30*60);
            } else {
               $split = explode(" ", $lastdate);
               $a_time = explode(":", $split[1]);
               $a_date = explode("-", $split[0]);
               $new_date = date('U', mktime($a_time[0],
                                            '0',
                                            '0',
                                            $a_date[1],
                                            $a_date[2],
                                            $a_date[0]));
            }
            
            //get data in serviceevents for each 30 minutes from this date to now
            while (($new_date + (15*60)) < date('U')) {

               $query = "SELECT * FROM `glpi_plugin_monitoring_serviceevents`
                  WHERE `plugin_monitoring_services_id`='".$plugin_monitoring_services_id."'
                     AND `date` >= '".(date('Y-m-d H:i:m', $new_date - (15*60)))."'
                     AND`date` <= '".(date('Y-m-d H:i:m', $new_date + (15*60)))."'
                  ORDER BY `date`";
               $result = $DB->query($query);
               
               // get data
               $mydatat = array();
               $pmServiceevent = new PluginMonitoringServiceevent();
               $pmService = new PluginMonitoringService();
               $ret = $pmServiceevent->getData($result, $pmComponent->fields['graph_template']);
               if (is_array($ret)) {
                  $mydatat = $ret[0];
               }
               $array_data = array();
               foreach ($mydatat as $name=>$a_values) {
                  $valfloat = array_sum($a_values) / count($a_values);
                  if ($valfloat > 2) {
                     $array_data[$name] = round($valfloat);
                  } else {
                     $array_data[$name] = round($valfloat, 2);
                  }
               }
               $input = array();
               $input['plugin_monitoring_services_id'] = $plugin_monitoring_services_id;
               $input['date'] = date('Y-m-d H:i:s', $new_date);
               $input['data'] = exportArrayToDB($array_data);
               $input['type'] = '30m';
               $this->add($input);
               $new_date = $new_date + (30 * 60);
            }
            

         // *** 1w (x 6 hours)
            $dateold = date('Y-m-d H:i:s', (date('U') - (7 * 24 * 3600)));
            $a_dateold['6h'] = $dateold;
            // Gest last value, and see if we must calculate new values
            $query = "SELECT * FROM `".$this->getTable()."`
               WHERE `plugin_monitoring_services_id`='".$plugin_monitoring_services_id."'
                  AND `type`='6h'
               ORDER BY `date` DESC
               LIMIT 1";
            $result = $DB->query($query);
            $lastdate = $dateold;
            $new_date = '';
            if ($DB->numrows($result) == '1') {
               $data = $DB->fetch_assoc($result);
               $new_date = PluginMonitoringServiceevent::convert_datetime_timestamp($data['date']);
               $new_date += (6 * 3600);
            } else {
               $split = explode(" ", $lastdate);
               $a_time = explode(":", $split[1]);
               $a_date = explode("-", $split[0]);
               $hour = '0';
               $addtime = 0;
               if ($a_time[0] > 18) {
                  $hour = 0;
                  $addtime = 24 * 3600;
               } else if ($a_time[0] > 12) {
                  $hour = 18;
               } else if ($a_time[0] > 6) {
                  $hour = 12;
               } else {
                  $hour = 6;
               }
               
               $new_date = date('U', mktime($hour,
                                            '0',
                                            '0',
                                            $a_date[1],
                                            $a_date[2],
                                            $a_date[0])) + $addtime;
            }
            
            //get data in serviceevents for each 6 hours from this date to now
            while (($new_date + (3 * 3600)) < date('U')) {

               $query = "SELECT * FROM `glpi_plugin_monitoring_serviceevents`
                  WHERE `plugin_monitoring_services_id`='".$plugin_monitoring_services_id."'
                     AND `date` >= '".(date('Y-m-d H:i:m', $new_date - (3 * 3600)))."'
                     AND`date` <= '".(date('Y-m-d H:i:m', $new_date + (3 * 3600)))."'
                  ORDER BY `date`";
               $result = $DB->query($query);
               
               // get data
               $mydatat = array();
               $pmServiceevent = new PluginMonitoringServiceevent();
               $pmService = new PluginMonitoringService();
               $ret = $pmServiceevent->getData($result, $pmComponent->fields['graph_template']);
               if (is_array($ret)) {
                  $mydatat = $ret[0];
               }
               $array_data = array();
               foreach ($mydatat as $name=>$a_values) {
                  $valfloat = array_sum($a_values) / count($a_values);
                  if ($valfloat > 2) {
                     $array_data[$name] = round($valfloat);
                  } else {
                     $array_data[$name] = round($valfloat, 2);
                  }
               }
               $input = array();
               $input['plugin_monitoring_services_id'] = $plugin_monitoring_services_id;
               $input['date'] = date('Y-m-d H:i:s', $new_date);
               $input['data'] = exportArrayToDB($array_data);
               $input['type'] = '6h';
               $this->add($input);
               $new_date = $new_date + (6 * 3600);
            }
            
            
         // *** 1m (x 1 day)   
            $dateold = date('Y-m-d H:i:s', mktime(date('H'),
                                                 date('i'),
                                                 date('s'),
                                                 date('m') - 1,
                                                 (date('d')),
                                                 date('Y')));
//            $a_dateold['1d'] = $dateold; //NOTE to keep data for each day (if we want use it in future)
            // Gest last value, and see if we must calculate new values
            $query = "SELECT * FROM `".$this->getTable()."`
               WHERE `plugin_monitoring_services_id`='".$plugin_monitoring_services_id."'
                  AND `type`='1d'
               ORDER BY `date` DESC
               LIMIT 1";
            $result = $DB->query($query);
            $lastdate = $dateold;
            $new_date = '';
            if ($DB->numrows($result) == '1') {
               $data = $DB->fetch_assoc($result);
               $new_date = PluginMonitoringServiceevent::convert_datetime_timestamp($data['date']);
               $new_date += (24 * 3600);
            } else {
               $split = explode(" ", $lastdate);
               $a_date = explode("-", $split[0]);
               
               $new_date = date('U', mktime('0',
                                            '0',
                                            '0',
                                            $a_date[1],
                                            $a_date[2],
                                            $a_date[0]));
            }
            
            //get data in serviceevents for each 6 hours from this date to now
            while (($new_date + (12 * 3600)) < date('U')) {

               $query = "SELECT * FROM `glpi_plugin_monitoring_serviceevents`
                  WHERE `plugin_monitoring_services_id`='".$plugin_monitoring_services_id."'
                     AND `date` >= '".(date('Y-m-d H:i:m', $new_date - (12 * 3600)))."'
                     AND`date` <= '".(date('Y-m-d H:i:m', $new_date + (12 * 3600)))."'
                  ORDER BY `date`";
               $result = $DB->query($query);
               
               // get data
               $mydatat = array();
               $pmServiceevent = new PluginMonitoringServiceevent();
               $pmService = new PluginMonitoringService();
               $ret = $pmServiceevent->getData($result, $pmComponent->fields['graph_template']);
               if (is_array($ret)) {
                  $mydatat = $ret[0];
               }
               $array_data = array();
               foreach ($mydatat as $name=>$a_values) {
                  $valfloat = array_sum($a_values) / count($a_values);
                  if ($valfloat > 2) {
                     $array_data[$name] = round($valfloat);
                  } else {
                     $array_data[$name] = round($valfloat, 2);
                  }
               }
               $input = array();
               $input['plugin_monitoring_services_id'] = $plugin_monitoring_services_id;
               $input['date'] = date('Y-m-d H:i:s', $new_date);
               $input['data'] = exportArrayToDB($array_data);
               $input['type'] = '1d';
               $this->add($input);
               $new_date = $new_date + (24 * 3600);
            }
         
         // *** 6m (x 5 days)
            $dateold = date('Y-m-d H:i:s', mktime(date('H'),
                                                 date('i'),
                                                 date('s'),
                                                 date('m') - 6,
                                                 (date('d')),
                                                 date('Y')));
            $a_dateold['5d'] = $dateold;
            // Gest last value, and see if we must calculate new values
            $query = "SELECT * FROM `".$this->getTable()."`
               WHERE `plugin_monitoring_services_id`='".$plugin_monitoring_services_id."'
                  AND `type`='5d'
               ORDER BY `date` DESC
               LIMIT 1";
            $result = $DB->query($query);
            $lastdate = $dateold;
            $new_date = '';
            if ($DB->numrows($result) == '1') {
               $data = $DB->fetch_assoc($result);
               $new_date = PluginMonitoringServiceevent::convert_datetime_timestamp($data['date']);
               $new_date += (5 * 24 * 3600);
            } else {
               $split = explode(" ", $lastdate);
               $a_date = explode("-", $split[0]);
               
               $new_date = date('U', mktime('0',
                                            '0',
                                            '0',
                                            $a_date[1],
                                            $a_date[2],
                                            $a_date[0]));
            }
            
            //get data in serviceevents for each 6 hours from this date to now
            while ($new_date + ((5 * 24 * 3600) / 2) < date('U')) {

               $query = "SELECT * FROM `glpi_plugin_monitoring_serviceevents`
                  WHERE `plugin_monitoring_services_id`='".$plugin_monitoring_services_id."'
                     AND `date` >= '".(date('Y-m-d H:i:m', $new_date - ((5 * 24 * 3600) / 2)))."'
                     AND`date` <= '".(date('Y-m-d H:i:m', $new_date + ((5 * 24 * 3600) / 2)))."'
                  ORDER BY `date`";
               $result = $DB->query($query);
               
               // get data
               $mydatat = array();
               $pmServiceevent = new PluginMonitoringServiceevent();
               $pmService = new PluginMonitoringService();
               $ret = $pmServiceevent->getData($result, $pmComponent->fields['graph_template']);
               if (is_array($ret)) {
                  $mydatat = $ret[0];
               }
               $array_data = array();
               foreach ($mydatat as $name=>$a_values) {
                  $valfloat = array_sum($a_values) / count($a_values);
                  if ($valfloat > 2) {
                     $array_data[$name] = round($valfloat);
                  } else {
                     $array_data[$name] = round($valfloat, 2);
                  }
               }
               $input = array();
               $input['plugin_monitoring_services_id'] = $plugin_monitoring_services_id;
               $input['date'] = date('Y-m-d H:i:s', $new_date);
               $input['data'] = exportArrayToDB($array_data);
               $input['type'] = '5d';
               $this->add($input);
               $new_date = $new_date + (5 * 24 * 3600);
            }
            
         // *** 1y (x 10 days)
            $dateold = date('Y-m-d H:i:s', mktime(date('H'),
                                                 date('i'),
                                                 date('s'),
                                                 date('m') - 10,
                                                 (date('d')),
                                                 date('Y')));
            $a_dateold['5d'] = $dateold;
            // Gest last value, and see if we must calculate new values
            $query = "SELECT * FROM `".$this->getTable()."`
               WHERE `plugin_monitoring_services_id`='".$plugin_monitoring_services_id."'
                  AND `type`='10d'
               ORDER BY `date` DESC
               LIMIT 1";
            $result = $DB->query($query);
            $lastdate = $dateold;
            $new_date = '';
            if ($DB->numrows($result) == '1') {
               $data = $DB->fetch_assoc($result);
               $new_date = PluginMonitoringServiceevent::convert_datetime_timestamp($data['date']);
               $new_date += (10 * 24 * 3600);
            } else {
               $split = explode(" ", $lastdate);
               $a_date = explode("-", $split[0]);
               
               $new_date = date('U', mktime('0',
                                            '0',
                                            '0',
                                            $a_date[1],
                                            $a_date[2],
                                            $a_date[0]));
            }
            
            //get data in serviceevents for each 6 hours from this date to now
            while ($new_date + (5 * 24 * 3600) < date('U')) {

               $query = "SELECT * FROM `glpi_plugin_monitoring_serviceevents`
                  WHERE `plugin_monitoring_services_id`='".$plugin_monitoring_services_id."'
                     AND `date` >= '".(date('Y-m-d H:i:m', $new_date - (5 * 24 * 3600)))."'
                     AND`date` <= '".(date('Y-m-d H:i:m', $new_date + (5 * 24 * 3600)))."'
                  ORDER BY `date`";
               $result = $DB->query($query);
               
               // get data
               $mydatat = array();
               $pmServiceevent = new PluginMonitoringServiceevent();
               $pmService = new PluginMonitoringService();
               $ret = $pmServiceevent->getData($result, $pmComponent->fields['graph_template']);
               if (is_array($ret)) {
                  $mydatat = $ret[0];
               }
               $array_data = array();
               foreach ($mydatat as $name=>$a_values) {
                  $valfloat = array_sum($a_values) / count($a_values);
                  if ($valfloat > 2) {
                     $array_data[$name] = round($valfloat);
                  } else {
                     $array_data[$name] = round($valfloat, 2);
                  }
               }
               $input = array();
               $input['plugin_monitoring_services_id'] = $plugin_monitoring_services_id;
               $input['date'] = date('Y-m-d H:i:s', $new_date);
               $input['data'] = exportArrayToDB($array_data);
               $input['type'] = '10d';
               $this->add($input);
               $new_date = $new_date + (10 * 24 * 3600);
            }
         
         
         
            
            
            
            
         // *** Delete old values
         foreach ($a_dateold as $name=>$date) {
            $query = "DELETE FROM `".$this->getTable()."`
               WHERE `plugin_monitoring_services_id`='".$plugin_monitoring_services_id."'
                  AND `type`='".$name."'
                  AND `date` < '".$date."'";
            $result = $DB->query($query);
         }
      }
   }
   
   
   
   static function getperfdataNames($rrdtool_template,$keepwarcrit=1) {
      
      $a_name = array();

      $func = "perfdata_".$rrdtool_template;
      $a_json = json_decode(PluginMonitoringPerfdata::$func());

      foreach ($a_json->parseperfdata as $data) {
         foreach ($data->DS as $data2) {
            if ($keepwarcrit == 0) {
               if (!strstr($data2->dsname, "warning")
                       && !strstr($data2->dsname, "critical")) {
                  $a_name[] = $data2->dsname;
               }
            } else {
               $a_name[] = $data2->dsname;
            }
         }
      }
      return $a_name;
   }
   
   
   
   static function colors($type='normal') {
      $a_colors = array();
      switch ($type) {
         case 'normal':
            $a_colors["006600"] = "006600";
            $a_colors["009900"] = "009900";
            $a_colors["67cb33"] = "67cb33";
            $a_colors["9afe66"] = "9afe66";

            $a_colors["003399"] = "003399";
            $a_colors["0066cb"] = "0066cb";
            $a_colors["0099ff"] = "0099ff";
            $a_colors["99cdff"] = "99cdff";
            
            $a_colors["6c6024"] = "6c6024";
            $a_colors["a39136"] = "a39136";
            $a_colors["d3c57e"] = "d3c57e";

            $a_colors["66246c"] = "66246c";
            $a_colors["9a36a3"] = "9a36a3";
            $a_colors["cd7ed3"] = "cd7ed3";
            $a_colors["eacaed"] = "eacaed";

            break;

         case 'warn':
            $a_colors["eacc00"] = "eacc00";
            $a_colors["ea8f00"] = "ea8f00";
            $a_colors["ea991a"] = "ea991a";

            break;
         
         case 'crit':
            $a_colors["ff0000"] = "ff0000";
            $a_colors["a00000"] = "a00000";
            $a_colors["720000"] = "720000";

            break;
      }
      return $a_colors;      
   }
   
   
   
   static function loadLib() {
      global $CFG_GLPI;
      
      echo '<link href="'.$CFG_GLPI["root_doc"].'/plugins/monitoring/lib/nvd3/src/nv.d3.css" rel="stylesheet" type="text/css">   
      <script src="'.$CFG_GLPI["root_doc"].'/plugins/monitoring/lib/nvd3/lib/d3.v2.min.js"></script>
      <script src="'.$CFG_GLPI["root_doc"].'/plugins/monitoring/lib/nvd3/nv.d3.min.js"></script>
      <script src="'.$CFG_GLPI["root_doc"].'/plugins/monitoring/lib/nvd3/src/tooltip.js"></script>
      <script src="'.$CFG_GLPI["root_doc"].'/plugins/monitoring/lib/nvd3/src/utils.js"></script>
      <script src="'.$CFG_GLPI["root_doc"].'/plugins/monitoring/lib/nvd3/src/models/legend.js"></script>
      <script src="'.$CFG_GLPI["root_doc"].'/plugins/monitoring/lib/nvd3/src/models/axis.js"></script>
      <script src="'.$CFG_GLPI["root_doc"].'/plugins/monitoring/lib/nvd3/src/models/scatter.js"></script>
      <script src="'.$CFG_GLPI["root_doc"].'/plugins/monitoring/lib/nvd3/src/models/line.js"></script>
      <script src="'.$CFG_GLPI["root_doc"].'/plugins/monitoring/lib/nvd3/src/models/lineChart.js"></script>';

   }
   
   
   
   static function preferences($components_id, $loadpreferences=1, $displayonly=0) {
      if ($loadpreferences == 1) {
         PluginMonitoringServicegraph::loadPreferences($components_id);
      }
      
      $pmComponent = new PluginMonitoringComponent();
      $pmComponent->getFromDB($components_id);
      
      echo "<form method='post'>";
      $a_perfnames = array();
      $a_perfnames = PluginMonitoringServicegraph::getperfdataNames($pmComponent->fields['graph_template']);
      echo "<table class='tab_cadre_fixe'>";      
      echo "<tr class='tab_bg_1'>";
      echo "<td rowspan='".ceil(count($a_perfnames) / 7)."' width='90'>";
      echo "Display&nbsp;:";
      
      echo "</td>";
      $i = 0;
      $j = 0;
      if (!isset($_SESSION['glpi_plugin_monitoring']['perfname'][$components_id])) {
         foreach ($a_perfnames as $name) {
            $_SESSION['glpi_plugin_monitoring']['perfname'][$components_id][$name] = 'checked';
         }
      }
      foreach ($a_perfnames as $name) {
         if ($i == 'O'
                 AND $j == '1') {
            echo "<tr>";
         }
         echo "<td>";
         $checked = "checked";
         if (isset($_SESSION['glpi_plugin_monitoring']['perfname'][$components_id])) {
            $checked = "";
         }
         if (isset($_SESSION['glpi_plugin_monitoring']['perfname'][$components_id][$name])) {
            $checked = $_SESSION['glpi_plugin_monitoring']['perfname'][$components_id][$name];
         }
         echo "<input type='checkbox' name='perfname[]' value='".$name."' ".$checked."/> ".$name;
         echo "</td>";
         $i++;
         if ($i == 6) {
            $i = 0;
            echo "</tr>";
         }
         $j = 1;
      }
      if ($i != 6) {
         echo "<td colspan='".(6-$i)."'></td>";
         echo "</tr>";
      }

      echo "</table>";

      if ($displayonly == 1) {
         return;
      }
      // * Invert perfname

      $a_perfnames = array();
      $a_perfnames = PluginMonitoringServicegraph::getperfdataNames($pmComponent->fields['graph_template']);
      echo "<table class='tab_cadre_fixe'>";      
      echo "<tr class='tab_bg_1'>";
      echo "<td rowspan='".ceil(count($a_perfnames) / 7)."' width='90'>";
      echo "Invert values&nbsp;:";
      
      echo "</td>";
      $i = 0;
      $j = 0;
      foreach ($a_perfnames as $name) {
         if ($i == 'O'
                 AND $j == '1') {
            echo "<tr>";
         }
         echo "<td>";
         $checked = "";
         if (isset($_SESSION['glpi_plugin_monitoring']['perfnameinvert'][$components_id][$name])) {
            $checked = $_SESSION['glpi_plugin_monitoring']['perfnameinvert'][$components_id][$name];
         }
         echo "<input type='checkbox' name='perfnameinvert[]' value='".$name."' ".$checked."/> ".$name;
         echo "</td>";
         $i++;
         if ($i == 6) {
            $i = 0;
            echo "</tr>";
         }
         $j = 1;
      }
      if ($i != 6) {
         echo "<td colspan='".(6-$i)."'></td>";
         echo "</tr>";
      }

      echo "</table>";
     
      
      // * Define color of perfname


      $a_perfnames = array();
      $a_perfnames = PluginMonitoringServicegraph::getperfdataNames($pmComponent->fields['graph_template']);
      foreach ($a_perfnames as $key=>$name) {
         if (!isset($_SESSION['glpi_plugin_monitoring']['perfname'][$components_id][$name])) {
            unset($a_perfnames[$key]);
         }
      }
      echo "<table class='tab_cadre_fixe'>";      
      echo "<tr class='tab_bg_1'>";
      echo "<td rowspan='".ceil(count($a_perfnames) / 4)."' width='90'>";
      echo "Colors&nbsp;:";
      
      echo "</td>";
      $i = 0;
      $j = 0;
      foreach ($a_perfnames as $name) {
         if ($i == 'O'
                 AND $j == '1') {
            echo "<tr>";
         }
         echo "<td>";
         echo $name."&nbsp;:";
         echo "</td>";
         echo "<td>";
         $a_colors = array();
         if (strstr($name, "warn")) {
            $a_colors = PluginMonitoringServicegraph::colors("warn");
         } else if (strstr($name, "crit")) {
            $a_colors = PluginMonitoringServicegraph::colors("crit");
         } else {
            $a_colors = PluginMonitoringServicegraph::colors();
         }
         echo " <select name='perfnamecolor[".$name."]' id='color".$name."'>";
         echo "<option value=''>".Dropdown::EMPTY_VALUE."</option>";
         foreach ($a_colors as $color) {
            $checked = '';
            if (isset($_SESSION['glpi_plugin_monitoring']['perfnamecolor'][$components_id][$name])
                    AND $_SESSION['glpi_plugin_monitoring']['perfnamecolor'][$components_id][$name] == $color) {
               $checked = 'selected';
            }
            echo "<option value='".$color."' style='background-color: #".$color.";' ".$checked.">".$color."</option>";
         }
         echo "</select>";
         echo "</td>";
         $i++;
         if ($i == 4) {
            $i = 0;
            echo "</tr>";
         }
         $j = 1;
      }
      if ($i != 4) {
         echo "<td colspan='".((4-$i) *2 )."'></td>";
         echo "</tr>";
      }

      echo "<tr>";
      echo "<td colspan='9' align='center'>";
      echo "<input type='hidden' name='id' value='".$components_id."'/>";
      echo "<input type='submit' name='updateperfdata' value=\"".__('Save')."\" class='submit'>";
      echo "</td>";
      echo "</tr>";
      echo "</table>";

      Html::closeForm();      
   }
   
   
   
   static function loadPreferences($components_id) {
      
      $pmComponent = new PluginMonitoringComponent();
      $pmComponent->getFromDB($components_id);
      
      $_SESSION['glpi_plugin_monitoring']['perfname'][$components_id] = array();
      $a_perfname = importArrayFromDB($pmComponent->fields['perfname']);
      foreach ($a_perfname as $perfname=>$active) {
         $_SESSION['glpi_plugin_monitoring']['perfname'][$components_id][$perfname] = 'checked';
      }
      
      $_SESSION['glpi_plugin_monitoring']['perfnameinvert'][$components_id] = array();
      $a_perfnameinvert = importArrayFromDB($pmComponent->fields['perfnameinvert']);
      foreach ($a_perfnameinvert as $perfname=>$active) {
         $_SESSION['glpi_plugin_monitoring']['perfnameinvert'][$components_id][$perfname] = 'checked';
      }
      
      $_SESSION['glpi_plugin_monitoring']['perfnamecolor'][$components_id] = array();
      $a_perfnamecolor = importArrayFromDB($pmComponent->fields['perfnamecolor']);
      foreach ($a_perfnamecolor as $perfname=>$color) {
         $_SESSION['glpi_plugin_monitoring']['perfnamecolor'][$components_id][$perfname] = $color;
      }
   }
}

?>