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

class PluginMonitoringEntity extends CommonDBTM {
   


   /**
   * Get name of this type
   *
   *@return text name of this type by language of the user connected
   *
   **/
   static function getTypeName() {
      global $LANG;

      return "entity";
   }



   function canCreate() {
      return haveRight('entity', 'w');
   }


   
   function canView() {
      return haveRight('entity', 'r');
   }


   
   function canCancel() {
      return haveRight('entity', 'w');
   }


   
   function canUndo() {
      return haveRight('entity', 'w');
   }


   
   function canValidate() {
      return true;
   }



   /**
   * Display form for entity tag
   *
   * @param $items_id integer ID of the entity 
   * @param $options array
   *
   *@return bool true if form is ok
   *
   **/
   function showForm($items_id, $options=array()) {
      global $DB,$CFG_GLPI,$LANG;

      $a_entities = $this->find("`entities_id`='".$items_id."'", "", 1);
      if (count($a_entities) == '0') {
         $input = array();
         $input['entities_id'] = $items_id;
         $id = $this->add($input);
         $this->getFromDB($id);
      } else {
         $a_entity = current($a_entities);
         $this->getFromDB($a_entity['id']);
      }

      echo "<form name='form' method='post' 
         action='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/entity.form.php'>";
      
      echo "<table class='tab_cadre_fixe'";
      
      echo "<tr class='tab_bg_1'>";
      echo "<th colspan='2'>";
      echo $LANG['plugin_monitoring']['entity'][1];
      echo "</th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_monitoring']['entity'][0]." :</td>";
      echo "<td>";
      echo "<input type='text' name='tag' value='".$this->fields["tag"]."' size='30'/>";
      
      echo "</td>";
      echo "</tr>";
      
      echo "<tr class='tab_bg_1'>";
      echo "<td colspan='2' align='center'>";
      echo "<input type='hidden' name='id' value='".$this->fields['id']."'/>";
      echo "<input type='submit' name='update' value=\"".$LANG['buttons'][7]."\" class='submit'>";
      echo "</td>";
      echo "</tr>";
      
      echo "</table>";
      echo "</form>";

      return true;
   }
}

?>