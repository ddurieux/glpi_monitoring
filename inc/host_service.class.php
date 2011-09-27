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
      echo "<a href='".GLPI_ROOT."/plugins/monitoring/front/host_service.form.php?items_id=".$items_id."&itemtype=".$itemtype."'>".
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
         echo "<a href='".GLPI_ROOT."/plugins/monitoring/front/host_service.form.php?id=".$data['id']."'>".$this->getName()."</a>";
         echo "<input type='hidden' name='id[]' value='".$this->fields['id']."'/>";
         echo "</td>";
         echo "<td>";
         // Template
         $a_listtemplates = $pMonitoringService->find("`is_template`='1'");
         $list = array();
         $list[0] = "------";
         foreach ($a_listtemplates as $datatemplates) {
            $list[$datatemplates['id']] = $datatemplates['name'];
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
   
   
   
   function showForm($items_id, $options=array(), $itemtype='') {
      global $DB,$CFG_GLPI,$LANG;
      
      $pMonitoringCommand = new PluginMonitoringCommand();
      $pMonitoringService = new PluginMonitoringService();

      if (isset($_GET['withtemplate']) AND ($_GET['withtemplate'] == '1')) {
         $options['withtemplate'] = 1;
      } else {
         $options['withtemplate'] = 0;
      }

      $this->getFromDB($items_id);
      if ($this->fields['plugin_monitoring_services_id'] == '0') {
         $pMonitoringService->getEmpty();
      } else {
         $pMonitoringService->getFromDB($this->fields['plugin_monitoring_services_id']);
      }
      $template = false;

//      $this->showTabs($options);
      $this->showFormHeader($options);

      echo "<tr>";
      echo "<td>".$LANG['common'][16]."&nbsp;:</td>";
      echo "<td>";
      $objectName = autoName($this->fields["name"], "name", ($template === "newcomp"),
                             $this->getType());
      autocompletionTextField($this, 'name', array('value' => $objectName));      
      echo "</td>";
      echo "<td>";
      echo "Gabarit&nbsp;:";
      echo "</td>";
      echo "<td>";
      if ($items_id != '0') {
         echo "<input type='hidden' name='update' value='update'>\n";
      }
      Dropdown::show("PluginMonitoringService", array(
            'name' => 'plugin_monitoring_services_id',
            'value' => $this->fields['plugin_monitoring_services_id'],
            'auto_submit' => true,
            'condition' => '`is_template` = "1"'
      ));
      echo "</td>";
      echo "<td>";
      echo "<input type='hidden' name='items_id' value='".$this->fields["items_id"]."'>\n";
      echo "<input type='hidden' name='itemtype' value='".$this->fields["itemtype"]."'>\n";
      echo "</td>";
      echo "</tr>";
      
      echo "<tr>";
      echo "<th colspan='4'>&nbsp;</th>";
      echo "</tr>";
      
      echo "<tr>";
      // * itemtype link
      if ($this->fields['itemtype'] != '') {
         $itemtype = $this->fields['itemtype'];
         $item = new $itemtype();
         $item->getFromDB($this->fields['items_id']);
         echo "<td>";
         echo "Type <i>".$item->getTypeName()."</i>";
         echo "&nbsp;:</td>";
         echo "<td>";
         echo $item->getLink(1);
         echo "</td>";
      } else {
         echo "<td colspan='2' align='center'>";
         echo "No type associated";
         echo "</td>";
      }      
      // * commande
      echo "<td>";
      echo "Commande&nbsp;:";
      echo "</td>";
      echo "<td align='center'>";
      if ($pMonitoringService->fields['is_template'] == '1') {
         $pMonitoringCommand->getFromDB($pMonitoringService->fields['plugin_monitoring_commands_id']);
         echo $pMonitoringCommand->getLink(1);         
      } else {
         $pMonitoringCommand->getFromDB($pMonitoringService->fields['plugin_monitoring_commands_id']);
         Dropdown::show("PluginMonitoringCommand", array(
                              'name' =>'plugin_monitoring_commands_id',
                              'value'=>$pMonitoringService->fields['plugin_monitoring_commands_id']
                              ));
      }
      echo "</td>";
      echo "</tr>";
      
      echo "<tr>";
      // * checks
      echo "<td>".$LANG['plugin_monitoring']['check'][0]."&nbsp;:</td>";
      echo "<td align='center'>";
      if ($pMonitoringService->fields['is_template'] == '1') {
         $pMonitoringCheck = new PluginMonitoringCheck();
         $pMonitoringCheck->getFromDB($pMonitoringService->fields['plugin_monitoring_checks_id']);
         echo $pMonitoringCheck->getLink(1);
      } else {
         Dropdown::show("PluginMonitoringCheck", 
                        array('name'=>'plugin_monitoring_checks_id',
                              'value'=>$pMonitoringService->fields['plugin_monitoring_checks_id']));
      }
      echo "</td>";
      // * active check
      echo "<td>";
      echo "Active checks enable&nbsp;:";
      echo "</td>";
      echo "<td align='center'>";
      if ($pMonitoringService->fields['is_template'] == '1') {
         echo Dropdown::getYesNo($pMonitoringService->fields['active_checks_enabled']);
      } else {
         echo Dropdown::showYesNo("active_checks_enabled", $pMonitoringService->fields['active_checks_enabled']);
      }
      echo "</td>";
      echo "</tr>";
      
      echo "<tr>";
      // * passive check
      echo "<td>";
      echo "Passive checks enable&nbsp;:";
      echo "</td>";
      echo "<td align='center'>";
      if ($pMonitoringService->fields['is_template'] == '1') {
         echo Dropdown::getYesNo($pMonitoringService->fields['passive_checks_enabled']);
      } else {
         echo Dropdown::showYesNo("passive_checks_enabled", $pMonitoringService->fields['passive_checks_enabled']);
      }
      echo "</td>";
      // * calendar
      echo "<td>".$LANG['plugin_monitoring']['host'][9]."&nbsp;:</td>";
      echo "<td align='center'>";
      if ($pMonitoringService->fields['is_template'] == '1') {
         $calendar = new Calendar();
         $calendar->getFromDB($pMonitoringService->fields['calendars_id']);
         echo $calendar->getLink(1);
      } else {
         dropdown::show("Calendar", array('name'=>'calendars_id',
                                 'value'=>$pMonitoringService->fields['calendars_id']));
      }
      echo "</td>";
      echo "</tr>";
      
      echo "<tr>";
      echo "<th colspan='4'>Remote check</th>";
      echo "</tr>";
      
      echo "<tr>";
      // * remotesystem
      echo "<td>";
      echo "Utility used for remote check&nbsp;:";
      echo "</td>";
      echo "<td>";
      $input = array();
      $input[''] = '------';
      $input['byssh'] = 'byssh';
      $input['nrpe'] = 'nrpe';
      $input['nsca'] = 'nsca';
      if ($pMonitoringService->fields['is_template'] == '1') {
         echo $input[$pMonitoringService->fields['remotesystem']];
      } else {
         Dropdown::showFromArray("remotesystem", 
                              $input, 
                              array('value'=>$pMonitoringService->fields['remotesystem']));
      }
      echo "</td>";      
      // * is_argument
      echo "<td>";
      echo "Use arguments (Only for NRPE)&nbsp;:";
      echo "</td>";
      echo "<td>";
      if ($pMonitoringService->fields['is_template'] == '1') {
         echo Dropdown::getYesNo($pMonitoringService->fields['is_arguments']);
      } else {
         Dropdown::showYesNo("is_arguments", $pMonitoringService->fields['is_arguments']);
      }
      echo "</td>"; 
      echo "</tr>";
      
      echo "<tr>";
      // alias command
      echo "<td>";
      echo "Alias command if required (Only for NRPE)&nbsp;:";
      echo "</td>";
      echo "<td>";
      if ($pMonitoringService->fields['is_template'] == '1') {
         echo "<input type='text' name='alias_commandhost_service' value='".$this->fields['alias_command']."' />";
      } else {
         echo "<input type='text' name='alias_command' value='".$pMonitoringService->fields['alias_command']."' />";
      }
      echo "</td>"; 
      echo "<td colspan='2'></td>";
      echo "</tr>";
      
      
      // * Manage arguments
      $array = array();
      $a_displayarg = array();
      if (isset($pMonitoringCommand->fields['command_line'])) {
         preg_match_all("/\\$(ARG\d+)\\$/", $pMonitoringCommand->fields['command_line'], $array);
         $a_arguments = importArrayFromDB($this->fields['arguments']);
         foreach ($array[0] as $arg) {
            if (strstr($arg, "ARG")) {
               $arg = str_replace('$', '', $arg);
               if (!isset($a_arguments[$arg])) {
                  $a_arguments[$arg] = '';
               }
               $a_displayarg[$arg] = $a_arguments[$arg];
               
            }
         }
      }
      if (count($a_displayarg) > 0) {
         $a_argtext = importArrayFromDB($pMonitoringCommand->fields['arguments']);
         echo "<tr>";
         echo "<th colspan='4'>Arguments&nbsp;</th>";
         echo "</tr>";
          
         foreach ($a_displayarg as $key=>$value) {
         echo "<tr>";
         echo "<th>".$key."</th>";
         echo "<td colspan='2'>";
            if (isset($a_argtext[$key])) {
               echo nl2br($a_argtext[$key])."&nbsp;:";
            } else {
               echo "Argument&nbsp;:";
            }
            echo "</td>";
            echo "<td>";
            echo "<input type='text' name='arg[".$key."]' value='".$value."'/><br/>";
            echo "</td>";
            echo "</tr>";
         }
      }
      
      $this->showFormButtons($options);
//      $this->addDivForTabs();
      return true;
   }
   
}

?>