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

define('GLPI_ROOT', '../../..');

include (GLPI_ROOT . "/inc/includes.php");

commonHeader($LANG['plugin_monitoring']['title'][0],$_SERVER["PHP_SELF"], "plugins",
             "monitoring", "servicesuggest");

//echo "<pre>";print_r($_POST); exit;
$pMonitoringService = new PluginMonitoringService();
$pMonitoringServicesuggest = new PluginMonitoringServicesuggest();

if (isset($_POST['addsuggest'])) {
   //echo "<pre>";print_r($_POST);echo "</pre>";exit;
   foreach ($_POST['suggestnum'] as $num) {
      $inputHS = array();
      if ($_POST['plugin_monitoring_servicetemplates_id'][$num] == '0'
              OR $_POST['plugin_monitoring_servicetemplates_id'][$num] == '') {
         // Add service
         
      } else {
         // use template service
         $inputHS['plugin_monitoring_servicetemplates_id'] = $_POST['plugin_monitoring_servicetemplates_id'][$num];
      }
      $inputHS['plugin_monitoring_services_id'] = $_POST['plugin_monitoring_services_id'];
      $inputHS['plugin_monitoring_servicesuggests_id'] = $_POST['plugin_monitoring_servicesuggests_id'][$num];
      $inputHS['name'] = '';
      if ($inputHS['plugin_monitoring_servicesuggests_id'] > 0) {
         $pMonitoringServicesuggest->getFromDB($inputHS['plugin_monitoring_servicesuggests_id']);
         $inputHS['name'] = $pMonitoringServicesuggest->fields['name'];
      }
      
      $inputHS['items_id'] = 0;
      $inputHS['itemtype'] = '';
      if ($_POST['itemtype'][$num] != '') {
         $inputHS['items_id'] = $_POST['items_id'][$num];
         $inputHS['itemtype'] = $_POST['itemtype'][$num];         
      }      
      $pMonitoringService->add($inputHS);
   }
}


glpi_header($_SERVER['HTTP_REFERER']);

commonFooter();

?>