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

class PluginMonitoringComponent extends CommonDBTM {
   
   /**
   * Get name of this type
   *
   *@return text name of this type by language of the user connected
   *
   **/
   static function getTypeName($nb=0) {
      return __('Components', 'monitoring');
   }
   
   
   /*
    * Add some services Component at install
    * 
    */
   function initComponents() {
            
      
   }



   static function canCreate() {      
      return PluginMonitoringProfile::haveRight("component", 'w');
   }


   
   static function canView() {
      return PluginMonitoringProfile::haveRight("component", 'r');
   }


   
   function defineTabs($options=array()){
      $ong = array();
      $this->addStandardTab("PluginMonitoringComponent", $ong, $options);    
      return $ong;
   }
   
   
   
   /**
    * Display tab
    * 
    * @param CommonGLPI $item
    * @param integer $withtemplate
    * 
    * @return varchar name of the tab(s) to display
    */
   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {

      if ($item->getID() > 0
              AND $item->fields['graph_template'] != '') {
         return array(
               __('Copy'),
               __('Components catalog', 'monitoring'),
               __('Graph configuration', 'monitoring')
             );
      } else if ($item->getID() > 0) {
         return array(
               __('Copy'),
               __('Components catalog', 'monitoring')
             );
      }
      return '';
   }
   
   
 
   /**
    * Display content of tab
    * 
    * @param CommonGLPI $item
    * @param integer $tabnum
    * @param interger $withtemplate
    * 
    * @return boolean true
    */
   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {

      if ($item->getType()=='PluginMonitoringComponent') {
         if ($tabnum == '0') {
            $item->copyItem($item->getID());
         } else if ($tabnum == '1') {
            PluginMonitoringComponentscatalog_Component::listForComponents($item->getID());
         } else if ($tabnum == '2') {
            $item->preferences($item->getID());
         }         
      }
      return true;
   }

   

   function getSearchOptions() {

      $tab = array();
    
      $tab['common'] = __('Components', 'monitoring');

		$tab[1]['table'] = $this->getTable();
		$tab[1]['field'] = 'name';
		$tab[1]['linkfield'] = 'name';
		$tab[1]['name'] = __('Name');
		$tab[1]['datatype'] = 'itemlink';

      $tab[2]['table']         = $this->getTable();
      $tab[2]['field']         = 'id';
      $tab[2]['name']          = __('ID');
      $tab[2]['massiveaction'] = false; // implicit field is id
     
      return $tab;
   }


   
   /**
   * Display form for service configuration
   *
   * @param $items_id integer ID 
   * @param $options array
   *
   *@return bool true if form is ok
   *
   **/
   function showForm($items_id, $options=array(), $copy=array()) {
      global $DB,$CFG_GLPI;

      $pMonitoringCommand = new PluginMonitoringCommand();
      
      if ($items_id == '0') {
         $this->getEmpty();
         $this->fields['active_checks_enabled']  = 1;
         $this->fields['passive_checks_enabled'] = 1;
      } else {
         $this->getFromDB($items_id);
      }
      
      if (count($copy) > 0) {
         foreach ($copy as $key=>$value) {
            $this->fields[$key] = stripslashes($value);
         }
      }
     
      $this->showTabs($options);
      $this->showFormHeader($options);

      if (isset($_SESSION['plugin_monitoring_components'])) {
         $this->fields = $_SESSION['plugin_monitoring_components'];
         if (!isset($this->fields["id"])) {
            $this->fields["id"] = '';
         }
         if (!isset($this->fields["arguments"])) {
            $this->fields["arguments"] = '';
         }
         unset($_SESSION['plugin_monitoring_components']);
      }
      
      echo "<tr>";
      echo "<td>";
      echo __('Name')."<font class='red'>*</font>&nbsp;:";
      echo "</td>";
      echo "<td>";
      echo "<input type='hidden' name='is_template' value='1' />";
      $objectName = autoName($this->fields["name"], "name", 1,
                             $this->getType());
      Html::autocompletionTextField($this, 'name', array('value' => $objectName));      
      echo "</td>";
      // * checks
      echo "<td>".__('Check definition', 'monitoring')."<font class='red'>*</font>&nbsp;:</td>";
      echo "<td>";
      Dropdown::show("PluginMonitoringCheck", 
                        array('name'=>'plugin_monitoring_checks_id',
                              'value'=>$this->fields['plugin_monitoring_checks_id']));
      echo "</td>";
      echo "</tr>";
      
      // * Link
      echo "<tr>";
      echo "<td>";
//      echo "Type of template&nbsp;:";
      echo "</td>";
      echo "<td>";
//      $a_types = array();
//      $a_types[''] = Dropdown::EMPTY_VALUE;
//      $a_types['partition'] = "Partition";
//      $a_types['processor'] = "Processor";
//      Dropdown::showFromArray("link", $a_types, array('value'=>$this->fields['link']));
      echo "</td>";
      // * active check
      echo "<td>";
      echo __('Active checks', 'monitoring')."<font class='red'>*</font>&nbsp;:";
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("active_checks_enabled", $this->fields['active_checks_enabled']);
      echo "</td>";
      echo "</tr>";
      
      // * command
      echo "<tr>";
      echo "<td>";
      echo __('Command', 'monitoring')."<font class='red'>*</font>&nbsp;:";
      echo "</td>";
      echo "<td>";
      $pMonitoringCommand->getFromDB($this->fields['plugin_monitoring_commands_id']);
      Dropdown::show("PluginMonitoringCommand", array(
                             'name' =>'plugin_monitoring_commands_id',
                              'value'=>$this->fields['plugin_monitoring_commands_id']
                              ));
      echo "</td>";
      // * passive check
      echo "<td>";
      echo __('Passive check', 'monitoring')."<font class='red'>*</font>&nbsp;:";
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("passive_checks_enabled", $this->fields['passive_checks_enabled']);
      echo "</td>";
      echo "</tr>";
      
      echo "<tr>";
      echo "<td>";
      echo __('Template (for graphs generation)', 'monitoring')."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Dropdown::showFromArray("graph_template", 
                              PluginMonitoringPerfdata::listPerfdata(), 
                              array('value'=>$this->fields['graph_template']));
      echo "</td>";
      // * calendar
      echo "<td>".__('Check period', 'monitoring')."<font class='red'>*</font>&nbsp;:</td>";
      echo "<td>";
      dropdown::show("Calendar", array('name'=>'calendars_id',
                                 'value'=>$this->fields['calendars_id']));
      echo "</td>";
      echo "</tr>";
      
      echo "<tr>";
      echo "<td>";
      echo __('Event handler', 'monitoring')."&nbsp;:";
      echo "</td>";
      echo "<td>";
      dropdown::show("PluginMonitoringEventhandler", 
                     array('name'  => 'plugin_monitoring_eventhandlers_id',
                           'value' => $this->fields['plugin_monitoring_eventhandlers_id']));
      echo "</td>";
      echo "<td colspan='2'>";
      echo "</td>";
      echo "</tr>";
      
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
      Dropdown::showFromArray("remotesystem", 
                              $input, 
                              array('value'=>$this->fields['remotesystem']));
      echo "</td>";      
      // * is_argument
      echo "<td>";
      echo __('Use arguments (NRPE only)', 'monitoring')."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("is_arguments", $this->fields['is_arguments']);
      echo "</td>"; 
      echo "</tr>";
      
      echo "<tr>";
      // alias command
      echo "<td>";
      echo __('Alias command if required (NRPE only)', 'monitoring')."&nbsp;:";
      echo "</td>";
      echo "<td>";
      echo "<input type='text' name='alias_command' value='".$this->fields['alias_command']."' />";
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
         $a_tags = $this->tagsAvailable();
         $a_argtext = importArrayFromDB($pMonitoringCommand->fields['arguments']);
         echo "<tr>";
         echo "<th colspan='4'>".__('Arguments', 'monitoring')."&nbsp;</th>";
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
            echo "<input type='text' name='arg[".$key."]' value='".$value."'/><br/>";
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
      
      echo "<tr>";
      echo "<th colspan='4'>".__('Weathermap', 'monitoring')."&nbsp;</th>";
      echo "</tr>";
      
      echo "<tr>";
      echo "<td>";
      echo __('Use this component for Weathermap', 'monitoring')."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("is_weathermap", $this->fields['is_weathermap']);
      echo "</td>";
      echo "<td>";
      $tooltip = __('Example', 'monitoring')." :<br/><br/>";
      $tooltip .= "perfdata : <i>inUsage=0.00%;85;98 outUsage=0.00%;85;98 inBandwidth=<strong>789944</strong>.00bps outBandwidth=486006.00bps inAbsolut=0 outAbsolut=12665653</i><br/><br/>";
      $tooltip .= __('Regex bandwidth input', 'monitoring')." : <i><strong>(?:.*)inBandwidth=(\d+)(?:.*)</strong></i><br/><br/>";
      $tooltip .= __('Assign the value from regular expression')." : <strong>789944</strong>";
      echo __('Regex bandwidth input', 'monitoring')."&nbsp;";
      Html::showToolTip($tooltip, array('autoclose'=>false));
      echo "&nbsp;:";
      echo "</td>";
      echo "<td>";
      echo "<input type='text' name='weathermap_regex_in' value='".$this->fields['weathermap_regex_in']."' size='40' />";
      echo "</td>"; 
      echo "</tr>";
      
      echo "<tr>";
      echo "<td colspan='2'>";
      echo "</td>";
      echo "<td>";
      $tooltip = __('Example', 'monitoring')." :<br/><br/>";
      $tooltip .= "perfdata : <i>inUsage=0.00%;85;98 outUsage=0.00%;85;98 inBandwidth=789944.00bps outBandwidth=<strong>486006</strong>.00bps inAbsolut=0 outAbsolut=12665653</i><br/><br/>";
      $tooltip .= __('Regex bandwidth output', 'monitoring')." : <i><strong>(?:.*)outBandwidth=(\d+)(?:.*)</strong></i><br/><br/>";
      $tooltip .= __('Assign the value from regular expression')." : <strong>789944</strong>";
      echo __('Regex bandwidth output', 'monitoring')."&nbsp;";
      Html::showToolTip($tooltip, array('autoclose'=>false));
      echo "&nbsp;:";
      echo "</td>";
      echo "<td>";
      echo "<input type='text' name='weathermap_regex_out' value='".$this->fields['weathermap_regex_out']."' size='40' />";
      echo "</td>"; 
      echo "</tr>";
      
      $this->showFormButtons($options);
      $this->addDivForTabs();
      
      return true;
   }
   
   
   
   function copyItem($items_id) {

      // Add form for copy item

      $this->getFromDB($items_id);
      $this->fields['id'] = 0;
      $this->showFormHeader(array());

      echo "<tr class='tab_bg_1'>";
      echo "<td colspan='4' class='center'>";
      foreach ($this->fields as $key=>$value) {
         if ($key != 'id') {
            echo "<input type='hidden' name='".$key."' value='".$value."'/>";
         }
      }
      echo "<input type='submit' name='copy' value=\"".__('copy', 'monitoring')."\" class='submit'>";
      echo "</td>";
      echo "</tr>";

      echo "</table>";
      Html::closeForm();
   }
   
   
   
   function tagsAvailable() {
      
      $elements = array();
      $elements[__('List of tags available', 'monitoring')] = '';
      $elements["[[HOSTNAME]]"] = __('Hostname of the device', 'monitoring');
      $elements["[[NETWORKPORTNUM]]"] = __('Network port number', 'monitoring');
      $elements["[[NETWORKPORTNAME]]"] = __('Network port name', 'monitoring');
      if (class_exists("PluginFusioninventoryNetworkPort")) {
         $elements["[[NETWORKPORTDESCR]]"] = __('Network port ifDescr of networking devices', 'monitoring');
         $elements["[SNMP:version]"] = __('SNMP version of network equipment or printer', 'monitoring');
         $elements["[SNMP:authentication]"] = __('SNMP community of network equipment or printer', 'monitoring');
      }      
      return $elements;
   }
   
   
   
   function preferences($components_id) {
      echo "<table class='tab_cadre_fixe'>"; 
            
      echo "<tr class='tab_bg_1'>";
      echo "<th>";
      echo __('Settings');      
      echo "</th>";
      echo "</tr>";
      
      echo "<tr class='tab_bg_3'>";
      echo "<td>";
      PluginMonitoringServicegraph::preferences($components_id);
      echo "</td>";
      echo "</tr>";
      echo "</table>";
   }
}

?>