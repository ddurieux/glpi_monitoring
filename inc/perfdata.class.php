<?php

/*
   ------------------------------------------------------------------------
   Plugin Monitoring for GLPI
   Copyright (C) 2011-2013 by the Plugin Monitoring for GLPI Development Team.

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
   @copyright Copyright (c) 2011-2013 Plugin Monitoring for GLPI team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      https://forge.indepnet.net/projects/monitoring/
   @since     2012
 
   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginMonitoringPerfdata extends CommonDBTM {

   
   static function listPerfdata() {
      $a_list = array();
      $a_list[""]                   = Dropdown::EMPTY_VALUE;
      $a_list["check_ping"]         = "check_ping";
      $a_list["check_cpu_usage"]    = "check_cpu_usage";
      $a_list["check_load"]         = "check_load";
      $a_list["check_mem"]          = "check_mem";
      $a_list["check_users"]        = "check_users";
      $a_list["check_iftraffic41"]  = "check_iftraffic41";
      $a_list["check_iftraffic5"]   = "check_iftraffic5";
      $a_list["check_pf"]           = "check_pf";
      $a_list["check_dig"]          = "check_dig";
      $a_list["check_disk"]         = "check_disk";
      $a_list["check_dns"]          = "check_dns";
      $a_list["check_http"]         = "check_http";
      $a_list["check_pop"]          = "check_pop";
      $a_list["check_smtp"]         = "check_smtp";
      $a_list["check_mysql_health__connection-time"] = "check_mysql_health__connection_time";
      $a_list["check_mysql_health__tmp_disk_tables"] = "check_mysql_health__tmp_disk_tables";
      $a_list["check_mysql_health__threads_connected"] = "check_mysql_health__threads_connected";
      $a_list["check_snmp_memory"] = "check_snmp_memory";
      $a_list["check_snmp_load__stand"] = "check_snmp_load__stand";
      $a_list["check_snmp_storage"] = "check_snmp_storage";
      $a_list["check_tcp"] = "check_tcp";
      $a_list["check_iostat_bsd"] = "check_iostat_bsd";
      $a_list["cucumber_nagios"] = "cucumber_nagios";
      $a_list["check_nginx_status"] = "check_nginx_status";
            
      ksort($a_list);
      return $a_list;
   }
   
   
   
   static function perfdata_check_ping() {
      
      $data = array();
      $data['command'] = 'check_ping';
      $data['parseperfdata'] = array();
      
      $ds = array();
      $ds[] = array('dsname' => 'response_time');
      $ds[] = array('dsname' => 'warning_limit_rta');
      $ds[] = array('dsname' => 'critical_limit_rta');
      $ds[] = array('dsname' => 'other_rta');
      $data['parseperfdata'][] = array('name' => 'rta',
                                       'DS'   => $ds);
      
      $ds = array();
      $ds[] = array('dsname' => 'packet_loss');
      $ds[] = array('dsname' => 'warning_limit_pl');
      $ds[] = array('dsname' => 'critical_limit_pl');
      $ds[] = array('dsname' => 'other_pl');
      $data['parseperfdata'][] = array('name' => 'pl',
                                       'DS'   => $ds);
      return json_encode($data);      
   }
   
   
   
   static function perfdata_check_cpu_usage() {
      
      $data = array();
      $data['command'] = 'check_cpu_usage';
      $data['parseperfdata'] = array();
      
      $ds = array();
      $ds[] = array('dsname' => 'usage');
      $ds[] = array('dsname' => 'usage_warning');
      $ds[] = array('dsname' => 'usage_critical');
      $data['parseperfdata'][] = array('name' => 'cpu_usage',
                                       'DS'   => $ds);
      
      $ds = array();
      $ds[] = array('dsname' => 'user');
      $data['parseperfdata'][] = array('name' => 'cpu_user',
                                       'DS'   => $ds);
      
      $ds = array();
      $ds[] = array('dsname' => 'cpu_system');
      $data['parseperfdata'][] = array('name' => 'cpu_system',
                                       'DS'   => $ds);
      return json_encode($data);      
   }
   
   
   
   static function perfdata_check_load() {
      
      $data = array();
      $data['command'] = 'check_load';
      $data['parseperfdata'] = array();
      
      $ds = array();
      $ds[] = array('dsname' => 'load1min_current');
      $ds[] = array('dsname' => 'load1min_warning');
      $ds[] = array('dsname' => 'load1min_critical');
      $ds[] = array('dsname' => 'load1min_other');
      $data['parseperfdata'][] = array('name' => 'load1',
                                       'DS'   => $ds);
      
      $ds = array();
      $ds[] = array('dsname' => 'load5min_current');
      $ds[] = array('dsname' => 'load5min_warning');
      $ds[] = array('dsname' => 'load5min_critical');
      $ds[] = array('dsname' => 'load5min_other');
      $data['parseperfdata'][] = array('name' => 'load5',
                                       'DS'   => $ds);
      
      $ds = array();
      $ds[] = array('dsname' => 'load15min_current');
      $ds[] = array('dsname' => 'load15min_warning');
      $ds[] = array('dsname' => 'load15min_critical');
      $ds[] = array('dsname' => 'load15min_other');
      $data['parseperfdata'][] = array('name' => 'load15',
                                       'DS'   => $ds);
      return json_encode($data);      
   }
   
   
   
   static function perfdata_check_mem() {
      
      $data = array();
      $data['command'] = 'check_mem';
      $data['parseperfdata'] = array();
      
      $ds = array();
      $ds[] = array('dsname' => 'memory_used');
      $data['parseperfdata'][] = array('name' => 'pct',
                                       'DS'   => $ds);
      return json_encode($data);      
   }
   
   
   
   static function perfdata_check_users() {
      
      $data = array();
      $data['command'] = 'check_users';
      $data['parseperfdata'] = array();
      
      $ds = array();
      $ds[] = array('dsname' => 'users_current');
      $ds[] = array('dsname' => 'users_warning');
      $ds[] = array('dsname' => 'users_critical');
      $ds[] = array('dsname' => 'users_other');
      $data['parseperfdata'][] = array('name' => 'users',
                                       'DS'   => $ds);
      return json_encode($data);      
   }
   
   
   
   static function perfdata_check_iftraffic41() {
      
      $data = array();
      $data['command'] = 'check_iftraffic41';
      $data['parseperfdata'] = array();
      
      $ds = array();
      $ds[] = array('dsname' => 'inpercentcurr');
      $ds[] = array('dsname' => 'inpercentwarn');
      $ds[] = array('dsname' => 'inpercentcrit');
      $data['parseperfdata'][] = array('name' => 'inUsage',
                                       'DS'   => $ds);
      
      $ds = array();
      $ds[] = array('dsname' => 'outpercent_curr');
      $ds[] = array('dsname' => 'outpercentwarn');
      $ds[] = array('dsname' => 'outpercentcrit');
      $data['parseperfdata'][] = array('name' => 'outUsage',
                                       'DS'   => $ds);
      
      $ds = array();
      $ds[] = array('dsname' => 'inbps');
      $data['parseperfdata'][] = array('name' => 'inBandwidth',
                                       'DS'   => $ds);
      
      $ds = array();
      $ds[] = array('dsname' => 'outbps');
      $data['parseperfdata'][] = array('name' => 'outBandwidth',
                                       'DS'   => $ds);
      
      $ds = array();
      $ds[] = array('dsname' => 'inbound');
      $data['parseperfdata'][] = array('name' => 'inAbsolut',
                                       'DS'   => $ds);
      
      $ds = array();
      $ds[] = array('dsname' => 'outbound');
      $data['parseperfdata'][] = array('name' => 'outAbsolut',
                                       'DS'   => $ds);
      return json_encode($data);      
   }
   
   
   
   static function perfdata_check_iftraffic5() {
      
      $data = array();
      $data['command'] = 'check_iftraffic5';
      $data['parseperfdata'] = array();
      
      $ds = array();
      $ds[] = array('dsname' => 'inUse');
      $data['parseperfdata'][] = array('name' => 'inUse',
                                       'DS'   => $ds);
      
      $ds = array();
      $ds[] = array('dsname' => 'outUse');
      $data['parseperfdata'][] = array('name' => 'outUse',
                                       'DS'   => $ds);
      
      $ds = array();
      $ds[] = array('dsname' => 'Warn');
      $data['parseperfdata'][] = array('name' => 'Warn',
                                       'DS'   => $ds);
      
      $ds = array();
      $ds[] = array('dsname' => 'Crit');
      $data['parseperfdata'][] = array('name' => 'Crit',
                                       'DS'   => $ds);
      
      $ds = array();
      $ds[] = array('dsname' => 'inBW');
      $data['parseperfdata'][] = array('name' => 'inBW',
                                       'DS'   => $ds);
      
      $ds = array();
      $ds[] = array('dsname' => 'outBW');
      $data['parseperfdata'][] = array('name' => 'outBW',
                                       'DS'   => $ds);
      
      $ds = array();
      $ds[] = array('dsname' => 'inUcast');
      $data['parseperfdata'][] = array('name' => 'inUcast',
                                       'DS'   => $ds);
      
      $ds = array();
      $ds[] = array('dsname' => 'inMcast');
      $data['parseperfdata'][] = array('name' => 'inMcast',
                                       'DS'   => $ds);
      
      $ds = array();
      $ds[] = array('dsname' => 'inBcast');
      $data['parseperfdata'][] = array('name' => 'inBcast',
                                       'DS'   => $ds);
      
      $ds = array();
      $ds[] = array('dsname' => 'outUcast');
      $data['parseperfdata'][] = array('name' => 'outUcast',
                                       'DS'   => $ds);
      
      $ds = array();
      $ds[] = array('dsname' => 'outMcast');
      $data['parseperfdata'][] = array('name' => 'outMcast',
                                       'DS'   => $ds);
      
      $ds = array();
      $ds[] = array('dsname' => 'outBcast');
      $data['parseperfdata'][] = array('name' => 'outBcast',
                                       'DS'   => $ds);
      
      $ds = array();
      $ds[] = array('dsname' => 'inDis');
      $data['parseperfdata'][] = array('name' => 'inDis',
                                       'DS'   => $ds);
      
      $ds = array();
      $ds[] = array('dsname' => 'inErr');
      $data['parseperfdata'][] = array('name' => 'inErr',
                                       'DS'   => $ds);
      
      $ds = array();
      $ds[] = array('dsname' => 'outDis');
      $data['parseperfdata'][] = array('name' => 'outDis',
                                       'DS'   => $ds);
      
      $ds = array();
      $ds[] = array('dsname' => 'outErr');
      $data['parseperfdata'][] = array('name' => 'outErr',
                                       'DS'   => $ds);
      
      return json_encode($data);      
   }
   
   
   
   static function perfdata_check_pf() {
      
      $data = array();
      $data['command'] = 'check_pf';
      $data['parseperfdata'] = array();
      
      $ds = array();
      $ds[] = array('dsname' => 'states_current');
      $ds[] = array('dsname' => 'states_warning');
      $ds[] = array('dsname' => 'states_critical');
      $data['parseperfdata'][] = array('name' => 'current',
                                       'DS'   => $ds);
      
      $ds = array();
      $ds[] = array('dsname' => 'percent');
      $data['parseperfdata'][] = array('name' => 'percent',
                                       'DS'   => $ds);
      
      $ds = array();
      $ds[] = array('dsname' => 'limit');
      $data['parseperfdata'][] = array('name' => 'limit',
                                       'DS'   => $ds);
      return json_encode($data);      
   }
   
   
   
   static function perfdata_check_dig() {
      
      $data = array();
      $data['command'] = 'check_dig';
      $data['parseperfdata'] = array();
      
      $ds = array();
      $ds[] = array('dsname' => 'time_current');
      $ds[] = array('dsname' => 'time_warning');
      $ds[] = array('dsname' => 'time_critical');
      $ds[] = array('dsname' => 'time_other');
      $data['parseperfdata'][] = array('name' => 'time',
                                       'DS'   => $ds);
      return json_encode($data);      
   }
   
   
   
   static function perfdata_check_disk() {
      
      $data = array();
      $data['command'] = 'check_disk';
      $data['parseperfdata'] = array();
      
      $ds = array();
      $ds[] = array('dsname' => 'used');
      $ds[] = array('dsname' => 'used_warning');
      $ds[] = array('dsname' => 'used_critical');
      $ds[] = array('dsname' => 'used_other');
      $ds[] = array('dsname' => 'totalcapacity');
      $data['parseperfdata'][] = array('name' => '',
                                       'DS'   => $ds);
      return json_encode($data);      
   }
   
   
   
   static function perfdata_check_dns() {
      
      $data = array();
      $data['command'] = 'check_dns';
      $data['parseperfdata'] = array();
      
      $ds = array();
      $ds[] = array('dsname' => 'time_current');
      $ds[] = array('dsname' => 'time_warning');
      $ds[] = array('dsname' => 'time_critical');
      $ds[] = array('dsname' => 'time_other');
      $data['parseperfdata'][] = array('name' => 'time',
                                       'DS'   => $ds);
      return json_encode($data);      
   }
   
   
   
   static function perfdata_check_http() {
      
      $data = array();
      $data['command'] = 'check_http';
      $data['parseperfdata'] = array();
      
      $ds = array();
      $ds[] = array('dsname' => 'time_current');
      $ds[] = array('dsname' => 'time_warning');
      $ds[] = array('dsname' => 'time_critical');
      $ds[] = array('dsname' => 'time_other');
      $data['parseperfdata'][] = array('name' => 'time',
                                       'DS'   => $ds);
      
      $ds = array();
      $ds[] = array('dsname' => 'size_current');
      $ds[] = array('dsname' => 'size_warning');
      $ds[] = array('dsname' => 'size_critical');
      $ds[] = array('dsname' => 'size_other');
      $data['parseperfdata'][] = array('name' => 'size',
                                       'DS'   => $ds);
      return json_encode($data);      
   }
   
   
   
   static function perfdata_check_pop() {
      
      $data = array();
      $data['command'] = 'check_pop';
      $data['parseperfdata'] = array();
      
      $ds = array();
      $ds[] = array('dsname' => 'time_current');
      $ds[] = array('dsname' => 'time_warning');
      $ds[] = array('dsname' => 'time_critical');
      $ds[] = array('dsname' => 'time_other');
      $ds[] = array('dsname' => 'time_timeout');
      $data['parseperfdata'][] = array('name' => 'time',
                                       'DS'   => $ds);
      return json_encode($data);      
   }
   
   
   
   static function perfdata_check_smtp() {
      
      $data = array();
      $data['command'] = 'check_smtp';
      $data['parseperfdata'] = array();
      
      $ds = array();
      $ds[] = array('dsname' => 'time_current');
      $ds[] = array('dsname' => 'time_warning');
      $ds[] = array('dsname' => 'time_critical');
      $ds[] = array('dsname' => 'time_other');
      $data['parseperfdata'][] = array('name' => 'time',
                                       'DS'   => $ds);
      return json_encode($data);      
   }
   
   
   
   static function perfdata_check_mysql_health__connection_time() {
      
      $data = array();
      $data['command'] = 'check_mysql_health__connection-time';
      $data['parseperfdata'] = array();
      
      $ds = array();
      $ds[] = array('dsname' => 'connection-time_current');
      $ds[] = array('dsname' => 'tconnection-time_warning');
      $ds[] = array('dsname' => 'connection-time_critical');
      $data['parseperfdata'][] = array('name' => 'connection-time',
                                       'DS'   => $ds);
      return json_encode($data);      
   }
   
   
   
   static function perfdata_check_mysql_health__tmp_disk_tables() {
      
      $data = array();
      $data['command'] = 'check_mysql_health__tmp_disk_tables';
      $data['parseperfdata'] = array();
      
      $ds = array();
      $ds[] = array('dsname' => 'tmp_table_on_disk_current');
      $ds[] = array('dsname' => 'tmp_table_on_disk_warning');
      $ds[] = array('dsname' => 'tmp_table_on_disk_critical');
      $data['parseperfdata'][] = array('name' => 'pct_tmp_table_on_disk',
                                       'DS'   => $ds);
      
      $ds = array();
      $ds[] = array('dsname' => 'tmp_table_on_disk_now_current');
      $ds[] = array('dsname' => 'tmp_table_on_disk_now_warning');
      $ds[] = array('dsname' => 'tmp_table_on_disk_now_critical');
      $data['parseperfdata'][] = array('name' => 'pct_tmp_table_on_disk_now',
                                       'DS'   => $ds);
      return json_encode($data);      
   }

   
   
   static function perfdata_check_mysql_health__threads_connected() {
      
      $data = array();
      $data['command'] = 'check_mysql_health__threads_connected';
      $data['parseperfdata'] = array();
      
      $ds = array();
      $ds[] = array('dsname' => 'threads_connected_current');
      $ds[] = array('dsname' => 'threads_connected_warning');
      $ds[] = array('dsname' => 'threads_connected_critical');
      $data['parseperfdata'][] = array('name' => 'threads_connected',
                                       'DS'   => $ds);
      return json_encode($data);      
   }   
   
   
   static function perfdata_check_snmp_memory() {
      
      $data = array();
      $data['command'] = 'check_snmp_memory';
      $data['parseperfdata'] = array();
      
      $ds = array();
      $ds[] = array('dsname' => 'memory_total');
      $ds[] = array('dsname' => 'memory_warning');
      $ds[] = array('dsname' => 'memory_critical');
      $ds[] = array('dsname' => 'memory_other');
      $data['parseperfdata'][] = array('name' => 'total',
                                       'DS'   => $ds);
      
      $ds = array();
      $ds[] = array('dsname' => 'memory_used');
      $ds[] = array('dsname' => 'memory_other1');
      $ds[] = array('dsname' => 'memory_other2');
      $ds[] = array('dsname' => 'memory_other3');
      $data['parseperfdata'][] = array('name' => 'used',
                                       'DS'   => $ds);
      
      $ds = array();
      $ds[] = array('dsname' => 'swap_used');
      $ds[] = array('dsname' => 'swap_other1');
      $ds[] = array('dsname' => 'swap_other2');
      $ds[] = array('dsname' => 'swap_other3');
      $data['parseperfdata'][] = array('name' => 'swap',
                                       'DS'   => $ds);
      
      $ds = array();
      $ds[] = array('dsname' => 'buffer_used');
      $ds[] = array('dsname' => 'buffer_other1');
      $ds[] = array('dsname' => 'buffer_other2');
      $ds[] = array('dsname' => 'buffer_other3');
      $data['parseperfdata'][] = array('name' => 'buffer',
                                       'DS'   => $ds);
      
      $ds = array();
      $ds[] = array('dsname' => 'cache_used');
      $ds[] = array('dsname' => 'cache_other1');
      $ds[] = array('dsname' => 'cache_other2');
      $ds[] = array('dsname' => 'cache_other3');
      $data['parseperfdata'][] = array('name' => 'cache',
                                       'DS'   => $ds);
      return json_encode($data);      
   }

   
   
   static function perfdata_check_snmp_load__stand() {
      
      $data = array();
      $data['command'] = 'check_snmp_load__stand';
      $data['parseperfdata'] = array();
      
      $ds = array();
      $ds[] = array('dsname' => 'cpu_load');
      $ds[] = array('dsname' => 'cpu_warning');
      $ds[] = array('dsname' => 'cpu_critical');
      $data['parseperfdata'][] = array('name' => 'cpu_used',
                                       'DS'   => $ds);
      return json_encode($data);      
   }   

   
   
   static function perfdata_check_snmp_storage() {

      $data = array();
      $data['command'] = 'check_snmp_storage';
      $data['parseperfdata'] = array();

      $ds = array();
      $ds[] = array('dsname' => 'used');
      $ds[] = array('dsname' => 'warning');
      $ds[] = array('dsname' => 'critical');
      $ds[] = array('dsname' => 'other');
      $ds[] = array('dsname' => 'total');
      $data['parseperfdata'][] = array('name' => '*',
                                       'DS'   => $ds);
      return json_encode($data);
   }   

   
   
   static function perfdata_check_tcp() {

      $data = array();
      $data['command'] = 'check_tcp';
      $data['parseperfdata'] = array();

      $ds = array();
      $ds[] = array('dsname' => 'response_time');
      $ds[] = array('dsname' => 'warning');
      $ds[] = array('dsname' => 'critical');
      $ds[] = array('dsname' => 'other');
      $ds[] = array('dsname' => 'timeout');
      $data['parseperfdata'][] = array('name' => 'time',
                                       'DS'   => $ds);
      return json_encode($data);
   }
   

   
   static function perfdata_check_iostat_bsd() {

      $data = array();
      $data['command'] = 'check_iostat_bsd';
      $data['parseperfdata'] = array();
      
      $ds = array();
      $ds[] = array('dsname' => 'IOTPS_read_write');
      $data['parseperfdata'][] = array('name' => 'tps',
                                       'DS'   => $ds);
      
      $ds = array();
      $ds[] = array('dsname' => 'IOTPS_read');
      $data['parseperfdata'][] = array('name' => 'tpsr',
                                       'DS'   => $ds);
      
      $ds = array();
      $ds[] = array('dsname' => 'IOTPS_write');
      $data['parseperfdata'][] = array('name' => 'tpsw',
                                       'DS'   => $ds);
      
      $ds = array();
      $ds[] = array('dsname' => 'Kbps_read');
      $data['parseperfdata'][] = array('name' => 'reads',
                                       'DS'   => $ds);
      
      $ds = array();
      $ds[] = array('dsname' => 'Kbps_write');
      $data['parseperfdata'][] = array('name' => 'writes',
                                       'DS'   => $ds);

      $ds = array();
      $ds[] = array('dsname' => 'transactiontime');
      $data['parseperfdata'][] = array('name' => 'svc_t',
                                       'DS'   => $ds);
      
      return json_encode($data);
   }
   
   
   
   static function perfdata_cucumber_nagios() {

      $data = array();
      $data['command'] = 'cucumber_nagios';
      $data['parseperfdata'] = array();

      $ds = array();
      $ds[] = array('dsname' => 'passed');
      $data['parseperfdata'][] = array('name' => 'passed',
                                       'DS'   => $ds);

      $ds = array();
      $ds[] = array('dsname' => 'failed');
      $data['parseperfdata'][] = array('name' => 'failed',
                                       'DS'   => $ds);

      $ds = array();
      $ds[] = array('dsname' => 'nosteps');
      $data['parseperfdata'][] = array('name' => 'nosteps',
                                       'DS'   => $ds);

      $ds = array();
      $ds[] = array('dsname' => 'total');
      $data['parseperfdata'][] = array('name' => 'total',
                                       'DS'   => $ds);

      $ds = array();
      $ds[] = array('dsname' => 'time');
      $data['parseperfdata'][] = array('name' => 'time',
                                       'DS'   => $ds);
      return json_encode($data);
   }
   
   
   
   static function perfdata_check_nginx_status() {

      $data = array();
      $data['command'] = 'check_nginx_status';
      $data['parseperfdata'] = array();

      $ds = array();
      $ds[] = array('dsname' => 'Writing');
      $data['parseperfdata'][] = array('name' => 'Writing',
                                       'DS'   => $ds);

      $ds = array();
      $ds[] = array('dsname' => 'Reading');
      $data['parseperfdata'][] = array('name' => 'Reading',
                                       'DS'   => $ds);

      $ds = array();
      $ds[] = array('dsname' => 'Waiting');
      $data['parseperfdata'][] = array('name' => 'Waiting',
                                       'DS'   => $ds);

      $ds = array();
      $ds[] = array('dsname' => 'Active');
      $data['parseperfdata'][] = array('name' => 'Active',
                                       'DS'   => $ds);

      $ds = array();
      $ds[] = array('dsname' => 'ReqPerSec');
      $data['parseperfdata'][] = array('name' => 'ReqPerSec',
                                       'DS'   => $ds);

      $ds = array();
      $ds[] = array('dsname' => 'ConnPerSec');
      $data['parseperfdata'][] = array('name' => 'ConnPerSec',
                                       'DS'   => $ds);

      $ds = array();
      $ds[] = array('dsname' => 'ReqPerConn');
      $data['parseperfdata'][] = array('name' => 'ReqPerConn',
                                       'DS'   => $ds);
      return json_encode($data);
   }
}
?>