<?php

/*
   ------------------------------------------------------------------------
   Plugin Monitoring for GLPI
   Copyright (C) 2011-2012 by the Plugin Monitoring for GLPI Development Team.

   https://forge.indepnet.net/projects/monitoring/
   ------------------------------------------------------------------------

   LICENSE

   This file is part of Plugin Monitoring project.

   Plugin Monitoring for GLPI is free software: you can redistribute it and/or modify
   it under the terms of the GNU Affero General Public License as published by
   the Free Software Foundation, either version 3 of the License, or
   (at your option) any later version.

   Plugin Monitoring for GLPI is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
   GNU Affero General Public License for more details.

   You should have received a copy of the GNU Affero General Public License
   along with Behaviors. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   Plugin Monitoring for GLPI
   @author    David Durieux
   @co-author 
   @comment   
   @copyright Copyright (c) 2011-2012 Plugin Monitoring for GLPI team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      https://forge.indepnet.net/projects/monitoring/
   @since     2011
 
   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginMonitoringService extends CommonDBTM {


   function canCreate() {
      return haveRight('computer', 'w');
   }


   
   function canView() {
      return haveRight('computer', 'r');
   }


   
   function canCancel() {
      return haveRight('computer', 'w');
   }


   
   function canUndo() {
      return haveRight('computer', 'w');
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
      $tab[4]['name']          = $LANG['plugin_monitoring']['service'][18];
      $tab[4]['datatype']      = 'datetime';

      $tab[5]['table'] = $this->getTable();
      $tab[5]['field'] = 'state_type';
      $tab[5]['name']  = $LANG['plugin_monitoring']['service'][19];
      
      $tab[6]['table'] = 'glpi_entities';
      $tab[6]['field'] = 'completename';
      $tab[6]['name']  = $LANG['entity'][0];
      
      return $tab;
   }

   
   
   function manageServices($itemtype, $items_id) {
      
      if ($itemtype == 'Computer') {
         $pmHostaddress = new PluginMonitoringHostaddress();
         $pmHostaddress->showForm($items_id, $itemtype);
      }
      $pmServices = new PluginMonitoringService();
      $pmServices->listByHost($itemtype, $items_id);
   }
   
   
   
   /**
    * Display services associated with host
    *
    * @param $itemtype value type of item
    * @param $items_id integer id of the object
    *
    **/
   function listByHost($itemtype, $items_id) {
      global $LANG,$CFG_GLPI,$DB;

      $pmComponentscatalog = new PluginMonitoringComponentscatalog();
      
      $query = "SELECT * FROM `glpi_plugin_monitoring_componentscatalogs_hosts`
         WHERE `items_id`='".$items_id."'
            AND `itemtype`='".$itemtype."'";
      $result = $DB->query($query);

      echo "<form name='form' method='post' 
         action='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/service.form.php'>";

      echo "<table class='tab_cadre_fixe'>";

      echo "<tr class='tab_bg_1'>";
      echo "<th colspan='5'>";
      echo $LANG['plugin_monitoring']['service'][0];
//      echo "&nbsp;<a href='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/service.form.php?services_id=".$a_hosts['id']."'>
//         <img src='".$CFG_GLPI['root_doc']."/pics/menu_add.png' /></a>";
//      
//      echo "&nbsp;<a href='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/servicedef.form.php?add_template=1'>
//         <img src='".$CFG_GLPI['root_doc']."/pics/menu_addtemplate.png' /></a>";
      echo "</th>";
      echo "</tr>";
      
      echo "<table>";

      while ($data=$DB->fetch_array($result)) {
         $pmComponentscatalog->getFromDB($data['plugin_monitoring_componentscalalog_id']);
         
         echo "<table class='tab_cadre_fixe'>";
         
         echo "<tr class='tab_bg_1'>";
         echo "<th colspan='7'>".$pmComponentscatalog->getTypeName()."&nbsp;:&nbsp;".$pmComponentscatalog->getLink()."</th>";
         echo "</tr>";
         
         echo "<tr class='tab_bg_1'>";
         echo "<th>";
         echo $LANG['joblist'][0];
         echo "</th>";
         echo "<th>";
         echo "</th>";
         echo "<th>";
         echo $LANG['state'][0];
         echo "</th>";
         echo "<th>";
         echo $LANG['stats'][7];
         echo "</th>";
//         echo "<th>";
//         echo $LANG['plugin_monitoring']['servicescatalog'][1];
//         echo "</th>";
         echo "<th>";
         echo $LANG['plugin_monitoring']['service'][18];
         echo "</th>";
         echo "<th>";
         echo "</th>";     
         echo "</tr>";
         
         $querys = "SELECT * FROM `glpi_plugin_monitoring_services`
            WHERE `plugin_monitoring_componentscatalogs_hosts_id`='".$data['id']."'";
         $results = $DB->query($querys);
         while ($datas=$DB->fetch_array($results)) {
            $this->getFromDB($datas['id']);            
            
            echo "<tr class='tab_bg_1'>";
            PluginMonitoringDisplay::displayLine($datas, 0);
            echo "</tr>";
            
         }
         echo "</table>";
         
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
      global $LANG;
      
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
      $this->showTabs($options);
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
      echo $LANG['common'][13]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      if ($items_id != '0') {
         echo "<input type='hidden' name='update' value='update'>\n";
      }
      echo "<input type='hidden' name='plugin_monitoring_servicedefs_id_s' value='".$this->fields['plugin_monitoring_servicedefs_id']."'>\n";
      if ($pMonitoringServicedef->fields['is_template'] == '0') {
         $this->fields['plugin_monitoring_servicedefs_id'] = 0;
      }      
      Dropdown::show("PluginMonitoringServicetemplate", array(
            'name' => 'plugin_monitoring_servicetemplates_id',
            'value' => $this->fields['plugin_monitoring_servicetemplates_id'],
            'auto_submit' => true
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
         echo $LANG['state'][6]." <i>".$item->getTypeName()."</i>";
         echo "&nbsp;:</td>";
         echo "<td>";
         echo $item->getLink(1);
         echo "</td>";
      } else {
         echo "<td colspan='2' align='center'>";
         echo "No type associated";
         echo "</td>";
      }      
      // * command
      echo "<td>";
      echo $LANG['plugin_monitoring']['service'][5]."&nbsp;:";
      echo "</td>";
      echo "<td align='center'>";
      if ($this->fields['plugin_monitoring_servicetemplates_id'] > 0) {
         $pMonitoringServicetemplate = new PluginMonitoringServicetemplate();
         $pMonitoringServicetemplate->getFromDB($this->fields['plugin_monitoring_servicetemplates_id']);
         $pMonitoringCommand->getFromDB($pMonitoringServicetemplate->fields['plugin_monitoring_commands_id']);
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
      if ($this->fields['plugin_monitoring_servicetemplates_id'] > 0) {
         $pMonitoringCheck = new PluginMonitoringCheck();
         $pMonitoringCheck->getFromDB($pMonitoringServicetemplate->fields['plugin_monitoring_checks_id']);
         echo $pMonitoringCheck->getLink(1);
      } else {
         Dropdown::show("PluginMonitoringCheck", 
                        array('name'=>'plugin_monitoring_checks_id',
                              'value'=>$pMonitoringServicedef->fields['plugin_monitoring_checks_id']));
      }
      echo "</td>";
      // * active check
      echo "<td>";
      echo $LANG['plugin_monitoring']['service'][6]."&nbsp;:";
      echo "</td>";
      echo "<td align='center'>";
      if ($this->fields['plugin_monitoring_servicetemplates_id'] > 0) {
         echo Dropdown::getYesNo($pMonitoringServicetemplate->fields['active_checks_enabled']);
      } else {
         echo Dropdown::showYesNo("active_checks_enabled", $pMonitoringServicedef->fields['active_checks_enabled']);
      }
      echo "</td>";
      echo "</tr>";
      
      echo "<tr>";
      // * passive check
      echo "<td>";
      echo $LANG['plugin_monitoring']['service'][7]."&nbsp;:";
      echo "</td>";
      echo "<td align='center'>";
      if ($this->fields['plugin_monitoring_servicetemplates_id'] > 0) {
         echo Dropdown::getYesNo($pMonitoringServicetemplate->fields['passive_checks_enabled']);
      } else {
         echo Dropdown::showYesNo("passive_checks_enabled", $pMonitoringServicedef->fields['passive_checks_enabled']);
      }
      echo "</td>";
      // * calendar
      echo "<td>".$LANG['plugin_monitoring']['host'][9]."&nbsp;:</td>";
      echo "<td align='center'>";
      if ($this->fields['plugin_monitoring_servicetemplates_id'] > 0) {
         $calendar = new Calendar();
         $calendar->getFromDB($pMonitoringServicetemplate->fields['calendars_id']);
         echo $calendar->getLink(1);
      } else {
         dropdown::show("Calendar", array('name'=>'calendars_id',
                                 'value'=>$pMonitoringServicedef->fields['calendars_id']));
      }
      echo "</td>";
      echo "</tr>";
      
      if (!($this->fields['plugin_monitoring_servicetemplates_id'] > 0
              AND $pMonitoringServicetemplate->fields['remotesystem'] == '')) {
      
         echo "<tr>";
         echo "<th colspan='4'>".$LANG['plugin_monitoring']['service'][8]."</th>";
         echo "</tr>";

         echo "<tr>";
         // * remotesystem
         echo "<td>";
         echo $LANG['plugin_monitoring']['service'][9]."&nbsp;:";
         echo "</td>";
         echo "<td>";
         $input = array();
         $input[''] = '------';
         $input['byssh'] = 'byssh';
         $input['nrpe'] = 'nrpe';
         $input['nsca'] = 'nsca';
         if ($this->fields['plugin_monitoring_servicetemplates_id'] > 0) {
            echo $input[$pMonitoringServicetemplate->fields['remotesystem']];
         } else {
            Dropdown::showFromArray("remotesystem", 
                                 $input, 
                                 array('value'=>$pMonitoringServicedef->fields['remotesystem']));
         }
         echo "</td>";      
         // * is_argument
         echo "<td>";
         echo $LANG['plugin_monitoring']['service'][10]."&nbsp;:";
         echo "</td>";
         echo "<td>";
         if ($this->fields['plugin_monitoring_servicetemplates_id'] > 0) {
            echo Dropdown::getYesNo($pMonitoringServicetemplate->fields['is_arguments']);
         } else {
            Dropdown::showYesNo("is_arguments", $pMonitoringServicedef->fields['is_arguments']);
         }
         echo "</td>"; 
         echo "</tr>";

         echo "<tr>";
         // alias command
         echo "<td>";
         echo $LANG['plugin_monitoring']['service'][11]."&nbsp;:";
         echo "</td>";
         echo "<td>";
         if ($this->fields['plugin_monitoring_servicetemplates_id'] > 0) {
            echo "<input type='text' name='alias_commandservice' value='".$this->fields['alias_command']."' />";
         } else {
            echo "<input type='text' name='alias_command' value='".$pMonitoringServicedef->fields['alias_command']."' />";
         }
         echo "</td>"; 

         echo "<td>";
         echo $LANG['plugin_monitoring']['service'][12]."&nbsp;:GHJKL";
         echo "</td>";
         echo "<td>";
         if ($this->fields['plugin_monitoring_servicetemplates_id'] > 0) {
            $pMonitoringCommand->getEmpty();
            $pMonitoringCommand->getFromDB($pMonitoringServicetemplate->fields['aliasperfdata_commands_id']);
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
         echo "<th colspan='4'>".$LANG['plugin_monitoring']['service'][13]."&nbsp;</th>";
         echo "</tr>";
          
         foreach ($a_displayarg as $key=>$value) {
         echo "<tr>";
         echo "<th>".$key."</th>";
         echo "<td colspan='2'>";
            if (isset($a_argtext[$key])) {
               echo nl2br($a_argtext[$key])."&nbsp;:";
            } else {
               echo $LANG['plugin_monitoring']['service'][14]."&nbsp;:";
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
      
      $pmService = new PluginMonitoringService();
      $pmComponentscatalog_Host = new PluginMonitoringComponentscatalog_Host();
      
      $pmService->getFromDB($services_id);
      
      $pmComponentscatalog_Host->getFromDB($pmService->fields['plugin_monitoring_componentscatalogs_hosts_id']);
      
      $itemtype = $pmComponentscatalog_Host->fields['itemtype'];
      $item = new $itemtype();
      $item->getFromDB($pmComponentscatalog_Host->fields['items_id']);

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
         if (class_exists("PluginFusinvsnmpCommonDBTM")) {
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
                  if ($PluginFusinvsnmpNetworkEquipment->getValue("plugin_fusinvsnmp_configsecurities_id") == '0') {
                     
                     switch ($a_arg[1]) {

                        case 'version':
                           return '2c';
                           break;

                        case 'authentication':
                           return 'public';
                           break;

                     }
                     
                  }
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
      }
      return $argument;
   }
}

?>