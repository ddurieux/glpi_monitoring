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

class PluginMonitoringUser extends CommonDBTM {

   static $rightname = 'plugin_monitoring_componentscatalog';

   /**
   * Get name of this type
   *
   *@return text name of this type by language of the user connected
   *
   **/
   static function getTypeName($nb=0) {
      return __('Alignak backend user', 'monitoring');
   }



   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {

      $array_ret = array();
      if (($item->getID() > 0) && (PluginMonitoringUser::canView())) {
         $array_ret[0] = self::createTabEntry(
                 __('Alignak backend user', 'monitoring'));
      }
      return $array_ret;
   }



   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {

      if ($item->getID() > 0) {
         $pmUser = new PluginMonitoringUser();
         $pmUser->showForm(0);
      }
      return true;
   }


   /**
   * Display form for Alignak backend user configuration
   *
   * @param $items_id integer ID
   * @param $options array
   *
   *@return bool true if form is ok
   *
   **/
   function showForm($items_id, $options=array()) {

      if ($items_id == '0') {
         $a_list = $this->find("`users_id`='".$_GET['id']."'", '', 1);
         if (count($a_list)) {
            $array = current($a_list);
            $items_id = $array['id'];
         }
      }

      if ($items_id != '0') {
         $this->getFromDB($items_id);
      } else {
         $this->getEmpty();
      }

//      $this->initForm($items_id, $options);
      $this->showFormHeader($options);

      $this->getFromDB($items_id);

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Alignak backend login', 'monitoring')." :</td>";
      echo "<td>";
      Html::autocompletionTextField($this, 'backend_login', array('value' => $this->fields['backend_login']));
      echo Html::hidden('users_id', array('value' => $_GET['id']));
      echo "</td>";
      echo "<td>".__('Alignak backend password', 'monitoring')." :</td>";
      echo "<td>";
      echo "<input name='backend_password' type='password' value='".$this->fields['backend_password']."' />";
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<th colspan='4'>".__('OR', 'monitoring')."</th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Alignak backend token', 'monitoring')." :</td>";
      echo "<td>";
      Html::autocompletionTextField($this, 'backend_token', array('value' => $this->fields['backend_token']));
      echo "</td>";
      echo "<td colspan='2'>";
      echo "</td>";
      echo "</tr>";

      $this->showFormButtons($options);

      return true;
   }



   static function my_token(&$backend) {
      $pmUser = new self();
      $a_list = $pmUser->find("`users_id`='".$_SESSION['glpiID']."'", '', 1);
      if (count($a_list)) {
         $user = current($a_list);
         if (!empty($user['token'])) {
            $backend->token = $user['backend_token'];
         } else {
            $backend->login($user['backend_login'], $user['backend_password']);
         }
      }
   }

}

?>