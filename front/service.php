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

Session::checkCentralAccess();

Html::header(__('Monitoring', 'monitoring'), $_SERVER["PHP_SELF"], "plugins",
             "monitoring", "display");


$pmDisplay = new PluginMonitoringDisplay();
$pmMessage = new PluginMonitoringMessage();

$pmMessage->getMessages();

$pmDisplay->menu();

$pmDisplay->refreshPage();

$pmDisplay->showCounters("Ressources", 1, 0);
// Manage search
if (isset($_SESSION['plugin_monitoring']['service'])) {
   $_GET = $_SESSION['plugin_monitoring']['service'];
}
if (isset($_GET['reset'])) {
   unset($_SESSION['glpisearch']['PluginMonitoringService']);
}
if (isset($_GET['glpi_tab'])) {
   unset($_GET['glpi_tab']);
}
Search::manageGetValues("PluginMonitoringService");
if (isset($_GET['hidesearch'])) {
   echo "<table class='tab_cadre_fixe'>";
   echo "<tr class='tab_bg_1'>";
   echo "<th>";
   echo "<a onClick='Ext.get(\"searchform\").toggle();'>
      <img src='".$CFG_GLPI["root_doc"]."/pics/deplier_down.png' />&nbsp;
         ".__('Display search form', 'monitoring')."
      &nbsp;<img src='".$CFG_GLPI["root_doc"]."/pics/deplier_down.png' /></a>";
   echo "</th>";
   echo "</tr>";
   echo "</table>";
   echo "<div style='display: none;' id='searchform'>";
}
Search::showGenericSearch("PluginMonitoringService", $_GET);
if (isset($_GET['hidesearch'])) {
   echo "</div>";
}

$pmDisplay->showBoard(950);
if (isset($_SESSION['glpisearch']['PluginMonitoringService']['reset'])) {
   unset($_SESSION['glpisearch']['PluginMonitoringService']['reset']);
}

Html::footer();
?>
