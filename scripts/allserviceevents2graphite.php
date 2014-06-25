<?php
error_reporting (E_ALL);

require("../../../inc/includes.php");

$server = "localhost";
$port = "2003";
// $port = "8125";
$graphite_prefix = "knm.kiosks.cnamts";
// $nc_server = "nc -q0 -u $server $port";
$nc_server = "nc -q0 $server $port";

$min = 0;
$max = 500;
$debug = 1;
$debug = 0;

$pmServices               = new PluginMonitoringService();
$computer                 = new Computer();
$pmComponentscatalog_Host = new PluginMonitoringComponentscatalog_Host();
$pmServiceevent           = new PluginMonitoringServiceevent();

$entities = array();
$pattern = "/\W/e";
$replacement = "";
$query_entities = "SELECT `id`, `name` FROM `glpi_entities` WHERE `entities_id` = 1";
$result_entities = $DB->query($query_entities);

while ($entity = $DB->fetch_array($result_entities)) {
  $id = $entity['id'];
  $entities[$id] = $entity['name'];
  $entities[$id] = strtolower(preg_replace($pattern, $replacement, $entities[$id]));
}

$states = array(
		"OK" => 1,
		"WARNING" => 2,
		"CRITICAL" => 3
		);

$a_services = $pmServices->find();
// print_r($a_services); exit;

foreach ($a_services as $a_service) {
  $services_id = $a_service['id'];
  $services_name = $a_service['name'];

  $counters_type = '';
  switch ($services_name) {
  case "nsca_printer":
  case "Imprimante":
    $counters_type = 'nsca_printer';
  break;
  case "Lecteur de cartes":
  case "nsca_reader":
    $counters_type = 'nsca_reader';
    break;
  case "CPU":
  case "nsca_cpu":
    $counters_type = 'nsca_cpu';
    break;
  case "Carte Vitale":
    $counters_type = 'nsca_sv';
    break;
  case "Alive":
  case "host_check":
    $counters_type = 'host_check';
    break;
  case "nsca_network":
  case "Réseau":
    $counters_type = 'nsca_network';
    break;
  case "nsca_memory":
  case "Mémoire":
    $counters_type = 'nsca_memory';
    break;
  case "nsca_disk":
  case "Disque":
    $counters_type = 'nsca_disk';
    break;
  case "Onduleur":
    $counters_type = 'nsca_battery';
    break;
  case "Navigateur":
    $counters_type = 'nsca_navigateur';
    break;
  case "nsca_fusion":
  case "Inventaire":
    $counters_type = 'nsca_fusion';
    break;
  case "Services":
  case "nsca_services":
    $counters_type = 'nsca_services';
    break;
  case "nsca_software":
  case "Autre logiciel":
    $counters_type = 'nsca_software';
    break;
  case "Matériel":
  case "Autre matériel":
    $counters_type = 'nsca_hardware';
    break;
  }
  
  $pmComponentscatalog_Host->getFromDB($a_service['plugin_monitoring_componentscatalogs_hosts_id']);
  $computer->getFromDB($pmComponentscatalog_Host->fields['items_id']);
  $hostname = $computer->fields['name'];

  /* DEBUG */

  /* if ($counters_type != "nsca_battery") { */
  /*   continue; */
  /* } */

  /* if ($services_id != 954) { */
  /*   continue; */
  /* } */

  /* if ($hostname != "ek3k-cnam-0019") { */
  /*   continue; */
  /* } */

  /* echo "Service : $hostname/$services_name $services_id\n"; */

  $previous_cut = 0;
  $previous_retracted = 0;
  $first = 1;

  $query = "SELECT `se`.`date`, `se`.`perf_data`, `se`.`state`, `s`.`entities_id` FROM `glpi_plugin_monitoring_serviceevents` as se, `glpi_plugin_monitoring_services` as s WHERE `se`.`plugin_monitoring_services_id` = '".$services_id."' AND `se`.`plugin_monitoring_services_id` = `s`.`id` ORDER BY `se`.`id` ASC ";

  $result = $DB->query($query);
  while($serviceevent = $DB->fetch_array($result)) {

    $state = 0;
    if (isset($states[$serviceevent['state']])) {
      $state = $states[$serviceevent['state']];
    }

    $matches = array();
    switch ($counters_type) {
    case 'nsca_printer':
      if (preg_match('/\'Cut Pages\'=(?P<cut>\d+)c \'Retracted Pages\'=(?P<retracted>\d+)c/', $serviceevent['perf_data'], $matches)) {
	$cut       = $matches['cut'];
	$retracted = $matches['retracted'];
	$reams     = 0;
	$empty     = 0;
	// $replace   = 0;
      } elseif (preg_match('/\'Cut Pages\'=(?P<cut>\d+)c \'Retracted Pages\'=(?P<retracted>\d+)c \'Paper Reams\'=(?P<reams>\d+)c \'Trash Empty\'=(?P<empty>\d+)c \'Printer Replace\'=(?P<replace>\d+)c/', $serviceevent['perf_data'], $matches)) {
	$cut       = $matches['cut'];
	$retracted = $matches['retracted'];
	$reams     = $matches['reams'];
	$empty     = $matches['empty'];
	// $replace   = $matches['replace']
      }	

      if (count($matches)) {
	if ($first) {
	  echo "Service ".$entities[$serviceevent['entities_id']]."/$hostname/$services_name : $services_id\n";
	  $previous_cut = $cut;
	  $previous_retracted = $retracted;
	  $previous_reams = $reams;
	  $replace = 0;
	  $first = 0;
	  continue;
	}

	// CUT BRUT
	exec_cmd("echo \"$graphite_prefix.".$entities[$serviceevent['entities_id']].".$hostname.shinken.nsca_printer.Cut_Pages ".$cut." ".strtotime($serviceevent['date'])."\" | $nc_server;\n");
	// RECTRACTED BRUT
	exec_cmd("echo \"$graphite_prefix.".$entities[$serviceevent['entities_id']].".$hostname.shinken.nsca_printer.Retracted_Pages ".$retracted." ".strtotime($serviceevent['date'])."\" | $nc_server;\n");
	// REAMS BRUT
	exec_cmd("echo \"$graphite_prefix.".$entities[$serviceevent['entities_id']].".$hostname.shinken.nsca_printer.Paper_Reams ".$reams." ".strtotime($serviceevent['date'])."\" | $nc_server;\n");
	// CUT
	if ($cut != $previous_cut) {
	  if ( (($cut-$previous_cut) > $max) || (($cut-$previous_cut) < $min)){ 
	    echo " ERROR cut >> ".($cut-$previous_cut)." pages @".strtotime($serviceevent['date'])."\n";
	    $previous_cut = $cut;
	    continue;
	  }

	  exec_cmd("echo \"$graphite_prefix.".$entities[$serviceevent['entities_id']].".$hostname.shinken.nsca_printer.Cut_Pages_diff ".($cut-$previous_cut)." ".strtotime($serviceevent['date'])."\" | $nc_server;\n");
	  $previous_cut = $cut;
	}

	// RETRACTED
	if ($retracted != $previous_retracted) {
	  if ( ($retracted - $previous_retracted) < -1 || ($retracted - $previous_retracted) > 1 ) { 
	    // Remplacement d'imprimante ?
	    echo " ERROR retracted >> ".($retracted-$previous_retracted)." pages @".strtotime($serviceevent['date'])." - printer change ?\n";
	    $replace++;
	    $previous_retracted = $retracted;
	    exec_cmd("echo \"$graphite_prefix.".$entities[$serviceevent['entities_id']].".$hostname.shinken.nsca_printer.Printer_Replace ".($replace)." ".strtotime($serviceevent['date'])."\" | $nc_server;\n");
	    continue;
	  }

	  exec_cmd("echo \"$graphite_prefix.".$entities[$serviceevent['entities_id']].".$hostname.shinken.nsca_printer.Retracted_Pages_diff ".($retracted-$previous_retracted)." ".strtotime($serviceevent['date'])."\" | $nc_server;\n");
	  $previous_retracted = $retracted;
	}

	// REAMS
	if ($reams != $previous_reams) {
	  if ( (($reams-$previous_reams) > $max) || (($reams-$previous_reams) < $min)){ 
	    echo " ERROR reams >> ".($reams-$previous_reams)." pages @".strtotime($serviceevent['date'])."\n";
	    $previous_reams = $reams;
	    continue;
	  }

	  exec_cmd("echo \"$graphite_prefix.".$entities[$serviceevent['entities_id']].".$hostname.shinken.nsca_printer.Reams_Pages_diff ".($reams-$previous_reams)." ".strtotime($serviceevent['date'])."\" | $nc_server;\n");
	  $previous_reams = $reams;
	}
	
      }
      break;
    case 'nsca_reader':
      // pas de perf-data
      break;
    case 'nsca_cpu':
      if (preg_match('/\'5m\'=(?P<cpu_5m>\d+)%;(?P<cpu_5m_crit>\d+);(?P<cpu_5m_warn>\d+) \'1m\'=(?P<cpu_1m>\d+)%;(?P<cpu_1m_crit>\d+);(?P<cpu_1m_warn>\d+) \'30s\'=(?P<cpu_30s>\d+)%;(?P<cpu_30s_crit>\d+);(?P<cpu_30s_warn>\d+)/', $serviceevent['perf_data'], $matches)) {

	$cpu_30s = $matches['cpu_30s'];
	$cpu_30s_warn = $matches['cpu_30s_warn'];
	$cpu_30s_crit = $matches['cpu_30s_crit'];

	$cpu_1m = $matches['cpu_1m'];
	$cpu_1m_warn = $matches['cpu_1m_warn'];
	$cpu_1m_crit = $matches['cpu_1m_crit'];

	$cpu_5m = $matches['cpu_5m'];
	$cpu_5m_warn = $matches['cpu_5m_warn'];
	$cpu_5m_crit = $matches['cpu_5m_crit'];

	exec_cmd("echo \"$graphite_prefix.".$entities[$serviceevent['entities_id']].".$hostname.shinken.".$counters_type.".30s ".$cpu_30s." ".strtotime($serviceevent['date'])."\" | $nc_server;\n");
	exec_cmd("echo \"$graphite_prefix.".$entities[$serviceevent['entities_id']].".$hostname.shinken.".$counters_type.".30s_warn ".$cpu_30s_warn." ".strtotime($serviceevent['date'])."\" | $nc_server;\n");
	exec_cmd("echo \"$graphite_prefix.".$entities[$serviceevent['entities_id']].".$hostname.shinken.".$counters_type.".30s_crit ".$cpu_30s_crit." ".strtotime($serviceevent['date'])."\" | $nc_server;\n");
	exec_cmd("echo \"$graphite_prefix.".$entities[$serviceevent['entities_id']].".$hostname.shinken.".$counters_type.".1m ".$cpu_1m." ".strtotime($serviceevent['date'])."\" | $nc_server;\n");
	exec_cmd("echo \"$graphite_prefix.".$entities[$serviceevent['entities_id']].".$hostname.shinken.".$counters_type.".1m_warn ".$cpu_1m_warn." ".strtotime($serviceevent['date'])."\" | $nc_server;\n");
	exec_cmd("echo \"$graphite_prefix.".$entities[$serviceevent['entities_id']].".$hostname.shinken.".$counters_type.".1m_crit ".$cpu_1m_crit." ".strtotime($serviceevent['date'])."\" | $nc_server;\n");
	exec_cmd("echo \"$graphite_prefix.".$entities[$serviceevent['entities_id']].".$hostname.shinken.".$counters_type.".5m ".$cpu_5m." ".strtotime($serviceevent['date'])."\" | $nc_server;\n");
	exec_cmd("echo \"$graphite_prefix.".$entities[$serviceevent['entities_id']].".$hostname.shinken.".$counters_type.".5m_warn ".$cpu_5m_warn." ".strtotime($serviceevent['date'])."\" | $nc_server;\n");
	exec_cmd("echo \"$graphite_prefix.".$entities[$serviceevent['entities_id']].".$hostname.shinken.".$counters_type.".5m_crit ".$cpu_5m_crit." ".strtotime($serviceevent['date'])."\" | $nc_server;\n");
      } else {
	echo "ERROR >> $counters_type : ".$serviceevent['perf_data']."\n";
      }
      break;
    case 'nsca_sv':
      // pas de perf-data
      break;
    case 'host_check':
      // perf-data non recupere
      // state de 'host_check' dans '__HOST__.state' au lieu de 'host_check.state'
      exec_cmd("echo \"$graphite_prefix.".$entities[$serviceevent['entities_id']].".$hostname.shinken.__HOST__.state ".$state." ".strtotime($serviceevent['date'])."\" | $nc_server;\n");
      break;
    case 'nsca_network':
      if (preg_match("/'CurrentBandwidth'=(?P<CurrentBandwidth>\d+) 'BytesReceivedPersec'=(?P<BytesReceivedPersec>\d+) 'BytesSentPersec'=(?P<BytesSentPersec>\d+) 'BytesTotalPersec'=(?P<BytesTotalPersec>\d+)/", $serviceevent['perf_data'], $matches)) {
	$current_bandwidth = $matches['CurrentBandwidth'];
	$bytes_received = $matches['BytesReceivedPersec'];
	$bytes_sent = $matches['BytesSentPersec'];
	$bytes_total = $matches['BytesTotalPersec'];

	exec_cmd("echo \"$graphite_prefix.".$entities[$serviceevent['entities_id']].".$hostname.shinken.".$counters_type.".CurrentBandwidth ".$current_bandwidth." ".strtotime($serviceevent['date'])."\" | $nc_server;\n");
	exec_cmd("echo \"$graphite_prefix.".$entities[$serviceevent['entities_id']].".$hostname.shinken.".$counters_type.".BytesReceivedPersec ".$bytes_received." ".strtotime($serviceevent['date'])."\" | $nc_server;\n");
	exec_cmd("echo \"$graphite_prefix.".$entities[$serviceevent['entities_id']].".$hostname.shinken.".$counters_type.".BytesSentPersec ".$bytes_sent." ".strtotime($serviceevent['date'])."\" | $nc_server;\n");
	exec_cmd("echo \"$graphite_prefix.".$entities[$serviceevent['entities_id']].".$hostname.shinken.".$counters_type.".BytesTotalPersec ".$bytes_total." ".strtotime($serviceevent['date'])."\" | $nc_server;\n");
      } else {
	echo "ERROR >> $counters_type : ".$serviceevent['perf_data']."\n";
      }
      break;
    case 'nsca_memory':

      /*
         'physical memory %'=29%;75;90 
         'physical memory'=1.022G;2.556;3.068;0;3.40799 
	 'virtual memory %'=14%;75;90 
	 'virtual memory'=288.945M;1535.906;1843.087;0;2047.875 
	 'paged bytes %'=29%;75;90 
	 'paged bytes'=1G;2.555;3.06599;0;3.407 
	 'page file %'=29%;75;90 
	 'page file'=1G;2.555;3.06599;0;3.407
      */


      if (preg_match("/'physical memory %'=(?P<physical_memory__>\d+)%;(?P<physical_memory___warn>\d+);(?P<physical_memory___crit>\d+) 'physical memory'=(?P<physical_memory>\d+\.\d+)\w;(?P<physical_memory_warn>\d+\.\d+);(?P<physical_memory_crit>\d+\.\d+);\d+;\d+\.\d+ 'virtual memory %'=(?P<virtual_memory__>\d+)%;(?P<virtual_memory___warn>\d+);(?P<virtual_memory___crit>\d+) 'virtual memory'=(?P<virtual_memory>\d+\.\d+)\w;(?P<virtual_memory_warn>\d+\.\d+);(?P<virtual_memory_crit>\d+\.\d+);\d+;\d+\.\d+ 'paged bytes %'=(?P<paged_bytes__>\d+)%;(?P<paged_bytes___warn>\d+);(?P<paged_bytes___crit>\d+) 'paged bytes'=(?P<paged_bytes>\d+\.*\d*)\w;(?P<paged_bytes_warn>\d+\.\d+);(?P<paged_bytes_crit>\d+\.\d+);\d+;\d+\.\d+ 'page file %'=(?P<page_file__>\d+)%;(?P<page_file___warn>\d+);(?P<page_file___crit>\d+) 'page file'=(?P<page_file>\d+\.*\d*)\w;(?P<page_file_warn>\d+\.\d+);(?P<page_file_crit>\d+\.\d+);\d+;\d+\.\d+/", $serviceevent['perf_data'], $matches)) {
	
	$physical_memory__ = $matches['physical_memory__'];
	$physical_memory___warn = $matches['physical_memory___warn'];
	$physical_memory___crit = $matches['physical_memory___crit'];

	$physical_memory = $matches['physical_memory'];
	$physical_memory_warn = $matches['physical_memory_warn'];
	$physical_memory_crit = $matches['physical_memory_crit'];

	$virtual_memory__ = $matches['virtual_memory__'];
	$virtual_memory___warn = $matches['virtual_memory___warn'];
	$virtual_memory___crit = $matches['virtual_memory___crit'];

	$virtual_memory = $matches['virtual_memory'];
	$virtual_memory_warn = $matches['virtual_memory_warn'];
	$virtual_memory_crit = $matches['virtual_memory_crit'];

	$paged_bytes__ = $matches['paged_bytes__'];
	$paged_bytes___warn = $matches['paged_bytes___warn'];
	$paged_bytes___crit = $matches['paged_bytes___crit'];

	$paged_bytes = $matches['paged_bytes'];
	$paged_bytes_warn = $matches['paged_bytes_warn'];
	$paged_bytes_crit = $matches['paged_bytes_crit'];

	$paged_file__ = $matches['paged_file__'];
	$paged_file___warn = $matches['paged_file___warn'];
	$paged_file___crit = $matches['paged_file___crit'];

	$paged_file = $matches['paged_file'];
	$paged_file_warn = $matches['paged_file_warn'];
	$paged_file_crit = $matches['paged_file_crit'];

      }

      if (count($matches)) {

	exec_cmd("echo \"$graphite_prefix.".$entities[$serviceevent['entities_id']].".$hostname.shinken.".$counters_type.".physical_memory__ ".$physical_memory__." ".strtotime($serviceevent['date'])."\" | $nc_server;\n");
	exec_cmd("echo \"$graphite_prefix.".$entities[$serviceevent['entities_id']].".$hostname.shinken.".$counters_type.".physical_memory___warn ".$physical_memory___warn." ".strtotime($serviceevent['date'])."\" | $nc_server;\n");
	exec_cmd("echo \"$graphite_prefix.".$entities[$serviceevent['entities_id']].".$hostname.shinken.".$counters_type.".physical_memory___crit ".$physical_memory___crit." ".strtotime($serviceevent['date'])."\" | $nc_server;\n");

	exec_cmd("echo \"$graphite_prefix.".$entities[$serviceevent['entities_id']].".$hostname.shinken.".$counters_type.".physical_memory ".$physical_memory." ".strtotime($serviceevent['date'])."\" | $nc_server;\n");
	exec_cmd("echo \"$graphite_prefix.".$entities[$serviceevent['entities_id']].".$hostname.shinken.".$counters_type.".physical_memory_warn ".$physical_memory_warn." ".strtotime($serviceevent['date'])."\" | $nc_server;\n");
	exec_cmd("echo \"$graphite_prefix.".$entities[$serviceevent['entities_id']].".$hostname.shinken.".$counters_type.".physical_memory_crit ".$physical_memory_crit." ".strtotime($serviceevent['date'])."\" | $nc_server;\n");

	exec_cmd("echo \"$graphite_prefix.".$entities[$serviceevent['entities_id']].".$hostname.shinken.".$counters_type.".virtual_memory__ ".$virtual_memory__." ".strtotime($serviceevent['date'])."\" | $nc_server;\n");
	exec_cmd("echo \"$graphite_prefix.".$entities[$serviceevent['entities_id']].".$hostname.shinken.".$counters_type.".virtual_memory___warn ".$virtual_memory___warn." ".strtotime($serviceevent['date'])."\" | $nc_server;\n");
	exec_cmd("echo \"$graphite_prefix.".$entities[$serviceevent['entities_id']].".$hostname.shinken.".$counters_type.".virtual_memory___crit ".$virtual_memory___crit." ".strtotime($serviceevent['date'])."\" | $nc_server;\n");

	exec_cmd("echo \"$graphite_prefix.".$entities[$serviceevent['entities_id']].".$hostname.shinken.".$counters_type.".virtual_memory ".$virtual_memory." ".strtotime($serviceevent['date'])."\" | $nc_server;\n");
	exec_cmd("echo \"$graphite_prefix.".$entities[$serviceevent['entities_id']].".$hostname.shinken.".$counters_type.".virtual_memory_warn ".$virtual_memory_warn." ".strtotime($serviceevent['date'])."\" | $nc_server;\n");
	exec_cmd("echo \"$graphite_prefix.".$entities[$serviceevent['entities_id']].".$hostname.shinken.".$counters_type.".virtual_memory_crit ".$virtual_memory_crit." ".strtotime($serviceevent['date'])."\" | $nc_server;\n");

	exec_cmd("echo \"$graphite_prefix.".$entities[$serviceevent['entities_id']].".$hostname.shinken.".$counters_type.".paged_bytes__ ".$paged_bytes__." ".strtotime($serviceevent['date'])."\" | $nc_server;\n");
	exec_cmd("echo \"$graphite_prefix.".$entities[$serviceevent['entities_id']].".$hostname.shinken.".$counters_type.".paged_bytes___warn ".$paged_bytes___warn." ".strtotime($serviceevent['date'])."\" | $nc_server;\n");
	exec_cmd("echo \"$graphite_prefix.".$entities[$serviceevent['entities_id']].".$hostname.shinken.".$counters_type.".paged_bytes___crit ".$paged_bytes___crit." ".strtotime($serviceevent['date'])."\" | $nc_server;\n");

	exec_cmd("echo \"$graphite_prefix.".$entities[$serviceevent['entities_id']].".$hostname.shinken.".$counters_type.".paged_bytes ".$paged_bytes." ".strtotime($serviceevent['date'])."\" | $nc_server;\n");
	exec_cmd("echo \"$graphite_prefix.".$entities[$serviceevent['entities_id']].".$hostname.shinken.".$counters_type.".paged_bytes_warn ".$paged_bytes_warn." ".strtotime($serviceevent['date'])."\" | $nc_server;\n");
	exec_cmd("echo \"$graphite_prefix.".$entities[$serviceevent['entities_id']].".$hostname.shinken.".$counters_type.".paged_bytes_crit ".$paged_bytes_crit." ".strtotime($serviceevent['date'])."\" | $nc_server;\n");

	exec_cmd("echo \"$graphite_prefix.".$entities[$serviceevent['entities_id']].".$hostname.shinken.".$counters_type.".paged_file__ ".$paged_file__." ".strtotime($serviceevent['date'])."\" | $nc_server;\n");
	exec_cmd("echo \"$graphite_prefix.".$entities[$serviceevent['entities_id']].".$hostname.shinken.".$counters_type.".paged_file___warn ".$paged_file___warn." ".strtotime($serviceevent['date'])."\" | $nc_server;\n");
	exec_cmd("echo \"$graphite_prefix.".$entities[$serviceevent['entities_id']].".$hostname.shinken.".$counters_type.".paged_file___crit ".$paged_file___crit." ".strtotime($serviceevent['date'])."\" | $nc_server;\n");

	exec_cmd("echo \"$graphite_prefix.".$entities[$serviceevent['entities_id']].".$hostname.shinken.".$counters_type.".paged_file ".$paged_file." ".strtotime($serviceevent['date'])."\" | $nc_server;\n");
	exec_cmd("echo \"$graphite_prefix.".$entities[$serviceevent['entities_id']].".$hostname.shinken.".$counters_type.".paged_file_warn ".$paged_file_warn." ".strtotime($serviceevent['date'])."\" | $nc_server;\n");
	exec_cmd("echo \"$graphite_prefix.".$entities[$serviceevent['entities_id']].".$hostname.shinken.".$counters_type.".paged_file_crit ".$paged_file_crit." ".strtotime($serviceevent['date'])."\" | $nc_server;\n");

      } else {
	echo "ERROR >> $counters_type : ".$serviceevent['perf_data']."\n";
      }
      break;
    case 'nsca_disk':
      // 'C: %'=1%;50;75 'C:'=16.616G;465.707;698.56;0;931.413      
      if (preg_match("/'C: %'=(?P<C___>\d+)%;(?P<C____warn>\d+);(?P<C____crit>\d+) 'C:'=(?P<C__>\d+\.+\d+)\w;(?P<C___warn>\d+\.+\d+);(?P<C___crit>\d+\.+\d+);\d+;\d+\.+\d+/", $serviceevent['perf_data'], $matches)) {
	// echo "OK >> $counters_type : ".$serviceevent['perf_data']."\n";
	$C___ = $matches['C___'];
	$C____warn = $matches['C____warn'];
	$C____crit = $matches['C____crit'];

	$C__ = $matches['C__'];
	$C___warn = $matches['C___warn'];
	$C___crit = $matches['C___crit'];

	exec_cmd("echo \"$graphite_prefix.".$entities[$serviceevent['entities_id']].".$hostname.shinken.".$counters_type.".C___ ".$C___." ".strtotime($serviceevent['date'])."\" | $nc_server;\n");
	exec_cmd("echo \"$graphite_prefix.".$entities[$serviceevent['entities_id']].".$hostname.shinken.".$counters_type.".C____warn ".$C____warn." ".strtotime($serviceevent['date'])."\" | $nc_server;\n");
	exec_cmd("echo \"$graphite_prefix.".$entities[$serviceevent['entities_id']].".$hostname.shinken.".$counters_type.".C____crit ".$C____crit." ".strtotime($serviceevent['date'])."\" | $nc_server;\n");

	exec_cmd("echo \"$graphite_prefix.".$entities[$serviceevent['entities_id']].".$hostname.shinken.".$counters_type.".C__ ".$C__." ".strtotime($serviceevent['date'])."\" | $nc_server;\n");
	exec_cmd("echo \"$graphite_prefix.".$entities[$serviceevent['entities_id']].".$hostname.shinken.".$counters_type.".C___warn ".$C___warn." ".strtotime($serviceevent['date'])."\" | $nc_server;\n");
	exec_cmd("echo \"$graphite_prefix.".$entities[$serviceevent['entities_id']].".$hostname.shinken.".$counters_type.".C___crit ".$C___crit." ".strtotime($serviceevent['date'])."\" | $nc_server;\n");

      } else {
	echo "ERROR >> $counters_type : *".$serviceevent['perf_data']."*\n";
      }
      break;
    case 'nsca_battery':
      // 'Battery level'=0%;10%;70%
      // ?
      break;
    case 'nsca_navigateur':
      // 'Sessions'=1026c
      if (preg_match("/'Sessions'=(?P<sessions>\d+)c/", $serviceevent['perf_data'], $matches)) {
	$sessions = $matches['sessions'];
	exec_cmd("echo \"$graphite_prefix.".$entities[$serviceevent['entities_id']].".$hostname.shinken.".$counters_type.".sessions ".$sessions." ".strtotime($serviceevent['date'])."\" | $nc_server;\n");
      } else {
	echo "ERROR >> $counters_type : ".$serviceevent['perf_data']."\n";
      }
      break;
    case 'nsca_fusion':
      // pas de perf-data
      break;
    case 'nsca_services':
      // pas de perf-data
      break;
    case 'nsca_software':
      // pas de perf-data
      break;
    case 'nsca_hardware':
      // pas de perf-data
      break;
    }

    if ($counters_type != 'host_check') {
      exec_cmd("echo \"$graphite_prefix.".$entities[$serviceevent['entities_id']].".$hostname.shinken.".$counters_type.".state ".$state." ".strtotime($serviceevent['date'])."\" | $nc_server;\n");
    }
  }
}

function exec_cmd ($cmd) {
  global $debug;
  if ($debug) {
    echo $cmd;
  } else {
    system($cmd);
  }
}