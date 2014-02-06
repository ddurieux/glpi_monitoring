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
   @since     2011
 
   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginMonitoringDowntime extends CommonDBTM {
   
   static function getTypeName($nb=0) {
      return _n(__('Host downtime', 'monitoring'),__('Host downtimes', 'monitoring'),$nb);
   }
   
   
   static function canCreate() {      
      return PluginMonitoringProfile::haveRight("downtime", 'w');
   }


   static function canUpdate() {
      return PluginMonitoringProfile::haveRight('downtime', 'w');
   }


   static function canDelete() {
      return PluginMonitoringProfile::haveRight('downtime', 'w');
   }


   static function canView() {
      return PluginMonitoringProfile::haveRight("downtime", 'r');
   }


   
   function defineTabs($options=array()){

      $ong = array();

      return $ong;
   }
   
   
   
   function getComments() {
   }

   
    
    /**
    * Display tab
    * 
    * @param CommonGLPI $item
    * @param integer $withtemplate
    * 
    * @return varchar name of the tab(s) to display
    */
   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {
      if ($item->getType() == 'Ticket'){
         return __('Downtimes', 'monitoring');
      }
      
      return '';
   }
   
   
 
   /**
    * Display content of tab
    * 
    * @param CommonGLPI $item
    * @param integer $tabnum
    * @param interger $withtemplate
    * 
    * @return boolean true
    */
   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {

      if ($item->getType()=='Ticket') {
         Toolbox::logInFile("pm", "Downtime, displayTabContentForItem, item concerned : ".$item->getField('itemtype')."/".$item->getField('items_id')."\n");
         if (self::canView()) {
            // Find a monitoring host ...
            $host_id=-1;
            $pmHost = new PluginMonitoringHost();
            $a_list = $pmHost->find("itemtype = '".$item->getField('itemtype')."' AND items_id = '".$item->getField('items_id')."'");
            foreach ($a_list as $data) {
               $host_id=$data['id'];
            }
            
            $pmDowntime = new PluginMonitoringDowntime();
            $pmDowntime->showForm(-1, $host_id);
            return true;
         }
      }
      return true;
   }

   

   function getSearchOptions() {

      $tab = array();

      $tab['common'] = __('Host downtimes', 'monitoring');

      $tab[1]['table']           = $this->getTable();
      $tab[1]['field']           = 'id';
      $tab[1]['linkfield']       = 'id';
      $tab[1]['name']            = __('ID');
      $tab[1]['datatype']        = 'itemlink';
      $tab[1]['massiveaction']   = false; // implicit field is id

      $tab[2]['table']           = $this->getTable();
      $tab[2]['field']           = 'plugin_monitoring_hosts_id';
      $tab[2]['name']            = __('Host name', 'monitoring');
      $tab[2]['datatype']        = 'specific';
      $tab[2]['massiveaction']   = false;

      $tab[3]['table']           = $this->getTable();
      $tab[3]['field']           = 'flexible';
      $tab[3]['name']            = __('Flexible downtime', 'monitoring');
      $tab[3]['datatype']        = 'bool';
      $tab[3]['massiveaction']   = false;

      $tab[4]['table']           = $this->getTable();
      $tab[4]['field']           = 'start_time';
      $tab[4]['name']            = __('Start time', 'monitoring');
      $tab[4]['datatype']        = 'datetime';
      $tab[4]['massiveaction']   = false;

      $tab[5]['table']           = $this->getTable();
      $tab[5]['field']           = 'end_time';
      $tab[5]['name']            = __('End time', 'monitoring');
      $tab[5]['datatype']        = 'datetime';
      $tab[5]['massiveaction']   = false;

      $tab[6]['table']           = $this->getTable();
      $tab[6]['field']           = 'duration';
      $tab[6]['name']            = __('Duration', 'monitoring');
      $tab[6]['massiveaction']   = false;

      $tab[7]['table']           = $this->getTable();
      $tab[7]['field']           = 'duration_type';
      $tab[7]['name']            = __('Duration type', 'monitoring');
      $tab[7]['massiveaction']   = false;

      $tab[8]['table']           = $this->getTable();
      $tab[8]['field']           = 'comment';
      $tab[8]['name']            = __('Comment', 'monitoring');
      $tab[8]['datatype']        = 'itemlink';
      // $tab[8]['datatype']        = 'text';
      $tab[8]['massiveaction']   = false;

      $tab[9]['table']           = $this->getTable();
      $tab[9]['field']           = 'users_id';
      $tab[9]['name']            = __('User', 'monitoring');
      $tab[9]['massiveaction']   = false;

      $tab[10]['table']          = $this->getTable();
      $tab[10]['field']          = 'notified';
      $tab[10]['name']           = __('Notified to monitoring system', 'monitoring');
      $tab[10]['datatype']       = 'bool';
      $tab[10]['massiveaction']  = false;

      $tab[11]['table']          = $this->getTable();
      $tab[11]['field']          = 'expired';
      $tab[11]['name']           = __('Period expired', 'monitoring');
      $tab[11]['datatype']       = 'bool';
      $tab[11]['massiveaction']  = false;

      return $tab;
   }


   static function getSpecificValueToDisplay($field, $values, array $options=array()) {

      if (!is_array($values)) {
         $values = array($field => $values);
      }
      switch ($field) {
         case 'plugin_monitoring_hosts_id':
            $pmHost = new PluginMonitoringHost();
            $pmHost->getFromDB($values[$field]);
            return $pmHost->getLink(array ("monitoring" => "1"));
            break;
            
         case 'duration_type':
            $a_duration_type = array();
            $a_duration_type['seconds'] = __('Second(s)', 'monitoring');
            $a_duration_type['minutes'] = __('Minute(s)', 'monitoring');
            $a_duration_type['hours']   = __('Hour(s)', 'monitoring');
            $a_duration_type['days']    = __('Day(s)', 'monitoring');
            return $a_duration_type[$values[$field]];
            break;
            
         case 'users_id':
            $user = new User();
            $user->getFromDB($values[$field]);    
            return $user->getName(1);
            break;
      }
      return parent::getSpecificValueToDisplay($field, $values, $options);
   }
   

   /**
    * Get entity
    */
   function getEntityID($options = array()) {
      return $this->fields["entities_id"];
   }
   

   /**
    * Set default content
    */
   function setDefaultContent($host_id) {
      // Start time : now ...
      $start_time = strtotime(date('Y-m-d H:i:s'));
      // End time : now + 2 hours ...
      $end_time = $start_time+7200;

      $this->fields["plugin_monitoring_hosts_id"]  = $host_id;
      $this->fields["start_time"]                  = date('Y-m-d H:i:s', $start_time);
      $this->fields["end_time"]                    = date('Y-m-d H:i:s', $end_time);
      $this->fields["flexible"]                    = 0;
      $this->fields["duration"]                    = 4;
      $this->fields["duration_type"]               = 'hours';
      $this->fields["users_id"]                    = $_SESSION['glpiID'];
      $this->fields["notified"]                    = 0;
      $this->fields["expired"]                     = 0;
   }
   

   /**
    * Get host identifier for a downtime
    */
   function getHostID() {
      return $this->fields["plugin_monitoring_hosts_id"];
   }
   
   
   /**
    * Get current downtime for an host
    */
   function getFromHost($host_id) {
      // $pmDowntime = new PluginMonitoringDowntime();
      $this->getFromDBByQuery("WHERE `" . $this->getTable() . "`.`plugin_monitoring_hosts_id` = '" . $this->getID() . "' AND `expired` = '0' ORDER BY end_time DESC LIMIT 1");
      return $this->getID();
   }
   
   
   /**
    * Get user name for a downtime
    */
   function getUsername() {
      $user = new User();
      $user->getFromDB($this->fields['users_id']);    
      return $user->getName(1);
   }
   
   
   /**
    * In scheduled downtime ?
    */
   function isInDowntime() {
      if ($this->getID() == -1) return false;
      
      if ($this->isExpired()) return false;
      
      // Now ...
      $now = strtotime(date('Y-m-d H:i:s'));
      // Start time ...
      $start_time = strtotime($this->fields["start_time"]);
      // End time ...
      $end_time = strtotime($this->fields["end_time"]);
      
      // Toolbox::logInFile("pm", "isInDowntime, now : $now, start : $start_time, end : $end_time\n");
      if (($start_time <= $now) && ($now <= $end_time)) {
         // Toolbox::logInFile("pm", "isInDowntime, yes, id : ".$this->getID()."\n");
         return true;
      }
      
      return false;
   }


   /**
    * Downtime expired ?
    */
   function isExpired() {
      if ($this->getID() == -1) return false;
      
      // Now ...
      $now = strtotime(date('Y-m-d H:i:s'));
      // Start time ...
      $start_time = strtotime($this->fields["start_time"]);
      // End time ...
      $end_time = strtotime($this->fields["end_time"]);
      
      $this->fields["expired"] = ($now > $end_time);
      $this->update($this->fields);
      // Toolbox::logInFile("pm", "isExpired, ".$this->fields["expired"]."\n");
      return ($this->fields["expired"] == 1);
   }


   function prepareInputForAdd($input) {
      // Toolbox::logInFile("pm", "Downtime, prepareInputForAdd\n");

      if ($this->isExpired()) {
         Session::addMessageAfterRedirect(__('Downtime period has already expired!', 'monitoring'), false, ERROR);
         return false;
      }
      
      // Check user ...
      if ($input["users_id"] == NOT_AVAILABLE) {;
         $input["users_id"] = $_SESSION['glpiID'];
      }

      // Compute duration in seconds
      if ($input['duration'] == 0) {
         $input['duration_seconds'] = 0;
      } else {
         $multiple = 1;
         if ($input['duration_type'] == 'seconds') {
            $multiple = 1;
         } else if ($input['duration_type'] == 'minutes') {
            $multiple = 60;
         } else if ($input['duration_type'] == 'hours') {
            $multiple = 3600;
         } else if ($input['duration_type'] == 'days') {
            $multiple = 86400;
         }
         $input['duration_seconds'] = $multiple * $input['duration'];
      }

      $user = new User();
      $user->getFromDB($input['users_id']);    
      
      // Downtime is to be created ...
      // ... send information to shinken via webservice   
      $pmShinkenwebservice = new PluginMonitoringShinkenwebservice();
      if ($pmShinkenwebservice->sendDowntime($input['plugin_monitoring_hosts_id'],
                                             -1, 
                                             $user->getName(1), 
                                             $input['comment'],
                                             $input['flexible'],
                                             $input['start_time'],
                                             $input['end_time'],
                                             $input['duration_seconds'],
                                             'add'
                                             )) {
         // ... and then send an acknowledge for the host
         if ($pmShinkenwebservice->sendAcknowledge($input['plugin_monitoring_hosts_id'], 
                                                   -1, 
                                                   $user->getName(1), 
                                                   $input['comment'],
                                                   '1', '1', '1')) {
            // Set host as acknowledged 
            $pmHost = new PluginMonitoringHost();
            $pmHost->getFromDB($input['plugin_monitoring_hosts_id']);
            $pmHost->setAcknowledged($input['comment']);
            
            $a_services = $pmHost->getServicesID();
            if (is_array($a_services)) {
               foreach ($a_services as $service_id) {
                  // Send acknowledge command for a service to shinken via webservice   
                  $pmShinkenwebservice = new PluginMonitoringShinkenwebservice();
                  if ($pmShinkenwebservice->sendAcknowledge(-1, 
                                                            $service_id, 
                                                            $user->getName(1), 
                                                            $input['comment'],
                                                            '1', '1', '1')) {
                     // Set service as acknowledged 
                     $pmService = new PluginMonitoringService();
                     $pmService->getFromDB($service_id);
                     $pmService->setAcknowledged($input['comment']);
                  }
               }
            }
         }
         
         Session::addMessageAfterRedirect(__('Downtime notified to the monitoring application:', 'monitoring'));
         $input['notified'] = 1;
      } else {
         Session::addMessageAfterRedirect(__('Downtime has not been accepted by the monitoring application:', 'monitoring'), false, ERROR);
         return false;
      }

      return $input;
   }


   /**
    * Actions done after the ADD of the item in the database
    *
    * @return nothing
   **/
   function post_addItem() {
      // Toolbox::logInFile("pm", "Downtime, post_add\n");
      
   }


   /**
    * Actions done before the DELETE of the item in the database /
    * Maybe used to add another check for deletion
    *
    * @return bool : true if item need to be deleted else false
   **/
   function pre_deleteItem() {
      // Toolbox::logInFile("pm", "Downtime, pre_deleteItem\n");

      $user = new User();
      $user->getFromDB($this->fields['users_id']);    
      
      // Downtime is to be created ...
      // ... send information to shinken via webservice   
      $pmShinkenwebservice = new PluginMonitoringShinkenwebservice();
      // sendDowntime($host_id=-1, $service_id=-1, $author= '', $comment='', $start_time='0', $fixed='0', $duration='1') {
      if ($pmShinkenwebservice->sendDowntime($this->fields['plugin_monitoring_hosts_id'],
                                             -1, 
                                             $user->getName(1), 
                                             $this->fields['comment'],
                                             $this->fields['flexible'],
                                             $this->fields['start_time'],
                                             $this->fields['end_time'],
                                             $this->fields['duration_seconds'],
                                             'delete'
                                             )) {
         Session::addMessageAfterRedirect(__('Downtime deletion notified to the monitoring application:', 'monitoring'));
         $this->fields['notified'] = 1;
      } else {
         Session::addMessageAfterRedirect(__('Downtime deletion has not been accepted by the monitoring application:', 'monitoring'), false, ERROR);
         return false;
      }

      return true;
   }

   
   /**
   * 
   *
   * @param $items_id integer ID 
   *
   * @param $host_id integer associated host ID
   * @param $options array
   *
   *@return bool true if form is ok
   *
   **/
   function showForm($items_id=-1, $host_id=-1, $options=array()) {
      global $DB,$CFG_GLPI;

      if (($host_id == -1) && ($items_id == -1)) return false;

      $createDowntime = false;
      
      $pmHost = new PluginMonitoringHost();
      if ($host_id != -1) {
         $pmHost->getFromDB($host_id);
         if ($pmHost->isInScheduledDowntime()) {
            // If host already in scheduled downtime, show current downtime ...
            $pmDowntime = new PluginMonitoringDowntime();
            $pmDowntime->getFromDBByQuery("WHERE `" . $pmDowntime->getTable() . "`.`plugin_monitoring_hosts_id` = '" . $host_id . "' LIMIT 1");
            $items_id = $pmDowntime->getID();
            $this->getFromDB($items_id);
         } else {
            // .. else create new downtime
            $createDowntime = true;
            $this->getEmpty();
            $this->setDefaultContent($host_id);
         }
      } else {
         $this->getFromDB($items_id);
      }

      // $pmDowntime = new PluginMonitoringDowntime();
      // $pmDowntime->getFromDBByQuery("WHERE `" . $pmDowntime->getTable() . "`.`plugin_monitoring_hosts_id` = '" . $this->getID() . "' LIMIT 1");
      // if (isset($options['monitoring']) && $options['monitoring']) {
      // }

      // Now ...
      $nowDate = date('Y-m-d');
      $nowTime = date('H:i:s');
      
      $this->showFormHeader($options);

      $this->isExpired();
      
      $pmHost = new PluginMonitoringHost();
      $pmHost->getFromDB($this->fields["plugin_monitoring_hosts_id"]);
      $itemtype = $pmHost->getField("itemtype");
      $item = new $itemtype();
      $item->getFromDB($pmHost->getField("items_id"));
      echo "<tr class='tab_bg_1'>";
      echo "<td>".$item->getTypeName()."</td>";
      echo "<td>";
      echo "<input type='hidden' name='plugin_monitoring_hosts_id' value='".$this->fields['plugin_monitoring_hosts_id']."' />";
      echo $item->getLink()."&nbsp;".$pmHost->getComments();
      echo "</td>";
      
      echo "<td></td>";
      echo "<td></td>";
      echo "</tr>";
      
      
      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Start time', 'monitoring')."</td>";
      echo "<td>";
      $date = $this->fields["start_time"];
      Html::showDateTimeField("start_time", array('value'      => $date,
                                                  'timestep'   => 10,
                                                  'maybeempty' => false,
                                                  'canedit'    => $createDowntime,
                                                  'mindate'    => $nowDate,
                                                  'mintime'    => $nowTime
                                            ));
      echo "</td>";

      echo "<td>".__('Flexible ?', 'monitoring')."</td>";
      echo "<td>";
      if ($createDowntime) {
         Dropdown::showYesNo('flexible', $this->fields['flexible']);
      } else {
         echo Dropdown::getYesNo($this->fields['flexible']);
      }
      echo "</td>";
      echo "</tr>";
      
      
      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('End time', 'monitoring')."</td>";
      echo "<td>";
      $date = $this->fields["end_time"];
      Html::showDateTimeField("end_time", array('value'      => $date,
                                                  'timestep'   => 10,
                                                  'maybeempty' => false,
                                                  'canedit'    => $createDowntime,
                                                  'mindate'    => $nowDate,
                                                  'mintime'    => $nowTime
                                            ));
      echo "</td>";

      echo "<td>".__('Duration', 'monitoring')."</td>";
      echo "<td>";
      if ($createDowntime) {
         Dropdown::showNumber("duration", array(
                   'value' => $this->fields['duration'], 
                   'min'   => 1,
                   'max'   => 300)
         );
      } else {
         echo $this->fields['duration'];
      }
      $a_duration_type = array();
      $a_duration_type['seconds'] = __('Second(s)', 'monitoring');
      $a_duration_type['minutes'] = __('Minute(s)', 'monitoring');
      $a_duration_type['hours']   = __('Hour(s)', 'monitoring');
      $a_duration_type['days']    = __('Day(s)', 'monitoring');

      if ($createDowntime) {
         Dropdown::showFromArray("duration_type",
                                 $a_duration_type,
                                 array('value'=>$this->fields['duration_type']));
      } else {
         echo "&nbsp;".$this->fields['duration_type'];
      }
      echo "</td>";
      echo "</tr>";
      
      
      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Comment', 'monitoring')."</td>";
      echo "<td >";
      if ($createDowntime) {
         echo "<textarea cols='80' rows='4' name='comment' >".$this->fields['comment']."</textarea>";
      } else {
         echo "<textarea cols='80' rows='4' name='comment' readonly='1' disabled='1' >".$this->fields['comment']."</textarea>";
      }
      echo "</td>";
      
      echo "</tr>";
      
      
      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('User', 'monitoring')."</td>";
      echo "<td>";
      echo "<input type='hidden' name='users_id' value='".$this->fields['users_id']."' />";
      echo $this->getUsername();
      echo "</td>";

      echo "<td>".__('Expired ?', 'monitoring')."</td>";
      echo "<td>";
      echo Dropdown::getYesNo($this->fields['expired']);
      echo "</td>";
      echo "</tr>";
         
      $this->showFormButtons();
      
      return true;
   }


   static function cronInfo($name){

      switch ($name) {
         case 'DowntimesExpired':
            return array (
               'description' => __('Update downtimes expiration','monitoring'));
            break;
      }
      return array();
   }

   static function cronDowntimesExpired() {
      global $DB;

      $query = "UPDATE `glpi_plugin_monitoring_downtimes` SET `expired` = '1' WHERE `expired` = '0' AND `end_time` < NOW();";
      Toolbox::logInFile("pm", "cronDowntimesExpired, query : $query\n");
      $DB->query($query);
      
      return true;
   }
}

?>