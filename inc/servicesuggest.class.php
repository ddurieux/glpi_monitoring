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


   
   function initSuggest() {
      
      // MySQL Server
      $input = array();
      $input['name'] = 'MySQL';
      $input['plugin_monitoring_commands_id'] = '17';
      $input['softwares_name'] = '([mM][yY][sS][qQ][lL])(.*)([sS][eE][rR][vV][eE][rR])';
      $this->add($input);
      
      // Apache Server
      $input = array();
      $input['name'] = 'Apache';
      $input['plugin_monitoring_commands_id'] = '2';
      $input['softwares_name'] = '^([aA][pP][aA][cC][hH][eE])(\\s[hH][tT][tT][pP](.*)|$)';
      $this->add($input);
      
      // PostgreSQL
      $input = array();
      $input['name'] = 'PostgreSQL';
      $input['plugin_monitoring_commands_id'] = '';
      $input['softwares_name'] = '[pP][oO][sS][tT][gG][rR][eE][sS][qQ][lL] ';
      $this->add($input);
      
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
      global $LANG,$CFG_GLPI;

      
      
      $pluginMonitoringCommand = new PluginMonitoringCommand();
      $pMonitoringHost = new PluginMonitoringHost();
      $pMonitoringHost_Service = new PluginMonitoringHost_Service();
      $num = -1;

      $a_hosts = current($pMonitoringHost->find("`items_id`='".$items_id."'
                        AND `itemtype`='".$itemtype."'"));
      
      $a_host_services = $pMonitoringHost_Service->find("`plugin_monitoring_hosts_id`='".$a_hosts['id']."'");
      $a_suggest_used = array();
      foreach ($a_host_services as $data) {
         $a_suggest_used[] = $data['plugin_monitoring_servicesuggests_id'];
      }
      
      echo "<form name='form' method='post' 
         action='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/servicesuggest.form.php'>";
      echo "<input type='hidden' name='plugin_monitoring_hosts_id' 
               value='".$a_hosts['id']."'/>";
      
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
         $num = $this->suggestPartitions($items_id, $num);
         $num = $this->suggestProcessor($items_id, $num);
         $num = $this->suggestSoftwares($items_id, $num, $a_suggest_used);
      }
     
      echo "</table>";
      
      echo "<table>";
      echo "<tr>";
      echo "<td>&nbsp;<img src='".$CFG_GLPI['root_doc']."/pics/arrow-left.png'/></td>";
      echo "<td>";
      echo "<input type='submit' class='submit' name='addsuggest' value='".$LANG['buttons'][8]."'/>";
      echo "</td>";
      echo "</tr>";
      echo "</table>";
      
      echo "</form>";
   }



   function suggestPartitions($items_id, $num) {
      global $LANG;
      
      $computerDisk = new ComputerDisk();
      $pluginMonitoringCommand = new PluginMonitoringCommand();
      $a_listcommands = $pluginMonitoringCommand->find("`command_name`='check_disk'", "", 1);
      $a_command = current($a_listcommands);
      $pluginMonitoringCommand->getFromDB($a_command['id']);
      
      $a_templates = $this->find("`link`='partition'");

      $a_list = $computerDisk->find("`computers_id`='".$items_id."'");
      foreach ($a_templates as $datatemplate) {
         foreach ($a_list as $data) {
            $num++;
            echo "<tr>";
            echo "<td><input type='checkbox' name='suggestnum[]' value='".$num."' /></td>";
            echo "<td><strong>".$LANG['computers'][6]." : </strong>".$data['name'];
            echo "<input type='hidden' name='itemtype[]' value='ComputerDisk'/>";
            echo "<input type='hidden' name='items_id[]' value='".$data['id']."'/>";
            echo "<input type='hidden' name='plugin_monitoring_servicesuggests_id[]' value='".$datatemplate['id']."'/>";
            echo "</td>";
            echo "<td>".$datatemplate['name']." ".$data['mountpoint']."</td>";
            echo "<td>";
            echo "<input type='hidden' name='plugin_monitoring_services_id[]' value=''/>";
            echo "</td>";
            echo "</tr>";
         }
      }
      return $num;
   }

   
   
   function suggestSoftwares($items_id, $num, $a_suggest_used) {
      global $DB,$LANG;
      
      $pMonitoringService = new PluginMonitoringService();
      
      $a_list = $this->find("`softwares_name` != ''");
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
            $num++;

            if (!in_array($data['id'], $a_suggest_used)) {

               echo "<tr>";
               echo "<td><input type='checkbox' name='suggestnum[]' value='".$num."'/></td>";
               echo "<td><strong>".$LANG['help'][31]." : </strong>".$sdata['softname'];
               echo "<input type='hidden' name='itemtype[]' value=''/>";
               echo "<input type='hidden' name='items_id[]' value=''/>";
               echo "<input type='hidden' name='plugin_monitoring_servicesuggests_id[]' value='".$data['id']."'/>";
               echo "</td>";
               echo "<td>Check mysql</td>";
               $a_listtemplates = $pMonitoringService->find("`is_template`='1'
                     AND `plugin_monitoring_commands_id`='".$data['plugin_monitoring_commands_id']."'");
               $list = array();
               $list[0] = "------";
               foreach ($a_listtemplates as $datatemplates) {
                  $list[$datatemplates['id']] = $datatemplates['template_name'];
               }
               echo "<td>";
               Dropdown::showFromArray("plugin_monitoring_services_id[]", $list);
               echo "</td>";
               echo "</tr>";
            }
         }
      }
      return $num;
   }
   
   
   
   function suggestHarddisk($items_id) {
      global $LANG;
      
   }
   
   
   
   function suggestProcessor($items_id, $num) {
      global $LANG;
    
      $a_templates = $this->find("`link`='processor'");

      foreach ($a_templates as $datatemplate) {
         $num++;
         echo "<tr>";
         echo "<td><input type='checkbox' name='suggestnum[]' value='".$num."' /></td>";
         echo "<td><strong>Processor : </strong>Check load";
         echo "<input type='hidden' name='itemtype[]' value=''/>";
         echo "<input type='hidden' name='items_id[]' value=''/>";
         echo "<input type='hidden' name='plugin_monitoring_servicesuggests_id[]' value='".$datatemplate['id']."'/>";
         echo "</td>";
         echo "<td>".$datatemplate['name']."</td>";
         echo "<td>";
         echo "<input type='hidden' name='plugin_monitoring_services_id[]' value=''/>";
         echo "</td>";
         echo "</tr>";
      }
      return $num;
   }
   
   
   
   function suggestMemory($items_id) {
      global $LANG;
      
   }
   
   
   
   function suggestNetwork($items_id) {
      global $LANG;
      
   }
   
}

?>