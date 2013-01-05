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

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginMonitoringDisplay extends CommonDBTM {
   
   function menu() {
      global $CFG_GLPI;
      
      echo "<table class='tab_cadre_fixe' width='950'>";
      echo "<tr class='tab_bg_3'>";
      echo "<td>";
      
      echo "<table class='tab_cadre_fixe' width='950'>";
      echo "<tr class='tab_bg_1'>";
      echo "<th width='33%' colspan='2'>";
      if (PluginMonitoringProfile::haveRight("servicescatalog", 'r')) {
         $this->displayPuce('display_servicescatalog');
         echo "<a href='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/display_servicescatalog.php'>";
         echo __('Services catalog', 'monitoring');
         echo "</a>";
      }
      echo "</th>";
      echo "<th width='33%' colspan='2'>";
      if (PluginMonitoringProfile::haveRight("componentscatalog", 'r')) {
         $this->displayPuce('display_componentscatalog');
         echo "<a href='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/display_componentscatalog.php'>";
         echo __('Components catalog', 'monitoring');
         echo "</a>";
      }
      echo "</th>";
      echo "<th colspan='2'>";
      $this->displayPuce('service');
      echo "<a href='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/service.php'>";
      echo __('All resources', 'monitoring');
      echo "</a>";
      echo "</th>";
      echo "</tr>";
      echo "</table>";
      

      $i = 1;
      if (PluginMonitoringProfile::haveRight("view", 'r')) {
         $pmDisplayview = new PluginMonitoringDisplayview();
         $a_views = $pmDisplayview->getViews();
         if (count($a_views) > 0) {
            echo "<table class='tab_cadre_fixe' width='950'>";
            echo "<tr class='tab_bg_1'>";

            foreach ($a_views as $views_id=>$name) {
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
   }
   
   
   
   function defineTabs($options=array()){
      global $CFG_GLPI;

      if (isset($_GET['glpi_tab'])) {
         Session::setActiveTab("PluginMonitoringDisplay",$_GET['glpi_tab']);
      }
      
      $pmDisplayview = new PluginMonitoringDisplayview();
      
      $ong = array();
      if (PluginMonitoringProfile::haveRight("servicescatalog", 'r')) {
         $ong[1] = __('Services catalog', 'monitoring');
      }
      if (PluginMonitoringProfile::haveRight("componentscatalog", 'r')) {
         $ong[2] = __('Components catalog', 'monitoring');
      }
      $ong[3] = __('All resources', 'monitoring');
      $ong[4] = __('Dependencies;', 'monitoring');
      if (PluginMonitoringProfile::haveRight("view", 'r')) {
         $i = 5;
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

   

   function showBoard($width='', $limit='') {
      global $DB,$CFG_GLPI;

      $where = '';
      if ($limit == 'hosts') {
         $where = "`plugin_monitoring_services_id`='0' ";
      } else if ($limit == 'services') {
         $where = "`plugin_monitoring_services_id`>0 ";
      }      
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
      
      $leftjoin = '';
      if (isset($_GET['field'])) {         
         foreach ($_GET['field'] as $value) {
            if ($value == '20'
                    OR $value == '21'
                    OR $value == '22') {
               $leftjoin .= " LEFT JOIN `glpi_plugin_monitoring_componentscatalogs_hosts`
                  ON `plugin_monitoring_componentscatalogs_hosts_id` = 
                  `glpi_plugin_monitoring_componentscatalogs_hosts`.`id` ";
            } else if ($value == '7') {
               $leftjoin .= " LEFT JOIN `glpi_plugin_monitoring_components`
                  ON `plugin_monitoring_components_id` = 
                  `glpi_plugin_monitoring_components`.`id` ";
            } else if ($value == '8') {
               if (!strstr($leftjoin, 'LEFT JOIN `glpi_plugin_monitoring_componentscatalogs_hosts`')) {
                  $leftjoin .= " LEFT JOIN `glpi_plugin_monitoring_componentscatalogs_hosts`
                  ON `plugin_monitoring_componentscatalogs_hosts_id` = 
                  `glpi_plugin_monitoring_componentscatalogs_hosts`.`id` ";
               }
               if (!strstr($leftjoin, 'LEFT JOIN `glpi_plugin_monitoring_componentscatalogs`')) {
                  $leftjoin .= " LEFT JOIN `glpi_plugin_monitoring_componentscatalogs`
                     ON `glpi_plugin_monitoring_componentscatalogs_hosts`.`plugin_monitoring_componentscalalog_id` = 
                     `glpi_plugin_monitoring_componentscatalogs`.`id` ";
               }
            }
         }
      }

      $query = "SELECT `".getTableForItemType("PluginMonitoringService")."`.* FROM `".getTableForItemType("PluginMonitoringService")."`
         ".$leftjoin."
         ".$where."
         ORDER BY `name`";
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
      if (strstr($_SERVER['PHP_SELF'], "displayfix.php")) {
         $parameters .= "&tab=".$_GET['tab'];
         Html::printPager($_GET['start'], $numrows, $CFG_GLPI['root_doc']."/plugins/monitoring/front/displayfix.php", $parameters);
      } else {
         Html::printPager($_GET['start'], $numrows, $CFG_GLPI['root_doc']."/plugins/monitoring/front/display.php", $parameters);
      }

      $limit = $numrows;
      if ($_SESSION["glpilist_limit"] < $numrows) {
         $limit = $_SESSION["glpilist_limit"];
      }
      $query .= " LIMIT ".intval($start)."," . intval($_SESSION['glpilist_limit']);
      
      $result = $DB->query($query);      
      if ($width == '') {
         echo "<table class='tab_cadrehov' style='width:100%;'>";
      } else {
         echo "<table class='tab_cadrehov' style='width:100%;'>";
      }
      
      echo "<tr class='tab_bg_1'>";
      echo "<th>";
      echo __('Status');
      echo "</th>";
      
      echo "<th>";
      echo __('Entity');
      echo "</th>";
      echo "<th>";
      echo __('Show graphics');
      echo "</th>";
      echo "<th>";
      echo __('Item type')." - ".__('Name');
      echo "</th>";
      echo "<th>";
      echo __('Components', 'monitoring');
      echo "</th>";
      echo "<th>";
      echo __('Status');
      echo "</th>";
      echo "<th>";
      echo __('Last check', 'monitoring');
      echo "</th>";
      echo "<th>";
      echo __('Result details');
      echo "</th>";
      echo "<th>";
      echo __('Check period', 'monitoring');
      echo "</th>";
      echo "</tr>";
      PluginMonitoringServicegraph::loadLib();
      while ($data=$DB->fetch_array($result)) {
         echo "<tr class='tab_bg_3'>";

         $this->displayLine($data);
         
         echo "</tr>";         
      }
      echo "</table>";
      echo "<br/>";
      if (strstr($_SERVER['PHP_SELF'], "displayfix.php")) {
         $parameters .= "&tab=".$_GET['tab'];
         Html::printPager($_GET['start'], $numrows, $CFG_GLPI['root_doc']."/plugins/monitoring/front/displayfix.php", $parameters);
      } else {
         Html::printPager($_GET['start'], $numrows, $CFG_GLPI['root_doc']."/plugins/monitoring/front/display.php", $parameters);
      }
   }
   
   
   
   static function displayLine($data, $displayhost=1) {
      global $DB,$CFG_GLPI;

      $pMonitoringService = new PluginMonitoringService();
      $networkPort = new NetworkPort();
      $pMonitoringComponent = new PluginMonitoringComponent();
      $pmComponentscatalog_Host = new PluginMonitoringComponentscatalog_Host();
      $entity = new Entity();
      
      $pMonitoringService->getFromDB($data['id']);
      
      echo "<td width='32' class='center'>";
      $shortstate = self::getState($data['state'], $data['state_type']);
      echo "<img src='".$CFG_GLPI['root_doc']."/plugins/monitoring/pics/box_".$shortstate."_32.png'/>";
      echo "</td>";
      echo "<td>";
      $entity->getFromDB($data['entities_id']);
      echo $entity->fields['completename'];
      echo "</td>";
      

      $pMonitoringComponent->getFromDB($data['plugin_monitoring_components_id']);
      echo "<td class='center'>";
      $timezone = '0';
      if (isset($_SESSION['plugin_monitoring_timezone'])) {
         $timezone = $_SESSION['plugin_monitoring_timezone'];
      }
      $timezone_file = str_replace("+", ".", $timezone);
         
      if ($pMonitoringComponent->fields['graph_template'] != '') {
         echo "<a href='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/display.form.php?itemtype=PluginMonitoringService&items_id=".$data['id']."'>";
         $pmServicegraph = new PluginMonitoringServicegraph();
         ob_start();
         $pmServicegraph->displayGraph($pMonitoringComponent->fields['graph_template'], 
                                       "PluginMonitoringService", 
                                       $data['id'], 
                                       "0", 
                                       '2h', 
                                       "div", 
                                       "600");
         $chart = ob_get_contents();
         ob_end_clean();
         $chart = "<table width='600' class='tab_cadre'><tr><td>".$chart."</td></tr></table>";
         Html::showToolTip($chart, array('img'=>$CFG_GLPI['root_doc']."/plugins/monitoring/pics/stats_32.png"));
         $pmServicegraph->displayGraph($pMonitoringComponent->fields['graph_template'], 
                                       "PluginMonitoringService", 
                                       $data['id'], 
                                       "0", 
                                       '2h', 
                                       "js");
      }
      echo "</a>";
      echo "</td>";
      
      if ($displayhost == '1') {
         $pmComponentscatalog_Host->getFromDB($data["plugin_monitoring_componentscatalogs_hosts_id"]);
         if (isset($pmComponentscatalog_Host->fields['itemtype']) 
                 AND $pmComponentscatalog_Host->fields['itemtype'] != '') {

            $itemtype = $pmComponentscatalog_Host->fields['itemtype'];
            $item = new $itemtype();
            $item->getFromDB($pmComponentscatalog_Host->fields['items_id']);
            echo "<td>";
            echo $item->getTypeName()." : ".$item->getLink();
            if (!is_null($pMonitoringService->fields['networkports_id'])
                    AND $pMonitoringService->fields['networkports_id'] > 0) {
               $networkPort->getFromDB($pMonitoringService->fields['networkports_id']);
               echo " [".$networkPort->getLink()."]";
            }
            echo "</td>";

         } else {
            echo "<td>".__('Resources', 'monitoring')."</td>";
         }
      }

      echo "<td>".$pMonitoringComponent->getLink();
      if (!is_null($pMonitoringService->fields['networkports_id'])
              AND $pMonitoringService->fields['networkports_id'] > 0) {
         $networkPort->getFromDB($pMonitoringService->fields['networkports_id']);
         echo " [".$networkPort->getLink()."]";
      }
      echo "</td>";
//      $nameitem = '';
//      if (isset($itemmat->fields['name'])) {
//         $nameitem = "[".$itemmat->getLink(1)."]";
//      }
      //if ($pMonitoringService->fields['plugin_monitoring_services_id'] == '0') {
         //echo "<td>".$itemmat->getLink(1)."</td>";
//      } else {
//         $pMonitoringServiceH->getFromDB($pMonitoringService->fields['plugin_monitoring_services_id']);
//         $itemtypemat = $pMonitoringServiceH->fields['itemtype'];
//         $itemmat = new $itemtypemat();
//         $itemmat->getFromDB($pMonitoringServiceH->fields['items_id']);
//         echo "<td>".$pMonitoringService->getLink(1).$nameitem." ".__('on', 'monitoring')." ".$itemmat->getLink(1)."</td>";
//      }
//      unset($itemmat);
      echo "<td class='center'>";
      echo $data['state'];
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
      
      if ($displayhost == '0') {
         $pmUnavaibility = new PluginMonitoringUnavaibility();
         $pmUnavaibility->displayValues($pMonitoringService->fields['id'], 'currentmonth', 1);
         $pmUnavaibility->displayValues($pMonitoringService->fields['id'], 'lastmonth', 1);
         $pmUnavaibility->displayValues($pMonitoringService->fields['id'], 'currentyear', 1);
         
         echo "<td>";
         $a_arg = importArrayFromDB($pMonitoringService->fields['arguments']);
         $cnt = '';
         if (count($a_arg) > 0) {
            $cnt = " (".count($a_arg).")";
         }
         echo "<a href='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/servicearg.form.php?id=".$data['id']."'>".
                 __('Configure', 'monitoring').$cnt."</a>";
         echo "</td>";
      }
   }

   
   
   static function getState($state, $state_type) {
      $shortstate = '';
      switch($state) {

         case 'UP':
         case 'OK':
            $shortstate = 'green';
            break;

         case 'DOWN':
         case 'UNREACHABLE':
         case 'CRITICAL':
         case 'DOWNTIME':
            $shortstate = 'red';
            break;

         case 'WARNING':
         case 'UNKNOWN':
         case 'RECOVERY':
         case 'FLAPPING':
         case '':
            $shortstate = 'orange';
            break;

      }
      if ($state_type == 'SOFT') {
         $shortstate.= '_soft';
      }
      return $shortstate;
   }
   
   
   
   function displayGraphs($itemtype, $items_id) {
      global $CFG_GLPI;

      $pmComponent              = new PluginMonitoringComponent();
      $pmConfig                 = new PluginMonitoringConfig();
      $pmComponentscatalog_Host = new PluginMonitoringComponentscatalog_Host();

      $item = new $itemtype();
      $item->getFromDB($items_id);
      $pmComponent->getFromDB($item->fields['plugin_monitoring_components_id']);
      if(!isset($_SESSION['glpi_plugin_monitoring']['perfname'][$pmComponent->fields['id']])) {
         PluginMonitoringServicegraph::loadPreferences($pmComponent->fields['id']);
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
         PluginMonitoringServicegraph::preferences($pmComponent->fields['id'], 0);
         echo "</div>";
         echo "</td>";
         echo "</tr>";
      }      

      $a_list = array();
      $a_list[] = "2h";
      $a_list[] = "12h";
      $a_list[] = "1d";
      if (!isset($_GET['mobile'])) {
         $a_list[] = "1w";
         $a_list[] = "1m";
         $a_list[] = "0y6m";
         $a_list[] = "1y";
      }
       
      foreach ($a_list as $time) {
      
      echo "<tr class='tab_bg_1'>";
      echo "<th colspan='2'>";
      echo $time;
      echo "</th>";
      echo "</tr>";
         
         echo "<tr class='tab_bg_1'>";
         echo "<td align='center' colspan='2'>";
         $img = '';
         $timezone = '0';
         if (isset($_SESSION['plugin_monitoring_timezone'])) {
            $timezone = $_SESSION['plugin_monitoring_timezone'];
         }
         $timezone_file = str_replace("+", ".", $timezone);

         $pmServicegraph = new PluginMonitoringServicegraph();
         $part = '';
         $width='';
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
      $warning = 0;
      $critical = 0;
      $ok_soft = 0;
      $warning_soft = 0;
      $critical_soft = 0;
      
      $play_sound = 0;
      
      if ($type == 'Ressources') {
         
         $ok = countElementsInTable("glpi_plugin_monitoring_services", 
                 "(`state`='OK' OR `state`='UP') AND `state_type`='HARD'
                    AND `entities_id` IN (".$_SESSION['glpiactiveentities_string'].")");

         $warning = countElementsInTable("glpi_plugin_monitoring_services", 
                 "(`state`='WARNING' OR `state`='UNKNOWN' OR `state`='RECOVERY' OR `state`='FLAPPING' OR `state` IS NULL)
                    AND `state_type`='HARD'
                    AND `entities_id` IN (".$_SESSION['glpiactiveentities_string'].")");

         $critical = countElementsInTable("glpi_plugin_monitoring_services", 
                 "(`state`='DOWN' OR `state`='UNREACHABLE' OR `state`='CRITICAL' OR `state`='DOWNTIME')
                    AND `state_type`='HARD'
                    AND `entities_id` IN (".$_SESSION['glpiactiveentities_string'].")");

         $warning_soft = countElementsInTable("glpi_plugin_monitoring_services", 
                 "(`state`='WARNING' OR `state`='UNKNOWN' OR `state`='RECOVERY' OR `state`='FLAPPING' OR `state` IS NULL)
                    AND `state_type`='SOFT'
                    AND `entities_id` IN (".$_SESSION['glpiactiveentities_string'].")");

         $critical_soft = countElementsInTable("glpi_plugin_monitoring_services", 
                 "(`state`='DOWN' OR `state`='UNREACHABLE' OR `state`='CRITICAL' OR `state`='DOWNTIME')
                    AND `state_type`='SOFT'
                    AND `entities_id` IN (".$_SESSION['glpiactiveentities_string'].")");

         $ok_soft = countElementsInTable("glpi_plugin_monitoring_services", 
                 "(`state`='OK' OR `state`='UP') AND `state_type`='SOFT'
                    AND `entities_id` IN (".$_SESSION['glpiactiveentities_string'].")");
         
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
                  AND `entities_id` IN (".$_SESSION['glpiactiveentities_string'].")";
            $result = $DB->query($query);
            $data2 = $DB->fetch_assoc($result);
            if ($data2['cpt'] > 0) {
               $critical++;
            } else {
               $query = "SELECT COUNT(*) AS cpt FROM `".$pmComponentscatalog_Host->getTable()."`
                  LEFT JOIN `glpi_plugin_monitoring_services` 
                     ON `plugin_monitoring_componentscatalogs_hosts_id`=`".$pmComponentscatalog_Host->getTable()."`.`id`
                  WHERE `plugin_monitoring_componentscalalog_id`='".$data['id']."'
                     AND (`state`='WARNING' OR `state`='UNKNOWN' OR `state`='RECOVERY' OR `state`='FLAPPING' OR `state` IS NULL)
                     AND `state_type`='HARD'
                     AND `entities_id` IN (".$_SESSION['glpiactiveentities_string'].")";
               $result = $DB->query($query);
               $data2 = $DB->fetch_assoc($result);
               if ($data2['cpt'] > 0) {
                  $warning++;
               } else {
                  $query = "SELECT COUNT(*) AS cpt FROM `".$pmComponentscatalog_Host->getTable()."`
                     LEFT JOIN `glpi_plugin_monitoring_services` 
                        ON `plugin_monitoring_componentscatalogs_hosts_id`=`".$pmComponentscatalog_Host->getTable()."`.`id`
                     WHERE `plugin_monitoring_componentscalalog_id`='".$data['id']."'
                     AND (`state`='OK' OR `state`='UP') AND `state_type`='HARD'
                     AND `entities_id` IN (".$_SESSION['glpiactiveentities_string'].")";
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
                  AND `entities_id` IN (".$_SESSION['glpiactiveentities_string'].")";
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
                     AND `entities_id` IN (".$_SESSION['glpiactiveentities_string'].")";
               $result = $DB->query($query);
               $data2 = $DB->fetch_assoc($result);
               if ($data2['cpt'] > 0) {
                  $warning_soft++;
               } else {
                  $query = "SELECT COUNT(*) AS cpt FROM `".$pmComponentscatalog_Host->getTable()."`
                     LEFT JOIN `glpi_plugin_monitoring_services` 
                        ON `plugin_monitoring_componentscatalogs_hosts_id`=`".$pmComponentscatalog_Host->getTable()."`.`id`
                     WHERE `plugin_monitoring_componentscalalog_id`='".$data['id']."'
                        AND (`state`='OK' OR `state`='UP') AND `state_type`='SOFT'
                        AND `entities_id` IN (".$_SESSION['glpiactiveentities_string'].")";
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
                 AND `entities_id` IN (".$_SESSION['glpiactiveentities_string'].")");

         $warning = countElementsInTable("glpi_plugin_monitoring_servicescatalogs", 
                 "(`state`='WARNING' OR `state`='UNKNOWN' OR `state`='RECOVERY' OR `state`='FLAPPING' OR `state` IS NULL)
                    AND `state_type`='HARD'
                    AND `entities_id` IN (".$_SESSION['glpiactiveentities_string'].")");

         $critical = countElementsInTable("glpi_plugin_monitoring_servicescatalogs", 
                 "(`state`='DOWN' OR `state`='UNREACHABLE' OR `state`='CRITICAL' OR `state`='DOWNTIME')
                    AND `state_type`='HARD'
                    AND `entities_id` IN (".$_SESSION['glpiactiveentities_string'].")");

         $warning_soft = countElementsInTable("glpi_plugin_monitoring_servicescatalogs", 
                 "(`state`='WARNING' OR `state`='UNKNOWN' OR `state`='RECOVERY' OR `state`='FLAPPING' OR `state` IS NULL)
                    AND `state_type`='SOFT'
                    AND `entities_id` IN (".$_SESSION['glpiactiveentities_string'].")");

         $critical_soft = countElementsInTable("glpi_plugin_monitoring_servicescatalogs", 
                 "(`state`='DOWN' OR `state`='UNREACHABLE' OR `state`='CRITICAL' OR `state`='DOWNTIME')
                    AND `state_type`='SOFT'
                    AND `entities_id` IN (".$_SESSION['glpiactiveentities_string'].")");

         $ok_soft = countElementsInTable("glpi_plugin_monitoring_servicescatalogs", 
                 "(`state`='OK' OR `state`='UP') AND `state_type`='SOFT'
                  AND `entities_id` IN (".$_SESSION['glpiactiveentities_string'].")");
         
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
         $a_return['warning'] = strval($warning);
         $a_return['warning_soft'] = strval($warning_soft);
         $a_return['critical'] = strval($critical);
         $a_return['critical_soft'] = strval($critical_soft);
         return $a_return;
      }

      $critical_link = $CFG_GLPI['root_doc'].
               "/plugins/monitoring/front/service.php?reset=reset&field[0]=3&searchtype[0]=equals&contains[0]=CRITICAL&link[1]=OR".
                  "&field[1]=3&searchtype[1]=equals&contains[1]=DOWN&link[2]=OR".
                  "&field[2]=3&searchtype[2]=equals&contains[2]=UNREACHABLE". 
                  "&itemtype=PluginMonitoringService&start=0&glpi_tab=3'";
      $warning_link = $CFG_GLPI['root_doc'].
               "/plugins/monitoring/front/service.php?reset=reset&field[0]=3&searchtype[0]=equals&contains[0]=WARNING&link[1]=OR".
                  "&field[1]=3&searchtype[1]=equals&contains[1]=UNKNOWN&link[2]=OR".
                  "&field[2]=3&searchtype[2]=equals&contains[2]=RECOVERY&link[3]=OR".
                  "&field[3]=3&searchtype[3]=equals&contains[3]=FLAPPING&link[4]=OR".
                  "&field[5]=3&searchtype[4]=equals&contains[4]=NULL".
                  "&itemtype=PluginMonitoringService&start=0&glpi_tab=3'";
      $ok_link = $CFG_GLPI['root_doc'].
               "/plugins/monitoring/front/service.php?reset=reset&field[0]=3&searchtype[0]=equals&contains[0]=OK&link[1]=OR".
                  "&field[1]=3&searchtype[1]=equals&contains[1]=UP".
                  "&itemtype=PluginMonitoringService&start=0&glpi_tab=3'";
      
      echo "<table align='center'>";
      echo "<tr>";
      echo "<td>";
         $background = '';
         if ($critical > 0) {
            $background = 'background="'.$CFG_GLPI['root_doc'].'/plugins/monitoring/pics/bg_critical.png"';
         }
         echo "<table class='tab_cadre' width='474' height='100' ".$background." >";
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
         echo "<tr>";
         echo "<th style='background-color:transparent;'>";
         echo "<font style='font-size: 11px;'>Soft : ".$critical_soft."</font>";         
         echo "</th>";
         echo "</tr>";
         echo "</table>";         
      echo "</td>";
      
      echo "<td>";
         $background = '';
         if ($warning > 0) {
            $background = 'background="'.$CFG_GLPI['root_doc'].'/plugins/monitoring/pics/bg_warning.png"';
         }
         echo "<table class='tab_cadre' width='316' height='100' ".$background." >";
         echo "<tr>";
         echo "<th style='background-color:transparent;'>";
         if ($type == 'Ressources' OR $type == 'Componentscatalog') {
            echo "<a href='".$warning_link.">".
                    "<font color='black' style='font-size: 12px;font-weight: bold;'>".__('Warning', 'monitoring')."</font></a>";
         } else {
            echo __('Warning', 'monitoring');
         }
         echo "</td>";
         echo "</tr>";
         echo "<tr>";
         echo "<th style='background-color:transparent;'>";
         if ($type == 'Ressources' OR $type == 'Componentscatalog') {
            echo "<a href='".$warning_link.">".
                    "<font color='black' style='font-size: 52px;'>".$warning."</font></a>";
         } else {
            echo "<font style='font-size: 52px;'>".$warning."</font>";
         }
         echo "</th>";
         echo "</tr>";
         echo "<tr>";
         echo "<th style='background-color:transparent;'>";
         echo "<font style='font-size: 11px;'>Soft : ".$warning_soft."</font>";         
         echo "</th>";
         echo "</tr>";
         echo "</table>";         
      echo "</td>";
      
      echo "<td>";
         $background = '';
         if ($ok > 0) {
            $background = 'background="'.$CFG_GLPI['root_doc'].'/plugins/monitoring/pics/bg_ok.png"';
         }
         echo "<table class='tab_cadre' width='158' height='100' ".$background." >";
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
         echo "<tr>";
         echo "<th style='background-color:transparent;'>";
         echo "<font style='font-size: 11px;'>Soft : ".$ok_soft."</font>";         
         echo "</th>";
         echo "</tr>";
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
   
   
   
   function showCounters($type, $display=1, $ajax=1) { 
      global $CFG_GLPI;

      if ($display == 0) {
         return $this->displayCounters($type, $display);
      }
            
      if ($ajax == 1) {
         echo "<div id=\"updatecounter".$type."\"></div>";
         echo "<script type=\"text/javascript\">

         var elcc".$type." = Ext.get(\"updatecounter".$type."\");
         var mgrcc".$type." = elcc".$type.".getUpdateManager();
         mgrcc".$type.".loadScripts=true;
         mgrcc".$type.".showLoadIndicator=false;
         mgrcc".$type.".startAutoRefresh(50, \"".$CFG_GLPI["root_doc"]."/plugins/monitoring/ajax/updateCounter.php\", \"type=".$type."\", \"\", true);
         </script>";
      } else {
         $this->displayCounters($type);
      }
   }

   
   
   function refreshPage() {
      echo "<form name='form' method='post' action='".$_SERVER["PHP_SELF"]."' >";
         echo "<table width='100%'>";
         echo "<tr>";
         echo "<td align='right'>";
         echo __('Page refresh (in seconds)', 'monitoring')." : ";
         echo "&nbsp;";
         Dropdown::showInteger("_refresh", $_SESSION['glpi_plugin_monitoring']['_refresh'], 30, 1000, 10);
         echo "&nbsp;";
         echo "<input type='submit' name='sessionupdate' class='submit' value=\"".__('Post')."\">";
         echo "</td>";
         echo "</tr>";
         echo "</table>";
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