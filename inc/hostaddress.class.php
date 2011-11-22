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

class PluginMonitoringHostaddress extends CommonDBTM {
   public $table = "glpi_plugin_monitoring_hostaddresses";
   

   /**
   * Get name of this type
   *
   *@return text name of this type by language of the user connected
   *
   **/
   static function getTypeName() {
      global $LANG;

      return "Host address";
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

   
   /**
   * 
   *
   * @param $items_id integer ID 
   * @param $options array
   *
   *@return bool true if form is ok
   *
   **/
   function showForm($items_id, $itemtype, $options=array()) {
      global $DB,$CFG_GLPI,$LANG;

      $query = "SELECT * FROM `".$this->getTable()."`
         WHERE `items_id`='".$items_id."'
            AND `itemtype`='".$itemtype."'
         LIMIT 1";
      
      $result = $DB->query($query);
      if ($DB->numrows($result) == '0') {
         $this->getEmpty();
      } else {
         $data = $DB->fetch_assoc($result);
         $this->getFromDB($data['id']);
      }

      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
      echo "<td>Interface to query IP (only if have many IPs)&nbsp;:</td>";
      echo "<td align='center'>";
      echo "<input type='hidden' name='itemtype' value='".$itemtype."'/>";
      echo "<input type='hidden' name='items_id' value='".$items_id."'/>";
      Dropdown::show("Networkport", array('name' =>'networkports_id',
                                          'value'=>$this->fields['networkports_id'],
                                          'condition'=>"`items_id`='".$items_id."' 
                                             AND `itemtype`='".$itemtype."'
                                             AND `ip` IS NOT NULL
                                             AND `ip` != '127.0.0.1'"));
      echo "</td>";
      echo "<td colspan='2'>";
      echo "</td>";
      echo "</tr>";
      
      $this->showFormButtons($options);

      return true;
   }
   
   
   static function getIp($items_id, $itemtype, $hostname) {
      global $DB;
      
      $networkPort = new NetworkPort();
      $pmHostaddress = new PluginMonitoringHostaddress();
      
      $ip = $hostname;
      if ($itemtype == 'NetworkEquipment') {
         if ($class->fields['ip'] != '') {
            $ip = $class->fields['ip'];
         }
      } else {
         $query = "SELECT * FROM `".$pmHostaddress->getTable()."`
         WHERE `items_id`='".$items_id."'
            AND `itemtype`='".$itemtype."'
         LIMIT 1";      
         $result = $DB->query($query);
         if ($DB->numrows($result) == '1') {
            $data = $DB->fetch_assoc($result);
            $pmHostaddress->getFromDB($data['id']);
            $networkPort->getFromDB($pmHostaddress->fields['networkports_id']);
            $ip = $networkPort->fields['ip'];
         } else {
            $a_listnetwork = $networkPort->find("`itemtype`='".$itemtype."'
               AND `items_id`='".$items_id."'", "`id`");
            foreach ($a_listnetwork as $datanetwork) {
               if ($datanetwork['ip'] != '' 
                       AND $datanetwork['ip'] != '127.0.0.1'
                       AND $ip != '') {
                  $ip = $datanetwork['ip'];
                  break;
               }
            }
         }
      }
      return $ip;
   }
}

?>