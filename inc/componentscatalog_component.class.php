<?php

/*
   ----------------------------------------------------------------------
   Monitoring plugin for GLPI
   Copyright (C) 2010-2011 by the GLPI plugin monitoring Team.

   https://forge.indepnet.net/projects/monitoring/
   ----------------------------------------------------------------------

   LICENSE

   This file is part of Monitoring plugin for GLPI.

   Monitoring plugin for GLPI is free software: you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation, either version 2 of the License, or
   any later version.

   Monitoring plugin for GLPI is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with Monitoring plugin for GLPI.  If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------
   Original Author of file: David DURIEUX
   Co-authors of file:
   Purpose of file:
   ----------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginMonitoringComponentscatalog_Component extends CommonDBTM {
   

   static function getTypeName() {
      global $LANG;

      return $LANG['plugin_monitoring']['component'][0];
   }


   function canCreate() {
      return true;
   }


   
   function canView() {
      return true;
   }


   
   function canCancel() {
      return true;
   }


   
   function canUndo() {
      return true;
   }


   
   function canValidate() {
      return true;
   }

   
   
   function showComponents($componentscatalogs_id) {
      global $DB,$LANG,$CFG_GLPI;

      $this->addComponent($componentscatalogs_id);
      
      $rand = mt_rand();
      
      $pmComponent = new PluginMonitoringComponent();
      $pmCommand   = new PluginMonitoringCommand();
      $pmCheck     = new PluginMonitoringCheck();
      $calendar    = new Calendar();
      
      echo "<form method='post' name='componentscatalog_component_form$rand' id='componentscatalog_component_form$rand' action=\"".
                $CFG_GLPI["root_doc"]."/plugins/monitoring/front/componentscatalog_component.form.php\">";
      
      echo "<table class='tab_cadre_fixe'>";

      echo "<tr>";
      echo "<th>";
      echo $LANG['plugin_monitoring']['component'][2];
      echo "</th>";
      echo "</tr>";
      
      echo "</table>";
      
      echo "<table class='tab_cadre_fixe'>";
      
      echo "<tr>";
      echo "<th width='10'>&nbsp;</th>";
      echo "<th>".$LANG['common'][16]."</th>";
      echo "<th>".$LANG['plugin_monitoring']['command'][2]."</th>";
      echo "<th>".$LANG['plugin_monitoring']['check'][0]."</th>";      
      echo "<th>".$LANG['plugin_monitoring']['host'][9]."</th>";
      echo "<th>".$LANG['plugin_monitoring']['service'][8]."</th>";
      echo "</tr>";
      
      $used = array();
      $query = "SELECT * FROM `".$this->getTable()."`
         WHERE `plugin_monitoring_componentscalalog_id`='".$componentscatalogs_id."'";
      $result = $DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         $used[] = $data['plugin_monitoring_components_id'];
         $pmComponent->getFromDB($data['plugin_monitoring_components_id']);
         echo "<tr>";
         echo "<td>";
         echo "<input type='checkbox' name='item[".$data["id"]."]' value='1'>";
         echo "</td>";
         echo "<td class='center'>";
         echo $pmComponent->getLink(1);         
         echo "</td>";
         echo "<td class='center'>";
         $pmCommand->getFromDB($pmComponent->fields['plugin_monitoring_commands_id']);
         echo $pmCommand->getLink();
         echo "</td>";
         echo "<td class='center'>";
         $pmCheck->getFromDB($pmComponent->fields['plugin_monitoring_checks_id']);
         echo $pmCheck->getLink();
         echo "</td>";
         echo "<td class='center'>";
         $calendar->getFromDB($pmComponent->fields['calendars_id']);
         echo $calendar->getLink();
         echo "</td>";
         echo "<td class='center'>";
         if ($pmComponent->fields['remotesystem'] == '') {
            echo "-";
         } else {
            echo $pmComponent->fields['remotesystem'];
         }         
         echo "</td>";
         
         echo "</tr>";
      }
      
      openArrowMassive("componentscatalog_host_form$rand", true);
      closeArrowMassive('deleteitem', $LANG['buttons'][6]);
      
      echo "</table>";
      
   }
   
   
   function addComponent($componentscatalogs_id) {
      global $DB,$LANG;
      
      $this->getEmpty();
      
      $pmComponent = new PluginMonitoringComponent();

      $this->showFormHeader();      

      $used = array();
      $query = "SELECT * FROM `".$this->getTable()."`
         WHERE `plugin_monitoring_componentscalalog_id`='".$componentscatalogs_id."'";
      $result = $DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         $used[] = $data['plugin_monitoring_components_id'];
      }      
     
      echo "<tr>";
      echo "<td colspan='2'>";
      echo $LANG['plugin_monitoring']['component'][1]."&nbsp;:";
      echo "<input type='hidden' name='plugin_monitoring_componentscalalog_id' value='".$componentscatalogs_id."'/>";
      echo "</td>";
      echo "<td colspan='2'>";
      Dropdown::show("PluginMonitoringComponent", array('name'=>'plugin_monitoring_components_id',
                                                        'used'=>$used));
      echo "</td>";
      echo "</tr>";
      
      $this->showFormButtons();
   }
   
   
   
   function addComponentToItems($componentscatalogs_id, $components_id) {
      global $DB;
      
      $pmService = new PluginMonitoringService();
      
      $query = "SELECT * FROM `glpi_plugin_monitoring_componentscatalogs_hosts`
         WHERE `plugin_monitoring_componentscalalog_id`='".$componentscatalogs_id."'";
      $result = $DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         $input = array();
         $input['plugin_monitoring_componentscatalogs_hosts_id'] = $data['id'];
         $input['plugin_monitoring_components_id'] = $components_id;
         $input['name'] = Dropdown::getDropdownName("glpi_plugin_monitoring_components", $components_id);
         $pmService->add($input);         
      }
   }
   
   
   function removeComponentToItems($componentscatalogs_id, $components_id) {
      global $DB;
      
      $pmService = new PluginMonitoringService();
      
      $query = "SELECT * FROM `glpi_plugin_monitoring_componentscatalogs_hosts`
         WHERE `plugin_monitoring_componentscalalog_id`='".$componentscatalogs_id."'";
      $result = $DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         $querys = "SELECT * FROM `glpi_plugin_monitoring_services`
            WHERE `plugin_monitoring_componentscatalogs_hosts_id`='".$data['id']."'
               AND `plugin_monitoring_components_id`='".$components_id."'";
         $results = $DB->query($querys);
         while ($datas=$DB->fetch_array($results)) {
            $pmService->delete(array('id'=>$datas['id']));
         } 
      }
   }
   
}

?>