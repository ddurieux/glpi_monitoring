<?php

/*
   ----------------------------------------------------------------------
   Monitoring plugin for GLPI
   Copyright (C) 2010-2011 by the GLPI plugin monitoring Team.

   https://forge.indepnet.net/projects/monitoring/
   ----------------------------------------------------------------------

   LICENSE

   This file is part of Monitoring plugin for GLPI.

   Monitoring plugin for GLPI is free software: you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation, either version 2 of the License, or
   any later version.

   Monitoring plugin for GLPI is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with Monitoring plugin for GLPI.  If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------
   Original Author of file: David DURIEUX
   Co-authors of file:
   Purpose of file:
   ----------------------------------------------------------------------
 */

class ManageRessources extends PHPUnit_Framework_TestCase {

   public function testPHPlogs() {
      global $CFG_GLPI, $PLUGIN_HOOKS;
      
      $_SESSION['glpi_use_mode'] = 2;
      $_SESSION["glpiID"] = 2;
      $_SESSION["glpiactiveentities_string"] = 0;
      $PLUGIN_HOOKS = plugin_init_monitoring();
      plugin::load("monitoring");
      
      $CFG_GLPI['root_doc'] = "http://127.0.0.1/fusion0.80/";

      loadLanguage("en_GB");
      
      $pmComponent = new PluginMonitoringComponent();
      $pmComponentscatalog = new PluginMonitoringComponentscatalog();
      $pmComponentscatalog_Component = new PluginMonitoringComponentscatalog_Component();
      $pmComponentscatalog_rule = new PluginMonitoringComponentscatalog_rule();
      $pmComponentscatalog_Host = new PluginMonitoringComponentscatalog_Host();
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
      $pmComponentscatalog_rule->add($input);
      
      // Check computer pc1 not added in ressources
         $a_hosts = $pmComponentscatalog_Host->find("`plugin_monitoring_componentscalalog_id`='".$catalogs_id."'");
         $this->assertEquals(count($a_hosts), '1', '[f2] Computer may be in component catalog'); 
      
      // Add Computer
         $input = array();
         $input['name'] = 'pc2';
         $input['entities_id'] = 0;
         $pc2 = $computer->add($input);
         
      // Check computer pc1 not added in ressources
         $a_hosts = $pmComponentscatalog_Host->find("`plugin_monitoring_componentscalalog_id`='".$catalogs_id."'");
         $this->assertEquals(count($a_hosts), '2', '[f3] 2 computers may be in component catalog'); 
      
      // Remove pc2
         $computer->delete(array('id'=>$pc2), 1);
         
      // Check computer pc1 not added in ressources
         $a_hosts = $pmComponentscatalog_Host->find("`plugin_monitoring_componentscalalog_id`='".$catalogs_id."'");
         $this->assertEquals(count($a_hosts), '1', '[f4] Computer may be unique in component catalog'); 
      
      
         
   }
   
}



class ManageRessources_AllTests  {

   public static function suite() {
      
      $suite = new PHPUnit_Framework_TestSuite('ManageRessources');
      return $suite;
   }
}
?>
