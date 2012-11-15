<?php

/*
   ------------------------------------------------------------------------
   Plugin Monitoring for GLPI
   Copyright (C) 2011-2012 by the Plugin Monitoring for GLPI Development Team.

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
   along with Behaviors. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   Plugin Monitoring for GLPI
   @author    David Durieux
   @co-author 
   @comment   
   @copyright Copyright (c) 2011-2012 Plugin Monitoring for GLPI team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      https://forge.indepnet.net/projects/monitoring/
   @since     2011
 
   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginMonitoringComponentscatalog extends CommonDropdown {
   
   /**
   * Get name of this type
   *
   *@return text name of this type by language of the user connected
   *
   **/
   static function getTypeName($nb=0) {
      return __('Components catalog', 'monitoring');
   }



   static function canCreate() {
      return PluginMonitoringProfile::haveRight("componentscatalog", 'w');
   }


   
   static function canView() {
      return PluginMonitoringProfile::haveRight("componentscatalog", 'r');
   }

   
   
   function defineTabs($options=array()){

      $ong = array();
      $this->addStandardTab("PluginMonitoringComponentscatalog", $ong, $options);
      return $ong;
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

      if ($item->getID() > 0) {
         $ong = array();
         $ong[1] = __('Components', 'monitoring');
         $ong[2] = __('Static hosts', 'monitoring');
         $ong[3] = _n('Rule', 'Rules', 2);
         $ong[4] = __('Dynamic hosts', 'monitoring');
         $ong[5] = __('Contacts', 'monitoring');
         $ong[6] = __('Availability', 'monitoring');
         $ong[7] = __('Report');
         return $ong;
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
      
      if ($item->getID() > 0) {
         switch($tabnum) {

            case 1:
               $pmComponentscatalog_Component = new PluginMonitoringComponentscatalog_Component();
               $pmComponentscatalog_Component->showComponents($item->getID());         
               break;

            case 2 :
               $pmComponentscatalog_Host = new PluginMonitoringComponentscatalog_Host();
               $pmComponentscatalog_Host->showHosts($item->getID(), 1);
               break;

            case 3 :
               $pmComponentscatalog_rule = new PluginMonitoringComponentscatalog_rule();
               $pmComponentscatalog_rule->showRules($item->getID());
               break;

            case 4 :
               $pmComponentscatalog_Host = new PluginMonitoringComponentscatalog_Host();
               $pmComponentscatalog_Host->showHosts($item->getID(), 0);
               break;

            case 5 : 
               $pmContact_Item = new PluginMonitoringContact_Item();
               $pmContact_Item->showContacts("PluginMonitoringComponentscatalog", $item->getID());
               break;

            case 6:
               $pmUnavaibility = new PluginMonitoringUnavaibility();
               $pmUnavaibility->displayComponentscatalog($item->getID());
               break;
            
            case 7:
               $pmPluginMonitoringComponentscatalog = new PluginMonitoringComponentscatalog();
               $pmPluginMonitoringComponentscatalog->showReport($item->getID());
               break;

            default :

         }
         
      }
      return true;
   }
   
   
   
   function getAdditionalFields() {
      return array(array('name'  => 'notification_interval',
                         'label' => __('Interval between 2 notifications (in minutes)', 'monitoring'),
                         'type'  => 'notificationinterval'));
   }
   
   
   
   function displaySpecificTypeField($ID, $field=array()) {
      
      
      switch ($field['type']) {
         case 'notificationinterval' :
            if ($ID > 0) {
//               $this->fields['notification_interval'];
            } else {
               $this->fields['notification_interval'] = 30;
            }
            Dropdown::showInteger('notification_interval', $this->fields['notification_interval'], 1, 1000);
            break;
      }
   }
   
   
   
   function showChecks() {      

      echo "<table class='tab_cadre' width='100%'>";
      echo "<tr class='tab_bg_4' style='background: #cececc;'>";
      
      $a_componentscatalogs = $this->find();
      $i = 0;
      foreach ($a_componentscatalogs as $data) {
         echo "<td>";

         echo $this->showWidget($data['id']);
         $this->ajaxLoad($data['id']);
         
         echo "</td>";
         
         $i++;
         if ($i == '6') {
            echo "</tr>";
            echo "<tr class='tab_bg_4' style='background: #cececc;'>";
            $i = 0;
         }
      }      
      
      echo "</tr>";
      echo "</table>";      
   }
   
   
   
   static function replayRulesCatalog($item) {
      global $DB;
      
      $datas = getAllDatasFromTable("glpi_plugin_monitoring_componentscatalogs_rules", 
              "`plugin_monitoring_componentscalalog_id`='".$item->getID()."'");
      $pmComponentscatalog_rule = new PluginMonitoringComponentscatalog_rule();
      foreach($datas as $data) {
         $pmComponentscatalog_rule->getFromDB($data['id']);
         PluginMonitoringComponentscatalog_rule::getItemsDynamicly($pmComponentscatalog_rule);
      }
   }
  
   
   
   static function removeCatalog($item) {
      global $DB;
      
      $pmComponentscatalog_Host = new PluginMonitoringComponentscatalog_Host();
      $pmComponentscatalog_rule = new PluginMonitoringComponentscatalog_rule(); 
      
      $query = "SELECT * FROM `glpi_plugin_monitoring_componentscatalogs_hosts`
         WHERE `plugin_monitoring_componentscalalog_id`='".$item->fields["id"]."'
            AND `is_static`='1'";
      $result = $DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         $pmComponentscatalog_Host->delete($data);
      }
      
      $query = "SELECT * FROM `glpi_plugin_monitoring_componentscatalogs_rules`
         WHERE `plugin_monitoring_componentscalalog_id`='".$item->fields["id"]."'";
      $result = $DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         $pmComponentscatalog_rule->delete($data);
      }
   }
   
   
   
   function showWidget($id) {
      return "<div id=\"updatecomponentscatalog".$id."\"></div>";
   }
   
   
   
   function showWidgetFrame($id) {
      global $DB, $CFG_GLPI;
      
      $this->getFromDB($id);
      $data = $this->fields;
      echo '<table  class="tab_cadre_fixe" style="width:158px;">';
      echo '<tr class="tab_bg_1">';
      echo '<th colspan="2" style="font-size:18px;" height="60">';
      echo $data['name']."&nbsp;";
      echo '</th>';
      echo '</tr>';
         
      $ret = $this->getInfoOfCatalog($id);
      $nb_ressources = $ret[0];
      $stateg = $ret[1];
      
      
      echo '<tr class="tab_bg_1">';
      echo '<td>';
      echo __('Resources', 'monitoring')."&nbsp;:";
      echo '</td>';
      echo '<th align="center" height="40" width="50%">';
      $link = $CFG_GLPI['root_doc'].
         "/plugins/monitoring/front/service.php?reset=reset&field[0]=8&searchtype[0]=equals&contains[0]=".$id.
            "&itemtype=PluginMonitoringService&start=0&glpi_tab=3";
      echo '<a href="'.$link.'">'.$nb_ressources.'</a>';
      echo '</th>';
      echo '</tr>';

      $background = '';
      $count = 0;
            
      $link = '';
      if ($stateg['CRITICAL'] > 0) {
         $count = $stateg['CRITICAL'];
         $background = 'background="'.$CFG_GLPI['root_doc'].'/plugins/monitoring/pics/bg_critical.png"';
         $link = $CFG_GLPI['root_doc'].
         "/plugins/monitoring/front/service.php?reset=reset&field[0]=3&searchtype[0]=equals&contains[0]=CRITICAL".
            "&link[1]=AND&field[1]=8&searchtype[1]=equals&contains[1]=".$id.
            "&link[2]=OR&field[2]=3&searchtype[2]=equals&contains[2]=DOWN".
            "&link[3]=AND&field[3]=8&searchtype[3]=equals&contains[3]=".$id.
            "&link[4]=OR&field[4]=3&searchtype[4]=equals&contains[4]=UNREACHABLE".
            "&link[5]=AND&field[5]=8&searchtype[5]=equals&contains[5]=".$id.
            "&itemtype=PluginMonitoringService&start=0&glpi_tab=3";
      } else if ($stateg['WARNING'] > 0) {
         $count = $stateg['WARNING'];
         $background = 'background="'.$CFG_GLPI['root_doc'].'/plugins/monitoring/pics/bg_warning.png"';
         $link = $CFG_GLPI['root_doc'].
         "/plugins/monitoring/front/service.php?reset=reset&field[0]=3&searchtype[0]=equals&contains[0]=WARNING".
            "&link[1]=AND&field[1]=8&searchtype[1]=equals&contains[1]=".$id.
            "&link[2]=OR&field[2]=3&searchtype[2]=equals&contains[2]=UNKNOWN".
            "&link[3]=AND&field[3]=8&searchtype[3]=equals&contains[3]=".$id.
            "&link[4]=OR&field[4]=3&searchtype[4]=equals&contains[4]=RECOVERY".
            "&link[5]=AND&field[5]=8&searchtype[5]=equals&contains[5]=".$id.
            "&link[6]=OR&field[6]=3&searchtype[6]=equals&contains[6]=FLAPPING".
            "&link[7]=AND&field[7]=8&searchtype[7]=equals&contains[7]=".$id.
            "&itemtype=PluginMonitoringService&start=0&glpi_tab=3";
      } else if ($stateg['OK'] > 0) {
         $count = $stateg['OK'];
         $background = 'background="'.$CFG_GLPI['root_doc'].'/plugins/monitoring/pics/bg_ok.png"';
         $link = $CFG_GLPI['root_doc'].
         "/plugins/monitoring/front/service.php?reset=reset&field[0]=3&searchtype[0]=equals&contains[0]=OK".
            "&link[1]=AND&field[1]=8&searchtype[1]=equals&contains[1]=".$id.
            "&link[2]=OR&field[2]=3&searchtype[2]=equals&contains[2]=UP".
            "&itemtype=PluginMonitoringService&start=0&glpi_tab=3";
      }
      echo  "<tr ".$background.">";
      echo  '<th style="background-color:transparent;" '.$background.'" colspan="2" height="100">';
      echo  '<a href="'.$link.'"><font style="font-size: 52px; color:black">'.$count.'</font></a>';         
      echo  '</th>';
      echo  '</tr>';
      
      echo  '</table>';
   }
   
   
   
   function ajaxLoad($id) {
      global $CFG_GLPI;
      
      echo "<script type=\"text/javascript\">

      var elcc".$id." = Ext.get(\"updatecomponentscatalog".$id."\");
      var mgrcc".$id." = elcc".$id.".getUpdateManager();
      mgrcc".$id.".loadScripts=true;
      mgrcc".$id.".showLoadIndicator=false;
      mgrcc".$id.".startAutoRefresh(50, \"".$CFG_GLPI["root_doc"]."/plugins/monitoring/ajax/updateWidgetComponentscatalog.php\", \"id=".$id."\", \"\", true);
      </script>";
   }
   
   
   
   function getInfoOfCatalog($componentscatalogs_id) {
      global $DB;
      
      $pmComponentscatalog_Host = new PluginMonitoringComponentscatalog_Host();
      $pmService = new PluginMonitoringService();
      
      $stateg = array();
      $stateg['OK'] = 0;
      $stateg['WARNING'] = 0;
      $stateg['CRITICAL'] = 0;
      $a_gstate = array();
      $nb_ressources = 0;
      $query = "SELECT * FROM `".$pmComponentscatalog_Host->getTable()."`
         WHERE `plugin_monitoring_componentscalalog_id`='".$componentscatalogs_id."'";
      $result = $DB->query($query);
      while ($dataComponentscatalog_Host=$DB->fetch_array($result)) {
         $queryService = "SELECT * FROM `".$pmService->getTable()."`
            WHERE `plugin_monitoring_componentscatalogs_hosts_id`='".$dataComponentscatalog_Host['id']."'
               AND `entities_id` IN (".$_SESSION['glpiactiveentities_string'].")";
         $resultService = $DB->query($queryService);
         while ($dataService=$DB->fetch_array($resultService)) {
            $nb_ressources++;
            if ($dataService['state_type'] != "HARD") {
               $a_gstate[$dataService['id']] = "OK";
            } else {
               $statecurrent = PluginMonitoringDisplay::getState($dataService['state'], $dataService['state_type']);
               if ($statecurrent == 'green') {
                  $a_gstate[$dataService['id']] = "OK";
               } else if ($statecurrent == 'orange') {
                  $a_gstate[$dataService['id']] = "WARNING";
               } else if ($statecurrent == 'red') {
                  $a_gstate[$dataService['id']] = "CRITICAL";
               }
            }
         }
      }
      foreach ($a_gstate as $value) {
         $stateg[$value]++;
      }
      return array($nb_ressources,
                   $stateg);
   }

   
   
   function getRessources($componentscatalogs_id, $state, $state_type='HARD') {
      global $DB;
      
      $a_services = array();
      
      $pmComponentscatalog_Host = new PluginMonitoringComponentscatalog_Host();
      
      $query = "SELECT * FROM `glpi_plugin_monitoring_services`         
         LEFT JOIN `".$pmComponentscatalog_Host->getTable()."`
            ON `plugin_monitoring_componentscatalogs_hosts_id`=
               `".$pmComponentscatalog_Host->getTable()."`.`id`
         WHERE `plugin_monitoring_componentscalalog_id`='".$componentscatalogs_id."'
            AND `state_type` LIKE '".$state_type."'
         ORDER BY `name`";
      $result = $DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         if (PluginMonitoringDisplay::getState($data['state'], $data['state_type']) == $state) {
            $a_services[] = $data;
         }
      }
      return $a_services;      
   }
   
   
   
   function showReport($componentscatalogs_id) {
      global $CFG_GLPI;

      $pmComponentscatalog_Component = new PluginMonitoringComponentscatalog_Component();
      $pmComponent = new PluginMonitoringComponent();
      
      $this->getFromDB($componentscatalogs_id);
      
      echo "<form name='form' method='post' 
         action='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/report_componentscatalog.form.php'>";
      
      echo "<table class='tab_cadre_fixe'>";
      echo '<tr class="tab_bg_1">';
      echo '<th colspan="4">';
      echo __('Report');
      echo "<input type='hidden' name='componentscatalogs_id' value='".$componentscatalogs_id."' />";
      echo '</th>';
      echo '</tr>';

      echo '<tr class="tab_bg_1">';
      echo "<td>".__('Start date')."</td>";
      echo "<td>";
      Html::showDateFormItem("date_start", date('Y-m-d H:i:s', date('U') - (24 * 3600 * 7)));
      echo "</td>";
      echo "<td>".__('End date')."</td>";
      echo "<td>";
      Html::showDateFormItem("date_end", date('Y-m-d'));
      echo "</td>";
      echo "</tr>";
      
      echo "</table>";
      echo "TODO : faire synthese semaine par semaine depuis 6 mois par exemple";
      echo "<table class='tab_cadre_fixe'>";      
      $a_composants = $pmComponentscatalog_Component->find("`plugin_monitoring_componentscalalog_id`='".$componentscatalogs_id."'");
      foreach ($a_composants as $comp_data) {
         $pmComponent->getFromDB($comp_data['plugin_monitoring_components_id']);

         echo "<tr class='tab_bg_1'>";
         echo "<td width='10'>";
         echo "<input type='checkbox' name='components_id[]' value='".$pmComponent->getID()."' checked />";
         echo "</td>";
         echo "<td>";
         echo $pmComponent->getLink();
         echo "</td>";      
         echo "</tr>";
         
         echo "<tr class='tab_bg_1'>";
         echo "<td width='10'>";
         echo "</td>";
         echo "<td>";
         PluginMonitoringServicegraph::preferences($pmComponent->getID(), 1, 1);
         echo "</td>";
      
         echo "</tr>";
      }
      echo "<tr class='tab_bg_1'>";
      echo "<td colspan='2' align='center'>";
      echo "<input type='submit' class='submit' name='generate' value='".__('Generate the report', 'monitoring')."'/>";
      echo "</td>";
      echo "</tr>";
      
      Html::closeForm();
   }
   
   
   
   function generateReport($array) {
      global $DB,$CFG_GLPI;
      
      $componentscatalogs_id = $array['componentscatalogs_id'];
      
      // define time for the report:
      // Week, week -1, week -2, month, month -1, month -2, year, year -1
      
      $pmUnavaibility = new PluginMonitoringUnavaibility();
      $pmComponent = new PluginMonitoringComponent();
      $pmServiceevent = new PluginMonitoringServiceevent();
      
      PluginMonitoringReport::beginCapture();
      
      $this->getFromDB($componentscatalogs_id);
      echo '<h1>'.$this->getTypeName().' : '.$this->getName().'<br/>
         Mois de Novembre</h1>';
      
      echo '<br/>';
      
      foreach ($array['components_id'] as $components_id) {
         $pmComponent->getFromDB($components_id);
         
         $a_name = $array['perfname'];
         
         
         echo "<table class='tab_cadre_fixe'>";
         echo '<tr class="tab_bg_1">';
         echo '<th colspan="'.(6 + (count($a_name) * 3)).'">';
         echo $pmComponent->getName();
         echo '</th>';
         echo '</tr>';
         
         echo '<tr class="tab_bg_1">';
         echo '<th>';
         echo __('Name');
         echo '</th>';
         echo '<th>';
         echo __('Entity');
         echo '</th>';
         echo '<th>';
         echo __('Itemtype');
         echo '</th>';
         echo '<th>';
         echo __('Trend', 'monitoring');
         echo '</th>';
         echo '<th>';
         echo __('Avaibility', 'monitoring');
         echo '</th>';
         echo '<th>';
         echo __('Unavaibility (time)', 'monitoring');
         echo '</th>';
         foreach ($a_name as $name) {
            echo '<th>';
            echo str_replace('_', ' ', $name).' '.__('min', 'monitoring');
            echo '</th>';
            echo '<th>';
            echo str_replace('_', ' ', $name).' '.__('avg', 'monitoring');
            echo '</th>';
            echo '<th>';
            echo str_replace('_', ' ', $name).' '.__('max', 'monitoring');
            echo '</th>';
         }
         echo '</tr>';

         $query = "SELECT `glpi_plugin_monitoring_componentscatalogs_hosts`.*, 
               `glpi_plugin_monitoring_services`.`id` as sid FROM `glpi_plugin_monitoring_componentscatalogs_hosts`
            LEFT JOIN `glpi_plugin_monitoring_services`
               ON `glpi_plugin_monitoring_componentscatalogs_hosts`.`id`=`plugin_monitoring_componentscatalogs_hosts_id`
            WHERE `plugin_monitoring_componentscalalog_id`='".$componentscatalogs_id."'
               AND `plugin_monitoring_components_id`='".$components_id."'";
         $result = $DB->query($query);
         while ($data=$DB->fetch_array($result)) {
            $itemtype = $data['itemtype'];
            $item = new $itemtype();
            $item->getFromDB($data['items_id']);
            
            $ret = array();
            if (count($a_name) > 0) {
               $queryevents = "SELECT * FROM `glpi_plugin_monitoring_serviceevents`
                  WHERE `plugin_monitoring_services_id`='".$data['sid']."'
                     AND `date` >= '".$array['date_start']."'
                     AND `date` <= '".$array['date_end']."'
                  ORDER BY `date`";
               $resultevents = $DB->query($queryevents);
               $ret = $pmServiceevent->getData($resultevents, $pmComponent->fields['graph_template']);
            }
            

            echo '<tr class="tab_bg_1">';
            echo '<td>';
            echo $item->getName();
            echo '</td>';
            echo '<td>';
            echo Dropdown::getDropdownName("glpi_entities", $item->fields['entities_id']);
            echo '</td>';
            echo '<td>';
            echo $item->getTypeName();
            echo '</td>';
            echo '<td>';
            //echo '<img src="../pics/right.png" width="10" />';
            echo "->";
            echo '</td>';
            echo '<td>';
            $a_times = $pmUnavaibility->parseEvents($data['id'], 'currentmonth');
            echo round(((($a_times[1] - $a_times[0]) / $a_times[1]) * 100), 3)."%";
            echo '</td>';
            echo '<td>';
            if ($a_times[0] == 0) {
               echo "-";
            } else {
               echo Html::timestampToString($a_times[0]);
            }
            echo '</td>';
            foreach ($a_name as $name) {
               echo '<td>';
               echo min($ret[0][$name]);
               echo '</td>';
               echo '<td>';
               echo round(array_sum($ret[0][$name]) / count($ret[0][$name]), 3);
               echo '</td>';
               echo '<td>';
               echo max($ret[0][$name]);
               echo '</td>';
            }
            echo '</tr>';
         }
         echo '</table>';
      }
      
      $content = PluginMonitoringReport::endCapture();
      PluginMonitoringReport::generatePDF($content);
//      echo $content;
   }
}

?>