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
      return Session::haveRight('computer', 'w');
   }


   
   function canView() {
      return Session::haveRight('computer', 'r');
   }


   
   function canCancel() {
      return Session::haveRight('computer', 'w');
   }


   
   function canUndo() {
      return Session::haveRight('computer', 'w');
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
      global $DB;

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
      echo "<td width='350'>Interface to query IP (only if have many IPs)&nbsp;:</td>";
      echo "<td>";
      echo "<input type='hidden' name='itemtype' value='".$itemtype."'/>";
      echo "<input type='hidden' name='items_id' value='".$items_id."'/>";
      if ($this->fields['networkports_id'] == '') {
         $this->fields['networkports_id'] = 0;
      }
      $a_networkport = array();
      $a_networkport['0'] = Dropdown::EMPTY_VALUE;
      $query = "SELECT * FROM `".getTableForItemType("NetworkPort")."`
         WHERE `items_id`='".$items_id."' 
            AND `itemtype`='".$itemtype."'
            AND `ip` IS NOT NULL
            AND `ip` != '127.0.0.1'
            AND `ip` != ''
         ORDER BY `name`";
      $result = $DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         $a_networkport[$data['id']] = $data['name'];
      }      
      Dropdown::showFromArray("networkports_id", $a_networkport, array('value'=>$this->fields['networkports_id']));
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
         $class = new $itemtype();
         $class->getFromDB($items_id);
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