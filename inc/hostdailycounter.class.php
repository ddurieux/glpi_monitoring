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

class PluginMonitoringHostdailycounter extends CommonDBTM {

   static $managedCounters = array(
      'cPagesInitial' => array(
         'name'     => 'Initial counter for printed pages',
         'default'  => 'previous',
         'editable' => 0,
      ),
      'cPagesTotal'   => array(
         'name'     => 'Cumulative total for printed pages',
         'default'  => 'previous',
         'editable' => 0,
      ),
      'cPagesToday'   => array(
         'name'     => 'Daily printed pages',
         'default'  => 'reset',
         'editable' => 0,
      ),
      'cPagesRemaining' => array(
         'name'         => 'Remaining pages',
         'default'      => 'previous',
         'editable'     => 0,
         'lowThreshold' => 100,
      ),
      'cRetractedInitial' => array(
         'name'     => 'Initial counter for retracted pages',
         'default'  => 'previous',
         'editable' => 0,
      ),
      'cRetractedTotal' => array(
         'name'     => 'Cumulative total for retracted pages',
         'default'  => 'previous',
         'editable' => 0,
      ),
      'cRetractedToday' => array(
         'name'     => 'Daily retracted pages',
         'default'  => 'reset',
         'editable' => 0,
      ),
      'cRetractedRemaining' => array(
         'name'     => 'Stored retracted pages',
         'default'  => 'previous',
         'editable' => 0,
      ),
      'cPrinterChanged' => array(
         'name'     => 'Cumulative total for printer changed',
         'default'  => 'previous',
         'editable' => 1,
      ),
      'cPaperChanged' => array(
         'name'     => 'Cumulative total for paper changed',
         'default'  => 'previous',
         'editable' => 1,
      ),
      'cBinEmptied'   => array(
         'name'     => 'Cumulative total for bin emptied',
         'default'  => 'previous',
         'editable' => 1,
      ),
      'cPaperLoad'    => array(
         'name'     => 'Paper load',
         'default'  => 'previous',
         'editable' => 0,
      ),
      'cCardsInsertedOkToday' => array(
         'name'     => 'Daily cards inserted',
         'default'  => 'previous',
         'editable' => 0,
      ),
      'cCardsInsertedOkTotal' => array(
         'name'     => 'Cumulative total for cards inserted',
         'default'  => 'previous',
         'editable' => 0,
      ),
      'cCardsInsertedKoToday' => array(
         'name'     => 'Daily bad cards',
         'default'  => 'previous',
         'editable' => 0,
      ),
      'cCardsInsertedKoTotal' => array(
         'name'     => 'Cumultative total for bad cards',
         'default'  => 'previous',
         'editable' => 0,
      ),
      'cCardsRemovedToday' => array(
         'name'     => 'Daily removed cards',
         'default'  => 'previous',
         'editable' => 0,
      ),
      'cCardsRemovedTotal' => array(
         'name'     => 'Cumultative total for removed cards',
         'default'  => 'previous',
         'editable' => 0,
      ),
   );


   static function getTypeName($nb=0) {
      return __CLASS__;
   }


   static function canCreate() {
      return PluginMonitoringProfile::haveRight("counters", 'w');
   }


   static function canUpdate() {
      return PluginMonitoringProfile::haveRight("counters", 'w');
   }


   static function canView() {
      return PluginMonitoringProfile::haveRight("counters", 'r');
   }


   function defineTabs($options=array()){

      $ong = array();

      return $ong;
   }



   function getComments() {
   }


   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {
      if ($item->getType() == 'Computer'){
         return __('Daily counters', 'monitoring');
      }

      return '';
   }


   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {

      if ($item->getType()=='Computer') {
         if (self::canView()) {
            // Show list filtered on item, sorted on day descending ...
            Search::showList(PluginMonitoringHostdailycounter::getTypeName(), array(
               'field' => array(2), 'searchtype' => array('equals'), 'contains' => array($item->getID()),
               'sort' => 3, 'order' => 'DESC'
               ));
            return true;
         }
      }
      return true;
   }


   function getSearchOptions() {

      $tab = array();

      $tab['common'] = __('Host daily counters', 'monitoring');

      $tab[1]['table']           = $this->getTable();
      $tab[1]['field']           = 'id';
      $tab[1]['name']            = __('ID');
      $tab[1]['datatype']        = 'itemlink';
      // $tab[1]['massiveaction']   = false;

      $tab[2]['table']          = "glpi_computers";
      $tab[2]['field']          = 'name';
      $tab[2]['name']           = __('Computer');
      $tab[2]['datatype']       = 'itemlink';

      $tab[3]['table']           = $this->getTable();
      $tab[3]['field']           = 'day';
      $tab[3]['name']            = __('Day', 'monitoring');
      $tab[3]['datatype']        = 'date';
      $tab[3]['massiveaction']   = false;

      $i = 4;
      foreach (self::$managedCounters as $key => $value) {
         $tab[$i]['table']          = $this->getTable();
         $tab[$i]['field']          = $key;
         $tab[$i]['name']           = $value['name'];
         // $tab[$i]['massiveaction']  = false;
         $tab[$i]['datatype']       = 'specific';
         // $tab[$i]['datatype']       = 'number';
         $i++;
      }

      return $tab;
   }


   static function getSpecificValueToDisplay($field, $values, array $options=array()) {

      if (!is_array($values)) {
         $values = array($field => $values);
      }
      switch ($field) {
         case 'hostname':
            $computer = new Computer();
            $computer->getFromDBByQuery("WHERE `name` = '" . $values[$field] . "' LIMIT 1");
            return $computer->getLink();
            break;

         default:
            // Do not display zero values ...
            // Toolbox::logInFile("pm", "$field = ".$values[$field]."\n");
            if ($values[$field] == '0') {
               return '-';
            } else {
               return '<strong>'.$values[$field].'</strong>';
            }
            break;
      }
      return parent::getSpecificValueToDisplay($field, $values, $options);
   }


   /**
    * Set default content
    */
   function setDefaultContent($hostname, $date, $previousRecordExists=null) {
      // Toolbox::logInFile("pm", "daily counter, setDefaultContent : $hostname / $date : ".(($previousRecordExists)?1:0)."\n");
      $this->fields['hostname'] = $hostname;
      $this->fields['day']      = $date;

      foreach (self::$managedCounters as $key => $value) {
         if ($value['default'] == 'previous') {
            $this->fields[$key] = $previousRecordExists ? $previousRecordExists->fields[$key] : 0;
         } else if ($value['default'] == 'reset') {
            $this->fields[$key] = 0;
         } else {
            $this->fields[$key] = 0;
         }
      }
   }


   /**
   *
   *
   * @param $items_id integer ID
   * @param $options array
   *
   *@return bool true if form is ok
   *
   **/
   function showForm($items_id, $options=array()) {
      global $DB,$CFG_GLPI;

      if ($items_id!='') {
         $this->getFromDB($items_id);
      } else {
         $this->getEmpty();
      }

      $this->showFormHeader(array("colspan" => count($this->fields)-3));

      echo "<tr class='tab_bg_1'>";
      echo "<td colspan='2'><strong>".__('Host name', 'monitoring')."&nbsp;:&nbsp;";
      // Hidden field to inform that the update is made from the GUI ...
      echo "<input type='hidden' name='gui' value='1'/>";
      echo $this->getField('hostname')."</strong></td>";
      echo "<td colspan='2'><strong>".__('Day', 'monitoring')."&nbsp;:&nbsp;";
      echo Html::convDate($this->getField('day'))."</strong></td>";
      echo "</tr>";

      echo "<tr><td colspan='".count(self::$managedCounters)."'><hr/></td></tr>";

      echo "<tr class='tab_bg_1'>";
      foreach (self::$managedCounters as $key => $value) {
         echo "<td align='center'>".__($value['name'], 'monitoring')."</td>";
      }
      echo "</tr>";

      echo "<tr class='tab_bg_2'>";
      foreach (self::$managedCounters as $key => $value) {
//         if ($this->canUpdate() and $value['editable']) {
         if ($this->canUpdate()) {
            echo "<td><input type='text' name='$key' value='".$this->fields[$key]."' size='8'/></td>";
         } else {
            echo "<td>".$this->getValueToDisplay($key, $this->fields[$key])."</td>";
         }
      }
      echo "</tr>";

      // Display non editable next record ...
      $pmCounters = new PluginMonitoringHostdailycounter();
      $a_olderCounters = current($pmCounters->find("`hostname`='".$this->getField('hostname')."' AND `day` > DATE('".$this->getField('day')."') ORDER BY `day` ASC LIMIT 1"));
      if (isset($a_olderCounters['id'])) {
         echo "<tr><td colspan='100'></tr></tr>";
         echo "<tr><td colspan='100'>".__('Next day counters: ', 'monitoring')."</tr></tr>";
         echo "<tr class='tab_bg_2'>";
         foreach (self::$managedCounters as $key => $value) {
            echo "<td>".$pmCounters->getValueToDisplay($key, $a_olderCounters[$key])."</td>";
         }
         echo "</tr>";
      }

      // Display non editable previous record ...
      $pmCounters = new PluginMonitoringHostdailycounter();
      $a_olderCounters = current($pmCounters->find("`hostname`='".$this->getField('hostname')."' AND `day` < DATE('".$this->getField('day')."') ORDER BY `day` DESC LIMIT 1"));
      if (isset($a_olderCounters['id'])) {
         echo "<tr><td colspan='100'></tr></tr>";
         echo "<tr><td colspan='100'>".__('Previous day counters: ', 'monitoring')."</tr></tr>";
         echo "<tr class='tab_bg_2'>";
         foreach (self::$managedCounters as $key => $value) {
            echo "<td>".$pmCounters->getValueToDisplay($key, $a_olderCounters[$key])."</td>";
         }
         echo "</tr>";
      }

      $this->showFormButtons(array("colspan" => count($this->fields)-3));

      return true;
   }


   static function cronInfo($name){

      switch ($name) {
         case 'DailyCounters':
            return array (
               'description' => __('Update daily counters','monitoring'));
            break;
      }
      return array();
   }

   static function cronDailyCounters($task=NULL) {

      PluginMonitoringHostdailycounter::runAddDays();

      return true;
   }

   /*
      Fred -> David : this function to check is counters are valid :
      - should be scheduled in cron
      - should be used to run prediction function
   */
   static function runCheckCounters($date='', $hostname='%', $interval=-1) {
      global $DB;

      if ($date == '') $date = date('Y-m-d H:i:s');

      Toolbox::logInFile("pm-checkCounters", "Check daily counters for '$hostname' up to $date, interval : $interval days\n");

      $pmCounters          = new PluginMonitoringHostdailycounter();
      $pmCurrentCounter    = new PluginMonitoringHostdailycounter();
      $pmPreviousCounter   = new PluginMonitoringHostdailycounter();

      if ($interval == -1) {
         $a_checkables = $pmCounters->find ("`hostname` LIKE '$hostname' AND `day` < date('$date')", "`hostname` ASC, `day` ASC");
      } else {
         $a_checkables = $pmCounters->find ("`hostname` LIKE '$hostname' AND `day` BETWEEN DATE_SUB('$date', INTERVAL $interval DAY) AND date('$date')", "`hostname` ASC, `day` ASC");
      }

      $negativeCountersHosts = array();
      foreach ($a_checkables as $checkable) {
         // echo "Checking negative daily counters for '".$checkable['hostname']."', day : ".$checkable['day']." ...<br/>";

         // What is checked ...
         foreach (self::$managedCounters as $key => $value) {
            // Toolbox::logInFile("pm-checkCounters", "Counter '$key' for '".$checkable['hostname']."', day : ".$checkable['day']."\n");
            if ($checkable[$key] < 0) {
               if (! isset($negativeCountersHosts[$checkable['hostname']])) {
                  $negativeCountersHosts[$checkable['hostname']] = array();
               }
               $negativeCountersHosts[$checkable['hostname']][$key] = $value;
               Toolbox::logInFile("pm-checkCounters", "Counter '$key' for '".$checkable['hostname']."', day : ".$checkable['day']." is negative !\n");
               // echo "Counter '$key' for '".$checkable['hostname']."', day : ".$checkable['day']." is negative !<br/>";
            }
         }
      }
      foreach ($negativeCountersHosts as $hostname => $negativeCounters) {
         foreach ($negativeCounters as $counter => $value) {
            Toolbox::logInFile("pm-checkCounters", "Host should be checked for $hostname : $counter\n");
            echo "Host $hostname should be checked for negative counters : $counter<br/>";
         }
      }

      $remainingPagesHosts = array();
      foreach ($a_checkables as $checkable) {
         // echo "Checking remaining pages daily counters for '".$checkable['hostname']."', day : ".$checkable['day']." ...<br/>";

         // What is checked ...
         foreach (self::$managedCounters as $key => $value) {
            if (! isset(self::$managedCounters[$key]['lowThreshold'])) continue;

            // Toolbox::logInFile("pm-checkCounters", "Counter '$key' for '".$checkable['hostname']."', day : ".$checkable['day']."\n");
            if ($checkable[$key] < self::$managedCounters[$key]['lowThreshold']) {
               if (! isset($remainingPagesHosts[$checkable['hostname']])) {
                  $remainingPagesHosts[$checkable['hostname']] = array();
               }
               $remainingPagesHosts[$checkable['hostname']][$key] = $value;
               Toolbox::logInFile("pm-checkCounters", "Counter '$key' for '".$checkable['hostname']."', day : ".$checkable['day']." is low !\n");
               echo "Counter '$key' for '".$checkable['hostname']."', day : ".$checkable['day']." is low !<br/>";
            }
         }
      }
   }



   // ************** update function David ************//
   static function runAddDays() {

      ini_set("max_execution_time", "0");

      $pmServices               = new PluginMonitoringService();
      $computer                 = new Computer();
      $pmComponentscatalog_Host = new PluginMonitoringComponentscatalog_Host();
      $pmServiceevent           = new PluginMonitoringServiceevent();

      $daysnameidx = Toolbox::getDaysOfWeekArray();

	  // Card reader counters
      $a_services = $pmServices->find("`name`='nsca_reader' OR `name`='Lecteur de cartes'");
      foreach ($a_services as $a_service) {
         $services_id = $a_service['id'];
         // Simply testing on one service ...
         // if($services_id != 3701) {
            // continue;
         // }

         $hostname = '';
         $pmComponentscatalog_Host->getFromDB($a_service['plugin_monitoring_componentscatalogs_hosts_id']);
         $computer->getFromDB($pmComponentscatalog_Host->fields['items_id']);
         $hostname = $computer->fields['name'];
         
         Toolbox::logInFile("pm-counters", "Service nsca_reader/$hostname : $services_id\n");
         
         $self = new self();
         $a_counters = current($self->find('`plugin_monitoring_services_id2`="'.$services_id.'"', '`id` DESC', 1));
         if (! isset($a_counters['id'])) {
            // First host daily counters ...
            $input = array();
            
            // Id of the card service ...
            $input['plugin_monitoring_services_id2'] = $services_id;
            // get first serviceevents
            $first = current($pmServiceevent->find("`plugin_monitoring_services_id`='".$services_id."'", '`id` ASC', 1));
            if (!isset($first['id'])) {
               continue;
            } else {
               $splitdate = explode(' ', $first['date']);
               $input['day'] = $splitdate[0];
            }
            Toolbox::logInFile("pm-counters", "Service nsca_reader : $services_id, day: ".$input['day']."\n");
            // Fetch perfdata of 1st event in day to update cPagesInitial and cRetractedInitial ...
            $a_first = $self->getFirstValues($services_id, $input['day']);
            $input['hostname']            = $hostname;
            $a = strptime($input['day'], '%Y-%m-%d');
            $timestamp = mktime(0, 0, 0, $a['tm_mon']+1, $a['tm_mday'], $a['tm_year']+1900);
            $input['dayname']             = $daysnameidx[date('w', $timestamp)];

            // fetch perfdata of last event in day to update counters ...
            $a_last = $self->getLastValues($services_id, $input['day']);

            if (count($a_first) == 0) {
               // Set null values ...
               $input['cCardsInsertedOkTotal']	= 0;
               $input['cCardsInsertedOkToday']  = 0;
               $input['cCardsInsertedKoTotal']	= 0;
               $input['cCardsInsertedKoToday']  = 0;
               $input['cCardsRemovedTotal']     = 0;
               $input['cCardsRemovedToday']     = 0;
            } else {
               // compute daily values thanks to first and last day values.
               // 'Powered Cards'=2339c 'Mute Cards'=89c 'Cards Removed'=2428c
               $input['cCardsInsertedOkTotal']	= $a_last['Powered Cards'];
               $input['cCardsInsertedOkToday']  = $a_last['Powered Cards'] - $a_first['Powered Cards'];
               $input['cCardsInsertedKoTotal']	= $a_last['Mute Cards'];
               $input['cCardsInsertedKoToday']  = $a_last['Mute Cards'] - $a_first['Mute Cards'];
               $input['cCardsRemovedTotal']     = $a_last['Cards Removed'];
               $input['cCardsRemovedToday']     = $a_last['Cards Removed'] - $a_first['Cards Removed'];
            }
            $tmpid = $self->add($input);
            $a_counters = $input;
         }

         // Here it exists, at min, one host daily counters line ... and a_counters is the last known counters.
         $prev = $a_counters;
         for ($i = (strtotime($a_counters['day']) + 86400); $i < strtotime(date('Y-m-d').' 00:00:00'); $i += 86400) {
            // Fetch perfdata of 1st event in day to update cPagesInitial and cRetractedInitial ...
            $a_first = $self->getFirstValues($services_id, date('Y-m-d', $i));

            // fetch perfdata of last event in day to update counters ...
            $a_last = $self->getLastValues($services_id, date('Y-m-d', $i));

            $input = array();
            
            // Do not update services_id but services_id2 ... beurk !!!
            $input['plugin_monitoring_services_id2'] = $services_id;
            $input['day']                       = date('Y-m-d', $i);
            $input['dayname']                   = $daysnameidx[date('w', $i)];
            $input['hostname']                  = $hostname;

            Toolbox::logInFile("pm-counters", "Counters ".$input['day']." for ".$input['hostname']." ($services_id)\n");

            if (count($a_first) == 0) {
               // Set null values ...
               $input['cCardsInsertedOkTotal']	= $prev['cCardsInsertedOkTotal'];
               $input['cCardsInsertedOkToday']  = 0;
               $input['cCardsInsertedKoTotal']	= $prev['cCardsInsertedKoTotal'];
               $input['cCardsInsertedKoToday']  = 0;
               $input['cCardsRemovedTotal']     = $prev['cCardsRemovedTotal'];
               $input['cCardsRemovedToday']     = 0;
            } else {
               // compute daily values thanks to first and last day values.
               // 'Powered Cards'=2339c 'Mute Cards'=89c 'Cards Removed'=2428c
               $input['cCardsInsertedOkTotal']	= $a_last['Powered Cards'];
               $input['cCardsInsertedOkToday']  = $a_last['Powered Cards'] - $a_first['Powered Cards'];
               $input['cCardsInsertedKoTotal']	= $a_last['Mute Cards'];
               $input['cCardsInsertedKoToday']  = $a_last['Mute Cards'] - $a_first['Mute Cards'];
               $input['cCardsRemovedTotal']     = $a_last['Cards Removed'];
               $input['cCardsRemovedToday']     = $a_last['Cards Removed'] - $a_first['Cards Removed'];
            }
            
            $self->add($input);
            $prev = $input;
         }
      }

	  // Card reader counters
      $a_services = $pmServices->find("`name`='nsca_printer' OR `name`='Imprimante'");
      foreach ($a_services as $a_service) {
         $services_id = $a_service['id'];
         // Simply testing on one service ...
         // if($services_id != 3699) {
            // continue;
         // }
         
         $hostname = '';
         $pmComponentscatalog_Host->getFromDB($a_service['plugin_monitoring_componentscatalogs_hosts_id']);
         $computer->getFromDB($pmComponentscatalog_Host->fields['items_id']);
         $hostname = $computer->fields['name'];
         
         Toolbox::logInFile("pm-counters", "Service nsca_printer/$hostname : $services_id\n");
         
         $self = new self();
         $a_counters = current($self->find('`plugin_monitoring_services_id`="'.$services_id.'"', '`id` DESC', 1));
         if (!isset($a_counters['id'])) {
            $input = array();
            
            // get first serviceevents
            $first = current($pmServiceevent->find("`plugin_monitoring_services_id`='".$services_id."'", '`id` ASC', 1));
            if (!isset($first['id'])) {
               continue;
            } else {
               $splitdate = explode(' ', $first['date']);
               $input['day'] = $splitdate[0];
            }
            
            $a_counters = current($self->find('`hostname`="'.$hostname.'"'
            . ' AND `day`="'.$input['day'].'"', '`id` DESC', 1));
            if (isset($a_counters['id'])) {
               Toolbox::logInFile("pm-counters", "Day : ".$a_counters['day']." still exists for $hostname\n");
               $input = $a_counters;
            }
            // First host daily counters ...
            $input['plugin_monitoring_services_id'] = $services_id;
            // Fetch perfdata of 1st event in day to update cPagesInitial and cRetractedInitial ...
            $a_first = $self->getFirstValues($services_id, $input['day']);
            if (count($a_first) == 0) {
               continue;
            }
            $input['hostname']            = $hostname;
            $a = strptime($input['day'], '%Y-%m-%d');
            $timestamp = mktime(0, 0, 0, $a['tm_mon']+1, $a['tm_mday'], $a['tm_year']+1900);
            $input['dayname']             = $daysnameidx[date('w', $timestamp)];
            $input['cRetractedInitial']   = $a_first['Retracted Pages'];
            $input['cPagesInitial']       = $a_first['Cut Pages'];
            // set up initial paper load ...
            $input['cPaperLoad']          = 2000;
            $input['cPaperChanged']       = 0;
            // set up printer changed and bin emptied counters ...
            $input['cPrinterChanged']     = 0;
            $input['cBinEmptied']         = 0;

            // fetch perfdata of last event in day to update cPagesInitial and cRetractedInitial ...
            $a_last = $self->getLastValues($services_id, $input['day']);

            // compute daily values thanks to first and last day values.
            $input['cRetractedTotal']     = $a_last['Retracted Pages'] - $a_first['Retracted Pages'];
            $input['cRetractedToday']     = $input['cRetractedTotal'];
            $input['cPagesTotal']         = $a_last['Cut Pages'] - $a_first['Cut Pages'];
            $input['cPagesToday']         = $input['cPagesTotal'];
            $input['cPagesRemaining']     = $input['cPaperLoad'] - $input['cPagesToday'];
            $input['cRetractedRemaining'] = $input['cRetractedToday'];

            if (isset($a_counters['id'])) {
               $tmpid = $self->update($input);
            } else {
               $tmpid = $self->add($input);
            }
            $a_counters = $input;
         }

         // Here it exists, at min, one host daily counters line ... and a_counters is the last known counters.
         $prev = $a_counters;
         unset($prev['id']);
         $a_cntprev = array();
         for ($i = (strtotime($a_counters['day']) + 86400); $i < strtotime(date('Y-m-d').' 00:00:00'); $i += 86400) {
            $input = array();
            
            // Fetch perfdata of 1st event in day to update cPagesInitial and cRetractedInitial ...
            $a_first = $self->getFirstValues($services_id, date('Y-m-d', $i));

            // Fetch perfdata of last event in day to update cPagesInitial and cRetractedInitial ...
            $a_cnt = $self->getLastValues($services_id, date('Y-m-d', $i));

            $input['day']                 = date('Y-m-d', $i);
            $input['dayname']             = $daysnameidx[date('w', $i)];
            $input['hostname']            = $hostname;

            $a_counters = current($self->find('`hostname`="'.$hostname.'"'
            . ' AND `day`="'.$input['day'].'"', '`id` DESC', 1));
            if (isset($a_counters['id'])) {
               Toolbox::logInFile("pm-counters", "Day : ".$a_counters['day']." still exists for $hostname\n");
               $input = $a_counters;
            }
            $input['plugin_monitoring_services_id'] = $services_id;
            
            Toolbox::logInFile("pm-counters", "Counters ".$input['day']." for ".$input['hostname']." ($services_id)\n");
            
            // Keep previous day values
            $input['cPaperLoad'] = $prev['cPaperLoad'];
            $input['cPaperChanged'] = $prev['cPaperChanged'];
            $input['cPagesInitial'] = $prev['cPagesInitial'];
            $input['cRetractedInitial'] = $prev['cRetractedInitial'];

            // Detect if bin was emptied today
            $binEmptiedToday = false;
            // Keep previous day values
            $input['cRetractedRemaining'] = $prev['cRetractedRemaining'];
            $input['cBinEmptied'] = $prev['cBinEmptied'];
            if ($a_cnt['Trash Empty'] > $prev['cBinEmptied']) {
               // No more paper in bin if bin is emptied ...
               $input['cRetractedRemaining'] = 0;
               $input['cBinEmptied'] = $a_cnt['Trash Empty'];
               $binEmptiedToday = true;
            }

            // Detect if printer was changed today
            $printerChangedToday = false;
            // Keep previous day values
            $input['cPrinterChanged'] = $prev['cPrinterChanged'];
            /* Detection :
               - changed printer counter increased
               - cut pages lower then previous value
               - retracted pages lower then previous value
            */
/*
            if ($a_cnt['Printer Replace'] > $prev['cPrinterChanged']
                  || $a_cnt['Cut Pages'] < $prev['cPagesTotal']
                  || $a_cnt['Retracted Pages'] < $prev['cRetractedTotal']) {
*/
            if ($a_cnt['Printer Replace'] > $prev['cPrinterChanged']
                  || $a_cnt['Cut Pages'] < $a_first['Cut Pages']
                  || $a_cnt['Retracted Pages'] < $a_first['Retracted Pages']) {

               // getPrinterChanged
               $retpages = $self->getPrinterChanged($services_id, date('Y-m-d', $i).' 00:00:00', date('Y-m-d', $i).' 23:59:59', $prev['cPrinterChanged']);
               $input['cPagesToday'] = $retpages[0]['Cut Pages'] + $retpages[1]['Cut Pages'];
               $input['cPagesTotal'] = $prev['cPagesTotal'] + $input['cPagesToday'];
               $input['cRetractedToday'] = $retpages[0]['Retracted Pages'] + $retpages[1]['Retracted Pages'];
               $input['cRetractedTotal'] = $prev['cRetractedTotal'] + $input['cRetractedTotal'];

               $input['cPrinterChanged'] = $a_cnt['Printer Replace'];
               // if ($input['cPrinterChanged'] == $prev['cPrinterChanged']) {
                  // $input['cPrinterChanged'] = '-10';
               // }
               $input['cPagesInitial'] = $retpages[2];
               $input['cRetractedInitial'] = $retpages[3];

               $input['cPagesRemaining'] = $input['cPaperLoad'] - $input['cPagesTotal'];
               $input['cRetractedRemaining'] += $input['cRetractedToday'];
               $printerChangedToday = true;
            } else {
               // When printer has not been changed :
               // 1/ Compute daily values thanks to first and last day values.
               $input['cPagesToday']         = $a_cnt['Cut Pages'] - $a_first['Cut Pages'];
               $input['cRetractedToday']     = $a_cnt['Retracted Pages'] - $a_first['Retracted Pages'];
               // 2/ Increase total values from previous day with daily values
               $input['cRetractedTotal']     = $prev['cRetractedTotal'] + $input['cRetractedToday'];
               $input['cPagesTotal']         = $prev['cPagesTotal'] + $input['cPagesToday'];
               // 3/ Compute remaining pages as total paper load - total printed pages
               $input['cPagesRemaining']     = $prev['cPagesRemaining'] - $input['cPagesToday'];
               // 4/ Compute remaining pages as total paper load - total printed pages
               $input['cRetractedRemaining'] += $input['cRetractedToday'];

               // Detect if paper was changed today
               if ($a_cnt['Paper Reams'] > $prev['cPaperChanged']) {
                  // getPaperChanged
                  $retpages = $self->getPaperChanged($services_id, date('Y-m-d', $i).' 00:00:00', date('Y-m-d', $i).' 23:59:59', $prev['cPaperChanged']);
                  $input['cPagesToday'] = $retpages[0] + $retpages[1];
                  $input['cRetractedToday'] = $retpages[2] + $retpages[3];
                  // Reset remaining pages with default paper ream load
                  $input['cPagesRemaining'] = 2000 - $retpages[1];
                  // Compute total paper load
                  $input['cPaperLoad'] = ($a_cnt['Paper Reams'] + 1) * 2000;
                  $input['cPaperChanged'] = $a_cnt['Paper Reams'];
               }
            }

            if (isset($a_counters['id'])) {
               $self->update($input);
            } else {
               $self->add($input);
            }

            $prev = $input;
            $a_cntprev = $a_cnt;
         }
         // Manage counter of today (REQUIRE refactoring)
         $yesterday = strtotime(date('Y-m-d').' 00:00:00') - 5000;
         $a_counters = current($self->find('`plugin_monitoring_services_id`="'.$services_id.'"'
                 . ' AND `day`="'.date('Y-m-d', $yesterday).'"', '`id` DESC', 1));
         $a_counters_today = current($self->find('`plugin_monitoring_services_id`="'.$services_id.'"'
                 . ' AND `day`="'.date('Y-m-d').'"', '`id` DESC', 1));
         if (isset($a_counters['id'])) {
            $prev = $a_counters;
            $a_first = $self->getFirstValues($services_id, date('Y-m-d'));
            if (count($a_first) == 0) {
               continue;
            }

            // Fetch perfdata of last event in day to update cPagesInitial and cRetractedInitial ...
            $a_cnt = $self->getLastValues($services_id, date('Y-m-d'));

            $input = array();
            $input['plugin_monitoring_services_id'] = $services_id;
            $input['day']                 = date('Y-m-d');
            $input['dayname']             = $daysnameidx[date('w')];
            $input['hostname']            = $hostname;


            // Keep previous day values
            $input['cPaperLoad'] = $prev['cPaperLoad'];
            $input['cPaperChanged'] = $prev['cPaperChanged'];
            $input['cPagesInitial'] = $prev['cPagesInitial'];
            $input['cRetractedInitial'] = $prev['cRetractedInitial'];

            // Detect if bin was emptied today
            $binEmptiedToday = false;
            // Keep previous day values
            $input['cRetractedRemaining'] = $prev['cRetractedRemaining'];
            $input['cBinEmptied'] = $prev['cBinEmptied'];
            if ($a_cnt['Trash Empty'] > $prev['cBinEmptied']) {
               // No more paper in bin if bin is emptied ...
               $input['cRetractedRemaining'] = 0;
               $input['cBinEmptied'] = $a_cnt['Trash Empty'];
               $binEmptiedToday = true;
            }

            // Detect if printer was changed today
            $printerChangedToday = false;
            // Keep previous day values
            $input['cPrinterChanged'] = $prev['cPrinterChanged'];
            /* Detection :
               - changed printer counter increased
               - cut pages lower then previous value
               - retracted pages lower then previous value
            */
/*
            if ($a_cnt['Printer Replace'] > $prev['cPrinterChanged']
                  || $a_cnt['Cut Pages'] < $prev['cPagesTotal']
                  || $a_cnt['Retracted Pages'] < $prev['cRetractedTotal']) {
*/
            if ($a_cnt['Printer Replace'] > $prev['cPrinterChanged']
                  || $a_cnt['Cut Pages'] < $a_first['Cut Pages']
                  || $a_cnt['Retracted Pages'] < $a_first['Retracted Pages']) {

               // getPrinterChanged
               $retpages = $self->getPrinterChanged($services_id, date('Y-m-d', $i).' 00:00:00', date('Y-m-d', $i).' 23:59:59', $prev['cPrinterChanged']);
               $input['cPagesToday'] = $retpages[0]['Cut Pages'] + $retpages[1]['Cut Pages'];
               $input['cPagesTotal'] = $prev['cPagesTotal'] + $input['cPagesToday'];
               $input['cRetractedToday'] = $retpages[0]['Retracted Pages'] + $retpages[1]['Retracted Pages'];
               $input['cRetractedTotal'] = $prev['cRetractedTotal'] + $input['cRetractedTotal'];

               $input['cPrinterChanged'] = $a_cnt['Printer Replace'];
               // if ($input['cPrinterChanged'] == $prev['cPrinterChanged']) {
                  // $input['cPrinterChanged'] = '-10';
               // }
               $input['cPagesInitial'] = $retpages[2];
               $input['cRetractedInitial'] = $retpages[3];

               $input['cPagesRemaining'] = $input['cPaperLoad'] - $input['cPagesTotal'];
               $input['cRetractedRemaining'] += $input['cRetractedToday'];
               $printerChangedToday = true;
            } else {
               // When printer has not been changed :
               // 1/ Compute daily values thanks to first and last day values.
               $input['cPagesToday']         = $a_cnt['Cut Pages'] - $a_first['Cut Pages'];
               $input['cRetractedToday']     = $a_cnt['Retracted Pages'] - $a_first['Retracted Pages'];
               // 2/ Increase total values from previous day with daily values
               $input['cRetractedTotal']     = $prev['cRetractedTotal'] + $input['cRetractedToday'];
               $input['cPagesTotal']         = $prev['cPagesTotal'] + $input['cPagesToday'];
               // 3/ Compute remaining pages as total paper load - total printed pages
               $input['cPagesRemaining']     = $prev['cPagesRemaining'] - $input['cPagesToday'];
               // 4/ Compute remaining pages as total paper load - total printed pages
               $input['cRetractedRemaining'] += $input['cRetractedToday'];

               // Detect if paper was changed today
               if ($a_cnt['Paper Reams'] > $prev['cPaperChanged']) {
                  // getPaperChanged
                  $retpages = $self->getPaperChanged($services_id, date('Y-m-d', $i).' 00:00:00', date('Y-m-d', $i).' 23:59:59', $prev['cPaperChanged']);
                  $input['cPagesToday'] = $retpages[0] + $retpages[1];
                  $input['cRetractedToday'] = $retpages[2] + $retpages[3];
                  // Reset remaining pages with default paper ream load
                  $input['cPagesRemaining'] = 2000 - $retpages[1];
                  // Compute total paper load
                  $input['cPaperLoad'] = ($a_cnt['Paper Reams'] + 1) * 2000;
                  $input['cPaperChanged'] = $a_cnt['Paper Reams'];
               }
            }
            if (isset($a_counters_today['id'])) {
               $input['id'] = $a_counters_today['id'];
               $self->update($input);
            } else {
               $self->add($input);
            }
         }
      }
   }



   function getFirstValues($services_id, $date) {
      global $DB;

      $pmService        = new PluginMonitoringService();
      $pmServiceevent   = new PluginMonitoringServiceevent();
      $pmComponent      = new PluginMonitoringComponent();

      $data2 = array();

      $pmService->getFromDB($services_id);
      $_SESSION['plugin_monitoring_checkinterval'] = 86400;
      $pmComponent->getFromDB($pmService->fields['plugin_monitoring_components_id']);

      $query = "SELECT
           id,
           perf_data,
           date
         FROM
           glpi_plugin_monitoring_serviceevents
             JOIN
               (SELECT MIN(glpi_plugin_monitoring_serviceevents.id) AS min
                FROM glpi_plugin_monitoring_serviceevents
                WHERE `plugin_monitoring_services_id` = '".$services_id."'
                   AND `glpi_plugin_monitoring_serviceevents`.`state` = 'OK'
                   AND `glpi_plugin_monitoring_serviceevents`.`perf_data` != ''
                   AND `glpi_plugin_monitoring_serviceevents`.`date` >= '".$date." 00:00:00'
                   AND `glpi_plugin_monitoring_serviceevents`.`date` <= '".$date." 23:59:59'
                   AND `event` LIKE 'Online%'

                ORDER BY glpi_plugin_monitoring_serviceevents.`date` ASC) min_id ON
              (min_id.min = id)";

      $resultevent = $DB->query($query);
      if ($DB->numrows($resultevent) == 0) {
         $query = "SELECT
              id,
              perf_data,
              date
            FROM
              glpi_plugin_monitoring_serviceevents
                JOIN
                  (SELECT MIN(glpi_plugin_monitoring_serviceevents.id) AS min
                   FROM glpi_plugin_monitoring_serviceevents
                   WHERE `plugin_monitoring_services_id` = '".$services_id."'
                      AND `glpi_plugin_monitoring_serviceevents`.`state` = 'OK'
                      AND `glpi_plugin_monitoring_serviceevents`.`perf_data` != ''
                      AND `glpi_plugin_monitoring_serviceevents`.`date` >= '".$date." 00:00:00'
                      AND `glpi_plugin_monitoring_serviceevents`.`date` <= '".$date." 23:59:59'

                   ORDER BY glpi_plugin_monitoring_serviceevents.`date` ASC) min_id ON
                 (min_id.min = id)";

         $resultevent = $DB->query($query);
      }


      while ($dataevent=$DB->fetch_array($resultevent)) {
         $ret = $pmServiceevent->getData(
                 array($dataevent),
                 $pmComponent->fields['graph_template'],
                 $dataevent['date'],
                 $dataevent['date']);
         foreach ($ret[4] as $perfname=>$legendname) {
            $data2[$perfname] = $ret[0][$legendname][0];
         }
      }
      return $data2;
   }



   function getLastValues($services_id, $date) {
      global $DB;

      $pmService        = new PluginMonitoringService();
      $pmServiceevent   = new PluginMonitoringServiceevent();
      $pmComponent      = new PluginMonitoringComponent();

      $data2 = array();

      $pmService->getFromDB($services_id);
      $_SESSION['plugin_monitoring_checkinterval'] = 86400;
      $pmComponent->getFromDB($pmService->fields['plugin_monitoring_components_id']);

      $query = "SELECT
           id,
           perf_data,
           date
         FROM
           glpi_plugin_monitoring_serviceevents
             JOIN
               (SELECT MAX(glpi_plugin_monitoring_serviceevents.id) AS max
                FROM glpi_plugin_monitoring_serviceevents
                WHERE `plugin_monitoring_services_id` = '".$services_id."'
                   AND `glpi_plugin_monitoring_serviceevents`.`state` = 'OK'
                   AND `glpi_plugin_monitoring_serviceevents`.`perf_data` != ''
                   AND `glpi_plugin_monitoring_serviceevents`.`date` >= '".$date." 00:00:00'
                   AND `glpi_plugin_monitoring_serviceevents`.`date` <= '".$date." 23:59:59'
                   AND `event` LIKE 'Online%'

                ORDER BY glpi_plugin_monitoring_serviceevents.`date` DESC) max_id ON
              (max_id.max = id)";

      $resultevent = $DB->query($query);
      if ($DB->numrows($resultevent) == 0) {
         $query = "SELECT
              id,
              perf_data,
              date
            FROM
              glpi_plugin_monitoring_serviceevents
                JOIN
                  (SELECT MAX(glpi_plugin_monitoring_serviceevents.id) AS max
                   FROM glpi_plugin_monitoring_serviceevents
                   WHERE `plugin_monitoring_services_id` = '".$services_id."'
                      AND `glpi_plugin_monitoring_serviceevents`.`state` = 'OK'
                      AND `glpi_plugin_monitoring_serviceevents`.`perf_data` != ''
                      AND `glpi_plugin_monitoring_serviceevents`.`date` >= '".$date." 00:00:00'
                      AND `glpi_plugin_monitoring_serviceevents`.`date` <= '".$date." 23:59:59'

                   ORDER BY glpi_plugin_monitoring_serviceevents.`date` DESC) max_id ON
                 (max_id.max = id)";

         $resultevent = $DB->query($query);
      }


      while ($dataevent=$DB->fetch_array($resultevent)) {
         $ret = $pmServiceevent->getData(
                 array($dataevent),
                 $pmComponent->fields['graph_template'],
                 $dataevent['date'],
                 $dataevent['date']);
         foreach ($ret[4] as $perfname=>$legendname) {
            $data2[$perfname] = $ret[0][$legendname][0];
         }
      }
      return $data2;
   }



   function getPaperChanged($services_id, $date_start, $date_end, $cnt_paperchanged) {
      global $DB;

      // get all data of this day
      $pmService        = new PluginMonitoringService();
      $pmServiceevent   = new PluginMonitoringServiceevent();
      $pmComponent      = new PluginMonitoringComponent();

      $pmService->getFromDB($services_id);
      $_SESSION['plugin_monitoring_checkinterval'] = 86400;
      $pmComponent->getFromDB($pmService->fields['plugin_monitoring_components_id']);
      $query = "SELECT * FROM `glpi_plugin_monitoring_serviceevents`
         WHERE `plugin_monitoring_services_id`='".$services_id."'
            AND `date` >= '".$date_start."'
            AND `date` <= '".$date_end."'
            AND `glpi_plugin_monitoring_serviceevents`.`state` = 'OK'
            AND `glpi_plugin_monitoring_serviceevents`.`perf_data` != ''
            AND `event` LIKE 'Online%'
         ORDER BY `date`";

      $resultevent = $DB->query($query);

      $ret = $pmServiceevent->getData(
              $resultevent,
              $pmComponent->fields['graph_template'],
              $date_start,
              $date_end);

      $word = '';
      foreach ($ret[4] as $perfname=>$legendname) {
         if ($perfname == 'Paper Reams') {
            $word = $legendname;
            break;
         }
      }
      $cnt_first    = -1;
      $val_atchange = 0;
      $cnt_atchange = 0;
      $val_end      = 0;
      $cnt_end      = 0;
      foreach ($ret[0][$word] as $num=>$val) {
         if ($cnt_first < 0) {
            $cnt_first = $num;
         }
         if ($val > $cnt_paperchanged) {
            if ($val_atchange == 0) {
               $val_atchange = $val;
               $cnt_atchange = $num;
            } else {
               $val_end = $val;
               $cnt_end = $num;
            }
         }
      }

      $pagesBefore = $ret[0][$ret[4]['Cut Pages']][$cnt_atchange] - $ret[0][$ret[4]['Cut Pages']][$cnt_first];
      $pagesAfter  = $ret[0][$ret[4]['Cut Pages']][$cnt_end] - $ret[0][$ret[4]['Cut Pages']][$cnt_atchange];
      $retractedBefore = $ret[0][$ret[4]['Retracted Pages']][$cnt_atchange] - $ret[0][$ret[4]['Retracted Pages']][$cnt_first];
      $retractedAfter  = $ret[0][$ret[4]['Retracted Pages']][$cnt_end] - $ret[0][$ret[4]['Retracted Pages']][$cnt_atchange];

      return array($pagesBefore, $pagesAfter, $retractedBefore, $retractedAfter);
   }



   // TODO : to finish
   function getPrinterChanged($services_id, $date_start, $date_end, $cnt_printerchanged) {
      global $DB;

      // get all data of this day
      $pmService        = new PluginMonitoringService();
      $pmServiceevent   = new PluginMonitoringServiceevent();
      $pmComponent      = new PluginMonitoringComponent();

      $pmService->getFromDB($services_id);
      $_SESSION['plugin_monitoring_checkinterval'] = 86400;
      $pmComponent->getFromDB($pmService->fields['plugin_monitoring_components_id']);
      $query = "SELECT * FROM `glpi_plugin_monitoring_serviceevents`
         WHERE `plugin_monitoring_services_id`='".$services_id."'
            AND `date` >= '".$date_start."'
            AND `date` <= '".$date_end."'
            AND `glpi_plugin_monitoring_serviceevents`.`state` = 'OK'
            AND `glpi_plugin_monitoring_serviceevents`.`perf_data` != ''
            AND `event` LIKE 'Online%'
         ORDER BY `date`";

      $resultevent = $DB->query($query);

      $ret = $pmServiceevent->getData(
              $resultevent,
              $pmComponent->fields['graph_template'],
              $date_start,
              $date_end);

      $a_word = array();
      foreach ($ret[4] as $perfname=>$legendname) {
         if ($perfname == 'Cut Pages') {
            $a_word['cut'] = $legendname;
         } else if ($perfname == 'Printer Replace') {
            $a_word['replace'] = $legendname;
            break;
         } else if ($perfname == 'Retracted Pages') {
            $a_word['retract'] = $legendname;
            break;
         }
      }

      $replace_num = -1;
      foreach ($a_word as $name=>$word) {
         $prev = -100000;
         foreach ($ret[0][$word] as $num=>$val) {
            if ($name == 'replace') {
               if ($val > $cnt_printerchanged) {
                  $replace_num = $num;
                  break 2;
               }
            } else {
               if ($val < $prev) {
                  $replace_num = $num;
                  break 2;
               }
            }
            $prev = $val;
         }
      }

      // Now we have number of change
      $a_before = array();
      $a_after  = array();

      if (!isset($a_word['cut'])) {
         $a_before['Cut Pages'] = 0;
         $a_after['Cut Pages']  = 0;
      } else {
         $keys = array_keys($ret[0][$a_word['cut']]);
         $numFirstCut = array_shift($keys);
         $numEndCut   = array_pop($keys);
         if ($numFirstCut == ''
                 || $numEndCut == '') {
            $a_before['Cut Pages'] = 0;
            $a_after['Cut Pages']  = 0;
         } else if (!isset($ret[0][$a_word['cut']][$replace_num])) {
            $a_before['Cut Pages'] = $ret[0][$a_word['cut']][$numEndCut] - $ret[0][$a_word['cut']][$numFirstCut];
            $a_after['Cut Pages']  = $ret[0][$a_word['cut']][$numEndCut] - $ret[0][$a_word['cut']][$numEndCut];
         } else {
            $a_before['Cut Pages'] = $ret[0][$a_word['cut']][$replace_num] - $ret[0][$a_word['cut']][$numFirstCut];
            $a_after['Cut Pages']  = $ret[0][$a_word['cut']][$numEndCut] - $ret[0][$a_word['cut']][$replace_num];
         }
      }

      if (isset($a_word['replace'])) {
         $numFirstReplace = key($ret[0][$a_word['replace']]);
         $numEndReplace   = key( array_slice( $ret[0][$a_word['replace']], -1, 1, TRUE ) );
         $a_before['Printer Replace'] = $ret[0][$a_word['replace']][$replace_num] - $ret[0][$a_word['replace']][$numFirstReplace];
         $a_after['Printer Replace']  = $ret[0][$a_word['replace']][$numEndReplace] - $ret[0][$a_word['replace']][$replace_num];
      } else {
         $a_before['Printer Replace'] = $cnt_printerchanged;
         $a_after['Printer Replace']  = $cnt_printerchanged;
      }

      if (!isset($a_word['retract'])) {
         $a_before['Retracted Pages'] = 0;
         $a_after['Retracted Pages']  = 0;
      } else {
         $keys = array_keys($ret[0][$a_word['retract']]);
         $numFirstRetract = array_shift($keys);
         $numEndRetract   = array_pop($keys);
         if ($numFirstRetract == ''
                 || $numEndRetract == '') {
            $a_before['Retracted Pages'] = 0;
            $a_after['Retracted Pages']  = 0;
         } else if (!isset($ret[0][$a_word['retract']][$replace_num])) {
            $a_before['Retracted Pages'] = $ret[0][$a_word['retract']][$numEndRetract] - $ret[0][$a_word['retract']][$numFirstRetract];
            $a_after['Retracted Pages']  = $ret[0][$a_word['retract']][$numEndRetract] - $ret[0][$a_word['retract']][$numEndRetract];
         } else {
            $a_before['Retracted Pages'] = $ret[0][$a_word['retract']][$replace_num] - $ret[0][$a_word['retract']][$numFirstRetract];
            $a_after['Retracted Pages']  = $ret[0][$a_word['retract']][$numEndRetract] - $ret[0][$a_word['retract']][$replace_num];
         }
      }

      // manage 'cPagesInitial' of new printer
      if (!isset($ret[0][$a_word['cut']][$replace_num])) {
         $cPagesInitial = $ret[0][$a_word['cut']][$numFirstCut];
      } else {
         $cPagesInitial = $ret[0][$a_word['cut']][$replace_num];
      }
      // manage 'cRetractedInitial'
      if (!isset($ret[0][$a_word['retract']][$replace_num])) {
         $cRetractedInitial = $ret[0][$a_word['retract']][$numFirstCut];
      } else {
         $cRetractedInitial = $ret[0][$a_word['retract']][$replace_num];
      }

      return array($a_before, $a_after, $cPagesInitial, $cRetractedInitial);
   }



   function predictionEndPaper() {
      global $DB;

      $pmServices     = new PluginMonitoringService();

      $a_services = $pmServices->find("`name`='nsca_printer' OR `name`='Imprimante'");
      $daysnameidx = Toolbox::getDaysOfWeekArray();

      $currentday = date('w', date('U'));
      $a_bornes = array();
      $nbweeks = 3; // check with last 14 days
      foreach ($a_services as $a_service) {
         $last = current($this->find("`plugin_monitoring_services_id`='".$a_service['id']."'", '`id` DESC', 1));

         $a_daynext    = array();
         $a_cntdaynext = array();
         $nbdays = 3;
         for ($i=1; $i <= $nbdays; $i++) {
            if (!isset($a_daynext[($i - 1)])) {
               $a_daynext[$i] = $currentday + 1;
            } else {
               $a_daynext[$i] = $a_daynext[($i - 1)] + 1;
            }
            if ($a_daynext[$i] == 7) {
               $a_daynext[$i] = 0;
            }
            $sql = 'SELECT SUM(`cPagesToday`) as cnt FROM `glpi_plugin_monitoring_hostdailycounters`
                    WHERE `dayname`="'.$daysnameidx[$a_daynext[$i]].'"
                       AND `plugin_monitoring_services_id`="'.$a_service['id'].'"
                    ORDER BY id DESC
                    LIMIT '.$nbweeks;
            $result = $DB->query($sql);
            $data = $DB->fetch_assoc($result);
            $a_cntdaynext[$i] = ceil($data['cnt'] / $nbweeks);

            // Extend for week end
            if ($a_daynext[$i] == 6
                    || $a_daynext[$i] == 0) {
               $nbdays++;
            }
         }

         if ($last['cPagesRemaining'] - (array_sum($a_cntdaynext)) < 0) {
            $a_bornes[$last['hostname']] = $last['cPagesRemaining'] - (array_sum($a_cntdaynext));
         }
      }
      ksort($a_bornes);
      echo "<table class='tab_cadre_fixe'>";

      echo "<tr class='tab_bg_1'>";
      echo "<th colspan='2'>";
      echo count($a_bornes)." bornes qui ne vont plus avoir de papier dans les 3 jours ouvrés";
      echo "</th>";
      echo "</tr>";

      foreach ($a_bornes as $host=>$cnt) {
         echo "<tr class='tab_bg_3'>";
         echo "<td>";
         echo $host;
         echo "</td>";
         echo "<td>";
         echo $cnt;
         echo "</td>";
         echo "</tr>";
      }
      echo "</table>";

   }



   function prepareInputForUpdate($input) {

      if (isset($input["cPaperChanged"])
              && !isset($input['cPagesRemaining'])) {
         $input['cPagesRemaining'] = 2000;
      }
      // cPrinterChanged
      // cBinEmptied

      return $input;
   }



   function post_updateItem($history=1) {
      global $DB, $CFG_GLPI;

      if (isset($_SESSION['plugin_monitoring_hostdailyupdate'])) {
         return;
      }
      $_SESSION['plugin_monitoring_hostdailyupdate'] = true;
      foreach ($this->updates as $field) {
         $oldvalue = $this->oldvalues[$field];
         $newvalue = $this->fields[$field];
         if ($field == 'cPaperChanged') {
            $cPagesRemaining = 2000;
            $a_data = getAllDatasFromTable(
                        'glpi_plugin_monitoring_hostdailycounters',
                        "`day` > '".$this->fields['day']."'
                           AND `plugin_monitoring_services_id`='".$this->fields['plugin_monitoring_services_id']."'",
                        false,
                        '`day` ASC');
            foreach ($a_data as $data) {
               if ($cPagesRemaining < $data['cPagesRemaining']) {
                  break;
               } else {
                  $data['cPagesRemaining'] = $cPagesRemaining - $data['cPagesToday'];
                  $cPagesRemaining = $data['cPagesRemaining'];
                  
                  $data['cPaperChanged'] += ($this->fields['cPaperChanged'] - $this->oldvalues['cPaperChanged']);
               
                  $data['cPaperLoad'] += 2000*($this->fields['cPaperChanged'] - $this->oldvalues['cPaperChanged']);
               
                  unset($data['hostname']);
                  $this->update($data);
               }
            }
         }
         
         if ($field == 'cBinEmptied') {
            $cRetractedRemaining = 0;
            $a_data = getAllDatasFromTable(
                        'glpi_plugin_monitoring_hostdailycounters',
                        "`day` > '".$this->fields['day']."'
                           AND `plugin_monitoring_services_id`='".$this->fields['plugin_monitoring_services_id']."'",
                        false,
                        '`day` ASC');
            foreach ($a_data as $data) {
               $data['cRetractedRemaining'] = $cRetractedRemaining + $data['cRetractedToday'];
               $cRetractedRemaining = $data['cRetractedRemaining'];
               
               $data['cBinEmptied'] += ($this->fields['cBinEmptied'] - $this->oldvalues['cBinEmptied']);
               
               unset($data['hostname']);
               $this->update($data);
            }
         }
         
         if ($field == 'cPrinterChanged') {
            $a_data = getAllDatasFromTable(
                        'glpi_plugin_monitoring_hostdailycounters',
                        "`day` > '".$this->fields['day']."'
                           AND `plugin_monitoring_services_id`='".$this->fields['plugin_monitoring_services_id']."'",
                        false,
                        '`day` ASC');
            foreach ($a_data as $data) {
               // TODO : Increment cPrinterChanged ... how to ?
               $data['cPrinterChanged'] += ($this->fields['cPrinterChanged'] - $this->oldvalues['cPrinterChanged']);
               $this->update($data);
            }
         }
         
         if ($field == 'cPagesToday') {
            // Update current day and more recent days ...
            $a_data = getAllDatasFromTable(
                        'glpi_plugin_monitoring_hostdailycounters',
                        "`day` >= '".$this->fields['day']."'
                           AND `plugin_monitoring_services_id`='".$this->fields['plugin_monitoring_services_id']."'",
                        false,
                        '`day` ASC');
            foreach ($a_data as $data) {
               $data['cPagesTotal'] += ($newvalue - $oldvalue);
               $data['cPagesRemaining'] -= ($newvalue - $oldvalue);
            
               unset($data['hostname']);
               $this->update($data);
            }
         }
         
         if ($field == 'cRetractedToday') {
            // Update current day and more recent days ...
            $a_data = getAllDatasFromTable(
                        'glpi_plugin_monitoring_hostdailycounters',
                        "`day` >= '".$this->fields['day']."'
                           AND `plugin_monitoring_services_id`='".$this->fields['plugin_monitoring_services_id']."'",
                        false,
                        '`day` ASC');
            foreach ($a_data as $data) {
               $data['cRetractedTotal'] += ($newvalue - $oldvalue);
               $data['cRetractedRemaining'] += ($newvalue - $oldvalue);
            
               unset($data['hostname']);
               $this->update($data);
            }
         }
      }
      unset($_SESSION['plugin_monitoring_hostdailyupdate']);
   }
}
?>