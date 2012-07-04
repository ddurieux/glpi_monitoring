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
   static function getTypeName() {
      global $LANG;

      return $LANG['plugin_monitoring']['displayview'][0];
   }



   function canCreate() {
      return haveRight('computer', 'w');
   }


   
   function canView() {
      return haveRight('computer', 'r');
   }


   
   function canCancel() {
      return haveRight('computer', 'w');
   }


   
   function canUndo() {
      return haveRight('computer', 'w');
   }


   
   function canValidate() {
      return true;
   }

   
   
   function view($id, $config=0) {
      global $DB,$CFG_GLPI;

      $pmDisplayview = new PluginMonitoringDisplayview();
      $pmDisplayview->getFromDB($id);
      
      if ($config == '1') {
         $this->addItem($id);
         echo "<div id='updatecoordonates'></div>";
      } else {
         if (!is_null($pmDisplayview->fields['counter'])) {
            $pmDisplay = new PluginMonitoringDisplay();
            $pmDisplay->displayCounters($pmDisplayview->fields['counter']);
         }
      }
      
      echo "<table class='tab_cadre_fixe' id='test'>";
      
      echo "<tr class='tab_bg_1'>";
      echo "<th>";
      
      echo $pmDisplayview->fields['name'];
      echo "</th>";
      echo "</tr>";
      
      echo "<tr class='tab_bg_1'>";
      echo "<td height='1000' id='panel'>";

      $query = "SELECT * FROM `glpi_plugin_monitoring_displayviews_items`
         WHERE `plugin_monitoring_displayviews_id`='".$id."'";
      $result = $DB->query($query);
      $a_items = array();
      while ($data=$DB->fetch_array($result)) {
         $this->displayItem($data, $config);
         $a_items[] = "item".$data['id'];
      }
      
echo "<script type='text/javascript'>
Ext.onReady(function() {

  //Simple 'border layout' panel to house both grids
  var displayPanel = new Ext.Panel({
    width    : 950,
    height   : 1000,
    layout: 'absolute',
    renderTo : 'panel',
    items    : [
      ".implode(",", $a_items)."
    ]
  });

});
</script>";

      
      echo "</td>";
      echo "</tr>";
      echo "</table>";
      echo "<br/>";
      
   }
   
   
   
   function displayItem($data, $config) {
      global $CFG_GLPI,$LANG;

      $itemtype = $data['itemtype'];
      $item = new $itemtype();
      $content = '';
      $title = $item->getTypeName();
      $event = '';
      if ($itemtype == "PluginMonitoringService") {
         $content = $item->showWidget($data['items_id'], $data['extra_infos']);
         $title .= " : ".Dropdown::getDropdownName(getTableForItemType('PluginMonitoringComponent'), $item->fields['plugin_monitoring_components_id']);
         $title .= ' '.$LANG['networking'][25].' ';
         $pmComponentscatalog_Host = new PluginMonitoringComponentscatalog_Host();
         $pmComponentscatalog_Host->getFromDB($item->fields["plugin_monitoring_componentscatalogs_hosts_id"]);
         if (isset($pmComponentscatalog_Host->fields['itemtype']) 
                 AND $pmComponentscatalog_Host->fields['itemtype'] != '') {

            $itemtype2 = $pmComponentscatalog_Host->fields['itemtype'];
            $item2 = new $itemtype2();
            $item2->getFromDB($pmComponentscatalog_Host->fields['items_id']);
            $title .= $item2->getName()." (".$item2->getTypeName().")";
            
         }
      } else if ($itemtype == "PluginMonitoringWeathermap") {
         $content = $item->showWidget($data['items_id'], $data['extra_infos']);
         $event = ", ".$item->widgetEvent($data['items_id']);
         $title .= " : ".Dropdown::getDropdownName(getTableForItemType('PluginMonitoringWeathermap'), $data['items_id']);
      } else {
         $content = $item->showWidget($data['items_id']);
      }
      echo "<script>
         var left = 0;
         var top = 0;
         var obj = document.getElementById('panel');
         if (obj.offsetParent) {
           do {
             left += obj.offsetLeft;
             top += obj.offsetTop;
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
             renderTo: Ext.getBody(),
             floating: true,
             frame: false,
             autoHeight  : true,
             autoWidth   : true,
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
                     this.y = el.getTop(true) - top - 5;


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
      
   }

   
   
   function addItem($displayviews_id) {
      global $DB,$CFG_GLPI,$LANG;

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
      echo $LANG['plugin_monitoring']['displayview'][3]." :</td>";
      echo "<td>";
      $elements = array();
      $elements['NULL'] = DROPDOWN_EMPTY_VALUE;
      $elements['PluginMonitoringServicescatalog'] = $LANG['plugin_monitoring']['businessrule'][0];
      $elements['PluginMonitoringComponentscatalog'] = $LANG['plugin_monitoring']['componentscatalog'][0];
      $elements['PluginMonitoringService'] = $LANG['plugin_monitoring']['service'][0];
      $elements['PluginMonitoringWeathermap'] = $LANG['plugin_monitoring']['weathermap'][0];
      $rand = Dropdown::showFromArray('itemtype', $elements, array('value'=>$this->fields['itemtype']));
      
      $params = array('itemtype'        => '__VALUE__',
                'displayviews_id' => $displayviews_id,
                'myname'          => "items_id",
                'a_entities' => $a_entities);

      ajaxUpdateItemOnSelectEvent("dropdown_itemtype".$rand,"items_id",
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
}

?>