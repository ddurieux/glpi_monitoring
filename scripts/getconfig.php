<?php

/*
   ------------------------------------------------------------------------
   Plugin Monitoring for GLPI
   Copyright (C) 2011-2014 by the Plugin Monitoring for GLPI Development Team.

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
   @copyright Copyright (c) 2011-2014 Plugin Monitoring for GLPI team
   @license   AGPL License 3.0 or (at your option) any later version
	      http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      https://forge.indepnet.net/projects/monitoring/
   @since     2011

   ------------------------------------------------------------------------
 */

if (!extension_loaded("xmlrpc")) {
   die("Extension xmlrpc not loaded\n");
}

/*
* SETTINGS
*/
chdir(dirname($_SERVER["SCRIPT_FILENAME"]));
chdir("../../..");
$url = "/" . basename(getcwd()) . "/plugins/webservices/xmlrpc.php";

$url = "/glpi/plugins/webservices/xmlrpc.php";
$host = 'localhost';
$glpi_user  = "glpi";
$glpi_pass  = "glpi";



/*
* LOGIN
*/
function login() {
   global $glpi_user, $glpi_pass, $ws_user, $ws_pass;

    $args['method']          = "glpi.doLogin";
    $args['login_name']      = $glpi_user;
    $args['login_password']  = $glpi_pass;

    if (isset($ws_user)){
       $args['username'] = $ws_user;
    }

    if (isset($ws_pass)){
       $args['password'] = $ws_pass;
    }

    if($result = call_glpi($args)) {
       return $result['session'];
    }
}

/*
* LOGOUT
*/
function logout() {
    $args['method'] = "glpi.doLogout";

    if($result = call_glpi($args)) {
       return true;
    }
}

/*
* GENERIC CALL
*/
function call_glpi($args) {
   global $host,$url,$deflate,$base64;

   echo "+ Calling {".$args['method']."} on http://$host/$url\n";

   if (isset($args['session'])) {
      $url_session = $url.'?session='.$args['session'];
   } else {
      $url_session = $url;
   }

   $header = "Content-Type: text/xml";

   if (isset($deflate)) {
      $header .= "\nAccept-Encoding: deflate";
   }


   $request = xmlrpc_encode_request($args['method'], $args);
   $context = stream_context_create(array('http' => array('method'  => "POST",
							  'header'  => $header,
							  'content' => $request)));

   $file = file_get_contents("http://$host/$url_session", false, $context);
   if (!$file) {
      die("+ No response\n");
   }

   if (in_array('Content-Encoding: deflate', $http_response_header)) {
      $lenc=strlen($file);
      echo "+ Compressed response : $lenc\n";
      $file = gzuncompress($file);
      $lend=strlen($file);
      echo "+ Uncompressed response : $lend (".round(100.0*$lenc/$lend)."%)\n";
   }

   $response = xmlrpc_decode($file);
   // echo "+ Response : $response\n";
   if (!is_array($response)) {
      echo "+ Response : $response\n";
      // echo $file;
      die ("+ Bad response\n");
   }

   if (xmlrpc_is_fault($response)) {
       echo(" -> xmlrpc error(".$response['faultCode']."): ".$response['faultString']."\n");
       return null;
   } else {
      return $response;
   }
}

/*
* ACTIONS
*/

// Init sessions
$session = login();

/*
* Get overall status
*/
$args['session'] = $session;
$args['method'] = "monitoring.dashboard";
/* Requested view :
   'Hosts', counters for all monitored hosts
   'Ressources', counters for all monitored services
   'Componentscatalog', counters for components catalogs
   'Businessrules', counters for business rules
*/
$args['view'] = "Hosts";
$counters = call_glpi($args);
print_r($counters);
// $args['view'] = "Ressources";
// $counters = call_glpi($args);
// print_r($counters);
// $args['view'] = "Componentscatalog";
// $counters = call_glpi($args);
// print_r($counters);
// $args['view'] = "Businessrules";
// $counters = call_glpi($args);
// print_r($counters);

/*
* Get hosts states
*/
$args['session'] = $session;
$args['method'] = "monitoring.getHostsStates";
/* Filter used in DB query; you may use :
   `glpi_entities`.`name`, for entity name, or any column name from glpi_entities table
   `glpi_computers`.`name`, for computer name, or any column name from glpi_computers table
   any column name from glpi_plugin_monitoring_hosts table
*/
// $args['filter'] = "`glpi_computers`.`name` LIKE 'ek3k%'";
$args['filter'] = "";

$hostsStates = call_glpi($args);
// print_r($hostsStates);
// foreach ($hostsStates as $computer) {
   // echo "---\n";
   // foreach ($computer as $key=>$value) {
      // echo "$key = $value\n";
   // }
// }
echo "Host states : \n";
foreach ($hostsStates as $computer) {
   echo " - ".$computer['host_name']." is ".$computer['state']." (".$computer['state_type'].")\n";
}

/*
* Get services states
*/
$args['session'] = $session;
$args['method'] = "monitoring.getServicesStates";
/* Filter used in DB query; you may use :
   `glpi_entities`.`name`, for entity name, or any column name from glpi_entities table
   `glpi_computers`.`name`, for computer name, or any column name from glpi_computers table
   `glpi_computers`.*,
   `glpi_plugin_monitoring_hosts`.*,
   `glpi_plugin_monitoring_services`.*,
   `glpi_plugin_monitoring_componentscatalogs_hosts`.*,
   `glpi_plugin_monitoring_components`.*
*/
// $args['filter'] = "`glpi_computers`.`name` LIKE 'ek3k%'";
$args['filter'] = "";

$servicesStates = call_glpi($args);
// print_r($servicesStates);
// foreach ($servicesStates as $service) {
   // echo "---\n";
   // foreach ($service as $key=>$value) {
      // echo "$key = $value\n";
   // }
// }
echo "Services states : \n";
foreach ($servicesStates as $service) {
   echo " - ".$service['host_name']." / ".$service['name']." is ".$service['state']." (".$service['state_type'].")\n";
}

/*
* Get services list
*/
$args['session'] = $session;
$args['method'] = "monitoring.getServicesList";
$args['statetype'] = "critical";
$args['view'] = "Ressources";

$servicesList = call_glpi($args);
print_r($servicesList);

/*
* Get Shinken configuration objects
*/
$method = "monitoring.shinkenGetConffiles";
$file = "all";

$args['session'] = $session;
$args['method'] = $method;
$args['file'] = $file;


$configfiles = call_glpi($args);

foreach ($configfiles as $filename=>$filecontent) {
   $filename = "plugins/monitoring/scripts/".$filename;
   $handle = fopen($filename,"w+");
   if (is_writable($filename)) {
       if (fwrite($handle, $filecontent) === FALSE) {
	 echo "Impossible to write file ".$filename."\n";
       }
       echo "File ".$filename." writen successful\n";
       fclose($handle);
   }
}

// Reset login after create entity
logout();
$session = login();

?>
