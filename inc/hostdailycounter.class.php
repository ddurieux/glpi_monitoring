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
         'name' => 'Initial counter for printed pages',
         'default' => 'previous',
         'editable' => 0,
      ), 
      'cPagesTotal' => array(
         'name' => 'Cumulative total for printed pages',
         'default' => 'previous',
         'editable' => 0,
      ), 
      'cPagesToday' => array(
         'name' => 'Daily printed pages',
         'default' => 'reset',
         'editable' => 0,
      ), 
      'cPagesRemaining' => array(
         'name' => 'Remaining pages',
         'default' => 'previous',
         'editable' => 0,
         'lowThreshold' => 100,
      ), 
      'cRetractedInitial' => array(
         'name' => 'Initial counter for retracted pages',
         'default' => 'previous',
         'editable' => 0,
      ), 
      'cRetractedTotal' => array(
         'name' => 'Cumulative total for retracted pages',
         'default' => 'previous',
         'editable' => 0,
      ), 
      'cRetractedToday' => array(
         'name' => 'Daily retracted pages',
         'default' => 'reset',
         'editable' => 0,
      ), 
      'cRetractedRemaining' => array(
         'name' => 'Stored retracted pages',
         'default' => 'previous',
         'editable' => 0,
      ), 
      'cPrinterChanged' => array(
         'name' => 'Cumulative total for printer changed',
         'default' => 'previous',
         'editable' => 1,
      ), 
      'cPaperChanged' => array(
         'name' => 'Cumulative total for paper changed',
         'default' => 'previous',
         'editable' => 1,
      ), 
      'cBinEmptied' => array(
         'name' => 'Cumulative total for bin emptied',
         'default' => 'previous',
         'editable' => 1,
      ), 
      'cPaperLoad' => array(
         'name' => 'Paper load',
         'default' => 'previous',
         'editable' => 0,
      ), 
      'cCardsInsertedOk' => array(
         'name' => 'Cards inserted Ok',
         'default' => 'previous',
         'editable' => 0,
      ), 
      'cCardsInsertedKo' => array(
         'name' => 'Cards inserted Ko',
         'default' => 'previous',
         'editable' => 0,
      ), 
      'cCardsRemoved' => array(
         'name' => 'Cards removed',
         'default' => 'previous',
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
         $tab[$i]['name']           = __($value['name'], 'monitoring');
         // $tab[$i]['massiveaction']  = false;
         // $tab[$i]['datatype']       = 'specific';
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
    * Before updating database ...
    */
   static function pre_item_update($dailyCounter) {
      // Toolbox::logInFile("pm", "daily counter, pre_item_update : ".$dailyCounter->fields['hostname']." / ".$dailyCounter->fields['day']."\n");
      
/*
      // fields contains current values ...
      foreach ($dailyCounter->fields as $key => $value) {
         if ($dailyCounter->input[$key] != $dailyCounter->fields[$key]) {
            Toolbox::logInFile("pm", "new field value $key = $value -> ".$dailyCounter->input[$key]." !\n");
         }
      }
*/
      
      // If update was made with GUI ...
      if (! isset($dailyCounter->input['gui'])) return true;
      
      // Toolbox::logInFile("pm", "Daily counter, pre_item_update from GUI `hostname`='".$dailyCounter->fields['hostname']."' AND `day`='".$dailyCounter->fields['day']."'\n");
      // Load current counter values ...
      $pmCurrentCounter = new PluginMonitoringHostdailycounter();
      if (! $pmCurrentCounter->getFromDBByQuery("WHERE `hostname`='".$dailyCounter->fields['hostname']."' AND `day`='".$dailyCounter->fields['day']."'")) return;
      
      if ($dailyCounter->input['cPrinterChanged'] != $dailyCounter->fields['cPrinterChanged']) {
         $previousRecordExists = false;
         $pmPreviousCounter = new PluginMonitoringHostdailycounter();
         $a_olderCounters = $pmPreviousCounter->find("`hostname`='".$dailyCounter->fields['hostname']."' AND `day` < DATE('".$dailyCounter->fields['day']."') ORDER BY `day` DESC LIMIT 1");
         foreach ($a_olderCounters as $olderCounter) {
            // Found an older daily counter row ...
            if ($pmPreviousCounter->getFromDB($olderCounter['id'])) {
               $previousRecordExists = true;
            }
         }
         foreach ($pmPreviousCounter->fields as $key => $value) {
            Toolbox::logInFile("pm", "Previous fields $key = $value\n");
         }
         
         // Update ...
         $pagesToday = $dailyCounter->fields['cPagesTotal'] - $dailyCounter->fields['cPagesToday'];
         $retractedToday = $dailyCounter->fields['cRetractedTotal'] - $dailyCounter->fields['cRetractedToday'];
         Toolbox::logInFile("pm", "Pages today $pagesToday\n");
         $dailyCounter->input['cPagesInitial'] = $dailyCounter->fields['cPagesTotal'];
         $dailyCounter->input['cRetractedInitial'] = $dailyCounter->fields['cRetractedTotal'];
         $dailyCounter->input['cPagesTotal'] = $previousRecordExists ? $pmPreviousCounter->fields['cPagesTotal']+$pagesToday : $pagesToday;
         $dailyCounter->input['cPagesToday'] = $pagesToday;
         $dailyCounter->input['cPagesRemaining'] = $dailyCounter->fields['cPaperLoad'] - $dailyCounter->input['cPagesTotal'];
         $dailyCounter->input['cRetractedTotal'] = $previousRecordExists ? $pmPreviousCounter->fields['cRetractedTotal']+$retractedToday : $retractedToday;
         $dailyCounter->input['cRetractedToday'] = $retractedToday;
         $dailyCounter->input['cRetractedRemaining'] = 0;

         foreach ($dailyCounter->input as $key => $value) {
            Toolbox::logInFile("pm", "New fields $key = $value\n");
         }
         
         $_SESSION['plugin_monitoring_hostdailycounters'] = 'printerChanged';
      }
      
      if ($dailyCounter->input['cPaperChanged'] != $dailyCounter->fields['cPaperChanged']) {
         // Update ...
         $dailyCounter->input['cPaperLoad'] = ($dailyCounter->input['cPaperChanged'] + 1) * 2000;
         $dailyCounter->input['cPagesRemaining'] = $dailyCounter->input['cPaperLoad'] - $dailyCounter->fields['cPagesTotal'];
         
         $_SESSION['plugin_monitoring_hostdailycounters'] = 'paperChanged';
      }
      
      if ($dailyCounter->input['cBinEmptied'] != $dailyCounter->fields['cBinEmptied']) {
         // Update ...
         $dailyCounter->input['cRetractedRemaining'] = 0;
         
         $_SESSION['plugin_monitoring_hostdailycounters'] = 'binEmptied';
      }
   }
   
   
   function post_updateItem($history=1) {
      global $DB;
      
      Toolbox::logInFile("pm", "daily counter, post_updateItem : ".$this->fields['hostname']." / ".$this->fields['day']."\n");
      
      // Post update after paper changed ...
      if (isset ($_SESSION['plugin_monitoring_hostdailycounters']) && $_SESSION['plugin_monitoring_hostdailycounters']=='paperChanged') {
         Toolbox::logInFile("pm", "post_updateItem, ".$_SESSION['plugin_monitoring_hostdailycounters']."\n");
         unset ($_SESSION['plugin_monitoring_hostdailycounters']);
         
         $pmCurrentCounter = new PluginMonitoringHostdailycounter();
         $a_recentCounters = $pmCurrentCounter->find("`hostname`='".$this->fields['hostname']."' AND `day` > DATE('".$this->fields['day']."') ORDER BY `day` ASC");
         foreach ($a_recentCounters as $recentCounter) {
            $pmRecentCounter = new PluginMonitoringHostdailycounter();
            if (! $pmRecentCounter->getFromDB($recentCounter['id'])) continue;
            
            // Update paper changed ...
            $pmRecentCounter->updateDailyCounters('cPaperChanged', $this->fields['cPaperChanged'], $pmCurrentCounter);
            // Update printed today pages ...
            $pmRecentCounter->updateDailyCounters('cPrintedToday', $pmRecentCounter->fields['cPrintedToday'], $pmCurrentCounter);
            // Update retracted today pages ...
            $pmRecentCounter->updateDailyCounters('cRetractedToday', $pmRecentCounter->fields['cRetractedToday'], $pmCurrentCounter);
            
            $pmCurrentCounter = $pmRecentCounter;
         }
/*
         $query = "SELECT * FROM `".$this->getTable()."` WHERE `hostname`='".$this->fields['hostname']."' AND `day` > DATE('".$this->fields['day']."') ORDER BY `day` ASC";
         $result = $DB->query($query);
         while ($data=$DB->fetch_array($result)) {
            Toolbox::logInFile("pm", "daily counter, update : ".$data['hostname']." / ".$data['day']."\n");
            $query2 = "UPDATE `".$this->getTable()."` SET 
               `cPrinterChanged`='".$this->fields['cPrinterChanged']."'
               WHERE `id`='".$data['id']."'";
            $result2 = $DB->query($query2);
            while ($data=$DB->fetch_array($result)) {
            }
         }
*/
      }
      
      // Post update after bin emptied ...
      if (isset ($_SESSION['plugin_monitoring_hostdailycounters']) && $_SESSION['plugin_monitoring_hostdailycounters']=='binEmptied') {
         Toolbox::logInFile("pm", "post_updateItem, ".$_SESSION['plugin_monitoring_hostdailycounters']."\n");
         unset ($_SESSION['plugin_monitoring_hostdailycounters']);
         
         $pmCurrentCounter = new PluginMonitoringHostdailycounter();
         $a_recentCounters = $pmCurrentCounter->find("`hostname`='".$this->fields['hostname']."' AND `day` > DATE('".$this->fields['day']."') ORDER BY `day` ASC");
         foreach ($a_recentCounters as $recentCounter) {
            $pmRecentCounter = new PluginMonitoringHostdailycounter();
            if (! $pmRecentCounter->getFromDB($recentCounter['id'])) continue;
            
            // Update bin emptied ...
            $pmRecentCounter->updateDailyCounters('cBinEmptied', $this->fields['cBinEmptied'], $pmCurrentCounter);
            // Update retracted today pages ...
            $pmRecentCounter->updateDailyCounters('cRetractedToday', $pmRecentCounter->fields['cRetractedToday'], $pmCurrentCounter);
            
            $pmCurrentCounter = $pmRecentCounter;
         }
      }
      
      // Post update after printer changed ...
      if (isset ($_SESSION['plugin_monitoring_hostdailycounters']) && $_SESSION['plugin_monitoring_hostdailycounters']=='printerChanged') {
         Toolbox::logInFile("pm", "post_updateItem, ".$_SESSION['plugin_monitoring_hostdailycounters']."\n");
         
         unset ($_SESSION['plugin_monitoring_hostdailycounters']);
         
         $pmCurrentCounter = new PluginMonitoringHostdailycounter();
         $pmCurrentCounter->getFromDB($this->fields['id']);
         $a_recentCounters = $pmCurrentCounter->find("`hostname`='".$this->fields['hostname']."' AND `day` > DATE('".$this->fields['day']."') ORDER BY `day` ASC");
         foreach ($a_recentCounters as $recentCounter) {
            $pmRecentCounter = new PluginMonitoringHostdailycounter();
            if (! $pmRecentCounter->getFromDB($recentCounter['id'])) continue;
            
            // Update paper changed ...
            $pmRecentCounter->updateDailyCounters('guiPrinterChanged', $this->fields['cPrinterChanged'], null);
            
            // Update printed today pages ...
            $pmRecentCounter->updateDailyCounters('guiPagesToday', $pmRecentCounter->fields['cPagesToday'], $pmCurrentCounter);

            // Update retracted today pages ...
            $pmRecentCounter->updateDailyCounters('guiRetractedToday', $pmRecentCounter->fields['cRetractedToday'], $pmCurrentCounter);
            
            $pmCurrentCounter = $pmRecentCounter;
         }
      }
   }
   
   
   /**
    * Update daily counters ...
    */
   function updateDailyCounters($counter, $value, $previousRecordExists=null) {
      $this->fields[$counter] = $value;
      // Paper load ...
      $this->fields['cPaperLoad'] = ($this->fields['cPaperChanged'] + 1) * 2000;
      
      Toolbox::logInFile("pm", "updateDailyCounters : ".$this->fields['hostname']." / ".$this->fields['day'].", ".$counter."=".$value."\n");
      
      switch ($counter) {
         case 'cBinEmptied':
            if ($previousRecordExists) {
               if ($this->fields[$counter] > $previousRecordExists->fields[$counter]) {
                  $this->fields['cRetractedRemaining'] = 0;
               } else {
                  $this->fields['cRetractedRemaining'] = $previousRecordExists->fields['cRetractedRemaining'] + $this->fields['cRetractedToday'];
               }
            }
            // Toolbox::logInFile("pm", "Daily counter, pages retracted remaining = ".$this->fields['cRetractedRemaining']."\n");
            break;
         case 'cPrinterChanged':
            // Printer has been changed today ...
            if ($previousRecordExists) {
               if ($this->fields[$counter] > $previousRecordExists->fields[$counter]) {
                  // if ($this->fields['cPagesInitial'] == $previousRecordExists->fields['cPagesInitial']) {
                  $this->fields['cPagesInitial'] = $this->fields['cPagesTotal'] - $previousRecordExists->fields['cPagesTotal'];
                  // $this->fields['cPagesToday'] = $this->fields['cPagesTotal'] - $previousRecordExists->fields['cPagesTotal'];
                  // }
               }
            }
            
            break;
         case 'cPaperChanged':
            if ($previousRecordExists) {
               if ($this->fields[$counter] > $previousRecordExists->fields[$counter]) {
                  // Paper has been changed today ...
                  $this->fields['cPagesRemaining'] = $this->fields['cPaperLoad'] - $this->fields['cPagesTotal'];
                  // if ($this->fields['cPagesRemaining'] == $previousRecordExists->fields['cPagesRemaining']) {
                     // $this->fields['cPagesRemaining'] = $previousRecordExists->fields['cPagesRemaining'] - $this->fields['cPagesToday'] + 2000;
                  // } else {
                     // $this->fields['cPagesRemaining'] = $previousRecordExists->fields['cPagesRemaining'] - $this->fields['cPagesToday'] + 2000;
                  // }
               }
            }
            break;
         case 'cPagesTotal':
            if ($previousRecordExists) {
               // Detect printer has changed ... but not 100% reliable !
               if ($this->fields['cPagesTotal'] < $previousRecordExists->fields['cPagesTotal']) {
                  // $this->fields['cPagesInitial'] = $value - $previousRecordExists->fields['cPagesTotal'];
                  // $this->fields['cPrinterChanged'] = $previousRecordExists->fields['cPrinterChanged'] + 1;
                  Toolbox::logInFile("pm", "***** updateDailyCounters : ".$this->fields['hostname']." / ".$this->fields['day'].", ".$counter."=".$value.", cPagesTotal : ".$this->fields['cPagesTotal']." lower than previous value ".$previousRecordExists->fields['cPagesTotal']."\n");
               }
               
               // Printer has been changed today ...
               if ($this->fields['cPrinterChanged'] > $previousRecordExists->fields['cPrinterChanged']) {
                  if ($this->fields['cPagesInitial'] == $previousRecordExists->fields['cPagesInitial']) {
                     $this->fields['cPagesInitial'] = $value - $previousRecordExists->fields['cPagesTotal'];
                  }
               } else {
                  // Detect printer has changed ... but not 100% reliable !
                  // if ($this->fields['cPagesTotal'] < $previousRecordExists->fields['cPagesTotal']) {
                     // $this->fields['cPagesInitial'] = $value - $previousRecordExists->fields['cPagesTotal'];
                     // $this->fields['cPrinterChanged'] = $previousRecordExists->fields['cPrinterChanged'] + 1;
                     // Toolbox::logInFile("pm", "updateDailyCounters, today : ".$this->fields['cPagesInitial']." / ".$this->fields['cPagesTotal']." / ".$this->fields['cPagesToday']." / ".$this->fields['cPagesRemaining']."\n");
                  // } else {
                     $this->fields['cPagesInitial'] = $previousRecordExists->fields['cPagesInitial'];
                  // }
               }
               
               $this->fields['cPagesTotal'] = $value - $this->fields['cPagesInitial'];
               
               // Compute pages printed today and pages remaining ...
               $this->fields['cPagesToday'] = $this->fields['cPagesTotal'] - $previousRecordExists->fields['cPagesTotal'];

               // Paper has been changed today ...
               $this->fields['cPagesRemaining'] = $this->fields['cPaperLoad'] - $this->fields['cPagesTotal'];
               // if ($this->fields['cPaperChanged'] > $previousRecordExists->fields['cPaperChanged']) {
                  // if ($this->fields['cPagesRemaining'] == $previousRecordExists->fields['cPagesRemaining']) {
                     // $this->fields['cPagesRemaining'] = $previousRecordExists->fields['cPagesRemaining'] - $this->fields['cPagesToday'] + 2000;
                  // }
               // } else {
                  // $this->fields['cPagesRemaining'] = $previousRecordExists->fields['cPagesRemaining'] - $this->fields['cPagesToday'];
               // }
            } else {
               if ($this->fields['cPagesInitial'] == 0) $this->fields['cPagesInitial'] = $value;
               $this->fields['cPagesTotal'] = $value - $this->fields['cPagesInitial'];
               $this->fields['cPagesToday'] = $this->fields['cPagesTotal'];
               $this->fields['cPagesRemaining'] = $this->fields['cPaperLoad'] - $this->fields['cPagesToday'];
            }
            // Toolbox::logInFile("pm", "updateDailyCounters, today : ".$this->fields['cPagesInitial']." / ".$this->fields['cPagesTotal']." / ".$this->fields['cPagesToday']." / ".$this->fields['cPagesRemaining']."\n");
            break;
         case 'cRetractedTotal':
            // Set up initial pages counter if empty ...
            if ($this->fields['cRetractedInitial'] == 0) $this->fields['cRetractedInitial'] = $value;
            
            if ($previousRecordExists) {
               $this->fields['cRetractedTotal'] = $value - $this->fields['cRetractedInitial'];
               
               $this->fields['cRetractedToday'] = $this->fields['cRetractedTotal'] - $previousRecordExists->fields['cRetractedTotal'];
               
               // Bin has been emptied today ...
               if ($this->fields['cBinEmptied'] > $previousRecordExists->fields['cBinEmptied']) {
                  // Toolbox::logInFile("pm", "daily counter, bin emptied today : ".$this->fields['cRetractedRemaining']." / ".$previousRecordExists->fields['cRetractedRemaining']."\n");
                  if ($this->fields['cRetractedRemaining'] == $previousRecordExists->fields['cRetractedRemaining']) {
                     $this->fields['cRetractedRemaining'] = 0;
                  } else {
                     $this->fields['cRetractedRemaining'] = $previousRecordExists->fields['cRetractedRemaining'] + $this->fields['cRetractedToday'];
                  }
               } else {
                  $this->fields['cRetractedRemaining'] = $previousRecordExists->fields['cRetractedRemaining'] + $this->fields['cRetractedToday'];
               }
            } else {
               if ($this->fields['cRetractedInitial'] == 0) $this->fields['cRetractedInitial'] = $value;
               $this->fields['cRetractedTotal'] = $value - $this->fields['cRetractedInitial'];
               $this->fields['cRetractedToday'] = $this->fields['cRetractedTotal'];
               $this->fields['cRetractedRemaining'] = $this->fields['cRetractedToday'];
            }
            // Toolbox::logInFile("pm", "updateDailyCounters, today : ".$this->fields['cPagesInitial']." / ".$this->fields['cPagesTotal']." / ".$this->fields['cPagesToday']." / ".$this->fields['cPagesRemaining']."\n");
            break;


// Specific for printer changed by GUI ...            
         case 'guiPrinterChanged':
            $this->fields['cPrinterChanged'] = $value;
            if ($previousRecordExists) {
               $this->fields['cPagesInitial'] = $previousRecordExists->fields['cPagesInitial'];
            } else {
               $this->fields['cPagesInitial'] = 0;
            }
            if ($previousRecordExists) {
               $this->fields['cRetractedInitial'] = $previousRecordExists->fields['cRetractedInitial'];
            } else {
               $this->fields['cRetractedInitial'] = 0;
            }
            break;
            
         case 'guiPagesToday':
            if ($previousRecordExists) {
               // Toolbox::logInFile("pm", "updateDailyCounters, previous :  ".$previousRecordExists->fields['day']."\n");
               // foreach ($previousRecordExists->fields as $key => $value) {
                  // Toolbox::logInFile("pm", "updateDailyCounters, previous : $key = $value\n");
               // }
               $this->fields['cPagesRemaining'] = $previousRecordExists->fields['cPagesRemaining'] - $value;
            } else {
               $this->fields['cPagesRemaining'] = $value;
            }
            break;
            
         case 'guiRetractedToday':
            if ($previousRecordExists) {
               $this->fields['cRetractedRemaining'] = $previousRecordExists->fields['cRetractedRemaining'] + $value;
            } else {
               $this->fields['cRetractedRemaining'] = $value;
            }
            break;
      }
      $this->update($this->fields);
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
         if ($this->canUpdate() and $value['editable']) {
            echo "<td><input type='text' name='$key' value='".$this->fields[$key]."' size='8'/></td>";
         } else {
            echo "<td>".$this->getValueToDisplay($key, $this->fields[$key])."</td>";
         }
      }
      echo "</tr>";
      
      $this->showFormButtons(array("colspan" => count($this->fields)-3));

      return true;
   }

   
   function getSpecificData($hostname='%', $which='last', $date='') { 
      global $DB;      
   
      if ($date == '') $date = date('Y-m-d H:i:s');
      
      $pmCounters = new PluginMonitoringHostdailycounter();
      $counters = array();
      
      $condition = "`hostname` LIKE '$hostname'";
      $order = "`day` ASC";
      $limit = "1";
      switch ($which) {
         case 'first': 
            $condition = "`hostname` LIKE '$hostname'";
            $order = "`day` ASC";
            $limit = "1";
            break;

         case 'last': 
            $condition = "`hostname` LIKE '$hostname'";
            $order = "`day` DESC";
            $limit = "1";
            break;

         default: 
            return;
            break;
      }
      
      $a_dailyCounters = $pmCounters->find ($condition, $order, $limit);
      foreach ($a_dailyCounters as $dailyCounter) {
         $counter = array();
         foreach (self::$managedCounters as $key => $value) {
            // Toolbox::logInFile("pm", "Counter '$key' ($value) = ".$dailyCounter[$key]."\n");
            $counter['id'] = $key;
            $counter['name'] = $value['name'];
            $counter['value'] = $dailyCounter[$key];
            $counters[] = $counter;
         }
      }
   
      return $counters;
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
         $a_olderCounters = $pmCounters->find("`hostname`='".$updatable['hostname']."' AND `day` < DATE('".$updatable['date']."') ORDER BY `day` DESC LIMIT 1");
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

   // Test with service 1155 !
   static function runUpdateCountersBis($services_id = '1155') {
      global $DB;
      

      Toolbox::logInFile("pm", "runUpdateCountersBis : service identifier : $services_id\n");
      $pmCounters          = new PluginMonitoringHostdailycounter();
      $pmCurrentCounter    = new PluginMonitoringHostdailycounter();
      $pmPreviousCounter   = new PluginMonitoringHostdailycounter();

      $pmService           = new PluginMonitoringService();
      $pmServiceevent      = new PluginMonitoringServiceevent();
      $pmComponent         = new PluginMonitoringComponent();
      $pmPerfdataDetail    = new PluginMonitoringPerfdataDetail();
      
      $hostname = '';
      $date = '';
      
      $where = '';
      if ($services_id != '0') {
         $where = " WHERE `id`='".$services_id."' ";
      }
      
      $query = "SELECT * FROM `glpi_plugin_monitoring_services` ".$where;
      $result = $DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         $pmService->getFromDB($data['id']);
         $pmComponent->getFromDB($pmService->fields['plugin_monitoring_components_id']);
         $a_perfdatas = PluginMonitoringPerfdata::getArrayPerfdata($pmComponent->fields['graph_template']);
         $servicename = $pmService->getName();
         $hostname = $pmService->getHostName();

         Toolbox::logInFile("pm", "runUpdateCountersBis, service $servicename, host : $hostname, graph template : ".$pmComponent->fields['graph_template']."\n");
         
         // Actually, care only about printer and reader perfdatas ...
         // TODO !!!
         if (($a_perfdatas['command'] != 'nsca_printer')
              && ($a_perfdatas['command'] != 'nsca_reader')) continue;
              
         $nb = countElementsInTable('glpi_plugin_monitoring_serviceevents', 
               "`counters`='0'
               AND `plugin_monitoring_services_id`='".$data['id']."'");
         
         Toolbox::logInFile("pm", "runUpdateCountersBis : service ".$data['id'].", template : ".$pmComponent->fields['graph_template'].", count : $nb\n");
         
         for ($i=0; $i < $nb; $i += 10000) {
            $query2 = "SELECT * FROM `glpi_plugin_monitoring_serviceevents`
               WHERE `counters`='0'
                  AND `plugin_monitoring_services_id`='".$data['id']."'
               ORDER BY `date` ASC
               LIMIT ".$i.", 10000 ";
            $result2 = $DB->query($query2);
            while ($data2=$DB->fetch_array($result2)) {
               // Toolbox::logInFile("pm", "runUpdateCountersBis, service event ".$data2['id'].", date : ".$data2['date']."\n");
               $ret = $pmServiceevent->getData(
                       array($data2), 
                       $pmComponent->fields['graph_template'], 
                       $data2['date'],
                       $data2['date']);

               $counters = array();
               foreach ($a_perfdatas['parseperfdata'] as $perfdata) {
                  $counter = $perfdata['name'];
                  foreach ($perfdata['DS'] as $DS) {
                     $counter .= " (".$DS['dsname'].")";
                     if (isset($ret[0][$DS['dsname']]) && ($ret[0][$DS['dsname']][0] != 0)) {
                        $counter .= " = ".$ret[0][$DS['dsname']][0];
                        $counters[preg_replace("/[^A-Za-z0-9\-_]/","",$perfdata['name'])] = $ret[0][$DS['dsname']][0];
                        // Toolbox::logInFile("pm", "runUpdateCountersBis, perfdata detail : $counter\n");
                     }
                  } 
               }
               
               // Previous daily counters exist ?
               $previousRecordExists = false;
               $a_olderCounters = $pmCounters->find("`hostname`='$hostname' AND `day` < DATE('".$data2['date']."') ORDER BY `day` DESC LIMIT 1");
               foreach ($a_olderCounters as $olderCounter) {
                  // Found an older daily counter row ...
                  if ($pmPreviousCounter->getFromDBByQuery("WHERE `hostname`='$hostname' AND `day`='".$olderCounter['day']."'")) {
                     $previousRecordExists = true;
                  }
               }
               
               // Current daily counters exist ?
               $currentRecordExists = false;
               $a_dailyCounters = $pmCounters->find("`hostname`='$hostname' AND `day`=DATE('".$data2['date']."')");
               foreach ($a_dailyCounters as $dailyCounter) {
                  // Found a daily counter row to be updated ...
                  if ($pmCurrentCounter->getFromDBByQuery("WHERE `hostname`='$hostname' AND `day`='".$dailyCounter['day']."'")) {
                     // Daily record still exists ... update it !
                     foreach ($counters as $counter=>$value) {
                        if ($counter=='CutPages') $counter='cPagesTotal';
                        if ($counter=='RetractedPages') $counter='cRetractedTotal';
                        Toolbox::logInFile("pm", "runUpdateCountersBis, $counter = $value\n");
                        $pmCurrentCounter->updateDailyCounters($counter, $value, ($previousRecordExists) ? $pmPreviousCounter : null);
                     }
                  }
                  $currentRecordExists = true;
               }
               if (! $currentRecordExists) {
                  // We never recorded anything for the day ... create first record !
                  $pmCurrentCounter->getEmpty();
                  $pmCurrentCounter->setDefaultContent($hostname, $data2['date'], ($previousRecordExists) ? $pmPreviousCounter : null);
                  $pmCurrentCounter->add($pmCurrentCounter->fields);
                  foreach ($counters as $counter=>$value) {
                     if ($counter=='CutPages') $counter='cPagesTotal';
                     if ($counter=='RetractedPages') $counter='cRetractedTotal';
                     Toolbox::logInFile("pm", "runUpdateCountersBis, $counter = $value\n");
                     $pmCurrentCounter->updateDailyCounters($counter, $value, ($previousRecordExists) ? $pmPreviousCounter : null);
                  }
               }
               
               $input = array();
               $input['id'] = $data2['id'];
               $input['counters'] = 1;
               $pmServiceevent->update($input);
            }
         }
      }
   }
   
   
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
}

?>