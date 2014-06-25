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

   static function getManagedCounters() {
      return array(
         'cPagesInitial'   => array(
            'service'   => 'printer',
            'name'      => __('Initial counter for printed pages', 'monitoring'),
            'default'   => 0,
            'hidden'    => true,
         ),
         'cPagesTotal'     => array(
            'service'   => 'printer',
            'type'      => 'total',
            'name'      => __('Total printed pages', 'monitoring'),
            'display'   => true,
            'default'   => 0,
            'editable'  => true,
            'max'       => 'max',
            'avg'       => 'avg',
            'sum'       => 'max',
         ),
         'cPagesToday'     => array(
            'service'   => 'printer',
            'name'      => __('Daily printed pages', 'monitoring'),
            'display'   => true,
            'default'   => 0,
            'editable'  => true,
            'max'       => 'max',
            'avg'       => 'avg',
            'sum'       => 'sum',
         ),
         'cPagesRemaining' => array(
            'service'         => 'printer',
            'name'            => __('Remaining pages', 'monitoring'),
            'display'         => true,
            'default'         => 2000,
            'editable'        => true,
            'lowThreshold'    => 200,
            'zeroDetection'   => array (
               "days"      => 3,             // How many days for paper end detection ...
               "weekend"   => false,         // Do not include WE days
               "counter"   => 'cPagesToday', // Which mean counter is to be used ?
            )
         ),
         'cRetractedInitial' => array(
            'service'   => 'printer',
            'name'      => __('Initial counter for retracted pages', 'monitoring'),
            'default'   => 0,
         ),
         'cRetractedTotal' => array(
            'service'   => 'printer',
            'type'      => 'total',
            'name'      => __('Total retracted pages', 'monitoring'),
            'display'   => true,
            'default'   => 0,
            'editable'  => true,
            'max'       => 'max',
            'avg'       => 'avg',
            'sum'       => 'max',
         ),
         'cRetractedToday' => array(
            'service'   => 'printer',
            'name'      => __('Daily retracted pages', 'monitoring'),
            'display'   => true,
            'default'   => 0,
            'editable'  => true,
            'max'       => 'max',
            'avg'       => 'avg',
            'sum'       => 'sum',
         ),
         'cRetractedRemaining' => array(
            'service'   => 'printer',
            'name'     => __('Stored retracted pages', 'monitoring'),
            'default'  => 0,
         ),
         'cPrinterChanged' => array(
            'service'   => 'printer',
            'name'     => __('Total for printer changed', 'monitoring'),
            'default'  => 0,
            'editable' => true,
         ),
         'cPaperChanged' => array(
            'service'   => 'printer',
            'name'     => __('Total for paper changed', 'monitoring'),
            'default'  => 0,
            'editable' => true,
         ),
         'cBinEmptied'   => array(
            'service'   => 'printer',
            'name'     => __('Total for bin emptied', 'monitoring'),
            'default'  => 0,
            'editable' => true,
         ),
         'cPaperLoad'    => array(
            'service'   => 'printer',
            'name'     => __('Paper load', 'monitoring'),
            'default'  => 2000,
         ),
         'cCardsInsertedOkToday' => array(
            'service'   => 'cards',
            'name'      => __('Daily inserted cards', 'monitoring'),
            'display'   => true,
            'default'   => 0,
            'editable'  => true,
            'max'       => 'max',
            'avg'       => 'avg',
            'sum'       => 'sum',
         ),
         'cCardsInsertedOkTotal' => array(
            'service'   => 'cards',
            'type'      => 'total',
            'name'      => __('Total inserted cards', 'monitoring'),
            'display'   => true,
            'default'   => 'previous',
            'editable'  => true,
            'max'       => 'max',
         ),
         'cCardsInsertedKoToday' => array(
            'service'   => 'cards',
            'name'      => __('Daily bad cards', 'monitoring'),
            'default'   => 0,
            'editable'  => true,
            'max'       => 'max',
            'avg'       => 'avg',
            'sum'       => 'sum',
         ),
         'cCardsInsertedKoTotal' => array(
            'service'   => 'cards',
            'type'      => 'total',
            'name'      => __('Total bad cards', 'monitoring'),
            'default'   => 'previous',
            'editable'  => true,
            'max'       => 'max',
         ),
         'cCardsRemovedToday' => array(
            'service'   => 'cards',
            'name'      => __('Daily removed cards', 'monitoring'),
            'display'   => true,
            'default'   => 0,
            'editable'  => true,
            'max'       => 'max',
            'avg'       => 'avg',
            'sum'       => 'sum',
         ),
         'cCardsRemovedTotal' => array(
            'service'   => 'cards',
            'type'      => 'total',
            'name'      => __('Total removed cards', 'monitoring'),
            'display'   => true,
            'default'   => 'previous',
            'editable'  => true,
            'max'       => 'max',
         ),
      );
   }


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
            Search::manageGetValues(self::getTypeName());
            Search::showList(self::getTypeName(), array(
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

      $tab[2]['table']           = "glpi_computers";
      $tab[2]['field']           = 'name';
      $tab[2]['name']            = __('Computer');
      $tab[2]['datatype']        = 'itemlink';

      $tab[3]['table']           = "glpi_entities";
      $tab[3]['field']           = 'name';
      $tab[3]['name']            = __('Entity');
      $tab[3]['datatype']        = 'itemlink';

      $tab[4]['table']           = $this->getTable();
      $tab[4]['field']           = 'day';
      $tab[4]['name']            = __('Day', 'monitoring');
      $tab[4]['datatype']        = 'date';
      $tab[4]['massiveaction']   = false;

      $i = 5;
      foreach (self::getManagedCounters() as $key => $value) {
         $tab[$i]['table']          = $this->getTable();
         $tab[$i]['field']          = $key;
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

      foreach (self::getManagedCounters() as $key => $value) {
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

      echo "<tr><td colspan='".count(self::getManagedCounters())."'><hr/></td></tr>";

      echo "<tr class='tab_bg_1'>";
      foreach (self::getManagedCounters() as $key => $value) {
         echo "<td align='center'>".__($value['name'], 'monitoring')."</td>";
      }
      echo "</tr>";

      echo "<tr class='tab_bg_2'>";
      foreach (self::getManagedCounters() as $key => $value) {
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
         foreach (self::getManagedCounters() as $key => $value) {
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
         foreach (self::getManagedCounters() as $key => $value) {
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
      global $DB, $CFG_GLPI;

      if ($date == '') $date = date('Y-m-d H:i:s');

      // Check out more recent counters per host ... 
      $a_checkables = PluginMonitoringHostdailycounter::getLastCountersPerHost(
         array (
            'start'  => 0,
            'limit'  => 10000
            )
      );
      
      // Check out average printed pages on each kiosk per each day type ... 
      $average = PluginMonitoringHostdailycounter::getStatistics(
         array (
            'start'  => 0,
            'limit'  => 2000,
            'type'   => 'avg',
            'group'  => 'hostname, dayname'
            )
      );

                  
      // Ticket SLA ...
      $sla = new Sla();
      $slas = current($sla->find("`name` LIKE '%proactive%' LIMIT 1"));
      $sla_id = isset($slas['id']) ? $slas['id'] : 0;
      $sla_name = Dropdown::getDropdownName("glpi_slas", $sla_id);
      
      // Ticket category ...
      $category = new ITILCategory();
      $categories = current($category->find("`name` LIKE '%rechargement%' LIMIT 1"));
      $category_id = isset($categories['id']) ? $categories['id'] : 0;
      $category_name = Dropdown::getDropdownName("glpi_itilcategories", $category_id);
      
      // Check all counters for zero detection ...
      foreach (self::getManagedCounters() as $key => $value) {
         if (! isset($value['zeroDetection'])) continue;
         
         $firstDetection = false;
         $daysnameidx = Toolbox::getDaysOfWeekArray();
         $todayNum = date('w', date('U'));
         $todayName = $daysnameidx[$todayNum];
         foreach ($a_checkables as $checkable) {
            // Toolbox::logInFile("pm-checkCounters", "Counter '$key' for '".$checkable['hostname'] . "', counter : ". $checkable[$key] . " (". $value['zeroDetection']['days'] . " days / ". $value['zeroDetection']['counter'] . ")\n");
            
            $filter = array (
               'start'  => 0,
               'limit'  => 2000,
               'statistics'   => 'avg',
               'group'  => 'hostname, dayname'
            );

            $nextDayNum = $todayNum;
            $listDays = array();
            
            // Week days excluding saturday and sunday
            for ($nbDays = $value['zeroDetection']['days']; $nbDays >= 0; ) {
               if (! $value['zeroDetection']['weekend']) {
                  // Skip Saturday ...
                  if ($nextDayNum == 6) {
                     $nextDayNum = 0; continue;
                  }
                  // Skip Sunday ...
                  if ($nextDayNum == 0) {
                     $nextDayNum = 1; continue;
                  }
               }
               
               $nbDays -= 1;
               $listDays[] = $daysnameidx[$nextDayNum];
               
               $nextDayNum++;
               if ($nextDayNum == 7) {
                  $nextDayNum = 0;
               }
            }
            $filter['filter'] = "hostname = '".$checkable['hostname']."' AND dayname IN ('".implode("','", $listDays) . "')";
            
            $breadcrumb = $checkable[$key];
            $breadcrumb = "";
            $currentValue = $checkable[$key];
            $average = PluginMonitoringHostdailycounter::getStatistics($filter);
            foreach ($average as $line) {
               if ($checkable['hostname'] == $line['hostname']) {
                  $checkable[$key] -= $line['avg_'.$value['zeroDetection']['counter']];
                  $breadcrumb .= ' - ' . $line['avg_'.$value['zeroDetection']['counter']];
               }
            }
            // Toolbox::logInFile("pm-checkCounters", "Counter '$key' for '".$checkable['hostname'] . "', counter : ". $checkable[$key] . "=" . $breadcrumb . "\n");
            if ($checkable[$key] <= 0) {
               if (! $firstDetection) {
                  echo '<table class="tab_cadre_fixe">';
                  echo '<tr class="tab_bg_1"><th colspan="4">';
                  echo __('Hosts out of paper in ', 'monitoring') . $value['zeroDetection']['days'] . __(' days.', 'monitoring');
                  if (PluginMonitoringProfile::haveRight("counters", 'w')) {
                     echo " ('". implode("','", $listDays). "')";
                  }
                  echo '</th></tr>';
                  echo '<tr>';
                  echo '<th>';
                  echo '';
                  echo '</th>';
                  echo '<th>';
                  echo 'Current Host / services status';
                  echo '</th>';
                  echo '<th colspan="2">';
                  echo 'Ticket';
                  echo '</th>';
                  echo '</tr>';

                  $firstDetection = true;
               }
      
               // Search existing tickets ...
               // Find computer ...
               $pmComputer = new Computer();
               $computer = current($pmComputer->find("`name`='".$checkable['hostname']."' LIMIT 1"));
               // ... and monitoring host.
               $pmHost = new PluginMonitoringHost();
               $host = current($pmHost->find("`itemtype`='Computer' AND `items_id` = '".$computer['id']."' LIMIT 1"));
               $pmHost->getFromDB($host['id']);
               
               echo '<tr class="tab_bg_1">';
               echo '<td>';
               echo $pmHost->getLink() . __(' will be out of paper in ', 'monitoring');
               echo $value['zeroDetection']['days'];
               echo __(' days ', 'monitoring');
               if (PluginMonitoringProfile::haveRight("counters", 'w')) {
                  echo "&nbsp;".Html::showToolTip(
                     "'$key', current value = ".$currentValue.", current and next days values: ".$breadcrumb." = ".$checkable[$key]
                     , array('display' => false));
               }
               echo '</td>';
               echo '<td>';
               
               // Get all host services except if state is ok ...
               $a_ret = PluginMonitoringHost::getServicesState($pmHost->getField('id'),
                                                               "`glpi_plugin_monitoring_services`.`state` != 'OK'");
               echo $pmHost->getField('state') . '&nbsp; / &nbsp;';
               echo $a_ret[0] . '&nbsp;';
               if (!empty($a_ret[1])) {
                  echo "&nbsp;".Html::showToolTip($a_ret[1], array('display' => false));
               }
               echo '</td>';
      
               // Find tickets not closed with SLA and category ...
               $track = new Ticket();
               $tickets = current($track->find("`status`<>'".Ticket::SOLVED."' AND `status`<>'".Ticket::CLOSED."' AND `slas_id`='$sla_id' AND `itilcategories_id`='$category_id' AND `itemtype`='Computer' AND `items_id` = '".$computer['id']."' ORDER BY `id` DESC LIMIT 1"));
               if (isset($tickets['id']) && $tickets['id']!='0') {
                  // Find ticket in DB ...
                  $track = new Ticket();
                  $track->getFromDB($tickets['id']);

                  // Display ticket id, name and tracking ...
                  $bgcolor = $_SESSION["glpipriority_".$track->fields["priority"]];
                  echo "<td class='center' bgcolor='$bgcolor'>".sprintf(__('%1$s: %2$s'), __('ID'),
                                                                        $track->fields["id"])."</td>";
                  echo "<td class='center'>";
                  $showprivate = Session::haveRight("show_full_ticket", 1);
                  $link = "<a id='ticket".$track->fields["id"]."' href='".$CFG_GLPI["root_doc"].
                            "/front/ticket.form.php?id=".$track->fields["id"];
                  $link .= "'>";
                  $link .= "<span class='b'>".$track->getNameID()."</span></a>";
                  $link = sprintf(__('%1$s (%2$s)'), $link,
                                  sprintf(__('%1$s - %2$s'), $track->numberOfFollowups($showprivate),
                                          $track->numberOfTasks($showprivate)));
                  $link = printf(__('%1$s %2$s'), $link,
                                 Html::showToolTip($track->fields['content'],
                                                   array('applyto' => 'ticket'.$track->fields["id"],
                                                         'display' => false)));
                  echo '</td>';
               } else {
                  echo '<td colspan="2">';
                  
                  if (PluginMonitoringProfile::haveRight("counters", 'w')) {
                     // Form to create a ticket ...
                     echo '<form name="form" method="post"
                        action="'.$CFG_GLPI['root_doc'].'/front/ticket.form.php">';

                     echo '<input type="hidden" name="itemtype" value="Computer" />';
                     echo '<input type="hidden" name="items_id" value="'.$computer['id'].'" />';
                     echo '<input type="hidden" name="locations_id" value="'.$computer['locations_id'].'" />';
                     echo '<input type="hidden" name="slas_id" value="'.$sla_id.'" />';
                     echo '<input type="hidden" name="itilcategories_id" value="'.$category_id.'" />';
                     $track_name = __('End paper prediction', 'monitoring')." / ".$sla_name." / ".$category_name;
                     echo '<input type="hidden" name="name" value="'.$track_name.'" />';
                     echo '<input type="hidden" name="content" value="'.$track_name.'" />';
                     
                     // Find ticket template if available ...
                     $track = new Ticket();
                     $tt = $track->getTicketTemplateToUse(0, Ticket::DEMAND_TYPE, $category_id, 0);
                                                         
                     if (isset($tt->predefined) && count($tt->predefined)) {
                        foreach ($tt->predefined as $predeffield => $predefvalue) {
                           // Load template data
                           $values[$predeffield]            = $predefvalue;
                           echo '<input type="hidden" name="'.$predeffield.'" value="'.$predefvalue.'" />';
                        }
                     }
                     
                     echo '<input type="submit" name="add" value="'.__('Add a ticket', 'monitoring').'" class="submit">';
                     Html::closeForm();
                  } else {
                     echo __('Paper replacement ticket not yet created.', 'monitoring');
                  }
                  echo '</td>';
               }
                        
               echo '</tr>';
/*
               
               if (PluginMonitoringProfile::haveRight("counters", 'w')) {
                  echo '<tr class="tab_bg_1">';
                  echo '<td colspan="3">';
                  echo "'$key', current value = ".$currentValue.", next days values: ".$breadcrumb." = ".$checkable[$key];
                  echo '</td>';
                  echo '</tr>';
               }
*/
            }
         }
         if ($firstDetection) {
            echo "</table>";
         }
      }

      if (PluginMonitoringProfile::haveRight("counters", 'w')) {
         // Check all counters for negative values ...
         $firstDetection = false;
         foreach ($a_checkables as $checkable) {
            foreach (self::getManagedCounters() as $key => $value) {
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
            foreach (self::getManagedCounters() as $key => $value) {
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

         // Check all counters for incorrect total per day values ...
         $initialValues = array (
            'ek3k-cnam-0001' => array('25/12/13 20:43', '113'),
            'ek3k-cnam-0002' => array('18/12/13 16:52', '639'),
            'ek3k-cnam-0003' => array('25/12/13 20:40', '71'),
            'ek3k-cnam-0006' => array('25/12/13 20:40', '99'),
            'ek3k-cnam-0010' => array('25/12/13 20:40', '781'),
            'ek3k-cnam-0011' => array('25/12/13 20:40', '63'),
            'ek3k-cnam-0012' => array('25/12/13 20:40', '611'),
            'ek3k-cnam-0013' => array('25/12/13 20:40', '1162'),
            'ek3k-cnam-0014' => array('25/12/13 20:40', '730'),
            'ek3k-cnam-0015' => array('25/12/13 20:40', '97'),
            'ek3k-cnam-0016' => array('25/12/13 20:40', '870'),
            'ek3k-cnam-0017' => array('25/12/13 20:40', '1059'),
            'ek3k-cnam-0018' => array('25/12/13 20:40', '826'),
            'ek3k-cnam-0019' => array('25/12/13 20:40', '915'),
            'ek3k-cnam-0020' => array('25/12/13 20:40', '473'),
            'ek3k-cnam-0021' => array('25/12/13 20:40', '487'),
            'ek3k-cnam-0022' => array('25/12/13 20:40', '397'),
            'ek3k-cnam-0023' => array('25/12/13 20:40', '917'),
            'ek3k-cnam-0024' => array('25/12/13 20:40', '1116'),
            'ek3k-cnam-0025' => array('25/12/13 20:40', '143'),
            'ek3k-cnam-0026' => array('25/12/13 20:40', '713'),
            'ek3k-cnam-0028' => array('25/12/13 20:40', '766'),
            'ek3k-cnam-0029' => array('25/12/13 20:40', '613'),
            'ek3k-cnam-0030' => array('25/12/13 20:40', '821'),
            'ek3k-cnam-0031' => array('25/12/13 20:40', '1015'),
            'ek3k-cnam-0032' => array('25/12/13 20:40', '972'),
            'ek3k-cnam-0033' => array('25/12/13 20:40', '853'),
            'ek3k-cnam-0034' => array('25/12/13 20:40', '537'),
            'ek3k-cnam-0035' => array('25/12/13 20:40', '422'),
            'ek3k-cnam-0036' => array('25/12/13 20:40', '714'),
            'ek3k-cnam-0037' => array('25/12/13 20:40', '683'),
            'ek3k-cnam-0038' => array('25/12/13 20:40', '509'),
            'ek3k-cnam-0039' => array('25/12/13 20:40', '1031'),
            'ek3k-cnam-0040' => array('25/12/13 20:40', '310'),
            'ek3k-cnam-0041' => array('25/12/13 20:40', '255'),
            'ek3k-cnam-0042' => array('25/12/13 20:40', '321'),
            'ek3k-cnam-0044' => array('25/12/13 20:40', '213'),
            'ek3k-cnam-0045' => array('25/12/13 20:40', '491'),
            'ek3k-cnam-0046' => array('25/12/13 20:40', '261'),
            'ek3k-cnam-0047' => array('25/12/13 20:40', '215'),
            'ek3k-cnam-0048' => array('25/12/13 20:40', '690'),
            'ek3k-cnam-0049' => array('25/12/13 20:40', '387'),
            'ek3k-cnam-0050' => array('25/12/13 20:40', '265'),
            'ek3k-cnam-0051' => array('25/12/13 20:40', '265'),
            'ek3k-cnam-0052' => array('25/12/13 20:40', '285'),
            'ek3k-cnam-0055' => array('25/12/13 20:40', '133'),
            'ek3k-cnam-0056' => array('25/12/13 20:40', '839'),
            'ek3k-cnam-0057' => array('25/12/13 20:40', '268'),
            'ek3k-cnam-0058' => array('21/12/13 10:10', '221'),
            'ek3k-cnam-0059' => array('25/12/13 20:40', '614'),
            'ek3k-cnam-0061' => array('25/12/13 20:39', '429'),
            'ek3k-cnam-0062' => array('25/12/13 20:40', '403'),
            'ek3k-cnam-0063' => array('25/12/13 20:40', '380'),
            'ek3k-cnam-0064' => array('25/12/13 20:40', '156'),
            'ek3k-cnam-0065' => array('25/12/13 20:40', '406'),
            'ek3k-cnam-0066' => array('25/12/13 20:40', '262'),
            'ek3k-cnam-0067' => array('25/12/13 20:40', '866'),
            'ek3k-cnam-0068' => array('25/12/13 20:40', '328'),
            'ek3k-cnam-0069' => array('25/12/13 20:40', '317'),
            'ek3k-cnam-0070' => array('25/12/13 20:40', '512'),
            'ek3k-cnam-0071' => array('25/12/13 20:39', '311'),
            'ek3k-cnam-0072' => array('25/12/13 20:40', '474'),
            'ek3k-cnam-0073' => array('25/12/13 20:40', '493'),
            'ek3k-cnam-0074' => array('25/12/13 20:40', '348'),
            'ek3k-cnam-0075' => array('25/12/13 20:40', '269'),
            'ek3k-cnam-0076' => array('23/12/13 16:10', '218'),
            'ek3k-cnam-0077' => array('25/12/13 20:40', '100'),
            'ek3k-cnam-0078' => array('25/12/13 20:40', '290'),
            'ek3k-cnam-0079' => array('25/12/13 20:40', '240'),
            'ek3k-cnam-0080' => array('25/12/13 20:40', '343'),
            'ek3k-cnam-0081' => array('25/12/13 20:40', '0'),
            'ek3k-cnam-0082' => array('25/12/13 20:40', '120'),
            'ek3k-cnam-0083' => array('25/12/13 20:40', '143'),
            'ek3k-cnam-0084' => array('25/12/13 20:41', '193'),
            'ek3k-cnam-0085' => array('25/12/13 20:40', '565'),
            'ek3k-cnam-0086' => array('23/12/13 16:10', '156'),
            'ek3k-cnam-0087' => array('25/12/13 20:40', '457'),
            'ek3k-cnam-0088' => array('25/12/13 20:40', '105'),
            'ek3k-cnam-0089' => array('25/12/13 20:40', '66'),
            'ek3k-cnam-0090' => array('25/12/13 20:40', '138'),
            'ek3k-cnam-0091' => array('25/12/13 20:40', '374'),
            'ek3k-cnam-0093' => array('25/12/13 20:40', '41'),
            'ek3k-cnam-0094' => array('25/12/13 20:40', '345'),
            'ek3k-cnam-0095' => array('25/12/13 20:40', '106'),
            'ek3k-cnam-0096' => array('25/12/13 20:40', '290'),
            'ek3k-cnam-0097' => array('25/12/13 20:40', '420'),
            'ek3k-cnam-0098' => array('25/12/13 20:40', '258'),
            'ek3k-cnam-0099' => array('25/12/13 20:40', '89'),
            'ek3k-cnam-0101' => array('25/12/13 20:40', '208'),
            'ek3k-cnam-0102' => array('25/12/13 20:40', '31'),
            'ek3k-cnam-0103' => array('25/12/13 20:40', '130'),
            'ek3k-cnam-0104' => array('25/12/13 20:40', '211'),
            'ek3k-cnam-0105' => array('25/12/13 20:40', '208'),
            'ek3k-cnam-0106' => array('25/12/13 20:40', '0'),
            'ek3k-cnam-0107' => array('25/12/13 20:40', '108'),
            'ek3k-cnam-0108' => array('25/12/13 20:40', '40'),
            'ek3k-cnam-0109' => array('25/12/13 20:40', '79'),
            'ek3k-cnam-0110' => array('25/12/13 20:40', '0'),
            'ek3k-cnam-0111' => array('25/12/13 20:40', '0'),
            'ek3k-cnam-0112' => array('25/12/13 20:40', '5'),
            'ek3k-cnam-0113' => array('25/12/13 20:40', '11'),
            'ek3k-cnam-0114' => array('25/12/13 20:40', '180'),
            'ek3k-cnam-0115' => array('25/12/13 20:40', '46'),
            'ek3k-cnam-0116' => array('25/12/13 20:40', '77'),
            'ek3k-cnam-0117' => array('25/12/13 20:40', '135'),
            'ek3k-cnam-0118' => array('25/12/13 20:40', '87'),
            'ek3k-cnam-0119' => array('25/12/13 20:40', '1'),
            'ek3k-cnam-0120' => array('25/12/13 20:40', '25'),
            'ek3k-cnam-0121' => array('25/12/13 20:40', '41'),
            'ek3k-cnam-0122' => array('25/12/13 20:40', '17'),
            'ek3k-cnam-0123' => array('25/12/13 07:10', '63'),
            'ek3k-cnam-0124' => array('25/12/13 20:40', '9'),
            'ek3k-cnam-0127' => array('25/12/13 20:40', '161'),
            'ek3k-cnam-0128' => array('25/12/13 20:40', '25'),
            'ek3k-cnam-0129' => array('25/12/13 20:40', '2'),
            'ek3k-cnam-0130' => array('23/12/13 15:10', '0'),
            'ek3k-cnam-0132' => array('25/12/13 20:40', '135'),
            'ek3k-cnam-0133' => array('25/12/13 20:40', '83'),
            'ek3k-cnam-0134' => array('25/12/13 20:40', '54'),
            'ek3k-cnam-0135' => array('25/12/13 20:40', '7'),
            'ek3k-cnam-0136' => array('25/12/13 20:40', '34'),
            'ek3k-cnam-0139' => array('25/12/13 20:40', '13'),
            'ek6k-cnam-0001' => array('25/12/13 23:59', '6'),
            'ek6k-cnam-0004' => array('25/12/13 23:45', '57'),
            'ek6k-cnam-0006' => array('25/12/13 23:45', '4'),
            'ek6k-cnam-0011' => array('25/12/13 23:45', '2'),
         );
         
         $firstDetection = false;
         $hosts = 0;
         foreach ($a_checkables as $checkable) {
            $firstDay = true;
            $printedPages = 0;
            $retractedPages = 0;
            
            $hostname = $checkable['hostname'];
            
            // Find counters for the host ...
            $pmCounters = new PluginMonitoringHostdailycounter();
            foreach ($pmCounters->find("`hostname`='$hostname' ORDER BY `day` ASC LIMIT 1") as $dailyCounters) {
               // echo __('Host', 'monitoring') ." '$hostname' ". __(', day: ', 'monitoring'). $dailyCounters['day'] . __(', pages counters, total : ', 'monitoring') .$dailyCounters['cPagesTotal']. __(', today : ', 'monitoring'). $dailyCounters['cPagesToday'];
               
               if ($dailyCounters['cPagesToday'] != $dailyCounters['cPagesTotal']) {
                  $printedPages = 0;
                  if (isset($initialValues[$hostname])) {
                     echo __(', initial value (', 'monitoring'). $initialValues[$hostname][0] .") ". $initialValues[$hostname][1];
                     
                     $printedPages = $initialValues[$hostname][1] + $dailyCounters['cPagesToday'];
                  } else {
                     $printedPages = $dailyCounters['cPagesToday'];
                  }
                  echo __(' ==> ', 'monitoring') .$printedPages. '<br/>';
                  
                  // Update current day and more recent days ...
                  // Set session variable to avoid post_update treatment ...
                  $_SESSION['plugin_monitoring_hostdailyupdate'] = true;
                  
                  // Update first day record
                  $dailyCounters['cPagesToday'] = $printedPages;
                  $dailyCounters['cPagesTotal'] = $printedPages;
                  unset($dailyCounters['hostname']);
                  $pmCounters->update($dailyCounters);
                  // echo __(' Update: ', 'monitoring') .serialize($dailyCounters). '<br/>';
                  echo __(' Update from: ', 'monitoring') .$dailyCounters['day']. ' / '.$dailyCounters['cPagesToday']. ' / '.$dailyCounters['cPagesTotal'];
                  
                  $a_data = getAllDatasFromTable(
                              'glpi_plugin_monitoring_hostdailycounters',
                              "`day` > '".$dailyCounters['day']."'
                                 AND `hostname`='$hostname'",
                              false,
                              '`day` ASC');

                  foreach ($a_data as $data) {
                     $printedPages += $data['cPagesToday'];
                     
                     if ($printedPages != $data['cPagesTotal']) {
                        $data['cPagesTotal'] = $printedPages;
                     
                        unset($data['hostname']);
                        $pmCounters->update($data);
                     }
                  }
                  if (isset($data)) {
                     echo __(' to: ', 'monitoring') .$data['day']. ' / '.$data['cPagesToday']. ' / '.$data['cPagesTotal']. '<br/>';
                  }
                  unset($_SESSION['plugin_monitoring_hostdailyupdate']);
                  // die('Test de Fred !');
               } else {
                  // echo '<br/>';
               }
               
               if ($dailyCounters['cPagesRemaining'] != 2000 - $dailyCounters['cPagesTotal']) {
                  echo __('Host', 'monitoring') ." '$hostname' ". __(' Update from: ', 'monitoring') .$dailyCounters['day']. ' / '.$dailyCounters['cPagesTotal']. ', remaining: '.$dailyCounters['cPagesRemaining'].'<br/>';

                  // Update current day and more recent days ...
                  // Set session variable to avoid post_update treatment ...
                  $_SESSION['plugin_monitoring_hostdailyupdate'] = true;
                  
                  $pagesRemaining = 2000 - $dailyCounters['cPagesTotal'];
                  $paperChanged = $dailyCounters['cPaperChanged'];
                  
                  // Update first day record
                  $dailyCounters['cPagesRemaining'] = $pagesRemaining;
                  unset($dailyCounters['hostname']);
                  $pmCounters->update($dailyCounters);
                  echo __(' Update from: ', 'monitoring') .$dailyCounters['day']. ' / '.$dailyCounters['cPagesTotal']. ', remaining: '.$dailyCounters['cPagesRemaining'];
                  
                  $a_data = getAllDatasFromTable(
                              'glpi_plugin_monitoring_hostdailycounters',
                              "`day` > '".$dailyCounters['day']."'
                                 AND `hostname`='$hostname'",
                              false,
                              '`day` ASC');

                  foreach ($a_data as $data) {
                     if ($paperChanged != $data['cPaperChanged']) {
                        $paperChanged = $data['cPaperChanged'];
                        $pagesRemaining = 2000 - $data['cPagesToday'];
                     } else {
                        $pagesRemaining -= $data['cPagesToday'];
                     }
                     $data['cPagesRemaining'] = $pagesRemaining;
                     unset($data['hostname']);
                     $pmCounters->update($data);
                  }
                  if (isset($data)) {
                     echo __(' to: ', 'monitoring') .$data['day']. ' / '.$data['cPagesToday']. ' / '.$data['cPagesTotal']. '<br/>';
                  }
                  unset($_SESSION['plugin_monitoring_hostdailyupdate']);
                  // die ('Test de Fred ...');
               } else {
                  // echo '<br/>';
               }
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

      Toolbox::logInFile("pm-counters", "Begin ---------- \n");
      /*
	   * Manage values from previous database server ...
       */
      $initialValues = array (
         'ek3k-cnam-0081' => array('2013-12-25', '127', '4', '0', '0'),
         'ek3k-cnam-0106' => array('2013-12-25', '126', '5', '0', '0'),
         'ek3k-cnam-0110' => array('2013-12-25', '99', '4', '0', '0'),
         'ek3k-cnam-0111' => array('2013-12-25', '109', '3', '0', '0'),
         'ek3k-cnam-0130' => array('2013-12-23', '116', '3', '0', '0'),
         'ek3k-cnam-0119' => array('2013-12-25', '114', '3', '1', '0'),
         'ek3k-cnam-0129' => array('2013-12-25', '141', '4', '2', '0'),
         'ek6k-cnam-0011' => array('2013-12-25', '108', '4', '2', '0'),
         'ek6k-cnam-0006' => array('2013-12-25', '338', '12', '4', '0'),
         'ek3k-cnam-0112' => array('2013-12-25', '106', '3', '5', '0'),
         'ek6k-cnam-0001' => array('2013-12-25', '318', '63', '6', '1'),
         'ek3k-cnam-0135' => array('2013-12-25', '98', '4', '7', '0'),
         'ek3k-cnam-0124' => array('2013-12-25', '134', '4', '9', '0'),
         'ek3k-cnam-0113' => array('2013-12-25', '151', '6', '11', '0'),
         'ek3k-cnam-0139' => array('2013-12-25', '144', '2', '13', '0'),
         'ek3k-cnam-0122' => array('2013-12-25', '109', '3', '17', '0'),
         'ek3k-cnam-0120' => array('2013-12-25', '152', '5', '25', '0'),
         'ek3k-cnam-0128' => array('2013-12-25', '153', '4', '25', '0'),
         'ek3k-cnam-0102' => array('2013-12-25', '143', '4', '31', '0'),
         'ek3k-cnam-0136' => array('2013-12-25', '126', '3', '34', '0'),
         'ek3k-cnam-0108' => array('2013-12-25', '140', '3', '40', '0'),
         'ek3k-cnam-0093' => array('2013-12-25', '225', '5', '41', '0'),
         'ek3k-cnam-0121' => array('2013-12-25', '141', '3', '41', '0'),
         'ek3k-cnam-0115' => array('2013-12-25', '186', '3', '46', '0'),
         'ek3k-cnam-0134' => array('2013-12-25', '192', '4', '54', '0'),
         'ek6k-cnam-0004' => array('2013-12-25', '297', '7', '57', '2'),
         'ek3k-cnam-0123' => array('2013-12-25', '180', '4', '63', '0'),
         'ek3k-cnam-0011' => array('2013-12-25', '286', '6', '63', '2'),
         'ek3k-cnam-0089' => array('2013-12-25', '244', '4', '66', '0'),
         'ek3k-cnam-0116' => array('2013-12-25', '203', '4', '77', '0'),
         'ek3k-cnam-0109' => array('2013-12-25', '182', '7', '79', '3'),
         'ek3k-cnam-0133' => array('2013-12-25', '182', '3', '83', '0'),
         'ek3k-cnam-0118' => array('2013-12-25', '194', '4', '87', '0'),
         'ek3k-cnam-0099' => array('2013-12-25', '264', '8', '89', '1'),
         'ek3k-cnam-0015' => array('2013-12-25', '395', '12', '98', '6'),
         'ek3k-cnam-0077' => array('2013-12-25', '334', '6', '100', '2'),
         'ek3k-cnam-0088' => array('2013-12-25', '313', '4', '105', '0'),
         'ek3k-cnam-0095' => array('2013-12-25', '351', '8', '106', '0'),
         'ek3k-cnam-0107' => array('2013-12-25', '242', '5', '108', '0'),
         'ek3k-cnam-0006' => array('2013-12-25', '483', '7', '111', '1'),
         'ek3k-cnam-0082' => array('2013-12-25', '303', '7', '120', '2'),
         'ek3k-cnam-0103' => array('2013-12-25', '267', '5', '130', '1'),
         'ek3k-cnam-0117' => array('2013-12-25', '384', '4', '135', '0'),
         'ek3k-cnam-0132' => array('2013-12-25', '226', '3', '135', '1'),
         'ek3k-cnam-0090' => array('2013-12-25', '497', '7', '138', '2'),
         'ek3k-cnam-0025' => array('2013-12-25', '398', '9', '143', '1'),
         'ek3k-cnam-0083' => array('2013-12-25', '280', '6', '143', '1'),
         'ek3k-cnam-0086' => array('2013-12-23', '437', '5', '156', '0'),
         'ek3k-cnam-0064' => array('2013-12-25', '346', '7', '156', '2'),
         'ek3k-cnam-0127' => array('2013-12-25', '272', '4', '161', '1'),
         'ek3k-cnam-0044' => array('2013-12-25', '405', '6', '170', '-3'),
         'ek3k-cnam-0114' => array('2013-12-25', '283', '4', '180', '1'),
         'ek3k-cnam-0084' => array('2013-12-25', '404', '8', '193', '2'),
         'ek3k-cnam-0105' => array('2013-12-25', '342', '3', '208', '0'),
         'ek3k-cnam-0101' => array('2013-12-25', '343', '6', '208', '1'),
         'ek3k-cnam-0104' => array('2013-12-25', '353', '7', '211', '2'),
         'ek3k-cnam-0076' => array('2013-12-23', '391', '4', '218', '0'),
         'ek3k-cnam-0055' => array('2013-12-25', '551', '6', '223', '0'),
         'ek3k-cnam-0079' => array('2013-12-25', '361', '5', '240', '1'),
         'ek3k-cnam-0041' => array('2013-12-25', '518', '8', '255', '2'),
         'ek3k-cnam-0075' => array('2013-12-25', '512', '8', '269', '2'),
         'ek3k-cnam-0047' => array('2013-12-25', '490', '7', '282', '1'),
         'ek3k-cnam-0051' => array('2013-12-25', '538', '7', '284', '2'),
         'ek3k-cnam-0078' => array('2013-12-25', '492', '7', '290', '1'),
         'ek3k-cnam-0096' => array('2013-12-25', '437', '8', '290', '5'),
         'ek3k-cnam-0052' => array('2013-12-25', '526', '7', '301', '1'),
         'ek3k-cnam-0069' => array('2013-12-25', '527', '8', '317', '3'),
         'ek3k-cnam-0058' => array('2013-12-21', '524', '6', '322', '1'),
         'ek3k-cnam-0098' => array('2013-12-25', '457', '6', '327', '2'),
         'ek3k-cnam-0068' => array('2013-12-25', '527', '10', '328', '5'),
         'ek3k-cnam-0080' => array('2013-12-25', '595', '9', '343', '4'),
         'ek3k-cnam-0094' => array('2013-12-25', '463', '8', '345', '4'),
         'ek3k-cnam-0057' => array('2013-12-25', '594', '19', '353', '13'),
         'ek3k-cnam-0046' => array('2013-12-25', '644', '8', '355', '4'),
         'ek3k-cnam-0091' => array('2013-12-25', '522', '8', '374', '4'),
         'ek3k-cnam-0050' => array('2013-12-25', '616', '8', '379', '3'),
         'ek3k-cnam-0049' => array('2013-12-25', '679', '6', '394', '0'),
         'ek3k-cnam-0062' => array('2013-12-25', '633', '12', '403', '7'),
         'ek3k-cnam-0065' => array('2013-12-25', '602', '4', '406', '0'),
         'ek3k-cnam-0033' => array('2013-12-25', '589', '5', '410', '2'),
         'ek3k-cnam-0097' => array('2013-12-25', '531', '5', '420', '1'),
         'ek3k-cnam-0035' => array('2013-12-25', '673', '10', '422', '2'),
         'ek3k-cnam-0074' => array('2013-12-25', '703', '11', '433', '6'),
         'ek3k-cnam-0040' => array('2013-12-25', '670', '11', '453', '7'),
         'ek3k-cnam-0087' => array('2013-12-25', '581', '8', '457', '4'),
         'ek3k-cnam-0071' => array('2013-12-25', '669', '7', '471', '3'),
         'ek3k-cnam-0072' => array('2013-12-25', '752', '5', '474', '1'),
         'ek3k-cnam-0070' => array('2013-12-25', '739', '5', '484', '-3'),
         'ek3k-cnam-0038' => array('2013-12-25', '727', '9', '509', '3'),
         'ek3k-cnam-0042' => array('2013-12-25', '787', '10', '531', '2'),
         'ek3k-cnam-0066' => array('2013-12-25', '766', '8', '539', '2'),
         'ek3k-cnam-0022' => array('2013-12-25', '804', '16', '548', '10'),
         'ek3k-cnam-0020' => array('2013-12-25', '780', '4', '552', '-2'),
         'ek3k-cnam-0085' => array('2013-12-25', '676', '8', '565', '5'),
         'ek3k-cnam-0063' => array('2013-12-25', '746', '6', '581', '2'),
         'ek3k-cnam-0061' => array('2013-12-25', '833', '9', '595', '5'),
         'ek3k-cnam-0012' => array('2013-12-25', '833', '9', '612', '5'),
         'ek3k-cnam-0059' => array('2013-12-25', '866', '16', '614', '9'),
         'ek3k-cnam-0045' => array('2013-12-25', '854', '6', '642', '1'),
         'ek3k-cnam-0034' => array('2013-12-25', '889', '10', '654', '5'),
         'ek3k-cnam-0029' => array('2013-12-25', '931', '14', '663', '8'),
         'ek3k-cnam-0021' => array('2013-12-25', '890', '12', '667', '3'),
         'ek3k-cnam-0073' => array('2013-12-25', '943', '15', '674', '10'),
         'ek3k-cnam-0037' => array('2013-12-25', '950', '16', '685', '7'),
         'ek3k-cnam-0048' => array('2013-12-25', '923', '10', '690', '6'),
         'ek3k-cnam-0014' => array('2013-12-25', '1014', '9', '730', '5'),
         'ek3k-cnam-0026' => array('2013-12-25', '1043', '9', '793', '1'),
         'ek3k-cnam-0030' => array('2013-12-25', '1168', '18', '821', '4'),
         'ek3k-cnam-0018' => array('2013-12-25', '1031', '21', '826', '13'),
         'ek3k-cnam-0056' => array('2013-12-25', '1062', '11', '842', '5'),
         'ek3k-cnam-0067' => array('2013-12-25', '1156', '8', '866', '2'),
         'ek3k-cnam-0016' => array('2013-12-25', '1099', '5', '870', '0'),
         'ek3k-cnam-0010' => array('2013-12-25', '1157', '16', '904', '9'),
         'ek3k-cnam-0036' => array('2013-12-25', '1133', '13', '929', '9'),
         'ek3k-cnam-0031' => array('2013-12-25', '1247', '11', '1018', '6'),
         'ek3k-cnam-0023' => array('2013-12-25', '1326', '24', '1021', '14'),
         'ek3k-cnam-0028' => array('2013-12-25', '1272', '20', '1040', '15'),
         'ek3k-cnam-0017' => array('2013-12-25', '1246', '11', '1061', '7'),
         'ek3k-cnam-0019' => array('2013-12-25', '1401', '11', '1123', '5'),
         'ek3k-cnam-0032' => array('2013-12-25', '1315', '24', '1129', '19'),
         'ek3k-cnam-0013' => array('2013-12-25', '1376', '17', '1163', '12'),
         'ek3k-cnam-0039' => array('2013-12-25', '1465', '22', '1249', '13'),
         'ek3k-cnam-0024' => array('2013-12-25', '1594', '7', '1337', '3'),
      );
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
         // ek3k-cnam-0366
         // if (($services_id != 5452) && ($services_id != 5453)) {
            // continue;
         // }
         
         // ek3k-cnam-0373
         // if (($services_id != 5686)) {
            // continue;
         // }
         
         // ek3k-cnam-0129
         // if (($services_id != 1503)) {
            // continue;
         // }
         
         // ek3k-cnam-0010
         // if (($services_id != 1151)) {
            // continue;
         // }
         // ek3k-cnam-0336
         // if (($services_id != 4659)) {
            // continue;
         // }

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
         $firstRecordCreated = false;
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
            Toolbox::logInFile("pm-counters", "First values : ".serialize($a_first)."\n");

            // Fetch perfdata of last event in day to update counters ...
            $a_last = $self->getLastValues($services_id, $input['day']);
            Toolbox::logInFile("pm-counters", "Last values : ".serialize($a_last)."\n");
            
            // Set default values for counters ...
            foreach (self::getManagedCounters() as $key => $value) {
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
               // Set null values ...
               $input['cPagesToday']         = 0;
               $input['cPagesTotal']         = 0;
               $input['cPagesRemaining']     = 2000 - $input['cPagesToday'];
               $input['cRetractedToday']     = 0;
               $input['cRetractedTotal']     = 0;
               $input['cRetractedRemaining'] = 0;
               
               if (isset($initialValues[$hostname])) {
                  Toolbox::logInFile("pm-counters", "Initial values for $hostname : ". serialize($initialValues[$hostname]) ."\n");
                  $input['cPagesToday']         = $initialValues[$hostname][3];
                  $input['cPagesTotal']         = $input['cPagesToday'];
                  $input['cPagesRemaining']     = $input['cPaperLoad'] - $input['cPagesToday'];
                  $input['cPagesInitial']       = $initialValues[$hostname][1];
                  
                  $input['cRetractedToday']     = $initialValues[$hostname][4];
                  $input['cRetractedTotal']     = $input['cRetractedToday'];
                  $input['cRetractedRemaining'] = $input['cRetractedToday'];
                  $input['cRetractedInitial']   = $initialValues[$hostname][2];
               } else {
                  if (count($a_first) != 0) {
                     $input['cRetractedInitial']   = $a_first['Retracted Pages'];
                     $input['cPagesInitial']       = $a_first['Cut Pages'];
                  } else {
                     $input['cRetractedInitial']   = $a_last['Retracted Pages'];
                     $input['cPagesInitial']       = $a_last['Cut Pages'];
                  }
               }
               
               if (count($a_first) != 0) {
                  // Compute daily values thanks to first and last day values.
                  $input['cPagesToday']         += $a_last['Cut Pages'] - $a_first['Cut Pages'];
                  $input['cPagesTotal']         = $input['cPagesToday'];
                  $input['cPagesRemaining']     = $input['cPaperLoad'] - $input['cPagesToday'];
                  $input['cRetractedToday']     += $a_last['Retracted Pages'] - $a_first['Retracted Pages'];
                  $input['cRetractedTotal']     = $input['cRetractedToday'];
                  $input['cRetractedRemaining'] = $input['cRetractedTotal'];
                  
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
            $firstRecordCreated = true;
         }

         // Here it exists, at min, one host daily counters line ... and a_counters is the last known counters.
         $previous = $a_counters;
         $todayUpdate = false;
         for ($i = (strtotime($a_counters['day'])); $i < strtotime(date('Y-m-d').' 23:59:59'); $i += 86400) {
            if ($firstRecordCreated) {
               $firstRecordCreated = false;
               continue;
            }
            $input = array();
            
            // Hostname / day record
            $input['day']                 = date('Y-m-d', $i);
            $input['dayname']             = $daysnameidx[date('w', $i)];
            $input['hostname']            = $hostname;

            if ($input['day'] == date('Y-m-d')) {
               $todayUpdate = true;
            }
            Toolbox::logInFile("pm-counters", "Day : ". $input['day'] . " -> ".date('Y-m-d').' 00:00:00'."\n");
            
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
                  $previous = $a_previous;
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
            foreach (self::getManagedCounters() as $key => $value) {
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
               $input['cRetractedTotal']     = $previous['cRetractedTotal'] + $input['cRetractedToday'];
               $input['cPagesTotal']         = $previous['cPagesTotal'] + $input['cPagesToday'];
               $input['cPagesRemaining']     = $previous['cPagesRemaining'] - $input['cPagesToday'];
               $input['cRetractedRemaining'] = $previous['cRetractedRemaining'] + $input['cRetractedToday'];
               
               if ((count($a_first) != 0) && (count($a_last) != 0)) {
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
                  // FM : last > first event fetched from service events ... not from database previous counters.
                  // if ($a_last['Printer Replace'] > $previous['cPrinterChanged']
                  if ($a_last['Printer Replace'] > $a_first['Printer Replace']
                     || ($a_last['Cut Pages'] > $a_first['Cut Pages'] + 500)
                     || ($a_last['Cut Pages'] < $a_first['Cut Pages'])
                     || ($a_last['Retracted Pages'] < $a_first['Retracted Pages'])
                     || ($a_last['Cut Pages'] < $previous['cPagesInitial'])
                     || ($a_last['Retracted Pages'] < $previous['cRetractedInitial'])
                     ) {
                     
                     Toolbox::logInFile("pm-counters", "Service $hostname/$services_name : $services_id, detected that printer has changed today!\n");
                     // FM : last > first event fetched from service events ... not from database previous counters.
                     // if ($a_last['Printer Replace'] > $previous['cPrinterChanged']) {
                     if ($a_last['Printer Replace'] > $a_first['Printer Replace']) {
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
                     Toolbox::logInFile("pm-counters", "Printer changed today, counters : ".serialize($retpages)."\n");
                     $input['cPagesToday'] = $retpages[0]['Cut Pages'] + $retpages[1]['Cut Pages'];
                     $input['cPagesTotal'] = $previous['cPagesTotal'] + $input['cPagesToday'];
                     $input['cRetractedToday'] = $retpages[0]['Retracted Pages'] + $retpages[1]['Retracted Pages'];
                     $input['cRetractedTotal'] = $previous['cRetractedTotal'] + $input['cRetractedToday'];

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

                     $input['cPagesRemaining'] = $previous['cPagesRemaining'] - $input['cPagesToday'];
                     //$input['cPaperLoad'] - $input['cPagesTotal'];
                     $input['cRetractedRemaining'] = $previous['cRetractedRemaining'] + $input['cRetractedToday'];
                     
                     // Detect if paper was changed today
                     // FM : last > first event fetched from service events ... not from database previous counters.
                     // if ($a_last['Paper Reams'] > $previous['cPaperChanged']) {
                     if ($a_last['Paper Reams'] > $a_first['Paper Reams']) {
                        // getPaperChanged
                        $retpages = $self->getPaperChanged($services_id, date('Y-m-d', $i).' 00:00:00', date('Y-m-d', $i).' 23:59:59', $previous['cPaperChanged']);
                        Toolbox::logInFile("pm-counters", "Paper changed today, counters : ".serialize($retpages)."\n");
                        // Do not change because printer changed ...
                        // $input['cPagesToday'] = $retpages[0] + $retpages[1];
                        // $input['cRetractedToday'] = $retpages[2] + $retpages[3];
                        // Reset remaining pages with default paper ream load
                        $input['cPagesRemaining'] = 2000 - ($input['cPagesToday']);
                        // Increase paper changed counter
                        $input['cPaperChanged'] = $previous['cPaperChanged'] + ($a_last['Paper Reams'] - $a_first['Paper Reams']);
                        // Compute total paper load
                        $input['cPaperLoad'] = $input['cPaperChanged'] * 2000;
                     } else {
                        // Set paper changed counter
                        $input['cPaperChanged'] = $a_last['Paper Reams'];
                     }
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
                     $input['cRetractedRemaining'] = $previous['cRetractedRemaining'] + $input['cRetractedToday'];

                     // Detect if paper was changed today
                     // FM : last > first event fetched from service events ... not from database previous counters.
                     // if ($a_last['Paper Reams'] > $previous['cPaperChanged']) {
                     if ($a_last['Paper Reams'] > $a_first['Paper Reams']) {
                        // getPaperChanged
                        $retpages = $self->getPaperChanged($services_id, date('Y-m-d', $i).' 00:00:00', date('Y-m-d', $i).' 23:59:59', $previous['cPaperChanged']);
                        Toolbox::logInFile("pm-counters", "Paper changed today, counters : ".serialize($retpages)."\n");
                        $input['cPagesToday'] = $retpages[0] + $retpages[1];
                        $input['cRetractedToday'] = $retpages[2] + $retpages[3];
                        // Reset remaining pages with default paper ream load
                        $input['cPagesRemaining'] = 2000 - ($retpages[0] + $retpages[1]);
                        // Increase paper changed counter
                        $input['cPaperChanged'] = $previous['cPaperChanged'] + ($a_last['Paper Reams'] - $a_first['Paper Reams']);
                        // Compute total paper load
                        $input['cPaperLoad'] = $input['cPaperChanged'] * 2000;
                     } else {
                        // Set paper changed counter
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

      Toolbox::logInFile("pm-counters", "End ---------- \n");
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

      // Search Paper Reams counter ...
      $query = "SELECT id, perf_data, date FROM glpi_plugin_monitoring_serviceevents
             JOIN
               (SELECT MIN(glpi_plugin_monitoring_serviceevents.id) AS min
                FROM glpi_plugin_monitoring_serviceevents
                WHERE `plugin_monitoring_services_id` = '".$services_id."'
                   AND (
                     `glpi_plugin_monitoring_serviceevents`.`perf_data` LIKE '%Paper Reams%'
                     OR
                     `glpi_plugin_monitoring_serviceevents`.`perf_data` LIKE '%Powered Cards%'
                   )
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

      // Search Paper Reams counter ...
      $query = "SELECT id, perf_data, date FROM glpi_plugin_monitoring_serviceevents
             JOIN
               (SELECT MAX(glpi_plugin_monitoring_serviceevents.id) AS max
                FROM glpi_plugin_monitoring_serviceevents
                WHERE `plugin_monitoring_services_id` = '".$services_id."'
                   AND (
                     `glpi_plugin_monitoring_serviceevents`.`perf_data` LIKE '%Paper Reams%'
                     OR
                     `glpi_plugin_monitoring_serviceevents`.`perf_data` LIKE '%Powered Cards%'
                   )
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
      
      $cnt_paperchanged = -1;
      
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
      // Toolbox::logInFile("pm-counters", "getPrinterChanged, ret : ".serialize($ret[4])."\n");

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
         $prevLow = -1000000000;
         $prevHigh = 1000000000;
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
               if ($val < $prevLow) {
                  $pagesCountIndex = $num;
                  Toolbox::logInFile("pm-counters", "getPrinterChanged, pages count index (Lower) : $pagesCountIndex\n");
                  break 1;
               }
               if ($val > $prevHigh + 500) {
                  $pagesCountIndex = $num;
                  Toolbox::logInFile("pm-counters", "getPrinterChanged, pages count index (Higher) : $pagesCountIndex\n");
                  break 1;
               }
            }
            $prevLow = $val;
            $prevHigh = $val;
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
         
         // Remaining pages
         if ($field == 'cPagesRemaining') {
            // Update current day and more recent days ...
            $a_data = getAllDatasFromTable(
                        'glpi_plugin_monitoring_hostdailycounters',
                        "`day` > '".$this->fields['day']."'
                           AND `hostname`='".$this->fields['hostname']."'",
                        false,
                        '`day` ASC');
            foreach ($a_data as $data) {
               $data['cPagesRemaining'] += ($newvalue - $oldvalue);
            
               unset($data['hostname']);
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
      $fields = "
         `glpi_entities`.`name` AS entity_name
      ";
      $join .= "
         INNER JOIN `glpi_computers`
            ON `glpi_plugin_monitoring_hostdailycounters`.`hostname` = `glpi_computers`.`name`
         INNER JOIN `glpi_entities`
            ON `glpi_computers`.`entities_id` = `glpi_entities`.`id`
      ";

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
            $where .= " AND `hostname` = '" . $params['hostsFilter'] . "'";
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
         foreach (self::getManagedCounters() as $key => $value) {
            if (isset($value['type']) && $value['type'] == $params['type']) {
               $counters[] = $key;
            }
         }
         $fields .= "`".implode("`,`",$counters)."`";
      } else {
         $fields .= "
            , `glpi_plugin_monitoring_hostdailycounters`.*
         ";
      }
      
      // Filter
      if (isset($params['filter'])) {
         $where .= " AND ".$params['filter'];
      }

      // Start / limit
      $order = "date(day) DESC";
      if (isset($params['order'])) {
         $order = $params['order'];
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
         ORDER BY $order
         LIMIT $start,$limit
      ";
      // Toolbox::logInFile("pm-ws", "getHostDailyCounters, query : $query\n");
      $resp = array ();
      $result = $DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         // Toolbox::logInFile("pm-counters", "getHostDailyCounters, line : ".$data['hostname']." / ".$data['day']."\n");
         $row = array ();
         $row['entityname'] = $data['entity_name'];
         $row['hostname'] = $data['hostname'];
         $row['day'] = $data['day'];
         
         foreach (self::getManagedCounters() as $key => $value) {
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
    * - filter : where clause
    * - entity : entity id
    */
   static function getLastCountersPerHost($params) {
      global $DB;

      $where = $join = '';
      $join .= "
         INNER JOIN `glpi_computers`
            ON `glpi_plugin_monitoring_hostdailycounters`.`hostname` = `glpi_computers`.`name`
         INNER JOIN `glpi_entities`
            ON `glpi_computers`.`entities_id` = `glpi_entities`.`id`
         INNER JOIN (
            SELECT hostname, MAX(DAY) AS max_day 
            FROM `glpi_plugin_monitoring_hostdailycounters` 
            GROUP BY hostname
         ) AS t2 ON (`t2`.`hostname` = `glpi_plugin_monitoring_hostdailycounters`.`hostname` AND `t2`.`max_day` = `glpi_plugin_monitoring_hostdailycounters`.`day`) 
      ";

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
      
      // Group
      $group = 'GROUP BY hostname';
      if (isset($params['group'])) {
         $group = 'GROUP BY '.$params['group'];
      }
      
      // Order
      $order = 'ORDER BY hostname ASC';
      if (isset($params['order'])) {
         $order = 'ORDER BY '.$params['order'];
      }
      
      $query = "
         SELECT `glpi_entities`.`name` AS entity_name, `glpi_entities`.`id` AS entity_id, `glpi_plugin_monitoring_hostdailycounters`.*
         FROM `glpi_plugin_monitoring_hostdailycounters`
         $join
         $where
         $group
         $order
      ";
      
      Toolbox::logInFile("pm-ws", "getLastCountersPerHost, query : $query\n");
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
         'entity_name' : group by entity_name
         'hostname' : group by hostname
         'day': group by day
    * - order:
         'hostname ASC' : sort by hostname
         'day DESC' : sort by day
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
      $fields = "
         `glpi_entities`.`name` AS entity_name, `glpi_entities`.`id` AS entity_id
      ";
      $join .= "
         INNER JOIN `glpi_computers`
            ON `glpi_plugin_monitoring_hostdailycounters`.`hostname` = `glpi_computers`.`name`
         INNER JOIN `glpi_entities`
            ON `glpi_computers`.`entities_id` = `glpi_entities`.`id`
      ";

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
      
      // $fields = 'hostname';
      // Group
      $group = '';
      if (isset($params['group'])) {
         $group = "GROUP BY ".$params['group'];
         $fields .= ', '.$params['group'];
      }
      
      // Order
      $order = '';
      if (isset($params['order'])) {
         $order = "ORDER BY ".$params['order'];
      }
      
      // statistics
      if (isset($params['statistics'])) {
         foreach (self::getManagedCounters() as $key => $value) {
            if (! isset($value['hidden']) && isset($value[$params['statistics']])) {
               $fields .= ", ROUND( ".$value[$params['statistics']]."(".$key."),2 ) AS ".$value[$params['statistics']]."_$key";
            }
         }
      }
      
      // Check out average printed pages on each kiosk per each day type ... only for the current and next 3 days.
      $query = "
         SELECT
         $fields
         FROM `glpi_plugin_monitoring_hostdailycounters`
         $join
         $where
         $group
         $order
         LIMIT $start,$limit
      ";
      // Toolbox::logInFile("pm-ws", "getStatistics, query : $query\n");
      // Toolbox::logInFile("pm-checkCounters", "getStatistics, query : $query\n");
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