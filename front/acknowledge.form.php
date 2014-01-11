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
   if (isset($_POST['serviceCount']) ) {
      // $pmHost = new PluginMonitoringHost();
      
      for ($i = 0; $i < $_POST['serviceCount']; $i++) {
         // Toolbox::logInFile("monitoring", "Acknowledge service ".$_POST['serviceId'.$i]."\n");
         
         $serviceData = array();
         $serviceData['id'] = $_POST['serviceId'.$i];
         $serviceData['is_acknowledged'] = '1';
         $serviceData['acknowledge_users_id'] = $_POST['acknowledge_users_id'];
         $serviceData['acknowledge_comment'] = $_POST['acknowledge_comment'];
         $pmService->update($serviceData);
         
         // Send acknowledge command to shinken via webservice   
         $pmShinkenwebservice = new PluginMonitoringShinkenwebservice();
         $user = new User();
         $user->getFromDB($this->fields['acknowledge_users_id']);    
         $pmShinkenwebservice->sendAcknowledge($_POST['serviceId'.$i], $user->getName(1)." : ".$serviceData['acknowledge_comment']);
      }
   } else {
      $pmService->update($_POST);
      // Send acknowledge command to shinken via webservice   
      $pmShinkenwebservice = new PluginMonitoringShinkenwebservice();
      $pmShinkenwebservice->sendAcknowledge($_POST['id'], $user->getName(1)." : ".$serviceData['acknowledge_comment']);
   }
   
   // "[date('U')] ACKNOWLEDGE_SVC_PROBLEM;Computer-11-debian;rrrrr-1;1;1;1;glpi;comment ddurieux\n"
   Html::redirect($_POST['referer']);
}

if (isset($_GET['host']) && isset($_GET['id'])) {
   // Acknowledge an host ...
   $pmService->addAcknowledge($_GET['id'], $_GET['host']);
} else if (isset($_GET['id'])) {
   // Acknowledge a service ...
   $pmService->addAcknowledge($_GET['id']);
}
if (isset($_GET['form'])) {
   $pmService->formAcknowledge($_GET['form']);
}

Html::footer();

?>