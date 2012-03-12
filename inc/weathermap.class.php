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
   

   function canCreate() {
      return haveRight('computer', 'w');
   }


   
   function canView() {
      return haveRight('computer', 'r');
   }


   
   function canCancel() {
      return haveRight('computer', 'w');
   }


   
   function canUndo() {
      return haveRight('computer', 'w');
   }


   
   function canValidate() {
      return true;
   }

   
   
   function generateConfig() {
      global $DB;
      
      $weathermaps_id = 1;
      
      $pmWeathermapnode = new PluginMonitoringWeathermapnode();
      
      $this->getFromDB($weathermaps_id);
      
      echo "\n";
      // image file to generate
      echo "IMAGEOUTPUTFILE test.png\n";
      echo "\n";
      echo "
WIDTH ".$this->fields["width"]."
HEIGHT ".$this->fields["height"]."
HTMLSTYLE overlib
TITLE ".$this->fields["name"]."
TIMEPOS 10 20 Creee le : ٪d ٪b ٪Y ٪H:٪M:٪S

KEYPOS DEFAULT 11 865 Charge Reseau
KEYSTYLE  DEFAULT horizontal
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
         echo "NODE ".preg_replace("/[^A-Za-z0-9_]/","",$data['name'])."_".$data['id']."
	LABEL ".$data['name']."
	ICON images/Cloud-Filled.png
	POSITION ".$data['x']." ".$data['y']."
";         
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
            $pmWeathermapnode->getFromDB($datal['plugin_monitoring_weathermapnodes_id_2']);
            echo "LINK ".preg_replace("/[^A-Za-z0-9_]/","",$data['name'])."_".$data['id']."-".preg_replace("/[^A-Za-z0-9_]/","",$pmWeathermapnode->fields['name'])."_".$pmWeathermapnode->fields['id']."
	INFOURL /cacti/graph.php?rra_id=all&local_graph_id=35
	OVERLIBGRAPH /cacti/graph_image.php?local_graph_id=35&rra_id=0&graph_nolegend=true&graph_height=100&graph_width=300
	BWLABELPOS 69 31
	TARGET static:1M:3M
	NODES ".preg_replace("/[^A-Za-z0-9_]/","",$data['name'])."_".$data['id']." ".preg_replace("/[^A-Za-z0-9_]/","",$pmWeathermapnode->fields['name'])."_".$pmWeathermapnode->fields['id']."
	BANDWIDTH ".$datal['bandwidth_in'].":".$datal['bandwidth_out']."
";
            
         }         
      }

      
   }
}

?>