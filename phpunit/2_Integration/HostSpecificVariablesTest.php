<?php

class HostSpecificVariablesTest extends RestoreDatabase_TestCase {

   /**
    * @test
    */
   public function testComputerGRAPHITE_PRE_empty() {

      $pmComponent         = new PluginMonitoringComponent();
      $pmComponentscatalog = new PluginMonitoringComponentscatalog();
      $pmComponentscatalog_Component = new PluginMonitoringComponentscatalog_Component();
      $pmComponentscatalog_Host = new PluginMonitoringComponentscatalog_Host();
      $computer    = new Computer();
      $pmCommand   = new PluginMonitoringCommand();
      $entity      = new Entity();

      $input = array(
         'name'        => 'entity-a',
         'entities_id' => 0,
         'comment'     => ''
      );
      $entity->add($input);

      $input = array(
         'name'        => 'entb',
         'entities_id' => 1,
         'comment'     => ''
      );
      $entity->add($input);

      $commands = $pmCommand->find("`name`='Check a DNS entry'", '', 1);
      $this->assertEquals(1, count($commands), "DNS command not found");
      $command = current($commands);

      $pmComponent->add(array(
         'name'                          => 'check DNS',
         'plugin_monitoring_commands_id' => $command['id'],
         'plugin_monitoring_checks_id'   => 1,
         'arguments'                     => '{"ARG1":"test"}'

      ));

      $computer->add(array(
          'name'        => 'computer 1',
          'entities_id' => 2
      ));

      $pmComponentscatalog->add(array(
          'name'         => 'Check DNS',
          'entities_id'  => 0,
          'is_recursive' => 1
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

      // Generate host and we see result
      $pmShinken = new PluginMonitoringShinken();
      $hosts = $pmShinken->generateHostsCfg();
      $this->assertEquals(
              "entitya.entb",
              $hosts[0]['_GRAPHITE_PRE'],
              "_GRAPHITE_PRE not right filled");
   }



   /**
    * @depends testComputerGRAPHITE_PRE_empty
    */
   public function testComputerGRAPHITE_PRE_filled() {

      $pmHostconfig = new PluginMonitoringHostconfig();
      $input = array(
         'items_id'        => 2,
         'itemtype'        => 'Entity',
         'graphite_prefix' => 'curr',
         'plugin_monitoring_components_id' => 1,
         'plugin_monitoring_realms_id' => 1
      );
      $hc_id = $pmHostconfig->add($input);

      // Generate host and we see result
      $pmShinken = new PluginMonitoringShinken();
      $hosts = $pmShinken->generateHostsCfg();
      $this->assertEquals(
              "curr.entitya.entb",
              $hosts[0]['_GRAPHITE_PRE'],
              "_GRAPHITE_PRE not right filled with current prefix entity");


      $input = array(
         'id'              => 1,
         'graphite_prefix' => 'r00t'
      );
      $pmHostconfig->update($input);

      $pmHostconfig->delete(array('id' => $hc_id));

      // Generate host and we see result
      $pmShinken = new PluginMonitoringShinken();
      $hosts = $pmShinken->generateHostsCfg();
      $this->assertEquals(
              "r00t.entitya.entb",
              $hosts[0]['_GRAPHITE_PRE'],
              "_GRAPHITE_PRE not right filled with prefix of root entity");

   }



   /**
    * @test
    */
   public function testNetworkEquipmentGRAPHITE_PRE() {

      self::restore_database();

      $pmComponent         = new PluginMonitoringComponent();
      $pmComponentscatalog = new PluginMonitoringComponentscatalog();
      $pmComponentscatalog_Component = new PluginMonitoringComponentscatalog_Component();
      $pmComponentscatalog_Host = new PluginMonitoringComponentscatalog_Host();
      $networkEquipment = new NetworkEquipment();
      $pmCommand   = new PluginMonitoringCommand();
      $entity      = new Entity();

      $input = array(
         'name'        => 'entity-a',
         'entities_id' => 0,
         'comment'     => ''
      );
      $entity->add($input);

      $input = array(
         'name'        => 'entb',
         'entities_id' => 1,
         'comment'     => ''
      );
      $entity->add($input);

      $commands = $pmCommand->find("`name`='Check a DNS entry'", '', 1);
      $this->assertEquals(1, count($commands), "DNS command not found");
      $command = current($commands);

      $pmComponent->add(array(
         'name'                          => 'check DNS',
         'plugin_monitoring_commands_id' => $command['id'],
         'plugin_monitoring_checks_id'   => 1,
         'arguments'                     => '{"ARG1":"test"}'

      ));

      $networkEquipment->add(array(
          'name'        => 'switch 1',
          'entities_id' => 2
      ));

      $pmComponentscatalog->add(array(
          'name'         => 'Check DNS',
          'entities_id'  => 0,
          'is_recursive' => 1
      ));

      $pmComponentscatalog_Component->add(array(
          'plugin_monitoring_componentscalalog_id' => 1,
          'plugin_monitoring_components_id'        => 1
      ));

      $pmComponentscatalog_Host->add(array(
          'plugin_monitoring_componentscalalog_id' => '1',
          'is_static'                              => '1',
          'items_id'                               => '1',
          'itemtype'                               => 'NetworkEquipment'
      ));

      // Generate host and we see result
      $pmShinken = new PluginMonitoringShinken();
      $hosts = $pmShinken->generateHostsCfg();
      $this->assertEquals(
              "entitya.entb",
              $hosts[0]['_GRAPHITE_PRE'],
              "_GRAPHITE_PRE not right filled");

   }



   /**
    * @depends testNetworkEquipmentGRAPHITE_PRE
    */
   public function testNetworkEquipmentGRAPHITE_PRE_filled() {

      $pmHostconfig = new PluginMonitoringHostconfig();
      $input = array(
         'items_id'        => 2,
         'itemtype'        => 'Entity',
         'graphite_prefix' => 'curr',
         'plugin_monitoring_components_id' => 1,
         'plugin_monitoring_realms_id' => 1
      );
      $hc_id = $pmHostconfig->add($input);

      // Generate host and we see result
      $pmShinken = new PluginMonitoringShinken();
      $hosts = $pmShinken->generateHostsCfg();
      $this->assertEquals(
              "curr.entitya.entb",
              $hosts[0]['_GRAPHITE_PRE'],
              "_GRAPHITE_PRE not right filled with current prefix entity");


      $input = array(
         'id'              => 1,
         'graphite_prefix' => 'r00t'
      );
      $pmHostconfig->update($input);

      $pmHostconfig->delete(array('id' => $hc_id));

      // Generate host and we see result
      $pmShinken = new PluginMonitoringShinken();
      $hosts = $pmShinken->generateHostsCfg();
      $this->assertEquals(
              "r00t.entitya.entb",
              $hosts[0]['_GRAPHITE_PRE'],
              "_GRAPHITE_PRE not right filled with prefix of root entity");

   }

}

?>
