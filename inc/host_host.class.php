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

class PluginMonitoringHost_Host extends CommonDBRelation {


   // From CommonDBRelation
   public $itemtype_1 = 'PluginMonitoringHost';
   public $items_id_1 = 'plugin_monitoring_hosts_id_1';
   public $itemtype_2 = 'PluginMonitoringHost';
   public $items_id_2 = 'plugin_monitoring_hosts_id_2';


   /*
    *
    * $items_id id of the host PluginMonitoringHost
    */
   function manageDependencies($items_id) {
      global $LANG;

      $pluginMonitoringHost = new PluginMonitoringHost();

      $a_list = $this->find("`plugin_monitoring_hosts_id_1`='".$items_id."'");

      echo "<form name='dependencies_form' id='dependencies_form'
             method='post' action=' ";
      echo getItemTypeFormURL(__CLASS__)."'>";
      echo "<table class='tab_cadre_fixe'>";
      echo "<tr class='tab_bg_1'>";
      echo "<th colspan='3'>";
      echo $LANG['plugin_monitoring']['host'][1];
      echo "</th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td class='right'>";
      $pluginMonitoringHost->showAllHosts("parent_to_add");
      echo "</td>";
      echo "<td class='center'>";
      echo "<input type='submit' class='submit' name='parent_add' value='".
            $LANG['buttons'][8]." >>'>";
      echo "<br><br>";

      if ($a_list) {
         echo "<input type='submit' class='submit' name='parent_delete' value='<< ".
               $LANG['buttons'][6]."'>";
      }
      echo "</td>";
      echo "<td>";
      if ($a_list) {
         echo "<select name='parent_to_delete[]' multiple size='5'>";
         foreach ($a_list as $data) {
            $pluginMonitoringHost->getFromDB($data['plugin_monitoring_hosts_id_2']);
            $classname = $pluginMonitoringHost->fields['itemtype'];
            $class = new $classname;
            $class->getFromDB($pluginMonitoringHost->fields['items_id']);
            echo "<option value='".$data['plugin_monitoring_hosts_id_2']."'>[".$class->getTypeName()."] ".$class->getName()." - ".$class->getField('serial')."</option>";
         }
         echo "</select>";
      } else {
         echo "&nbsp;";
      }
      echo "</td>";
      echo "</tr>";
      echo "</table>";
      echo "<input type='hidden' name='id' value='".$items_id."' />";
      echo "</form>";
   }
}

?>