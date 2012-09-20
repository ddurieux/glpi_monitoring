<?php

// Direct access to file
if (strpos($_SERVER['PHP_SELF'],"getServices.php")) {
   define('GLPI_ROOT','../../../../..');
   include (GLPI_ROOT."/inc/includes.php");
   header("Content-Type: text/html; charset=UTF-8");
   Html::header_nocache();
}

if (!defined('GLPI_ROOT')) {
   die("Can not acces directly to this file");
}

Session::checkLoginUser();
$a_json = array();
$a_json['services'] = array();

$pmComponentscatalog_Host = new PluginMonitoringComponentscatalog_Host();
$networkPort = new NetworkPort();
$pMonitoringService = new PluginMonitoringService();

$query = "SELECT * FROM `glpi_plugin_monitoring_services`
         ORDER BY `name`";
$result = $DB->query($query);
while ($data=$DB->fetch_array($result)) {
   $state = PluginMonitoringDisplay::getState($data['state'], $data['state_type']);
   
   $host = '';
   $pmComponentscatalog_Host->getFromDB($data["plugin_monitoring_componentscatalogs_hosts_id"]);
   if (isset($pmComponentscatalog_Host->fields['itemtype']) 
           AND $pmComponentscatalog_Host->fields['itemtype'] != '') {

      $itemtype = $pmComponentscatalog_Host->fields['itemtype'];
      $item = new $itemtype();
      $item->getFromDB($pmComponentscatalog_Host->fields['items_id']);
      $host .= " [".$item->fields['name'];
//      if (!is_null($pMonitoringService->fields['networkports_id'])
//              AND $pMonitoringService->fields['networkports_id'] > 0) {
//         $networkPort->getFromDB($pMonitoringService->fields['networkports_id']);
//         $host .= " (".$networkPort->getLink().")";
//      }
      $host .= "]";
   }
   $statewanted = 1;
   if (isset($_GET['state'])
           AND $_GET['state'] != $state) {
      $statewanted = 0;
   }
   
   if ($statewanted == 1) {
      $a_json['services'][] = array('title' => 
          "<table><tr><td><img src='".$CFG_GLPI['root_doc']."/plugins/monitoring/pics/box_".$state."_32.png'/></td><td> ".$data['name'].$host."</td></tr></table>",
                                   'content' => "<iframe src ='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/display.form.php?itemtype=PluginMonitoringService&items_id=".$data['id']."&mobile=1' width='310' height='1030' frameborder='0'></iframe>",
                                   'state' => $state,
                                   'date' => $data['last_check'],
                                   'event' => $data['event']);
   }
}


echo json_encode($a_json);
?>