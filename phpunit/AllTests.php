<?php


if (!defined('GLPI_ROOT')) {
   define('GLPI_ROOT', realpath('../../..'));

   include_once (GLPI_ROOT . "/inc/autoload.function.php");
   spl_autoload_register('glpi_autoload');

   include_once (GLPI_ROOT . "/inc/includes.php");

   file_put_contents(GLPI_ROOT."/files/_log/sql-errors.log", '');
   file_put_contents(GLPI_ROOT."/files/_log/php-errors.log", '');

   $dir = GLPI_ROOT."/files/_files/_plugins/monitoring";
   $objects = scandir($dir);
     foreach ($objects as $object) {
       if ($object != "." && $object != "..") {
         if (filetype($dir."/".$object) == "dir") {
         } else {
            unlink($dir."/".$object);
         }
       }
     }


   include_once (GLPI_ROOT . "/inc/timer.class.php");

   include_once (GLPI_ROOT . "/inc/common.function.php");

   // Security of PHP_SELF
   $_SERVER['PHP_SELF']=Html::cleanParametersURL($_SERVER['PHP_SELF']);

   function glpiautoload($classname) {
      global $DEBUG_AUTOLOAD, $CFG_GLPI;
      static $notfound = array();

      // empty classname or non concerted plugin
      if (empty($classname) || is_numeric($classname)) {
         return FALSE;
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
               return FALSE;
            }
         } else {
            // Standard use of GLPI
            if (!in_array($plugname, $_SESSION['glpi_plugins'])) {
               // Plugin not activated
               return FALSE;
            }
         }
      } else {
         // Is ezComponent class ?
         $matches = array();
         if (preg_match('/^ezc([A-Z][a-z]+)/', $classname, $matches)) {
            include_once(GLPI_EZC_BASE);
            ezcBase::autoload($classname);
            return TRUE;
         } else {
            $item=strtolower($classname);
         }
      }

      // No errors for missing classes due to implementation
      if (!isset($CFG_GLPI['missingclasses'])
              OR !in_array($item, $CFG_GLPI['missingclasses'])){
         if (file_exists("$dir$item.class.php")) {
            include_once ("$dir$item.class.php");
            if ($_SESSION['glpi_use_mode']==Session::DEBUG_MODE) {
               $DEBUG_AUTOLOAD[]=$classname;
            }

         } else if (!isset($notfound["$classname"])) {
            // trigger an error to get a backtrace, but only once (use prefix 'x' to handle empty case)
            //Toolbox::logInFile('debug', "file $dir$item.class.php not founded trying to load class $classname\n");
            trigger_error("GLPI autoload : file $dir$item.class.php not founded trying to load class '$classname'");
            $notfound["$classname"] = TRUE;
         }
      }
   }

   spl_autoload_register('glpiautoload');
   
   $_SESSION["glpiname"] = 'glpi';

   include (GLPI_ROOT . "/config/based_config.php");
   include (GLPI_ROOT . "/inc/includes.php");
   restore_error_handler();

   error_reporting(E_ALL | E_STRICT);
   ini_set('display_errors', 'On');
}
ini_set("memory_limit", "-1");
ini_set("max_execution_time", "0");

$_SESSION['glpiactiveprofile'] = array();
$_SESSION['glpiactiveprofile']['interface'] = 'central';
$_SESSION['glpiactiveprofile']['internet'] = 'w';
$_SESSION['glpiactiveprofile']['computer'] = 'w';
$_SESSION['glpiactiveprofile']['monitor'] = 'w';
$_SESSION['glpiactiveprofile']['printer'] = 'w';
$_SESSION['glpiactiveprofile']['peripheral'] = 'w';
$_SESSION['glpiactiveprofile']['networking'] = 'w';

$_SESSION['glpiactiveentities'] = array(0, 1);

$_SESSION["glpiname"] = 'glpi';

Plugin::load('monitoring');

require_once 'GLPIInstall/AllTests.php';
require_once 'MonitoringInstall/AllTests.php';
require_once 'GLPIlogs/AllTests.php';

require_once '1_Unit/PerfdataForGraph.php';
require_once '2_Integration/Host.php';

class AllTests {
   public static function suite() {
      $suite = new PHPUnit_Framework_TestSuite('monitoring');
      if (file_exists("save.sql")) {
         unlink("save.sql");
      }
      $suite->addTest(GLPIInstall_AllTests::suite());
      $suite->addTest(MonitoringInstall_AllTests::suite());
     
      $suite->addTest(PerfdataForGraph_AllTests::suite());
      $suite->addTest(Host_AllTests::suite());
      
      return $suite;
   }
}

?>
