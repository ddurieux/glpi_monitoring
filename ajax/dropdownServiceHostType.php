<?php
/*
 * @version $Id: dropdownConnectPortDeviceType.php 14684 2011-06-11 06:32:40Z remi $
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2011 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org
 -------------------------------------------------------------------------

 LICENSE

 This file is part of GLPI.

 GLPI is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 GLPI is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with GLPI; if not, write to the Free Software Foundation, Inc.,
 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 --------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: David Durieux
// Purpose of file:
// ----------------------------------------------------------------------

define('GLPI_ROOT', '../../..');
include (GLPI_ROOT . "/inc/includes.php");

header("Content-Type: text/html; charset=UTF-8");
header_nocache();

if (class_exists($_POST["itemtype"])) {
   $table = getTableForItemType($_POST["itemtype"]);
   

   $query = "SELECT `$table`.`name`, 
                    `".getTableForItemType("PluginMonitoringHost")."`.`id`
                        
             FROM `".getTableForItemType("PluginMonitoringHost")."`
             LEFT JOIN `$table` ON `$table`.`id` = `items_id`             
             WHERE `itemtype` = '".$_POST["itemtype"]."'
             ORDER BY `$table`.`name`";
   $result = $DB->query($query);
   $a_hosts = array();
   $a_hosts[0] = DROPDOWN_EMPTY_VALUE;
   while ($data = $DB->fetch_array($result)) {
      $a_hosts[$data['id']] = $data['name'];
   }
   $rand = Dropdown::showFromArray("hosts", $a_hosts);

   
   $params = array('hosts'           => '__VALUE__',
                   'entity_restrict' => $_POST["entity_restrict"],
                   'itemtype'        => $_POST['itemtype'],
                   'comments'        => $_POST['comments'],
                   'rand'            => $rand,
                   'myname'          => "items");

   ajaxUpdateItemOnSelectEvent("dropdown_hosts".$rand, "show_items$rand",
                               $CFG_GLPI["root_doc"]."/plugins/monitoring/ajax/dropdownServiceHost.php",
                               $params);

   echo "<span id='show_items$rand'><input type='hidden' name='services_id[]' value='0'/></span>";
}

?>