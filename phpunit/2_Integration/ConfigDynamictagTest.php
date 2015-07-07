<?php

class ConfigDynamictagTest extends RestoreDatabase_TestCase {

   function prepare($dyntag) {

      $pmComponent = new PluginMonitoringComponent();
      $pmComponentscatalog = new PluginMonitoringComponentscatalog();
      $pmComponentscatalog_Component = new PluginMonitoringComponentscatalog_Component();
      $pmComponentscatalog_Host = new PluginMonitoringComponentscatalog_Host();
      $computer = new Computer();
      $pmCommand = new PluginMonitoringCommand();

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
      self::restore_database();
      $this->prepare('[[HOSTNAME]]');

      // Generate host and we see result
      $pmShinken = new PluginMonitoringShinken();
      $services = $pmShinken->generateServicesCfg();
      $this->assertEquals("pm-check_dig!h_computer 1", $services[0]['check_command'], "May have hostname converted");
   }



}

?>
