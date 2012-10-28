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

class PluginMonitoringComponentscatalog_rule extends CommonDBTM {
   

   /**
   * Get name of this type
   *
   *@return text name of this type by language of the user connected
   *
   **/
   static function getTypeName($nb=0) {
      return $LANG['rulesengine'][17];
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
      echo "<th>";
      echo $LANG['rulesengine'][17];
      echo "</th>";
      echo "</tr>";
      echo "</table>";

      echo "<table class='tab_cadre_fixe'>";     
      
      echo "<tr>";
      echo "<th width='10'>&nbsp;</th>";
      echo "<th>".$LANG['common'][17]."</th>";
      echo "<th>".$LANG['common'][16]."</th>";
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
      
      $a_usedItemtypes = array();
      $query = "SELECT * FROM `".$this->getTable()."`
         WHERE `plugin_monitoring_componentscalalog_id`='".$componentscatalogs_id."'";
      $result = $DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         $a_usedItemtypes[$data['itemtype']] = $data['itemtype'];
      }
      
      if (count($a_usedItemtypes) == count($CFG_GLPI['networkport_types'])) {
         return;
      }
      
      $this->getEmpty();
      
      $this->showFormHeader();
      
      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo $LANG['common'][16]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      echo "<input type='text' name='name' value=''/>";
      echo "</td>";
      echo "<td>";
      echo $LANG['state'][6]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Dropdown::showItemTypes("itemtypen",
                              $CFG_GLPI['networkport_types'],
                              $a_usedItemtypes);
      echo "<input type='hidden' name='plugin_monitoring_componentscalalog_id' value='".$componentscatalogs_id."' >";
      echo "</td>";
      echo "</tr>";
      
      $this->showFormButtons();
     
   }
   
   
   
   function addRule() {
      
//      $param = array();
//      if (isset($_SESSION['plugin_monitoring_rules'])) {
//         $param = $_SESSION['plugin_monitoring_rules'];
//      }
//      if (isset($_GET)) {
//         unset($_GET);
//      }
//      $_GET = $_POST;
//      if (isset($_SESSION['plugin_monitoring_rules_REQUEST_URI'])) {
//         $_SERVER['REQUEST_URI'] = $_SESSION['plugin_monitoring_rules_REQUEST_URI'];
//      }
      
      Search::manageGetValues($_GET['itemtype']);
      $this->showGenericSearch($_GET['itemtype'], $_GET);
            
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
     
      if (!isset($_SESSION['glpiactive_entity'])) {
         $default_entity = 0;
      } else {
         $default_entity = $_SESSION['glpiactive_entity'];
      }
      $entities_isrecursive = 0;
      if (isset($_SESSION['glpiactiveentities'])
              AND count($_SESSION['glpiactiveentities']) > 1) {
         $entities_isrecursive = 1;
      }
      
      Session::changeActiveEntities($pmComponentscatalog->fields['entities_id'], 
                           $pmComponentscatalog->fields['is_recursive']);
      
      $array_return = Search::constructSQL($_GET['itemtype'], $_GET);
      Search::showList($_GET['itemtype'], $_GET, $array_return);

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
      
      if ($pmCc_Rule->getFromDB($parm->fields['id'])) {
      
         // Load right entity
            $pmComponentscatalog->getFromDB($pmCc_Rule->fields['plugin_monitoring_componentscalalog_id']);
            if (!isset($_SESSION['glpiactive_entity'])) {
               $default_entity = 0;
            } else {
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

         $devices_present = array();
         $queryd = "SELECT * FROM `glpi_plugin_monitoring_componentscatalogs_hosts`
            WHERE `plugin_monitoring_componentscalalog_id`='".$pmCc_Rule->fields["plugin_monitoring_componentscalalog_id"]."'
               AND `itemtype`='".$pmCc_Rule->fields['itemtype']."'
               AND `is_static`='0'";
         $result = $DB->query($queryd);
         while ($data=$DB->fetch_array($result)) {
            $devices_present[$data['id']] = 1;
         }
         $glpilist_limit = $_SESSION['glpilist_limit'];
         $_SESSION['glpilist_limit'] = 500000;
         $result = $pmCc_Rule->constructSQL($itemtype, 
                                        $_GET);
         $_SESSION['glpilist_limit'] = $glpilist_limit;

         while ($data=$DB->fetch_array($result)) {
            $queryh = "SELECT * FROM `glpi_plugin_monitoring_componentscatalogs_hosts`
               WHERE `plugin_monitoring_componentscalalog_id`='".$pmCc_Rule->fields["plugin_monitoring_componentscalalog_id"]."'
                  AND `itemtype`='".$pmCc_Rule->fields['itemtype']."'
                  AND `items_id`='".$data['id']."'
                     LIMIT 1";
            $resulth = $DB->query($queryh);
            if ($DB->numrows($resulth) == '0') {
               $input = array();
               $input['plugin_monitoring_componentscalalog_id'] = $pmCc_Rule->fields["plugin_monitoring_componentscalalog_id"];
               $input['is_static'] = '0';
               $input['items_id'] = $data['id'];
               $input['itemtype'] = $pmCc_Rule->fields['itemtype'];
               $componentscatalogs_hosts_id = $pmComponentscatalog_Host->add($input);
               $pmComponentscatalog_Host->linkComponentsToItem($pmCc_Rule->fields["plugin_monitoring_componentscalalog_id"], 
                                                               $componentscatalogs_hosts_id);
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
         
         // Reload current entity
            Session::changeActiveEntities($default_entity,
                                 $entities_isrecursive);
      } else { // Purge
         $devices_present = array();
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
         
         Search::manageGetValues($data['itemtype']);

         $array_return = Search::constructSQL($itemtype, $_GET);
         $result = $array_return['result'];
//         $resultr = $pmComponentscatalog_rule->constructSQL($itemtype, 
//                                        $_GET,
//                                        $items_id);
         if ($DB->numrows($resultr) > 0) {
            $a_find[$data['plugin_monitoring_componentscalalog_id']] = 1;
         } else {
            if (!isset($a_find[$data['plugin_monitoring_componentscalalog_id']])) {
               $a_find[$data['plugin_monitoring_componentscalalog_id']] = 0;
            }
         }
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
   }
   
   
   
   /*
    * Cloned Core function to display with our require.
    */
   function showGenericSearch($itemtype, $params) {
      global $CFG_GLPI;
     
      if (!isset($_GET['id'])) {
         $this->getEmpty();
      } else {
         $this->getFromDB($_GET['id']);
      }
      
      echo "<form name='searchform$itemtype' method='get' action=\"".
              $CFG_GLPI['root_doc']."/plugins/monitoring/front/componentscatalog_rule.form.php\">";

      $this->showFormHeader();
      
      echo "<tr>";
      echo "<td colspan='4'>";
      $_GET['noformheader'] = true;
      $_GET['noformfooter'] = true;
      $_GET['nodustbinbutton'] = true;
      $_GET['nobookmarkbutton'] = true;
      Search::showGenericSearch($_GET['itemtype'], $_GET);
      echo "</td>";
      echo "</tr>";

      echo "<tr>"; 
      if (isset($_GET['id'])) {
         echo "<td colspan='2' class='center'>";
         echo "<input type='hidden' name='plugin_monitoring_componentscalalog_id' value='".$_GET['plugin_monitoring_componentscalalog_id']."' >";
         echo "<input type='hidden' name='id' value='".$_GET['id']."' >";
         echo "<input type='submit' name='updaterule' value=\"Update this rule\" class='submit' >";
         echo "</td>";
         echo "<td colspan='2' class='center'>";
         echo "<input type='submit' name='deleterule' value=\"Delete this rule\" class='submit' >";

      } else {
         echo "<td colspan='4' class='center'>";
         echo "<input type='hidden' name='plugin_monitoring_componentscalalog_id' value='".$_GET['plugin_monitoring_componentscalalog_id']."' >";
         echo "<input type='submit' name='addrule' value=\"Add this rule\" class='submit' >";
      }
      echo "</td>";
      echo "</tr>";
      echo "</table>\n";
      
      echo "</form>";
   }
}

?>