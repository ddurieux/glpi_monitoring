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
   
   
   function defineTabs($options=array()){
      global $LANG,$CFG_GLPI;

      $ong = array();
      $ong[1] = "Business Rules";
      $ong[2] = "All services";
      $ong[3] = "Hosts";
      $ong[4] = "Services";
      return $ong;
   }
   
   


   function showBoard($width='', $limit='') {
      global $DB,$CFG_GLPI,$LANG;

      $where = '';
      if ($limit == 'hosts') {
         $where = "`plugin_monitoring_services_id`='0' ";
      } else if ($limit == 'services') {
         $where = "`plugin_monitoring_services_id`>0 ";
      }      
      if (isset($_SESSION['plugin_monitoring']['service']['field'])) {
         foreach ($_SESSION['plugin_monitoring']['service']['field'] as $key=>$value) {
            $wheretmp = '';
            if (isset($_SESSION['plugin_monitoring']['service']['link'][$key])) {
               $wheretmp.= " ".$_SESSION['plugin_monitoring']['service']['link'][$key]." ";
            }

            $wheretmp .= Search::addWhere(
                                   "",
                                   0,
                                   "PluginMonitoringDisplay",
                                   $_SESSION['plugin_monitoring']['service']['field'][$key],
                                   $_SESSION['plugin_monitoring']['service']['searchtype'][$key],
                                   $_SESSION['plugin_monitoring']['service']['contains'][$key]);
            if (!strstr($wheretmp, "``.``")) {
               if ($where != ''
                       AND !isset($_SESSION['plugin_monitoring']['service']['link'][$key])) {
                  $where .= " AND ";
               }
               $where .= $wheretmp;
            }
         }
      }

      if ($where != '') {
         $where = " WHERE ".$where;
         $where = str_replace("`".getTableForItemType("PluginMonitoringDisplay")."`.", 
                 "", $where);
         
      }
      $query = "SELECT * FROM `".getTableForItemType("PluginMonitoringService")."` ".$where."
         ORDER BY `plugin_monitoring_services_id`";
      $result = $DB->query($query);
      if ($width == '') {
         echo "<table class='tab_cadrehov'>";
      } else {
         echo "<table class='tab_cadrehov' style='width:".$width."px;'>";
      }
      
      echo "<tr class='tab_bg_1'>";
      echo "<th>";
      echo $LANG['joblist'][0];
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
         if ($data['plugin_monitoring_services_id'] == '0') {
            echo "<tr class='tab_bg_3'>";
         } else {
            echo "<tr class='tab_bg_1'>";
         }

         $this->displayLine($data);
         
         echo "</tr>";         
      }
      echo "</table>";
   }
   
   
   
   static function displayLine($data) {
      global $DB,$CFG_GLPI,$LANG;

      $pMonitoringService = new PluginMonitoringService();
      $pMonitoringServiceH = new PluginMonitoringService();
      
      $pMonitoringService->getFromDB($data['id']);
      
      echo "<td width='32' class='center'>";
      $shortstate = self::getState($data['state'], $data['state_type']);
      echo "<img src='".$CFG_GLPI['root_doc']."/plugins/monitoring/pics/box_".$shortstate."_32.png'/>";
      echo "</td>";
      if (isset($pMonitoringService->fields['itemtype']) 
              AND $pMonitoringService->fields['itemtype'] != '') {

         $itemtypemat = $pMonitoringService->fields['itemtype'];
         $itemmat = new $itemtypemat();
         $itemmat->getFromDB($pMonitoringService->fields['items_id']);
         echo "<td>";
         echo $itemmat->getTypeName();
         echo "</td>";

      } else {
         echo "<td>Services</td>";
      }
      $nameitem = '';
      if (isset($itemmat->fields['name'])) {
         $nameitem = "[".$itemmat->getLink(1)."]";
      }
      if ($pMonitoringService->fields['plugin_monitoring_services_id'] == '0') {
         echo "<td>".$itemmat->getLink(1)."</td>";
      } else {
         $pMonitoringServiceH->getFromDB($pMonitoringService->fields['plugin_monitoring_services_id']);
         $itemtypemat = $pMonitoringServiceH->fields['itemtype'];
         $itemmat = new $itemtypemat();
         $itemmat->getFromDB($pMonitoringServiceH->fields['items_id']);
         echo "<td>".$pMonitoringService->getLink(1).$nameitem." ".$LANG['networking'][25]." ".$itemmat->getLink(1)."</td>";
      }
      
      

      unset($itemmat);
      echo "<td class='center'>";
      echo $data['state'];
      echo "</td>";

      echo "<td class='center'>";
      $to = new PluginMonitoringRrdtool();
      $plu = new PluginMonitoringServiceevent();
      $img = '';

      if ($to->displayGLPIGraph("PluginMonitoringService", $data['id'], "12h")) {
         $img = "<img src='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/send.php?file=PluginMonitoringService-".$data['id']."-12h.gif'/>";
         echo "<a href='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/display.form.php?itemtype=PluginMonitoringService&items_id=".$data['id']."'>";
      } else {
         $img = '';
      }
      if ($img != '') {
         showToolTip($img, array('img'=>$CFG_GLPI['root_doc']."/plugins/monitoring/pics/stats_32.png"));
         echo "</a>";
      }
      echo "</td>";

      // Mode dégradé
      if ($pMonitoringService->fields['plugin_monitoring_services_id'] > 0) {
         echo "<td></td>";
      } else {
         echo "<td align='center'>";
         // Get all services of this host
         $a_serv = $pMonitoringService->find("`plugin_monitoring_services_id`='".$data['id']."'");
         $globalserv_state = array();
         $globalserv_state['red'] = 0;
         $globalserv_state['red_soft'] = 0;
         $globalserv_state['orange'] = 0;
         $globalserv_state['orange_soft'] = 0;
         $globalserv_state['green'] = 0;
         $tooltip = "<table class='tab_cadrehov' width='300'>";
         $tooltip .= "<tr class='tab_bg_1'>
            <td width='200'><strong>Host</strong> :</td><td>
            <img src='".$CFG_GLPI['root_doc']."/plugins/monitoring/pics/box_".$shortstate."_32.png'/></td></tr>";
         foreach ($a_serv as $sdata) {
            $stateserv = self::getState($sdata['state'], $data['state_type']);
            if (isset($globalserv_state[$stateserv])) {
               $globalserv_state[$stateserv]++;
            }
            $tooltip .= "<tr class='tab_bg_1'><td>".$sdata['name']." :</td><td>
                     <img src='".$CFG_GLPI['root_doc']."/plugins/monitoring/pics/box_".$stateserv."_32.png'/></td></tr>";
         }
         $tooltip .= "</table>";
         $globalserv_state[$shortstate]++;
         
         $img = '';
         if ($globalserv_state['red'] > 0) {
            $img = $CFG_GLPI['root_doc']."/plugins/monitoring/pics/box_red_32.png";
         } else if ($globalserv_state['red_soft'] > 0) {
            $img = $CFG_GLPI['root_doc']."/plugins/monitoring/pics/box_red_32_soft.png";
         } else if ($globalserv_state['orange'] > 0) {
            $img = $CFG_GLPI['root_doc']."/plugins/monitoring/pics/box_orange_32.png";
         } else if ($globalserv_state['orange_soft'] > 0) {
            $img = $CFG_GLPI['root_doc']."/plugins/monitoring/pics/box_orange_32_soft.png";
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

   
   static function getState($state, $state_type) {
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
         case '':
            $shortstate = 'orange';
            break;

      }
      if ($state_type == 'SOFT') {
         $shortstate.= '_soft';
      }
      return $shortstate;
   }
   
   
   
   function displayGraphs($itemtype, $items_id) {
      global $CFG_GLPI;

      $to = new PluginMonitoringRrdtool();
      $plu = new PluginMonitoringHostevent();
      
      $item = new $itemtype();
      $item->getFromDB($items_id);
      
      echo "<table class='tab_cadre_fixe'>";
      
      echo "<tr class='tab_bg_1'>";
      echo "<th>";
      echo $item->getLink(1);
      echo "</th>";
      echo "</tr>";

      $a_list = array();
      $a_list[] = "12h";
      $a_list[] = "1d";
      $a_list[] = "1w";
      $a_list[] = "1m";
      $a_list[] = "0y6m";
      $a_list[] = "1y";
       
      foreach ($a_list as $time) {
      
      echo "<tr class='tab_bg_1'>";
      echo "<th>";
      echo $time;
      echo "</th>";
      echo "</tr>";
         
         echo "<tr class='tab_bg_1'>";
         echo "<td align='center'>";
         $img = '';
         if ($itemtype == 'PluginMonitoringHost_Service') {
            $plu->parseToRrdtool($items_id, $itemtype);
            $to->displayGLPIGraph($itemtype, $items_id, $time, 900);
            $img = "<img src='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/send.php?file=".$itemtype."-".$items_id."-".$time.".gif'/>";
         } else {
            $plu->parseToRrdtool($items_id, $itemtype);
            $to->displayGLPIGraph($itemtype, $items_id, $time, 900);
            $img = "<img src='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/send.php?file=".$itemtype."-".$items_id."-".$time.".gif'/>";
         }         
         echo $img;
         echo "</td>";
         echo "</tr>";
      }
      echo "</table>";
   }
   
   
   
   function displayCounters() {
      global $CFG_GLPI;
      
      $ok = countElementsInTable("glpi_plugin_monitoring_services", 
              "(`state`='OK' OR `state`='UP') AND `state_type`='HARD'");
      
      $warning = countElementsInTable("glpi_plugin_monitoring_services", 
              "(`state`='WARNING' OR `state`='UNKNOWN' OR `state`='RECOVERY' OR `state`='FLAPPING' OR `state` IS NULL)
                 AND `state_type`='HARD'");
      
      $critical = countElementsInTable("glpi_plugin_monitoring_services", 
              "(`state`='DOWN' OR `state`='UNREACHABLE' OR `state`='CRITICAL' OR `state`='DOWNTIME')
                 AND `state_type`='HARD'");
    
      $warning_soft = countElementsInTable("glpi_plugin_monitoring_services", 
              "(`state`='WARNING' OR `state`='UNKNOWN' OR `state`='RECOVERY' OR `state`='FLAPPING' OR `state` IS NULL)
                 AND `state_type`='SOFT'");
      
      $critical_soft = countElementsInTable("glpi_plugin_monitoring_services", 
              "(`state`='DOWN' OR `state`='UNREACHABLE' OR `state`='CRITICAL' OR `state`='DOWNTIME')
                 AND `state_type`='SOFT'");
    
      echo "<table align='center'>";
      echo "<tr>";
      echo "<td>";
         echo "<table class='tab_cadre'>";
         echo "<tr class='tab_bg_1'>";
         echo "<th width='70'>";
         if ($critical > 0) {
            echo "<img src='".$CFG_GLPI['root_doc']."/plugins/monitoring/pics/box_red_40.png'/>";
         }
         echo "</th>";
         echo "<th width='70'>";
         if ($warning > 0) {
            echo "<img src='".$CFG_GLPI['root_doc']."/plugins/monitoring/pics/box_orange_40.png'/>";
         }
         echo "</th>";
         echo "<th width='70'>";
         echo "<img src='".$CFG_GLPI['root_doc']."/plugins/monitoring/pics/box_green_40.png'/>";
         echo "</th>";
         echo "</tr>";

         echo "<th height='30'>";
         if ($critical > 0) {
            echo $critical;
         }
         echo "</th>";
         echo "<th>";
         if ($warning > 0) {
            echo $warning;
         }
         echo "</th>";
         echo "<th>";
         echo $ok;
         echo "</th>";
         echo "</tr>";      
         echo "</table>";
      echo "</td>";
      echo "<td width='100'>";
      
      echo "</td>";
      echo "<td>";
         echo "<table class='tab_cadre'>";
         echo "<tr class='tab_bg_1'>";
         echo "<th width='70' height='40'>";
         if ($critical_soft > 0) {
            echo "<img src='".$CFG_GLPI['root_doc']."/plugins/monitoring/pics/box_red_40_soft.png'/>";
         }
         echo "</th>";
         echo "<th width='70'>";
         if ($warning_soft > 0) {
            echo "<img src='".$CFG_GLPI['root_doc']."/plugins/monitoring/pics/box_orange_40_soft.png'/>";
         }
         echo "</th>";
         echo "</tr>";
         echo "<th height='30'>";
         if ($critical_soft > 0) {
            echo $critical_soft;
         }
         echo "</th>";
         echo "<th>";
         if ($warning_soft > 0) {
            echo $warning_soft;
         }
         echo "</th>";
         echo "</tr>";      
         echo "</table>";
      echo "</td>";
      echo "</tr>";
      echo "</table>";
      
   }

}

?>