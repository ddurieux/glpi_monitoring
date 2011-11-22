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

define ("PLUGIN_MONITORING_VERSION","1.0.0");

// Init the hooks of monitoring
function plugin_init_monitoring() {
   global $PLUGIN_HOOKS,$CFG_GLPI,$LANG;

      if (isset($_SESSION["glpiID"])) {

         $PLUGIN_HOOKS['use_massive_action']['monitoring']=1;

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

         $PLUGIN_HOOKS['submenu_entry']['monitoring']['add']['components'] = 'front/component.form.php?add=1';
         $PLUGIN_HOOKS['submenu_entry']['monitoring']['search']['components'] = 'front/component.php';
         
         $PLUGIN_HOOKS['submenu_entry']['monitoring']['add']['contacttemplates'] = 'front/contacttemplate.form.php?add=1';
         $PLUGIN_HOOKS['submenu_entry']['monitoring']['search']['contacttemplates'] = 'front/contacttemplate.php';
         
         
         $PLUGIN_HOOKS['submenu_entry']['monitoring']['search']['service'] = 'front/display.php';
         
         // Fil ariane
         $PLUGIN_HOOKS['submenu_entry']['monitoring']['options']['components']['title'] = "Components";
         $PLUGIN_HOOKS['submenu_entry']['monitoring']['options']['components']['page']  = '/plugins/monitoring/front/component.php';

         $PLUGIN_HOOKS['submenu_entry']['monitoring']['options']['contacttemplates']['title'] = "Contacttemplates";
         $PLUGIN_HOOKS['submenu_entry']['monitoring']['options']['contacttemplates']['page']  = '/plugins/monitoring/front/contacttemplate.php';
        
         
         // Define hook item
         $rule_check = array('PluginMonitoringComponentscatalog_rule','isThisItemCheckRule');
         $PLUGIN_HOOKS['item_add']['monitoring'] = 
                                 array('Computer'         => $rule_check,
                                       'NetworkEquipment' => $rule_check,
                                       'Printer'          => $rule_check,
                                       'Peripheral'       => $rule_check,
                                       'Phone'            => $rule_check);
         $PLUGIN_HOOKS['item_update']['monitoring'] = 
                                 array('Computer'         => $rule_check,
                                       'NetworkEquipment' => $rule_check,
                                       'Printer'          => $rule_check,
                                       'Peripheral'       => $rule_check,
                                       'Phone'            => $rule_check);


      }

   $PLUGIN_HOOKS['webservices']['monitoring'] = 'plugin_monitoring_registerMethods';
}

// Name and Version of the plugin
function plugin_version_monitoring() {
   return array('name'           => 'Monitoring',
                'shortname'      => 'monitoring',
                'version'        => PLUGIN_MONITORING_VERSION,
                'oldname'        => 'tracker',
                'author'         =>'<a href="mailto:d.durieux@siprossii.com">David DURIEUX</a>',
                'homepage'       =>'https://forge.indepnet.net/projects/monitoring/',
                'minGlpiVersion' => '0.80'
   );
}


// Optional : check prerequisites before install : may print errors or add to message after redirect
function plugin_monitoring_check_prerequisites() {
   global $LANG;
   if (GLPI_VERSION >= '0.80') {
      return true;
   } else {
      echo "error";
   }
}

function plugin_monitoring_check_config() {
   return true;
}

function plugin_monitoring_haveTypeRight($type,$right) {
   return true;
}

?>