<?php

/*
   ------------------------------------------------------------------------
   Plugin Monitoring for GLPI
   Copyright (C) 2011-2012 by the Plugin Monitoring for GLPI Development Team.

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
   along with Behaviors. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   Plugin Monitoring for GLPI
   @author    David Durieux
   @co-author 
   @comment   
   @copyright Copyright (c) 2011-2012 Plugin Monitoring for GLPI team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      https://forge.indepnet.net/projects/monitoring/
   @since     2011
 
   ------------------------------------------------------------------------
 */

define ("PLUGIN_MONITORING_VERSION","0.80+1.3");

// Init the hooks of monitoring
function plugin_init_monitoring() {
   global $PLUGIN_HOOKS,$LANG;
   
   $PLUGIN_HOOKS['change_profile']['monitoring'] = array('PluginMonitoringProfile','changeprofile');
   
   $Plugin = new Plugin();
   if ($Plugin->isActivated('monitoring')) {
      if (isset($_SESSION["glpiID"])) {
         Plugin::loadLang("monitoring");
         
         $PLUGIN_HOOKS['use_massive_action']['monitoring']=1;
         $PLUGIN_HOOKS['add_css']['monitoring']="css/views.css";
         
         $plugin = new Plugin();
         if ($plugin->isActivated('monitoring')) {
            
            $PLUGIN_HOOKS['menu_entry']['monitoring'] = true;
         }

         $PLUGIN_HOOKS['config_page']['monitoring'] = 'front/config.form.php';
         $PLUGIN_HOOKS['submenu_entry']['monitoring']['config'] = 'front/config.form.php';
         
         // Tabs for each type
         $PLUGIN_HOOKS['headings']['monitoring'] = 'plugin_get_headings_monitoring';
         $PLUGIN_HOOKS['headings_action']['monitoring'] = 'plugin_headings_actions_monitoring';
         
         // Icons add, search...
         $PLUGIN_HOOKS['submenu_entry']['monitoring']['add']['commands'] = 'front/command.form.php?add=1';
         $PLUGIN_HOOKS['submenu_entry']['monitoring']['search']['commands'] = 'front/command.php';

         $PLUGIN_HOOKS['submenu_entry']['monitoring']['add']['checks'] = 'front/check.form.php?add=1';
         $PLUGIN_HOOKS['submenu_entry']['monitoring']['search']['checks'] = 'front/check.php';

         $PLUGIN_HOOKS['submenu_entry']['monitoring']['add']['componentscatalog'] = 'front/componentscatalog.form.php?add=1';
         $PLUGIN_HOOKS['submenu_entry']['monitoring']['search']['componentscatalog'] = 'front/componentscatalog.php';

         $PLUGIN_HOOKS['submenu_entry']['monitoring']['add']['servicescatalog'] = 'front/servicescatalog.form.php?add=1';
         $PLUGIN_HOOKS['submenu_entry']['monitoring']['search']['servicescatalog'] = 'front/servicescatalog.php';
         
         $PLUGIN_HOOKS['submenu_entry']['monitoring']['add']['components'] = 'front/component.form.php?add=1';
         $PLUGIN_HOOKS['submenu_entry']['monitoring']['search']['components'] = 'front/component.php';
         
         $PLUGIN_HOOKS['submenu_entry']['monitoring']['add']['contacttemplates'] = 'front/contacttemplate.form.php?add=1';
         $PLUGIN_HOOKS['submenu_entry']['monitoring']['search']['contacttemplates'] = 'front/contacttemplate.php';
         
         $PLUGIN_HOOKS['submenu_entry']['monitoring']['add']['displayview'] = 'front/displayview.form.php?add=1';
         $PLUGIN_HOOKS['submenu_entry']['monitoring']['search']['displayview'] = 'front/displayview.php';
         
         $PLUGIN_HOOKS['submenu_entry']['monitoring']['add']['rrdtemplates'] = 'front/rrdtemplate.form.php?add=1';
         $PLUGIN_HOOKS['submenu_entry']['monitoring']['search']['rrdtemplates'] = 'front/rrdtemplate.php';

         $PLUGIN_HOOKS['submenu_entry']['monitoring']['add']['PluginMonitoringRealm'] = 'front/realm.form.php?add=1';
         $PLUGIN_HOOKS['submenu_entry']['monitoring']['search']['PluginMonitoringRealm'] = 'front/realm.php';

         $PLUGIN_HOOKS['submenu_entry']['monitoring']['add']['weathermap'] = 'front/weathermap.form.php?add=1';
         $PLUGIN_HOOKS['submenu_entry']['monitoring']['search']['weathermap'] = 'front/weathermap.php';
         
         $PLUGIN_HOOKS['submenu_entry']['monitoring']['search']['service'] = 'front/display.php';
         
         // Fil ariane
         $PLUGIN_HOOKS['submenu_entry']['monitoring']['options']['components']['title'] = $LANG['plugin_monitoring']['component'][0];
         $PLUGIN_HOOKS['submenu_entry']['monitoring']['options']['components']['page']  = '/plugins/monitoring/front/component.php';

         $PLUGIN_HOOKS['submenu_entry']['monitoring']['options']['contacttemplates']['title'] = $LANG['plugin_monitoring']['contacttemplate'][0];
         $PLUGIN_HOOKS['submenu_entry']['monitoring']['options']['contacttemplates']['page']  = '/plugins/monitoring/front/contacttemplate.php';

         $PLUGIN_HOOKS['submenu_entry']['monitoring']['options']['componentscatalog']['title'] = $LANG['plugin_monitoring']['componentscatalog'][0];
         $PLUGIN_HOOKS['submenu_entry']['monitoring']['options']['componentscatalog']['page']  = '/plugins/monitoring/front/componentscatalog.php';
         
         $PLUGIN_HOOKS['submenu_entry']['monitoring']['options']['servicescatalog']['title'] = $LANG['plugin_monitoring']['servicescatalog'][0];
         $PLUGIN_HOOKS['submenu_entry']['monitoring']['options']['servicescatalog']['page']  = '/plugins/monitoring/front/servicescatalog.php';
         
         $PLUGIN_HOOKS['submenu_entry']['monitoring']['options']['display']['title'] = $LANG['plugin_monitoring']['display'][0];
         $PLUGIN_HOOKS['submenu_entry']['monitoring']['options']['display']['page']  = '/plugins/monitoring/front/display.php';

         
         // Define hook item
         $rule_check = array('PluginMonitoringComponentscatalog_rule','isThisItemCheckRule');
         $PLUGIN_HOOKS['item_add']['monitoring'] = 
                                 array('Computer'         => $rule_check,
                                       'NetworkEquipment' => $rule_check,
                                       'Printer'          => $rule_check,
                                       'Peripheral'       => $rule_check,
                                       'Phone'            => $rule_check,
                                       'PluginMonitoringComponentscatalog_rule' =>
                                             array('PluginMonitoringComponentscatalog_rule','getItemsDynamicly'));
         $PLUGIN_HOOKS['item_update']['monitoring'] = 
                                 array('Computer'         => $rule_check,
                                       'NetworkEquipment' => $rule_check,
                                       'Printer'          => $rule_check,
                                       'Peripheral'       => $rule_check,
                                       'Phone'            => $rule_check,
                                       'PluginMonitoringComponentscatalog_rule' =>
                                             array('PluginMonitoringComponentscatalog_rule','getItemsDynamicly'));
         $PLUGIN_HOOKS['item_purge']['monitoring'] = 
                                 array('Computer'         => $rule_check,
                                       'NetworkEquipment' => $rule_check,
                                       'Printer'          => $rule_check,
                                       'Peripheral'       => $rule_check,
                                       'Phone'            => $rule_check,
                                       'PluginMonitoringComponentscatalog_rule' =>
                                             array('PluginMonitoringComponentscatalog_rule','getItemsDynamicly'),
                                       'PluginMonitoringComponentscatalog_Host' =>
                                             array('PluginMonitoringComponentscatalog_Host','unlinkComponentsToItem'),
                                       'PluginMonitoringComponentscatalog' =>
                                             array('PluginMonitoringComponentscatalog','removeCatalog'),
                                       'PluginMonitoringBusinessrulegroup' =>
                                             array('PluginMonitoringBusinessrule','removeBusinessruleonDeletegroup'));

         if (!isset($_SESSION['glpi_plugin_monitoring']['_refresh'])) {
            $_SESSION['glpi_plugin_monitoring']['_refresh'] = '60';
         }
      }

      $PLUGIN_HOOKS['webservices']['monitoring'] = 'plugin_monitoring_registerMethods';
      
   }
   return $PLUGIN_HOOKS;
}

// Name and Version of the plugin
function plugin_version_monitoring() {
   return array('name'           => 'Monitoring',
                'shortname'      => 'monitoring',
                'version'        => PLUGIN_MONITORING_VERSION,
                'author'         =>'<a href="mailto:d.durieux@siprossii.com">David DURIEUX</a>',
                'homepage'       =>'https://forge.indepnet.net/projects/monitoring/',
                'minGlpiVersion' => '0.80'
   );
}


// Optional : check prerequisites before install : may print errors or add to message after redirect
function plugin_monitoring_check_prerequisites() {

   if (version_compare(GLPI_VERSION,'0.80','lt') || version_compare(GLPI_VERSION,'0.81','ge')) {
      echo "error";
   } else {
      return true;
   }
}

function plugin_monitoring_check_config() {
   return true;
}

function plugin_monitoring_haveTypeRight($type,$right) {
   return true;
}

?>