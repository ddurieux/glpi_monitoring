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

   function createGraph($rrdtool_template, $items_id, $timestamp) {

      $fname = GLPI_PLUGIN_DOC_DIR."/monitoring/PluginMonitoringService-".$items_id.".rrd";
      
      $a_filename = explode("-", $rrdtool_template);
      $filename = GLPI_PLUGIN_DOC_DIR."/monitoring/templates/".$a_filename[0]."-perfdata.json";
      if (!file_exists($filename)) {
         return;
      }
      $a_json = json_decode(file_get_contents($filename));
      
      $opts = '';
      $opts .= ' --start '.($timestamp - 300);
      $opts .= ' --step 300';

      foreach ($a_json->parseperfdata as $data) {
         foreach ($data->DS as $data_DS) {
            $opts .= ' DS:'.$data_DS->dsname.':'.$data_DS->format.':'.$data_DS->heartbeat.':'.$data_DS->min.':'.$data_DS->max;
         }
      }
      
      $opts .= " RRA:LAST:0.5:1:1400";
      $opts .= " RRA:AVERAGE:0.5:5:1016";
      
//      $opts .= " RRA:AVERAGE:0.5:1:600";
//      $opts .= " RRA:AVERAGE:0.5:6:700";
//      $opts .= " RRA:AVERAGE:0.5:24:775";
//      $opts .= " RRA:AVERAGE:0.5:288:797";
//      $opts .= " RRA:MAX:0.5:1:600";
//      $opts .= " RRA:MAX:0.5:6:700";
//      $opts .= " RRA:MAX:0.5:24:775";
//      $opts .= " RRA:MAX:0.5:288:797";

      //$ret = rrd_create($fname, $opts, count($opts));

      system(PluginMonitoringConfig::getRRDPath().'/rrdtool create '.$fname.$opts, $ret);
      if (isset($ret) 
              AND $ret != '0' ) {
         echo "Create error: $ret for ".PluginMonitoringConfig::getRRDPath()."/rrdtool create ".$fname.$opts."\n";
      }
   }

   
   
   function addData($rrdtool_template, $items_id, $timestamp, $perf_data) {

      $fname = GLPI_PLUGIN_DOC_DIR."/monitoring/PluginMonitoringService-".$items_id.".rrd";
      if (!file_exists($fname)) {
         $this->createGraph($rrdtool_template, $items_id, $timestamp);
      }
      
      $a_filename = explode("-", $rrdtool_template);
      $filename = GLPI_PLUGIN_DOC_DIR."/monitoring/templates/".$a_filename[0]."-perfdata.json";
      if (!file_exists($filename)) {
         return;
      }
      $a_json = json_decode(file_get_contents($filename));
      $a_perfdata = explode(" ", $perf_data);
      $rrdtool_value = $timestamp;
      foreach ($a_json->parseperfdata as $num=>$data) {
         if (isset($a_perfdata[$num])) {
            $a_a_perfdata = explode("=", $a_perfdata[$num]);
            if ($a_a_perfdata[0] == $data->name) {
               $a_perfdata_final = explode(";", $a_a_perfdata[1]);
               foreach ($a_perfdata_final as $nb_val=>$val) {
                  if ($val != '') {
                     if (strstr($val, "ms")) {
                        $val = round(str_replace("ms", "", $val),0);
                     } else if (strstr($val, "s")) {
                        $val = round((str_replace("s", "", $val) * 1000),0);
                     } else if (strstr($val, "%")) {
                        $val = round(str_replace("%", "", $val),0);
                     } else if (!strstr($val, "timeout")){
                        $val = round($val,2);
                     } else {
                        $val = $data->DS[$nb_val]->max;
                     }
                     $rrdtool_value .= ':'.$val;
                  }
               }
            } else {
               foreach ($data->DS as $nb_DS) {
                  $rrdtool_value .= ':U';
               }
            }
         } else {
            foreach ($data->DS as $nb_DS) {
               $rrdtool_value .= ':U';
            }
         }         
      }      
      //$ret = rrd_update($fname, $value);

      system(PluginMonitoringConfig::getRRDPath()."/rrdtool update ".$fname." ".$rrdtool_value, $ret);
      if (isset($ret) 
              AND $ret != '0' ) {
         echo "Create error: $ret for ".PluginMonitoringConfig::getRRDPath()."/rrdtool update ".$fname." ".$rrdtool_value."\n";
      }
   }
   
   
   
   /**
    * Function used to generate gif of rrdtool graph
    * 
    * @param type $itemtype
    * @param type $items_id
    * @param type $time 
    */
   function displayGLPIGraph($rrdtool_template, $itemtype, $items_id, $time='1d', $width='470') {
      global $LANG;

      $filename = GLPI_PLUGIN_DOC_DIR."/monitoring/templates/".$rrdtool_template."_graph.json";
      if (!file_exists($filename)) {
         return;
      }
      $a_json = json_decode(file_get_contents($filename));

      $opts = "";
      $opts .= ' --start -'.$time;
      $opts .= " --title '".$a_json->data[0]->labels[0]->title."'";
//      $opts .= " --vertical-label '".$a_json->data->labels->vertical-label."'";
      $opts .= " --width ".$width;
//      if (count($a_legend) > 4) {
         $opts .= " --height 200";
//      }
      foreach ($a_json->data[0]->miscellaneous[0]->color as $color) {
         $opts .= " --color ".$color;
      }
      foreach ($a_json->data[0]->data as $data) {
         $data = str_replace("[[RRDFILE]]", 
                             GLPI_PLUGIN_DOC_DIR."/monitoring/".$itemtype."-".$items_id.".rrd", 
                             $data);
         if (strstr($time, "d") OR  strstr($time, "h")) {
            $data = str_replace("AVERAGE", "LAST", $data);
         }
         $opts .= " ".$data;
      }

      //$ret = rrd_graph(GLPI_PLUGIN_DOC_DIR."/monitoring/".$itemtype."-".$items_id."-".$time.".gif", $opts, count($opts));
      if (file_exists(GLPI_PLUGIN_DOC_DIR."/monitoring/".$itemtype."-".$items_id.".rrd")) {
         ob_start();
         system(PluginMonitoringConfig::getRRDPath()."/rrdtool graph ".GLPI_PLUGIN_DOC_DIR."/monitoring/".$itemtype."-".$items_id."-".$time.".gif ".$opts, $ret);
         ob_end_clean();
         if (isset($ret) 
                 AND $ret != '0' ) {
            echo "Create error: $ret for ".PluginMonitoringConfig::getRRDPath()."/rrdtool graph ".GLPI_PLUGIN_DOC_DIR."/monitoring/".$itemtype."-".$items_id."-".$time.".gif ".
                     $opts."\n";
         }
      }
      return true;
   }
   
   
   
   function perfdataToRRDTool () {
      
      $json = '{ 
  "command": "check_ping", 
  "parseperfdata": [ 
      {
          "name": "rta",
          "DS": [
              {
                  "ds-name": "response_time",
                  "format": "GAUGE",
                  "heartbeat": "600",
                  "min": "0",
                  "max": "U"
              }, 
              {
                  "ds-name": "warning_limit_rta",
                  "format": "GAUGE",
                  "heartbeat": "600",
                  "min": "0",
                  "max": "U"

              }, 
              {
                  "ds-name": "critical_limit_rta",
                  "format": "GAUGE",
                  "heartbeat": "600",
                  "min": "0",
                  "max": "U"
              },
              {
                  "ds-name": "other",
                  "format": "GAUGE",
                  "heartbeat": "600",
                  "min": "0",
                  "max": "U"
               }
           ]
       },
      {
          "name": "pl",
          "DS": [
              {
                  "ds-name": "packet_loss",
                  "format": "GAUGE",
                  "heartbeat": "600",
                  "min": "0",
                  "max": "U"
              },
              {
                  "ds-name": "warning_limit_pl",
                  "format": "GAUGE",
                  "heartbeat": "600",
                  "min": "0",
                  "max": "U"
              },
              {
                  "ds-name": "critical_limit_pl",
                  "format": "GAUGE",
                  "heartbeat": "600",
                  "min": "0",
                  "max": "U"
              },
              {
                  "ds-name": "other",
                  "format": "GAUGE",
                  "heartbeat": "600",
                  "min": "0",
                  "max": "U"
               }
           ]
       }
   ] 
}';
      
      $perfdata = "rta=43.633999ms;10.000000;timeout;0.000000 pl=0%;50;79;0";
      
      $a_perfdata = explode(" ", $perfdata);
      $rrdtool_value = '';
      $a_json = json_decode($json);
      foreach ($a_json->parseperfdata as $num=>$data) {
         if (isset($a_perfdata[$num])) {
            $a_a_perfdata = explode("=", $a_perfdata[$num]);
              if ($a_a_perfdata[0] == $data->name) {
               $a_perfdata_final = explode(";", $a_a_perfdata[1]);
               foreach ($a_perfdata_final as $nb_val=>$val) {
                  if (strstr($val, "ms")) {
                     $val = round(str_replace("ms", "", $val),0);
                  } else if (strstr($val, "s")) {
                     $val = round((str_replace("s", "", $val) * 1000),0);
                  } else if (strstr($val, "%")) {
                     $val = round(str_replace("%", "", $val),0);
                  } else if (!strstr($val, "timeout")){
                     $val = round($val,0);
                  } else {
                     $val = $data->DS[$nb_val]->max;
                  }
                  $rrdtool_value .= ':'.$val;
               }
            } else {
               foreach ($data->DS as $nb_DS) {
                  $rrdtool_value .= ':U';
               }
            }
         } else {
            foreach ($data->DS as $nb_DS) {
               $rrdtool_value .= ':U';
            }
         }         
      }
      echo $rrdtool_value;
      
   }
   
}

?>