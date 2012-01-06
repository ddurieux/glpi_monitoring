<?php

/*
   ----------------------------------------------------------------------
   Monitoring plugin for GLPI
   Copyright (C) 2010-2011 by the GLPI plugin monitoring Team.

   https://forge.indepnet.net/projects/monitoring/
   ----------------------------------------------------------------------

   LICENSE

   This file is part of Monitoring plugin for GLPI.

   Monitoring plugin for GLPI is free software: you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation, either version 2 of the License, or
   any later version.

   Monitoring plugin for GLPI is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with Monitoring plugin for GLPI.  If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------
   Original Author of file: David DURIEUX
   Co-authors of file:
   Purpose of file:
   ----------------------------------------------------------------------
 */


if (!defined('GLPI_ROOT')) {   
   define('GLPI_ROOT', '../../..');
   
   file_put_contents(GLPI_ROOT."/files/_log/sql-errors.log", '');
   file_put_contents(GLPI_ROOT."/files/_log/php-errors.log", '');
   
   include_once (GLPI_ROOT . "/inc/timer.class.php");

   include_once (GLPI_ROOT . "/inc/common.function.php");

   // Security of PHP_SELF
   $_SERVER['PHP_SELF']=cleanParametersURL($_SERVER['PHP_SELF']);

   function glpiautoload($classname) {
      global $DEBUG_AUTOLOAD, $CFG_GLPI;
      static $notfound = array();

      // empty classname or non concerted plugin
      if (empty($classname) || is_numeric($classname)) {
         return false;
      }

      $dir=GLPI_ROOT . "/inc/";
      //$classname="PluginExampleProfile";
      if ($plug=isPluginItemType($classname)) {
         $plugname=strtolower($plug['plugin']);
         $dir=GLPI_ROOT . "/plugins/$plugname/inc/";
         $item=strtolower($plug['class']);
         // Is the plugin activate ?
         // Command line usage of GLPI : need to do a real check plugin activation
         if (isCommandLine()) {
            $plugin = new Plugin();
            if (count($plugin->find("directory='$plugname' AND state=".Plugin::ACTIVATED)) == 0) {
               // Plugin does not exists or not activated
               return false;
            }
         } else {
            // Standard use of GLPI
            if (!in_array($plugname,$_SESSION['glpi_plugins'])) {
               // Plugin not activated
               return false;
            }
         }
      } else {
         // Is ezComponent class ?
         if (preg_match('/^ezc([A-Z][a-z]+)/',$classname,$matches)) {
            include_once(GLPI_EZC_BASE);
            ezcBase::autoload($classname);
            return true;
         } else {
            $item=strtolower($classname);
         }
      }

      // No errors for missing classes due to implementation
      if (!isset($CFG_GLPI['missingclasses']) 
              OR !in_array($item,$CFG_GLPI['missingclasses'])){
         if (file_exists("$dir$item.class.php")) {
            include_once ("$dir$item.class.php");
            if ($_SESSION['glpi_use_mode']==DEBUG_MODE) {
               $DEBUG_AUTOLOAD[]=$classname;
            }

         } else if (!isset($notfound["x$classname"])) {
            // trigger an error to get a backtrace, but only once (use prefix 'x' to handle empty case)
            //logInFile('debug',"file $dir$item.class.php not founded trying to load class $classname\n");
            trigger_error("GLPI autoload : file $dir$item.class.php not founded trying to load class '$classname'");
            $notfound["x$classname"] = true;
         }
      } 
   }
      
   spl_autoload_register('glpiautoload');
   include (GLPI_ROOT . "/config/based_config.php");
   include (GLPI_ROOT . "/inc/includes.php");
   restore_error_handler();

   error_reporting(E_ALL | E_STRICT);
   ini_set('display_errors','On');
}
ini_set("memory_limit", "-1");
ini_set("max_execution_time", "0");

require_once 'GLPIInstall/AllTests.php';
require_once 'MonitoringInstall/AllTests.php';
require_once 'GLPIlogs/AllTests.php';
require_once 'ManageRessources/AllTests.php';

class AllTests {
   public static function suite() {
      $suite = new PHPUnit_Framework_TestSuite('Monitoring');
      $suite->addTest(GLPIInstall_AllTests::suite());
      $suite->addTest(MonitoringInstall_AllTests::suite());
      $suite->addTest(ManageRessources_AllTests::suite());
      return $suite;
   }
}

?>