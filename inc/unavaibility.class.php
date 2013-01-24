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
   @since     2011
 
   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginMonitoringUnavaibility extends CommonDBTM {
   private $currentstate = '';
   private $plugin_monitoring_services_id = 0;
   private $unavaibilities_id = 0;
   
   
   static function getTypeName($nb=0) {
      return __('Unavaibility', 'monitoring');
   }
   
   
   
   static function cronUnavaibility() {      
      
      ini_set("max_execution_time", "0");
      
      $pmUnavaibility = new PluginMonitoringUnavaibility();
      $pmUnavaibility->runUnavaibility();
      
      return true;
   }
   
   
  
   function getSearchOptions() {
      $tab = array();
      $tab['common'] = _n('Characteristic', 'Characteristics', 2);

      $tab[1]['table']         = $this->getTable();
      $tab[1]['field']         = 'id';
      $tab[1]['name']          = __('ID');
      $tab[1]['massiveaction'] = false;
      
      $tab[2]['table'] = "glpi_plugin_monitoring_services";
      $tab[2]['field'] = 'name';
      $tab[2]['linkfield'] = 'plugin_monitoring_services_id';
      $tab[2]['name'] = __('Ressource', 'monitoring');
      $tab[2]['datatype'] = 'itemlink';
      $tab[2]['itemlink_type']  = 'PluginMonitoringService';
      
      $tab[3]['table']         = $this->getTable();
      $tab[3]['field']         = 'begin_date';
      $tab[3]['name']          = __('Start', 'monitoring');
      $tab[3]['datatype']      = 'datetime';
      $tab[3]['massiveaction'] = false;
      
      $tab[4]['table']         = $this->getTable();
      $tab[4]['field']         = 'end_date';
      $tab[4]['name']          = __('End', 'monitoring');
      $tab[4]['datatype']      = 'datetime';
      $tab[4]['massiveaction'] = false;
      
      $tab[5]['table']         = $this->getTable();
      $tab[5]['field']         = 'duration';
      $tab[5]['name']          = __('Duration', 'monitoring');
      $tab[5]['datatype']      = 'timestamp';
      $tab[5]['withseconds']   = true;
      $tab[5]['massiveaction'] = false;
      
      return $tab;
   }
   
   
   
   static function runUnavaibility($services_id = 0) {
      global $DB;
      

      $pmUnavaibility = new PluginMonitoringUnavaibility();
      $pmServiceevent = new PluginMonitoringServiceevent();
      
      $where = '';
      if ($services_id != '0') {
         $where = " WHERE `id`='".$services_id."' ";
      }
      
      $query = "SELECT * FROM `glpi_plugin_monitoring_services` ".$where;
      $result = $DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         $pmUnavaibility->getCurrentState($data['id']);
         
         $query2 = "SELECT * FROM `glpi_plugin_monitoring_serviceevents`
            WHERE `unavailability`='0'
               AND `state_type`='HARD'
               AND `plugin_monitoring_services_id`='".$data['id']."'
            ORDER BY `date`";
         $result2 = $DB->query($query2);
         while ($data2=$DB->fetch_array($result2)) {
            $pmUnavaibility->checkState($data2['state'], 
                                        $data2['date'], 
                                        $data['id'], 
                                        $data2['event']);
            $input = array();
            $input['id'] = $data2['id'];
            $input['unavailability'] = 1;
            $pmServiceevent->update($input);
         }
      }
      
   }
   
   
   

   function getCurrentState($plugin_monitoring_services_id) {
      $this->plugin_monitoring_services_id = $plugin_monitoring_services_id;
      
      $a_states = $this->find("`plugin_monitoring_services_id`='".$plugin_monitoring_services_id."'",
                              "`id` DESC", 1);
      if (count($a_states) > 0) {
         $a_state = current($a_states);
         if (is_null($a_state['end_date'])) {
            $this->currentstate = 'critical';
            $this->unavaibilities_id = $a_state['id'];
         } else {
            $this->currentstate = 'ok';
         }
      } else {
         $this->currentstate = 'ok';
      }
   }
   
   
   
   function checkState($stateevent, $date, $services_id, $event) {
      
      $state = PluginMonitoringDisplay::getState($stateevent, "HARD", $event);
      
      if ($state == 'red') { // Critial
         if ($this->currentstate == 'ok') {
            // Add 
            $input = array();
            $input['plugin_monitoring_services_id'] = $this->plugin_monitoring_services_id;
            $input['begin_date'] = $date;
            $this->unavaibilities_id = $this->add($input);
            $this->currentstate = 'critical';
         }
      } else { // Ok
         if ($this->currentstate == 'critical') {
            // update
            $input = array();
            $input['id'] = $this->unavaibilities_id;
            $input['end_date'] = $date;
            $this->getFromDB($this->unavaibilities_id);
            $input['duration'] =  strtotime($date) - strtotime($this->fields['begin_date']);
            $this->update($input);
            $this->unavaibilities_id = 0;
            $this->currentstate = 'ok';
         }
      }      
   }
   
   
   
   function displayComponentscatalog($componentscatalogs_id) {
      global $DB, $CFG_GLPI;
      
      echo "<table class='tab_cadre_fixe'>";     
      
      echo "<tr>";
      echo "<th>".__('Type')."</th>";
      echo "<th>".__('Entity')."</th>";
      echo "<th>".__('Name')."</th>";
      echo "<th>".__('Resource', 'monitoring')."</th>";
      echo "<th>".__('Current month', 'monitoring')."</th>";
      echo "<th>".__('Last month', 'monitoring')."</th>";
      echo "<th>".__('Current year', 'monitoring')."</th>";
      echo "<th>".__('Detail', 'monitoring')."</th>";
      echo "</tr>";

      
      $query = "SELECT `glpi_plugin_monitoring_services`.*, `itemtype`, `items_id`
         FROM `glpi_plugin_monitoring_services`
         LEFT JOIN `glpi_plugin_monitoring_componentscatalogs_hosts`
            ON `plugin_monitoring_componentscatalogs_hosts_id`=
               `glpi_plugin_monitoring_componentscatalogs_hosts`.`id`
         WHERE `plugin_monitoring_componentscalalog_id`='".$componentscatalogs_id."'";
      $result = $DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         $itemtype = $data['itemtype'];
         $item = new $itemtype();
         if ($item->getFromDB($data['items_id'])) {
            echo "<tr class='tab_bg_3'>";
            echo "<td class='center'>";
            echo $item->getTypeName();
            echo "</td>";
            echo "<td class='center'>";
            echo Dropdown::getDropdownName("glpi_entities",$item->fields['entities_id'])."</td>";
            echo "<td class='center".
                  (isset($item->fields['is_deleted']) && $item->fields['is_deleted'] ? " tab_bg_2_2'" : "'");
            echo ">".$item->getLink(1)."</td>";
            echo "<td class='center'>";
            echo $data['name'];
            echo "</td>";
            $this->displayValues($data['id'], 'currentmonth');

            $this->displayValues($data['id'], 'lastmonth');

            $this->displayValues($data['id'], 'currentyear');

            echo "<td class='center'>";
            echo "<a href='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/unavaibility.php?".
                    "field[0]=2&searchtype[0]=equals&contains[0]=".$data['id'].
                    "&sort=3&order=DESC&itemtype=PluginMonitoringUnavaibility'>
               <img src='".$CFG_GLPI['root_doc']."/plugins/monitoring/pics/info.png'/></a>";
            echo "</td>";
            echo "</tr>";
         }
      }      
      echo "</table>";
   }
   
   
   
   /**
    *
    * @param type $services_id
    * @param type $period can have values 'currentmonth','lastmonth', 'currentyear'
    */
   function parseEvents($services_id,$period, $startp='', $endp='') {
      global $DB;

      $timecriticalSeconds = 0;
      $totaltime = 0;
      if ($period == ''
              AND $startp != '') {
         
         $begindate = $startp;
         $enddate   = $endp;
         $timestart   = strtotime($begindate);
         $timeend     = strtotime($enddate);
         $totaltime = $timeend - $timestart;

         $query = "SELECT * FROM `glpi_plugin_monitoring_unavaibilities`
            WHERE `plugin_monitoring_services_id`='".$services_id."'
               AND `begin_date` >= '".$begindate."'
               AND `end_date` <= '".$enddate."'";
         $result = $DB->query($query);
         while ($data=$DB->fetch_array($result)) {
            $timestart   = strtotime($data['begin_date']);
            $timeend     = strtotime($data['end_date']);
            $activetime  = $timeend-$timestart;
            $timecriticalSeconds += $activetime;
         }            
         // unvaibility when more than end
         $query = "SELECT * FROM `glpi_plugin_monitoring_unavaibilities`
            WHERE `plugin_monitoring_services_id`='".$services_id."'
               AND `begin_date` >= '".$begindate."'
               AND `begin_date` <= '".$enddate."'
               AND `end_date` > '".$enddate."'";
         $result = $DB->query($query);
         while ($data=$DB->fetch_array($result)) {
            $timestart   = strtotime($data['begin_date']);
            $timeend     = strtotime($enddate);
            $activetime  = $timeend-$timestart;
            $timecriticalSeconds += $activetime;
         }
         // unvaibility when before start
         $query = "SELECT * FROM `glpi_plugin_monitoring_unavaibilities`
            WHERE `plugin_monitoring_services_id`='".$services_id."'
               AND `begin_date` < '".$begindate."'
               AND `end_date` <= '".$enddate."'
               AND `end_date` >= '".$begindate."'";
         $result = $DB->query($query);
         while ($data=$DB->fetch_array($result)) {
            $timestart   = strtotime($begindate);
            $timeend     = strtotime($data['end_date']);
            $activetime  = $timeend-$timestart;
            $timecriticalSeconds += $activetime;
         }
         return array($timecriticalSeconds, $totaltime);
      }
      
      switch ($period) {

         case 'currentmonth':
            $begindate = date("Y-m-d H:i:s", strtotime(date('m').'/01/'.date('Y').' 00:00:00'));
            $enddate = date("Y-m-d H:i:s", strtotime('+1 month',strtotime(date('m').'/01/'.date('Y').' 23:59:59')));
            $timestart   = strtotime($begindate);
            $timeend     = strtotime($enddate);
            $totaltime = $timeend - $timestart;
               
            $month = date('Y-m-');
            $query = "SELECT * FROM `glpi_plugin_monitoring_unavaibilities`
               WHERE `plugin_monitoring_services_id`='".$services_id."'
                  AND `begin_date` LIKE '".$month."%'
                  AND `end_date` LIKE '".$month."%'";
            $result = $DB->query($query);
            while ($data=$DB->fetch_array($result)) {
               $timestart   = strtotime($data['begin_date']);
               $timeend     = strtotime($data['end_date']);
               $activetime = $timeend-$timestart;
               $timecriticalSeconds += $activetime;
            }
            // unvaibility on 2 months
            $query = "SELECT * FROM `glpi_plugin_monitoring_unavaibilities`
               WHERE `plugin_monitoring_services_id`='".$services_id."'
                  AND `begin_date` NOT LIKE '".$month."%'
                  AND `end_date` LIKE '".$month."%'";
            $result = $DB->query($query);
            while ($data=$DB->fetch_array($result)) {
               $timestart   = strtotime($begindate);
               $timeend     = strtotime($data['end_date']);
               $activetime = $timeend-$timestart;
               $timecriticalSeconds += $activetime;
            }
            // current unvaibility (not finished)
            $query = "SELECT * FROM `glpi_plugin_monitoring_unavaibilities`
               WHERE `plugin_monitoring_services_id`='".$services_id."'
                  AND `end_date` IS NULL";
            $result = $DB->query($query);
            while ($data=$DB->fetch_array($result)) {
               $timestart   = strtotime($data['begin_date']);
               $activetime = date('U')-$timestart;
               $timecriticalSeconds += $activetime;
            }
            break;

         case 'lastmonth':
            $m = date('n');
            $begindate = date('Y-m-d 00:00:00',mktime(1,1,1,$m-1,1,date('Y')));
            $enddate = date('Y-m-d 23:59:59',mktime(1,1,1,$m,0,date('Y')));
            $timestart   = strtotime($begindate);
            $timeend     = strtotime($enddate);
            $totaltime = $timeend - $timestart;
            
            $month = date('Y-m-',mktime(1,1,1,$m-1,1,date('Y')));
            $query = "SELECT * FROM `glpi_plugin_monitoring_unavaibilities`
               WHERE `plugin_monitoring_services_id`='".$services_id."'
                  AND `begin_date` LIKE '".$month."%'
                  AND `end_date` LIKE '".$month."%'";
            $result = $DB->query($query);
            while ($data=$DB->fetch_array($result)) {
               $timestart   = strtotime($data['begin_date']);
               $timeend     = strtotime($data['end_date']);
               $activetime = $timeend-$timestart;
               $timecriticalSeconds += $activetime;
            }            
            // unvaibility on 2 months
            $query = "SELECT * FROM `glpi_plugin_monitoring_unavaibilities`
               WHERE `plugin_monitoring_services_id`='".$services_id."'
                  AND `begin_date` NOT LIKE '".$month."%'
                  AND `end_date` LIKE '".$month."%'";
            $result = $DB->query($query);
            while ($data=$DB->fetch_array($result)) {
               $timestart   = strtotime($begindate);
               $timeend     = strtotime($data['end_date']);
               $activetime = $timeend-$timestart;
               $timecriticalSeconds += $activetime;
            }
            $query = "SELECT * FROM `glpi_plugin_monitoring_unavaibilities`
               WHERE `plugin_monitoring_services_id`='".$services_id."'
                  AND `begin_date` LIKE '".$month."%'
                  AND `end_date` NOT LIKE '".$month."%'";
            $result = $DB->query($query);
            while ($data=$DB->fetch_array($result)) {
               $timestart   = strtotime($data['begin_date']);
               $timeend     = strtotime($enddate);
               $activetime = $timeend-$timestart;
               $timecriticalSeconds += $activetime;
            }
            break;
         
         case 'currentyear':
            $begindate = date("Y-m-d H:i:s", strtotime('01/01/'.date('Y').' 00:00:00'));
            $enddate = date("Y-m-d H:i:s", strtotime('12/31/'.date('Y').' 23:59:59'));
            $timestart   = strtotime($begindate);
            $timeend     = strtotime($enddate);
            $totaltime = $timeend - $timestart;
               
            $year = date('Y-');
            $query = "SELECT * FROM `glpi_plugin_monitoring_unavaibilities`
               WHERE `plugin_monitoring_services_id`='".$services_id."'
                  AND `begin_date` LIKE '".$year."%'
                  AND `end_date` LIKE '".$year."%'";
            $result = $DB->query($query);
            while ($data=$DB->fetch_array($result)) {
               $timestart   = strtotime($data['begin_date']);
               $timeend     = strtotime($data['end_date']);
               $activetime = $timeend-$timestart;
               $timecriticalSeconds += $activetime;
            }
            // unvaibility on 2 years
            $query = "SELECT * FROM `glpi_plugin_monitoring_unavaibilities`
               WHERE `plugin_monitoring_services_id`='".$services_id."'
                  AND `begin_date` NOT LIKE '".$year."%'
                  AND `end_date` LIKE '".$year."%'";
            $result = $DB->query($query);
            while ($data=$DB->fetch_array($result)) {
               $timestart   = strtotime($begindate);
               $timeend     = strtotime($data['end_date']);
               $activetime = $timeend-$timestart;
               $timecriticalSeconds += $activetime;
            }
            // current unvaibility (not finished)
            $query = "SELECT * FROM `glpi_plugin_monitoring_unavaibilities`
               WHERE `plugin_monitoring_services_id`='".$services_id."'
                  AND `end_date` IS NULL";
            $result = $DB->query($query);
            while ($data=$DB->fetch_array($result)) {
               $timestart   = strtotime($data['begin_date']);
               $activetime = date('U')-$timestart;
               $timecriticalSeconds += $activetime;
            }
            break;
         
      }
      return array($timecriticalSeconds, $totaltime);
   }
   
   
   
   function displayValues($services_id,$period, $tooltip=0) {
            
      $a_times = $this->parseEvents($services_id, $period);
      $displaytime = '';
      if ($a_times[0] > 0) {
         echo "<td style='background-color: rgb(255, 120, 0);-moz-border-radius: 4px;-webkit-border-radius: 4px;-o-border-radius: 4px;padding: 2px;' align='center'>";
         if ($tooltip == '1') {
            $displaytime = '&nbsp;'.Html::showToolTip(Html::timestampToString($a_times[0]), array('display'=>false));
         } else {
            $displaytime = '<br/>'.Html::timestampToString($a_times[0]);
         }
      } else {
         echo "<td align='center'>";
      }      
      echo round(((($a_times[1] - $a_times[0]) / $a_times[1]) * 100), 3)."%".$displaytime;
      echo "</td>";
      
   }
   
   
   
   function showList($get) {
      Search::manageGetValues("PluginMonitoringUnavaibility");
      Search::showList("PluginMonitoringUnavaibility", $get);
   }
   
}
?>