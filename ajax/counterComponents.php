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
   @co-author
   @comment
   @copyright Copyright (c) 2011-2016 Plugin Monitoring for GLPI team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      https://forge.indepnet.net/projects/monitoring/
   @since     2014

   ------------------------------------------------------------------------
 */

// Direct access to file
if (strpos($_SERVER['PHP_SELF'],"counterComponents.php")) {
   include ("../../../inc/includes.php");
   header("Content-Type: text/html; charset=UTF-8");
   Html::header_nocache();
}

if (!defined('GLPI_ROOT')) {
   die("Can not acces directly to this file");
}

Session::checkLoginUser();

if (isset($_POST['id'])) {
   // Get all components for this componentscatalog

   $elements = array(
       0 => Dropdown::EMPTY_VALUE
   );
   $query = "SELECT `glpi_plugin_monitoring_components`.*
      FROM `glpi_plugin_monitoring_componentscatalogs_components`
      LEFT JOIN `glpi_plugin_monitoring_components`
         ON `plugin_monitoring_components_id` =
            `glpi_plugin_monitoring_components`.`id`
      WHERE `plugin_monitoring_componentscalalog_id`='".$_POST['id']."'
      ORDER BY `glpi_plugin_monitoring_components`.`name`";
   $result = $DB->query($query);
   while ($data=$DB->fetchArray($result)) {
      $elements[$data['id']] = $data['name'];
   }

   $rand = Dropdown::showFromArray(
           'PluginMonitoringComponent',
           $elements);
   Ajax::updateItemOnSelectEvent(
           'dropdown_PluginMonitoringComponent'.$rand,
           'add_data', $CFG_GLPI["root_doc"]."/plugins/monitoring/ajax/counterData.php",
           array('id' => '__VALUE__'));

}

?>