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

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Name')." :</td>";
      echo "<td>";
      echo "<input type='text' name='name' value='".$this->fields["name"]."' size='30'/>";
      echo "</td>";
      echo "<td>".__('Type', 'monitoring')."&nbsp;:</td>";
      echo "<td>";
      $elements = $this->getGaugeTypes(); 
      Dropdown::showFromArray('type', $elements, array('value' => $this->fields["type"]));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Time', 'monitoring')." :</td>";
      echo "<td>";
      $elements = PluginMonitoringCustomitem_Common::getTimes(); 
      Dropdown::showFromArray('time', $elements, array('value' => $this->fields["time"]));
      echo "</td>";
      echo "<td>".__('Calendar', 'monitoring')."&nbsp;:</td>";
      echo "<td>";
      Dropdown::show('Calendar', array(
          'name'  => 'time_specific',
          'value' => $this->fields['time_specific']
      ));
      echo "</td>";
      echo "</tr>";

      $this->showFormButtons($options);
      
      
      
      
      
      
      $array = importArrayFromDB($this->fields['aggregate_items']);
      $pmPerfdataDetail = new PluginMonitoringPerfdataDetail();
      echo "<table class='tab_cadre_fixe'>";
      foreach ($array as $itemtype=>$data1) {
         foreach ($data1 as $items_id=>$data2) {
            $item1 = new $itemtype();
            $item1->getFromDB($items_id);
            echo "<tr class='tab_bg_3'>";
            echo "<td>";
            echo "[".$item1->getTypeName()."] ";
            echo $item1->fields['name'];
            echo "</td>";
            echo "<td>";
            foreach ($data2 as $itemtype2=>$data3) {
               foreach ($data3 as $items_id2=>$data4) {
                  $item2 = new $itemtype2();
                  $item2->getFromDB($items_id2);
                  echo "[".$item2->getTypeName()."] ";
                  echo $item2->fields['name'];
    
                  echo "</td>";
                  echo "<td>";
                  foreach ($data4 as $num=>$data5) {
                     $perfdetail = getAllDatasFromTable(
                             'glpi_plugin_monitoring_perfdatadetails', 
                             "`plugin_monitoring_perfdatas_id`='".$item2->fields['graph_template']."'");
                     $elements = array();
                     foreach ($perfdetail as $perfdata) {
                        for ($i=1; $i <= 15; $i++) {
                           if ($perfdata['dsname'.$i] != '') {
                              $elements[$perfdata['id']."/".$i] = $perfdata['dsname'.$i];
                           }
                        }
                     }
                     echo "<table>";
                     echo "<tr>";
                     echo "<td>";
                     echo __('Value', 'monitoring');
                     echo " : </td>";
                     echo "<td>";
                     Dropdown::showFromArray('tt', $elements);
                     echo "</td>";
                     echo "</tr>";
                     echo "<tr>";
                     echo "<td>";
                     echo __('Warning', 'monitoring');
                     echo " : </td>";
                     echo "<td>";
                     Dropdown::showFromArray('tt', $elements);
                     echo "</td>";
                     echo "</tr>";
                     echo "<tr>";
                     echo "<td>";
                     echo __('Critical', 'monitoring');
                     echo " : </td>";
                     echo "<td>";
                     Dropdown::showFromArray('tt', $elements);
                     echo "</td>";
                     echo "</tr>";
                     echo "<tr>";
                     echo "<td>";
                     echo __('Limit', 'monitoring');
                     echo " : </td>";
                     echo "<td>";
                     Dropdown::showFromArray('tt', $elements);
                     echo "</td>";
                     echo "</tr>";
                     
                  }
                  // Show value / warn / crit / limit

                  
               }
            }
            
            
            echo "</td>";
            echo "</tr>";
         }         
      }
      echo "</table>";
      
      return true;
   }
   
   
   
   function getGaugeTypes() {
      $a_types = array(
          'lastvalue'      => __('Last value', 'monitoring'),
          'lastvaluediff'  => __('Last value (diff for incremantal)', 'monitoring'),
          'average'        => __('Average', 'monitoring'),
          'median'         => __('Median', 'monitoring'),
      );
      return $a_types;
   }
   
   
   
   function type_lastvalue() {
      global $DB;
      
      $pmService        = new PluginMonitoringService();
      $pmServiceevent   = new PluginMonitoringServiceevent();
      $pmComponent      = new PluginMonitoringComponent();
      $pmPerfdataDetail = new PluginMonitoringPerfdataDetail();
      
      $val    = 0;
      $nb_val = 0;

      $a_tocheck = array(
          'warn'  => 0,
          'crit'  => 0,
          'limit' => 0
      );
      
      $a_types = array('warn', 'crit', 'limit');
      for ($i=0; $i< count($a_types); $i++) {
         if (is_numeric($this->fields['aggregate_'.$a_types[$i]])) {
            $a_ret[$a_types[$i]] = $this->fields['aggregate_'.$a_types[$i]];
         } else {
            $a_ret[$a_types[$i]] = 0;
            $a_tocheck[$a_types[$i]] = 1;
         }
      }

      
//      $input = array(
//          'PluginMonitoringComponentscatalog' => array(
//              '9' => array(
//                      'PluginMonitoringComponent' => array(
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
               // for manage warn, crit and limit
               foreach ($a_tocheck as $other_type=>$num_type) {
                  if ($num_type == 1) {
                     $other_items = importArrayFromDB($this->fields['aggregate_'.$other_type]);
                     foreach ($other_items[$itemtype] as $a_perfdatadetails) {
                        $pmPerfdataDetail->getFromDB($a_perfdatadetails['perfdatadetails_id']);
                        $a_ret[$other_type] += array_sum($ret[0][$pmPerfdataDetail->fields['dsname'.$a_perfdatadetails['perfdatadetails_dsname']]]);
                     }
                  }
               }
               break;
            
            case 'PluginMonitoringComponentscatalog':
               $pmComponentscatalog = new PluginMonitoringComponentscatalog();
               foreach ($data as $items_id=>$data2) {
                  $ret = $pmComponentscatalog->getInfoOfCatalog($items_id);
                  $a_hosts = $ret[6];
                  foreach ($data2['PluginMonitoringComponent'] as $items_id_components=>$data4) {
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
                                $nb_val,
                                $a_tocheck, 
                                $a_ret);
//                        // for manage warn, crit and limit
//                        foreach ($a_tocheck as $other_type=>$num_type) {
//                           if ($num_type == 1) {
//                              $other_items = importArrayFromDB($this->fields['aggregate_'.$other_type]);
//                              foreach ($other_items[$itemtype][$items_id]['PluginMonitoringComponents'][$items_id_components] as $a_perfdatadetails) {
//                                 $pmPerfdataDetail->getFromDB($a_perfdatadetails['perfdatadetails_id']);
//                                 $a_ret[$other_type] += array_sum($ret[0][$pmPerfdataDetail->fields['dsname'.$a_perfdatadetails['perfdatadetails_dsname']]]);
//                              }
//                           }
//                        }
                     }
                  }
               }
               break;
               
         }
      }
      if ($nb_val != 0) {
         $val = ($val / $nb_val);
      }
      foreach ($a_tocheck as $other_type=>$num_type) {
         if ($num_type == 1) {
            $a_ret[$other_type] = ($a_ret[$other_type] / $nb_val); 
         }
      }
      $a_ret['val'] = $val;
      return $a_ret;
   }

   
   
   function getLastValofService($data, &$val, &$nb_val, $a_tocheck, &$a_ret) {
      global $DB;
      
      $pmService        = new PluginMonitoringService();
      $pmServiceevent   = new PluginMonitoringServiceevent();
      $pmComponent      = new PluginMonitoringComponent();
      $pmPerfdataDetail = new PluginMonitoringPerfdataDetail();

      foreach ($data as $items_id=>$data2) {
         $pmService->getFromDB($items_id);
         $_SESSION['plugin_monitoring_checkinterval'] = PluginMonitoringComponent::getTimeBetween2Checks($pmService->fields['plugin_monitoring_components_id']);
         $pmComponent->getFromDB($pmService->fields['plugin_monitoring_components_id']);
         
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
            $val += $ret[0][$pmPerfdataDetail->fields['dsname'.$a_perfdatadetails['perfdatadetails_dsname']]][0];
            $nb_val++;
         }
         // for manage warn, crit and limit
         foreach ($a_tocheck as $other_type=>$num_type) {
            if ($num_type == 1) {
               $other_items = importArrayFromDB($this->fields['aggregate_'.$other_type]);
               foreach ($other_items[$itemtype][$items_id]['PluginMonitoringComponent'][$items_id_components] as $a_perfdatadetails) {
                  $pmPerfdataDetail->getFromDB($a_perfdatadetails['perfdatadetails_id']);
                  $a_ret[$other_type] += array_sum($ret[0][$pmPerfdataDetail->fields['dsname'.$a_perfdatadetails['perfdatadetails_dsname']]]);
               }
            }
         }
      }
   }

   
   
   function type_other($type='average') {
      global $DB;
      
      $pmService        = new PluginMonitoringService();
      $pmServiceevent   = new PluginMonitoringServiceevent();
      $pmComponent      = new PluginMonitoringComponent();
      $pmPerfdataDetail = new PluginMonitoringPerfdataDetail();

      $a_date = PluginMonitoringCustomitem_Common::getTimeRange($this->fields);
      
      $val    = 0;
      $a_val  = array();
      $nb_val = 0;

      $a_tocheck = array(
          'warn'  => 0,
          'crit'  => 0,
          'limit' => 0
      );
      
      $a_types = array('warn', 'crit', 'limit');
      for ($i=0; $i< count($a_types); $i++) {
         if (is_numeric($this->fields['aggregate_'.$a_types[$i]])) {
            $a_ret[$a_types[$i]] = $this->fields['aggregate_'.$a_types[$i]];
         } else {
            $a_ret[$a_types[$i]] = 0;
            $a_tocheck[$a_types[$i]] = 1;
         }
      }

      
      $items = importArrayFromDB($this->fields['aggregate_items']);
      foreach ($items as $itemtype=>$data) {
         switch ($itemtype) {
            
            case 'PluginMonitoringService':
               foreach ($data as $items_id=>$data2) {
                  $pmService->getFromDB($items_id);
                  $_SESSION['plugin_monitoring_checkinterval'] = PluginMonitoringComponent::getTimeBetween2Checks($pmService->fields['plugin_monitoring_components_id']);
                  $pmComponent->getFromDB($pmService->fields['plugin_monitoring_components_id']);
                  $query = "SELECT * FROM `glpi_plugin_monitoring_serviceevents`
                     WHERE `plugin_monitoring_services_id`='".$items_id."'
                        AND `date` >= '".$a_date['begin']."'
                     ORDER BY `date`";
                  $result = $DB->query($query);

                  $ret = $pmServiceevent->getData(
                          $result, 
                          $pmComponent->fields['graph_template'], 
                          $a_date['begin'],
                          $a_date['end']);
                  foreach ($data2 as $a_perfdatadetails) {
                     $pmPerfdataDetail->getFromDB($a_perfdatadetails['perfdatadetails_id']);
                     $nb_val += count($ret[0][$pmPerfdataDetail->fields['dsname'.$a_perfdatadetails['perfdatadetails_dsname']]]);
                     $val += array_sum($ret[0][$pmPerfdataDetail->fields['dsname'.$a_perfdatadetails['perfdatadetails_dsname']]]);
                     $a_val = array_merge($a_val, $ret[0][$pmPerfdataDetail->fields['dsname'.$a_perfdatadetails['perfdatadetails_dsname']]]);
                  }
                  // for manage warn, crit and limit
                  foreach ($a_tocheck as $other_type=>$num_type) {
                     if ($num_type == 1) {
                        $other_items = importArrayFromDB($this->fields['aggregate_'.$other_type]);
                        foreach ($other_items[$itemtype][$items_id] as $a_perfdatadetails) {
                           $pmPerfdataDetail->getFromDB($a_perfdatadetails['perfdatadetails_id']);
                           $a_ret[$other_type] += array_sum($ret[0][$pmPerfdataDetail->fields['dsname'.$a_perfdatadetails['perfdatadetails_dsname']]]);
                        }
                     }
                  }
               }
               break;
            
            case 'PluginMonitoringComponentscatalog':
               $pmComponentscatalog = new PluginMonitoringComponentscatalog();
               foreach ($data as $items_id=>$data2) {
                  $ret = $pmComponentscatalog->getInfoOfCatalog($items_id);
                  $a_hosts = $ret[6];
                  foreach ($data2['PluginMonitoringComponent'] as $items_id_components=>$data4) {
                     $query = "SELECT * FROM `glpi_plugin_monitoring_services`
                        WHERE `plugin_monitoring_components_id`='".$items_id_components."'
                           AND `plugin_monitoring_componentscatalogs_hosts_id` IN 
                              ('".implode("','", $a_hosts)."')
                           AND `entities_id` IN (".$_SESSION['glpiactiveentities_string'].")";
                     $result = $DB->query($query);
                     while ($dataq=$DB->fetch_array($result)) {
                        $pmService->getFromDB($dataq['id']);
                        $_SESSION['plugin_monitoring_checkinterval'] = PluginMonitoringComponent::getTimeBetween2Checks($pmService->fields['plugin_monitoring_components_id']);
                        $pmComponent->getFromDB($dataq['plugin_monitoring_components_id']);
                        $query = "SELECT * FROM `glpi_plugin_monitoring_serviceevents`
                           WHERE `plugin_monitoring_services_id`='".$dataq['id']."'
                              AND `date` >= '".$a_date['begin']."'
                           ORDER BY `date`";
                        $result = $DB->query($query);
                        
                        $ret = $pmServiceevent->getData(
                                $result, 
                                $pmComponent->fields['graph_template'], 
                                $a_date['begin'],
                                $a_date['end']);
                        foreach ($data4 as $a_perfdatadetails) {
                           $pmPerfdataDetail->getFromDB($a_perfdatadetails['perfdatadetails_id']);
                           $nb_val += count($ret[0][$pmPerfdataDetail->fields['dsname'.$a_perfdatadetails['perfdatadetails_dsname']]]);
                           $val += array_sum($ret[0][$pmPerfdataDetail->fields['dsname'.$a_perfdatadetails['perfdatadetails_dsname']]]);
                           $a_val = array_merge($a_val, $ret[0][$pmPerfdataDetail->fields['dsname'.$a_perfdatadetails['perfdatadetails_dsname']]]);
                        }
                        // for manage warn, crit and limit
                        foreach ($a_tocheck as $other_type=>$num_type) {
                           if ($num_type == 1) {
                              $other_items = importArrayFromDB($this->fields['aggregate_'.$other_type]);
                              foreach ($other_items[$itemtype][$items_id]['PluginMonitoringComponent'][$items_id_components] as $a_perfdatadetails) {
                                 $pmPerfdataDetail->getFromDB($a_perfdatadetails['perfdatadetails_id']);
                                 $a_ret[$other_type] += array_sum($ret[0][$pmPerfdataDetail->fields['dsname'.$a_perfdatadetails['perfdatadetails_dsname']]]);
                              }
                           }
                        }
                     }
                  }
               }
               break;
               
         }
      }
      if ($nb_val != 0) {
         if ($type == 'average') {
            $val = ($val / $nb_val);
         } else if ($type == 'median') {
            sort($a_val);
            $count = count($a_val); //total numbers in array
            $middleval = floor(($count-1)/2); // find the middle value, or the lowest middle value
            if($count % 2) { // odd number, middle is the median
               $median = $a_val[$middleval];
            } else { // even number, calculate avg of 2 medians
               $low = $arr[$middleval];
               $high = $arr[$middleval+1];
               $median = (($low+$high)/2);
            }
            $val = $median;
         }
      }
      foreach ($a_tocheck as $other_type=>$num_type) {
         if ($num_type == 1) {
            $a_ret[$other_type] = ($a_ret[$other_type] / $nb_val); 
         }
      }
      $a_ret['val'] = $val;
      return $a_ret;
   }

   
   // *********************************************************************//
   // ************************** Show widget ******************************//
   // *********************************************************************//
   
   
   
   function showWidget($id) {
      PluginMonitoringServicegraph::loadLib();
      
      return "<div id=\"updatecustomitem_gauge".$id."\"></div>";
   }
   
   
   
   function showWidgetFrame($id) {
      global $DB, $CFG_GLPI;
      
      $this->getFromDB($id);
      if ($this->fields['type'] == 'average'
              || $this->fields['type'] == 'median') {
         $a_val = $this->type_other($this->fields['type']);
      } else {
         $func = 'type_'.$this->fields['type'];
         $a_val = $this->$func();
      }
      $warn_cnt = $a_val['warn'] / $a_val['limit'];
      $crit_cnt = $a_val['crit'] / $a_val['limit'];

      $val = $a_val['val'];
      if (strstr($val, '.')) {
         $split = explode('.', $val);
         if (count($split[1]) > 2) {
            $val = round($val, 2);
         }
      }
      
      echo "<script>
			var gauges = [];
			
			function createGauge(name, label, min, max) {
				var config = {
					size: 198,
					label: label,
					min: undefined != min ? min : 0,
					max: undefined != max ? max : ".$a_val['limit'].",
					majorTicks: 11,
					minorTicks: 5
				}
				
				var range = config.max - config.min;
				config.greenZones = [{ from: config.min, to: config.min + range*".$warn_cnt." }];
				config.yellowZones = [{ from: config.min + range*".$warn_cnt.", to: config.min + range*".$crit_cnt." }];
				config.redZones = [{ from: config.min + range*".$crit_cnt.", to: config.max }];
				
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
      
      $sess = serialize($_SESSION);
      $sess = str_replace('"', "#####", $sess);
      $sess = str_replace("'", "@@@@@", $sess);

      $sess_id = session_id();
      echo "<script type=\"text/javascript\">

      var elcc".$id." = Ext.get(\"updatecustomitem_gauge".$id."\");
      var mgrcc".$id." = elcc".$id.".getUpdateManager();
      mgrcc".$id.".loadScripts=true;
      mgrcc".$id.".showLoadIndicator=false;
      mgrcc".$id.".startAutoRefresh(50, \"".$CFG_GLPI["root_doc"]."/plugins/monitoring/ajax/updateWidgetCustomitem_gauge.php\", \"id=".$id."&sess=".$sess."&sess_id=".$sess_id."\", \"\", true);
      </script>";
   }

}

?>