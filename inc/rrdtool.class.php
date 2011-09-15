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
            if (strstr($data, "_time")) {
               $matches[$key] = $matches[$key] * 1000;
            }
            if (!strstr($data, "timeout")) {
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
   function displayGLPIGraph($itemtype, $items_id, $time='1d') {
      
      $pluginMonitoringCommand = new PluginMonitoringCommand();
      $pluginMonitoringCommand->getFromDB(20);
      $a_legend = importArrayFromDB($pluginMonitoringCommand->fields['legend']);

      $opts = array();
      $opts[] = '--start';
      $opts[] = '-'.$time;
      if ($pluginMonitoringCommand->fields['unit'] == "ms") {
         $opts[] = "-v";
         $opts[] = "Time in ms";
      }
      $opts[] = "--width";
      $opts[] = "470";
      $opts[] = "-c";
      $opts[] = "BACK#e1cc7b";
      $opts[] = "-c";
      $opts[] = "CANVAS#f1f1f1";

      foreach ($a_legend as $legend){
          if (!strstr($legend, "timeout")) {
            $opts[] = "DEF:".$legend."=".GLPI_PLUGIN_DOC_DIR."/monitoring/".$itemtype."-".$items_id.".rrd:".$legend.":AVERAGE";
          }
      }
      $i = 2;
      foreach ($a_legend as $legend){
         $color = "#00FF00";
         $type = "AREA";
         if (strstr($legend, "warning")) {
            $type = "LINE".$i;
            $color = "#0000FF";
            $i++;
         } else if (strstr($legend, "critical")) {
            $type = "LINE".$i;
            $color = "#FF0000";
            $i++;
         }
         if (!strstr($legend, "timeout")) {
            $opts[] = $type.":".$legend.$color.":".$legend;
            $opts[] = "GPRINT:".$legend.":LAST:Last\: %2.2lf ms";
            $opts[] = "GPRINT:".$legend.":MIN:Min\: %2.2lf ms";
            $opts[] = "GPRINT:".$legend.":MAX:Max\: %2.2lf ms";
            $opts[] = "GPRINT:".$legend.":AVERAGE:Avg\: %2.2lf ms\l";
         }                 
      }
      foreach ($a_legend as $legend){
         if (!strstr($legend, "timeout")) {
            $opts[] = "CDEF:1".$legend."=".$legend.",0.98,*";
         }
      }
      $ret = rrd_graph(GLPI_PLUGIN_DOC_DIR."/monitoring/".$itemtype."-".$items_id."-".$time.".gif", $opts, count($opts));
      if( !is_array($ret)) {
         $err = rrd_error();
         echo "rrd_graph() ERROR: $err\n";
      }
      
      
//      $data = rrd_fetch(GLPI_PLUGIN_DOC_DIR."/monitoring/".$itemtype."-".$items_id.".rrd",
//              $opts, count($opts));
//print_r($data);      
//// Use rrd_fetch to get data
      
   }
   
}

?>