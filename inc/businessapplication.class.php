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
      $pMonitoringHost_Service = new PluginMonitoringHost_Service();
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
            
            $a_brules = $pMonitoringBusinessrule->find("`plugin_monitoring_businessapplications_id`='".$data['id']."'");
            $state = array();
            $state['OK'] = 0;
            $state['WARNING'] = 0;
            $state['CRITICAL'] = 0;
            foreach ($a_brules as $brulesdata) {
               if ($brulesdata['itemtype'] == 'PluginMonitoringHost_Service') {
                  $pMonitoringHost_Service->getFromDB($brulesdata['items_id']);
                  switch($pMonitoringHost_Service->fields['state']) {

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
            }
            if ($state['CRITICAL'] > 0) {
               $color = 'red';
            } else if ($state['WARNING'] > 0) {
               $color = 'orange';
            } else {
               $color = 'green';
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
      $pMonitoringHost_Service = new PluginMonitoringHost_Service();
      
      $this->getFromDB($id);
      echo "<table class='tab_cadrehov'>";
      $a_brules = $pMonitoringBusinessrule->find("`plugin_monitoring_businessapplications_id`='".$id."'");
      $a_groups = array();
      foreach ($a_brules as $brulesdata) {
         $a_groups[$brulesdata['group']] = '';
      }
      
      echo "<tr class='tab_bg_1'>";
      echo "<th rowspan='".(count($a_brules) + (count($a_brules) + 1))."' width='200'>";
      echo $this->getName();
      echo "</th>";
      
      $i = 0;
      foreach($a_groups as $group=>$num) {
         $a_brulesg = $pMonitoringBusinessrule->find("`plugin_monitoring_businessapplications_id`='".$id."'
            AND `group`='".$group."'");

         if ($i > 0) {
            echo "<tr class='tab_bg_4'>";
         }
         echo "<th height='5' colspan='9'></th>";
         if ($i == '0') {
            echo "<th rowspan='".(count($a_brules) + (count($a_brules) + 1))."' width='1'></th>";
         }
         echo "</tr>";
         echo "<tr class='tab_bg_1'>";
         echo "<th rowspan='".count($a_brulesg)."'>Group ".$i;
         echo "</th>";
         $j = 0;
         foreach ($a_brulesg as $brulesdata) {
            if ($brulesdata['itemtype'] == 'PluginMonitoringHost_Service') {
               if ($j > 0) {
                  echo "<tr class='tab_bg_1'>";
               }
               $pMonitoringHost_Service->getFromDB($brulesdata['items_id']);
               PluginMonitoringDisplay::displayLine($pMonitoringHost_Service->fields, $brulesdata['itemtype']);
               echo "</tr>";
               $j++;
            }
         }         
         
         if ($j > 0) {
            echo "</tr>";
         }
         $i++;
      }
      echo "<tr class='tab_bg_4'>";
      echo "<th height='4' colspan='9'></th>";
      echo "</tr>";
      
      echo "</table>";
      
      $state = "OK";
      
      

      
   }
   
   
}

?>