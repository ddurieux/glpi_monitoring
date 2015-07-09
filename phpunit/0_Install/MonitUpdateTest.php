<?php

/*
 * bootstrop.php needs to be loaded since tests are run in separate process
 */
include_once('bootstrap.php');
include_once('commonfunction.php');
include_once (GLPI_ROOT . "/config/based_config.php");
include_once (GLPI_ROOT . "/inc/dbmysql.class.php");
include_once (GLPI_CONFIG_DIR . "/config_db.php");

include_once('0_Install/MonitDB.php');

class UpdateTest extends RestoreDatabase_TestCase {

   /**
    * @dataProvider provider
    * @runInSeparateProcess
    * @preserveGlobalState disabled
    * @test
    */
   function update($version = '', $verify = FALSE) {
      self::restore_database();
      global $DB;
      $DB->connect();

      if ($version == '') {
         return;
      }


      $query = "SHOW TABLES";
      $result = $DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         if (
            strstr($data[0], "tracker")
            OR strstr($data[0], "fusi")
         ) {
            $DB->query("DROP TABLE ".$data[0]);
         }
      }
      $query = "DELETE FROM `glpi_displaypreferences`
         WHERE `itemtype` LIKE 'PluginFus%'";
      $DB->query($query);

      $sqlfile = GLPI_ROOT ."/plugins/monitoring/phpunit/0_Install/mysql/i-".$version.".sql";
      // Load specific Monitoring version in database
      $result = load_mysql_file(
         $DB->dbuser,
         $DB->dbhost,
         $DB->dbdefault,
         $DB->dbpassword,
         $sqlfile
      );
      $this->assertEquals( 0, $result['returncode'],
         "Failed to install Monitoring ".$sqlfile.":\n".
         implode("\n", $result['output'])
      );
      $output = array();
      $returncode = 0;
      exec(
         "php -f ".MONIT_ROOT."/scripts/cli_install.php -- --as-user 'glpi' --serviceevents",
         $output,
         $returncode
      );
      $this->assertEquals(0,$returncode,implode("\n", $output));

      $GLPIlog = new GLPIlogs();
      $GLPIlog->testSQLlogs();
      $GLPIlog->testPHPlogs();

      $MonitDB = new MonitDB();
      $MonitDB->checkInstall("monitoring", "upgrade from ".$version);

   }

   public function provider() {
      // version, verifyConfig, nb entity rules
      return array(
         array("0.84+1.0", TRUE),
      );
   }

}

?>
