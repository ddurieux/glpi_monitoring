<?php

class ConfigDifferentItemtypesTest extends RestoreDatabase_TestCase {

   /**
    * @test
    */
   public function testGenerateHostsConfig() {

      $pmComponent         = new PluginMonitoringComponent();
      $pmComponentscatalog = new PluginMonitoringComponentscatalog();
      $pmComponentscatalog_Component = new PluginMonitoringComponentscatalog_Component();
      $pmComponentscatalog_Host = new PluginMonitoringComponentscatalog_Host();
      $computer    = new Computer();
      $networkEquipement = new NetworkEquipment();
      $printer     = new Printer();
      $pmCommand   = new PluginMonitoringCommand();
      $entity      = new Entity();

      $PM_EXPORTFOMAT = 'integer';

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
          'name'        => 'computer1',
          'entities_id' => 0
      ));
      $computer->add(array(
          'name'        => 'computer2',
          'entities_id' => 0
      ));
      $printer->add(array(
          'name'        => 'printer1',
          'entities_id' => 0
      ));
      $printer->add(array(
          'name'        => 'printer2',
          'entities_id' => 0
      ));
      $networkEquipement->add(array(
          'name'        => 'ne1',
          'entities_id' => 0
      ));
      $networkEquipement->add(array(
          'name'        => 'ne2',
          'entities_id' => 0
      ));
      $networkEquipement->add(array(
          'name'        => 'ne3',
          'entities_id' => 0
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
      $pmComponentscatalog_Host->add(array(
          'plugin_monitoring_componentscalalog_id' => '1',
          'is_static'                              => '1',
          'items_id'                               => '2',
          'itemtype'                               => 'Computer'
      ));
      $pmComponentscatalog_Host->add(array(
          'plugin_monitoring_componentscalalog_id' => '1',
          'is_static'                              => '1',
          'items_id'                               => '1',
          'itemtype'                               => 'Printer'
      ));
      $pmComponentscatalog_Host->add(array(
          'plugin_monitoring_componentscalalog_id' => '1',
          'is_static'                              => '1',
          'items_id'                               => '2',
          'itemtype'                               => 'Printer'
      ));
      $pmComponentscatalog_Host->add(array(
          'plugin_monitoring_componentscalalog_id' => '1',
          'is_static'                              => '1',
          'items_id'                               => '1',
          'itemtype'                               => 'NetworkEquipment'
      ));
      $pmComponentscatalog_Host->add(array(
          'plugin_monitoring_componentscalalog_id' => '1',
          'is_static'                              => '1',
          'items_id'                               => '2',
          'itemtype'                               => 'NetworkEquipment'
      ));
      $pmComponentscatalog_Host->add(array(
          'plugin_monitoring_componentscalalog_id' => '1',
          'is_static'                              => '1',
          'items_id'                               => '3',
          'itemtype'                               => 'NetworkEquipment'
      ));

      // Generate host and we see result
      $pmShinken = new PluginMonitoringShinken();
      $hosts = $pmShinken->generateHostsCfg();
      $elements = array(
         'host_name'                    => 'computer1',
         '_HOSTID'                      => 1,
         '_ENTITIESID'                  => 0,
         '_ITEMTYPE'                    => 'Computer',
         '_ITEMSID'                     => 1,
         '_ENTITY'                      => 'rootentity',
         '_ENTITY_COMPLETE'             => 'rootentity',
         '_GRAPHITE_PRE'                => 'rootentity',
         'hostgroups'                   => 'rootentity',
         'alias'                        => 'rootentity / computer1',
         'icon_set'                     => 'host',
         'custom_views'                 => 'kiosk',
         'address'                      => 'computer1',
         'parents'                      => '_fake_rootentity',
         'check_command'                => 'pm-check_dig!test',
         'check_interval'               => 5,
         'retry_interval'               => 1,
         'max_check_attempts'           => 5,
         'active_checks_enabled'        => 1,
         'passive_checks_enabled'       => 1,
         'check_period'                 => '24x7',
         'check_freshness'              => 0,
         'freshness_threshold'          => 0,
         'event_handler_enabled'        => 1,
         'realm'                        => 'All',
         'business_impact'              => 3,
         'notes'                        => 'Comment,,comment::',
         'process_perf_data'            => 1,
         'flap_detection_enabled'       => 0,
         'flap_detection_options'       => 'o',
         'low_flap_threshold'           => 25,
         'high_flap_threshold'          => 50,
         'failure_prediction_enabled'   => 0,
         'retain_status_information'    => 0,
         'retain_nonstatus_information' => 0,
         'notes_url'                    => '',
         'action_url'                   => '',
         'icon_image'                   => '',
         'icon_image_alt'               => '',
         'vrml_image'                   => '',
         'statusmap_image'              => '',
         'contacts'                     => '',
         'notifications_enabled'        => 0,
         'notification_period'          => '24x7',
         'notification_options'         => 'd,u,r,f,s',
         'notification_interval'        => 86400,
         'stalking_options'             => '',
         'use'                          => 'Check DNS'
      );
      $this->assertEquals(
              $elements,
              $hosts[0],
              "computer 1 not right");
      $elements['host_name'] = 'computer2';
      $elements['_HOSTID']   = 2;
      $elements['_ITEMSID']  = 2;
      $elements['alias']     = 'rootentity / computer2';
      $elements['address']   = 'computer2';
      $this->assertEquals(
              $elements,
              $hosts[1],
              "computer 2 not right");
      $elements['host_name'] = 'ne1';
      $elements['_HOSTID']   = 5;
      $elements['_ITEMSID']  = 1;
      $elements['_ITEMTYPE'] = 'NetworkEquipment';
      $elements['alias']     = 'rootentity / ne1';
      $elements['address']   = 'ne1';
      unset($elements['parents']);
      $this->assertEquals(
              $elements,
              $hosts[2],
              "network equipment 1 not right");
      $elements['host_name'] = 'ne2';
      $elements['_HOSTID']   = 6;
      $elements['_ITEMSID']  = 2;
      $elements['alias']     = 'rootentity / ne2';
      $elements['address']   = 'ne2';
      $this->assertEquals(
              $elements,
              $hosts[3],
              "network equipment 2 not right");
      $elements['host_name'] = 'ne3';
      $elements['_HOSTID']   = 7;
      $elements['_ITEMSID']  = 3;
      $elements['alias']     = 'rootentity / ne3';
      $elements['address']   = 'ne3';
      $this->assertEquals(
              $elements,
              $hosts[4],
              "network equipment 3 not right");
      $elements['host_name'] = 'printer1';
      $elements['_HOSTID']   = 3;
      $elements['_ITEMSID']  = 1;
      $elements['_ITEMTYPE'] = 'Printer';
      $elements['alias']     = 'rootentity / printer1';
      $elements['address']   = 'printer1';
      $elements['parents']   = '_fake_rootentity';
      $this->assertEquals(
              $elements,
              $hosts[5],
              "printer 1 not right");
      $elements['host_name'] = 'printer2';
      $elements['_HOSTID']   = 4;
      $elements['_ITEMSID']  = 2;
      $elements['alias']     = 'rootentity / printer2';
      $elements['address']   = 'printer2';
      $this->assertEquals(
              $elements,
              $hosts[6],
              "printer 2 not right");
   }

}

?>
