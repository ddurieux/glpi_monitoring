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
   @since     2012
 
   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginMonitoringDisplayview_item extends CommonDBTM {
   

   /**
   * Get name of this type
   *
   *@return text name of this type by language of the user connected
   *
   **/
   static function getTypeName($nb=0) {
      return __('Views', 'monitoring');
   }



   static function canCreate() {
      return PluginMonitoringProfile::haveRight("view", 'w');
   }


   
   static function canView() {
      return PluginMonitoringProfile::haveRight("view", 'r');
   }


   
   function canCancel() {
      return PluginMonitoringProfile::haveRight("view", 'w');
   }


   
   function canUndo() {
      return PluginMonitoringProfile::haveRight("view", 'w');
   }


   
   function canValidate() {
      return true;
   }

   
   
   function view($id, $config=0) {
      global $DB, $CFG_GLPI;

      $pmDisplayview       = new PluginMonitoringDisplayview();
      $pmDisplayview_rule  = new PluginMonitoringDisplayview_rule();
      
      $pmDisplayview->getFromDB($id);
      
         echo "<script type='text/javascript'>
            function fittext(itemid) {
               document.getElementById(itemid).style.fontSize = '50px';
               var fontsize = 50;
               while(document.getElementById(itemid).offsetWidth > 120) {
                  fontsize--;
                  if (fontsize > 20) {
                     fontsize--;
                  }
                  document.getElementById(itemid).style.fontSize = fontsize + 'px';
               }
               while(document.getElementById(itemid).offsetHeight > 67) {
                  fontsize--;
                  document.getElementById(itemid).style.fontSize = fontsize + 'px';
               }
               if (fontsize > 30) {
                  document.getElementById(itemid).style.fontSize = '30px';
               }
               if (fontsize < 7) {
                  document.getElementById(itemid).style.fontSize = '7px';
               }
            }
         </script>";
      
      PluginMonitoringServicegraph::loadLib();
      
      if ($config == '1') {
         $this->addItem($id);
         $pmDisplayview_rule->showReplayRulesForm($id);
         echo "<div id='updatecoordonates'></div>";
      } else {
         if (!is_null($pmDisplayview->fields['counter'])) {
            $pmDisplay = new PluginMonitoringDisplay();
            $pmDisplay->showCounters($pmDisplayview->fields['counter']);
         }
      }
      
      echo "<table class='tab_cadre_fixe' id='test' style='width:".$pmDisplayview->fields['width']."px'>";
      
      echo "<tr class='tab_bg_1'>";
      echo "<th>";
      
      echo $pmDisplayview->fields['name'];
      echo "</th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<th>";
      echo "<a onClick='Ext.get(\"date_text\").toggle();Ext.get(\"date_select\").toggle();".
              "Ext.get(\"time_text\").toggle();Ext.get(\"time_select\").toggle();'>
      <img src='".$CFG_GLPI["root_doc"]."/pics/deplier_down.png' />&nbsp;
         ".__('Date and time select', 'monitoring')."
      &nbsp;<img src='".$CFG_GLPI["root_doc"]."/pics/deplier_down.png' /></a>";
      echo "</th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1' id='date_text' style='display: none;'>";
      echo "<th>";
      echo __('Select date', 'monitoring');
      echo "</th>";
      echo "</tr>";
      
      echo "<tr class='tab_bg_1' id='date_select' style='display: none;'>";
      echo "<td>";

      $query = "SELECT * FROM `glpi_plugin_monitoring_displayviews_items`
         WHERE `plugin_monitoring_displayviews_id`='".$id."'
            AND `itemtype`='PluginMonitoringService'";
      $result = $DB->query($query);
      $a_items = array();
      $end = time();
      $start = time();

      $pmComponent = new PluginMonitoringComponent();
      while ($data=$DB->fetch_array($result)) {
         $itemtype = $data['itemtype'];
         $item = new $itemtype();
         $item->getFromDB($data['items_id']);
         if (isset($item->fields['plugin_monitoring_components_id'])) {
            $oldvalue = current(getAllDatasFromTable(
                    'glpi_plugin_monitoring_serviceevents', 
                    "`plugin_monitoring_services_id`='".$data['items_id']."'",
                    false,
                    'date ASC LIMIT 1'));
            $date = new DateTime($oldvalue['date']);
            if ($date->getTimestamp() < $start) {
               $start = $date->getTimestamp();
            }
            $pmComponent->getFromDB($item->fields['plugin_monitoring_components_id']);
         
            $a_items["item".$data['id']] = array(
                'rrdtool_template'  => $pmComponent->fields['graph_template'],
                'itemtype'          => $data['itemtype'],
                'items_id'          => $data['items_id'],
                'timezone'          => 0,
                'time'              => $data['extra_infos'],
                'pmComponents_id'   => $pmComponent->fields['id']
            );
         }
      }

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
                  ";
               $pmServicegraph = new PluginMonitoringServicegraph();
               foreach ($a_items as $js_id=>$js_data) {
                  echo "mgr".$js_data['items_id'].$js_data['time'].".stopAutoRefresh();\n";
                  $pmServicegraph->startAutoRefresh($js_data['rrdtool_template'],
                                                    $js_data['itemtype'],
                                                    $js_data['items_id'],
                                                    $js_data['timezone'],
                                                    $js_data['time'],
                                                    $js_data['pmComponents_id']);
               }
               echo "
            }
        }
    });

});
</script>";
      echo '<center><div id="custom-tip-slider"></div></center>';
      echo "</td>";
      echo "</tr>";   

     echo "<tr class='tab_bg_1' id='time_text' style='display: none;'>";
      echo "<th>";
      echo __('Select time', 'monitoring');
      echo "</th>";
      echo "</tr>";
      
      echo "<tr class='tab_bg_1' id='time_select' style='display: none;'>";
      echo "<td>";
      
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
                  ";
               $pmServicegraph = new PluginMonitoringServicegraph();
               foreach ($a_items as $js_id=>$js_data) {
                  echo "mgr".$js_data['items_id'].$js_data['time'].".stopAutoRefresh();\n";
                  $pmServicegraph->startAutoRefresh($js_data['rrdtool_template'],
                                                    $js_data['itemtype'],
                                                    $js_data['items_id'],
                                                    $js_data['timezone'],
                                                    $js_data['time'],
                                                    $js_data['pmComponents_id']);
               }
               echo "
            }
        }
    });

});
</script>";
      echo '<center><div id="custom-tip-slider-time"></div></center>';
      echo "</td>";
      echo "</tr>";   

      
      echo "<tr class='tab_bg_1'>";
      echo "<th>";
      echo __('View', 'monitoring');
      echo "</th>";
      echo "</tr>";
      
      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo "<input type='hidden' name='updateviewid' id='updateviewid' value='".$id."' />";
      if ($config == 0) {
         echo "<div id='filariane'>&nbsp;</div>";
         echo "<input type='hidden' name='updatefil' id='updatefil' value='".$id."!' />";
         
      
         echo "<script type=\"text/javascript\">
            function reloadfil() {
               Ext.get('filariane').load({
                   url: '".$CFG_GLPI["root_doc"]."/plugins/monitoring/ajax/updateFilariane.php',
                   scripts: true,
                      params:'updatefil=' + Ext.get('updatefil').getValue() + '&id=".$_GET['id'].
                 "&currentview=' + Ext.get('updateviewid').getValue()
               });
            }
            reloadfil();
         </script>";
      }
      echo "</td>";
      echo "</tr>";

         
      echo "<tr class='tab_bg_1'>";
      echo "<td height='1200' id='panel'>";

      echo '<div id="custom_date" style="display:none"></div>';
      echo '<div id="custom_time" style="display:none"></div>';
      
      echo "<div id='viewform'>";
      echo "<script type='text/javascript'>
        
        //Simple 'border layout' panel to house both grids
        var displayPanel = new Ext.Panel({
          id       : 'viewpanel',
          width    : ".$pmDisplayview->fields['width'].",
          height   : 1200,
          layout: 'absolute',
          renderTo : 'panel',
          items    : []
        });

      </script>";
      
      echo "</div>";
      echo "<script type=\"text/javascript\">
         function reloadview() {
            Ext.get('viewform').load({
                url: '".$CFG_GLPI["root_doc"]."/plugins/monitoring/ajax/loadView.php',
                scripts: true,
                   params:'id=' + Ext.get('updateviewid').getValue() + '&config=".$config."'
            });
         }
         reloadview();
      </script>";
      
      echo "</td>";
      echo "</tr>";
      echo "</table>";
      echo "<br/>";
      
   }
   
   
   
   function reloadView($id, $config) {
      global $DB;
      
      $pmDisplayview = new PluginMonitoringDisplayview();
      $pmDisplayview->getFromDB($id);
      
      $query = "SELECT * FROM `glpi_plugin_monitoring_displayviews_items`
         WHERE `plugin_monitoring_displayviews_id`='".$id."'";
      $result = $DB->query($query);
      $a_items = array();
      while ($data=$DB->fetch_array($result)) {
         if ($this->displayItem($data, $config)) {
            $a_items[] = "item".$data['id'];
         }
      }
      


      echo "<script type='text/javascript'>
        
        Ext.getCmp('viewpanel').items.each(function(c){Ext.getCmp('viewpanel').remove(c);});
        Ext.getCmp('viewpanel').setWidth('".$pmDisplayview->fields['width']."');
        ";
      if (count($a_items) > 0) {
        echo "Ext.getCmp('viewpanel').add(".implode(",", $a_items).");
        Ext.getCmp('viewpanel').doLayout();
        ";
      }
      echo "</script>";
   }
   
   
   
   function displayItem($data, $config) {
      global $CFG_GLPI;

      $itemtype = $data['itemtype'];
      $itemtype2 = '';
      if ($itemtype == 'host'
              || $itemtype == 'service') {
         $itemtype2 = $itemtype;
         $itemtype = 'PluginMonitoringDisplayview';
      }
      $item = new $itemtype();
      $content = '';
      $title = $item->getTypeName();
      $event = '';
      $width='';
      if ($itemtype == "PluginMonitoringService") {
         $content = $item->showWidget($data['items_id'], $data['extra_infos']);
         if (!isset($item->fields['plugin_monitoring_components_id'])) {
            return false;
         }
         $title .= " : <a href=\"".$CFG_GLPI['root_doc']."/plugins/monitoring/front/display.form.php?itemtype=PluginMonitoringService&items_id=".$data['items_id']."\">".
            Dropdown::getDropdownName(getTableForItemType('PluginMonitoringComponent'), 
                                      $item->fields['plugin_monitoring_components_id']);
         $title .= '</a> '.__('on', 'monitoring').' ';
         $pmComponentscatalog_Host = new PluginMonitoringComponentscatalog_Host();
         $pmComponentscatalog_Host->getFromDB($item->fields["plugin_monitoring_componentscatalogs_hosts_id"]);
         if (isset($pmComponentscatalog_Host->fields['itemtype']) 
                 AND $pmComponentscatalog_Host->fields['itemtype'] != '') {

            $itemtype2 = $pmComponentscatalog_Host->fields['itemtype'];
            $item2 = new $itemtype2();
            $item2->getFromDB($pmComponentscatalog_Host->fields['items_id']);
            $title .= str_replace("'", "\"", $item2->getLink()." (".$item2->getTypeName()." / ".$data['extra_infos'].")");            
         }
         $width = "width: 475,";
      } else if ($itemtype == "PluginMonitoringWeathermap") {
         $content = $item->showWidget($data['items_id'], $data['extra_infos']);
         $content = '<div id="weathermap-'.$data['items_id'].'">'.$content."</div>";
         $event = ", ".$item->widgetEvent($data['items_id']);
         $title .= " : ".Dropdown::getDropdownName(getTableForItemType('PluginMonitoringWeathermap'), $data['items_id']);
         $item->getFromDB($data['items_id']);
         $width = "width:".(($item->fields['width'] * $data['extra_infos']) / 100).",";
      } else {
         if ($itemtype2 != '') {
            $content = $item->showWidget2($data['id']);
         } else {
            $content = $item->showWidget($data['items_id']);
         }
         if ($data['itemtype'] == 'PluginMonitoringServicescatalog') {
            $width = "width: 202,";
         } else {
            $width = "width: 180,";
         }
      }
      if ($config == 0
              && $itemtype != "PluginMonitoringService") {
         $title = '';
      }
      echo "<script>
         var left = 0;
         var topd = 0;
         var obj = document.getElementById('panel');
         if (obj.offsetParent) {
           do {
             left += obj.offsetLeft;
             topd += obj.offsetTop;
           } while (obj = obj.offsetParent);
         }

        var item".$data['id']." = new Ext.Panel({
             closable: true,           
             title: '".$title."',
             x: ".$data['x'].",
             y: ".$data['y'].",
             html       : '".$content."',
             baseCls : 'x-panel',
             layout : 'fit',
             bodyStyle: 'background:transparent',
             ";
      if ($config == 0
              && $itemtype != "PluginMonitoringService") {
         echo "border: false,";
      }
       echo "renderTo: Ext.getBody(),
             floating: false,
             frame: false,
             ".$width."
             autoHeight  : true,
             layout: 'fit',
             draggable: {
                 //Config option of Ext.Panel.DD class.
                 //It's a floating Panel, so do not show a placeholder proxy in the original position.
                 insertProxy: false,

                 //Called for each mousemove event while dragging the DD object.
                 onDrag : function(e){
                     //Record the x,y position of the drag proxy so that we can
                     //position the Panel at end of drag.
                     var el = this.proxy.getEl();
                     this.x = el.getLeft(true) - left - 5;
                     this.y = el.getTop(true) - topd - 5;


                     //Keep the Shadow aligned if there is one.
                     var s = this.panel.getEl().shadow;
                     if (s) {
                         s.realign(this.x, this.y, pel.getWidth(), pel.getHeight());
                     }
                 },

                 //Called on the mouseup event.
                 endDrag : function(e){
                     this.panel.setPosition(this.x, this.y);\n";
      if ($config == '1') {
         echo "      Ext.get('updatecoordonates').load({
                        url: '".$CFG_GLPI['root_doc']."/plugins/monitoring/ajax/displayview_itemcoordinates.php',
                        scripts: true,
                        params:'id=".$data['id']."&x=' + (this.x)  + '&y=' + (this.y)
                     });\n";
         echo "      if (this.x < 1) {
                        this.panel.destroy();
                     }
                     if (this.y < 0) {
                        this.panel.destroy();
                     }
            
            ";
      }
      echo "      }
             }
             ".$event."
         });
     </script>";//.show()

      if ($itemtype == "PluginMonitoringService") {
         $pmComponent = new PluginMonitoringComponent();
         $item = new $itemtype();
         
         $item->getFromDB($data['items_id']);
         $pmComponent->getFromDB($item->fields['plugin_monitoring_components_id']);
         $pmServicegraph = new PluginMonitoringServicegraph();
         $pmServicegraph->displayGraph($pmComponent->fields['graph_template'], 
                                       "PluginMonitoringService", 
                                       $data['items_id'], 
                                       "0", 
                                       $data['extra_infos'], 
                                       "js");
      } else if($itemtype == "PluginMonitoringComponentscatalog") {
         $pmComponentscatalog = new PluginMonitoringComponentscatalog();
         $pmComponentscatalog->ajaxLoad($data['items_id']);
      } else if($itemtype == "PluginMonitoringServicescatalog") {
         $pmServicescatalog = new PluginMonitoringServicescatalog();
         $pmServicescatalog->ajaxLoad($data['items_id']);
      } else if ($itemtype2 != '') {
         $pmDisplayview = new PluginMonitoringDisplayview();
         $pmDisplayview->ajaxLoad2($data['id']);
      } else if($itemtype == "PluginMonitoringDisplayview") {
         $pmDisplayview = new PluginMonitoringDisplayview();
         $pmDisplayview->ajaxLoad($data['items_id']);
      }
      
      if ($itemtype == "PluginMonitoringWeathermap") {
//         echo "<script type='text/javascript'>
//            function updateimagew".$data['items_id']."() {
//               var demain=new Date();
//               document.getElementById('weathermap-".$data['items_id']."').innerHTML = demain.getTime() + '".$content."';
//            }
//            setInterval(updateimagew".$data['items_id'].", 50000);
//         </script>";
//      }
         echo "<script type='text/javascript'>
         var mgr = new Ext.UpdateManager('weathermap-".$data['items_id']."');
         mgr.startAutoRefresh(50, \"".$CFG_GLPI["root_doc"]."/plugins/monitoring/ajax/widgetWeathermap.php\", \"id=".$data['items_id']."&extra_infos=".$data['extra_infos']."\", \"\", true);
         </script>";
      }
      return true;
   }

   
   
   function addItem($displayviews_id) {
      global $DB,$CFG_GLPI;

      $this->getEmpty();
      
      $pmDisplayview = new PluginMonitoringDisplayview();
      $pmDisplayview->getFromDB($displayviews_id);
      
      // Manage entity_sons
      $a_entities = array();
      if (!($pmDisplayview->fields['entities_id']<0)) {
         if ($pmDisplayview->fields['is_recursive'] == '0') {
            $a_entities[$pmDisplayview->fields['entities_id']] = $pmDisplayview->fields['entities_id'];
         } else {
            $a_entities = getSonsOf('glpi_entities', $pmDisplayview->fields['entities_id']);
         }
      }
      
      $options = array();
      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo "<input type='hidden' name='plugin_monitoring_displayviews_id' value='".$displayviews_id."' />";
      echo __('Element to display', 'monitoring')." :</td>";
      echo "<td>";
      $elements = array();
      $elements['NULL'] = Dropdown::EMPTY_VALUE;
      $elements['PluginMonitoringDisplayview']         = __('Views', 'monitoring');
      $elements['PluginMonitoringServicescatalog']     = __('Business rules', 'monitoring');
//      $elements['service']                             = __('Resources (info)', 'monitoring');
      $elements['host']                                = __('Host (info)', 'monitoring');
      $elements['PluginMonitoringService']       = __('Resources (graph)', 'monitoring');
      $elements['PluginMonitoringComponentscatalog']   = __('Components catalog', 'monitoring');
      $elements['PluginMonitoringWeathermap']          = __('Weathermap', 'monitoring');
      
      $rand = Dropdown::showFromArray('itemtype', $elements, array('value'=>$this->fields['itemtype']));
      
      $params = array('itemtype'        => '__VALUE__',
                'displayviews_id' => $displayviews_id,
                'myname'          => "items_id",
                'a_entities' => $a_entities);

      Ajax::updateItemOnSelectEvent("dropdown_itemtype".$rand,"items_id",
                                  $CFG_GLPI["root_doc"]."/plugins/monitoring/ajax/dropdownDisplayviewItemtype.php",
                                  $params);
      echo "<span id='items_id'></span>";
      echo "<input type='hidden' name='x' value='1' />";
      echo "<input type='hidden' name='y' value='1' />";
      echo "</td>";

      echo "<td colspan='2'></td>";
      echo "</tr>";
      
      $this->showFormButtons($options);

      return true;
   }
   
   
   
   function getCounterOfViews($id, $a_counter) {
      $a_views = $this->find("`itemtype`='PluginMonitoringDisplayview'"
              ." AND `plugin_monitoring_displayviews_id`='".$id."'");
      foreach ($a_views as $data) {
         $a_counter = $this->getCounterOfViews($data['items_id'], $a_counter);
      }
      $a_counter = $this->getCounterOfView($id, $a_counter);
      return $a_counter;
   }
   
   
   
   function getCounterOfView($id, $a_counter) {
      global $DB;
                  

      $a_hosts = $this->find("`itemtype`='host'"
              ." AND `plugin_monitoring_displayviews_id`='".$id."'");

      foreach ($a_hosts as $data) {
         $query = "SELECT * FROM `glpi_plugin_monitoring_services`"
                          . " LEFT JOIN `glpi_plugin_monitoring_componentscatalogs_hosts`"
                          . "    ON `plugin_monitoring_componentscatalogs_hosts_id`="
                          . " `glpi_plugin_monitoring_componentscatalogs_hosts`.`id`"
                          . " WHERE `items_id`='".$data['items_id']."'"
                          . "    AND `itemtype`='".$data['extra_infos']."'"
                          . "    AND `glpi_plugin_monitoring_services`.`id` IS NOT NULL";
         
         $result = $DB->query($query);
         while ($data2=$DB->fetch_array($result)) {
            $ret = PluginMonitoringDisplay::getState($data2['state'], 
                                                     $data2['state_type'], 
                                                     '', 
                                                     $data2['is_acknowledged']);
            if (strstr($ret, '_soft')) {
               $a_counter['ok']++;
            } else if ($ret == 'red') {
               $a_counter['critical']++;
            } else if ($ret == 'redblue') {
               $a_counter['acknowledge']++;
            } else if ($ret == 'orange'
                    || $ret == 'yellow') {
               $a_counter['warning']++;
            } else {
               $a_counter['ok']++;
            }

         }
      }
      return $a_counter;
   }
}

?>
