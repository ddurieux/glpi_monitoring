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

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginMonitoringDisplay extends CommonDBTM {
   static $ar_counterTypes;

   function menu() {
      global $CFG_GLPI;

      $redirect = FALSE;
      $a_url = array();

      if (PluginMonitoringProfile::haveRight("restartshinken", 'r')) {
         echo "<table class='tab_cadre_fixe' style='width: 120px; position: absolute; left: 15px;'>";
         echo "<tr class='tab_bg_1'>";
         echo "<td>";
         echo "<button><a href='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/restartshinken.form.php'>".__('Restart Shinken', 'monitoring')."</a></button>";
         echo "</td>";
         echo "</tr>";
         echo "</table>";
      }

/* Moke-up for user's preferences ...
      if (PluginMonitoringProfile::haveRight("preferences", 'r')) {
         echo "<table class='tab_cadre_fixe' style='width: 120px; position: absolute; right: 15px;'>";
         echo "<tr class='tab_bg_1'>";
         echo "<td>";
         echo "<button><a href='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/restartshinken.form.php'>".__('Restart Shinken', 'monitoring')."</a></button>";
         echo "</td>";
         echo "</tr>";
         echo "</table>";
      }
*/

      echo "<table class='tab_cadre_fixe' width='950'>";
      echo "<tr class='tab_bg_3'>";
      echo "<td>";

      if (PluginMonitoringProfile::haveRight("dashboard_system_status", 'r')
              || PluginMonitoringProfile::haveRight("dashboard_perfdata", 'r')
              || PluginMonitoringProfile::haveRight("dashboard_hosts_status", 'r')
              || PluginMonitoringProfile::haveRight("dashboard_services_catalogs", 'r')
              || PluginMonitoringProfile::haveRight("dashboard_components_catalogs", 'r')
              || PluginMonitoringProfile::haveRight("dashboard_all_ressources", 'r')) {
         echo "<table class='tab_cadre_fixe'>";
         echo "<tr class='tab_bg_1'>";
         if (PluginMonitoringProfile::haveRight("dashboard_system_status", 'r')) {
            echo "<th colspan='2'>";
            $this->displayPuce('display_system_status');
            echo "<a href='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/display_system_status.php'>";
            echo __('System status', 'monitoring');
            echo "</a>";
            $a_url[] = $CFG_GLPI['root_doc']."/plugins/monitoring/front/display_system_status.php";
            echo "</th>";
         } else {
            if (basename($_SERVER['PHP_SELF']) == 'display_system_status.php') {
               $redirect = TRUE;
            }
         }
         if (PluginMonitoringProfile::haveRight("dashboard_hosts_status", 'r')) {
            echo "<th colspan='2'>";
            $this->displayPuce('host');
            echo "<a href='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/host.php'>";
            echo __('Hosts status', 'monitoring');
            echo "</a>";
            $a_url[] = $CFG_GLPI['root_doc']."/plugins/monitoring/front/host.php";
            echo "</th>";
         } else {
            if (basename($_SERVER['PHP_SELF']) == 'host.php') {
               $redirect = TRUE;
            }
         }
         if (PluginMonitoringProfile::haveRight("dashboard_all_ressources", 'r')) {
            echo "<th colspan='2'>";
            $this->displayPuce('service');
            echo "<a href='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/service.php'>";
            echo __('All resources', 'monitoring');
            echo "</a>";
            $a_url[] = $CFG_GLPI['root_doc']."/plugins/monitoring/front/service.php";
            echo "</th>";
         } else {
            if (basename($_SERVER['PHP_SELF']) == 'service.php') {
               $redirect = TRUE;
            }
         }
         if (PluginMonitoringProfile::haveRight("dashboard_perfdata", 'r')) {
            echo "<th colspan='2'>";
            $this->displayPuce('perfdatas');
            echo "<a href='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/perfdatas.php'>";
            echo __('Performance data', 'monitoring');
            echo "</a>";
            $a_url[] = $CFG_GLPI['root_doc']."/plugins/monitoring/front/perfdatas.php";
            echo "</th>";
         } else {
            if (basename($_SERVER['PHP_SELF']) == 'perfdatas.php') {
               $redirect = TRUE;
            }
         }
         if (PluginMonitoringProfile::haveRight("dashboard_services_catalogs", 'r')) {
            echo "<th colspan='2'>";
            $this->displayPuce('display_servicescatalog');
            echo "<a href='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/display_servicescatalog.php'>";
            echo __('Services catalogs', 'monitoring');
            echo "</a>";
            $a_url[] = $CFG_GLPI['root_doc']."/plugins/monitoring/front/display_servicescatalog.php";
            echo "</th>";
         } else {
            if (basename($_SERVER['PHP_SELF']) == 'display_servicescatalog.php') {
               $redirect = TRUE;
            }
         }
         if (PluginMonitoringProfile::haveRight("dashboard_components_catalogs", 'r')) {
            echo "<th colspan='2'>";
            $this->displayPuce('display_componentscatalog');
            echo "<a href='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/display_componentscatalog.php'>";
            echo __('Components catalogs', 'monitoring');
            echo "</a>";
            $a_url[] = $CFG_GLPI['root_doc']."/plugins/monitoring/front/display_componentscatalog.php";
            echo "</th>";
         } else {
            if (basename($_SERVER['PHP_SELF']) == 'display_componentscatalog.php') {
               $redirect = TRUE;
            }
         }
         echo "</tr>";
         echo "</table>";
         if (PluginMonitoringProfile::haveRight("counters", 'r')) {
            echo "<table class='tab_cadre_fixe'>";
            echo "<tr class='tab_bg_1'>";
            echo "<th>";
            echo "<a href='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/hostdailycounter.php'>".__('Daily counters', 'monitoring')."</a>";
            echo "</th>";
            echo "</tr>";
            echo "</table>";
         }
         if (PluginMonitoringProfile::haveRight("acknowledge", 'r')
              || PluginMonitoringProfile::haveRight("downtime", 'r')) {
            echo "<table class='tab_cadre_fixe'>";
            echo "<tr class='tab_bg_1'>";
            echo "<th>";
            echo "<a href='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/downtime.php'>".__('Downtimes', 'monitoring')."</a>";
            echo "</th>";
            echo "<th>";
            echo "<a href='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/acknowledge.php'>".__('Acknowledges', 'monitoring')."</a>";
            echo "</th>";
            echo "</tr>";
            echo "</table>";
         }
      } else {
         if (basename($_SERVER['PHP_SELF']) == 'display_servicescatalog.php') {
            $redirect = TRUE;
         } else if (basename($_SERVER['PHP_SELF']) == 'display_componentscatalog.php') {
            $redirect = TRUE;
         } else if (basename($_SERVER['PHP_SELF']) == 'service.php') {
            $redirect = TRUE;
         } else if (basename($_SERVER['PHP_SELF']) == 'host.php') {
            $redirect = TRUE;
         }
      }

      if (PluginMonitoringProfile::haveRight("dashboard_views", 'r')) {
         $i = 1;
            $pmDisplayview = new PluginMonitoringDisplayview();
            $a_views = $pmDisplayview->getViews();
            if (count($a_views) > 0) {
               echo "<table class='tab_cadre_fixe' width='950'>";
               echo "<tr class='tab_bg_1'>";

               foreach ($a_views as $views_id=>$name) {
                  $pmDisplayview->getFromDB($views_id);
                  if ($pmDisplayview->haveVisibilityAccess()) {
                     if ($i == 6) {
                        echo "</tr>";
                        echo "<tr class='tab_bg_1'>";
                        $i = 1;
                     }
                     echo "<th width='20%'>";
                     $this->displayPuce('display_view', $views_id);
                     echo "<a href='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/display_view.php?id=".$views_id."'>";
                     echo htmlentities($name);
                     echo "</a>";
                     echo "</th>";
                     $i++;
                     $a_url[] = $CFG_GLPI['root_doc']."/plugins/monitoring/front/display_view.php?id=".$views_id;
                  }
               }
               // Fred : what is it for ?
               // It's to finish properly the table
               for ($i;$i < 6; $i++) {
                  echo "<td width='20%'>";
                  echo "</td>";
               }
               echo "</tr>";
               echo "</table>";
            }
      }

      if (PluginMonitoringProfile::haveRight("dashboard_sliders", 'r')) {
         $i = 1;
            $pmSlider = new PluginMonitoringSlider();
            $a_sliders = $pmSlider->getSliders();
            if (count($a_sliders) > 0) {
               echo "<table class='tab_cadre_fixe' width='950'>";
               echo "<tr class='tab_bg_1'>";

               foreach ($a_sliders as $sliders_id=>$name) {
                  $pmSlider->getFromDB($sliders_id);
                  if ($pmSlider->haveVisibilityAccess()) {
                     if ($i == 6) {
                        echo "</tr>";
                        echo "<tr class='tab_bg_1'>";
                        $i = 1;
                     }
                     echo "<th width='20%'>";
                     $this->displayPuce('slider', $sliders_id);
                     echo "<a href='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/display_slider.php?id=".$sliders_id."'>";
                     echo htmlentities($name);
                     echo "</a>";
                     echo "</th>";
                     $i++;
                     $a_url[] = $CFG_GLPI['root_doc']."/plugins/monitoring/front/display_slider.php?id=".$sliders_id;
                  }
               }
               for ($i;$i < 6; $i++) {
                  echo "<td width='20%'>";
                  echo "</td>";
               }
               echo "</tr>";
               echo "</table>";
            }
      }


      echo "</td>";
      echo "</tr>";
      echo "</table>";

      if ($redirect) {
         Html::redirect(array_shift($a_url));
      }
   }


   function defineTabs($options=array()){
      global $CFG_GLPI;

      Toolbox::logInFile("pm", "defineTabs ... no more use function ?\n");
      if (isset($_GET['glpi_tab'])) {
         Session::setActiveTab("PluginMonitoringDisplay",$_GET['glpi_tab']);
      }

      $pmDisplayview = new PluginMonitoringDisplayview();

      $ong = array();
      if (PluginMonitoringProfile::haveRight("dashboard_system_status", 'r')) {
         $ong[1] = __('System status', 'monitoring');
      }
      if (PluginMonitoringProfile::haveRight("dashboard_hosts_status", 'r')) {
         $ong[2] = __('Hosts status', 'monitoring');
      }
      if (PluginMonitoringProfile::haveRight("dashboard_services_catalogs", 'r')) {
         $ong[3] = __('Services catalog', 'monitoring');
      }
      if (PluginMonitoringProfile::haveRight("dashboard_components_catalogs", 'r')) {
         $ong[4] = __('Components catalog', 'monitoring');
      }
      if (PluginMonitoringProfile::haveRight("dashboard_all_ressources", 'r')) {
         $ong[5] = __('All resources', 'monitoring');
      }
      $ong[6] = __('Dependencies;', 'monitoring');
      if (PluginMonitoringProfile::haveRight("dashboard_views", 'r')) {
         $i = 7;
         $a_views = $pmDisplayview->getViews();
         foreach ($a_views as $name) {
            $ong[$i] = htmlentities($name);
            $i++;
         }
      }
      return $ong;
   }


   function showTabs($options=array()) {
      global $CFG_GLPI;

      Toolbox::logInFile("pm", "showTabs ... no more use function ?\n");
      // for objects not in table like central
      $ID = 0;
      if (isset($this->fields['id'])) {
         $ID = $this->fields['id'];
      }

      $target         = $_SERVER['PHP_SELF'];
      $extraparamhtml = "";
      $extraparam     = "";
      $withtemplate   = "";

      if (is_array($options) && count($options)) {
         if (isset($options['withtemplate'])) {
            $withtemplate = $options['withtemplate'];
         }
         foreach ($options as $key => $val) {
            $extraparamhtml .= "&amp;$key=$val";
            $extraparam     .= "&$key=$val";
         }
      }

      if (empty($withtemplate) && $ID && $this->getType() && $this->displaylist) {
         $glpilistitems =& $_SESSION['glpilistitems'][$this->getType()];
         $glpilisttitle =& $_SESSION['glpilisttitle'][$this->getType()];
         $glpilisturl   =& $_SESSION['glpilisturl'][$this->getType()];

         if (empty($glpilisturl)) {
            $glpilisturl = $this->getSearchURL();
         }

         echo "<div id='menu_navigate'>";

         $next = $prev = $first = $last = -1;
         $current = false;
         if (is_array($glpilistitems)) {
            $current = array_search($ID,$glpilistitems);
            if ($current !== false) {

               if (isset($glpilistitems[$current+1])) {
                  $next = $glpilistitems[$current+1];
               }

               if (isset($glpilistitems[$current-1])) {
                  $prev = $glpilistitems[$current-1];
               }

               $first = $glpilistitems[0];
               if ($first == $ID) {
                  $first = -1;
               }

               $last = $glpilistitems[count($glpilistitems)-1];
               if ($last == $ID) {
                  $last = -1;
               }

            }
         }
         $cleantarget = Html::cleanParametersURL($target);
         echo "<ul>";
         echo "<li><a href=\"javascript:showHideDiv('tabsbody','tabsbodyimg','".$CFG_GLPI["root_doc"].
                    "/pics/deplier_down.png','".$CFG_GLPI["root_doc"]."/pics/deplier_up.png')\">";
         echo "<img alt='' name='tabsbodyimg' src=\"".$CFG_GLPI["root_doc"]."/pics/deplier_up.png\">";
         echo "</a></li>";

         echo "<li><a href=\"".$glpilisturl."\">";

         if ($glpilisttitle) {
            if (Toolbox::strlen($glpilisttitle) > $_SESSION['glpidropdown_chars_limit']) {
               $glpilisttitle = Toolbox::substr($glpilisttitle, 0,
                                            $_SESSION['glpidropdown_chars_limit'])
                                . "&hellip;";
            }
            echo $glpilisttitle;

         } else {
            echo __('List');
         }
         echo "</a>&nbsp;:&nbsp;</li>";

         if ($first > 0) {
            echo "<li><a href='$cleantarget?id=$first$extraparamhtml'><img src='".
                       $CFG_GLPI["root_doc"]."/pics/first.png' alt=\"".__('First').
                       "\" title=\"".__('First')."\"></a></li>";
         } else {
            echo "<li><img src='".$CFG_GLPI["root_doc"]."/pics/first_off.png' alt=\"".
                       __('First')."\" title=\"".__('First')."\"></li>";
         }

         if ($prev > 0) {
            echo "<li><a href='$cleantarget?id=$prev$extraparamhtml'><img src='".
                       $CFG_GLPI["root_doc"]."/pics/left.png' alt=\"".__('Previous').
                       "\" title=\"".__('Previous')."\"></a></li>";
         } else {
            echo "<li><img src='".$CFG_GLPI["root_doc"]."/pics/left_off.png' alt=\"".
                       __('Previous')."\" title=\"".__('Previous')."\"></li>";
         }

         if ($current !== false) {
            echo "<li>".($current+1) . "/" . count($glpilistitems)."</li>";
         }

         if ($next > 0) {
            echo "<li><a href='$cleantarget?id=$next$extraparamhtml'><img src='".
                       $CFG_GLPI["root_doc"]."/pics/right.png' alt=\"".__('Next').
                       "\" title=\"".__('Next')."\"></a></li>";
         } else {
            echo "<li><img src='".$CFG_GLPI["root_doc"]."/pics/right_off.png' alt=\"".
                       __('Next')."\" title=\"".__('Next')."\"></li>";
         }

         if ($last > 0) {
            echo "<li><a href='$cleantarget?id=$last$extraparamhtml'><img src=\"".
                       $CFG_GLPI["root_doc"]."/pics/last.png\" alt=\"".__('Last').
                       "\" title=\"".__('Last')."\"></a></li>";
         } else {
            echo "<li><img src='".$CFG_GLPI["root_doc"]."/pics/last_off.png' alt=\"".
                       __('Last')."\" title=\"".__('Last')."\"></li>";
         }
         echo "</ul></div>";
         echo "<div class='sep'></div>";
      }

      echo "<div id='tabspanel' class='center-h'></div>";

      $onglets     = $this->defineTabs($options);
      $display_all = true;
      if (isset($onglets['no_all_tab'])) {
         $display_all = false;
         unset($onglets['no_all_tab']);
      }
      $class = $this->getType();
      if ($_SESSION['glpi_use_mode']==Session::DEBUG_MODE
          && ($ID > 0 || $this->showdebug)
          && (method_exists($class, 'showDebug')
              || in_array($class, $CFG_GLPI["infocom_types"])
              || in_array($class, $CFG_GLPI["reservation_types"]))) {

            $onglets[-2] = __('Debug');
      }

      if (count($onglets)) {
         $tabpage = $this->getTabsURL();
         $tabs    = array();

         foreach ($onglets as $key => $val ) {
            $tabs[$key] = array('title'  => $val,
                                'url'    => $tabpage,
                                'params' => "target=$target&itemtype=".$this->getType().
                                            "&glpi_tab=$key&id=$ID$extraparam");
         }

         $plug_tabs = Plugin::getTabs($target,$this, $withtemplate);
         $tabs += $plug_tabs;
         // Not all tab for templates and if only 1 tab
         if ($display_all && empty($withtemplate) && count($tabs)>1) {
            $tabs[-1] = array('title'  => __('All'),
                              'url'    => $tabpage,
                              'params' => "target=$target&itemtype=".$this->getType().
                                          "&glpi_tab=-1&id=$ID$extraparam");
         }
         Ajax::createTabs('tabspanel', 'tabcontent', $tabs, $this->getType(), "'100%'");
      }
   }


   /**
    * Display list of services
    */
   function showResourcesBoard($width='', $perfdatas=false) {
      global $DB,$CFG_GLPI;

      if (! isset($_SESSION['plugin_monitoring_reduced_interface'])) {
         $_SESSION['plugin_monitoring_reduced_interface'] = false;
      }

      if (! isset($_GET['order'])) {
         $_GET['order'] = "ASC";
      }
      if (! isset($_GET['sort'])) {
         $_GET['sort'] = "";
      }

      $order = "ASC";
      if (isset($_GET['order'])) {
         $order = $_GET['order'];
      }

      $where = '';
/*
      if ($limit == 'hosts') {
         $where = "`plugin_monitoring_services_id`='0' ";
      } else if ($limit == 'services') {
         $where = "`plugin_monitoring_services_id`>0 ";
      }
*/
      if (isset($_GET['field'])) {
         foreach ($_GET['field'] as $key=>$value) {
            $wheretmp = '';
            if (isset($_GET['link'][$key])) {
               $wheretmp.= " ".$_GET['link'][$key]." ";
            }
            $wheretmp .= Search::addWhere(
                                   "",
                                   0,
                                   "PluginMonitoringService",
                                   $_GET['field'][$key],
                                   $_GET['searchtype'][$key],
                                   $_GET['contains'][$key]);
            if (!strstr($wheretmp, "``.``")) {
               if ($where != ''
                       AND !isset($_GET['link'][$key])) {
                  $where .= " AND ";
               }
               $where .= $wheretmp;
            }
         }
      }
      if ($where != '') {
         $where = "(".$where;
         $where .= ") AND ";
      }
      $where .= ' `glpi_plugin_monitoring_services`.`entities_id` IN ('.$_SESSION['glpiactiveentities_string'].')';

      if ($where != '') {
         $where = " WHERE ".$where;
         $where = str_replace("`".getTableForItemType("PluginMonitoringDisplay")."`.",
                 "", $where);

      }

      $leftjoin = "
         INNER JOIN `glpi_plugin_monitoring_services`
            ON (`glpi_plugin_monitoring_services`.`plugin_monitoring_componentscatalogs_hosts_id` = `glpi_plugin_monitoring_componentscatalogs_hosts`.`id`)
         INNER JOIN `glpi_plugin_monitoring_hosts`
            ON `glpi_plugin_monitoring_componentscatalogs_hosts`.`items_id` = `glpi_plugin_monitoring_hosts`.`items_id`
            AND `glpi_plugin_monitoring_componentscatalogs_hosts`.`itemtype` = `glpi_plugin_monitoring_hosts`.`itemtype`
         INNER JOIN `glpi_plugin_monitoring_componentscatalogs`
            ON `plugin_monitoring_componentscalalog_id` = `glpi_plugin_monitoring_componentscatalogs`.`id`
         INNER JOIN `glpi_plugin_monitoring_components`
            ON (`glpi_plugin_monitoring_services`.`plugin_monitoring_components_id` = `glpi_plugin_monitoring_components`.`id`)
         INNER JOIN `glpi_entities`
            ON (`glpi_plugin_monitoring_services`.`entities_id` = `glpi_entities`.`id`)
      ";

      $leftjoin .= " LEFT JOIN `glpi_computers`
         ON `glpi_plugin_monitoring_componentscatalogs_hosts`.`items_id` =
               `glpi_computers`.`id`
               AND `glpi_plugin_monitoring_componentscatalogs_hosts`.`itemtype`='Computer'";
      $leftjoin .= " LEFT JOIN `glpi_printers`
         ON `glpi_plugin_monitoring_componentscatalogs_hosts`.`items_id` =
               `glpi_printers`.`id`
               AND `glpi_plugin_monitoring_componentscatalogs_hosts`.`itemtype`='Printer'";
      $leftjoin .= " LEFT JOIN `glpi_networkequipments`
         ON `glpi_plugin_monitoring_componentscatalogs_hosts`.`items_id` =
               `glpi_networkequipments`.`id`
               AND `glpi_plugin_monitoring_componentscatalogs_hosts`.`itemtype`='NetworkEquipment'";


      // * ORDER
      $ORDERQUERY = "ORDER BY `glpi_plugin_monitoring_services`.`name` ASC";
      $toview = array(1, 2, 3, 4, 5);
      $toviewComplete = array(
          'ITEM_0' => 'host_name',
          'ITEM_1' => 'component_name',
          'ITEM_2' => 'state',
          'ITEM_3' => 'last_check',
          'ITEM_4' => 'event'
      );
      foreach ($toview as $key => $val) {
         if ($_GET['sort']==$val) {
            $ORDERQUERY = Search::addOrderBy("PluginMonitoringService", $_GET['sort'],
                                             $_GET['order'], $key);
            foreach ($toviewComplete as $keyi=>$vali) {
               $ORDERQUERY= str_replace($keyi, $vali, $ORDERQUERY);
            }
         }
      }

      $query = "SELECT
         `glpi_plugin_monitoring_services`.*,
         CONCAT_WS('', `glpi_computers`.`name`, `glpi_printers`.`name`, `glpi_networkequipments`.`name`) AS host_name,
         `glpi_plugin_monitoring_componentscatalogs_hosts`.`itemtype`,
         `glpi_plugin_monitoring_componentscatalogs_hosts`.`items_id`,
         `glpi_plugin_monitoring_hosts`.`id` AS host_id, `glpi_plugin_monitoring_hosts`.`state` AS host_state, `glpi_plugin_monitoring_hosts`.`is_acknowledged` AS host_acknowledged,
         `glpi_plugin_monitoring_components`.`id` AS component_id, `glpi_plugin_monitoring_components`.`name` AS component_name
         FROM `glpi_plugin_monitoring_componentscatalogs_hosts`
         ".$leftjoin."
         ".$where."
         ".$ORDERQUERY;
      // Toolbox::logInFile("pm", "Query services - $query\n");
      $result = $DB->query($query);

      $start = 0;
      if (isset($_GET["start"])) {
         $start = $_GET["start"];
      }

      $numrows = $DB->numrows($result);
      $parameters = '';

      $globallinkto = Search::getArrayUrlLink("field",$_GET['field']).
                Search::getArrayUrlLink("link",$_GET['link']).
                Search::getArrayUrlLink("contains",$_GET['contains']).
                Search::getArrayUrlLink("searchtype",$_GET['searchtype']).
                Search::getArrayUrlLink("field2",$_GET['field2']).
                Search::getArrayUrlLink("contains2",$_GET['contains2']).
                Search::getArrayUrlLink("itemtype2",$_GET['itemtype2']).
                Search::getArrayUrlLink("searchtype2",$_GET['searchtype2']).
                Search::getArrayUrlLink("link2",$_GET['link2']);

      $parameters = "sort=".$_GET['sort']."&amp;order=".$_GET['order'].$globallinkto;
      Html::printPager($start, $numrows, $CFG_GLPI['root_doc']."/plugins/monitoring/front/service.php", $parameters);

      $limit = $numrows;
      if ($_SESSION["glpilist_limit"] < $numrows) {
         $limit = $_SESSION["glpilist_limit"];
      }
      $query .= " LIMIT ".intval($start)."," . intval($_SESSION['glpilist_limit']);

      // Fred : on repose la requête sur la base une 2ème fois ... ?
      // Toolbox::logInFile("pm", "query services - $query\n");
      $result = $DB->query($query);


      // Pour la génération des graphes ...
      echo '<div id="custom_date" style="display:none"></div>';
      echo '<div id="custom_time" style="display:none"></div>';

      echo "<br/>";
      if ($perfdatas) {
         echo "<table class='tab_cadrehov' style='width:100%;'>
            <tr class='tab_bg_1'><th colspan='2' class='left'>".__('Global counters', 'monitoring')."</th></tr>
            <tr class='tab_bg_3' id='global_counters'></tr>
            </table>";
         echo "<br/>";
      }
      if ($width == '') {
         echo "<table class='tab_cadrehov' style='width:100%;'>";
      } else {
         echo "<table class='tab_cadrehov' style='width:".$width."px;'>";
      }
      $num = 0;

      echo "<tr class='tab_bg_1'>";
      if (! $_SESSION['plugin_monitoring_reduced_interface']) {
         // echo Search::showHeaderItem(0, __('Show counters', 'monitoring'), $num);
         echo Search::showHeaderItem(0, __('Show graphics', 'monitoring'), $num);
      }
      $this->showHeaderItem(__('Host name', 'monitoring'), 1, $num, $start, $globallinkto, 'service.php', 'PluginMonitoringService');
      $this->showHeaderItem(__('Component', 'monitoring'), 2, $num, $start, $globallinkto, 'service.php', 'PluginMonitoringService');
      if (! $perfdatas) {
         $this->showHeaderItem(__('Resource state', 'monitoring'), 3, $num, $start, $globallinkto, 'service.php', 'PluginMonitoringService');
         $this->showHeaderItem(__('Last check', 'monitoring'), 4, $num, $start, $globallinkto, 'service.php', 'PluginMonitoringService');
         echo Search::showHeaderItem(0, __('Result details', 'monitoring'), $num);
         echo Search::showHeaderItem(0, __('Check period', 'monitoring'), $num);
         if (PluginMonitoringProfile::haveRight("acknowledge", 'r')) {
            echo Search::showHeaderItem(0, __('Acknowledge', 'monitoring'), $num);
         }
      }
      echo "</tr>";

      PluginMonitoringDisplay::$ar_counterTypes = array();
      PluginMonitoringToolbox::loadLib();
      while ($data=$DB->fetch_array($result)) {
         // Reduced array or not ?
         if ($_SESSION['plugin_monitoring_reduced_interface'] and $data['state'] == 'OK') continue;

         echo "<tr class='tab_bg_3'>";
         $this->displayLine($data, 1, $perfdatas);
         echo "</tr>";
      }
      echo "</table>";
      echo "<br/>";
      Html::printPager($_GET['start'], $numrows, $CFG_GLPI['root_doc']."/plugins/monitoring/front/service.php", $parameters);

      if ($perfdatas) {
         // foreach(PluginMonitoringDisplay::$ar_counterTypes as $counter_id => $counter_name) {
            // Toolbox::logInFile("pm", "Counter type +++ : $counter_id => $counter_name\n");
         // }
         echo "<script>
            Ext.onReady(function(){
               // console.log('Ready ...');
               window.setTimeout(function(){";
            foreach(PluginMonitoringDisplay::$ar_counterTypes as $counter_id => $counter_name) {
echo "
                  var global = Ext.get('#global_counter_".$counter_id."');
                  if (! global) {
                     var html = \"<th id='global_counter_".$counter_id."' counterType ='".$counter_id."' class='global_counter'>\";
                     html += \"<span class='global_counter_name'>".$counter_name."</span>\";
                     html += \"<span>&nbsp;:&nbsp;</span>\";
                     html += \"<span class='global_counter_value'>0</span>\";
                     html += \"</th>\";
                     Ext.select('#global_counters').createChild(html);
                     console.log('Created an element for global \'".$counter_id." / ".$counter_name."\' counter.');
                  }
";
            }

echo "
               }, 100);
               window.setInterval(function(){
                  Ext.select('.global_counter').each(function(el) {
                     var counterType = el.getAttribute('counterType');
                     // console.log('Global counter for '+counterType+' exists.');

                     el.select('.global_counter_value').each(function(elGlobalValue) {
                        elGlobalValue.update('0');
                        // console.log('Global counter value is : '+parseFloat(elGlobalValue.dom.innerHTML));

                        var select = 'td[counter=\'' + counterType + '\'][counterType=\'difference\']';
                        Ext.select(select).each(function(el) {
                           // console.log('Local counter is ' + el.getAttribute('counter') + ', value is : '+parseFloat(el.dom.innerHTML));
                           var newCounter = parseFloat(elGlobalValue.dom.innerHTML) + parseFloat(el.dom.innerHTML);
                           elGlobalValue.update(newCounter.toString());
                        });
                     });
                  });
               }, 1000);
            });
         </script>";
      }
   }


   /**
    * Display list of hosts
    */
   function showHostsBoard($width='', $limit='') {
      global $DB,$CFG_GLPI;

      if (! isset($_SESSION['plugin_monitoring_reduced_interface'])) {
         $_SESSION['plugin_monitoring_reduced_interface'] = false;
      }

      if (! isset($_GET['order'])) {
         $_GET['order'] = "ASC";
      }
      if (! isset($_GET['sort'])) {
         $_GET['sort'] = "";
      }

      $order = "ASC";
      if (isset($_GET['order'])) {
         $order = $_GET['order'];
      }
      $where = '';
      if (isset($_GET['field'])) {
         foreach ($_GET['field'] as $key=>$value) {
            $wheretmp = '';
            if (isset($_GET['link'][$key])) {
               $wheretmp.= " ".$_GET['link'][$key]." ";
            }
            $wheretmp .= Search::addWhere(
                                   "",
                                   0,
                                   "PluginMonitoringHost",
                                   $_GET['field'][$key],
                                   $_GET['searchtype'][$key],
                                   $_GET['contains'][$key]);
            if (!strstr($wheretmp, "``.``")) {
               if ($where != ''
                       AND !isset($_GET['link'][$key])) {
                  $where .= " AND ";
               }
               $where .= $wheretmp;
            }
         }
      }
      if ($where != '') {
         $where = "(".$where;
         $where .= ") AND ";
      }
      $where .= " CONCAT_WS('', `glpi_computers`.`entities_id`, `glpi_printers`.`entities_id`, `glpi_networkequipments`.`entities_id`) IN (".$_SESSION['glpiactiveentities_string'].")";

      if ($where != '') {
         $where = " WHERE ".$where;
         $where = str_replace("`".getTableForItemType("PluginMonitoringDisplay")."`.",
                 "", $where);

      }

      $leftjoin = "
         LEFT JOIN `glpi_computers`
            ON `glpi_plugin_monitoring_hosts`.`items_id` = `glpi_computers`.`id`
               AND `glpi_plugin_monitoring_hosts`.`itemtype`='Computer'
         LEFT JOIN `glpi_printers`
            ON `glpi_plugin_monitoring_hosts`.`items_id` = `glpi_printers`.`id`
               AND `glpi_plugin_monitoring_hosts`.`itemtype`='Printer'
         LEFT JOIN `glpi_networkequipments`
            ON `glpi_plugin_monitoring_hosts`.`items_id` = `glpi_networkequipments`.`id`
               AND `glpi_plugin_monitoring_hosts`.`itemtype`='NetworkEquipment'
         LEFT JOIN `glpi_entities`
            ON CONCAT_WS('', `glpi_computers`.`entities_id`, `glpi_printers`.`entities_id`, `glpi_networkequipments`.`entities_id`) = `glpi_entities`.`id`

      ";

      // * ORDER
      $ORDERQUERY = "ORDER BY entity_name ASC, host_name ASC";
      $toview = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9);
      $toviewComplete = array(
          'ITEM_0' => 'entity_name',
          'ITEM_1' => 'host_name',
          'ITEM_2' => 'host_state',
          'ITEM_3' => 'service_state',
          'ITEM_4' => 'last_check',
          'ITEM_5' => 'event',
          'ITEM_6' => 'perf_data',
          'ITEM_7' => 'is_acknowledged'
      );
      foreach ($toview as $key => $val) {
         if ($_GET['sort']==$val) {
            $ORDERQUERY = Search::addOrderBy("PluginMonitoringHost", $_GET['sort'],
                                             $_GET['order'], $key);
            foreach ($toviewComplete as $keyi=>$vali) {
               $ORDERQUERY= str_replace($keyi, $vali, $ORDERQUERY);
            }
         }
      }

//            `glpi_computers`.*

      $query = "SELECT
            `glpi_entities`.`name` AS entity_name,
            CONCAT_WS('', `glpi_computers`.`id`, `glpi_printers`.`id`, `glpi_networkequipments`.`id`) AS idComputer,
            CONCAT_WS('', `glpi_computers`.`name`, `glpi_printers`.`name`, `glpi_networkequipments`.`name`) AS host_name,
            `glpi_plugin_monitoring_hosts`.*,
            `glpi_plugin_monitoring_hosts`.`state` AS host_state,
            `glpi_plugin_monitoring_hosts`.`is_acknowledged` AS host_acknowledged
         FROM `glpi_plugin_monitoring_hosts`
         ".$leftjoin."
         ".$where."
         ".$ORDERQUERY;
      // Toolbox::logInFile("pm", "Query hosts - $query\n");

      $result = $DB->query($query);

      if (! isset($_GET["start"])) {
         $_GET["start"]=0;
      }
      $start=$_GET['start'];
      if (! isset($_GET["order"])) {
         $_GET["order"]="ASC";
      }

      $numrows = $DB->numrows($result);
      $parameters = '';

      $globallinkto = '';

      $parameters = "sort=".$_GET['sort']."&amp;order=".$_GET['order'].$globallinkto;
      Html::printPager($_GET['start'], $numrows, $CFG_GLPI['root_doc']."/plugins/monitoring/front/host.php", $parameters);

      $limit = $numrows;
      if ($_SESSION["glpilist_limit"] < $numrows) {
         $limit = $_SESSION["glpilist_limit"];
      }
      $query .= " LIMIT ".intval($start)."," . intval($_SESSION['glpilist_limit']);

      // Toolbox::logInFile("pm", "Query hosts - $query\n");
      $result = $DB->query($query);

      echo '<div id="custom_date" style="display:none"></div>';
      echo '<div id="custom_time" style="display:none"></div>';

      if ($width == '') {
         echo "<table class='tab_cadrehov' style='width:100%;'>";
      } else {
         echo "<table class='tab_cadrehov' style='width:".$width."px;'>";
      }
      $num = 0;

      if (PluginMonitoringProfile::haveRight("host_command", 'r')) {
         // Host test command ...
         $pmCommand = new PluginMonitoringCommand();
         $a_commands = array();
         $a_list = $pmCommand->find("command_name LIKE 'host_action'");
         foreach ($a_list as $data) {
            $host_command_name = $data['name'];
            $host_command_command = $data['command_line'];
         }
      }

      echo "<tr class='tab_bg_1'>";
      $this->showHeaderItem(__('Daily counters', 'monitoring'), 0, $num, $start, $globallinkto, 'host.php', 'PluginMonitoringHost');
      $this->showHeaderItem(__('Entity'), 0, $num, $start, $globallinkto, 'host.php', 'PluginMonitoringHost');
      $this->showHeaderItem(__('Type'), 0, $num, $start, $globallinkto, 'host.php', 'PluginMonitoringHost');
      $this->showHeaderItem(__('Host', 'monitoring'), 1, $num, $start, $globallinkto, 'host.php', 'PluginMonitoringHost');
      $this->showHeaderItem(__('Host state'), 2, $num, $start, $globallinkto, 'host.php', 'PluginMonitoringHost');
      if (isset($host_command_name)) {
         echo '<th>'.__('Host action', 'monitoring').'</th>';
      }
      echo '<th>'.__('Host resources state', 'monitoring').'</th>';
      echo '<th>'.__('IP address', 'monitoring').'</th>';
      $this->showHeaderItem(__('Last check', 'monitoring'), 4, $num, $start, $globallinkto, 'host.php', 'PluginMonitoringHost');
      $this->showHeaderItem(__('Result details', 'monitoring'), 5, $num, $start, $globallinkto, 'host.php', 'PluginMonitoringHost');
      $this->showHeaderItem(__('Performance data', 'monitoring'), 6, $num, $start, $globallinkto, 'host.php', 'PluginMonitoringHost');
      if (PluginMonitoringProfile::haveRight("acknowledge", 'r')
         || PluginMonitoringProfile::haveRight("downtime", 'r')) {
         $this->showHeaderItem(__('Maintenance', 'monitoring'), 7, $num, $start, $globallinkto, 'host.php', 'PluginMonitoringHost');
      }
      echo "</tr>";

      while ($data=$DB->fetch_array($result)) {
         // Reduced array or not ?
         if ($_SESSION['plugin_monitoring_reduced_interface'] and $data['state'] == 'UP') continue;

         if (isset($host_command_name)) {
            $data['host_command_name'] = $host_command_name;
            $data['host_command_command'] = $host_command_command;
         }

         // Get all host services except if state is ok or is already acknowledged ...
         $a_ret = PluginMonitoringHost::getServicesState($data['id'],
                                                         "`glpi_plugin_monitoring_services`.`state` != 'OK'
                                                         AND `glpi_plugin_monitoring_services`.`is_acknowledged` = '0'");
         $data['host_services_state'] = $a_ret[0];
         $data['host_services_state_list'] = $a_ret[1];

         // Get host first IP address
         $data['ip'] = __('Unknown IP address', 'monitoring');
         $ip = PluginMonitoringHostaddress::getIp($data['items_id'], $data['itemtype'], '');
         if ($ip != '') {
            $data['ip'] = $ip;
         }
         echo "<tr class='tab_bg_3'>";
         $this->displayHostLine($data);
         echo "</tr>";
      }
      echo "</table>";
      echo "<br/>";
      Html::printPager($start, $numrows, $CFG_GLPI['root_doc']."/plugins/monitoring/front/host.php", $parameters);
   }



   /**
    * Manage header of list
    */
   function showHeaderItem($title, $numoption, &$num, $start, $globallinkto, $page, $itemtype) {
      global $CFG_GLPI;

      $order = "ASC";
      if (isset($_GET["order"])) {
         $order = $_GET["order"];
      }

      $linkto = $CFG_GLPI['root_doc']."/plugins/monitoring/front/$page?".
              "itemtype=$itemtype&amp;sort=".$numoption."&amp;order=".
                ($order=="ASC"?"DESC":"ASC")."&amp;start=".$start.
                $globallinkto;
      $issort = false;
      if (isset($_GET['sort']) && $_GET['sort'] == $numoption) {
         $issort = true;
      }
      echo Search::showHeaderItem(0, $title, $num, $linkto, $issort, $order);
   }



   static function displayLine($data, $displayhost=1, $displayCounters=0, $displayGraphs=true) {
      global $DB,$CFG_GLPI;

      $pMonitoringService = new PluginMonitoringService();
      $pMonitoringService->getFromDB($data['id']);
      $pMonitoringComponent = new PluginMonitoringComponent();
      $pMonitoringComponent->getFromDB($data['plugin_monitoring_components_id']);

      $networkPort = new NetworkPort();

      // If host is acknowledged, force service to be displayed as unknown acknowledged.
      if (isset($data['host_acknowledged']) && $data['host_acknowledged']) {
         $shortstate = 'yellowblue';
         $data['state'] = 'UNKNOWN';
      } else {
         $shortstate = PluginMonitoringHost::getState($data['state'],
                                      $data['state_type'],
                                      $data['event'],
                                      $pMonitoringService->isCurrentlyAcknowledged());
      }

      $timezone = '0';
      if (isset($_SESSION['plugin_monitoring_timezone'])) {
         $timezone = $_SESSION['plugin_monitoring_timezone'];
      }

      if ($displayGraphs) {
         if (!isset($_SESSION['plugin_monitoring_reduced_interface'])
                 || !$_SESSION['plugin_monitoring_reduced_interface']) {
            // echo "<td class='center'>";
            // if ($pMonitoringComponent->hasCounters()) {
               // $pmServicegraph = new PluginMonitoringServicegraph();
               // $html = $pmServicegraph->displayCounter($pMonitoringComponent->fields['graph_template'], $data['id']);
               // $counters = "<table width='600' class='tab_cadre'><tr><td>".$html."</td></tr></table>";
               // Html::showToolTip($counters, array(
                  // 'img'    => $CFG_GLPI['root_doc']."/plugins/monitoring/pics/stats_32.png"
               // ));
            // }
            // echo "</td>";

            echo "<td class='center'>";
            // Even if not exist incremental perfdata ...
            if ($pMonitoringComponent->hasPerfdata()) {
               echo "<a href='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/display.form.php?itemtype=PluginMonitoringService&items_id=".$data['id']."'>";
               ob_start();
               $pmServicegraph = new PluginMonitoringServicegraph();
               $pmServicegraph->displayGraph($pMonitoringComponent->fields['graph_template'],
                                             "PluginMonitoringService",
                                             $data['id'],
                                             "0",
                                             '2h',
                                             "div",
                                             "600");
               $div = ob_get_contents();
               ob_end_clean();
               $chart = "<table width='600' class='tab_cadre'><tr><td>".$div."</td></tr></table>";
               Html::showToolTip($chart, array('img'=>$CFG_GLPI['root_doc']."/plugins/monitoring/pics/stats_32.png"));
               $pmServicegraph->displayGraph($pMonitoringComponent->fields['graph_template'],
                                             "PluginMonitoringService",
                                             $data['id'],
                                             "0",
                                             '2h',
                                             "js");
               echo "</a>";
            }
            echo "</td>";
         }
      }

      if ($displayhost) {
         $pmComponentscatalog_Host = new PluginMonitoringComponentscatalog_Host();
         $pmComponentscatalog_Host->getFromDB($data["plugin_monitoring_componentscatalogs_hosts_id"]);
         if (isset($pmComponentscatalog_Host->fields['itemtype'])
                 AND $pmComponentscatalog_Host->fields['itemtype'] != '') {

            echo "<td>";

            $itemtype = $pmComponentscatalog_Host->fields['itemtype'];
            $item = new $itemtype();
            $item->getFromDB($pmComponentscatalog_Host->fields['items_id']);

            // echo "<span>".$item->getLink(array ("monitoring" => "1"))."</span>&nbsp;";
            if (!is_null($pMonitoringService->fields['networkports_id'])
                    AND $pMonitoringService->fields['networkports_id'] > 0) {
               $networkPort->getFromDB($pMonitoringService->fields['networkports_id']);
               echo " [".$networkPort->getLink()."]";
            }
            $pm_Host = new PluginMonitoringHost();
            $pm_Host->getFromDB($pMonitoringService->getHostID());
            echo "<span>".$pm_Host->getLink(array ("monitoring" => "1"))."</span>";
            echo "</td>";

         } else {
            echo "<td>".__('Resources', 'monitoring')."</td>";
         }
      }

      echo "<td>";
      if (PluginMonitoringProfile::haveRight("config", 'r')) {
         echo $pMonitoringComponent->getLink();
      } else {
         echo $pMonitoringComponent->getName();
      }
      if (!is_null($pMonitoringService->fields['networkports_id'])
              AND $pMonitoringService->fields['networkports_id'] > 0) {
         $networkPort->getFromDB($pMonitoringService->fields['networkports_id']);
         echo " [".$networkPort->getLink()."]";
      }
      echo "</td>";

      if ($displayCounters) {
         $ar_counters = $pMonitoringComponent->hasCounters();
         // Toolbox::logInFile("pm", "Counters : ".serialize($ar_counters)."\n");
         if (is_array($ar_counters)) {
            $pmServicegraph = new PluginMonitoringServicegraph();
            foreach($ar_counters as $counter => $counter_title) {
               PluginMonitoringDisplay::$ar_counterTypes[$counter] = $counter_title;
               $html = $pmServicegraph->displayCounter($pMonitoringComponent->fields['graph_template'], $data['id'], false, $counter, $counter_title);
               echo "<td class='center'>$html</td>";
            }
         }
      } else {
         echo "<td class='center page foldtl resource".$data['state']." resource".$data['state_type']."'>";
         echo "<div class=''>";
         echo "<div>";
         echo $data['state'];
         echo "</div>";
         echo "</div>";
         echo "</td>";

         echo "<td>";
         echo Html::convDate($data['last_check']).' '. substr($data['last_check'], 11, 8);
         echo "</td>";

         echo "<td>";
         echo $data['event'];
         echo "</td>";

         echo "<td align='center'>";
         $segments = CalendarSegment::getSegmentsBetween($pMonitoringComponent->fields['calendars_id'],
                 date('w', date('U')), date('H:i:s'),
                 date('w', date('U')), date('H:i:s'));
         if (count($segments) == '0') {
            echo "<img src='".$CFG_GLPI['root_doc']."/plugins/monitoring/pics/service_pause.png' />";
         } else {
            echo "<img src='".$CFG_GLPI['root_doc']."/plugins/monitoring/pics/service_run.png' />";
         }
         echo "</td>";

         if (! $displayhost) {
            $pmUnavailability = new PluginMonitoringUnavailability();
            $pmUnavailability->displayValues($pMonitoringService->fields['id'], 'currentmonth', 1);
            $pmUnavailability->displayValues($pMonitoringService->fields['id'], 'lastmonth', 1);
            $pmUnavailability->displayValues($pMonitoringService->fields['id'], 'currentyear', 1);

            echo "<td class='center'>";
            echo "<a href='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/unavailability.php?".
                    "field[0]=2&searchtype[0]=equals&contains[0]=".$pMonitoringService->fields['id'].
                    "&sort=3&order=DESC&itemtype=PluginMonitoringUnavailability'>
               <img src='".$CFG_GLPI['root_doc']."/plugins/monitoring/pics/info.png'/></a>";
            echo "</td>";
         }

         if (PluginMonitoringProfile::haveRight("acknowledge", 'r')) {
            echo "<td>";
            if ($pMonitoringService->isCurrentlyAcknowledged()) {
               if (PluginMonitoringProfile::haveRight("acknowledge", 'w')) {
                  echo "<span>";
                  echo "<a href='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/acknowledge.form.php?itemtype=Service&items_id=".$data['id']."'>"
                           ."<img src='".$CFG_GLPI['root_doc']."/plugins/monitoring/pics/acknowledge_ok.png'"
                          ." alt='".htmlspecialchars(__('Modify acknowledge comment for the service', 'monitoring'), ENT_QUOTES)."'"
                          ." title='".htmlspecialchars(__('Modify acknowledge comment for the service', 'monitoring'), ENT_QUOTES)."'/>"
                       ."</a>";
                  echo "&nbsp;&nbsp;</span>";
               } else {
                  echo "<span>";
                  echo "<img src='".$CFG_GLPI['root_doc']."/plugins/monitoring/pics/acknowledge_ok.png'"
                          ." alt='".htmlspecialchars(__('Service problem has been acknowledged', 'monitoring'), ENT_QUOTES)."'"
                          ." title='".htmlspecialchars(__('Service problem has been acknowledged', 'monitoring'), ENT_QUOTES)."'/>";
                  echo "&nbsp;&nbsp;</span>";
               }
               // Display acknowledge data ...
               $pMonitoringService->getAcknowledge();
            } else if ($shortstate == 'red'
                    || $shortstate == 'yellow'
                    || $shortstate == 'orange'
                    || !empty($data['host_services_state_list'])) {
               if (PluginMonitoringProfile::haveRight("acknowledge", 'w')) {
                  echo "<span>";
                  echo "<a href='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/acknowledge.form.php?itemtype=Service&items_id=".$data['id']."'>"
                           ."<img src='".$CFG_GLPI['root_doc']."/plugins/monitoring/pics/acknowledge_ko.png'"
                          ." alt='".htmlspecialchars(__('Add an acknowledge for the service', 'monitoring'), ENT_QUOTES)."'"
                          ." title='".htmlspecialchars(__('Add an acknowledge for the service', 'monitoring'), ENT_QUOTES)."'/>"
                       ."</a>";
                  echo "&nbsp;&nbsp;</span>";
               }
            }
            echo "</td>";
         }
      }

      if ($displayhost == '0') {
         echo "<td>";
         if (PluginMonitoringProfile::haveRight("config_components_catalogs", 'w')) {

            $a_arg = importArrayFromDB($pMonitoringService->fields['arguments']);
            $cnt = '';
            if (count($a_arg) > 0) {
               $cnt = " (".count($a_arg).")";
            }
            echo "<a href='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/servicearg.form.php?id=".$data['id']."'>".
                    __('Configure', 'monitoring').$cnt."</a>";
         }
         echo "</td>";
      }
   }



   static function displayHostLine($data) {
      global $DB,$CFG_GLPI;

      $pm_Host = new PluginMonitoringHost();
      $pm_Host->getFromDB($data['id']);

      if ($data['state'] == '') {
         $data['state'] = 'UNKNOWN';
         $data['state_type'] = 'SOFT';
      }

      $shortstate = $pm_Host->getState($data['state'],
                                      $data['state_type'],
                                      $data['event'],
                                      $pm_Host->isCurrentlyAcknowledged());

      echo "<td class='center'>";
      // Only if counters exist for the host
      if ($pm_Host->hasDailyCounters()) {
         $pmCounters = new PluginMonitoringHostdailycounter();
         $html = $pm_Host->displayCounters(array('cPagesTotal', 'cRetractedTotal', 'cPrinterChanged', 'cPaperChanged'), $data['id']);
         $counters = "<table width='600' class='tab_cadre'><tr><td>".$html."</td></tr></table>";
         Html::showToolTip($counters, array(
            // 'title'  => __('Counters', 'monitoring'),
            'img'    => $CFG_GLPI['root_doc']."/plugins/monitoring/pics/stats_32.png"
         ));
      }
      echo "</td>";

      echo "<td>";
      $entity = new Entity();
      $entity->getFromDB($data["entities_id"]);
      echo $entity = $entity->getName();
      // echo "&nbsp;".$entity->getComments();
      echo "</td>";

      echo "<td>";
      $item = new $data['itemtype'];
      echo $item->getTypeName();
      echo "</td>";

      echo "<td>";
      echo "<span>".$pm_Host->getLink()."</span>";
      echo "</td>";

      echo "<td class='center'>";
      echo "<div class='page foldtl resource".$data['state']." resource".$data['state_type']."'>";
      echo "<div style='vertical-align:middle;'>";
      echo "<span>";
      echo $data['state'];
      echo "</span>";
      echo "</div>";
      echo "</div>";
      echo "</td>";

      if (isset($data['host_command_name'])) {
         $scriptName=$CFG_GLPI['root_doc']."/plugins/monitoring/scripts/".$data['host_command_command'];
         $scriptArgs=$data['host_name']." ".$data['ip'];

         echo "<td class='center'>";
         echo "<form name='form' method='post'
            action='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/".$data['host_command_command'].".php'>";

         echo "<input type='hidden' name='host_id' value='".$data['idComputer']."' />";
         echo "<input type='hidden' name='host_name' value='".$data['host_name']."' />";
         echo "<input type='hidden' name='host_ip' value='".$data['ip']."' />";
         echo "<input type='hidden' name='host_state' value='".$data['state']."' />";
         echo "<input type='hidden' name='host_statetype' value='".$data['state_type']."' />";
         echo "<input type='hidden' name='host_event' value='".$data['event']."' />";
         echo "<input type='hidden' name='host_perfdata' value='".$data['perf_data']."' />";
         echo "<input type='hidden' name='host_last_check' value='".$data['last_check']."' />";
         echo "<input type='hidden' name='glpi_users_id' value='".$_SESSION['glpiID']."' />";

         echo "<input type='submit' name='host_command' value=\"".$data['host_command_name']."\" class='submit'>";
         Html::closeForm();

         echo "</td>";
      }

      echo "<td class='center'>";
      echo "<div class='page foldtl resource".$data['host_services_state']."'>";
      echo "<div style='vertical-align:middle;'>";
      echo "<span>";
      if (PluginMonitoringProfile::haveRight("dashboard_all_ressources", 'r')) {
         $link = $CFG_GLPI['root_doc'].
            "/plugins/monitoring/front/service.php?hidesearch=1&reset=reset".
               "&field[0]=1&searchtype[0]=equals&contains[0]=".$data['items_id'].
               "&itemtype=PluginMonitoringService&start=0'";

         echo '<a href="'.$link.'">'.$data['host_services_state']."</a>";
      } else {
         echo '<span>'.$data['host_services_state']."</span>";
      }
      if (!empty($data['host_services_state_list'])) {
         echo "&nbsp;".Html::showToolTip($data['host_services_state_list'], array('display' => false));
      }
      echo "</span>";
      echo "</div>";
      echo "</div>";
      echo "</td>";

      echo "<td>";
      echo $data['ip'];
      echo "</td>";

      echo "<td>";
      echo Html::convDate($data['last_check']).' '. substr($data['last_check'], 11, 8);
      echo "</td>";

      echo "<td>";
      echo $data['event'];
      echo "</td>";

      echo "<td>";
      echo $data['perf_data'];
      echo "</td>";

      if (PluginMonitoringProfile::haveRight("acknowledge", 'r')
         || PluginMonitoringProfile::haveRight("downtime", 'r')) {
         echo "<td>";
         // Manage downtimes for an host
         if (PluginMonitoringProfile::haveRight("downtime", 'r')) {
            if ($pm_Host->isInScheduledDowntime()) {
               $pmDowntime = new PluginMonitoringDowntime();
               $pmDowntime->getFromDBByQuery("WHERE `" . $pmDowntime->getTable() . "`.`plugin_monitoring_hosts_id` = '" . $pm_Host->getID() . "' ORDER BY end_time DESC LIMIT 1");

               $downtime_id = $pmDowntime->getID();
               // Toolbox::logInFile("pm", "Host ".$pm_Host->getName()." is in downtime period \n");
               if (PluginMonitoringProfile::haveRight("downtime", 'w')) {
                  echo "<div style='float: left; margin-right: 10px;'>";
                  echo "<span>";
                  echo "<a href='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/downtime.form.php?host_id=".$data['id']."'>"
                           ."<img src='".$CFG_GLPI['root_doc']."/plugins/monitoring/pics/downtime_scheduled.png'"
                          ." alt='".htmlspecialchars(__('Edit the downtime scheduled for the host', 'monitoring'), ENT_QUOTES)."'"
                          ." title='".htmlspecialchars(__('Edit the downtime scheduled for the host', 'monitoring'), ENT_QUOTES)."'/>"
                       ."</a>";
                  echo "&nbsp;&nbsp;</span>";
                  echo "</div>";
               } else {
                  echo "<div style='float: left; margin-right: 10px;'>";
                  echo "<span>";
                  echo "<img src='".$CFG_GLPI['root_doc']."/plugins/monitoring/pics/downtime_scheduled.png'"
                          ." alt='".htmlspecialchars(__('A downtime is scheduled for the host', 'monitoring'), ENT_QUOTES)."'"
                          ." title='".htmlspecialchars(__('A downtime is scheduled for the host', 'monitoring'), ENT_QUOTES)."'/>";
                  echo "&nbsp;&nbsp;</span>";
                  echo "</div>";
               }
            } else {
               if (PluginMonitoringProfile::haveRight("downtime", 'w')) {
                  echo "<div style='float: left; margin-right: 10px;'>";
                  echo "<span>";
                  echo "<a href='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/downtime.form.php?id=-1&host_id=".$data['id']."'>"
                           ."<img src='".$CFG_GLPI['root_doc']."/plugins/monitoring/pics/downtime_to_schedule.png'"
                          ." alt='".htmlspecialchars(__('Schedule a downtime for the host', 'monitoring'), ENT_QUOTES)."'"
                          ." title='".htmlspecialchars(__('Schedule a downtime for the host', 'monitoring'), ENT_QUOTES)."'/>"
                       ."</a>";
                  echo "&nbsp;&nbsp;</span>";
                  echo "</div>";
               }
            }
         }
         echo "<div style='float: left;'>";
         // Manage acknowledgement for an host
         if ($pm_Host->isCurrentlyAcknowledged()) {
            if (PluginMonitoringProfile::haveRight("acknowledge", 'w')) {
               echo "<span>";
               echo "<a href='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/acknowledge.form.php?itemtype=Host&items_id=".$data['id']."'>"
                        ."<img src='".$CFG_GLPI['root_doc']."/plugins/monitoring/pics/acknowledge_ok.png'"
                       ." alt='".htmlspecialchars(__('Modify acknowledge comment for the host', 'monitoring'), ENT_QUOTES)."'"
                       ." title='".htmlspecialchars(__('Modify acknowledge comment for the host', 'monitoring'), ENT_QUOTES)."'/>"
                    ."</a>";
               echo "&nbsp;&nbsp;</span>";
            } else {
               echo "<span>";
               echo "<img src='".$CFG_GLPI['root_doc']."/plugins/monitoring/pics/acknowledge_ok.png'"
                       ." alt='".htmlspecialchars(__('Host problem has been acknowledged', 'monitoring'), ENT_QUOTES)."'"
                       ." title='".htmlspecialchars(__('Host problem has been acknowledged', 'monitoring'), ENT_QUOTES)."'/>";
               echo "&nbsp;&nbsp;</span>";
            }
            // Display acknowledge data ...
            $pm_Host->getAcknowledge();
         } else if ($shortstate == 'red'
                 || $shortstate == 'yellow'
                 || $shortstate == 'orange'
                 || !empty($data['host_services_state_list'])) {
            if (PluginMonitoringProfile::haveRight("acknowledge", 'w')) {
               echo "<span>";
               echo "<a href='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/acknowledge.form.php?itemtype=Host&items_id=".$data['id']."'>"
                        ."<img src='".$CFG_GLPI['root_doc']."/plugins/monitoring/pics/acknowledge_ko.png'"
                       ." alt='".htmlspecialchars(__('Add an acknowledge for the host and all faulty services of the host', 'monitoring'), ENT_QUOTES)."'"
                       ." title='".htmlspecialchars(__('Add an acknowledge for the host and all faulty services of the host', 'monitoring'), ENT_QUOTES)."'/>"
                    ."</a>";
               echo "&nbsp;&nbsp;</span>";
            }
         }
         echo "</div>";
         echo "</td>";
      }
   }



   function displayGraphs($itemtype, $items_id) {
      global $CFG_GLPI;

      echo '<script type="text/javascript">
    jQuery(function() {
      jQuery("#jquery-tagbox-select").tagBox({
        enableDropdown: true,
        separator: "####",
        tagButtonTitle: "'.__('Add to graph', 'monitoring').'",
        dropdownSource: function() {
          return jQuery("#jquery-tagbox-select-options");
        }
      });
    });
    jQuery(function() {
      jQuery("#jquery-tagbox-select2").tagBox({
        enableDropdown: true,
        separator: "####",
        tagButtonTitle: "'.__('Invert', 'monitoring').'",
        dropdownSource: function() {
          return jQuery("#jquery-tagbox-select2-options");
        }
      });
    });
  </script>';


      $pmComponent              = new PluginMonitoringComponent();
      $pmConfig                 = new PluginMonitoringConfig();
      $pmComponentscatalog_Host = new PluginMonitoringComponentscatalog_Host();

      $item = new $itemtype();
      $item->getFromDB($items_id);
      $pmComponent->getFromDB($item->fields['plugin_monitoring_components_id']);
      if(!isset($_SESSION['glpi_plugin_monitoring']['perfname'][$pmComponent->fields['id']])) {
         PluginMonitoringToolbox::loadPreferences($pmComponent->fields['id']);
      }
      $css_width = '950';
      if (isset($_GET['mobile'])) {
         $css_width = '300';
      }


      echo "<table class='tab_cadre' width='".$css_width."'>";

      echo "<tr class='tab_bg_1'>";
      echo "<th>";
      $title = Dropdown::getDropdownName(getTableForItemType('PluginMonitoringComponent'), $item->fields['plugin_monitoring_components_id']);
      $title .= ' '.__('on', 'monitoring').' ';
      $pmComponentscatalog_Host->getFromDB($item->fields["plugin_monitoring_componentscatalogs_hosts_id"]);
      if (isset($pmComponentscatalog_Host->fields['itemtype'])
              AND $pmComponentscatalog_Host->fields['itemtype'] != '') {

          $itemtype2 = $pmComponentscatalog_Host->fields['itemtype'];
          $item2 = new $itemtype2();
          $item2->getFromDB($pmComponentscatalog_Host->fields['items_id']);
          $title .= str_replace("'", "\"", $item2->getLink()." (".$item2->getTypeName().")");
      }
      echo $title;
      echo "</th>";
      echo "<th width='200'>";
      if (!isset($_GET['mobile'])) {
         echo "<form method='post'>";
         $a_timezones = PluginMonitoringConfig::getTimezones();
          if (!isset($_SESSION['plugin_monitoring_timezone'])) {
            $_SESSION['plugin_monitoring_timezone'] = '0';
         }
         $a_timezones_allowed = array();
         $pmConfig->getFromDB(1);
         $a_temp = importArrayFromDB($pmConfig->fields['timezones']);
         foreach ($a_temp as $key) {
            $a_timezones_allowed[$key] = $a_timezones[$key];
         }
         if (count($a_timezones_allowed) == '0') {
            $a_timezones_allowed['0'] = $a_timezones['0'];
         }

         Dropdown::showFromArray('plugin_monitoring_timezone',
                                 $a_timezones_allowed,
                                 array('value'=>$_SESSION['plugin_monitoring_timezone']));
         echo "&nbsp;<input type='submit' name='update' value=\"".__('Save')."\" class='submit'>";
         Html::closeForm();
      }
      echo "</th>";
      echo "</tr>";

      $timezone = '0';
      if (isset($_SESSION['plugin_monitoring_timezone'])) {
         $timezone = $_SESSION['plugin_monitoring_timezone'];
      }

      if (!isset($_GET['mobile'])) {
         echo "<tr class='tab_bg_1'>";
         echo "<th colspan='2'>";
         echo "<div id='legendlink'><a onClick='Ext.get(\"options\").toggle();'>[ Options ]</a></div>";
         echo "</th>";
         echo "</tr>";

         // * Display perfname
         echo "<tr class='tab_bg_1'>";
         echo "<td colspan='2'>";
         echo "<div id='options' style='display:none'>";
         PluginMonitoringToolbox::preferences($pmComponent->fields['id'], 0);
         echo "</div>";
         echo "</td>";
         echo "</tr>";

         // * Display date slider
         echo "<tr class='tab_bg_1'>";
         echo "<th colspan='2'>";
         echo __('Select date (only last 2, 12 and 24 hours)', 'monitoring');
         echo "</th>";
         echo "</tr>";

         echo "<tr class='tab_bg_1'>";
         echo "<td colspan='2'>";

         $end = time();

         $oldvalue = current(getAllDatasFromTable('glpi_plugin_monitoring_serviceevents',
                                                  "`plugin_monitoring_services_id`='".$items_id."'",
                                                  false,
                                                  'date ASC LIMIT 1'));
         $date = new DateTime($oldvalue['date']);
         $start = $date->getTimestamp();
         $pmServicegraph = new PluginMonitoringServicegraph();
echo "
<script type=\"text/javascript\">

Ext.onReady(function(){

    var tip = new Ext.slider.Tip({
        getText: function(thumb){
            return String.format('<b> ' + new Date(thumb.value * 1000).format('Y-m-d') + '</b>');
        }
    });

    new Ext.Slider({
        renderTo: 'custom-tip-slider',
        width: 940,
        increment: 86400,
        minValue: ".$start.",
        maxValue: ".$end.",
        value: ".$end.",
        plugins: tip,
        listeners: {
            dragend: function(slider, thumb, value){
               document.getElementById('custom_date').textContent = slider.getValue();
               mgr".$items_id."2h.stopAutoRefresh();
               mgr".$items_id."12h.stopAutoRefresh();
               mgr".$items_id."1d.stopAutoRefresh();
               mgr".$items_id."1w.stopAutoRefresh();
               mgr".$items_id."1m.stopAutoRefresh();
               mgr".$items_id."0y6m.stopAutoRefresh();
               mgr".$items_id."1y.stopAutoRefresh();
                  ";
               $a_graphlist = array('2h', '12h', '1d', '1w', '1m', '0y6m', '1y');
               foreach ($a_graphlist as $time) {
                  $pmServicegraph->startAutoRefresh($pmComponent->fields['graph_template'],
                                                    $itemtype,
                                                    $items_id,
                                                    $timezone,
                                                    $time,
                                                    $pmComponent->fields['id']);
               }
               echo "
            }
        }
    });

});
</script>";
         echo '<center><div id="custom-tip-slider"></div></center>';
         echo '<div id="custom_date" style="display:none"></div>';
         echo "</td>";
         echo "</tr>";

         // * Display time slider
         echo "<tr class='tab_bg_1'>";
         echo "<th colspan='2'>";
         echo __('Select time (only last 2, 12 and 24 hours)', 'monitoring');
         echo "</th>";
         echo "</tr>";

         echo "<tr class='tab_bg_1'>";
         echo "<td colspan='2'>";

         $start = 0 + 86400 - 3600;
         $end = 86400 + 86400 - 3600 - 300;
         $current = mktime(date('H'), date('i'), 0, 1, 2, 1970);

echo "
<script type=\"text/javascript\">

Ext.onReady(function(){

    var tiptime = new Ext.slider.Tip({
        getText: function(thumb){
            return String.format('<b> ' + new Date(thumb.value * 1000).format('H:i:s') + '</b>');
        }
    });

    new Ext.Slider({
        renderTo: 'custom-tip-slider-time',
        width: 940,
        increment: 300,
        minValue: ".$start.",
        maxValue: ".$end.",
        value: ".$current.",
        plugins: tiptime,
        listeners: {
            dragend: function(slider, thumb, value){
               document.getElementById('custom_time').textContent = slider.getValue();
               mgr".$items_id."2h.stopAutoRefresh();
               mgr".$items_id."12h.stopAutoRefresh();
               mgr".$items_id."1d.stopAutoRefresh();
               mgr".$items_id."1w.stopAutoRefresh();
               mgr".$items_id."1m.stopAutoRefresh();
               mgr".$items_id."0y6m.stopAutoRefresh();
               mgr".$items_id."1y.stopAutoRefresh();
                  ";
               $a_graphlist = array('2h', '12h', '1d', '1w', '1m', '0y6m', '1y');
               foreach ($a_graphlist as $time) {
                  $pmServicegraph->startAutoRefresh($pmComponent->fields['graph_template'],
                                                    $itemtype,
                                                    $items_id,
                                                    $timezone,
                                                    $time,
                                                    $pmComponent->fields['id']);
               }
               echo "
            }
        }
    });
});
</script>";
         echo '<center><div id="custom-tip-slider-time"></div></center>';
         echo '<div id="custom_time" style="display:none"></div>';
         echo "</td>";
         echo "</tr>";
      }

      $a_list = array();
      $a_list["2h"]  = __("Last 2 hours", "monitoring");
      $a_list["12h"] = __("Last 12 hours", "monitoring");
      $a_list["1d"]  = __("Last 24 hours", "monitoring");
      if (!isset($_GET['mobile'])) {
         $a_list["1w"]     = __("Last 7 days (average)", "monitoring");
         $a_list["1m"]     = __("Last month (average)", "monitoring");
         $a_list["0y6m"]   = __("Last 6 months (average)", "monitoring");
         $a_list["1y"]     = __("Last year (average)", "monitoring");
      }

      foreach ($a_list as $time=>$name) {

         echo "<tr class='tab_bg_1'>";
         echo "<th colspan='2'>";
         echo $name;
         echo "</th>";
         echo "</tr>";

         echo "<tr class='tab_bg_1'>";
         echo "<td align='center' colspan='2'>";

         $pmServicegraph = new PluginMonitoringServicegraph();
         $part = '';
         $width='950';
         if (isset($_GET['mobile'])) {
            $width='294';
         }
         $pmServicegraph->displayGraph($pmComponent->fields['graph_template'],
                                       $itemtype,
                                       $items_id,
                                       $timezone,
                                       $time,
                                       $part,
                                       $width);

         echo "</td>";
         echo "</tr>";
      }
      echo "</table>";
   }



   function displayCounters($type, $display=1) {
      global $DB,$CFG_GLPI;

      $ok = 0;
      $warningdata = 0;
      $warningconnection = 0;
      $critical = 0;
      $ok_soft = 0;
      $warningdata_soft = 0;
      $warningconnection_soft = 0;
      $critical_soft = 0;
      $acknowledge = 0;

      $play_sound = 0;

      if ($type == 'Ressources') {
         $ok = $this->countServicesQuery("
            `glpi_plugin_monitoring_services`.`state_type`='HARD'
            AND `glpi_plugin_monitoring_services`.`state`='OK'
            AND `glpi_plugin_monitoring_hosts`.`is_acknowledged`='0'
            AND `glpi_plugin_monitoring_services`.`is_acknowledged`='0'");

         $warningdata = $this->countServicesQuery("
            `glpi_plugin_monitoring_services`.`state_type`='HARD'
            AND (
                  (`glpi_plugin_monitoring_services`.`state`='WARNING' AND `glpi_plugin_monitoring_services`.`event` IS NOT NULL AND `glpi_plugin_monitoring_services`.`event` <> '') OR
                  (`glpi_plugin_monitoring_services`.`state`='RECOVERY') OR
                  (`glpi_plugin_monitoring_services`.`state`='FLAPPING')
            )
            AND `glpi_plugin_monitoring_hosts`.`is_acknowledged`='0'
            AND `glpi_plugin_monitoring_services`.`is_acknowledged`='0'");

         $warningconnection = $this->countServicesQuery("
            `glpi_plugin_monitoring_services`.`state_type`='HARD'
            AND (
                  (`glpi_plugin_monitoring_services`.`state`='WARNING' AND `glpi_plugin_monitoring_services`.`event` IS NULL) OR
                  (`glpi_plugin_monitoring_services`.`state`='UNKNOWN') OR
                  (`glpi_plugin_monitoring_services`.`state` IS NULL)
            )
            AND `glpi_plugin_monitoring_hosts`.`is_acknowledged`='0'
            AND `glpi_plugin_monitoring_services`.`is_acknowledged`='0'");

         $critical = $this->countServicesQuery("
            `glpi_plugin_monitoring_services`.`state_type`='HARD'
            AND `glpi_plugin_monitoring_services`.`state`='CRITICAL'
            AND `glpi_plugin_monitoring_hosts`.`is_acknowledged`='0'
            AND `glpi_plugin_monitoring_services`.`is_acknowledged`='0'");


         $ok_soft = $this->countServicesQuery("
            `glpi_plugin_monitoring_services`.`state_type`!='HARD'
            AND `glpi_plugin_monitoring_services`.`state`='OK'
            AND `glpi_plugin_monitoring_hosts`.`is_acknowledged`='0'
            AND `glpi_plugin_monitoring_services`.`is_acknowledged`='0'");

         $warningdata_soft = $this->countServicesQuery("
            `glpi_plugin_monitoring_services`.`state_type`!='HARD'
            AND (
                  (`glpi_plugin_monitoring_services`.`state`='WARNING' AND `glpi_plugin_monitoring_services`.`event` IS NOT NULL) OR
                  (`glpi_plugin_monitoring_services`.`state`='RECOVERY') OR
                  (`glpi_plugin_monitoring_services`.`state`='FLAPPING')
            )
            AND `glpi_plugin_monitoring_hosts`.`is_acknowledged`='0'
            AND `glpi_plugin_monitoring_services`.`is_acknowledged`='0'");

         $warningconnection_soft = $this->countServicesQuery("
            `glpi_plugin_monitoring_services`.`state_type`!='HARD'
            AND (
                  (`glpi_plugin_monitoring_services`.`state`='WARNING' AND `glpi_plugin_monitoring_services`.`event` IS NULL) OR
                  (`glpi_plugin_monitoring_services`.`state`='UNKNOWN') OR
                  (`glpi_plugin_monitoring_services`.`state` IS NULL)
            )
            AND `glpi_plugin_monitoring_hosts`.`is_acknowledged`='0'
            AND `glpi_plugin_monitoring_services`.`is_acknowledged`='0'");

         $critical_soft = $this->countServicesQuery("
            `glpi_plugin_monitoring_services`.`state_type`!='HARD'
            AND `glpi_plugin_monitoring_services`.`state`='CRITICAL'
            AND `glpi_plugin_monitoring_hosts`.`is_acknowledged`='0'
            AND `glpi_plugin_monitoring_services`.`is_acknowledged`='0'");


         $acknowledge = $this->countServicesQuery("
            `glpi_plugin_monitoring_hosts`.`is_acknowledged`='1'
            OR `glpi_plugin_monitoring_services`.`is_acknowledged`='1'");

         // ** Manage play sound if critical increase since last refresh
            if (isset($_SESSION['plugin_monitoring_dashboard_Ressources'])) {
               if ($critical > $_SESSION['plugin_monitoring_dashboard_Ressources']) {
                  $play_sound = 1;
               }
            }
            $_SESSION['plugin_monitoring_dashboard_Ressources'] = $critical;

      } else if ($type == 'Componentscatalog') {
         $pmComponentscatalog_Host = new PluginMonitoringComponentscatalog_Host();
         $queryCat = "SELECT * FROM `glpi_plugin_monitoring_componentscatalogs`";
         $resultCat = $DB->query($queryCat);
         while ($data=$DB->fetch_array($resultCat)) {

            $query = "SELECT COUNT(*) AS cpt FROM `".$pmComponentscatalog_Host->getTable()."`
               LEFT JOIN `glpi_plugin_monitoring_services`
                  ON `plugin_monitoring_componentscatalogs_hosts_id`=`".$pmComponentscatalog_Host->getTable()."`.`id`
               WHERE `plugin_monitoring_componentscalalog_id`='".$data['id']."'
                  AND (`state`='DOWN' OR `state`='UNREACHABLE' OR `state`='CRITICAL' OR `state`='DOWNTIME')
                  AND `state_type`='HARD'
                  AND `entities_id` IN (".$_SESSION['glpiactiveentities_string'].")
                  AND `is_acknowledged`='0'";
//            Toolbox::logInFile("pm", "Query critical - $query\n");
            $result = $DB->query($query);
            $data2 = $DB->fetch_assoc($result);
            if ($data2['cpt'] > 0) {
               $critical++;
            } else {
               $query = "SELECT COUNT(*) AS cpt, `glpi_plugin_monitoring_services`.`state`
                     FROM `".$pmComponentscatalog_Host->getTable()."`
                  LEFT JOIN `glpi_plugin_monitoring_services`
                     ON `plugin_monitoring_componentscatalogs_hosts_id`=`".$pmComponentscatalog_Host->getTable()."`.`id`
                  WHERE `plugin_monitoring_componentscalalog_id`='".$data['id']."'
                     AND (`state`='WARNING' OR `state`='UNKNOWN' OR `state`='RECOVERY' OR `state`='FLAPPING' OR `state` IS NULL)
                     AND `state_type`='HARD'
                     AND `entities_id` IN (".$_SESSION['glpiactiveentities_string'].")
                     AND `is_acknowledged`='0'";
               $result = $DB->query($query);
               $data2 = $DB->fetch_assoc($result);
               if ($data2['cpt'] > 0) {
                  $warningdata++;
               } else {
                  $query = "SELECT COUNT(*) AS cpt FROM `".$pmComponentscatalog_Host->getTable()."`
                     LEFT JOIN `glpi_plugin_monitoring_services`
                        ON `plugin_monitoring_componentscatalogs_hosts_id`=`".$pmComponentscatalog_Host->getTable()."`.`id`
                     WHERE `plugin_monitoring_componentscalalog_id`='".$data['id']."'
                     AND (`state`='OK' OR `state`='UP') AND `state_type`='HARD'
                     AND `entities_id` IN (".$_SESSION['glpiactiveentities_string'].")
                     AND `is_acknowledged`='0'";
                  $result = $DB->query($query);
                  $data2 = $DB->fetch_assoc($result);
                  if ($data2['cpt'] > 0) {
                     $ok++;
                  }
               }
            }

           $query = "SELECT COUNT(*) AS cpt FROM `".$pmComponentscatalog_Host->getTable()."`
               LEFT JOIN `glpi_plugin_monitoring_services`
                  ON `plugin_monitoring_componentscatalogs_hosts_id`=`".$pmComponentscatalog_Host->getTable()."`.`id`
               WHERE `plugin_monitoring_componentscalalog_id`='".$data['id']."'
                  AND (`state`='DOWN' OR `state`='UNREACHABLE' OR `state`='CRITICAL' OR `state`='DOWNTIME')
                  AND `state_type`='SOFT'
                  AND `entities_id` IN (".$_SESSION['glpiactiveentities_string'].")
                  AND `is_acknowledged`='0'";
            $result = $DB->query($query);
            $data2 = $DB->fetch_assoc($result);
            if ($data2['cpt'] > 0) {
               $critical_soft++;
            } else {
               $query = "SELECT COUNT(*) AS cpt FROM `".$pmComponentscatalog_Host->getTable()."`
                  LEFT JOIN `glpi_plugin_monitoring_services`
                     ON `plugin_monitoring_componentscatalogs_hosts_id`=`".$pmComponentscatalog_Host->getTable()."`.`id`
                  WHERE `plugin_monitoring_componentscalalog_id`='".$data['id']."'
                     AND (`state`='WARNING' OR `state`='UNKNOWN' OR `state`='RECOVERY' OR `state`='FLAPPING' OR `state` IS NULL)
                     AND `state_type`='SOFT'
                     AND `entities_id` IN (".$_SESSION['glpiactiveentities_string'].")
                     AND `is_acknowledged`='0'";
               $result = $DB->query($query);
               $data2 = $DB->fetch_assoc($result);
               if ($data2['cpt'] > 0) {
                  $warningdata_soft++;
               } else {
                  $query = "SELECT COUNT(*) AS cpt FROM `".$pmComponentscatalog_Host->getTable()."`
                     LEFT JOIN `glpi_plugin_monitoring_services`
                        ON `plugin_monitoring_componentscatalogs_hosts_id`=`".$pmComponentscatalog_Host->getTable()."`.`id`
                     WHERE `plugin_monitoring_componentscalalog_id`='".$data['id']."'
                        AND (`state`='OK' OR `state`='UP') AND `state_type`='SOFT'
                        AND `entities_id` IN (".$_SESSION['glpiactiveentities_string'].")
                        AND `is_acknowledged`='0'";
                  $result = $DB->query($query);
                  $data2 = $DB->fetch_assoc($result);
                  if ($data2['cpt'] > 0) {
                     $ok_soft++;
                  }
               }
            }
         }

         // ** Manage play sound if critical increase since last refresh
            if (isset($_SESSION['plugin_monitoring_dashboard_Componentscatalog'])) {
               if ($critical > $_SESSION['plugin_monitoring_dashboard_Componentscatalog']) {
                  $play_sound = 1;
               }
            }
            $_SESSION['plugin_monitoring_dashboard_Componentscatalog'] = $critical;

      } else if ($type == 'Businessrules') {
         $ok = countElementsInTable("glpi_plugin_monitoring_servicescatalogs",
                 "(`state`='OK' OR `state`='UP') AND `state_type`='HARD'
                 AND `entities_id` IN (".$_SESSION['glpiactiveentities_string'].")
                 AND `is_acknowledged`='0'");

         $warningdata = countElementsInTable("glpi_plugin_monitoring_servicescatalogs",
                 "(`state`='WARNING' OR `state`='UNKNOWN'
                        OR `state`='RECOVERY' OR `state`='FLAPPING')
                    AND `state_type`='HARD'
                    AND `entities_id` IN (".$_SESSION['glpiactiveentities_string'].")
                    AND `is_acknowledged`='0'");

         $critical = countElementsInTable("glpi_plugin_monitoring_servicescatalogs",
                 "(`state`='DOWN' OR `state`='UNREACHABLE' OR `state`='CRITICAL' OR `state`='DOWNTIME')
                    AND `state_type`='HARD'
                    AND `entities_id` IN (".$_SESSION['glpiactiveentities_string'].")
                    AND `is_acknowledged`='0'");

         $warningdata_soft = countElementsInTable("glpi_plugin_monitoring_servicescatalogs",
                 "(`state`='WARNING' OR `state`='UNKNOWN'
                        OR `state`='RECOVERY' OR `state`='FLAPPING')
                    AND `state_type`='SOFT'
                    AND `entities_id` IN (".$_SESSION['glpiactiveentities_string'].")
                    AND `is_acknowledged`='0'");

         $critical_soft = countElementsInTable("glpi_plugin_monitoring_servicescatalogs",
                 "(`state`='DOWN' OR `state`='UNREACHABLE' OR `state`='CRITICAL' OR `state`='DOWNTIME')
                    AND `state_type`='SOFT'
                    AND `entities_id` IN (".$_SESSION['glpiactiveentities_string'].")
                    AND `is_acknowledged`='0'");

         $ok_soft = countElementsInTable("glpi_plugin_monitoring_servicescatalogs",
                 "(`state`='OK' OR `state`='UP') AND `state_type`='SOFT'
                  AND `entities_id` IN (".$_SESSION['glpiactiveentities_string'].")
                    AND `is_acknowledged`='0'");

         // ** Manage play sound if critical increase since last refresh
            if (isset($_SESSION['plugin_monitoring_dashboard_Businessrules'])) {
               if ($critical > $_SESSION['plugin_monitoring_dashboard_Businessrules']) {
                  $play_sound = 1;
               }
            }
            $_SESSION['plugin_monitoring_dashboard_Businessrules'] = $critical;

      }
      if ($display == '0') {
         $a_return = array();
         $a_return['ok'] = strval($ok);
         $a_return['ok_soft'] = strval($ok_soft);
         $a_return['warningdata'] = strval($warningdata);
         $a_return['warningconnection'] = strval($warningconnection);
         $a_return['warningdata_soft'] = strval($warningdata_soft);
         $a_return['warningconnection_soft'] = strval($warningconnection_soft);
         $a_return['critical'] = strval($critical);
         $a_return['critical_soft'] = strval($critical_soft);
         $a_return['acknowledge'] = strval($acknowledge);
         return $a_return;
      }

      $critical_link = $CFG_GLPI['root_doc'].
               "/plugins/monitoring/front/service.php?hidesearch=1&reset=reset".
                  "&field[0]=3&searchtype[0]=contains&contains[0]=CRITICAL&link[1]=AND".
                  "&field[1]=7&searchtype[1]=contains&contains[1]=0&link[1]=AND".
                  "&field[2]=8&searchtype[2]=equals&contains[2]=0".
                  "&itemtype=PluginMonitoringService&start=0&glpi_tab=3'";
      $warning_link = $CFG_GLPI['root_doc'].
               "/plugins/monitoring/front/service.php?hidesearch=1&reset=reset".
                  "&field[0]=3&searchtype[0]=contains&contains[0]=FLAPPING&link[1]=AND".
                  "&field[1]=7&searchtype[1]=equals&contains[1]=0&link[2]=AND".
                  "&field[2]=8&searchtype[2]=equals&contains[2]=0&link[2]=OR".
                  "&field[3]=3&searchtype[3]=contains&contains[3]=RECOVERY&link[4]=AND".
                  "&field[4]=7&searchtype[4]=equals&contains[4]=0&link[5]=AND".
                  "&field[5]=8&searchtype[5]=equals&contains[5]=0&link[6]=OR".
                  "&field[6]=3&searchtype[6]=contains&contains[6]=UNKNOWN&link[7]=AND".
                  "&field[7]=7&searchtype[7]=equals&contains[7]=0&link[8]=AND".
                  "&field[8]=8&searchtype[8]=equals&contains[8]=0&link[9]=AND".
                  "&field[9]=3&searchtype[9]=contains&contains[9]=WARNING&link[10]=AND".
                  "&field[10]=7&searchtype[10]=equals&contains[10]=0&link[11]=AND".
                  "&field[11]=8&searchtype[11]=equals&contains[11]=0".
                  "&itemtype=PluginMonitoringService&start=0&glpi_tab=3'";
      $warningdata_link = $CFG_GLPI['root_doc'].
               "/plugins/monitoring/front/service.php?hidesearch=1&reset=reset".
                  "&field[0]=3&searchtype[0]=contains&contains[0]=FLAPPING&link[1]=AND".
                  "&field[1]=7&searchtype[1]=equals&contains[1]=0&link[2]=AND".
                  "&field[2]=8&searchtype[2]=equals&contains[2]=0&link[2]=OR".
                  "&field[3]=3&searchtype[3]=contains&contains[3]=RECOVERY&link[4]=AND".
                  "&field[4]=7&searchtype[4]=equals&contains[4]=0&link[5]=AND".
                  "&field[5]=8&searchtype[5]=equals&contains[5]=0&link[6]=OR".
                  "&field[6]=3&searchtype[6]=contains&contains[6]=WARNING&link[7]=AND".
                  "&field[7]=7&searchtype[7]=equals&contains[7]=0&link[8]=AND".
                  "&field[8]=8&searchtype[8]=equals&contains[8]=0".
                  "&itemtype=PluginMonitoringService&start=0&glpi_tab=3'";
      $warningconnection_link = $CFG_GLPI['root_doc'].
               "/plugins/monitoring/front/service.php?hidesearch=1&reset=reset".
                  "&field[0]=3&searchtype[0]=contains&contains[0]=UNKNOWN&link[1]=AND".
                  "&field[1]=7&searchtype[1]=equals&contains[1]=0&link[2]=AND".
                  "&field[2]=8&searchtype[2]=equals&contains[2]=0&link[3]=OR".
                  "&field[3]=3&searchtype[3]=contains&contains[3]=NULL&link[4]=AND".
                  "&field[4]=7&searchtype[4]=equals&contains[4]=0&link[5]=AND".
                  "&field[5]=8&searchtype[5]=equals&contains[5]=0".
                  "&itemtype=PluginMonitoringService&start=0&glpi_tab=3'";
      $ok_link = $CFG_GLPI['root_doc'].
               "/plugins/monitoring/front/service.php?hidesearch=1&reset=reset&".
                  "field[0]=3&searchtype[0]=contains&contains[0]=OK".
                  "&itemtype=PluginMonitoringService&start=0&glpi_tab=3'";
      $acknowledge_link = $CFG_GLPI['root_doc'].
               "/plugins/monitoring/front/service.php?hidesearch=1&reset=reset&".
                  "field[0]=7&searchtype[0]=equals&contains[0]=1".
                  "&itemtype=PluginMonitoringService&start=0&glpi_tab=3'";

      echo "<table align='center'>";
      echo "<tr>";
      echo "<td width='414'>";
         $background = '';
         if ($critical > 0) {
            $background = 'background="'.$CFG_GLPI['root_doc'].'/plugins/monitoring/pics/bg_critical.png"';
         }
         echo "<table class='tab_cadre' width='100%' height='130' ".$background." >";
         echo "<tr>";
         echo "<th style='background-color:transparent;'>";
         if ($type == 'Ressources' OR $type == 'Componentscatalog') {
            echo "<a href='".$critical_link.">".
                    "<font color='black' style='font-size: 12px;font-weight: bold;'>".__('Critical', 'monitoring')."</font></a>";
         } else {
            echo __('Critical', 'monitoring');
         }
         echo "</td>";
         echo "</tr>";
         echo "<tr>";
         echo "<th style='background-color:transparent;'>";
         if ($type == 'Ressources' OR $type == 'Componentscatalog') {
            echo "<a href='".$critical_link.">".
                    "<font color='black' style='font-size: 52px;font-weight: bold;'>".$critical."</font></a>";
         } else {
            echo "<font style='font-size: 52px;'>".$critical."</font>";
        }
         echo "</th>";
         echo "</tr>";
         echo "<tr><td>";
         echo "<p style='font-size: 11px; text-align: center;'> Soft : ".$critical_soft."</p>";
         echo "</td></tr>";
         echo "</table>";
      echo "</td>";

      echo "<td width='188'>";
         $background = '';
         if ($warningdata > 0) {
            $background = 'background="'.$CFG_GLPI['root_doc'].'/plugins/monitoring/pics/bg_warning.png"';
         }
         if ($type == 'Ressources') {
            echo "<table class='tab_cadre' width='100%' height='130' ".$background." >";
         } else {
            echo "<table class='tab_cadre' width='100%' height='130' ".$background." >";
         }
         echo "<tr>";
         echo "<th style='background-color:transparent;'>";
         if ($type == 'Ressources') {
            echo "<a href='".$warningdata_link.">".
                    "<font color='black' style='font-size: 12px;font-weight: bold;'>".__('Warning', 'monitoring')."</font></a>";
         } else {
            if ($type == 'Componentscatalog') {
               echo "<a href='".$warning_link.">".
                       "<font color='black' style='font-size: 12px;font-weight: bold;'>".__('Warning', 'monitoring')."</font></a>";
            } else {
               echo __('Warning', 'monitoring');
            }
         }
         echo "</td>";
         echo "</tr>";
         echo "<tr>";
         echo "<th style='background-color:transparent;'>";
         if ($type == 'Ressources') {
            echo "<a href='".$warningdata_link.">".
                    "<font color='black' style='font-size: 52px;'>".$warningdata."</font></a>";
         } else if ($type == 'Componentscatalog') {
            echo "<a href='".$warning_link.">".
                    "<font color='black' style='font-size: 52px;'>".$warningdata."</font></a>";
         } else {
            echo "<font style='font-size: 52px;'>".$warningdata."</font>";
         }
         echo "</th>";
         echo "</tr>";
         echo "<tr><td>";
         echo "<p style='font-size: 11px; text-align: center;'> Soft : ".$warningdata_soft."</p>";
         echo "</td></tr>";
         echo "</table>";
      echo "</td>";

      if ($type == 'Ressources') {
         echo "<td width='188'>";
            $background = '';
            if ($warningconnection > 0) {
               $background = 'background="'.$CFG_GLPI['root_doc'].'/plugins/monitoring/pics/bg_warning_yellow.png"';
            }
            echo "<table class='tab_cadre' width='100%' height='130' ".$background." >";
            echo "<tr>";
            echo "<th style='background-color:transparent;'>";
            if ($type == 'Ressources' OR $type == 'Componentscatalog') {
               echo "<a href='".$warningconnection_link.">".
                       "<font color='black' style='font-size: 12px;font-weight: bold;'>".__('Warning (connection)', 'monitoring')."</font></a>";
            } else {
               echo __('Warning (connection)', 'monitoring');
            }
            echo "</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<th style='background-color:transparent;'>";
            if ($type == 'Ressources' OR $type == 'Componentscatalog') {
               echo "<a href='".$warningconnection_link.">".
                       "<font color='black' style='font-size: 52px;'>".$warningconnection."</font></a>";
            } else {
               echo "<font style='font-size: 52px;'>".$warningconnection."</font>";
            }
            echo "</th>";
            echo "</tr>";
            echo "<tr><td>";
            echo "<p style='font-size: 11px; text-align: center;'> Soft : ".$warningconnection_soft."</p>";
            echo "</td></tr>";
            echo "</table>";
         echo "</td>";
      }

      echo "<td width='148'>";
         $background = '';
         if ($ok > 0) {
            $background = 'background="'.$CFG_GLPI['root_doc'].'/plugins/monitoring/pics/bg_ok.png"';
         }
         echo "<table class='tab_cadre' width='100%' height='130' ".$background." >";
         echo "<tr>";
         echo "<th style='background-color:transparent;'>";
         if ($type == 'Ressources' OR $type == 'Componentscatalog') {
            echo "<a href='".$ok_link.">".
                    "<font color='black' style='font-size: 12px;font-weight: bold;'>".__('OK', 'monitoring')."</font></a>";
         } else {
            echo __('OK', 'monitoring');
         }
         echo "</td>";
         echo "</tr>";
         echo "<tr>";
         echo "<th style='background-color:transparent;'>";
         if ($type == 'Ressources' OR $type == 'Componentscatalog') {
            echo "<a href='".$ok_link.">".
                    "<font color='black' style='font-size: 52px;font-weight: bold;'>".$ok."</font></a>";
         } else {
            echo "<font style='font-size: 52px;'>".$ok."</font>";
         }
         echo "</th>";
         echo "</tr>";
         echo "<tr><td>";
         echo "<p style='font-size: 11px; text-align: center;'> Soft : ".$ok_soft."</p>";
         echo "</td></tr>";
         echo "</table>";
      echo "</td>";

      echo "<td width='120'>";
         $background = '';
         if ($acknowledge > 0) {
            $background = 'background="'.$CFG_GLPI['root_doc'].'/plugins/monitoring/pics/bg_acknowledge.png"';
         }
         echo "<table class='tab_cadre' width='100%' height='130' ".$background." >";
         echo "<tr>";
         echo "<th style='background-color:transparent;'>";
         if ($type == 'Ressources' OR $type == 'Componentscatalog') {
            echo "<a href='".$acknowledge_link."'>".
                    "<font color='black' style='font-size: 12px;font-weight: bold;'>".__('Acknowledge', 'monitoring')."</font></a>";
         } else {
            echo __('Acknowledge', 'monitoring');
         }
         echo "</td>";
         echo "</tr>";
         echo "<tr>";
         echo "<th style='background-color:transparent;'>";
         if ($type == 'Ressources' OR $type == 'Componentscatalog') {
            echo "<a href='".$acknowledge_link."'>".
                    "<font color='black' style='font-size: 52px;font-weight: bold;'>".$acknowledge."</font></a>";
         } else {
            echo "<font style='font-size: 52px;'>".$acknowledge."</font>";
         }
         echo "</th>";
         echo "</tr>";
         echo "<tr><td>";
         echo "<p style='font-size: 11px; text-align: center;'>&nbsp;</p>";
         echo "</td></tr>";
         echo "</table>";
      echo "</td>";

      echo "</tr>";
      echo "</table><br/>";

      // ** play sound
      if ($play_sound == '1') {
         echo '<audio autoplay="autoplay">
                 <source src="../audio/star-trek.ogg" type="audio/ogg" />
                 Your browser does not support the audio element.
               </audio>';
      }
   }



   function displayHostsCounters($display=1) {
      global $DB,$CFG_GLPI;

      $play_sound = 0;

      $a_devicetypes = array('Computer', 'Printer', 'NetworkEquipment');

      $up = 0;
      $up_soft = 0;
      $unreachable = 0;
      $unreachable_soft = 0;
      $unknown = 0;
      $unknown_soft = 0;
      $down = 0;
      $down_soft = 0;
      $acknowledge = 0;

      foreach ($a_devicetypes as $itemtype) {

         $up += $this->countHostsQuery($itemtype, "`glpi_plugin_monitoring_hosts`.`state`='UP'
                     AND `state_type`='HARD'
                     AND `is_acknowledged`='0'");

         $up_soft += $this->countHostsQuery($itemtype, "`glpi_plugin_monitoring_hosts`.`state`='UP'
                     AND `state_type`='SOFT'
                     AND `is_acknowledged`='0'");

         $unreachable += $this->countHostsQuery($itemtype, "`glpi_plugin_monitoring_hosts`.`state`='UNREACHABLE'
                     AND `state_type`='HARD'
                     AND `is_acknowledged`='0'");

         $unreachable_soft += $this->countHostsQuery($itemtype, "`glpi_plugin_monitoring_hosts`.`state`='UNREACHABLE'
                     AND `state_type`='SOFT'
                     AND `is_acknowledged`='0'");

         $unknown += $this->countHostsQuery($itemtype, "(`glpi_plugin_monitoring_hosts`.`state`='UNKNOWN' AND `state_type`='HARD')
                     OR (`glpi_plugin_monitoring_hosts`.`state` IS NULL)
                     AND `is_acknowledged`='0'");

         $unknown_soft += $this->countHostsQuery($itemtype, "(`glpi_plugin_monitoring_hosts`.`state`='UNKNOWN' AND `state_type`='SOFT')
                     AND `is_acknowledged`='0'");

         $down += $this->countHostsQuery($itemtype, "`glpi_plugin_monitoring_hosts`.`state`='DOWN'
                     AND `state_type`='HARD'
                     AND `is_acknowledged`='0'");

         $down_soft += $this->countHostsQuery($itemtype, "`glpi_plugin_monitoring_hosts`.`state`='DOWN'
                     AND `state_type`='SOFT'
                     AND `is_acknowledged`='0'");

         $acknowledge += $this->countHostsQuery($itemtype, "`glpi_plugin_monitoring_hosts`.`state_type`='HARD'
                    AND `is_acknowledged`='1'");

      }


      // ** Manage play sound if down increased since last refresh
      if (isset($_SESSION['plugin_monitoring_dashboard_hosts_down'])) {
         if ($down > $_SESSION['plugin_monitoring_dashboard_hosts_down']) {
            $play_sound = 1;
         }
      }
      $_SESSION['plugin_monitoring_dashboard_hosts_down'] = $down;

      // ** Manage play sound if unreachable increased since last refresh
      if (isset($_SESSION['plugin_monitoring_dashboard_hosts_unreachable'])) {
         if ($unreachable > $_SESSION['plugin_monitoring_dashboard_hosts_unreachable']) {
            $play_sound = 1;
         }
      }
      $_SESSION['plugin_monitoring_dashboard_hosts_unreachable'] = $unreachable;

      if ($display == '0') {
         $a_return = array();
         $a_return['up'] = strval($up);
         $a_return['up_soft'] = strval($up_soft);
         $a_return['unreachable'] = strval($unreachable);
         $a_return['unreachable_soft'] = strval($unreachable_soft);
         $a_return['unknown'] = strval($unknown);
         $a_return['unknown_soft'] = strval($unknown_soft);
         $a_return['down'] = strval($down);
         $a_return['down_soft'] = strval($down_soft);
         $a_return['acknowledge'] = strval($acknowledge);
         return $a_return;
      }


      $down_link = $CFG_GLPI['root_doc'].
               "/plugins/monitoring/front/host.php?hidesearch=1&reset=reset".
                  "&field[0]=2&searchtype[0]=contains&contains[0]=DOWN".
                  "&field[1]=9&searchtype[1]=contains&contains[1]=0".
                  "&itemtype=PluginMonitoringHost&start=0'";
      $up_link = $CFG_GLPI['root_doc'].
               "/plugins/monitoring/front/host.php?hidesearch=1&reset=reset".
                  "&field[0]=2&searchtype[0]=contains&contains[0]=UP".
                  "&field[1]=9&searchtype[1]=contains&contains[1]=0".
                  "&itemtype=PluginMonitoringHost&start=0'";
      $unreachable_link = $CFG_GLPI['root_doc'].
               "/plugins/monitoring/front/host.php?hidesearch=1&reset=reset".
                  "&field[0]=2&searchtype[0]=contains&contains[0]=UNREACHABLE".
                  "&field[1]=9&searchtype[1]=contains&contains[1]=0".
                  "&itemtype=PluginMonitoringHost&start=0'";
      $unknown_link = $CFG_GLPI['root_doc'].
               "/plugins/monitoring/front/host.php?hidesearch=1&reset=reset".
                  "&field[0]=2&searchtype[0]=contains&contains[0]=UNKNOWN&link[1]=OR".
                  "&field[1]=2&searchtype[1]=contains&contains[1]=NULL&link[2]=AND".
                  "&field[2]=9&searchtype[2]=contains&contains[2]=0".
                  "&itemtype=PluginMonitoringHost&start=0'";
      $ack_link = $CFG_GLPI['root_doc'].
               "/plugins/monitoring/front/host.php?hidesearch=1&reset=reset".
                  "&field[0]=9&searchtype[0]=contains&contains[0]=1".
                  "&itemtype=PluginMonitoringHost&start=0'";

      echo "<table align='center' width='80%'>";
      echo "<tr>";
      echo "<td width='414'>";
         $background = '';
         if ($down > 0) {
            $background = 'background="'.$CFG_GLPI['root_doc'].'/plugins/monitoring/pics/bg_critical.png"';
         }
         echo "<table class='tab_cadre' width='100%' height='130' ".$background." >";
         echo "<th style='background-color:transparent;'>";
         echo "<a href='".$down_link.">".
            "<font color='black' style='font-size: 12px;font-weight: bold;'>".__('Down', 'monitoring')."</font>".
            "</a>";
         echo "</th>";
         echo "<tr><td align='center'>";
         echo "<a href='".$down_link.">".
            "<font color='black' style='font-size: 52px;font-weight: bold;'>".$down."</font>".
            "</a>";
         echo "</td></tr>";
         echo "<tr><td>";
         echo "<p style='font-size: 11px; text-align: center;'> Soft : ".$down_soft."</p>";
         echo "</td></tr>";
         echo "</table>";
      echo "</td>";

      echo "<td width='188'>";
         $background = '';
         if ($unreachable > 0) {
            $background = 'background="'.$CFG_GLPI['root_doc'].'/plugins/monitoring/pics/bg_warning.png"';
         }
         echo "<table class='tab_cadre' width='100%' height='130' ".$background." >";
         echo "<th style='background-color:transparent;'>";
         echo "<a href='".$unreachable_link.">".
            "<font color='black' style='font-size: 12px;font-weight: bold;'>".__('Unreachable', 'monitoring')."</font>".
            "</a>";
         echo "</th>";
         echo "<tr><td align='center'>";
         echo "<a href='".$unreachable_link.">".
            "<font color='black' style='font-size: 52px;font-weight: bold;'>".$unreachable."</font>".
            "</a>";
         echo "</td></tr>";
         echo "<tr><td>";
         echo "<p style='font-size: 11px; text-align: center;'> Soft : ".$unreachable_soft."</p>";
         echo "</td></tr>";
         echo "</table>";
      echo "</td>";

      echo "<td width='188'>";
         $background = '';
         if ($unknown > 0) {
            $background = 'background="'.$CFG_GLPI['root_doc'].'/plugins/monitoring/pics/bg_warning.png"';
         }
         echo "<table class='tab_cadre' width='100%' height='130' ".$background." >";
         echo "<th style='background-color:transparent;'>";
         echo "<a href='".$unknown_link.">".
            "<font color='black' style='font-size: 12px;font-weight: bold;'>".__('Unknown', 'monitoring')."</font>".
            "</a>";
         echo "</th>";
         echo "<tr><td align='center'>";
         echo "<a href='".$unknown_link.">".
            "<font color='black' style='font-size: 52px;font-weight: bold;'>".$unknown."</font>".
            "</a>";
         echo "</td></tr>";
         echo "<tr><td>";
         echo "<p style='font-size: 11px; text-align: center;'> Soft : ".$unknown_soft."</p>";
         echo "</td></tr>";
         echo "</table>";
      echo "</td>";

      echo "<td width='148'>";
         $background = '';
         if ($up > 0) {
            $background = 'background="'.$CFG_GLPI['root_doc'].'/plugins/monitoring/pics/bg_ok.png"';
         }
         echo "<table class='tab_cadre' width='100%' height='130' ".$background." >";
         echo "<th style='background-color:transparent;'>";
         echo "<a href='".$up_link.">".
            "<font color='black' style='font-size: 12px;font-weight: bold;'>".__('Up', 'monitoring')."</font>".
            "</a>";
         echo "</th>";
         echo "<tr><td align='center'>";
         echo "<a href='".$up_link.">".
            "<font color='black' style='font-size: 52px;font-weight: bold;'>".$up."</font>".
            "</a>";
         echo "</td></tr>";
         echo "<tr><td>";
         echo "<p style='font-size: 11px; text-align: center;'> Soft : ".$up_soft."</p>";
         echo "</td></tr>";
         echo "</table>";
      echo "</td>";

      echo "<td width='120'>";
         $background = '';
         if ($acknowledge > 0) {
            $background = 'background="'.$CFG_GLPI['root_doc'].'/plugins/monitoring/pics/bg_acknowledge.png"';
         }
         echo "<table class='tab_cadre' width='100%' height='130' ".$background." >";
         echo "<tr>";
         echo "<th style='background-color:transparent;'>";
         echo "<a href='".$ack_link.">".
            "<font color='black' style='font-size: 12px;font-weight: bold;'>".__('Acknowledge', 'monitoring')."</font>".
            "</a>";
         echo "</th>";
         echo "<tr><td align='center'>";
         echo "<a href='".$ack_link.">".
            "<font color='black' style='font-size: 52px;font-weight: bold;'>".$acknowledge."</font>".
            "</a>";
         echo "</td></tr>";
         echo "<tr><td>";
         echo "<p style='font-size: 11px; text-align: center;'>&nbsp;</p>";
         echo "</td></tr>";
         echo "</table>";
      echo "</td>";

      echo "</tr>";
      echo "</table><br/>";

      // ** play sound
      if ($play_sound == '1') {
         echo '<audio autoplay="autoplay">
                 <source src="../audio/star-trek.ogg" type="audio/ogg" />
                 Your browser does not support the audio element.
               </audio>';
      }
   }



   function countHostsQuery($itemtype, $whereState) {
      global $DB;

      $query = "SELECT COUNT(`glpi_plugin_monitoring_hosts`.`id`) AS cpt
          FROM `glpi_plugin_monitoring_hosts`
          LEFT JOIN `".getTableForItemType($itemtype)."`
             ON `itemtype`='".$itemtype."'
               AND `items_id`=`".getTableForItemType($itemtype)."`.`id`
          WHERE ".$whereState."
            AND `".getTableForItemType($itemtype)."`.`entities_id` IN (".$_SESSION['glpiactiveentities_string'].")";
      $result = $DB->query($query);
      $ligne  = $DB->fetch_assoc($result);
      return $ligne['cpt'];
   }



   function countServicesQuery($whereState) {
      global $DB;

      $query = "SELECT COUNT(`glpi_plugin_monitoring_services`.`id`) AS cpt
         FROM `glpi_plugin_monitoring_services`
         INNER JOIN `glpi_plugin_monitoring_componentscatalogs_hosts`
            ON (`glpi_plugin_monitoring_services`.`plugin_monitoring_componentscatalogs_hosts_id` = `glpi_plugin_monitoring_componentscatalogs_hosts`.`id`)
         INNER JOIN `glpi_plugin_monitoring_hosts`
            ON (`glpi_plugin_monitoring_componentscatalogs_hosts`.`items_id` = `glpi_plugin_monitoring_hosts`.`items_id`)
         WHERE ".$whereState."
            AND `glpi_plugin_monitoring_services`.`entities_id` IN (".$_SESSION['glpiactiveentities_string'].")";
      $result = $DB->query($query);
      $ligne  = $DB->fetch_assoc($result);
      return $ligne['cpt'];
   }



   function showCounters($type, $display=1, $ajax=1) {
      global $CFG_GLPI;

      if ($display == 0) {
         return $this->displayCounters($type, $display);
      }

      if ($ajax == 1) {
         $sess_id = session_id();
         PluginMonitoringSecurity::updateSession();

         echo "<div id=\"updatecounter".$type."\"></div>";
         echo "<script type=\"text/javascript\">

         var elcc".$type." = Ext.get(\"updatecounter".$type."\");
         var mgrcc".$type." = elcc".$type.".getUpdateManager();
         mgrcc".$type.".loadScripts=true;
         mgrcc".$type.".showLoadIndicator=false;
         mgrcc".$type.".startAutoRefresh(50, \"".$CFG_GLPI["root_doc"].
                 "/plugins/monitoring/ajax/updateCounter.php\","
                 . " \"type=".$type."&sess_id=".$sess_id.
                 "&glpiID=".$_SESSION['glpiID'].
                 "&plugin_monitoring_securekey=".$_SESSION['plugin_monitoring_securekey'].
                 "\", \"\", true);
         </script>";
      } else {
         $this->displayCounters($type);
      }
   }



   function showHostsCounters($display=1, $ajax=1) {
      global $CFG_GLPI;

      if ($display == 0) {
         return $this->displayHostsCounters($display);
      }

      if ($ajax == 1) {
         $sess_id = session_id();
         PluginMonitoringSecurity::updateSession();

         echo "<div id=\"updatecounter".$type."\"></div>";
         echo "<script type=\"text/javascript\">

         var elcc".$type." = Ext.get(\"updatecounter".$type."\");
         var mgrcc".$type." = elcc".$type.".getUpdateManager();
         mgrcc".$type.".loadScripts=true;
         mgrcc".$type.".showLoadIndicator=false;
         mgrcc".$type.".startAutoRefresh(50, \"".$CFG_GLPI["root_doc"].
                 "/plugins/monitoring/ajax/updateHostsCounter.php\","
                 . " \"type=".$type."&sess_id=".$sess_id.
                 "&glpiID=".$_SESSION['glpiID'].
                 "&plugin_monitoring_securekey=".$_SESSION['plugin_monitoring_securekey'].
                 "\", \"\", true);
         </script>";
      } else {
         $this->displayHostsCounters();
      }
   }



   function refreshPage($onlyreduced=false) {

      // Toolbox::logInFile("pm", "refreshPage : $onlyreduced\n");
      if (!$onlyreduced) {
         if (isset($_POST['_refresh'])) {
            $_SESSION['glpi_plugin_monitoring']['_refresh'] = $_POST['_refresh'];
         }

         echo '<meta http-equiv ="refresh" content="'.$_SESSION['glpi_plugin_monitoring']['_refresh'].'">';
      }

      echo "<form name='form' method='post' action='".$_SERVER["PHP_SELF"]."' >";
      echo "<div align='right'>";
      if (!$onlyreduced) {
            echo __('Page refresh (in seconds)', 'monitoring')." : ";
            echo "&nbsp;";
            Dropdown::showNumber("_refresh", array(
                   'value' => $_SESSION['glpi_plugin_monitoring']['_refresh'],
                   'min'   => 30,
                   'max'   => 1000,
                   'step'  => 10)
            );
            echo "&nbsp;";
      }
      echo __('Reduced interface', 'monitoring')." : ";
      echo "&nbsp;";
      Dropdown::showYesNo("reduced_interface", $_SESSION['plugin_monitoring_reduced_interface']);
      echo "&nbsp;";
      echo "<input type='submit' name='sessionupdate' class='submit' value=\"".__('Post')."\">";
      echo "</div>";
      Html::closeForm();
   }



   function displayPuce($scriptname, $items_id='') {
      global $CFG_GLPI;

      $split = explode("/", $_SERVER['PHP_SELF']);
      if ($split[(count($split) -1)] == $scriptname.".php") {
         $display = 0;
         if ($items_id != '') {
            if (isset($_GET['id'])
                    && $_GET['id'] == $items_id) {
               $display = 1;
            }
         } else {
            $display = 1;
         }
         if ($display == 1) {
            echo "<img src='".$CFG_GLPI['root_doc']."/pics/right.png' /> ";
         }
      }
   }
}

?>
