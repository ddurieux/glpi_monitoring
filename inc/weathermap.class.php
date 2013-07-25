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

class PluginMonitoringWeathermap extends CommonDBTM {
   

   static function getTypeName($nb=0) {
      return __('Weathermap', 'monitoring');
   }
   
   static function canCreate() {
      return PluginMonitoringProfile::haveRight("weathermap", 'w');
   }


   
   static function canView() {
      return PluginMonitoringProfile::haveRight("weathermap", 'r');
   }

   
   
   function defineTabs($options=array()){
      $ong = array();
      $this->addStandardTab(__CLASS__, $ong, $options);
      return $ong;
   }
   
   
   
   /**
    * Display tab
    *
    * @param CommonGLPI $item
    * @param integer $withtemplate
    *
    * @return varchar name of the tab(s) to display
    */
   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {

      $ong = array();
      if ($item->getID() > 0) {
         $ong[1] = __('Weathermap', 'monitoring');
         $ong[2] = __('Nodes and links', 'monitoring');
      }
      return $ong;
   }
   
   
   
   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {

      if ($tabnum == 2) {
         echo $item->configureNodesLinks($item->getID());
      }
      return TRUE;
   }
   
   
   
   function generateConfig($weathermaps_id) {
      global $DB,$CFG_GLPI;
      
      if ($weathermaps_id < 1) {
         return;
      }
      
      $conf = "\n";

      $pmWeathermapnode = new PluginMonitoringWeathermapnode();
      $pmComponent = new PluginMonitoringComponent();
      $pmService = new PluginMonitoringService();
      
      $this->getFromDB($weathermaps_id);
      
      if ($this->fields['background'] != '') {
         $conf .= "BACKGROUND ".GLPI_PLUGIN_DOC_DIR."/monitoring/weathermapbg/".$this->fields['background']."\n";
         //$conf .= "BACKGROUND http://192.168.20.194".$CFG_GLPI['root_doc']."/plugins/monitoring/front/send.php?file=weathermapbg/".$this->fields['background']."\n";
      }
      // image file to generate
      $conf .= "IMAGEOUTPUTFILE ".GLPI_PLUGIN_DOC_DIR."/monitoring/weathermap-".$weathermaps_id.".png\n";
      $conf .= "\n";
      
      $conf .= "WIDTH ".$this->fields["width"]."
HEIGHT ".$this->fields["height"]."
HTMLSTYLE overlib
TITLE ".$this->fields["name"]."
TIMEPOS 10 20 Cree le : ".Html::convDateTime(date("Y-m-d H:i:s"))."

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
      $link = '';
      while ($data=$DB->fetch_array($result)) {
         $name = $data['name'];         
         if ($name == '') {
            $itemtype = $data['itemtype'];
            $item = new $itemtype();
            $item->getFromDB($data['items_id']);
            $name = $item->getName();    
            $link = $item->getLinkURL();
         }
         
         $conf .= "NODE ".preg_replace("/[^A-Za-z0-9_]/","",$data['name'])."_".$data['id']."\n".
            "   LABEL ".$name."\n".
            "   POSITION ".$data['x']." ".$data['y']."\n";
         if ($link != '') {
            $conf .= "   INFOURL ".$link."\n";
         }
         $conf .= "\n";
      }
      
      $conf .= "\n\n# regular LINKs:\n";
      
      $bwlabelpos=array();
      $bwlabelpos[0] = "BWLABELPOS 81 39";
      $bwlabelpos[1] = "BWLABELPOS 71 29";
      $i = 0;
      $doublelink = array();
      $doublelinkbegin = array();
      $doublelinkdiff = array();
      $doublelinknumber = array();
      $query = "SELECT `".getTableForItemType("PluginMonitoringWeathermaplink")."`.*, 
            count(`".getTableForItemType("PluginMonitoringWeathermaplink")."`.`id`) as `cnt` 
            FROM `".getTableForItemType("PluginMonitoringWeathermaplink")."` 
         LEFT JOIN `".getTableForItemType("PluginMonitoringWeathermapnode")."`
            ON `plugin_monitoring_weathermapnodes_id_1` = `".getTableForItemType("PluginMonitoringWeathermapnode")."`.`id`

         WHERE `plugin_monitoring_weathermaps_id`='".$weathermaps_id."'
         group by `plugin_monitoring_weathermapnodes_id_1`, `plugin_monitoring_weathermapnodes_id_2`
         HAVING cnt >1";
      $result=$DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         $tlink = $data['plugin_monitoring_weathermapnodes_id_1']."-".$data['plugin_monitoring_weathermapnodes_id_2'];
         $doublelink[$tlink] = $data['cnt'];
         $doublelinknumber[$tlink] = 0;
         $beg = 0;
         $diff = 0;
         switch($data['cnt']) {
            
            case 2:
               $beg = -22;
               $diff = 44;
               break;
            
            case 3:
               $beg = -33;
               $diff = 33;               
               break;
            
            case 4:
               $beg = -39;
               $diff = 26;
               break;
            
            case 5:
               $beg = -60;
               $diff = 30;
               break;
            
         }
         $doublelinkbegin[$tlink] = $beg;
         $doublelinkdiff[$tlink] = $diff;
      }
      
      $query = "SELECT * FROM `".getTableForItemType("PluginMonitoringWeathermapnode")."`
         WHERE `plugin_monitoring_weathermaps_id`='".$weathermaps_id."'
         ORDER BY `name`";
      $result = $DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         $queryl = "SELECT * FROM `".getTableForItemType("PluginMonitoringWeathermaplink")."`
            WHERE `plugin_monitoring_weathermapnodes_id_1`='".$data['id']."'";
         $resultl = $DB->query($queryl);
         while ($datal=$DB->fetch_array($resultl)) {
             $bandwidth = $datal['bandwidth_in']." ".$datal['bandwidth_out'];
            if ($datal['bandwidth_in'] == $datal['bandwidth_out']) {
               $bandwidth = $datal['bandwidth_in'];
            }
            $pmWeathermapnode->getFromDB($datal['plugin_monitoring_weathermapnodes_id_2']);
            
            $queryevent = "SELECT * FROM `glpi_plugin_monitoring_serviceevents`
               WHERE `plugin_monitoring_services_id`='".$datal['plugin_monitoring_services_id']."'
                  ORDER BY `date` DESC
                  LIMIT 1";
            $resultevent = $DB->query($queryevent);
            $in = '';
            $out = '';
            while ($dataevent=$DB->fetch_array($resultevent)) {
               $pmService->getFromDB($datal['plugin_monitoring_services_id']);
               $pmComponent->getFromDB($pmService->fields['plugin_monitoring_components_id']);
               
               $matches1 = array();
               preg_match("/".$pmComponent->fields['weathermap_regex_in']."/m", $dataevent['perf_data'], $matches1);
               if (isset($matches1[1])) {
                  $in = $matches1[1];
               }
               $matches1 = array();
               preg_match("/".$pmComponent->fields['weathermap_regex_out']."/m", $dataevent['perf_data'], $matches1);
               if (isset($matches1[1])) {
                  $out = $matches1[1];
               }
            }
            $in = $this->checkBandwidth("in", $in, $bandwidth);
            $out = $this->checkBandwidth("out", $out, $bandwidth);
            $nodesuffix = '';
            $tlink = $datal['plugin_monitoring_weathermapnodes_id_1']."-".$datal['plugin_monitoring_weathermapnodes_id_2'];
            if (isset($doublelink[$tlink])) {               
               $nodesuffix = ":".($doublelinkbegin[$tlink] + ($doublelinknumber[$tlink] * $doublelinkdiff[$tlink])).":0";
               $doublelinknumber[$tlink]++;
            }
            $conf .= "LINK ".preg_replace("/[^A-Za-z0-9_]/","",$data['name'])."_".$data['id']."-".preg_replace("/[^A-Za-z0-9_]/","",$pmWeathermapnode->fields['name'])."_".$pmWeathermapnode->fields['id'].$nodesuffix."\n";
            $timezone = '0';
            if (isset($_SESSION['plugin_monitoring_timezone'])) {
               $timezone = $_SESSION['plugin_monitoring_timezone'];
            }
            $timezone_file = str_replace("+", ".", $timezone);
//            if (file_exists(GLPI_ROOT."/files/_plugins/monitoring/PluginMonitoringService-".$datal['plugin_monitoring_services_id']."-2h".$timezone_file.".gif")) {
               $conf .= "   INFOURL ".$CFG_GLPI['root_doc']."/plugins/monitoring/front/display.form.php?itemtype=PluginMonitoringService&items_id=".$datal['plugin_monitoring_services_id']."\n".
                  "   OVERLIBGRAPH ".$CFG_GLPI['root_doc']."/plugins/monitoring/front/send.php?file=PluginMonitoringService-".$datal['plugin_monitoring_services_id']."-2h".$timezone_file.".png\n";
//            }
            $conf .= "   ".$bwlabelpos[$i]."\n";
            // Manage for port down
               $retflag = PluginMonitoringDisplay::getState($pmService->fields['state'], 
                                                            $pmService->fields['state_type'], 
                                                            '', 
                                                            $pmService->fields['is_acknowledged']);
               if ($retflag == 'red') {
                  $conf .= "   TARGET static:".$datal['bandwidth_in'].":".$datal['bandwidth_out']."\n";
               } else {
                  $conf .= "   TARGET static:".$in.":".$out."\n";
               }
            
            $conf .= "   NODES ".preg_replace("/[^A-Za-z0-9_]/","",$data['name'])."_".$data['id'].$nodesuffix." ".preg_replace("/[^A-Za-z0-9_]/","",$pmWeathermapnode->fields['name'])."_".$pmWeathermapnode->fields['id'].$nodesuffix."\n";
            $conf .= "   BANDWIDTH ".$bandwidth."\n\n";
            $i++;
            if ($i == '2') {
               $i = 0;
            }
         }         
      }
      return $conf;
   }
   
   
   
   function showForm($items_id, $options=array()) {
      global $DB,$CFG_GLPI;

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
      echo __('Name')."&nbsp;:";
      echo "</td>";
      echo "<td>";
      $objectName = autoName($this->fields["name"], "name", 1,
                             $this->getType());
      Html::autocompletionTextField($this, 'name', array('value' => $objectName));      
      echo "</td>";
      echo "<td>".__('Width', 'monitoring')."&nbsp;:</td>";
      echo "<td>";
      Dropdown::showNumber("width", array(
                'value' => $this->fields['width'], 
                'min'   => 100, 
                'max'   => 3000,
                'step'  => 20)
      );
      echo "</td>";
      echo "</tr>";
      
      echo "<tr>";
      echo "<td>";
      echo __('Background image', 'monitoring')."&nbsp;:";
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
      echo "<td>".__('Height', 'monitoring')."&nbsp;:</td>";
      echo "<td>";
      Dropdown::showNumber("height", array(
                'value' => $this->fields['height'], 
                'min'   => 100, 
                'max'   => 3000,
                'step'  => 20)
      );
      echo "</td>";
      echo "</tr>";
      
      
      $this->showFormButtons($options);
      $this->addDivForTabs();
      
      return true;
   }
   
   
   
   function configureNodesLinks($weathermaps_id) {
      global $DB,$CFG_GLPI;
      
      $networkPort = new NetworkPort();
      $pmWeathermapnode = new PluginMonitoringWeathermapnode();
      
      
      $this->getFromDB($weathermaps_id);
      
      echo "<table class='tab_cadre_fixe'>";
      echo "<tr class='tab_bg_1'>";
      echo "<th colspan='2'>";
      echo __('Nodes and links', 'monitoring');
      echo "</th>";
      echo "</tr>";
      
      $this->generateWeathermap($weathermaps_id, 1, 1);
      $map = "<img src='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/send.php?file=weathermap-".$weathermaps_id.".png'/>";
      
      echo "<tr class='tab_bg_1'>";
      echo "<td valign='top' width='10'>";      
      if ($this->fields['background'] == '') {
         echo '<script language="JavaScript" type="text/JavaScript">

         function FindPosition(oElement) {
            if(typeof( oElement.offsetParent ) != "undefined") {
               for(var posX = 0, posY = 0; oElement; oElement = oElement.offsetParent) {
                  posX += oElement.offsetLeft;
                  posY += oElement.offsetTop;
               }
               return [ posX, posY ];
            } else {
               return [ oElement.x, oElement.y ];
            }
         }

         function GetCoordinates(e) {
            var PosX = 0;
            var PosY = 0;
            var ImgPos;
            ImgPos = FindPosition(myImg);
            if (!e) var e = window.event;
            if (e.pageX || e.pageY) {
               PosX = e.pageX;
               PosY = e.pageY;
            } else if (e.clientX || e.clientY) {
               PosX = e.clientX + document.body.scrollLeft
                 + document.documentElement.scrollLeft;
               PosY = e.clientY + document.body.scrollTop
                 + document.documentElement.scrollTop;
            }
            PosX = PosX - ImgPos[0];
            PosY = PosY - ImgPos[1];

            document.pointform.x.value = PosX;
            document.pointform.y.value = PosY;
         }

         var myImg = document.getElementById("myImgId");
         myImg.onmousedown = GetCoordinates;

         </script>';
         echo "<div><img src='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/send.php?file=weathermap-".$weathermaps_id.".png'/>";
         echo "<div style='position: absolute; top:40px;' id='myImgId' >
            <table class='gridweathermap' width='".$this->fields['width']."' 
               height='".$this->fields['height']."'>";
         $line = '';
         $nbcol = ceil($this->fields['width'] / 15);
         for ($num=0; $num < $nbcol; $num++) {
            $line .= "<td></td>";
         }
         $line = '<tr>'.$line.'</tr>';
         $nbline = ceil($this->fields['height'] / 15);
         for ($num=0; $num < $nbline; $num++) {
            echo $line;
         }
         echo "</table></div></div>";
         
      } else {
         echo '<div id="pointer_div" onclick="point_it(event)" style = "background-image:url(\''.$this->fields['background'].'\');">
            <img id="cross" style="position:relative;visibility:hidden;z-index:2;">
            '.$map.'</div>';
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
      }
      echo "</td>";
      echo "<td valign='top'>";
      
      echo "<div style='position: fixed;top: 30px;right: 0;z-index:999;' >";
      echo "<table class='tab_cadre' width='100%'>";
      echo "<tr>";
      echo "<td>";
      echo "<a onClick='Ext.get(\"weathermapform\").toggle();'>
      <img src='".$CFG_GLPI["root_doc"]."/pics/deplier_down.png' />&nbsp;
         ".__('Display weathermap form', 'monitoring')."
      &nbsp;<img src='".$CFG_GLPI["root_doc"]."/pics/deplier_down.png' /></a>";
      echo "</td>";
      echo "</tr>";
      echo"</table>";
      echo "</div>";
      
      echo "<div style='position: fixed;top: 50px;right: 0;z-index:1000;' id='weathermapform' >";
      echo '<form name="pointform" method="post" action="'.$CFG_GLPI['root_doc'].'/plugins/monitoring/front/weathermapnode.form.php">';
      echo "<table>";
      echo "<tr>";
      echo "<td>";
      
         echo "<table class='tab_cadre' width='100%'>";
         echo "<tr>";
         echo "<th colspan='2'>";
         echo "x : ";
         echo '<input type="text" name="x" size="4" />';
         echo " ";
         echo "y : ";
         echo '<input type="text" name="y" size="4" />';
         echo "</th>";
         echo "</tr>";
         
         // * Add node
         echo "<tr>";
         echo "<th colspan='2'>";
         echo "<input type='hidden' name='plugin_monitoring_weathermaps_id' value='".$weathermaps_id."' />";
         echo __('Add a node', 'monitoring');
         echo "</th>";
         echo "</tr>";
         
         echo "<tr>";
         echo "<td>";
         echo __('Node', 'monitoring')."&nbsp;:";
         echo "</td>";
         echo "<td>";
         Dropdown::showAllItems("items_id");         
         echo "</td>";
         echo "</tr>";
         
         echo "<tr>";
         echo "<td>";
         echo __('Name')."&nbsp;:";
         echo "</td>";
         echo "<td>";
         echo "<input type='text' name='name' value='' />";     
         echo "</td>";
         echo "</tr>";
         
         echo "<tr>";
         echo "<td align='center' colspan='2'>";
         echo "<input type='submit' name='add' value=\"".__('Add')."\" class='submit'>";
         echo "</td>";
         echo "</tr>";
            

         // * Change node position
         echo "<tr>";
         echo "<th colspan='2'>";
         echo __('Edit a node', 'monitoring');
         echo "</th>";
         echo "</tr>";
        
         echo "<tr>";
         echo "<td colspan='2' align='center'>";

         $query = "SELECT * FROM `".getTableForItemType("PluginMonitoringWeathermapnode")."`
            WHERE `plugin_monitoring_weathermaps_id`='".$weathermaps_id."'
            ORDER BY `name`";
         $result = $DB->query($query);
         $elements = array();
         $elements[0] = Dropdown::EMPTY_VALUE;
         $result = $DB->query($query);
         while ($data=$DB->fetch_array($result)) {
            $itemtype = $data['itemtype'];
            if ($itemtype == '0') {
               $pmWeathermapnode->delete($data);
            } else {
               $item = new $itemtype();
               $item->getFromDB($data['items_id']);
               $name = $data['name'];
               if ($name == '') {
                  $name = $item->getName();
               }
               $elements[$data['id']] = $name;  
            }
         }
         $rand = Dropdown::showFromArray('id_update', $elements);         
         
         $params = array('items_id'        => '__VALUE__',
                         'rand'            => $rand);

         Ajax::updateItemOnSelectEvent("dropdown_id_update$rand", "show_updatenode$rand",
                                     $CFG_GLPI["root_doc"]."/plugins/monitoring/ajax/dropdownWnode.php",
                                     $params, false);

         echo "<span id='show_updatenode$rand'></span>\n";
         
         echo "</td>";
         echo "</tr>";
         
         echo "<tr>";
         echo "<td align='center' colspan='2'>";
         echo "<input type='submit' name='update' value=\"".__('Save')."\" class='submit'>";
         echo "</td>";
         echo "</tr>";
         

         // * Delete node
         echo "<tr>";
         echo "<th colspan='2'>";
         echo __('Delete a node', 'monitoring');
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
         $elements[0] = Dropdown::EMPTY_VALUE;
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
         echo "<input type='submit' name='purge' value=\"".__('Delete permanently')."\" class='submit'>";
         echo "</td>";
         echo "</tr>";
         
         echo "</table>";
         Html::closeForm();
         
      echo "</td>";
      echo "</tr>";
      echo "<tr>";
      echo "<td>";
         
         echo '<form name="formlink" method="post" action="'.$CFG_GLPI['root_doc'].'/plugins/monitoring/front/weathermaplink.form.php">';
         echo "<table class='tab_cadre' width='100%'>";
         // *Add Link
         echo "<tr>";
         echo "<th colspan='2'>";
         echo __('Add a link', 'monitoring');
         echo "</th>";
         echo "</tr>";
         
         echo "<tr>";
         echo "<td>";
         echo __('Source', 'monitoring')."*&nbsp;:";
         echo "</td>";
         echo "<td>";

         $query = "SELECT `glpi_plugin_monitoring_weathermapnodes`.`id` as `id`,
               `glpi_plugin_monitoring_weathermapnodes`.`name` as `name`,
               `glpi_plugin_monitoring_componentscatalogs_hosts`.`itemtype`, 
               `glpi_plugin_monitoring_componentscatalogs_hosts`.`items_id`,
               `glpi_plugin_monitoring_services`.`id` as `services_id`,
               `glpi_plugin_monitoring_components`.`name` as `components_name`,
               `plugin_monitoring_commands_id`, `glpi_plugin_monitoring_components`.`arguments`,
               `glpi_plugin_monitoring_services`.`networkports_id`
            FROM `glpi_plugin_monitoring_weathermapnodes`
            
            LEFT JOIN `glpi_plugin_monitoring_componentscatalogs_hosts`
               ON (`glpi_plugin_monitoring_weathermapnodes`.`items_id`=`glpi_plugin_monitoring_componentscatalogs_hosts`.`items_id`
                  AND `glpi_plugin_monitoring_weathermapnodes`.`itemtype`=`glpi_plugin_monitoring_componentscatalogs_hosts`.`itemtype`)
            
            LEFT JOIN `glpi_plugin_monitoring_services` 
               ON `plugin_monitoring_componentscatalogs_hosts_id`= `glpi_plugin_monitoring_componentscatalogs_hosts`.`id`

            LEFT JOIN `glpi_plugin_monitoring_components` 
               ON `plugin_monitoring_components_id` = `glpi_plugin_monitoring_components`.`id`
            

            WHERE `is_weathermap` = '1'
               AND `plugin_monitoring_weathermaps_id`='".$weathermaps_id."'
            ORDER BY `itemtype`,`items_id`,`glpi_plugin_monitoring_components`.`name`";
         $elements = array();
         $elements[0] = Dropdown::EMPTY_VALUE;
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
                     if (strstr($argument, "[[NETWORKPORTDESCR]]")){
                        if (class_exists("PluginFusinvsnmpNetworkPort")) {
                           $pfNetworkPort = new PluginFusinvsnmpNetworkPort();
                           $pfNetworkPort->loadNetworkport($data['networkports_id']);
                           $argument = $pfNetworkPort->getValue("ifdescr");
                        }
                     } elseif (strstr($argument, "[[NETWORKPORTNUM]]")){
                        $networkPort = new NetworkPort();
                        $networkPort->getFromDB($data['networkports_id']);
                        $argument = $pfNetworkPort->fields['logical_number'];
                     } elseif (strstr($argument, "[[NETWORKPORTNAME]]")){
                        $networkPort = new NetworkPort();
                        $networkPort->getFromDB($data['networkports_id']);
                        $argument = $pfNetworkPort->fields['name'];
                     }
                     
                     
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
               $networkPort->getFromDB($data['networkports_id']);
               $elements2[$data['id']."-".$data['services_id']] = $name." [".$networkPort->getfield('name')."] (".$data['components_name'].")";
            } else {
               $networkPort->getFromDB($data['networkports_id']);
               $elements[$data['id']."-".$data['services_id']] = $name." [".$networkPort->getfield('name')."] (".$data['components_name'].") > ".$device_connected;
            }
         }
         if (count($elements) > 1
                 AND count($elements2) > 0) {
            
            $elements = array_merge($elements,array('0'=>Dropdown::EMPTY_VALUE));
            $elements = array_merge($elements, $elements2);
            
         } else {
            $elements = array_merge($elements, $elements2);
         }

         Dropdown::showFromArray('linksource', $elements);

         echo "</td>";
         echo "</tr>";
         
         echo "<tr>";
         echo "<td>";
         echo __('Destination', 'monitoring')."&nbsp;:";
         echo "</td>";
         echo "<td>";

         echo "<div id='nodedestination'>";
         
         $query = "SELECT * FROM `".getTableForItemType("PluginMonitoringWeathermapnode")."`
            WHERE `plugin_monitoring_weathermaps_id`='".$weathermaps_id."'
            ORDER BY `name`";
         $result = $DB->query($query);
         $elements = array();
         $elements[0] = Dropdown::EMPTY_VALUE;
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
         echo __('Max bandwidth input', 'monitoring')."&nbsp;:";
         echo "</td>";
         echo "<td>";
         echo "<input type='text' name='bandwidth_in' value=''/>";
         echo "</td>";
         echo "</tr>";
         
         echo "<tr>";
         echo "<td>";
         echo __('Max bandwidth output', 'monitoring')."&nbsp;:";
         echo "</td>";
         echo "<td>";
         echo "<input type='text' name='bandwidth_out' value=''/>";
         echo "</td>";
         echo "</tr>";
         
         echo "<tr>";
         echo "<td align='center' colspan='2'>";
         echo "<input type='submit' name='add' value=\"".__('Add')."\" class='submit'>";
         echo "</td>";
         echo "</tr>";
         
         // * Edit link
         echo "<tr>";
         echo "<th colspan='2'>";
         echo __('Edit a link', 'monitoring');
         echo "</th>";
         echo "</tr>";
         echo "<tr>";
         echo "<td colspan='2' align='center'>";
         $pmWeathermapnode = new PluginMonitoringWeathermapnode();
         $query = "SELECT `glpi_plugin_monitoring_weathermaplinks`.`id` as `id`,
               `itemtype`, `items_id`, `name`, `plugin_monitoring_weathermapnodes_id_2`
            FROM `glpi_plugin_monitoring_weathermaplinks`
            
            LEFT JOIN `glpi_plugin_monitoring_weathermapnodes`
               ON `glpi_plugin_monitoring_weathermapnodes`.`id` = `plugin_monitoring_weathermapnodes_id_1`

            WHERE `plugin_monitoring_weathermaps_id` = '".$weathermaps_id."'";
         $elements = array();
         $elements[0] = Dropdown::EMPTY_VALUE;
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
         $rand = Dropdown::showFromArray('id_update', $elements);
         
         $params = array('items_id'        => '__VALUE__',
                         'rand'            => $rand);

         Ajax::updateItemOnSelectEvent("dropdown_id_update$rand", "show_updatelink$rand",
                                     $CFG_GLPI["root_doc"]."/plugins/monitoring/ajax/dropdownWlink.php",
                                     $params, false);
         echo "<span id='show_updatelink$rand'></span>\n";
         echo "</td>";
         echo "</tr>";
         
         
         // * Delete link
         echo "<tr>";
         echo "<th colspan='2'>";
         echo __('Delete a link', 'monitoring');
         echo "</th>";
         echo "</tr>";
         
         echo "<tr>";
         echo "<td>";
         echo "Link :";
         echo "</td>";
         echo "<td>";
         Dropdown::showFromArray('id', $elements);
         echo "</td>";
         echo "</tr>";
         
         echo "<tr>";
         echo "<td align='center' colspan='2'>";
         echo "<input type='submit' name='purge' value=\"".__('Delete permanently')."\" class='submit'>";
         echo "</td>";
         echo "</tr>";
      
         echo "</table>";
         Html::closeForm();
         
      echo "</td>";
      echo "</tr>";
      echo "</table>";
      echo "</div>";
      
      echo "</td>";
      echo "</tr>";
      
   }
   
   
   
   function generateWeathermap($weathermaps_id, $force=0, $makehtml=0) {
      
      if ($force == '0'
              AND file_exists(GLPI_PLUGIN_DOC_DIR."/monitoring/weathermap-".$weathermaps_id.".png")) {
         $time_generate = filectime(GLPI_PLUGIN_DOC_DIR."/monitoring/weathermap-".$weathermaps_id.".png");
         if (($time_generate + 150) > date('U')) {
            return;
         }
      } 

      require_once GLPI_ROOT."/plugins/monitoring/lib/weathermap/WeatherMap.functions.php";
      require_once GLPI_ROOT."/plugins/monitoring/lib/weathermap/HTML_ImageMap.class.php";
      require_once GLPI_ROOT."/plugins/monitoring/lib/weathermap/Weathermap.class.php";
      require_once GLPI_ROOT."/plugins/monitoring/lib/weathermap/WeatherMapNode.class.php";
      require_once GLPI_ROOT."/plugins/monitoring/lib/weathermap/WeatherMapLink.class.php";

      $map=new WeatherMap();
      if ($map->ReadConfig($this->generateConfig($weathermaps_id))) {

         $imagefile=GLPI_PLUGIN_DOC_DIR."/monitoring/weathermap-".$weathermaps_id.".png";

         $map->ReadData();

         if ($imagefile != '') {
            $map->DrawMap($imagefile);
            $map->imagefile=$imagefile;
         }

      } else { 
         echo "Problem to generate weathermap"; 
      }

      if ($makehtml == '1') {
         $map->htmlstyle = '';
         $fd=fopen(GLPI_PLUGIN_DOC_DIR."/monitoring/weathermap-".$weathermaps_id.".html", 'w');

         $html = $map->MakeHTML();
         
         $lines = explode("\n",$html);
         $objects_id = array();
         $services_id = array();
         foreach ($lines as $line) {
            $match = array();
            preg_match_all("/\<area id=\"([\w\d:]*)\"  href=\"(?:.*)items_id=(\d+)\" /", $line, $match);
            if (isset($match[1][0])) {
               $objects_id[$match[1][0]] = $match[1][0];
               $services_id[$match[1][0]] = $match[2][0];
            }
         }
         $pmService = new PluginMonitoringService();
         $pmComponent = new PluginMonitoringComponent();
         $pmServicegraph = new PluginMonitoringServicegraph();
         $i = 0;
         foreach ($objects_id as $o_id) {
            $pmService->getFromDB($services_id[$o_id]);
            $pmComponent->getFromDB($pmService->fields['plugin_monitoring_components_id']);
            ob_start();
            $pmServicegraph->displayGraph($pmComponent->fields['graph_template'], 
                                          "PluginMonitoringService", 
                                          $services_id[$o_id], 
                                          "0", 
                                          '2h', 
                                          "div", 
                                          "400");
            $chart = '';
            $chart = ob_get_contents();
            ob_end_clean();
            $chart = str_replace('<div id="chart'.$services_id[$o_id].'2h">', 
                                 '<div id="chart'.$services_id[$o_id].'2h'.$i.'">', 
                                 $chart);
            $chart = str_replace('<div id="updategraph'.$services_id[$o_id].'2h">', 
                                 '<div id="updategraph'.$services_id[$o_id].'2h'.$i.'">', 
                                 $chart);         
            $chart = "<table width='400' class='tab_cadre'><tr><td>".$chart."</td></tr></table>";
             
            $html .= "\n".$this->showToolTip($chart, 
                           array('applyto'=>$o_id, 'display'=>false));
            ob_start();
            $pmServicegraph->displayGraph($pmComponent->fields['graph_template'], 
                              "PluginMonitoringService", 
                              $services_id[$o_id], 
                              "0", 
                              '2h', 
                              "js");
            $chart = '';
            $chart = ob_get_contents();
            ob_end_clean();
            $chart = str_replace('"updategraph'.$services_id[$o_id].'2h"', 
                                 '"updategraph'.$services_id[$o_id].'2h'.$i.'"', 
                                 $chart);
            $chart = str_replace('&time=2h&', 
                                 '&time=2h&suffix='.$i.'&', 
                                 $chart);
            $html .= "\n".$chart;
            $i++;
         }
         
         fwrite($fd, $html);
         fwrite($fd,
            '<hr /><span id="byline">Network Map created with <a href="http://www.network-weathermap.com/?vs=
            0.97a">PHP Network Weathermap v0.97a</a></span></body></html>');
         fclose ($fd);
      }
   }
   
   
   
   function prepareInputForUpdate($input) {

      $mime = '';
      if (isset($_FILES['background']['type']) && !empty($_FILES['background']['type'])) {
         $mime = $_FILES['background']['type'];
      }
      if (isset($mime) AND !empty($mime)) {
         if ($mime == 'image/png'
                 OR $mime == 'image/x-png'
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
   
   
   
   function showWidget($id, $pourcentage) {
      global $DB, $CFG_GLPI;

      $this->generateWeathermap($id);
      $imgdisplay = $CFG_GLPI['root_doc'].'/plugins/monitoring/front/send.php?file=weathermap-'.$id.'.png&date='.date('U');
      $img = GLPI_PLUGIN_DOC_DIR."/monitoring/weathermap-".$id.".png";
      if (file_exists($img)) {
         list($width, $height, $type, $attr) = getimagesize($img);
         $table_width = 950;
         $withreduced = $width;
         if ((($table_width * $pourcentage) / 100) < $width) {
            $withreduced = ceil(($table_width * $pourcentage) / 100);
         }
         return '<img src="'.$imgdisplay.'" width="'.$withreduced.'" />';
      }
   }
   
   
   
   function widgetEvent($id) {
      global $CFG_GLPI;
      
      $img = GLPI_PLUGIN_DOC_DIR."/monitoring/weathermap-".$id.".png";
      if (file_exists($img)) {
         list($width, $height, $type, $attr) = getimagesize($img);      
         return "listeners: {render: function(c) {c.body.on('click', function() { window.open('".$CFG_GLPI["root_doc"]."/plugins/monitoring/front/weathermap_full.php?id=".
                                         $id."', 'weathermap', 'height=".($height + 100).", ".
                                         "width=".($width + 50).", top=100, left=100, scrollbars=yes') });}}";
      }
   }
   
   
   
   /**
    *
    * @param type $type ("in" or "out")
    */
   function checkBandwidth($type, $bandwidth, $bandwidthmax) {

      if ($bandwidth == '') {
         return 0;
      }
      
      $bdmax = $bandwidthmax;
      if (strstr($bandwidthmax, ":")) {
         $split = explode(":", $bandwidthmax);
         if ($type == 'in') {
            $bdmax= $split[0];
         } else if ($type == 'out') {
            $bdmax= $split[1];
         }         
      }
      
      if (strstr($bdmax, "G")) {
         $bdmax = $bdmax * 1000 * 1000 * 1000;
      } else if (strstr($bdmax, "M")) {
         $bdmax = $bdmax * 1000 * 1000;
      } else if (strstr($bdmax, "K")) {
         $bdmax = $bdmax * 1000;
      }
      
      if ($bandwidth > ($bdmax * 1000)) {
         return "0";
      } else {
         return $bandwidth;
      }
   }   
   
   
   function showToolTip($content, $options=array()) {
      global $CFG_GLPI;

      $param['applyto']    = '';
      $param['title']      = '';
      $param['contentid']  = '';
      $param['link']       = '';
      $param['linkid']     = '';
      $param['linktarget'] = '';
      $param['img']        = $CFG_GLPI["root_doc"]."/pics/aide.png";
      $param['popup']      = '';
      $param['ajax']       = '';
      $param['display']    = true;
      $param['autoclose']  = true;

      if (is_array($options) && count($options)) {
         foreach ($options as $key => $val) {
            $param[$key] = $val;
         }
      }

      // No empty content to have a clean display
      if (empty($content)) {
         $content = "&nbsp;";
      }
      $rand = mt_rand();
      $out  = '';

      // Force link for popup
      if (!empty($param['popup'])) {
         $param['link'] = '#';
      }

      if (empty($param['applyto'])) {
         if (!empty($param['link'])) {
            $out .= "<a id='".(!empty($param['linkid'])?$param['linkid']:"tooltiplink$rand")."'";

            if (!empty($param['linktarget'])) {
               $out .= " target='".$param['linktarget']."' ";
            }
            $out .= " href='".$param['link']."'";

            if (!empty($param['popup'])) {
               $out .= " onClick=\"var w=window.open('".$CFG_GLPI["root_doc"]."/front/popup.php?popup=".
                                                     $param['popup']."', 'glpibookmarks', 'height=400, ".
                                                     "width=600, top=100, left=100, scrollbars=yes' ); ".
                       "w.focus();\" ";
            }
            $out .= '>';
         }
         $out .= "<img id='tooltip$rand' alt='' src='".$param['img']."'>";

         if (!empty($param['link'])) {
            $out .= "</a>";
         }
         $param['applyto'] = "tooltip$rand";
      }

      if (empty($param['contentid'])) {
         $param['contentid'] = "content".$param['applyto'];
      }

      $out .= "<span id='".$param['contentid']."' class='x-hidden'>$content</span>";

      $out .= "<script type='text/javascript' >\n";

      $out .= "new Ext.ToolTip({
               target: '".$param['applyto']."',
               anchor: 'left',
               autoShow: true,
               trackMouse: true,
               ";

      if ($param['autoclose']) {
         $out .= "autoHide: true,

                  dismissDelay: 0";
      } else {
         $out .= "autoHide: false,
                  closable: true,
                  autoScroll: true";
      }

      if (!empty($param['title'])) {
         $out .= ",title: \"".$param['title']."\"";
      }
      $out .= ",contentEl: '".$param['contentid']."'";
      $out .= "});";
      $out .= "</script>";

      if ($param['display']) {
         echo $out;
      } else {
         return $out;
      }
   }
   
   
   
   function generateAllGraphs($weathermaps_id) {
      global $DB;
      
      $pmServicegraph = new PluginMonitoringServicegraph();
      $pmComponent = new PluginMonitoringComponent();
      
      $cache = array();
      
      $query = "SELECT * FROM `glpi_plugin_monitoring_weathermaplinks`
         LEFT JOIN `glpi_plugin_monitoring_weathermapnodes` 
            ON `glpi_plugin_monitoring_weathermapnodes`.`id`=`plugin_monitoring_weathermapnodes_id_1`
         LEFT JOIN `glpi_plugin_monitoring_services` 
            ON `glpi_plugin_monitoring_services`.`id`=`plugin_monitoring_services_id`
         WHERE `plugin_monitoring_weathermaps_id`='".$weathermaps_id."'";
      $result = $DB->query($query);
      while ($data=$DB->fetch_array($result)) {

         $graph_template = '';
         if (isset($cache[$data['plugin_monitoring_components_id']])) {
            $graph_template = $cache[$data['plugin_monitoring_components_id']];
         } else {
            $pmComponent->getFromDB($data['plugin_monitoring_components_id']);
            $cache[$data['plugin_monitoring_components_id']] = $pmComponent->fields['graph_template'];
            $graph_template = $pmComponent->fields['graph_template'];
         }

         $pmServicegraph->displayGraph($graph_template, 
                                       "PluginMonitoringService", 
                                       $data['plugin_monitoring_services_id'], 
                                       0, 
                                       '2h');
         
      }
   }
}

?>