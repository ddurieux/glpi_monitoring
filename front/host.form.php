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
             "monitoring", "host");

$pluginMonitoringHost = new PluginMonitoringHost();
if (isset($_POST["add"])) {
   if (($_POST['items_id'] != "0") AND ($_POST['items_id'] != "")) {
      $pluginMonitoringHost->add($_POST);
   }
   glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset ($_POST["update"])) {

   if ($_POST['parenttype'] == '0' OR $_POST['parenttype'] == '2') {
      $_POST['parents'] = "";
   }
   $pluginMonitoringHost->update($_POST);
   glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset ($_POST["delete"])) {
   $pluginMonitoringHost->delete($_POST, 1);
   glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset($_POST['parent_add'])) {
   // Add host in dependencies/parent of host

   $pluginMonitoringHost->getFromDB($_POST['id']);

   $array = importArrayFromDB($pluginMonitoringHost->fields['parents']);
   if (!array_search($_POST['itemtype']."-".$_POST['parent_to_add'], $array)) {
      $array[] = $_POST['itemtype']."-".$_POST['parent_to_add'];
   }

   $input = array();
   $input['id'] = $pluginMonitoringHost->fields['id'];
   $input['parents'] = exportArrayToDB($array);
   $pluginMonitoringHost->update($input);
   glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset($_POST['parent_delete'])) {
   // Delete host in dependencies/parent of host

   $pluginMonitoringHost->getFromDB($_POST['id']);

   $array = importArrayFromDB($pluginMonitoringHost->fields['parents']);
   $key = array_search($_POST['parent_to_delete'][0], $array);
   unset($array[$key]);

   $input = array();
   $input['id'] = $pluginMonitoringHost->fields['id'];
   $input['parents'] = exportArrayToDB($array);
   $pluginMonitoringHost->update($input);
   glpi_header($_SERVER['HTTP_REFERER']);
}



if (isset($_GET["id"])) {
   $pluginMonitoringHost->showForm($_GET["id"]);
} else {
   $pluginMonitoringHost->showForm("");
}

commonFooter();

?>