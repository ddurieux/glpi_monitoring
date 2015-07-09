<?php

require_once("0_Install/MonitDB.php");

class MonitTest extends Common_TestCase {


   /**
    * @depends GLPIInstallTest::installDatabase
    */
   public function testInstall() {

      global $DB;
      $DB->connect();
      $this->assertTrue($DB->connected, "Problem connecting to the Database");


      // Delete if Table of monitoring yet in DB
      $query = "SHOW FULL TABLES WHERE TABLE_TYPE LIKE 'VIEW'";
      $result = $DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         if (strstr($data[0], "fusi")) {
            $DB->query("DROP VIEW ".$data[0]);
         }
      }

      $query = "SHOW TABLES";
      $result = $DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         if (strstr($data[0], "tracker")
            OR strstr($data[0], "fusi")) {
               $DB->query("DROP TABLE ".$data[0]);
            }
      }

      $output = array();
      $returncode = 0;
      exec(
         "php -f ".MONIT_ROOT. "/scripts/cli_install.php -- --as-user 'glpi' --serviceevents",
         $output, $returncode
      );
      $this->assertEquals(0,$returncode,
         "Error when installing plugin in CLI mode\n".
         implode("\n",$output)
      );

      $GLPIlog = new GLPIlogs();
      $GLPIlog->testSQLlogs();
      $GLPIlog->testPHPlogs();

      $MonitDBTest = new MonitDB();
      $MonitDBTest->checkInstall("monitoring", "install new version");

   }
}



?>
