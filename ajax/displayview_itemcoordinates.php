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
   @since     2011
 
   ------------------------------------------------------------------------
 */

include ("../../../inc/includes.php");

header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

Session::checkCentralAccess();

//print_r($_POST);

if (isset($_POST['id'])
        AND isset($_POST['x'])
        AND isset($_POST['y'])) {
   
   $pmDisplayview_item = new PluginMonitoringDisplayview_item();
   if ($pmDisplayview_item->getFromDB($_POST['id'])) {
      if ($_POST['x'] < 1
              OR $_POST['y'] < 0) {
         $pmDisplayview_item->delete(array('id' => $_POST['id']));
      } else {
         $input = array();
         $input['id'] = $_POST['id'];
         $input['x'] = $_POST['x'];
         $input['y'] = $_POST['y'];
         $pmDisplayview_item->update($input);  
      }
   }
   
}


?>