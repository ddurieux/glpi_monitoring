<?php

/*
   ------------------------------------------------------------------------
   Plugin Monitoring for GLPI
   Copyright (C) 2011-2016 by the Plugin Monitoring for GLPI Development Team.

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
   @co-author Frederic Mohier
   @comment
   @copyright Copyright (c) 2011-2016 Plugin Monitoring for GLPI team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      https://forge.indepnet.net/projects/monitoring/
   @since     2011

   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginMonitoringHostnotificationtemplate extends CommonDBTM {


   /**
   * Get name of this type
   *
   *@return text name of this type by language of the user connected
   *
   **/
   static function getTypeName($nb=0) {
      return __('Host notification templates', 'monitoring');
   }



   static function canCreate() {
      return Session::haveRight("plugin_monitoring_componentscatalog", CREATE);
   }



   static function canUpdate() {
      return Session::haveRight("plugin_monitoring_componentscatalog", UPDATE);
   }



   static function canView() {
      return Session::haveRight("plugin_monitoring_componentscatalog", READ);
   }



   function getSearchOptions() {

      $tab = array();

      $tab['common'] = __('Components', 'monitoring');

      $i=1;
      $tab[$i]['table']          = $this->getTable();
      $tab[$i]['field']          = 'name';
      $tab[$i]['linkfield']      = 'name';
      $tab[$i]['name']           = __('Name');
      $tab[$i]['datatype']          = 'itemlink';

      $i++;
      $tab[$i]['table']           = $this->getTable();
      $tab[$i]['field']           = 'host_notification_period';
      $tab[$i]['name']            = __('Notification period', 'monitoring');
      $tab[$i]['datatype']        = 'specific';

      $i++;
      $tab[$i]['table']           = $this->getTable();
      $tab[$i]['field']           = 'host_notifications_enabled';
      $tab[$i]['name']            = __('Enabled/disabled', 'monitoring');
      $tab[$i]['datatype']        = 'bool';

      $i++;
      $tab[$i]['table']           = $this->getTable();
      $tab[$i]['field']           = 'host_notification_options_n';
      $tab[$i]['name']            = __('No notifications', 'monitoring');
      $tab[$i]['datatype']        = 'bool';

      $i++;
      $tab[$i]['table']           = $this->getTable();
      $tab[$i]['field']           = 'host_notification_options_d';
      $tab[$i]['name']            = __('Host down', 'monitoring');
      $tab[$i]['datatype']        = 'bool';

      $i++;
      $tab[$i]['table']           = $this->getTable();
      $tab[$i]['field']           = 'host_notification_options_u';
      $tab[$i]['name']            = __('Host unreachable', 'monitoring');
      $tab[$i]['datatype']        = 'bool';

      $i++;
      $tab[$i]['table']           = $this->getTable();
      $tab[$i]['field']           = 'host_notification_options_r';
      $tab[$i]['name']            = __('Host recovery', 'monitoring');
      $tab[$i]['datatype']        = 'bool';

      $i++;
      $tab[$i]['table']           = $this->getTable();
      $tab[$i]['field']           = 'host_notification_options_d';
      $tab[$i]['name']            = __('Host downtime', 'monitoring');
      $tab[$i]['datatype']        = 'bool';

      $i++;
      $tab[$i]['table']           = $this->getTable();
      $tab[$i]['field']           = 'host_notification_options_f';
      $tab[$i]['name']            = __('Host flapping', 'monitoring');
      $tab[$i]['datatype']        = 'bool';

      return $tab;
   }


   static function getSpecificValueToDisplay($field, $values, array $options=array()) {

      if (!is_array($values)) {
         $values = array($field => $values);
      }
      switch ($field) {
         case 'host_notification_period':
            $calendar = new Calendar();
            $calendar->getFromDB($values[$field]);
            return $calendar->getName(1);
            break;

      }
      return parent::getSpecificValueToDisplay($field, $values, $options);
   }


   /**
   * Display form for configuration
   *
   * @param $items_id integer ID
   * @param $options array
   *
   *@return bool true if form is ok
   *
   **/
   function showForm($items_id, $options=array()) {
      global $DB,$CFG_GLPI;

      if ($items_id == '') {
         if (isset($_POST['id'])) {
            $a_list = $this->find("`users_id`='".$_POST['id']."'", '', 1);
            if (count($a_list)) {
               $array = current($a_list);
               $items_id = $array['id'];
            }
         }
      }

      if ($items_id!='') {
         $this->getFromDB($items_id);
      } else {
         $this->getEmpty();
      }

      $this->showTabs($options);
      $this->showFormHeader($options);

      $this->getFromDB($items_id);

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Name')."&nbsp;:</td>";
      echo "<td align='center'>";

      $objectName = autoName($this->fields["name"], "name", false,
                             $this->getType());
      Html::autocompletionTextField($this, 'name', array('value' => $objectName));
      echo "</td>";
      echo "<td></td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<th colspan='2'>".__('Hosts', 'monitoring')."</th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Notifications', 'monitoring')."&nbsp;:</td>";
      echo "<td align='center'>";
      Dropdown::showYesNo('host_notifications_enabled', $this->fields['host_notifications_enabled']);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Period', 'monitoring')."&nbsp;:</td>";
      echo "<td align='center'>";
      dropdown::show("Calendar", array('name'=>'host_notification_period',
                                 'value'=>$this->fields['host_notification_period']));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Notify on DOWN host states', 'monitoring')."&nbsp;:</td>";
      echo "<td align='center'>";
      Dropdown::showYesNo('host_notification_options_d', $this->fields['host_notification_options_d']);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Notify on UNREACHABLE host states', 'monitoring')."&nbsp;:</td>";
      echo "<td align='center'>";
      Dropdown::showYesNo('host_notification_options_u', $this->fields['host_notification_options_u']);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Notify on host recoveries (UP states)', 'monitoring')."&nbsp;:</td>";
      echo "<td align='center'>";
      Dropdown::showYesNo('host_notification_options_r', $this->fields['host_notification_options_r']);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Notify when the host starts and stops flapping', 'monitoring')."&nbsp;:</td>";
      echo "<td align='center'>";
      Dropdown::showYesNo('host_notification_options_f', $this->fields['host_notification_options_f']);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Notify when host scheduled downtime starts and ends', 'monitoring')."&nbsp;:</td>";
      echo "<td align='center'>";
      Dropdown::showYesNo('host_notification_options_s', $this->fields['host_notification_options_s']);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('The contact will not receive any type of host notifications', 'monitoring')."&nbsp;:</td>";
      echo "<td align='center'>";
      Dropdown::showYesNo('host_notification_options_n', $this->fields['host_notification_options_n']);
      echo "</td>";
      echo "</tr>";

      $this->showFormButtons($options);

      return true;
   }


}

?>