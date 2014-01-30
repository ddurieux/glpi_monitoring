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
   @since     2014
 
   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginMonitoringCustomitem_Counter extends CommonDBTM {

   

   /**
   * Get name of this type
   *
   *@return text name of this type by language of the user connected
   *
   **/
   static function getTypeName($nb=0) {
      return __('Custom item', 'monitoring')." - ".__('Counter', 'monitoring');
   }



   static function canCreate() {
      return PluginMonitoringProfile::haveRight("config", 'w');
   }


   
   static function canView() {
      return PluginMonitoringProfile::haveRight("config", 'r');
   }

   

   function getSearchOptions() {

      $tab = array();
    
      $tab['common'] = __('Commands', 'monitoring');

		$tab[1]['table'] = $this->getTable();
		$tab[1]['field'] = 'name';
		$tab[1]['linkfield'] = 'name';
		$tab[1]['name'] = __('Name');
		$tab[1]['datatype'] = 'itemlink';

      return $tab;
   }



   function defineTabs($options=array()){
      $ong = array();
      return $ong;
   }



   /**
   * Display form for agent configuration
   *
   * @param $items_id integer ID 
   * @param $options array
   *
   *@return bool true if form is ok
   *
   **/
   function showForm($items_id, $options=array(), $copy=array()) {
      global $DB,$CFG_GLPI;

      if ($items_id!='') {
         $this->getFromDB($items_id);
      } else {
         $this->getEmpty();
      }
      
      $this->showTabs($options);
      $this->showFormHeader($options);

//      echo "<tr class='tab_bg_1'>";
//      echo "<td>".__('Name')." :</td>";
//      echo "<td>";
//      echo "<input type='text' name='name' value='".$this->fields["name"]."' size='30'/>";
//      echo "</td>";
//      echo "<td>".__('Command name', 'monitoring')."&nbsp;:</td>";
//      echo "<td>";
//      echo "<input type='text' name='command_name' value='".$this->fields["command_name"]."' size='30'/>";
//      echo "</td>";
//      echo "</tr>";
      
      $this->showFormButtons($options);
      
      return true;
   }
   
   
   
//   function getLastValofService($data, &$val, &$nb_val) {
//      $pmService        = new PluginMonitoringService();
//      $pmServiceevent   = new PluginMonitoringServiceevent();
//      $pmComponent      = new PluginMonitoringComponent();
//      $pmPerfdataDetail = new PluginMonitoringPerfdataDetail();
//
//      foreach ($data as $items_id=>$data2) {
//         $pmService->getFromDB($items_id);
//         $_SESSION['plugin_monitoring_checkinterval'] = PluginMonitoringComponent::getTimeBetween2Checks($pmService->fields['plugin_monitoring_components_id']);
//         $pmComponent->getFromDB($pmService->fields['plugin_monitoring_components_id']);
//         $getvalues = $pmServiceevent->getSpecificData(
//                 $pmComponent->fields['graph_template'], 
//                 $items_id, 
//                 'last',
//                 '');
//         foreach ($data2 as $a_perfdatadetails) {
//            $pmPerfdataDetail->getFromDB($a_perfdatadetails['perfdatadetails_id']);
//            $val += $getvalues[$pmPerfdataDetail->fields['dsname'.$a_perfdatadetails['perfdatadetails_dsname']]];
//            $nb_val++;
//         }
//      }
//   }

   
   
   function getCounter() {
      global $DB;
      
      $pmService        = new PluginMonitoringService();
      $pmServiceevent   = new PluginMonitoringServiceevent();
      $pmComponent      = new PluginMonitoringComponent();
      $pmPerfdataDetail = new PluginMonitoringPerfdataDetail();
      
      $a_date = PluginMonitoringCustomitem_Common::getTimeRange($this->fields);
      
      $val    = 0;
     
      $items = importArrayFromDB($this->fields['aggregate_items']);
      foreach ($items as $itemtype=>$data) {
         switch ($itemtype) {
            
            case 'PluginMonitoringService':
               foreach ($data as $items_id=>$data2) {
                  $a_first_counter = array();
                  $a_last_counter  = array();
                  $pmService->getFromDB($items_id);
                  $_SESSION['plugin_monitoring_checkinterval'] = PluginMonitoringComponent::getTimeBetween2Checks($pmService->fields['plugin_monitoring_components_id']);
                  $pmComponent->getFromDB($pmService->fields['plugin_monitoring_components_id']);
                  // First
                  $query = "SELECT * FROM `glpi_plugin_monitoring_serviceevents`
                     WHERE `plugin_monitoring_services_id`='".$items_id."'
                        AND `date` >= '".$a_date['begin']."'
                        AND `state` = 'OK'
                        AND `perf_data` != ''
                     ORDER BY `date`
                     LIMIT 1";
                  $resultevent = $DB->query($query);
                  $dataevent = $DB->fetch_assoc($resultevent);

                  $result = $DB->query($query);
                  $ret = $pmServiceevent->getData(
                          $result, 
                          $pmComponent->fields['graph_template'], 
                          $dataevent['date'],
                          $dataevent['date']);
                  foreach ($data2 as $a_perfdatadetails) {
                     $pmPerfdataDetail->getFromDB($a_perfdatadetails['perfdatadetails_id']);
                     $a_first_counter[] = $ret[0][$pmPerfdataDetail->fields['dsname'.$a_perfdatadetails['perfdatadetails_dsname']]][0];
                  }
                  // Last
                  $query = "SELECT * FROM `glpi_plugin_monitoring_serviceevents`
                     WHERE `plugin_monitoring_services_id`='".$items_id."'
                        AND `state` = 'OK'
                        AND `perf_data` != ''
                     ORDER BY `date` DESC
                     LIMIT 1";
                  $resultevent = $DB->query($query);
                  $dataevent = $DB->fetch_assoc($resultevent);

                  $result = $DB->query($query);
                  $ret = $pmServiceevent->getData(
                          $result, 
                          $pmComponent->fields['graph_template'], 
                          $dataevent['date'],
                          $dataevent['date']);
                  foreach ($data2 as $a_perfdatadetails) {
                     $pmPerfdataDetail->getFromDB($a_perfdatadetails['perfdatadetails_id']);
                     $a_last_counter[] = $ret[0][$pmPerfdataDetail->fields['dsname'.$a_perfdatadetails['perfdatadetails_dsname']]][0];
                  }
                  // Calcul
                  foreach ($a_first_counter as $num=>$cnt) {
                     $val += ($a_last_counter[$num] - $cnt);
                  }
               }
               break;
            
            case 'PluginMonitoringComponentscatalog':
               $pmComponentscatalog = new PluginMonitoringComponentscatalog();
               foreach ($data as $items_id=>$data2) {
                  $ret = $pmComponentscatalog->getInfoOfCatalog($items_id);
                  $a_hosts = $ret[6];
                  foreach ($data2['PluginMonitoringComponents'] as $items_id_components=>$data4) {
                     $query = "SELECT * FROM `glpi_plugin_monitoring_services`
                        WHERE `plugin_monitoring_components_id`='".$items_id_components."'
                           AND `plugin_monitoring_componentscatalogs_hosts_id` IN 
                              ('".implode("','", $a_hosts)."')
                           AND `entities_id` IN (".$_SESSION['glpiactiveentities_string'].")";
                     $result = $DB->query($query);
                     while ($dataq=$DB->fetch_array($result)) {
                        $a_first_counter = array();
                        $a_last_counter  = array();
                        $pmService->getFromDB($dataq['id']);
                        $_SESSION['plugin_monitoring_checkinterval'] = PluginMonitoringComponent::getTimeBetween2Checks($pmService->fields['plugin_monitoring_components_id']);
                        $pmComponent->getFromDB($dataq['plugin_monitoring_components_id']);
                        // First
                        $query = "SELECT * FROM `glpi_plugin_monitoring_serviceevents`
                           WHERE `plugin_monitoring_services_id`='".$dataq['id']."'
                              AND `date` >= '".$a_date['begin']."'
                              AND `state` = 'OK'
                              AND `perf_data` != ''
                           ORDER BY `date`
                           LIMIT 1";
                        $resultevent = $DB->query($query);
                        $dataevent = $DB->fetch_assoc($resultevent);

                        $result = $DB->query($query);
                        $ret = $pmServiceevent->getData(
                                $result, 
                                $pmComponent->fields['graph_template'], 
                                $dataevent['date'],
                                $dataevent['date']);
                        foreach ($data4 as $a_perfdatadetails) {
                           $pmPerfdataDetail->getFromDB($a_perfdatadetails['perfdatadetails_id']);
                           $a_first_counter[] = $ret[0][$pmPerfdataDetail->fields['dsname'.$a_perfdatadetails['perfdatadetails_dsname']]][0];
                        }
                        // Last
                        $query = "SELECT * FROM `glpi_plugin_monitoring_serviceevents`
                           WHERE `plugin_monitoring_services_id`='".$dataq['id']."'
                              AND `state` = 'OK'
                              AND `perf_data` != ''
                           ORDER BY `date` DESC
                           LIMIT 1";
                        $resultevent = $DB->query($query);
                        $dataevent = $DB->fetch_assoc($resultevent);

                        $result = $DB->query($query);
                        $ret = $pmServiceevent->getData(
                                $result, 
                                $pmComponent->fields['graph_template'], 
                                $dataevent['date'],
                                $dataevent['date']);
                        foreach ($data4 as $a_perfdatadetails) {
                           $pmPerfdataDetail->getFromDB($a_perfdatadetails['perfdatadetails_id']);
                           $a_last_counter[] = $ret[0][$pmPerfdataDetail->fields['dsname'.$a_perfdatadetails['perfdatadetails_dsname']]][0];
                        }
                        // Calcul
                        foreach ($a_first_counter as $num=>$cnt) {
                           $val += ($a_last_counter[$num] - $cnt);
                        }
                     }
                  }
               }
               break;
               
         }
      }
      return $val;
   }


   // *********************************************************************//
   // ************************** Show widget ******************************//
   // *********************************************************************//
   
   
   
   function showWidget($id) {
      PluginMonitoringServicegraph::loadLib();
      
      return "<div id=\"updatecustomitem_counter".$id."\"></div>";
   }
   
   
   
   function showWidgetFrame($id) {
      global $DB, $CFG_GLPI;
      $this->getFromDB($id);
      
      echo '<div class="ch-item">
         <div class="ch-info-counter">
			<h1><a href="';
         echo '<span id="devicea-'.$id.'">'.$this->getName().'</span></a></h1>
			<p><font style="font-size: 28px;">'.$this->getCounter().'</font></p>
         </div>
		</div>';

   }

   
   
   function ajaxLoad($id) {
      global $CFG_GLPI;
      
      $sess_id = session_id();
      PluginMonitoringSecurity::updateSession();

      echo "<script type=\"text/javascript\">

      var elcc".$id." = Ext.get(\"updatecustomitem_counter".$id."\");
      var mgrcc".$id." = elcc".$id.".getUpdateManager();
      mgrcc".$id.".loadScripts=true;
      mgrcc".$id.".showLoadIndicator=false;
      mgrcc".$id.".startAutoRefresh(50, \"".$CFG_GLPI["root_doc"].
              "/plugins/monitoring/ajax/updateWidgetCustomitem_counter.php\","
              . " \"id=".$id."&sess_id=".$sess_id.
              "&glpiID=".$_SESSION['glpiID'].
              "&plugin_monitoring_securekey=".$_SESSION['plugin_monitoring_securekey'].
              "\", \"\", true);
      </script>";
   }

}

?>