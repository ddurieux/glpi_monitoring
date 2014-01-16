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
   @since     2013
 
   ------------------------------------------------------------------------
 */

include ("../../../inc/includes.php");

PluginMonitoringProfile::checkRight("acknowledge","r");

Html::header(__('Monitoring', 'monitoring'),$_SERVER["PHP_SELF"], "plugins", 
             "monitoring", "acknowledge");

$pmService = new PluginMonitoringService();

if (isset($_POST['add']) ||isset($_POST['update']) ) {
   $user = new User();
   $user->getFromDB($_POST['acknowledge_users_id']);
   
   if (isset($_POST['hostname'])) {
      // Acknowledge an host ...
      if (isset($_POST['hostAcknowledge'])) {
         // Toolbox::logInFile("monitoring", "Acknowledge host ".$_POST['host_id']." / ".$_POST['hostname']."\n");
   
         // Send acknowledge command for an host to shinken via webservice   
         $pmShinkenwebservice = new PluginMonitoringShinkenwebservice();
         if ($pmShinkenwebservice->sendAcknowledge('', $user->getName(1), $_POST['acknowledge_comment'], $_POST['id'], $_POST['hostname'])) {
            $pmHost = new PluginMonitoringHost();
            $pmHost->getFromDBByQuery("WHERE `items_id` = '".$_POST['host_id']."'");
            $hostData = array();
            $hostData['id'] = $pmHost->fields['id'];
            $hostData['is_acknowledged'] = '1';
            $hostData['acknowledge_users_id'] = $_POST['acknowledge_users_id'];
            $hostData['acknowledge_comment'] = $_POST['acknowledge_comment'];
            $pmHost->update($hostData);
         }
      }
      
      // Acknowledge all services of an host ...
      if (isset($_POST['serviceCount'])) {
         // Toolbox::logInFile("monitoring", "Acknowledge host (all services) ".$_POST['host_id']." / ".$_POST['hostname']."\n");
   
         for ($i = 0; $i < $_POST['serviceCount']; $i++) {
            // Toolbox::logInFile("monitoring", " - acknowledge service ".$_POST['serviceId'.$i]."\n");
            
            // Send acknowledge command for a service to shinken via webservice   
            $pmShinkenwebservice = new PluginMonitoringShinkenwebservice();
            if ($pmShinkenwebservice->sendAcknowledge($_POST['serviceId'.$i], $user->getName(1), $_POST['acknowledge_comment'])) {
               $serviceData = array();
               $serviceData['id'] = $_POST['serviceId'.$i];
               $serviceData['is_acknowledged'] = '1';
               $serviceData['acknowledge_users_id'] = $_POST['acknowledge_users_id'];
               $serviceData['acknowledge_comment'] = $_POST['acknowledge_comment'];
               $pmService->update($serviceData);
            }
         }
      }
   } else {
      // Toolbox::logInFile("monitoring", "Acknowledge service ".$_POST['id']."\n");
   
      // Send acknowledge command for a service to shinken via webservice   
      $pmShinkenwebservice = new PluginMonitoringShinkenwebservice();
      if ($pmShinkenwebservice->sendAcknowledge($_POST['id'], $user->getName(1), $_POST['acknowledge_comment'])) {
         // Simply acknowledge a service ...
         $pmService->update($_POST);
      }
   }
   
   Html::redirect($_POST['referer']);
}

if (isset($_GET['host']) && isset($_GET['id'])) {
   // Acknowledge an host ...
   $pmService->addAcknowledge($_GET['id'], $_GET['host'], isset($_GET['allServices']));
} else if (isset($_GET['id'])) {
   // Acknowledge a service ...
   $pmService->addAcknowledge($_GET['id']);
}

// Modify acknowledge comment ...
if (isset($_GET['form'])) {
   if (isset($_GET['host'])) {
      // ... for an host
      $pmService->formAcknowledge($_GET['form'], $_GET['host']);
   } else {
      // ... for a service
      $pmService->formAcknowledge($_GET['form']);
   }
}

Html::footer();

?>