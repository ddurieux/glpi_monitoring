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

class PluginMonitoringUnavaibilities extends CommonDBTM {
   private $currentstate = '';
   private $items_id = 0;
   private $itemtype = '';
   private $unavaibilities_id = 0;
   

   function getCurrentState($itemtype, $items_id) {
      $this->items_id = $items_id;
      $this->itemtype = $itemtype;
      
      $a_states = $this->find("`itemtype`='".$itemtype."'
         AND `items_id`='".$items_id."'", "`id` DESC", 1);
      if (count($a_states) > 0) {
         $a_state = current($a_states);
         if (is_null($a_state['end_date'])) {
            $this->currentstate = 'critical';
            $this->unavaibilities_id = $a_state['id'];
         } else {
            $this->currentstate = 'ok';
         }
      } else {
         $this->currentstate = 'ok';
      }
   }
   
   
   
   function checkState($stateevent, $date) {
      
      $state = PluginMonitoringDisplay::getState($stateevent, "HARD");
      
      if ($state == 'red') { // Critial
         if ($this->currentstate == 'ok') {
            // Add 
            $input = array();
            $input['items_id'] = $this->items_id;
            $input['itemtype'] = $this->itemtype;
            $input['begin_date'] = $date;
            $this->unavaibilities_id = $this->add($input);
            $this->currentstate = 'critical';
         }
      } else { // Ok
         if ($this->currentstate == 'critical') {
            // update
            $input = array();
            $input['id'] = $this->unavaibilities_id;
            $input['end_date'] = $date;
            $this->update($input);
            $this->unavaibilities_id = 0;
            $this->currentstate = 'ok';
         }
      }      
   }
   
   
   
   function parseEvents() {
      // Get last event have availability to 1
      
      
      
   }
   
}

?>