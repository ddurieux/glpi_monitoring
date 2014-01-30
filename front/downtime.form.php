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
   @author    Frédéric Mohier
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

PluginMonitoringProfile::checkRight("downtime","r");

Html::header(__('Monitoring - downtimes', 'monitoring'),'', "plugins", "monitoring", "downtime");

$pmDowntime = new PluginMonitoringDowntime();

if (isset ($_POST["add"])) {
   $pmDowntime->add($_POST);
   Html::back();
} else if (isset ($_POST["update"])) {
   $pmDowntime->update($_POST);
   Html::back();
} else if (isset ($_POST["delete"])) {
   $pmDowntime->delete($_POST);
   $pmDowntime->redirectToList();
}

if (isset($_POST['add']) || isset($_POST['add_and_ticket'])) {
   $user = new User();
   $user->getFromDB($_POST['downtime_users_id']);
   $username = $user->getName(1);
   
   if (isset($_POST['hostname'])) {
      $pmHost = new PluginMonitoringHost();
      $pmHost->getFromDB($_POST['host_id']);
      // Toolbox::logInFile("pm", "Downtime for host ".$pmHost->getName()." : ".$comment." : \n");
   
      // Downtime an host ...
      if (isset($_POST['hostdowntime'])) {
         // Toolbox::logInFile("pm", "Downtime host ".$_POST['host_id']." / ".$pmHost->getName()."\n");
   
         // Send downtime command for an host to shinken via webservice   
         $pmShinkenwebservice = new PluginMonitoringShinkenwebservice();
         if ($pmShinkenwebservice->sendDowntime($_POST['host_id'], -1, $username, $comment)) {
            $hostData = array();
            $hostData['id'] = $pmHost->fields['id'];
            $hostData['is_downtimed'] = '1';
            $hostData['is_downtimeconfirmed'] = '1';
            $hostData['downtime_users_id'] = $_POST['downtime_users_id'];
            $hostData['downtime_comment'] = $comment;
            $pmHost->update($hostData);
         }
      }
      
      // Downtime all services of an host ...
      if (isset($_POST['serviceCount'])) {
         // Toolbox::logInFile("pm", "Downtime host (all services) ".$_POST['host_id']." / ".$_POST['hostname']."\n");
   
         for ($i = 0; $i < $_POST['serviceCount']; $i++) {
            // Toolbox::logInFile("pm", " - downtime service ".$_POST['serviceId'.$i]."\n");
            
            // Send downtime command for a service to shinken via webservice   
            $pmShinkenwebservice = new PluginMonitoringShinkenwebservice();
            if ($pmShinkenwebservice->sendDowntime(-1, $_POST['serviceId'.$i], $username, $comment)) {
               $serviceData = array();
               $serviceData['id'] = $_POST['serviceId'.$i];
               $serviceData['is_downtimed'] = '1';
               $serviceData['is_downtimeconfirmed'] = '0';
               $serviceData['downtime_users_id'] = $_POST['downtime_users_id'];
               $serviceData['downtime_comment'] = $comment;
               $pmService->update($serviceData);
            }
         }
      }
      
      // Request for a ticket creation ...
      if (isset($_POST['add_and_ticket'])) {
         // Toolbox::logInFile("pm", "Request ticket creation ".$_POST['host_id']." / ".$_POST['hostname']."\n");
   
         $pmTicket = new Ticket();
         $tkt = array();
         $tkt['_users_id_requester'] = $_POST['downtime_users_id'];
         $tkt["_users_id_requester_notif"]['use_notification'] = 1;

         $tkt['_auto_update'] = 1;
         // $tkt['itemtype']     = $_POST['itemtype'];
         // $tkt["items_id`"]    = $_POST['items_id`'];
         
         $tkt['_head']        = $_POST['name'];
         $tkt['content']      = $comment;
         
         // Medium urgency
         $tkt['urgency']      = "3";
         
         // $tkt['entities_id']  = $entity;
         // $tkt['date']         = $head['date'];
         // Medium
         $tkt['urgency']      = "3";

         $ticketId = $pmTicket->add($tkt);
         // Toolbox::logInFile("pm", "Ticket id ".$ticketId."\n");
         
         Html::redirect($_POST['redirect']);
      }
   } else {
      // Toolbox::logInFile("pm", "Downtime service ".$_POST['id']."\n");
   
      // Send downtime command for a service to shinken via webservice   
      $pmShinkenwebservice = new PluginMonitoringShinkenwebservice();
      if ($pmShinkenwebservice->sendDowntime(-1, $_POST['id'], $username, $comment)) {
         // Simply downtime a service ...
         $serviceData = array();
         $serviceData['id'] = $_POST['id'];
         $serviceData['is_downtimed'] = '1';
         $serviceData['is_downtimeconfirmed'] = '0';
         $serviceData['downtime_users_id'] = $_POST['downtime_users_id'];
         $serviceData['downtime_comment'] = $comment;
         $pmService->update($serviceData);
      }
   }
   
   Html::redirect($_POST['referer']);
}

// Read or edit downtime ...
if (isset($_GET['id'])) {
   // Toolbox::logInFile("pm", "Downtime, showForm ".$_GET['id']."\n");
   // If host_id is defined, use it ...
   $pmDowntime->showForm($_GET['id'], (isset($_GET['host_id'])) ? $_GET['host_id'] : '');
}

Html::footer();

?>