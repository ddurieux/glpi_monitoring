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
      $tab[4]['field']           = 'cPagesTotal';
      $tab[4]['name']            = __('Cumulative total for printed pages', 'monitoring');
      $tab[4]['massiveaction']   = false;

      $tab[5]['table']           = $this->getTable();
      $tab[5]['field']           = 'cPagesToday';
      $tab[5]['name']            = __('Daily printed pages', 'monitoring');
      $tab[5]['massiveaction']   = false;

      $tab[6]['table']           = $this->getTable();
      $tab[6]['field']           = 'cPagesRemaining';
      $tab[6]['name']            = __('Remaining pages', 'monitoring');
      $tab[6]['massiveaction']   = false;

      $tab[7]['table']           = $this->getTable();
      $tab[7]['field']           = 'cRetractedTotal';
      $tab[7]['name']            = __('Cumulative total for retracted pages', 'monitoring');
      $tab[7]['massiveaction']   = false;

      $tab[8]['table']           = $this->getTable();
      $tab[8]['field']           = 'cRetractedToday';
      $tab[8]['name']            = __('Daily retracted pages', 'monitoring');
      $tab[8]['massiveaction']   = false;

      $tab[9]['table']           = $this->getTable();
      $tab[9]['field']           = 'cRetractedRemaining';
      $tab[9]['name']            = __('Stored retracted pages', 'monitoring');
      $tab[9]['massiveaction']   = false;

      $tab[10]['table']          = $this->getTable();
      $tab[10]['field']          = 'cPrinterChanged';
      $tab[10]['name']           = __('Cumulative total for printer changed', 'monitoring');
      $tab[10]['massiveaction']  = false;

      $tab[11]['table']          = $this->getTable();
      $tab[11]['field']          = 'cPaperChanged';
      $tab[11]['name']           = __('Cumulative total for paper changed', 'monitoring');
      $tab[11]['massiveaction']  = false;

      $tab[12]['table']          = $this->getTable();
      $tab[12]['field']          = 'cBinEmptied';
      $tab[12]['name']           = __('Cumulative total for bin emptied', 'monitoring');
      $tab[12]['massiveaction']  = false;

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
      }
      return parent::getSpecificValueToDisplay($field, $values, $options);
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
}

?>