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

class PluginMonitoringServicesuggest extends CommonDBTM {
   

   /**
   * Get name of this type
   *
   *@return text name of this type by language of the user connected
   *
   **/
   static function getTypeName() {
      global $LANG;

      return $LANG['plugin_monitoring']['servicesuggest'][0];
   }



   function canCreate() {
      return true;
   }


   
   function canView() {
      return true;
   }


   
   function canCancel() {
      return true;
   }


   
   function canUndo() {
      return true;
   }


   
   function canValidate() {
      return true;
   }

   

   function getSearchOptions() {
      global $LANG;

      $tab = array();
    
      $tab['common'] = $LANG['plugin_monitoring']['servicesuggest'][0];

		$tab[1]['table'] = $this->getTable();
		$tab[1]['field'] = 'name';
		$tab[1]['linkfield'] = 'name';
		$tab[1]['name'] = $LANG['common'][16];
		$tab[1]['datatype'] = 'itemlink';

      $tab[2]['table']     = $this->getTable();
      $tab[2]['field']     = 'is_active';
      $tab[2]['linkfield'] = 'is_active';
      $tab[2]['name']      = $LANG['common'][60];
      $tab[2]['datatype']  = 'bool';

      return $tab;
   }



   function defineTabs($options=array()){
      global $LANG,$CFG_GLPI;

      $ong = array();

      return $ong;
   }



   function listSuggests($itemtype, $items_id) {
      global $LANG;

      $pluginMonitoringCommand = new PluginMonitoringCommand();

      echo "<br/>";
      echo "<table class='tab_cadre' width='950'>";

      echo "<tr>";
      echo "<th colspan='8'>Suggests</th>";
      echo "</tr>";

      echo "<tr>";
      echo "<th>".$LANG['common'][16]."</th>";
      echo "<th>Comments</th>";
      echo "<th>".$LANG['common'][13]."</th>";
      echo "<th>".$LANG['plugin_monitoring']['command'][1]."</th>";
      echo "<th>".$LANG['plugin_monitoring']['service'][1]."</th>";
      echo "<th>check_interval</th>";
      echo "<th>Last check</th>";
      echo "<th>State</th>";
      echo "</tr>";

      if ($itemtype == "Computer") {
         $this->suggestPartitions($items_id);
      }


      echo "</table>";
   }



   function suggestPartitions($items_id) {
      $computerDisk = new ComputerDisk();
      $pluginMonitoringCommand = new PluginMonitoringCommand();
      $a_listcommands = $pluginMonitoringCommand->find("`command_name`='check_disk'", "", 1);
      $a_command = current($a_listcommands);
      $pluginMonitoringCommand->getFromDB($a_command['id']);

      $a_list = $computerDisk->find("`computers_id`='".$items_id."'");
      foreach ($a_list as $data) {
         echo "<tr>";
         echo "<td>Disk_".$data['name']."</td>";
         echo "<td>Check disk ".$data['mountpoint']."</td>";
         echo "<td></td>";
         echo "<td>".$pluginMonitoringCommand->getLink(1)."</td>";
         echo "<td>3</td>";
         echo "<td>1</td>";
         echo "<td></td>";
         echo "<td></td>";
         echo "</tr>";
      }

   }

}

?>