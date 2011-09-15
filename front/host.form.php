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

define('GLPI_ROOT', '../../..');

include (GLPI_ROOT . "/inc/includes.php");

commonHeader($LANG['plugin_monitoring']['title'][0],$_SERVER["PHP_SELF"], "plugins",
             "monitoring", "host");

$pluginMonitoringHost = new PluginMonitoringHost();
if (isset($_POST["add"])) {
   if (((isset($_POST['items_id']) AND $_POST['items_id'] != "0") AND ($_POST['items_id'] != ""))
         OR (isset($_POST['is_template']) AND ($_POST['is_template'] == "1"))) {

      if (isset($_POST['template_id']) AND $_POST['template_id'] > 0) {
         $pluginMonitoringHost->getFromDB($_POST['template_id']);
         $_POST['parenttype'] = $pluginMonitoringHost->fields['parenttype'];
         $_POST['plugin_monitoring_commands_id'] = $pluginMonitoringHost->fields['plugin_monitoring_commands_id'];
         $_POST['plugin_monitoring_checks_id'] = $pluginMonitoringHost->fields['plugin_monitoring_checks_id'];
         $_POST['active_checks_enabled'] = $pluginMonitoringHost->fields['active_checks_enabled'];
         $_POST['passive_checks_enabled'] = $pluginMonitoringHost->fields['passive_checks_enabled'];
         $_POST['calendars_id'] = $pluginMonitoringHost->fields['calendars_id'];
      }

      $hosts_id = $pluginMonitoringHost->add($_POST);

      if (isset($_POST['template_id']) AND $_POST['template_id'] > 0) {
         // Add parents
         $pluginMonitoringHost_Host = new PluginMonitoringHost_Host();
         $a_list = $pluginMonitoringHost_Host->find("`plugin_monitoring_hosts_id_1`='".$_POST['template_id']."'");
         foreach ($a_list as $data) {
            $input = array();
            $input['plugin_monitoring_hosts_id_1'] = $hosts_id;
            $input['plugin_monitoring_hosts_id_2'] = $data['plugin_monitoring_hosts_id_2'];
            $pluginMonitoringHost_Host->add($input);
         }

         // Add contacts
         $pluginMonitoringHost_Contact = new PluginMonitoringHost_Contact();
         $a_list = $pluginMonitoringHost_Contact->find("`plugin_monitoring_hosts_id`='".$_POST['template_id']."'");
         foreach ($a_list as $data) {
            $input = array();
            $input['plugin_monitoring_hosts_id'] = $hosts_id;
            $input['plugin_monitoring_contacts_id'] = $data['plugin_monitoring_contacts_id'];
            $pluginMonitoringHost_Contact->add($input);
         }         
      }
      if (isset($_POST['is_template']) AND ($_POST['is_template'] == "1")) {
         glpi_header($_SERVER['HTTP_REFERER']."&id=".$hosts_id);
      }
   }
   glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset ($_POST["update"])) {

   if ($_POST['parenttype'] == '0' OR $_POST['parenttype'] == '2') {
      $_POST['parents'] = "";
   }
   $pluginMonitoringHost->update($_POST);
   glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset ($_POST["delete"])) {
   $pluginMonitoringHost->delete($_POST, 1);
   glpi_header($_SERVER['HTTP_REFERER']);
}

if (isset($_GET["id"])) {
   $pluginMonitoringHost->showForm($_GET["id"]);
} else {
   $pluginMonitoringHost->showForm("");
}

commonFooter();

?>