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

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginMonitoringDisplayview_rule extends CommonDBTM {


   static $rightname = 'plugin_monitoring_displayview';

   /**
   * Get name of this type
   *
   *@return text name of this type by language of the user connected
   *
   **/
   static function getTypeName($nb=0) {
      return _n('Rule', 'Rules', $nb);
   }



   static function cronInfo($name){

      switch ($name) {
         case 'replayallviewrules':
            return array (
               'description' => __('Replay all views rules','monitoring'));
            break;
      }
      return array();
   }


   static function cronReplayallviewrules() {
      ini_set("max_execution_time", "0");

      $pmDisplayview_rule = new PluginMonitoringDisplayview_rule();
      $a_rules = $pmDisplayview_rule->find();
      foreach ($a_rules as $data) {
         $pmDisplayview_rule->getFromDB($data['id']);
         $pmDisplayview_rule->getItemsDynamicly($pmDisplayview_rule);
      }
      return true;
   }



   function addRulesTabs($displayviews_id, $tab) {
      $i = 20;
      $a_hosts = $this->find("`plugin_monitoring_displayviews_id`='".$displayviews_id."'"
              . " AND `type`='host'");

      foreach ($a_hosts as $data) {
         $tab[$i] = __('Host rule', 'monitoring').': '.__($data['itemtype']);
         $i++;
      }

      $a_resources = $this->find("`plugin_monitoring_displayviews_id`='".$displayviews_id."'"
              . " AND `type`='service'");

      foreach ($a_resources as $data) {
         $tab[$i] = __('Resource rule', 'monitoring').': '.__($data['itemtype']);
         $i++;
      }

      return $tab;
   }



   function ShowRulesTabs($displayviews_id, $tabnum) { // Verified
      global $CFG_GLPI;

      $i = 20;
      $id = 0;
      $a_hosts = $this->find("`plugin_monitoring_displayviews_id`='".$displayviews_id."'"
              . " AND `type`='host'");

      foreach ($a_hosts as $data) {
         if ($i == $tabnum) {
            $id = $data['id'];
         }
         $i++;
      }

      $a_resources = $this->find("`plugin_monitoring_displayviews_id`='".$displayviews_id."'"
              . " AND `type`='service'");

      foreach ($a_resources as $data) {
         if ($i == $tabnum) {
            $id = $data['id'];
         }
         $i++;
      }

      echo "<table class='tab_cadre_fixe' width='600'>";
      echo "<tr class='tab_bg_1'>";
      echo "<th align='center'>";
      echo __('Host rule', 'monitoring');
      echo "</th>";
      echo "</tr>";

      echo "<tr class='tab_bg_2'>";
      echo "<td class='center'>";
      echo "<br/><a href='".$CFG_GLPI['root_doc'].
              "/plugins/monitoring/front/displayview_rule.form.php?id=".$id."' class='vsubmit' >".
              __('Edit rule', 'monitoring')."</a><br/><br/>";
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td class='right'>";
      echo "<form method='post' action='".Toolbox::getItemTypeFormURL('PluginMonitoringDisplayview_rule')."'>";
      echo "<input type='hidden' name='id' value='".$id."' />";
      echo "<input type='submit' name='deleterule' value=\""._sx('button', 'Delete permanently')."\"
                         class='submit' ".
                         Html::addConfirmationOnAction(__('Confirm the final deletion?')).">";
      Html::closeForm();
      echo "</th>";
      echo "</tr>";
      echo "</table>";
   }



   function addRule() { // Verified
      global $CFG_GLPI;

      $params = Search::manageParams($_GET['itemtype'], $_GET);
      $params['showbookmark'] = false;
      $params['target'] = $CFG_GLPI['root_doc']."/plugins/monitoring/front/displayview_rule.form.php";
      $params['addhidden'] = array();
      $params['addhidden']['plugin_monitoring_displayviews_id'] = $_GET['plugin_monitoring_displayviews_id'];
      $params['addhidden']['name'] = $_GET['name'];
      if (isset($_GET['id'])) {
         $params['addhidden']['id'] = $_GET['id'];
      }

      ob_start();
      Search::showGenericSearch($_GET['itemtype'], $params);
      $form = ob_get_contents();
      ob_end_clean();
      if (isset($_GET['id'])) {
         $table = "<tr class='tab_bg_1'>"
                 . "<td align='center'>"
                 . "<input type='submit' name='updaterule' value=\"".__('Update this rule', 'monitoring')."\" class='submit' >"
                 . "</td>"
                 . "<td align='center'>"
                 . "<input type='submit' name='deleterule' value=\"".__('Delete this rule', 'monitoring')."\" class='submit' >"
                 . "</td>"
                 . "</tr>"
                 . "</table><input";
      } else {
         $table = "<tr class='tab_bg_1'>"
                 . "<td align='center' colspan='2'>"
                 . "<input type='submit' name='addrule' value=\"".__('Add this rule', 'monitoring')."\" class='submit' >"
                 . "</td>"
                 . "</tr>"
                 . "</table><input";
      }
      $form = str_replace("</table>\n<input", $table, $form);
      echo $form;

      echo "<br/>";
      echo "<table class='tab_cadre_fixe'>";

      echo "<tr>";
      echo "<th>";
      echo __('Preview', 'monitoring');
      echo "</th>";
      echo "</tr>";

      echo "<tr>";
      echo "<td>";

      $pmDisplayview = new PluginMonitoringDisplayview();
      $pmDisplayview->getFromDB($_GET['plugin_monitoring_displayviews_id']);

      $default_entity = 0;
      if (isset($_SESSION['glpiactive_entity'])) {
         $default_entity = $_SESSION['glpiactive_entity'];
      }
      $entities_isrecursive = 0;
      if (isset($_SESSION['glpiactiveentities'])
              AND count($_SESSION['glpiactiveentities']) > 1) {
         $entities_isrecursive = 1;
      }

      Session::changeActiveEntities($pmDisplayview->fields['entities_id'],
                           $pmDisplayview->fields['is_recursive']);

      Search::showList($_GET['itemtype'], $params);

      Session::changeActiveEntities($default_entity,
                           $entities_isrecursive);
      echo "</td>";
      echo "</tr>";
      echo "</table>";
   }



   /*
    * Use when add a rule, calculate for all items in GLPI DB
    */
   static function getItemsDynamicly($parm) {
      global $DB;

      $pmDisplayview_rule        = new PluginMonitoringDisplayview_rule();
      $pmDisplayview_item        = new PluginMonitoringDisplayview_item();
      $pmDisplayview             = new PluginMonitoringDisplayview();

      $devices_present = array();
      if ($pmDisplayview_rule->getFromDB($parm->fields['id'])) {
         if ($pmDisplayview->getFromDB($pmDisplayview_rule->fields['plugin_monitoring_displayviews_id'])) {
            // Load right entity

            $default_entity = 0;
            if (isset($_SESSION['glpiactive_entity'])) {
               $default_entity = $_SESSION['glpiactive_entity'];
            }
            $entities_isrecursive = 0;
            if (isset($_SESSION['glpiactiveentities'])
                    AND count($_SESSION['glpiactiveentities']) > 1) {
               $entities_isrecursive = 1;
            }
            if (!isset($_SESSION['glpiactiveprofile']['entities'])) {
               $_SESSION['glpiactiveprofile']['entities'] = array();
            }
            Session::changeActiveEntities($pmDisplayview->fields['entities_id'],
                                          $pmDisplayview->fields['is_recursive']);

            $itemtype = $pmDisplayview_rule->fields['itemtype'];
            $condition = importArrayFromDB($pmDisplayview_rule->fields['condition']);

            $params = Search::manageParams($itemtype, $condition);

            $queryd = "SELECT * FROM `glpi_plugin_monitoring_displayviews_items`
               WHERE `plugin_monitoring_displayviews_id`='".$pmDisplayview_rule->fields["plugin_monitoring_displayviews_id"]."'
                  AND `itemtype`='".$pmDisplayview_rule->fields['type']."'
                  AND `extra_infos`='".$itemtype."'";
            $result = $DB->query($queryd);
            while ($data=$DB->fetch_array($result)) {
               $devices_present[$data['items_id']] = $data['id'];
            }

            $data = Search::prepareDatasForSearch($itemtype, $params);
            $data['search']['export_all'] = true;
            Search::constructSQL($data);

            $DBread = DBConnection::getReadConnection();
            $DBread->query("SET SESSION group_concat_max_len = 16384;");
            $result = $DBread->query($data['sql']['search']);

            while ($data=$DB->fetch_array($result)) {
               if (!isset($devices_present[$data['id']])) {
                  // Verify this device has one or more resources
                  $query_h = "SELECT * FROM `glpi_plugin_monitoring_componentscatalogs_hosts`"
                          . " LEFT JOIN `glpi_plugin_monitoring_services`"
                          . "    ON `plugin_monitoring_componentscatalogs_hosts_id`="
                          . " `glpi_plugin_monitoring_componentscatalogs_hosts`.`id`"
                          . " WHERE `items_id`='".$data['id']."'"
                          . "    AND `itemtype`='".$pmDisplayview_rule->fields['itemtype']."'"
                          . "    AND `glpi_plugin_monitoring_services`.`id` IS NOT NULL";
                  $result_h = $DB->query($query_h);
                  if ($DB->numrows($result_h) > 0) {

                     $input = array();
                     $input['plugin_monitoring_displayviews_id'] = $pmDisplayview_rule->fields["plugin_monitoring_displayviews_id"];
                     $input['x'] = '1';
                     $input['y'] = '1';
                     $input['items_id'] = $data['id'];
                     $input['itemtype'] = $pmDisplayview_rule->fields['type'];
                     $input['extra_infos'] = $pmDisplayview_rule->fields['itemtype'];

                     $pmDisplayview_item->add($input);
                  }
               } else {
                  // Verify this device has one or more resources
                  $query_h = "SELECT * FROM `glpi_plugin_monitoring_componentscatalogs_hosts`"
                          . " LEFT JOIN `glpi_plugin_monitoring_services`"
                          . "    ON `plugin_monitoring_componentscatalogs_hosts_id`="
                          . " `glpi_plugin_monitoring_componentscatalogs_hosts`.`id`"
                          . " WHERE `items_id`='".$data['id']."'"
                          . "    AND `itemtype`='".$pmDisplayview_rule->fields['itemtype']."'"
                          . "    AND `glpi_plugin_monitoring_services`.`id` IS NOT NULL";
                  $result_h = $DB->query($query_h);
                  if ($DB->numrows($result_h) > 0) {
                     unset($devices_present[$data['id']]);
                  }
               }
            }

            // Reload current entity
               Session::changeActiveEntities($default_entity,
                                    $entities_isrecursive);
         } else {
            $pmDisplayview->delete(array('id' => $pmDisplayview_rule->fields['plugin_monitoring_displayviews_id']));
         }
      }
      foreach ($devices_present as $id) {
         $pmDisplayview_item->delete(array('id'=>$id));
      }
      return true;
   }



   function showReplayRulesForm($displayviews_id, $options=array()) {

      echo "<form method='post' action='".Toolbox::getItemTypeFormURL('PluginMonitoringDisplayview_rule')."'>";
      echo "<table class='tab_cadre_fixe'>";

      echo "<tr class='tab_bg_1'>";
      echo "<th>";
      echo __('Replay all rules', 'monitoring');
      echo "</th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td class='center'>";
      echo "<input type='hidden' name='displayviews_id' value='".$displayviews_id."' />";
      echo "<input type='submit' name='replayrules' value=\""._sx('button', 'Replay all rules', 'monitoring')."\"
                         class='submit'>";

      echo "</table>";
      Html::closeForm();
   }



   /*
    * Delete view items created by rule
    */
   function post_purgeItem() {
      global $DB;

      $query = "DELETE FROM `glpi_plugin_monitoring_displayviews_items`"
              . " WHERE `itemtype`='host'"
              . " AND `extra_infos`='".$this->fields['itemtype']."'"
              . " AND `plugin_monitoring_displayviews_id`='".$this->fields['plugin_monitoring_displayviews_id']."'";
      $DB->query($query);
   }

}

?>
