<?php

/*
   ------------------------------------------------------------------------
   Plugin Monitoring for GLPI
   Copyright (C) 2011-2012 by the Plugin Monitoring for GLPI Development Team.

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
   along with Behaviors. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   Plugin Monitoring for GLPI
   @author    David Durieux
   @co-author 
   @comment   
   @copyright Copyright (c) 2011-2012 Plugin Monitoring for GLPI team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      https://forge.indepnet.net/projects/monitoring/
   @since     2011
 
   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   define('GLPI_ROOT', '../../..');
}
if (!defined("GLPI_PLUGIN_DOC_DIR")){
   define("GLPI_PLUGIN_DOC_DIR",GLPI_ROOT . "/files/_plugins");
}
$docDir = GLPI_PLUGIN_DOC_DIR.'/monitoring';

if (isset($_GET['file'])) {
   $filename = $_GET['file'];

   // Security test : document in $docDir
   if (strstr($filename,"../") || strstr($filename,"..\\")){
      echo "Security attack !!!";
      Event::log($filename, "sendFile", 1, "security",
                 $_SESSION["glpiname"]." tries to get a non standard file.");
      return;
   }

   $file = $docDir.'/'.$filename;
   $mime = '';
   if (preg_match("/PluginMonitoringService-([0-9]+)-2h([0-9]+).png/", $filename)) {
      include (GLPI_ROOT."/inc/includes.php");

      $match = array();
      preg_match("/PluginMonitoringService-([0-9]+)-2h([0-9]+).png/", $filename, $match);

      $pmServicegraph = new PluginMonitoringServicegraph();
      $pmService = new PluginMonitoringService();
      $pmComponent = new PluginMonitoringComponent();
      $pmService->getFromDB($match[1]);
      $pmComponent->getFromDB($pmService->fields['plugin_monitoring_components_id']);

      $pmServicegraph->displayGraph($pmComponent->fields['graph_template'], 
                                    "PluginMonitoringService", 
                                    $match[1], 
                                    $match[2], 
                                    '2h');
      $mime = "PNG";
   }
   
   if (!file_exists($file)){
      echo "Error file $filename does not exist";
      return;
   } else {
      // Now send the file with header() magic
      header("Expires: Mon, 26 Nov 1962 00:00:00 GMT");
      header('Pragma: private'); /// IE BUG + SSL
      header('Pragma: no-cache');
      header('Cache-control: private, must-revalidate'); /// IE BUG + SSL
      header("Content-disposition: filename=\"$filename\"");
      if ($mime != '') {
         header("Content-type: ".$mime);
      }
      
      $f=fopen($file,"r");

      if (!$f){
         echo "Error opening file $filename";
      } else {
         // Pour que les \x00 ne devienne pas \0
         $mc=get_magic_quotes_runtime();
         if ($mc) @set_magic_quotes_runtime(0);
         $fsize=filesize($file);

         if ($fsize){
            echo fread($f, filesize($file));
         } else {
            echo $LANG['document'][47];
         }

         if ($mc) @set_magic_quotes_runtime($mc);
      }
   }
}

?>