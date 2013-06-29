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

class PluginMonitoringComponentscatalog_rule extends CommonDBTM {
   

   /**
   * Get name of this type
   *
   *@return text name of this type by language of the user connected
   *
   **/
   static function getTypeName($nb=0) {
      return _n('Rule', 'Rules', $nb);
   }



   static function canCreate() {
      return PluginMonitoringProfile::haveRight("componentscatalog", 'w');
   }


   
   static function canView() {
      return PluginMonitoringProfile::haveRight("componentscatalog", 'r');
   }



   function showRules($componentscatalogs_id) {
      global $DB;

      $this->preaddRule($componentscatalogs_id);

      $query = "SELECT * FROM `".$this->getTable()."`
         WHERE `plugin_monitoring_componentscalalog_id`='".$componentscatalogs_id."'";
      $result = $DB->query($query);
      echo "<table class='tab_cadre_fixe'>";
      echo "<tr>";
      echo "<th colspan='3'>";
      echo __('Rule');
      echo "</th>";
      echo "</tr>";
      
      echo "<tr>";
      echo "<th width='10'>&nbsp;</th>";
      echo "<th>".__('Type')."</th>";
      echo "<th>".__('Name')."</th>";
      echo "</tr>";
      
      while ($data=$DB->fetch_array($result)) {
         echo "<tr>";
         echo "<td>";
         echo "<input type='checkbox' name='item[".$data["id"]."]' value='1'>";
         echo "</td>";
         echo "<td class='center'>";
         echo $data['itemtype'];
         echo "</td>";
         echo "<td class='center'>";
         $this->getFromDB($data['id']);
         echo $this->getLink();
         echo "</td>";
         echo "</tr>";
      }
      
      echo "</table>";

      return true;
   }
   
   
   
   function preaddRule($componentscatalogs_id) {
      global $CFG_GLPI,$DB;
 
      $networkport_types = $CFG_GLPI['networkport_types'];
      $networkport_types[] = "PluginMonitoringNetworkport";
      
      $a_usedItemtypes = array();
      $query = "SELECT * FROM `".$this->getTable()."`
         WHERE `plugin_monitoring_componentscalalog_id`='".$componentscatalogs_id."'";
      $result = $DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         $key = array_search($data['itemtype'], $networkport_types);
         if (isset($key)) {
            unset($networkport_types[$key]);
         }
      }
      
      if (count($a_usedItemtypes) == count($networkport_types)) {
         return;
      }
      
      $this->getEmpty();
      
      $this->showFormHeader();
      
      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo __('Name')."&nbsp;:";
      echo "</td>";
      echo "<td>";
      echo "<input type='text' name='name' value=''/>";
      echo "</td>";
      echo "<td>";
      echo __('Item type')."&nbsp;:";
      echo "</td>";
      echo "<td>";

      Dropdown::showItemType($networkport_types);
      echo "<input type='hidden' name='plugin_monitoring_componentscalalog_id' value='".$componentscatalogs_id."' >";
      echo "</td>";
      echo "</tr>";
      
      $this->showFormButtons();
     
   }
   
   
   
   function addRule() {
      
      Search::manageGetValues($_GET['itemtype']);
      $pmSearch = new PluginMonitoringSearch();
      $pmSearch->showGenericSearch($_GET['itemtype'], $_GET);
      
      echo "<br/><br/>";
      echo "<table class='tab_cadre_fixe'>";

      echo "<tr>";
      echo "<th>";
      echo __('Preview', 'monitoring');
      echo "</th>";
      echo "</tr>";
      
      echo "<tr>";
      echo "<td>";
      
      $pmComponentscatalog = new PluginMonitoringComponentscatalog();
      $pmComponentscatalog->getFromDB($_GET['plugin_monitoring_componentscalalog_id']);
     
      $default_entity = 0;
      if (isset($_SESSION['glpiactive_entity'])) {
         $default_entity = $_SESSION['glpiactive_entity'];
      }
      $entities_isrecursive = 0;
      if (isset($_SESSION['glpiactiveentities'])
              AND count($_SESSION['glpiactiveentities']) > 1) {
         $entities_isrecursive = 1;
      }
      
      Session::changeActiveEntities($pmComponentscatalog->fields['entities_id'], 
                           $pmComponentscatalog->fields['is_recursive']);
      
      Search::showList($_GET['itemtype'], $_GET);

      Session::changeActiveEntities($default_entity,
                           $entities_isrecursive);
      echo "</td>";
      echo "</tr>";
      echo "</table>";
      

   }
   

   
   /*
    * Use when add a rule, caclculate for all items in GLPI DB
    */
   static function getItemsDynamicly($parm) {
      global $DB;

      $pmCc_Rule                 = new PluginMonitoringComponentscatalog_rule();
      $pmComponentscatalog_Host  = new PluginMonitoringComponentscatalog_Host();
      $pmComponentscatalog       = new PluginMonitoringComponentscatalog();
      $pmSearch                  = new PluginMonitoringSearch();
      $pmService                 = new PluginMonitoringService();
      
      $devices_present = array();
      $devicesnetworkport_present = array();
      if ($pmCc_Rule->getFromDB($parm->fields['id'])) {
      
         // Load right entity
            $pmComponentscatalog->getFromDB($pmCc_Rule->fields['plugin_monitoring_componentscalalog_id']);
            $default_entity = 0;
            if (isset($_SESSION['glpiactive_entity'])) {
               $default_entity = $_SESSION['glpiactive_entity'];
            }
            $entities_isrecursive = 0;
            if (isset($_SESSION['glpiactiveentities'])
                    AND count($_SESSION['glpiactiveentities']) > 1) {
               $entities_isrecursive = 1;
            }
            Session::changeActiveEntities($pmComponentscatalog->fields['entities_id'], 
                                 $pmComponentscatalog->fields['is_recursive']);
         
         
         $get_tmp = '';
         $itemtype = $pmCc_Rule->fields['itemtype'];
         if (isset($_GET)) {
             $get_tmp = $_GET;  
         }
         if (isset($_SESSION["glpisearchcount"][$pmCc_Rule->fields['itemtype']])) {
            unset($_SESSION["glpisearchcount"][$pmCc_Rule->fields['itemtype']]);
         }
         if (isset($_SESSION["glpisearchcount2"][$pmCc_Rule->fields['itemtype']])) {
            unset($_SESSION["glpisearchcount2"][$pmCc_Rule->fields['itemtype']]);
         }

         $_GET = importArrayFromDB($pmCc_Rule->fields['condition']);

         $_GET["glpisearchcount"] = count($_GET['field']);
         if (isset($_GET['field2'])) {
            $_GET["glpisearchcount2"] = count($_GET['field2']);
         }

         Search::manageGetValues($pmCc_Rule->fields['itemtype']);

         $queryd = "SELECT * FROM `glpi_plugin_monitoring_componentscatalogs_hosts`
            WHERE `plugin_monitoring_componentscalalog_id`='".$pmCc_Rule->fields["plugin_monitoring_componentscalalog_id"]."'
               AND `itemtype`='".$pmCc_Rule->fields['itemtype']."'
               AND `is_static`='0'";
         $result = $DB->query($queryd);
         while ($data=$DB->fetch_array($result)) {
            $devices_present[$data['id']] = 1;
         }
         
         $queryd = "SELECT `glpi_plugin_monitoring_services`.`id` FROM `glpi_plugin_monitoring_componentscatalogs_hosts`
               LEFT JOIN `glpi_plugin_monitoring_services`
                  ON `plugin_monitoring_componentscatalogs_hosts_id` = 
                     `glpi_plugin_monitoring_componentscatalogs_hosts`.`id`
            WHERE `plugin_monitoring_componentscalalog_id`='".$pmCc_Rule->fields["plugin_monitoring_componentscalalog_id"]."'
               AND `itemtype`='NetworkEquipment'
               AND `is_static`='0'";
         $result = $DB->query($queryd);
         while ($data=$DB->fetch_array($result)) {
            $devicesnetworkport_present[$data['id']] = 1;
         }
         
         $glpilist_limit = $_SESSION['glpilist_limit'];
         $_SESSION['glpilist_limit'] = 500000;
         $result = $pmSearch->constructSQL($itemtype, 
                                        $_GET);
         $_SESSION['glpilist_limit'] = $glpilist_limit;

         while ($data=$DB->fetch_array($result)) {
            $networkports_id = 0;
            $itemtype_device = $pmCc_Rule->fields['itemtype'];
            $items_id_device = $data['id'];
            if ($itemtype_device == 'PluginMonitoringNetworkport') {
//               $queryh = "SELECT `itemtype`, `items_id`,
//                     `glpi_plugin_monitoring_services`.`id`,                
//                     `glpi_plugin_monitoring_componentscatalogs_hosts`.`id` as hid
//                     FROM `glpi_plugin_monitoring_componentscatalogs_hosts`
//                  LEFT JOIN `glpi_plugin_monitoring_services`
//                     ON `plugin_monitoring_componentscatalogs_hosts_id` = 
//                        `glpi_plugin_monitoring_componentscatalogs_hosts`.`id`
//                  WHERE `plugin_monitoring_componentscalalog_id`='".$pmCc_Rule->fields["plugin_monitoring_componentscalalog_id"]."'
//                     AND `itemtype`='".$itemtype_device."'
//                     AND `items_id`='".$items_id_device."'
//                     AND `glpi_plugin_monitoring_services`.`id` IS NULL";
//               $resulth = $DB->query($queryh);
//               while ($datah=$DB->fetch_array($resulth)) {
//                  $pmComponentscatalog_Host->delete(array('id'=>$datah['hid']));
//               }
               
               $pmNetworkport = new PluginMonitoringNetworkport();
               $pmNetworkport->getFromDB($data['id']);
               $itemtype_device = $pmNetworkport->fields['itemtype'];
               $items_id_device = $pmNetworkport->fields['items_id'];
               $networkports_id = $pmNetworkport->fields['networkports_id'];
               $networkPort = new NetworkPort();
               
               if ($networkPort->getFromDB($networkports_id)) {   
                  
                  $querynet = "SELECT `itemtype`, `items_id`,
                        `glpi_plugin_monitoring_services`.`id`               
                        FROM `glpi_plugin_monitoring_componentscatalogs_hosts`
                     LEFT JOIN `glpi_plugin_monitoring_services`
                        ON `plugin_monitoring_componentscatalogs_hosts_id` = 
                           `glpi_plugin_monitoring_componentscatalogs_hosts`.`id`
                     WHERE `plugin_monitoring_componentscalalog_id`='".$pmCc_Rule->fields["plugin_monitoring_componentscalalog_id"]."'
                        AND `itemtype`='".$itemtype_device."'
                        AND `items_id`='".$items_id_device."'
                        AND `networkports_id`='".$networkports_id."'
                           LIMIT 1";
                   $resultnet = $DB->query($querynet);
                   if ($DB->numrows($resultnet) == 0) {
                     $input = array();
                     $input['plugin_monitoring_componentscalalog_id'] = $pmCc_Rule->fields["plugin_monitoring_componentscalalog_id"];
                     $input['is_static'] = '0';
                     $input['items_id'] = $items_id_device;
                     $input['itemtype'] = $itemtype_device;
                     $componentscatalogs_hosts_id = $pmComponentscatalog_Host->add($input);
                     $pmComponentscatalog_Host->linkComponentsToItem($pmCc_Rule->fields["plugin_monitoring_componentscalalog_id"], 
                                                                     $componentscatalogs_hosts_id,
                                                                     $networkports_id);
                  } else {
                     $data2 = $DB->fetch_assoc($resultnet);
                     // modify entity of services (if entity of device is changed)
                        $itemtype = $data2['itemtype'];
                        $item = new $itemtype();
                        $item->getFromDB($data2['items_id']);
                        $queryu = "UPDATE `glpi_plugin_monitoring_services`
                           SET `entities_id`='".$item->fields['entities_id']."'
                              WHERE `id`='".$data2['id']."'";
                        $DB->query($queryu);                  


                     unset($devicesnetworkport_present[$data2['id']]);
                  }
               } else {
                  $pmNetworkport->delete($pmNetworkport->fields);
               }    
               // Reload current entity
               Session::changeActiveEntities($default_entity,
                             $entities_isrecursive);
               
            } else {
               $queryh = "SELECT * FROM `glpi_plugin_monitoring_componentscatalogs_hosts`
                  WHERE `plugin_monitoring_componentscalalog_id`='".$pmCc_Rule->fields["plugin_monitoring_componentscalalog_id"]."'
                     AND `itemtype`='".$itemtype_device."'
                     AND `items_id`='".$items_id_device."'
                        LIMIT 1";
               $resulth = $DB->query($queryh);
               if ($DB->numrows($resulth) == '0') {
                  $input = array();
                  $input['plugin_monitoring_componentscalalog_id'] = $pmCc_Rule->fields["plugin_monitoring_componentscalalog_id"];
                  $input['is_static'] = '0';
                  $input['items_id'] = $items_id_device;
                  $input['itemtype'] = $itemtype_device;
                  $componentscatalogs_hosts_id = $pmComponentscatalog_Host->add($input);
                  $pmComponentscatalog_Host->linkComponentsToItem($pmCc_Rule->fields["plugin_monitoring_componentscalalog_id"], 
                                                                  $componentscatalogs_hosts_id,
                                                                  $networkports_id);
               } else {
                  $data2 = $DB->fetch_assoc($resulth);
                  // modify entity of services (if entity of device is changed)
                     $itemtype = $data2['itemtype'];
                     $item = new $itemtype();
                     $item->getFromDB($data2['items_id']);
                     $queryu = "UPDATE `glpi_plugin_monitoring_services`
                        SET `entities_id`='".$item->fields['entities_id']."'
                           WHERE `plugin_monitoring_componentscatalogs_hosts_id`='".$data2['id']."'";
                     $DB->query($queryu);                  


                  unset($devices_present[$data2['id']]);
               }
            }
         }
         
         // Reload current entity
            Session::changeActiveEntities($default_entity,
                                 $entities_isrecursive);
            
         foreach ($devicesnetworkport_present as $id => $num) {
            $_SESSION['plugin_monitoring_hosts']['itemtype'] = $itemtype_device;
            $_SESSION['plugin_monitoring_hosts']['items_id'] = $items_id_device;
            $pmService->delete(array('id'=>$id));
         }
      } else { // Purge
         $queryd = "SELECT * FROM `glpi_plugin_monitoring_componentscatalogs_hosts`
            WHERE `plugin_monitoring_componentscalalog_id`='".$parm->fields["plugin_monitoring_componentscalalog_id"]."'
               AND `itemtype`='".$parm->fields['itemtype']."'
               AND `is_static`='0'";
         $result = $DB->query($queryd);
         while ($data=$DB->fetch_array($result)) {
            $devices_present[$data['id']] = 1;
         }
      }
      foreach ($devices_present as $id => $num) {
         $pmComponentscatalog_Host->delete(array('id'=>$id));
      }
      return true;
   }
   
   
   
   /*
    * When add or update an item (Computer, ...), check if 
    * a rule verify it
    */
   static function isThisItemCheckRule($parm) {
      global $DB;

      $itemtype = get_class($parm);
      $items_id = $parm->fields['id'];
      
      $a_find = array();
      $pmComponentscatalog_rule = new PluginMonitoringComponentscatalog_rule();
      $pmComponentscatalog      = new PluginMonitoringComponentscatalog();
      $pmSearch                 = new PluginMonitoringSearch();

      $query = "SELECT * FROM `".$pmComponentscatalog_rule->getTable()."`
         WHERE `itemtype`='".$itemtype."'";
      $result = $DB->query($query);
      $get_tmp = array();
      if (isset($_GET)) {
          $get_tmp = $_GET;  
      }
      while ($data=$DB->fetch_array($result)) {
         if (isset($_SESSION["glpisearchcount"][$data['itemtype']])) {
            unset($_SESSION["glpisearchcount"][$data['itemtype']]);
         }
         if (isset($_SESSION["glpisearchcount2"][$data['itemtype']])) {
            unset($_SESSION["glpisearchcount2"][$data['itemtype']]);
         }

         $_GET = importArrayFromDB($data['condition']);
         
         $_GET["glpisearchcount"] = count($_GET['field']);
         if (isset($_GET['field2'])) {
            $_GET["glpisearchcount2"] = count($_GET['field2']);
         }
         
         if (!isset($_SESSION['glpiactiveentities_string'])) {
            $_SESSION['glpiactiveentities_string'] = $parm->fields['entities_id'];
         }
         
         // Load right entity
            $pmComponentscatalog->getFromDB($data['plugin_monitoring_componentscalalog_id']);
            $default_entity = 0;
            if (isset($_SESSION['glpiactive_entity'])) {
               $default_entity = $_SESSION['glpiactive_entity'];
            }
            $entities_isrecursive = 0;
            if (isset($_SESSION['glpiactiveentities'])
                    AND count($_SESSION['glpiactiveentities']) > 1) {
               $entities_isrecursive = 1;
            }
            if (!isset($_SESSION['glpiactiveprofile']['entities'])) {
               $_SESSION['glpiactiveprofile']['entities'] = array(
                   $pmComponentscatalog->fields['entities_id'] => array(
                       'id'           => $pmComponentscatalog->fields['entities_id'],
                       'name'         => '',
                       'is_recursive' => $pmComponentscatalog->fields['is_recursive']
                   )
               );
            }
            Session::changeActiveEntities($pmComponentscatalog->fields['entities_id'], 
                                 $pmComponentscatalog->fields['is_recursive']);
            
         
         Search::manageGetValues($data['itemtype']);

         $resultr = $pmSearch->constructSQL($itemtype, 
                                        $_GET,
                                        $items_id);
         if ($DB->numrows($resultr) > 0) {
            $a_find[$data['plugin_monitoring_componentscalalog_id']] = 1;
         } else {
            if (!isset($a_find[$data['plugin_monitoring_componentscalalog_id']])) {
               $a_find[$data['plugin_monitoring_componentscalalog_id']] = 0;
            }
         }
         
         // Reload current entity
            Session::changeActiveEntities($default_entity,
                                 $entities_isrecursive);
      }
      if (count($get_tmp) > 0) {
         $_GET = $get_tmp; 
      }
      $pmComponentscatalog_Host= new PluginMonitoringComponentscatalog_Host();
      
      foreach ($a_find as $componentscalalog_id => $is_present) {
         if ($is_present == '0') { // * Remove from dynamic if present      
            $query = "SELECT * FROM `glpi_plugin_monitoring_componentscatalogs_hosts`
               WHERE `plugin_monitoring_componentscalalog_id`='".$componentscalalog_id."'
                  AND `itemtype`='".$itemtype."'
                  AND `items_id`='".$items_id."'
                  AND`is_static`='0'";
            $result = $DB->query($query);
            while ($data=$DB->fetch_array($result)) {
               $pmComponentscatalog_Host->delete(array('id'=>$data['id']));
            }
         } else { //  add if not present
            $query = "SELECT * FROM `glpi_plugin_monitoring_componentscatalogs_hosts`
               WHERE `plugin_monitoring_componentscalalog_id`='".$componentscalalog_id."'
                  AND `itemtype`='".$itemtype."'
                  AND `items_id`='".$items_id."'
                     LIMIT 1";
            $result = $DB->query($query);
            if ($DB->numrows($result) == '0') {
               $input = array();
               $input['plugin_monitoring_componentscalalog_id'] = $componentscalalog_id;
               $input['is_static'] = '0';
               $input['items_id'] = $items_id;
               $input['itemtype'] = $itemtype;
               $componentscatalogs_hosts_id = $pmComponentscatalog_Host->add($input);
               $pmComponentscatalog_Host->linkComponentsToItem($componentscalalog_id, 
                                                               $componentscatalogs_hosts_id);
            } else {
               $data2 = $DB->fetch_assoc($result);
               // modify entity of services (if entity of device is changed)
                  $item = new $itemtype();
                  $item->getFromDB($items_id);
                  $queryu = "UPDATE `glpi_plugin_monitoring_services`
                     SET `entities_id`='".$item->fields['entities_id']."'
                        WHERE `plugin_monitoring_componentscatalogs_hosts_id`='".$data2['id']."'";
                  $DB->query($queryu); 
            }     
         }
      }
      if ($itemtype == 'NetworkEquipment') {
         //Get networkports
         $pmComponentscatalog_rule->isThisItemCheckRuleNetworkport($parm);
      }
   }
   
   
   
   static function isThisItemCheckRuleNetworkport($parm) {
      global $DB;
      
      $pmComponentscatalog_rule = new self();
      $pmService = new PluginMonitoringService();
      $pmSearch  = new PluginMonitoringSearch();

      $a_networkports_id = array();
      if (get_class($parm) == 'PluginMonitoringNetworkport') {
         $a_networkports_id[$parm->fields['networkports_id']] = $parm->fields['items_id'];
      } else if (get_class($parm) == 'NetworkEquipment') {
         $query = "SELECT * FROM `glpi_plugin_monitoring_networkports`
            WHERE `items_id`='".$parm->fields['id']."'";
         $result = $DB->query($query);
         while ($data=$DB->fetch_array($result)) {
            $a_networkports_id[$data['networkports_id']] = $parm->fields['id'];
         }         
      }
            
      foreach ($a_networkports_id as $networkports_id=>$networkequipments_id) {
         $a_find = array();

         $query = "SELECT * FROM `".$pmComponentscatalog_rule->getTable()."`
            WHERE `itemtype`='PluginMonitoringNetworkport'";
         $result = $DB->query($query);
         $get_tmp = array();
         if (isset($_GET)) {
             $get_tmp = $_GET;  
         }
         while ($data=$DB->fetch_array($result)) {
            if (isset($_SESSION["glpisearchcount"][$data['itemtype']])) {
               unset($_SESSION["glpisearchcount"][$data['itemtype']]);
            }
            if (isset($_SESSION["glpisearchcount2"][$data['itemtype']])) {
               unset($_SESSION["glpisearchcount2"][$data['itemtype']]);
            }

            $_GET = importArrayFromDB($data['condition']);

            $_GET["glpisearchcount"] = count($_GET['field']);
            if (isset($_GET['field2'])) {
               $_GET["glpisearchcount2"] = count($_GET['field2']);
            }

            if (!isset($_SESSION['glpiactiveentities_string'])) {
               $_SESSION['glpiactiveentities_string'] = $parm->fields['entities_id'];
            }

            Search::manageGetValues($data['itemtype']);

            $resultr = $pmSearch->constructSQL("PluginMonitoringNetworkport", 
                                           $_GET,
                                           $networkports_id);
            if ($DB->numrows($resultr) > 0) {
               $a_find[$data['plugin_monitoring_componentscalalog_id']][$networkports_id] = 1;
            } else {
               if (!isset($a_find[$data['plugin_monitoring_componentscalalog_id']][$networkports_id])) {
                  $a_find[$data['plugin_monitoring_componentscalalog_id']][$networkports_id] = 0;
               }
            }
         }
         if (count($get_tmp) > 0) {
            $_GET = $get_tmp; 
         }
         $pmComponentscatalog_Host= new PluginMonitoringComponentscatalog_Host();

         foreach ($a_find as $componentscalalog_id => $datan) {
            foreach ($datan as $networkports_id=>$is_present) {
               // Get all networports in this rule
               if ($is_present == '0') { // * Remove from dynamic if present      
                  $query = "SELECT `glpi_plugin_monitoring_services`.`id`,
                        `glpi_plugin_monitoring_componentscatalogs_hosts`.`id` as hid
                        FROM `glpi_plugin_monitoring_services`
                     LEFT JOIN `glpi_plugin_monitoring_componentscatalogs_hosts` ON
                        `plugin_monitoring_componentscatalogs_hosts_id` = `glpi_plugin_monitoring_componentscatalogs_hosts`.`id`
                     WHERE `plugin_monitoring_componentscalalog_id`='".$componentscalalog_id."'
                        AND `itemtype`='NetworkEquipment'
                        AND `items_id`='".$networkequipments_id."'
                        AND `is_static`='0'
                        AND `networkports_id`='".$networkports_id."'";
                  $result = $DB->query($query);
                  while ($data=$DB->fetch_array($result)) {
                     $pmComponentscatalog_Host->getFromDB($data['hid']);
                     $_SESSION['plugin_monitoring_hosts'] = $pmComponentscatalog_Host->fields;
                     $pmService->delete(array('id'=>$data['id']));
                  }
               } else { //  add if not present
                  // * Add componentscatalogs_hosts if not exist
                  $componentscatalogs_hosts_id = 0;
                  $query = "SELECT * FROM `glpi_plugin_monitoring_componentscatalogs_hosts`
                     WHERE `plugin_monitoring_componentscalalog_id`='".$componentscalalog_id."'
                        AND `itemtype`='NetworkEquipment'
                        AND `items_id`='".$networkequipments_id."'
                           LIMIT 1";
                  $result = $DB->query($query);
                  if ($DB->numrows($result) == '0') {
                     $input = array();
                     $input['plugin_monitoring_componentscalalog_id'] = $componentscalalog_id;
                     $input['is_static'] = '0';
                     $input['itemtype'] = "NetworkEquipment";
                     $input['items_id'] = $networkequipments_id;
                     $componentscatalogs_hosts_id = $pmComponentscatalog_Host->add($input);
                  } else {
                     $a_componentscatalogs_hosts = $DB->fetch_assoc($result);
                     $componentscatalogs_hosts_id = $a_componentscatalogs_hosts['id'];
                  }
                  // * Add service if not exist
                  $pmComponentscatalog_Host->linkComponentsToItem($componentscalalog_id, 
                                                                  $componentscatalogs_hosts_id,
                                                                  $networkports_id);
  
               }
            }
         }
      }
   }
}

?>
