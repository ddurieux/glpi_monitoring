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

class PluginMonitoringComponentscatalog extends CommonDropdown {
   
   /**
   * Get name of this type
   *
   *@return text name of this type by language of the user connected
   *
   **/
   static function getTypeName() {
      global $LANG;

      return $LANG['plugin_monitoring']['componentscatalog'][0];
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
   
   
   
   function defineTabs($options=array()){
      global $LANG;

      $ong = array();
      
      if ($_GET['id'] > 0) {
         $ong[1] = $LANG['plugin_monitoring']['component'][0];
         $ong[2] = $LANG['plugin_monitoring']['component'][3];
         $ong[3] = $LANG['rulesengine'][17];
         $ong[4] = $LANG['plugin_monitoring']['component'][4];
         $ong[5] = $LANG['plugin_monitoring']['contact'][20];
      }
      
      return $ong;
   }
   
   
   
   function showChecks() {
      global $DB,$CFG_GLPI,$LANG;
      
      $pmService = new PluginMonitoringService();
      $pmComponentscatalog_Host = new PluginMonitoringComponentscatalog_Host();
      
      echo "<table class='tab_cadre' width='100%'>";
      echo "<tr class='tab_bg_4' style='background: #cececc;'>";
      
      $a_componentscatalogs = $this->find();
      $i = 0;
      foreach ($a_componentscatalogs as $data) {
         echo "<td>";

         echo "<table  class='tab_cadre_fixe' style='width:158px;'>";
         echo "<tr class='tab_bg_1'>";
         echo "<th colspan='2' style='font-size:18px;' height='60'>";
         echo $data['name'];
         echo "</th>";
         echo "</tr>";
         
         $stateg = array();
         $stateg['OK'] = 0;
         $stateg['WARNING'] = 0;
         $stateg['CRITICAL'] = 0;
         $a_gstate = array();
         $nb_ressources = 0;
         $query = "SELECT * FROM `".$pmComponentscatalog_Host->getTable()."`
            WHERE `plugin_monitoring_componentscalalog_id`='".$data['id']."'";
         $result = $DB->query($query);
         while ($dataComponentscatalog_Host=$DB->fetch_array($result)) {
            $queryService = "SELECT * FROM `".$pmService->getTable()."`
               WHERE `plugin_monitoring_componentscatalogs_hosts_id`='".$dataComponentscatalog_Host['id']."'";
            $resultService = $DB->query($queryService);
            while ($dataService=$DB->fetch_array($resultService)) {
               $nb_ressources++;
               $state = array();
               $state['OK'] = 0;
               $state['WARNING'] = 0;
               $state['CRITICAL'] = 0;
               if ($dataService['state_type'] != "HARD") {
                  $a_gstate[$dataService['id']] = "OK";
               } else {
                  switch($dataService['state']) {

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
                  if ($state['CRITICAL'] >= 1) {
                     $a_gstate[$dataService['id']] = "CRITICAL";
                  } else if ($state['WARNING'] >= 1) {
                     $a_gstate[$dataService['id']] = "WARNING";
                  } else {
                     $a_gstate[$dataService['id']] = "OK";
                  }
               }
            }
         }
         foreach ($a_gstate as $value) {
            $stateg[$value]++;
         }
         echo "<tr class='tab_bg_1'>";
         echo "<td>";
         echo $LANG['plugin_monitoring']['service'][0]."&nbsp;:";
         echo "</td>";
         echo "<th align='center' height='40' width='50%'>";
         echo $nb_ressources;
         echo "</th>";
         echo "</tr>";
         
         $background = '';
         $count = 0;
         if ($stateg['CRITICAL'] > 0) {
            $count = $stateg['CRITICAL'];
            $background = 'background="'.$CFG_GLPI['root_doc'].'/plugins/monitoring/pics/bg_critical.png"';
         } else if ($stateg['WARNING'] > 0) {
            $count = $stateg['WARNING'];
            $background = 'background="'.$CFG_GLPI['root_doc'].'/plugins/monitoring/pics/bg_warning.png"';
         } else if ($stateg['OK'] > 0) {
            $count = $stateg['OK'];
            $background = 'background="'.$CFG_GLPI['root_doc'].'/plugins/monitoring/pics/bg_ok.png"';
         }
         echo "<tr ".$background.">";
         echo "<th style='background-color:transparent;' colspan='2' height='100'>";
         echo "<font style='font-size: 52px;'>".$count."</font>";         
         echo "</th>";
         echo "</tr>";

         echo "</table>";

         echo "</td>";
         
         $i++;
         if ($i == '6') {
            echo "</tr>";
            echo "<tr class='tab_bg_4' style='background: #cececc;'>";
            $i = 0;
         }
      }      
      
      echo "</tr>";
      echo "</table>";      
   }
  
   
}

?>