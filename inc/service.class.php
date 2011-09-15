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

class PluginMonitoringService extends CommonDBTM {
   
   /**
   * Get name of this type
   *
   *@return text name of this type by language of the user connected
   *
   **/
   static function getTypeName() {
      global $LANG;

      return $LANG['plugin_monitoring']['service'][0];
   }
   
   
   function initTemplates() {
      
      
      
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
    
      $tab['common'] = $LANG['plugin_monitoring']['service'][0];

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

      $ong[2] = $LANG['plugin_monitoring']['businessrule'][0]; 
      
      return $ong;
   }

   
   
   function maybeTemplate() {

      if (!isset($this->fields['id'])) {
         $this->getEmpty();
      }
      if (strstr($_SERVER['PHP_SELF'], 'service.form.php')) {
         return false;
      } else {
         return isset($this->fields['is_template']);
      }
   }

   
   
   /**
   * Display form for service configuration
   *
   * @param $items_id integer ID 
   * @param $options array
   *
   *@return bool true if form is ok
   *
   **/
   function showForm($items_id, $options=array(), $itemtype='') {
      global $DB,$CFG_GLPI,$LANG;

      if (isset($_GET['withtemplate']) AND ($_GET['withtemplate'] == '1')) {
         $options['withtemplate'] = 1;
      } else {
         $options['withtemplate'] = 0;
      }

      if ($items_id == '' AND $itemtype != '') {
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
         if (isset($_GET['items_id']) AND isset($_GET['itemtype'])) {
            $this->fields['itemtype'] = $_GET['itemtype'];
            $this->fields['items_id'] = $_GET['items_id'];         
         }
      }
      $template = false;

      $link = NOT_AVAILABLE;
      $type = $this->fields['itemtype'];
      if (class_exists($this->fields['itemtype'])) {
         $item = new $this->fields['itemtype']();
         $type = $item->getTypeName();

         if ($item->getFromDB($this->fields["items_id"])) {
            $link = $item->getLink();
         }
      }
      $this->showTabs($options);
      $this->showFormHeader($options);

      echo "<tr>";
      echo "<td>".$LANG['common'][16]."&nbsp;:</td>";
      echo "<td>";
      $objectName = autoName($this->fields["name"], "name", ($template === "newcomp"),
                             $this->getType());
      autocompletionTextField($this, 'name', array('value' => $objectName));      
      echo "</td>";
      echo "<td>";
      echo $type."&nbsp;:";
      echo "</td>";
      echo "<td>";
      echo "<input type='hidden' name='items_id' value='".$this->fields["items_id"]."'>\n";
      echo "<input type='hidden' name='itemtype' value='".$this->fields["itemtype"]."'>\n";
      echo $link;
      echo "</td>";
      echo "</tr>";
      
      echo "<tr>";
      echo "<th colspan='2'>".$LANG['common'][7]."&nbsp;:</th>";
      echo "<th colspan='2' width='50%'>";
      echo $LANG['plugin_monitoring']['service'][3]."&nbsp;:";
      echo "</th>";
      echo "</tr>";
      
      echo "<tr>";
      echo "<td colspan='2' align='center'>";
      Dropdown::show("PluginMonitoringService", array(
            'name' => 'template_link',
            'value' => $this->fields['template_link'],
            'auto_submit' => true,
            'condition' => '`is_template` = "1"'
      ));
      echo "</td>";
      echo "<td>".$LANG['plugin_monitoring']['command'][1]."&nbsp;:</td>";
      echo "<td>";
      Dropdown::show("PluginMonitoringCommand", array('name'=>'plugin_monitoring_commands_id',
                                                'value'=>$this->fields['plugin_monitoring_commands_id']));
      echo "</td>";
      echo "</tr>";
      
      echo "<tr>";
      echo "<td colspan='2' align='center'>";
      echo "</td>";
      echo "<td>".$LANG['plugin_monitoring']['service'][4]."&nbsp;:</td>";
      echo "<td>";
      $objectName = autoName($this->fields["arguments"], "arguments", ($template === "newcomp"),
                             $this->getType());
      autocompletionTextField($this, 'arguments', array('value' => $objectName));
      echo "</td>";
      echo "</tr>";
      
      echo "<tr>";
      echo "<td colspan='2' align='center'>";
      echo "</td>";
      echo "<td>".$LANG['plugin_monitoring']['check'][0]."&nbsp;:</td>";
      echo "<td align='center'>";
      Dropdown::show("PluginMonitoringCheck", array('name'=>'plugin_monitoring_checks_id',
                                                'value'=>$this->fields['plugin_monitoring_checks_id']));
      echo "</td>";
      echo "</tr>";
      
      echo "<tr>";
      echo "<td colspan='2' align='center'>";
      echo "</td>";
      echo "<td>".$LANG['plugin_monitoring']['host'][9]."&nbsp;:</td>";
      echo "<td align='center'>";
      dropdown::show("Calendar", array('name'=>'calendars_id',
                                 'value'=>$this->fields['calendars_id']));
      echo "</td>";
      echo "</tr>";

      $this->showFormButtons($options);
      $this->addDivForTabs();
      return true;
   }



   /**
    * Display services associated with host
    *
    * @param $itemtype value type of item
    * @param $items_id integer id of the object
    *
    **/
   function listByHost($itemtype, $items_id) {
      global $LANG;

      echo "<table class='tab_cadre'>";
      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo "<a href='".GLPI_ROOT."/plugins/monitoring/front/service.form.php?items_id=".$items_id."&itemtype=".$itemtype."'>".
         $LANG['plugin_monitoring']['service'][2]."</a>";
      echo "</td>";
      echo "</tr>";
      echo "</table>";
      echo "<br/>";      
      
      $pluginMonitoringCommand = new PluginMonitoringCommand();

      $start = 0;
      if (isset($_REQUEST["start"])) {
         $start = $_REQUEST["start"];
      }

      $a_list = $this->find("`itemtype`='".$itemtype."'
         AND `items_id`='".$items_id."'");

      $number = count($a_list);
      echo "<table class='tab_cadre' >";
      
      echo "<tr>";
      echo "<td colspan='7'>";
      printAjaxPager('',$start,$number);
      echo "</td>";
      echo "</tr>";

      echo "<tr>";
      echo "<th>".$LANG['common'][16]."</th>";
      echo "<th>".$LANG['common'][13]."</th>";
      echo "<th>".$LANG['plugin_monitoring']['command'][1]."</th>";
      echo "<th>".$LANG['plugin_monitoring']['service'][1]."</th>";
      echo "<th>check_interval</th>";
      echo "<th>Last check</th>";
      echo "<th>State</th>";
      echo "</tr>";

      foreach ($a_list as $data) {
         echo "<tr>";
         $template = "";
         if ($data['template_link'] > 0) {
            $this->getFromDB($data['template_link']);
            $template = $this->getLink(1);
            $data['name'] = $this->fields['name'];
            $data['plugin_monitoring_commands_id'] = $this->fields['plugin_monitoring_commands_id'];
            $data['criticity'] = $this->fields['criticity'];
            $data['check_interval'] = $this->fields['check_interval'];
         }
         $this->getFromDB($data['id']);
         echo "<td>";
         echo "<a href='". $this->getLinkURL()."'>".$this->getName()."</a>";
         echo "</td>";
         echo "<td>".$template."</td>";
         $pluginMonitoringCommand->getFromDB($data['plugin_monitoring_commands_id']);
         echo "<td>".$pluginMonitoringCommand->getLink(1)."</td>";
         echo "<td>".$data['criticity']."</td>";
         echo "<td>".$data['check_interval']."</td>";
         echo "<td>".$data['last_check']."</td>";
         echo "<td>".$data['event']."</td>";
         echo "</tr>";
      }
      echo "</table>";
   }
}

?>