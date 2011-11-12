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

class PluginMonitoringContacttemplate extends CommonDBTM {
   

   /**
   * Get name of this type
   *
   *@return text name of this type by language of the user connected
   *
   **/
   static function getTypeName() {
      global $LANG;

      return $LANG['plugin_monitoring']['contacttemplate'][0];
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


   
   /**
   * Display form for agent configuration
   *
   * @param $items_id integer ID 
   * @param $options array
   *
   *@return bool true if form is ok
   *
   **/
   function showForm($items_id, $options=array()) {
      global $DB,$CFG_GLPI,$LANG;

      if ($items_id == '') {
         $a_list = $this->find("`users_id`='".$_POST['id']."'", '', 1);
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

      $this->getFromDB($items_id);

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['common'][16]."&nbsp;:</td>";
      echo "<td align='center'>";
      
      $objectName = autoName($this->fields["name"], "name", false,
                             $this->getType());
      autocompletionTextField($this, 'name', array('value' => $objectName));
      echo "</td>";
      echo "<td>".$LANG['plugin_monitoring']['contacttemplate'][1]."&nbsp;:</td>";
      echo "<td align='center'>";
      echo Dropdown::showYesNo("is_default", $this->fields['is_default']);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<th colspan='2'>".$LANG['plugin_monitoring']['contact'][3]."</th>";
      echo "<th colspan='2'>".$LANG['plugin_monitoring']['contact'][4]."</th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_monitoring']['contact'][5]."&nbsp;:</td>";
      echo "<td align='center'>";
      Dropdown::showYesNo('host_notifications_enabled', $this->fields['host_notifications_enabled']);
      echo "</td>";
      echo "<td>".$LANG['plugin_monitoring']['contact'][5]."&nbsp;:</td>";
      echo "<td align='center'>";
      Dropdown::showYesNo('service_notifications_enabled', $this->fields['service_notifications_enabled']);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_monitoring']['contact'][19]."&nbsp;:</td>";
      echo "<td align='center'>";
      dropdown::show("PluginMonitoringNotificationcommand", array('name'=>'host_notification_commands',
                                 'value'=>$this->fields['host_notification_commands']));
      echo "</td>";
      echo "<td>".$LANG['plugin_monitoring']['contact'][19]."&nbsp;:</td>";
      echo "<td align='center'>";
      dropdown::show("PluginMonitoringNotificationcommand", array('name'=>'service_notification_commands',
                                 'value'=>$this->fields['service_notification_commands']));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_monitoring']['contact'][6]."&nbsp;:</td>";
      echo "<td align='center'>";
      dropdown::show("Calendar", array('name'=>'host_notification_period',
                                 'value'=>$this->fields['host_notification_period']));
      echo "</td>";
      echo "<td>".$LANG['plugin_monitoring']['contact'][6]."&nbsp;:</td>";
      echo "<td align='center'>";
      dropdown::show("Calendar", array('name'=>'service_notification_period',
                                 'value'=>$this->fields['service_notification_period']));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_monitoring']['contact'][7]."&nbsp;:</td>";
      echo "<td align='center'>";
      Dropdown::showYesNo('host_notification_options_d', $this->fields['host_notification_options_d']);
      echo "</td>";
      echo "<td>".$LANG['plugin_monitoring']['contact'][8]."&nbsp;:</td>";
      echo "<td align='center'>";
      Dropdown::showYesNo('service_notification_options_w', $this->fields['service_notification_options_w']);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_monitoring']['contact'][9]."&nbsp;:</td>";
      echo "<td align='center'>";
      Dropdown::showYesNo('host_notification_options_u', $this->fields['host_notification_options_u']);
      echo "</td>";
      echo "<td>".$LANG['plugin_monitoring']['contact'][10]."&nbsp;:</td>";
      echo "<td align='center'>";
      Dropdown::showYesNo('service_notification_options_u', $this->fields['service_notification_options_u']);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_monitoring']['contact'][11]."&nbsp;:</td>";
      echo "<td align='center'>";
      Dropdown::showYesNo('host_notification_options_r', $this->fields['host_notification_options_r']);
      echo "</td>";
      echo "<td>".$LANG['plugin_monitoring']['contact'][12]."&nbsp;:</td>";
      echo "<td align='center'>";
      Dropdown::showYesNo('service_notification_options_c', $this->fields['service_notification_options_c']);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_monitoring']['contact'][13]."&nbsp;:</td>";
      echo "<td align='center'>";
      Dropdown::showYesNo('host_notification_options_f', $this->fields['host_notification_options_f']);
      echo "</td>";
      echo "<td>".$LANG['plugin_monitoring']['contact'][14]."&nbsp;:</td>";
      echo "<td align='center'>";
      Dropdown::showYesNo('service_notification_options_r', $this->fields['service_notification_options_r']);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_monitoring']['contact'][15]."&nbsp;:</td>";
      echo "<td align='center'>";
      Dropdown::showYesNo('host_notification_options_s', $this->fields['host_notification_options_s']);
      echo "</td>";
      echo "<td>".$LANG['plugin_monitoring']['contact'][16]."&nbsp;:</td>";
      echo "<td align='center'>";
      Dropdown::showYesNo('service_notification_options_f', $this->fields['service_notification_options_f']);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_monitoring']['contact'][17]."&nbsp;:</td>";
      echo "<td align='center'>";
      Dropdown::showYesNo('host_notification_options_n', $this->fields['host_notification_options_n']);
      echo "</td>";
      echo "<td>".$LANG['plugin_monitoring']['contact'][18]."&nbsp;:</td>";
      echo "<td align='center'>";
      Dropdown::showYesNo('service_notification_options_n', $this->fields['service_notification_options_n']);
      echo "</td>";
      echo "</tr>";

      $this->showFormButtons($options);

      return true;
   }


}

?>