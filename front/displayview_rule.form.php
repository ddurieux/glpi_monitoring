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
   @co-author
   @comment
   @copyright Copyright (c) 2011-2016 Plugin Monitoring for GLPI team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      https://forge.indepnet.net/projects/monitoring/
   @since     2013

   ------------------------------------------------------------------------
 */

include ("../../../inc/includes.php");

Session::checkRight("plugin_monitoring_displayview", UPDATE);

Html::header(__('Monitoring', 'monitoring'),$_SERVER["PHP_SELF"], "plugins",
             "PluginMonitoringDashboard", "displayview");


$pmDisplayview_rule = new PluginMonitoringDisplayview_rule();
$pmDisplayview = new PluginMonitoringDisplayview();

if (isset($_GET['updaterule'])) {
   if (!isset($_GET['criteria'])
        AND !isset($_GET['reset'])) {
//      $_SESSION['plugin_monitoring_rules'] = $_POST;
   } else {
      $_POST = $_GET;
      $input = array();
      $pmDisplayview->getFromDB($_POST['plugin_monitoring_displayviews_id']);
      $input['id'] = $_POST['id'];
      $input['entities_id'] = $pmDisplayview->fields['entities_id'];
      $input['is_recursive'] = $pmDisplayview->fields['is_recursive'];
      $input['name'] = $_POST['name'];
      $input['itemtype'] = $_POST['itemtype'];
      $input['plugin_monitoring_displayviews_id'] = $_POST['plugin_monitoring_displayviews_id'];
      unset($_POST['entities_id']);
      unset($_POST['is_recursive']);
      unset($_POST['name']);
      unset($_POST['updaterule']);
      unset($_POST['itemtypen']);
      unset($_POST['plugin_monitoring_displayviews_id']);
      unset($_POST['id']);
      $input['condition'] = exportArrayToDB($_POST);
      $pmDisplayview_rule->update($input);
      unset($_SESSION['plugin_monitoring_rules']);
      unset($_SESSION["glpisearch"][$input['itemtype']]);
      $pmDisplayview_rule->getItemsDynamicly($pmDisplayview_rule);
      Html::redirect($CFG_GLPI['root_doc']."/plugins/monitoring/front/displayview.form.php?id=".$input['plugin_monitoring_displayviews_id']);
   }
} else if (isset($_GET['deleterule'])) {
   $_POST = $_GET;
   $pmDisplayview_rule->delete($_POST);
   Html::redirect($CFG_GLPI['root_doc']."/plugins/monitoring/front/displayview.form.php?id=".$_POST['plugin_monitoring_displayviews_id']);
} else if (isset($_POST['deleterule'])) {
   $pmDisplayview_rule->delete($_POST);
   Html::back();
} else if (isset($_POST['replayrules'])) {
   $a_rules = $pmDisplayview_rule->find("`plugin_monitoring_displayviews_id`='".$_POST['displayviews_id']."'");
   foreach ($a_rules as $data) {
      $pmDisplayview_rule->getFromDB($data['id']);
      $pmDisplayview_rule->getItemsDynamicly($pmDisplayview_rule);
   }
   Html::back();
} else if (isset($_GET['contains'])
        OR isset($_GET['reset'])) {
//   if (isset($_SESSION['plugin_monitoring_rules'])) {
//      unset($_SESSION['plugin_monitoring_rules']);
//   }
//   $_SESSION['plugin_monitoring_rules'] = $_POST;
//   $_SESSION['plugin_monitoring_rules_REQUEST_URI'] = $_SERVER['REQUEST_URI'];
   //Html::back();
} else if (isset($_GET['id'])
        AND !isset($_GET['itemtype'])) {

   $pmDisplayview_rule->getFromDB($_GET['id']);

   $val = importArrayFromDB($pmDisplayview_rule->fields['condition']);

   $params = Search::manageParams($pmDisplayview_rule->fields['itemtype'], $val);

   $url = str_replace("?id=".$_GET['id'], "", $_SERVER['REQUEST_URI']);
   $url .= "?".Toolbox::append_params($params);
   $url .= "&plugin_monitoring_displayviews_id=".$pmDisplayview_rule->fields['plugin_monitoring_displayviews_id'];
   $url .= "&name=".$pmDisplayview_rule->fields['name'];
   $url .= "&id=".$_GET['id'];

   Html::redirect($url);
}

if (isset($_POST['name'])) {
   $a_construct = array();
   foreach ($_POST as $key=>$value) {
      $a_construct[] = $key."=".$value;
   }
   $_SERVER['REQUEST_URI'] = $_SERVER['REQUEST_URI']."?".implode("&", $a_construct);
   Html::redirect($_SERVER['REQUEST_URI']);
}

$pmDisplayview_rule->addRule();

Html::footer();

?>