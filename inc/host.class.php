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

class PluginMonitoringHost extends CommonDBTM {
   

   /**
   * Get name of this type
   *
   *@return text name of this type by language of the user connected
   *
   **/
   static function getTypeName() {
      global $LANG;

      return $LANG['plugin_monitoring']['host'][0];
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
    
      $tab['common'] = $LANG['plugin_monitoring']['host'][0];

      return $tab;
   }



   function defineTabs($options=array()){
      global $LANG,$CFG_GLPI;

      $ong = array();

      return $ong;
   }



   /**
   * Display form for agent configuration
   *
   * @param $items_id integer ID 
   * @param $options array
   *
   *@return bool true if form is ok
   *
   **/
   function showForm($items_id, $options=array(), $itemtype='') {
      global $DB,$CFG_GLPI,$LANG;

      if (isset($_GET['withtemplate']) AND ($_GET['withtemplate'] == '1')) {
         $options['withtemplate'] = 1;
      } else {
         $options['withtemplate'] = 0;
      }

      if ($items_id == '' AND $itemtype != '') {
         $a_list = $this->find("`items_id`='".$_POST['id']."' AND `itemtype`='".$itemtype."'", '', 1);
         if (count($a_list)) {
            $array = current($a_list);
            $items_id = $array['id'];
         }
      }

      if ($items_id!='') {
         $this->getFromDB($items_id);
      } else {
         $this->getEmpty();
      }

      $this->showFormHeader($options);

      if (($items_id!='')
         OR ($options['withtemplate'] == '1')) {
         
         $this->getFromDB($items_id);

         echo "<tr class='tab_bg_1'>";
         echo "<td>".$LANG['plugin_monitoring']['host'][1]."&nbsp;:</td>";
         echo "<td align='center'>";
         $array = array();
         $array[0] = $LANG['common'][49];
         $array[1] = $LANG['plugin_monitoring']['host'][3];
         if ($itemtype != "NetworkEquipment") {
            $array[2] = $LANG['plugin_monitoring']['host'][2];
         }
         Dropdown::showFromArray("parenttype", $array, array('value'=>$this->fields['parenttype']));
         echo "</td>";
         if (($itemtype == "NetworkEquipment")
            OR ($options['withtemplate'] == '1')) {
            echo "<td colspan='2'>";
         } else {
            echo "<td>".$LANG['plugin_monitoring']['host'][7]."&nbsp;:</td>";
            echo "<td align='center'>";
            // List all dynamic dependencies
            $networkPort = new NetworkPort();
            $a_list = $networkPort->find("`items_id`='".$_POST['id']."'
               AND `itemtype`='".$itemtype."'");
            foreach ($a_list as $data) {
               $networkports_id = $networkPort->getContact($data['id']);
               if ($networkports_id) {
                  $networkPort->getFromDB($networkports_id);
                  $classname = $networkPort->fields['itemtype'];
                  $class = new $classname;
                  $class->getFromDB($networkPort->fields['items_id']);
                  echo $class->getName(1);
                  echo "<br/>";
               }
            }
         }
         echo "</td>";
         echo "</tr>";

         echo "<tr class='tab_bg_1'>";
         echo "<td>".$LANG['plugin_monitoring']['command'][1]."&nbsp;:</td>";
         echo "<td>";
         Dropdown::show("PluginMonitoringCommand", array('name'=>'plugin_monitoring_commands_id',
                                                   'value'=>$this->fields['plugin_monitoring_commands_id']));
         echo "</td>";
         echo "<td>".$LANG['plugin_monitoring']['check'][0]."&nbsp;:</td>";
         echo "<td align='center'>";
         Dropdown::show("PluginMonitoringCheck", array('name'=>'plugin_monitoring_checks_id',
                                                   'value'=>$this->fields['plugin_monitoring_checks_id']));
         echo "</td>";
         echo "</tr>";

         echo "<tr class='tab_bg_1'>";
         echo "<td>".$LANG['plugin_monitoring']['host'][9]."&nbsp;:</td>";
         echo "<td align='center'>";
         dropdown::show("Calendar", array('name'=>'calendars_id',
                                    'value'=>$this->fields['calendars_id']));
         echo "</td>";
         echo "<td colspan='2'></td>";
         echo "</tr>";

         echo "<tr class='tab_bg_1'>";
         echo "<td>".$LANG['plugin_monitoring']['host'][5]."&nbsp;:</td>";
         echo "<td align='center'>";
         Dropdown::showYesNo("active_checks_enabled", $this->fields['active_checks_enabled']);
         echo "</td>";
         echo "<td>".$LANG['plugin_monitoring']['host'][6]."&nbsp;:</td>";
         echo "<td align='center'>";
         Dropdown::showYesNo("passive_checks_enabled", $this->fields['passive_checks_enabled']);
         echo "</td>";
         echo "</tr>";

         $withtemplate = $options['withtemplate'];
         unset($options['withtemplate']);
         $this->showFormButtons($options);

         if ($withtemplate == '1') {
            if ($this->getField('parenttype') == '1') {
               $pluginMonitoringHost_Host = new PluginMonitoringHost_Host();
               $pluginMonitoringHost_Host->manageDependencies($this->getField('id'));
            }
            if ($this->getField('id')) {
               $pluginMonitoringHost_Contact = new PluginMonitoringHost_Contact();
               $pluginMonitoringHost_Contact->manageContacts($this->getField('id'));
            }
         }
      } else {
         // Add button for host creation
         echo "<tr>";
         echo "<td>".$LANG['common'][7]."&nbsp;:</td>";
         echo "<td>";
         $a_list = $this->find("`is_template`='1'");
         $a_elements = array();
         $a_elements[0] = "------";
         foreach ($a_list as $data) {
            $a_elements[$data['id']] = $data['template_name'];
         }
         $rand = Dropdown::showFromArray("template_id", $a_elements);
         
//         $options_tooltip = array('contentid' => "comment_template".$rand);
//         $options_tooltip['link']       = $this->getLinkURL();
//         $options_tooltip['linktarget'] = '_blank';
//
//         showToolTip("test",$options_tooltip);

         echo "<img alt='' title=\"".$LANG['buttons'][8]."\" src='".$CFG_GLPI["root_doc"].
                     "/pics/add_dropdown.png' style='cursor:pointer; margin-left:2px;'
                     onClick=\"var w = window.open('".$this->getFormURL()."?withtemplate=1&popup=1&amp;rand=".
                     $rand."' ,'glpipopup', 'height=400, ".
                     "width=1000, top=100, left=100, scrollbars=yes' );w.focus();\">";
         echo "</td>";
         echo "<td colspan='2' align='center' width='50%'>";
         echo "<input name='items_id' type='hidden' value='".$_POST['id']."' />";
         echo "<input name='itemtype' type='hidden' value='".$itemtype."' />";
         echo "<input name='add' value='Add this host to monitoring' class='submit' type='submit'></td>";
         echo "</tr>";
         $this->showFormButtons(array('canedit'=>false));
      }

      return true;
   }



   /*
    * Function used to display hosts with itemtype in dropdown
    */
   function showAllHosts($myname, $value_type=0, $value=0, $entity_restrict=-1, $types='',
                                $onlyglobal=false) {
      global $LANG, $DB, $CFG_GLPI;

      $types = array();
      $query = "SELECT * FROM `".$this->getTable()."`
         GROUP BY `itemtype`";
      if ($result = $DB->query($query)) {
         while ($data=$DB->fetch_array($result)) {
            $types[] = $data['itemtype'];
         }
      }

      $rand    = mt_rand();
      $options = array();

      foreach ($types as $type) {
         if (class_exists($type)) {
            $item = new $type();
            $options[$type] = $item->getTypeName($type);
         }
      }
      asort($options);

      if (count($options)) {
         echo "<select name='itemtype' id='itemtype$rand'>";
         echo "<option value='0'>".DROPDOWN_EMPTY_VALUE."</option>\n";

         foreach ($options as $key => $val) {
            echo "<option value='".$key."'>".$val."</option>";
         }
         echo "</select>";

         $params = array('idtable'          => '__VALUE__',
                          'value'           => $value,
                          'myname'          => $myname,
                          'entity_restrict' => $entity_restrict);

         if ($onlyglobal) {
            $params['condition'] = "`is_global` = '1'";
         }
         ajaxUpdateItemOnSelectEvent("itemtype$rand", "show_$myname$rand",
                                     $CFG_GLPI["root_doc"]."/plugins/monitoring/ajax/dropdownAllHosts.php", $params);

         echo "<br><span id='show_$myname$rand'>&nbsp;</span>\n";

         if ($value>0) {
            echo "<script type='text/javascript' >\n";
            echo "window.document.getElementById('itemtype$rand').value='".$value_type."';";
            echo "</script>\n";

            $params["idtable"] = $value_type;
            ajaxUpdateItem("show_$myname$rand", $CFG_GLPI["root_doc"]."/plugins/monitoring/ajax/dropdownAllHosts.php",
                           $params);
         }
      }
      return $rand;
   }


   function showHostChecks() {

      $pluginMonitoringHostevent = new PluginMonitoringHostevent();

      $a_list = $this->find("`event` NOT LIKE '% OK -%' AND `is_template`='0'");

      echo "<table class='tab_cadre_fixe'>";
      
      echo "<tr class='tab_bg_1'>";
      echo "<th>Name</th>";
      echo "<th>type</th>";
      echo "<th>IP</th>";
      echo "<th>Last check</th>";
      echo "<th>State</th>";
      echo "<th>Since</th>";
      echo "</tr>";

      foreach ($a_list as $data) {
         echo "<tr class='tab_bg_1'>";
         $classname = $data['itemtype'];
         $class = new $classname;
         $class->getFromDB($data['items_id']);
         echo "<td>".$class->getLink(1)." </td>";
         echo "<td>".$class->getTypeName()." </td>";
         echo "<td></td>";
         echo "<td>".convDateTime($data['last_check'])."</td>";
         if (strstr($data['event'], " OK -")) {
            echo "<td style='background-color: #00ff00;'>Ok</td>";
            echo "<td></td>";
         } else {
            echo "<td style='background-color: #ff0000;'>".$data['event']."</td>";
            $a_hostevents = $pluginMonitoringHostevent->find("`plugin_monitoring_hosts_id`='".$data['id']."'",
                                                             "`date` DESC", 1);
            $a_hostevent = current($a_hostevents);
            $time = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'))
                    - $pluginMonitoringHostevent->convert_datetime_timestamp($a_hostevent['date']);
            echo "<td>".$time." seconds</td>";
         }


         echo "</tr>";
      }

      echo "</table>";
   }



   function massiveactionAddHost($itemtype, $items_id, $templates_id) {
      $a_list = $this->find("`itemtype`='".$itemtype."' AND `items_id`='".$items_id."'");

      if (count($a_list) == '0') {
         // Add host to monitoring system
         if ($templates_id > 0) {
            $this->getFromDB($templates_id);
            $input = array();
            $input['itemtype'] = $itemtype;
            $input['items_id'] = $items_id;
            $input['parenttype'] = $this->fields['parenttype'];
            $input['plugin_monitoring_commands_id'] = $this->fields['plugin_monitoring_commands_id'];
            $input['plugin_monitoring_checks_id'] = $this->fields['plugin_monitoring_checks_id'];
            $input['active_checks_enabled'] = $this->fields['active_checks_enabled'];
            $input['passive_checks_enabled'] = $this->fields['passive_checks_enabled'];
            $input['calendars_id'] = $this->fields['calendars_id'];

            $hosts_id = $this->add($input);
            // Add parents
            $pluginMonitoringHost_Host = new PluginMonitoringHost_Host();
            $a_list = $pluginMonitoringHost_Host->find("`plugin_monitoring_hosts_id_1`='".$templates_id."'");
            foreach ($a_list as $data) {
               $input = array();
               $input['plugin_monitoring_hosts_id_1'] = $hosts_id;
               $input['plugin_monitoring_hosts_id_2'] = $data['plugin_monitoring_hosts_id_2'];
               $pluginMonitoringHost_Host->add($input);
            }

            // Add contacts
            $pluginMonitoringHost_Contact = new PluginMonitoringHost_Contact();
            $a_list = $pluginMonitoringHost_Contact->find("`plugin_monitoring_hosts_id`='".$templates_id."'");
            foreach ($a_list as $data) {
               $input = array();
               $input['plugin_monitoring_hosts_id'] = $hosts_id;
               $input['plugin_monitoring_contacts_id'] = $data['plugin_monitoring_contacts_id'];
               $pluginMonitoringHost_Contact->add($input);
            }
         }
      }
   }

}

?>