<?php
/*
 * @version $Id: dropdownValue.php 15573 2011-09-01 10:10:06Z moyo $
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2011 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org
 -------------------------------------------------------------------------

 LICENSE

 This file is part of GLPI.

 GLPI is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 GLPI is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with GLPI; if not, write to the Free Software Foundation, Inc.,
 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 --------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: Frédéric MOHIER
// Purpose of file:
// ----------------------------------------------------------------------

$USEDBREPLICATE = 1;

// Direct access to file
if (strpos($_SERVER['PHP_SELF'],"updatePerfdata.php")) {
   include ("../../../inc/includes.php");
   header("Content-Type: text/html; charset=UTF-8");
   Html::header_nocache();
}

if (!defined('GLPI_ROOT')) {
   die("Can not acces directly to this file");
}

Session::checkLoginUser();

/* debug ...
echo "<div>";
print_r($_POST);
echo "</div>";
*/

// Get component graph configuration ...
if(!isset($_SESSION['glpi_plugin_monitoring']['perfname'][$_POST['components_id']])) {
   PluginMonitoringServicegraph::loadPreferences($_POST['components_id']);
}
/* debug ...
echo "<div>";
print_r($_SESSION['glpi_plugin_monitoring']['perfname'][$_POST['components_id']]);
echo "</div>";
*/


$pmServiceevent = new PluginMonitoringServiceevent();

echo "<table class='tab_cadrehov' style='width: 360px;'>";

$a_ret = $pmServiceevent->getSpecificData($_POST['rrdtool_template'], $_POST['items_id'], 'first');
echo "<tr class='tab_bg_1'><th colspan='2'>".__('Counters : first registered value', 'monitoring')."</th></tr>";
foreach ($a_ret as $name=>$data) {
   if (! isset($_SESSION['glpi_plugin_monitoring']['perfname'][$_POST['components_id']][$name])) continue;

   echo "<tr class='tab_bg_3'><td class='left'>$name</td><td class='center'>$data</td></tr>";
}

$a_ret = $pmServiceevent->getSpecificData($_POST['rrdtool_template'], $_POST['items_id'], 'last');
echo "<tr class='tab_bg_1'><th colspan='2'>".__('Counters : current value', 'monitoring')."</th></tr>";
foreach ($a_ret as $name=>$data) {
   if (! isset($_SESSION['glpi_plugin_monitoring']['perfname'][$_POST['components_id']][$name])) continue;
   
   echo "<tr class='tab_bg_3'><td class='left'>$name</td><td class='center'>$data</td></tr>";
}

echo "</table>";

?>