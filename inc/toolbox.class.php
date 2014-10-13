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


/**
 * Toolbox of various utility methods
 **/
class PluginMonitoringToolbox {

   static function loadLib() {
      global $CFG_GLPI;

      echo '<link href="'.$CFG_GLPI["root_doc"].'/plugins/monitoring/lib/nvd3/src/nv.d3.css" rel="stylesheet" type="text/css">
      <script src="'.$CFG_GLPI["root_doc"].'/plugins/monitoring/lib/nvd3/lib/d3.v2.min.js"></script>
      <script src="'.$CFG_GLPI["root_doc"].'/plugins/monitoring/lib/nvd3/nv.d3.min.js"></script>
      <script src="'.$CFG_GLPI["root_doc"].'/plugins/monitoring/lib/nvd3/src/tooltip.js"></script>
      <script src="'.$CFG_GLPI["root_doc"].'/plugins/monitoring/lib/nvd3/src/utils.js"></script>
      <script src="'.$CFG_GLPI["root_doc"].'/plugins/monitoring/lib/nvd3/src/models/legend.js"></script>
      <script src="'.$CFG_GLPI["root_doc"].'/plugins/monitoring/lib/nvd3/src/models/axis.js"></script>
      <script src="'.$CFG_GLPI["root_doc"].'/plugins/monitoring/lib/nvd3/src/models/scatter.js"></script>
      <script src="'.$CFG_GLPI["root_doc"].'/plugins/monitoring/lib/nvd3/src/models/line.js"></script>
      <script src="'.$CFG_GLPI["root_doc"].'/plugins/monitoring/lib/nvd3/src/models/lineChart.js"></script>
      <script src="'.$CFG_GLPI["root_doc"].'/plugins/monitoring/lib/gauge.js"></script>
      <script src="'.$CFG_GLPI["root_doc"].'/plugins/monitoring/lib/jqueryplugins/tooltipsy/tooltipsy.min.js"></script>
      <script src="'.$CFG_GLPI["root_doc"].'/plugins/monitoring/lib/jqueryplugins/tooltipsy/jquery.tipsy.js"></script>';
   }



   static function loadPreferences($components_id) {

      $pmComponent = new PluginMonitoringComponent();
      $pmComponent->getFromDB($components_id);

      $_SESSION['glpi_plugin_monitoring']['perfname'][$components_id] = array();
      // $a_perfname = importArrayFromDB($pmComponent->fields['perfname']);
      $a_perfname = @unserialize($pmComponent->fields['perfname']);

      if ($a_perfname === FALSE) {
         // Val not serialized
         $input = array(
             'id' => $pmComponent->fields['id'],
             'perfname' => ''
         );
         $pmComponent->update($input);
         $a_perfname = array();
         if (isset($_SESSION['glpi_plugin_monitoring']['perfname'][$components_id])) {
            unset($_SESSION['glpi_plugin_monitoring']['perfname'][$components_id]);
         }
      }

      // No perfdata for this service ...
      if (!is_array($a_perfname)) return false;

      foreach ($a_perfname as $perfname=>$active) {
         $_SESSION['glpi_plugin_monitoring']['perfname'][$components_id][$perfname] = 'checked';
      }

      $_SESSION['glpi_plugin_monitoring']['perfnameinvert'][$components_id] = array();
      // $a_perfnameinvert = importArrayFromDB($pmComponent->fields['perfnameinvert']);
      $a_perfnameinvert = @unserialize($pmComponent->fields['perfnameinvert']);
      if ($a_perfnameinvert !== false) {
         foreach ($a_perfnameinvert as $perfname=>$active) {
            $_SESSION['glpi_plugin_monitoring']['perfnameinvert'][$components_id][$perfname] = 'checked';
         }

         $_SESSION['glpi_plugin_monitoring']['perfnamecolor'][$components_id] = array();
         // $a_perfnamecolor = importArrayFromDB($pmComponent->fields['perfnamecolor']);
         $a_perfnamecolor = unserialize($pmComponent->fields['perfnamecolor']);
         foreach ($a_perfnamecolor as $perfname=>$color) {
            $_SESSION['glpi_plugin_monitoring']['perfnamecolor'][$components_id][$perfname] = $color;
         }
      }
      return true;
   }



   static function preferences($components_id, $loadpreferences=1, $displayonly=0) {
      global $CFG_GLPI;

      if ($loadpreferences == 1) {
         if (! PluginMonitoringToolbox::loadPreferences($components_id)) return false;
      }

      $pmComponent = new PluginMonitoringComponent();
      $pmComponent->getFromDB($components_id);

      echo "<form method='post'>";
      $a_perfnames = array();
      $a_perfnames = PluginMonitoringServicegraph::getperfdataNames($pmComponent->fields['graph_template']);
      echo "<table class='tab_cadre_fixe'>";
      echo "<tr class='tab_bg_3'>";
      echo "<td rowspan='".ceil(count($a_perfnames) / 7)."' width='90'>";
      echo __('Display', 'monitoring')."&nbsp;:";

      echo "</td>";
      $i = 0;
      $j = 0;
      if (!isset($_SESSION['glpi_plugin_monitoring']['perfname'][$components_id])) {
         foreach ($a_perfnames as $name) {
            $_SESSION['glpi_plugin_monitoring']['perfname'][$components_id][$name] = 'checked';
         }
      }

      echo "<td>";
      echo '<select id="jquery-tagbox-select-options">';

      $a_incremental = array();
      $a_perfdatadetails = getAllDatasFromTable('glpi_plugin_monitoring_perfdatadetails',
                           "plugin_monitoring_perfdatas_id='".$pmComponent->fields['graph_template']."'");
      foreach ($a_perfdatadetails as $data) {
         for ($nb=1; $nb <= 15; $nb++) {
            if ($data['dsnameincr'.$nb] == '1') {
               $a_incremental[$data['dsname'.$nb]] = 1;
            }
         }
      }
      $a_list_val = array();
      foreach ($a_perfnames as $name) {
         $disabled = '';
         if (isset($_SESSION['glpi_plugin_monitoring']['perfname'][$components_id][$name])) {
            $a_list_val[] = $name;
            $disabled = 'disabled="disabled"';
         }
         echo '<option value="'.$name.'" '.$disabled.'>'.$name.'</option>';
         if (isset($a_incremental[$name])) {
            $name .= ' | diff';
            $disabled = '';
            if (isset($_SESSION['glpi_plugin_monitoring']['perfname'][$components_id][$name])) {
               $a_list_val[] = $name;
               $disabled = 'disabled="disabled"';
            }
            echo '<option value="'.$name.'" '.$disabled.'>'.$name.'</option>';
         }
      }
      echo '</select>
      <input name="perfname" id="jquery-tagbox-select" type="text" value="'.implode('####', $a_list_val).'" />';

      echo "</td>";
      echo "</tr>";

//      foreach ($a_perfnames as $name) {
//         if ($i == 'O'
//                 AND $j == '1') {
//            echo "<tr>";
//         }
//         echo "<td>";
//         $checked = "checked";
//         if (isset($_SESSION['glpi_plugin_monitoring']['perfname'][$components_id])) {
//            $checked = "";
//         }
//         if (isset($_SESSION['glpi_plugin_monitoring']['perfname'][$components_id][$name])) {
//            $checked = $_SESSION['glpi_plugin_monitoring']['perfname'][$components_id][$name];
//         }
//         echo "<input type='checkbox' name='perfname[]' value='".$name."' ".$checked."/> ".$name;
//         echo "</td>";
//         $i++;
//         if ($i == 6) {
//            $i = 0;
//            echo "</tr>";
//         }
//         $j = 1;
//      }
//      if ($i != 6) {
//         echo "<td colspan='".(6-$i)."'></td>";
//         echo "</tr>";
//      }
      echo "<tr class='tab_bg_3'>";
      echo "<td colspan='9' align='center'>";
      echo "<input type='hidden' name='id' value='".$components_id."'/>";
      echo "<input type='submit' name='updateperfdata' value=\"".__('Save')."\" class='submit'>";
      echo "</td>";
      echo "</tr>";

      echo "</table>";

      if ($displayonly == 1) {
         return;
      }
      // * Invert perfname

      $a_perfnames = array();
      $a_perfnames = PluginMonitoringServicegraph::getperfdataNames($pmComponent->fields['graph_template']);
      echo "<table class='tab_cadre_fixe'>";
      echo "<tr class='tab_bg_3'>";
      echo "<td rowspan='".ceil(count($a_perfnames) / 7)."' width='90'>";
      echo __('Invert values', 'monitoring')."&nbsp;:";

      echo "</td>";
      $i = 0;
      $j = 0;
      echo "<td>";
      echo '<select id="jquery-tagbox-select2-options">';

      $a_incremental = array();
      $a_perfdatadetails = getAllDatasFromTable('glpi_plugin_monitoring_perfdatadetails',
                           "plugin_monitoring_perfdatas_id='".$pmComponent->fields['graph_template']."'");
      foreach ($a_perfdatadetails as $data) {
         for ($nb=1; $nb <= 15; $nb++) {
            if ($data['dsnameincr'.$nb] == '1') {
               $a_incremental[$data['dsname'.$nb]] = 1;
            }
         }
      }
      $a_list_val2 = array();
      foreach ($a_list_val as $name) {
         $disabled = '';
         if (isset($_SESSION['glpi_plugin_monitoring']['perfnameinvert'][$components_id][$name])) {
            $a_list_val2[] = $name;
            $disabled = 'disabled="disabled"';
         }
         echo '<option value="'.$name.'" '.$disabled.'>'.$name.'</option>';
         if (isset($a_incremental[$name])) {
            $name .= ' | diff';
            $disabled = '';
            if (isset($_SESSION['glpi_plugin_monitoring']['perfnameinvert'][$components_id][$name])) {
               $a_list_val[] = $name;
               $disabled = 'disabled="disabled"';
            }
            echo '<option value="'.$name.'" '.$disabled.'>'.$name.'</option>';
         }
      }
      echo '</select>
      <input name="perfnameinvert" id="jquery-tagbox-select2" type="text" value="'.implode('####', $a_list_val2).'" />';
      echo "</td>";

//      foreach ($a_perfnames as $name) {
//         if ($i == 'O'
//                 AND $j == '1') {
//            echo "<tr>";
//         }
//         echo "<td>";
//         $checked = "";
//         if (isset($_SESSION['glpi_plugin_monitoring']['perfnameinvert'][$components_id][$name])) {
//            $checked = $_SESSION['glpi_plugin_monitoring']['perfnameinvert'][$components_id][$name];
//         }
//         echo "<input type='checkbox' name='perfnameinvert[]' value='".$name."' ".$checked."/> ".$name;
//         echo "</td>";
//         $i++;
//         if ($i == 6) {
//            $i = 0;
//            echo "</tr>";
//         }
//         $j = 1;
//      }
//      if ($i != 6) {
//         echo "<td colspan='".(6-$i)."'></td>";
//         echo "</tr>";
//      }
      echo "<tr class='tab_bg_3'>";
      echo "<td colspan='9' align='center'>";
      echo "<input type='hidden' name='id' value='".$components_id."'/>";
      echo "<input type='submit' name='updateperfdata' value=\"".__('Save')."\" class='submit'>";
      echo "</td>";
      echo "</tr>";

      echo "</table>";


      // * Define color of perfname


      $a_perfnames = array();
      $a_perfnames = PluginMonitoringServicegraph::getperfdataNames($pmComponent->fields['graph_template']);
      foreach ($a_perfnames as $key=>$name) {
         if (!isset($_SESSION['glpi_plugin_monitoring']['perfname'][$components_id][$name])) {
            unset($a_perfnames[$key]);
         }
      }
      echo "<table class='tab_cadre_fixe'>";
      echo "<tr class='tab_bg_1'>";
      echo "<td rowspan='".ceil(count($a_perfnames) / 4)."' width='90'>";
      echo __('Colors', 'monitoring')."&nbsp;:";

      echo "</td>";
      $i = 0;
      $j = 0;

      $a_colors_warn = PluginMonitoringServicegraph::colors("warn");
      $a_colors_crit = PluginMonitoringServicegraph::colors("crit");
      $a_colors = PluginMonitoringServicegraph::colors();

      foreach ($a_list_val as $name) {
         if ($i == 'O'
                 AND $j == '1') {
            echo "<tr>";
         }
         echo "<td>";
         echo $name."&nbsp;:";
         echo "</td>";
         echo "<td>";

         $color = 'ffffff';
         if (isset($_SESSION['glpi_plugin_monitoring']['perfnamecolor'][$components_id][$name])) {
            $color = $_SESSION['glpi_plugin_monitoring']['perfnamecolor'][$components_id][$name];
         } else {
            if (strstr($name, "warn")) {
               $color = array_shift($a_colors_warn);
            } else if (strstr($name, "crit")) {
               $color = array_shift($a_colors_crit);
            } else {
               $color = array_shift($a_colors);
            }
         }

         echo ' <input class="color" id="color'.$name.'" name="perfnamecolor['.$name.']" value="'.$color.'" size="6" />';

         echo '<script type="text/javascript">
var myPicker = new jscolor.color(document.getElementById(\'color'.$name.'\'), {})
myPicker.fromString(\''.$color.'\')
</script>
';

//         echo " <select name='perfnamecolor[".$name."]' id='color".$name."'>";
//         echo "<option value=''>".Dropdown::EMPTY_VALUE."</option>";
//         foreach ($a_colors as $color) {
//            $checked = '';
//            if (isset($_SESSION['glpi_plugin_monitoring']['perfnamecolor'][$components_id][$name])
//                    AND $_SESSION['glpi_plugin_monitoring']['perfnamecolor'][$components_id][$name] == $color) {
//               $checked = 'selected';
//            }
//            echo "<option value='".$color."' style='background-color: #".$color.";' ".$checked.">".$color."</option>";
//         }
//         echo "</select>";
         echo "</td>";
         $i++;
         if ($i == 4) {
            $i = 0;
            echo "</tr>";
         }
         $j = 1;
      }
      if ($i != 4) {
         echo "<td colspan='".((4-$i) *2 )."'></td>";
         echo "</tr>";
      }

      echo "<tr>";
      echo "<td colspan='9' align='center'>";
      echo "<input type='hidden' name='id' value='".$components_id."'/>";
      echo "<input type='submit' name='updateperfdata' value=\"".__('Save')."\" class='submit'>";
      echo "</td>";
      echo "</tr>";
      echo "</table>";

      Html::closeForm();

      return true;
   }



   /**
    * Log when extra-debug is activated
    */
   static function logIfExtradebug($file, $message) {
      $config = new PluginMonitoringConfig();
      $config->getFromDB(1);
      if ($config->fields['extradebug']) {
         if (is_array($message)) {
            $message = print_r($message, TRUE);
         }
         Toolbox::logInFile($file, $message);
      }
   }

}

?>
