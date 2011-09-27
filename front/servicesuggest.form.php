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

define('GLPI_ROOT', '../../..');

include (GLPI_ROOT . "/inc/includes.php");

commonHeader($LANG['plugin_monitoring']['title'][0],$_SERVER["PHP_SELF"], "plugins",
             "monitoring", "servicesuggest");

//echo "<pre>";print_r($_POST); exit;
$pMonitoringService = new PluginMonitoringService();
$pMonitoringHost_Service = new PluginMonitoringHost_Service();
$pMonitoringServicesuggest = new PluginMonitoringServicesuggest();

if (isset($_POST['addsuggest'])) {
   //echo "<pre>";print_r($_POST);echo "</pre>";exit;
   foreach ($_POST['suggestnum'] as $num) {
      $inputHS = array();
      if ($_POST['plugin_monitoring_services_id'][$num] == '0'
              OR $_POST['plugin_monitoring_services_id'][$num] == '') {
         // Add service
         
      } else {
         // use template service
         $inputHS['plugin_monitoring_services_id'] = $_POST['plugin_monitoring_services_id'][$num];
      }
      $inputHS['plugin_monitoring_hosts_id'] = $_POST['plugin_monitoring_hosts_id'];
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
      $pMonitoringHost_Service->add($inputHS);
   }
}


glpi_header($_SERVER['HTTP_REFERER']);

commonFooter();

?>