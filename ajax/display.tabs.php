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

define('GLPI_ROOT', '../../..');
include (GLPI_ROOT . "/inc/includes.php");

header("Content-Type: text/html; charset=UTF-8");
header_nocache();

if (!isset($_POST["id"])) {
   exit();
}
$pmDisplay = new PluginMonitoringDisplay();
$pmBusinessrule = new PluginMonitoringBusinessrule();

$pmDisplayview = new PluginMonitoringDisplayview();
$a_views = $pmDisplayview->getViews();

switch($_REQUEST['glpi_tab']) {
   case -1 :

      break;

   case 1 :
      $pmServicescatalog = new PluginMonitoringServicescatalog();
      $pmDisplay->displayCounters("Businessrules");
      $pmServicescatalog->showBAChecks();
      break;
   
   case 2:
      $pmComponentscatalog = new PluginMonitoringComponentscatalog();
      $pmDisplay->displayCounters("Componentscatalog");
      $pmComponentscatalog->showChecks();
      break;

   case 3:
      $pmDisplay->displayCounters("Ressources");
      // Manage search
      $_GET = $_SESSION['plugin_monitoring']['service'];
      if (isset($_GET['reset'])) {
         unset($_SESSION['glpisearch']['PluginMonitoringService']);
      }
      Search::manageGetValues("PluginMonitoringService");
      Search::showGenericSearch("PluginMonitoringService", $_SESSION['plugin_monitoring']['service']);

      $pmDisplay->showBoard(950);
      if (isset($_SESSION['glpisearch']['PluginMonitoringService']['reset'])) {
         unset($_SESSION['glpisearch']['PluginMonitoringService']['reset']);
      }
      break;

   case 4:
PluginMonitoringCanvas::onload();

$pmCanvas = new PluginMonitoringCanvas();
$pmCanvas->show();
      
      break;

   default :
      $i = 5;
      foreach ($a_views as $views_id=>$name) {
         if ($_REQUEST['glpi_tab'] == $i) {
            $pmDisplayview_item = new PluginMonitoringDisplayview_item();
            $pmDisplayview_item->view($views_id);
         }
         $i++;
      }
      break;

}

ajaxFooter();

?>
