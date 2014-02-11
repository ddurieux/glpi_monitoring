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

class Update extends PHPUnit_Framework_TestCase {

   public function testUpdate08410() {
      global $PF_CONFIG;
      
      $PF_CONFIG = array();
      
      $Update = new Update();
      $Update->update("0.84+1.0");
   }
   
   
   function update($version = '') {
      global $DB;
      $DB->connect();
      
      if ($version == '') {
         return;
      }
      echo "#####################################################\n
            ######### Update from version ".$version."###############\n
            #####################################################\n";
      $GLPIInstall = new GLPIInstall();
      $GLPIInstall->testInstall();

      $query = "SHOW TABLES";
      $result = $DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         if (strstr($data[0], "monitoring")) {
            $DB->query("DROP TABLE ".$data[0]);
         }
      }
      $query = "DELETE FROM `glpi_displaypreferences`
         WHERE `itemtype` LIKE 'PluginMonitoring%'";
      $DB->query($query);

      // ** Insert in DB
      $res = $DB->runFile(GLPI_ROOT ."/plugins/monitoring/phpunit/MonitoringInstall/Update/mysql/i-".$version.".sql");
      $this->assertTrue($res, "Fail: SQL Error during insert version ".$version);

      passthru("cd ../scripts/ && php -f cli_install.php");

      $GLPIlog = new GLPIlogs();
      $GLPIlog->testSQLlogs();
      $GLPIlog->testPHPlogs();
      
      $MonitoringInstall = new MonitoringInstall();
      $MonitoringInstall->testDB("monitoring", "upgrade from ".$version);
   }
   
   
   
   public function testInstallCleanVersion() {
      global $DB;
      
      $DB->connect();
      
      $Install = new Install();
      $Install->testInstall(0);
      
   }
}



class Update_AllTests  {

   public static function suite() {

      $suite = new PHPUnit_Framework_TestSuite('Update');
      return $suite;

   }
}

?>
