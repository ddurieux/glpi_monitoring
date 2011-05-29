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

define('GLPI_ROOT','../../..');
include (GLPI_ROOT."/inc/includes.php");

header("Content-Type: text/html; charset=UTF-8");
header_nocache();

checkCentralAccess();

// Make a select box
if ($_POST["idtable"] && class_exists($_POST["idtable"])) {
   $table = getTableForItemType($_POST["idtable"]);

   // Link to user for search only > normal users
   $link = "dropdownValue.php";

   $rand     = mt_rand();
   $use_ajax = false;

   if ($CFG_GLPI["use_ajax"] && countElementsInTable($table)>$CFG_GLPI["ajax_limit_count"]) {
      $use_ajax = true;
   }

   $paramsallitems = array('searchText'          => '__VALUE__',
                           'table'               => $table,
                           'itemtype'            => $_POST["idtable"],
                           'rand'                => $rand,
                           'myname'              => $_POST["myname"],
                           'displaywith'         => array('otherserial', 'serial'),
                           'display_emptychoice' => true);

   if (isset($_POST['value'])) {
      $paramsallitems['value'] = $_POST['value'];
   }
   if (isset($_POST['entity_restrict'])) {
      $paramsallitems['entity_restrict'] = $_POST['entity_restrict'];
   }
   if (isset($_POST['condition'])) {
      $paramsallitems['condition'] = stripslashes($_POST['condition']);
   }

   $pluginMonitoringHost = new PluginMonitoringHost();
   $classname = $_POST['idtable'];
   $class = new $classname;
   
   $a_list = $pluginMonitoringHost->find("`itemtype`='".$classname."'");
   $a_elements = array();
   foreach ($a_list as $data) {
      $class->getFromDB($data['items_id']);
      $a_elements[$data['id']] = $class->getName();
   }
   asort($a_elements);
   Dropdown::showFromArray($_POST['myname'], $a_elements);

}

?>