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
   define('GLPI_ROOT', '../../..');
}

require_once GLPI_ROOT."/inc/includes.php";

Session::checkCentralAccess();

if (isset($_POST['updateaddremovetab'])) {
   if ($_POST['type'] == 'remove') {
      $_SESSION['glpi_plugin_monitoring']['displayonlytab'][$_POST['tabnum']] = 1;
      Html::redirect($CFG_GLPI["root_doc"]."/plugins/monitoring/front/displayfix.php?tab=".$_POST['tabnum']);
      
   } else if ($_POST['type'] == 'add') {
      if (isset($_SESSION['glpi_plugin_monitoring']['displayonlytab'][$_POST['tabnum']])) {
         unset($_SESSION['glpi_plugin_monitoring']['displayonlytab'][$_POST['tabnum']]);
      }
      Html::redirect($CFG_GLPI["root_doc"]."/plugins/monitoring/front/display.php");
   }
}

if (isset($_POST['sessionupdate'])) {
   $_SESSION['glpi_plugin_monitoring']['_refresh'] = $_POST['_refresh'];
   Html::back();
}

Html::header($LANG['plugin_monitoring']['title'][0], $_SERVER["PHP_SELF"], "plugins",
             "monitoring", "display");

PluginMonitoringDisplay::addRemoveTab('add', $_GET['tab']);

if ($_GET['tab'] < 5 ) {
   echo '<meta http-equiv ="refresh" content="'.$_SESSION['glpi_plugin_monitoring']['_refresh'].'">';
}
   
$pmMessage = new PluginMonitoringMessage();
$pmMessage->getMessages();

if ($_GET['tab'] < 5 ) {
   $pmDisplay = new PluginMonitoringDisplay();
   $pmDisplay->refreshPage();
}

if (isset($_SESSION['plugin_monitoring']['service'])) {
   unset($_SESSION['plugin_monitoring']['service']);
}
PluginMonitoringDisplay::displayTab($_GET['tab']);

Html::footer();
?>