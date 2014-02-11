<?php

/*
   ------------------------------------------------------------------------
   Plugin Monitoring for GLPI
   Copyright (C) 2011-2014 by the Plugin Monitoring for GLPI Development Team.

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
   @copyright Copyright (c) 2011-2014 Plugin Monitoring for GLPI team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      https://forge.indepnet.net/projects/monitoring/
   @since     2014
 
   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginMonitoringSlider extends CommonDBTM {
   

   /**
   * Get name of this type
   *
   *@return text name of this type by language of the user connected
   *
   **/
   static function getTypeName($nb=0) {
      return __('Slider', 'monitoring');
   }



   static function canCreate() {
      return PluginMonitoringProfile::haveRight("config", 'w');
   }


   
   static function canView() {
      return PluginMonitoringProfile::haveRight("config", 'r');
   }

   

   function getSearchOptions() {

      $tab = array();
    
      $tab['common'] = __('Slider', 'monitoring');

		$tab[1]['table'] = $this->getTable();
		$tab[1]['field'] = 'name';
		$tab[1]['linkfield'] = 'name';
		$tab[1]['name'] = __('Name');
		$tab[1]['datatype'] = 'itemlink';

      return $tab;
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
   function showForm($items_id, $options=array(), $copy=array()) {
      global $DB,$CFG_GLPI;

      if ($items_id!='') {
         $this->getFromDB($items_id);
      } else {
         $this->getEmpty();
      }
      
      if (count($copy) > 0) {
         foreach ($copy as $key=>$value) {
            $this->fields[$key] = stripslashes($value);
         }
      }

      $this->showTabs($options);
      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Name')." :</td>";
      echo "<td>";
      echo "<input type='text' name='name' value='".$this->fields["name"]."' size='30'/>";
      echo "</td>";
      echo "<td>".__('Command name', 'monitoring')."&nbsp;:</td>";
      echo "<td>";
      echo "<input type='text' name='command_name' value='".$this->fields["command_name"]."' size='30'/>";
      echo "</td>";
      echo "</tr>";
      
      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Command line', 'monitoring')."&nbsp;:</td>";
      echo "<td colspan='3'>";
      echo '<input type="text" name="command_line" value="'.htmlspecialchars($this->fields["command_line"]).'" size="97"/>';
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Arguments description', 'monitoring')."&nbsp;:</td>";
      echo "<td colspan='3'>";
         $arguments = array();
         preg_match_all("/\\$(ARG\d+)\\$/", $this->fields['command_line'], $arguments);
         $arrayargument = importArrayFromDB($this->fields["arguments"]);
         echo "<table>";
         foreach ($arguments[0] as $adata) {
            $adata = str_replace('$', '', $adata);
            echo "<tr>";
            echo "<td>";
            echo " ".$adata. " : ";
            echo "</td>";
            echo "<td>";
            if (!isset($arrayargument[$adata])) {
               $arrayargument[$adata] = '';
            }
            echo "<textarea cols='90' rows='2' name='argument_".$adata."' >".$arrayargument[$adata]."</textarea>";
            echo "</td>";
            echo "</tr>";
         }
         echo "</table>";
      
      echo "</td>";
      echo "</tr>";
      
      $this->showFormButtons($options);
      
      // Add form for copy item
      if ($items_id!='' && PluginMonitoringProfile::haveRight("config","w")) {
         $this->fields['id'] = 0;
         $this->showFormHeader($options);
         
         echo "<tr class='tab_bg_1'>";
         echo "<td colspan='4' class='center'>";
         foreach ($this->fields as $key=>$value) {
            if ($key != 'id') {
               echo "<input type='hidden' name='".$key."' value='".$value."'/>";
            }
         }
         echo "<input type='submit' name='copy' value=\"".__('copy', 'monitoring')."\" class='submit'>";
         echo "</td>";
         echo "</tr>";
         
         echo "</table>";
         Html::closeForm();
      }

      return true;
   }



   function slideSlider() {
      global $CFG_GLPI;
      echo '<script src="'.$CFG_GLPI["root_doc"].'/plugins/monitoring/lib/slider.js-14/js/jssor.slider.mini.js"></script>
<script>
    jQuery(document).ready(function ($) {
        //Define an array of slideshow transition code
        var _SlideshowTransitions = [
        {$Duration:4000,$Opacity:2}
        ];
        var options = {
            $AutoPlay: true,
            $SlideshowOptions: {
                    $Class: $JssorSlideshowRunner$,
                    $Transitions: _SlideshowTransitions,
                    $TransitionsOrder: 1,
                    $ShowLink: true
                }
        };
        var jssor_slider1 = new $JssorSlider$(\'slider1_container\', options);
    });
</script>';
      $pm = new PluginMonitoringComponentscatalog();
      echo '<div id="slider1_container" style="position: relative;
top: 0px; left: 0px; width: 300px; height: 300px;">
    <!-- Slides Container -->
    <div u="slides" style="cursor: move; position: absolute; overflow: hidden;
    left: 0px; top: 0px; width: 300px; height: 300px;">
        <div>';
      echo $pm->showWidget(8);
      echo $pm->showWidgetFrame(8);
      echo '</div>
        <div>';
      echo $pm->showWidget(9);
      echo $pm->showWidgetFrame(9);
      echo '</div>
        <div>';
      echo $pm->showWidget(2);
      echo $pm->showWidgetFrame(2);
      echo '</div>
    </div>
</div>';
   }
}

?>