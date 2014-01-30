<?php

class Host extends PHPUnit_Framework_TestCase {

   /*
    * We test add service and so add a host
    * We test too when remove all services of a host, je host may be deleted
    */
   
   public function testAddService() {
      global $DB;

      $DB->connect();

      Plugin::loadLang('monitoring');
      $pmComponent = new PluginMonitoringComponent();
      $pmComponentscatalog = new PluginMonitoringComponentscatalog();
      $pmComponentscatalog_Component = new PluginMonitoringComponentscatalog_Component();
      $pmComponentscatalog_Host = new PluginMonitoringComponentscatalog_Host();
      $computer = new Computer();
      
      $pmComponent->add(array(
          'name'                        => 'check',
          'plugin_monitoring_checks_id' => '2'
      ));
      
      $computer->add(array(
          'name'        => 'computer1',
          'entities_id' => '0'
      ));
      
      $pmComponentscatalog->add(array(
          'name'        => 'Check ping',
          'entities_id' => '0'
      ));
      
      $pmComponentscatalog_Component->add(array(
          'plugin_monitoring_componentscalalog_id' => '1',
          'plugin_monitoring_components_id'        => '1'
      ));
      
      $pmComponentscatalog_Host->add(array(
          'plugin_monitoring_componentscalalog_id' => '1',
          'is_static'                              => '1',
          'items_id'                               => '1',
          'itemtype'                               => 'Computer'
      ));
      // Used in front/componentscatalog_host.form.php
         $pmComponentscatalog_Host->linkComponentsToItem(1, 1);
      
      $this->assertEquals(1, countElementsInTable('glpi_plugin_monitoring_services'), "May have one service");
      $this->assertEquals(1, countElementsInTable('glpi_plugin_monitoring_hosts'), "May have a host created");
   }

   
   
   public function testDeleteService() {
      global $DB;

      $DB->connect();

      Plugin::loadLang('monitoring');

      $pmComponentscatalog_Host = new PluginMonitoringComponentscatalog_Host();

      $pmComponentscatalog_Host->delete(array('id' => '1'));
      
      $this->assertEquals(0, countElementsInTable('glpi_plugin_monitoring_services'), "The service may be deleted");
      $this->assertEquals(0, countElementsInTable('glpi_plugin_monitoring_hosts'), "The host may be deleted (no service in this host)");
      
   }
}



class Host_AllTests  {

   public static function suite() {

      $suite = new PHPUnit_Framework_TestSuite('Host');
      return $suite;
   }
}

?>