<?php

class ShinkenGetConfigTagTest extends RestoreDatabase_TestCase {

   public function prepare() {

      $entity   = new Entity();
      $pmEntity = new PluginMonitoringEntity();
      $pmComponent         = new PluginMonitoringComponent();
      $pmComponentscatalog = new PluginMonitoringComponentscatalog();
      $pmComponentscatalog_Component = new PluginMonitoringComponentscatalog_Component();
      $pmComponentscatalog_Host = new PluginMonitoringComponentscatalog_Host();
      $computer    = new Computer();
      $pmCommand   = new PluginMonitoringCommand();
      $networkport = new NetworkPort();
      $networkName = new NetworkName();
      $iPAddress   = new IPAddress();

      // Add entities
      $entities_id = $entity->add(array(
         'name'        => 'entityA',
         'entities_id' => 0,
         'comment'     => ''
      ));
      $pmEntity->add(array(
         'entities_id' => $entities_id,
         'tag' => 'tagA'
      ));
      // Add hosts

      $commands = $pmCommand->find("`name`='Check a DNS entry'", '', 1);
      $this->assertEquals(1, count($commands), "DNS command not found");
      $command = current($commands);

      $pmComponent->add(array(
         'name'                          => 'check DNS',
         'plugin_monitoring_commands_id' => $command['id'],
         'plugin_monitoring_checks_id'   => 1,
         'arguments'                     => '{}'

      ));

      $computer->add(array(
          'name'        => 'computer 1',
          'entities_id' => '1'
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
    * @depends prepare
    */
   public function testGetHosts_tagA() {

      $shinken = new PluginMonitoringShinken();

      $hosts = $shinken->generateHostsCfg(0, 'tagA');

      $this->assertEquals(1, count($hosts), "May have 1 host");
   }

   /**
    * @depends prepare
    */
   public function testGetHosts_tagNotExist() {

      $shinken = new PluginMonitoringShinken();

      $hosts = $shinken->generateHostsCfg(0, 'tagxxx');

      $this->assertEquals(0, count($hosts), "May have 0 host");
   }


}

?>
