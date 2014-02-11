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
   @since     2011
 
   ------------------------------------------------------------------------
 */


class b_MonitoringInstall extends PHPUnit_Framework_TestCase {

   public function testDB($pluginname='') {
      global $DB;
       
      if ($pluginname == '') {
         return;
      }
      
       $comparaisonSQLFile = "plugin_".$pluginname."-empty.sql";
       // See http://joefreeman.co.uk/blog/2009/07/php-script-to-compare-mysql-database-schemas/
       
       $file_content = file_get_contents("../../".$pluginname."/install/mysql/".$comparaisonSQLFile);
       $a_lines = explode("\n", $file_content);
       
       $a_tables_ref = array();
       $current_table = '';
       foreach ($a_lines as $line) {
          if (strstr($line, "CREATE TABLE ")) {
             $matches = array();
             preg_match("/`(.*)`/", $line, $matches);
             $current_table = $matches[1];
          } else {
             if (preg_match("/^`/", trim($line))) {
                $s_line = explode("`", $line);
                $s_type = explode("COMMENT", $s_line[2]);
                $s_type[0] = trim($s_type[0]);
                $s_type[0] = str_replace(" COLLATE utf8_unicode_ci", "", $s_type[0]);
                $s_type[0] = str_replace(" CHARACTER SET utf8", "", $s_type[0]);
                $a_tables_ref[$current_table][$s_line[1]] = str_replace(",", "", $s_type[0]);
             }
          }
       }
       
      // * Get tables from MySQL
      $a_tables_db = array();
      $a_tables = array();
      // SHOW TABLES;
      $query = "SHOW TABLES";
      $result = $DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         if (strstr($data[0], $pluginname)){
            $data[0] = str_replace(" COLLATE utf8_unicode_ci", "", $data[0]);
            $data[0] = str_replace("( ", "(", $data[0]);
            $data[0] = str_replace(" )", ")", $data[0]);
            $a_tables[] = $data[0];
         }
      }
      
      foreach($a_tables as $table) {
         $query = "SHOW COLUMNS FROM ".$table;
         $result = $DB->query($query);
         while ($data=$DB->fetch_array($result)) {
            $construct = $data['Type'];
//            if ($data['Type'] == 'text') {
//               $construct .= ' COLLATE utf8_unicode_ci';
//            }
            if ($data['Type'] == 'text') {
               if ($data['Null'] == 'NO') {
                  $construct .= ' NOT NULL';
               } else {
                  $construct .= ' DEFAULT NULL';
               }
            } else if ($data['Type'] == 'longtext') {
               if ($data['Null'] == 'NO') {
                  $construct .= ' NOT NULL';
               } else {
                  $construct .= ' DEFAULT NULL';
               }
            } else {
               if ((strstr($data['Type'], "char")
                       OR $data['Type'] == 'datetime'
                       OR strstr($data['Type'], "int"))
                       AND $data['Null'] == 'YES'
                       AND $data['Default'] == '') {
                  $construct .= ' DEFAULT NULL';
               } else {               
                  if ($data['Null'] == 'YES') {
                     $construct .= ' NULL';
                  } else {
                     $construct .= ' NOT NULL';
                  }
                  if ($data['Extra'] == 'auto_increment') {
                     $construct .= ' AUTO_INCREMENT';
                  } else {
//                     if ($data['Type'] != 'datetime') {
                        $construct .= " DEFAULT '".$data['Default']."'";
//                     }
                  }
               }
            }
            $a_tables_db[$table][$data['Field']] = $construct;
         }         
      }
      
       // Compare
      $tables_toremove = array_diff_assoc($a_tables_db, $a_tables_ref);
      $tables_toadd = array_diff_assoc($a_tables_ref, $a_tables_db);
       
      // See tables missing or to delete
      $this->assertEquals(count($tables_toadd), 0, 'Tables missing '.print_r($tables_toadd));
      $this->assertEquals(count($tables_toremove), 0, 'Tables to delete '.print_r($tables_toremove));
      
      // See if fields are same
      foreach ($a_tables_db as $table=>$data) {
         if (isset($a_tables_ref[$table])) {
            $fields_toremove = array_diff_assoc($data, $a_tables_ref[$table]);
            $fields_toadd = array_diff_assoc($a_tables_ref[$table], $data);
            if (count($fields_toadd) > 0 
                    && count($fields_toremove) > 0) {
               echo "======= DB ============== Ref =======> ".$table."\n";

               print_r($data);
               print_r($a_tables_ref[$table]);
            }
            
            // See tables missing or to delete
            $this->assertEquals(count($fields_toadd), 0, 'Fields missing/not good in '.$table.' '.print_r($fields_toadd));
            $this->assertEquals(count($fields_toremove), 0, 'Fields to delete in '.$table.' '.print_r($fields_toremove));
            
         }         
      }
 
      /*
       * Verify cron created
       */
      $crontask = new CronTask();
      $this->assertFalse($crontask->getFromDBbyName('PluginMonitoringServiceevent', 'updaterrd'), 
              'Cron updaterrd may be deleted');
      
      // TODO : test glpi_displaypreferences, rules, bookmark...
      
   }
}

require_once 'Install/AllTest.php';
require_once 'Update/AllTest.php';

?>