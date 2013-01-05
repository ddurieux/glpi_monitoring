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

class PluginMonitoringHostconfig extends CommonDBTM {

   
   function initConfig() {
      global $DB;
      
      $query = "SELECT * FROM `".$this->getTable()."`
         WHERE `items_id`='0'
            AND `itemtype`='Entity'
         LIMIT 1";
      
      $result = $DB->query($query);
      if ($DB->numrows($result) == '0') {
         $input = array();
         $input['itemtype'] = 'Entity';
         $input['items_id'] = 0;
         
         $query2 = "SELECT * FROM `glpi_plugin_monitoring_commands`
            WHERE `command_name`='check_host_alive'
            LIMIT 1";
         $result2 = $DB->query($query2);
         if ($DB->numrows($result2) == '1') {
            $data = $DB->fetch_assoc($result2);
            $input['plugin_monitoring_commands_id'] = $data['id'];
         }
         
         $query2 = "SELECT * FROM `glpi_plugin_monitoring_checks`
            LIMIT 1";
         $result2 = $DB->query($query2);
         if ($DB->numrows($result2) == '1') {
            $data = $DB->fetch_assoc($result2);
            $input['plugin_monitoring_checks_id'] = $data['id'];
         }
         
         $query2 = "SELECT * FROM `glpi_calendars`
            WHERE `entities_id`='0'
            LIMIT 1";
         $result2 = $DB->query($query2);
         if ($DB->numrows($result2) == '1') {
            $data = $DB->fetch_assoc($result2);
            $input['calendars_id'] = $data['id'];
         }
         
         $query2 = "SELECT * FROM `glpi_plugin_monitoring_realms`
            LIMIT 1";
         $result2 = $DB->query($query2);
         if ($DB->numrows($result2) == '1') {
            $data = $DB->fetch_assoc($result2);
            $input['plugin_monitoring_realms_id'] = $data['id'];
         }
         
         $this->add($input);         
      }
   }
   
   

   /**
   * Get name of this type
   *
   *@return text name of this type by language of the user connected
   *
   **/
   static function getTypeName($nb=0) {
      return "Host config";
   }



   static function canCreate() {
      return Session::haveRight('computer', 'w');
   }


   
   static function canView() {
      return Session::haveRight('computer', 'r');
   }


   
   /**
   *
   * @param $items_id integer ID 
   * @param $options array
   *
   *@return bool true if form is ok
   *
   **/
   function showForm($items_id, $itemtype, $options=array()) {
      global $DB,$CFG_GLPI;
      
      $pmCommand = new PluginMonitoringCommand();
      $pmCheck = new PluginMonitoringCheck();
      $calendar = new Calendar();
      $pmRealm  = new PluginMonitoringRealm();
      
      $entities_id = 0;
      if ($itemtype == "Entity") {
         $entities_id = $items_id;
      } else {
         $item = new $itemtype();
         $item->getFromDB($items_id);
         $entities_id = $item->fields['entities_id'];
      }      

      $query = "SELECT * FROM `".$this->getTable()."`
         WHERE `items_id`='".$items_id."'
            AND `itemtype`='".$itemtype."'
         LIMIT 1";
      
      $result = $DB->query($query);
      if ($DB->numrows($result) == '0') {
         $this->getEmpty();
         if ($entities_id != '0'
              OR $itemtype != 'Entity') {
            $this->fields['plugin_monitoring_commands_id'] = -1;
            $this->fields['plugin_monitoring_checks_id'] = -1;
            $this->fields['calendars_id'] = -1;
            $this->fields['plugin_monitoring_realms_id'] = -1;
         }
      } else {
         $data = $DB->fetch_assoc($result);
         $this->getFromDB($data['id']);
      }

      echo "<form name='form' method='post' 
         action='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/hostconfig.form.php'>";
      
      echo "<table class='tab_cadre_fixe'";
      
      echo "<tr class='tab_bg_1'>";
      echo "<th colspan='4'>";
      echo __('Hosts configuration', 'monitoring');
      echo "</th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo __('Command', 'monitoring')."&nbsp;:";
      echo "</td>";
      echo "<td>";
      $input = array();

      if ($entities_id != '0'
              OR $itemtype != 'Entity') {
         $input["-1"] = __('Inheritance of the parent entity');
      }
      $query = "SELECT * FROM `".getTableForItemType("PluginMonitoringCommand")."`
         ORDER BY `name`";
      $result = $DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         $input[$data['id']] = $data['name'];
      }
      Dropdown::showFromArray('plugin_monitoring_commands_id', $input, array(
          'value'=>$this->fields['plugin_monitoring_commands_id']));

      echo "</td>";
      echo "<td>".__('Check definition', 'monitoring')."&nbsp;:</td>";
      echo "<td>";
      $input = array();
      if ($entities_id != '0'
              OR $itemtype != 'Entity') {
         $input["-1"] = __('Inheritance of the parent entity');
      }
      $query = "SELECT * FROM `".getTableForItemType("PluginMonitoringCheck")."`
         ORDER BY `name`";
      $result = $DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         $input[$data['id']] = $data['name'];
      }
      Dropdown::showFromArray('plugin_monitoring_checks_id', $input, array(
          'value'=>$this->fields['plugin_monitoring_checks_id']));
      
      echo "</td>";
      echo "</tr>";
      
      // Inheritance
      if ($this->fields['plugin_monitoring_commands_id'] == '-1'
              OR $this->fields['plugin_monitoring_checks_id'] == '-1') {
         
         echo "<tr class='tab_bg_1'>";
         if ($this->fields['plugin_monitoring_commands_id'] == '-1') {
            echo "<td colspan='2' class='green center'>";
            echo __('Inheritance of the parent entity')."&nbsp;:&nbsp;";
            $pmCommand->getFromDB($this->getValueAncestor("plugin_monitoring_commands_id", $entities_id));
            echo $pmCommand->fields['name'];
            echo "</td>";
         } else {
            echo "<td colspan='2'>";
            echo "</td>";
         }
         if ($this->fields['plugin_monitoring_checks_id'] == '-1') {
            echo "<td colspan='2' class='green center'>";
            echo __('Inheritance of the parent entity')."&nbsp;:&nbsp;";
            $pmCheck->getFromDB($this->getValueAncestor("plugin_monitoring_checks_id", $entities_id));
            echo $pmCheck->fields['name'];
            echo "</td>";
         } else {
            echo "<td colspan='2'>";
            echo "</td>";
         }
         echo "</tr>";
      }
      
      
      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Reaml', 'monitoring')."&nbsp;:</td>";
      echo "<td>";
      $input = array();
      if ($entities_id != '0'
              OR $itemtype != 'Entity') {
         $input["-1"] = __('Inheritance of the parent entity');
      }
      $query = "SELECT * FROM `".getTableForItemType("PluginMonitoringRealm")."`
         ORDER BY `name`";
      $result = $DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         $input[$data['id']] = $data['name'];
      }
      Dropdown::showFromArray('plugin_monitoring_realms_id', $input, array(
          'value'=>$this->fields['plugin_monitoring_realms_id']));
      echo "</td>";

      echo "<td>".__('Check period', 'monitoring')."&nbsp;:</td>";
      echo "<td>";
      if (Session::isMultiEntitiesMode()) {
         $input = array();
         if ($entities_id != '0'
                 OR $itemtype != 'Entity') {
            $input["-1"] = __('Inheritance of the parent entity');
         }
         $entities_ancestors = getAncestorsOf("glpi_entities", $entities_id);
         if (!isset($entities_ancestors[$entities_id])) {
            $entities_ancestors[$entities_id] = $entities_id;
         }

         $query = "SELECT * FROM `".getTableForItemType("Calendar")."`
            WHERE `entities_id` IN ('".implode(",", $entities_ancestors)."') AND `is_recursive`='1'
            ORDER BY `name`";
         $result = $DB->query($query);
         while ($data=$DB->fetch_array($result)) {
            $input[$data['id']] = $data['name'];
         }
         Dropdown::showFromArray('calendars_id', $input, array(
             'value'=>$this->fields['calendars_id']));
      } else {
         Dropdown::show("Calendar", array('value' => $this->fields['calendars_id']));
      }

      echo "</td>";
      echo "</tr>";
      
      // Inheritance
      if ($this->fields['calendars_id'] == '-1'
              OR $this->fields['plugin_monitoring_realms_id'] == '-1') {

         echo "<tr class='tab_bg_1'>";
         if ($this->fields['plugin_monitoring_realms_id'] == '-1') {
            echo "<td colspan='2' class='green center'>";
            echo __('Inheritance of the parent entity')."&nbsp;:&nbsp;";
            $pmRealm->getFromDB($this->getValueAncestor("plugin_monitoring_realms_id", $entities_id));
            echo $pmRealm->fields['name'];
            echo "</td>";
         } else {
            echo "<td colspan='2'>";
            echo "</td>";
         }
         if ($this->fields['calendars_id'] == '-1') {     
            echo "<td colspan='2' class='green center'>";
            echo __('Inheritance of the parent entity')."&nbsp;:&nbsp;";
            $calendar->getFromDB($this->getValueAncestor("calendars_id", $entities_id));
            echo $calendar->fields['name'];
            echo "</td>";
         } else {
            echo "<td colspan='2'>";
            echo "</td>";
         }
         echo "</tr>";
      }
      
      if ($itemtype == 'Entity'
              AND $items_id == '0') {
         echo "<tr class='tab_bg_1'>";
         echo "<td>";
         echo __('Shinken Server', 'monitoring')."&nbsp;:";
         echo "</td>";
         echo "<td>";
         Dropdown::show("Computer", array(
             'name'  => 'computers_id',
             'value' => $this->fields['computers_id']
            ));
         echo "</td>";
         echo "<td colspan='2'></td>";
         echo "</tr>";
      }
      
      
      echo "<tr class='tab_bg_1'>";
      echo "<td colspan='4' align='center'>";
      if (isset($this->fields['id']) AND $this->fields['id'] != '') {
         echo "<input type='hidden' name='id' value='".$this->fields['id']."'/>";
      }
      echo "<input type='hidden' name='itemtype' value='".$itemtype."'/>";
      echo "<input type='hidden' name='items_id' value='".$items_id."'/>";
      echo "<input type='submit' name='update' value=\"".__('Save')."\" class='submit'>";
      echo "</td>";
      echo "</tr>";
      
      echo "</table>";
      Html::closeForm();

      return true;
   }
   
   
   
   function getValueAncestor($fieldname, $entities_id, $itemtype='', $items_id='') {
      global $DB;
      
      if ($itemtype != ''
              AND $items_id != '') {
         
         $query = "SELECT * FROM `".$this->getTable()."`
            WHERE `items_id`='".$items_id."'
               AND `itemtype`='".$itemtype."'
            LIMIT 1";
         $result = $DB->query($query);
         if ($DB->numrows($result) == '1') {
            $data = $DB->fetch_assoc($result);
            if ($data[$fieldname] != '-1') {
               return $data[$fieldname];
            } 
         }
      }
      
      
      
      $query = "SELECT * FROM `".$this->getTable()."`
         WHERE `items_id`='".$entities_id."'
            AND `itemtype`='Entity'
         LIMIT 1";
      
      $result = $DB->query($query);
      if ($DB->numrows($result) == '0') {
         $entities_ancestors = getAncestorsOf("glpi_entities", $entities_id);
         
         $nbentities = count($entities_ancestors);
         for ($i=0; $i<$nbentities; $i++) {
            $entity = array_pop($entities_ancestors);
            $query = "SELECT * FROM `".$this->getTable()."`
               WHERE `items_id`='".$entity."'
                  AND `itemtype`='Entity'
               LIMIT 1";
            $result = $DB->query($query);
            if ($DB->numrows($result) != '0') {
               $data = $DB->fetch_assoc($result);
               if ($data[$fieldname] != '-1') {
                  return $data[$fieldname];
               }
            }
         }         
      } else {
         $data = $DB->fetch_assoc($result);
         if ($data[$fieldname] != '-1') {
            return $data[$fieldname];
         } else {
            $entities_ancestors = getAncestorsOf("glpi_entities", $entities_id);

            $nbentities = count($entities_ancestors);
            for ($i=0; $i<$nbentities; $i++) {
               $entity = array_pop($entities_ancestors);
               $query = "SELECT * FROM `".$this->getTable()."`
                  WHERE `items_id`='".$entity."'
                     AND `itemtype`='Entity'
                  LIMIT 1";
               $result = $DB->query($query);
               if ($DB->numrows($result) != '0') {
                  $data = $DB->fetch_assoc($result);
                  if ($data[$fieldname] != '-1') {
                     return $data[$fieldname];
                  }
               }
            } 
         }
      }

   }
   
}

?>