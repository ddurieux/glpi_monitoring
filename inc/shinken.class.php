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

class PluginMonitoringShinken extends CommonDBTM {
   

   function generateConfig() {
      global $DB,$CFG_GLPI,$LANG;

      


      return true;
   }


   function constructFile($name, $array) {
      $config = '';
      $config .= "define ".$name."{\n";
      foreach ($array as $key => $value) {
         $c = 35;
         $c = $c - strlen($key);
         $config .= "       ".$key;
         for ($t=0; $t < $c; $t++) {
            $config .= " ";
         }
         $config .= $value."\n";
      }
      $config .= "}\n";
      $config .= "\n\n";
      return $config;
   }


   function generateCommandsCfg($file=0) {
      
      $pluginMonitoringCommand = new PluginMonitoringCommand();
      $pluginMonitoringNotificationcommand = new PluginMonitoringNotificationcommand();

      $a_commands = array();
      $i=0;

      $a_list = $pluginMonitoringCommand->find();
      $a_listnotif = $pluginMonitoringNotificationcommand->find();
      $a_list = array_merge($a_list, $a_listnotif);
      foreach ($a_list as $data) {
         if ($data['command_name'] != "bp_rule") {
            $a_commands[$i]['name'] = $data['name'];
            $a_commands[$i]['command_name'] = $data['command_name'];
            $a_commands[$i]['command_line'] = $data['command_line'];
            $i++;
         }
      }

      if ($file == "1") {
         $config = "# Generated by plugin monitoring for GLPI\n# on ".date("Y-m-d H:i:s")."\n\n";
         foreach ($a_commands as $data) {
            $config .= "# ".$data['name']."\n";
            unset($data['name']);
            $config .= $this->constructFile("command", $data);
         }
         return array('commands.cfg', $config);         
      } else {
         return $a_commands;
      }
   }


   
   function generateHostsCfg($file=0) {
      
      $pluginMonitoringHost         = new PluginMonitoringHost();
      $pluginMonitoringHost_Host    = new PluginMonitoringHost_Host();
      $pluginMonitoringContact      = new PluginMonitoringContact();
      $pluginMonitoringHost_Contact = new PluginMonitoringHost_Contact();
      $pluginMonitoringCommand      = new PluginMonitoringCommand();
      $pluginMonitoringCheck        = new PluginMonitoringCheck();
      $calendar      = new Calendar();
      $user          = new User();
      $networkPort   = new NetworkPort();

      $a_hosts = array();
      $i=0;

      $a_list = $pluginMonitoringHost->find("`is_template`='0'");
      foreach ($a_list as $data) {
         $classname = $data['itemtype'];
         $class = new $classname;
         $class->getFromDB($data['items_id']);

         $a_hosts[$i]['host_name'] = $classname."-".$data['id']."-".$class->fields['name'];
            $ip = $class->fields['name'];
            if ($data['itemtype'] == 'NetworkEquipment') {
               if ($class->fields['ip'] != '') {
                  $ip = $class->fields['ip'];
               }
            } else {
               $a_listnetwork = $networkPort->find("`itemtype`='".$data['itemtype']."'
                  AND `items_id`='".$data['items_id']."'");
               foreach ($a_listnetwork as $datanetwork) {
                  if ($datanetwork['ip'] != '' AND $datanetwork['ip'] != '127.0.0.1') {
                     $ip = $datanetwork['ip'];
                     break;
                  }
               }
            }
         $a_hosts[$i]['address'] = $ip;
            $a_parents = array();
            switch ($data['parenttype']) {

               case 0:
                  // Disable
                  break;

               case 1:
                  // Static
                  $a_list_parent = $pluginMonitoringHost_Host->find("`plugin_monitoring_hosts_id_1`='".$data['id']."'");
                  foreach ($a_list_parent as $data_parent) {
                     $pluginMonitoringHost->getFromDB($data_parent['plugin_monitoring_hosts_id_2']);
                     $classnameparent = $pluginMonitoringHost->fields['itemtype'];
                     $classparent = new $classnameparent;
                     $classparent->getFromDB($pluginMonitoringHost->fields['items_id']);
                     $a_parents[] = $classnameparent."-".$data_parent['plugin_monitoring_hosts_id_2']."-".$classparent->fields['name'];
                  }
                  break;

               case 2:
                  // dynamic
                  if ($data['itemtype'] != 'NetworkEquipment') {
                     $a_listnetwork = $networkPort->find("`itemtype`='".$data['itemtype']."'
                        AND `items_id`='".$data['items_id']."'");
                     foreach ($a_listnetwork as $datanetwork) {
                        $contact_id = $networkPort->getContact($datanetwork['id']);
                        if ($contact_id) {
                           $networkPort->getFromDB($contact_id);
                           $classnameparent = $networkPort->fields['itemtype'];
                           $classparent = new $classnameparent;
                           $classparent->getFromDB($networkPort->fields['items_id']);
                           $a_listhostt = $pluginMonitoringHost->find("`itemtype`='".$classnameparent."'
                              AND `items_id`='".$networkPort->fields['items_id']."'", "", 1);
                           if (count($a_listhostt) > 0) {
                              $a_hostt = current($a_listhostt);
                              $a_parents[] = $classnameparent."-".$a_hostt['id']."-".$classparent->fields['name'];
                           }
                        }
                     }
                  }
                  break;

            }
         if (count($a_parents) > 0) {
            $a_hosts[$i]['parents'] = implode(',', $a_parents);
         } else {
            $a_hosts[$i]['parents'] = "";
         }
            $pluginMonitoringCommand->getFromDB($data['plugin_monitoring_commands_id']);
         $a_hosts[$i]['check_command'] = $pluginMonitoringCommand->fields['command_name'];
            $pluginMonitoringCheck->getFromDB($data['plugin_monitoring_checks_id']);
         $a_hosts[$i]['check_interval'] = $pluginMonitoringCheck->fields['check_interval'];
         $a_hosts[$i]['retry_interval'] = $pluginMonitoringCheck->fields['retry_interval'];
         $a_hosts[$i]['max_check_attempts'] = $pluginMonitoringCheck->fields['max_check_attempts'];
         if ($calendar->getFromDB($data['calendars_id'])) {
            $a_hosts[$i]['check_period'] = $calendar->fields['name'];
         }
            $a_contacts = array();
            $a_list_contact = $pluginMonitoringHost_Contact->find("`plugin_monitoring_hosts_id`='".$data['id']."'");
            foreach ($a_list_contact as $data_contact) {
               $pluginMonitoringContact->getFromDB($data_contact['plugin_monitoring_contacts_id']);
               $user->getFromDB($pluginMonitoringContact->fields['users_id']);
               $a_contacts[] = $user->fields['name'];
            }
         $a_hosts[$i]['contacts'] = implode(',', $a_contacts);
         $i++;
      }
      

      if ($file == "1") {
         $config = "# Generated by plugin monitoring for GLPI\n# on ".date("Y-m-d H:i:s")."\n\n";

         foreach ($a_hosts as $data) {
            $config .= $this->constructFile("host", $data);
         }
         return array('hosts.cfg', $config);

      } else {
         return $a_hosts;
      }
   }

   
   
   function generateServicesCfg($file=0) {
      
      $pluginMonitoringHost         = new PluginMonitoringHost();
      $pluginMonitoringHost_Host    = new PluginMonitoringHost_Host();
      $pluginMonitoringHost_Service = new PluginMonitoringHost_Service();
      $pMonitoringService = new PluginMonitoringService();
      $pluginMonitoringContact      = new PluginMonitoringContact();
      $pluginMonitoringHost_Contact = new PluginMonitoringHost_Contact();
      $pMonitoringCommand      = new PluginMonitoringCommand();
      $pMonitoringCheck        = new PluginMonitoringCheck();
      $pluginMonitoringBusinessapplication = new PluginMonitoringBusinessapplication();
      $pluginMonitoringBusinessrule = new PluginMonitoringBusinessrule();
      $calendar      = new Calendar();
      $user          = new User();
      
      $a_services = array();
      $i=0;
      
      $a_listH = $pluginMonitoringHost->find("`is_template`='0'");
      foreach ($a_listH as $data) {
         $classname = $data['itemtype'];
         $class = new $classname;
         $class->getFromDB($data['items_id']);
         $a_listHS = $pluginMonitoringHost_Service->find("`plugin_monitoring_hosts_id`='".$data['id']."'");
         foreach ($a_listHS as $dataHS) {

            $a_services[$i]['host_name'] = $classname."-".$data['id']."-".$class->fields['name'];
            $a_services[$i]['service_description'] = $dataHS['name'];
            $pMonitoringService->getFromDB($dataHS['plugin_monitoring_services_id']);
            $pMonitoringCommand->getFromDB($pMonitoringService->fields['plugin_monitoring_commands_id']);
            $a_services[$i]['check_command'] = $pMonitoringCommand->fields['command_name'];
               $pMonitoringCheck->getFromDB($pMonitoringService->fields['plugin_monitoring_checks_id']);
            $a_services[$i]['check_interval'] = $pMonitoringCheck->fields['check_interval'];
            $a_services[$i]['retry_interval'] = $pMonitoringCheck->fields['retry_interval'];
            $a_services[$i]['max_check_attempts'] = $pMonitoringCheck->fields['max_check_attempts'];
            if ($calendar->getFromDB($data['calendars_id'])) {
               $a_services[$i]['check_period'] = $calendar->fields['name'];            
            }
               $a_contacts = array();
               $a_list_contact = $pluginMonitoringHost_Contact->find("`plugin_monitoring_hosts_id`='".$data['id']."'");
               foreach ($a_list_contact as $data_contact) {
                  $pluginMonitoringContact->getFromDB($data_contact['plugin_monitoring_contacts_id']);
                  $user->getFromDB($pluginMonitoringContact->fields['users_id']);
                  $a_contacts[] = $user->fields['name'];
               }
            $a_services[$i]['contacts'] = implode(',', $a_contacts);
            $i++;
         }         
      }

      /*
	  define service{
	  host_name               linux-server
	  service_description     check-disk-sda1
	  check_command           check-disk!/dev/sda1
	  max_check_attempts      5
	  check_interval          5
	  retry_interval          3
	  check_period            24x7
	  notification_interval   30
	  notification_period     24x7
	  notification_options    w,c,r
	  contact_groups          linux-admins
	  poller_tag              DMZ
	  }
       */
      
      
      
      // Generate Business rules
      /*
define service{
     use         standard-service
     host_name   dummy
     service_description  ERP
     check_command        bp_rule!(h1,database1 | h2,database2) & (2 of: h3,Http1 & h4,Http4 & h5,Http5) & (h6,IPVS1 | h7,IPVS2)
}
       */
      $a_listBA = $pluginMonitoringBusinessapplication->find();
      foreach ($a_listBA as $dataBA) {
         $a_services[$i]['use'] = "standard-service";
         $a_services[$i]['host_name'] = $dataBA['name'];
         $a_services[$i]['service_description'] = $dataBA['comment'];
         $command = "bp_rule!";
         $a_listBR = $pluginMonitoringBusinessrule->find(
                 "`plugin_monitoring_businessapplications_id`='".$dataBA['id']."'",
                 "`group`, `position`");
         $a_group = array();
         foreach ($a_listBR as $dataBR) {
            $itemtype = $dataBR['itemtype'];
            $item = new $itemtype();
            $item->getFromDB($dataBR['items_id']);
            $pluginMonitoringHost->getFromDB($item->fields['plugin_monitoring_hosts_id']);
               $classname = $pluginMonitoringHost->fields['itemtype'];
               $class = new $classname;
               $class->getFromDB($pluginMonitoringHost->fields['items_id']);
            $hostname = $classname."-".$data['id']."-".$class->fields['name'];
 
            if ($dataBR['operator'] == 'and'
                    OR $dataBR['operator'] == 'or') {
               
               $operator = ' & ';
               if ($dataBR['operator'] == 'or') {
                  $operator = ' | ';
               }
               
               $a_group[$dataBR['group']] .= $operator.$hostname.",".$item->getName();
            } else {
               $a_group[$dataBR['group']] = $dataBR['operator']." ".$hostname.",".$item->getName();
            }            
         }
         foreach ($a_group as $key=>$value) {
            $a_group[$key] = "(".$value.")";
         }
         $a_services[$i]['check_command'] = $command.implode(" & ", $a_group);
         $i++;
      }
      
      if ($file == "1") {
         $config = "# Generated by plugin monitoring for GLPI\n# on ".date("Y-m-d H:i:s")."\n\n";

         foreach ($a_services as $data) {
            $config .= $this->constructFile("service", $data);
         }
         return array('services.cfg', $config);

      } else {
         return $a_services;
      }
   }

   
   


   function generateContactsCfg($file=0) {
      
      $pluginMonitoringContact             = new PluginMonitoringContact();
      $pluginMonitoringNotificationcommand = new PluginMonitoringNotificationcommand();
      $user = new User();
      $calendar = new Calendar();

      $a_contacts = array();
      $i=0;

      $a_listmcontacts = $pluginMonitoringContact->find();
      foreach ($a_listmcontacts as $data) {
         $user->getFromDB($data['users_id']);

         $a_contacts[$i]['contact_name'] = $user->fields['name'];
         $a_contacts[$i]['alias'] = $user->getName();
         $a_contacts[$i]['host_notifications_enabled'] = $data['host_notifications_enabled'];
         $a_contacts[$i]['service_notifications_enabled'] = $data['service_notifications_enabled'];
            $calendar->getFromDB($data['service_notification_period']);
         $a_contacts[$i]['service_notification_period'] = $calendar->fields['name'];
            $calendar->getFromDB($data['host_notification_period']);
         $a_contacts[$i]['host_notification_period'] = $calendar->fields['name'];
            $a_servicenotif = array();
            if ($data['service_notification_options_w'] == '1')
               $a_servicenotif[] = "w";
            if ($data['service_notification_options_u'] == '1')
               $a_servicenotif[] = "u";
            if ($data['service_notification_options_c'] == '1')
               $a_servicenotif[] = "c";
            if ($data['service_notification_options_r'] == '1')
               $a_servicenotif[] = "r";
            if ($data['service_notification_options_f'] == '1')
               $a_servicenotif[] = "f";
            if ($data['service_notification_options_n'] == '1')
               $a_servicenotif = array("n");
            if (count($a_servicenotif) == "0")
               $a_servicenotif = array("n");
         $a_contacts[$i]['service_notification_options'] = implode(",", $a_servicenotif);
            $a_hostnotif = array();
            if ($data['host_notification_options_d'] == '1')
               $a_hostnotif[] = "d";
            if ($data['host_notification_options_u'] == '1')
               $a_hostnotif[] = "u";
            if ($data['host_notification_options_r'] == '1')
               $a_hostnotif[] = "r";
            if ($data['host_notification_options_f'] == '1')
               $a_hostnotif[] = "f";
            if ($data['host_notification_options_s'] == '1')
               $a_hostnotif[] = "s";
            if ($data['host_notification_options_n'] == '1')
               $a_hostnotif = array("n");
            if (count($a_hostnotif) == "0")
               $a_hostnotif = array("n");
         $a_contacts[$i]['host_notification_options'] = implode(",", $a_hostnotif);
            $pluginMonitoringNotificationcommand->getFromDB($data['service_notification_commands']);
         $a_contacts[$i]['service_notification_commands'] = $pluginMonitoringNotificationcommand->fields['command_name'];
            $pluginMonitoringNotificationcommand->getFromDB($data['host_notification_commands']);
         $a_contacts[$i]['host_notification_commands'] = $pluginMonitoringNotificationcommand->fields['command_name'];
         $a_contacts[$i]['email'] = $user->fields['email'];
         $a_contacts[$i]['pager'] = $data['pager'];
         $i++;
      }

      if ($file == "1") {
         $config = "# Generated by plugin monitoring for GLPI\n# on ".date("Y-m-d H:i:s")."\n\n";

         foreach ($a_contacts as $data) {
            $config .= $this->constructFile("contact", $data);
         }
         return array('contacts.cfg', $config);

      } else {
         return $a_contacts;
      }
   }



   function generateTimeperiodsCfg($file=0) {

      $calendar = new Calendar();
      $calendarSegment = new CalendarSegment();

      $a_timeperiods = array();
      $i=0;
      
      $a_listcalendar = $calendar->find();
      foreach ($a_listcalendar as $datacalendar) {
         //if ($datacalendar['name'] != "Default") {
            $a_timeperiods[$i]['timeperiod_name'] = $datacalendar['name'];
            $a_timeperiods[$i]['alias'] = $datacalendar['name'];
            $a_listsegment = $calendarSegment->find("`calendars_id`='".$datacalendar['id']."'");
            foreach ($a_listsegment as $datasegment) {
               $begin = preg_replace("/:00$/", "", $datasegment['begin']);
               $end = preg_replace("/:00$/", "", $datasegment['end']);
               switch ($datasegment['day']) {

                  case "0":
                     $day = "sunday";
                     break;

                  case "1":
                     $day = "monday";
                     break;

                  case "2":
                     $day = "tuesday";
                     break;

                  case "3":
                     $day = "wednesday";
                     break;

                  case "4":
                     $day = "thursday";
                     break;

                  case "5":
                     $day = "friday";
                     break;

                  case "6":
                     $day = "saturday";
                     break;

               }
               $a_timeperiods[$i][$day] = $begin."-".$end;
            }
            $i++;
         //}
      }

      if ($file == "1") {
         $config = "# Generated by plugin monitoring for GLPI\n# on ".date("Y-m-d H:i:s")."\n\n";

         foreach ($a_timeperiods as $data) {
            $config .= $this->constructFile("timeperiod", $data);
         }
         return array('timeperiods.cfg', $config);

      } else {
         return $a_timeperiods;
      }
   }


}

?>