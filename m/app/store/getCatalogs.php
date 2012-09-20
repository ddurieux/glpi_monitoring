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
      

      $a_json['catalogs'][] = array('title' => 
          "<table><tr><td><img src='".$CFG_GLPI['root_doc']."/plugins/monitoring/pics/box_".$currentState."_32.png'/></td><td> ".$data['name']." (".$nbstate."/".$nb_ressources." ressources)</td></tr></table>",
                                    'state' => $currentState,
                                    'ressources' => $nb_ressources);
   }
}

echo json_encode($a_json);
?>