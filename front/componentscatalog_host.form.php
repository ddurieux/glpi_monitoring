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

PluginMonitoringProfile::checkRight("config_components_catalogs","w");

$pmComponentscatalog_Host = new PluginMonitoringComponentscatalog_Host();

if (isset ($_POST["add"])) {
   if (isset($_POST['items_id'])
           AND $_POST['items_id'] != '0') {
      $componentscatalogs_hosts_id = $pmComponentscatalog_Host->add($_POST);
      $pmComponentscatalog_Host->linkComponentsToItem($_POST['plugin_monitoring_componentscalalog_id'], 
                                                      $componentscatalogs_hosts_id);
   }
   Html::back();
} else if (isset($_POST["deleteitem"])) {
   foreach ($_POST["item"] as $id=>$num) {
      $pmComponentscatalog_Host->delete(array('id'=>$id));
   }
   Html::back();
}

Html::footer();

?>