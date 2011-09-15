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

class PluginMonitoringCommand extends CommonDBTM {
   

   function initCommands() {
      global $DB;

      $input = array();
      $input['name'] = 'Simple tcp port check';
      $input['command_name'] = 'check_tcp';
      $input['command_line'] = "\$PLUGINSDIR\$/check_tcp  -H \$HOSTADDRESS\$ -p \$ARG1$";
      $input['regex'] = addslashes('time=(\\d+\\.\\d+)\\w;(\\d*\\.*\\d*);(\\d*\\.*\\d*);(\\d*\\.*\\d*);(\\d*\\.*\\d*)');
      $legend = array();
      $legend[1] = "response_time";
      $legend[2] = "warning_time";
      $legend[3] = "critical_time";
      $legend[5] = "timeout_time";
      $input['legend'] = exportArrayToDB($legend);
      $input['unit'] = "ms"; 
      $this->add($input);

      $input = array();
      $input['name'] = 'Simple web check';
      $input['command_name'] = 'check_http';
      $input['command_line'] = "\$PLUGINSDIR\$/check_http -H \$HOSTADDRESS\$";
      $input['regex'] = addslashes('time=(\\d+\\.\\d+)\\w;(\\d*\\.*\\d*);(\\d*\\.*\\d*);(\\d*\\.*\\d*)');
      $legend = array();
      $legend[1] = "response_time";
      $legend[2] = "warning_time";
      $legend[3] = "critical_time";
      $legend[4] = "timeout_time";
      $input['legend'] = exportArrayToDB($legend);
      $input['unit'] = "ms";
      $this->add($input);

      $input = array();
      $input['name'] = 'Simple web check with SSL';
      $input['command_name'] = 'check_https';
      $input['command_line'] = "\$PLUGINSDIR\$/check_http -H \$HOSTADDRESS\$ -S";
      $input['regex'] = addslashes('time=(\\d+\\.\\d+)\\w;(\\d*\\.*\\d*);(\\d*\\.*\\d*);(\\d*\\.*\\d*)');
      $legend = array();
      $legend[1] = "response_time";
      $legend[2] = "warning_time";
      $legend[3] = "critical_time";
      $legend[4] = "timeout_time";
      $input['legend'] = exportArrayToDB($legend);
      $input['unit'] = "ms";
      $this->add($input);
      
      $input = array();
      $input['name'] = 'Check a DNS entry';
      $input['command_name'] = 'check_dig';
      $input['command_line'] = "\$PLUGINSDIR\$/check_dig -H \$HOSTADDRESS\$ -l \$ARG1\$";
      $input['regex'] = addslashes('time=(\\d+\\.\\d+)\\w;(\\d*\\.*\\d*);(\\d*\\.*\\d*);(\\d*\\.*\\d*)');
      $legend = array();
      $legend[1] = "response_time";
      $legend[2] = "warning_time";
      $legend[3] = "critical_time";
      $legend[4] = "timeout_time";
      $input['legend'] = exportArrayToDB($legend);
      $input['unit'] = "ms";
      $this->add($input);

      $input = array();
      $input['name'] = 'Check a FTP service';
      $input['command_name'] = 'check_ftp';
      $input['command_line'] = "\$PLUGINSDIR\$/check_ftp -H \$HOSTADDRESS\$";
      $input['regex'] = addslashes('time=(\\d+\\.\\d+)\\w;(\\d*\\.*\\d*);(\\d*\\.*\\d*);(\\d*\\.*\\d*);(\\d*\\.*\\d*)');
      $legend = array();
      $legend[1] = "response_time";
      $legend[2] = "warning_time";
      $legend[3] = "critical_time";
      $legend[6] = "timeout_time";
      $input['legend'] = exportArrayToDB($legend);
      $input['unit'] = "ms";
      $this->add($input);

      $input = array();
      $input['name'] = 'Ask a nrpe agent';
      $input['command_name'] = 'check_nrpe';
      $input['command_line'] = "\$PLUGINSDIR\$/check_nrpe -H \$HOSTADDRESS\$ -t 9 -u -c \$ARG1\$";
      $this->add($input);

      $input = array();
      $input['name'] = 'Simple ping command';
      $input['command_name'] = 'check_ping';
      $input['command_line'] = "\$PLUGINSDIR\$/check_ping -H \$HOSTADDRESS\$ -w 3000,100% -c 5000,100% -p 1";
      $input['regex'] = addslashes('time=(\\d+\\.\\d+)\\w;(\\d*\\.*\\d*);(\\d*\\.*\\d*);(\\d*\\.*\\d*)');
      $legend = array();
      $legend[1] = "response_time";
      $legend[2] = "warning_time";
      $legend[3] = "critical_time";
      $input['legend'] = exportArrayToDB($legend);
      $input['unit'] = "ms";
      $this->add($input);

      $input = array();
      $input['name'] = 'Look at good ssh launch';
      $input['command_name'] = 'check_ssh';
      $input['command_line'] = "\$PLUGINSDIR\$/check_ssh -H \$HOSTADDRESS\$";
      $this->add($input);
      
      $input = array();
      $input['name'] = 'Look for good SMTP connexion';
      $input['command_name'] = 'check_smtp';
      $input['command_line'] = "\$PLUGINSDIR\$/check_smtp -H \$HOSTADDRESS\$";
      $input['regex'] = addslashes('time=(\\d+\\.\\d+)\\w;(\\d*\\.*\\d*);(\\d*\\.*\\d*);(\\d*\\.*\\d*)');
      $legend = array();
      $legend[1] = "response_time";
      $legend[2] = "warning_time";
      $legend[3] = "critical_time";
      $input['legend'] = exportArrayToDB($legend);
      $input['unit'] = "ms";
      $this->add($input);

      $input = array();
      $input['name'] = 'Look for good SMTPS connexion';
      $input['command_name'] = 'check_smtps';
      $input['command_line'] = "\$PLUGINSDIR\$/check_smtp -H \$HOSTADDRESS\$ -S";
      $input['regex'] = addslashes('time=(\\d+\\.\\d+)\\w;(\\d*\\.*\\d*);(\\d*\\.*\\d*);(\\d*\\.*\\d*)');
      $legend = array();
      $legend[1] = "response_time";
      $legend[2] = "warning_time";
      $legend[3] = "critical_time";
      $input['legend'] = exportArrayToDB($legend);
      $input['unit'] = "ms";
      $this->add($input);

      $input = array();
      $input['name'] = 'Look at a SSL certificate';
      $input['command_name'] = 'check_https_certificate';
      $input['command_line'] = "\$PLUGINSDIR\$/check_http -H \$HOSTADDRESS\$ -C 30";
      $this->add($input);

      $input = array();
      $input['name'] = 'Look at an HP printer state';
      $input['command_name'] = 'check_hpjd';
      $input['command_line'] = "\$PLUGINSDIR\$/check_hpjd -H \$HOSTADDRESS\$ -C \$SNMPCOMMUNITYREAD\$";
      $this->add($input);

      $input = array();
      $input['name'] = 'Look at Oracle connexion';
      $input['command_name'] = 'check_oracle_listener';
      $input['command_line'] = "\$PLUGINSDIR\$/check_oracle --tns \$HOSTADDRESS\$";
      $this->add($input);

      $input = array();
      $input['name'] = 'Look at MSSQL connexion';
      $input['command_name'] = 'check_mssql_connexion';
      $input['command_line'] = "\$PLUGINSDIR\$/check_mssql_health --hostname \$HOSTADDRESS\$ --username \"\$MSSQLUSER\$\" --password \"\$MSSQLPASSWORD\$\" --mode connection-time";
      $this->add($input);

      $input = array();
      $input['name'] = 'Ldap query';
      $input['command_name'] = 'check_ldap';
      $input['command_line'] = "\$PLUGINSDIR\$/check_ldap -H \$HOSTADDRESS\$ -b \"\$LDAPBASE\$\" -D \$DOMAINUSER\$ -P \"\$DOMAINPASSWORD\$\"";
      $this->add($input);

      $input = array();
      $input['name'] = 'Ldaps query';
      $input['command_name'] = 'check_ldaps';
      $input['command_line'] = "\$PLUGINSDIR\$/check_ldaps -H \$HOSTADDRESS\$ -b \"\$LDAPBASE\$\" -D \$DOMAINUSER\$ -P \"\$DOMAINPASSWORD\$\"";
      $this->add($input);

      $input = array();
      $input['name'] = 'Distant mysql check';
      $input['command_name'] = 'check_mysql_connexion';
      $input['command_line'] = "\$PLUGINSDIR\$/check_mysql -H \$HOSTADDRESS\$ -u \$MYSQLUSER\$ -p \$MYSQLPASSWORD\$";
      $this->add($input);

      $input = array();
      $input['name'] = 'ESX hosts checks';
      $input['command_name'] = 'check_esx_host';
      $input['command_line'] = "\$PLUGINSDIR\$/check_esx3.pl -D \$VCENTER\$ -H \$HOSTADDRESS\$ -u \$VCENTERLOGIN\$ -p \$VCENTERPASSWORD\$ l \$ARG1\$";
      $this->add($input);

      $input = array();
      $input['name'] = 'ESX VM checks';
      $input['command_name'] = 'check_esx_vm';
      $input['command_line'] = "\$PLUGINSDIR\$/check_esx3.pl -D \$VCENTER\$ -N \$HOSTALIAS\$ -u \$VCENTERLOGIN\$ -p \$VCENTERLOGIN\$ -l \$ARG1\$";
      $this->add($input);

      $input = array();
      $input['name'] = 'Check Linux host alive';
      $input['command_name'] = 'check_linux_host_alive';
      $input['command_line'] = "\$PLUGINSDIR\$/check_tcp -H \$HOSTADDRESS\$ -p 22 -t 3";
      $input['regex'] = addslashes('time=(\\d+\\.\\d+)\\w;(\\d*\\.*\\d*);(\\d*\\.*\\d*);(\\d*\\.*\\d*);(\\d*\\.*\\d*)');
      $legend = array();
      $legend[1] = "response_time";
      $legend[2] = "warning_time";
      $legend[3] = "critical_time";
      $legend[5] = "timeout_time";
      $input['legend'] = exportArrayToDB($legend);
      $input['unit'] = "ms";
      $this->add($input);

      $input = array();
      $input['name'] = 'Check host alive';
      $input['command_name'] = 'check_host_alive';
      $input['command_line'] = "\$PLUGINSDIR\$/check_ping -H \$HOSTADDRESS\$ -w 1,50% -c 2,70% -p 1";
      $input['regex'] = addslashes('time=(\\d+\\.\\d+)\\w;(\\d*\\.*\\d*);(\\d*\\.*\\d*);(\\d*\\.*\\d*)');
      $legend = array();
      $legend[1] = "response_time";
      $legend[2] = "warning_time";
      $legend[3] = "critical_time";
      $input['legend'] = exportArrayToDB($legend);
      $input['unit'] = "ms";
      $this->add($input);

      $input = array();
      $input['name'] = 'Check Windows host alive';
      $input['command_name'] = 'check_windows_host_alive';
      $input['command_line'] = "\$PLUGINSDIR\$/check_tcp -H \$HOSTADDRESS\$ -p 139 -t 3";
      $input['regex'] = addslashes('time=(\\d+\\.\\d+)\\w;(\\d*\\.*\\d*);(\\d*\\.*\\d*);(\\d*\\.*\\d*);(\\d*\\.*\\d*)');
      $legend = array();
      $legend[1] = "response_time";
      $legend[2] = "warning_time";
      $legend[3] = "critical_time";
      $legend[5] = "timeout_time";
      $input['legend'] = exportArrayToDB($legend);
      $input['unit'] = "ms";
      $this->add($input);

      $input = array();
      $input['name'] = 'Check local disk';
      $input['command_name'] = 'check_local_disk';
      $input['command_line'] = "\$PLUGINSDIR\$/check.sh \$HOSTADDRESS\$ -c \$ARG1\$ SERVICE \$USER1\$";
      $this->add($input);

      $input = array();
      $input['name'] = 'Check local disk';
      $input['command_name'] = 'check-host-alive';
      $input['command_line'] = "\$PLUGINSDIR\$/check.sh \$HOSTADDRESS\$ -c \$ARG1\$ SERVICE \$USER1\$";
      $this->add($input);
   
      $input = array();
      $input['name'] = 'Business rules';
      $input['command_name'] = 'bp_rule';
      $input['command_line'] = "";
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

      return $LANG['plugin_monitoring']['command'][0];
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
    
      $tab['common'] = $LANG['plugin_monitoring']['command'][0];

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