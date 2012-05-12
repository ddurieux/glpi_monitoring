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

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginMonitoringDisplay extends CommonDBTM {
   
   
   function defineTabs($options=array()){
      global $LANG,$CFG_GLPI;

      if (isset($_GET['glpi_tab'])) {
         setActiveTab("PluginMonitoringDisplay",$_GET['glpi_tab']);
      }
      
      $pmDisplayview = new PluginMonitoringDisplayview();
      
      $ong = array();
      if (PluginMonitoringProfile::haveRight("servicescatalog", 'r')) {
         $ong[1] = $LANG['plugin_monitoring']['servicescatalog'][0];
      }
      if (PluginMonitoringProfile::haveRight("componentscatalog", 'r')) {
         $ong[2] = $LANG['plugin_monitoring']['componentscatalog'][0];
      }
      $ong[3] = $LANG['plugin_monitoring']['service'][21];
      $ong[4] = $LANG['plugin_monitoring']['dependency'][0];
      if (PluginMonitoringProfile::haveRight("view", 'r')) {
         $i = 5;
         $a_views = $pmDisplayview->getViews();
         foreach ($a_views as $views_id=>$name) {
            $ong[$i] = htmlentities($name);
            $i++;
         }
      }
      return $ong;
   }
   
   
   
   function showTabs($options=array()) {
      global $LANG, $CFG_GLPI;
      
      // for objects not in table like central
      if (isset($this->fields['id'])) {
         $ID = $this->fields['id'];
      } else {
        $ID = 0;
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
         $cleantarget = cleanParametersURL($target);
         echo "<ul>";
         echo "<li><a href=\"javascript:showHideDiv('tabsbody','tabsbodyimg','".$CFG_GLPI["root_doc"].
                    "/pics/deplier_down.png','".$CFG_GLPI["root_doc"]."/pics/deplier_up.png')\">";
         echo "<img alt='' name='tabsbodyimg' src=\"".$CFG_GLPI["root_doc"]."/pics/deplier_up.png\">";
         echo "</a></li>";

         echo "<li><a href=\"".$glpilisturl."\">";

         if ($glpilisttitle) {
            if (utf8_strlen($glpilisttitle) > $_SESSION['glpidropdown_chars_limit']) {
               $glpilisttitle = utf8_substr($glpilisttitle, 0,
                                            $_SESSION['glpidropdown_chars_limit'])
                                . "&hellip;";
            }
            echo $glpilisttitle;

         } else {
            echo $LANG['common'][53];
         }
         echo "</a>&nbsp;:&nbsp;</li>";

         if ($first > 0) {
            echo "<li><a href='$cleantarget?id=$first$extraparamhtml'><img src='".
                       $CFG_GLPI["root_doc"]."/pics/first.png' alt=\"".$LANG['buttons'][55].
                       "\" title=\"".$LANG['buttons'][55]."\"></a></li>";
         } else {
            echo "<li><img src='".$CFG_GLPI["root_doc"]."/pics/first_off.png' alt=\"".
                       $LANG['buttons'][55]."\" title=\"".$LANG['buttons'][55]."\"></li>";
         }

         if ($prev > 0) {
            echo "<li><a href='$cleantarget?id=$prev$extraparamhtml'><img src='".
                       $CFG_GLPI["root_doc"]."/pics/left.png' alt=\"".$LANG['buttons'][12].
                       "\" title=\"".$LANG['buttons'][12]."\"></a></li>";
         } else {
            echo "<li><img src='".$CFG_GLPI["root_doc"]."/pics/left_off.png' alt=\"".
                       $LANG['buttons'][12]."\" title=\"".$LANG['buttons'][12]."\"></li>";
         }

         if ($current !== false) {
            echo "<li>".($current+1) . "/" . count($glpilistitems)."</li>";
         }

         if ($next > 0) {
            echo "<li><a href='$cleantarget?id=$next$extraparamhtml'><img src='".
                       $CFG_GLPI["root_doc"]."/pics/right.png' alt=\"".$LANG['buttons'][11].
                       "\" title=\"".$LANG['buttons'][11]."\"></a></li>";
         } else {
            echo "<li><img src='".$CFG_GLPI["root_doc"]."/pics/right_off.png' alt=\"".
                       $LANG['buttons'][11]."\" title=\"".$LANG['buttons'][11]."\"></li>";
         }

         if ($last > 0) {
            echo "<li><a href='$cleantarget?id=$last$extraparamhtml'><img src=\"".
                       $CFG_GLPI["root_doc"]."/pics/last.png\" alt=\"".$LANG['buttons'][56].
                       "\" title=\"".$LANG['buttons'][56]."\"></a></li>";
         } else {
            echo "<li><img src='".$CFG_GLPI["root_doc"]."/pics/last_off.png' alt=\"".
                       $LANG['buttons'][56]."\" title=\"".$LANG['buttons'][56]."\"></li>";
         }
         echo "</ul></div>";
         echo "<div class='sep'></div>";
      }

      echo "<div id='tabspanel' class='center-h'></div>";

      $active      = 0;
      $onglets     = $this->defineTabs($options);
      $display_all = true;
      if (isset($onglets['no_all_tab'])) {
         $display_all = false;
         unset($onglets['no_all_tab']);
      }
      $class = $this->getType();
      if ($_SESSION['glpi_use_mode']==DEBUG_MODE
          && ($ID > 0 || $this->showdebug)
          && (method_exists($class, 'showDebug')
              || in_array($class, $CFG_GLPI["infocom_types"])
              || in_array($class, $CFG_GLPI["reservation_types"]))) {

            $onglets[-2] = $LANG['setup'][137];
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
            $tabs[-1] = array('title'  => $LANG['common'][66],
                              'url'    => $tabpage,
                              'params' => "target=$target&itemtype=".$this->getType().
                                          "&glpi_tab=-1&id=$ID$extraparam");
         }
         createAjaxTabs('tabspanel', 'tabcontent', $tabs, $this->getType(), "'100%'");
      }
   }

   

   function showBoard($width='', $limit='') {
      global $DB,$CFG_GLPI,$LANG;

      $where = '';
      if ($limit == 'hosts') {
         $where = "`plugin_monitoring_services_id`='0' ";
      } else if ($limit == 'services') {
         $where = "`plugin_monitoring_services_id`>0 ";
      }      
      if (isset($_SESSION['plugin_monitoring']['service']['field'])) {
         foreach ($_SESSION['plugin_monitoring']['service']['field'] as $key=>$value) {
            $wheretmp = '';
            if (isset($_SESSION['plugin_monitoring']['service']['link'][$key])) {
               $wheretmp.= " ".$_SESSION['plugin_monitoring']['service']['link'][$key]." ";
            }
            $wheretmp .= Search::addWhere(
                                   "",
                                   0,
                                   "PluginMonitoringService",
                                   $_SESSION['plugin_monitoring']['service']['field'][$key],
                                   $_SESSION['plugin_monitoring']['service']['searchtype'][$key],
                                   $_SESSION['plugin_monitoring']['service']['contains'][$key]);
            if (!strstr($wheretmp, "``.``")) {
               if ($where != ''
                       AND !isset($_SESSION['plugin_monitoring']['service']['link'][$key])) {
                  $where .= " AND ";
               }
               $where .= $wheretmp;
            }
         }
      }
      if ($where != '') {
         $where .= " AND ";
      }
      $where .= ' `glpi_plugin_monitoring_services`.`entities_id` IN ('.$_SESSION['glpiactiveentities_string'].')';

      if ($where != '') {
         $where = " WHERE ".$where;
         $where = str_replace("`".getTableForItemType("PluginMonitoringDisplay")."`.", 
                 "", $where);
         
      }
      
      $leftjoin = '';
      if (isset($_SESSION['plugin_monitoring']['service']['field'])) {         
         foreach ($_SESSION['plugin_monitoring']['service']['field'] as $key=>$value) {
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
      printPager($_GET['start'], $numrows, $CFG_GLPI['root_doc']."/plugins/monitoring/front/display.php", $parameters);

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
      echo $LANG['joblist'][0];
      echo "</th>";
      
      echo "<th>";
      echo $LANG['entity'][0];
      echo "</th>";
      
      echo "<th>";
      echo $LANG['state'][6]." - ".$LANG['common'][16];
      echo "</th>";
      echo "<th>";
      echo $LANG['plugin_monitoring']['component'][0];
      echo "</th>";
      echo "<th>";
      echo $LANG['state'][0];
      echo "</th>";
      echo "<th>";
      echo $LANG['stats'][7];
      echo "</th>";
      echo "<th>";
      echo $LANG['plugin_monitoring']['service'][18];
      echo "</th>";
      echo "<th>";
      echo $LANG['rulesengine'][82];
      echo "</th>";     
      echo "</tr>";
      while ($data=$DB->fetch_array($result)) {
         echo "<tr class='tab_bg_3'>";

         $this->displayLine($data);
         
         echo "</tr>";         
      }
      echo "</table>";
      echo "<br/>";
      printPager($_GET['start'], $numrows, $CFG_GLPI['root_doc']."/plugins/monitoring/front/display.php", $parameters);

   }
   
   
   
   static function displayLine($data, $displayhost=1) {
      global $DB,$CFG_GLPI,$LANG;

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
            echo "<td>".$LANG['plugin_monitoring']['service'][0]."</td>";
         }
      }
      $pMonitoringComponent->getFromDB($data['plugin_monitoring_components_id']);
      echo "<td>".$pMonitoringComponent->getLink();
      if (!is_null($pMonitoringService->fields['networkports_id'])
              AND $pMonitoringService->fields['networkports_id'] > 0) {
         $networkPort->getFromDB($pMonitoringService->fields['networkports_id']);
         echo " [".$networkPort->getLink()."]";
      }
      echo "</td>";
      $nameitem = '';
      if (isset($itemmat->fields['name'])) {
         $nameitem = "[".$itemmat->getLink(1)."]";
      }
      //if ($pMonitoringService->fields['plugin_monitoring_services_id'] == '0') {
         //echo "<td>".$itemmat->getLink(1)."</td>";
//      } else {
//         $pMonitoringServiceH->getFromDB($pMonitoringService->fields['plugin_monitoring_services_id']);
//         $itemtypemat = $pMonitoringServiceH->fields['itemtype'];
//         $itemmat = new $itemtypemat();
//         $itemmat->getFromDB($pMonitoringServiceH->fields['items_id']);
//         echo "<td>".$pMonitoringService->getLink(1).$nameitem." ".$LANG['networking'][25]." ".$itemmat->getLink(1)."</td>";
//      }
      
      

      unset($itemmat);
      echo "<td class='center'>";
      echo $data['state'];
      echo "</td>";

      echo "<td class='center'>";
      $to = new PluginMonitoringRrdtool();
      $plu = new PluginMonitoringServiceevent();
      $img = '';
      $timezone = '0';
      if (isset($_SESSION['plugin_monitoring_timezone'])) {
         $timezone = $_SESSION['plugin_monitoring_timezone'];
      }
      $timezone_file = str_replace("+", ".", $timezone);
      
      $img = "<img src='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/send.php?file=PluginMonitoringService-".$data['id']."-2h".$timezone_file.".gif'/>";
         

      echo "<a href='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/display.form.php?itemtype=PluginMonitoringService&items_id=".$data['id']."'>";
      if (file_exists(GLPI_ROOT."/files/_plugins/monitoring/PluginMonitoringService-".$data['id']."-2h".$timezone_file.".gif")) {
         $img = "<img src='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/send.php?file=PluginMonitoringService-".$data['id']."-2h".$timezone_file.".gif'/>";
         showToolTip($img, array('img'=>$CFG_GLPI['root_doc']."/plugins/monitoring/pics/stats_32.png"));
      } else {
         
      }
      echo "</a>";
      echo "</td>";

      echo "<td>";
      echo convDate($data['last_check']).' '. substr($data['last_check'], 11, 8);
      echo "</td>";

      echo "<td>";
      echo $data['event'];
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
                 $LANG['plugin_monitoring']['service'][25].$cnt."</a>";
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
      global $CFG_GLPI,$LANG;

      $to = new PluginMonitoringRrdtool();
      $plu = new PluginMonitoringServiceevent();
      $pmComponent = new PluginMonitoringComponent();
      $pmConfig = new PluginMonitoringConfig();

      $item = new $itemtype();
      $item->getFromDB($items_id);
 
      $pmComponent->getFromDB($item->fields['plugin_monitoring_components_id']);

      echo "<table class='tab_cadre_fixe'>";
      
      echo "<tr class='tab_bg_1'>";
      echo "<th>";
      echo $item->getLink(1);
      echo "</th>";
      echo "<th width='200'>";
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
      echo "&nbsp;<input type='submit' name='update' value=\"".$LANG['buttons'][7]."\" class='submit'>";
      echo "</form>";
      echo "</th>";
      echo "</tr>";

      $a_list = array();
      $a_list[] = "2h";
      $a_list[] = "12h";
      $a_list[] = "1d";
      $a_list[] = "1w";
      $a_list[] = "1m";
      $a_list[] = "0y6m";
      $a_list[] = "1y";
       
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
         
         $to->displayGLPIGraph($pmComponent->fields['graph_template'], 
                               $itemtype, 
                               $items_id, 
                               $timezone, 
                               $time);
         $img = "<img src='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/send.php?file=".$itemtype."-".$items_id."-".$time.$timezone_file.".gif'/>";
         echo $img;
         echo "</td>";
         echo "</tr>";
      }
      echo "</table>";
   }
   
   
   
   function displayCounters($type, $display=1) {
      global $DB,$CFG_GLPI,$LANG;
      
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
                          AND `state_type`='HARD'";
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
                             AND `state_type`='HARD'";
               $result = $DB->query($query);
               $data2 = $DB->fetch_assoc($result);
               if ($data2['cpt'] > 0) {
                  $warning++;
               } else {
                  $query = "SELECT COUNT(*) AS cpt FROM `".$pmComponentscatalog_Host->getTable()."`
                     LEFT JOIN `glpi_plugin_monitoring_services` 
                        ON `plugin_monitoring_componentscatalogs_hosts_id`=`".$pmComponentscatalog_Host->getTable()."`.`id`
                     WHERE `plugin_monitoring_componentscalalog_id`='".$data['id']."'
                        AND (`state`='OK' OR `state`='UP') AND `state_type`='HARD'";
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
                          AND `state_type`='SOFT'";
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
                             AND `state_type`='SOFT'";
               $result = $DB->query($query);
               $data2 = $DB->fetch_assoc($result);
               if ($data2['cpt'] > 0) {
                  $warning_soft++;
               } else {
                  $query = "SELECT COUNT(*) AS cpt FROM `".$pmComponentscatalog_Host->getTable()."`
                     LEFT JOIN `glpi_plugin_monitoring_services` 
                        ON `plugin_monitoring_componentscatalogs_hosts_id`=`".$pmComponentscatalog_Host->getTable()."`.`id`
                     WHERE `plugin_monitoring_componentscalalog_id`='".$data['id']."'
                        AND (`state`='OK' OR `state`='UP') AND `state_type`='SOFT'";
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
                 "(`state`='OK' OR `state`='UP') AND `state_type`='HARD'");

         $warning = countElementsInTable("glpi_plugin_monitoring_servicescatalogs", 
                 "(`state`='WARNING' OR `state`='UNKNOWN' OR `state`='RECOVERY' OR `state`='FLAPPING' OR `state` IS NULL)
                    AND `state_type`='HARD'");

         $critical = countElementsInTable("glpi_plugin_monitoring_servicescatalogs", 
                 "(`state`='DOWN' OR `state`='UNREACHABLE' OR `state`='CRITICAL' OR `state`='DOWNTIME')
                    AND `state_type`='HARD'");

         $warning_soft = countElementsInTable("glpi_plugin_monitoring_servicescatalogs", 
                 "(`state`='WARNING' OR `state`='UNKNOWN' OR `state`='RECOVERY' OR `state`='FLAPPING' OR `state` IS NULL)
                    AND `state_type`='SOFT'");

         $critical_soft = countElementsInTable("glpi_plugin_monitoring_servicescatalogs", 
                 "(`state`='DOWN' OR `state`='UNREACHABLE' OR `state`='CRITICAL' OR `state`='DOWNTIME')
                    AND `state_type`='SOFT'");

         $ok_soft = countElementsInTable("glpi_plugin_monitoring_servicescatalogs", 
                 "(`state`='OK' OR `state`='UP') AND `state_type`='SOFT'");
         
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
      // *** Test new presentation
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
         if ($type == 'Ressources') {
            echo "<a href='".$critical_link.">".
                    "<font color='black' style='font-size: 12px;font-weight: bold;'>".$LANG['plugin_monitoring']['display'][2]."</font></a>";
         } else {
            echo $LANG['plugin_monitoring']['display'][2];
         }
         echo "</td>";
         echo "</tr>";
         echo "<tr>";
         echo "<th style='background-color:transparent;'>";
         if ($type == 'Ressources') {
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
         if ($type == 'Ressources') {
            echo "<a href='".$warning_link.">".
                    "<font color='black' style='font-size: 12px;font-weight: bold;'>".$LANG['plugin_monitoring']['display'][3]."</font></a>";
         } else {
            echo $LANG['plugin_monitoring']['display'][3];
         }
         echo "</td>";
         echo "</tr>";
         echo "<tr>";
         echo "<th style='background-color:transparent;'>";
         if ($type == 'Ressources') {
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
         if ($type == 'Ressources') {
            echo "<a href='".$ok_link.">".
                    "<font color='black' style='font-size: 12px;font-weight: bold;'>".$LANG['plugin_monitoring']['display'][4]."</font></a>";
         } else {
            echo $LANG['plugin_monitoring']['display'][4];
         }
         echo "</td>";
         echo "</tr>";
         echo "<tr>";
         echo "<th style='background-color:transparent;'>";
         if ($type == 'Ressources') {
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

   
   
   function refreshPage() {
      global $LANG;
      
      echo "<form name='form' method='post' action='".$_SERVER["PHP_SELF"]."' >";
         echo "<table width='100%'>";
         echo "<tr>";
         echo "<td align='right'>";
         echo $LANG['plugin_monitoring']['display'][1]." : ";
         echo "&nbsp;";
         Dropdown::showInteger("_refresh", $_SESSION['glpi_plugin_monitoring']['_refresh'], 30, 1000, 10);
         echo "&nbsp;";
         echo "<input type='submit' name='sessionupdate' class='submit' value=\"".$LANG['buttons'][2]."\">";
         echo "</td>";
         echo "</tr>";
         echo "</table>";
      echo "</form>";
      
   }
   
   
}

?>