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

function plugin_monitoring_giveItem($type,$id,$data,$num) {
   global $CFG_GLPI, $LANG;

   $searchopt = &Search::getOptions($type);
   $table = $searchopt[$id]["table"];
   $field = $searchopt[$id]["field"];
         
   switch ($table.'.'.$field) {

   }

   return "";
}



/* Cron */
function cron_plugin_monitoring() {
   return 1;
}



function plugin_monitoring_install() {
   global $DB, $LANG;

//   include (GLPI_ROOT . "/plugins/fusioninventory/install/update.php");
//   $version_detected = pluginFusioninventoryGetCurrentVersion(PLUGIN_FUSIONINVENTORY_VERSION);
//   if ((isset($version_detected)) AND ($version_detected != PLUGIN_FUSIONINVENTORY_VERSION)) {
//      pluginFusioninventoryUpdate($version_detected);
//   } else {
      include (GLPI_ROOT . "/plugins/monitoring/install/install.php");
      pluginMonitoringInstall(PLUGIN_MONITORING_VERSION);
//   }

   return true;
}

// Uninstall process for plugin : need to return true if succeeded
function plugin_monitoring_uninstall() {
//   if (!class_exists('PluginFusioninventorySetup')) { // if plugin is unactive
//      include(GLPI_ROOT . "/plugins/fusioninventory/inc/setup.class.php");
//   }
//   return PluginFusioninventorySetup::uninstall();
}

// Define headings added by the plugin //
function plugin_get_headings_monitoring($item,$withtemplate) {
   global $LANG;

   switch (get_class($item)) {
      case 'Computer' :
      case 'Device':
      case 'Printer':
      case 'NetworkEquipment':
         $array = array();
         $array[1] = $LANG['plugin_monitoring']['title'][0]."-".$LANG['plugin_monitoring']['host'][8];
         return $array;
         break;
      case 'User':
         $array = array();
         $array[1] = $LANG['plugin_monitoring']['title'][0]."-".$LANG['plugin_monitoring']['contact'][0];
         return $array;
         break;
   }

   return false;
}

// Define headings actions added by the plugin
//function plugin_headings_actions_fusioninventory($type) {
function plugin_headings_actions_monitoring($item) {
   switch (get_class($item)) {
      case 'Computer':
      case 'Device':
      case 'Printer':
      case 'NetworkEquipment':
         $array = array ();
         $array[1] = "plugin_headings_monitoring_hosts";
         return $array;
         break;
      case 'User':
         $array = array ();
         $array[1] = "plugin_headings_monitoring_contacts";
         return $array;
         break;
   }
   return false;
}


//function plugin_headings_fusioninventory_locks($type, $id) {
function plugin_headings_monitoring_hosts($item) {
   
   $pluginMonitoringHost = new PluginMonitoringHost();
   $pluginMonitoringHost->showForm('', array(), get_class($item));
   if ($pluginMonitoringHost->getField('id')
           AND $pluginMonitoringHost->getField('parenttype') == '1') {

      $pluginMonitoringHost_Host = new PluginMonitoringHost_Host();
      $pluginMonitoringHost_Host->manageDependencies($pluginMonitoringHost->getField('id'));
   }
   $pluginMonitoringHost_Contact = new PluginMonitoringHost_Contact();
   $pluginMonitoringHost_Contact->manageContacts($pluginMonitoringHost->getField('id'));
}


function plugin_headings_monitoring_contacts($item) {

   $pluginMonitoringContact = new PluginMonitoringContact();
   $pluginMonitoringContact->showForm('');
}


function plugin_headings_monitoring_tasks($item, $itemtype='', $items_id=0) {
 
}



function plugin_headings_monitoring($item, $withtemplate=0) {
	global $CFG_GLPI;

}



function plugin_monitoring_MassiveActions($type) {
   global $LANG;
   
   return array ();
}

function plugin_monitoring_MassiveActionsFieldsDisplay($options=array()) {
   global $LANG;

   return false;
}



function plugin_monitoring_MassiveActionsDisplay($options=array()) {
   global $LANG, $CFG_GLPI, $DB;

   return "";
}



function plugin_monitoring_MassiveActionsProcess($data) {
   global $LANG;

   
}


function plugin_monitoring_addSelect($type,$id,$num) {
	global $SEARCH_OPTION;

   $searchopt = &Search::getOptions($type);
   $table = $searchopt[$id]["table"];
   $field = $searchopt[$id]["field"];

   switch ($type) {

   }
   return "";
}


function plugin_monitoring_forceGroupBy($type) {
    return false;
}


function plugin_monitoring_addLeftJoin($itemtype,$ref_table,$new_table,$linkfield,&$already_link_tables) {

   switch ($itemtype) {
      
   }
   return "";
}


function plugin_monitoring_addOrderBy($type,$id,$order,$key=0) {
   return "";
}


function plugin_monitoring_addDefaultWhere($type) {

}


function plugin_monitoring_addWhere($link,$nott,$type,$id,$val) {
	global $SEARCH_OPTION;

   $searchopt = &Search::getOptions($type);
   $table = $searchopt[$id]["table"];
   $field = $searchopt[$id]["field"];

   switch ($type) {

   }
   return "";
}


/*
 * Webservices
 */
function plugin_monitoring_registerMethods() {
   global $WEBSERVICES_METHOD;

   $WEBSERVICES_METHOD['monitoring.test'] = array('PluginMonitoringWebservice',
                                                       'methodTest');
   $WEBSERVICES_METHOD['monitoring.shinken'] = array('PluginMonitoringWebservice',
                                                       'methodShinken');
}

?>