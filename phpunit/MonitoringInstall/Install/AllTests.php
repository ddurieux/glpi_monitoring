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

class Install extends PHPUnit_Framework_TestCase {

   public function testInstall($verify=1) {
      global $DB;
      
      $DB->connect();

      // Delete if Table of Monitoring yet in DB
      $query = "SHOW FULL TABLES WHERE TABLE_TYPE LIKE 'VIEW'";
      $result = $DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         if (strstr($data[0], "monitoring")) {
            $DB->query("DROP VIEW ".$data[0]);
         }
      } 
      
      $query = "SHOW TABLES";
      $result = $DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         if (strstr($data[0], "monitoring")) {
            $DB->query("DROP TABLE ".$data[0]);
         }
      }

      passthru("cd ../tools && /usr/local/bin/php -f cli_install.php");

      $_SESSION['glpi_use_mode'] = 2;
      $_SESSION["glpiID"] = 2;
      
      Plugin::load("monitoring");
      
      Session::loadLanguage("en_GB");
      
      if ($verify == '1') {
         $MonitoringInstall = new MonitoringInstall();
         $MonitoringInstall->testDB("monitoring");
         
      }
      
      $GLPIlog = new GLPIlogs();
      $GLPIlog->testSQLlogs();
      $GLPIlog->testPHPlogs();
   }
}



class Install_AllTests  {

   public static function suite() {

      $suite = new PHPUnit_Framework_TestSuite('Install');
      return $suite;
   }
}
?>
