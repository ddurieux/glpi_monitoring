<?php

define('GLPI_ROOT','../../..');
include (GLPI_ROOT."/inc/includes.php");
header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

Session::checkLoginUser();

switch ($_POST['itemtype']) {

   case 'PluginMonitoringServicescatalog':
      Dropdown::show('PluginMonitoringServicescatalog', array('name'=>'items_id'));
      break;

   case 'PluginMonitoringComponentscatalog':
      Dropdown::show('PluginMonitoringComponentscatalog', array('name'=>'items_id'));
      break;

   case 'PluginMonitoringService':
      $rand = mt_rand();
      echo "<select name='itemtype' id='itemtype$rand'>";
      echo "<option value='0'>".Dropdown::EMPTY_VALUE."</option>";

//      $a_types =array();
      echo "<option value='Computer'>".Computer::getTypeName()."</option>";
      echo "<option value='NetworkEquipment'>".NetworkEquipment::getTypeName()."</option>";
      echo "</select>";

      $params = array('itemtype'        => '__VALUE__',
                      'entity_restrict' => $_POST['a_entities'],
                      'selectgraph' => '1',
//                      'current'         => $ID,
//                      'comments'        => $p['comments'],
//                      'myname'          => $p['name'],
                      'rand'            => $rand);

      Ajax::updateItemOnSelectEvent("itemtype$rand", "show_itemtype$rand",
                                  $CFG_GLPI["root_doc"]."/plugins/monitoring/ajax/dropdownServiceHostType.php",
                                  $params);

      echo "<span id='show_itemtype$rand'><input type='hidden' name='services_id[]' value='0'/></span>\n";

      
//      $rand = Dropdown::show('PluginMonitoringService', array('name'=>'items_id'));
//      $params = array('items_id'  => '__VALUE__',
//                'itemtype' => "PluginMonitoringService");
//
//      Ajax::updateItemOnSelectEvent("dropdown_items_id".$rand,"extra_infos",
//                                  $CFG_GLPI["root_doc"]."/plugins/monitoring/ajax/dropdownDisplayviewExtrainfos.php",
//                                  $params);
//      echo "<span id='extra_infos'></span>";
      break;

   case 'PluginMonitoringWeathermap':
      Dropdown::show('PluginMonitoringWeathermap', array('name'=>'items_id'));
      break;

   default:
      break;
   
}


?>
