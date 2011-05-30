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

class PluginMonitoringNotificationcommand extends CommonDBTM {
   

   function initCommands() {
      global $DB;

      $input = array();
      $input['name'] = 'Host : notify by mail';
      $input['command_name'] = 'notify-host-by-email';
      $input['command_line'] = "\$PLUGINSDIR\$/sendmailhost.pl \"\$NOTIFICATIONTYPE\$\" \"\$HOSTNAME\$\" \"\$HOSTSTATE\$\" \"\$HOSTADDRESS\$\" \"\$HOSTOUTPUT\$\" \"\$SHORTDATETIME\$\" \"\$CONTACTEMAIL\$\"";
      $this->add($input);

      $input = array();
      $input['name'] = 'Service : notify by mail';
      $input['command_name'] = 'notify-service-by-email';
      $input['command_line'] = "\$PLUGINSDIR\$/sendmailservices.pl \"\$NOTIFICATIONTYPE\$\" \"\$SERVICEDESC\$\" \"\$HOSTALIAS\$\" \"\$HOSTADDRESS\$\" \"\$SERVICESTATE\$\" \"\$SHORTDATETIME\$\" \"\$SERVICEOUTPUT\$\" \"\$CONTACTEMAIL\$\" \"\$SERVICENOTESURL\$\"";
      $this->add($input);
      
   }


   /**
   * Get name of this type
   *
   *@return text name of this type by language of the user connected
   *
   **/
   static function getTypeName() {
      global $LANG;

      return "notification commands";
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

   

   function getSearchOptions() {
      global $LANG;

      $tab = array();
    
      $tab['common'] = "notification commands";

		$tab[1]['table'] = $this->getTable();
		$tab[1]['field'] = 'name';
		$tab[1]['linkfield'] = 'name';
		$tab[1]['name'] = $LANG['common'][16];
		$tab[1]['datatype'] = 'itemlink';

      $tab[2]['table']     = $this->getTable();
      $tab[2]['field']     = 'is_active';
      $tab[2]['linkfield'] = 'is_active';
      $tab[2]['name']      = $LANG['common'][60];
      $tab[2]['datatype']  = 'bool';

      return $tab;
   }



   function defineTabs($options=array()){
      global $LANG,$CFG_GLPI;

      $ong = array();

      return $ong;
   }



   /**
   * Display form for agent configuration
   *
   * @param $items_id integer ID 
   * @param $options array
   *
   *@return bool true if form is ok
   *
   **/
   function showForm($items_id, $options=array()) {
      global $DB,$CFG_GLPI,$LANG;

      if ($items_id!='') {
         $this->getFromDB($items_id);
      } else {
         $this->getEmpty();
      }

      $this->showTabs($options);
      $this->showFormHeader($options);


      
      $this->showFormButtons($options);
      $this->addDivForTabs();

      return true;
   }


}

?>