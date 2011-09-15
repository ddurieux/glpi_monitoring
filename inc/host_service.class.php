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
      global $LANG;

      $pMonitoringHost = new PluginMonitoringHost();

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
      
      $pluginMonitoringCommand = new PluginMonitoringCommand();

      $start = 0;
      if (isset($_REQUEST["start"])) {
         $start = $_REQUEST["start"];
      }

      $a_list = $this->find("`plugin_monitoring_hosts_id`='".$a_hosts['id']."'");

      $number = count($a_list);
      echo "<table class='tab_cadre' >";
      
      echo "<tr>";
      echo "<td colspan='7'>";
      printAjaxPager('',$start,$number);
      echo "</td>";
      echo "</tr>";

      echo "<tr>";
      echo "<th>".$LANG['common'][16]."</th>";
      echo "<th>".$LANG['common'][13]."</th>";
      echo "<th>".$LANG['plugin_monitoring']['command'][1]."</th>";
      echo "<th>".$LANG['plugin_monitoring']['service'][1]."</th>";
      echo "<th>check_interval</th>";
      echo "<th>Last check</th>";
      echo "<th>State</th>";
      echo "</tr>";

      foreach ($a_list as $data) {
         echo "<tr>";
         $template = "";
         if ($data['template_link'] > 0) {
            $this->getFromDB($data['template_link']);
            $template = $this->getLink(1);
            $data['name'] = $this->fields['name'];
            $data['plugin_monitoring_commands_id'] = $this->fields['plugin_monitoring_commands_id'];
            $data['criticity'] = $this->fields['criticity'];
            $data['check_interval'] = $this->fields['check_interval'];
         }
         $this->getFromDB($data['id']);
         echo "<td>";
         echo "<a href='". $this->getLinkURL()."'>".$this->getName()."</a>";
         echo "</td>";
         echo "<td>".$template."</td>";
         $pluginMonitoringCommand->getFromDB($data['plugin_monitoring_commands_id']);
         echo "<td>".$pluginMonitoringCommand->getLink(1)."</td>";
         echo "<td>".$data['criticity']."</td>";
         echo "<td>".$data['check_interval']."</td>";
         echo "<td>".$data['last_check']."</td>";
         echo "<td>".$data['event']."</td>";
         echo "</tr>";
      }
      echo "</table>";
   }
   
}

?>