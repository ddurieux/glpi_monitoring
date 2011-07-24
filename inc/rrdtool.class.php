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

   function createGraph($commands_id, $itemtype, $items_id) {
      
      $fname = "net.rrd";
      
      $pluginMonitoringCommand = new PluginMonitoringCommand();
      $pluginMonitoringCommand->getFromDB($commands_id);
      $a_legend = importArrayFromDB($pluginMonitoringCommand->fields['legend']);

      $opts = array( "–step", "300", "–start", 0);
      foreach ($a_legend as $legend){
         $opts[] = "DS:".$legend.":COUNTER:600:U:U";
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
      
      $fname = "net.rrd";

      $pluginMonitoringCommand = new PluginMonitoringCommand();
      $pluginMonitoringCommand->getFromDB($commands_id);
      $a_legend = importArrayFromDB($pluginMonitoringCommand->fields['legend']);
      
      $matches = array();
      preg_match('/'.$pluginMonitoringCommand->fields['regex'].'/',
            $perf_data, $matches);
            
      $value = $timestamp;
      foreach ($matches as $key=>$data) {
         if (isset($a_legend[$key])) {
            $value .= ':'.$data;
         }
      }
      $ret = rrd_update($fname, $value);

      if( $ret == 0 ) {
         $err = rrd_error();
         echo "ERROR occurred: $err\n";
      }
   }
   
}

?>