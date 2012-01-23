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

class PluginMonitoringConfig extends CommonDBTM {
   

   /**
   * Get name of this type
   *
   *@return text name of this type by language of the user connected
   *
   **/
   static function getTypeName() {
      global $LANG;

      return $LANG['plugin_monitoring']['config'][1];
   }



   function canCreate() {
      return haveRight('computer', 'w');
   }


   
   function canView() {
      return haveRight('computer', 'r');
   }


   
   function canCancel() {
      return haveRight('computer', 'w');
   }


   
   function canUndo() {
      return haveRight('computer', 'w');
   }


   
   function canValidate() {
      return true;
   }


   
   /**
   * Display form for configuration
   *
   * @param $items_id integer ID 
   * @param $options array
   *
   *@return bool true if form is ok
   *
   **/
   function showForm($items_id, $options=array()) {
      global $DB,$CFG_GLPI,$LANG;

      $options['candel'] = false;

      if ($this->getFromDB("1")) {
         
      } else {
         $input = array();
         $input['rrdtoolpath'] = "/usr/local/bin/";
         $this->add($input);
         $this->getFromDB("1");
      }

      $this->showFormHeader($options);

      $this->getFromDB($items_id);

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_monitoring']['config'][2]."&nbsp;:</td>";
      echo "<td align='center'>";
      echo "<input name='rrdtoolpath' type='text' value='".$this->fields['rrdtoolpath']."' />";
      echo "</td>";
      echo "<td>";
      echo $LANG['plugin_monitoring']['config'][0]."&nbsp:";
      echo "</td>";
      echo "<td>";
         $a_timezones = $this->getTimezones();
      
         $a_timezones_selected = importArrayFromDB($this->fields['timezones']);
         $a_timezones_selected2 = array();
         foreach ($a_timezones_selected as $timezone) {
            $a_timezones_selected2[$timezone] = $a_timezones[$timezone];
            unset($a_timezones[$timezone]);
         }
         ksort($a_timezones_selected2);
            
            echo "<table>";
            echo "<tr>";
            echo "<td class='right'>";

            if (count($a_timezones)) {
               echo "<select name='timezones_to_add[]' multiple size='5'>";

               foreach ($a_timezones as $key => $val) {
                  echo "<option value='$key'>".$val."</option>";
               }

               echo "</select>";
            }

            echo "</td><td class='center'>";

            if (count($a_timezones)) {
               echo "<input type='submit' class='submit' name='timezones_add' value='".
                     $LANG['buttons'][8]." >>'>";
            }
            echo "<br><br>";

            if (count($a_timezones_selected2)) {
               echo "<input type='submit' class='submit' name='timezones_delete' value='<< ".
                     $LANG['buttons'][6]."'>";
            }
            echo "</td><td>";

         if (count($a_timezones_selected2)) {
            echo "<select name='timezones_to_delete[]' multiple size='5'>";
            foreach ($a_timezones_selected2 as $key => $val) {
               echo "<option value='$key'>".$val."</option>";
            }
            echo "</select>";
         } else {
            echo "&nbsp;";
         }
         echo "</td>";
         echo "</tr>";
         echo "</table>";
      echo "</td>";
      echo "</tr>";

      $this->showFormButtons($options);

      return true;
   }

   
   
   static function getRRDPath() {
      
      $pmConfig = new PluginMonitoringConfig();
      $pmConfig->getFromDB("1");
      return $pmConfig->getField("rrdtoolpath");
   }
   
   
   static function getTimezones() {
      $a_timezones = array();
      $a_timezones['0'] = "GMT";
      $a_timezones['+1'] = "GMT+1";
      $a_timezones['+2'] = "GMT+2";
      $a_timezones['+3'] = "GMT+3";
      $a_timezones['+4'] = "GMT+4";
      $a_timezones['+5'] = "GMT+5";
      $a_timezones['+6'] = "GMT+6";
      $a_timezones['+7'] = "GMT+7";
      $a_timezones['+8'] = "GMT+8";
      $a_timezones['+9'] = "GMT+9";
      $a_timezones['+10'] = "GMT+10";
      $a_timezones['+11'] = "GMT+11";
      $a_timezones['+12'] = "GMT+12";
      $a_timezones['-1'] = "GMT-1";
      $a_timezones['-2'] = "GMT-2";
      $a_timezones['-3'] = "GMT-3";
      $a_timezones['-4'] = "GMT-4";
      $a_timezones['-5'] = "GMT-5";
      $a_timezones['-6'] = "GMT-6";
      $a_timezones['-7'] = "GMT-7";
      $a_timezones['-8'] = "GMT-8";
      $a_timezones['-9'] = "GMT-9";
      $a_timezones['-10'] = "GMT-10";
      $a_timezones['-11'] = "GMT-11";
      
      ksort($a_timezones);
      return $a_timezones;
      
   }

}

?>