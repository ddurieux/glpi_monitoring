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
   @since     2013
 
   ------------------------------------------------------------------------
 */

include ("../../../inc/includes.php");

PluginMonitoringProfile::checkRight("config","r");

Html::header(__('Monitoring', 'monitoring'),$_SERVER["PHP_SELF"], "plugins", 
             "monitoring", "notificationcommand");

$pmNotificationcommand = new PluginMonitoringNotificationcommand();

if (isset($_POST["copy"])) {
   $pmNotificationcommand->showForm("", array(), $_POST);
   Html::footer();
   exit;
} else if (isset ($_POST["add"])) {
   $pmNotificationcommand->add($_POST);
   Html::back();
} else if (isset ($_POST["update"])) {
   $pmNotificationcommand->update($_POST);
   Html::back();
} else if (isset ($_POST["delete"])) {
   $pmNotificationcommand->delete($_POST);
   $pmNotificationcommand->redirectToList();
}


if (isset($_GET["id"])) {
   $pmNotificationcommand->showForm($_GET["id"]);
} else {
   $pmNotificationcommand->showForm("");
}

Html::footer();

?>