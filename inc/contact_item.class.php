<?php

/*
   ----------------------------------------------------------------------
   Monitoring plugin for GLPI
   Copyright (C) 2010-2011 by the GLPI plugin monitoring Team.

   https://forge.indepnet.net/projects/monitoring/
   ----------------------------------------------------------------------

   LICENSE

   This file is part of Monitoring plugin for GLPI.

   Monitoring plugin for GLPI is free software: you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation, either version 2 of the License, or
   any later version.

   Monitoring plugin for GLPI is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with Monitoring plugin for GLPI.  If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------
   Original Author of file: David DURIEUX
   Co-authors of file:
   Purpose of file:
   ----------------------------------------------------------------------
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
      return true;
   }


   
   function canView() {
      return true;
   }


   
   function canCancel() {
      return true;
   }


   
   function canUndo() {
      return true;
   }


   
   function canValidate() {
      return true;
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
      
      openArrowMassive("contact_item_form$rand", true);
      closeArrowMassive('deleteitem', $LANG['buttons'][6]);
      
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