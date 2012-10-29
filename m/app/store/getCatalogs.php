<?php

// Direct access to file
if (strpos($_SERVER['PHP_SELF'],"getCatalogs.php")) {
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
$a_json['catalogs'] = array();


$pmComponentscatalog = new PluginMonitoringComponentscatalog();
$pmComponentscatalog_Host = new PluginMonitoringComponentscatalog_Host();
$a_componentscatalogs = $pmComponentscatalog->find("", "`name`");

foreach ($a_componentscatalogs as $data) {
   $ret = $pmComponentscatalog->getInfoOfCatalog($data['id']);
   $nb_ressources = $ret[0];
   $stateg = $ret[1];
   
   $currentState = 'green';
   $nbstate = $stateg['OK'];
   if ($stateg['CRITICAL'] > 0) {
      $currentState = 'red';
      $nbstate = $stateg['CRITICAL'];
   } else if ($stateg['WARNING'] > 0) {
      $currentState = 'orange';
      $nbstate = $stateg['WARNING'];
   }

   if (isset($_GET['state'])
           AND $_GET['state'] == $currentState) {
      
      $a_services = "<table>";
      if ($currentState != 'green') {
         $a_services_crit = $pmComponentscatalog->getRessources($data['id'], $currentState);
         foreach ($a_services_crit as $data) {
            $a_services .= "<tr>";
            $a_services .= "<td>";
            $a_services .= "<img src='".$CFG_GLPI['root_doc']."/plugins/monitoring/pics/box_".$currentState."_32.png'/>";
            $a_services .= "</td>";
            $a_services .= "<td colspan='2'>";
            
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
            
            $a_services .= $data['name'].$host;
            $a_services .= "</td>";
            $a_services .= "</tr>";

            $a_services .= "<tr>";
            $a_services .= "<td>";
            $a_services .= "</td>";
            $a_services .= "<td>";
            $a_services .= "Date (last event):";
            $a_services .= "</td>";
            $a_services .= "<td>";
            $a_services .= $data['last_check'];
            $a_services .= "</td>";
            $a_services .= "</tr>";

            $a_services .= "<tr>";
            $a_services .= "<td>";
            $a_services .= "</td>";
            $a_services .= "<td>";
            $a_services .= "Event:";
            $a_services .= "</td>";
            $a_services .= "<td>";
            $a_services .= $data['event'];
            $a_services .= "</td>";
            $a_services .= "</tr>";
            
            $a_services .= "<tr>";
            $a_services .= "<td colspan='3' bgcolor='#616161' style='height:1px'>";
            $a_services .= "</td>";
            $a_services .= "</tr>";
         }
      }
      $a_services .= "</table>";
      
      $a_json['catalogs'][] = array('title' => 
          "<table><tr><td><img src='".$CFG_GLPI['root_doc']."/plugins/monitoring/pics/box_".$currentState."_32.png'/></td><td> ".$data['name']." (".$nbstate."/".$nb_ressources." ressources)</td></tr></table>",
                                    'state' => $currentState,
                                    'ressources' => $nb_ressources,
                                    'content' => $a_services);
   }
}

echo json_encode($a_json);
?>