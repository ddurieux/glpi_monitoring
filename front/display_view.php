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
   @since     2011

   ------------------------------------------------------------------------
 */

include ("../../../inc/includes.php");

$title = __('Monitoring', 'monitoring');
if ($_SESSION["glpiactiveprofile"]["interface"] == "central") {
   Session::checkCentralAccess();
   Html::header($title, $_SERVER["PHP_SELF"], "plugins",
                "PluginMonitoringDashboard", "dashboard");
} else {
   Session::checkHelpdeskAccess();
   Html::helpHeader($title, $_SERVER['PHP_SELF']);
}

$pmDisplay = new PluginMonitoringDisplay();
$pmDisplayview = new PluginMonitoringDisplayview();
$pmDisplayview_item = new PluginMonitoringDisplayview_item();
$pmMessage = new PluginMonitoringMessage();

$pmMessage->getMessages();

$pmDisplay->menu();

$pass = 0;
$a_views = $pmDisplayview->getViews();
if (isset($a_views[$_GET['id']])) {
   $pmDisplayview->getFromDB($_GET['id']);
   $pass = $pmDisplayview->haveVisibilityAccess();
}
if ($pass == 0) {
   Session::checkRight("plugin_monitoring_displayview", READ);
}

if (isset($a_views[$_GET['id']])) {
   $pmDisplayview_item->view($_GET['id']);
} else {
   Html::displayRightError();
}
if ($_SESSION["glpiactiveprofile"]["interface"] == "central") {
   Html::footer();
} else {
   Html::helpFooter();
}
?>