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

class GLPIlogs extends PHPUnit_Framework_TestCase {

   public function testSQLlogs() {
      
      $filecontent = '';
      $filecontent = file_get_contents(GLPI_ROOT."/files/_log/sql-errors.log");
      
      $this->assertEquals($filecontent, '', 'sql-errors.log not empty');      
   }
   
   
   
   public function testPHPlogs() {
      
      $filecontent = '';
      $filecontent = file_get_contents(GLPI_ROOT."/files/_log/php-errors.log");
      
      $this->assertEquals($filecontent, '', 'php-errors.log not empty');      
   } 
   
}



class GLPIlogs_AllTests  {

   public static function suite() {
      
      $suite = new PHPUnit_Framework_TestSuite('GLPIlogs');
      return $suite;
   }
}
?>
