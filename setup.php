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
      }


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