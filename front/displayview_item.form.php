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

include ("../../../inc/includes.php");

PluginMonitoringProfile::checkRight("config_views","r");

Html::header(__('Monitoring', 'monitoring'),$_SERVER["PHP_SELF"], "plugins", 
             "monitoring", "displayview_item");

$pmDisplayview_item = new PluginMonitoringDisplayview_item();
   
if (isset($_POST['plugin_monitoring_services_id'])
        AND $_POST['plugin_monitoring_services_id'] > 0) {
   $_POST['items_id'] = $_POST['plugin_monitoring_services_id'];
   $_POST['itemtype'] = "PluginMonitoringService";
   
}

if (isset ($_POST["add"])) {
   if ($_POST['itemtype'] == 'host'
           || $_POST['itemtype'] == 'service') {
      
      $input = $_POST;
      $input['itemtype'] = $_POST['type'];
      $input['type'] = $_POST['itemtype'];
      $input['condition'] = exportArrayToDB(array(
          'name'     => '',
          'itemtype' => $input['itemtype'],
          'field'    => array(1),
          'searchtype' => array('contains')
      ));
      $pmDisplayview_rule = new PluginMonitoringDisplayview_rule();
      $pmDisplayview_rule->add($input);
   } else {
      $pmDisplayview_item->add($_POST);
   }
   Html::back();
} else if (isset ($_POST["delete"])) {
   $pmDisplayview_item->delete($_POST);
   Html::back();
}

Html::footer();

?>