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
      'cPagesInitial' => 'Initial counter for printed pages',
      'cPagesTotal' => 'Cumulative total for printed pages',
      'cPagesToday' => 'Daily printed pages',
      'cPagesRemaining' => 'Remaining pages',
      'cRetractedInitial' => 'Initial counter for retracted pages',
      'cRetractedTotal' => 'Cumulative total for retracted pages',
      'cRetractedToday' => 'Daily retracted pages',
      'cRetractedRemaining' => 'Stored retracted pages',
      'cPrinterChanged' => 'Cumulative total for printer changed',
      'cPaperChanged' => 'Cumulative total for paper changed',
      'cBinEmptied' => 'Cumulative total for bin emptied',
      'cPaperLoad' => 'Paper load',
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

   
   
   /**
    * Display tab
    * 
    * @param CommonGLPI $item
    * @param integer $withtemplate
    * 
    * @return varchar name of the tab(s) to display
    */
   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {
      if ($item->getType() == 'Computer'){
         return __('Daily counters', 'monitoring');
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
         $tab[$i]['name']           = __($value, 'monitoring');
         // $tab[$i]['massiveaction']  = false;
         $tab[$i]['datatype']       = 'number';
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
            if ($values[$field] == '0') {
               return ' ';
            } else {
               return $values[$field];
            }
            break;
      }
      return parent::getSpecificValueToDisplay($field, $values, $options);
   }


   /**
    * Set default content
    */
   function setDefaultContent($hostname, $date, $previousRecordExists=null) {
      $this->fields['hostname']            = $hostname;
      $this->fields['day']                 = $date;
      $this->fields['cPrinterChanged']     = $previousRecordExists ? $previousRecordExists->fields['cPrinterChanged'] : 0;
      $this->fields['cPaperChanged']       = $previousRecordExists ? $previousRecordExists->fields['cPaperChanged'] : 0;
      $this->fields['cBinEmptied']         = $previousRecordExists ? $previousRecordExists->fields['cBinEmptied'] : 0;
      $this->fields['cPagesInitial']       = $previousRecordExists ? $previousRecordExists->fields['cPagesInitial'] : 0;
      $this->fields['cPagesTotal']         = $previousRecordExists ? $previousRecordExists->fields['cPagesTotal'] : 0;
      $this->fields['cPagesToday']         = $previousRecordExists ? $previousRecordExists->fields['cPagesToday'] : 0;
      $this->fields['cPagesRemaining']     = $previousRecordExists ? $previousRecordExists->fields['cPagesRemaining'] : 0;
      $this->fields['cRetractedInitial']   = $previousRecordExists ? $previousRecordExists->fields['cRetractedInitial'] : 0;
      $this->fields['cRetractedTotal']     = $previousRecordExists ? $previousRecordExists->fields['cRetractedTotal'] : 0;
      $this->fields['cRetractedToday']     = $previousRecordExists ? $previousRecordExists->fields['cRetractedToday'] : 0;
      $this->fields['cRetractedRemaining'] = $previousRecordExists ? $previousRecordExists->fields['cRetractedRemaining'] : 0;
      $this->fields['cPaperLoad']          = $previousRecordExists ? $previousRecordExists->fields['cPaperLoad'] : 2000;
   }
   
   
   /**
    * Before updating database ...
    */
   static function pre_item_update($dailyCounter) {
      // Toolbox::logInFile("pm", "daily counter, pre_item_update : ".$dailyCounter->fields['hostname']." / ".$dailyCounter->fields['day']."\n");
   }
   
   
   /**
    * Update daily counters ...
    */
   function updateDailyCounters($counter, $value, $previousRecordExists=null) {
      $this->fields[$counter] = $value;
      // Paper load ...
      $this->fields['cPaperLoad'] = ($this->fields['cPaperChanged'] + 1) * 2000;
      
      switch ($counter) {
         case 'cBinEmptied':
            break;
         case 'cPrinterChanged':
            break;
         case 'cPaperChanged':
            break;
         case 'cPagesTotal':
            if ($previousRecordExists) {
               // Printer has changed today ...
               if ($this->fields['cPrinterChanged'] > $previousRecordExists->fields['cPrinterChanged']) {
                  if ($this->fields['cPagesInitial'] == $previousRecordExists->fields['cPagesInitial']) {
                     $this->fields['cPagesInitial'] = $value - $previousRecordExists->fields['cPagesTotal'];
                  }
               } else {
                  $this->fields['cPagesInitial'] == $previousRecordExists->fields['cPagesInitial'];
               }
               
               $this->fields['cPagesTotal'] = $value - $this->fields['cPagesInitial'];
               
               // Compute pages printed today and pages remaining ...
               $this->fields['cPagesToday'] = $this->fields['cPagesTotal'] - $previousRecordExists->fields['cPagesTotal'];

               // Paper has changed today ...
               if ($this->fields['cPaperChanged'] > $previousRecordExists->fields['cPaperChanged']) {
                  if ($this->fields['cPagesRemaining'] == $previousRecordExists->fields['cPagesRemaining']) {
                     $this->fields['cPagesRemaining'] = $previousRecordExists->fields['cPagesRemaining'] - $this->fields['cPagesToday'] + 2000;
                  }
               } else {
                  $this->fields['cPagesRemaining'] = $previousRecordExists->fields['cPagesRemaining'] - $this->fields['cPagesToday'];
               }
            } else {
               $this->fields['cPagesToday'] = 0;
               $this->fields['cPagesRemaining'] = $this->fields['cPaperLoad'] - $this->fields['cPagesToday'];
               if ($this->fields['cPagesInitial'] == 0) $this->fields['cPagesInitial'] = $value;
               $this->fields['cPagesTotal'] = $value - $this->fields['cPagesInitial'];
            }
            break;
         case 'cRetractedTotal':
            // Toolbox::logInFile("pm", "daily counter, updated : ".$this->fields['hostname']." / ".$this->fields['day'].", ".$counter."=".$value."\n");
            // Set up initial pages counter if empty ...
            if ($this->fields['cRetractedInitial'] == 0) $this->fields['cRetractedInitial'] = $value;
            
            if ($previousRecordExists) {
               $this->fields['cRetractedTotal'] = $value - $this->fields['cRetractedInitial'];
               
               $this->fields['cRetractedToday'] = $this->fields['cRetractedTotal'] - $previousRecordExists->fields['cRetractedTotal'];
               
               // Bin has been emptied today ...
               if ($this->fields['cBinEmptied'] > $previousRecordExists->fields['cBinEmptied']) {
                  if ($this->fields['cRetractedRemaining'] == $previousRecordExists->fields['cRetractedRemaining']) {
                     $this->fields['cRetractedRemaining'] = 0;
                  }
               } else {
                  $this->fields['cRetractedRemaining'] = $previousRecordExists->fields['cRetractedRemaining'] + $this->fields['cRetractedToday'];
               }
            } else {
               $this->fields['cRetractedToday'] = 0;
               $this->fields['cRetractedRemaining'] = $value;
               $this->fields['cRetractedTotal'] = $value - $this->fields['cRetractedInitial'];
            }
            // Compute pages printed today and pages remaining ...
            break;
      }
      $this->update($this->fields);
      // Toolbox::logInFile("pm", "daily counter, updated : ".$this->fields['hostname']." / ".$this->fields['day'].", ".$counter."=".$value."\n");
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
      echo "<td>".__('Host name', 'monitoring')."&nbsp;:&nbsp;";
      echo $this->getField('hostname')."</td>";
      echo "<td>".__('Day', 'monitoring')."&nbsp;:&nbsp;";
      echo Html::convDate($this->getField('day'))."</td>";
      echo "</tr>";
      
      echo "<tr class='tab_bg_1'>";
      foreach (self::$managedCounters as $key => $value) {
         echo "<td align='center'>".__($value, 'monitoring')."</td>";
      }
      echo "</tr>";
      
      echo "<tr class='tab_bg_1'>";
      foreach (self::$managedCounters as $key => $value) {
         if ($this->canUpdate()) {
            echo "<td><input type='text' name='$key' value='".$this->fields[$key]."' size='8'/></td>";
         } else {
            echo "<td>".$this->getValueToDisplay($key, $value)."</td>";
         }
      }
      echo "</tr>";
      
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
      
      ini_set("max_execution_time", "0");
      
      if ($task) {
         $task->log("Daily counters update started.\n");
      } else {
         Session::addMessageAfterRedirect("Daily counters update started.",false,ERROR);
      }
      
      $memoryUsage = memory_get_usage() / 1024 / 1024;
      Toolbox::logInFile("pm", "Memory usage $memoryUsage\n");
      // Toolbox::logInFile("pm", "Memory usage ".memory_get_usage()."\n");
      $pmCounters = new PluginMonitoringHostdailycounter();
      do {
         $remaining = $pmCounters->runUpdateCounters();
         if ($remaining > 0) {
            Toolbox::logInFile("pm", "More update needed : $remaining records.\n");
            echo "<pre>More update needed : $remaining records!</pre>";
            if ($task) {
               $task->log("More update needed : $remaining records.\n");
            } else {
               Session::addMessageAfterRedirect("More update needed : $remaining records.",false,ERROR);
            }
         }
         
         $memoryUsage = memory_get_usage() / 1024 / 1024;
         Toolbox::logInFile("pm", "Memory usage $memoryUsage\n");
         if ($memoryUsage >= 64) {
            Toolbox::logInFile("pm", "Allowed memory usage exceeded, please run the task again ...\n");
         }
      } while (($remaining > 0) && ($memoryUsage < 64));
      
      if ($task) {
         $task->log("Daily counters update started.\n");
      } else {
         Session::addMessageAfterRedirect("Daily counters update started.",false,ERROR);
      }
      
      return true;
   }
   
   static function runUpdateCounters($date='', $hostname='%', $limit='5000') {
      global $DB;
      
      if ($date == '') $date = date('Y-m-d H:i:s');

      Toolbox::logInFile("pm", "Update daily counters for '$hostname' up to $date, limit : $limit\n");
      
      $pmHostCounter       = new PluginMonitoringHostCounter();
      $pmCounters          = new PluginMonitoringHostdailycounter();
      $pmCurrentCounter    = new PluginMonitoringHostdailycounter();
      $pmPreviousCounter   = new PluginMonitoringHostdailycounter();
      
      $a_updatables = $pmHostCounter->find ("`updated`='0' AND `hostname` LIKE '$hostname' AND `date` < '$date'", "`hostname` ASC, `date` ASC", $limit);
      foreach ($a_updatables as $updatable) {
         $pmHostCounter->getFromDBByQuery("WHERE `id`='".$updatable['id']."'");
         
         // Found a counter update to be applied ...
         $currentRecordExists = false;
         $previousRecordExists = false;
         
         if (isset($olderCounter)) unset ($olderCounter);
         $a_olderCounters = $pmCounters->find("`hostname`='".$updatable['hostname']."' AND `day`< DATE('".$updatable['date']."') ORDER BY `day`DESC LIMIT 1");
         foreach ($a_olderCounters as $olderCounter) {
            // Found an older daily counter row ...
            if ($pmPreviousCounter->getFromDBByQuery("WHERE `hostname`='".$olderCounter['hostname']."' AND `day`='".$olderCounter['day']."'")) {
               $previousRecordExists = true;
            }
         }
         
         if (isset($dailyCounter)) unset ($dailyCounter);
         $a_dailyCounters = $pmCounters->find("`hostname`='".$updatable['hostname']."' AND `day`=DATE('".$updatable['date']."')");
         foreach ($a_dailyCounters as $dailyCounter) {
            // Found a daily counter row to be updated ...
            if ($pmCurrentCounter->getFromDBByQuery("WHERE `hostname`='".$dailyCounter['hostname']."' AND `day`='".$dailyCounter['day']."'")) {
               // Daily record still exists ...
               $pmCurrentCounter->updateDailyCounters($updatable['counter'], $updatable['value'], ($previousRecordExists) ? $pmPreviousCounter : null);
            }
            $currentRecordExists = true;
         }
         if (! $currentRecordExists) {
            // We never recorded anything ... create first record !
            $pmCurrentCounter->getEmpty();
            $pmCurrentCounter->setDefaultContent($updatable['hostname'], $updatable['date'], ($previousRecordExists) ? $pmPreviousCounter : null);
            $pmCurrentCounter->add($pmCurrentCounter->fields);
            $pmCurrentCounter->updateDailyCounters($updatable['counter'], $updatable['value'], ($previousRecordExists) ? $pmPreviousCounter : null);
         }
         
         $pmHostCounter->fields['updated'] = '1';
         $pmHostCounter->update($pmHostCounter->fields);
      }

      return countElementsInTable($pmHostCounter->getTable(),
                                  "`updated`='0' AND `hostname` LIKE '$hostname' AND `date` < date('$date')");
   }
   
   static function runCheckCounters($date='', $hostname='%', $interval=7) {
      global $DB;
      
      if ($date == '') $date = date('Y-m-d H:i:s');

      Toolbox::logInFile("pm", "Check daily counters for '$hostname' up to $date, interval : $interval days\n");
      
      $pmCounters          = new PluginMonitoringHostdailycounter();
      $pmCurrentCounter    = new PluginMonitoringHostdailycounter();
      $pmPreviousCounter   = new PluginMonitoringHostdailycounter();
      
      $a_checkables = $pmCounters->find ("`hostname` LIKE '$hostname' AND `day` BETWEEN DATE_SUB('$date', INTERVAL $interval DAY) AND date('$date')", "`hostname` ASC, `day` ASC");
      foreach ($a_checkables as $checkable) {
         Toolbox::logInFile("pm", "Daily counters for '".$checkable['hostname']."', day : ".$checkable['day']."\n");
         continue;
         
         // What is checked ...
         foreach (self::$managedCounters as $key => $value) {
            if ($this->fields[$key] < 0) {
               Toolbox::logInFile("pm-counters", "Counter '$key' for '".$checkable['hostname']."', day : ".$checkable['day']." is negative !\n");
            }
         }

         
         
         
         
         $pmCurrentCounter->getFromDB($checkable['id']);
         
         // Found a counter update to be applied ...
         $currentRecordExists = false;
         $previousRecordExists = false;
         
         if (isset($olderCounter)) unset ($olderCounter);
         $a_olderCounters = $pmCounters->find("`hostname`='".$checkable['hostname']."' AND `day`< DATE('".$checkable['date']."') ORDER BY `day`DESC LIMIT 1");
         foreach ($a_olderCounters as $olderCounter) {
            // Found an older daily counter row ...
            if ($pmPreviousCounter->getFromDBByQuery("WHERE `hostname`='".$olderCounter['hostname']."' AND `day`='".$olderCounter['day']."'")) {
               $previousRecordExists = true;
            }
         }
         
         if (isset($dailyCounter)) unset ($dailyCounter);
         $a_dailyCounters = $pmCounters->find("`hostname`='".$checkable['hostname']."' AND `day`=DATE('".$checkable['date']."')");
         foreach ($a_dailyCounters as $dailyCounter) {
            // Found a daily counter row to be updated ...
            if ($pmCurrentCounter->getFromDBByQuery("WHERE `hostname`='".$dailyCounter['hostname']."' AND `day`='".$dailyCounter['day']."'")) {
               // Daily record still exists ...
               $pmCurrentCounter->updateDailyCounters($checkable['counter'], $checkable['value'], ($previousRecordExists) ? $pmPreviousCounter : null);
            }
            $currentRecordExists = true;
         }
         if (! $currentRecordExists) {
            // We never recorded anything ... create first record !
            $pmCurrentCounter->getEmpty();
            $pmCurrentCounter->setDefaultContent($checkable['hostname'], $checkable['date'], ($previousRecordExists) ? $pmPreviousCounter : null);
            $pmCurrentCounter->add($pmCurrentCounter->fields);
            $pmCurrentCounter->updateDailyCounters($checkable['counter'], $checkable['value'], ($previousRecordExists) ? $pmPreviousCounter : null);
         }
         
         $pmHostCounter->fields['updated'] = '1';
         $pmHostCounter->update($pmHostCounter->fields);
      }

      // return countElementsInTable($pmHostCounter->getTable(),
                                  // "`updated`='0' AND `hostname` LIKE '$hostname' AND `date` < date('$date')");
   }
}

?>