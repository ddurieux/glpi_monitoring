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
      echo "<th colspan='7'>Suggests</th>";
      echo "</tr>";

      echo "<tr>";
      echo "<th></th>";
      echo "<th>".$LANG['common'][16]."</th>";
      echo "<th>Comments</th>";
      echo "<th>".$LANG['common'][13]."</th>";
      echo "</tr>";

      if ($itemtype == "Computer") {
         $this->suggestPartitions($items_id);
         $this->suggestSoftwares($items_id);
      }


      echo "</table>";
   }



   function suggestPartitions($items_id) {
      global $LANG;
      
      $computerDisk = new ComputerDisk();
      $pluginMonitoringCommand = new PluginMonitoringCommand();
      $a_listcommands = $pluginMonitoringCommand->find("`command_name`='check_disk'", "", 1);
      $a_command = current($a_listcommands);
      $pluginMonitoringCommand->getFromDB($a_command['id']);

      $a_list = $computerDisk->find("`computers_id`='".$items_id."'");
      foreach ($a_list as $data) {
         echo "<tr>";
         echo "<td></td>";
         echo "<td><strong>".$LANG['computers'][6]." : </strong>".$data['name']."</td>";
         echo "<td>Check disk ".$data['mountpoint']."</td>";
         echo "<td></td>";
         echo "</tr>";
      }
   }

   
   
   function suggestSoftwares($items_id) {
      global $DB,$LANG;
      
      $pMonitoringService = new PluginMonitoringService();
      
      $a_list = $this->find();
      foreach($a_list as $data) {
         $query = "SELECT `glpi_softwares`.`softwarecategories_id`,
                    `glpi_softwares`.`name` AS softname,
                    `glpi_computers_softwareversions`.`id`,
                    `glpi_states`.`name` AS state,
                    `glpi_softwareversions`.`id` AS verid,
                    `glpi_softwareversions`.`softwares_id`,
                    `glpi_softwareversions`.`name` AS version
             FROM `glpi_computers_softwareversions`
             LEFT JOIN `glpi_softwareversions`
                  ON (`glpi_computers_softwareversions`.`softwareversions_id`
                        = `glpi_softwareversions`.`id`)
             LEFT JOIN `glpi_states`
                  ON (`glpi_states`.`id` = `glpi_softwareversions`.`states_id`)
             LEFT JOIN `glpi_softwares`
                  ON (`glpi_softwareversions`.`softwares_id` = `glpi_softwares`.`id`)
             WHERE `glpi_computers_softwareversions`.`computers_id` = '$items_id'
                  AND `glpi_softwares`.`name` REGEXP '".$data['softwares_name']."'
             ORDER BY `softwarecategories_id`, `softname`, `version`";
         $result = $DB->query($query);
         while ($sdata = $DB->fetch_array($result)) {
            
            $pMonitoringService->getFromDB($data['plugin_monitoring_services_id']);
            echo "<tr>";
            echo "<td></td>";
            echo "<td><strong>".$LANG['help'][31]." : </strong>".$sdata['softname']."</td>";
            echo "<td>Check mysql</td>";
            echo "<td></td>";
            echo "</tr>";
         }
      }
   }
   
}

?>