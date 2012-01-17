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
	die("Sorry. You can't access directly to this file");
}

class PluginMonitoringWebservice {


   /**
   * Method for import XML by webservice
   *
   * @param $params array ID of the agent
   * @param $protocol value the communication protocol used
   *
   *@return array or error value
   *
   **/
   static function methodTest($params, $protocol) {
      global $LANG, $CFG_GLPI;

      if (isset ($params['help'])) {
         return array('base64'  => 'string,mandatory',
                      'help'    => 'bool,optional');
      }
      if (!isset ($_SESSION['glpiID'])) {
         return PluginWebservicesMethodCommon::Error($protocol, WEBSERVICES_ERROR_NOTAUTHENTICATED);
      }

      print_r($params);
//      $content = base64_decode($params['base64']);

      

//      $PluginFusinvinventoryImportXML = new PluginFusinvinventoryImportXML();
//      $PluginFusinvinventoryImportXML->importXMLContent($content);

//      $msg = $LANG['plugin_fusinvinventory']['importxml'][1];
//      return PluginWebservicesMethodCommon::Error($protocol, WEBSERVICES_ERROR_FAILED, '', $msg);
   }

   
   static function methodShinkenGetConffiles($params, $protocol) {
      global $LANG, $CFG_GLPI;

      if (isset ($params['help'])) {
         return array('file'  => 'config filename to get : commands.cfg, hosts.cfg',
                      'help'    => 'bool,optional');
      }

      ini_set("max_execution_time", "0");
      ini_set("memory_limit", "-1");
      $pluginMonitoringShinken = new PluginMonitoringShinken();
      switch ($params['file']) {

         case 'commands.cfg':
            $array = $pluginMonitoringShinken->generateCommandsCfg(1);
            return array($array[0]=>$array[1]);
            break;

         case 'hosts.cfg':
            $array = $pluginMonitoringShinken->generateHostsCfg(1);
            return array($array[0]=>$array[1]);
            break;

         case 'contacts.cfg':
            $array = $pluginMonitoringShinken->generateContactsCfg(1);
            return array($array[0]=>$array[1]);
            break;

         case 'timeperiods.cfg':
            $array = $pluginMonitoringShinken->generateTimeperiodsCfg(1);
            return array($array[0]=>$array[1]);
            break;
         
         case 'services.cfg':
            $array = $pluginMonitoringShinken->generateServicesCfg(1);
            return array($array[0]=>$array[1]);
            break;

         case 'all':
            $output = array();
            $array = $pluginMonitoringShinken->generateCommandsCfg(1);
            $output[$array[0]] = $array[1];
            $array = $pluginMonitoringShinken->generateHostsCfg(1);
            $output[$array[0]] = $array[1];
            $array = $pluginMonitoringShinken->generateContactsCfg(1);
            $output[$array[0]] = $array[1];
            $array = $pluginMonitoringShinken->generateTimeperiodsCfg(1);
            $output[$array[0]] = $array[1];
            $array = $pluginMonitoringShinken->generateServicesCfg(1);
            $output[$array[0]] = $array[1];

            return $output;
            break;

      }
   }


   static function methodShinkenCommands($params, $protocol) {
      global $LANG, $CFG_GLPI;

      $pluginMonitoringShinken = new PluginMonitoringShinken();
      $array = $pluginMonitoringShinken->generateCommandsCfg();
      return $array;
   }

   
   
   static function methodShinkenHosts($params, $protocol) {
      global $LANG, $CFG_GLPI;

      $pluginMonitoringShinken = new PluginMonitoringShinken();
      $array = $pluginMonitoringShinken->generateHostsCfg();
      return $array;
   }
   
   
   
   static function methodShinkenServices($params, $protocol) {
      global $LANG, $CFG_GLPI;

      $pluginMonitoringShinken = new PluginMonitoringShinken();
      $array = $pluginMonitoringShinken->generateServicesCfg();
      return $array;
   }



   static function methodShinkenContacts($params, $protocol) {
      global $LANG, $CFG_GLPI;

      $pluginMonitoringShinken = new PluginMonitoringShinken();
      $array = $pluginMonitoringShinken->generateContactsCfg();
      return $array;
   }



   static function methodShinkenTimeperiods($params, $protocol) {
      global $LANG, $CFG_GLPI;

      $pluginMonitoringShinken = new PluginMonitoringShinken();
      $array = $pluginMonitoringShinken->generateTimeperiodsCfg();
      return $array;
   }
   
   
   
   static function methodDashboard($params, $protocol) {
      $array = array();
      
      if (!isset($params['view'])) {
         return array();
      }
      
      $pm = new PluginMonitoringDisplay();
      $array = $pm->displayCounters($params['view'], 0);
            
      return $array;
   }

}

?>