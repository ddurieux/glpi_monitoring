<?php

/*
   ------------------------------------------------------------------------
   Plugin Monitoring for GLPI
   Copyright (C) 2011-2016 by the Plugin Monitoring for GLPI Development Team.

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
   @copyright Copyright (c) 2011-2016 Plugin Monitoring for GLPI team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      https://forge.indepnet.net/projects/monitoring/
   @since     2011

   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginMonitoringShinkenState extends CommonDBTM {

   static $rightname = 'config';

   /**
   * Get name of this type
   *
   *@return text name of this type by language of the user connected
   *
   **/
   static function getTypeName($nb=0) {
      return __('Shinken host/services state', 'monitoring');
   }


   static function getTable() {
      return 'glpi_plugin_monitoring_shinkenstates';
   }
   
   
   function getSearchOptions() {
      $tab = array();
      $i = 1;

      $tab['common'] = self::getTypeName();

      $i=1;
      $tab[$i]['table']           = $this->getTable();
      $tab[$i]['field']           = 'id';
      $tab[$i]['linkfield']       = 'id';
      $tab[$i]['name']            = __('ID', 'monitoring');
      // $tab[$i]['datatype']        = 'itemlink';

      $i++;
      $tab[$i]['table']           = $this->getTable();
      $tab[$i]['field']           = 'hostname';
      $tab[$i]['name']            = __('Hostname', 'monitoring');
      $tab[$i]['massiveaction']   = false;

      $i++;
      $tab[$i]['table']           = $this->getTable();
      $tab[$i]['field']           = 'service';
      $tab[$i]['name']            = __('Service', 'monitoring');
      $tab[$i]['massiveaction']   = false;

      $i++;
      $tab[$i]['table']           = $this->getTable();
      $tab[$i]['field']           = 'state';
      $tab[$i]['name']            = __('Service state', 'monitoring');
      $tab[$i]['massiveaction']   = false;

      $i++;
      $tab[$i]['table']           = $this->getTable();
      $tab[$i]['field']           = 'state_type';
      $tab[$i]['name']            = __('Service state type', 'monitoring');
      $tab[$i]['massiveaction']   = false;

      $i++;
      $tab[$i]['table']           = $this->getTable();
      $tab[$i]['field']           = 'last_check';
      $tab[$i]['name']            = __('Last check', 'monitoring');
      $tab[$i]['datatype']        = 'datetime';
      $tab[$i]['massiveaction']   = false;

      $i++;
      $tab[$i]['table']           = $this->getTable();
      $tab[$i]['field']           = 'last_output';
      $tab[$i]['name']            = __('Last check output', 'monitoring');
      $tab[$i]['massiveaction']   = false;

      $i++;
      $tab[$i]['table']           = $this->getTable();
      $tab[$i]['field']           = 'last_perfdata';
      $tab[$i]['name']            = __('Last perfdata output', 'monitoring');
      $tab[$i]['massiveaction']   = false;

      $i++;
      $tab[$i]['table']           = $this->getTable();
      $tab[$i]['field']           = 'is_ack';
      $tab[$i]['name']            = __('Acknowledged', 'monitoring');
      $tab[$i]['datatype']        = 'boolean';
      $tab[$i]['massiveaction']   = false;
      
      return $tab;
   }


   static function getSpecificValueToDisplay($field, $values, array $options=array()) {

      if (!is_array($values)) {
         $values = array($field => $values);
      }

      switch ($field) {
      case 'state':
         return PluginMonitoringShinkenState::getState($values[$field]);
         break;
      case 'is_ack':
         return ($values[$field] == 0) ? " " : __('Acknowledged', 'monitoring');
         break;
      }
      return parent::getSpecificValueToDisplay($field, $values, $options);
   }


   /**
    * Get service state
    *
    * Return :
    * - OK if service is OK
    * - CRITICAL if service is CRITICAL
    * - WARNING if service is WARNING, RECOVERY or FLAPPING
    * - UNKNOWN else
    */
   static function getState($state) {
      $returned_state = '~';
      
      switch($state) {
         case 0:
            $returned_state = 'OK';
            break;

         case 1:
            $returned_state = 'WARNING';
            break;

         case 2:
            $returned_state = 'CRITICAL';
            break;

         case 3:
            $returned_state = 'UNKNOWN';
            break;
      }

      return $returned_state;
   }

}

?>