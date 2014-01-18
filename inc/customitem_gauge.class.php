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

class PluginMonitoringCustomitem_Gauge extends CommonDBTM {

   

   /**
   * Get name of this type
   *
   *@return text name of this type by language of the user connected
   *
   **/
   static function getTypeName($nb=0) {
      return __('Custom item', 'monitoring')." - ".__('Gauge', 'monitoring');
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

/*
  
DB

name
entities_id
is_recursive
type
aggregate_items // we aggrgate many services (services and/or components)
   [itemtype][id]...
time // 1 day, 1 week(7 days or since last monday), 1 month(30 days or since 01) , 1 year
time_specific // like use working hours

  
       
  */    
      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Name')." :</td>";
      echo "<td>";
      echo "<input type='text' name='name' value='".$this->fields["name"]."' size='30'/>";
      echo "</td>";
      echo "<td>".__('Command name', 'monitoring')."&nbsp;:</td>";
      echo "<td>";
      echo "<input type='text' name='command_name' value='".$this->fields["command_name"]."' size='30'/>";
      echo "</td>";
      echo "</tr>";
      
      $this->showFormButtons($options);
      
      return true;
   }
   
   
   
   function getGaugeTypes() {
      $a_types = array(
          'lastvalue'      => __('Last value', 'monitoring'),
          'lastvaluediff'  => __('Last value (diff for incremantal)', 'monitoring'),
          'average'        => __('Average', 'monitoring'),
          'median'         => __('Median', 'monitoring'),
          'counter'        => __('counter (for incremental values)', 'monitoring'),
          
      );
      return $a_types;
   }
   
   
   
   function getGaugeTimes() {
      $a_times = array(
          'lastday24h'      => __('Last day (last 24 hours)', 'monitoring'),
          'lastdaymidnight' => __('Last day (since midnight)', 'monitoring'),
          'week7d'          => __('Last week (last 7 days)', 'monitoring'),
          'weekmonday'      => __('Last week (since Monday)', 'monitoring'),
          'weeksunday'      => __('Last week (since Sunday)', 'monitoring'),
          'month30d'        => __('Last month (last 30 days)', 'monitoring'),
          'monthfirstday'   => __('Last month (since first day of month)', 'monitoring'),
          'year365day'      => __('Last year (365 days)', 'monitoring'),
          'yearjanuary'     => __('Last year (since first January)', 'monitoring')
      );
      return $a_times;
   }
   
   
   
   function type_lastvalue() {
      global $DB;
      
      $pmService        = new PluginMonitoringService();
      $pmServiceevent   = new PluginMonitoringServiceevent();
      $pmComponent      = new PluginMonitoringComponent();
      $pmPerfdataDetail = new PluginMonitoringPerfdataDetail();
      
      $val    = 0;
      $nb_val = 0;

//      $input = array(
//          'PluginMonitoringComponentscatalog' => array(
//              '9' => array(
//                      'PluginMonitoringComponents' => array(
//                          '11' => array(array(
//                              'perfdatadetails_id' => '3',
//                              'perfdatadetails_dsname' => '1'
//                          ))
//                         )
//                      
//                      )
//          )
//      );
//$this->update(array(
//    'id' => $this->fields['id'],
//    'aggregate_items' => exportArrayToDB($input)
//));
      
      $items = importArrayFromDB($this->fields['aggregate_items']);
      foreach ($items as $itemtype=>$data) {
         switch ($itemtype) {
            
            case 'PluginMonitoringService':
               $a_ret = $this->getLastValofService($data, $val, $nb_val);
               $val    = $a_ret[0];
               $nb_val = $a_ret[1];
               break;
            
            case 'PluginMonitoringComponentscatalog':
               $pmComponentscatalog = new PluginMonitoringComponentscatalog();
               foreach ($data as $items_id=>$data2) {
                  $ret = $pmComponentscatalog->getInfoOfCatalog($items_id);
                  $a_hosts = $ret[5];
                  foreach ($data2['PluginMonitoringComponents'] as $items_id_components=>$data4) {
                     // get services  (use entities of user)
                     $query = "SELECT * FROM `glpi_plugin_monitoring_services`
                        WHERE `plugin_monitoring_components_id`='".$items_id_components."'
                           AND `plugin_monitoring_componentscatalogs_hosts_id` IN 
                              ('".implode("','", $a_hosts)."')
                           AND `entities_id` IN (".$_SESSION['glpiactiveentities_string'].")";
                     $result = $DB->query($query);
                     while ($dataq=$DB->fetch_array($result)) {
                        $this->getLastValofService(
                                array($dataq['id'] => $data4), 
                                $val, 
                                $nb_val);
                     }
                  }
               }
               break;
               
         }
      }
      if ($nb_val != 0) {
         $val = ($val / $nb_val);
      }
      return $val;
   }

   
   
   function getLastValofService($data, &$val, &$nb_val) {
      $pmService        = new PluginMonitoringService();
      $pmServiceevent   = new PluginMonitoringServiceevent();
      $pmComponent      = new PluginMonitoringComponent();
      $pmPerfdataDetail = new PluginMonitoringPerfdataDetail();
      
      foreach ($data as $items_id=>$data2) {
         $pmService->getFromDB($items_id);
         $pmComponent->getFromDB($pmService->fields['plugin_monitoring_components_id']);
         $getvalues = $pmServiceevent->getSpecificData(
                 $pmComponent->fields['graph_template'], 
                 $items_id, 
                 'last',
                 '');
         foreach ($data2 as $a_perfdatadetails) {
            $pmPerfdataDetail->getFromDB($a_perfdatadetails['perfdatadetails_id']);
            $val += $getvalues[$pmPerfdataDetail->fields['dsname'.$a_perfdatadetails['perfdatadetails_dsname']]];
            $nb_val++;
         }
      }
   }

   
   function showWidget($id) {
      PluginMonitoringServicegraph::loadLib();
      
      return "<div id=\"updatecustomitem_gauge".$id."\"></div>";
   }
   
   
   
   function showWidgetFrame($id) {
      global $DB, $CFG_GLPI;
      
      $this->getFromDB($id);
      $func = 'type_'.$this->fields['type'];
      $val = $this->$func();
      
      //$val = mt_rand(0, 100);
      echo "<script>
			var gauges = [];
			
			function createGauge(name, label, min, max) {
				var config = {
					size: 198,
					label: label,
					min: undefined != min ? min : 0,
					max: undefined != max ? max : 100,
					majorTicks: 11,
					minorTicks: 5
				}
				
				var range = config.max - config.min;
				config.greenZones = [{ from: config.min, to: config.min + range*0.80 }];
				config.yellowZones = [{ from: config.min + range*0.80, to: config.min + range*0.95 }];
				config.redZones = [{ from: config.min + range*0.95, to: config.max }];
				
				gauges[name] = new Gauge(name + 'GaugeContainer', config);
				gauges[name].render();
            gauges[name].redraw(".$val.");
			}
			
		</script>
		<span id='updatecustomitem_gauge".$id."GaugeContainer'></span>

      <script>createGauge('updatecustomitem_gauge".$id."', '".$this->fields['name']."');</script>";
      
   }

   
   
   function ajaxLoad($id) {
      global $CFG_GLPI;
      
      echo "<script type=\"text/javascript\">

      var elcc".$id." = Ext.get(\"updatecustomitem_gauge".$id."\");
      var mgrcc".$id." = elcc".$id.".getUpdateManager();
      mgrcc".$id.".loadScripts=true;
      mgrcc".$id.".showLoadIndicator=false;
      mgrcc".$id.".startAutoRefresh(50, \"".$CFG_GLPI["root_doc"]."/plugins/monitoring/ajax/updateWidgetCustomitem_gauge.php\", \"id=".$id."\", \"\", true);
      </script>";
   }

}

?>