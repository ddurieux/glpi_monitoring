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

class PluginMonitoringService extends CommonDBTM {


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
      $tab['common'] = $LANG['common'][32];

      $tab[1]['table']         = $this->getTable();
      $tab[1]['field']         = 'name';
      $tab[1]['name']          = $LANG['common'][16];
      $tab[1]['datatype']      = 'itemlink';
      $tab[1]['itemlink_type'] = $this->getType();
      $tab[1]['massiveaction'] = false; // implicit key==1
      
      $tab[2]['table']         = $this->getTable();
      $tab[2]['field']         = 'id';
      $tab[2]['name']          = $LANG['common'][2];
      $tab[2]['massiveaction'] = false;
      
      $tab[3]['table'] = $this->getTable();
      $tab[3]['field'] = 'state';
      $tab[3]['name']  = "Status";
      
      $tab[4]['table']         = $this->getTable();
      $tab[4]['field']         = 'last_check';
      $tab[4]['name']          = 'last_check';
      $tab[4]['datatype']      = 'datetime';

      $tab[5]['table'] = $this->getTable();
      $tab[5]['field'] = 'state_type';
      $tab[5]['name']  = "Type de status";
      
      return $tab;
   }

   
   
   
   function manageServices($itemtype, $items_id) {
      global $CFG_GLPI,$LANG;
      
      // Check if host service exist
      $a_serv = $this->find("`items_id` = '".$items_id."'
                        AND `itemtype`='".$itemtype."'", "", 1); 
      if (count($a_serv) > 0) {
         // Manage and display services
         $this->listByHost($itemtype, $items_id);
         $pluginMonitoringServicesuggest = new PluginMonitoringServicesuggest();
         $pluginMonitoringServicesuggest->listSuggests($itemtype, $items_id);
      } else {
         // Button to activate 
         echo "<table class='tab_cadre_fixe'>";
         echo "<tr class='tab_bg_1'>";
         echo "<th>Add this host to be monitored</th>";
         echo "</tr>";
         echo "<tr class='tab_bg_1'>";
         echo "<td align='center'>";
         echo "<form name='form' method='post' action='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/service.form.php'>";
         echo "<input type='hidden' name='itemtype' value='".$itemtype."' />";
         echo "<input type='hidden' name='items_id' value='".$items_id."' />";
         echo "<input type='hidden' name='name' value='Check host alive' />";
         echo "<input type='hidden' name='plugin_monitoring_services_id' value='0' />";
         // TODO : Use a check ping
         	
         echo "<input type='submit' name='add' value=\"".$LANG['buttons'][8]."\" class='submit'>";
         echo "</form>";
         echo "</td>";
         echo "</tr>";
         echo "</table>";
      }
      
      
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

      $pMonitoringServicedef = new PluginMonitoringServicedef();

      $a_hosts = current($this->find("`items_id`='".$items_id."'
                        AND `itemtype`='".$itemtype."'"));
      
      $start = 0;
      if (isset($_REQUEST["start"])) {
         $start = $_REQUEST["start"];
      }

      $a_list = $this->find("`plugin_monitoring_services_id`='".$a_hosts['id']."'
         OR `id`='".$a_hosts['id']."'");

      $number = count($a_list);
      echo "<form name='form' method='post' 
         action='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/service.form.php'>";

      echo "<table class='tab_cadre' width='950' >";

      echo "<tr class='tab_bg_1'>";
      echo "<th colspan='5'>";
      echo "Services";
      echo "&nbsp;<a href='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/service.form.php?services_id=".$a_hosts['id']."'>
         <img src='".$CFG_GLPI['root_doc']."/pics/menu_add.png' /></a>";
      
      echo "&nbsp;<a href='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/servicedef.form.php?add_template=1'>
         <img src='".$CFG_GLPI['root_doc']."/pics/menu_addtemplate.png' /></a>";
      echo "</th>";
      echo "</tr>";
      
      echo "<tr class='tab_bg_1'>";
      echo "<th>".$LANG['common'][16]."</th>";
      echo "<th>Check Host</th>";
      echo "<th>".$LANG['common'][13]."</th>";
      echo "<th>Configuration complete</th>";
      echo "<th width='32'>".$LANG['joblist'][0]."</th>";
      echo "</tr>";

      foreach ($a_list as $data) {
         $pMonitoringServicedef->getEmpty();
         echo "<tr class='tab_bg_1'>";
         $this->getFromDB($data['id']);
         
         echo "<td>";
         echo "<a href='".GLPI_ROOT."/plugins/monitoring/front/service.form.php?id=".$data['id']."'>".$this->getName()."</a>";
         echo "<input type='hidden' name='id[]' value='".$this->fields['id']."'/>";
         echo "</td>";
         echo "<td align='center'>";
         if ($data['plugin_monitoring_services_id'] == '0') {
            echo "<img src='".$CFG_GLPI['root_doc']."/pics/ok.png' width='20' height='20' />";
         }         
         echo "</td>";
         echo "<td class='center'>";
         // Template
         $a_listtemplates = $pMonitoringServicedef->find("`is_template`='1'");
         $list = array();
         $list[0] = "------";
         foreach ($a_listtemplates as $datatemplates) {
            $list[$datatemplates['id']] = $datatemplates['name'];
         }
         $pMonitoringServicedef->getFromDB($data['plugin_monitoring_servicedefs_id']);
         echo $pMonitoringServicedef->getName(1);
         echo "</td>";
         $complete = 1;
         
         if (!isset($pMonitoringServicedef->fields['plugin_monitoring_commands_id'])
                 OR empty($pMonitoringServicedef->fields['plugin_monitoring_commands_id'])) {
            $complete = 0;
         }
         if (!isset($pMonitoringServicedef->fields['plugin_monitoring_checks_id'])
                 OR empty($pMonitoringServicedef->fields['plugin_monitoring_checks_id'])) {
            $complete = 0;
         }
         if (!isset($pMonitoringServicedef->fields['calendars_id'])
                 OR empty($pMonitoringServicedef->fields['calendars_id'])) {
            $complete = 0;
         }
         $color = " bgcolor='#00FF00'";
         if ($complete == '0') {
            $color = " bgcolor='#FF0000'";
         }
         echo "<td align='center' ".$color.">";
         echo Dropdown::getYesNo($complete);
         echo "</td>";
         
         // Status
         $shortstate = PluginMonitoringDisplay::getState($data['state'], $data['state_type']);
         echo "<td class='center'>";
         echo "<img src='".$CFG_GLPI['root_doc']."/plugins/monitoring/pics/box_".$shortstate."_32.png'/>";
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
   
   
   
   function showForm($items_id, $options=array(), $services_id='') {
      global $DB,$CFG_GLPI,$LANG;
      
      $pMonitoringCommand = new PluginMonitoringCommand();
      $pMonitoringServicedef = new PluginMonitoringServicedef();
      
      if (isset($_GET['withtemplate']) AND ($_GET['withtemplate'] == '1')) {
         $options['withtemplate'] = 1;
      } else {
         $options['withtemplate'] = 0;
      }

      if ($services_id!='') {
         $this->getEmpty();
      } else {
         $this->getFromDB($items_id);
      }

      $this->showFormHeader($options);
      if (!isset($this->fields['plugin_monitoring_servicedefs_id'])
              OR empty($this->fields['plugin_monitoring_servicedefs_id'])) {
         $pMonitoringServicedef->getEmpty();
      } else {
         $pMonitoringServicedef->getFromDB($this->fields['plugin_monitoring_servicedefs_id']);
      }
      $template = false;


      echo "<tr>";
      echo "<td>";
      if ($services_id!='') {
         echo "<input type='hidden' name='plugin_monitoring_services_id' value='".$services_id."' />";
      }
      echo $LANG['common'][16]."&nbsp;:";
      echo "</td>";
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
      echo "<input type='hidden' name='plugin_monitoring_servicedefs_id_s' value='".$this->fields['plugin_monitoring_servicedefs_id']."'>\n";
      if ($pMonitoringServicedef->fields['is_template'] == '0') {
         $this->fields['plugin_monitoring_servicedefs_id'] = 0;
      }      
      Dropdown::show("PluginMonitoringServicedef", array(
            'name' => 'plugin_monitoring_servicedefs_id',
            'value' => $this->fields['plugin_monitoring_servicedefs_id'],
            'auto_submit' => true,
            'condition' => '`is_template` = "1"'
      ));
      echo "</td>";
      echo "<td>";
      if ($this->fields["items_id"] == '') {

      } else {
         echo "<input type='hidden' name='items_id' value='".$this->fields["items_id"]."'>\n";
         echo "<input type='hidden' name='itemtype' value='".$this->fields["itemtype"]."'>\n";
      }
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
      if ($pMonitoringServicedef->fields['is_template'] == '1') {
         $pMonitoringCommand->getFromDB($pMonitoringServicedef->fields['plugin_monitoring_commands_id']);
         echo $pMonitoringCommand->getLink(1);         
      } else {
         $pMonitoringCommand->getFromDB($pMonitoringServicedef->fields['plugin_monitoring_commands_id']);
         Dropdown::show("PluginMonitoringCommand", array(
                              'name' =>'plugin_monitoring_commands_id',
                              'value'=>$pMonitoringServicedef->fields['plugin_monitoring_commands_id']
                              ));
      }
      echo "</td>";
      echo "</tr>";
      
      echo "<tr>";
      // * checks
      echo "<td>".$LANG['plugin_monitoring']['check'][0]."&nbsp;:</td>";
      echo "<td align='center'>";
      if ($pMonitoringServicedef->fields['is_template'] == '1') {
         $pMonitoringCheck = new PluginMonitoringCheck();
         $pMonitoringCheck->getFromDB($pMonitoringServicedef->fields['plugin_monitoring_checks_id']);
         echo $pMonitoringCheck->getLink(1);
      } else {
         Dropdown::show("PluginMonitoringCheck", 
                        array('name'=>'plugin_monitoring_checks_id',
                              'value'=>$pMonitoringServicedef->fields['plugin_monitoring_checks_id']));
      }
      echo "</td>";
      // * active check
      echo "<td>";
      echo "Active checks enable&nbsp;:";
      echo "</td>";
      echo "<td align='center'>";
      if ($pMonitoringServicedef->fields['is_template'] == '1') {
         echo Dropdown::getYesNo($pMonitoringServicedef->fields['active_checks_enabled']);
      } else {
         echo Dropdown::showYesNo("active_checks_enabled", $pMonitoringServicedef->fields['active_checks_enabled']);
      }
      echo "</td>";
      echo "</tr>";
      
      echo "<tr>";
      // * passive check
      echo "<td>";
      echo "Passive checks enable&nbsp;:";
      echo "</td>";
      echo "<td align='center'>";
      if ($pMonitoringServicedef->fields['is_template'] == '1') {
         echo Dropdown::getYesNo($pMonitoringServicedef->fields['passive_checks_enabled']);
      } else {
         echo Dropdown::showYesNo("passive_checks_enabled", $pMonitoringServicedef->fields['passive_checks_enabled']);
      }
      echo "</td>";
      // * calendar
      echo "<td>".$LANG['plugin_monitoring']['host'][9]."&nbsp;:</td>";
      echo "<td align='center'>";
      if ($pMonitoringServicedef->fields['is_template'] == '1') {
         $calendar = new Calendar();
         $calendar->getFromDB($pMonitoringServicedef->fields['calendars_id']);
         echo $calendar->getLink(1);
      } else {
         dropdown::show("Calendar", array('name'=>'calendars_id',
                                 'value'=>$pMonitoringServicedef->fields['calendars_id']));
      }
      echo "</td>";
      echo "</tr>";
      
      if (!($pMonitoringServicedef->fields['is_template'] == '1'
              AND $pMonitoringServicedef->fields['remotesystem'] == '')) {
      
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
         if ($pMonitoringServicedef->fields['is_template'] == '1') {
            echo $input[$pMonitoringServicedef->fields['remotesystem']];
         } else {
            Dropdown::showFromArray("remotesystem", 
                                 $input, 
                                 array('value'=>$pMonitoringServicedef->fields['remotesystem']));
         }
         echo "</td>";      
         // * is_argument
         echo "<td>";
         echo "Use arguments (Only for NRPE)&nbsp;:";
         echo "</td>";
         echo "<td>";
         if ($pMonitoringServicedef->fields['is_template'] == '1') {
            echo Dropdown::getYesNo($pMonitoringServicedef->fields['is_arguments']);
         } else {
            Dropdown::showYesNo("is_arguments", $pMonitoringServicedef->fields['is_arguments']);
         }
         echo "</td>"; 
         echo "</tr>";

         echo "<tr>";
         // alias command
         echo "<td>";
         echo "Alias command if required (Only for NRPE)&nbsp;:";
         echo "</td>";
         echo "<td>";
         if ($pMonitoringServicedef->fields['is_template'] == '1') {
            echo "<input type='text' name='alias_commandservice' value='".$this->fields['alias_command']."' />";
         } else {
            echo "<input type='text' name='alias_command' value='".$pMonitoringServicedef->fields['alias_command']."' />";
         }
         echo "</td>"; 

         echo "<td>";
         echo "Command link (used for graphs generation)&nbsp;:";
         echo "</td>";
         echo "<td>";
         if ($pMonitoringServicedef->fields['is_template'] == '1') {
            $pMonitoringCommand->getEmpty();
            $pMonitoringCommand->getFromDB($pMonitoringServicedef->fields['aliasperfdata_commands_id']);
            echo $pMonitoringCommand->getLink(1);         
         } else {
            $pMonitoringCommand->getFromDB($pMonitoringServicedef->fields['aliasperfdata_commands_id']);
            Dropdown::show("PluginMonitoringCommand", array(
                                 'name' =>'aliasperfdata_commands_id',
                                 'value'=>$pMonitoringServicedef->fields['aliasperfdata_commands_id']
                                 ));
         }
         echo "</td>"; 
         echo "</tr>";
      }
      
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
         echo "<th colspan='4'>Argument ([text:text] is used to get values dynamically)&nbsp;</th>";
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
            
            if ($value == '') {
               $matches = array();
               preg_match('/(\[\w+\:\w+\])/',
                              nl2br($a_argtext[$key]), $matches);
               if (isset($matches[0])) {
                  $value = $matches[0];
               }
            }
            
            echo "</td>";
            echo "<td>";
            echo "<input type='text' name='arg[".$key."]' value='".$value."'/><br/>";
            echo "</td>";
            echo "</tr>";
         }
      }
      
      $this->showFormButtons($options);
      return true;
   }
   
   
   static function convertArgument($services_id, $argument) {
      global $DB;
      
      $pMonitoringService = new PluginMonitoringService();
      $pMonitoringService->getFromDB($services_id);
      $itemtype = $pMonitoringService->fields['itemtype'];
      $item = new $itemtype();
      $item->getFromDB($pMonitoringService->fields['items_id']);

      $argument = str_replace("[", "", $argument);
      $argument = str_replace("]", "", $argument);
      $a_arg = explode(":", $argument);
      
      $devicetype = '';
      $devicedata = array();
      if ($itemtype == "NetworkPort") {
         $itemtype2 = $item->fields['itemtype'];
         $item2 = new $itemtype2();
         $item2->getFromDB($item->fields['items_id']);
         $devicetype = $itemtype2;
         $devicedata = $item2->fields;
      } else {
         $devicetype = $itemtype;
         $devicedata = $item->fields;
      }
      
      if ($devicetype == "NetworkEquipment") {
         $PluginFusinvsnmpNetworkEquipment = new PluginFusinvsnmpCommonDBTM("glpi_plugin_fusinvsnmp_networkequipments");
         $PluginFusinvsnmpNetworkEquipment->load($devicedata['id']); 
         switch ($a_arg[0]) {
            
            case 'OID':
               // Load SNMP model and get oid.portnum
               $query = "SELECT `glpi_plugin_fusioninventory_mappings`.`name` AS `mapping_name`,
                                `glpi_plugin_fusinvsnmp_modelmibs`.*
                         FROM `glpi_plugin_fusinvsnmp_modelmibs`
                              LEFT JOIN `glpi_plugin_fusioninventory_mappings`
                                        ON `glpi_plugin_fusinvsnmp_modelmibs`.`plugin_fusioninventory_mappings_id`=
                                           `glpi_plugin_fusioninventory_mappings`.`id`
                         WHERE `plugin_fusinvsnmp_models_id`='".$PluginFusinvsnmpNetworkEquipment->getValue("plugin_fusinvsnmp_models_id")."'
                           AND `is_active`='1'
                           AND `oid_port_counter`='0'
                           AND `glpi_plugin_fusioninventory_mappings`.`name`='".$a_arg[1]."'";

               $result=$DB->query($query);
               while ($data=$DB->fetch_array($result)) {
                  return Dropdown::getDropdownName('glpi_plugin_fusinvsnmp_miboids',$data['plugin_fusinvsnmp_miboids_id']).
                       ".".$item->fields['logical_number'];
               }

               
               return '';
               break;
            
            case 'SNMP':
               $pFusinvsnmpConfigSecurity = new PluginFusinvsnmpConfigSecurity();
               $pFusinvsnmpConfigSecurity->getFromDB($PluginFusinvsnmpNetworkEquipment->getValue("plugin_fusinvsnmp_configsecurities_id"));

               switch ($a_arg[1]) {
               
                  case 'version':
                     if ($pFusinvsnmpConfigSecurity->fields['snmpversion'] == '2') {
                        $pFusinvsnmpConfigSecurity->fields['snmpversion'] = '2c';
                     }
                     return $pFusinvsnmpConfigSecurity->fields['snmpversion'];
                     break;
                  
                  case 'authentication':
                     return $pFusinvsnmpConfigSecurity->fields['community'];
                     break;

               }
               
               
               break;
               
         }
      }
      return $argument;
   }
}

?>