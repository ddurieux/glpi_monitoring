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

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginMonitoringComponentscatalog_rule extends CommonDBTM {
   

   /**
   * Get name of this type
   *
   *@return text name of this type by language of the user connected
   *
   **/
   static function getTypeName() {
      global $LANG;

      return "Rules";
   }



   function canCreate() {
      return true;
   }


   
   function canView() {
      return true;
   }


   
   function canCancel() {
      return true;
   }


   
   function canUndo() {
      return true;
   }


   
   function canValidate() {
      return true;
   }

   

   function showRules($componentscatalogs_id) {
      global $DB,$CFG_GLPI,$LANG;

      $this->addRule($componentscatalogs_id);

      $rand = mt_rand();

      $query = "SELECT * FROM `".$this->getTable()."`
         WHERE `plugin_monitoring_componentscalalog_id`='".$componentscatalogs_id."'";
      $result = $DB->query($query);
      echo "<table class='tab_cadre_fixe'>";
      echo "<tr>";
      echo "<th>";
      echo $LANG['plugin_monitoring']['rule'][0];
      echo "</th>";
      echo "</tr>";
      echo "</table>";

      echo "<table class='tab_cadre_fixe'>";     
      
      echo "<tr>";
      echo "<th width='10'>&nbsp;</th>";
      echo "<th>".$LANG['common'][17]."</th>";
      echo "<th>".$LANG['entity'][0]."</th>";
      echo "<th>".$LANG['common'][16]."</th>";
      echo "</tr>";
      
      while ($data=$DB->fetch_array($result)) {         
         echo "<tr>";
         echo "<td>";
         echo "<input type='checkbox' name='item[".$data["id"]."]' value='1'>";
         echo "</td>";
         echo "<td class='center'>";
         echo $data['itemtype'];
         echo "</td>";
         echo "<td class='center'>";

         echo "</td>";
         echo "<td class='center'>";
         echo $data['name'];
         echo "</td>";
         echo "</tr>";         
      }
      
      echo "</table>";

      return true;
   }
   
   
   
   function addRule($componentscatalogs_id) {
      
      $param = array();
      if (isset($_SESSION['plugin_monitoring_rules'])) {
         $param = $_SESSION['plugin_monitoring_rules'];
      }
      $_GET = $param;
      if (isset($_SESSION['plugin_monitoring_rules_REQUEST_URI'])) {
         $_SERVER['REQUEST_URI'] = $_SESSION['plugin_monitoring_rules_REQUEST_URI'];
      }
      $itemtype = 'Computer';
      if (isset($_SESSION['plugin_monitoring_rules']['itemtype'])) {
         $itemtype = $_SESSION['plugin_monitoring_rules']['itemtype'];
      }
      Search::manageGetValues($itemtype);
      $this->showGenericSearch($itemtype, $param);
      
   }
   

   
   
   /*
    * Cloned Core function to display with our require.
    */
   function showGenericSearch($itemtype, $params) {
      global $LANG, $CFG_GLPI;

      // Default values of parameters
      $p['link']        = array();//
      $p['field']       = array();
      $p['contains']    = array();
      $p['searchtype']  = array();
      $p['sort']        = '';
      $p['is_deleted']  = 0;
      $p['link2']       = '';//
      $p['contains2']   = '';
      $p['field2']      = '';
      $p['itemtype2']   = '';
      $p['searchtype2'] = '';

      foreach ($params as $key => $val) {
         $p[$key] = $val;
      }

      $options = Search::getCleanedOptions($itemtype);
      $target  = getItemTypeSearchURL($itemtype);

      // Instanciate an object to access method
      $item = NULL;
      if ($itemtype!='States' && class_exists($itemtype)) {
         $item = new $itemtype();
      }

      $linked =  Search::getMetaItemtypeAvailable($itemtype);
      $this->showFormHeader();
//      echo "<form name='searchform$itemtype' method='get' action=\"".
//              $CFG_GLPI['root_doc']."/plugins/monitoring/front/componentscatalog_rule.form.php\">";
//      echo "<table class='tab_cadre_fixe'>";
      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo $LANG['common'][16]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      echo "<input type='text' name='name' value='".$_SESSION['plugin_monitoring_rules']['name']."'/>";
      echo "</td>";
      echo "<td>";
      echo $LANG['state'][6]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Dropdown::dropdownTypes("itemtype", 
                              $_SESSION['plugin_monitoring_rules']['itemtype'],
                              $CFG_GLPI['networkport_types']);
      echo "</td>";
      echo "</tr>";
      if (isset($_SESSION['plugin_monitoring_rules']['itemtype'])) {
      echo "<tr class='tab_bg_1'>";
      echo "<td colspan='3'>";      
      echo "<table>";

      // Display normal search parameters
      for ($i=0 ; $i<$_SESSION["glpisearchcount"][$itemtype] ; $i++) {
         echo "<tr><td class='left' width='50%'>";

         // First line display add / delete images for normal and meta search items
         if ($i==0) {
            echo "<input type='hidden' disabled id='add_search_count' name='add_search_count'
                   value='1'>";
            echo "<a href='#' onClick = \"document.getElementById('add_search_count').disabled=false;
                   document.forms['searchform$itemtype'].submit();\">";
            echo "<img src=\"".$CFG_GLPI["root_doc"]."/pics/plus.png\" alt='+' title=\"".
                   $LANG['search'][17]."\"></a>&nbsp;&nbsp;&nbsp;&nbsp;";
            if ($_SESSION["glpisearchcount"][$itemtype]>1) {
               echo "<input type='hidden' disabled id='delete_search_count'
                      name='delete_search_count' value='1'>";
               echo "<a href='#' onClick = \"document.getElementById('delete_search_count').disabled=false;
                      document.forms['searchform$itemtype'].submit();\">";
               echo "<img src=\"".$CFG_GLPI["root_doc"]."/pics/moins.png\" alt='-' title=\"".
                     $LANG['search'][18]."\"></a>&nbsp;&nbsp;&nbsp;&nbsp;";
            }
            if (is_array($linked) && count($linked)>0) {
               echo "<input type='hidden' disabled id='add_search_count2' name='add_search_count2'
                      value='1'>";
               echo "<a href='#' onClick=\"document.getElementById('add_search_count2').disabled=false;
                      document.forms['searchform$itemtype'].submit();\">";
               echo "<img src=\"".$CFG_GLPI["root_doc"]."/pics/meta_plus.png\" alt='+' title=\"".
                      $LANG['search'][19]."\"></a>&nbsp;&nbsp;&nbsp;&nbsp;";

               if ($_SESSION["glpisearchcount2"][$itemtype]>0) {
                  echo "<input type='hidden' disabled id='delete_search_count2'
                         name='delete_search_count2' value='1'>";
                  echo "<a href='#' onClick=\"document.getElementById('delete_search_count2').disabled=false;
                         document.forms['searchform$itemtype'].submit();\">";
                  echo "<img src=\"".$CFG_GLPI["root_doc"]."/pics/meta_moins.png\" alt='-' title=\"".
                        $LANG['search'][20]."\"></a>&nbsp;&nbsp;&nbsp;&nbsp;";
               }
            }

            $itemtable = getTableForItemType($itemtype);
//            if ($item && $item->maybeDeleted()) {
//               echo "<input type='hidden' id='is_deleted' name='is_deleted' value='".
//                      $p['is_deleted']."'>";
//               echo "<a href='#' onClick = \"toogle('is_deleted','','','');
//                      document.forms['searchform$itemtype'].submit();\">
//                      <img src=\"".$CFG_GLPI["root_doc"]."/pics/showdeleted".
//                       (!$p['is_deleted']?'_no':'').".png\" name='img_deleted'  alt=\"".
//                       (!$p['is_deleted']?$LANG['common'][3]:$LANG['common'][81])."\" title=\"".
//                       (!$p['is_deleted']?$LANG['common'][3]:$LANG['common'][81])."\" ></a>";
//               // Dropdown::showYesNo("is_deleted",$p['is_deleted']);
//               echo '&nbsp;&nbsp;';
//            }
         }



         // Display link item
         if ($i>0) {
            echo "<select name='link[$i]'>";
            echo "<option value = 'AND' ";
            if (is_array($p["link"]) && isset($p["link"][$i]) && $p["link"][$i] == "AND") {
               echo "selected";
            }
            echo ">AND</option>\n";

            echo "<option value='OR' ";
            if (is_array($p["link"]) && isset($p["link"][$i]) && $p["link"][$i] == "OR") {
               echo "selected";
            }
            echo ">OR</option>\n";

            echo "<option value='AND NOT' ";
            if (is_array($p["link"]) && isset($p["link"][$i]) && $p["link"][$i] == "AND NOT") {
               echo "selected";
            }
            echo ">AND NOT</option>\n";

            echo "<option value='OR NOT' ";
            if (is_array($p["link"]) && isset($p["link"][$i]) && $p["link"][$i] == "OR NOT") {
               echo "selected";
            }
            echo ">OR NOT</option>";
            echo "</select>&nbsp;";
         }


         // display select box to define search item
         echo "<select id='Search$itemtype$i' name=\"field[$i]\" size='1'>";
         echo "<option value='view' ";
         if (is_array($p['field']) && isset($p['field'][$i]) && $p['field'][$i] == "view") {
            echo "selected";
         }
         echo ">".$LANG['search'][11]."</option>\n";

         reset($options);
         $first_group = true;
         $selected    = 'view';
         $str_limit   = 28;
         $nb_in_group = 0;
         $group = '';

         foreach ($options as $key => $val) {
            // print groups
            if (!is_array($val)) {
               if (!$first_group) {
                  $group .= "</optgroup>\n";
               } else {
                  $first_group = false;
               }
               if ($nb_in_group) {
                  echo $group;
               }
               $group       = '';
               $nb_in_group = 0;

               $group .= "<optgroup label=\"".utf8_substr($val,0,$str_limit)."\">";
            } else {
               if (!isset($val['nosearch']) || $val['nosearch']==false) {
                  $nb_in_group ++;
                  $group .= "<option title=\"".cleanInputText($val["name"])."\" value='$key'";
                  if (is_array($p['field']) && isset($p['field'][$i]) && $key == $p['field'][$i]) {
                     $group .= "selected";
                     $selected = $key;
                  }
                  $group .= ">". utf8_substr($val["name"], 0, $str_limit) ."</option>\n";
               }
            }
         }
         if (!$first_group) {
            $group .= "</optgroup>\n";
         }
         if ($nb_in_group) {
            echo $group;
         }

         echo "<option value='all' ";
         if (is_array($p['field']) && isset($p['field'][$i]) && $p['field'][$i] == "all") {
            echo "selected";
         }
         echo ">".$LANG['common'][66]."</option>";
         echo "</select>\n";

         echo "</td><td class='left'>";
         echo "<div id='SearchSpan$itemtype$i'>\n";

         $_POST['itemtype']   = $itemtype;
         $_POST['num']        = $i;
         $_POST['field']      = $selected;
         $_POST['searchtype'] = (is_array($p['searchtype'])
                                 && isset($p['searchtype'][$i])?$p['searchtype'][$i]:"" );
         $_POST['value']      = (is_array($p['contains'])
                                 && isset($p['contains'][$i])?stripslashes($p['contains'][$i]):"" );
         include (GLPI_ROOT."/ajax/searchoption.php");
         echo "</div>\n";

         $params = array('field'      => '__VALUE__',
                         'itemtype'   => $itemtype,
                         'num'        => $i,
                         'value'      => $_POST["value"],
                         'searchtype' => $_POST["searchtype"]);
         ajaxUpdateItemOnSelectEvent("Search$itemtype$i", "SearchSpan$itemtype$i",
                                     $CFG_GLPI["root_doc"]."/ajax/searchoption.php", $params, false);

         echo "</td></tr>\n";
      }


      $metanames = array();

      if (is_array($linked) && count($linked)>0) {
         for ($i=0 ; $i<$_SESSION["glpisearchcount2"][$itemtype] ; $i++) {
            echo "<tr><td class='left' colspan='2'>";
            $rand = mt_rand();

            echo "<table width='100%'><tr class='left'><td width='35%'>";
            // Display link item (not for the first item)
            echo "<select name='link2[$i]'>";
            echo "<option value='AND' ";
            if (is_array($p['link2']) && isset($p['link2'][$i]) && $p['link2'][$i] == "AND") {
               echo "selected";
            }
            echo ">AND</option>\n";

            echo "<option value='OR' ";
            if (is_array($p['link2']) && isset($p['link2'][$i]) && $p['link2'][$i] == "OR") {
               echo "selected";
            }
            echo ">OR</option>\n";

            echo "<option value='AND NOT' ";
            if (is_array($p['link2']) && isset($p['link2'][$i]) && $p['link2'][$i] == "AND NOT") {
               echo "selected";
            }
            echo ">AND NOT</option>\n";

            echo "<option value='OR NOT' ";
            if (is_array($p['link2'] )&& isset($p['link2'][$i]) && $p['link2'][$i] == "OR NOT") {
               echo "selected";
            }
            echo ">OR NOT</option>\n";
            echo "</select>&nbsp;";

            // Display select of the linked item type available
            echo "<select name='itemtype2[$i]' id='itemtype2_".$itemtype."_".$i."_$rand'>";
            echo "<option value=''>".DROPDOWN_EMPTY_VALUE."</option>";
            foreach ($linked as $key) {
               if (!isset($metanames[$key])) {
                  $linkitem = new $key();
                  $metanames[$key] = $linkitem->getTypeName();
               }
               echo "<option value='$key'>".utf8_substr($metanames[$key], 0, 20)."</option>\n";
            }
            echo "</select>&nbsp;";

            echo "</td><td>";
            // Ajax script for display search met& item
            echo "<span id='show_".$itemtype."_".$i."_$rand'>&nbsp;</span>\n";

            $params = array('itemtype'    => '__VALUE__',
                            'num'         => $i,
                            'field'       => (is_array($p['field2'])
                                              && isset($p['field2'][$i])?$p['field2'][$i]:""),
                            'value'       => (is_array($p['contains2'])
                                              && isset($p['contains2'][$i])?$p['contains2'][$i]:""),
                            'searchtype2' => (is_array($p['searchtype2'])
                                              && isset($p['searchtype2'][$i])?$p['searchtype2'][$i]:""));

            ajaxUpdateItemOnSelectEvent("itemtype2_".$itemtype."_".$i."_$rand","show_".$itemtype."_".
                                          $i."_$rand",$CFG_GLPI["root_doc"]."/ajax/updateMetaSearch.php",
                                        $params, false);

            if (is_array($p['itemtype2'])
                && isset($p['itemtype2'][$i])
                && !empty($p['itemtype2'][$i])) {

               $params['itemtype'] = $p['itemtype2'][$i];
               ajaxUpdateItem("show_".$itemtype."_".$i."_$rand",
                              $CFG_GLPI["root_doc"]."/ajax/updateMetaSearch.php", $params, false);
               echo "<script type='text/javascript' >";
               echo "window.document.getElementById('itemtype2_".$itemtype."_".$i."_$rand').value='".
                                                    $p['itemtype2'][$i]."';";
               echo "</script>\n";
            }
            echo "</td></tr></table>";

            echo "</td></tr>\n";
         }
      }
      echo "</table>\n";
      echo "</td>\n";

      echo "<td width='150px'>";
      echo "<table width='100%'>";
      // Display sort selection
/*      echo "<tr><td colspan='2'>".$LANG['search'][4];
      echo "&nbsp;<select name='sort' size='1'>";
      reset($options);
      $first_group=true;
      foreach ($options as $key => $val) {
         if (!is_array($val)) {
            if (!$first_group) {
               echo "</optgroup>\n";
            } else {
               $first_group=false;
            }
            echo "<optgroup label=\"$val\">";
         } else {
            echo "<option value='$key'";
            if ($key == $p['sort']) {
               echo " selected";
            }
            echo ">".utf8_substr($val["name"],0,20)."</option>\n";
         }
      }
      if (!$first_group) {
         echo "</optgroup>\n";
      }
      echo "</select> ";
      echo "</td></tr>\n";
*/
      // Display deleted selection

      echo "<tr>";

      // Display submit button
      echo "<td width='80' class='center'>";
      echo "<input type='submit' value=\"".$LANG['buttons'][0]."\" class='submit' >";
      echo "</td><td>";
//      Bookmark::showSaveButton(BOOKMARK_SEARCH,$itemtype);
      echo "<a href='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/componentscatalog_rule.form.php?reset=reset' >";
      echo "&nbsp;&nbsp;<img title=\"".$LANG['buttons'][16]."\" alt=\"".$LANG['buttons'][16]."\" src='".
            $CFG_GLPI["root_doc"]."/pics/reset.png' class='calendrier'></a>";

      echo "</td></tr>";
      
      echo "</table>\n";

      echo "</td></tr>";
      echo "<tr>";
      }
      echo "<td colspan='4' class='center'>";
      echo "<input type='submit' name='addrule' value=\"Add this rule\" class='submit' >";
      echo "</td>";
      echo "</tr>";
      echo "</table>\n";

      // For dropdown
      echo "<input type='hidden' name='itemtype' value='$itemtype'>";

      // Reset to start when submit new search
      echo "<input type='hidden' name='start' value='0'>";
      
      echo "</form>";
   }

   


}

?>