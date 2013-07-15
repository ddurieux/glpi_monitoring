<?php

/*
   ------------------------------------------------------------------------
   Plugin Monitoring for GLPI
   Copyright (C) 2011-2013 by the Plugin Monitoring for GLPI Development Team.

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
   @copyright Copyright (c) 2011-2013 Plugin Monitoring for GLPI team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      https://forge.indepnet.net/projects/monitoring/
   @since     2013
 
   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginMonitoringTag extends CommonDBTM {
   
   
   /**
   * Get name of this type
   *
   *@return text name of this type by language of the user connected
   *
   **/
   static function getTypeName($nb=0) {
      return __('Tag', 'monitoring');
   }



   static function canCreate() {
      return PluginMonitoringProfile::haveRight("config", 'w');
   }

   
   
   static function canView() {
      return PluginMonitoringProfile::haveRight("config", 'r');
   }
   
   
   
   static function canDelete() {
      return FALSE;
   }
   

   
   function getSearchOptions() {
      $tab = array();
    
      $tab['common'] = __('Commands', 'monitoring');

		$tab[1]['table']     = $this->getTable();
		$tab[1]['field']     = 'tag';
		$tab[1]['linkfield'] = 'tag';
		$tab[1]['name']      = __('Tag', 'monitoring');
      $tab[1]['datatype']  = 'itemlink';

		$tab[2]['table']     = $this->getTable();
		$tab[2]['field']     = 'ip';
		$tab[2]['linkfield'] = 'ip';
		$tab[2]['name']      = __('Ip');

		$tab[3]['table']     = $this->getTable();
		$tab[3]['field']     = 'username';
		$tab[3]['linkfield'] = 'username';
		$tab[3]['name']      = __('Username (Shinken webservice)', 'monitoring');
   
      return $tab;
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
   function showForm($items_id, $options=array(), $copy=array()) {
      global $DB,$CFG_GLPI;

      if ($items_id!='') {
         $this->getFromDB($items_id);
      } else {
         $this->getEmpty();
      }
      
      $this->showTabs($options);
      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Tag', 'monitoring')." :</td>";
      echo "<td>";
      echo $this->fields["tag"];
      echo "</td>";
      echo "<td>".__('Username (Shinken webservice)', 'monitoring')."&nbsp;:</td>";
      echo "<td>";
      echo "<input type='text' name='username' value='".$this->fields["username"]."' size='30'/>";
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('IP address')." :</td>";
      echo "<td>";
      echo "<input type='text' name='ip' value='".$this->fields["ip"]."' size='30'/>";
      echo "</td>";
      echo "<td>".__('Password (Shinken webservice)', 'monitoring')."&nbsp;:</td>";
      echo "<td>";
      echo "<input type='text' name='password' value='".$this->fields["password"]."' size='30'/>";
      echo "</td>";
      echo "</tr>";      
      
      $this->showFormButtons($options);
      
      return true;
   }
   
   
   
   function setIP($tag, $ip) {
      
      $id = $this->getTagID($tag);
      $input= array();
      $input['id'] = $id;
      $input['ip'] = $ip;
      $this->update($input);      
   }
   
   
   
   function getIP($tag) {
      
      $a_tags = $this->find("`tag`='".$tag."'", '', 1);
      if (count($a_tags) == 1) {
         $a_tag = current($a_tags);
         return $a_tag['ip'];
      }
      return '';
   }
   
   
   
   function getAuth($tag) {
      
      $a_tags = $this->find("`tag`='".$tag."'", '', 1);
      if (count($a_tags) == 1) {
         $a_tag = current($a_tags);
         return $a_tag['username'].":".$a_tag['password'];
      }
      return '';
   }
   
   
   
   function getTagID($tag) {
      
      $a_tags = $this->find("`tag`='".$tag."'", '', 1);
      if (count($a_tags) == 1) {
         $a_tag = current($a_tags);
         return $a_tag['id'];
      }
      
      return $this->add(array('tag' => $tag));
   }
   
}

?>