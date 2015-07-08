<?php

class ConfigDynamictagTest extends RestoreDatabase_TestCase {

   function prepare($dyntag) {

      $pmComponent         = new PluginMonitoringComponent();
      $pmComponentscatalog = new PluginMonitoringComponentscatalog();
      $pmComponentscatalog_Component = new PluginMonitoringComponentscatalog_Component();
      $pmComponentscatalog_Host = new PluginMonitoringComponentscatalog_Host();
      $computer    = new Computer();
      $pmCommand   = new PluginMonitoringCommand();
      $networkport = new NetworkPort();
      $networkName = new NetworkName();
      $iPAddress   = new IPAddress();

      $commands = $pmCommand->find("`name`='Check a DNS entry'", '', 1);
      $this->assertEquals(1, count($commands), "DNS command not found");
      $command = current($commands);

      $pmComponent->add(array(
         'name'                          => 'check DNS',
         'plugin_monitoring_commands_id' => $command['id'],
         'plugin_monitoring_checks_id'   => 1,
         'arguments'                     => '{"ARG1":"h_'.$dyntag.'"}'

      ));

      $computer->add(array(
          'name'        => 'computer 1',
          'entities_id' => '0'
      ));

      // Management port
      $managementports_id = $networkport->add(array(
         'itemtype'          => 'Computer',
         'instantiation_type'=> 'NetworkPortEthernet',
         'items_id'          => 1,
         'entities_id'       => 0,
         'name'              => 'rl0',
         'logical_number'    => '1'
      ));
      $networknames_id = $networkName->add(array(
         'entities_id' => 0,
         'itemtype'    => 'NetworkPort',
         'items_id'    => $managementports_id
      ));
      $iPAddress->add(array(
         'entities_id' => 0,
         'itemtype' => 'NetworkName',
         'items_id' => $networknames_id,
         'name' => '192.168.200.124'
      ));

      $managementports_id = $networkport->add(array(
         'itemtype'          => 'Computer',
         'instantiation_type'=> 'NetworkPortEthernet',
         'items_id'          => 1,
         'entities_id'       => 0,
         'name'              => 'rl1',
         'logical_number'    => '2'
      ));
      $networknames_id = $networkName->add(array(
         'entities_id' => 0,
         'itemtype'    => 'NetworkPort',
         'items_id'    => $managementports_id
      ));
      $iPAddress->add(array(
         'entities_id' => 0,
         'itemtype' => 'NetworkName',
         'items_id' => $networknames_id,
         'name' => '192.168.200.200'
      ));

      $pmComponentscatalog->add(array(
          'name'        => 'Check DNS',
          'entities_id' => '0'
      ));

      $pmComponentscatalog_Component->add(array(
          'plugin_monitoring_componentscalalog_id' => 1,
          'plugin_monitoring_components_id'        => 1
      ));

      $pmComponentscatalog_Host->add(array(
          'plugin_monitoring_componentscalalog_id' => '1',
          'is_static'                              => '1',
          'items_id'                               => '1',
          'itemtype'                               => 'Computer'
      ));

      $this->assertEquals(1, countElementsInTable('glpi_plugin_monitoring_services'), "May have one service");

   }


   /**
    * @test
    */
   public function testArgumentHostHOSTNAME() {

      $this->prepare('[[HOSTNAME]]');

      // Generate host and we see result
      $pmShinken = new PluginMonitoringShinken();
      $hosts = $pmShinken->generateHostsCfg();
      $this->assertEquals("pm-check_dig!h_computer 1", $hosts[0]['check_command'], "May have hostname converted");
   }



   /**
    * @test
    */
   public function testArgumentServiceHOSTNAME() {

      // Generate services and we see result
      $pmShinken = new PluginMonitoringShinken();
      $services = $pmShinken->generateServicesCfg();
      $this->assertEquals("pm-check_dig!h_computer 1", $services[0]['check_command'], "May have hostname converted");
   }



   /**
    * @test
    */
   public function testArgumentHostIP() {

      self::restore_database();
      $this->prepare('[[IP]]');

      // Generate host and we see result
      $pmShinken = new PluginMonitoringShinken();
      $hosts = $pmShinken->generateHostsCfg();
      $this->assertEquals("pm-check_dig!h_192.168.200.124", $hosts[0]['check_command'], "May have IP converted");
   }



   /**
    * @test
    */
   public function testArgumentServiceIP() {

      // Generate services and we see result
      $pmShinken = new PluginMonitoringShinken();
      $services = $pmShinken->generateServicesCfg();
      $this->assertEquals("pm-check_dig!h_192.168.200.124", $services[0]['check_command'], "May have IP converted");
   }



   /**
    * @test
    */
   public function testArgumentHostComputerNETWORKPORTNUM() {

      self::restore_database();
      $this->prepare('[[NETWORKPORTNUM]]');

      // Generate host and we see result
      $pmShinken = new PluginMonitoringShinken();
      $hosts = $pmShinken->generateHostsCfg();
      $this->assertEquals("pm-check_dig!h_1", $hosts[0]['check_command'], "May have NETWORKPORTNUM converted");
   }



   /**
    * @test
    */
   public function testArgumentServiceComputerNETWORKPORTNUM() {

      // Generate services and we see result
      $pmShinken = new PluginMonitoringShinken();
      $services = $pmShinken->generateServicesCfg();
      $this->assertEquals("pm-check_dig!h_1", $services[0]['check_command'], "May have NETWORKPORTNUM converted");
   }



   /**
    * @test
    */
   public function testArgumentHostComputerNETWORKPORTNAME() {

      self::restore_database();
      $this->prepare('[[NETWORKPORTNAME]]');

      // Generate host and we see result
      $pmShinken = new PluginMonitoringShinken();
      $hosts = $pmShinken->generateHostsCfg();
      $this->assertEquals("pm-check_dig!h_rl0", $hosts[0]['check_command'], "May have NETWORKPORTNAME converted");
   }



   /**
    * @test
    */
   public function testArgumentServiceComputerNETWORKPORTNAME() {

      // Generate services and we see result
      $pmShinken = new PluginMonitoringShinken();
      $services = $pmShinken->generateServicesCfg();
      $this->assertEquals("pm-check_dig!h_rl0", $services[0]['check_command'], "May have NETWORKPORTNAME converted");
   }

}

?>
