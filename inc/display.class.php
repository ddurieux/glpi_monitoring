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

class PluginMonitoringDisplay extends CommonDBTM {
   

   function showBoard($itemtype) {
      global $DB,$CFG_GLPI,$LANG;

      $item = new $itemtype();
      $query = "SELECT * FROM `".getTableForItemType($itemtype)."`";
      $result = $DB->query($query);
      echo "<table class='tab_cadrehov'>";
      
      echo "<tr class='tab_bg_1'>";
      echo "<th>";
      echo "</th>";
      echo "<th>";
      echo "</th>";
      echo "<th>";
      echo "</th>";
      echo "<th>";
      echo "Status";
      echo "</th>";
      echo "<th>";
      echo "Graphs";
      echo "</th>";
      echo "<th>";
      echo "Dégradé";
      echo "</th>";
      echo "<th>";
      echo "Last check";
      echo "</th>";
      echo "<th>";
      echo "</th>";     
      echo "</tr>";
      while ($data=$DB->fetch_array($result)) {
         echo "<tr class='tab_bg_1'>";

         $this->displayLine($data, $itemtype);
         
         echo "</tr>";         
      }
      echo "</table>";
   }
   
   
   
   static function displayLine($data, $itemtype) {
      global $DB,$CFG_GLPI,$LANG;
      
      $pMonitoringHost_Service = new PluginMonitoringHost_Service();
      
      echo "<td width='32'>";
      $shortstate = self::getState($data['state']);
      echo "<img src='".$CFG_GLPI['root_doc']."/plugins/monitoring/pics/box_".$shortstate."_32.png'/>";
      echo "</td>";
      if (isset($data['itemtype']) AND $data['itemtype'] != '') {
         $itemtypemat = $data['itemtype'];
         $itemmat = new $itemtypemat();
         $itemmat->getFromDB($data['items_id']);
         echo "<td>";
         echo $itemmat->getTypeName();
         echo "</td>";

         echo "<td>";
         echo $itemmat->getLink(1);
         echo "</td>";
      } else {
         echo "<td>Services</td>";
         echo "<td>".$data['name']." sur ...</td>";
      }

      echo "<td align='center'>";
      echo $data['state'];
      echo "</td>";

      echo "<td>";
      $to = new PluginMonitoringRrdtool();
      $plu = new PluginMonitoringHostevent();
      $img = '';
      if ($itemtype == 'PluginMonitoringHost_Service') {
         $plu->parseToRrdtool($data['id'], $itemtype);
         $to->displayGLPIGraph($itemtype, $data['id'], "12h");
         $img = "<img src='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/send.php?file=".$itemtype."-".$data['id']."-12h.gif'/>";
      } else if (isset($data['itemtype']) AND $data['itemtype'] != '') {
         $plu->parseToRrdtool($data['items_id'], $data['itemtype']);
         $to->displayGLPIGraph($data['itemtype'], $data['items_id'], "12h");
         $img = "<img src='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/send.php?file=".$data['itemtype']."-".$data['items_id']."-12h.gif'/>";
      }         
      echo "<a href=''>";
      showToolTip($img, array('img'=>$CFG_GLPI['root_doc']."/plugins/monitoring/pics/stats_32.png"));
      echo "</a>";
      echo "</td>";

      // Mode dégradé
      if ($itemtype == 'PluginMonitoringHost_Service') {
         echo "<td></td>";
      } else {
         echo "<td align='center'>";
         // Get all services of this host
         $a_serv = $pMonitoringHost_Service->find("`plugin_monitoring_hosts_id`='".$data['id']."'");
         $globalserv_state['red'] = 0;
         $globalserv_state['orange'] = 0;
         $globalserv_state['green'] = 0;
         $tooltip = "<table class='tab_cadrehov' width='300'>";
         $tooltip .= "<tr class='tab_bg_1'>
            <td width='200'><strong>Host</strong> :</td><td>
            <img src='".$CFG_GLPI['root_doc']."/plugins/monitoring/pics/box_".$shortstate."_32.png'/></td></tr>";
         foreach ($a_serv as $sdata) {
            $stateserv = self::getState($sdata['state']);
            $globalserv_state[$stateserv]++;
            $tooltip .= "<tr class='tab_bg_1'><td>".$sdata['name']." :</td><td>
                     <img src='".$CFG_GLPI['root_doc']."/plugins/monitoring/pics/box_".$stateserv."_32.png'/></td></tr>";
         }
         $tooltip .= "</table>";
         $globalserv_state[$shortstate]++;
         
         $img = '';
         if ($globalserv_state['red'] > 0) {
            $img = $CFG_GLPI['root_doc']."/plugins/monitoring/pics/box_red_32.png";
         } else if ($globalserv_state['orange'] > 0) {
            $img = $CFG_GLPI['root_doc']."/plugins/monitoring/pics/box_orange_32.png";
         } else {
            $img = $CFG_GLPI['root_doc']."/plugins/monitoring/pics/box_green_32.png";
         }
         showToolTip($tooltip, array('img'=>$img));
         echo "</td>";
      }


      echo "<td>";
      echo convDate($data['last_check']).' '. substr($data['last_check'], 11, 8);
      echo "</td>";

      echo "<td>";
      echo $data['event'];
      echo "</td>";
   }

   
   static function getState($state) {
      $shortstate = '';
      switch($state) {

         case 'UP':
         case 'OK':
            $shortstate = 'green';
            break;

         case 'DOWN':
         case 'UNREACHABLE':
         case 'CRITICAL':
         case 'DOWNTIME':
            $shortstate = 'red';
            break;

         case 'WARNING':
         case 'UNKNOWN':
         case 'RECOVERY':
         case 'FLAPPING':
            $shortstate = 'orange';
            break;

      }
      return $shortstate;
   }

}

?>