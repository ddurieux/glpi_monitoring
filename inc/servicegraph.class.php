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

class PluginMonitoringServicegraph extends CommonDBTM {
   private $jsongraph_a_ref = array();
   private $jsongraph_a_convert = array();

   
   function displayGraph($rrdtool_template, $itemtype, $items_id, $timezone, $time='1d', $width='470') {
      global $DB,$LANG;
      
      $timezonefile = str_replace("+", ".", $timezone);
      
      // Cache 1 minute
      if (file_exists(GLPI_PLUGIN_DOC_DIR."/monitoring/".$itemtype."-".$items_id."-".$time.$timezonefile.".png")) {
         $time_generate = filectime(GLPI_PLUGIN_DOC_DIR."/monitoring/".$itemtype."-".$items_id."-".$time.$timezonefile.".png");
         if (($time_generate + 150) > date('U')) {
            return;
         }
      }
      
      $filename = GLPI_PLUGIN_DOC_DIR."/monitoring/templates/".$rrdtool_template."_graph.json";

      $loadfile = file_get_contents($filename);
      if (!$loadfile) {
         return;
      }
      $a_jsong = json_decode($loadfile);
      
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
      
      
      $opts = "";

      /* pChart library inclusions */
      include_once("../lib/pChart2.1.3/class/pData.class.php");
      include_once("../lib/pChart2.1.3/class/pDraw.class.php");
      include_once("../lib/pChart2.1.3/class/pImage.class.php");
      
      $MyData = new pData();      

      // ** Get in table serviceevents
      $mydatat = array();
      $a_labels = array();
      $a_ref = array();
      $pmServiceevent = new PluginMonitoringServiceevent();
      $pmService = new PluginMonitoringService();
      $pmService->getFromDB($items_id);
      
      $begin = '';
      switch ($time) {
         
         case '2h':
            $begin = date('Y-m-d H:i:s', date('U') - (2 * 3600));
            
            $query = "SELECT * FROM `glpi_plugin_monitoring_serviceevents`
               WHERE `plugin_monitoring_services_id`='".$items_id."'
                  AND `date` > '".$begin."'
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
            $begin = date('Y-m-d H:i:s', date('U') - (12 * 3600));
            
            $query = "SELECT * FROM `glpi_plugin_monitoring_serviceevents`
               WHERE `plugin_monitoring_services_id`='".$items_id."'
                  AND `date` > '".$begin."'
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
            $begin = date('Y-m-d H:i:s', date('U') - (24 * 3600));
            
            $query = "SELECT * FROM `".$this->getTable()."`
               WHERE `plugin_monitoring_services_id`='".$items_id."'
                  AND `type`='30m'
               ORDER BY `date`";
            $result = $DB->query($query);
            while ($edata=$DB->fetch_array($result)) {
               $dat = importArrayFromDB($edata['data']);
               if (count($dat) > 0) {
                  $datemod = $edata['date'];
                  $split = explode(' ', $datemod);
                  $split2 = explode(':', $split[1]);
                  array_push($a_labels, $split2[0].':'.$split2[1]);
               }
               foreach ($dat as $name=>$value) {
                  if (!isset($mydatat[$name])) {
                     $mydatat[$name] = array();
                  }
                  array_push($mydatat[$name], $value);
               }
            }
            $ret = $pmServiceevent->getRef($rrdtool_template);
            $a_ref = $ret[0];
            break;
         
         case '1w':
            $begin = date('Y-m-d H:i:s', date('U') - (7 * 24 * 3600));
            
            $query = "SELECT * FROM `".$this->getTable()."`
               WHERE `plugin_monitoring_services_id`='".$items_id."'
                  AND `type`='6h'
               ORDER BY `date`";
            $result = $DB->query($query);
            while ($edata=$DB->fetch_array($result)) {
               $dat = importArrayFromDB($edata['data']);
               if (count($dat) > 0) {
                  $datemod = $edata['date'];
                  $daynum = Calendar::getDayNumberInWeek(PluginMonitoringServiceevent::convert_datetime_timestamp($edata['date']));
                  $split = explode(' ', $datemod);
                  $split2 = explode(':', $split[1]);
                  array_push($a_labels, $LANG['calendarDay'][$daynum]." ".$split2[0].':'.$split2[1]);
               }
               foreach ($dat as $name=>$value) {
                  if (!isset($mydatat[$name])) {
                     $mydatat[$name] = array();
                  }
                  array_push($mydatat[$name], $value);
               }
            }
            $ret = $pmServiceevent->getRef($rrdtool_template);
            $a_ref = $ret[0];
            break;

         case '1m':
            $begin = date('Y-m-d H:i:s', date('U') - (30 * 24 * 3600));
            
            $query = "SELECT * FROM `".$this->getTable()."`
               WHERE `plugin_monitoring_services_id`='".$items_id."'
                  AND `type`='1d'
               ORDER BY `date`";
            $result = $DB->query($query);
            while ($edata=$DB->fetch_array($result)) {
               $dat = importArrayFromDB($edata['data']);
               if (count($dat) > 0) {
                  $datemod = $edata['date'];
                  $daynum = Calendar::getDayNumberInWeek(PluginMonitoringServiceevent::convert_datetime_timestamp($edata['date']));
                  $split = explode(' ', $datemod);
                  $split2 = explode(':', $split[1]);
                  $day = explode("-", $split[0]);
                  array_push($a_labels, $LANG['calendarDay'][$daynum]." ".$day[2]);
               }
               foreach ($dat as $name=>$value) {
                  if (!isset($mydatat[$name])) {
                     $mydatat[$name] = array();
                  }
                  array_push($mydatat[$name], $value);
               }
            }
            $ret = $pmServiceevent->getRef($rrdtool_template);
            $a_ref = $ret[0];
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
               if (count($dat) > 0) {
                  $datemod = $edata['date'];
                  $daynum = date('m', PluginMonitoringServiceevent::convert_datetime_timestamp($edata['date']));
                  $daynum = $daynum - 1;
                  $split = explode(' ', $datemod);
                  $day = explode("-", $split[0]);
                  array_push($a_labels, $LANG['calendarM'][$daynum]." ".$day[2]);
               }
               foreach ($dat as $name=>$value) {
                  if (!isset($mydatat[$name])) {
                     $mydatat[$name] = array();
                  }
                  array_push($mydatat[$name], $value);
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
               if (count($dat) > 0) {
                  $datemod = $edata['date'];
                  $daynum = date('m', PluginMonitoringServiceevent::convert_datetime_timestamp($edata['date']));
                  $daynum = $daynum - 1;
                  $split = explode(' ', $datemod);
                  $day = explode("-", $split[0]);
                  array_push($a_labels, $LANG['calendarM'][$daynum]." ".$day[2]);
               }
               foreach ($dat as $name=>$value) {
                  if (!isset($mydatat[$name])) {
                     $mydatat[$name] = array();
                  }
                  array_push($mydatat[$name], $value);
               }
            }
            $ret = $pmServiceevent->getRef($rrdtool_template);
            $a_ref = $ret[0];
            break;
         
      }
      
      $i = 0;
      foreach ($mydatat as $name=>$data) {
         $i++;
         if ($i == '2') {
            $datat = $data;
            $data = array();
            foreach ($datat as $val) {
               array_push($data, -$val);
            }
         }
         if (empty($data)) {
            array_push($data, 0);
         }
         $MyData->addPoints($data, $name);
         $color = str_split($a_ref[$name]);
         $MyData->setPalette($name,array("R"=>hexdec($color[0].$color[1]),
                                         "G"=>hexdec($color[2].$color[3]),
                                         "B"=>hexdec($color[4].$color[5])));
      }
      $MyData->setAxisDisplay(0,AXIS_FORMAT_METRIC,1);
//    $MyData->setSerieTicks("Probe 2",4);
//    $MyData->setAxisName(0,"Temperatures");
      $MyData->addPoints($a_labels,"Labels");
//    $MyData->setSerieDescription("Labels","Months");
      $MyData->setAbscissa("Labels");
      $myPicture = new pImage(700,230,$MyData);
      $myPicture->Antialias = FALSE;

      $Settings = array("R"=>225, "G"=>204, "B"=>123);
      $myPicture->drawFilledRectangle(0,0,700,230,$Settings);

      $Settings = array("R"=>255, "G"=>255, "B"=>255);
      $myPicture->drawFilledRectangle(60,40,650,200,$Settings);

      /* Add a border to the picture */
      $myPicture->drawRectangle(0,0,699,229,array("R"=>0,"G"=>0,"B"=>0));


      /* Write the chart title */ 
      $myPicture->setFontProperties(array("FontName"=>"../lib/pChart2.1.3/fonts/verdana.ttf","FontSize"=>11));
      $myPicture->drawText(350,20, $a_jsong->data[0]->labels[0]->title, array("FontSize"=>13,"Align"=>TEXT_ALIGN_MIDDLEMIDDLE));

      /* Set the default font */
      $myPicture->setFontProperties(array("FontName"=>"../lib/pChart2.1.3/fonts/verdana.ttf","FontSize"=>7));

      /* Define the chart area */
      $myPicture->setGraphArea(60,40,650,200);

      /* Draw the scale */
      $labelskip = round(count($a_labels) / 8);
      if ($time == '1d') {
         $labelskip = 3;
      } else if($time == '1m') {
         $labelskip = 3;
      } else if($time == '0y6m') {
         $labelskip = 4;
      } else if($time == '1y') {
         $labelskip = 3;
      }
      $scaleSettings = array("XMargin"=>10,
                             "YMargin"=>10,
                             "Floating"=>TRUE,
          "GridR"=>158, "GridG"=>158, "GridB"=>158, "GridAlpha"=>80,

                             "DrawSubTicks"=>TRUE,
                             "CycleBackground"=>FALSE,
          "LabelSkip"=>$labelskip);
      $myPicture->drawScale($scaleSettings);

      /* Write the chart legend */
      $myPicture->drawLegend(540,20,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));

      /* Turn on Antialiasing */
      $myPicture->Antialias = TRUE;


      $Config = array("ForceTransparency"=>60);

      /* Draw the area chart */
      $myPicture->drawAreaChart($Config);

      $myPicture->render(GLPI_PLUGIN_DOC_DIR."/monitoring/".$itemtype."-".$items_id."-".$time.$timezonefile.".png");
      
      return;      
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
                  $array_data[$name] = round(array_sum($a_values) / count($a_values));
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
                  $array_data[$name] = round(array_sum($a_values) / count($a_values));
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
                  $array_data[$name] = round(array_sum($a_values) / count($a_values));
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
                  $array_data[$name] = round(array_sum($a_values) / count($a_values));
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
                  $array_data[$name] = round(array_sum($a_values) / count($a_values));
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
}

?>