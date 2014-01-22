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
   @since     2014
 
   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginMonitoringSecurity extends CommonDBTM {
   

   /**
   * Get name of this type
   *
   *@return text name of this type by language of the user connected
   *
   **/
   static function getTypeName($nb=0) {
      return __('Security', 'monitoring');
   }



   static function canCreate() {
      return true;
   }


   
   static function canView() {
      return true;
   }


   
   function generateKey($length=250) {
     $random = '';
     for ($i = 0; $i < $length; $i++) {
        $rnd = mt_rand(1, 3);
        if ($rnd == 1) {
           $random .= chr(mt_rand(48, 57));
        } else if ($rnd == 2) {
           $random .= chr(mt_rand(65, 90));
        } else if ($rnd == 3) {
           $random .= chr(mt_rand(97, 122));
        }
     }
     return $random;
   }


   
   /**
    * Check if security key in ajax page is same than kay of DB
    */
   function isSecure() {
      $a_data = $this->find("`users_id`='".$_SESSION['glpiID']."'", '', 1);
      if (count($a_data) == 1) {
         $data = current($a_data);
         if (isset($_SESSION['plugin_monitoring_securekey'])
                 && $_SESSION['plugin_monitoring_securekey'] == $data['key']) {
            // It's ok
            return;
         }
      }
      Html::displayRightError();
   }
   
   
   
   function updateSecurity() {
      
      if (isset($_SESSION['plugin_monitoring_checktime'])) {
         // Its a monitoring ajax page
         $maxlifetime = ini_get("session.gc_maxlifetime");
         if (date('U') > ($_SESSION['plugin_monitoring_lastsessiontime'] + $maxlifetime - 200)) {
            $a_data = $this->find("`users_id`='".$_SESSION['glpiID']."'", '', 1);
            if (count($a_data) == 1) {
               $data = current($a_data);
               if (date('U') > (strtotime($data['last_session_start']) + $maxlifetime - 200)) {
                  session_start();
                  $data['last_session_start'] = date('Y-m-d H:i:s');
                  $this->update($data);
               }
            }
            $_SESSION['plugin_monitoring_lastsessiontime'] = date('U');
         }
      } else {
         // It's standard page
         $_SESSION['plugin_monitoring_lastsessiontime'] = date('U');
         $a_data = $this->find("`users_id`='".$_SESSION['glpiID']."'", '', 1);
         if (count($a_data) == 1) {
            $data = current($a_data);
            if (session_id() != $data['session_id']) {
               $data['key'] = $this->generateKey();
               $data['session_id'] = session_id();
               $data['last_session_start'] = $_SESSION['glpi_currenttime'];
               $this->update($data);
            }
            $_SESSION['plugin_monitoring_securekey'] = $data['key'];
         } else {
            $input = array(
                'users_id' => $_SESSION['glpiID'],
                'key' => $this->generateKey(),
                'session_id' => session_id(),
                'last_session_start' => $_SESSION['glpi_currenttime']
            );
            $this->add($input);
            $_SESSION['plugin_monitoring_securekey'] = $input['key'];
         }
      }
   }
   
   

   static function setCheckSessionTime() {
      $_SESSION['plugin_monitoring_checktime'] = 1;
   }
   
   
   
   static function deleteCheckSessionTime() {
      if (isset($_SESSION['plugin_monitoring_checktime'])) {
         unset($_SESSION['plugin_monitoring_checktime']);
      }
   }

}

?>