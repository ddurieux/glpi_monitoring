<?php

/*
   ------------------------------------------------------------------------
   Plugin Monitoring for GLPI
   Copyright (C) 2011-2016 by the Plugin Monitoring for GLPI Development Team.

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
   @co-author Frédéric Mohier
   @comment
   @copyright Copyright (c) 2011-2016 Plugin Monitoring for GLPI team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      https://forge.indepnet.net/projects/monitoring/
   @since     2011

   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginMonitoringShinken extends CommonDBTM {


   // Comment to remove custom variable from host/service configuration
   public static $shinkenParameters = array(
      // GLPI root entity name
      'glpi' => array(
         'rootEntity'   => '',
         // Entity id
         'entityId' => '_ENTITIESID',
         // Entity name
         'entityName' => '_ENTITY',
         // Entity complete
         'entityComplete' => '_ENTITY_COMPLETE',
         // Item type
         'itemType' => '_ITEMTYPE',
         // Item id
         'itemId' => '_ITEMSID',
         // Location
         'location' => '_LOC_NAME',
         // Latitude
         'lat' => '_LOC_LAT',
         // Longitude
         'lng' => '_LOC_LNG',
         // Altitude
         'alt' => '_LOC_ALT',
      ),
      // Shinken configuration
      'shinken' => array(
         // Build fake hosts for business rules
         'fake_bp_hosts' => array(
            // Default values
            'build' => false,
            // Hostname
            'hostname' => 'BP_host'
         ),
         // Build fake hosts for parents relationship
         'fake_hosts' => array(
            // Default values
            'build' => false,
            // Default check command
            'check_command' => 'check_dummy!0!Fake host is up',
            // Default check_period
            'check_period' => '24x7',
            // Fake hosts tag
            'use' => 'fake',
            // Fake hosts name prefix
            'name_prefix' => '_fake_',
            // Hostgroup name
            'hostgroup_name' => 'fake_hosts',
            // Hostgroup alias
            'hostgroup_alias' => 'Fake hosts',
            // Main root parent
            'root_parent' => 'Root',
            // Main root parent
            'bp_host' => 'BP_host'
         ),
         // Build fake contacts for fake hosts
         'fake_contacts' => array(
            // Default values
            'build' => false,
            // Contact name
            'contact_name' => 'monitoring',
         ),
         'hosts' => array(
            // Default check_period
            'check_period' => '24x7',
            // Default values
            // 'use' => 'important',
            'business_impact' => 3,
            'process_perf_data' => '1',
            // Default hosts notifications : none !
            'notifications_enabled' => '0',
            'notification_period' => '24x7',
            'notification_options' => 'd,u,r,f,s',
            'notification_interval' => '86400',
            /*
            low_flap_threshold:	      This directive is used to specify the low state change threshold used in flap detection for this host. More information on flap detection can be found here. If you set this directive to a value of 0, the program-wide value specified by the low_host_flap_threshold directive will be used.
            high_flap_threshold:	      This directive is used to specify the high state change threshold used in flap detection for this host. More information on flap detection can be found here. If you set this directive to a value of 0, the program-wide value specified by the high_host_flap_threshold directive will be used.
            flap_detection_enabled:	   This directive is used to determine whether or not flap detection is enabled for this host. More information on flap detection can be found here. Values: 0 = disable host flap detection, 1 = enable host flap detection.
            flap_detection_options:    This directive is used to determine what host states the flap detection logic will use for this host. Valid options are a combination of one or more of the following: o = UP states, d = DOWN states, u = UNREACHABLE states.
            */
            'flap_detection_enabled' => '0',
            'flap_detection_options' => 'o',
            'low_flap_threshold' => '25',
            'high_flap_threshold' => '50',

            'stalking_options' => '',

            'failure_prediction_enabled' => '0',
            'retain_status_information' => '0',
            'retain_nonstatus_information' => '0',
            // Set as 'entity' to use hostgroupname else use the defined value ...
            // When fake_hosts are built (see upper), use 'entity' !
            'parents' => 'entity',
            // Shinken host parameters
            'notes_url' => '',
            'action_url' => '',
            'icon_image' => '',
            'icon_image_alt' => '',
            'vrml_image' => '',
            'statusmap_image' => '',
         ),
         'services' => array(
            // Default check_period - leave empty to use check period defined fo the host.
            'check_period'          => '',
            // Default values
            'business_impact'       => 3,
            'process_perf_data'     => 1,
            // Default services notifications : none !
            'notifications_enabled' => 0,
            'notification_period'   => '24x7',
            'notification_options'  => 'w,u,c,r,f,s',
            'notification_interval' => 86400,
            /*
            low_flap_threshold:	      This directive is used to specify the low state change threshold used in flap detection for this host. More information on flap detection can be found here. If you set this directive to a value of 0, the program-wide value specified by the low_host_flap_threshold directive will be used.
            high_flap_threshold:	      This directive is used to specify the high state change threshold used in flap detection for this host. More information on flap detection can be found here. If you set this directive to a value of 0, the program-wide value specified by the high_host_flap_threshold directive will be used.
            flap_detection_enabled:	   This directive is used to determine whether or not flap detection is enabled for this host. More information on flap detection can be found here. Values: 0 = disable host flap detection, 1 = enable host flap detection.
            flap_detection_options:	   This directive is used to determine what service states the flap detection logic will use for this service. Valid options are a combination of one or more of the following: o = OK states, w = WARNING states, c = CRITICAL states, u = UNKNOWN states.
            */
            'flap_detection_enabled' => 0,
            'flap_detection_options' => 'o,w,c,u',
            'low_flap_threshold'     => 25,
            'high_flap_threshold'    => 50,

            'stalking_options' => '',

            'failure_prediction_enabled'   => 0,
            'retain_status_information'    => 0,
            'retain_nonstatus_information' => 0,

            // Shinken service parameters
            'notes' => '',
            'notes_url' => '',
            'action_url' => '',
            'icon_image' => '',
            'icon_image_alt' => '',
         ),
         'contacts' => array(
            // Default user category
            'user_category' => 'glpi',
            // Default user's note : this prefix + monitoring template name
            'note' => 'Monitoring template : ',
            // Default host/service notification period
            'host_notification_period' => '24x7',
            'service_notification_period' => '24x7',
            'retain_status_information' => '0',
            'retain_nonstatus_information' => '0',
         )
      ),
      // Graphite configuration
      'graphite' => array(
         // Prefix
         'prefix' => array(
            'name'   => '_GRAPHITE_PRE',
            'value'  => ''
         )
      ),
      // WebUI configuration
      'webui' => array(
         // Hosts custom view
         'hostView' => array(
            'name'      => 'custom_views',
            'value'     => 'kiosk'
         ),
         // Hosts icon set
         'hostIcons' => array(
            'name'      => 'icon_set',
            'value'     => 'host'
         ),
         // Services icon set
         'serviceIcons' => array(
            'name'      => 'icon_set',
            'value'     => 'service'
         ),
         // Contacts role
         'contacts' => array(
            // Used if not defined in contact template
            'is_admin'              => '1',
            'can_submit_commands'   => '0',
            // Use this password if user has an empty password
            'password'              => 'shinken'
         ),
      ),
   );

   var $accentsource = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ', 'Ά', 'ά', 'Έ', 'έ', 'Ό', 'ό', 'Ώ', 'ώ', 'Ί', 'ί', 'ϊ', 'ΐ', 'Ύ', 'ύ', 'ϋ', 'ΰ', 'Ή', 'ή');
   var $accentdestination = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o', 'Α', 'α', 'Ε', 'ε', 'Ο', 'ο', 'Ω', 'ω', 'Ι', 'ι', 'ι', 'ι', 'Υ', 'υ', 'υ', 'υ', 'Η', 'η');

   function generateConfig() {

      return true;
   }



   function writeFile($name, $array) {
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

   function removeAccents($str) {
      return str_replace($this->accentsource, $this->accentdestination, $str);
   }

   function shinkenFilter($str) {
      return preg_replace("/[^A-Za-z0-9\-_]/","", strtolower(self::removeAccents($str)));
   }

   function graphiteFilter($str) {
      return preg_replace("/[^A-Za-z0-9.]/","", strtolower(self::removeAccents($str)));
   }



   function generateCommandsCfg($file=0) {

      PluginMonitoringToolbox::logIfExtradebug(
         'pm-shinken',
         "Starting generateCommandsCfg ...\n"
      );
      $pmCommand = new PluginMonitoringCommand();
      $pmNotificationcommand = new PluginMonitoringNotificationcommand();
      $pmEventhandler = new PluginMonitoringEventhandler();

      $a_commands = array();
      $i=0;

      // Only active commands and notification commands ...
      $a_list = $pmCommand->find("`is_active`='1'");
      $a_listnotif = $pmNotificationcommand->find("`is_active`='1'");
      $a_list = array_merge($a_list, $a_listnotif);

      $reload_shinken_found = false;
      $restart_shinken_found = false;
      $restart_shinken_1_4_found = false;
      foreach ($a_list as $data) {
         if ($data['command_name'] == "bp_rule") {
            continue;
         }

         $a_commands[$i] = array();

         // For comments ...
         $a_commands[$i] = $this->add_value_type($data['name'], 'name', $a_commands[$i]);

         // For Shinken ...
         $a_commands[$i] = $this->add_value_type(
                 PluginMonitoringCommand::$command_prefix . $data['command_name'],
                 'command_name', $a_commands[$i]);
         $a_commands[$i] = $this->add_value_type($data['command_line'], 'command_line', $a_commands[$i]);
         if (! empty($data['module_type'])) {
            $a_commands[$i] = $this->add_value_type($data['module_type'], 'module_type', $a_commands[$i]);
         }
         if (! empty($data['poller_tag'])) {
            $a_commands[$i] = $this->add_value_type($data['poller_tag'], 'poller_tag', $a_commands[$i]);
         }
         if (! empty($data['reactionner_tag'])) {
            $a_commands[$i] = $this->add_value_type($data['reactionner_tag'], 'reactionner_tag', $a_commands[$i]);
         }

         if ($data['command_name'] == "reload-shinken") {
            $reload_shinken_found = true;
            // No prefix for this command (WS arbiter)
            $a_commands[$i] = $this->add_value_type($data['command_name'], 'command_name', $a_commands[$i]);
         }
         if ($data['command_name'] == "restart-shinken") {
            $restart_shinken_found = true;
            // No prefix for this command (WS arbiter)
            $a_commands[$i] = $this->add_value_type($data['command_name'], 'command_name', $a_commands[$i]);
         }
         if ($data['command_name'] == "restart_shinken") {
            $restart_shinken_1_4_found = true;
         }
         PluginMonitoringToolbox::logIfExtradebug(
            'pm-shinken',
            "- command: ".$a_commands[$i]['command_name']." -> ".$a_commands[$i]['name']."\n"
         );
         $a_commands[$i] = $this->properties_list_to_string($a_commands[$i]);
         $i++;
      }
      if (! $restart_shinken_1_4_found) {
         // * Restart shinken command
         if (!isset($a_commands[$i])) {
            $a_commands[$i] = array();
         }
         $a_commands[$i] = $this->add_value_type('Restart Shinken (1.4)', 'name', $a_commands[$i]);
         $a_commands[$i] = $this->add_value_type('restart_shinken', 'command_name', $a_commands[$i]);
         $a_commands[$i] = $this->add_value_type(
                 "nohup sh -c '/usr/local/shinken/bin/stop_arbiter.sh && sleep 3 && /usr/local/shinken/bin/launch_arbiter.sh'  > /dev/null 2>&1 &",
                 'command_line', $a_commands[$i]);
      }
      if (! $reload_shinken_found) {
         // * Reload shinken command (2.0)
         if (!isset($a_commands[$i])) {
            $a_commands[$i] = array();
         }
         $a_commands[$i] = $this->add_value_type('Reload Shinken configuration', 'name', $a_commands[$i]);
         $a_commands[$i] = $this->add_value_type('reload-shinken', 'command_name', $a_commands[$i]);
         $a_commands[$i] = $this->add_value_type(
                 "nohup sh -c '/etc/init.d/shinken reload' > /dev/null 2>&1 &",
                 'command_line', $a_commands[$i]);
      }
      if (! $restart_shinken_found) {
         // * Restart shinken command (2.0)
         if (!isset($a_commands[$i])) {
            $a_commands[$i] = array();
         }
         $a_commands[$i] = $this->add_value_type('Restart Shinken', 'name', $a_commands[$i]);
         $a_commands[$i] = $this->add_value_type('restart-shinken', 'command_name', $a_commands[$i]);
         $a_commands[$i] = $this->add_value_type(
                 "nohup sh -c '/etc/init.d/shinken restart' > /dev/null 2>&1 &",
                 'command_line', $a_commands[$i]);
      }

      // Event handlers
      $a_list = $pmEventhandler->find("`is_active`='1'");
      foreach ($a_list as $data) {
         if ($data['command_name'] != "bp_rule") {
            if (!isset($a_commands[$i])) {
               $a_commands[$i] = array();
            }

            $a_commands[$i] = $this->add_value_type($data['name'], 'name', $a_commands[$i]);
            $a_commands[$i] = $this->add_value_type(
                    PluginMonitoringCommand::$command_prefix . $data['command_name'],
                    'command_name', $a_commands[$i]);
            $a_commands[$i] = $this->add_value_type($data['command_line'], 'command_line', $a_commands[$i]);
            PluginMonitoringToolbox::logIfExtradebug(
               'pm-shinken',
               "- command: ".$a_commands[$i]['command_name']." -> ".$a_commands[$i]['name']."\n"
            );
            $a_commands[$i] = $this->properties_list_to_string($a_commands[$i]);
            $i++;
         }
      }
      PluginMonitoringToolbox::logIfExtradebug(
         'pm-shinken',
         "End generateCommandsCfg\n"
      );

      if ($file == "1") {
         $config = "# Generated by plugin monitoring for GLPI\n# on ".date("Y-m-d H:i:s")."\n\n";
         foreach ($a_commands as $data) {
            $config .= "# ".$data['name']."\n";
            unset($data['name']);
            $config .= $this->writeFile("command", $data);
         }
         return array('commands.cfg', $config);
      } else {
         return $a_commands;
      }
   }



   function generateHostsCfg($file=0, $tag='') {
      global $DB;

      // Log Shinken restart event with Tag information ...
      // Should be moved to the webservice caller function ???
      $pmLog = new PluginMonitoringLog();
      if (isset($_SERVER['HTTP_USER_AGENT'])
              AND strstr($_SERVER['HTTP_USER_AGENT'], 'xmlrpclib.py')) {
         if (!isset($_SESSION['glpi_currenttime'])) {
            $_SESSION['glpi_currenttime'] = date("Y-m-d H:i:s");
         }
         $input = array();
         $input['user_name'] = "Shinken";
         $input['action'] = "restart";
         $input['date_mod'] = date("Y-m-d H:i:s");
         $input['value'] = $tag;
         $pmLog->add($input);
      }

      PluginMonitoringToolbox::logIfExtradebug(
         'pm-shinken',
         "Starting generateHostsCfg ($tag) ...\n"
      );
      $pmCommand     = new PluginMonitoringCommand();
      $pmCheck       = new PluginMonitoringCheck();
      $pmComponent   = new PluginMonitoringComponent();
      $pmEntity      = new PluginMonitoringEntity();
      $pmHostconfig  = new PluginMonitoringHostconfig();
      $pmHost        = new PluginMonitoringHost();
      $calendar      = new Calendar();
      $pmRealm       = new PluginMonitoringRealm();
      $networkEquipment = new NetworkEquipment();
      $pmContact_Item = new PluginMonitoringContact_Item();
      $profile_User   = new Profile_User();
      $pmEventhandler = new PluginMonitoringEventhandler();
      $user           = new User();
      $pmConfig       = new PluginMonitoringConfig();
      $computerType   = new ComputerType();

      $default_host = self::$shinkenParameters['shinken']['hosts'];

      // Get computer type container / VM
      $conteners = $computerType->find("`name`='BSDJail'");

      $pmConfig->getFromDB(1);

      $a_hosts = array();
      $i=0;
      $a_parents_found = array();
      $a_hosts_found = array();

      $a_entities_allowed = $pmEntity->getEntitiesByTag($tag);
      $a_entities_list = array();
      foreach ($a_entities_allowed as $entity) {
         // @ddurieux: should array_merge ($a_entities_list and getSonsOf("glpi_entities", $entity)) ?
         $a_entities_list = getSonsOf("glpi_entities", $entity);
      }
      $where = '';
      if (! isset($a_entities_allowed['-1'])) {
         $where = getEntitiesRestrictRequest("WHERE", "glpi_entities", '', $a_entities_list);
      }


      // * Prepare contacts
      $a_contacts_entities = array();
      $a_list_contact = $pmContact_Item->find("`itemtype`='PluginMonitoringComponentscatalog'
         AND `users_id`>0");
      foreach ($a_list_contact as $data) {
         $contactentities = getSonsOf('glpi_entities', $data['entities_id']);
         if (isset($a_contacts_entities[$data['items_id']][$data['users_id']])) {
            $contactentities = array_merge($contactentities, $a_contacts_entities[$data['items_id']][$data['users_id']]);
         }
         $a_contacts_entities[$data['items_id']][$data['users_id']] = $contactentities;
      }

      $query = "SELECT
         `glpi_plugin_monitoring_componentscatalogs_hosts`.*,
         CONCAT_WS('', `glpi_computers`.`id`, `glpi_printers`.`id`, `glpi_networkequipments`.`id`) AS id,
         CONCAT_WS('', `glpi_computers`.`entities_id`, `glpi_printers`.`entities_id`, `glpi_networkequipments`.`id`) AS device_entities_id,
         CONCAT_WS('', `glpi_computers`.`comment`, `glpi_printers`.`comment`, `glpi_networkequipments`.`comment`) AS comment,
         `glpi_entities`.`id` AS entityId, `glpi_entities`.`name` AS entityName,
         `glpi_entities`.`completename` AS entityFullName,
         `glpi_locations`.`id`, `glpi_locations`.`completename` AS locationName,
         `glpi_locations`.`comment` AS locationComment, `glpi_locations`.`building` AS locationGPS,
         `glpi_plugin_monitoring_services`.`networkports_id`
         FROM `glpi_plugin_monitoring_componentscatalogs_hosts`
         LEFT JOIN `glpi_computers`
            ON `glpi_computers`.`id` = `glpi_plugin_monitoring_componentscatalogs_hosts`.`items_id`
               AND `glpi_plugin_monitoring_componentscatalogs_hosts`.`itemtype`='Computer'
         LEFT JOIN `glpi_printers`
            ON `glpi_plugin_monitoring_componentscatalogs_hosts`.`items_id` = `glpi_printers`.`id`
               AND `glpi_plugin_monitoring_componentscatalogs_hosts`.`itemtype`='Printer'
         LEFT JOIN `glpi_networkequipments`
            ON `glpi_plugin_monitoring_componentscatalogs_hosts`.`items_id` = `glpi_networkequipments`.`id`
               AND `glpi_plugin_monitoring_componentscatalogs_hosts`.`itemtype`='NetworkEquipment'

         LEFT JOIN `glpi_entities`
            ON ((`glpi_computers`.`entities_id` = `glpi_entities`.`id`
                  AND `glpi_computers`.`id` IS NOT NULL)
                OR
                (`glpi_printers`.`entities_id` = `glpi_entities`.`id`
                  AND `glpi_printers`.`id` IS NOT NULL)
                OR
                (`glpi_networkequipments`.`entities_id` = `glpi_entities`.`id`
                  AND `glpi_networkequipments`.`id` IS NOT NULL)
                )

         LEFT JOIN `glpi_locations` ON `glpi_locations`.`id` = `glpi_computers`.`locations_id`
         LEFT JOIN `glpi_plugin_monitoring_services`
            ON `glpi_plugin_monitoring_services`.`plugin_monitoring_componentscatalogs_hosts_id`
               = `glpi_plugin_monitoring_componentscatalogs_hosts`.`id`
         $where
         GROUP BY `itemtype`, `items_id`";
      $result = $DB->query($query);
      while ($data=$DB->fetch_array($result)) {

         $a_hosts[$i] = array();
         $classname = $data['itemtype'];
         $class = new $classname;
         if (! $class->getFromDB($data['items_id'])) {
            Toolbox::logDebug('[Monitoring] Host item not found: '.print_r($data, true));
            return;
         }

         $pmHost->getFromDBByQuery("WHERE `glpi_plugin_monitoring_hosts`.`itemtype` = '" . $data['itemtype'] . "' AND `glpi_plugin_monitoring_hosts`.`items_id` = '" . $data['items_id'] . "' LIMIT 1");

         $a_hosts[$i] = $this->add_value_type(
                 self::shinkenFilter($class->fields['name']),
                 'host_name', $a_hosts[$i]);
         if ($pmConfig->fields['append_id_hostname'] == 1) {
            $hn = array();
            $hn = $this->add_value_type($class->fields['id'], 'host_name', $hn);
            $a_hosts[$i]['host_name'] .= "-".$hn['host_name'];
         }
         // Fix if hostname is not defined ...
         if (empty($a_hosts[$i]['host_name'])) {
            continue;
         }
         $a_hosts_found[$a_hosts[$i]['host_name']] = 1;
         PluginMonitoringToolbox::logIfExtradebug(
            'pm-shinken',
            " - add host ".$a_hosts[$i]['host_name']."\n"
         );

         // Host customs variables
         $a_hosts[$i] = $this->add_value_type(
                 $pmHost->getField('id'), '_HOSTID', $a_hosts[$i]);
         if (isset(self::$shinkenParameters['glpi']['entityId'])) {
            $a_hosts[$i] = $this->add_value_type(
                    $data['entityId'],
                    self::$shinkenParameters['glpi']['entityId'], $a_hosts[$i]);
         }
         if (isset(self::$shinkenParameters['glpi']['itemType'])) {
            $a_hosts[$i] = $this->add_value_type(
                    $classname,
                    self::$shinkenParameters['glpi']['itemType'], $a_hosts[$i]);
         }
         if (isset(self::$shinkenParameters['glpi']['itemId'])) {
            $a_hosts[$i] = $this->add_value_type(
                    $data['items_id'],
                    self::$shinkenParameters['glpi']['itemId'], $a_hosts[$i]);
         }

         if (isset(self::$shinkenParameters['glpi']['entityName'])) {
            $a_hosts[$i] = $this->add_value_type(
                    strtolower(self::shinkenFilter($data['entityName'])),
                    self::$shinkenParameters['glpi']['entityName'], $a_hosts[$i]);
         }

         // Dynamic setup of a default parameter ...
         self::$shinkenParameters['glpi']['rootEntity'] = __('Root entity');
         $data['entityFullName'] = preg_replace("/ > /","#",$data['entityFullName']);
         $data['entityFullName'] = preg_replace("/". self::$shinkenParameters['glpi']['rootEntity'] ."#/","",$data['entityFullName']);
         $data['entityFullName'] = preg_replace("/#/","_",$data['entityFullName']);
         if (isset(self::$shinkenParameters['glpi']['entityComplete'])) {
            $a_hosts[$i] = $this->add_value_type(
                    self::shinkenFilter ($data['entityFullName']),
                    self::$shinkenParameters['glpi']['entityComplete'], $a_hosts[$i]);
         }
         $data['entityFullName'] = preg_replace("/_/",".",$data['entityFullName']);

         // Graphite
         if (isset(self::$shinkenParameters['graphite']['prefix']['name'])) {
            // Dynamic setup of a default parameter ...
            self::$shinkenParameters['graphite']['prefix']['value'] = $pmHostconfig->getValueAncestor('graphite_prefix', $data['entityId']);
            if (self::$shinkenParameters['graphite']['prefix']['value'] != '') {
               self::$shinkenParameters['graphite']['prefix']['value'] .= '.';
            }

            $a_hosts[$i] = $this->add_value_type(
                    strtolower(self::$shinkenParameters['graphite']['prefix']['value'] . self::graphiteFilter($data['entityFullName'])),
                    self::$shinkenParameters['graphite']['prefix']['name'], $a_hosts[$i]);
         }

         // Location
         if (isset(self::$shinkenParameters['glpi']['location'])) {
            if (! empty($data['locationName'])) {
               $string = utf8_decode(strip_tags(trim($data['locationName'])));
               $string = preg_replace("/[\r\n]/",".",$data['locationName']);
               $string = $this->shinkenFilter($string);
               $a_hosts[$i] = $this->add_value_type(
                       $string,
                       self::$shinkenParameters['glpi']['location'], $a_hosts[$i]);
               $data['hostLocation'] = $string;
            }
         }
         if (isset(self::$shinkenParameters['glpi']['lat'])) {
            if (! empty($data['locationGPS'])) {
               $split = explode(',', $data['locationGPS']);
               if (count($split) > 2) {
                  // At least 3 elements, let us consider as GPS coordinates with altitude ...
                  $a_hosts[$i] = $this->add_value_type(
                          $split[0],
                          self::$shinkenParameters['glpi']['lat'], $a_hosts[$i]);
                  $a_hosts[$i] = $this->add_value_type(
                          $split[1],
                          self::$shinkenParameters['glpi']['lng'], $a_hosts[$i]);
                  $a_hosts[$i] = $this->add_value_type(
                          $split[2],
                          self::$shinkenParameters['glpi']['alt'], $a_hosts[$i]);
               } else if (count($split) > 1) {
                  // At least 2 elements, let us consider as GPS coordinates ...
                  $a_hosts[$i] = $this->add_value_type(
                          $split[0],
                          self::$shinkenParameters['glpi']['lat'], $a_hosts[$i]);
                  $a_hosts[$i] = $this->add_value_type(
                          $split[1],
                          self::$shinkenParameters['glpi']['lng'], $a_hosts[$i]);
               }
            }
         }

         // Hostgroup name
         $a_hosts[$i] = $this->add_value_type(
                 preg_replace("/[ ]/","_", self::shinkenFilter($data['entityName'])),
                 'hostgroups', $a_hosts[$i]);
         // Alias
         $a_hosts[$i] = $this->add_value_type(
                 $this->shinkenFilter($data['entityName'])." / ". $a_hosts[$i]['host_name'],
                 'alias', $a_hosts[$i]);
         if (isset($data['hostLocation'])) {
            $hn = array();
            $hn = $this->add_value_type(" (".$data['hostLocation'].")", 'alias', $hn);
            $a_hosts[$i]['alias'] .= "-".$hn['alias'];
         }

         // WebUI user interface ...
         if (isset(self::$shinkenParameters['webui']['hostIcons']['name'])) {
            $a_hosts[$i] = $this->add_value_type(
                    self::$shinkenParameters['webui']['hostIcons']['value'],
                    self::$shinkenParameters['webui']['hostIcons']['name'], $a_hosts[$i]);
         }
         if (isset(self::$shinkenParameters['webui']['hostView']['name'])) {
            $a_hosts[$i] = $this->add_value_type(
                    self::$shinkenParameters['webui']['hostView']['value'],
                    self::$shinkenParameters['webui']['hostView']['name'], $a_hosts[$i]);
         }

         // IP address
         $ip = PluginMonitoringHostaddress::getIp($data['items_id'], $data['itemtype'], $class->fields['name']);
         $a_hosts[$i] = $this->add_value_type($ip, 'address', $a_hosts[$i]);

         // use host IP of container if activated
         if ($data['itemtype'] == 'Computer') {
            if ($pmConfig->fields['nrpe_prefix_contener'] == 1) {
               if (isset($conteners[$class->fields['computertypes_id']])) {
                  // get Host of contener/VM
                  $where = "LOWER(`uuid`)".  ComputerVirtualMachine::getUUIDRestrictRequest($class->fields['uuid']);
                  $hosts = getAllDatasFromTable('glpi_computervirtualmachines', $where);
                  if (!empty($hosts)) {
                     $host = current($hosts);
                     $ip = PluginMonitoringHostaddress::getIp($host['computers_id'], 'Computer', '');
                     $a_hosts[$i] = $this->add_value_type($ip, 'address', $a_hosts[$i]);
                  }
               }
            }
         }


         // Manage dependencies
         $parent = '';
         if ($data['itemtype'] != 'NetworkEquipment') {
            $networkPort = new NetworkPort();
            $a_networkports = $networkPort->find("`itemtype`='".$data['itemtype']."'
               AND `items_id`='".$data['items_id']."'");
            foreach ($a_networkports as $data_n) {
               $networkports_id = $networkPort->getContact($data_n['id']);
               if ($networkports_id) {
                  $networkPort->getFromDB($networkports_id);
                  if ($networkPort->fields['itemtype'] == 'NetworkEquipment') {
                     $networkEquipment->getFromDB($networkPort->fields['items_id']);
                     $parent = self::shinkenFilter($networkEquipment->fields['name']);
                     $a_parents_found[$parent] = 1;
                     $pmHost->updateDependencies($classname, $data['items_id'], 'NetworkEquipment-'.$networkPort->fields['items_id']);
                  }
               }
            }

            if (empty($parent)) {
               if ($default_host['parents'] == 'entity') {
                  foreach ($a_hosts[$i]['hostgroups'] as $val) {
                     $a_hosts[$i] = $this->add_value_type(
                             self::$shinkenParameters['shinken']['fake_hosts']['name_prefix'].$val,
                             'parents', $a_hosts[$i]);
                  }
               } else {
                  $a_hosts[$i] = $this->add_value_type($default_host['parents'], 'parents', $a_hosts[$i]);
               }
            }
         }

         // Host check command
         $pmComponent->getFromDB($pmHostconfig->getValueAncestor('plugin_monitoring_components_id',
                                                                  $class->fields['entities_id'],
                                                                  $classname,
                                                                  $class->getID()));

         $pmCommand->getFromDB($pmComponent->fields['plugin_monitoring_commands_id']);

         $a_fields = $pmComponent->fields;

         // Manage host check_command arguments
         $array = array();
         preg_match_all("/\\$(ARG\d+)\\$/", $pmCommand->fields['command_line'], $array);
         sort($array[0]);
         $a_arguments = importArrayFromDB($pmCommand->fields['arguments']);
         $a_argumentscustom = importArrayFromDB($a_fields['arguments']);
         foreach ($a_argumentscustom as $key=>$value) {
            $a_arguments[$key] = $value;
         }
         foreach ($a_arguments as $key=>$value) {
            $a_arguments[$key] = str_replace('!', '\!', html_entity_decode($value));
         }
         $args = '';
         foreach ($array[0] as $arg) {
            if ($arg != '$PLUGINSDIR$'
                    AND $arg != '$NAGIOSPLUGINSDIR$'
                    AND $arg != '$HOSTADDRESS$'
                    AND $arg != '$MYSQLUSER$'
                    AND $arg != '$MYSQLPASSWORD$') {
               $arg = str_replace('$', '', $arg);
               if (!isset($a_arguments[$arg])) {
                  $args .= '!';
               } else {
                  if (strstr($a_arguments[$arg], "[[HOSTNAME]]")) {
                     $a_arguments[$arg] = str_replace(
                             "[[HOSTNAME]]",
                             $class->fields['name'],
                             $a_arguments[$arg]);
                  } elseif (strstr($a_arguments[$arg], "[[NETWORKPORTDESCR]]")){
                     if (class_exists("PluginFusioninventoryNetworkPort")) {
                        $pfNetworkPort = new PluginFusioninventoryNetworkPort();
                        $pfNetworkPort->loadNetworkport($data['networkports_id']);
                        $descr = $pfNetworkPort->getValue("ifdescr");
                        $a_arguments[$arg] = str_replace("[[NETWORKPORTDESCR]]", $descr, $a_arguments[$arg]);
                     }
                  } elseif (strstr($a_arguments[$arg], "[[NETWORKPORTNUM]]")){
                     $networkPort = new NetworkPort();
                     if (isset($data['networkports_id'])
                             && $data['networkports_id'] > 0) {
                        $networkPort->getFromDB($data['networkports_id']);
                     } else if ($classname == 'Computer') {
                        $networkPort = PluginMonitoringHostaddress::getNetworkport($class->fields['id'], $classname);
                     }
                     if ($networkPort->getID() > 0) {
                        $logicalnum = $networkPort->fields['logical_number'];
                        $a_arguments[$arg] = str_replace("[[NETWORKPORTNUM]]", $logicalnum, $a_arguments[$arg]);
                     }
                  } elseif (strstr($a_arguments[$arg], "[[NETWORKPORTNAME]]")) {
                     $networkPort = new NetworkPort();
                     if (isset($data['networkports_id'])
                             && $data['networkports_id'] > 0) {
                        $networkPort->getFromDB($data['networkports_id']);
                     } else if ($classname == 'Computer') {
                        $networkPort = PluginMonitoringHostaddress::getNetworkport($class->fields['id'], $classname);
                     }
                     if ($networkPort->getID() > 0) {
                        $portname = $networkPort->fields['name'];
                        $a_arguments[$arg] = str_replace("[[NETWORKPORTNAME]]", $portname, $a_arguments[$arg]);
                     }
                  } else if (strstr($a_arguments[$arg], '[[IP]]')) {
                     $ip = PluginMonitoringHostaddress::getIp(
                             $data['items_id'], $data['itemtype'], '');
                     $a_arguments[$arg] = str_replace("[[IP]]", $ip, $a_arguments[$arg]);
                  } else if (strstr($a_arguments[$arg], "[")) {
                     $a_arguments[$arg] = PluginMonitoringService::convertArgument($data['id'], $a_arguments[$arg]);
                  }
                  if ($a_arguments == '') {
                     $notadd = 1;
                     if ($notadddescription != '') {
                        $notadddescription .= ", ";
                     }
                     $notadddescription .= "Argument ".$a_arguments[$arg]." Not have value";
                  }
                  $args .= '!'.$a_arguments[$arg];
                  if ($a_arguments[$arg] == ''
                          AND $a_component['alias_command'] != '') {
                     $args .= $a_component['alias_command'];
                  }
               }
            }
         }
         $a_hosts[$i] = $this->add_value_type(
                 PluginMonitoringCommand::$command_prefix . $pmCommand->fields['command_name'].$args,
                 'check_command', $a_hosts[$i]);

         // Check retries
         $pmCheck->getFromDB($a_fields['plugin_monitoring_checks_id']);
         $a_hosts[$i] = $this->add_value_type(
                 $pmCheck->fields['check_interval'], 'check_interval',
                 $a_hosts[$i]);
         $a_hosts[$i] = $this->add_value_type(
                 $pmCheck->fields['retry_interval'], 'retry_interval',
                 $a_hosts[$i]);
         $a_hosts[$i] = $this->add_value_type(
                 $pmCheck->fields['max_check_attempts'], 'max_check_attempts',
                 $a_hosts[$i]);

         $a_hosts[$i] = $this->add_value_type(
                 $a_fields['active_checks_enabled'], 'active_checks_enabled',
                 $a_hosts[$i]);
         $a_hosts[$i] = $this->add_value_type(
                 $a_fields['passive_checks_enabled'], 'passive_checks_enabled',
                 $a_hosts[$i]);

         // Check period
         // Host entity jetlag ...
         $timeperiodsuffix = '_'.$pmHostconfig->getValueAncestor('jetlag', $data['entityId']);
         if ($timeperiodsuffix == '_0') {
            $timeperiodsuffix = '';
         }
         // Use the calendar defined for the host entity ...
         $calendar = new Calendar();
         $cid = Entity::getUsedConfig('calendars_id', $data['entityId'], '', 0);
         PluginMonitoringToolbox::logIfExtradebug(
            'pm-shinken',
            " - add host ".$a_hosts[$i]['host_name']." in entity ".$data['entityId']. ", calendar: ". $cid ."\n"
         );

         if ($calendar->getFromDB($cid) && $this->_addTimeperiod($data['entityId'], $cid)) {
            $a_hosts[$i] = $this->add_value_type(
                    self::shinkenFilter($calendar->fields['name'].$timeperiodsuffix),
                    'check_period', $a_hosts[$i]);
         } else {
            $a_hosts[$i] = $this->add_value_type(
                    $default_host['check_period'], 'check_period', $a_hosts[$i]);
         }

         // Manage freshness
         if ($a_fields['freshness_count'] == 0) {
            $a_hosts[$i] = $this->add_value_type('0', 'check_freshness', $a_hosts[$i]);
            $a_hosts[$i] = $this->add_value_type('0', 'freshness_threshold', $a_hosts[$i]);
         } else {
            $multiple = 1;
            if ($a_fields['freshness_type'] == 'seconds') {
               $multiple = 1;
            } else if ($a_fields['freshness_type'] == 'minutes') {
               $multiple = 60;
            } else if ($a_fields['freshness_type'] == 'hours') {
               $multiple = 3600;
            } else if ($a_fields['freshness_type'] == 'days') {
               $multiple = 86400;
            }
            $a_hosts[$i] = $this->add_value_type('1', 'check_freshness', $a_hosts[$i]);
            $a_hosts[$i] = $this->add_value_type(
                    $a_fields['freshness_count'] * $multiple,
                    'freshness_threshold', $a_hosts[$i]);
         }

         // Manage event handler
         if ($a_fields['plugin_monitoring_eventhandlers_id'] > 0) {
            if ($pmEventhandler->getFromDB($a_fields['plugin_monitoring_eventhandlers_id'])) {
               $a_hosts[$i] = $this->add_value_type(
                       PluginMonitoringCommand::$command_prefix . $pmEventhandler->fields['command_name'],
                       'event_handler', $a_hosts[$i]);
               $a_hosts[$i] = $this->add_value_type(
                       '1', 'event_handler_enabled', $a_hosts[$i]);
            } else {
               $a_hosts[$i] = $this->add_value_type(
                       '1', 'event_handler_enabled', $a_hosts[$i]);
            }
         } else {
            $a_hosts[$i] = $this->add_value_type(
                    '1', 'event_handler_enabled', $a_hosts[$i]);
         }

         // Realm
         $pmRealm->getFromDB($pmHostconfig->getValueAncestor('plugin_monitoring_realms_id',
                                                                              $class->fields['entities_id'],
                                                                              $classname,
                                                                              $class->getID()));
         $a_hosts[$i] = $this->add_value_type(
                 $pmRealm->fields['name'], 'realm', $a_hosts[$i]);

         // Business impact
         if (isset ($a_fields['business_priority'])) {
            $a_hosts[$i] = $this->add_value_type(
                 $a_fields['business_priority'],
                 'business_impact', $a_hosts[$i]);
         } else {
            if (! empty($default_host['business_impact'])) {
               $a_hosts[$i] = $this->add_value_type(
                       $default_host['business_impact'],
                       'business_impact', $a_hosts[$i]);
            } else {
               $a_hosts[$i] = $this->add_value_type(
                       '0', 'business_impact', $a_hosts[$i]);
            }
         }

         // Additional information in host note
         /* Shinken WebUI notes definition:

            # Define a simple classic note
               #notes                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin et leo gravida, lobortis nunc nec, imperdiet odio. Vivamus quam velit, scelerisque nec egestas et, semper ut massa. Vestibulum id tincidunt lacus. Ut in arcu at ex egestas vestibulum eu non sapien. Nulla facilisi. Aliquam non blandit tellus, non luctus tortor. Mauris tortor libero, egestas quis rhoncus in, sollicitudin et tortor.

               # Define a classic note with a title
               #notes                KB1023::note with a title

               # Define a note with a title and an icon (from font-awesome icons)
               #notes                KB1023,,tag::<strong>Lorem ipsum dolor sit amet</strong>, consectetur adipiscing elit. Proin et leo gravida, lobortis nunc nec, imperdiet odio. Vivamus quam velit, scelerisque nec egestas et, semper ut massa. Vestibulum id tincidunt lacus. Ut in arcu at ex egestas vestibulum eu non sapien. Nulla facilisi. Aliquam non blandit tellus, non luctus tortor. Mauris tortor libero, egestas quis rhoncus in, sollicitudin et tortor.

               # Define two notes with a title and an icon
               #notes                KB1023,,tag::<strong>Lorem ipsum dolor sit amet</strong>, consectetur adipiscing elit. Proin et leo gravida, lobortis nunc nec, imperdiet odio. Vivamus quam velit, scelerisque nec egestas et, semper ut massa. Vestibulum id tincidunt lacus. Ut in arcu at ex egestas vestibulum eu non sapien. Nulla facilisi. Aliquam non blandit tellus, non luctus tortor. Mauris tortor libero, egestas quis rhoncus in, sollicitudin et tortor.|KB1024,,tag::<strong>Lorem ipsum dolor sit amet</strong>, consectetur adipiscing elit. Proin et leo gravida, lobortis nunc nec, imperdiet odio. Vivamus quam velit, scelerisque nec egestas et, semper ut massa. Vestibulum id tincidunt lacus. Ut in arcu at ex egestas vestibulum eu non sapien. Nulla facilisi. Aliquam non blandit tellus, non luctus tortor. Mauris tortor libero, egestas quis rhoncus in, sollicitudin et tortor.

               notes_url            http://www.my-KB.fr?host=$HOSTADDRESS$|http://www.my-KB.fr?host=$HOSTNAME$

         */
         // Location in notes ...
         // PluginMonitoringToolbox::logIfExtradebug(
            // 'pm-shinken',
            // " - location:{$data['locationName']} - {$data['locationComment']}\n"
         // );
         $notes = array();
         if (isset(self::$shinkenParameters['glpi']['location'])
                 && isset($data['locationName'])
                 && isset($data['locationComment'])) {
            $comment = str_replace("\r\n", "<br/>", $data['locationComment']);
            $comment = preg_replace('/[[:cntrl:]]/', '§', $comment);
            $notes[] = "Location,,home::<strong>{$data['locationName']}</strong><br/>{$comment}";
         }
         // Computer comment in notes ...
         if (isset($data['comment'])) {
            $comment = str_replace("\r\n", "<br/>", $data['comment']);
            $comment = preg_replace('/[[:cntrl:]]/', '§', $comment);
            $notes[] = "Comment,,comment::{$comment}";
         }
         if (count($notes) > 0) {
            $a_hosts[$i] = $this->add_value_type(
                    implode("|", $notes), 'notes', $a_hosts[$i]);
         }

         // Extra parameters
         // Should make a loop :/P !!!!
         $extrapram = array(
            'process_perf_data',
            'flap_detection_enabled',
            'flap_detection_options',
            'stalking_options',
            'low_flap_threshold',
            'high_flap_threshold',
            'failure_prediction_enabled',
            'retain_status_information',
            'retain_nonstatus_information',
            // 'notes',
            'notes_url',
            'action_url',
            'icon_image',
            'icon_image_alt',
            'vrml_image',
            'statusmap_image'
         );
         foreach ($extrapram as $parm) {
            if (isset($default_host[$parm])) {
               $a_hosts[$i] = $this->add_value_type(
                       $default_host[$parm], $parm, $a_hosts[$i]);
            }
         }

         // For contacts, check if a component catalog contains the host associated component ...
         $a_hosts[$i] = $this->add_value_type( '', 'contacts', $a_hosts[$i]);

         if (($a_fields['passive_checks_enabled'] == '1') and ($a_fields['active_checks_enabled'] == '0')) {
            // Specific query if host is only passively checked ...
            // Find the first component catalog in which the host is used ...
            $querycont = "SELECT * FROM `glpi_plugin_monitoring_componentscatalogs_hosts`
               LEFT JOIN `glpi_plugin_monitoring_services`
                  ON `plugin_monitoring_componentscatalogs_hosts_id`
                     = `glpi_plugin_monitoring_componentscatalogs_hosts`.`id`
               WHERE `items_id`='".$data['items_id']."' AND `itemtype`='".$data['itemtype']."'
               LIMIT 1";
         } else {
            // Find component catalog which contains the host associated component ...
            $querycont = "SELECT * FROM `glpi_plugin_monitoring_componentscatalogs_hosts`
               LEFT JOIN `glpi_plugin_monitoring_services`
                  ON `plugin_monitoring_componentscatalogs_hosts_id`
                     = `glpi_plugin_monitoring_componentscatalogs_hosts`.`id`
               WHERE `plugin_monitoring_components_id`='".$pmComponent->fields['id']."' AND
                  `items_id`='".$data['items_id']."' AND `itemtype`='".$data['itemtype']."'
               LIMIT 1";
         }

         $resultcont = $DB->query($querycont);
         if ($DB->numrows($resultcont) != 0) {
            $a_componentscatalogs_hosts = $DB->fetch_assoc($resultcont);

            PluginMonitoringToolbox::logIfExtradebug(
               'pm-shinken',
               "generateHostsCfg - CC, host: {$a_hosts[$i]['host_name']} in {$a_componentscatalogs_hosts['plugin_monitoring_componentscalalog_id']}\n"
            );
            // Notification options / interval
            $pmComponentscatalog = new PluginMonitoringComponentscatalog();
            if (! $pmComponentscatalog->getFromDB($a_componentscatalogs_hosts['plugin_monitoring_componentscalalog_id'])) {
               continue;
            }

            PluginMonitoringToolbox::logIfExtradebug(
               'pm-shinken',
               "generateHostsCfg - CC, host: {$a_hosts[$i]['host_name']} in {$pmComponentscatalog->fields['name']}\n"
            );
            // Host template/tag is CC name
            $a_hosts[$i] = $this->add_value_type(
                    $pmComponentscatalog->fields['name'],
                    'use', $a_hosts[$i]);

            // Hosts notification
            $pmHNTemplate = new PluginMonitoringHostnotificationtemplate();
            if ((! isset ($pmComponentscatalog->fields['hostsnotification_id']))
               ||  (! $pmHNTemplate->getFromDB($pmComponentscatalog->fields['hostsnotification_id']))) {
               // No notifications defined for host, use defaults ...
               $extrapram = array(
                  'notifications_enabled',
                  'notification_period',
                  'notification_options',
                  'notification_interval'
               );
               foreach ($extrapram as $parm) {
                  if (isset($default_host[$parm])) {
                     $a_hosts[$i] = $this->add_value_type(
                             $default_host[$parm], $parm, $a_hosts[$i]);
                  }
               }

               PluginMonitoringToolbox::logIfExtradebug(
                  'pm-shinken',
                  "generateHostsCfg - CC, host: {$a_hosts[$i]['host_name']} in {$pmComponentscatalog->fields['name']}, no notifications.\n"
               );
            } else {
               $a_HN = $pmHNTemplate->fields;

               PluginMonitoringToolbox::logIfExtradebug(
                  'pm-shinken',
                  "generateHostsCfg - CC, host: {$a_hosts[$i]['host_name']} in {$pmComponentscatalog->fields['name']}, notification template: {$a_HN['name']}.\n"
               );

               if ($a_HN['host_notifications_enabled'] == 0) {
                  // No notifications for host
                  $a_hosts[$i] = $this->add_value_type(
                          '0', 'notifications_enabled', $a_hosts[$i]);
                  $a_hosts[$i] = $this->add_value_type(
                          '24x7', 'notification_period', $a_hosts[$i]);
                  $a_hosts[$i] = $this->add_value_type(
                          '', 'notification_options', $a_hosts[$i]);
                  $a_hosts[$i] = $this->add_value_type(
                          '0', 'notification_interval', $a_hosts[$i]);

                  PluginMonitoringToolbox::logIfExtradebug(
                     'pm-shinken',
                     "generateHostsCfg - CC, host: {$a_hosts[$i]['host_name']} in {$pmComponentscatalog->fields['name']}, no notifications for host.\n"
                  );
               } else {
                  if (! isset($a_HN['host_notification_period']) ||
                        ! $a_HN['host_notifications_enabled']) {
                     // No notifications for host
                     $a_hosts[$i] = $this->add_value_type(
                             '0', 'notifications_enabled', $a_hosts[$i]);
                     $a_hosts[$i] = $this->add_value_type(
                             '24x7', 'notification_period', $a_hosts[$i]);
                     $a_hosts[$i] = $this->add_value_type(
                             '', 'notification_options', $a_hosts[$i]);
                     $a_hosts[$i] = $this->add_value_type(
                             '0', 'notification_interval', $a_hosts[$i]);

                     PluginMonitoringToolbox::logIfExtradebug(
                        'pm-shinken',
                        "generateHostsCfg - CC, host: {$a_hosts[$i]['host_name']} in {$pmComponentscatalog->fields['name']}, no notifications for host.\n"
                     );
                  } else {
                     // Notifications enabled for host
                     $a_hosts[$i] = $this->add_value_type(
                             '1', 'notifications_enabled', $a_hosts[$i]);

                     PluginMonitoringToolbox::logIfExtradebug(
                        'pm-shinken',
                        "generateHostsCfg - CC, host: {$a_hosts[$i]['host_name']}, host notification period : {$a_HN['host_notification_period']}.\n"
                     );
                     // Notification period
                     if ($calendar->getFromDB($a_HN['host_notification_period']) && $this->_addTimeperiod($class->fields['entities_id'], $a_HN['host_notification_period'])) {
                        $a_hosts[$i] = $this->add_value_type(
                                self::shinkenFilter($calendar->fields['name'].$timeperiodsuffix),
                                'notification_period', $a_hosts[$i]);
                     } else {
                        if (! empty($default_host['notification_period']))
                           $a_hosts[$i] = $this->add_value_type(
                                   $default_host['notification_period'],
                                   'notification_period', $a_hosts[$i]);
                     }

                     // Notification options
                     if ($a_HN['host_notification_options_d'] == 1) {
                        $a_hosts[$i] = $this->add_value_type(
                                "d", 'notification_options', $a_hosts[$i]);
                     }
                     if ($a_HN['host_notification_options_u'] == 1) {
                        $a_hosts[$i] = $this->add_value_type(
                                "u", 'notification_options', $a_hosts[$i]);
                     }
                     if ($a_HN['host_notification_options_r'] == 1) {
                        $a_hosts[$i] = $this->add_value_type(
                                "r", 'notification_options', $a_hosts[$i]);
                     }
                     if ($a_HN['host_notification_options_f'] == 1) {
                        $a_hosts[$i] = $this->add_value_type(
                                "f", 'notification_options', $a_hosts[$i]);
                     }
                     if ($a_HN['host_notification_options_s'] == 1) {
                        $a_hosts[$i] = $this->add_value_type(
                                "s", 'notification_options', $a_hosts[$i]);
                     }
                     if ($a_HN['host_notification_options_n'] == 1) {
                        $a_hosts[$i] = $this->add_value_type(
                                "n", 'notification_options', $a_hosts[$i]);
                     }
                     if (count($a_hosts[$i]['notification_options']) == 0) {
                        if (isset($a_hosts[$i]['notification_options'])) {
                           unset($a_hosts[$i]['notification_options']);
                        }
                        $a_hosts[$i] = $this->add_value_type(
                                "n", 'notification_options', $a_hosts[$i]);
                     }

                     // Notification interval
                     if (isset ($pmComponentscatalog->fields['notification_interval']) ) {
                        $a_hosts[$i] = $this->add_value_type(
                                $pmComponentscatalog->fields['notification_interval'],
                                'notification_interval', $a_hosts[$i]);
                     } else {
                        if (! empty($default_host['notification_interval']))
                           $a_hosts[$i] = $this->add_value_type(
                                   $default_host['notification_interval'],
                                   'notification_interval', $a_hosts[$i]);
                     }
                  }
               }
            }

            $a_hosts[$i]['contacts'] = array();
            $a_list_contact = $pmContact_Item->find("`itemtype`='PluginMonitoringComponentscatalog'
               AND `items_id`='".$a_componentscatalogs_hosts['plugin_monitoring_componentscalalog_id']."'");
            foreach ($a_list_contact as $data_contact) {
               if (isset($a_contacts_entities[$a_componentscatalogs_hosts['plugin_monitoring_componentscalalog_id']][$data_contact['users_id']])) {
                  if (in_array($class->fields['entities_id'], $a_contacts_entities[$a_componentscatalogs_hosts['plugin_monitoring_componentscalalog_id']][$data_contact['users_id']])) {
                     $user->getFromDB($data_contact['users_id']);
                     $a_hosts[$i] = $this->add_value_type(
                              $user->fields['name'],
                              'contacts', $a_hosts[$i]);
                  }
               }
            }
            if (count($a_hosts[$i]['contacts']) == 0) {
               if (self::$shinkenParameters['shinken']['fake_contacts']['build']) {
                     $a_hosts[$i] = $this->add_value_type(
                              self::$shinkenParameters['shinken']['fake_contacts']['contact_name'],
                              'contacts', $a_hosts[$i]);
               } else {
                     $a_hosts[$i] = $this->add_value_type(
                              '', 'contacts', $a_hosts[$i]);
               }
            }
         }
         $a_hosts[$i] = $this->properties_list_to_string($a_hosts[$i]);
         $i++;
      }

      // Fake host for business rules
      $pmServicescatalog = new PluginMonitoringServicescatalog();
      $a_listBA = $pmServicescatalog->find("`is_generic`='0'");
      if (count($a_listBA) > 0) {
         PluginMonitoringToolbox::logIfExtradebug(
            'pm-shinken',
            " - add host_for_bp\n"
         );
         $a_hosts[$i] = array();
         $a_hosts[$i] = $this->add_value_type(
                 self::$shinkenParameters['shinken']['fake_hosts']['name_prefix'] . self::$shinkenParameters['shinken']['fake_bp_hosts']['hostname'],
                 'host_name', $a_hosts[$i]);
         $a_hosts[$i] = $this->add_value_type(
                 PluginMonitoringCommand::$command_prefix . self::$shinkenParameters['shinken']['fake_hosts']['check_command'],
                 'check_command', $a_hosts[$i]);
         $a_hosts[$i] = $this->add_value_type(
                 'Fake host for business rules',
                 'alias', $a_hosts[$i]);
         // $a_hosts[$i]['_HOSTID'] = '0';
         // $a_hosts[$i]['_ITEMSID'] = '0';
         // $a_hosts[$i]['_ITEMTYPE'] = 'Computer';
         $a_hosts[$i] = $this->add_value_type('127.0.0.1', 'address', $a_hosts[$i]);
         $a_hosts[$i] = $this->add_value_type(
                 self::$shinkenParameters['shinken']['fake_hosts']['name_prefix'] . self::$shinkenParameters['shinken']['fake_hosts']['root_parent'],
                 'parents', $a_hosts[$i]);
         $a_hosts[$i] = $this->add_value_type(
                 self::$shinkenParameters['shinken']['fake_hosts']['hostgroup_name'],
                 'hostgroups', $a_hosts[$i]);
         $a_hosts[$i] = $this->add_value_type('1', 'active_checks_enabled', $a_hosts[$i]);
         $a_hosts[$i] = $this->add_value_type('0', 'passive_checks_enabled', $a_hosts[$i]);
         $a_hosts[$i] = $this->add_value_type('60', 'check_interval', $a_hosts[$i]);
         $a_hosts[$i] = $this->add_value_type('1', 'retry_interval', $a_hosts[$i]);
         $a_hosts[$i] = $this->add_value_type('1', 'max_check_attempts', $a_hosts[$i]);

         // Check period is always defined by the root entity !
         $a_hosts[$i] = $this->add_value_type(
                 self::$shinkenParameters['shinken']['fake_hosts']['check_period'],
                 'check_period', $a_hosts[$i]);
         // Host entity jetlag ...
         $timeperiodsuffix = '_'.$pmHostconfig->getValueAncestor('jetlag', 0);
         if ($timeperiodsuffix == '_0') {
            $timeperiodsuffix = '';
         }
         $calendar = new Calendar();
         $a_calendars = $calendar->find("`name`='".self::$shinkenParameters['shinken']['fake_hosts']['check_period']."'");
         foreach ($a_calendars as $calendar) {
            PluginMonitoringToolbox::logIfExtradebug(
               'pm-shinken',
               " - add host_for_bp 2: ".serialize($calendar)." / ".$calendar['name']."\n"
            );
            if ($this->_addTimeperiod(0, $calendar['id'])) {
               $a_hosts[$i] = $this->add_value_type(
                       self::shinkenFilter($calendar['name'].$timeperiodsuffix),
                       'check_period', $a_hosts[$i]);
            }
         }

         if (self::$shinkenParameters['shinken']['fake_contacts']['build']) {
            $a_hosts[$i] = $this->add_value_type(
                     self::$shinkenParameters['shinken']['fake_contacts']['contact_name'],
                     'contacts', $a_hosts[$i]);
         } else {
            $a_hosts[$i] = $this->add_value_type('', 'contacts', $a_hosts[$i]);
         }
         if (! empty(self::$shinkenParameters['shinken']['fake_hosts']['use'])) {
            $a_hosts[$i] = $this->add_value_type(
                    self::$shinkenParameters['shinken']['fake_hosts']['use'],
                    'use', $a_hosts[$i]);
         }

         $extrapram = array(
            'process_perf_data',
            'notification_period',
            'notification_options',
            'notification_interval',
            'notes',
            'notes_url',
            'action_url',
            'icon_image',
            'icon_image_alt',
            'vrml_image',
            'statusmap_image'
         );
         foreach ($extrapram as $parm) {
            if (isset($default_host[$parm])) {
               $a_hosts[$i] = $this->add_value_type(
                       $default_host[$parm], $parm, $a_hosts[$i]);
            }
         }
         $a_hosts[$i] = $this->properties_list_to_string($a_hosts[$i]);
         $i++;
      }


      // Add one fake host for each entity
      if (self::$shinkenParameters['shinken']['fake_hosts']['build']) {
         PluginMonitoringToolbox::logIfExtradebug(
            'pm-shinken',
            " - add fake hosts for parents relationship\n"
         );

         // Main root parent
         $a_hosts[$i] = $this->add_value_type(
                 self::$shinkenParameters['shinken']['fake_hosts']['name_prefix'] . self::$shinkenParameters['shinken']['fake_hosts']['root_parent'],
                 'host_name', $a_hosts[$i]);
         // $a_hosts[$i]['check_command'] = PluginMonitoringCommand::$command_prefix . 'check_dummy!0';
         $a_hosts[$i] = $this->add_value_type(
                 PluginMonitoringCommand::$command_prefix . self::$shinkenParameters['shinken']['fake_hosts']['check_command'],
                 'check_command', $a_hosts[$i]);
         $a_hosts[$i] = $this->add_value_type(
                 'Main root parent', 'alias', $a_hosts[$i]);
         // $a_hosts[$i]['_HOSTID'] = '0';
         // $a_hosts[$i]['_ITEMSID'] = '0';
         // $a_hosts[$i]['_ITEMTYPE'] = 'Computer';
         $a_hosts[$i] = $this->add_value_type(
                 '127.0.0.1', 'address', $a_hosts[$i]);
         $a_hosts[$i] = $this->add_value_type('', 'parents', $a_hosts[$i]);
         $a_hosts[$i] = $this->add_value_type(
                 self::$shinkenParameters['shinken']['fake_hosts']['hostgroup_name'],
                 'hostgroups', $a_hosts[$i]);
         $a_hosts[$i] = $this->add_value_type('60', 'check_interval', $a_hosts[$i]);
         $a_hosts[$i] = $this->add_value_type('1', 'retry_interval', $a_hosts[$i]);
         $a_hosts[$i] = $this->add_value_type('1', 'max_check_attempts', $a_hosts[$i]);
         // Check period is always defined by the root entity !
         $a_hosts[$i] = $this->add_value_type(
                 self::$shinkenParameters['shinken']['fake_hosts']['check_period'],
                 'check_period', $a_hosts[$i]);
         // Host entity jetlag ...
         $timeperiodsuffix = '_'.$pmHostconfig->getValueAncestor('jetlag', 0);
         if ($timeperiodsuffix == '_0') {
            $timeperiodsuffix = '';
         }
         $calendar = new Calendar();
         $a_calendars = $calendar->find("`name`='".self::$shinkenParameters['shinken']['fake_hosts']['check_period']."'");
         foreach ($a_calendars as $calendar) {
            if ($this->_addTimeperiod(0, $calendar['id'])) {
               $a_hosts[$i] = $this->add_value_type(
                       self::shinkenFilter($calendar['name'].$timeperiodsuffix),
                       'check_period', $a_hosts[$i]);
            }
         }

         if (self::$shinkenParameters['shinken']['fake_contacts']['build']) {
            $a_hosts[$i] = $this->add_value_type(
                    self::$shinkenParameters['shinken']['fake_contacts']['contact_name'],
                    'contacts', $a_hosts[$i]);
         } else {
            $a_hosts[$i] = $this->add_value_type('', 'contacts', $a_hosts[$i]);
         }
         if (! empty(self::$shinkenParameters['shinken']['fake_hosts']['use'])) {
            $a_hosts[$i] = $this->add_value_type(
                    self::$shinkenParameters['shinken']['fake_hosts']['use'],
                    'use', $a_hosts[$i]);
         }

         $extrapram = array(
            'process_perf_data',
            'notification_period',
            'notification_options',
            'notification_interval',
            'notes',
            'notes_url',
            'action_url',
            'icon_image',
            'icon_image_alt',
            'vrml_image',
            'statusmap_image'
         );
         foreach ($extrapram as $parm) {
            if (isset($default_host[$parm])) {
               $a_hosts[$i] = $this->add_value_type(
                       $default_host[$parm], $parm, $a_hosts[$i]);
            }
         }
         $a_hosts[$i] = $this->properties_list_to_string($a_hosts[$i]);
         $i++;

         $a_entities_allowed = $pmEntity->getEntitiesByTag($tag);
         $a_entities_list = array();
         foreach ($a_entities_allowed as $entity) {
            // @ddurieux: should array_merge ($a_entities_list and getSonsOf("glpi_entities", $entity)) ?
            $a_entities_list = getSonsOf("glpi_entities", $entity);
         }
         $where = '';
         if (! isset($a_entities_allowed['-1'])) {
            $where = getEntitiesRestrictRequest("WHERE", "glpi_entities", '', $a_entities_list);
         }

         $query = "SELECT
            `glpi_entities`.`id` AS entityId, `glpi_entities`.`name` AS entityName
            FROM `glpi_entities` $where";
         $result = $DB->query($query);
         while ($dataEntity=$DB->fetch_array($result)) {
            // Hostgroup name : used as host name for parents ...
            $fake_host_name = self::shinkenFilter($dataEntity['entityName']);
            $fake_host_name = preg_replace("/[ ]/","_",$fake_host_name);

            $a_hosts[$i] = $this->add_value_type(
                    self::$shinkenParameters['shinken']['fake_hosts']['name_prefix'] . $fake_host_name,
                    'host_name', $a_hosts[$i]);
            $a_hosts[$i] = $this->add_value_type(
                     PluginMonitoringCommand::$command_prefix . self::$shinkenParameters['shinken']['fake_hosts']['check_command'],
                    'check_command', $a_hosts[$i]);
            $a_hosts[$i] = $this->add_value_type(
                    $this->shinkenFilter($dataEntity['entityName']), 'alias', $a_hosts[$i]);
            // $a_hosts[$i]['_HOSTID'] = '0';
            // $a_hosts[$i]['_ITEMSID'] = '0';
            // $a_hosts[$i]['_ITEMTYPE'] = 'Computer';
            $a_hosts[$i] = $this->add_value_type(
                    '127.0.0.1', 'address', $a_hosts[$i]);
            $a_hosts[$i] = $this->add_value_type(
                    self::$shinkenParameters['shinken']['fake_hosts']['name_prefix'] . self::$shinkenParameters['shinken']['fake_hosts']['root_parent'],
                    'parents', $a_hosts[$i]);
            $a_hosts[$i] = $this->add_value_type(
                    self::$shinkenParameters['shinken']['fake_hosts']['hostgroup_name'],
                    'hostgroups', $a_hosts[$i]);
            $a_hosts[$i] = $this->add_value_type(
                    '60', 'check_interval', $a_hosts[$i]);
            $a_hosts[$i] = $this->add_value_type(
                    '1', 'retry_interval', $a_hosts[$i]);
            $a_hosts[$i] = $this->add_value_type(
                    '1', 'max_check_attempts', $a_hosts[$i]);
            $a_hosts[$i] = $this->add_value_type(
                    '24x7', 'check_period', $a_hosts[$i]);
            // Check period is defined by the current entity !
            $a_hosts[$i] = $this->add_value_type(
                    self::$shinkenParameters['shinken']['fake_hosts']['check_period'],
                    'check_period', $a_hosts[$i]);
            // Host entity jetlag ...
            $timeperiodsuffix = '_'.$pmHostconfig->getValueAncestor('jetlag', $dataEntity['entityId']);
            if ($timeperiodsuffix == '_0') {
               $timeperiodsuffix = '';
            }
            $calendar = new Calendar();
            $a_calendars = $calendar->find("`name`='".self::$shinkenParameters['shinken']['fake_hosts']['check_period']."'");
            foreach ($a_calendars as $calendar) {
               if ($this->_addTimeperiod(0, $calendar['id'])) {
                  $a_hosts[$i] = $this->add_value_type(
                          self::shinkenFilter($calendar['name'].$timeperiodsuffix),
                          'check_period', $a_hosts[$i]);
               }
            }

            if (self::$shinkenParameters['shinken']['fake_contacts']['build']) {
               $a_hosts[$i] = $this->add_value_type(
                       self::$shinkenParameters['shinken']['fake_contacts']['contact_name'],
                       'contacts', $a_hosts[$i]);
            } else {
               $a_hosts[$i] = $this->add_value_type(
                       '', 'contacts', $a_hosts[$i]);
            }
            if (! empty(self::$shinkenParameters['shinken']['fake_hosts']['use'])) {
               $a_hosts[$i] = $this->add_value_type(
                       self::$shinkenParameters['shinken']['fake_hosts']['use'],
                       'use', $a_hosts[$i]);
            }
            $extrapram = array(
               'process_perf_data',
               'notification_period',
               'notification_options',
               'notification_interval',
               'notes',
               'notes_url',
               'action_url',
               'icon_image',
               'icon_image_alt',
               'vrml_image',
               'statusmap_image'
            );
            foreach ($extrapram as $parm) {
               if (isset($default_host[$parm])) {
                  $a_hosts[$i] = $this->add_value_type(
                          $default_host[$parm], $parm, $a_hosts[$i]);
               }
            }
            $a_hosts[$i] = $this->properties_list_to_string($a_hosts[$i]);
            $i++;
         }
         PluginMonitoringToolbox::logIfExtradebug(
            'pm-shinken',
            "End generateHostgroupsCfg\n"
         );
      }

      // Check if parents all exist in hosts config
      foreach ($a_parents_found as $host => $num) {
         if (!isset($a_hosts_found[$host])) {
            // Delete parents not added in hosts config
            foreach ($a_hosts as $id=>$data) {
               if (isset($data['parents'])
                       && $data['parents'] == $host) {
                  $a_hosts[$id] = $this->add_value_type(
                          '', 'parents', $a_hosts[$id]);
               }
            }
         }
      }


      PluginMonitoringToolbox::logIfExtradebug(
         'pm-shinken',
         "End generateHostsCfg\n"
      );

      if ($file == "1") {
         $config = "# Generated by plugin monitoring for GLPI\n# on ".date("Y-m-d H:i:s")."\n\n";

         foreach ($a_hosts as $data) {
            $config .= $this->writeFile("host", $data);
         }
         return array('hosts.cfg', $config);

      } else {
         return $a_hosts;
      }
   }



   function generateServicesCfg($file=0, $tag='') {
      global $DB;

      PluginMonitoringToolbox::logIfExtradebug(
         'pm-shinken',
         "Starting generateServicesCfg services ($tag) ...\n"
      );
      $pMonitoringCommand      = new PluginMonitoringCommand();
      $pmEventhandler          = new PluginMonitoringEventhandler();
      $pMonitoringCheck        = new PluginMonitoringCheck();
      $pmComponent             = new PluginMonitoringComponent();
      $pmEntity                = new PluginMonitoringEntity();
      $pmContact_Item          = new PluginMonitoringContact_Item();
      $networkPort             = new NetworkPort();
      $pmService               = new PluginMonitoringService();
      $pmComponentscatalog     = new PluginMonitoringComponentscatalog();
      $pmComponentscatalog_Host= new PluginMonitoringComponentscatalog_Host();
      $pmHostconfig            = new PluginMonitoringHostconfig();
      $calendar                = new Calendar();
      $user                    = new User();
      $profile_User            = new Profile_User();
      $pmConfig                = new PluginMonitoringConfig();
      $computerType            = new ComputerType();

      $a_services = array();
      $i=0;

      // Get computer type contener / VM
      $conteners = $computerType->find("`name`='BSDJail'");

      $pmConfig->getFromDB(1);

      // TODO: only contacts in allowed entities ...
      // Prepare individual contacts
      $a_contacts_entities = array();
      $a_list_contact = $pmContact_Item->find("`itemtype`='PluginMonitoringComponentscatalog'
         AND `users_id`>0");
      foreach ($a_list_contact as $data) {
         $contactentities = getSonsOf('glpi_entities', $data['entities_id']);
         if (isset($a_contacts_entities[$data['items_id']][$data['users_id']])) {
            $contactentities = array_merge($contactentities, $a_contacts_entities[$data['items_id']][$data['users_id']]);
         }
         $a_contacts_entities[$data['items_id']][$data['users_id']] = $contactentities;
      }
      // Prepare groups contacts
      $group = new Group();
      $a_list_contact = $pmContact_Item->find("`itemtype`='PluginMonitoringComponentscatalog'
         AND `groups_id`>0");
      foreach ($a_list_contact as $data) {
         $group->getFromDB($data['groups_id']);
         if ($group->fields['is_recursive'] == 1) {
            $contactentities = getSonsOf('glpi_entities', $group->fields['entities_id']);
         } else {
            $contactentities = array($group->fields['entities_id'] => $group->fields['entities_id']);
         }
         $queryg = "SELECT * FROM `glpi_groups_users`
            WHERE `groups_id`='".$data['groups_id']."'";
         $resultg = $DB->query($queryg);
         while ($datag=$DB->fetch_array($resultg)) {
            if (isset($a_contacts_entities[$data['items_id']][$datag['users_id']])) {
               $contactentities = array_merge($contactentities, $a_contacts_entities[$data['items_id']][$datag['users_id']]);
            }
            $a_contacts_entities[$data['items_id']][$datag['users_id']] = $contactentities;
         }
      }



      $a_entities_allowed = $pmEntity->getEntitiesByTag($tag);
      $a_entities_list = array();
      foreach ($a_entities_allowed as $entity) {
         // @ddurieux: should array_merge ($a_entities_list and getSonsOf("glpi_entities", $entity)) ?
         $a_entities_list = getSonsOf("glpi_entities", $entity);
      }
      $where = '';
      if (! isset($a_entities_allowed['-1'])) {
         $where = getEntitiesRestrictRequest("WHERE", "glpi_plugin_monitoring_services", '', $a_entities_list);
      }

      $a_components = $pmComponent->find();
      $a_componentscatalogs = $pmComponentscatalog->find();
      $componentscatalog_hosts = $pmComponentscatalog_Host->find();
      $timeperiodsuffixes = array();
      foreach ($a_entities_list as $entities_id) {
         $timeperiodsuffixes[$entities_id] = '_'.$pmHostconfig->getValueAncestor('jetlag', $entities_id);
      }
      $a_commands = $pMonitoringCommand->find();
      $a_checks = $pMonitoringCheck->find();
      $a_calendars = $calendar->find();
      $a_users = array();

      // --------------------------------------------------
      // "Normal" services ....
      $query = "SELECT * FROM `glpi_plugin_monitoring_services` $where";
      $result = $DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         PluginMonitoringToolbox::logIfExtradebug(
            'pm-shinken',
            " - fetching service: {$data['id']}\n"
         );

         $notadd = 0;
         $notadddescription = '';
         $a_component= array();
         if (isset($a_components[$data['plugin_monitoring_components_id']])) {
            $a_component = $a_components[$data['plugin_monitoring_components_id']];
         }
         if (empty($a_component)) {
            PluginMonitoringToolbox::logIfExtradebug(
               'pm-shinken',
               " *** fetching service: {$data['id']} - no associated component !\n"
            );
            continue;
         }
         $a_hostname        = array();
         $a_hostname_single = array();
         $a_hostname_type   = array();
         $a_hostname_id     = array();
         $hostname = '';
         $plugin_monitoring_componentscatalogs_id = 0;
         $computerTypes_id = 0;
         $entities_id = 0;
         if (isset($componentscatalog_hosts[$data['plugin_monitoring_componentscatalogs_hosts_id']])) {
            $datah = $componentscatalog_hosts[$data['plugin_monitoring_componentscatalogs_hosts_id']];
            $itemtype = $datah['itemtype'];
            $item = new $itemtype();
            if (! $item->getFromDB($datah['items_id'])) {
               PluginMonitoringToolbox::logIfExtradebug(
                  'pm-shinken',
                  " *** fetching service: {$data['id']} - no itemtype/items_id found !\n"
               );
               continue;
            }

            // Fix if hostname is not defined ...
            if (empty($item->fields['name'])) {
               PluginMonitoringToolbox::logIfExtradebug(
                  'pm-shinken',
                  " *** fetching service: {$data['id']} - no item name found !\n"
               );
               continue;
            }

            if (!isset($a_services[$i])) {
               $a_services[$i] = array();
            }

            PluginMonitoringToolbox::logIfExtradebug(
               'pm-shinken',
               " - fetching service: {$data['id']} ({$a_component['name']} - {$a_component['id']}) - {$datah['itemtype']} - {$datah['items_id']} -> {$item->fields['name']}\n"
            );
            $h = self::shinkenFilter($item->fields['name']);
            $a_hostname_single[] = $h;
            if ($pmConfig->fields['append_id_hostname'] == 1) {
               $h .= "-".$datah['items_id'];
            }
            $a_hostname[] = $h;
            $a_hostname_type[] = $datah['itemtype'];
            $a_hostname_id[] = $datah['items_id'];
            $hostname = $item->fields['name'];
            $entities_id = $item->fields['entities_id'];
            $plugin_monitoring_componentscatalogs_id = $datah['plugin_monitoring_componentscalalog_id'];
            if ($itemtype == 'Computer') {
               $computerTypes_id = $item->fields['computertypes_id'];
            }
         }
         if (count($a_hostname) > 0) {
            if (isset($_SESSION['plugin_monitoring']['servicetemplates'][$a_component['id']])) {
               $a_services[$i] = $this->add_value_type(
                       $_SESSION['plugin_monitoring']['servicetemplates'][$a_component['id']],
                       'use', $a_services[$i]);
            }
            $a_services[$i] = $this->add_value_type(
                    implode(",", array_unique($a_hostname)),
                    'host_name', $a_services[$i]);
            $a_services[$i] = $this->add_value_type(
                    implode(",", array_unique($a_hostname_id)),
                    '_HOSTITEMSID', $a_services[$i]);
            $a_services[$i] = $this->add_value_type(
                    implode(",", array_unique($a_hostname_type)),
                    '_HOSTITEMTYPE', $a_services[$i]);

            // Define display_name / service_description
            if (!empty($a_component['description'])) {
               $a_services[$i] = $this->add_value_type(
                       $a_component['description'],
                       'service_description', $a_services[$i]);
            } else {
               $a_services[$i] = $this->add_value_type(
                       self::shinkenFilter($a_component['name']),
                       'service_description', $a_services[$i]);
            }
            // In case have multiple networkt port, may have description different, else be dropped by shinken
            if ($data['networkports_id'] > 0) {
               $networkPort->getFromDB($data['networkports_id']);
               $hn = array();
               $hn = $this->add_value_type(self::shinkenFilter($networkPort->fields['name'])
                       , 'service_description', $hn);
               $a_services[$i]['service_description'] .= "-".$hn['service_description'];
            }
            $a_services[$i]['display_name'] = $this->shinkenFilter($a_component['name']);
            PluginMonitoringToolbox::logIfExtradebug(
               'pm-shinken',
               " - add service ".$a_services[$i]['service_description']." on ".$a_services[$i]['host_name']."\n"
            );

            if (isset(self::$shinkenParameters['glpi']['entityId'])) {
               $a_services[$i] = $this->add_value_type(
                       $item->fields['entities_id'],
                       self::$shinkenParameters['glpi']['entityId'],
                       $a_services[$i]);
            }
            if (isset(self::$shinkenParameters['glpi']['itemType'])) {
               $a_services[$i] = $this->add_value_type(
                       'Service',
                       self::$shinkenParameters['glpi']['itemType'],
                       $a_services[$i]);
            }
            if (isset(self::$shinkenParameters['glpi']['itemId'])) {
               $a_services[$i] = $this->add_value_type(
                       $data['id'],
                       self::$shinkenParameters['glpi']['itemId'],
                       $a_services[$i]);
            }

            $a_command = $a_commands[$a_component['plugin_monitoring_commands_id']];
            // Manage arguments
            $array = array();
            preg_match_all("/\\$(ARG\d+)\\$/", $a_command['command_line'], $array);
            sort($array[0]);
            $a_arguments = importArrayFromDB($a_component['arguments']);
            $a_argumentscustom = importArrayFromDB($data['arguments']);
            foreach ($a_argumentscustom as $key=>$value) {
               $a_arguments[$key] = $value;
            }
            foreach ($a_arguments as $key=>$value) {
               $a_arguments[$key] = str_replace('!', '\!', html_entity_decode($value));
            }
            $args = '';
            foreach ($array[0] as $arg) {
               if ($arg != '$PLUGINSDIR$'
                       AND $arg != '$NAGIOSPLUGINSDIR$'
                       AND $arg != '$HOSTADDRESS$'
                       AND $arg != '$MYSQLUSER$'
                       AND $arg != '$MYSQLPASSWORD$') {
                  $arg = str_replace('$', '', $arg);
                  if (!isset($a_arguments[$arg])) {
                     $args .= '!';
                  } else {
                     if (strstr($a_arguments[$arg], "[[HOSTNAME]]")) {
                        $a_arguments[$arg] = str_replace("[[HOSTNAME]]", $hostname, $a_arguments[$arg]);
                     } elseif (strstr($a_arguments[$arg], "[[NETWORKPORTDESCR]]")){
                        if (class_exists("PluginFusioninventoryNetworkPort")) {
                           $pfNetworkPort = new PluginFusioninventoryNetworkPort();
                           $pfNetworkPort->loadNetworkport($data['networkports_id']);
                           $descr = $pfNetworkPort->getValue("ifdescr");
                           $a_arguments[$arg] = str_replace("[[NETWORKPORTDESCR]]", $descr, $a_arguments[$arg]);
                        }
                     } elseif (strstr($a_arguments[$arg], "[[NETWORKPORTNUM]]")){
                        $networkPort = new NetworkPort();
                        if (isset($data['networkports_id'])
                                && $data['networkports_id'] > 0) {
                           $networkPort->getFromDB($data['networkports_id']);
                        } else if ($a_services[$i]['_HOSTITEMTYPE'] == 'Computer') {
                           $networkPort = PluginMonitoringHostaddress::getNetworkport(
                                   $a_services[$i]['_HOSTITEMSID'],
                                   $a_services[$i]['_HOSTITEMTYPE']);
                        }
                        if ($networkPort->getID() > 0) {
                           $logicalnum = $networkPort->fields['logical_number'];
                           $a_arguments[$arg] = str_replace("[[NETWORKPORTNUM]]", $logicalnum, $a_arguments[$arg]);
                        }
                     } elseif (strstr($a_arguments[$arg], "[[NETWORKPORTNAME]]")){
                        $networkPort = new NetworkPort();
                        if (isset($data['networkports_id'])
                                && $data['networkports_id'] > 0) {
                           $networkPort = new NetworkPort();
                           $networkPort->getFromDB($data['networkports_id']);
                        } else if ($a_services[$i]['_HOSTITEMTYPE'] == 'Computer') {
                           $networkPort = PluginMonitoringHostaddress::getNetworkport(
                                   $a_services[$i]['_HOSTITEMSID'],
                                   $a_services[$i]['_HOSTITEMTYPE']);
                        }
                        if ($networkPort->getID() > 0) {
                          $portname = $networkPort->fields['name'];
                          $a_arguments[$arg] = str_replace("[[NETWORKPORTNAME]]", $portname, $a_arguments[$arg]);
                        }
                     } else if (strstr($a_arguments[$arg], '[[IP]]')) {
                        $split = explode('-', current($a_hostname));
                        $ip = PluginMonitoringHostaddress::getIp(
                                $a_hostname_id[0], $a_hostname_type[0], '');
                        $a_arguments[$arg] = str_replace("[[IP]]", $ip, $a_arguments[$arg]);
                     } else if (strstr($a_arguments[$arg], "[")) {
                        $a_arguments[$arg] = PluginMonitoringService::convertArgument($data['id'], $a_arguments[$arg]);
                     }
                     if ($a_arguments == '') {
                        $notadd = 1;
                        if ($notadddescription != '') {
                           $notadddescription .= ", ";
                        }
                        $notadddescription .= "Argument ".$a_arguments[$arg]." do not have value";
                     }
                     $args .= '!'.$a_arguments[$arg];
                     if ($a_arguments[$arg] == ''
                             AND $a_component['alias_command'] != '') {
                        $args .= $a_component['alias_command'];
                     }
                  }
               }
            }
            // End manage arguments
            if ($a_component['remotesystem'] == 'nrpe') {
               if ($a_component['alias_command'] != '') {
                  $alias_command = $a_component['alias_command'];
                  if (strstr($alias_command, '[[IP]]')) {
                     $split = explode('-', current($a_hostname));
                     $ip = PluginMonitoringHostaddress::getIp(
                             $a_hostname_id[0], $a_hostname_type[0], '');
                     $alias_command = str_replace("[[IP]]", $ip, $alias_command);
                  }
                  if (current($a_hostname_type) == 'Computer') {
                     if ($pmConfig->fields['nrpe_prefix_contener'] == 1) {
                        if (isset($conteners[$computerTypes_id])) {
                           // get Host of contener/VM
                           $where = "LOWER(`uuid`)".  ComputerVirtualMachine::getUUIDRestrictRequest($item->fields['uuid']);
                           $hosts = getAllDatasFromTable('glpi_computervirtualmachines', $where);
                           if (!empty($hosts)) {
                              $host = current($hosts);
//                                 $ip = PluginMonitoringHostaddress::getIp($host['computers_id'], 'Computer', '');
                              $alias_command = current($a_hostname_single)."_".$alias_command;
                           }
                        }
                     }
                  }
                  $a_services[$i] = $this->add_value_type(
                          PluginMonitoringCommand::$command_prefix . "check_nrpe!".$alias_command,
                          'check_command', $a_services[$i]);
               } else {
                  $a_services[$i] = $this->add_value_type(
                          PluginMonitoringCommand::$command_prefix . "check_nrpe!".$a_command['command_name'],
                          'check_command', $a_services[$i]);
               }
            } else {
               $a_services[$i] = $this->add_value_type(
                       PluginMonitoringCommand::$command_prefix . $a_command['command_name'].$args,
                       'check_command', $a_services[$i]);
            }

            // * Manage event handler
            if ($a_component['plugin_monitoring_eventhandlers_id'] > 0) {
               if ($pmEventhandler->getFromDB($a_component['plugin_monitoring_eventhandlers_id'])) {
                  $a_services[$i] = $this->add_value_type(
                          PluginMonitoringCommand::$command_prefix . $pmEventhandler->fields['command_name'],
                          'event_handler', $a_services[$i]);
                  $a_services[$i] = $this->add_value_type('1',
                          'event_handler_enabled', $a_services[$i]);
               } else {
                  $a_services[$i] = $this->add_value_type('0',
                          'event_handler_enabled', $a_services[$i]);
               }
            } else {
               $a_services[$i] = $this->add_value_type('0',
                       'event_handler_enabled', $a_services[$i]);
            }

            // * Contacts
            $a_services[$i]['contacts'] = array();
            $a_list_contact = $pmContact_Item->find("`itemtype`='PluginMonitoringComponentscatalog'
               AND `items_id`='".$plugin_monitoring_componentscatalogs_id."'");
            foreach ($a_list_contact as $data_contact) {
               if ($data_contact['users_id'] > 0) {
                  if (isset($a_contacts_entities[$plugin_monitoring_componentscatalogs_id][$data_contact['users_id']])) {
                     if (in_array($data['entities_id'], $a_contacts_entities[$plugin_monitoring_componentscatalogs_id][$data_contact['users_id']])) {
                        if (!isset($a_users[$data_contact['users_id']])) {
                           $user->getFromDB($data_contact['users_id']);
                           $a_users[$data_contact['users_id']] = $user->fields['name'];
                        }
                        $a_services[$i] = $this->add_value_type(
                                $a_users[$data_contact['users_id']],
                                'contacts', $a_services[$i]);
                     }
                  }
               } else if ($data_contact['groups_id'] > 0) {
                  $queryg = "SELECT * FROM `glpi_groups_users`
                     WHERE `groups_id`='".$data_contact['groups_id']."'";
                  $resultg = $DB->query($queryg);
                  while ($datag=$DB->fetch_array($resultg)) {
                     if (in_array($data['entities_id'], $a_contacts_entities[$plugin_monitoring_componentscatalogs_id][$datag['users_id']])) {
                        if (!isset($a_users[$datag['users_id']])) {
                           $user->getFromDB($datag['users_id']);
                           $a_users[$datag['users_id']] = $user->fields['name'];
                        }
                        $a_services[$i] = $this->add_value_type(
                                $a_users[$datag['users_id']],
                                'contacts', $a_services[$i]);
                     }
                  }
               }
            }

            if (count($a_services[$i]['contacts']) == 0) {
               if (self::$shinkenParameters['shinken']['fake_contacts']['build']) {
                  $a_services[$i] = $this->add_value_type(
                          self::$shinkenParameters['shinken']['fake_contacts']['contact_name'],
                          'contacts', $a_services[$i]);
               } else {
                  $a_services[$i] = $this->add_value_type('', 'contacts', $a_services[$i]);
               }
            }

            $timeperiodsuffix = $timeperiodsuffixes[$entities_id];
            if ($timeperiodsuffix == '_0') {
               $timeperiodsuffix = '';
            }
            // ** If service template has not been defined :
            if (! isset($_SESSION['plugin_monitoring']['servicetemplates'][$a_component['id']])) {
               $a_check = $a_checks[$a_component['plugin_monitoring_checks_id']];
               $a_services[$i] = $this->add_value_type(
                       $a_check['check_interval'], 'check_interval',
                       $a_services[$i]);
               $a_services[$i] = $this->add_value_type(
                       $a_check['retry_interval'], 'retry_interval',
                       $a_services[$i]);
               $a_services[$i] = $this->add_value_type(
                       $a_check['max_check_attempts'], 'max_check_attempts',
                       $a_services[$i]);
               if (isset($a_calendars[$a_component['calendars_id']]) && $this->_addTimeperiod($entities_id, $a_component['calendars_id'])) {
                  $a_services[$i] = $this->add_value_type(
                          self::shinkenFilter($a_calendars[$a_component['calendars_id']]['name'].$timeperiodsuffix),
                          'check_period', $a_services[$i]);
               } else {
                  $a_services[$i] = $this->add_value_type(
                          self::$shinkenParameters['shinken']['services']['check_period'],
                          'check_period', $a_services[$i]);
               }
               $elements = array(
                  'notification_interval'  => 30,
                  'notification_period'    => "24x7",
                  'notification_options'   => 'w,u,c,r,f,s',
                  'process_perf_data'      => 1,
                  'active_checks_enabled'  => 1,
                  'passive_checks_enabled' => 1,
                  'parallelize_check'      => 1,
                  'obsess_over_service'    => 0,
                  'check_freshness'        => 1,
                  'freshness_threshold'    => 3600,
                  'notifications_enabled'  => 1
               );
               foreach ($elements as $key=>$val) {
                  $a_services[$i] = $this->add_value_type(
                          $val, $key, $a_services[$i]);
               }

               if (! empty(self::$shinkenParameters['shinken']['services']['business_impact'])) {
                  $a_services[$i] = $this->add_value_type(
                          self::$shinkenParameters['shinken']['services']['business_impact'],
                          'business_impact', $a_services[$i]);
               } else {
                  $a_services[$i] = $this->add_value_type(
                          '0', 'business_impact', $a_services[$i]);
               }

               if (isset($a_services[$i]['event_handler'])) {
                  $a_services[$i] = $this->add_value_type(
                          '1', 'event_handler_enabled', $a_services[$i]);
               } else {
                  $a_services[$i] = $this->add_value_type(
                          '0', 'event_handler_enabled', $a_services[$i]);
               }
               // $a_services[$i]['flap_detection_enabled'] = '1';
               // $a_services[$i]['failure_prediction_enabled'] = '1';

               // Persist service status
               // $a_services[$i]['retain_status_information'] = '1';
               // $a_services[$i]['retain_nonstatus_information'] = '1';
            } else {
               $default_service = self::$shinkenParameters['shinken']['services'];
               // Default parameters
               $extrapram = array(
                  'process_perf_data',
                  'notes',
                  'notes_url',
                  'action_url',
                  'icon_image',
                  'icon_image_alt'
               );
               foreach ($extrapram as $parm) {
                  if (isset($default_service[$parm])) {
                     $a_services[$i] = $this->add_value_type(
                             $default_service[$parm], $parm, $a_services[$i]);
                  }
               }

               // Notification options / interval
               $a_componentscatalog = $a_componentscatalogs[$plugin_monitoring_componentscatalogs_id];

               PluginMonitoringToolbox::logIfExtradebug(
                  'pm-shinken',
                  "generateServicesCfg - CC, service: {$a_services[$i]['service_description']}/{$a_services[$i]['host_name']} in {$a_componentscatalog['name']}\n"
               );
               $pmSNTemplate = new PluginMonitoringServicenotificationtemplate();
               if ((! isset ($a_componentscatalog['servicesnotification_id']))
                  ||  (! $pmSNTemplate->getFromDB($a_componentscatalog['servicesnotification_id']))) {
                  // No notifications defined for service, use defaults ...
                  $extrapram = array(
                     'notifications_enabled',
                     'notification_period',
                     'notification_options',
                     'notification_interval'
                  );
                  foreach ($extrapram as $parm) {
                     if (isset($default_service[$parm])) {
                        $a_services[$i] = $this->add_value_type(
                                $default_service[$parm], $parm, $a_services[$i]);
                     }
                  }

                  PluginMonitoringToolbox::logIfExtradebug(
                     'pm-shinken',
                     "generateServicesCfg - CC, service: {$a_services[$i]['service_description']}/{$a_services[$i]['host_name']} in {$a_componentscatalog['name']}, no notifications.\n"
                  );
               } else {
                  $a_SN = $pmSNTemplate->fields;

                  PluginMonitoringToolbox::logIfExtradebug(
                     'pm-shinken',
                     "generateServicesCfg - CC, service: {$a_services[$i]['service_description']}/{$a_services[$i]['host_name']} in {$a_componentscatalog['name']}, notification template: {$a_SN['name']}.\n"
                  );

                  if ($a_SN['service_notifications_enabled'] == 0) {
                     // No notifications for service
                     $a_services[$i] = $this->add_value_type(
                             '0', 'notifications_enabled', $a_services[$i]);
                     $a_services[$i] = $this->add_value_type(
                             '24x7', 'notification_period', $a_services[$i]);
                     $a_services[$i] = $this->add_value_type(
                             '', 'notification_options', $a_services[$i]);
                     $a_services[$i] = $this->add_value_type(
                             '0', 'notification_interval', $a_services[$i]);

                     PluginMonitoringToolbox::logIfExtradebug(
                        'pm-shinken',
                        "generateServicesCfg - CC, service: {$a_services[$i]['service_description']}/{$a_services[$i]['host_name']} in {$a_componentscatalog['name']}, no notifications.\n"
                     );
                  } else {
                     if (! isset($a_SN['service_notification_period']) ||
                           ! $a_SN['service_notifications_enabled']) {
                        // No notifications for service
                        $a_services[$i] = $this->add_value_type(
                                '0', 'notifications_enabled', $a_services[$i]);
                        $a_services[$i] = $this->add_value_type(
                                '24x7', 'notification_period', $a_services[$i]);
                        $a_services[$i] = $this->add_value_type(
                                '', 'notification_options', $a_services[$i]);
                        $a_services[$i] = $this->add_value_type(
                                '0', 'notification_interval', $a_services[$i]);

                        PluginMonitoringToolbox::logIfExtradebug(
                           'pm-shinken',
                           "generateServicesCfg - CC, service: {$a_services[$i]['service_description']}/{$a_services[$i]['host_name']} in {$a_componentscatalog['name']}, no notifications.\n"
                        );
                     } else {
                        // Notifications enabled for service
                        $a_services[$i] = $this->add_value_type(
                                '1', 'notifications_enabled', $a_services[$i]);

                        // Notification period
                        if (isset($a_calendars[$a_SN['service_notification_period']]) && $this->_addTimeperiod($entities_id, $a_SN['service_notification_period'])) {
                           $a_services[$i] = $this->add_value_type(
                                   self::shinkenFilter($a_calendars[$a_SN['service_notification_period']]['name'].$timeperiodsuffix),
                                   'notification_period', $a_services[$i]);
                        } else {
                           if (! empty(self::$shinkenParameters['shinken']['services']['notification_period']))
                              $a_services[$i] = $this->add_value_type(
                                      self::$shinkenParameters['shinken']['services']['notification_period'],
                                      'notification_period', $a_services[$i]);
                        }

                        // Notification options
                        if ($a_SN['service_notification_options_w'] == 1) {
                           $a_services[$i] = $this->add_value_type(
                                   'w', 'notification_options', $a_services[$i]);
                        }
                        if ($a_SN['service_notification_options_u'] == 1) {
                           $a_services[$i] = $this->add_value_type(
                                   'u', 'notification_options', $a_services[$i]);
                        }
                        if ($a_SN['service_notification_options_c'] == 1) {
                           $a_services[$i] = $this->add_value_type(
                                   'c', 'notification_options', $a_services[$i]);
                        }
                        if ($a_SN['service_notification_options_r'] == 1) {
                           $a_services[$i] = $this->add_value_type(
                                   'r', 'notification_options', $a_services[$i]);
                        }
                        if ($a_SN['service_notification_options_f'] == 1) {
                           $a_services[$i] = $this->add_value_type(
                                   'f', 'notification_options', $a_services[$i]);
                        }
                        if ($a_SN['service_notification_options_s'] == 1) {
                           $a_services[$i] = $this->add_value_type(
                                   's', 'notification_options', $a_services[$i]);
                        }
                        if ($a_SN['service_notification_options_n'] == 1) {
                           $a_services[$i] = $this->add_value_type(
                                   'n', 'notification_options', $a_services[$i]);
                        }
                        if (count($a_services[$i]['notification_options']) == 0) {
                           if (isset($a_services[$i]['notification_options'])) {
                              unset($a_services[$i]['notification_options']);
                           }
                           $a_services[$i] = $this->add_value_type(
                                   'n', 'notification_options', $a_services[$i]);
                        }

                        // Notification interval
                        if (isset ($a_componentscatalog['notification_interval']) ) {
                           $a_services[$i] = $this->add_value_type(
                                   $a_componentscatalog['notification_interval'],
                                   'notification_interval', $a_services[$i]);
                        } else {
                           if (! empty(self::$shinkenParameters['shinken']['hosts']['notification_interval'])) {
                              $a_services[$i] = $this->add_value_type(
                                      self::$shinkenParameters['shinken']['hosts']['notification_interval'],
                                      'notification_interval', $a_services[$i]);
                           }
                        }
                     }
                  }
               }

               // Calendar ...
               $a_services[$i]['check_period'] = '24x7';
               $timeperiodsuffix = $timeperiodsuffixes[$entities_id];
               if ($timeperiodsuffix == '_0') {
                  $timeperiodsuffix = '';
               }
               // Use the calendar defined for the service (host) entity ...
               $calendar = new Calendar();
               $cid = Entity::getUsedConfig('calendars_id', $item->fields['entities_id'], '', 0);
               if ($calendar->getFromDB($cid) && $this->_addTimeperiod($item->fields['entities_id'], $cid)) {
                  $a_services[$i] = $this->add_value_type(
                          self::shinkenFilter($calendar->fields['name'].$timeperiodsuffix),
                          'check_period', $a_services[$i]);
               }
               // @mohierf@ : test, service get its host check period ... when default timeperiod is empty !
               if (self::$shinkenParameters['shinken']['services']['check_period'] != '') {
                  // @mohierf@ : service get its own check period ...
                  if (isset ($a_componentscatalog['calendars_id']) ) {
                    if (isset($a_calendars[$a_componentscatalog['calendars_id']])
                            && $this->_addTimeperiod($entities_id, $a_componentscatalog['calendars_id'])) {
                        $a_services[$i] = $this->add_value_type(
                                self::shinkenFilter($a_calendars[$a_componentscatalog['calendars_id']]['name'].$timeperiodsuffix),
                                'check_period', $a_services[$i]);
                     } else {
                        $a_services[$i] = $this->add_value_type(
                                self::$shinkenParameters['shinken']['services']['check_period'],
                                'check_period', $a_services[$i]);
                     }
                  } else {
                     if (isset($a_calendars[$a_component['calendars_id']])
                             && $this->_addTimeperiod($entities_id, $a_component['calendars_id'])) {
                        $a_services[$i] = $this->add_value_type(
                                self::shinkenFilter($a_calendars[$a_component['calendars_id']]['name'].$timeperiodsuffix),
                                'check_period', $a_services[$i]);
                     } else {
                        $a_services[$i] = $this->add_value_type(
                                self::$shinkenParameters['shinken']['services']['check_period'],
                                'check_period', $a_services[$i]);
                     }
                  }
               }
            }

            // WebUI user interface ...
            if (isset(self::$shinkenParameters['webui']['serviceIcons']['name'])) {
               $a_services[$i] = $this->add_value_type(
                       self::$shinkenParameters['webui']['serviceIcons']['value'],
                       self::$shinkenParameters['webui']['serviceIcons']['name'],
                       $a_services[$i]);
            }

            if ($notadd == '1') {
               unset($a_services[$i]);
               $input = array();
               $input['id'] = $data['id'];
               $input['event'] = $notadddescription;
               $input['state'] = "CRITICAL";
               $input['state_type'] = "HARD";
               $pmService->update($input);
            } else {
               $a_services[$i] = $this->properties_list_to_string($a_services[$i]);
               $i++;
            }
         }
      }

      PluginMonitoringToolbox::logIfExtradebug(
         'pm-shinken',
         "End generateServicesCfg services\n"
      );

      PluginMonitoringToolbox::logIfExtradebug(
         'pm-shinken',
         "Starting generateServicesCfg business rules ...\n"
      );

      // --------------------------------------------------
      // Business rules services ...
      $pmServicescatalog = new PluginMonitoringServicescatalog();
      $pmBusinessrulegroup = new PluginMonitoringBusinessrulegroup();
      $pmBusinessrule = new PluginMonitoringBusinessrule();
      $pmBusinessrule_component = new PluginMonitoringBusinessrule_component();
      // Prepare individual contacts
      $a_contacts_entities = array();
      $a_list_contact = $pmContact_Item->find("`itemtype`='PluginMonitoringServicescatalog'
         AND `users_id`>0");
      foreach ($a_list_contact as $data) {
         $contactentities = getSonsOf('glpi_entities', $data['entities_id']);
         if (isset($a_contacts_entities[$data['items_id']][$data['users_id']])) {
            $contactentities = array_merge($contactentities, $a_contacts_entities[$data['items_id']][$data['users_id']]);
         }
         $a_contacts_entities[$data['items_id']][$data['users_id']] = $contactentities;
      }
      // Prepare groups contacts
      $group = new Group();
      $a_list_contact = $pmContact_Item->find("`itemtype`='PluginMonitoringServicescatalog'
         AND `groups_id`>0");
      foreach ($a_list_contact as $data) {
         $group->getFromDB($data['groups_id']);
         if ($group->fields['is_recursive'] == 1) {
            $contactentities = getSonsOf('glpi_entities', $group->fields['entities_id']);
         } else {
            $contactentities = array($group->fields['entities_id'] => $group->fields['entities_id']);
         }
         $queryg = "SELECT * FROM `glpi_groups_users`
            WHERE `groups_id`='".$data['groups_id']."'";
         $resultg = $DB->query($queryg);
         while ($datag=$DB->fetch_array($resultg)) {
            if (isset($a_contacts_entities[$data['items_id']][$datag['users_id']])) {
               $contactentities = array_merge($contactentities, $a_contacts_entities[$data['items_id']][$datag['users_id']]);
            }
            $a_contacts_entities[$data['items_id']][$datag['users_id']] = $contactentities;
         }
      }

      // Services catalogs
      $a_listBA = $pmServicescatalog->find("`is_generic`='0'");
      foreach ($a_listBA as $dataBA) {

         if (isset($a_entities_allowed['-1'])
                 OR isset($a_entities_allowed[$dataBA['entities_id']])) {

            $a_grouplist = $pmBusinessrulegroup->find("`plugin_monitoring_servicescatalogs_id`='".$dataBA['id']."'");
            $a_group = array();
            foreach ($a_grouplist as $gdata) {

               $pmBusinessrule_component->replayDynamicServices($gdata['id']);
               $a_listBR = $pmBusinessrule->find(
                       "`plugin_monitoring_businessrulegroups_id`='".$gdata['id']."'");
               foreach ($a_listBR as $dataBR) {
                  if ($pmService->getFromDB($dataBR['plugin_monitoring_services_id'])) {
                     if ($pmService->getHostName() != '') {
                        $hostname = self::shinkenFilter($pmService->getHostName());

                        if ($gdata['operator'] == 'and'
                                OR $gdata['operator'] == 'or'
                                OR strstr($gdata['operator'], ' of:')) {

                           $operator = '|';
                           if ($gdata['operator'] == 'and') {
                              $operator = '&';
                           }
                           if (!isset($a_group[$gdata['id']])) {
                              $a_group[$gdata['id']] = '';
                              if (strstr($gdata['operator'], ' of:')) {
                                 $a_group[$gdata['id']] = $gdata['operator'];
                              }
                              $a_group[$gdata['id']] .= $hostname.",".self::shinkenFilter($pmService->getName(array('shinken'=>true)));
                           } else {
                              $a_group[$gdata['id']] .= $operator.$hostname.",".self::shinkenFilter($pmService->getName(array('shinken'=>true)));
                           }
                        } else {
                           $a_group[$gdata['id']] = $gdata['operator']." ".$hostname.",".self::shinkenFilter($item->getName());
                        }
                     }
                  }
                  PluginMonitoringToolbox::logIfExtradebug(
                     'pm-shinken',
                     "   - SC group : ".$a_group[$gdata['id']]."\n"
                  );
               }
            }
            if (count($a_group) > 0) {
               if (isset($a_checks[$dataBA['plugin_monitoring_checks_id']])) {
                  $a_services[$i] = array();
                  $a_check = $a_checks[$dataBA['plugin_monitoring_checks_id']];
                  $a_services[$i] = $this->add_value_type(
                          $a_check['check_interval'],
                          'check_interval', $a_services[$i]);
                  $a_services[$i] = $this->add_value_type(
                          $a_check['retry_interval'],
                          'retry_interval', $a_services[$i]);
                  $a_services[$i] = $this->add_value_type(
                          $a_check['max_check_attempts'],
                          'max_check_attempts', $a_services[$i]);
                  if (isset($a_calendars[$dataBA['calendars_id']])) {
                     $a_services[$i] = $this->add_value_type(
                             $a_calendars[$dataBA['calendars_id']]['name'],
                             'check_period', $a_services[$i]);
                  }
                  $a_services[$i] = $this->add_value_type(
                          self::$shinkenParameters['shinken']['fake_hosts']['name_prefix'] . self::$shinkenParameters['shinken']['fake_bp_hosts']['hostname'],
                          'host_name', $a_services[$i]);
                  $a_services[$i] = $this->add_value_type(
                          $dataBA['business_priority'],
                          'business_impact', $a_services[$i]);
                  $a_services[$i] = $this->add_value_type(
                          self::shinkenFilter($dataBA['name']),
                          'service_description', $a_services[$i]);
                  $a_services[$i] = $this->add_value_type(
                          $dataBA['id'], '_ENTITIESID', $a_services[$i]);
                  $a_services[$i] = $this->add_value_type(
                          $dataBA['id'], '_ITEMSID', $a_services[$i]);
                  $a_services[$i] = $this->add_value_type(
                          'ServiceCatalog', '_ITEMTYPE', $a_services[$i]);
                  $command = "bp_rule!";

                  foreach ($a_group as $key=>$value) {
                     if (!strstr($value, "&")
                             AND !strstr($value, "|")) {
                        $a_group[$key] = trim($value);
                     } else {
                        $a_group[$key] = "(".trim($value).")";
                     }
                  }
                  $a_services[$i] = $this->add_value_type(
                          $command.implode("&", $a_group),
                          'check_command', $a_services[$i]);
                  if ($dataBA['notification_interval'] != '30') {
                     $a_services[$i] = $this->add_value_type(
                             $dataBA['notification_interval'],
                             'notification_interval', $a_services[$i]);
                  } else {
                     $a_services[$i] = $this->add_value_type(
                             '30', 'notification_interval', $a_services[$i]);
                  }
                  $elements = array(
                     'notification_period'          => "24x7",
                     'notification_options'         => 'w,u,c,r,f,s',
                     'active_checks_enabled'        => 1,
                     'process_perf_data'            => 1,
                     'active_checks_enabled'        => 1,
                     'passive_checks_enabled'       => 1,
                     'parallelize_check'            => 1,
                     'obsess_over_service'          => 0,
                     'check_freshness'              => 1,
                     'freshness_threshold'          => 3600,
                     'notifications_enabled'        => 1,
                     'event_handler_enabled'        => 0,
                     'flap_detection_enabled'       => 1,
                     'failure_prediction_enabled'   => 1,
                     'retain_status_information'    => 1,
                     'retain_nonstatus_information' => 1,
                  );
                  foreach ($elements as $key=>$val) {
                     $a_services[$i] = $this->add_value_type(
                             $val, $key, $a_services[$i]);
                  }

                  // * Contacts
                  $a_services[$i]['contacts'] = array();
                  $a_list_contact = $pmContact_Item->find("`itemtype`='PluginMonitoringServicescatalog'
                     AND `items_id`='".$dataBA['id']."'");
                  foreach ($a_list_contact as $data_contact) {
                     if ($data_contact['users_id'] > 0) {
                        if (isset($a_contacts_entities[$dataBA['id']][$data_contact['users_id']])) {
                           if (in_array($data['entities_id'], $a_contacts_entities[$dataBA['id']][$data_contact['users_id']])) {
                              if (!isset($a_users[$data_contact['users_id']])) {
                                 $user->getFromDB($data_contact['users_id']);
                                 $a_users[$data_contact['users_id']] = $user->fields['name'];
                              }
                              $a_services[$i] = $this->add_value_type(
                                      $a_users[$data_contact['users_id']],
                                      'contacts', $a_services[$i]);
                           }
                        }
                     } else if ($data_contact['groups_id'] > 0) {
                        $queryg = "SELECT * FROM `glpi_groups_users`
                           WHERE `groups_id`='".$data_contact['groups_id']."'";
                        $resultg = $DB->query($queryg);
                        while ($datag=$DB->fetch_array($resultg)) {
                           if (in_array($data['entities_id'], $a_contacts_entities[$dataBA['id']][$datag['users_id']])) {
                              if (!isset($a_users[$datag['users_id']])) {
                                 $user->getFromDB($datag['users_id']);
                                 $a_users[$datag['users_id']] = $user->fields['name'];
                              }
                              $a_services[$i] = $this->add_value_type(
                                      $a_users[$datag['users_id']],
                                      'contacts', $a_services[$i]);
                           }
                        }
                     }
                  }
                  $a_services[$i] = $this->properties_list_to_string($a_services[$i]);
                  $i++;
               }
            }
         }
      }

      PluginMonitoringToolbox::logIfExtradebug(
         'pm-shinken',
         "End generateServicesCfg business rules\n"
      );

      PluginMonitoringToolbox::logIfExtradebug(
         'pm-shinken',
         "Starting generateServicesCfg business rules templates ...\n"
      );

      // Services catalogs templates
      // TODO : correctly test and improve it !
      $a_listBA = $pmServicescatalog->find("`is_generic`='1'");
      foreach ($a_listBA as $dataBA) {
         PluginMonitoringToolbox::logIfExtradebug(
            'pm-shinken',
            "   - SC : ".$dataBA['id']."\n"
         );

         if (isset($a_entities_allowed['-1'])
                 OR isset($a_entities_allowed[$dataBA['entities_id']])) {

            $pmServicescatalog->getFromDB($dataBA['id']);

            $a_entitiesServices = $pmServicescatalog->getGenericServicesEntities();
            foreach ($a_entitiesServices as $idEntity=>$a_entityServices) {
               // New entity ... so new business rule !
               PluginMonitoringToolbox::logIfExtradebug(
                  'pm-shinken',
                  "   - SC templated services for an entity : ".$idEntity."\n"
               );

               $pmDerivatedSC = new PluginMonitoringServicescatalog();
               $a_derivatedSC = $pmDerivatedSC->find("`entities_id`='$idEntity' AND `name` LIKE '".$dataBA['name']."%'");
               foreach ($a_derivatedSC as $a_derivated) {
                  PluginMonitoringToolbox::logIfExtradebug(
                     'pm-shinken',
                     "   - a_derivated : ".$a_derivated['name']."\n"
                  );
                  $a_derivatedSC = $a_derivated;
               }

               $a_group = array();
               foreach ($a_entityServices as $services) {
                  if ($pmService->getFromDB($services['serviceId'])) {
                     // Toolbox::logInFile("pm-shinken", "   - SC templated service entity : ".$services['entityId'].", service :  ".$pmService->getName(true)." on ".$pmService->getHostName()."\n");
                     if ($pmService->getHostName() != '') {
                        $hostname = self::shinkenFilter($pmService->getHostName());

                        $serviceFakeId = $services['entityId'];

                        $pmBusinessrulegroup->getFromDB($services['BRgroupId']);
                        $BRoperator = $pmBusinessrulegroup->getField('operator');
                        if ($BRoperator == 'and'
                                OR $BRoperator == 'or'
                                OR strstr($BRoperator, ' of:')) {

                           $operator = '|';
                           if ($BRoperator == 'and') {
                              $operator = '&';
                           }
                           if (!isset($a_group[$serviceFakeId])) {
                              $a_group[$serviceFakeId] = '';
                              if (strstr($BRoperator, ' of:')) {
                                 $a_group[$serviceFakeId] = $BRoperator;
                              }
                              $a_group[$serviceFakeId] .= $hostname.",".self::shinkenFilter($pmService->getName(array('shinken'=>true)));
                           } else {
                              $a_group[$serviceFakeId] .= $operator.$hostname.",".self::shinkenFilter($pmService->getName(array('shinken'=>true)));
                           }
                        } else {
                           $a_group[$serviceFakeId] = $BRoperator." ".$hostname.",".self::shinkenFilter($pmService->getHostName());
                        }
                        // Toolbox::logInFile("pm-shinken", "   - SCT group : ".$a_group[$serviceFakeId]."\n");
                     }
                  }
               }
               if (count($a_group) > 0) {
                  $a_check = $a_checks[$a_derivatedSC['plugin_monitoring_checks_id']];
                  $a_services[$i] = $this->add_value_type(
                          $a_check['check_interval'],
                          'check_interval', $a_services[$i]);
                  $a_services[$i] = $this->add_value_type(
                          $a_check['retry_interval'],
                          'retry_interval', $a_services[$i]);
                  $a_services[$i] = $this->add_value_type(
                          $a_check['max_check_attempts'],
                          'max_check_attempts', $a_services[$i]);
                  if (isset($a_calendars[$a_derivatedSC['calendars_id']])) {
                     $a_services[$i] = $this->add_value_type(
                             $a_calendars[$a_derivatedSC['calendars_id']]['name'],
                             'check_period', $a_services[$i]);
                  }
                  $a_services[$i] = $this->add_value_type(
                          self::$shinkenParameters['shinken']['fake_hosts']['name_prefix'] . self::$shinkenParameters['shinken']['fake_hosts']['bp_host'],
                          'host_name', $a_services[$i]);
                  $a_services[$i] = $this->add_value_type(
                          $a_derivatedSC['business_priority'],
                          'business_impact', $a_services[$i]);
                  $a_services[$i] = $this->add_value_type(
                          self::shinkenFilter($a_derivatedSC['name']),
                          'service_description', $a_services[$i]);
                  $a_services[$i] = $this->add_value_type(
                          $a_derivatedSC['entities_id'],
                          '_ENTITIESID', $a_services[$i]);
                  $a_services[$i] = $this->add_value_type(
                          $a_derivatedSC['id'], '_ITEMSID', $a_services[$i]);
                  $a_services[$i] = $this->add_value_type(
                          'ServiceCatalog', '_ITEMTYPE', $a_services[$i]);
                  $command = "bp_rule!";

                  foreach ($a_group as $key=>$value) {
                     if (!strstr($value, "&")
                             AND !strstr($value, "|")) {
                        $a_group[$key] = trim($value);
                     } else {
                        $a_group[$key] = "(".trim($value).")";
                     }
                  }
                  $a_services[$i] = $this->add_value_type(
                          $command.implode("&", $a_group),
                          'check_command', $a_services[$i]);
                  if ($a_derivatedSC['notification_interval'] != 30) {
                     $a_services[$i] = $this->add_value_type(
                             $a_derivatedSC['notification_interval'],
                             'notification_interval', $a_services[$i]);
                  } else {
                     $a_services[$i] = $this->add_value_type(
                             '30', 'notification_interval', $a_services[$i]);
                  }
                  $elements = array(
                     'notification_period'          => "24x7",
                     'notification_options'         => 'w,u,c,r,f,s',
                     'active_checks_enabled'        => 1,
                     'process_perf_data'            => 1,
                     'active_checks_enabled'        => 1,
                     'passive_checks_enabled'       => 1,
                     'parallelize_check'            => 1,
                     'obsess_over_service'          => 0,
                     'check_freshness'              => 1,
                     'freshness_threshold'          => 3600,
                     'notifications_enabled'        => 1,
                     'event_handler_enabled'        => 0,
                     'flap_detection_enabled'       => 1,
                     'failure_prediction_enabled'   => 1,
                     'retain_status_information'    => 1,
                     'retain_nonstatus_information' => 1,
                  );
                  foreach ($elements as $key=>$val) {
                     $a_services[$i] = $this->add_value_type(
                             $val, $key, $a_services[$i]);
                  }

                  // * Contacts
                  $a_services[$i]['contacts'] = array();
                  $a_list_contact = $pmContact_Item->find("`itemtype`='PluginMonitoringServicescatalog'
                     AND `items_id`='".$dataBA['id']."'");
                  foreach ($a_list_contact as $data_contact) {
                     if ($data_contact['users_id'] > 0) {
                        if (isset($a_contacts_entities[$dataBA['id']][$data_contact['users_id']])) {
                           if (in_array($data['entities_id'], $a_contacts_entities[$dataBA['id']][$data_contact['users_id']])) {
                              if (!isset($a_users[$data_contact['users_id']])) {
                                 $user->getFromDB($data_contact['users_id']);
                                 $a_users[$data_contact['users_id']] = $user->fields['name'];
                              }
                              $a_services[$i] = $this->add_value_type(
                                      $a_users[$data_contact['users_id']],
                                      'contacts', $a_services[$i]);
                           }
                        }
                     } else if ($data_contact['groups_id'] > 0) {
                        $queryg = "SELECT * FROM `glpi_groups_users`
                           WHERE `groups_id`='".$data_contact['groups_id']."'";
                        $resultg = $DB->query($queryg);
                        while ($datag=$DB->fetch_array($resultg)) {
                           if (in_array($data['entities_id'], $a_contacts_entities[$dataBA['id']][$datag['users_id']])) {
                              if (!isset($a_users[$datag['users_id']])) {
                                 $user->getFromDB($datag['users_id']);
                                 $a_users[$datag['users_id']] = $user->fields['name'];
                              }
                              $a_services[$i] = $this->add_value_type(
                                      $a_users[$datag['users_id']],
                                      'contacts', $a_services[$i]);
                           }
                        }
                     }
                  }
                  $a_services[$i] = $this->properties_list_to_string($a_services[$i]);
                  $i++;
               }
            }
         }
      }

      PluginMonitoringToolbox::logIfExtradebug(
         'pm-shinken',
         "End generateServicesCfg business rules templates\n"
      );

      if ($file == "1") {
         $config = "# Generated by plugin monitoring for GLPI\n# on ".date("Y-m-d H:i:s")."\n\n";

         foreach ($a_services as $data) {
            $config .= $this->writeFile("service", $data);
         }
         return array('services.cfg', $config);

      } else {
         return $a_services;
      }
   }



   function generateTemplatesCfg($file=0, $tag='') {
      global $DB;

      if (!isset($_SESSION['plugin_monitoring'])) {
         $_SESSION['plugin_monitoring'] = array();
      }

      PluginMonitoringToolbox::logIfExtradebug(
         'pm-shinken',
         "Starting generateTemplatesCfg ($tag) ...\n"
      );
      $pMonitoringCheck = new PluginMonitoringCheck();

      $a_servicetemplates = array();
      $i=0;
      $a_templatesdef = array();

      // Build a Shinken service template for each declare component ...
      // Fix service template association bug: #191
      $query = "SELECT * FROM `glpi_plugin_monitoring_components` ORDER BY `id`";
      // Select components with some grouping ...
      // $query = "SELECT * FROM `glpi_plugin_monitoring_components`
         // GROUP BY `plugin_monitoring_checks_id`, `active_checks_enabled`,
            // `passive_checks_enabled`, `freshness_count`, `freshness_type`, `calendars_id`, `business_priority`
         // ORDER BY `id`";
      $result = $DB->query($query);
      if ($DB->numrows($result) != 0) {
      while ($data=$DB->fetch_array($result)) {

         $a_servicetemplates[$i] = array();
         PluginMonitoringToolbox::logIfExtradebug(
            'pm-shinken',
            " - add template ".'template'.$data['id'].'-service'."\n"
         );
         // Fix service template association bug: #191
         // $a_servicetemplates[$i] = $this->add_value_type(
                 // self::shinkenFilter('stag-'.$data['id']),
                 // 'name', $a_servicetemplates[$i]);
         $a_servicetemplates[$i] = $this->add_value_type(
                 self::shinkenFilter($data['name']),
                 'name', $a_servicetemplates[$i]);
         // Alias is not used by Shinken !
         $a_servicetemplates[$i] = $this->add_value_type(
                 $data['description'].' / '.$data['name'],
                 'alias', $a_servicetemplates[$i]);
         if (isset ($data['business_priority'])) {
            $a_servicetemplates[$i] = $this->add_value_type(
                    $data['business_priority'],
                    'business_impact', $a_servicetemplates[$i]);
         } else {
            if (! empty(self::$shinkenParameters['shinken']['services']['business_impact'])) {
               $a_servicetemplates[$i] = $this->add_value_type(
                       self::$shinkenParameters['shinken']['services']['business_impact'],
                       'business_impact', $a_servicetemplates[$i]);
            } else {
               $a_servicetemplates[$i] = $this->add_value_type(
                       '0', 'business_impact', $a_servicetemplates[$i]);
            }
         }
         $pMonitoringCheck->getFromDB($data['plugin_monitoring_checks_id']);
         $a_servicetemplates[$i] = $this->add_value_type(
                 $pMonitoringCheck->fields['check_interval'],
                 'check_interval', $a_servicetemplates[$i]);
         $a_servicetemplates[$i] = $this->add_value_type(
                 $pMonitoringCheck->fields['retry_interval'],
                 'retry_interval', $a_servicetemplates[$i]);
         $a_servicetemplates[$i] = $this->add_value_type(
                 $pMonitoringCheck->fields['max_check_attempts'],
                 'max_check_attempts', $a_servicetemplates[$i]);
         // check_period, defined in each service ...
         // if ($calendar->getFromDB($data['calendars_id'])) {
            // $a_servicetemplates[$i]['check_period'] = $calendar->fields['name'];
         // }
         // notification parameters, defined in each service ...
         // $a_servicetemplates[$i]['notification_interval'] = '30';
         // $a_servicetemplates[$i]['notification_period'] = "24x7";
         // $a_servicetemplates[$i]['notification_options'] = 'w,u,c,r,f,s';
         // $a_servicetemplates[$i]['process_perf_data'] = '1';
         $a_servicetemplates[$i] = $this->add_value_type(
                 $data['active_checks_enabled'],
                 'active_checks_enabled', $a_servicetemplates[$i]);
         $a_servicetemplates[$i] = $this->add_value_type(
                 $data['passive_checks_enabled'],
                 'passive_checks_enabled', $a_servicetemplates[$i]);
         $a_servicetemplates[$i] = $this->add_value_type(
                 '1', 'parallelize_check', $a_servicetemplates[$i]);
         $a_servicetemplates[$i] = $this->add_value_type(
                 '0', 'obsess_over_service', $a_servicetemplates[$i]);
         // Manage freshness
         // $a_servicetemplates[$i]['check_freshness'] = '1';
         // $a_servicetemplates[$i]['freshness_threshold'] = '3600';
         if ($data['freshness_count'] == 0) {
            $a_servicetemplates[$i] = $this->add_value_type(
                    '0', 'check_freshness', $a_servicetemplates[$i]);
            $a_servicetemplates[$i] = $this->add_value_type(
                    '3600', 'freshness_threshold', $a_servicetemplates[$i]);
         } else {
            $multiple = 1;
            if ($data['freshness_type'] == 'seconds') {
               $multiple = 1;
            } else if ($data['freshness_type'] == 'minutes') {
               $multiple = 60;
            } else if ($data['freshness_type'] == 'hours') {
               $multiple = 3600;
            } else if ($data['freshness_type'] == 'days') {
               $multiple = 86400;
            }
            $a_servicetemplates[$i] = $this->add_value_type(
                    '1', 'check_freshness', $a_servicetemplates[$i]);
            $a_servicetemplates[$i] = $this->add_value_type(
                    ($data['freshness_count'] * $multiple),
                    'freshness_threshold', $a_servicetemplates[$i]);
         }
         $a_servicetemplates[$i] = $this->add_value_type(
                 '1', 'notifications_enabled', $a_servicetemplates[$i]);
         $a_servicetemplates[$i] = $this->add_value_type(
                 '0', 'event_handler_enabled', $a_servicetemplates[$i]);
         $a_servicetemplates[$i] = $this->add_value_type(
                 self::$shinkenParameters['shinken']['services']['stalking_options'],
                 'stalking_options', $a_servicetemplates[$i]);

         if (isset(self::$shinkenParameters['shinken']['services']['flap_detection_enabled'])) {
            $a_servicetemplates[$i] = $this->add_value_type(
                    self::$shinkenParameters['shinken']['services']['flap_detection_enabled'],
                    'flap_detection_enabled', $a_servicetemplates[$i]);
            $a_servicetemplates[$i] = $this->add_value_type(
                    self::$shinkenParameters['shinken']['services']['flap_detection_options'],
                    'flap_detection_options', $a_servicetemplates[$i]);
            $a_servicetemplates[$i] = $this->add_value_type(
                    self::$shinkenParameters['shinken']['services']['low_flap_threshold'],
                    'low_flap_threshold', $a_servicetemplates[$i]);
            $a_servicetemplates[$i] = $this->add_value_type(
                    self::$shinkenParameters['shinken']['services']['high_flap_threshold'],
                    'high_flap_threshold', $a_servicetemplates[$i]);
         }
         $a_servicetemplates[$i] = $this->add_value_type(
                 self::$shinkenParameters['shinken']['services']['failure_prediction_enabled'],
                 'failure_prediction_enabled', $a_servicetemplates[$i]);
         $a_servicetemplates[$i] = $this->add_value_type(
                 self::$shinkenParameters['shinken']['services']['retain_status_information'],
                 'retain_status_information', $a_servicetemplates[$i]);
         $a_servicetemplates[$i] = $this->add_value_type(
                 self::$shinkenParameters['shinken']['services']['retain_nonstatus_information'],
                 'retain_nonstatus_information', $a_servicetemplates[$i]);

         $a_servicetemplates[$i] = $this->add_value_type(
                 '0', 'is_volatile', $a_servicetemplates[$i]);
/* Fred: Previous line should be commented and this comment should be removed ... but there is a bug in Shinken notifications with volatile services !
         if ($data['passive_checks_enabled'] == '1' && $data['active_checks_enabled'] == '0') {
            $a_servicetemplates[$i]['is_volatile'] = '1';
         } else {
            $a_servicetemplates[$i]['is_volatile'] = '0';
         }
*/
         $a_servicetemplates[$i] = $this->add_value_type(
                 '0', 'register', $a_servicetemplates[$i]);

         // Manage user interface ...
         $a_servicetemplates[$i] = $this->add_value_type(
                 'service', 'icon_set', $a_servicetemplates[$i]);

         // Fix service template association bug: #191
         // And simplify code !
         // $queryc = "SELECT * FROM `glpi_plugin_monitoring_components`
            // WHERE `plugin_monitoring_checks_id`='".$data['plugin_monitoring_checks_id']."'
               // AND `active_checks_enabled`='".$data['active_checks_enabled']."'
               // AND `passive_checks_enabled`='".$data['passive_checks_enabled']."'
               // AND `calendars_id`='".$data['calendars_id']."'";
         // $resultc = $DB->query($queryc);
         // while ($datac=$DB->fetch_array($resultc)) {
            // $a_templatesdef[$datac['id']] = $a_servicetemplates[$i]['name'];
         // }
         $a_templatesdef[$data['id']] = $a_servicetemplates[$i]['name'];

         $a_servicetemplates[$i] = $this->properties_list_to_string($a_servicetemplates[$i]);
         $i++;
      }
      }
      $_SESSION['plugin_monitoring']['servicetemplates'] = $a_templatesdef;

      PluginMonitoringToolbox::logIfExtradebug(
         'pm-shinken',
         "End generateTemplatesCfg\n"
      );

      if ($file == "1") {
         $config = "# Generated by plugin monitoring for GLPI\n# on ".date("Y-m-d H:i:s")."\n\n";

         foreach ($a_servicetemplates as $data) {
            $config .= $this->writeFile("service", $data);
         }
         return array('servicetemplates.cfg', $config);

      } else {
         return $a_servicetemplates;
      }
   }



   function generateHostgroupsCfg($file=0, $tag='') {
      global $DB;

      PluginMonitoringToolbox::logIfExtradebug(
         'pm-shinken',
         "Starting generateHostgroupsCfg ($tag) ...\n"
      );
      $pmEntity      = new PluginMonitoringEntity();

      $a_hostgroups = array();
      $i=0;

      $a_entities_allowed = $pmEntity->getEntitiesByTag($tag);
      $a_entities_list = array();
      foreach ($a_entities_allowed as $entity) {
         // @ddurieux: should array_merge ($a_entities_list and getSonsOf("glpi_entities", $entity)) ?
         $a_entities_list = getSonsOf("glpi_entities", $entity);
      }
      $where = '';
      if (! isset($a_entities_allowed['-1'])) {
         $where = getEntitiesRestrictRequest("WHERE", "glpi_entities", '', $a_entities_list);
      }

      $query = "SELECT
         `glpi_entities`.`id` AS entityId, `glpi_entities`.`name` AS entityName, `glpi_entities`.`level` AS entityLevel
         , `glpi_entities`.`comment` AS comment
         , `glpi_entities`.`address` AS address , `glpi_entities`.`postcode` AS postcode, `glpi_entities`.`town` AS town, `glpi_entities`.`state` AS state, `glpi_entities`.`country` AS country
         , `glpi_entities`.`website` AS website , `glpi_entities`.`fax` AS fax, `glpi_entities`.`email` AS email, `glpi_entities`.`phonenumber` AS phonenumber
         FROM `glpi_entities` $where";
      $result = $DB->query($query);
      while ($data=$DB->fetch_array($result)) {
/*
Nagios configuration file :
   define hostgroup{
      hostgroup_name	hostgroup_name
      alias	alias
      members	hosts
      hostgroup_members	hostgroups
      notes	note_string
      notes_url	url
      action_url	url
   }
*/
         $a_hostgroups[$i] = array();
         // Hostgroup name
         $hostgroup_name = strtolower(self::shinkenFilter($data['entityName']));
         $hostgroup_name = preg_replace("/[ ]/","_",$hostgroup_name);

         PluginMonitoringToolbox::logIfExtradebug(
            'pm-shinken',
            " - add group $hostgroup_name ...\n"
         );

         $a_hostgroups[$i] = $this->add_value_type(
                 $hostgroup_name, 'hostgroup_name', $a_hostgroups[$i]);
         $a_hostgroups[$i] = $this->add_value_type(
                 $data['entityName'], 'alias', $a_hostgroups[$i]);

         // Custom variable are ignored for hostgroups ... simple information for debug purpose !
         // $a_hostgroups[$i] = $this->add_value_type(
                 // $data['entityLevel'], '_GROUP_LEVEL', $a_hostgroups[$i]);
         // $a_hostgroups[$i] = $this->add_value_type(
                 // $data['entityLevel'], 'level', $a_hostgroups[$i]);

         // Host group members
         $a_sons_list = getSonsOf("glpi_entities", $data['entityId']);
         if (count($a_sons_list) > 1) {
            // $a_hostgroups[$i] = $this->add_value_type(
                    // '', 'hostgroup_members', $a_hostgroups[$i]);
            $first_member = true;
            foreach ($a_sons_list as $son_entity) {
               if ($son_entity == $data['entityId']) continue;
               if (! in_array ($son_entity, $a_entities_list)) continue;

               $pmEntity = new Entity();
               $pmEntity->getFromDB($son_entity);
               // Only immediate sub level are considered as hostgroup members
               if ($data['entityLevel']+1 != $pmEntity->fields['level']) continue;

               $hostgroup_name = self::shinkenFilter($pmEntity->getField('name'));
               $hostgroup_name = preg_replace("/[ ]/","_",$hostgroup_name);

               $a_hostgroups[$i] = $this->add_value_type(
                       $hostgroup_name, 'hostgroup_members', $a_hostgroups[$i]);
               if ($first_member) $first_member = false;
            }
         }

         // Comments in notes ...
         // PluginMonitoringToolbox::logIfExtradebug(
            // 'pm-shinken',
            // " - location:{$data['locationName']} - {$data['locationComment']}\n"
         // );
         $notes = array();
         if (isset($data['comment'])) {
            $comment = str_replace("\r\n", "<br/>", $data['comment']);
            $notes[] = "Comment,,comment::{$comment}";
         }
         if (isset($data['address'])) {
            $address  = str_replace("\r\n", "<br/>", $data['address']);
            if (! empty($data['postcode']) && ! empty($data['town'])) {
               $address .= "<br/>" . $data['postcode'] . " " . $data['town'];
            } else if (! empty($data['postcode'])) {
               $address .= "<br/>" . $data['postcode'];
            } else if (! empty($data['town'])) {
               $address .= "<br/>" . $data['town'];
            }
            if (! empty($data['state']) && ! empty($data['country'])) {
               $address .= "<br/>" . $data['state'] . " - " . $data['country'];
            } else if (! empty($data['state'])) {
               $address .= "<br/>" . $data['state'];
            } else if (! empty($data['country'])) {
               $address .= "<br/>" . $data['country'];
            }
            $address .= "<br/>";
            if (! empty($data['phonenumber'])) {
               $address .= "<br/><i class='fa fa-phone'></i>&nbsp;: " . $data['phonenumber'];
            }
            if (! empty($data['fax'])) {
               $address .= "<br/><i class='fa fa-fax'></i>&nbsp;: " . $data['fax'];
            }
            if (! empty($data['email'])) {
               $address .= "<br/><i class='fa fa-envelope'></i>&nbsp;: " . $data['email'];
            }
            if (! empty($data['website'])) {
               $address .= "<br/><i class='fa fa-globe'></i>&nbsp;: " . $data['website'];
            }
            $notes[] = "Address,,envelope::{$address}";
         }
         if (count($notes) > 0) {
            $a_hostgroups[$i] = $this->add_value_type(
                    implode("|", $notes), 'notes', $a_hostgroups[$i]);
         }

         $a_hostgroups[$i] = $this->properties_list_to_string($a_hostgroups[$i]);
         $i++;
      }

      // Add an hostgroup for fake hosts
      if (self::$shinkenParameters['shinken']['fake_hosts']['build']) {
         $a_hostgroups[$i]['hostgroup_name'] = self::$shinkenParameters['shinken']['fake_hosts']['hostgroup_name'];
         $a_hostgroups[$i]['alias'] = self::$shinkenParameters['shinken']['fake_hosts']['hostgroup_alias'];
      }

      PluginMonitoringToolbox::logIfExtradebug(
         'pm-shinken',
         "End generateHostgroupsCfg\n"
      );

      if ($file == "1") {
         $config = "# Generated by plugin monitoring for GLPI\n# on ".date("Y-m-d H:i:s")."\n\n";

         foreach ($a_hostgroups as $data) {
            $config .= $this->writeFile("hostgroup", $data);
         }
         return array('hostgroups.cfg', $config);

      } else {
         return $a_hostgroups;
      }
   }



   function generateContactsCfg($file=0, $tag='') {
      global $DB;

      $pmEntity      = new PluginMonitoringEntity();
      $calendar      = new Calendar();

      PluginMonitoringToolbox::logIfExtradebug(
         'pm-shinken',
         "Starting generateContactsCfg ($tag) ...\n"
      );

      $a_users_used = array();
      $a_contacts = array();
      // Add default contact 'monitoring' for fake hosts
//      $a_calendars = current($calendar->find("", "", 1));
//      $cal = '24x7';
//      if (isset($a_calendars['name'])) {
//         $cal = $a_calendars['name'];
//      }
//      $a_contacts[-1] = array(
//          'contact_name'                   => 'monitoring',
//          'alias'                          => 'monitoring',
//          'host_notifications_enabled'     => '0',
//          'service_notifications_enabled'  => '0',
//          'service_notification_period'    => $cal,
//          'host_notification_period'       => $cal,
//          'service_notification_options' => '',
//          'host_notification_options'    => '',
//          'service_notification_commands'  => '',
//          'host_notification_commands'     => '',
//          'email'                          => '',
//          'pager'                          => '',
//      );


      $a_entities_allowed = $pmEntity->getEntitiesByTag($tag);
      $a_entities_list = array();
      foreach ($a_entities_allowed as $entity) {
         // @ddurieux: should array_merge ($a_entities_list and getSonsOf("glpi_entities", $entity)) ?
         $a_entities_list = getSonsOf("glpi_entities", $entity);
      }
      // Always add root entity contacts
      $a_entities_list[] = '0';
      $where = '';
      if (! isset($a_entities_allowed['-1'])) {
         $where = getEntitiesRestrictRequest("WHERE", "glpi_plugin_monitoring_contacts_items", '', $a_entities_list);
      }


      $i=0;

      $query = "SELECT * FROM `glpi_plugin_monitoring_contacts_items` $where";
      $result = $DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         if ($data['users_id'] > 0) {
            if ((!isset($a_users_used[$data['users_id']]))) {
               $a_contacts = $this->_addContactUser($a_contacts, $data['users_id'], $i);
               $i++;
               $a_users_used[$data['users_id']] = 1;
            }
         } else if ($data['groups_id'] > 0) {
            $queryg = "SELECT * FROM `glpi_groups_users`
               WHERE `groups_id`='".$data['groups_id']."'";
            $resultg = $DB->query($queryg);
            while ($datag=$DB->fetch_array($resultg)) {
               if ((!isset($a_users_used[$datag['users_id']]))) {
                  $a_contacts = $this->_addContactUser($a_contacts, $datag['users_id'], $i);
                  $i++;
                  $a_users_used[$datag['users_id']] = 1;
               }
            }
         }
      }
      // Add default monitoring user
      $user = new User();
      $a_monit_user = current($user->find("`name`='monitoring'", '', 1));
      if ((!isset($a_users_used[$a_monit_user['id']]))) {
         $a_contacts = $this->_addContactUser($a_contacts, $a_monit_user['id'], $i);
      }

      PluginMonitoringToolbox::logIfExtradebug(
         'pm-shinken',
         "End generateContactsCfg\n"
      );

      if ($file == "1") {
         $config = "# Generated by plugin monitoring for GLPI\n# on ".date("Y-m-d H:i:s")."\n\n";

         foreach ($a_contacts as $data) {
            $config .= $this->writeFile("contact", $data);
         }
         return array('contacts.cfg', $config);

      } else {
         return $a_contacts;
      }
   }



   function _addContactUser($a_contacts, $users_id, $i) {

      $pmContact              = new PluginMonitoringContact();
      $pmNotificationcommand  = new PluginMonitoringNotificationcommand();
      $pmContacttemplate      = new PluginMonitoringContacttemplate();
      $user                   = new User();
      $calendar               = new Calendar();

      $user->getFromDB($users_id);

      // Get contact template
      $a_pmcontact = current($pmContact->find("`users_id`='".$users_id."'", "", 1));
      if (empty($a_pmcontact) OR
              (isset($a_pmcontact['plugin_monitoring_contacttemplates_id'])
              AND $a_pmcontact['plugin_monitoring_contacttemplates_id'] == '0')) {
         // Use default template
         $a_pmcontact = current($pmContacttemplate->find("`is_default`='1'", "", 1));
      } else {
         // Use contact defined template
         $a_pmcontact = current($pmContacttemplate->find("`id`='".$a_pmcontact['plugin_monitoring_contacttemplates_id']."'", "", 1));
      }
      $a_contacts[$i] = array();
      $a_contacts[$i] = $this->add_value_type(
              $user->fields['name'], 'contact_name', $a_contacts[$i]);
      $a_contacts[$i] = $this->add_value_type(
              $user->getName(), 'alias', $a_contacts[$i]);
      if (isset(self::$shinkenParameters['shinken']['contacts']['note'])) {
         $a_contacts[$i] = $this->add_value_type(
                 self::$shinkenParameters['shinken']['contacts']['note'] . $a_pmcontact['name'],
                 'note', $a_contacts[$i]);
      }
      PluginMonitoringToolbox::logIfExtradebug(
         'pm-shinken',
         "- generate contact '".$user->fields['name']."' - ".$user->getName()."\n"
      );

      if (!isset($a_pmcontact['host_notification_period'])) {
         $a_calendars = current($calendar->find("", "", 1));
         $cal = '24x7';
         if (isset($a_calendars['name'])) {
            $cal = $a_calendars['name'];
         }
         $a_pmcontact['host_notifications_enabled']     = 0;
         $a_pmcontact['service_notifications_enabled']  = 0;
         $a_pmcontact['service_notification_period']    = $cal;
         $a_pmcontact['host_notification_period']       = $cal;
         $a_pmcontact['service_notification_options_w'] = 0;
         $a_pmcontact['service_notification_options_u'] = 0;
         $a_pmcontact['service_notification_options_c'] = 0;
         $a_pmcontact['service_notification_options_r'] = 0;
         $a_pmcontact['service_notification_options_f'] = 0;
         $a_pmcontact['service_notification_options_s'] = 0;
         $a_pmcontact['service_notification_options_n'] = 0;
         $a_pmcontact['host_notification_options_d']    = 0;
         $a_pmcontact['host_notification_options_u']    = 0;
         $a_pmcontact['host_notification_options_r']    = 0;
         $a_pmcontact['host_notification_options_f']    = 0;
         $a_pmcontact['host_notification_options_s']    = 0;
         $a_pmcontact['host_notification_options_n']    = 0;
         $a_pmcontact['service_notification_commands']  = 2;
         $a_pmcontact['host_notification_commands']     = 1;
      }
      $a_contacts[$i] = $this->add_value_type(
              $a_pmcontact['host_notifications_enabled'],
              'host_notifications_enabled', $a_contacts[$i]);
      $a_contacts[$i] = $this->add_value_type(
              $a_pmcontact['service_notifications_enabled'],
              'service_notifications_enabled', $a_contacts[$i]);

      // Contact entity jetlag ...
      $pmHostconfig  = new PluginMonitoringHostconfig();
      $timeperiodsuffix = '_'.$pmHostconfig->getValueAncestor('jetlag', $user->fields['entities_id']);
      if ($timeperiodsuffix == '_0') {
         $timeperiodsuffix = '';
      }

      if ($calendar->getFromDB($a_pmcontact['service_notification_period']) && $this->_addTimeperiod($user->fields['entities_id'], $a_pmcontact['service_notification_period'])) {
         $a_contacts[$i] = $this->add_value_type(
                 self::shinkenFilter($calendar->fields['name'].$timeperiodsuffix),
                 'service_notification_period', $a_contacts[$i]);
      } else {
         $a_contacts[$i] = $this->add_value_type(
                 self::$shinkenParameters['shinken']['contacts']['service_notification_period'],
                 'service_notification_period', $a_contacts[$i]);
      }

      if ($calendar->getFromDB($a_pmcontact['host_notification_period']) && $this->_addTimeperiod($user->fields['entities_id'], $a_pmcontact['host_notification_period'])) {
         $a_contacts[$i] = $this->add_value_type(
                 self::shinkenFilter($calendar->fields['name'].$timeperiodsuffix),
                 'host_notification_period', $a_contacts[$i]);
      } else {
         $a_contacts[$i] = $this->add_value_type(
                 self::$shinkenParameters['shinken']['contacts']['host_notification_period'],
                 'host_notification_period', $a_contacts[$i]);
      }

      $a_contacts[$i]['service_notification_options'] = array();
      if ($a_pmcontact['service_notification_options_w'] == 1) {
         $a_contacts[$i] = $this->add_value_type(
                 'w', 'service_notification_options', $a_contacts[$i]);
      }
      if ($a_pmcontact['service_notification_options_u'] == 1) {
         $a_contacts[$i] = $this->add_value_type(
                 'u', 'service_notification_options', $a_contacts[$i]);
      }
      if ($a_pmcontact['service_notification_options_c'] == 1) {
         $a_contacts[$i] = $this->add_value_type(
                 'c', 'service_notification_options', $a_contacts[$i]);
      }
      if ($a_pmcontact['service_notification_options_r'] == 1) {
         $a_contacts[$i] = $this->add_value_type(
                 'r', 'service_notification_options', $a_contacts[$i]);
      }
      if ($a_pmcontact['service_notification_options_f'] == 1) {
         $a_contacts[$i] = $this->add_value_type(
                 'f', 'service_notification_options', $a_contacts[$i]);
      }
      if ($a_pmcontact['service_notification_options_s'] == 1) {
         $a_contacts[$i] = $this->add_value_type(
                 's', 'service_notification_options', $a_contacts[$i]);
      }
      if ($a_pmcontact['service_notification_options_n'] == 1) {
         $a_contacts[$i] = $this->add_value_type(
                 'n', 'service_notification_options', $a_contacts[$i]);
      }
      if (count($a_contacts[$i]['service_notification_options']) == 0) {
         $a_contacts[$i] = $this->add_value_type(
                 'n', 'service_notification_options', $a_contacts[$i]);
      }

      $a_contacts[$i]['host_notification_options'] = array();
      if ($a_pmcontact['host_notification_options_d'] == 1) {
         $a_contacts[$i] = $this->add_value_type(
                 'd', 'host_notification_options', $a_contacts[$i]);
      }
      if ($a_pmcontact['host_notification_options_u'] == 1) {
         $a_contacts[$i] = $this->add_value_type(
                 'u', 'host_notification_options', $a_contacts[$i]);
      }
      if ($a_pmcontact['host_notification_options_r'] == 1) {
         $a_contacts[$i] = $this->add_value_type(
                 'r', 'host_notification_options', $a_contacts[$i]);
      }
      if ($a_pmcontact['host_notification_options_f'] == 1) {
         $a_contacts[$i] = $this->add_value_type(
                 'f', 'host_notification_options', $a_contacts[$i]);
      }
      if ($a_pmcontact['host_notification_options_s'] == 1) {
         $a_contacts[$i] = $this->add_value_type(
                 's', 'host_notification_options', $a_contacts[$i]);
      }
      if ($a_pmcontact['host_notification_options_n'] == 1) {
         $a_contacts[$i] = $this->add_value_type(
                 'n', 'host_notification_options', $a_contacts[$i]);
      }
      if (count($a_contacts[$i]['host_notification_options']) == 0) {
         $a_contacts[$i] = $this->add_value_type(
                 'n', 'host_notification_options', $a_contacts[$i]);
      }

      $pmNotificationcommand->getFromDB($a_pmcontact['service_notification_commands']);
      if (isset($pmNotificationcommand->fields['command_name'])) {
         $a_contacts[$i] = $this->add_value_type(
                 PluginMonitoringCommand::$command_prefix . $pmNotificationcommand->fields['command_name'],
                 'service_notification_commands', $a_contacts[$i]);
      } else {
         $a_contacts[$i] = $this->add_value_type(
                 '', 'service_notification_commands', $a_contacts[$i]);
      }
      $pmNotificationcommand->getFromDB($a_pmcontact['host_notification_commands']);
      if (isset($pmNotificationcommand->fields['command_name'])) {
         $a_contacts[$i] = $this->add_value_type(
                 PluginMonitoringCommand::$command_prefix . $pmNotificationcommand->fields['command_name'],
                 'host_notification_commands', $a_contacts[$i]);
      } else {
         $a_contacts[$i] = $this->add_value_type(
                 '', 'host_notification_commands', $a_contacts[$i]);
      }

      // Get first email
      $a_emails = UserEmail::getAllForUser($users_id);
      $first = 0;
      foreach ($a_emails as $email) {
         if ($first == 0) {
            $a_contacts[$i] = $this->add_value_type(
                    $email, 'email', $a_contacts[$i]);
         }
         $first++;
      }
      if (!isset($a_contacts[$i]['email'])) {
         $a_contacts[$i] = $this->add_value_type(
                 '', 'email', $a_contacts[$i]);
      }
      $a_contacts[$i] = $this->add_value_type(
              $user->fields['phone'], 'pager', $a_contacts[$i]);

      // Persist contact status
      if (isset(self::$shinkenParameters['shinken']['contacts']['retain_status_information'])) {
         $a_contacts[$i] = $this->add_value_type(
                 self::$shinkenParameters['shinken']['contacts']['retain_status_information'],
                 'retain_status_information', $a_contacts[$i]);
      }
      if (isset(self::$shinkenParameters['shinken']['contacts']['retain_nonstatus_information'])) {
         $a_contacts[$i] = $this->add_value_type(
                 self::$shinkenParameters['shinken']['contacts']['retain_nonstatus_information'],
                 'retain_nonstatus_information', $a_contacts[$i]);
      }
      if (isset($a_pmcontact['shinken_administrator'])) {
         $a_contacts[$i] = $this->add_value_type(
                 $a_pmcontact['shinken_administrator'],
                 'is_admin', $a_contacts[$i]);
      } else {
         $a_contacts[$i] = $this->add_value_type(
                 self::$shinkenParameters['webui']['contacts']['is_admin'],
                 'is_admin', $a_contacts[$i]);
      }
      if (isset($a_pmcontact['shinken_can_submit_commands'])) {
         $a_contacts[$i] = $this->add_value_type(
                 $a_pmcontact['shinken_can_submit_commands'],
                 'can_submit_commands', $a_contacts[$i]);
      } else {
         $a_contacts[$i] = $this->add_value_type(
                 self::$shinkenParameters['webui']['contacts']['can_submit_commands'],
                 'can_submit_commands', $a_contacts[$i]);
      }
      if (empty($user->fields['password'])) {
         $a_contacts[$i] = $this->add_value_type(
                 self::$shinkenParameters['webui']['contacts']['password'],
                 'password', $a_contacts[$i]);
      } else {
         $a_contacts[$i] = $this->add_value_type(
                 $user->fields['password'],  'password', $a_contacts[$i]);
      }

      /*
      TODO:
      address1, address2, ..., address6 are available in Shinken
      */
      $a_contacts[$i] = $this->properties_list_to_string($a_contacts[$i]);
      return $a_contacts;
   }



   function generateTimeperiodsCfg($file=0, $tag='') {

      if (!isset($_SESSION['plugin_monitoring'])) {
         $_SESSION['plugin_monitoring'] = array();
      }

      PluginMonitoringToolbox::logIfExtradebug(
         'pm-shinken',
         "Starting generateTimeperiodsCfg ...\n"
      );

      PluginMonitoringToolbox::logIfExtradebug(
         'pm-shinken',
         "End generateTimeperiodsCfg\n"
      );

      if ($file == "1") {
         $config = "# Generated by plugin monitoring for GLPI\n# on ".date("Y-m-d H:i:s")."\n\n";

         if (isset($_SESSION['plugin_monitoring']['timeperiods'])) {
            $config .= "#Time periods\n\n";
            foreach ($_SESSION['plugin_monitoring']['timeperiods'] as $data) {
               $config .= $this->writeFile("timeperiod", $data);
            }
         }
         if (isset($_SESSION['plugin_monitoring']['holidays'])) {
            $config .= "#Exclusion periods\n\n";
            foreach ($_SESSION['plugin_monitoring']['holidays'] as $data) {
               $config .= $this->writeFile("timeperiod", $data);
            }
         }
         return array('timeperiods.cfg', $config);

      } else {
         $a_timeperiods = array();
         $i=0;
         if (isset($_SESSION['plugin_monitoring']['timeperiods'])) {
            foreach ($_SESSION['plugin_monitoring']['timeperiods'] as $data) {
               $a_timeperiods[$i] = array();
               foreach ($data as $key=>$val) {
                  $a_timeperiods[$i] = $this->add_value_type(
                          $val, $key, $a_timeperiods[$i]);
               }
               PluginMonitoringToolbox::logIfExtradebug(
                  'pm-shinken',
                  " - ".serialize($data)."\n"
               );
               $a_timeperiods[$i] = $this->properties_list_to_string($a_timeperiods[$i]);
               $i++;
            }
         }
         if (isset($_SESSION['plugin_monitoring']['holidays'])) {
            foreach ($_SESSION['plugin_monitoring']['holidays'] as $data) {
               $a_timeperiods[$i] = array();
               foreach ($data as $key=>$val) {
                  $a_timeperiods[$i] = $this->add_value_type(
                          $val, $key, $a_timeperiods[$i]);
               }
               PluginMonitoringToolbox::logIfExtradebug(
                  'pm-shinken',
                  " - ".serialize($data)."\n"
               );
               $a_timeperiods[$i] = $this->properties_list_to_string($a_timeperiods[$i]);
               $i++;
            }
         }
         return $a_timeperiods;
      }
   }


   function _addHoliday($entities_id=-1, $holidays_id=-1) {

      if (! isset($_SESSION['plugin_monitoring']['holidays'])) {
         $_SESSION['plugin_monitoring']['holidays'] = array();
      }
      PluginMonitoringToolbox::logIfExtradebug(
         'pm-shinken',
         "Starting _addHoliday: $entities_id / $holidays_id ...\n"
      );
      $calendar         = new Calendar();
      $calendarSegment  = new CalendarSegment();
      $calendar_Holiday = new Calendar_Holiday();
      $holiday          = new Holiday();
      $hostconfig       = new PluginMonitoringHostconfig();
      $entity           = new Entity();

      // Jetlag for required entity ...
      if (!isset($_SESSION['plugin_monitoring']['jetlag'])) {
         $_SESSION['plugin_monitoring']['jetlag'] = array();
      }
      if (!isset($_SESSION['plugin_monitoring']['jetlag'][$entities_id])) {
         $_SESSION['plugin_monitoring']['jetlag'][$entities_id] =
                        $hostconfig->getValueAncestor('jetlag', $entities_id);
      }
      $timeperiodsuffix = $_SESSION['plugin_monitoring']['jetlag'][$entities_id];
      if ($timeperiodsuffix == '_0') {
         $timeperiodsuffix = '';
      }
      PluginMonitoringToolbox::logIfExtradebug(
         'pm-shinken',
         " - entity: $entities_id, jetlag: $timeperiodsuffix\n"
      );

      if (! $holiday->getFromDB($holidays_id)) {
         PluginMonitoringToolbox::logIfExtradebug(
            'pm-shinken',
            " - invalid holiday: $holidays_id ...\n"
         );
         return false;
      }
      PluginMonitoringToolbox::logIfExtradebug(
         'pm-shinken',
         " - holiday: $holidays_id, jetlag: $timeperiodsuffix\n"
      );

      $tmp = array();
      if ($timeperiodsuffix == 0) {
         $tmp['timeperiod_name'] = self::shinkenFilter($holiday->fields['name']);
         $tmp['alias'] = $holiday->fields['name'];
      } else {
         $tmp['timeperiod_name'] = self::shinkenFilter($holiday->fields['name']."_".$timeperiodsuffix);
         $tmp['alias'] = $holiday->fields['name']." (".$timeperiodsuffix.")";
      }

      // If timeperiod already exists in memory ...
      if (isset($_SESSION['plugin_monitoring']['holidays'][ $tmp['timeperiod_name'] ])) {
         return $tmp['timeperiod_name'];
      }

      PluginMonitoringToolbox::logIfExtradebug(
         'pm-shinken',
         " - _addHoliday, building TP '{$tmp['timeperiod_name']}' for entity: $entities_id\n"
      );
         // $holiday->getFromDB($a_choliday['holidays_id']);
      if ($holiday->fields['is_perpetual'] == 1
              && $holiday->fields['begin_date'] == $holiday->fields['end_date']) {
         $datetime = strtotime($holiday->fields['begin_date']);
         $tmp[strtolower(date('F', $datetime)).
             ' '.date('j', $datetime)] = '00:00-24:00';
      }


      $days = array('sunday','monday','tuesday', 'wednesday','thursday',
                    'friday', 'saturday');
      $saturday = '';
      $reportHours = 0;
      $beforeday = 'saturday';
      foreach ($days as $numday=>$day) {
         if (isset($tmp[$day])) {
            $splitDay = explode(',', $tmp[$day]);
            $toAdd = '';
            if ($reportHours > 0) {
               $toAdd = '00:00-'.sprintf("%02s", $reportHours).':00';
               $reportHours = 0;
            }
            foreach ($splitDay as $num=>$hourMinute) {
               $previous_begin = 0;
               $beginEnd = explode('-', $hourMinute);
               // ** Begin **
               $split = explode(':', $beginEnd[0]);
               $split[0] += $timeperiodsuffix;
               if ($split[0] > 24) {
                  //$reportHours = $split[0] - 24;
                  unset($splitDay[$num]);
               } else {
                  if ($split[0] < 0) {
                     $reportHours = $split[0];
                     $previous_begin = 24 + $split[0];
                     $split[0] = '00';
                  }
                  $beginEnd[0] = sprintf("%02s", $split[0]).':'.$split[1];
                  // ** End **
                  $split = explode(':', $beginEnd[1]);
                  $split[0] += $timeperiodsuffix;
                  if ($split[0] < 0) {
                     if ($numday-1 == -1) {
                        $saturday .= ",".sprintf("%02s", $previous_begin).":00-".sprintf("%02s", (24 + $split[0])).":00";
                     } else {
                        $tmp[$days[($numday-1)]] .= ",".sprintf("%02s", $previous_begin).":00-".sprintf("%02s", (24 + $split[0])).":00";
                     }
                     unset($splitDay[$num]);
                  } else {
                     if ($split[0] > 24) {
                        $reportHours = $split[0] - 24;
                        $split[0] = 24;
                     }
                     $beginEnd[1] = sprintf("%02s", $split[0]).':'.$split[1];

                     $hourMinute = implode('-', $beginEnd);
                     $splitDay[$num] = $hourMinute;
                  }
               }
            }
            if ($reportHours < 0) {
               $reportHours = 0;
            }
            if (!empty($toAdd)) {
               array_unshift($splitDay, $toAdd);
            }
            $tmp[$day] = implode(',', $splitDay);
         } else if ($reportHours > 0) {
            //$tmp[$day] = '00:00-'.$reportHours.':00';
            $reportHours = 0;
         }
         $beforeday = $day;
      }
      // Manage for report hours from saturday to sunday
      if ($reportHours > 0) {
         $splitDay = explode(',', $tmp['sunday']);
         array_unshift($splitDay, '00:00-'.sprintf("%02s", $reportHours).':00');
         $tmp['sunday'] = implode(',', $splitDay);
      }
      if ($saturday != '') {
         if (isset($tmp['saturday'])) {
            $tmp['saturday'] .= $saturday;
         } else {
            $tmp['saturday'] = $saturday;
         }
      }

      // concatenate if needed
      foreach ($days as $day) {
         if (isset($tmp[$day])) {
            $splitDay = explode(',', $tmp[$day]);
            $beforeHour = '';
            $beforeNum  = 0;
            foreach ($splitDay as $num=>$data) {
               if (substr($data, 0, 2) == $beforeHour) {
                  $splitDay[$beforeNum] = substr($splitDay[$beforeNum], 0, 6).substr($data, 6, 5);
                  $beforeHour = substr($data, 6, 2);
                  unset($splitDay[$num]);
               } else {
                  $beforeHour = substr($data, 6, 2);
                  $beforeNum = $num;
               }
            }
            $tmp[$day] = implode(',', $splitDay);
         }
      }

      $_SESSION['plugin_monitoring']['holidays'][ $tmp['timeperiod_name'] ] = $tmp;

      PluginMonitoringToolbox::logIfExtradebug(
         'pm-shinken',
         "End _addHoliday: {$tmp['timeperiod_name']}\n"
      );

      return $tmp['timeperiod_name'];
   }


   function _addTimeperiod($entities_id=-1, $calendars_id=-1) {

      if (! isset($_SESSION['plugin_monitoring']['timeperiods'])) {
         $_SESSION['plugin_monitoring']['timeperiods'] = array();
      }
      if (! isset($_SESSION['plugin_monitoring']['timeperiodsmapping'])) {
         $_SESSION['plugin_monitoring']['timeperiodsmapping'] = array();
      }
      PluginMonitoringToolbox::logIfExtradebug(
         'pm-shinken',
         "Starting _addTimeperiod: $entities_id / $calendars_id ...\n"
      );
      $calendar         = new Calendar();
      $calendarSegment  = new CalendarSegment();
      $calendar_Holiday = new Calendar_Holiday();
      $hostconfig       = new PluginMonitoringHostconfig();
      $entity           = new Entity();

      if (! $entity->getFromDB($entities_id)) {
         PluginMonitoringToolbox::logIfExtradebug(
            'pm-shinken',
            " - invalid entity: $entities_id\n"
         );
         return false;
      }
      // Jetlag for required entity ...
      if (!isset($_SESSION['plugin_monitoring']['jetlag'])) {
         $_SESSION['plugin_monitoring']['jetlag'] = array();
      }
      if (!isset($_SESSION['plugin_monitoring']['jetlag'][$entities_id])) {
         $_SESSION['plugin_monitoring']['jetlag'][$entities_id] =
                        $hostconfig->getValueAncestor('jetlag', $entities_id);
      }
      $timeperiodsuffix = $_SESSION['plugin_monitoring']['jetlag'][$entities_id];
      if ($timeperiodsuffix == '_0') {
         $timeperiodsuffix = '';
      }

      if (!isset($_SESSION['plugin_monitoring']['timeperiodsmapping'][$calendars_id])) {
         if (! $calendar->getFromDB($calendars_id)) {
            PluginMonitoringToolbox::logIfExtradebug(
               'pm-shinken',
               " - invalid calendar: $calendars_id ...\n"
            );
            return false;
         }
         $_SESSION['plugin_monitoring']['timeperiodsmapping'][$calendars_id] = $calendar->fields['name'];
      }
      $tp_name = $_SESSION['plugin_monitoring']['timeperiodsmapping'][$calendars_id];
      PluginMonitoringToolbox::logIfExtradebug(
         'pm-shinken',
         " - _addTimeperiod, entity: $entities_id, jetlag: $timeperiodsuffix\n"
      );

      $tmp = array();
      if ($timeperiodsuffix == 0) {
         $tmp['timeperiod_name'] = self::shinkenFilter($tp_name);
         $tmp['alias'] = $tp_name;
      } else {
         $tmp['timeperiod_name'] = self::shinkenFilter($tp_name."_".$timeperiodsuffix);
         $tmp['alias'] = $tp_name." (".$timeperiodsuffix.")";
      }

      // If timeperiod already exists in memory ...
      if (isset($_SESSION['plugin_monitoring']['timeperiods'][ $tmp['timeperiod_name'] ])) {
         return true;
      }

      PluginMonitoringToolbox::logIfExtradebug(
         'pm-shinken',
         " - _addTimeperiod, building calendar '{$tmp['timeperiod_name']}' for entity: $entities_id\n"
      );
      // $tmp['timeperiod_name'] = self::shinkenFilter($calendar->fields['name']);
      // $tmp['alias'] = $calendar->fields['name'];
      $a_listsegment = $calendarSegment->find("`calendars_id`='".$calendars_id."'");
      $a_cal = array();
      foreach ($a_listsegment as $datasegment) {
         $begin = preg_replace("/:00$/", "", $datasegment['begin']);
         $end = preg_replace("/:00$/", "", $datasegment['end']);
         $day = "";
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
         $a_cal[$day][] = $begin."-".$end;
      }
      foreach ($a_cal as $day=>$a_times) {
         $tmp[$day] = implode(',', $a_times);
      }
      $a_cholidays = $calendar_Holiday->find("`calendars_id`='".$calendars_id."'");
      $a_excluded = array();
      foreach ($a_cholidays as $a_choliday) {
         PluginMonitoringToolbox::logIfExtradebug(
            'pm-shinken',
            " - _addTimeperiod, building holiday '{$a_choliday['holidays_id']}'\n"
         );
         $a_excluded[] = $this->_addHoliday($entities_id, $a_choliday['holidays_id']);
         // $holiday->getFromDB($a_choliday['holidays_id']);
         // if ($holiday->fields['is_perpetual'] == 1
                 // && $holiday->fields['begin_date'] == $holiday->fields['end_date']) {
            // $datetime = strtotime($holiday->fields['begin_date']);
            // $tmp[strtolower(date('F', $datetime)).
                // ' '.date('j', $datetime)] = '00:00-24:00';
         // }
      }
      if (count($a_excluded) > 0) {
         $tmp['exclude'] = implode(',', $a_excluded);
      }


      // if ($timeperiodsuffix == 0) {
         // $tmp['timeperiod_name'] = self::shinkenFilter($tmp['timeperiod_name']);
      // } else {
         // $tmp['timeperiod_name'] = self::shinkenFilter($tmp['timeperiod_name']."_".$timeperiodsuffix);
         // $tmp['alias'] = $tmp['alias']." (".$timeperiodsuffix.")";
      // }
      $days = array('sunday','monday','tuesday', 'wednesday','thursday',
                    'friday', 'saturday');
      $saturday = '';
      $reportHours = 0;
      $beforeday = 'saturday';
      foreach ($days as $numday=>$day) {
         if (isset($tmp[$day])) {
            $splitDay = explode(',', $tmp[$day]);
            $toAdd = '';
            if ($reportHours > 0) {
               $toAdd = '00:00-'.sprintf("%02s", $reportHours).':00';
               $reportHours = 0;
            }
            foreach ($splitDay as $num=>$hourMinute) {
               $previous_begin = 0;
               $beginEnd = explode('-', $hourMinute);
               // ** Begin **
               $split = explode(':', $beginEnd[0]);
               $split[0] += $timeperiodsuffix;
               if ($split[0] > 24) {
                  //$reportHours = $split[0] - 24;
                  unset($splitDay[$num]);
               } else {
                  if ($split[0] < 0) {
                     $reportHours = $split[0];
                     $previous_begin = 24 + $split[0];
                     $split[0] = '00';
                  }
                  $beginEnd[0] = sprintf("%02s", $split[0]).':'.$split[1];
                  // ** End **
                  $split = explode(':', $beginEnd[1]);
                  $split[0] += $timeperiodsuffix;
                  if ($split[0] < 0) {
                     if ($numday-1 == -1) {
                        $saturday .= ",".sprintf("%02s", $previous_begin).":00-".sprintf("%02s", (24 + $split[0])).":00";
                     } else {
                        $tmp[$days[($numday-1)]] .= ",".sprintf("%02s", $previous_begin).":00-".sprintf("%02s", (24 + $split[0])).":00";
                     }
                     unset($splitDay[$num]);
                  } else {
                     if ($split[0] > 24) {
                        $reportHours = $split[0] - 24;
                        $split[0] = 24;
                     }
                     $beginEnd[1] = sprintf("%02s", $split[0]).':'.$split[1];

                     $hourMinute = implode('-', $beginEnd);
                     $splitDay[$num] = $hourMinute;
                  }
               }
            }
            if ($reportHours < 0) {
//                     if (!isset($tmp[$beforeday])) {
//                        $tmp[$beforeday] = array();
//                     }
//                     $splitBeforeDay = explode(',', $tmp[$beforeday]);
//                     $splitBeforeDay[] = sprintf("%02s", (24 + $reportHours)).':00-24:00';
//                     $tmp[$beforeday] = implode(',', $splitBeforeDay);
               $reportHours = 0;
            }
            if (!empty($toAdd)) {
               array_unshift($splitDay, $toAdd);
            }
            $tmp[$day] = implode(',', $splitDay);
         } else if ($reportHours > 0) {
            //$tmp[$day] = '00:00-'.$reportHours.':00';
            $reportHours = 0;
         }
         $beforeday = $day;
      }
      // Manage for report hours from saturday to sunday
      if ($reportHours > 0) {
         $splitDay = explode(',', $tmp['sunday']);
         array_unshift($splitDay, '00:00-'.sprintf("%02s", $reportHours).':00');
         $tmp['sunday'] = implode(',', $splitDay);
      }
      if ($saturday != '') {
         if (isset($tmp['saturday'])) {
            $tmp['saturday'] .= $saturday;
         } else {
            $tmp['saturday'] = $saturday;
         }
      }

      // concatenate if needed
      foreach ($days as $day) {
         if (isset($tmp[$day])) {
            $splitDay = explode(',', $tmp[$day]);
            $beforeHour = '';
            $beforeNum  = 0;
            foreach ($splitDay as $num=>$data) {
               if (substr($data, 0, 2) == $beforeHour) {
                  $splitDay[$beforeNum] = substr($splitDay[$beforeNum], 0, 6).substr($data, 6, 5);
                  $beforeHour = substr($data, 6, 2);
                  unset($splitDay[$num]);
               } else {
                  $beforeHour = substr($data, 6, 2);
                  $beforeNum = $num;
               }
            }
            $tmp[$day] = implode(',', $splitDay);
         }
      }

      $_SESSION['plugin_monitoring']['timeperiods'][ $tmp['timeperiod_name'] ] = $tmp;

      PluginMonitoringToolbox::logIfExtradebug(
         'pm-shinken',
         "End _addTimeperiod: {$tmp['timeperiod_name']}\n"
      );

      return true;
   }



   /**
    * Add value with the right type (str, int, bool, float)
    */
   function add_value_type($val, $key, $data) {
      global $PM_EXPORTFOMAT;

      if ($this->is_property_list($key)) {
         if (!isset($data[$key])) {
            $data[$key] = array();
         }
         $data[$key][] = (string)$val;
      } else {
         switch ($key) {

            case "active_checks_enabled": // bool
            case "broker_complete_links":
            case "business_rule_downtime_as_ack":
            case "business_rule_smart_notifications":
            case "can_submit_commands":
            case "check_freshness":
            case "default":
            case "enable_environment_macros":
            case "event_handler_enabled":
            case "expert":
            case "explode_hostgroup":
            case "failure_prediction_enabled":
            case "flap_detection_enabled":
            case "host_dependency_enabled":
            case "host_notifications_enabled":
            case "inherits_parent":
            case "is_active":
            case "is_admin":
            case "is_volatile":
            case "merge_host_contacts":
            case "notifications_enabled":
            case "obsess_over_host":
            case "obsess_over_service":
            case "parallelize_check":
            case "passive_checks_enabled":
            case "process_perf_data":
            case "register":
            case "retain_nonstatus_information":
            case "retain_status_information":
            case "service_notifications_enabled":
            case "snapshot_enabled":
            case "trigger_broker_raise_enabled":
               if ($PM_EXPORTFOMAT == 'boolean') {
                  $data[$key] = (bool)$val;
                  if ($data[$key] == '') {
                     $data[$key] = (bool)0;
                  }
               } else {
                  $data[$key] = (int)$val;
                  if ($data[$key] == '') {
                     $data[$key] = (int)0;
                  }
               }
               break;

            case "business_impact": // int
            case "check_interval":
            case "discoveryrule_order":
            case "first_notification":
            case "first_notification_delay":
            case "first_notification_time":
            case "freshness_threshold":
            case "high_flap_threshold":
            case "id":
            case "last_notification":
            case "last_notification_time":
            case "low_flap_threshold":
            case "max_check_attempts":
            case "min_business_impact":
            case "notification_interval":
            case "retry_interval":
            case "notification_interval":
            case "snapshot_interval":
            case "timeout":
            case "time_to_orphanage":
            // case "_ENTITIESID":
            // case "_HOSTID":
            // case "_ITEMSID":
               $data[$key] = (int)$val;
               break;

           default: // string
              $data[$key] = (string)$val;
         }
      }
      return $data;
   }


   function properties_list_to_string($data) {
      foreach ($data as $key=>$val) {
         if ($this->is_property_list($key)) {
               $data[$key] = implode(',', array_unique($val));
         }
      }
      return $data;
   }


   function is_property_list($key) {

      switch ($key) {

         case "business_impact_modulations": // list
         case "business_rule_host_notification_options":
         case "business_rule_service_notification_options":
         case "checkmodulations":
         case "contacts":
         case "contact_groups":
         case "custom_views":
         case "dateranges":
         case "escalations":
         case "escalation_options":
         case "exclude":
         case "execution_failure_criteria":
         case "flap_detection_options":
         case "higher_realms":
         case "hostgroups":
         case "hostgroup_members":
         case "host_notification_commands":
         case "host_notification_options":
         case "labels":
         case "macromodulations":
         case "members":
         case "modules":
         case "notificationways":
         case "notification_failure_criteria":
         case "notification_options":
         case "parents":
         case "realm_members":
         case "resultmodulations":
         case "servicegroups":
         case "service_dependencies":
         case "service_excludes":
         case "service_includes":
         case "service_notification_commands":
         case "service_notification_options":
         case "service_overrides":
         case "snapshot_criteria":
         case "stalking_options":
         case "trending_policies":
         case "unknown_members":
            return true;
      }
      return false;
   }
}

?>