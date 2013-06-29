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

class PluginMonitoringComponentscatalog_Host extends CommonDBTM {
   

   static function getTypeName($nb=0) {
      return __('Hosts', 'monitoring');
   }


   static function canCreate() {
      return PluginMonitoringProfile::haveRight("componentscatalog", 'w');
   }


   
   static function canView() {
      return PluginMonitoringProfile::haveRight("componentscatalog", 'r');
   }

   
   
   function showHosts($componentscatalogs_id, $static) {
      global $DB,$CFG_GLPI;
      
      if ($static == '1') {
         $this->addHost($componentscatalogs_id);
      }
      
      $rand = mt_rand();

      $query = "SELECT * FROM `".$this->getTable()."`
         WHERE `plugin_monitoring_componentscalalog_id`='".$componentscatalogs_id."'
            AND `is_static`='".$static."'";
      $result = $DB->query($query);
      
      echo "<form method='post' name='componentscatalog_host_form$rand' id='componentscatalog_host_form$rand' action=\"".
                $CFG_GLPI["root_doc"]."/plugins/monitoring/front/componentscatalog_host.form.php\">";
      
      echo "<table class='tab_cadre_fixe'>";
      echo "<tr>";
      echo "<th colspan='5'>";
      if ($DB->numrows($result)==0) {
         echo __('No associated item');
      } else {
         echo __('Associated item');
      }
      echo "</th>";
      echo "</tr>";
      echo "</table>";
      
      
      echo "<table class='tab_cadre_fixe'>";     
      
      echo "<tr>";
      echo "<th width='10'>&nbsp;</th>";
      echo "<th>".__('Type')."</th>";
      echo "<th>".__('Entity')."</th>";
      echo "<th>".__('Name')."</th>";
      echo "<th>".__('Serial number')."</th>";
      echo "<th>".__('Inventory number')."</th>";
      echo "</tr>";
      
      while ($data=$DB->fetch_array($result)) {
         $itemtype = $data['itemtype'];
         $item = new $itemtype();
           
         $display_normal = 1;
         $networkports = false;
         if ($itemtype == 'NetworkEquipment') {
            $querys = "SELECT * FROM `glpi_plugin_monitoring_services`
               WHERE `plugin_monitoring_componentscatalogs_hosts_id`='".$data['id']."'
                  AND `networkports_id`='0'";
            $results = $DB->query($querys);
            if ($DB->numrows($results) == 0) {
               $display_normal = 0;
            }
            
            $querys = "SELECT * FROM `glpi_plugin_monitoring_services`
               WHERE `plugin_monitoring_componentscatalogs_hosts_id`='".$data['id']."'
                  AND `networkports_id`!='0'";
            $results = $DB->query($querys);
            if ($DB->numrows($results) > 0) {
               $networkports = true;
            }
         }
         $item->getFromDB($data['items_id']);
         if ($display_normal == 1) {
            echo "<tr>";
            echo "<td>";
            echo "<input type='checkbox' name='item[".$data["id"]."]' value='1'>";
            echo "</td>";
            echo "<td class='center'>";
            echo $item->getTypeName();
            echo "</td>";
            echo "<td class='center'>";
            echo Dropdown::getDropdownName("glpi_entities",$item->fields['entities_id'])."</td>";
            echo "<td class='center".
                  (isset($item->fields['is_deleted']) && $item->fields['is_deleted'] ? " tab_bg_2_2'" : "'");
            echo ">".$item->getLink()."</td>";
            echo "<td class='center'>".
                  (isset($item->fields["serial"])? "".$item->fields["serial"]."" :"-")."</td>";
            echo "<td class='center'>".
                  (isset($item->fields["otherserial"])? "".$item->fields["otherserial"]."" :"-")."</td>";

            echo "</tr>";
         }
         
         if ($networkports) {
            $itemport = new NetworkPort();
            while ($datas = $DB->fetch_array($results)) {
               $itemport->getFromDB($datas['networkports_id']);
               echo "<tr>";
               echo "<td>";
               echo "<input type='checkbox' name='item[".$data["id"]."]' value='1'>";
               echo "</td>";
               echo "<td class='center'>";
               echo $itemport->getTypeName();
               echo "</td>";
               echo "<td class='center'>";
               echo Dropdown::getDropdownName("glpi_entities",$item->fields['entities_id'])."</td>";
               echo "<td colspan='3' class='left".
                     (isset($item->fields['is_deleted']) && $item->fields['is_deleted'] ? " tab_bg_2_2'" : "'");
               echo ">".$itemport->getLink()." on ".$item->getLink(1)."</td>";
               echo "</tr>";
            }
         }
      }
      
      if ($static == '1') {
         Html::openArrowMassives("componentscatalog_host_form$rand", true);
         Html::closeArrowMassives(array('deleteitem' => _sx('button', 'Delete permanently')));
         Html::closeForm();
      }
      
      echo "</table>";
   }
   
   
   
   function addHost($componentscatalogs_id) {
      global $DB;
      
      $this->getEmpty();
      
      $this->showFormHeader();      
     
      echo "<tr>";
      echo "<td colspan='2'>";
      echo __('Add a new component', 'monitoring')."&nbsp;:";
      echo "<input type='hidden' name='plugin_monitoring_componentscalalog_id' value='".$componentscatalogs_id."'/>";
      echo "<input type='hidden' name='is_static' value='1'/>";
      echo "</td>";
      echo "<td colspan='2'>";
      Dropdown::showAllItems('items_id');
      echo "</td>";
      echo "</tr>";
      
      $this->showFormButtons();
   }
   
   
   
   function linkComponentsToItem($componentscatalogs_id, $componentscatalogs_hosts_id, $networkports_id=0) {
      global $DB;
      Toolbox::logInFile("NET", $networkports_id."\n");
      $pmService                 = new PluginMonitoringService();
      $pmComponentscatalog_Host  = new PluginMonitoringComponentscatalog_Host();
      
      $pmComponentscatalog_Host->getFromDB($componentscatalogs_hosts_id);
      
      $query = "SELECT * FROM `glpi_plugin_monitoring_componentscatalogs_components`
         WHERE `plugin_monitoring_componentscalalog_id`='".$componentscatalogs_id."'";
      $result = $DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         $input = array();         
         $itemtype = $pmComponentscatalog_Host->fields['itemtype'];
         $item = new $itemtype();
         $item->getFromDB($pmComponentscatalog_Host->fields['items_id']);
         if ($networkports_id == '0') {
            $input['entities_id'] =  $item->fields['entities_id'];
            $input['plugin_monitoring_componentscatalogs_hosts_id'] = $componentscatalogs_hosts_id;
            $input['plugin_monitoring_components_id'] = $data['plugin_monitoring_components_id'];
            $input['name'] = Dropdown::getDropdownName("glpi_plugin_monitoring_components", $data['plugin_monitoring_components_id']);
            $input['state'] = 'WARNING';
            $input['state_type'] = 'HARD';
            $pmService->add($input);
         } else if ($networkports_id > 0) {
            $a_services = $pmService->find("`plugin_monitoring_components_id`='".$data['plugin_monitoring_components_id']."'
               AND `plugin_monitoring_componentscatalogs_hosts_id`='".$componentscatalogs_hosts_id."'
               AND `networkports_id`='".$networkports_id."'", "", 1);
            $item = new NetworkPort();
            $item->getFromDB($networkports_id);
            if (count($a_services) == '0') {
               $input = array();
               $input['networkports_id'] = $networkports_id;
               $input['entities_id'] =  $item->fields['entities_id'];
               $input['plugin_monitoring_componentscatalogs_hosts_id'] = $componentscatalogs_hosts_id;
               $input['plugin_monitoring_components_id'] = $data['plugin_monitoring_components_id'];
               $input['name'] = Dropdown::getDropdownName("glpi_plugin_monitoring_components", $data['plugin_monitoring_components_id']);
               $input['state'] = 'WARNING';
               $input['state_type'] = 'HARD';
               $pmService->add($input);
            } else {
               $a_service = current($a_services);               
               $queryu = "UPDATE `glpi_plugin_monitoring_services`
                  SET `entities_id`='".$item->fields['entities_id']."'
                     WHERE `id`='".$a_service['id']."'";
               $DB->query($queryu);
            }
         }
      }      
   }
   
   
   
   static function unlinkComponentsToItem($parm) {
      global $DB;
      
      $pmService  = new PluginMonitoringService();
      
      $query = "SELECT * FROM `glpi_plugin_monitoring_services`
         WHERE `plugin_monitoring_componentscatalogs_hosts_id`='".$parm->fields['id']."'";
      $result = $DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         $_SESSION['plugin_monitoring_hosts'] = $parm->fields;
         $pmService->delete(array('id'=>$data['id']));
      }      
   }
   
   
   
   /**
    * Put in session informations for add in log what change in config
    * 
    * @return type 
    */
   function pre_deleteItem() {
      $_SESSION['plugin_monitoring_hosts'] = $this->fields;
      
      return true;
   }
}

?>