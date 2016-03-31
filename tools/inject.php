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

include ("../../inc/includes.php");

$a_services = array();
$pmService = new PluginMonitoringService();
$a_services = $pmService->find("`plugin_monitoring_components_id`='12'");
for ($i = 0; $i < 1000000000; $i++) {
   foreach ($a_services as $id=>$data) {
      $perf = "inUsage=0.00%;85;98 outUsage=0.00%;85;98 ".
              "inBandwidth=".rand(1, 100000).".00bps outBandwidth=".rand(1, 100000).".00bps inAbsolut=0 outAbsolut=12665653";
      $ins = "INSERT INTO `glpi_plugin_monitoring_serviceevents`
         (`plugin_monitoring_services_id`, `date`, `perf_data`, `state`, `state_type`)
         VALUES('".$id."', '".date('Y-m-d H:i:s')."', '".$perf."', 'OK', 'HARD')";
     
      $DB->query($ins);

      $rand = rand(0, 100);
      if ($rand < 40) {
         sleep(1);
      }      
   }
}



commonFooter();

?>
