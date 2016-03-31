<?php

/*
   ------------------------------------------------------------------------
   Plugin Monitoring for GLPI
   Copyright (C) 2011-2016 by the Plugin Monitoring for GLPI Development Team.

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
   @author    Frederic Mohier
   @co-author
   @comment
   @copyright Copyright (c) 2011-2016 Plugin Monitoring for GLPI team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      https://forge.indepnet.net/projects/monitoring/
   @since     2011

   ------------------------------------------------------------------------
 */

/*
   ------------------------------------------------------------------------
    This script allows to create a new ticket in Glpi database. It uses the
   Kiosks plugin Web Service createTicket.
   
    Command line is:
    -v to activate verbose mode
    -d to activate WS debug mode (ticket is not created)
    
    -e to specify Id of the ticket entity (eg. -e=2). 
      Default is 0 (root)
    
    -t to specify type of the concerned device (eg. -t=Computer)
      Default is Computer
      
    -i to specify Id of the concerned device (eg. -i=1)
      Default is 1
      
    -c to specify category of the ticket (eg. -c=1)
      Default is 1
      
    The created ticket is always an Incident one. It automatically uses the
   template associated with type/category to use predefined fields.
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
$host = '127.0.0.1';
$glpi_user  = "shinken";
$glpi_pass  = "shinken";

/*
* PARAMETERS
*/

// $argv[0] is full script name
$options = getopt("vdt::e::i::c::");
// var_dump($options);
// die('Test options');

$verbose = isset($options['v']) ? true : false;
echo '+ Use command line parameter -v to set verbose mode: '. ($verbose ? "Actif" : "Inactif") ."\n";

$debug = isset($options['d']) ? true : false;
echo '+ Use command line parameter -d to set Debug mode: '. ($debug ? "Actif" : "Inactif") ."\n";
if ($debug) {
   echo  "\n" . "* Debug mode is on, ticket will not be created!" . "\n" . "\n";
}

$type = 'Computer';
if (isset($options['t'])) {
   $type = $options['t'];
}
echo '+ Use command line parameter -t="Computer" to set item type: '. $type ."\n";

$id = '1';
if (isset($options['i'])) {
   $id = $options['i'];
}
echo '+ Use command line parameter -i="9" to set item id: '. $id ."\n";

$entity = '0';
if (isset($options['e'])) {
   $entity = $options['e'];
}
echo '+ Use command line parameter -e="6" to set item entity: '. $entity ."\n";

$category = '1';
if (isset($options['c'])) {
   $category = $options['c'];
}
echo '+ Use command line parameter -c="1" to set item category: '. $category ."\n";



/*
* LOGIN
*/
function login() {
   global $glpi_user, $glpi_pass, $verbose;

   $args['method']          = "glpi.doLogin";
   $args['login_name']      = $glpi_user;
   $args['login_password']  = $glpi_pass;

   if($result = call_glpi($args)) {
      return $result['session'];
   }
   
   if ($verbose) {
      echo "* No session!\n";
   }
   return '';
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
   global $host,$url,$deflate,$verbose;

   echo "+ Calling {$args['method']} on http://$host/$url\n";

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
                                                          'content' => $request,
                                                          'timeout' => 500)));

   if ($verbose) {
      echo "Request: \n" . $request . "\n\n";
      echo "Context: \n" . $context . "\n\n";
   }
   $file = file_get_contents("http://$host/$url_session", false, $context);
   if (!$file) {
      die("+ No response\n");
   }

   if (in_array('Content-Encoding: deflate', $http_response_header)) {
      $lenc=strlen($file);
      if ($verbose) {
         echo "+ Compressed response : $lenc\n";
      }
      $file = gzuncompress($file);
      $lend=strlen($file);
      if ($verbose) {
         echo "+ Uncompressed response : $lend (".round(100.0*$lenc/$lend)."%)\n";
      }
   }
   if ($verbose) {
      echo "Response file: \n" . $file . "\n\n";
   }

   $response = xmlrpc_decode($file);
   if (!is_array($response)) {
      echo $file;
      die ("+ Bad response\n");
   }

   if (xmlrpc_is_fault($response)) {
       echo("xmlrpc error(".$response['faultCode']."): ".$response['faultString']."\n");
   } else {
      if ($verbose) {
         echo "Response array: \n" . serialize($response) . "\n\n";
      }
      return $response;
   }
}

/*
* ACTIONS
*/

// Init session
$session = login();
if ($verbose) {
   echo "Session: " . $session . "\n";
}

/*
   Host custom variables: 
   _HOSTID                            3
   _ENTITIESID                        6
   _ITEMTYPE                          Computer
   _ITEMSID                           9
   _ENTITY                            desk1

 */
$args['session']     = $session;
$args['method']      = "kiosks.createTicket";
if ($debug){
   $args['debug']    = true;
}
$args['title']       = "Titre du ticket ...";
$args['content']     = "Contenu du ticket ...";
$args['source']      = "Shinken";
// Entity: _ENTITIESID
$args['entity']      = (int)$entity;
// Use ticket template
$args['template']    = true;
// Type: incident
$args['type']        = (int)"1";
// Category
$args['category']    = (int)"1";
// Item type: _ITEMTYPE
$args['itemtype']    = $type;
// Item id: _ITEMSID
$args['item']        = (int)$id;

$ticket = call_glpi($args);
if ($verbose){
   print_r($ticket);
}

// Close session
logout();
?>
