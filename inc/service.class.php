<?php

/*
   ------------------------------------------------------------------------
   Plugin Monitoring for GLPI
   Copyright (C) 2011-2013 by the Plugin Monitoring for GLPI Development Team.

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
   along with Monitoring. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   Plugin Monitoring for GLPI
   @author    David Durieux
   @co-author 
   @comment   
   @copyright Copyright (c) 2011-2013 Plugin Monitoring for GLPI team
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


   static function getTypeName($nb=0) {
      return __('Resources', 'monitoring');
   }
   
   
   static function canCreate() {
      return Session::haveRight('computer', 'w');
   }


   
   function getSearchOptions() {
      $tab = array();
      $tab['common'] = _n('Characteristic', 'Characteristics', 2);

      $tab[1]['table']         = $this->getTable();
      $tab[1]['field']         = 'name';
      $tab[1]['name']          = __('Name');
      $tab[1]['datatype']      = 'itemlink';
      $tab[1]['itemlink_type'] = $this->getType();
      $tab[1]['massiveaction'] = false; // implicit key==1
      
      $tab[2]['table']         = $this->getTable();
      $tab[2]['field']         = 'id';
      $tab[2]['name']          = __('ID');
      $tab[2]['massiveaction'] = false;
      
      $tab[3]['table'] = $this->getTable();
      $tab[3]['field'] = 'state';
      $tab[3]['name']  = "Status";
      $tab[3]['datatype'] = 'string';
      //$tab[3]['searchtype'] = 'equals';
      
      $tab[4]['table']         = $this->getTable();
      $tab[4]['field']         = 'last_check';
      $tab[4]['name']          = __('Last check', 'monitoring');
      $tab[4]['datatype']      = 'datetime';

      $tab[5]['table'] = $this->getTable();
      $tab[5]['field'] = 'state_type';
      $tab[5]['name']  = __('State type', 'monitoring');
      $tab[5]['searchtype'] = 'equals';
      
      $tab[6]['table'] = 'glpi_entities';
      $tab[6]['field'] = 'completename';
      $tab[6]['name']  = __('Entity');
      
      $tab[7]['table'] = "glpi_plugin_monitoring_components";
      $tab[7]['field'] = 'name';
      $tab[7]['linkfield'] = 'plugin_monitoring_components_id';
      $tab[7]['name'] = __('Component', 'monitoring');
      $tab[7]['datatype'] = 'itemlink';
      $tab[7]['itemlink_type']  = 'PluginMonitoringComponent';
      
      $tab[8]['table'] = "glpi_plugin_monitoring_componentscatalogs";
      $tab[8]['field'] = 'name';
      $tab[8]['name'] = __('Components catalog', 'monitoring');
      $tab[8]['datatype'] = 'itemlink';
      
      $tab[9]['table']         = $this->getTable();
      $tab[9]['field']         = 'event';
      $tab[9]['name']          = "Event";
      $tab[9]['massiveaction'] = false;

      $tab[10]['table'] = $this->getTable();
      $tab[10]['field'] = 'state';
      $tab[10]['name']  = "Status";
      $tab[10]['datatype'] = 'string';

      $tab[20]['table'] = $this->getTable();
      $tab[20]['field'] = 'Computer';
      $tab[20]['name']  = __('Item')." > ".__('Computer');
      $tab[20]['searchtype'] = 'equals';
      
      $tab[21]['table'] = $this->getTable();
      $tab[21]['field'] = 'Printer';
      $tab[21]['name']  = __('Item')." > ".__('Printer');
      $tab[21]['searchtype'] = 'equals';
      
      $tab[22]['table'] = $this->getTable();
      $tab[22]['field'] = 'NetworkEquipment';
      $tab[22]['name']  = __('Item')." > ".__('Network device');
      $tab[22]['searchtype'] = 'equals';
      
      $tab[23]['table'] = $this->getTable();
      $tab[23]['field'] = 'is_acknowledged';
      $tab[23]['name']  = __('Acknowledge', 'monitoring');
      $tab[23]['datatype'] = 'bool';
     
      return $tab;
   }

   
   
   function manageServices($itemtype, $items_id) {
      
      if ($itemtype == 'Computer') {
         $pmHostaddress = new PluginMonitoringHostaddress();
         $item = new $itemtype();
         if ($item->can($items_id, 'w')) {
            $pmHostaddress->showForm($items_id, $itemtype);
         }
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
      global $CFG_GLPI,$DB;

      $pmComponentscatalog = new PluginMonitoringComponentscatalog();
      
      $query = "SELECT * FROM `glpi_plugin_monitoring_componentscatalogs_hosts`
         WHERE `items_id`='".$items_id."'
            AND `itemtype`='".$itemtype."'";
      $result = $DB->query($query);

//      echo "<form name='form' method='post' 
//         action='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/service.form.php'>";

      echo "<table class='tab_cadre_fixe'>";

      echo "<tr class='tab_bg_1'>";
      echo "<th colspan='5'>";
      echo __('Resources', 'monitoring');
      $item = new $itemtype();
      $item->getFromDB($items_id);
      echo " - ".$item->getTypeName();
      echo " - ".$item->getName();
//      echo "&nbsp;<a href='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/service.form.php?services_id=".$a_hosts['id']."'>
//         <img src='".$CFG_GLPI['root_doc']."/pics/menu_add.png' /></a>";
//      
//      echo "&nbsp;<a href='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/servicedef.form.php?add_template=1'>
//         <img src='".$CFG_GLPI['root_doc']."/pics/menu_addtemplate.png' /></a>";
      echo "</th>";
      echo "</tr>";
      
      echo "<table class='tab_cadre_fixe'>";

      while ($data=$DB->fetch_array($result)) {
         $pmComponentscatalog->getFromDB($data['plugin_monitoring_componentscalalog_id']);
         
         echo "<tr class='tab_bg_1'>";
         echo "<th colspan='14'>".$pmComponentscatalog->getTypeName()."&nbsp;:&nbsp;".$pmComponentscatalog->getLink()."</th>";
         echo "</tr>";
         
         echo "<tr class='tab_bg_1'>";
         echo "<th>";
         echo __('Status');
         echo "</th>";
         echo "<th>";
         echo __('Entity');
         echo "</th>";
         echo "<th>";
         echo __('Components', 'monitoring');
         echo "</th>";
         echo "<th>";
         echo __('Components', 'monitoring');
         echo "</th>";
         echo "<th>";
         echo __('Status');
         echo "</th>";
         echo "<th>";
         echo __('Last check', 'monitoring');
         echo "</th>";
         echo "<th>";
         echo __('Result details');
         echo "</th>";
         echo "<th>";
         echo __('Check period', 'monitoring');
         echo "</th>";
         echo "<th>".__('Current month', 'monitoring')." ".Html::showToolTip(__('Availability', 'monitoring'), array('display'=>false))."</th>";
         echo "<th>".__('Last month', 'monitoring')." ".Html::showToolTip(__('Availability', 'monitoring'), array('display'=>false))."</th>";
         echo "<th>".__('Current year', 'monitoring')." ".Html::showToolTip(__('Availability', 'monitoring'), array('display'=>false))."</th>";
         echo "<th>".__('Detail', 'monitoring')."</th>";
         echo '<th>'.__('Acknowledge', 'monitoring').'</th>';
         echo "<th>";
         echo __('Arguments', 'monitoring');
         echo "</th>"; 
         echo "</tr>";
         
         $querys = "SELECT `glpi_plugin_monitoring_services`.* FROM `glpi_plugin_monitoring_services`
            LEFT JOIN `glpi_plugin_monitoring_components`
               on `plugin_monitoring_components_id` = `glpi_plugin_monitoring_components`.`id`
            WHERE `plugin_monitoring_componentscatalogs_hosts_id`='".$data['id']."'
               ORDER BY `name`";
         $results = $DB->query($querys);
         while ($datas=$DB->fetch_array($results)) {
            $this->getFromDB($datas['id']);            
            
            echo "<tr class='tab_bg_1'>";
            PluginMonitoringDisplay::displayLine($datas, 0);
            echo "</tr>";
            
         }
                  
         echo "<tr style='border:1px solid #ccc;background-color:#ffffff'>";
         echo "<td colspan='14' height='5'></td>";
         echo "</tr>";
      }
      
      echo "</table>";

      Html::closeForm();
   }

   
   
   /**
    * Display graphs of services associated with host
    *
    * @param $itemtype value type of item
    * @param $items_id integer id of the object
    *
    **/
   function showGraphsByHost($itemtype, $items_id) {
      global $CFG_GLPI,$DB;

      PluginMonitoringServicegraph::loadLib();
      $pmComponentscatalog = new PluginMonitoringComponentscatalog();
      $pmComponent = new PluginMonitoringComponent();
      $pmServicegraph = new PluginMonitoringServicegraph();

      $query = "SELECT * FROM `glpi_plugin_monitoring_componentscatalogs_hosts`
         WHERE `items_id`='".$items_id."'
            AND `itemtype`='".$itemtype."'";
      $result = $DB->query($query);

      echo '<div id="custom_date" style="display:none"></div>';
      echo '<div id="custom_time" style="display:none"></div>';
      
      echo "<table class='tab_cadre_fixe'>";
      $td = 0;
      while ($data=$DB->fetch_array($result)) {
         $pmComponentscatalog->getFromDB($data['plugin_monitoring_componentscalalog_id']);
         
         $querys = "SELECT `glpi_plugin_monitoring_services`.* FROM `glpi_plugin_monitoring_services`
            LEFT JOIN `glpi_plugin_monitoring_components`
               on `plugin_monitoring_components_id` = `glpi_plugin_monitoring_components`.`id`
            WHERE `plugin_monitoring_componentscatalogs_hosts_id`='".$data['id']."'
               ORDER BY `name`";
         $results = $DB->query($querys);
         while ($datas=$DB->fetch_array($results)) {
            $pmComponent->getFromDB($datas['plugin_monitoring_components_id']);
            if ($pmComponent->fields['graph_template'] != '') {
               if ($td == 0) {
                  echo "<tr>";
               }
               echo "<td width='425'>";
               echo "<table class='tab_cadre'>";
               echo "<tr class='tab_bg_3'>";
               echo "<th width='475'>";
               echo $pmComponent->fields['name'];
               echo "</th>";
               echo "</tr>";
               echo "<tr class='tab_bg_1'>";
               echo "<td>";
               $pmServicegraph->displayGraph($pmComponent->fields['graph_template'], 
                                             "PluginMonitoringService", 
                                             $datas['id'], 
                                             "0", 
                                             "2h", 
                                             "",
                                             450);
               echo "</td>";
               echo "</tr>";
               echo "</table>";
               $td++;
               echo "</td>";
               if ($td == 2) {
                  echo "</tr>";
                  $td = 0;
               }
            }
         }
      }

      if ($td == 1) {
         echo "<td></td>";
      }
      echo "</tr>";
      echo "</table>";
      
   }
   
   
   
   function showForm($items_id, $options=array(), $services_id='') {
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
      echo __('Name')."&nbsp;:";
      echo "</td>";
      echo "<td>";
      $objectName = autoName($this->fields["name"], "name", ($template === "newcomp"),
                             $this->getType());
      Html::autocompletionTextField($this, 'name', array('value' => $objectName));      
      echo "</td>";
      echo "<td>";
      echo __('Template')."&nbsp;:";
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
         echo __('Item Type')." <i>".$item->getTypeName()."</i>";
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
      echo __('Command', 'monitoring')."&nbsp;:";
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
      echo "<td>".__('Check definition', 'monitoring')."&nbsp;:</td>";
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
      echo __('Active check', 'monitoring')."&nbsp;:";
      echo "</td>";
      echo "<td align='center'>";
      if ($this->fields['plugin_monitoring_servicetemplates_id'] > 0) {
         echo Dropdown::getYesNo($pMonitoringServicetemplate->fields['active_checks_enabled']);
      } else {
         Dropdown::showYesNo("active_checks_enabled", $pMonitoringServicedef->fields['active_checks_enabled']);
      }
      echo "</td>";
      echo "</tr>";
      
      echo "<tr>";
      // * passive check
      echo "<td>";
      echo __('Passive check', 'monitoring')."&nbsp;:";
      echo "</td>";
      echo "<td align='center'>";
      if ($this->fields['plugin_monitoring_servicetemplates_id'] > 0) {
         echo Dropdown::getYesNo($pMonitoringServicetemplate->fields['passive_checks_enabled']);
      } else {
         Dropdown::showYesNo("passive_checks_enabled", $pMonitoringServicedef->fields['passive_checks_enabled']);
      }
      echo "</td>";
      // * calendar
      echo "<td>".__('Check period', 'monitoring')."&nbsp;:</td>";
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
         echo "<th colspan='4'>".__('Remote check', 'monitoring')."</th>";
         echo "</tr>";

         echo "<tr>";
         // * remotesystem
         echo "<td>";
         echo __('Utility used for remote check', 'monitoring')."&nbsp;:";
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
         echo __('Use arguments (NRPE only)', 'monitoring')."&nbsp;:";
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
         echo __('Alias command if required (NRPE only)', 'monitoring')."&nbsp;:";
         echo "</td>";
         echo "<td>";
         if ($this->fields['plugin_monitoring_servicetemplates_id'] > 0) {
            echo "<input type='text' name='alias_commandservice' value='".$this->fields['alias_command']."' />";
         } else {
            echo "<input type='text' name='alias_command' value='".$pMonitoringServicedef->fields['alias_command']."' />";
         }
         echo "</td>"; 

         echo "<td>";
         echo __('Template (for graphs generation)', 'monitoring')."&nbsp;:GHJKL";
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
         echo "<th colspan='4'>".__('Argument ([text:text] is used to get values dynamically)', 'monitoring')."&nbsp;</th>";
         echo "</tr>";
          
         foreach ($a_displayarg as $key=>$value) {
         echo "<tr>";
         echo "<th>".$key."</th>";
         echo "<td colspan='2'>";
            if (isset($a_argtext[$key])) {
               echo nl2br($a_argtext[$key])."&nbsp;:";
            } else {
               echo __('Argument', 'monitoring')."&nbsp;:";
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
         if (class_exists("PluginFusioninventoryNetworkEquipment")) {
            $pfNetworkEquipment = new PluginFusioninventoryNetworkEquipment();
            $a_pfNetworkEquipment = current($pfNetworkEquipment->find("`networkequipments_id`='".$devicedata['id']."'", "", 1));
            
            switch ($a_arg[0]) {

               case 'OID':
                  // Load SNMP model and get oid.portnum
                  $query = "SELECT `glpi_plugin_fusioninventory_mappings`.`name` AS `mapping_name`,
                                   `glpi_plugin_fusioninventory_snmpmodelmibs`.*
                            FROM `glpi_plugin_fusioninventory_snmpmodelmibs`
                                 LEFT JOIN `glpi_plugin_fusioninventory_mappings`
                                           ON `glpi_plugin_fusioninventory_snmpmodelmibs`.`plugin_fusioninventory_mappings_id`=
                                              `glpi_plugin_fusioninventory_mappings`.`id`
                            WHERE `plugin_fusioninventory_snmpmodels_id`='".$a_pfNetworkEquipment['plugin_fusioninventory_snmpmodels_id']."'
                              AND `is_active`='1'
                              AND `oid_port_counter`='0'
                              AND `glpi_plugin_fusioninventory_mappings`.`name`='".$a_arg[1]."'";

                  $result=$DB->query($query);
                  while ($data=$DB->fetch_array($result)) {
                     return Dropdown::getDropdownName('glpi_plugin_fusioninventory_snmpmodelmiboids',$data['plugin_fusioninventory_snmpmodelmiboids_id']).
                          ".".$item->fields['logical_number'];
                  }


                  return '';
                  break;

               case 'SNMP':
                  if ($a_pfNetworkEquipment['plugin_fusioninventory_configsecurities_id'] == '0') {
                     
                     switch ($a_arg[1]) {

                        case 'version':
                           return '2c';
                           break;

                        case 'authentication':
                           return 'public';
                           break;

                     }
                     
                  }
                  $pfConfigSecurity = new PluginFusioninventoryConfigSecurity();
                  $pfConfigSecurity->getFromDB($a_pfNetworkEquipment['plugin_fusioninventory_configsecurities_id']);

                  switch ($a_arg[1]) {

                     case 'version':
                        if ($pfConfigSecurity->fields['snmpversion'] == '2') {
                           $pfConfigSecurity->fields['snmpversion'] = '2c';
                        }
                        return $pfConfigSecurity->fields['snmpversion'];
                        break;

                     case 'authentication':
                        return $pfConfigSecurity->fields['community'];
                        break;

                  }

                  break;

            }
         }
      }
      return $argument;
   }
   
   
   
   function showCustomArguments($services_id) {
      
      $pmComponent = new PluginMonitoringComponent();
      $pmCommand = new PluginMonitoringCommand();
      $pmComponentscatalog_Host = new PluginMonitoringComponentscatalog_Host();
      
      $this->getFromDB($services_id);
      
      $options = array();
      $options['target'] = str_replace("service.form.php", "servicearg.form.php", $this->getFormURL());
      
      $this->showFormHeader($options);
      
      $pmComponentscatalog_Host->getFromDB($this->fields['plugin_monitoring_componentscatalogs_hosts_id']);
      $itemtype = $pmComponentscatalog_Host->fields['itemtype'];
      $item = new $itemtype();
      $item->getFromDB($pmComponentscatalog_Host->fields['items_id']);
      echo "<tr>";
      echo "<td>";
      echo $item->getTypeName()." :";
      echo "</td>";
      echo "<td>";
      echo $item->getLink();
      echo "</td>";
      echo "<td colspan='2'></td>";
      echo "</tr>";
      
      $pmComponent->getFromDB($this->fields['plugin_monitoring_components_id']);
      $pmCommand->getFromDB($pmComponent->fields['plugin_monitoring_commands_id']);
      
      $array = array();
      $a_displayarg = array();
      if (isset($pmCommand->fields['command_line'])) {
         preg_match_all("/\\$(ARG\d+)\\$/", $pmCommand->fields['command_line'], $array);
         $a_arguments = importArrayFromDB($pmComponent->fields['arguments']);
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
         $a_tags = $pmComponent->tagsAvailable();
         array_shift($a_tags);
         $a_argtext = importArrayFromDB($pmCommand->fields['arguments']);
         echo "<tr>";
         echo "<th colspan='2'>".__('Component arguments', 'monitoring')."</th>";
         echo "<th colspan='2'>".__('List of tags available', 'monitoring')."&nbsp;</th>";
         echo "</tr>";
          
         foreach ($a_displayarg as $key=>$value) {
         echo "<tr>";
         echo "<td>";
            if (isset($a_argtext[$key])
                    AND $a_argtext[$key] != '') {
               echo nl2br($a_argtext[$key])."&nbsp;:";
            } else {
               echo __('Argument', 'monitoring')." (".$key.")&nbsp;:";
            }
            echo "</td>";
            echo "<td>";
            echo $value."<br/>";
            echo "</td>";
            if (count($a_tags) > 0) {
               foreach ($a_tags as $key=>$value) {
                  echo "<td class='tab_bg_3'>";
                  echo "<strong>".$key."</strong>&nbsp;:";
                  echo "</td>";
                  echo "<td class='tab_bg_3'>";
                  echo $value;
                  echo "</td>";
                  unset($a_tags[$key]);
                  break;
               }
            } else {
               echo "<td colspan='2'></td>";
            }
            echo "</tr>";
         }
         foreach ($a_tags as $key=>$value) {
            echo "<tr>";
            echo "<td colspan='2'></td>";
            echo "<td class='tab_bg_3'>";
            echo "<strong>".$key."</strong>&nbsp;:";
            echo "</td>";
            echo "<td class='tab_bg_3'>";
            echo $value;
            echo "</td>";
            echo "</tr>";
         }
      }
      
      // customized arguments 
      echo "<tr>";
      echo "<th colspan='4'>".__('Custom arguments for this resource (empty : inherit)', 'monitoring')."</th>";
      echo "</tr>";
      $array = array();
      $a_displayarg = array();
      if (isset($pmCommand->fields['command_line'])) {
         preg_match_all("/\\$(ARG\d+)\\$/", $pmCommand->fields['command_line'], $array);
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
      $a_argtext = importArrayFromDB($pmCommand->fields['arguments']);
      foreach ($a_displayarg as $key=>$value) {
         echo "<tr>";
         echo "<td>";
         if (isset($a_argtext[$key])
                 AND $a_argtext[$key] != '') {
            echo nl2br($a_argtext[$key])."&nbsp;:";
         } else {
            echo __('Argument', 'monitoring')." (".$key.")&nbsp;:";
         }
         echo "</td>";
         echo "<td>";
         echo "<input type='text' name='arg[".$key."]' value='".$value."'/><br/>";
         echo "</td>";
         echo "<td colspan='2'></td>";
         echo "</tr>";
      }
      
      $this->showFormButtons($options);
      
   }
   
   
   
   function post_addItem() {

      $pmLog = new PluginMonitoringLog();
      $pmComponentscatalog_Host = new PluginMonitoringComponentscatalog_Host();
      
      $input = array();
      $input['itemtype'] = "PluginMonitoringService";
      $input['items_id'] = $this->fields['id'];
      $input['action'] = "add";
      $pmComponentscatalog_Host->getFromDB($this->fields['plugin_monitoring_componentscatalogs_hosts_id']);
      $itemtype = $pmComponentscatalog_Host->fields['itemtype'];
      $item = new $itemtype();
      $item->getFromDB($pmComponentscatalog_Host->fields['items_id']);      
      $input['value'] = "New service ".$this->fields['name']." for ".$item->getTypeName()." ".$item->getName();
      $pmLog->add($input);
   }

   

   function post_purgeItem() {

      $pmLog = new PluginMonitoringLog();
      
      $input = array();
      $input['itemtype'] = "PluginMonitoringService";
      $input['items_id'] = $this->fields['id'];
      $input['action'] = "delete";

      $itemtype = $_SESSION['plugin_monitoring_hosts']['itemtype'];
      $item = new $itemtype();
      $item->getFromDB($_SESSION['plugin_monitoring_hosts']['items_id']);

      if (isset($_SESSION['plugin_monitoring_hosts']['id'])) {
         $input['value'] = "Service ".$this->fields['name']." of ".$item->getTypeName()." ".$item->getName();
      } else {
         $input['value'] = "Service ".$this->fields['name']." of port of ";
      }
      $pmLog->add($input);
      
      unset($_SESSION['plugin_monitoring_hosts']);
   }
   

   
   function showWidget($id, $time) {
      global $DB, $CFG_GLPI;
      
      $pmComponent = new PluginMonitoringComponent();
      
      if ($this->getFromDB($id)) {
         $pmComponent->getFromDB($this->fields['plugin_monitoring_components_id']);

         $pmServicegraph = new PluginMonitoringServicegraph();
         ob_start();
         $pmServicegraph->displayGraph($pmComponent->fields['graph_template'], 
                                       "PluginMonitoringService", 
                                       $id, 
                                       "0", 
                                       $time, 
                                       "div", 
                                       "475");
         $chart = ob_get_contents();
         ob_end_clean();
         return $chart;
      }
   }
   
   
   
   /**
    * Form to add acknowledge on a critical service
    */
   function addAcknowledge($id) {
      global $CFG_GLPI;
      
      if ($this->getFromDB($id)) {
         echo "<form name='form' method='post' 
            action='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/acknowledge.form.php'>";
      
         echo "<table class='tab_cadre_fixe'>";
         echo "<tr class='tab_bg_1'>";
         echo "<th colspan='2'>";
         echo __('Add an acknowledge for service', 'monitoring')." : ".$this->fields['name'];
         echo "</td>";
         echo "</tr>";
         
         echo "<tr class='tab_bg_1'>";
         echo "<td>";
         echo __('Comments');
         echo "</td>";
         echo "<td>";
         echo "<textarea cols='80' rows='4' name='acknowledge_comment' ></textarea>";
         echo "</td>";
         echo "</tr>";
         
         echo "<tr class='tab_bg_1'>";
         echo "<td colspan='2' align='center'>";
         echo "<input type='hidden' name='id' value='".$id."' />";
         echo "<input type='hidden' name='is_acknowledged' value='1' />";
         echo "<input type='hidden' name='acknowledge_users_id' value='".$_SESSION['glpiID']."' />";

         echo "<input type='hidden' name='referer' value='".$_SERVER['HTTP_REFERER']."' />";
         
         
         echo "<input type='submit' name='add' value=\"".__('Add')."\" class='submit'>";            
         echo "</td>";
         echo "</tr>";
         echo "</table>";
         
         Html::closeForm();
      }
   }
}

?>