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

class PluginMonitoringRrdtool extends CommonDBTM {

   function createGraph($commands_id, $itemtype, $items_id, $timestamp) {
      
      $fname = GLPI_PLUGIN_DOC_DIR."/monitoring/".$itemtype."-".$items_id.".rrd";
      
      $pluginMonitoringCommand = new PluginMonitoringCommand();
      $pluginMonitoringCommand->getFromDB($commands_id);
      $a_legend = importArrayFromDB($pluginMonitoringCommand->fields['legend']);

      $opts = array();
      $opts[] = '--step';
      $opts[] = '300';
      $opts[] = '--start';
      $opts[] = ($timestamp - 300);
      foreach ($a_legend as $legend){
         if (!strstr($legend, "timeout")) {
            $opts[] = "DS:".$legend.":GAUGE:600:U:U";
         }
      }
      $opts[] = "RRA:AVERAGE:0.5:1:600";
      $opts[] = "RRA:AVERAGE:0.5:6:700";
      $opts[] = "RRA:AVERAGE:0.5:24:775";
      $opts[] = "RRA:AVERAGE:0.5:288:797";
      $opts[] = "RRA:MAX:0.5:1:600";
      $opts[] = "RRA:MAX:0.5:6:700";
      $opts[] = "RRA:MAX:0.5:24:775";
      $opts[] = "RRA:MAX:0.5:288:797";

      $ret = rrd_create($fname, $opts, count($opts));

      if( $ret == 0 ) {
       $err = rrd_error();
       echo "Create error: $err\n";
      }
   }

   
   
   function addData($commands_id, $itemtype, $items_id, $timestamp, $perf_data) {

      $fname = GLPI_PLUGIN_DOC_DIR."/monitoring/".$itemtype."-".$items_id.".rrd";
      if (!file_exists($fname)) {
         $this->createGraph($commands_id, $itemtype, $items_id, $timestamp);
      }

      $pluginMonitoringCommand = new PluginMonitoringCommand();
      $pluginMonitoringCommand->getFromDB($commands_id);
      $a_legend = importArrayFromDB($pluginMonitoringCommand->fields['legend']);
      
      $matches = array();
      preg_match('/'.$pluginMonitoringCommand->fields['regex'].'/',
            $perf_data, $matches);
      $value = $timestamp;
      foreach($a_legend as $key=>$data) {
         if (!empty($matches[$key])) {
            if (strstr($matches[$key], "ms")) {
               $value .= ':'. round(str_replace("ms", "", $matches[$key]));
            } else if (strstr($matches[$key], "s")) {
               $value .= ':'. round((str_replace("s", "", $matches[$key]) * 1000));
            } else if (!strstr($data, "timeout")){
               $value .= ':'.round($matches[$key]);
            }
            
         } else {
            if (!strstr($data, "timeout")) {
               $value .= ':U';
            }
         }
                  
      }
      $ret = rrd_update($fname, $value);

      if( $ret == 0 ) {
         $err = rrd_error();
         echo "ERROR occurred: $err\n";
      }
  
   }
   
   
   
   /**
    * Function used to generate gif of rrdtool graph
    * 
    * @param type $itemtype
    * @param type $items_id
    * @param type $time 
    */
   function displayGLPIGraph($itemtype, $items_id, $time='1d', $width='470') {
      
      $pluginMonitoringCommand = new PluginMonitoringCommand();

      $title = '';
      if ($itemtype == "PluginMonitoringHost_Service") {
         $pMonitoringHost_Service = new PluginMonitoringHost_Service();
         $pMonitoringService = new PluginMonitoringService();
         $pMonitoringHost_Service->getFromDB($items_id);
         $pMonitoringService->getFromDB($pMonitoringHost_Service->fields['plugin_monitoring_services_id']);
         $pluginMonitoringCommand->getFromDB($pMonitoringService->fields['plugin_monitoring_commands_id']);
         $title = $pMonitoringHost_Service->fields['name'];
      } else if ($itemtype == "Computer") {
         $pluginMonitoringHost = new PluginMonitoringHost();
         $hdata = current($pluginMonitoringHost->find("`items_id`='".$items_id."'
                  AND `itemtype`='".$itemtype."'", "", 1));

         $pluginMonitoringCommand->getFromDB($hdata['plugin_monitoring_commands_id']);
         $title = 'Ping';
      }
      
      
      $a_legend = importArrayFromDB($pluginMonitoringCommand->fields['legend']);

      $opts = array();
      $opts[] = '--title';
      $opts[] = $title;
      $opts[] = '--start';
      $opts[] = '-'.$time;
      if ($pluginMonitoringCommand->fields['unit'] == "ms") {
         $opts[] = "-v";
         $opts[] = "Time in ms";
      }
      $opts[] = "--width";
      $opts[] = $width;
      if (count($a_legend) > 3) {
         $opts[] = "--height";
         $opts[] = "200";
      }
      $opts[] = "-c";
      $opts[] = "BACK#e1cc7b";
      $opts[] = "-c";
      $opts[] = "CANVAS#f1f1f1";

      foreach ($a_legend as $legend){
          if (!strstr($legend, "timeout")) {
            $opts[] = "DEF:".$legend."=".GLPI_PLUGIN_DOC_DIR."/monitoring/".$itemtype."-".$items_id.".rrd:".$legend.":AVERAGE";
          }
      }
      $a_colors = array();
      $a_colors[] = "#00FF00";
      $a_colors[] = "#cc0000";
      $a_colors[] = "#0000cc";
      $a_colors[] = "#ff9900";
      $a_colors[] = "#993300";
      $a_colors[] = "#336699";
      $a_colors[] = "#939933";
      $a_colors[] = "#e031de";
      $a_colors[] = "#3bdb8a";
      
      $j = 0;
      foreach ($a_legend as $legend){
         #$type = "AREA";
         $type = "LINE";
         if (strstr($legend, "warning")) {
            $type = "LINE";
            $color = "#0000FF";
           } else if (strstr($legend, "critical")) {
            $type = "LINE";
            $color = "#FF0000";
         } else if (strstr($legend, "packet_loss")) {
            $type = "LINE";
            $color = "#FF0000";
         } else {
            $color = $a_colors[$j];
         }
         if (strstr($legend, "packet_loss")) {
            $opts[] = $type.":".$legend.$color.":".$legend;
            $opts[] = "GPRINT:".$legend.":LAST:Last\: %2.0lf";
            $opts[] = "GPRINT:".$legend.":MIN:Min\: %2.0lf";
            $opts[] = "GPRINT:".$legend.":MAX:Max\: %2.0lf";
            $opts[] = "GPRINT:".$legend.":AVERAGE:Avg\: %2.0lf\l";
         } else if (!strstr($legend, "timeout") 
                 AND $pluginMonitoringCommand->fields['unit'] == "ms") {
            $opts[] = $type.":".$legend.$color.":".$legend;
            $opts[] = "GPRINT:".$legend.":LAST:Last\: %2.2lf ms";
            $opts[] = "GPRINT:".$legend.":MIN:Min\: %2.2lf ms";
            $opts[] = "GPRINT:".$legend.":MAX:Max\: %2.2lf ms";
            $opts[] = "GPRINT:".$legend.":AVERAGE:Avg\: %2.2lf ms\l";
         } else if (!strstr($legend, "timeout")) {
            $opts[] = $type.":".$legend.$color.":".$legend;
            $opts[] = "GPRINT:".$legend.":LAST:Last\: %2.0lf";
            $opts[] = "GPRINT:".$legend.":MIN:Min\: %2.0lf";
            $opts[] = "GPRINT:".$legend.":MAX:Max\: %2.0lf";
            $opts[] = "GPRINT:".$legend.":AVERAGE:Avg\: %2.0lf\l";
         }
         $j++;
      }
      foreach ($a_legend as $legend){
         if (!strstr($legend, "timeout")) {
            $opts[] = "CDEF:1".$legend."=".$legend.",0.98,*";
         }
      }
      $ret = rrd_graph(GLPI_PLUGIN_DOC_DIR."/monitoring/".$itemtype."-".$items_id."-".$time.".gif", $opts, count($opts));
      if( !is_array($ret)) {
         $err = rrd_error();
         return false;
         //echo "rrd_graph() ERROR: $err\n";
      }
      return true;
   }
   
}

?>