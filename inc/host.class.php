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

class PluginMonitoringHost extends CommonDBTM {

   

   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {

      $array_ret = array();
      if ($item->getID() > 0) {
         $array_ret[0] = self::createTabEntry(
                 __('Monitoring', 'monitoring')."-".__('Resources', 'monitoring'));
      }
      return $array_ret;
   }



   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {

      if ($item->getID() > 0) {
         PluginMonitoringServicegraph::loadLib();
         $pmService = new PluginMonitoringService();
         $pmService->manageServices(get_class($item), $item->fields['id']);
         $pmHostconfig = new PluginMonitoringHostconfig();
         $pmHostconfig->showForm($item->getID(), get_class($item));
      }
      return true;
   }
   
   
   
   function verifyHosts() {
      global $DB;
      
      $query = "SELECT * FROM `glpi_plugin_monitoring_componentscatalogs_hosts`
         GROUP BY `itemtype`, `items_id`";
      $result = $DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         $queryH = "SELECT * FROM `".$this->getTable()."`
            WHERE `itemtype`='".$data['itemtype']."'
              AND `items_id`='".$data['items_id']."'
            LIMIT 1";
         $resultH = $DB->query($queryH);
         if ($DB->numrows($resultH) == '0') {
            $input = array();
            $input['itemtype'] = $data['itemtype'];
            $input['items_id'] = $data['items_id'];
            $this->add($input);
         }
      }      
   }
   
   
   /**
    * If host not exist add it 
    * 
    * 
    */
   static function addHost($item) {
      global $DB;
      
      $pmHost = new self();
      
      $query = "SELECT * FROM `".$pmHost->getTable()."`
         WHERE `itemtype`='".$item->fields['itemtype']."'
           AND `items_id`='".$item->fields['items_id']."'
         LIMIT 1";
      $result = $DB->query($query);
      if ($DB->numrows($result) == '0') {
         $input = array();
         $input['itemtype'] = $item->fields['itemtype'];
         $input['items_id'] = $item->fields['items_id'];
         $pmHost->add($input);
      }      
   }
   
   
   
   function updateDependencies($itemtype, $items_id, $parent) {
      global $DB;
      
      $query = "UPDATE `glpi_plugin_monitoring_hosts`
         SET `dependencies`='".$parent."'
         WHERE `itemtype`='".$itemtype."'
           AND `items_id`='".$items_id."'";
      $DB->query($query);
   }
}

?>