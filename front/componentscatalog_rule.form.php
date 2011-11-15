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
             "monitoring", "checks");

if (isset($_POST['itemtypen'])) {
   $_POST['itemtype'] = $_POST['itemtypen'];
}

$pmComponentscatalog_rule = new PluginMonitoringComponentscatalog_rule();

if (isset($_GET['addrule'])) {
   if (!isset($_GET['contains'])
        AND !isset($_GET['reset'])) {
//      $_SESSION['plugin_monitoring_rules'] = $_POST;
         } else {
      $_POST = $_GET;
      $input = array();
      $input['entities_id'] = $_POST['entities_id'];
      $input['is_recursive'] = $_POST['is_recursive'];
      $input['name'] = $_POST['name'];
      $input['itemtype'] = $_POST['itemtype'];
      $input['plugin_monitoring_componentscalalog_id'] = $_POST['plugin_monitoring_componentscalalog_id'];
      unset($_POST['entities_id']);
      unset($_POST['is_recursive']);
      unset($_POST['name']);
      unset($_POST['addrule']);
      unset($_POST['itemtypen']);
      unset($_POST['plugin_monitoring_componentscalalog_id']);
      $input['condition'] = exportArrayToDB($_POST);
      $rules_id = $pmComponentscatalog_rule->add($input);
      unset($_SESSION['plugin_monitoring_rules']);
      $pmComponentscatalog_rule->getItemsDynamicly($rules_id);
      glpi_header($CFG_GLPI['root_doc']."/plugins/monitoring/front/componentscatalog.form.php?id=".$_POST['plugin_monitoring_componentscalalog_id']);

   }
} else if (isset($_GET['contains'])
        OR isset($_GET['reset'])) {
//   if (isset($_SESSION['plugin_monitoring_rules'])) {
//      unset($_SESSION['plugin_monitoring_rules']);
//   }
//   $_SESSION['plugin_monitoring_rules'] = $_POST;
//   $_SESSION['plugin_monitoring_rules_REQUEST_URI'] = $_SERVER['REQUEST_URI'];
   //glpi_header($_SERVER['HTTP_REFERER']);
}

if (isset($_POST['name'])) {      
   $a_construct = array();
   foreach ($_POST as $key=>$value) {
      $a_construct[] = $key."=".$value;
   }
   $_SERVER['REQUEST_URI'] = $_SERVER['REQUEST_URI']."?".implode("&", $a_construct);
   glpi_header($_SERVER['REQUEST_URI']);
}

$pmComponentscatalog_rule->addRule();

commonFooter();

?>