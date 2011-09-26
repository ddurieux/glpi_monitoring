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

class PluginMonitoringHost_Service extends CommonDBTM {


   /**
    * Display services associated with host
    *
    * @param $itemtype value type of item
    * @param $items_id integer id of the object
    *
    **/
   function listByHost($itemtype, $items_id) {
      global $LANG,$CFG_GLPI;

      $pMonitoringHost = new PluginMonitoringHost();
      $pMonitoringService = new PluginMonitoringService();
      $pMonitoringCheck = new PluginMonitoringCheck();
      $pMonitoringCommand = new PluginMonitoringCommand();

      $a_hosts = current($pMonitoringHost->find("`items_id`='".$items_id."'
                        AND `itemtype`='".$itemtype."'"));
      
      echo "<table class='tab_cadre'>";
      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo "<a href='".GLPI_ROOT."/plugins/monitoring/front/service.form.php?items_id=".$items_id."&itemtype=".$itemtype."'>".
         $LANG['plugin_monitoring']['service'][2]."</a>";
      echo "</td>";
      echo "</tr>";
      echo "</table>";
      echo "<br/>";      

      $start = 0;
      if (isset($_REQUEST["start"])) {
         $start = $_REQUEST["start"];
      }

      $a_list = $this->find("`plugin_monitoring_hosts_id`='".$a_hosts['id']."'");

      $number = count($a_list);
      echo "<form name='form' method='post' 
         action='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/host_service.form.php'>";

      echo "<table class='tab_cadre' width='950' >";
      
//      echo "<tr>";
//      echo "<td colspan='9'>";
//      printAjaxPager('',$start,$number);
//      echo "</td>";
//      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<th>".$LANG['common'][16]."</th>";
      echo "<th>".$LANG['common'][13]."</th>";
      echo "<th>".$LANG['plugin_monitoring']['command'][1]."</th>";
      echo "<th>".$LANG['plugin_monitoring']['service'][1]."</th>";
      echo "<th>check_interval</th>";
      echo "<th>Last check</th>";
      echo "<th>State</th>";
      echo "<th>Arguments</th>";
      echo "</tr>";

      foreach ($a_list as $data) {

         if ($data['plugin_monitoring_services_id'] > 0) {
            $pMonitoringService->getFromDB($data['plugin_monitoring_services_id']);
         } else {

         }

         echo "<tr class='tab_bg_1'>";
         $this->getFromDB($data['id']);
         
         echo "<td>";
         echo $this->getName();
         echo "<input type='hidden' name='id[]' value='".$this->fields['id']."'/>";
         echo "</td>";
         echo "<td>";
         // Template
         $a_listtemplates = $pMonitoringService->find("`is_template`='1'");
         $list = array();
         $list[0] = "------";
         foreach ($a_listtemplates as $datatemplates) {
            $list[$datatemplates['id']] = $datatemplates['template_name'];
         }
         Dropdown::showFromArray("plugin_monitoring_services_id[]", 
                                 $list,
                                 array("value"=>$pMonitoringService->fields['id']));
         echo "</td>";
         $pMonitoringCommand->getFromDB($pMonitoringService->fields['plugin_monitoring_commands_id']);
         echo "<td>".$pMonitoringCommand->getLink(1)."</td>";
         echo "<td></td>";
         $pMonitoringCheck->getFromDB($pMonitoringService->fields['plugin_monitoring_checks_id']);
         echo "<td>".$pMonitoringCheck->getName(1)."</td>";
         echo "<td>".$this->fields['last_check']."</td>";
         echo "<td>".$this->fields['event']."</td>";
         // Manage arguments
         
         echo "<td>";
         $array = array();
         preg_match_all("/\\$[A-Z]+\\$/", $pMonitoringCommand->fields['command_line'], $array);
         $a_arguments = importArrayFromDB($this->fields['arguments']);
         foreach ($array[0] as $arg) {
            if (strstr($arg, "ARG")) {
               $arg = str_replace('$', '', $arg);
               if (!isset($a_arguments[$arg])) {
                  $a_arguments[$arg] = '';
               }
               echo $arg." : <input type='text' name='arg".$this->fields['id']."||".$arg."' value='".$a_arguments[$arg]."' /><br/>";
            }
         }
         echo "</td>";
         
         echo "</tr>";
      }
      echo "<tr class='tab_bg_1'>";
      echo "<td colspan='8' align='center'>";
      echo "<input type='submit' class='submit' name='update' value='".$LANG['buttons'][7]."'>";
      echo "</td>";
      echo "</tr>";
      
      echo "</table>";

      echo "</form>";
   }
   
}

?>