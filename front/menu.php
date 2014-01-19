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
             "monitoring", "menu");

$pmMessage = new PluginMonitoringMessage();
$pmMessage->getMessages();

$toDisplayArea=0;

if (PluginMonitoringProfile::haveRight("dashboard", 'r') && ! PluginMonitoringProfile::haveRight("config", 'r')) {
   Html::redirect($CFG_GLPI['root_doc']."/plugins/monitoring/front/dashboard.php");
}

if (PluginMonitoringProfile::haveRight("dashboard", 'r')
      && (
         PluginMonitoringProfile::haveRight("restartshinken", 'r')
         || PluginMonitoringProfile::haveRight("dashboard_system_status", 'r')
         || PluginMonitoringProfile::haveRight("dashboard_hosts_status", 'r')
         || PluginMonitoringProfile::haveRight("dashboard_services_catalogs", 'r')
         || PluginMonitoringProfile::haveRight("dashboard_components_catalogs", 'r')
         || PluginMonitoringProfile::haveRight("dashboard_all_ressources", 'r')
         || PluginMonitoringProfile::haveRight("dashboard_perfdata", 'r')
         || PluginMonitoringProfile::haveRight("dashboard_views", 'r'))) {
   $toDisplayArea++;

   echo "<table class='tab_cadre' width='950'>";
   echo "<tr class='tab_bg_1'>";
   echo "<th height='80'>";
   echo "<a href='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/dashboard.php'>".__('Dashboard', 'monitoring')."</a>";
   echo "</th>";
   echo "</tr>";
   echo "</table>";

   echo "<br/>";
}

if (PluginMonitoringProfile::haveRight("config_services_catalogs", 'r')
      || PluginMonitoringProfile::haveRight("config_weathermap", 'r')
      || PluginMonitoringProfile::haveRight("config_views", 'r')) {
   echo "<table class='tab_cadre' width='950'>";
   echo "<tr class='tab_bg_1'>";

   if (PluginMonitoringProfile::haveRight("config_views", 'r')) {
      $toDisplayArea++;
      echo "<th align='center' height='50' width='100%'>";
      echo "<a href='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/displayview.php'>".__('Views', 'monitoring')."</a>";
      echo "</th>";
   }
   echo "</tr>";
   echo "</table>";
   echo "<br/>";
}


if (PluginMonitoringProfile::haveRight("config_services_catalogs", 'r')
      || PluginMonitoringProfile::haveRight("config_weathermap", 'r')
      || PluginMonitoringProfile::haveRight("config_views", 'r')) {
   echo "<table class='tab_cadre' width='950'>";
   echo "<tr class='tab_bg_1'>";
   if (PluginMonitoringProfile::haveRight("config_services_catalogs", 'r')) {
      $toDisplayArea++;
      echo "<th align='center' height='50' width='33%'>";
      echo "<a href='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/servicescatalog.php'>".__('Services catalogs', 'monitoring')."</a>";
      echo "</th>";
   }

   if (PluginMonitoringProfile::haveRight("config_weathermap", 'r')) {
      $toDisplayArea++;
      echo "<th align='center' height='50' width='33%'>";
      echo "<a href='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/weathermap.php'>".__('Weathermaps', 'monitoring')."</a>";
      echo "</th>";
   }

   if (PluginMonitoringProfile::haveRight("config_views", 'r')) {
      $toDisplayArea++;
      echo "<th align='center' height='50' width='34%'>";
      echo "<a href='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/displayview.php'>".__('Views', 'monitoring')."</a>";
      echo "</th>";
   }
   echo "</tr>";
   echo "</table>";
   echo "<br/>";
}

if (PluginMonitoringProfile::haveRight("config_components_catalogs", 'r')) {
   $toDisplayArea++;
   echo "<table class='tab_cadre' width='950'>";
   echo "<th height='40'>";
   echo "<a href='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/componentscatalog.php'>".__('Components catalog', 'monitoring')."</a>";
   echo "</th>";
   echo "</tr>";
   echo "</table>";

   echo "<br/>";
}

if (PluginMonitoringProfile::haveRight("config", 'r')) {

   $toDisplayArea++;
   echo "<table class='tab_cadre' width='950'>";
   echo "<tr class='tab_bg_1'>";
   echo "<th colspan='5' height='30' width='55%'>";
   echo "<a href='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/component.php'>".__('Components', 'monitoring')."</a>";
   echo "</th>";

   echo "<th rowspan='2' width='11%'>";
   echo "<a href='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/contacttemplate.php'>".__('Contact templates', 'monitoring')."</a>";
   echo "</th>";

   echo "<th rowspan='2' width='11%'>";
   echo "<a href='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/notificationcommand.php'>".__('Notification commands', 'monitoring')."</a>";
   echo "</th>";

   echo "<th rowspan='2' width='11%'>";
   echo "<a href='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/realm.php'>".__('Reamls', 'monitoring')."</a>";
   echo "</th>";

   echo "<th rowspan='2'>";
   echo "<a href='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/tag.php'>".__('Tag', 'monitoring')."</a>";
   echo "</th>";
   echo "</tr>";


   echo "<tr class='tab_bg_1'>";
   echo "<th width='11%' height='25'>";
   echo "<a href='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/command.php'>".__('Commands', 'monitoring')."</a>";
   echo "</th>";

   echo "<th width='11%'>";
   echo "<a href='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/check.php'>".__('Check definition', 'monitoring')."</a>";
   echo "</th>";

   echo "<th width='11%'>";
   if (Session::haveRight('calendar', 'r')) {
      echo "<a href='".$CFG_GLPI['root_doc']."/front/calendar.php'>".__('Calendar')."</a>";
   }
   echo "</th>";

   echo "<th width='11%'>";
   echo "<a href='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/eventhandler.php'>".__('Event handler', 'monitoring')."</a>";
   echo "</th>";
   
   echo "<th width='11%'>";
   echo "<a href='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/perfdata.php'>".__('Graph templates', 'monitoring')."</a>";
   echo "</th>";
   echo "</tr>";  

   echo "</table>";
}

if ($toDisplayArea <= 0) {
   echo "<table class='tab_cadre' width='950'>";
   echo "<tr class='tab_bg_1'>";
   echo "<th height='80'>";
   echo __('Sorry, your profile does not allow any views in the Monitoring', 'monitoring');
   echo "</th>";
   echo "</tr>";
   echo "</table>";
}

Html::footer();

?>