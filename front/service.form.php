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
             "monitoring", "host_service");


$pMonitoringService = new PluginMonitoringService();

//echo "<pre>";print_r($_POST);exit;

if (isset($_POST['add'])) {
   
   $pMonitoringServicedef = new PluginMonitoringServicedef();
   $_POST['plugin_monitoring_servicedefs_id'] = $pMonitoringServicedef->add($_POST);
   if (isset($_POST['arg'])) {
      $_POST['arguments'] = exportArrayToDB($_POST['arg']);
   }
   if (isset($_POST['alias_commandservice'])) {
      $_POST['alias_command'] = $_POST['alias_commandservice'];
   }
   $pMonitoringService->add($_POST);
   glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset($_POST['update'])) {
   if (is_array($_POST['id'])) {
      foreach ($_POST['id'] as $key=>$id) {
         $input = array();
         $input['id'] = $id;
         $input['plugin_monitoring_servicedefs_id'] = $_POST['plugin_monitoring_servicedefs_id'][$key];
         $a_arguments = array();
         foreach ($_POST as $key=>$value) {
            if (strstr($key, "arg".$id."||")) {
               $a_ex = explode("||", $key);
               $a_arguments[$a_ex[1]] = $value;
            }
         }
         $input['arguments'] = exportArrayToDB($a_arguments);
         $pMonitoringService->update($input);
      }
   } else {
      $pMonitoringServicedef = new PluginMonitoringServicedef();
      if ($_POST['plugin_monitoring_servicedefs_id'] == '0') {
         // Add the service
         $id = $_POST['id'];
         unset($_POST['id']);
         $_POST['plugin_monitoring_servicedefs_id'] = $pMonitoringServicedef->add($_POST);
         $_POST['id'] = $id;
      } else {
         $pMonitoringServicedef->getFromDB($_POST['plugin_monitoring_servicedefs_id']);
         if ($pMonitoringServicedef->fields['is_template'] == '0') {
            $pMonitoringServicedef->update($_POST);
         }
      }
      if (isset($_POST['arg'])) {
         $_POST['arguments'] = exportArrayToDB($_POST['arg']);
      }
      if (isset($_POST['alias_commandservice'])) {
         $_POST['alias_command'] = $_POST['alias_commandservice'];
      }
      $pMonitoringService->update($_POST);
   }
   glpi_header($_SERVER['HTTP_REFERER']);
}

if (isset($_GET["id"])) {
   $pMonitoringService->showForm($_GET["id"]);
} else {
   $pMonitoringService->showForm('', array(), $_GET['services_id']);
}

commonFooter();

?>