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
   @since     2011
 
   ------------------------------------------------------------------------
 */


if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginMonitoringProfile extends CommonDBTM {

   static function canView() {
      return Session::haveRight('profile','r');
   }

   static function canCreate() {
      return Session::haveRight('profile','w');
   }

   
   
   /**
    * Get the name of the index field
    *
    * @return name of the index field
   **/
   static function getIndexName() {
      return "profiles_id";
   }


   /**
    * Create full profile
    *
    **/
   static function initProfile() {
      if (isset($_SESSION['glpiactiveprofile']['id'])) {
         $input = array();
         $input['profiles_id'] = $_SESSION['glpiactiveprofile']['id'];
         $input['dashboard'] = 'w';
         $input['servicescatalog'] = 'w';
         $input['view'] = 'w';
         $input['componentscatalog'] = 'w';
         $input['viewshomepage'] = 'r';
         $input['weathermap'] = 'w';
         $input['component'] = 'w';
         $input['command'] = 'w';
         $input['config'] = 'w';
         $input['check'] = 'w';
         $input['allressources'] = 'w';
         $input['restartshinken'] = 'w';
         $pmProfile = new self();
         $pmProfile->add($input);
      }
   }
   
   

   static function changeprofile() {
      if (isset($_SESSION['glpiactiveprofile']['id'])) {
         $tmp = new self();
          if ($tmp->getFromDB($_SESSION['glpiactiveprofile']['id'])) {
             $_SESSION["glpi_plugin_monitoring_profile"] = $tmp->fields;
          } else {
             unset($_SESSION["glpi_plugin_monitoring_profile"]);
          }
      }
   }

   
   
   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {

      if (Session::haveRight('profile', "r")) {
         if ($item->getID() > 0) {
            return self::createTabEntry(__('Monitoring', 'monitoring'));
         }
      }
      return '';
   }



   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {

      if ($item->getID() > 0) {
         $pmProfile = new self();
         $pmProfile->showForm($item->getID());
      }

      return true;
   }

   
   

    /**
    * Show profile form
    *
    * @param $items_id integer id of the profile
    * @param $target value url of target
    *
    * @return nothing
    **/
   function showForm($items_id) {
      global $CFG_GLPI;
      
      if ($items_id > 0 
              AND $this->getFromDB($items_id)) {
        
      } else {
         $this->getEmpty();
      }
      
      if (!Session::haveRight("profile","r")) {
         return false;
      }
      $canedit=Session::haveRight("profile","w");
      if ($canedit) {
         echo "<form method='post' action='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/profile.form.php'>";
         echo '<input type="hidden" name="profiles_id" value="'.$items_id.'"/>';
      }

      echo "<div class='spaced'>";
      echo "<table class='tab_cadre_fixe'>";

      echo "<tr>";
      echo "<th colspan='4'>".__('Monitoring', 'monitoring')." :</th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo __('Dashboard', 'monitoring')."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Profile::dropdownNoneReadWrite("dashboard", $this->fields["dashboard"], 1, 1, 1);
      echo "</td>";
      echo "<td>";
      echo __('Services catalog', 'monitoring')."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Profile::dropdownNoneReadWrite("servicescatalog", $this->fields["servicescatalog"], 1, 1, 1);
      echo "</td>";
      echo "</tr>";
      
      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo __('Views', 'monitoring')."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Profile::dropdownNoneReadWrite("view", $this->fields["view"], 1, 1, 1);
      echo "</td>";
      echo "<td>";
      echo __('Components catalog', 'monitoring')."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Profile::dropdownNoneReadWrite("componentscatalog", $this->fields["componentscatalog"], 1, 1, 1);
      echo "</td>";
      echo "</tr>";
      
      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo __('Views in GLPI home page', 'monitoring')."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Profile::dropdownNoneReadWrite("viewshomepage", $this->fields["viewshomepage"], 1, 1, 0);
      echo "</td>";
      echo "<td>";
      echo __('Weathermap', 'monitoring')."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Profile::dropdownNoneReadWrite("weathermap", $this->fields["weathermap"], 1, 1, 1);
      echo "</td>";
      echo "</tr>";
      
      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo __('Components', 'monitoring')."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Profile::dropdownNoneReadWrite("component", $this->fields["component"], 1, 1, 1);
      echo "</td>";
      echo "<td>";
      echo __('Commands', 'monitoring')."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Profile::dropdownNoneReadWrite("command", $this->fields["command"], 1, 1, 1);
      echo "</td>";
      echo "</tr>";

      
      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo __('Setup')."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Profile::dropdownNoneReadWrite("config", $this->fields["config"], 1, 1, 1);
      echo "</td>";
      echo "<td>";
      echo __('Check definition', 'monitoring')."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Profile::dropdownNoneReadWrite("check", $this->fields["check"], 1, 1, 1);
      echo "</td>";
      echo "</tr>";
      
      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo __('See all resources in dashboard', 'monitoring')."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Profile::dropdownNoneReadWrite("allressources", $this->fields["allressources"], 1, 1, 0);
      echo "</td>";
      echo "<td>";
      echo __('Restart shinken', 'monitoring')."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Profile::dropdownNoneReadWrite("restartshinken", $this->fields["restartshinken"], 1, 0, 1);
      echo "</td>";
      echo "</tr>";
      
      if ($canedit) {
         echo "<tr>";
         echo "<th colspan='4'>";
         echo "<input type='hidden' name='profile_id' value='".$items_id."'/>";
         echo "<input type='submit' name='update' value=\"".__('Save')."\" class='submit'>";
         echo "</td>";
         echo "</tr>";
         echo "</table>";
         Html::closeForm();
      } else {
         echo "</table>";
      }
      echo "</div>";

      Html::closeForm();
   }

   

   static function checkRight($module, $right) {
      global $CFG_GLPI;

      if (!PluginMonitoringProfile::haveRight($module, $right)) {
         // Gestion timeout session
         if (!Session::getLoginUserID()) {
            Html::redirect($CFG_GLPI["root_doc"] . "/index.php");
            exit ();
         }
         Html::displayRightError();
      }
   }



   static function haveRight($module, $right) {
      global $DB;

      //If GLPI is using the slave DB -> read only mode
      if ($DB->isSlave() && $right == "w") {
         return false;
      }

      $matches = array(""  => array("", "r", "w"), // ne doit pas arriver normalement
                       "r" => array("r", "w"),
                       "w" => array("w"),
                       "1" => array("1"),
                       "0" => array("0", "1")); // ne doit pas arriver non plus

      if (isset ($_SESSION["glpi_plugin_monitoring_profile"][$module])
          && in_array($_SESSION["glpi_plugin_monitoring_profile"][$module], $matches[$right])) {
         return true;
      }
      return false;
   }

      
   
   /**
    * Update the item in the database
    *
    * @param $updates fields to update
    * @param $oldvalues old values of the updated fields
    *
    * @return nothing
   **/
   function updateInDB($updates, $oldvalues=array()) {
      global $DB, $CFG_GLPI;

      foreach ($updates as $field) {
         if (isset($this->fields[$field])) {
            $query  = "UPDATE `".$this->getTable()."`
                       SET `".$field."`";

            if ($this->fields[$field]=="NULL") {
               $query .= " = ".$this->fields[$field];

            } else {
               $query .= " = '".$this->fields[$field]."'";
            }

            $query .= " WHERE `profiles_id` ='".$this->fields["profiles_id"]."'";

            if (!$DB->query($query)) {
               if (isset($oldvalues[$field])) {
                  unset($oldvalues[$field]);
               }
            }

         } else {
            // Clean oldvalues
            if (isset($oldvalues[$field])) {
               unset($oldvalues[$field]);
            }
         }

      }

      if (count($oldvalues)) {
         Log::constructHistory($this, $oldvalues, $this->fields);
      }
      return true;
   }

   
   
   /**
    * Add a message on update action
   **/
   function addMessageOnUpdateAction() {
      global $CFG_GLPI;

      $link = $this->getFormURL();
      if (!isset($link)) {
         return;
      }

      $addMessAfterRedirect = false;

      if (isset($this->input['_update'])) {
         $addMessAfterRedirect = true;
      }

      if (isset($this->input['_no_message']) || !$this->auto_message_on_action) {
         $addMessAfterRedirect = false;
      }

      if ($addMessAfterRedirect) {
         $profile = new Profile();
         $profile->getFromDB($this->fields['profiles_id']);
         // Do not display quotes
         if (isset($profile->fields['name'])) {
            $profile->fields['name'] = stripslashes($profile->fields['name']);
         } else {
            $profile->fields['name'] = $profile->getTypeName()." : ".__('ID')." ".
                                    $profile->fields['id'];
         }

         Session::addMessageAfterRedirect(__('Item successfully updated')."&nbsp;: " .
                                 (isset($this->input['_no_message_link'])?$profile->getNameID()
                                                                         :$profile->getLink()));
      }
   }
}

?>