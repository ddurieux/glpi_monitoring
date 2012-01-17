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
      $arg = array();
      $arg['ARG1'] = 'Machine name to lookup';
      $input['arguments'] = exportArrayToDB($arg);
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
      $input['regex'] = addslashes('Uptime: (\\d+)  Threads: (\\d+)  Questions: (\\d+)  Slow queries: (\\d+)  Opens: (\\d+)  Flush tables: (\\d+)  Open tables: (\\d+)  Queries per second avg: (.+\\d+)');
      $legend = array();
      $legend[2] = "Threads";
      $legend[2] = "Questions";
      $legend[3] = "Slow_queries";
      $legend[4] = "Opens";
      $legend[5] = "Flush_tables";
      $legend[6] = "Open_tables";
      $legend[7] = "Qps_avg";
      $input['legend'] = exportArrayToDB($legend);
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
      $input['regex'] = addslashes('rta=(\\d+\\.\\d+\\w+)+;(\\d*\\.*\\d*);(\\d*\\.*\\d*);(\\d*\.*\\d*)');
      $legend = array();
      $legend[1] = "response_time";
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
      $input['name'] = 'Check disk';
      $input['command_name'] = 'check_disk';
      $input['command_line'] = "\$PLUGINSDIR\$/check_disk -w \$ARG1\$ -c \$ARG2\$ -p \$ARG3\$";
      $input['regex'] = addslashes('(.*)=(\\d+)\\w+;(\\d+);(\\d+);(\\d+);(\\d+)');
      $legend = array();
      $legend[2] = "Used";
      $legend[3] = "Warning";
      $legend[4] = "Critical";
      $legend[6] = "Total_capacity";
      $input['legend'] = exportArrayToDB($legend);
      $arg = array();
      $arg['ARG1'] = 'INTEGER: WARNING status if less than INTEGER units of disk are free\n
         PERCENT%: WARNING status if less than PERCENT of disk space is free';
      $arg['ARG2'] = 'INTEGER: CRITICAL status if less than INTEGER units of disk are free\n
         PERCENT%: CRITICAL status if less than PERCENT of disk space is free';
      $arg['ARG3'] = 'Path or partition';
      $input['arguments'] = exportArrayToDB($arg);
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
      
      $input = array();
      $input['name'] = 'Check local cpu';
      $input['command_name'] = 'check_cpu_usage';
      $input['command_line'] = "\$PLUGINSDIR\$/check_cpu_usage -w \$ARG1\$ -c \$ARG2\$";
      $input['regex'] = addslashes('cpu_usage=(\\d+)%;(\\d+);(\\d+); cpu_user=(\\d+)%; cpu_system=(\\d+)%;');
      $legend = array();
      $legend[1] = "cpu_usage";
      $legend[2] = "warning";
      $legend[3] = "critical";
      $legend[4] = "cpu_user";
      $legend[5] = "cpu_system";
      $input['legend'] = exportArrayToDB($legend);
      $arg = array();
      $arg['ARG1'] = 'Percentage of CPU for warning';
      $arg['ARG2'] = 'Percentage of CPU for critical';
      $input['arguments'] = exportArrayToDB($arg);
      $this->add($input);
      
      $input = array();
      $input['name'] = 'Check load';
      $input['command_name'] = 'check_load';
      $input['command_line'] = "\$PLUGINSDIR\$/check_load -r -w \$ARG1\$ -c \$ARG2\$";
      $arg = array();
      $arg['ARG1'] = 'WARNING status if load average exceeds WLOADn (WLOAD1,WLOAD5,WLOAD15)';
      $arg['ARG2'] = 'CRITICAL status if load average exceed CLOADn (CLOAD1,CLOAD5,CLOAD15)';
      $input['arguments'] = exportArrayToDB($arg);
      $this->add($input);
      
      $input = array();
      $input['name'] = 'Check snmp';
      $input['command_name'] = 'check_snmp';
      $input['command_line'] = "\$PLUGINSDIR\$/check_snmp -H \$HOSTADDRESS\$ -P \$ARG1\$ -C \$ARG2\$ -o \$ARG3\$,\$ARG4\$,\$ARG5\$,\$ARG6\$,\$ARG7\$,\$ARG8\$,\$ARG9\$,\$ARG10\$";
      $arg = array();
      $arg['ARG1'] = 'SNMP protocol version (1|2c|3) [SNMP:version]';
      $arg['ARG2'] = 'Community string for SNMP communication [SNMP:authentication]';
      $arg['ARG3'] = 'oid [OID:ifinoctets]';
      $arg['ARG4'] = 'oid [OID:ifoutoctets]';
      $arg['ARG5'] = 'oid [OID:ifinerrors]';
      $arg['ARG6'] = 'oid [OID:ifouterrors]';
      $arg['ARG7'] = 'oid';
      $arg['ARG8'] = 'oid';
      $arg['ARG9'] = 'oid';
      $arg['ARG10'] = 'oid';
      $input['arguments'] = exportArrayToDB($arg);
      $this->add($input);
      
      
      $input = array();
      $input['name'] = 'Check users connected';
      $input['command_name'] = 'check_users';
      $input['command_line'] = "\$PLUGINSDIR\$/check_users -w \$ARG1\$ -c \$ARG2\$";
      $input['regex'] = addslashes('users=(\\d+);(\\d+);(\\d+);(\\d+)');
      $legend = array();
      $legend[1] = "users";
      $legend[2] = "warning";
      $legend[3] = "critical";
      $input['legend'] = exportArrayToDB($legend);
      $arg = array();
      $arg['ARG1'] = 'Set WARNING status if more than INTEGER users are logged in';
      $arg['ARG2'] = 'Set CRITICAL status if more than INTEGER users are logged in';
      $input['arguments'] = exportArrayToDB($arg);
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

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['common'][16]." :</td>";
      echo "<td align='center'>";
      echo "<input type='text' name='name' value='".$this->fields["name"]."' size='30'/>";
      echo "</td>";
      echo "<td>".$LANG['plugin_monitoring']['command'][2]."&nbsp;:</td>";
      echo "<td align='center'>";
      echo "<input type='text' name='command_name' value='".$this->fields["command_name"]."' size='30'/>";
      echo "</td>";
      echo "</tr>";
      
      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_monitoring']['command'][3]."&nbsp;:</td>";
      echo "<td align='center' colspan='3'>";
      echo "<input type='text' name='command_line' value='".$this->fields["command_line"]."' size='91'/>";
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_monitoring']['command'][4]."&nbsp;:</td>";
      echo "<td colspan='3'>";
         $arguments = array();
         preg_match_all("/\\$(ARG\d+)\\$/", $this->fields['command_line'], $arguments);
         $arrayargument = importArrayFromDB($this->fields["arguments"]);
         echo "<table>";
         foreach ($arguments[0] as $adata) {
            $adata = str_replace('$', '', $adata);
            echo "<tr>";
            echo "<td>";
            echo " ".$adata. " : ";
            echo "</td>";
            echo "<td>";
            if (!isset($arrayargument[$adata])) {
               $arrayargument[$adata] = '';
            }
            echo "<textarea cols='90' rows='2' name='argument_".$adata."' >".$arrayargument[$adata]."</textarea>";
            echo "</td>";
            echo "</tr>";
         }
         echo "</table>";
      
      echo "</td>";
      echo "</tr>";
      
      
      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_monitoring']['command'][5]."&nbsp;:</td>";
      echo "<td align='center' colspan='3'>";
      echo "<input type='text' name='regex' value='".$this->fields["regex"]."' size='91'/>";
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_monitoring']['command'][6]."&nbsp;:</td>";
      echo "<td colspan='3'>";
         $split = explode("(", $this->fields["regex"]);
         echo "<input type='hidden' name='legendnb' value='".(count($split) - 1)."' />";
         $arraylegend = importArrayFromDB($this->fields["legend"]);
         echo "<table>";
         $i = 0;
         for ($i = 0; $i < count($split); $i++) {
            if ($i > 0) {
               echo "<tr>";
               echo "<td>";
               echo "Data ".$i. " : ";
               echo "</td>";
               echo "<td>";
               if (!isset($arraylegend[$i])) {
                  $arraylegend[$i] = '';
               }
               echo "<input type='text' name='legend_".$i."' value='".$arraylegend[$i]."' />";
               echo "</td>";
               echo "</tr>";
            }
         }
         echo "</table>";
      echo "</td>";
      echo "</tr>";
      
      $this->showFormButtons($options);
      $this->addDivForTabs();

      return true;
   }


   function convertPostdata($data) {
      
      // Convert Legend datas
      $legendnb = $data['legendnb'];
      $larray = array();
      for ($i = 1;$i <= $legendnb; $i++) {
         $larray[$i] = $_POST['legend_'.$i];
      }
      $data['legend'] = exportArrayToDB($larray);
      
      // Convert arguments descriptions
      $a_arguments = array();
      foreach ($data as $name=>$value) {
         if (strstr($name, "argument_")) {
            $name = str_replace("argument_", "", $name);
            $a_arguments[$name] = $value;            
         }
      }
      $data['arguments'] = exportArrayToDB($a_arguments);      
      return $data;
   }
   
}

?>