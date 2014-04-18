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

/* Counters names to be included in plugin translation :
   'Initial counter for printed pages'
   'Total printed pages'
   'Daily printed pages'
   'Remaining pages'
   'Initial counter for retracted pages'
   'Total retracted pages'
   'Daily retracted pages'
   'Stored retracted pages'
   'Total for printer changed'
   'Total for paper changed'
   'Total for bin emptied'
   'Paper load'
   'Daily inserted cards'
   'Total inserted cards'
   'Daily bad cards'
   'Total bad cards'
   'Daily removed cards'
   'Total removed cards'
*/

   static $managedCounters = array(
      'cPagesInitial'   => array(
         'service'   => 'printer',
         'name'      => 'Initial counter for printed pages',
         'default'   => 0,
         'hidden'    => true,
      ),
      'cPagesTotal'     => array(
         'service'   => 'printer',
         'type'      => 'total',
         'name'      => 'Total printed pages',
         'default'   => 0,
         'editable'  => true,
      ),
      'cPagesToday'     => array(
         'service'   => 'printer',
         'name'      => 'Daily printed pages',
         'default'   => 0,
         'editable'  => true,
         'avg'       => true,
         'sum'       => true,
      ),
      'cPagesRemaining' => array(
         'service'   => 'printer',
         'name'            => 'Remaining pages',
         'default'         => 2000,
         'lowThreshold'    => 200,
         'zeroDetection'   => array (
            "days"      => 3,
            "counter"   => 'cPagesToday',
         )
      ),
      'cRetractedInitial' => array(
         'service'   => 'printer',
         'name'      => 'Initial counter for retracted pages',
         'default'   => 0,
      ),
      'cRetractedTotal' => array(
         'service'   => 'printer',
         'type'      => 'total',
         'name'      => 'Total retracted pages',
         'default'   => 0,
         'editable'  => true,
      ),
      'cRetractedToday' => array(
         'service'   => 'printer',
         'name'      => 'Daily retracted pages',
         'default'   => 0,
         'editable'  => true,
         'avg'       => true,
         'sum'       => true,
      ),
      'cRetractedRemaining' => array(
         'service'   => 'printer',
         'name'     => 'Stored retracted pages',
         'default'  => 0,
      ),
      'cPrinterChanged' => array(
         'service'   => 'printer',
         'name'     => 'Total for printer changed',
         'default'  => 0,
         'editable' => true,
      ),
      'cPaperChanged' => array(
         'service'   => 'printer',
         'name'     => 'Total for paper changed',
         'default'  => 0,
         'editable' => true,
      ),
      'cBinEmptied'   => array(
         'service'   => 'printer',
         'name'     => 'Total for bin emptied',
         'default'  => 0,
         'editable' => true,
      ),
      'cPaperLoad'    => array(
         'service'   => 'printer',
         'name'     => 'Paper load',
         'default'  => 2000,
      ),
      'cCardsInsertedOkToday' => array(
         'service'   => 'cards',
         'name'      => 'Daily inserted cards',
         'default'   => 0,
         'editable'  => true,
         'avg'       => true,
         'sum'       => true,
      ),
      'cCardsInsertedOkTotal' => array(
         'service'   => 'cards',
         'type'      => 'total',
         'name'      => 'Total inserted cards',
         'default'   => 'previous',
         'editable'  => true,
      ),
      'cCardsInsertedKoToday' => array(
         'service'   => 'cards',
         'name'      => 'Daily bad cards',
         'default'   => 0,
         'editable'  => true,
         'avg'       => true,
         'sum'       => true,
      ),
      'cCardsInsertedKoTotal' => array(
         'service'   => 'cards',
         'type'      => 'total',
         'name'      => 'Total bad cards',
         'default'   => 'previous',
         'editable'  => true,
      ),
      'cCardsRemovedToday' => array(
         'service'   => 'cards',
         'name'      => 'Daily removed cards',
         'default'   => 0,
         'editable'  => true,
         'avg'       => true,
         'sum'       => true,
      ),
      'cCardsRemovedTotal' => array(
         'service'   => 'cards',
         'type'      => 'total',
         'name'      => 'Total removed cards',
         'default'   => 'previous',
         'editable'  => true,
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
      $tab[1]['massiveaction']   = false;

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
         // TODO : Translation
         $tab[$i]['name']           = $value['name'];
         $tab[$i]['name']           = __($value['name'], 'monitoring');

         $tab[$i]['datatype']       = 'specific';
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
            // Do not display zero values ... tab is more easily readable!
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
        if ($this->canUpdate() and isset($value['editable'])) {
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
      This function to check if existing counters are valid:
      - should be scheduled in cron ?
      - should be used to run prediction function ?
   */
   static function runCheckCounters($date='', $hostname='%', $interval=2) {
      global $DB;

      if ($date == '') $date = date('Y-m-d H:i:s');

/*
      $pmCounters          = new PluginMonitoringHostdailycounter();
      $pmCurrentCounter    = new PluginMonitoringHostdailycounter();
      $pmPreviousCounter   = new PluginMonitoringHostdailycounter();

      if ($interval == -1) {
         $a_checkables = $pmCounters->find ("`hostname` LIKE '$hostname' AND `day` < date('$date')", "`hostname` ASC, `day` DESC");
      } else {
         $a_checkables = $pmCounters->find ("`hostname` LIKE '$hostname' AND `day` BETWEEN DATE_SUB('$date', INTERVAL $interval DAY) AND DATE('$date')", "`hostname` ASC, `day` DESC");
      }

      echo "<table class='tab_cadre_fixe'>";
      echo "<tr class='tab_bg_1'>";
      echo "<th colspan='2'>";
      echo "Bornes à surveiller";
      echo "</th>";
      echo "</tr>";

      // Check all counters for negative values ...
      foreach (self::$managedCounters as $key => $value) {
         foreach ($a_checkables as $checkable) {
            // Toolbox::logInFile("pm-checkCounters", "Counter '$key' for '".$checkable['hostname']."', day : ".$checkable['day']."\n");
            if ($checkable[$key] < 0) {
               echo "<tr class='tab_bg_1'>";
               echo "<td>Kiosk '".$checkable['hostname']."' has negative counter value : '$key' = ".$checkable[$key].", day : ".$checkable['day'].".</td>";
               echo "</tr>";
            }
         }
      }

      echo "</table>";
*/

      // Check out average printed pages on each kiosk per each day type ... only for the current and next 3 days.
      // $daysnameidx = Toolbox::getDaysOfWeekArray();
      // $a = strptime($input['day'], '%Y-%m-%d');
      // $timestamp = mktime(0, 0, 0, $a['tm_mon']+1, $a['tm_mday'], $a['tm_year']+1900);
      // $input['dayname'] = $daysnameidx[date('w', $timestamp)];
      // $currentday = $daysnameidx[date('w', date('U'))];

      // $next_week[0] = date('w', date('U'));
      // $next_week[1] = date('w', date('U')) + 1;
      // $next_week[2] = date('w', date('U')) + 2;
      // $next_week[3] = date('w', date('U')) + 3;
      // $next_week[4] = date('w', date('U')) + 4;
      // $next_week[5] = date('w', date('U')) + 5;
      // $next_week[6] = date('w', date('U')) + 6;
/*
      $query = "
         SELECT 
          hostname, 
          DAYNAME AS day_name, 
          WEEKDAY(`day`) day_num, 
          AVG(cPagesToday) AS day_average
         FROM `glpi_plugin_monitoring_hostdailycounters` 
         GROUP BY `hostname`, `day_name`
         ORDER BY hostname, day_num;
      ";
      $result = $DB->query($query);
*/
      
      // Check out more recent counters per host ... 
      $a_checkables = PluginMonitoringHostdailycounter::getLastCountersPerHost(
         array (
            'start'  => 0,
            'limit'  => 10000
            )
      );
      
      // Check all counters for negative values ...
      $firstDetection = false;
      foreach ($a_checkables as $checkable) {
         foreach (self::$managedCounters as $key => $value) {
            // Toolbox::logInFile("pm-checkCounters", "Counter '$key' for '".$checkable['hostname']."', day : ".$checkable['day']."\n");
            if ($checkable[$key] < 0) {
               if (! $firstDetection) {
                  echo "<table class='tab_cadre_fixe'>";
                  echo "<tr class='tab_bg_1'><th colspan='2'>";
                  echo __('Negative counters', 'monitoring');
                  echo "</th></tr>";
                  $firstDetection = true;
               }
               echo "<tr class='tab_bg_1'><td>";
               echo __('Host', 'monitoring') ." '".$checkable['hostname']."' ". __('has negative counter value:', 'monitoring') ." '$key' = ".$checkable[$key]. __(', day: ', 'monitoring'). $checkable['day'];
               echo "</td></tr>";
            }
         }
      }
      if ($firstDetection) {
         echo "</table>";
      }


      // Check all counters for low threshold values ...
      $firstDetection = false;
      foreach ($a_checkables as $checkable) {
         foreach (self::$managedCounters as $key => $value) {
            // Toolbox::logInFile("pm-checkCounters", "Counter '$key' for '".$checkable['hostname']."', day : ".$checkable['day']."\n");
            if (isset($value['lowThreshold']) && ($checkable[$key] >= 0) && ($checkable[$key] < $value['lowThreshold'])) {
               if (! $firstDetection) {
                  echo "<table class='tab_cadre_fixe'>";
                  echo "<tr class='tab_bg_1'><th colspan='2'>";
                  echo __('Thresholds detection, lower than ', 'monitoring'). " ".$value['lowThreshold'];
                  echo "</th></tr>";
                  $firstDetection = true;
               }
               echo "<tr class='tab_bg_1'><td>";
               echo __('Host', 'monitoring') ." '".$checkable['hostname']."' ". __('has counter value lower than defined threshold:', 'monitoring') ." '$key' = ".$checkable[$key]. __(', day: ', 'monitoring'). $checkable['day'];
               echo "</td></tr>";
            }
         }
      }
      if ($firstDetection) {
         echo "</table>";
      }

      // Check out average printed pages on each kiosk per each day type ... 
      $average = PluginMonitoringHostdailycounter::getStatistics(
         array (
            'start'  => 0,
            'limit'  => 2000,
            'type'   => 'avg',
            'group'  => 'hostname, dayname'
            )
      );

      // Check all counters for zero detection ...
      foreach (self::$managedCounters as $key => $value) {
         if (isset($value['zeroDetection']) && ($checkable[$key] >= 0)) {
            $firstDetection = false;
            $daysnameidx = Toolbox::getDaysOfWeekArray();
            $todayNum = date('w', date('U'));
            $todayName = $daysnameidx[$todayNum];
            foreach ($a_checkables as $checkable) {
               Toolbox::logInFile("pm-checkCounters", "Counter '$key' for '".$checkable['hostname'] . "', counter : ". $checkable[$key] . " (". $value['zeroDetection']['days'] . " / ". $value['zeroDetection']['counter'] . ")\n");
               
               $filter = array (
                  'start'  => 0,
                  'limit'  => 2000,
                  'type'   => 'avg',
                  'group'  => 'hostname, dayname'
               );

               $nextDayNum = $todayNum;
               $listDays = array();
               for ($i=$value['zeroDetection']['days']; $i >= 0; $i--) {
                  $listDays[] = $daysnameidx[$nextDayNum];
                  
                  $nextDayNum++;
                  if ($nextDayNum == 7) {
                     $nextDayNum = 0;
                  }
               }
               $filter['filter'] = "hostname = '".$checkable['hostname']."' AND dayname IN ('".implode("','", $listDays) . "')";
               
               $breadcrumb = $checkable[$key];
               $average = PluginMonitoringHostdailycounter::getStatistics($filter);
               foreach ($average as $line) {
                  if ($checkable['hostname'] == $line['hostname']) {
                     $checkable[$key] -= $line['avg_'.$value['zeroDetection']['counter']];
                     $breadcrumb .= '-' . $line['avg_'.$value['zeroDetection']['counter']];
                  }
               }
               Toolbox::logInFile("pm-checkCounters", "Counter '$key' for '".$checkable['hostname'] . "', counter : ". $checkable[$key] . "=" . $breadcrumb . "\n");
               if ($checkable[$key] <= 0) {
                  if (! $firstDetection) {
                     echo "<table class='tab_cadre_fixe'>";
                     echo "<tr class='tab_bg_1'><th colspan='2'>";
                     echo __('End paper detection in ', 'monitoring') . $value['zeroDetection']['days'] . __(' days.', 'monitoring');
                     echo "</th></tr>";

                     $firstDetection = true;
                  }
                  echo "<tr class='tab_bg_1'><td>";
                  echo __('Host', 'monitoring') ." '".$checkable['hostname']."' ". __('has a counter which will become negative in ', 'monitoring') . $value['zeroDetection']['days'] . __(' days ', 'monitoring'). " : '$key' -> ".$checkable[$key]." = ".$breadcrumb;
                  echo "</td></tr>";
               }
            }
            if ($firstDetection) {
               echo "</table>";
            }
         }
      }
   }


   static function runAddDays() {

      ini_set("max_execution_time", "0");

      $pmServices               = new PluginMonitoringService();
      $computer                 = new Computer();
      $pmComponentscatalog_Host = new PluginMonitoringComponentscatalog_Host();
      $pmServiceevent           = new PluginMonitoringServiceevent();

      $daysnameidx = Toolbox::getDaysOfWeekArray();

      // Card reader and printer counters
      // TODO : make it generic from data in managed counters table ...
      $a_services = $pmServices->find("`name`='nsca_reader' OR `name`='Lecteur de cartes' OR `name`='nsca_printer' OR `name`='Imprimante'");
      foreach ($a_services as $a_service) {
         $services_id = $a_service['id'];
         $services_name = $a_service['name'];


         // if (! in_array($services_id, array (1157,1196,1278,5010,1945,1623,1314,1172,1165,3699))) {
            // continue;
         // }

         // Simply testing on one host (card and printer services) ...
         // TODO : comment !!!!
         // ek3k-cnam-0023 - 2147479585 pages on 2014-02-25 between 12:42 and 16:53! => faulty printer counter : 2147483647 !
         // if (($services_id != 1157)) {
            // continue;
         // }
         // ek3k-cnam-0090 - 500 pages on 2014-01-29 and 2014-01-30
         // Faulty perf_data : 'Cut Pages'=0c ... !
         // if (($services_id != 1196)) {
            // continue;
         // }
         // ***** ek3k-cnam-0111 - 1000 pages on 2014-02-15 (Samedi !)
         // if (($services_id != 1278)) {
            // continue;
         // }
         // ek3k-cnam-0254 - 1600 pages on 2014-04-15 - printer changed at 09:25
         // if (($services_id != 5010)) {
            // continue;
         // }
         // ek3k-cnam-0141 - 5500 pages on 2014-04-07 - critical all day and one Ok state ...
         // if (($services_id != 1945)) {
            // continue;
         // }
         // ek6k-cnam-0014 - 1297 pages on 2013-12-31 - printer changed at 15:42
         // if (($services_id != 1623)) {
            // continue;
         // }
         // ek6k-cnam-0006 - 597 pages on 2014-03-10 - critical ...
         // if (($services_id != 1314)) {
            // continue;
         // }
         // ek3k-cnam-0047
         // if (($services_id != 1172)) {
            // continue;
         // }
         // ek3k-cnam-0036
         // if (($services_id != 1165) && ($services_id != 1703)) {
            // continue;
         // }
         // ek3k-cnam-0265
         // if (($services_id != 3701) && ($services_id != 3699)) {
            // continue;
         // }
         
         /*
          * Filters : 
          * Ok - ignore cut pages = 0
          * Ok - ignore cut pages = 2147483647
          * Ok - do not filter critical/warning states
          * - printer is changed is more than X pages printed in day
          */

         $counters_type = '';
         if (($services_name == "nsca_reader") || ($services_name == "Lecteur de cartes")) {
            $counters_type = 'cards';
         }
         if (($services_name == "nsca_printer") || ($services_name == "Imprimante")) {
            $counters_type = 'printer';
         }
         
         $pmComponentscatalog_Host->getFromDB($a_service['plugin_monitoring_componentscatalogs_hosts_id']);
         $computer->getFromDB($pmComponentscatalog_Host->fields['items_id']);
         $hostname = $computer->fields['name'];
         
         Toolbox::logInFile("pm-counters", "Service $hostname/$services_name : $services_id\n");
         $self = new self();
         $a_counters = current($self->find("`hostname`='$hostname' AND LOCATE('$counters_type', `counters`) > 0", "`id` DESC", 1));
         if (! isset($a_counters['id'])) {
            // There is no daily counters of the requested type for this host in the table ... create first host daily counters record.
            $input = array();
            
            // Get first service event ...
            $first = current($pmServiceevent->find("`plugin_monitoring_services_id`='".$services_id."'", '`id` ASC', 1));
            if (!isset($first['id'])) {
               Toolbox::logInFile("pm-counters", "Service $hostname/$services_name : $services_id, no service event recorded for this service!\n");
               continue;
            } else {
               $splitdate = explode(' ', $first['date']);
               $input['day'] = $splitdate[0];
            }
            $a_counters = current($self->find("`hostname`='$hostname' AND `day`='" . $input['day'] . "'", "`id` DESC", 1));
            if (isset($a_counters['id'])) {
               Toolbox::logInFile("pm-counters", "Day : ".$a_counters['day']." still exists for $hostname, for another service than $services_name ?\n");
               $input = $a_counters;
            }
            
            // Hostname / day record
            $input['hostname']            = $hostname;
            $a = strptime($input['day'], '%Y-%m-%d');
            $timestamp = mktime(0, 0, 0, $a['tm_mon']+1, $a['tm_mday'], $a['tm_year']+1900);
            $input['dayname']             = $daysnameidx[date('w', $timestamp)];
            // $input['daynum']              = date('w', $timestamp);
            
            // Recorded counters types
            if (isset($input['counters'])) {
               $counters_types = explode(',', $input['counters']);
               $counters_types[] = $counters_type;
               $input['counters'] = implode(',', array_unique($counters_types));
            } else {
               $input['counters'] = $counters_type;
            }

            // Fetch perfdata of 1st event in day ...
            $a_first = $self->getFirstValues($services_id, $input['day']);

            // Fetch perfdata of last event in day to update counters ...
            $a_last = $self->getLastValues($services_id, $input['day']);
            
            // Set default values for counters ...
            foreach (self::$managedCounters as $key => $value) {
               if (isset($value['service']) && $value['service'] == $counters_type) {
                  if (isset($value['default'])) {
                     $input[$key] = $value['default'];
                  } else {
                     $input[$key] = 0;
                  }
               }
            }
            Toolbox::logInFile("pm-counters", "Initial default values : ".serialize($input)."\n");
            if ($counters_type == 'cards') {
               if (count($a_first) != 0) {
                  // Compute daily values thanks to first and last day values.
                  // 'Powered Cards'=2339c 'Mute Cards'=89c 'Cards Removed'=2428c
                  $input['cCardsInsertedOkTotal']	= $a_last['Powered Cards'];
                  $input['cCardsInsertedOkToday']  = $a_last['Powered Cards'] - $a_first['Powered Cards'];
                  $input['cCardsInsertedKoTotal']	= $a_last['Mute Cards'];
                  $input['cCardsInsertedKoToday']  = $a_last['Mute Cards'] - $a_first['Mute Cards'];
                  $input['cCardsRemovedTotal']     = $a_last['Cards Removed'];
                  $input['cCardsRemovedToday']     = $a_last['Cards Removed'] - $a_first['Cards Removed'];
               }
            }
            if ($counters_type == 'printer') {
               if (count($a_first) != 0) {
                  $input['cRetractedInitial']   = $a_first['Retracted Pages'];
                  $input['cPagesInitial']       = $a_first['Cut Pages'];

                  // Compute daily values thanks to first and last day values.
                  $input['cRetractedTotal']     = $a_last['Retracted Pages'] - $a_first['Retracted Pages'];
                  $input['cRetractedToday']     = $input['cRetractedTotal'];
                  $input['cPagesTotal']         = $a_last['Cut Pages'] - $a_first['Cut Pages'];
                  $input['cPagesToday']         = $input['cPagesTotal'];
                  $input['cPagesRemaining']     = $input['cPaperLoad'] - $input['cPagesToday'];
                  $input['cRetractedRemaining'] = $input['cRetractedToday'];
                  
                  // Do not care about bin emptied, printer changed or paper loaded ...
                  $input['cBinEmptied']         = $a_last['Trash Empty'];
                  $input['cPrinterChanged']     = $a_last['Printer Replace'];
                  $input['cPaperChanged']       = $a_last['Paper Reams'];
               }
            }

            Toolbox::logInFile("pm-counters", "Updated initial values : ".serialize($input)."\n");
            if (isset($a_counters['id'])) {
               $self->update($input);
               Toolbox::logInFile("pm-counters", "Service $hostname/$services_name : $services_id, updated first record for day: ".$input['day']."\n");
            } else {
               $self->add($input);
               Toolbox::logInFile("pm-counters", "Service $hostname/$services_name : $services_id, added first record for day: ".$input['day']."\n");
            }
            $a_counters = $input;
         }

         // Here it exists, at min, one host daily counters line ... and a_counters is the last known counters.
         $previous = $a_counters;
         for ($i = (strtotime($a_counters['day'])); $i < strtotime(date('Y-m-d').' 00:00:00'); $i += 86400) {
            $input = array();
            
            // Hostname / day record
            $input['day']                 = date('Y-m-d', $i);
            // $input['daynum']              = date('w', $i);
            $input['dayname']             = $daysnameidx[date('w', $i)];
            $input['hostname']            = $hostname;

            // Fetch perfdata of 1st event in day ...
            $a_first = $self->getFirstValues($services_id, $input['day']);
            Toolbox::logInFile("pm-counters", "First values : ".serialize($a_first)."\n");

            // Fetch perfdata of last event in day to update counters ...
            $a_last = $self->getLastValues($services_id, $input['day']);
            Toolbox::logInFile("pm-counters", "Last values : ".serialize($a_last)."\n");

            $a_counters = current($self->find("`hostname`='$hostname' AND `day`='" . $input['day'] . "'", "`id` DESC", 1));
            if (isset($a_counters['id'])) {
               Toolbox::logInFile("pm-counters", "Day : ".$a_counters['day']." still exists for $hostname/$services_name, let us update ...\n");
               $input = $a_counters;
               
               // Previous day exists ?
               $a_previous = current($self->find("`hostname`='$hostname' AND `day`='" . date('Y-m-d', $i-86400) . "'", "`id` DESC", 1));
               if (isset($a_previous['id'])) {
                  Toolbox::logInFile("pm-counters", "Day : ".$a_previous['day']." exists for $hostname/$services_name, use as previous record ...\n");
                  $previous = $a_counters;
               }
            }

            // Recorded counters types
            if (isset($input['counters'])) {
               $counters_types = explode(',', $input['counters']);
               $counters_types[] = $counters_type;
               $input['counters'] = implode(',', array_unique($counters_types));
            } else {
               $input['counters'] = $counters_type;
            }

            // Set default values for counters from previous record ...
            foreach (self::$managedCounters as $key => $value) {
               if (isset($value['service']) && $value['service'] == $counters_type) {
                  $input[$key] = $previous[$key];
               }
            }
            
            Toolbox::logInFile("pm-counters", "Initial values for {$input['day']} : ".serialize($input)."\n");
            if ($counters_type == 'cards') {
               // Set null values ...
               $input['cCardsInsertedOkToday']  = 0;
               $input['cCardsInsertedKoToday']  = 0;
               $input['cCardsRemovedToday']     = 0;
               
               if (count($a_first) != 0) {
                  // Compute daily values thanks to first and last day values.
                  // 'Powered Cards'=2339c 'Mute Cards'=89c 'Cards Removed'=2428c
                  $input['cCardsInsertedOkTotal']	= $a_last['Powered Cards'];
                  $input['cCardsInsertedOkToday']  = $a_last['Powered Cards'] - $a_first['Powered Cards'];
                  $input['cCardsInsertedKoTotal']	= $a_last['Mute Cards'];
                  $input['cCardsInsertedKoToday']  = $a_last['Mute Cards'] - $a_first['Mute Cards'];
                  $input['cCardsRemovedTotal']     = $a_last['Cards Removed'];
                  $input['cCardsRemovedToday']     = $a_last['Cards Removed'] - $a_first['Cards Removed'];
               } else {
                  Toolbox::logInFile("pm-counters", "Service $hostname/$services_name : $services_id, no first service event for day: ".$input['day']."\n");
               }
            }
            if ($counters_type == 'printer') {
               // Set null values ...
               $input['cPagesToday']         = 0;
               $input['cRetractedToday']     = 0;
               
               if (count($a_first) != 0) {
                  // Detect if bin was emptied today
                  $binEmptiedToday = false;
                  if ($a_last['Trash Empty'] > $previous['cBinEmptied']) {
                     // No more paper in bin if bin is emptied ...
                     $input['cRetractedRemaining'] = 0;
                     $input['cBinEmptied'] = $a_last['Trash Empty'];
                     $binEmptiedToday = true;
                  }
                  
                   // Detect if printer was changed today
                  // Toolbox::logInFile("pm-counters", "Printer replace : ".$a_last['Printer Replace']." / ".$previous['cPrinterChanged']."\n");
                  if ($a_last['Printer Replace'] > $previous['cPrinterChanged']
                     || ($a_last['Cut Pages'] > $a_first['Cut Pages'] + 500)
                     || ($a_last['Cut Pages'] < $a_first['Cut Pages'])
                     || ($a_last['Retracted Pages'] < $a_first['Retracted Pages'])
                     || ($a_last['Cut Pages'] < $previous['cPagesInitial'])
                     || ($a_last['Retracted Pages'] < $previous['cRetractedInitial'])
                     ) {
                     
                     Toolbox::logInFile("pm-counters", "Service $hostname/$services_name : $services_id, detected that printer has changed today!\n");
                     if ($a_last['Printer Replace'] > $previous['cPrinterChanged']) {
                        Toolbox::logInFile("pm-counters", "Printer replaced counter increased!\n");
                     }
                     if ($a_last['Cut Pages'] < $a_first['Cut Pages']) {
                        Toolbox::logInFile("pm-counters", "Last cut pages < first cut pages!\n");
                     }
                     if ($a_last['Retracted Pages'] < $a_first['Retracted Pages']) {
                        Toolbox::logInFile("pm-counters", "Last retracted pages < first retracted pages!\n");
                     }
                     if ($a_last['Cut Pages'] < $previous['cPagesInitial']) {
                        Toolbox::logInFile("pm-counters", "Last cut pages < previous initial pages!\n");
                     }
                     if ($a_last['Retracted Pages'] < $previous['cRetractedInitial']) {
                        Toolbox::logInFile("pm-counters", "Last retracted pages < previous initial pages!\n");
                     }

                     // getPrinterChanged
                     $retpages = $self->getPrinterChanged($services_id, date('Y-m-d', $i).' 00:00:00', date('Y-m-d', $i).' 23:59:59', $previous['cPrinterChanged']);
                     // Toolbox::logInFile("pm-counters", "Printer changed counters : ".serialize($retpages)."\n");
                     $input['cPagesToday'] = $retpages[0]['Cut Pages'] + $retpages[1]['Cut Pages'];
                     $input['cPagesTotal'] = $previous['cPagesTotal'] + $input['cPagesToday'];
                     $input['cRetractedToday'] = $retpages[0]['Retracted Pages'] + $retpages[1]['Retracted Pages'];
                     $input['cRetractedTotal'] = $previous['cRetractedTotal'] + $input['cRetractedTotal'];

                     // TODO : check if really ok ...
                     // Toolbox::logInFile("pm-counters", "input['cPrinterChanged'] : ".$input['cPrinterChanged']."\n");
                     // Toolbox::logInFile("pm-counters", "a_last['Printer Replace'] : ".$a_last['Printer Replace']."\n");
                     // Toolbox::logInFile("pm-counters", "a_first['Printer Replace'] : ".$a_first['Printer Replace']."\n");
                     // $input['cPrinterChanged'] = ($a_last['Printer Replace'] != 0) ? $input['cPrinterChanged'] + ($a_last['Printer Replace'] - $a_first['Printer Replace']) : $input['cPrinterChanged']+1;
                     if ($retpages[1]['Printer Replace'] == $retpages[0]['Printer Replace']) {
                        $input['cPrinterChanged'] += $retpages[1]['Printer Replace'] - $retpages[0]['Printer Replace'];
                     } else {
                        $input['cPrinterChanged'] = $retpages[1]['Printer Replace'];
                     }
                     $input['cPagesInitial'] = $retpages[2];
                     $input['cRetractedInitial'] = $retpages[3];

                     $input['cPagesRemaining'] = $input['cPaperLoad'] - $input['cPagesTotal'];
                     $input['cRetractedRemaining'] += $input['cRetractedToday'];
                  } else {
                     // When printer has not been changed :
                     // 1/ Compute daily values thanks to first and last day values.
                     $input['cPagesToday']         = $a_last['Cut Pages'] - $a_first['Cut Pages'];
                     $input['cRetractedToday']     = $a_last['Retracted Pages'] - $a_first['Retracted Pages'];
                     // 2/ Increase total values from previous day with daily values
                     $input['cRetractedTotal']     = $previous['cRetractedTotal'] + $input['cRetractedToday'];
                     $input['cPagesTotal']         = $previous['cPagesTotal'] + $input['cPagesToday'];
                     // 3/ Decrease remaining pages with printed pages today
                     $input['cPagesRemaining']     = $previous['cPagesRemaining'] - $input['cPagesToday'];
                     // 4/ Increase retracted remaining pages with retracted pages today
                     $input['cRetractedRemaining'] += $input['cRetractedToday'];

                     // Detect if paper was changed today
                     if ($a_last['Paper Reams'] > $previous['cPaperChanged']) {
                        // getPaperChanged
                        $retpages = $self->getPaperChanged($services_id, date('Y-m-d', $i).' 00:00:00', date('Y-m-d', $i).' 23:59:59', $previous['cPaperChanged']);
                        Toolbox::logInFile("pm-counters", "Paper changed counters : ".serialize($retpages)."\n");
                        $input['cPagesToday'] = $retpages[0] + $retpages[1];
                        $input['cRetractedToday'] = $retpages[2] + $retpages[3];
                        // Reset remaining pages with default paper ream load
                        $input['cPagesRemaining'] = 2000 - $retpages[1];
                        // Compute total paper load
                        $input['cPaperLoad'] = ($a_last['Paper Reams'] + 1) * 2000;
                        $input['cPaperChanged'] = $a_last['Paper Reams'];
                     }
                  }
               } else {
                  Toolbox::logInFile("pm-counters", "Service $hostname/$services_name : $services_id, no first service event for day: ".$input['day']."\n");
               }
            }
            
            Toolbox::logInFile("pm-counters", "Updated values : ".serialize($input)."\n");
            if (isset($a_counters['id'])) {
               $self->update($input);
               Toolbox::logInFile("pm-counters", "Service $hostname/$services_name : $services_id, updated record for day: ".$input['day']."\n");
            } else {
               $self->add($input);
               Toolbox::logInFile("pm-counters", "Service $hostname/$services_name : $services_id, added record for day: ".$input['day']."\n");
            }
            $previous = $input;
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

      $query = "SELECT id, perf_data, date FROM glpi_plugin_monitoring_serviceevents
             JOIN
               (SELECT MIN(glpi_plugin_monitoring_serviceevents.id) AS min
                FROM glpi_plugin_monitoring_serviceevents
                WHERE `plugin_monitoring_services_id` = '".$services_id."'
                   AND `glpi_plugin_monitoring_serviceevents`.`perf_data` != ''
                   AND `glpi_plugin_monitoring_serviceevents`.`perf_data` NOT LIKE '\'Cut Pages\'=0c%'
                   AND `glpi_plugin_monitoring_serviceevents`.`perf_data` NOT LIKE '\'Cut Pages\'=2147483647c%'
                   AND `glpi_plugin_monitoring_serviceevents`.`date` >= '".$date." 00:00:00'
                   AND `glpi_plugin_monitoring_serviceevents`.`date` <= '".$date." 23:59:59'

                ORDER BY glpi_plugin_monitoring_serviceevents.`date` ASC) min_id ON
              (min_id.min = id)";
      // Toolbox::logInFile("pm-counters", "getFirstValues: ".$query."\n");

      $resultevent = $DB->query($query);

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

      $query = "SELECT id, perf_data, date FROM glpi_plugin_monitoring_serviceevents
             JOIN
               (SELECT MAX(glpi_plugin_monitoring_serviceevents.id) AS max
                FROM glpi_plugin_monitoring_serviceevents
                WHERE `plugin_monitoring_services_id` = '".$services_id."'
                   AND `glpi_plugin_monitoring_serviceevents`.`perf_data` != ''
                   AND `glpi_plugin_monitoring_serviceevents`.`perf_data` NOT LIKE '\'Cut Pages\'=0c%'
                   AND `glpi_plugin_monitoring_serviceevents`.`perf_data` NOT LIKE '\'Cut Pages\'=2147483647c%'
                   AND `glpi_plugin_monitoring_serviceevents`.`date` >= '".$date." 00:00:00'
                   AND `glpi_plugin_monitoring_serviceevents`.`date` <= '".$date." 23:59:59'

                ORDER BY glpi_plugin_monitoring_serviceevents.`date` DESC) max_id ON
              (max_id.max = id)";

      // Toolbox::logInFile("pm-counters", "getLastValues: ".$query."\n");
      $resultevent = $DB->query($query);

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
            AND `glpi_plugin_monitoring_serviceevents`.`perf_data` LIKE '%Cut Pages%'
            AND `glpi_plugin_monitoring_serviceevents`.`perf_data` NOT LIKE '\'Cut Pages\'=0c%'
            AND `glpi_plugin_monitoring_serviceevents`.`perf_data` NOT LIKE '\'Cut Pages\'=2147483647c%'
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
      if (isset($ret[0][$word])) {
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
      } else {
         $numFirstCut = 0;
         $numEndCut   = count($ret[0][$a_word['cut']])-1;
         $pagesBefore = 0;
         $pagesAfter  = $ret[0][$ret[4]['Cut Pages']][$numEndCut] - $ret[0][$ret[4]['Cut Pages']][$numFirstCut];
         $numFirstRetract = 0;
         $numEndRetract   = count($ret[0][$a_word['cut']])-1;
         $retractedBefore = 0;
         $retractedAfter  = $ret[0][$ret[4]['Retracted Pages']][$numEndRetract] - $ret[0][$ret[4]['Retracted Pages']][$numFirstRetract];
      }

      return array($pagesBefore, $pagesAfter, $retractedBefore, $retractedAfter);
   }


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
            AND `glpi_plugin_monitoring_serviceevents`.`perf_data` LIKE '%Cut Pages%'
            AND `glpi_plugin_monitoring_serviceevents`.`perf_data` NOT LIKE '\'Cut Pages\'=0c%'
            AND `glpi_plugin_monitoring_serviceevents`.`perf_data` NOT LIKE '\'Cut Pages\'=2147483647c%'
         ORDER BY `date`";

            // AND (
               // `glpi_plugin_monitoring_serviceevents`.`event` LIKE 'Online%'
               // OR
               // `glpi_plugin_monitoring_serviceevents`.`event` LIKE 'Offline%'
               // OR
               // `glpi_plugin_monitoring_serviceevents`.`event` LIKE 'NearPaperEnd%'
               // OR
               // `glpi_plugin_monitoring_serviceevents`.`event` LIKE 'PaperEnd%'
               // OR
               // `glpi_plugin_monitoring_serviceevents`.`event` LIKE 'PaperJam%'
               // OR
               // `glpi_plugin_monitoring_serviceevents`.`event` LIKE 'Other%'
            // )
      $resultevent = $DB->query($query);
      // Toolbox::logInFile("pm-counters", "getPrinterChanged, resultevent : ".serialize($resultevent)."\n");

      $ret = $pmServiceevent->getData(
              $resultevent,
              $pmComponent->fields['graph_template'],
              $date_start,
              $date_end);
      Toolbox::logInFile("pm-counters", "getPrinterChanged, ret : ".serialize($ret[4])."\n");

      $a_word = array();
      // Reverse for being sure that printer replace is first if exists ...
      foreach (array_reverse($ret[4]) as $perfname=>$legendname) {
         if ($perfname == 'Printer Replace') {
            // Sure it will be the first word ...
            $a_word['replace'] = $legendname;
         } else if ($perfname == 'Cut Pages') {
            $a_word['cut'] = $legendname;
         } else if ($perfname == 'Retracted Pages') {
            $a_word['retract'] = $legendname;
         }
      }
      // Toolbox::logInFile("pm-counters", "getPrinterChanged : ".serialize($a_word)."\n");

      $printerReplacementIndex = -1;
      $pagesCountIndex = -1;
      foreach ($a_word as $name=>$word) {
         $prev = -100000;
         // Toolbox::logInFile("pm-counters", "getPrinterChanged, replace first index : $name / $word\n");
         if ($name == 'replace') {
            $cnt_printerchanged = -1;
         }
         foreach ($ret[0][$word] as $num=>$val) {
            if (($val == 0) || ($val == 2147483647)) {
               continue;
            }
            if ($name == 'replace') {
               if ($val > $cnt_printerchanged) {
                  if (-1 == $cnt_printerchanged) {
                     $cnt_printerchanged = $val;
                  } else {
                     $printerReplacementIndex = $num;
                     Toolbox::logInFile("pm-counters", "getPrinterChanged, printer changed index : $printerReplacementIndex\n");
                     break 1;
                  }
               }
            } else {
               if ($val < $prev) {
                  $pagesCountIndex = $num;
                  break 1;
               }
            }
            $prev = $val;
         }
      }
      Toolbox::logInFile("pm-counters", "getPrinterChanged, printer replacement index : $printerReplacementIndex, pages count index : $pagesCountIndex\n");
      if ($pagesCountIndex == -1) {
         $pagesCountIndex = $printerReplacementIndex;
      }

      // Now we have number of change
      $a_before = array();
      $a_after  = array();

      $a_before['Cut Pages'] = 0;
      $a_after['Cut Pages']  = 0;
      if (isset($a_word['cut'])) {
         $numFirstCut = 0;
         $numEndCut   = count($ret[0][$a_word['cut']])-1;
         if ($pagesCountIndex==-1 || !isset($ret[0][$a_word['cut']][$pagesCountIndex])) {
            $a_before['Cut Pages'] = $ret[0][$a_word['cut']][$numEndCut] - $ret[0][$a_word['cut']][$numFirstCut];
            $a_after['Cut Pages']  = 0;
         } else {
            $a_before['Cut Pages'] = $ret[0][$a_word['cut']][$pagesCountIndex-1] - $ret[0][$a_word['cut']][$numFirstCut];
            $a_after['Cut Pages']  = $ret[0][$a_word['cut']][$numEndCut] - $ret[0][$a_word['cut']][$pagesCountIndex];
         }
      }
      Toolbox::logInFile("pm-counters", "getPrinterChanged, a_before['Cut Pages'] : ".$a_before['Cut Pages'].", a_after['Cut Pages'] : ".$a_after['Cut Pages']."\n");

      $a_before['Printer Replace'] = $cnt_printerchanged;
      $a_after['Printer Replace']  = $cnt_printerchanged;
      if (isset($a_word['replace'])) {
         $numFirstReplace = 0;
         $numEndReplace   = count($ret[0][$a_word['replace']])-1;
         $a_before['Printer Replace'] = $ret[0][$a_word['replace']][$numFirstReplace];
         $a_after['Printer Replace']  = $ret[0][$a_word['replace']][$numEndReplace];
      }
      Toolbox::logInFile("pm-counters", "getPrinterChanged, a_before['Printer Replace'] : ".$a_before['Printer Replace'].", a_after['Printer Replace'] : ".$a_after['Printer Replace']."\n");

      $a_before['Retracted Pages'] = 0;
      $a_after['Retracted Pages']  = 0;
      if (isset($a_word['retract'])) {
         $numFirstRetract = 0;
         $numEndRetract   = count($ret[0][$a_word['cut']])-1;
         if ($pagesCountIndex==-1 || !isset($ret[0][$a_word['retract']][$pagesCountIndex])) {
            $a_before['Retracted Pages'] = $ret[0][$a_word['retract']][$numEndRetract] - $ret[0][$a_word['retract']][$numFirstRetract];
            $a_after['Retracted Pages']  = $ret[0][$a_word['retract']][$numEndRetract] - $ret[0][$a_word['retract']][$numEndRetract];
         } else {
            $a_before['Retracted Pages'] = $ret[0][$a_word['retract']][$pagesCountIndex-1] - $ret[0][$a_word['retract']][$numFirstRetract];
            $a_after['Retracted Pages']  = $ret[0][$a_word['retract']][$numEndRetract] - $ret[0][$a_word['retract']][$pagesCountIndex];
         }
      }
      Toolbox::logInFile("pm-counters", "getPrinterChanged, a_before['Retracted Pages'] : ".$a_before['Retracted Pages'].", a_after['Retracted Pages'] : ".$a_after['Retracted Pages']."\n");

      // manage 'cPagesInitial' of new printer
      if (isset($a_word['cut'])) {
         if (!isset($ret[0][$a_word['cut']][$printerReplacementIndex])) {
            $cPagesInitial = $ret[0][$a_word['cut']][0];
         } else {
            $cPagesInitial = $ret[0][$a_word['cut']][$pagesCountIndex];
         }
      } else {
         $cPagesInitial=0;
         Toolbox::logInFile("pm-counters", "getPrinterChanged, no cut pages ...\n");
      }
      // manage 'cRetractedInitial'
      if (isset($a_word['retract'])) {
         if (!isset($ret[0][$a_word['retract']][$printerReplacementIndex])) {
            $cRetractedInitial = $ret[0][$a_word['retract']][0];
         } else {
            $cRetractedInitial = $ret[0][$a_word['retract']][$pagesCountIndex];
         }
      } else {
         $cRetractedInitial=0;
         Toolbox::logInFile("pm-counters", "getPrinterChanged, no retract pages ...\n");
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
      echo count($a_bornes)." bornes ne vont plus avoir de papier dans les 3 jours ouvrés";
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

      // Toolbox::logInFile("pm-counters", "post_updateItem ...\n");
      
      if (! isset($_SESSION['plugin_monitoring_hostdailyupdate_gui'])) {
         return;
      }
      
      if (isset($_SESSION['plugin_monitoring_hostdailyupdate'])) {
         return;
      }
      
      $_SESSION['plugin_monitoring_hostdailyupdate'] = true;
      foreach ($this->updates as $field) {
         if (! isset($this->oldvalues[$field])) continue;
         $oldvalue = $this->oldvalues[$field];
         $newvalue = $this->fields[$field];
         
         // Paper changed ...
         if ($field == 'cPaperChanged') {
            $cPagesRemaining = 2000;
            $a_data = getAllDatasFromTable(
                        'glpi_plugin_monitoring_hostdailycounters',
                        "`day` > '".$this->fields['day']."'
                           AND `hostname`='".$this->fields['hostname']."'",
                        false,
                        '`day` ASC');
            foreach ($a_data as $data) {
               if ($cPagesRemaining < $data['cPagesRemaining']) {
                  // $data['cPagesRemaining'] = $cPagesRemaining - $data['cPagesToday'];
                  $cPagesRemaining = $data['cPagesRemaining'];
                  
                  $data['cPaperChanged'] += ($this->fields['cPaperChanged'] - $this->oldvalues['cPaperChanged']);
               
                  $data['cPaperLoad'] += 2000*($this->fields['cPaperChanged'] - $this->oldvalues['cPaperChanged']);
               
                  unset($data['hostname']);
                  $this->update($data);
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
         
         // Bin emptied
         if ($field == 'cBinEmptied') {
            $cRetractedRemaining = 0;
            $a_data = getAllDatasFromTable(
                        'glpi_plugin_monitoring_hostdailycounters',
                        "`day` > '".$this->fields['day']."'
                           AND `hostname`='".$this->fields['hostname']."'",
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
         
         // Printer changed
         if ($field == 'cPrinterChanged') {
            $a_data = getAllDatasFromTable(
                        'glpi_plugin_monitoring_hostdailycounters',
                        "`day` > '".$this->fields['day']."'
                           AND `hostname`='".$this->fields['hostname']."'",
                        false,
                        '`day` ASC');
            foreach ($a_data as $data) {
               // TODO : Increment cPrinterChanged ... how to ?
               $data['cPrinterChanged'] += ($this->fields['cPrinterChanged'] - $this->oldvalues['cPrinterChanged']);
               $this->update($data);
            }
         }
         
         // Daily Printed pages
         if ($field == 'cPagesToday') {
            // Update current day and more recent days ...
            $a_data = getAllDatasFromTable(
                        'glpi_plugin_monitoring_hostdailycounters',
                        "`day` > '".$this->fields['day']."'
                           AND `hostname`='".$this->fields['hostname']."'",
                        false,
                        '`day` ASC');
            foreach ($a_data as $data) {
               $data['cPagesTotal'] += ($newvalue - $oldvalue);
               $data['cPagesRemaining'] -= ($newvalue - $oldvalue);
            
               unset($data['hostname']);
               $this->update($data);
            }
         }
         
         // Daily Retracted pages
         if ($field == 'cRetractedToday') {
            // Update current day and more recent days ...
            $a_data = getAllDatasFromTable(
                        'glpi_plugin_monitoring_hostdailycounters',
                        "`day` > '".$this->fields['day']."'
                           AND `hostname`='".$this->fields['hostname']."'",
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
      unset($_SESSION['plugin_monitoring_hostdailyupdate_gui']);
   }


   /*
    * Request on table with parameters 
    * - start / limit : rows to fetch
    * - hostsFilter : filter on hostname or hostnames list
    * - daysFilter : filter on day or days list
    * - entity : filter on entity
    * - service:
         'printer'
         'cards'
    * - type:
         'daily'
         'total'
    * - period:
         'currentDay'
         'lastDay'
         'currentWeek'
         'lastWeek'
         'currentMonth'
         'lastMonth'
         'currentYear'
         'lastYear'
    */
   static function getHostDailyCounters($params) {
      global $DB, $CFG_GLPI;

      $where = $join = $fields = '';
      $fields = '*';
      $join .= "INNER JOIN `glpi_computers`
                      ON `glpi_plugin_monitoring_hostdailycounters`.`hostname` = `glpi_computers`.`name` ";

      // Start / limit
      $start = 0;
      $limit = $CFG_GLPI["list_limit_max"];
      if (isset($params['limit']) && is_numeric($params['limit'])) {
         $limit = $params['limit'];
      }
      if (isset($params['start']) && is_numeric($params['start'])) {
         $start = $params['start'];
      }

      // Filters
      if (isset($params['entity'])) {
         if (!Session::haveAccessToEntity($params['entity'])) {
            return self::Error($protocol, WEBSERVICES_ERROR_NOTALLOWED, '', 'entity');
         }
         $where = getEntitiesRestrictRequest("WHERE", "glpi_computers", '', $params['entity']) .
                     $where;
      } else {
         $where = getEntitiesRestrictRequest("WHERE", "glpi_computers") .
                     $where;
      }

      // Hosts filter
      if (isset($params['hostsFilter'])) {
         if (is_array($params['hostsFilter'])) {
            $where .= " AND `hostname` IN ('" . implode("','",$params['hostsFilter']) . "')";
         } else {
            $where .= " AND `hostname` = " . $params['hostsFilter'];
         }
      }

      // Days filter
      if (isset($params['daysFilter'])) {
         if (is_array($params['daysFilter'])) {
            $where .= " AND `day` IN ('" . implode("','",$params['daysFilter']) . "')";
         } else {
            $where .= " AND `day` = " . $params['daysFilter'];
         }
      }

      // Counters type
      if (isset($params['type'])) {
         $counters = array();
         foreach (self::$managedCounters as $key => $value) {
            if (isset($value['type']) && $value['type'] == $params['type']) {
               $counters[] = $key;
            }
         }
         $fields = "`".implode("`,`",$counters)."`";
      }
      
      // Filter
      if (isset($params['filter'])) {
         $where .= " AND ".$params['filter'];
      }

      // Period
      if (isset($params['period'])) {
         switch ($params['period']) {
         case 'currentDay':
            $where .= " AND DATE(day) = DATE(NOW())";
            break;
         case 'lastDay':
            $where .= " AND DATE(day) = DATE(CURRENT_DATE - INTERVAL 1 DAY)";
            break;
         case 'currentWeek':
            $where .= " AND WEEK(day) = WEEK(CURRENT_DATE)";
            break;
         case 'lastWeek':
            $where .= " AND WEEK(day) = WEEK(CURRENT_DATE - INTERVAL 1 WEEK)";
            break;
         case 'currentMonth':
            $where .= " AND MONTH(day) = MONTH(CURRENT_DATE)";
            break;
         case 'lastMonth':
            $where .= " AND MONTH(day) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)";
            break;
         case 'currentYear':
            $where .= " AND YEAR(day) = YEAR(CURRENT_DATE)";
            break;
         case 'lastYear':
            $where .= " AND YEAR(day) = YEAR(CURRENT_DATE - INTERVAL 1 YEAR)";
            break;
         }
      }
      
      $query = "
         SELECT
            $fields
         FROM `glpi_plugin_monitoring_hostdailycounters`
         $join
         $where
         ORDER BY date(day) DESC
         LIMIT $start,$limit
      ";
      // Toolbox::logInFile("pm-ws", "getHostDailyCounters, query : $query\n");
      $resp = array ();
      $result = $DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         // Toolbox::logInFile("pm-counters", "getHostDailyCounters, line : ".$data['hostname']." / ".$data['day']."\n");
         $row = array ();
         $row['hostname'] = $data['hostname'];
         $row['day'] = $data['day'];
         
         foreach (self::$managedCounters as $key => $value) {
            if (! isset($value['hidden']) && isset($data[$key])) {
               $row[$key] = $data[$key];
            }
         }
         $resp[] = $row;
      }

      return $resp;
   }


   /*
    * Get more recent counters record
    * - filter
    * - entity
    */
   static function getLastCountersPerHost($params) {
      global $DB;

      $where = $join = '';
      $join .= "INNER JOIN `glpi_computers`
                      ON `tmp`.`hostname` = `glpi_computers`.`name` ";

      // Entity
      if (isset($params['entity'])) {
         if (!Session::haveAccessToEntity($params['entity'])) {
            return self::Error($protocol, WEBSERVICES_ERROR_NOTALLOWED, '', 'entity');
         }
         $where = getEntitiesRestrictRequest("WHERE", "glpi_computers", '', $params['entity']) .
                     $where;
      } else {
         $where = getEntitiesRestrictRequest("WHERE", "glpi_computers") .
                     $where;
      }

      // Filter
      if (isset($params['filter'])) {
         $where .= " AND " . $params['filter'];
      }
      
      $query = "
         SELECT tmp.* FROM ( SELECT * FROM `glpi_plugin_monitoring_hostdailycounters` ORDER BY DATE(DAY) DESC ) tmp
         $join
         $where
         GROUP BY hostname
         ORDER BY hostname
      ";
      //Toolbox::logInFile("pm-ws", "getLastCountersPerDay, query : $query\n");
      $resp = array ();
      $result = $DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         $row = array();
         foreach ($data as $key=>$value) {
            if (is_string($key)) {
               $row[$key] = $value;
            }
         }
         $resp[] = $row;
      }

      return $resp;
   }


   /*
    * Request statistics on table with parameters 
    * - start / limit
    * - entity
    * - statistics:
         'avg' : average value
         'sum' : cumulated value
    * - filter: filter results by
    * - group:
         'hostname' : group by hostname
         'day': group by day
    * - order:
         'hostname' : sort by hostname
         'day' : sort by day
    * - period:
         'currentDay'
         'lastDay'
         'currentWeek'
         'lastWeek'
         'currentMonth'
         'lastMonth'
         'currentYear'
         'lastYear'
    */
   static function getStatistics($params) {
      global $DB, $CFG_GLPI;

      $where = $join = $fields = '';
      $join .= "INNER JOIN `glpi_computers`
                      ON `glpi_plugin_monitoring_hostdailycounters`.`hostname` = `glpi_computers`.`name` ";

      // Start / limit
      $start = 0;
      $limit = $CFG_GLPI["list_limit_max"];
      if (isset($params['limit']) && is_numeric($params['limit'])) {
         $limit = $params['limit'];
      }
      if (isset($params['start']) && is_numeric($params['start'])) {
         $start = $params['start'];
      }

      // Entity
      if (isset($params['entity'])) {
         if (!Session::haveAccessToEntity($params['entity'])) {
            return self::Error($protocol, WEBSERVICES_ERROR_NOTALLOWED, '', 'entity');
         }
         $where = getEntitiesRestrictRequest("WHERE", "glpi_computers", '', $params['entity']) .
                     $where;
      } else {
         $where = getEntitiesRestrictRequest("WHERE", "glpi_computers") .
                     $where;
      }

      // Filter
      if (isset($params['filter'])) {
         $where .= " AND " . $params['filter'];
      }
      
      // Period
      if (isset($params['period'])) {
         switch ($params['period']) {
         case 'currentDay':
            $where .= " AND DATE(day) = DATE(NOW())";
            break;
         case 'lastDay':
            $where .= " AND DATE(day) = DATE(CURRENT_DATE - INTERVAL 1 DAY)";
            break;
         case 'currentWeek':
            $where .= " AND WEEK(day) = WEEK(CURRENT_DATE)";
            break;
         case 'lastWeek':
            $where .= " AND WEEK(day) = WEEK(CURRENT_DATE - INTERVAL 1 WEEK)";
            break;
         case 'currentMonth':
            $where .= " AND MONTH(day) = MONTH(CURRENT_DATE)";
            break;
         case 'lastMonth':
            $where .= " AND MONTH(day) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)";
            break;
         case 'currentYear':
            $where .= " AND YEAR(day) = YEAR(CURRENT_DATE)";
            break;
         case 'lastYear':
            $where .= " AND YEAR(day) = YEAR(CURRENT_DATE - INTERVAL 1 YEAR)";
            break;
         }
      }
      
      $fields = 'hostname';
      // Group
      $group = '';
      if (isset($params['group'])) {
         $group = "GROUP BY ".$params['group'];
         $fields = $params['group'];
      }
      
      // Order
      $order = $fields.' ASC';
      if (isset($params['order'])) {
         $order = $params['order'];
      }
      
      // statistics
      if (isset($params['statistics'])) {
         foreach (self::$managedCounters as $key => $value) {
            if (! isset($value['hidden']) && isset($value[$params['statistics']])) {
               $fields .= ", ROUND( ".$params['statistics']."(".$key."),2 ) AS ".$params['statistics']."_$key";
            }
         }
      }
      
      // Check out average printed pages on each kiosk per each day type ... only for the current and next 3 days.
/*
      $query = "
         SELECT 
          hostname, 
          DAYNAME AS day_name, 
          WEEKDAY(`day`) day_num, 
          AVG(cPagesToday) AS day_average
         FROM `glpi_plugin_monitoring_hostdailycounters` 
         WHERE WEEKDAY(`day`) IN (
            WEEKDAY(NOW()), 
            WEEKDAY(DATE_ADD(NOW(), INTERVAL +1 DAY)),
            WEEKDAY(DATE_ADD(NOW(), INTERVAL +2 DAY)),
            WEEKDAY(DATE_ADD(NOW(), INTERVAL +3 DAY))
         )
         GROUP BY `hostname`, `dayname`
         ORDER BY hostname, day_num;
      ";
*/
      $query = "
         SELECT
         $fields
         FROM `glpi_plugin_monitoring_hostdailycounters`
         $join
         $where
         $group
         ORDER BY $order
         LIMIT $start,$limit
      ";
      Toolbox::logInFile("pm-ws", "getStatistics, query : $query\n");
      $resp = array ();
      $result = $DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         // Toolbox::logInFile("pm-counters", "getHostDailyCounters, line : ".$data['hostname']." / ".$data['day']."\n");
         $row = array ();
         foreach ($data as $key => $value) {
            if (is_string($key)) {
               $row[$key] = $data[$key];
            }
         }
         $resp[] = $row;
      }

      return $resp;
   }
}
?>