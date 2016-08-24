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
      global $CFG_GLPI;

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
      echo "<th colspan='4'>";
      echo __('Link glpi user with alignak backend existing user', 'monitoring');
      echo "</th>";
      echo "</tr>";

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


      echo "<form name='form' method='post' action='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/user.form.php'>";
      echo "<table class='tab_cadre_fixe'>";
      echo "<tr class='tab_bg_1'>";
      echo "<th colspan='4'>";
      echo __('Create alignak backend account and link to this user', 'monitoring');
      echo "</th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td colspan='4' align='center'>";
      echo "<input type='hidden' name='users_id' value='".$_GET['id']."' />";
      echo "<input type='submit' name='import' value=\"".__('Create alignak account', 'monitoring')."\" class='submit'>";
      echo "</td>";
      echo "</tr>";

      echo "</table>";
      Html::closeForm();
      return true;
   }


   /**
   * Get an authentication token for Alignak backend or WebUI
   *
   * If Alignak token is in the PHP session, return this token and do not try to log in the backend.
   *
   * If Alignak token is not in the session:
   * 1/ If $backend parameter is provided, log in to the Alignak backend (with current user
   * configured username/password or token) and set the backend token in the PHP session.
   *
   * 2/ If $backend parameter is null, returns the current user token if it exists else returns an
   * empty token.
   *
   * Return the token to the function caller. Alignak backend or WebUI are not accessible.
   *
   * @param $backend Alignak backend client class object
   *
   * @return computed token
   *
   **/
   static function myToken(&$backend=null) {
      if (isset($_SESSION['alignak_backend_token'])) {
         PluginMonitoringToolbox::logIfExtradebug(
            "Use session stored token: ". $_SESSION['alignak_backend_token'] . "\n"
         );
         $backend->token = $_SESSION['alignak_backend_token'];
         return $_SESSION['alignak_backend_token'];
      }

      $token = '';

      $pmUser = new self();
      $a_list = $pmUser->find("`users_id`='".$_SESSION['glpiID']."'", '', 1);
      if (count($a_list)) {
         $user = current($a_list);
         if ($backend) {
            if (!empty($user['token'])) {
               $backend->token = $user['backend_token'];
            } else {
               $backend->login($user['backend_login'], $user['backend_password']);
            }
            $token = $backend->token;
         } else {
            if (!empty($user['token'])) {
               $token = $user['backend_token'];
            }
         }
         if (! isset($_SESSION['alignak_backend_token'])) {
            $_SESSION['alignak_backend_token'] = $token;
            PluginMonitoringToolbox::logIfExtradebug(
               "Store Alignak backend token in the current session: ". $_SESSION['alignak_backend_token'] . "\n"
            );
         }
      }
      return($token);
   }



   /**
    * Create an account of GLPI account in alignak backend
    *
    * @param integer $users_id
    */
   function createInBackend($users_id) {
      global $PM_CONFIG;

      $user = new User();
      $user->getFromDB($users_id);

      $pm_user_data = array();
      $a_list = $this->find("`users_id`='".$users_id."'", '', 1);
      if (count($a_list)) {
         $pm_user_data = current($a_list);
      }

      if (!empty($pm_user_data['backend_login'])) {
         // Yet associated to as backend account
         return TRUE;
      }

      $abc = new Alignak_Backend_Client($PM_CONFIG['alignak_backend_url']);
      PluginMonitoringUser::myToken($abc);

      $data = array(
          'name'     => $user->fields['name'],
          'email'    => '',
          '_realm'   => 'xxx',
          'password' => $this->randomPassword()
      );

      try {
         // Get default timeperiod
         $timeperiods = $abc->get('timeperiod', array('name' => '24x7'));
         $timeperiod = $timeperiods['_items'][0];
         $data['_realm'] = $timeperiod['_realm'];
         $data['service_notification_period'] = $timeperiod['_id'];
         $data['host_notification_period'] = $timeperiod['_id'];
         // Add user
         $backend_id = $abc->post("user/", $data);
      } catch (Exception $e) {
         
          echo 'Caught exception: ',  $e->getMessage(), "\n";
          return FALSE;
      }
      $input = array(
          'backend_login'    => $user->fields['name'],
          'backend_password' => $data['password']
      );
      if (count($pm_user_data)) {
         $input['id'] = $pm_user_data['id'];
         $this->update($input);
      } else {
         $input['users_id'] = $users_id;
         $this->add($input);
      }
      return TRUE;
   }



   /**
    * Generate a random password
    *
    * @return string
    */
   function randomPassword() {
      $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
      $pass = array(); //remember to declare $pass as an array
      $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
      for ($i = 0; $i < 8; $i++) {
         $n = rand(0, $alphaLength);
         $pass[] = $alphabet[$n];
      }
      return implode($pass); //turn the array into a string
   }



   /**
    * Display form related to the massive action selected
    *
    * @param object $ma MassiveAction instance
    * @return boolean
    */
   static function showMassiveActionsSubForm(MassiveAction $ma) {
      if ($ma->getAction() == 'createalignakuser') {
         echo Html::submit(__('Create accounts', 'monitoring'),
                                      array('name' => 'massiveaction'));
         return TRUE;
      }
      return parent::showMassiveActionsSubForm($ma);
   }



   /**
    * Execution code for massive action
    *
    * @param object $ma MassiveAction instance
    * @param object $item item on which execute the code
    * @param array $ids list of ID on which execute the code
    */
   static function processMassiveActionsForOneItemtype(MassiveAction $ma, CommonDBTM $item,
                                                       array $ids) {

      $pfAgent = new self();

      switch ($ma->getAction()) {

         case 'createalignakuser' :
            $pmUser = new PluginMonitoringUser();
            foreach ($ids as $key) {
               if ($pmUser->createInBackend($key)) {
                  $ma->itemDone($item->getType(), $key, MassiveAction::ACTION_OK);
               } else {
                  $ma->itemDone($item->getType(), $key, MassiveAction::ACTION_KO);
               }
            }
            return;
      }
      return;
   }
}

?>