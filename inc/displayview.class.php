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
   @since     2012
 
   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginMonitoringDisplayview extends CommonDBTM {
   

   /**
   * Get name of this type
   *
   *@return text name of this type by language of the user connected
   *
   **/
   static function getTypeName() {
      global $LANG;

      return $LANG['plugin_monitoring']['displayview'][0];
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

   

   function getSearchOptions() {
      global $LANG;

      $tab = array();
    
      $tab['common'] = $LANG['plugin_monitoring']['displayview'][0];

		$tab[1]['table'] = $this->getTable();
		$tab[1]['field'] = 'name';
		$tab[1]['linkfield'] = 'name';
		$tab[1]['name'] = $LANG['common'][16];
		$tab[1]['datatype'] = 'itemlink';


      return $tab;
   }



   function defineTabs($options=array()){
      global $LANG,$CFG_GLPI;

      $ong = array();
      if ($this->fields['id'] > 0) {
         $ong[1] = 'items';
      }
      
      return $ong;
   }



   /**
   * Display form for agent configuration
   *
   * @param $items_id integer ID 
   * @param $options array
   *
   *@return bool true if form is ok
   *
   **/
   function showForm($items_id, $options=array()) {
      global $DB,$CFG_GLPI,$LANG;

      if ($items_id!='') {
         $this->getFromDB($items_id);
      } else {
         $this->getEmpty();
      }

      $this->showTabs($options);
      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['common'][16]." :</td>";
      echo "<td>";
      echo "<input type='text' name='name' value='".$this->fields["name"]."' size='30'/>";
      echo "</td>";

      echo "<td>".$LANG['common'][17]."&nbsp;:</td>";
      echo "<td>";
      $elements = array();
      $elements['public'] = $LANG['common'][76];
      $elements['private'] = $LANG['common'][77];
      
      $value = 'public';
      if ($this->fields["users_id"] > '0') {
         $value = 'private';
      }
      Dropdown::showFromArray('users_id', $elements, array('value'=>$value));
      echo "</td>";
      echo "</tr>";
      
      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_monitoring']['displayview'][1]."&nbsp;:</td>";
      echo "<td>";
      $elements = array();
      $elements['NULL'] = Dropdown::EMPTY_VALUE;
      $elements['Businessrules'] = $LANG['plugin_monitoring']['businessrule'][0];
      $elements['Componentscatalog'] = $LANG['plugin_monitoring']['componentscatalog'][0];
      $elements['Ressources'] = $LANG['plugin_monitoring']['service'][0];
      Dropdown::showFromArray('counter', $elements, array('value'=>$this->fields['counter']));
      echo "</td>";
      
      echo "<td>";
      echo $LANG['plugin_monitoring']['displayview'][2];
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("in_central", $this->fields['in_central']);
      echo "</td>";
      echo "</tr>";
      
      echo "<tr class='tab_bg_1'>";
      echo "<td colspan='2'>";
      echo "</td>";
      echo "<td>";
      echo $LANG['common'][60];
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("is_active", $this->fields['is_active']);
      echo "</td>";
      echo "</tr>";
      
      $this->showFormButtons($options);
      $this->addDivForTabs();

      return true;
   }
   
   
   
   function getViews($central='0') {
      global $DB;
      
      $wcentral = '';
      if ($central == '1') {
         $wcentral = " AND `in_central`='1' ";
      }
      
      $a_views = array();
      $query = "SELECT * FROM `glpi_plugin_monitoring_displayviews`      
                WHERE `is_active` = '1'
                  AND (`users_id`='0' OR `users_id`='".$_SESSION['glpiID']."')
                  ".$wcentral."
                  ".getEntitiesRestrictRequest(" AND", 'glpi_plugin_monitoring_displayviews', "entities_id",'', true)."
                ORDER BY `users_id`, `name`";
      $result = $DB->query($query);
      if ($DB->numrows($result)) {
         while ($data = $DB->fetch_array($result)) {
            $a_views[$data['id']] = $data['name'];
         }
      }
      return $a_views;
   }

}

?>