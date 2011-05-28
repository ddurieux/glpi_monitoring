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

   if (isset($_POST['itemtype'])) {
      $array = array();
      $array['itemtype'] = $_POST['itemtype'];
      $array['items_id'] = $_POST['parents'];
      $_POST['parents'] = exportArrayToDB($array);
   }
   if ($_POST['parenttype'] == '0' OR $_POST['parenttype'] == '2') {
      $_POST['parents'] = "";
   }
   unset($_POST['itemtype']);
   $pluginMonitoringHost->update($_POST);
   glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset ($_POST["delete"])) {

   
}



if (isset($_GET["id"])) {
   $agent->showForm($_GET["id"]);
} else {
   $agent->showForm("");
}

commonFooter();

?>