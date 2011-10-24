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

class PluginMonitoringCheck extends CommonDBTM {
   

   function initChecks() {
      global $DB;

      $input = array();
      $input['name'] = '5 checks / 1 retry';
      $input['max_check_attempts'] = '5';
      $input['check_interval']     = '5';
      $input['retry_interval']     = '1';
      $this->add($input);

   }


   /**
   * Get name of this type
   *
   *@return text name of this type by language of the user connected
   *
   **/
   static function getTypeName() {
      global $LANG;

      return $LANG['plugin_monitoring']['check'][0];
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
    
      $tab['common'] = $LANG['plugin_monitoring']['check'][0];

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

      return $ong;
   }
   
   
   
   function getComments() {

      $comment = $LANG['plugin_monitoring']['check'][1].' : '.$this->fields['max_check_attempts'].'<br/>
         '.$LANG['plugin_monitoring']['check'][2].' : '.$this->fields['check_interval'].' minutes<br/>
         '.$LANG['plugin_monitoring']['check'][3].' : '.$this->fields['retry_interval'].' minutes';
      
      if (!empty($comment)) {
         return showToolTip($comment, array('display' => false));
      }

      return $comment;
   }



   /**
   * 
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
      echo "<td align='center'>";
      echo "<input type='text' name='name' value='".$this->fields["name"]."' size='30'/>";
      echo "</td>";
      echo "<td>".$LANG['plugin_monitoring']['check'][1]."&nbsp;:</td>";
      echo "<td align='center'>";
      echo "<input type='text' name='max_check_attempts' value='".$this->fields["max_check_attempts"]."' size='30'/>";
      echo "</td>";
      echo "</tr>";
      
      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_monitoring']['check'][2]."&nbsp;:</td>";
      echo "<td align='center'>";
      Dropdown::showInteger("check_interval", $this->fields["check_interval"], 1, 100);
      echo "</td>";
      echo "<td>".$LANG['plugin_monitoring']['check'][3]."&nbsp;:</td>";
      echo "<td align='center'>";
      Dropdown::showInteger("retry_interval", $this->fields["retry_interval"], 1, 100);
      echo "</td>";
      echo "</tr>";
      
      $this->showFormButtons($options);
      $this->addDivForTabs();

      return true;
   }


}

?>