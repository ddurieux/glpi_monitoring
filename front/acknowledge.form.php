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

Html::header(__('Monitoring - acknowledge', 'monitoring'),'', "monitoring", "acknowledge");

$pmService = new PluginMonitoringService();

if (isset($_POST['add']) || isset($_POST['update']) || isset($_POST['add_and_ticket'])) {
   $user = new User();
   $user->getFromDB($_POST['acknowledge_users_id']);
   $username = $user->getName(1);
   $comment = $_POST['acknowledge_comment'];
   
   if (isset($_POST['hostname'])) {
      $pmHost = new PluginMonitoringHost();
      $pmHost->getFromDB($_POST['host_id']);
      // Toolbox::logInFile("pm", "Acknowledge for host ".$pmHost->getName()." : ".$comment." : \n");
   
      // Acknowledge an host ...
      if (isset($_POST['hostAcknowledge'])) {
         // Toolbox::logInFile("pm", "Acknowledge host ".$_POST['host_id']." / ".$pmHost->getName()."\n");
   
         // Send acknowledge command for an host to shinken via webservice   
         $pmShinkenwebservice = new PluginMonitoringShinkenwebservice();
         if ($pmShinkenwebservice->sendAcknowledge($_POST['host_id'], -1, $username, $comment)) {
            $hostData = array();
            $hostData['id'] = $pmHost->fields['id'];
            $hostData['is_acknowledged'] = '1';
            $hostData['is_acknowledgeconfirmed'] = '1';
            $hostData['acknowledge_users_id'] = $_POST['acknowledge_users_id'];
            $hostData['acknowledge_comment'] = $comment;
            $pmHost->update($hostData);
         }
      }
      
      // Acknowledge all services of an host ...
      if (isset($_POST['serviceCount'])) {
         // Toolbox::logInFile("pm", "Acknowledge host (all services) ".$_POST['host_id']." / ".$_POST['hostname']."\n");
   
         for ($i = 0; $i < $_POST['serviceCount']; $i++) {
            // Toolbox::logInFile("pm", " - acknowledge service ".$_POST['serviceId'.$i]."\n");
            
            // Send acknowledge command for a service to shinken via webservice   
            $pmShinkenwebservice = new PluginMonitoringShinkenwebservice();
            if ($pmShinkenwebservice->sendAcknowledge(-1, $_POST['serviceId'.$i], $username, $comment)) {
               $serviceData = array();
               $serviceData['id'] = $_POST['serviceId'.$i];
               $serviceData['is_acknowledged'] = '1';
               $serviceData['is_acknowledgeconfirmed'] = '0';
               $serviceData['acknowledge_users_id'] = $_POST['acknowledge_users_id'];
               $serviceData['acknowledge_comment'] = $comment;
               $pmService->update($serviceData);
            }
         }
      }
      
      // Request for a ticket creation ...
      if (isset($_POST['add_and_ticket'])) {
         // Toolbox::logInFile("pm", "Request ticket creation ".$_POST['host_id']." / ".$_POST['hostname']."\n");
   
         $pmTicket = new Ticket();
         $tkt = array();
         $tkt['_users_id_requester'] = $_POST['acknowledge_users_id'];
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
      // Toolbox::logInFile("pm", "Acknowledge service : ".$_POST['id']."\n");
   
      // Send acknowledge command for a service to shinken via webservice   
      $pmShinkenwebservice = new PluginMonitoringShinkenwebservice();
      if ($pmShinkenwebservice->sendAcknowledge(-1, $_POST['id'], $username, $comment)) {
         // Simply acknowledge a service ...
         $serviceData = array();
         $serviceData['id'] = $_POST['id'];
         $serviceData['is_acknowledged'] = '1';
         $serviceData['is_acknowledgeconfirmed'] = '0';
         $serviceData['acknowledge_users_id'] = $_POST['acknowledge_users_id'];
         $serviceData['acknowledge_comment'] = $comment;
         $pmService->update($serviceData);
      }
   }
   
   Html::redirect($_POST['referer']);
}

if (isset($_GET['host']) && isset($_GET['id'])) {
   // Acknowledge an host ... id is pmHost identifier !
   $pmHost = new PluginMonitoringHost();
   $pmHost->getFromDBByQuery("WHERE `items_id` = '".$_GET['id']."'");
   $pmHost->showAddAcknowledgeForm($_GET['id'], isset($_GET['allServices']));
} else if (isset($_GET['id'])) {
   // Acknowledge a service ...
   $pmService->showAddAcknowledgeForm($_GET['id']);
}

// Modify acknowledge comment ...
if (isset($_GET['form'])) {
   if (isset($_GET['host'])) {
      // ... for an host
      $pmHost = new PluginMonitoringHost();
      $pmHost->getFromDBByQuery("WHERE `items_id` = '".$_GET['form']."'");
      $pmHost->showUpdateAcknowledgeForm($_GET['form']);
   } else {
      // ... for a service
      $pmService->showUpdateAcknowledgeForm($_GET['form']);
   }
}

Html::footer();

?>