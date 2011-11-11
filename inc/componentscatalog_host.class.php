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

class PluginMonitoringComponentscatalog_Host extends CommonDBTM {
   

   static function getTypeName() {
      global $LANG;

      return "Hosts";
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

   
   
   function showStatichosts($componentscatalogs_id) {
      global $DB;
      
      $this->addHost($componentscatalogs_id);      
      
      $query = "SELECT * FROM `".$this->getTable()."`
         WHERE `plugin_monitoring_componentscalalog_id`='".$componentscatalogs_id."'
            AND `is_static`='1'";
      $result = $DB->query($query);
      echo "<table class='tab_cadre_fixe'>";
      while ($data=$DB->fetch_array($result)) {
         $itemtype = $data['itemtype'];
         $item = new $itemtype();
         $item->getFromDB($data['items_id']);
         echo "<tr class='tab_bg_1'>";
         echo "<td></td>";
         echo "<td>";
         echo $item->getTypeName();
         echo "</td>";
         echo "<td>";
         echo $item->getLink(1);
         echo "</td>";
         echo "</tr>";
      }
      echo "</table>";
      
   }
   
   
   function addHost($componentscatalogs_id) {
      global $DB,$LANG;
      
      $this->getEmpty();
      
      $pmComponent = new PluginMonitoringComponent();

      $this->showFormHeader();      

      $used = array();
      $query = "SELECT * FROM `".$this->getTable()."`
         WHERE `plugin_monitoring_componentscalalog_id`='".$componentscatalogs_id."'";
      $result = $DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         
      }      
     
      echo "<tr>";
      echo "<td colspan='2'>";
      echo $LANG['plugin_monitoring']['component'][1]."&nbsp;:";
      echo "<input type='hidden' name='plugin_monitoring_componentscalalog_id' value='".$componentscatalogs_id."'/>";
      echo "<input type='hidden' name='static' value='1'/>";
      echo "</td>";
      echo "<td colspan='2'>";
      Dropdown::showAllItems('items_id');
      echo "</td>";
      echo "</tr>";
      
      $this->showFormButtons();
   }
   
   
}

?>