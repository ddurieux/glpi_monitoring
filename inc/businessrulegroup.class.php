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

class PluginMonitoringBusinessrulegroup extends CommonDBTM {
   
   
   static function getTypeName($nb=0) {
      global $LANG;

      return $LANG['plugin_monitoring']['businessrule'][11];
   }
   

   function canCreate() {
      return haveRight('computer', 'w');
   }


   
   function canView() {
      return haveRight('computer', 'r');
   }


   
   function canCancel() {
      return haveRight('computer', 'w');
   }


   
   function canUndo() {
      return haveRight('computer', 'w');
   }


   
   function canValidate() {
      return true;
   }

   
   function showForm($items_id, $servicescatalogs_id, $options=array()) {
      global $LANG;

      if ($items_id!='') {
         $this->getFromDB($items_id);
      } else {
         $this->getEmpty();
      }

      $this->showFormHeader($options);
      
      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo "<input type='hidden' name='plugin_monitoring_servicescatalogs_id' value='".$servicescatalogs_id."'/>";
      echo $LANG['common'][16]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      echo "<input type='text' name='name' value='".$this->fields["name"]."' size='30'/>";
      echo "</td>";
      echo "<td>";
      echo $LANG['rulesengine'][9]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      $first_operator = array();
      $first_operator['or'] = "or";
      $first_operator['2 of:'] = $LANG['plugin_monitoring']['businessrule'][2];
      $first_operator['3 of:'] = $LANG['plugin_monitoring']['businessrule'][3];
      $first_operator['4 of:'] = $LANG['plugin_monitoring']['businessrule'][4];
      $first_operator['5 of:'] = $LANG['plugin_monitoring']['businessrule'][5];
      $first_operator['6 of:'] = $LANG['plugin_monitoring']['businessrule'][6];
      $first_operator['7 of:'] = $LANG['plugin_monitoring']['businessrule'][7];
      $first_operator['8 of:'] = $LANG['plugin_monitoring']['businessrule'][8];
      $first_operator['9 of:'] = $LANG['plugin_monitoring']['businessrule'][9];
      $first_operator['10 of:'] = $LANG['plugin_monitoring']['businessrule'][10];
      Dropdown::showFromArray('operator', $first_operator, array("value"=>$this->fields['operator']));
      echo "</td>";
      
      echo "</tr>";      

      $this->showFormButtons($options);

      return true;
   }
}

?>