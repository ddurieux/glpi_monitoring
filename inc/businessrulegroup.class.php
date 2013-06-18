<?php

/*
   ------------------------------------------------------------------------
   Plugin Monitoring for GLPI
   Copyright (C) 2011-2013 by the Plugin Monitoring for GLPI Development Team.

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
   @copyright Copyright (c) 2011-2013 Plugin Monitoring for GLPI team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      https://forge.indepnet.net/projects/monitoring/
   @since     2011
 
   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginMonitoringBusinessrulegroup extends CommonDBTM {
   
   
   static function getTypeName($nb=0) {
      return _n('Group', 'Groups', $nb, 'monitoring');
   }
   

   static function canCreate() {
      return PluginMonitoringProfile::haveRight("servicescatalog", 'w');
   }


   
   static function canView() {
      return PluginMonitoringProfile::haveRight("servicescatalog", 'r');
   }

   
   
   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {

      // can exists for template
      if (($item->getType() == 'PluginMonitoringServicescatalog')
          && $item->getID() > 0) {

         return self::createTabEntry(self::getTypeName(2), 0);
      }
      return '';
   }


   /**
    * @param $item            CommonGLPI object
    * @param $tabnum          (default 1)
    * @param $withtemplate    (default 0)
    */
   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {

      $pmBusinessrule = new PluginMonitoringBusinessrule();
      $pmBusinessrule->showForm($item->fields['id']);
      return true;
   }
   
   

   
   
   function showForm($items_id, $servicescatalogs_id, $options=array()) {
      global $CFG_GLPI;

      if ($items_id!='') {
         $this->getFromDB($items_id);
      } else {
         $this->getEmpty();
      }

      $this->showFormHeader($options);
      
      $rand = mt_rand();
      
      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo "<input type='hidden' name='plugin_monitoring_servicescatalogs_id' value='".$servicescatalogs_id."'/>";
      echo __('Name')."&nbsp;:";
      echo "</td>";
      echo "<td>";
      echo "<input type='text' name='name' value='".$this->fields["name"]."' size='30'/>";
      echo "</td>";
      if ($items_id!='') {
         echo "<th colspan='2' width='60%'>"; 
         echo __('Resources', 'monitoring');      
         echo "&nbsp;";
         echo "<img onClick=\"Ext.get('ressources".$rand."').setDisplayed('block')\"
                    title=\"".__('add')."\" alt=\"".__('add')."\"
                    class='pointer'  src='".$CFG_GLPI["root_doc"]."/pics/add_dropdown.png'>";
      
         echo "</th>";
         echo "</tr>";  

         echo "<tr>";
      }
      echo "<td valign='top'>";
      echo __('Logical operator')."&nbsp;:";
      echo "</td>";
      echo "<td valign='top'>";
      $first_operator = array();
      $first_operator['or'] = "or";
      $first_operator['2 of:'] = __('2 of', 'monitoring');
      $first_operator['3 of:'] = __('3 of', 'monitoring');
      $first_operator['4 of:'] = __('4 of', 'monitoring');
      $first_operator['5 of:'] = __('5 of', 'monitoring');
      $first_operator['6 of:'] = __('6 of', 'monitoring');
      $first_operator['7 of:'] = __('7 of', 'monitoring');
      $first_operator['8 of:'] = __('8 of', 'monitoring');
      $first_operator['9 of:'] = __('9 of', 'monitoring');
      $first_operator['10 of:'] = __('10 of', 'monitoring');
      Dropdown::showFromArray('operator', $first_operator, array("value"=>$this->fields['operator']));
      echo "</td>";
      if ($items_id!='') {
         echo "<td colspan='2'>";
         // ** Dropdown to display
            echo "<div style='display:none' id='ressources".$rand."' >";
            echo "<table>";
            echo "<tr class='tab_bg_1'>";
            echo "<td>";
            echo "<form name='form' method='post' action='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/businessrule.form.php'>";
            echo "<input type='hidden' name='plugin_monitoring_businessrulegroups_id' value='".$items_id."' />";
            PluginMonitoringBusinessrule::dropdownService(0, array('name' => 'type'));         
            echo "<input type='submit' name='add' value=\"".__('Add')."\" class='submit'>";
            Html::closeForm();
            echo "</td>";
            echo "</tr>";
            echo "</table>";
            echo "<hr>";
            echo "</div>";


            echo "<table width='100%'>";
         $pmBusinessrule = new PluginMonitoringBusinessrule();
         $pmService = new PluginMonitoringService();
         $a_services = $pmBusinessrule->find("`plugin_monitoring_businessrulegroups_id`='".$items_id."'");
         foreach ($a_services as $gdata) {
            if ($pmService->getFromDB($gdata['plugin_monitoring_services_id'])) {

               $shortstate = PluginMonitoringDisplay::getState($pmService->fields['state'], 
                                                               $pmService->fields['state_type'],
                                                               '',
                                                               $pmService->fields['is_acknowledged']);

               echo "<tr class='tab_bg_1'>";
               echo "<td>";
               echo "<img src='".$CFG_GLPI['root_doc']."/plugins/monitoring/pics/box_".$shortstate."_32.png'/>";
               echo "</td>";
               echo "<td>";
               $pmComponentscatalog_Host = new PluginMonitoringComponentscatalog_Host();
               $pmService->getFromDB($gdata["plugin_monitoring_services_id"]);
               $pmComponentscatalog_Host->getFromDB($pmService->fields['plugin_monitoring_componentscatalogs_hosts_id']);
               echo $pmService->getLink(1);
               echo " ".__('on', 'monitoring')." ";
               $itemtype2 = $pmComponentscatalog_Host->fields['itemtype'];
               $item2 = new $itemtype2();
               $item2->getFromDB($pmComponentscatalog_Host->fields['items_id']);
               echo $item2->getLink(1);
               echo "</td>";
               echo "<td>";
               echo "<input type='submit' name='deletebusinessrules-".$gdata['id']."' value=\""._sx('button', 'Delete permanently')."\" class='submit'>";
               echo "</td>";
            } else {
               // resource deleted
               echo "<tr class='tab_bg_1'>";
               echo "<td colspan='2' bgcolor='#ff0000'>";
               echo __('Resource deleted', 'monitoring');
               echo "</td>";
               echo "<td>";
               echo "<input type='submit' name='deletebusinessrules-".$gdata['id']."' value=\"".__('Clean')."\" class='submit'>";
               echo "</td>";
            }
         }
         echo "</tr>";
      }  
      echo "</table>";
      
      echo "</td>";      
      echo "</tr>";  

      $this->showFormButtons($options);

      return true;
   }
}

?>