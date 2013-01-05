<?php

/*
   ------------------------------------------------------------------------
   Plugin Monitoring for GLPI
   Copyright (C) 2011-2013 by the Plugin Monitoring for GLPI Development Team.

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
   @copyright Copyright (c) 2011-2013 Plugin Monitoring for GLPI team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      https://forge.indepnet.net/projects/monitoring/
   @since     2011
 
   ------------------------------------------------------------------------
 */


function displayMigrationMessage ($id, $msg="") {
   // display nothing
}


if (!defined('GLPI_ROOT')) {
   define('GLPI_ROOT', '../../..');
   require GLPI_ROOT . "/inc/includes.php";
   restore_error_handler();

   error_reporting(E_ALL | E_STRICT);
   ini_set('display_errors','On');
}

class a_GLPIInstall extends PHPUnit_Framework_TestCase {

   public function testInstall() {
      global $DB, $CFG_GLPI;
      
//      include_once (GLPI_ROOT . "/inc/dbmysql.class.php");
//      include_once (GLPI_CONFIG_DIR . "/config_db.php");
      
      $CFG_GLPI['root_doc'] = "http://127.0.0.1/glpi083_phpunit/";
      echo "TEST";
      $query = "SHOW FULL TABLES WHERE TABLE_TYPE LIKE 'VIEW'";
      $result = $DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         $DB->query("DROP VIEW ".$data[0]);
      }      

      $query = "SHOW TABLES";
      $result = $DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         $DB->query("DROP TABLE ".$data[0]);
      }
      

      
      // Install a fresh 0.80.5 DB
      $DB  = new DB();
      $res = $DB->runFile(GLPI_ROOT ."/install/mysql/glpi-0.83.1-empty.sql");
      $this->assertTrue($res, "Fail: SQL Error during install");

      // update default language
      $query = "UPDATE `glpi_configs`
                SET `language` = 'en_GB'";
      $this->assertTrue($DB->query($query), "Fail: can't set default language");
      $query = "UPDATE `glpi_users`
                SET `language` = 'en_GB'";
      $this->assertTrue($DB->query($query), "Fail: can't set users language");
      
      $GLPIlog = new d_GLPIlogs();
      $GLPIlog->testSQLlogs();
      $GLPIlog->testPHPlogs();
   }
}



//class GLPIInstall_AllTests  {

//   public static function suite() {

//      $suite = new PHPUnit_Framework_TestSuite('GLPIInstall');
//      return $suite;
//   }
//}
?>
