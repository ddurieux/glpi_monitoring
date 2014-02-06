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
   
   /**
   * Get name of this type
   *
   *@return text name of this type by language of the user connected
   *
   **/
   static function getTypeName($nb=0) {
      return _n(__('Host daily counter', 'monitoring'),__('Host daily counters', 'monitoring'),$nb);
   }
   
   
   static function canCreate() {      
      return PluginMonitoringProfile::haveRight("config", 'w');
   }

   
   static function canView() {
      return PluginMonitoringProfile::haveRight("config", 'r');
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
         Toolbox::logInFile("pm", "Daily counters, displayTabContentForItem, item concerned : ".$item->getField('itemtype')."/".$item->getField('items_id')."\n");
         if (self::canView()) {
            // TODO ...
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
      $tab[1]['linkfield']       = 'id';
      $tab[1]['name']            = __('Identifier');
      $tab[1]['massiveaction']   = false; // implicit field is id

      $tab[2]['table']           = $this->getTable();
      $tab[2]['field']           = 'hostname';
      $tab[2]['name']            = __('Host name', 'monitoring');
      $tab[2]['datatype']        = 'specific';
      $tab[2]['massiveaction']   = false;

      $tab[3]['table']           = $this->getTable();
      $tab[3]['field']           = 'day';
      $tab[3]['name']            = __('Day', 'monitoring');
      $tab[3]['datatype']        = 'date';
      $tab[3]['massiveaction']   = false;

      
      $tab[4]['table']           = $this->getTable();
      $tab[4]['field']           = 'cPagesInitial';
      $tab[4]['name']            = __('Initial counter for printed pages', 'monitoring');
      $tab[4]['massiveaction']   = false;

      $tab[5]['table']           = $this->getTable();
      $tab[5]['field']           = 'cPagesTotal';
      $tab[5]['name']            = __('Cumulative total for printed pages', 'monitoring');
      $tab[5]['massiveaction']   = false;

      $tab[6]['table']           = $this->getTable();
      $tab[6]['field']           = 'cPagesToday';
      $tab[6]['name']            = __('Daily printed pages', 'monitoring');
      $tab[6]['datatype']        = 'specific';
      $tab[6]['massiveaction']   = false;

      $tab[7]['table']           = $this->getTable();
      $tab[7]['field']           = 'cPagesRemaining';
      $tab[7]['name']            = __('Remaining pages', 'monitoring');
      $tab[7]['massiveaction']   = false;

      
      $tab[8]['table']           = $this->getTable();
      $tab[8]['field']           = 'cRetractedInitial';
      $tab[8]['name']            = __('Initial counter for retracted pages', 'monitoring');
      $tab[8]['massiveaction']   = false;

      $tab[9]['table']           = $this->getTable();
      $tab[9]['field']           = 'cRetractedTotal';
      $tab[9]['name']            = __('Cumulative total for retracted pages', 'monitoring');
      $tab[9]['massiveaction']   = false;

      $tab[10]['table']           = $this->getTable();
      $tab[10]['field']           = 'cRetractedToday';
      $tab[10]['name']            = __('Daily retracted pages', 'monitoring');
      $tab[10]['datatype']        = 'specific';
      $tab[10]['massiveaction']   = false;

      $tab[11]['table']           = $this->getTable();
      $tab[11]['field']           = 'cRetractedRemaining';
      $tab[11]['name']            = __('Stored retracted pages', 'monitoring');
      $tab[11]['massiveaction']   = false;

      
      $tab[12]['table']          = $this->getTable();
      $tab[12]['field']          = 'cPrinterChanged';
      $tab[12]['name']           = __('Cumulative total for printer changed', 'monitoring');
      $tab[12]['massiveaction']  = false;

      $tab[13]['table']          = $this->getTable();
      $tab[13]['field']          = 'cPaperChanged';
      $tab[13]['name']           = __('Cumulative total for paper changed', 'monitoring');
      $tab[13]['massiveaction']  = false;

      $tab[14]['table']          = $this->getTable();
      $tab[14]['field']          = 'cBinEmptied';
      $tab[14]['name']           = __('Cumulative total for bin emptied', 'monitoring');
      $tab[14]['massiveaction']  = false;


      $tab[15]['table']          = $this->getTable();
      $tab[15]['field']          = 'cPaperLoad';
      $tab[15]['name']           = __('Paper load', 'monitoring');
      $tab[15]['massiveaction']  = false;

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
            if ($previousRecordExists) {
               $this->fields['cRetractedTotal'] = $value - $this->fields['cRetractedInitial'];
               
               $this->fields['cRetractedToday'] = $this->fields['cRetractedTotal'] - $previousRecordExists->fields['cRetractedTotal'];
               
               // Bin has been emptied today ...
               if ($this->fields['cBinEmptied'] > $previousRecordExists->fields['cBinEmptied']) {
                  if ($this->fields['cRetractedRemaining'] == $previousRecordExists->fields['cRetractedRemaining']) {
                     $this->fields['cRetractedRemaining'] = 0;
                  }
               } else {
                  $this->fields['cRetractedRemaining'] = $previousRecordExists->fields['cRetractedRemaining'] - $this->fields['cRetractedToday'];
               }
            } else {
               $this->fields['cRetractedToday'] = 0;
               $this->fields['cRetractedRemaining'] = $value;
            }
               
            // Set up initial pages counter if empty ...
            if ($this->fields['cRetractedInitial'] == 0) $this->fields['cRetractedInitial'] = $value;
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

      $this->showTabs($options);
      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Name')." :</td>";
      echo "<td align='center'>";
      echo "<input type='text' name='name' value='".$this->fields["name"]."' size='30'/>";
      echo "</td>";
      echo "<td>".__('Max check attempts (number of retries)', 'monitoring')."&nbsp;:</td>";
      echo "<td align='center'>";
      Dropdown::showNumber("max_check_attempts", array(
                'value' => $this->fields['max_check_attempts'], 
                'min'   => 1)
      );
      echo "</td>";
      echo "</tr>";
      
      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Time in minutes between 2 checks', 'monitoring')."&nbsp;:</td>";
      echo "<td align='center'>";
      Dropdown::showNumber("check_interval", array(
                'value' => $this->fields['check_interval'], 
                'min'   => 1)
      );
      echo "</td>";
      echo "<td>".__('Time in minutes between 2 retries', 'monitoring')."&nbsp;:</td>";
      echo "<td align='center'>";
      Dropdown::showNumber("retry_interval", array(
                'value' => $this->fields['retry_interval'], 
                'min'   => 1)
      );
      echo "</td>";
      echo "</tr>";
      
      $this->showFormButtons($options);
      $this->addDivForTabs();

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
         if ($memoryUsage > 64) {
            Toolbox::logInFile("pm", "Update daily counters for '$hostname' up to $date, limit : $limit\n");
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
      // echo "<pre>Updating daily counters for '$hostname' up to $date, limit : $limit ...</pre>";
      
      $pmHostCounter       = new PluginMonitoringHostCounter();
      $pmCounters          = new PluginMonitoringHostdailycounter();
      $pmCurrentCounter    = new PluginMonitoringHostdailycounter();
      $pmPreviousCounter   = new PluginMonitoringHostdailycounter();
      
      $a_updatables = $pmHostCounter->find ("`updated`='0' AND `hostname` LIKE '$hostname' AND `date` < date('$date')", "`hostname` ASC, `date` ASC", $limit);
      foreach ($a_updatables as $updatable) {
         // Toolbox::logInFile("pm", "updatable counter, hostname : ".$updatable['hostname'].", date : ".$updatable['date']."\n");
         $pmHostCounter->getFromDBByQuery("WHERE `id`='".$updatable['id']."'");
         
         // Found a counter update to be applied ...
         $currentRecordExists = false;
         $previousRecordExists = false;
         
         if (isset($olderCounter)) unset ($olderCounter);
         $a_olderCounters = $pmCounters->find("`hostname`='".$updatable['hostname']."' AND `day`< DATE('".$updatable['date']."') ORDER BY `day`DESC LIMIT 1");
         foreach ($a_olderCounters as $olderCounter) {
            // Found an older daily counter row ...
            // Toolbox::logInFile("pm", "older daily counter, hostname : ".$olderCounter['hostname'].", day : ".$olderCounter['day']."\n");
            if ($pmPreviousCounter->getFromDBByQuery("WHERE `hostname`='".$olderCounter['hostname']."' AND `day`='".$olderCounter['day']."'")) {
               $previousRecordExists = true;
            }
         }
         
         if (isset($dailyCounter)) unset ($dailyCounter);
         $a_dailyCounters = $pmCounters->find("`hostname`='".$updatable['hostname']."' AND `day`=DATE('".$updatable['date']."')");
         foreach ($a_dailyCounters as $dailyCounter) {
            // Found a daily counter row to be updated ...
            // Toolbox::logInFile("pm", "current daily counter, hostname : ".$dailyCounter['hostname'].", day : ".$dailyCounter['day']."\n");
            if ($pmCurrentCounter->getFromDBByQuery("WHERE `hostname`='".$dailyCounter['hostname']."' AND `day`='".$dailyCounter['day']."'")) {
               // Daily record still exists ...
               $pmCurrentCounter->updateDailyCounters($updatable['counter'], $updatable['value'], ($previousRecordExists) ? $pmPreviousCounter : null);
               // Toolbox::logInFile("pm", "daily counter, updated : ".$pmCurrentCounter->fields['hostname'].", pages : ".$pmCurrentCounter->fields['cPagesToday']."\n");
            }
            $currentRecordExists = true;
         }
         if (! $currentRecordExists) {
            // Toolbox::logInFile("pm", "daily counter not found ...\n");
            // We never recorded anything ... create first record !
            $pmCurrentCounter->getEmpty();
            $pmCurrentCounter->setDefaultContent($updatable['hostname'], $updatable['date'], ($previousRecordExists) ? $pmPreviousCounter : null);
            $pmCurrentCounter->add($pmCurrentCounter->fields);
            // Toolbox::logInFile("pm", "daily counter, created first record for hostname : ".$pmCurrentCounter->fields['hostname'].", day : ".$pmCurrentCounter->fields['day']."\n");
            $pmCurrentCounter->updateDailyCounters($updatable['counter'], $updatable['value'], ($previousRecordExists) ? $pmPreviousCounter : null);
         }
         
         $pmHostCounter->fields['updated'] = '1';
         $pmHostCounter->update($pmHostCounter->fields);
      }

      return countElementsInTable($pmHostCounter->getTable(),
                                  "`updated`='0' AND `hostname` LIKE '$hostname' AND `date` < date('$date')");
   }
}

?>