<?php

class NetworkportsTest extends RestoreDatabase_TestCase {

   /*
    * We test add service and so add a host
    * We test too when remove all services of a host, je host may be deleted
    */

   /**
    * @test
    */
   public function testAddNetworkport() {

      $networkEquipment             = new NetworkEquipment();
      $networkPort                  = new NetworkPort();
      $pmNetworkport                = new PluginMonitoringNetworkport();
      $pmComponent                  = new PluginMonitoringComponent();
      $pmComponentscatalog          = new PluginMonitoringComponentscatalog();
      $pmComponentscatalog_Component= new PluginMonitoringComponentscatalog_Component();
      $pmComponentscatalog_rule     = new PluginMonitoringComponentscatalog_rule();


      // Add component
         $input = array(
             'name'                          => 'traffic',
             'plugin_monitoring_commands_id' => '7'
         );
         $id = $pmComponent->add($input);
         $this->assertGreaterThan(0, $id, 'Components not created');

      // add component catalog
         $input = array(
             'name'        => 'traffic',
             'entities_id' => '0'
         );
         $id = $pmComponentscatalog->add($input);
         $this->assertGreaterThan(0, $id, 'Componentscatalog not created');

      // Add components catalog component
         $input = array(
             'plugin_monitoring_componentscalalog_id' => 1,
             'plugin_monitoring_components_id'        => 1
         );
         $id = $pmComponentscatalog_Component->add($input);
         $this->assertEquals(1, $id, 'Componentscatalog_component not created');

      // Add components catalog rule
         $input = array(
             'plugin_monitoring_componentscalalog_id' => 1,
             'name'        => 'networkports',
             'itemtype'    => 'PluginMonitoringNetworkport',
             'condition'   => '{"field":["view"],"searchtype":["contains"],"contains":[""],"itemtype":"PluginMonitoringNetworkport","start":"0"}'
         );
         $id = $pmComponentscatalog_rule->add($input);
         $this->assertEquals(1, $id, 'Componentscatalog_rule not created');

      // Add a new switch
         $input = array(
             'entities_id' => 0,
             'name'        => 'switch'
         );
         $id = $networkEquipment->add($input);
         $this->assertEquals(1, $id, 'NetworkEquipment not created');

      // Add 2 ports on the switch
         $input = array(
             'name'               => 'Fa0',
             'entities_id'        => 0,
             'items_id'           => '1',
             'itemtype'           => 'NetworkEquipment',
             'instantiation_type' => 'NetworkPortEthernet',
             'logical_number'     => 10001
         );
         $id = $networkPort->add($input);
         $this->assertEquals(1, $id, 'NetworkPort not created');

         $input = array(
             'name'               => 'Fa1',
             'entities_id'        => 0,
             'items_id'           => '1',
             'itemtype'           => 'NetworkEquipment',
             'instantiation_type' => 'NetworkPortEthernet',
             'logical_number'     => 10002
         );
         $id = $networkPort->add($input);
         $this->assertEquals(2, $id, 'NetworkPort not created');

      // Add the port in monitoring
         $_POST = array(
             'itemtype'        => 'NetworkEquipment',
             'items_id'        => '1',
             'networkports_id' => array(1, 2)
         );
         $pmNetworkport->updateNetworkports();
         $this->assertEquals(
                 2,
                 countElementsInTable('glpi_plugin_monitoring_networkports'),
                 "May have 2 networkports in glpi_plugin_monitoring_networkports");


      // Check glpi_plugin_monitoring_componentscatalogs_hosts have 1 entry
         $this->assertEquals(
                 1,
                 countElementsInTable('glpi_plugin_monitoring_componentscatalogs_hosts'),
                 "May have one entrie in glpi_plugin_monitoring_componentscatalogs_hosts");

      // Check have services created
         $a_services = getAllDatasFromTable('glpi_plugin_monitoring_services');
         $this->assertEquals(2, count($a_services), "May have one service");
   }



   /**
    * @test
    */
/* TODO
   public function testUncheckNetworkport() {

      $pmNetworkport                = new PluginMonitoringNetworkport();

      // Uncheck the first port in monitoring
         $_POST = array(
             'itemtype'        => 'NetworkEquipment',
             'items_id'        => '1',
             'networkports_id' => array(2)
         );
         $pmNetworkport->updateNetworkports();

      // Check have services deleted
         $a_services = getAllDatasFromTable('glpi_plugin_monitoring_services');
         $this->assertEquals(1, count($a_services), "May have one service");

      // Check glpi_plugin_monitoring_componentscatalogs_hosts have 1 entry
         $this->assertEquals(
                 1,
                 countElementsInTable('glpi_plugin_monitoring_componentscatalogs_hosts'),
                 "May have one entrie in glpi_plugin_monitoring_componentscatalogs_hosts");
   }
*/


   /**
    * @test
    */
/* TODO
   public function testDeleteNetworkport() {
      $networkport                = new Networkport();

      // Delete the second port in switch
         $networkport->delete(array('id' => '2'));

      // Check have services deleted
         $a_services = getAllDatasFromTable('glpi_plugin_monitoring_services');
         $this->assertEquals(0, count($a_services), "May have no service");

      // Check glpi_plugin_monitoring_componentscatalogs_hosts have 0 entry
         $this->assertEquals(
                 0,
                 countElementsInTable('glpi_plugin_monitoring_componentscatalogs_hosts'),
                 "May have one entrie in glpi_plugin_monitoring_componentscatalogs_hosts");
   }
*/

}

?>
