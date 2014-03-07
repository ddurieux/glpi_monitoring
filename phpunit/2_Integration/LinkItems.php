<?php

class Host extends PHPUnit_Framework_TestCase {

   /*
    * We test add service and so add a host
    * We test too when remove all services of a host, je host may be deleted
    */

   public function testAddService() {
      global $DB;

      $DB->connect();

      $_SESSION["glpiname"] = 'glpi';
      Plugin::load('monitoring');

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

      $this->assertEquals(1, countElementsInTable('glpi_plugin_monitoring_services'), "May have one service");
   }



   public function testAddServicesCatalog() {
      global $DB;

      $DB->connect();

      $_SESSION["glpiname"] = 'glpi';
      Plugin::load('monitoring');

      Plugin::loadLang('monitoring');

      $pmServicescatalog = new PluginMonitoringServicescatalog();
      $pmBusinessruleGroup = new PluginMonitoringBusinessruleGroup();
      $pmBusinessrule = new PluginMonitoringBusinessrule();


      $pmServicescatalog->add(array(
          'name'                        => 'bp1',
          'entities_id'                 => '0',
          'plugin_monitoring_checks_id' => '2',
          'calendars_id'                => '2',
          'notification_interval'       => '30'
      ));
      $pmBusinessruleGroup->add(array(
          'name'                                  => 'first group',
          'plugin_monitoring_servicescatalogs_id' => '1',
          'operator'                              => 'and'
      ));
      $pmBusinessrule->add(array(
          'plugin_monitoring_businessrulegroups_id' => '1',
          'plugin_monitoring_services_id'           => '1',
          'is_dynamic'                              => '0'
      ));

      $this->assertEquals(1, countElementsInTable('glpi_plugin_monitoring_businessrules'), "May have one service in services catalog");

   }



   public function testAddHost() {
      global $DB;

      $DB->connect();

      $this->assertEquals(1, countElementsInTable('glpi_plugin_monitoring_hosts'), "May have a host created");
   }



   public function testDeleteService() {
      global $DB;

      $DB->connect();

      $_SESSION["glpiname"] = 'glpi';
      Plugin::load('monitoring');

      Plugin::loadLang('monitoring');

      $pmComponentscatalog_Host = new PluginMonitoringComponentscatalog_Host();

      $pmComponentscatalog_Host->delete(array('id' => '1'));

      $this->assertEquals(0, countElementsInTable('glpi_plugin_monitoring_services'), "The service may be deleted");
   }



   public function testDeleteServiceofServicesCatalog() {
      global $DB;

      $DB->connect();

      $this->assertEquals(0, countElementsInTable('glpi_plugin_monitoring_businessrules'), "The service may be deleted of services catalog");
   }



   public function testDeleteHost() {
      global $DB;

      $DB->connect();

      $this->assertEquals(0, countElementsInTable('glpi_plugin_monitoring_hosts'), "The host may be deleted (no service in this host)");
   }
}



class LinkItems_AllTests  {

   public static function suite() {

      $suite = new PHPUnit_Framework_TestSuite('LinkItems');
      return $suite;
   }
}

?>