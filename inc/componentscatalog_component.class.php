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
      global $DB,$LANG;

      $this->addComponent($componentscatalogs_id);
      
      $pmComponent = new PluginMonitoringComponent();

      echo "<table class='tab_cadre_fixe'>";     

      echo "<tr>";
      echo "<th>";
      echo $LANG['plugin_monitoring']['component'][0];
      echo "</th>";
      echo "</tr>";
      
      $used = array();
      $query = "SELECT * FROM `".$this->getTable()."`
         WHERE `plugin_monitoring_componentscalalog_id`='".$componentscatalogs_id."'";
      $result = $DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         echo "<tr>";      
         echo "<td>";
         $used[] = $data['plugin_monitoring_components_id'];
         $pmComponent->getFromDB($data['plugin_monitoring_components_id']);
         echo $pmComponent->getLink(1);
         
         echo "</td>";
         echo "</tr>";
      }      
      
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
   
}

?>