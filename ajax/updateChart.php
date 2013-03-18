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

// Direct access to file
if (strpos($_SERVER['PHP_SELF'],"updateChart.php")) {
   include ("../../../inc/includes.php");
   header("Content-Type: text/html; charset=UTF-8");
   Html::header_nocache();
}

if (!defined('GLPI_ROOT')) {
   die("Can not acces directly to this file");
}

Session::checkLoginUser();

$pmServicegraph = new PluginMonitoringServicegraph();

$enddate = '';
if ($_POST['customdate'] == ''
        && $_POST['customtime'] == '') {
   $enddate = '';
} else if ($_POST['customdate'] == '') {  
   $enddate =  mktime(date('H', $_POST['customtime']), 
                      date('i', $_POST['customtime']), 
                      date('s', $_POST['customtime']));
} else if ($_POST['customtime'] == '') {
   $enddate = $_POST['customdate'];
} else {
   // have the 2 defined   
   $enddate =  mktime(date('H', $_POST['customtime']), 
                      date('i', $_POST['customtime']), 
                      date('s', $_POST['customtime']),
                      date('n', $_POST['customdate']), 
                      date('d', $_POST['customdate']), 
                      date('Y', $_POST['customdate']));
}

$a_ret = $pmServicegraph->generateData($_POST['rrdtool_template'], 
                             $_POST['itemtype'], 
                             $_POST['items_id'], 
                             $_POST['timezone'], 
                             $_POST['time'],
                             $enddate);
$mydatat = $a_ret[0];
$a_labels = $a_ret[1];
$format = $a_ret[2];
$suffix = '';
if (isset($_POST['suffix'])) {
   $suffix = $_POST['suffix'];
}

if(!isset($_SESSION['glpi_plugin_monitoring']['perfname'][$_POST['components_id']])) {
   PluginMonitoringServicegraph::loadPreferences($_POST['components_id']);
}

//$format = "%H:%M";
//if ($_POST['time'] != "2h"
//   AND $_POST['time'] != "12h"
//   AND $_POST['time'] != "1d") {
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
   if (isset($_SESSION['glpi_plugin_monitoring']['perfname'][$_POST['components_id']])) {
      $display = "";
   }
   if (isset($_SESSION['glpi_plugin_monitoring']['perfname'][$_POST['components_id']][$name])) {
      $display = $_SESSION['glpi_plugin_monitoring']['perfname'][$_POST['components_id']][$name];
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

$pmComponent->getFromDB($_POST['components_id']);
$pmCommand->getFromDB($pmComponent->fields['plugin_monitoring_commands_id']);

echo '<script type="text/javascript">

function updategraph'.$_POST['items_id'].$_POST['time'].$suffix.'() {

   var chart = nv.models.lineChart();

   chart.xAxis // chart sub-models (ie. xAxis, yAxis, etc) when accessed directly, return themselves, not the partent chart, so need to chain separately
      .tickFormat(function(d) { return d3.time.format("'.$format.'")(new Date(d)) });

   chart.yAxis
      .axisLabel("'.$pmCommand->fields['name'].$titleunit.'")
      .tickFormat(d3.format(\''.$formaty.'\'));

   //chart.forceY([-400,400]);
   chart.forceY([0]);
   data = getdata'.$_POST['items_id'].$_POST['time'].'();
   d3.select("#chart'.$_POST['items_id'].$_POST['time'].$suffix.' svg")
     .datum(data)
     .transition().duration(50)
     .call(chart);
    
}


function getdata'.$_POST['items_id'].$_POST['time'].'() {
   var format = d3.time.format("'.$format.'");
';
$lab = '';
foreach ($mydatat as $name=>$data) {
   $display = "checked";
   if (isset($_SESSION['glpi_plugin_monitoring']['perfname'][$_POST['components_id']])) {
      $display = "";
   }
   if (isset($_SESSION['glpi_plugin_monitoring']['perfname'][$_POST['components_id']][$name])) {
      $display = $_SESSION['glpi_plugin_monitoring']['perfname'][$_POST['components_id']][$name];
   }
   if ($display == "checked") {
      echo "var ".$name." = new Array();\n";
      $i = 0;
      foreach ($a_labels as $label) {
         if (!isset($data[$i])
                 OR $data[$i] == '') {
            $data[$i] = 0;
         }
         if (isset($_SESSION['glpi_plugin_monitoring']['perfnameinvert'][$_POST['components_id']][$name])) {
            $data[$i] = "-".$data[$i];
         }
         echo $name.".push({x: format.parse('".$label."'), y: ".$data[$i]."});\n";
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

if (isset($_SESSION['glpi_plugin_monitoring']['perfnamecolor'][$_POST['components_id']])) {
   foreach ($_SESSION['glpi_plugin_monitoring']['perfnamecolor'][$_POST['components_id']] as $perfname=>$colorperfname) {
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

foreach ($mydatat as $name=>$data) {
   $display = "checked";
   if (isset($_SESSION['glpi_plugin_monitoring']['perfname'][$_POST['components_id']])) {
      $display = "";
   }
   if (isset($_SESSION['glpi_plugin_monitoring']['perfname'][$_POST['components_id']][$name])) {
      $display = $_SESSION['glpi_plugin_monitoring']['perfname'][$_POST['components_id']][$name];
   }
   if ($display == "checked") {
      $area = 'true';
      $colordisplay = '';
      if (isset($_SESSION['glpi_plugin_monitoring']['perfnamecolor'][$_POST['components_id']][$name])) {
         $colordisplay = $_SESSION['glpi_plugin_monitoring']['perfnamecolor'][$_POST['components_id']][$name];
      } else {
         if (strstr($name, "warn")) {
            $colordisplay = array_shift($colorwarn);
         } else if (strstr($name, "crit")) {
            $colordisplay = array_shift($colorcrit);
         } else {
            $colordisplay = array_shift($color);     
         }
      }
      
      if (strstr($name, "warning")) {
         $area = 'false';
      } else if (strstr($name, "critical")) {
         $area = 'false';
      }
      
      echo '     {
         area: '.$area.',
         values: '.$name.',
         key: "'.$name.'",
         color: "#'.$colordisplay.'"
       },
';
   }
}
echo '  ];
}

updategraph'.$_POST['items_id'].$_POST['time'].$suffix.'();

</script>';

?>