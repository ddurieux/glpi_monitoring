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
