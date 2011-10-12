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

class PluginMonitoringBusinessapplication extends CommonDropdown {
   
   /**
   * Get name of this type
   *
   *@return text name of this type by language of the user connected
   *
   **/
   static function getTypeName() {
      global $LANG;

      return "Business application";
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

   
   function showBAChecks() {
      global $CFG_GLPI,$LANG;
      
      $pMonitoringBusinessrule = new PluginMonitoringBusinessrule();
      $pMonitoringBusinessrulegroup = new PluginMonitoringBusinessrulegroup();
      $pMonitoringService = new PluginMonitoringService();
      echo "<table class='tab_cadre' width='100%'>";
      echo "<tr class='tab_bg_4' style='background: #cececc;'>";
      
      $a_ba = $this->find();
      foreach ($a_ba as $data) {
         echo "<td>";

         echo "<table  class='tab_cadre_fixe' style='width:200px;height:200px'>";
         echo "<tr class='tab_bg_1'>";
         echo "<th colspan='2' style='font-size:20px;' height='50'>";
         echo $data['name'];
         echo "</th>";
         echo "</tr>";

         echo "<tr class='tab_bg_1'>";
         echo "<td>";
         echo $LANG['state'][0]."&nbsp;:";
         echo "</td>";
         echo "<td width='40'>";
         switch($data['state']) {

            case 'UP':
            case 'OK':
               echo "<img src='".$CFG_GLPI['root_doc']."/plugins/monitoring/pics/box_green_40.png'/>";
               break;

            case 'DOWN':
            case 'UNREACHABLE':
            case 'CRITICAL':
            case 'DOWNTIME':
               echo "<img src='".$CFG_GLPI['root_doc']."/plugins/monitoring/pics/box_red_40.png'/>";
               break;

            case 'WARNING':
            case 'UNKNOWN':
            case 'RECOVERY':
            case 'FLAPPING':
            case '':
               echo "<img src='".$CFG_GLPI['root_doc']."/plugins/monitoring/pics/box_orange_40.png'/>";
               break;

         }
         echo "</td>";
         echo "</tr>";

         echo "<tr class='tab_bg_1'>";
         echo "<td>";
         echo "Mode dégradé&nbsp;:";
         echo "</td>";
         echo "<td width='40' align='center'>";
         $a_group = $pMonitoringBusinessrulegroup->find("`plugin_monitoring_businessapplications_id`='".$data['id']."'");
         $a_gstate = array();
         foreach ($a_group as $gdata) {
            $a_brules = $pMonitoringBusinessrule->find("`plugin_monitoring_businessrulegroups_id`='".$gdata['id']."'");
            $state = array();
            $state['OK'] = 0;
            $state['WARNING'] = 0;
            $state['CRITICAL'] = 0;
            foreach ($a_brules as $brulesdata) {
               $pMonitoringService->getFromDB($brulesdata['plugin_monitoring_services_id']);
               switch($pMonitoringService->fields['state']) {

                  case 'UP':
                  case 'OK':
                     $state['OK']++;
                     break;

                  case 'DOWN':
                  case 'UNREACHABLE':
                  case 'CRITICAL':
                  case 'DOWNTIME':
                     $state['CRITICAL']++;
                     break;

                  case 'WARNING':
                  case 'UNKNOWN':
                  case 'RECOVERY':
                  case 'FLAPPING':
                     $state['WARNING']++;
                     break;

               }
            }
            if ($gdata['operator'] == 'or') {
               if ($state['OK'] >= 1) {
                  $a_gstate[$gdata['id']] = "OK";
               } else if ($state['WARNING'] >= 1) {
                  $a_gstate[$gdata['id']] = "WARNING";
               } else {
                  $a_gstate[$gdata['id']] = "CRITICAL";
               }            
            } else {
               $num_min = str_replace(" of:", "", $gdata['operator']);
               if ($state['OK'] >= $num_min) {
                  $a_gstate[$gdata['id']] = "OK";
               } else if ($state['WARNING'] >= $num_min) {
                  $a_gstate[$gdata['id']] = "WARNING";
               } else {
                  $a_gstate[$gdata['id']] = "CRITICAL";
               } 
            }
//            
//            
//            
//            if ($state['CRITICAL'] > 0) {
//               $a_gstate[$gdata['id']] = "CRITICAL";
//            } else if ($state['WARNING'] > 0) {
//               $a_gstate[$gdata['id']] = "WARNING";
//            } else {
//               $a_gstate[$gdata['id']] = "OK";
//            }
         }
         $state = array();
         $state['OK'] = 0;
         $state['WARNING'] = 0;
         $state['CRITICAL'] = 0;
         foreach ($a_gstate as $value) {
            $state[$value]++;
         }
         $color = 'green';
         if ($state['CRITICAL'] > 0) {
            $color = 'red';
         } else if ($state['WARNING'] > 0) {
            $color = 'orange';
         }
         echo "<img src='".$CFG_GLPI['root_doc']."/plugins/monitoring/pics/box_".$color."_32.png' />";
         echo "</td>";
         echo "</tr>";

         echo "<tr class='tab_bg_1'>";
         echo "<td colspan='2' align='center'>";
         echo "<a href='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/businessapplication.form.php?id=".$data['id']."&detail=1'>Détail</a>";
         echo "</td>";
         echo "</tr>";

         echo "</table>";

         echo "</td>";
      }      
      
      echo "</tr>";
      echo "</table>";      
   }
   
   
   function showBADetail($id) {
      global $CFG_GLPI,$LANG;
      
      $pMonitoringBusinessrule = new PluginMonitoringBusinessrule();
      $pMonitoringBusinessrulegroup = new PluginMonitoringBusinessrulegroup();
      $pMonitoringService = new PluginMonitoringService();
      
      $this->getFromDB($id);
      echo "<table class='tab_cadrehov'>";
      $a_groups = $pMonitoringBusinessrulegroup->find("`plugin_monitoring_businessapplications_id`='".$id."'");
      
      echo "<tr class='tab_bg_1'>";
      
      $color = PluginMonitoringDisplay::getState($this->fields['state'], $this->fields['state_type']);
      $pic = $color;
      $color = str_replace("_soft", "", $color);
      
      echo "<td rowspan='".count($a_groups)."' class='center' width='200' bgcolor='".$color."'>";
      echo "<strong style='font-size: 20px'>".$this->getName()."</strong><br/>";
      echo "<img src='".$CFG_GLPI['root_doc']."/plugins/monitoring/pics/box_".$pic."_40.png'/>";
      echo "</td>";
      
      $i = 0;
      foreach($a_groups as $gdata) {
         $a_brulesg = $pMonitoringBusinessrule->find("`plugin_monitoring_businessrulegroups_id`='".$gdata['id']."'");

         if ($i > 0) {
            echo "<tr>";
         }
         
         $state = array();
         $state['red'] = 0;
         $state['red_soft'] = 0;
         $state['orange'] = 0;
         $state['orange_soft'] = 0;
         $state['green'] = 0;
         $state['green_soft'] = 0;
         foreach ($a_brulesg as $brulesdata) {
            $pMonitoringService->getFromDB($brulesdata['plugin_monitoring_services_id']);
            $state[PluginMonitoringDisplay::getState($pMonitoringService->fields['state'], $pMonitoringService->fields['state_type'])]++;
         }
         $color = "";
         if ($gdata['operator'] == 'or') {
            if ($state['green'] >= 1) {
               $color = "green";
            } else if ($state['orange'] >= 1) {
               $color = "orange";
            } else if ($state['orange_soft'] >= 1) {
               $color = "orange";
            } else if ($state['red'] >= 1) {
               $color = "red";
            } else if ($state['red_soft'] >= 1) {
               $color = "red";
            }            
         } else {
            $num_min = str_replace(" of:", "", $gdata['operator']);
            if ($state['green'] >= $num_min) {
               $color = "green";
            } else if ($state['orange'] >= $num_min) {
               $color = "orange";
            } else if ($state['orange_soft'] >= $num_min) {
               $color = "orange";
            } else if ($state['red'] >= $num_min) {
               $color = "red";
            } else if ($state['red_soft'] >= $num_min) {
               $color = "red";
            } 
         }
         
         echo "<td class='center' bgcolor='".$color."'>";
         echo $gdata['name']."<br/>[ ".$gdata['operator']." ]";
         echo "</td>";
         echo "<td bgcolor='".$color."'>";
            echo "<table>";
            foreach ($a_brulesg as $brulesdata) {
               echo "<tr class='tab_bg_1'>";
               $pMonitoringService->getFromDB($brulesdata['plugin_monitoring_services_id']);
               PluginMonitoringDisplay::displayLine($pMonitoringService->fields);
              echo "</tr>";
            }
            echo "</table>";
         echo "</th>";
         echo "</tr>";
         $i++;
      }
      echo "</tr>";
      
      echo "</table>";
      
      $state = "OK";
      
      

      
   }
   
   
}

?>