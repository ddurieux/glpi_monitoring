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

class PluginMonitoringContact_Item extends CommonDBTM {
   

   static function getTypeName() {
      global $LANG;

      return $LANG['plugin_monitoring']['contact'][20];
   }


   function canCreate() {
      return PluginMonitoringProfile::haveRight("config", 'w');
   }


   
   function canView() {
      return PluginMonitoringProfile::haveRight("config", 'r');
   }


   
   function canCancel() {
      return PluginMonitoringProfile::haveRight("config", 'w');
   }


   
   function canUndo() {
      return PluginMonitoringProfile::haveRight("config", 'w');
   }

   
   
   function showContacts($itemtype, $items_id) {
      global $DB,$LANG,$CFG_GLPI;

      $this->addContact($itemtype, $items_id);
      
      $group = new Group();
      $user  = new User();
      
      $rand = mt_rand();
      
      echo "<form method='post' name='contact_item_form$rand' id='contact_item_form$rand' action=\"".
                $CFG_GLPI["root_doc"]."/plugins/monitoring/front/contact_item.form.php\">";
      
      echo "<table class='tab_cadre_fixe'>";

      echo "<tr>";
      echo "<th>";
      echo $LANG['plugin_monitoring']['contact'][20];
      echo "</th>";
      echo "</tr>";
      
      echo "</table>";
      
      echo "<table class='tab_cadre_fixe'>";
      
      echo "<tr>";
      echo "<th width='10'>&nbsp;</th>";
      echo "<th>".$LANG['common'][35]." - ".$LANG['common'][16]."</th>";
      echo "<th colspan='2'></th>";
      echo "</tr>";
      
      $used = array();
      // Display groups first
      $query = "SELECT * FROM `".$this->getTable()."`
         WHERE `items_id`='".$items_id."'
            AND `itemtype`='".$itemtype."'
            AND `groups_id` > 0";
      $result = $DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         $group->getFromDB($data['groups_id']);

         echo "<tr>";
         echo "<td>";
         echo "<input type='checkbox' name='item[".$data["id"]."]' value='1'>";
         echo "</td>";
         echo "<td class='center'>";
         echo $group->getLink(1);         
         echo "</td>";
         echo "<td colspan='2'>";

         echo "</td>";
         
         echo "</tr>";
      }
      
      echo "<tr>";
      echo "<th width='10'>&nbsp;</th>";
      echo "<th>".$LANG['common'][34]." - ".$LANG['common'][16]."</th>";
      echo "<th>".$LANG['setup'][14]."</th>";
      echo "<th>".$LANG['help'][35]."</th>";
      echo "</tr>";
      
      $used = array();
      // Display Users
      $query = "SELECT * FROM `".$this->getTable()."`
         WHERE `items_id`='".$items_id."'
            AND `itemtype`='".$itemtype."'
            AND `users_id` > 0";
      $result = $DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         $user->getFromDB($data['users_id']);

         echo "<tr>";
         echo "<td>";
         echo "<input type='checkbox' name='item[".$data["id"]."]' value='1'>";
         echo "</td>";
         echo "<td class='center'>";
         echo $user->getLink(1);         
         echo "</td>";
         echo "<td class='center'>";
         echo $user->fields['email'];
         echo "</td>";
         echo "<td class='center'>";
         echo $user->fields['phone'];
         echo "</td>";
         
         echo "</tr>";
      }
      
      Html::openArrowMassives("contact_item_form$rand", true);
      Html::closeArrowMassives('deleteitem', $LANG['buttons'][6]);
      
      echo "</table>";
      
   }
   
   
   function addContact($itemtype, $items_id) {
      global $DB,$LANG;
      
      $this->getEmpty();
      
      $this->showFormHeader();      
     
      echo "<tr>";
      echo "<td>";
      echo $LANG['common'][34]."&nbsp;:";
      echo "<input type='hidden' name='items_id' value='".$items_id."'/>";
      echo "<input type='hidden' name='itemtype' value='".$itemtype."'/>";
      echo "</td>";
      echo "<td>";
      Dropdown::show("User", array('name'=>'users_id'));
      echo "</td>";
      echo "<td colspan='2'>";
      echo "</td>";
      echo "</tr>";
      
      $this->showFormButtons();
      
      $this->showFormHeader();      
     
      echo "<tr>";
      echo "<td>";
      echo $LANG['common'][35]."&nbsp;:";
      echo "<input type='hidden' name='items_id' value='".$items_id."'/>";
      echo "<input type='hidden' name='itemtype' value='".$itemtype."'/>";
      echo "</td>";
      echo "<td>";
      Dropdown::show("Group", array('name'=>'groups_id'));
      echo "</td>";
      echo "<td colspan='2'>";
      echo "</td>";
      echo "</tr>";
      
      $this->showFormButtons();
   }
   
   
 
}

?>