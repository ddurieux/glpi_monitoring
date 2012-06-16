<?php

/*
   ------------------------------------------------------------------------
   Plugin Monitoring for GLPI
   Copyright (C) 2011-2012 by the Plugin Monitoring for GLPI Development Team.

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
   along with Behaviors. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   Plugin Monitoring for GLPI
   @author    David Durieux
   @co-author 
   @comment   
   @copyright Copyright (c) 2011-2012 Plugin Monitoring for GLPI team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      https://forge.indepnet.net/projects/monitoring/
   @since     2011
 
   ------------------------------------------------------------------------
 */

define('GLPI_ROOT', '../../..');
include (GLPI_ROOT . "/inc/includes.php");

PluginMonitoringProfile::checkRight("weathermap","r");

if (!isset($_GET['id'])) {
   exit;
}
$id = $_GET['id'];
$pmWeathermap = new PluginMonitoringWeathermap();
$pmWeathermap->generateWeathermap($id);
$html = file_get_contents(GLPI_PLUGIN_DOC_DIR."/monitoring/weathermap-".$id.".html");
//$matches = array();
//preg_match_all("/img  src\=([[:print:]]+)\.png/i", $html, $matches);



$html = str_replace(GLPI_PLUGIN_DOC_DIR."/monitoring/weathermap-".$id.".png", 
         $CFG_GLPI['root_doc']."/plugins/monitoring/front/send.php?file=weathermap-".$id.".png", $html);
$html = str_replace("overlib.js", GLPI_ROOT."/plugins/monitoring/lib/weathermap/overlib.js", $html);

$html = str_replace('/lib/weathermap/overlib.js',
        '/lib/tooltip.js', $html);
$html = str_replace('<body>', '<body><div id="dhtmltooltip"></div><style type="text/css">
#dhtmltooltip{
position: absolute;
width: 700px;
height: 230px;
border: 2px solid black;
padding: 2px;
background-color: lightyellow;
visibility: hidden;
z-index: 100;
/*Remove below line to remove shadow. Below line should always appear last within this CSS*/
filter: progid:DXImageTransform.Microsoft.Shadow(color=gray,direction=135);
}

</style>', $html);
$html = str_replace('return overlib', 'ddrivetip', $html);
$html = str_replace('return nd();', 'hideddrivetip()', $html);

echo $html;

echo '<meta http-equiv ="refresh" content="60">';

?>