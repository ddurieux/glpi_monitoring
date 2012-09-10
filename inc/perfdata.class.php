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
      $a_list["check_pf"]        = "check_pf";
      
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
   
}

?>