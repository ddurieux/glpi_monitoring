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

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginMonitoringShinkenwebservice extends CommonDBTM {
   
   function sendAcknowledge($services_id) {
      global $DB;
      
      $pmService   = new PluginMonitoringService();
      $pmComponent = new PluginMonitoringComponent();
      $pmTag       = new PluginMonitoringTag();
      $pmService->getFromDB($services_id);
      
      $hostname = '';
      $queryh = "SELECT * FROM `glpi_plugin_monitoring_componentscatalogs_hosts` 
         WHERE `id` = '".$pmService->fields['plugin_monitoring_componentscatalogs_hosts_id']."'
         LIMIT 1";
      $resulth = $DB->query($queryh);
      while ($datah=$DB->fetch_array($resulth)) {
         $itemtype = $datah['itemtype'];
         $item = new $itemtype();
         if ($item->getFromDB($datah['items_id'])) {
            $hostname = $itemtype."-".$datah['items_id']."-".preg_replace("/[^A-Za-z0-9]/","",$item->fields['name']);
         }         
      }
      
      $a_component = current($pmComponent->find("`id`='".$pmService->fields['plugin_monitoring_components_id']."'", "", 1));
      $service_description = preg_replace("/[^A-Za-z0-9]/","",$a_component['name'])."-".$pmService->fields['id'];
      
      $tag = PluginMonitoringEntity::getTagByEntities($pmService->fields['entities_id']);
      $ip = $pmTag->getIP($tag);
      $auth = $pmTag->getAuth($tag);
      
      $url = 'http://'.$ip.':7760/';
      $action = 'acknowledge';
      $a_fields = array(
          'host_name'            => urlencode($hostname),
          'service_description'  => urlencode($service_description),
          'author'               => urlencode($_SESSION['glpiname']),
          'comment'              => urlencode('')
      );
      
      $this->sendCommand($url, $action, $a_fields,'', $auth);
   }
   
   
   
   function sendRestartArbiter($force=0) {
      
      $pmTag = new PluginMonitoringTag();
      $pmLog = new PluginMonitoringLog();
      
      $a_tags = $pmTag->find();
      foreach ($a_tags as $data) {
         if (!$pmLog->isRestartLessThanFiveMinutes()
                 || $force) {         
            $url = 'http://'.$data['ip'].':7760/';
            $action = 'restart';
            $a_fields = array();

            $auth = $pmTag->getAuth($data['tag']);
            $restart = $this->sendCommand($url, $action, $a_fields, '', $auth);

            if ($restart) {
               $input = array();
               $input['user_name'] = $_SESSION['glpifirstname'].' '.$_SESSION['glpirealname'].
                       ' ('.$_SESSION['glpiname'].')';
               $input['action']    = "restart_planned";
               $input['date_mod']  = date("Y-m-d H:i:s");
               $pmLog->add($input);
            }
         }
      }
   }
   
   
   
   function sendCommand($url, $action, $a_fields, $fields_string='', $auth='') {

      if ($fields_string == '') {
         foreach($a_fields as $key=>$value) { 
            $fields_string .= $key.'='.$value.'&'; 
         }
         rtrim($fields_string, '&');
      }
      
      $ch = curl_init();
      
      curl_setopt($ch,CURLOPT_URL, $url.$action);
      curl_setopt($ch,CURLOPT_POST, count($a_fields));
      curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      if ($auth != '') {
         curl_setopt($ch,CURLOPT_USERPWD, $auth);
      }
      
      $ret = curl_exec($ch);
      $return = true;
      if ($ret === false) {
         Session::addMessageAfterRedirect(
                 __('Shinken restart failed:', 'monitoring').' '.curl_error($ch), 
                 false, 
                 ERROR);
         $return = false;
      } else if (strstr($ret, 'error')) {
         Session::addMessageAfterRedirect(
                 __('Shinken restart failed:', 'monitoring').' '.$ret, 
                 false, 
                 ERROR);
         $return = false;
      }
      curl_close($ch);
      return $return;
   }   
   
}

?>