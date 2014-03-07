<?php

/*
   ------------------------------------------------------------------------
   Plugin Monitoring for GLPI
   Copyright (C) 2011-2014 by the Plugin Monitoring for GLPI Development Team.

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
   @author    Frédéric Mohier
   @co-author
   @comment
   @copyright Copyright (c) 2011-2014 Plugin Monitoring for GLPI team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      https://forge.indepnet.net/projects/monitoring/
   @since     2013

   ------------------------------------------------------------------------
 */

include ("../../../inc/includes.php");

PluginMonitoringProfile::checkRight("downtime","r");

Html::header(__('Monitoring - downtimes', 'monitoring'),'', "plugins", "monitoring", "downtime");

$pmDowntime = new PluginMonitoringDowntime();

if (isset ($_POST["add"])) {
   $pmDowntime->add($_POST);
   $pmDowntime->redirectToList();
} else if (isset ($_POST["update"])) {
   $pmDowntime->update($_POST);
   $pmDowntime->redirectToList();
} else if (isset ($_POST["delete"])) {
   $pmDowntime->delete($_POST);
   $pmDowntime->redirectToList();
}

// Read or edit downtime ...
if (isset($_GET['id']) || isset($_GET['host_id'])) {
   // If host_id is defined, use it ...
   $pmDowntime->showForm((isset($_GET['id'])) ? $_GET['id'] : -1, (isset($_GET['host_id'])) ? $_GET['host_id'] : -1);
}

Html::footer();

?>