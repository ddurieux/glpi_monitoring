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

if ($_SESSION["glpiactiveprofile"]["interface"] == "central") {
   Session::checkCentralAccess();
   Html::header(__('Monitoring - dashboard', 'monitoring'), $_SERVER["PHP_SELF"], "plugins",
                "PluginMonitoringDashboard", "dashboard");
} else {
   Session::checkHelpdeskAccess();
   Html::helpHeader(__('Monitoring - dashboard', 'monitoring'), $_SERVER['PHP_SELF']);
}

Session::checkRight("plugin_monitoring_dashboard", READ);

$pm = new PluginMonitoringComponentscatalog();
//$pm->create_default_templates();

$pmDisplay = new PluginMonitoringDisplay();
$pmMessage = new PluginMonitoringMessage();

$pmMessage->getMessages();

$pmDisplay->menu();


$abc = new Alignak_Backend_Client($PM_CONFIG['alignak_backend_url']);
PluginMonitoringUser::my_token($abc);

$pmWebui = new PluginMonitoringWebui();
$pmWebui->authentication($abc->token);

echo "<table class='tab_cadre_fixe'>";
echo '<tr>';
echo "<td width='50%' style='vertical-align: top;'>";
$page = $PM_CONFIG['alignak_webui_url']."/external/widget/livestate_hosts_chart?widget_id=livestate_hosts_chart";
$pmWebui->load_page($page);
echo "</td>";
echo "<td width='50%' style='vertical-align: top;'>";
$page = $PM_CONFIG['alignak_webui_url']."/external/widget/livestate_hosts_history_chart?widget_id=livestate_hosts_history_chart";
$pmWebui->load_page($page);
echo "</td>";
echo "</tr>";
echo "</table>";

echo '<tr>';
echo "<td></td>";
echo "<td width='50%' style='vertical-align: top;'>";
$page = $PM_CONFIG['alignak_webui_url']."/external/widget/livestate_services_chart?widget_id=livestate_services_chart";
$pmWebui->load_page($page);
echo "</td>";
echo "<td width='50%' style='vertical-align: top;'>";
$page = $PM_CONFIG['alignak_webui_url']."/external/widget/livestate_services_history_chart?widget_id=livestate_services_history_chart";
$pmWebui->load_page($page);
echo "</td>";
echo "</tr>";
echo "</table>";

echo '<tr>';
echo "<td></td>";
echo "<td width='50%' style='vertical-align: top;'>";
$page = $PM_CONFIG['alignak_webui_url']."/external/widget/livestate_hosts_counters?widget_id=livestate_hosts_counters";
$pmWebui->load_page($page);
echo "</td>";
echo "<td width='50%' style='vertical-align: top;'>";
$page = $PM_CONFIG['alignak_webui_url']."/external/widget/livestate_services_counters?widget_id=livestate_services_counters";
$pmWebui->load_page($page);
echo "</td>";
echo "</tr>";
echo "</table>";

echo '<tr>';
echo "<td></td>";
echo "<td width='50%' style='vertical-align: top;'>";
$page = $PM_CONFIG['alignak_webui_url']."/external/widget/livestate_hosts_sla?widget_id=livestate_hosts_sla";
$pmWebui->load_page($page);
echo "</td>";
echo "<td width='50%' style='vertical-align: top;'>";
$page = $PM_CONFIG['alignak_webui_url']."/external/widget/livestate_services_sla?widget_id=livestate_services_sla";
$pmWebui->load_page($page);
echo "</td>";
echo "</tr>";
echo "</table>";

echo "<table class='tab_cadre_fixe'>";
echo '<tr>';
echo "<td width='50%' style='vertical-align: top;'>";
$page = $PM_CONFIG['alignak_webui_url']."/external/widget/livestate_table?widget_id=livestate_table";
$pmWebui->load_page($page);
echo "</td>";
echo "<td width='50%' style='vertical-align: top;'>";
echo "</td>";
echo "</tr>";


if ($_SESSION["glpiactiveprofile"]["interface"] == "central") {
   Html::footer();
} else {
   Html::helpFooter();
}
?>