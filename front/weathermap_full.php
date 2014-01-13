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

Html::popHeader("weathermap");

PluginMonitoringProfile::checkRight("config_weathermap","r");

if (!isset($_GET['id'])) {
   exit;
}
$id = $_GET['id'];
$pmWeathermap = new PluginMonitoringWeathermap();
$pmWeathermap->generateWeathermap($id);

echo '<div id="custom_date" style="display:none"></div>';
echo '<div id="custom_time" style="display:none"></div>';

//$pmWeathermap->generateAllGraphs($id);
$html = file_get_contents(GLPI_PLUGIN_DOC_DIR."/monitoring/weathermap-".$id.".html");

$html = str_replace(GLPI_PLUGIN_DOC_DIR."/monitoring/weathermap-".$id.".png", 
         $CFG_GLPI['root_doc']."/plugins/monitoring/front/send.php?file=weathermap-".$id.".png", $html);


PluginMonitoringServicegraph::loadLib();
echo $html;

echo '<meta http-equiv ="refresh" content="150">';

?>