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

class PluginMonitoringWeathermap extends CommonDBTM {
   

   static function getTypeName() {
      global $LANG;

      return $LANG['plugin_monitoring']['weathermap'][0];
   }
   
   function canCreate() {
      return PluginMonitoringProfile::haveRight("weathermap", 'w');
   }


   
   function canView() {
      return PluginMonitoringProfile::haveRight("weathermap", 'r');
   }


   
   function canCancel() {
      return PluginMonitoringProfile::haveRight("weathermap", 'w');
   }


   
   function canUndo() {
      return PluginMonitoringProfile::haveRight("weathermap", 'w');
   }


   
   function defineTabs($options=array()){
      global $LANG;

      $ong = array();
      
      if (isset($_GET['id'])
              AND $_GET['id'] > 0) {
         $ong[1] = $LANG['plugin_monitoring']['weathermap'][0];
         $ong[2] = $LANG['plugin_monitoring']['weathermap'][6];
      }
      
      return $ong;
   }
   
   
   
   function generateConfig() {
      global $DB;
      
      if (!isset($_GET['id'])
              OR (isset($_GET['id'])
                      AND $_GET['id'] < 1)) {
         return;
      }
      
      $weathermaps_id = $_GET['id'];
      
      $pmWeathermapnode = new PluginMonitoringWeathermapnode();
      
      $this->getFromDB($weathermaps_id);
      
      echo "\n";
      if ($this->fields['background'] != '') {
         echo "BACKGROUND ".GLPI_PLUGIN_DOC_DIR."/monitoring/weathermapbg/".$this->fields['background']."\n";
      }
      // image file to generate
      echo "IMAGEOUTPUTFILE test.png\n";
      echo "\n";
      
      echo "
WIDTH ".$this->fields["width"]."
HEIGHT ".$this->fields["height"]."
HTMLSTYLE overlib
TITLE ".$this->fields["name"]."
TIMEPOS 10 20 Cree le : ".convDateTime(date("Y-m-d H:i:s"))."

KEYPOS DEFAULT 10 ".($this->fields["height"] - ($this->fields["width"] /16))."
KEYSTYLE  DEFAULT horizontal ".($this->fields["width"] /4)."
KEYTEXTCOLOR 0 0 0
KEYOUTLINECOLOR 0 0 0
KEYBGCOLOR 255 255 255
BGCOLOR 255 255 255
TITLECOLOR 0 0 0
TIMECOLOR 0 0 0
SCALE DEFAULT 0.0001 0.1   255 255 255   255 255 255  
SCALE DEFAULT 0.1 50   0 255 0   255 215 0  
SCALE DEFAULT 50 100   255 215 0   255 0 0  

SET key_hidezero_DEFAULT 1

# End of global section


# TEMPLATE-only NODEs:
NODE DEFAULT
	MAXVALUE 100


# TEMPLATE-only LINKs:
LINK DEFAULT
	WIDTH 4
	BANDWIDTH 100M


# regular NODEs:
";
      $query = "SELECT * FROM `".getTableForItemType("PluginMonitoringWeathermapnode")."`
         WHERE `plugin_monitoring_weathermaps_id`='".$weathermaps_id."'
         ORDER BY `name`";
      $result = $DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         $name = $data['name'];
         if ($name == '') {
            $itemtype = $data['itemtype'];
            $item = new $itemtype();
            $item->getFromDB($data['items_id']);
            $name = $item->getName();            
         }
         
         echo "NODE ".preg_replace("/[^A-Za-z0-9_]/","",$data['name'])."_".$data['id']."
	LABEL ".$name."
	POSITION ".$data['x']." ".$data['y']."      
";         
      }
      

$in = 0;
$out = 0;
$query = "SELECT * FROM `glpi_plugin_monitoring_serviceevents`
         WHERE `plugin_monitoring_services_id`='13616'
         ORDER BY `id` DESC
         LIMIT 1";
$result = $DB->query($query);
while ($data=$DB->fetch_array($result)) {
   $matches = array();
   preg_match("/(?:.*)inBandwidth=([0-9]*).(?:.*)bps outBandwidth=([0-9]*).(?:.*)bps/m", $data['perf_data'], $matches);

   $in = $matches[1];
   $out = $matches[2];
}

echo "

# regular LINKs:
";
      $query = "SELECT * FROM `".getTableForItemType("PluginMonitoringWeathermapnode")."`
         WHERE `plugin_monitoring_weathermaps_id`='".$weathermaps_id."'
         ORDER BY `name`";
      $result = $DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         $queryl = "SELECT * FROM `".getTableForItemType("PluginMonitoringWeathermaplink")."`
            WHERE `plugin_monitoring_weathermapnodes_id_1`='".$data['id']."'";
         $resultl = $DB->query($queryl);
         while ($datal=$DB->fetch_array($resultl)) {
            $bandwidth = $datal['bandwidth_in'].":".$datal['bandwidth_out'];
            if ($datal['bandwidth_in'] == $datal['bandwidth_out']) {
               $bandwidth = $datal['bandwidth_in'];
            }
            $pmWeathermapnode->getFromDB($datal['plugin_monitoring_weathermapnodes_id_2']);
            
            $queryevent = "SELECT * FROM `glpi_plugin_monitoring_serviceevents`
               WHERE `plugin_monitoring_services_id`='".$datal['plugin_monitoring_services_id']."'
                  ORDER BY `id` desc
                  LIMIT 1";
            $resultevent = $DB->query($queryevent);
            while ($dataevent=$DB->fetch_array($resultevent)) {
               $matches1 = array();
               preg_match("/(?:.*)inBandwidth=([0-9]*).(?:.*)bps outBandwidth=([0-9]*).(?:.*)bps/m", $dataevent['perf_data'], $matches1);
               $in = $matches1[1];
               $out = $matches1[2];
            }
            
            
            echo "LINK ".preg_replace("/[^A-Za-z0-9_]/","",$data['name'])."_".$data['id']."-".preg_replace("/[^A-Za-z0-9_]/","",$pmWeathermapnode->fields['name'])."_".$pmWeathermapnode->fields['id']."
	INFOURL /cacti/graph.php?rra_id=all&local_graph_id=35
	OVERLIBGRAPH /cacti/graph_image.php?local_graph_id=35&rra_id=0&graph_nolegend=true&graph_height=100&graph_width=300
	BWLABELPOS 69 31
	TARGET static:".$in.":".$out."
	NODES ".preg_replace("/[^A-Za-z0-9_]/","",$data['name'])."_".$data['id']." ".preg_replace("/[^A-Za-z0-9_]/","",$pmWeathermapnode->fields['name'])."_".$pmWeathermapnode->fields['id']."
	BANDWIDTH ".$bandwidth."      
";
            
         }         
      }
   }
   
   
   
   function showForm($items_id, $options=array()) {
      global $DB,$CFG_GLPI,$LANG;

      if ($items_id == '0') {
         $this->getEmpty();
      } else {
         $this->getFromDB($items_id);
      }
     
      $this->showTabs($options);
      $options['formoptions'] = " enctype='multipart/form-data'";
      $this->showFormHeader($options);

      echo "<tr>";
      echo "<td>";
      echo $LANG['common'][16]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      $objectName = autoName($this->fields["name"], "name", 1,
                             $this->getType());
      autocompletionTextField($this, 'name', array('value' => $objectName));      
      echo "</td>";
      echo "<td>".$LANG['plugin_monitoring']['weathermap'][3]."&nbsp;:</td>";
      echo "<td>";
      Dropdown::showInteger("width", $this->fields['width'], 100, 3000);
      echo "</td>";
      echo "</tr>";
      
      echo "<tr>";
      echo "<td>";
      echo $LANG['plugin_monitoring']['weathermap'][5]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      if ($this->fields['background'] == '') {
         echo "<input type='file' size='25' value='' name='background'/>";
      } else {
         echo $this->fields['background'];
         echo "&nbsp;";
         echo "<input type='image' name='deletepic' value='deletepic' class='submit' src='".$CFG_GLPI["root_doc"]."/pics/delete.png' >";

      }
      echo "</td>";
      echo "<td>".$LANG['plugin_monitoring']['weathermap'][4]."&nbsp;:</td>";
      echo "<td>";
      Dropdown::showInteger("height", $this->fields['height'], 100, 3000);
      echo "</td>";
      echo "</tr>";
      
      
      $this->showFormButtons($options);
      $this->addDivForTabs();
      
      return true;
   }
   
   
   
   function configureNodesLinks($weathermaps_id) {
      global $LANG,$DB,$CFG_GLPI;
      
      $networkPort = new NetworkPort();
      $pmWeathermapnode = new PluginMonitoringWeathermapnode();
      
      
      $this->getFromDB($weathermaps_id);
      
      echo "<table class='tab_cadre_fixe'>";
      echo "<tr class='tab_bg_1'>";
      echo "<th colspan='2'>";
      echo $LANG['plugin_monitoring']['weathermap'][6];
      echo "</th>";
      echo "</tr>";
      
      $this->generateWeathermap($weathermaps_id);
      $map = "<img src='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/send.php?file=weathermap-".$weathermaps_id.".png'/>";
      
      echo "<tr class='tab_bg_1'>";
      echo "<td valign='top' width='10'>";      
      if ($this->fields['background'] == '') {
         echo '<div id="pointer_div" onclick="point_it(event)" style = "background-color:grey;">
            <div id="cross" style="position:relative;visibility:hidden;z-index:2;"></div>
            '.$map.'
            </div>';
      } else {
         echo '<div id="pointer_div" onclick="point_it(event)" style = "background-image:url(\''.$this->fields['background'].'\');">
            <img id="cross" style="position:relative;visibility:hidden;z-index:2;">
            '.$map.'</div>';
      }
      echo "</td>";
      echo "<td valign='top'>";
      
 echo '<script language="JavaScript">
function point_it(event){
	pos_x = event.offsetX?(event.offsetX):event.pageX;
	pos_y = event.offsetY?(event.offsetY):event.pageY;
	document.getElementById("cross").style.left = (pos_x-1) ;
	document.getElementById("cross").style.top = (pos_y-15) ;
   
   var topValue= 0;
   var leftValue= 0;
   var obj = document.getElementById("pointer_div");
   while(obj){
	   leftValue+= obj.offsetLeft;
	   topValue+= obj.offsetTop;
	   obj= obj.offsetParent;
   }


	document.pointform.x.value = pos_x-leftValue;
	document.pointform.y.value = pos_y-topValue;

}
</script>';
   echo '<form name="pointform" method="post" action="'.$CFG_GLPI['root_doc'].'/plugins/monitoring/front/weathermapnode.form.php">';
      echo "<table align='center'>";
      echo "<tr>";
      echo "<td>";
      echo "x :";
      echo "</td>";
      echo "<td>";
      echo '<input type="text" name="x" size="4" />';
      echo "</td>";
      echo "<td>";
      echo "y :";
      echo "</td>";
      echo "<td>";
      echo '<input type="text" name="y" size="4" />';
      echo "</td>";
      echo "</tr>";
      echo "</table>";
      echo "<input type='hidden' name='plugin_monitoring_weathermaps_id' value='".$weathermaps_id."' />";
      
         echo "<table class='tab_cadre'>";
         // * Add node
         echo "<tr>";
         echo "<th colspan='2'>";
         echo "add node";
         echo "</th>";
         echo "</tr>";
         
         echo "<tr>";
         echo "<td>";
         echo $LANG['plugin_monitoring']['weathermap'][7]."&nbsp;:";
         echo "</td>";
         echo "<td>";
         Dropdown::showAllItems("items_id");
         
         echo "</td>";
         echo "</tr>";
         
         echo "<tr>";
         echo "<td>";
         echo $LANG['common'][16]."&nbsp;:";
         echo "</td>";
         echo "<td>";
         echo "<input type='text' name='name' value='' />";     
         echo "</td>";
         echo "</tr>";
         
         echo "<tr>";
         echo "<td align='center' colspan='2'>";
         echo "<input type='submit' name='add' value=\"".$LANG['buttons'][8]."\" class='submit'>";
         echo "</td>";
         echo "</tr>";
            

         // * Change node position
         echo "<tr>";
         echo "<th colspan='2'>";
         echo "Change node position";
         echo "</th>";
         echo "</tr>";

         echo "<tr>";
         echo "<td>";
         echo "</td>";
         echo "<td>";

         $query = "SELECT * FROM `".getTableForItemType("PluginMonitoringWeathermapnode")."`
            WHERE `plugin_monitoring_weathermaps_id`='".$weathermaps_id."'
            ORDER BY `name`";
         $result = $DB->query($query);
         $elements = array();
         $elements[0] = DROPDOWN_EMPTY_VALUE;
         $result = $DB->query($query);
         while ($data=$DB->fetch_array($result)) {
            $itemtype = $data['itemtype'];
            $item = new $itemtype();
            $item->getFromDB($data['items_id']);
            $name = $data['name'];
            if ($name == '') {
               $name = $item->getName();
            }
            $elements[$data['id']] = $name;            
         }
         Dropdown::showFromArray('id', $elements);
         
         echo "</td>";
         echo "</tr>";
         
         echo "<tr>";
         echo "<td align='center' colspan='2'>";
         echo "<input type='submit' name='update' value=\"".$LANG['buttons'][7]."\" class='submit'>";
         echo "</td>";
         echo "</tr>";
         

         // * Delete node
         echo "<tr>";
         echo "<th colspan='2'>";
         echo "Delete node";
         echo "</th>";
         echo "</tr>";

         echo "<tr>";
         echo "<td>";
         echo "</td>";
         echo "<td>";
         $query = "SELECT * FROM `".getTableForItemType("PluginMonitoringWeathermapnode")."`
            WHERE `plugin_monitoring_weathermaps_id`='".$weathermaps_id."'
            ORDER BY `name`";
         $result = $DB->query($query);
         $elements = array();
         $elements[0] = DROPDOWN_EMPTY_VALUE;
         $result = $DB->query($query);
         while ($data=$DB->fetch_array($result)) {
            $itemtype = $data['itemtype'];
            $item = new $itemtype();
            $item->getFromDB($data['items_id']);
            $name = $data['name'];
            if ($name == '') {
               $name = $item->getName();
            }
            $elements[$data['id']] = $name;            
         }
         Dropdown::showFromArray('id', $elements);
         echo "</td>";
         echo "</tr>";         
         
         echo "<tr>";
         echo "<td align='center' colspan='2'>";
         echo "<input type='submit' name='purge' value=\"".$LANG['buttons'][22]."\" class='submit'>";
         echo "</td>";
         echo "</tr>";
         
         echo "</table>";
         
         echo "</form><br/>";

         
         
         echo '<form name="formlink" method="post" action="'.$CFG_GLPI['root_doc'].'/plugins/monitoring/front/weathermaplink.form.php">';
         echo "<table class='tab_cadre'>";
         // *Add Link
         echo "<tr>";
         echo "<th colspan='2'>";
         echo "Add link";
         echo "</th>";
         echo "</tr>";
         
         echo "<tr>";
         echo "<td>";
         echo "Node source*&nbsp:";
         echo "</td>";
         echo "<td>";

         $query = "SELECT `glpi_plugin_monitoring_weathermapnodes`.`id` as `id`,
               `glpi_plugin_monitoring_weathermapnodes`.`name` as `name`,
               `glpi_plugin_monitoring_componentscatalogs_hosts`.`itemtype`, 
               `glpi_plugin_monitoring_componentscatalogs_hosts`.`items_id`,
               `glpi_plugin_monitoring_services`.`id` as `services_id`,
               `glpi_plugin_monitoring_components`.`name` as `components_name`,
               `plugin_monitoring_commands_id`, `glpi_plugin_monitoring_components`.`arguments`
            FROM `glpi_plugin_monitoring_weathermapnodes`
            
            LEFT JOIN `glpi_plugin_monitoring_componentscatalogs_hosts`
               ON (`glpi_plugin_monitoring_weathermapnodes`.`items_id`=`glpi_plugin_monitoring_componentscatalogs_hosts`.`items_id`
                  AND `glpi_plugin_monitoring_weathermapnodes`.`itemtype`=`glpi_plugin_monitoring_componentscatalogs_hosts`.`itemtype`)
            
            LEFT JOIN `glpi_plugin_monitoring_services` 
               ON `plugin_monitoring_componentscatalogs_hosts_id`= `glpi_plugin_monitoring_componentscatalogs_hosts`.`id`

            LEFT JOIN `glpi_plugin_monitoring_components` 
               ON `plugin_monitoring_components_id` = `glpi_plugin_monitoring_components`.`id`
            

            WHERE `is_weathermap` = '1'
            ORDER BY `itemtype`,`items_id`,`glpi_plugin_monitoring_components`.`name`";
         $elements = array();
         $elements[0] = DROPDOWN_EMPTY_VALUE;
         $elements2 = array();
         $result = $DB->query($query);
         while ($data=$DB->fetch_array($result)) {
            $itemtype = $data['itemtype'];
            $item = new $itemtype();
            $item->getFromDB($data['items_id']);
            $name = $data['name'];
            if ($name == '') {
               $name = $item->getName();
            }
            // Try to get device/node connected on this port
            $device_connected = '';
            if ($data['arguments'] != '') {
               $arguments = importArrayFromDB($data['arguments']);
               foreach ($arguments as $argument) {
                  if (!is_numeric($argument)) {
                     // Search networkport have this name or description
                     $a_ports = $networkPort->find("`itemtype`='".$itemtype."'
                        AND `items_id`='".$data['items_id']."'
                        AND `name`='".$argument."'");
                     foreach ($a_ports as $pdata) {
                        if ($device_connected == '') {
                           $oppositeports_id = $networkPort->getContact($pdata['id']);
                           if ($oppositeports_id) {
                              $networkPort->getFromDB($oppositeports_id);
                              $a_nodes = $pmWeathermapnode->find("
                                 `plugin_monitoring_weathermaps_id`='".$weathermaps_id."'
                                 AND `itemtype`='".$networkPort->fields['itemtype']."'
                                 AND `items_id`='".$networkPort->fields['items_id']."'", "", 1);
                              if (count($a_nodes) > 0) {
                                 $a_node = current($a_nodes);
                                 $device_connected = $pmWeathermapnode->getNodeName($a_node['id']);
                              }
                           }
                        }                        
                     }
                     if ($device_connected == ''
                             AND class_exists("PluginFusinvsnmpNetworkPort")) {
                        $queryn = "SELECT `glpi_networkports`.`id` FROM `glpi_plugin_fusinvsnmp_networkports`
                           
                           LEFT JOIN `glpi_networkports`
                              ON `glpi_networkports`.`id`=`networkports_id`
                              
                           WHERE `itemtype`='".$itemtype."'
                           AND `items_id`='".$data['items_id']."'
                           AND `ifdescr`='".$argument."'";
                        
                        $resultn = $DB->query($queryn);
                        while ($pdata=$DB->fetch_array($resultn)) {
                           if ($device_connected == '') {
                              $oppositeports_id = $networkPort->getContact($pdata['id']);
                              if ($oppositeports_id) {
                                 $networkPort->getFromDB($oppositeports_id);
                                 $a_nodes = $pmWeathermapnode->find("
                                    `plugin_monitoring_weathermaps_id`='".$weathermaps_id."'
                                    AND `itemtype`='".$networkPort->fields['itemtype']."'
                                    AND `items_id`='".$networkPort->fields['items_id']."'", "", 1);
                                 if (count($a_nodes) > 0) {
                                    $a_node = current($a_nodes);
                                    
         $queryl = "SELECT `plugin_monitoring_weathermapnodes_id_1`
            FROM `glpi_plugin_monitoring_weathermaplinks`
            
            LEFT JOIN `glpi_plugin_monitoring_weathermapnodes`
               ON `glpi_plugin_monitoring_weathermapnodes`.`id` = `plugin_monitoring_weathermapnodes_id_1`

            WHERE ((`plugin_monitoring_weathermapnodes_id_1`='".$data['id']."'
                        AND `plugin_monitoring_weathermapnodes_id_2`='".$a_node['id']."')
                     OR (`plugin_monitoring_weathermapnodes_id_1`='".$a_node['id']."'
                        AND `plugin_monitoring_weathermapnodes_id_2`='".$data['id']."'))
               AND `plugin_monitoring_weathermaps_id` = '".$weathermaps_id."'";
         $resultl = $DB->query($queryl);
         if ($DB->numrows($resultl) == '0') {
                                    $device_connected = $pmWeathermapnode->getNodeName($a_node['id']);
         }
                                 }
                              }
                           }                        
                        }                        
                     }
                  }
               }               
            }
            if ($device_connected == '') {
               $elements2[$data['id']."-".$data['services_id']] = $name." (".$data['components_name'].")";
            } else {
               $elements[$data['id']."-".$data['services_id']] = $name." (".$data['components_name'].") > ".$device_connected;
            }
         }
         if (count($elements) > 1
                 AND count($elements2) > 0) {
            
            $elements = array_merge($elements,array('0'=>DROPDOWN_EMPTY_VALUE));
            $elements = array_merge($elements, $elements2);
            
         } else {
            $elements = array_merge($elements, $elements2);
         }

         Dropdown::showFromArray('linksource', $elements);

         echo "</td>";
         echo "</tr>";
         
         echo "<tr>";
         echo "<td>";
         echo "Node destination&nbsp:";
         echo "</td>";
         echo "<td>";

         echo "<div id='nodedestination'>";
         
         $query = "SELECT * FROM `".getTableForItemType("PluginMonitoringWeathermapnode")."`
            WHERE `plugin_monitoring_weathermaps_id`='".$weathermaps_id."'
            ORDER BY `name`";
         $result = $DB->query($query);
         $elements = array();
         $elements[0] = DROPDOWN_EMPTY_VALUE;
         $result = $DB->query($query);
         while ($data=$DB->fetch_array($result)) {
            $itemtype = $data['itemtype'];
            $item = new $itemtype();
            $item->getFromDB($data['items_id']);
            $name = $data['name'];
            if ($name == '') {
               $name = $item->getName();
            }
            $elements[$data['id']] = $name;            
         }
         Dropdown::showFromArray('plugin_monitoring_weathermapnodes_id_2', $elements);
         echo "</div>";
         echo "</td>";
         echo "</tr>";
         
         echo "<tr>";
         echo "<td>";
         echo "Max bandwidth input&nbsp:";
         echo "</td>";
         echo "<td>";
         echo "<input type='text' name='bandwidth_in' value=''/>";
         echo "</td>";
         echo "</tr>";
         
         echo "<tr>";
         echo "<td>";
         echo "Max bandwidth output&nbsp:";
         echo "</td>";
         echo "<td>";
         echo "<input type='text' name='bandwidth_out' value=''/>";
         echo "</td>";
         echo "</tr>";
         
         echo "<tr>";
         echo "<td align='center' colspan='2'>";
         echo "<input type='submit' name='add' value=\"".$LANG['buttons'][8]."\" class='submit'>";
         echo "</td>";
         echo "</tr>";
         
         // * Edit link
         echo "<tr>";
         echo "<th colspan='2'>";
         echo "Edit link";
         echo "</th>";
         echo "</tr>";

         echo "<tr>";
         echo "<td colspan='2'>";
         echo "</td>";
         echo "</tr>";
         
         
         // * Delete link
         echo "<tr>";
         echo "<th colspan='2'>";
         echo "Delete link";
         echo "</th>";
         echo "</tr>";
         
         echo "<tr>";
         echo "<td>";
         echo "Link :";
         echo "</td>";
         echo "<td>";
         $pmWeathermapnode = new PluginMonitoringWeathermapnode();
         $query = "SELECT `glpi_plugin_monitoring_weathermaplinks`.`id` as `id`,
               `itemtype`, `items_id`, `name`, `plugin_monitoring_weathermapnodes_id_2`
            FROM `glpi_plugin_monitoring_weathermaplinks`
            
            LEFT JOIN `glpi_plugin_monitoring_weathermapnodes`
               ON `glpi_plugin_monitoring_weathermapnodes`.`id` = `plugin_monitoring_weathermapnodes_id_1`

            WHERE `plugin_monitoring_weathermaps_id` = '".$weathermaps_id."'";
         $elements = array();
         $elements[0] = DROPDOWN_EMPTY_VALUE;
         $result = $DB->query($query);
         while ($data=$DB->fetch_array($result)) {
            $itemtype = $data['itemtype'];
            $item = new $itemtype();
            $item->getFromDB($data['items_id']);
            $name1 = $data['name'];
            if ($name1 == '') {
               $name1 = $item->getName();
            }
            $pmWeathermapnode->getFromDB($data['plugin_monitoring_weathermapnodes_id_2']);
            $itemtype = $pmWeathermapnode->fields['itemtype'];
            $item = new $itemtype();
            $item->getFromDB($pmWeathermapnode->fields['items_id']);
            $name2 = $pmWeathermapnode->fields['name'];
            if ($name2 == '') {
               $name2 = $item->getName();
            }
            
            $elements[$data['id']] = $name1." - ".$name2;            
         }
         Dropdown::showFromArray('id', $elements);

         echo "</td>";
         echo "</tr>";
         
         echo "<tr>";
         echo "<td align='center' colspan='2'>";
         echo "<input type='submit' name='purge' value=\"".$LANG['buttons'][22]."\" class='submit'>";
         echo "</td>";
         echo "</tr>";
      
         echo "</table>";
         echo "</form>";
      
      echo "</td>";
      echo "</tr>";
      
   }
   
   
   
   function generateWeathermap($weathermaps_id) {
      global $CFG_GLPI;
      
      system("/usr/local/bin/php ".GLPI_ROOT."/plugins/monitoring/lib/weathermap/weathermap ".
         "--config 'http://".$_SERVER['SERVER_NAME'].$CFG_GLPI['root_doc']."/plugins/monitoring/front/weathermap_conf.php?id=".$weathermaps_id."' ".
         "--output '".GLPI_PLUGIN_DOC_DIR."/monitoring/weathermap-".$weathermaps_id.".png'");
   }
   
   
   
   function prepareInputForUpdate($input) {

      if (isset($_FILES['background']['type']) && !empty($_FILES['background']['type'])) {
         $mime = $_FILES['background']['type'];
      }
      if (isset($mime)) {
         if ($mime == 'image/png'
                 OR $mime == 'image/jpg'
                 OR $mime == 'image/jpeg') {
            
            // Upload file
            copy($_FILES['background']['tmp_name'], GLPI_PLUGIN_DOC_DIR."/monitoring/weathermapbg/".$_FILES['background']['name']);            
            $input['background'] = $_FILES['background']['name'];
            unlink($_FILES['background']['tmp_name']);
         } else if (isset($input['background'])){
            unset($input['background']);
         }
      }
      return $input;
   }
   
   
   
   function showWidget($id) {
      global $LANG, $DB, $CFG_GLPI;
   
      $this->generateWeathermap($id);
      return '<img src="'.$CFG_GLPI['root_doc'].'/plugins/monitoring/front/send.php?file=weathermap-'.$id.'.png"/>';
   }
   
}

?>