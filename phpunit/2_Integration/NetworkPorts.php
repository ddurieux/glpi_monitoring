<?php

class NetworkPorts extends PHPUnit_Framework_TestCase {

   /*
    * We test add service and so add a host
    * We test too when remove all services of a host, je host may be deleted
    */
   
   public function testAddNetworkport() {
      global $DB;

      $DB->connect();

      $_SESSION["glpiname"] = 'glpi';
      Plugin::load('monitoring');

      Plugin::loadLang('monitoring');
  
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
         $this->assertGreaterThan(0, $id, 'Componentscatalog_component not created');
      
      // Add components catalog rule
         $input = array(
             'plugin_monitoring_componentscalalog_id' => 1,
             'name'        => 'networkports',
             'itemtype'    => 'PluginMonitoringNetworkport',
             'condition'   => '{"field":["view"],"searchtype":["contains"],"contains":[""],"itemtype":"PluginMonitoringNetworkport","start":"0"}'
         );
         $id = $pmComponentscatalog_Component->add($input);
         $this->assertGreaterThan(0, $id, 'Componentscatalog_rule not created');
      
      // Add a new switch
         $input = array(
             'entities_id' => 0,
             'name'        => 'switch'
         );
         $id = $networkEquipment->add($input);
         $this->assertGreaterThan(0, $id, 'NetworkEquipment not created');

      // Add port on the switch
         $input = array(
             'name'               => 'Fa0',
             'entities_id'        => 0,
             'items_id'           => '1',
             'itemtype'           => 'NetworkEquipment',
             'instantiation_type' => 'NetworkPortEthernet',
             'logical_number'     => 10001
         );
         $id = $networkPort->add($input);
         $this->assertGreaterThan(0, $id, 'NetworkPort not created');
      
      // Add the port in monitoring
         $_POST = array(
             'itemtype'        => 'NetworkEquipment',
             'items_id'        => '1',
             'networkports_id' => array('1')
         );
         $pmNetworkport->updateNetworkports();
      
      $a_services = getAllDatasFromTable('glpi_plugin_monitoring_services');
      $this->assertEquals(1, count($a_services), "May have one service");

   }
}



class NetworkPorts_AllTests  {

   public static function suite() {

      $suite = new PHPUnit_Framework_TestSuite('NetworkPorts');
      return $suite;
   }
}

?>