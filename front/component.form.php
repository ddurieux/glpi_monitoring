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

PluginMonitoringProfile::checkRight("component","w");

Html::header(__('Monitoring', 'monitoring'),$_SERVER["PHP_SELF"], "plugins", 
             "monitoring", "components");


$pMonitoringComponent = new PluginMonitoringComponent();

if (isset ($_POST["add"])) {
   if (empty($_POST['name'])
           OR empty($_POST['plugin_monitoring_checks_id'])
           OR empty($_POST['plugin_monitoring_commands_id'])
           OR empty($_POST['calendars_id'])) {
      
      $_SESSION['plugin_monitoring_components'] = $_POST;
    
      Session::addMessageAfterRedirect("<font class='red'>".__('Fields with asterisk are required', 'monitoring')."</font>");
      Html::back();
      exit;
   }   
   
   $pMonitoringComponent->add($_POST);
   Html::back();
} else if (isset ($_POST["update"])) {
   if (isset($_POST['arg'])) {
      $_POST['arguments'] = exportArrayToDB($_POST['arg']);
   }   
   if (empty($_POST['name'])
           OR empty($_POST['plugin_monitoring_checks_id'])
           OR empty($_POST['plugin_monitoring_commands_id'])
           OR empty($_POST['calendars_id'])) {
    
      $_SESSION['plugin_monitoring_components'] = $_POST;
    
      Session::addMessageAfterRedirect("<font class='red'>".__('Fields with asterisk are required', 'monitoring')."</font>");
      Html::back();
      exit;
   }

   $pMonitoringComponent->update($_POST);
   Html::back();
} else if (isset ($_POST["delete"])) {
   $pMonitoringComponent->delete($_POST);
   $pMonitoringComponent->redirectToList();
}


if (isset($_GET["id"])) {
   $pMonitoringComponent->showForm($_GET["id"]);
} else {
   $pMonitoringComponent->showForm(0);
}

Html::footer();

?>