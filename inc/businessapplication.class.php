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

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginMonitoringBusinessapplication extends CommonDropdown {
   
   /**
   * Get name of this type
   *
   *@return text name of this type by language of the user connected
   *
   **/
   static function getTypeName() {
      global $LANG;

      return "Business application";
   }



   function canCreate() {
      return true;
   }


   
   function canView() {
      return true;
   }


   
   function canCancel() {
      return true;
   }


   
   function canUndo() {
      return true;
   }


   
   function canValidate() {
      return true;
   }

   
   function showBAChecks() {
      global $LANG;
      
      echo "<table>";
      echo "<tr>";
      
         $a_ba = $this->find();
         foreach ($a_ba as $data) {
            echo "<td>";
            
            echo "<table  class='tab_cadre' width='200'>";
            echo "<tr>";
            echo "<th colspan='2'>";
            echo $data['name'];
            echo "</th>";
            echo "</tr>";
            
            echo "<tr class='tab_bg_1'>";
            echo "<td>";
            echo $LANG['state'][0]."&nbsp:";
            echo "</td>";
            echo "<td width='30'>";
     echo "OK";
            echo "</td>";
            echo "</tr>";
            echo "</table>";
            
            echo "</td>";
         }     
      
      
      echo "</tr>";
      echo "</table>";
      
   }
   
   
}

?>