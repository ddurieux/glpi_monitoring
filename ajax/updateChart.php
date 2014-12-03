<?php
/*
 * @version $Id: dropdownValue.php 15573 2011-09-01 10:10:06Z moyo $
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2011 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org
 -------------------------------------------------------------------------

 LICENSE

 This file is part of GLPI.

 GLPI is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 GLPI is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with GLPI; if not, write to the Free Software Foundation, Inc.,
 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 --------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: David DURIEUX
// Purpose of file:
// ----------------------------------------------------------------------

$USEDBREPLICATE = 1;

// Direct access to file
if (strpos($_SERVER['PHP_SELF'],"updateChart.php")) {
   include ("../../../inc/includes.php");
   header("Content-Type: text/html; charset=UTF-8");
   Html::header_nocache();
}
session_write_close();

if (!defined('GLPI_ROOT')) {
   die("Can not acces directly to this file");
}

Session::checkLoginUser();
$itemtype = $_GET['itemtype'];
$item = new $itemtype();
if (!$item->getFromDB($_GET['items_id'])) {
   echo __('Item not exist', 'monitoring');
   exit;
}

$pmServicegraph = new PluginMonitoringServicegraph();

$enddate = '';
if ($_GET['customdate'] == ''
        && $_GET['customtime'] == '') {
   $enddate = '';
} else if ($_GET['customdate'] == '') {
   $enddate =  mktime(date('H', $_GET['customtime']),
                      date('i', $_GET['customtime']),
                      date('s', $_GET['customtime']));
} else if ($_GET['customtime'] == '') {
   $enddate = $_GET['customdate'];
} else {
   // have the 2 defined
   $enddate =  mktime(date('H', $_GET['customtime']),
                      date('i', $_GET['customtime']),
                      date('s', $_GET['customtime']),
                      date('n', $_GET['customdate']),
                      date('d', $_GET['customdate']),
                      date('Y', $_GET['customdate']));
}
if (isset($_GET['components_id'])
        && !isset($_SESSION['glpi_plugin_monitoring']['perfname'][$_GET['components_id']])) {
   PluginMonitoringToolbox::loadPreferences($_GET['components_id']);
}
if (! isset($_SESSION['glpi_plugin_monitoring']['perfname'][$_GET['components_id']])) {
   echo __('No data ...', 'monitoring');
   exit;
}
$time_start = microtime(true);
if (isset($_SESSION['glpi_plugin_monitoring']['perfname'][$_GET['components_id']][''])) {
   unset($_SESSION['glpi_plugin_monitoring']['perfname'][$_GET['components_id']]['']);
}
$a_ret = $pmServicegraph->generateData($_GET['rrdtool_template'],
                             $_GET['itemtype'],
                             $_GET['items_id'],
                             $_GET['timezone'],
                             $_GET['time'],
                             $enddate,
                             $_SESSION['glpi_plugin_monitoring']['perfname'][$_GET['components_id']]);
//$time_end = microtime(true);
//$time = $time_end - $time_start;
//echo "Did nothing in " . $time . " <strong>seconds</strong>\n";

$mydatat = $a_ret[0];
$a_labels = $a_ret[1];
$format = $a_ret[2];

$suffix = '';
if (isset($_GET['suffix'])) {
   $suffix = $_GET['suffix'];
}


//$format = "%H:%M";
//if ($_GET['time'] != "2h"
//   AND $_GET['time'] != "12h"
//   AND $_GET['time'] != "1d") {
//   if (isset($_SESSION['glpi_plugin_monitoring']['dateformat'])) {
//      $format = $_SESSION['glpi_plugin_monitoring']['dateformat'];
//   } else {
//      $format = "%Y-%m-%d %Hh";
//   }
//} else {
//   $format = "(%d)%H:%M";
//}




$formaty = ".0f";
$max = 0;
$titleunit = '';
foreach ($mydatat as $name=>$data) {
   $display = "checked";
   if (isset($_SESSION['glpi_plugin_monitoring']['perfname'][$_GET['components_id']])) {
      $display = "";
   }
   if (isset($_SESSION['glpi_plugin_monitoring']['perfname'][$_GET['components_id']][$name])) {
      $display = $_SESSION['glpi_plugin_monitoring']['perfname'][$_GET['components_id']][$name];
   }
   if ($display == "checked") {
      if ($max < max($data)) {
         $max = max($data);
      }
   }
}
if ($max <= 2) {
   $formaty = ".2f";
} else if ($max <= 4) {
   $formaty = ".1f";
}

if ($max > 2000) {
   $formaty = "0.3s";
}

$pmComponent = new PluginMonitoringComponent();
$pmCommand = new PluginMonitoringCommand();

$pmComponent->getFromDB($_GET['components_id']);
$pmCommand->getFromDB($pmComponent->fields['plugin_monitoring_commands_id']);

echo '<script type="text/javascript">
';

echo 'function updategraph'.$_GET['items_id'].$_GET['time'].$suffix.'() {

   var chart = nv.models.lineChart();

   chart.xAxis // chart sub-models (ie. xAxis, yAxis, etc) when accessed directly, return themselves, not the partent chart, so need to chain separately
      .tickFormat(function(d) { return d3.time.format("'.$format.'")(new Date(d)) });

   chart.yAxis
      .axisLabel("'.$pmCommand->fields['name'].$titleunit.'")
      .tickFormat(d3.format(\''.$formaty.'\'));

   //chart.forceY([-400,400]);
   chart.forceY([0]);
   data = getdata'.$_GET['items_id'].$_GET['time'].'();
   d3.select("#chart'.$_GET['items_id'].$_GET['time'].$suffix.' svg")
     .datum(data)
     .transition().duration(50)
     .call(chart);

}


function getdata'.$_GET['items_id'].$_GET['time'].'() {
   var format = d3.time.format("'.$format.'");
';
$lab = '';
$num = 1;
$a_names = array();
foreach ($mydatat as $name=>$data) {
   if (!isset($a_names[$name])) {
      $a_names[$name] = $num;
      $num++;
   }
   $display = "checked";
   if (isset($_SESSION['glpi_plugin_monitoring']['perfname'][$_GET['components_id']])) {
      $display = "";
   }
   if (isset($_SESSION['glpi_plugin_monitoring']['perfname'][$_GET['components_id']][$name])) {
      $display = $_SESSION['glpi_plugin_monitoring']['perfname'][$_GET['components_id']][$name];
   }
   if ($display == "checked") {
      echo "var val".$a_names[$name]." = new Array();\n";
      $i = 0;
      $datawarn=0;
      $datacrit=0;
      foreach ($a_labels as $label) {
         if (!isset($data[$i])
                 OR $data[$i] == '') {
            $data[$i] = 0;
         }
         if (isset($_SESSION['glpi_plugin_monitoring']['perfnameinvert'][$_GET['components_id']][$name])) {
            $data[$i] = "-".$data[$i];
         }
         if ($data[$i]=='0') {
            if (strstr(strtolower($name), "warn")) {
               $data[$i]=$datawarn;
            } else if (strstr(strtolower($name), "crit")) {
               $data[$i]=$datacrit;
            }
         } else {
            if (strstr(strtolower($name), "warn")) {
               $datawarn=max($datawarn, $data[$i]);
            } else if (strstr(strtolower($name), "crit")) {
               $datacrit=max($datacrit, $data[$i]);
            }
         }
         echo "val".$a_names[$name].".push({x: format.parse('".$label."'), y: ".$data[$i]."});\n";
         $i++;
         $lab = $label;
      }
   }
}


echo '
  return [
  ';
$color = array();
$color = PluginMonitoringServicegraph::colors();

$colorwarn = array();
$colorwarn = PluginMonitoringServicegraph::colors("warn");

$colorcrit = array();
$colorcrit = PluginMonitoringServicegraph::colors("crit");

if (isset($_SESSION['glpi_plugin_monitoring']['perfnamecolor'][$_GET['components_id']])) {
   foreach ($_SESSION['glpi_plugin_monitoring']['perfnamecolor'][$_GET['components_id']] as $perfname=>$colorperfname) {
      if (isset($color[$colorperfname])) {
         unset($color[$colorperfname]);
      }
      if (isset($colorwarn[$colorperfname])) {
         unset($colorwarn[$colorperfname]);
      }
      if (isset($colorcrit[$colorperfname])) {
         unset($colorcrit[$colorperfname]);
      }
   }
}

$nSerie=0;
foreach ($mydatat as $name=>$data) {
   $display = "checked";
   if (isset($_SESSION['glpi_plugin_monitoring']['perfname'][$_GET['components_id']])) {
      $display = "";
   }
   if (isset($_SESSION['glpi_plugin_monitoring']['perfname'][$_GET['components_id']][$name])) {
      $display = $_SESSION['glpi_plugin_monitoring']['perfname'][$_GET['components_id']][$name];
   }
   if ($display == "checked") {
      $area = 'true';
      $colordisplay = '';
      if (isset($_SESSION['glpi_plugin_monitoring']['perfnamecolor'][$_GET['components_id']][$name])) {
         $colordisplay = $_SESSION['glpi_plugin_monitoring']['perfnamecolor'][$_GET['components_id']][$name];
      } else {
         if (strstr(strtolower($name), "warn")) {
            $colordisplay = array_shift($colorwarn);
         } else if (strstr(strtolower($name), "crit")) {
            $colordisplay = array_shift($colorcrit);
         } else {
            $colordisplay = array_shift($color);
         }
      }

      if (strstr(strtolower($name), "warn")) {
         $area = 'false';
      } else if (strstr(strtolower($name), "crit")) {
         $area = 'false';
      }
      if ($nSerie != 0) {
         echo ',';
      }
      echo '     {
         area: '.$area.',
         values: val'.$a_names[$name].',
         key: "'.$name.'",
         color: "#'.$colordisplay.'"
       }
';
      $nSerie++;
   }
}
echo '  ];
}

updategraph'.$_GET['items_id'].$_GET['time'].$suffix.'();
';
echo '</script>';

Html::ajaxFooter();

?>