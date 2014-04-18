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
   @since     2011

   ------------------------------------------------------------------------
 */

include ("../../../inc/includes.php");

PluginMonitoringProfile::checkRight("counters","r");

Html::header(__('Monitoring - daily counters', 'monitoring'),'', "plugins", "monitoring", "hostdailycounter");

$pmMessage = new PluginMonitoringMessage();
$pmMessage->getMessages();

$pmDisplay = new PluginMonitoringDisplay();
$pmDisplay->menu();

Search::show('PluginMonitoringHostdailycounter');

if (isset($_POST['paperprediction'])) {
   $pmHostdailycounter = new PluginMonitoringHostdailycounter();
   $pmHostdailycounter->predictionEndPaper();
   Search::show('PluginMonitoringHostdailycounter');
   Html::footer();
   exit;
}

// if (isset($_GET['checkCounters'])) {
   // Hostname, up to date and limit may be specified as a parameter ...
   // Default hostname is all hosts, else hostname is used in SQL LIKE query
   // Default date is now
   // Default interval is 7 days
   PluginMonitoringHostdailycounter::runCheckCounters(
      isset($_GET['date']) ? $_GET['date'] : '', 
      isset($_GET['hostname']) ? $_GET['hostname'] : '%', 
      isset($_GET['interval']) ? $_GET['interval'] : 7);
// }

// echo "<center>";
// echo "<form method='post'>";
// echo "<input type='submit' class='submit' name='paperprediction' value='Voir les bornes qui ne vont plus avoir de papier'>";
// Html::closeForm();
// echo "</center>";

// $pmHostdailycounter = new PluginMonitoringHostdailycounter();
// $pmHostdailycounter->predictionEndPaper();
Html::footer();
?>
