<?php

/*
   ------------------------------------------------------------------------
   Plugin Monitoring for GLPI
   Copyright (C) 2011-2014 by the Plugin Monitoring for GLPI Development Team.

   https://forge.indepnet.net/projects/monitoring/
   ------------------------------------------------------------------------

   LICENSE

   This file is part of Plugin Monitoring project.

   Plugin Monitoring for GLPI is free software: you can redistribute it and/or modify
   it under the terms of the GNU Affero General Public License as published by
   the Free Software Foundation, either version 3 of the License, or
   (at your option) any later version.

   Plugin Monitoring for GLPI is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
   GNU Affero General Public License for more details.

   You should have received a copy of the GNU Affero General Public License
   along with Monitoring. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   Plugin Monitoring for GLPI
   @author    David Durieux
   @co-author
   @comment
   @copyright Copyright (c) 2011-2014 Plugin Monitoring for GLPI team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      https://forge.indepnet.net/projects/monitoring/
   @since     2011

   ------------------------------------------------------------------------
 */

class c_ManageRessources extends PHPUnit_Framework_TestCase {

   public function testPHPlogs() {
      global $CFG_GLPI, $PLUGIN_HOOKS;

      $_SESSION['glpi_use_mode'] = 2;
      $_SESSION["glpiID"] = 2;
      $_SESSION["glpiactiveentities_string"] = 0;

      passthru("cd ../scripts/ && /usr/local/bin/php -f cli_install.php");

      $PLUGIN_HOOKS = plugin_init_monitoring();
      plugin::load("monitoring");

      $CFG_GLPI['root_doc'] = "http://127.0.0.1/monitoring0.85/";

      Session::loadLanguage("en_GB");
      Plugin::loadLang("monitoring", "en_GB");
      $pmComponent = new PluginMonitoringComponent();
      $pmComponentscatalog = new PluginMonitoringComponentscatalog();
      $pmComponentscatalog_Component = new PluginMonitoringComponentscatalog_Component();
      $pmComponentscatalog_rule = new PluginMonitoringComponentscatalog_rule();
      $pmComponentscatalog_Host = new PluginMonitoringComponentscatalog_Host();
      $pmService = new PluginMonitoringService();
      $computer = new Computer();

      // Add components
      $input = array();
      $input['name'] = 'Host alive';
      $input['plugin_monitoring_commands_id '] = '21';
      $input['plugin_monitoring_checks_id'] = '1';
      $input['calendars_id'] = '1';
      $components_id = $pmComponent->add($input);

      // Add components catalog
      $input = array();
      $input['name'] = 'linux servers';
      $catalogs_id = $pmComponentscatalog->add($input);

      $input = array();
      $input['plugin_monitoring_componentscalalog_id'] = $catalogs_id;
      $input['plugin_monitoring_components_id'] = $components_id;
      $pmComponentscatalog_Component->add($input);

      // Add Computer
         $input = array();
         $input['name'] = 'pc1';
         $input['entities_id'] = 0;
         $pc1 = $computer->add($input);

      // Check computer pc1 not added in ressources
         $a_hosts = $pmComponentscatalog_Host->find("`plugin_monitoring_componentscalalog_id`='".$catalogs_id."'");
         $this->assertEquals(count($a_hosts), '0', '[f1] Computer in component cataglog and may not be');

      $input = array();
      $input['plugin_monitoring_componentscalalog_id'] = $catalogs_id;
      $input['name'] = 'all have name';
      $input['itemtype'] = 'Computer';
      $input['condition'] = '{"field":["1"],"searchtype":["contains"],"contains":["pc"],"itemtype":"Computer","start":"0"}';
      $rules_id = $pmComponentscatalog_rule->add($input);

      // Check computer pc1 not added in ressources
         $a_hosts = $pmComponentscatalog_Host->find("`plugin_monitoring_componentscalalog_id`='".$catalogs_id."'");
         $this->assertEquals(count($a_hosts), '1', '[f2] Computer may be in component catalog');

      // Check service of this computer created
         $a_services = $pmService->find();
         $this->assertEquals(count($a_services), '1', '[s2] One service may be created');


      // Add Computer
         $input = array();
         $input['name'] = 'pc2';
         $input['entities_id'] = 0;
         $pc2 = $computer->add($input);

      // Check computer pc1 not added in ressources
         $a_hosts = $pmComponentscatalog_Host->find("`plugin_monitoring_componentscalalog_id`='".$catalogs_id."'");
         $this->assertEquals(count($a_hosts), '2', '[f3] 2 computers may be in component catalog');

      // Check service of this computer created
         $a_services = $pmService->find();
         $this->assertEquals(count($a_services), '2', '[s3] 2 services may be created');


      // Remove pc2
         $computer->delete(array('id'=>$pc2), 1);

      // Check computer pc1 added in ressources
         $a_hosts = $pmComponentscatalog_Host->find("`plugin_monitoring_componentscalalog_id`='".$catalogs_id."'");
         $this->assertEquals(count($a_hosts), '1', '[f4] Computer may be unique in component catalog');

      // Check service of this computer created
         $a_services = $pmService->find();
         $this->assertEquals(count($a_services), '1', '[s4] One service may be created');


      // Modify rule
         $input['id'] = $rules_id;
         $input['condition'] = '{"field":["1"],"searchtype":["contains"],"contains":["tc"],"itemtype":"Computer","start":"0"}';
         $pmComponentscatalog_rule->update($input);

      // Check no computer in ressources
         $a_hosts = $pmComponentscatalog_Host->find("`plugin_monitoring_componentscalalog_id`='".$catalogs_id."'");
         $this->assertEquals(count($a_hosts), '0', '[f5] Computer may be deleted on rule update');

      // Check service
         $a_services = $pmService->find();
         $this->assertEquals(count($a_services), '0', '[s5] No service may be created');


      // Modify rule
         $input['id'] = $rules_id;
         $input['condition'] = '{"field":["1"],"searchtype":["contains"],"contains":["pc"],"itemtype":"Computer","start":"0"}';
         $pmComponentscatalog_rule->update($input);

      // Check computer pc1 added in ressources
         $a_hosts = $pmComponentscatalog_Host->find("`plugin_monitoring_componentscalalog_id`='".$catalogs_id."'");
         $this->assertEquals(count($a_hosts), '1', '[f6] Computer may be unique in component catalog');

      // Delete rule
         $pmComponentscatalog_rule->delete(array('id'=>$rules_id), 1);

      // Check not have computer in ressources
         $a_hosts = $pmComponentscatalog_Host->find("`plugin_monitoring_componentscalalog_id`='".$catalogs_id."'");
         $this->assertEquals(count($a_hosts), '0', '[f7] must have no computer in component catalog');

      // Check service
         $a_services = $pmService->find();
         $this->assertEquals(count($a_services), '0', '[s7] No service may be created');


   }

}

?>
