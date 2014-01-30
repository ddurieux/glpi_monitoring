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
         $pmComponent->add(array(
             'name' => 'check',
             'plugin_monitoring_checks_id' => '2'
         ));
      
   }

}



class Host_AllTests  {

   public static function suite() {

      $suite = new PHPUnit_Framework_TestSuite('Host');
      return $suite;
   }
}

?>