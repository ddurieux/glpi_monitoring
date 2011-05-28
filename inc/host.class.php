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

class PluginMonitoringHost extends CommonDBTM {
   

   /**
   * Get name of this type
   *
   *@return text name of this type by language of the user connected
   *
   **/
   static function getTypeName() {
      global $LANG;

      return $LANG['plugin_monitoring']['host'][0];
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

   

   function getSearchOptions() {
      global $LANG;

      $tab = array();
    
      $tab['common'] = $LANG['plugin_monitoring']['host'][0];

      return $tab;
   }



   function defineTabs($options=array()){
      global $LANG,$CFG_GLPI;

      $ong = array();

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
   function showForm($items_id, $options=array(), $itemtype='') {
      global $DB,$CFG_GLPI,$LANG;

      if ($items_id == '') {
         $a_list = $this->find("`items_id`='".$_POST['id']."' AND `itemtype`='".$itemtype."'", '', 1);
         if (count($a_list)) {
            $array = current($a_list);
            $items_id = $array['id'];
         }
      }

      if ($items_id!='') {
         $this->getFromDB($items_id);
      } else {
         $this->getEmpty();
      }

      $this->showFormHeader($options);

      if ($items_id!='') {
         $this->getFromDB($items_id);

         echo "<tr class='tab_bg_1'>";
         echo "<td>".$LANG['plugin_monitoring']['host'][1]."&nbsp;:</td>";
         echo "<td align='center'>";
         $array = array();
         $array[0] = $LANG['common'][49];
         $array[1] = $LANG['plugin_monitoring']['host'][3];
         $array[2] = $LANG['plugin_monitoring']['host'][2];
         Dropdown::showFromArray("parenttype", $array, array('value'=>$this->fields['parenttype']));
         echo "</td>";
         echo "<td>".$LANG['plugin_monitoring']['host'][7]."&nbsp;:</td>";
         echo "<td align='center'>";
         echo "</td>";
         echo "</tr>";

         echo "<tr class='tab_bg_1'>";
         echo "<td>".$LANG['plugin_monitoring']['host'][5]."&nbsp;:</td>";
         echo "<td align='center'>";
         Dropdown::showYesNo("active_checks_enabled", $this->fields['active_checks_enabled']);
         echo "</td>";
         echo "<td>".$LANG['plugin_monitoring']['host'][6]."&nbsp;:</td>";
         echo "<td align='center'>";
         Dropdown::showYesNo("passive_checks_enabled", $this->fields['passive_checks_enabled']);
         echo "</td>";
         echo "</tr>";
         
         $this->showFormButtons($options);
      } else {
         // Add button for host creation
         echo "<tr>";
         echo "<td colspan='4' align='center'>";
         echo "<input name='items_id' type='hidden' value='".$_POST['id']."' />";
         echo "<input name='itemtype' type='hidden' value='".$itemtype."' />";
         echo "<input name='add' value='Add this host to monitoring' class='submit' type='submit'></td>";
         echo "</tr>";
         $this->showFormButtons(array('canedit'=>false));
      }
      


      return true;
   }



   function manageDependencies($items_id) {
      global $LANG;

      $this->getFromDB($items_id);


      echo "<form name='dependencies_form' id='dependencies_form'
             method='post' action=' ";
      echo getItemTypeFormURL(__CLASS__)."'>";
      echo "<table class='tab_cadre_fixe'>";
      echo "<tr class='tab_bg_1'>";
      echo "<th colspan='3'>";
      echo $LANG['plugin_monitoring']['host'][1];
      echo "</th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td class='right'>";
      Dropdown::showAllItems("parent_to_add");
      echo "</td>";
      echo "<td class='center'>";
      echo "<input type='submit' class='submit' name='parent_add' value='".
            $LANG['buttons'][8]." >>'>";
      echo "<br><br>";
      if ($this->getField('parents')) {
         echo "<input type='submit' class='submit' name='parent_delete' value='<< ".
               $LANG['buttons'][6]."'>";
      }
      echo "</td>";
      echo "<td>";
      if ($this->getField('parents')) {
         echo "<select name='parent_to_delete[]' multiple size='5'>";
         $array = importArrayFromDB($this->getField('parents'));
         foreach ($array as $data) {
            $datasplit = explode("-", $data);
            $classname = $datasplit[0];
            $class = new $classname;
            $class->getFromDB($datasplit[1]);
            echo "<option value='".$data."'>[".$datasplit[0]."] ".$class->getName()." - ".$class->getField('serial')."</option>";
         }
         echo "</select>";
      } else {
         echo "&nbsp;";
      }
      echo "</td>";
      echo "</tr>";
      echo "</table>";
      echo "<input type='hidden' name='id' value='".$items_id."' />";
      echo "</form>";
   }
}

?>