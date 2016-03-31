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
   @since     2015

   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

/**
 * This class is used to configure if user with profil 'helpdesk' is
 * redirected to monitoring dashboard when connect / go on homepage
 */
class PluginMonitoringRedirecthome extends CommonDBTM {

   static $rightname = 'search_config';

   /**
   * Get name of this type
   *
   *@return text name of this type by language of the user connected
   *
   **/
   static function getTypeName($nb=0) {
      return __('Monitoring redirect', 'monitoring');
   }



   /**
    * Display tab
    *
    * @param CommonGLPI $item
    * @param integer $withtemplate
    *
    * @return varchar name of the tab(s) to display
    */
   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {

      $profile = new Profile();
      $array_ret = array();
      $profiles = Profile_User::getUserProfiles($item->getID());
      foreach ($profiles as $profiles_id) {
         $profile->getFromDB($profiles_id);
         if ($profile->fields['interface'] == 'helpdesk') {
            $array_ret[] = self::createTabEntry(__('Redirect home (monitoring)', 'monitoring'));
            return $array_ret;
         }
      }
      return $array_ret;
   }



   /**
    * Display content of tab
    *
    * @param CommonGLPI $item
    * @param integer $tabnum
    * @param interger $withtemplate
    *
    * @return boolean true
    */
   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {
      $rh = new PluginMonitoringRedirecthome();
      $rh->showForm($item->getID());
   }



   function showForm($users_id=0, $options=array()) {

      if ($users_id != 0) {
         $a_list = $this->find("`users_id`='".$_GET['id']."'", '', 1);
         if (count($a_list)) {
            $array = current($a_list);
            $this->getFromDB($array['id']);
         } else {
            $this->getEmpty();
            $this->fields['users_id'] = $users_id;
         }
      } else {
         return true;
      }

      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Redirect home page to monitoring page when login')."&nbsp;:</td>";
      echo "<td align='center'>";
      echo "<input type='hidden' name='users_id' value='".$this->fields['users_id']."'/>";
      Dropdown::showYesNo('is_redirected', $this->fields['is_redirected']);
      echo "</td>";
      echo "<td colspan='2'>";
      echo "</td>";
      echo "</tr>";

      $this->showFormButtons(array('candel'=>false));

      return true;
   }



   /**
    * Check if this user must be redirected
    *
    * @param integer $users_id
    * @return boolean
    */
   function is_redirect($users_id) {
      $a_list = $this->find("`users_id`='".$users_id."'", '', 1);
      if (count($a_list)) {
         $array = current($a_list);
         return $array['is_redirected'];
      }
      return False;
   }
}

?>