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

class Update extends PHPUnit_Framework_TestCase {

   public function testUpdate() {
      
      $Update = new Update();
      $Update->Update("i-0.80+1.0");
      $Update->Update("i-0.80+1.1");
      $Update->Update("i-0.80+1.2");
      $Update->Update("i-0.83+1.0");
      $Update->Update("u-0.80+1.0_to_0.80+1.1");
      $Update->Update("u-0.80+1.1_to_0.80+1.2");
      
   }
   
   
   function Update($version = '') {
      global $DB;

      if ($version == '') {
         return;
      }
      $GLPIInstall = new a_GLPIInstall();
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
      $res = $DB->runFile(GLPI_ROOT ."/plugins/monitoring/phpunit/b_MonitoringInstall/Update/mysql/".$version.".sql");
      $this->assertTrue($res, "Fail: SQL Error during insert version ".$version);
      
      echo "Install plugin monitoring (cli)\n";
      passthru("cd ../tools/ && /usr/local/bin/php -f cli_install.php");
      
      Plugin::load("monitoring");
      
      Session::loadLanguage("en_GB");
      Plugin::loadLang('monitoring');
      
      $MonitoringInstall = new b_MonitoringInstall();
      $MonitoringInstall->testDB("monitoring");
      
      $GLPIlog = new d_GLPIlogs();
      $GLPIlog->testSQLlogs();
      $GLPIlog->testPHPlogs();
      
   }
}



//class Update_AllTests  {
//
//   public static function suite() {
//
//      $suite = new PHPUnit_Framework_TestSuite('Update');
//      return $suite;
//      
//   }
//}

?>