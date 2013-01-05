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

if (in_array('--help', $_SERVER['argv'])) {
   die("usage: ".$_SERVER['argv'][0]." [ --optimize ]\n");
}

chdir(dirname($_SERVER["SCRIPT_FILENAME"]));

if (!defined('GLPI_ROOT')) {
   define('GLPI_ROOT', '../../..');
}

include (GLPI_ROOT . "/inc/includes.php");

// Init debug variable
$_SESSION['glpi_use_mode'] = Session::DEBUG_MODE;
$_SESSION['glpilanguage']  = "en_GB";

Session::loadLanguage();

// Only show errors
$CFG_GLPI["debug_sql"]        = $CFG_GLPI["debug_vars"] = 0;
$CFG_GLPI["use_log_in_files"] = 1;
ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);
//set_error_handler('userErrorHandlerDebug');

$DB = new DB();
if (!$DB->connected) {
   die("No DB connection\n");
}

/* ----------------------------------------------------------------- */
/**
 * Extends class Migration to redefine display mode
**/
class CliMigration extends Migration {

   function __construct($ver) {
      $this->deb     = time();
      $this->version = $ver;
   }


   function displayMessage ($msg) {

      $msg .= " (".Html::timestampToString(time()-$this->deb).")";
      echo str_pad($msg, 100)."\r";
   }


   function displayTitle($title) {
      echo "\n".str_pad(" $title ", 100, '=', STR_PAD_BOTH)."\n";
   }


   function displayWarning($msg, $red=false) {

      if ($red) {
         $msg = "** $msg";
      }
      echo str_pad($msg, 100)."\n";
   }
}

/*---------------------------------------------------------------------*/

if (!TableExists("glpi_configs")) {
   die("GLPI not installed\n");
}

$plugin = new Plugin();
   
if (!isset($_SERVER['argv'][1])) {

   include (GLPI_ROOT . "/plugins/monitoring/install/update.php");
   include (GLPI_ROOT . "/plugins/monitoring/locales/en_GB.php");
   $current_version = pluginMonitoringGetCurrentVersion(PLUGIN_MONITORING_VERSION);

   $migration = new CliMigration($current_version);

   if (!isset($current_version)) {
      $current_version = 0;
   }
   if ($current_version == '0') {
      $migration->displayWarning("***** Install process of plugin MONITORING *****");
   } else {
      $migration->displayWarning("***** Update process of plugin MONITORING *****");
   }

   $migration->displayWarning("Current Monitoring version: $current_version");
   $migration->displayWarning("Version to update: ".PLUGIN_MONITORING_VERSION);

   // To prevent problem of execution time
   ini_set("max_execution_time", "0");

   if (($current_version != PLUGIN_MONITORING_VERSION)
        AND $current_version!='0') {
      pluginMonitoringUpdate($current_version, $migration);
      $migration->displayWarning("Update done.");
   } else if ($current_version == PLUGIN_MONITORING_VERSION) {
      $migration->displayWarning("No migration needed.");
   } else {
      include (GLPI_ROOT . "/plugins/monitoring/install/install.php");
      pluginMonitoringInstall(PLUGIN_MONITORING_VERSION, $migration);
      $migration->displayWarning("installation done.");
   }

   $plugin->getFromDBbyDir("monitoring");
   $plugin->load("monitoring");
   $plugin->activate($plugin->fields['id']);
   $plugin->load("monitoring");
}


if (in_array('--optimize', $_SERVER['argv'])) {

   $migration->displayTitle(__('Optimizing tables'));
   DBmysql::optimize_tables($migration);

   $migration->displayWarning("Optimize done.");
}
