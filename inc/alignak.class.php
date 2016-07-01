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
   @since     2016

   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginMonitoringAlignak {

   private $server = 'http://127.0.0.1:90';
   private $resource = '';

   function __construct($resource) {
      $this->resource = $resource;
      $this->abc = new Alignak_Backend_Client($this->server);
      if (isset($_SESSION['glpi_plugin_monitoring']['alignak_token'])) {
         $this->abc->token = $_SESSION['glpi_plugin_monitoring']['alignak_token'];
      } else {
         $this->abc->login('admin', 'admin');
         $_SESSION['glpi_plugin_monitoring']['alignak_token'] = $this->abc->token;
      }
   }



   function showList() {
      $params = Search::manageParams('PluginMonitoringCommand', $_GET);
      $data = Search::prepareDatasForSearch('PluginMonitoringCommand', $params);
      $this->constructDatas($data);

      // Get all data in the backend and convert data to compatible format for
      // GLPI list
      Search::displayDatas($data);
   }



   function constructDatas(array &$data) {
      global $CFG_GLPI;

      $options = array('max_results' => $data['search']['list_limit']);
      // Manage paging
      if ($data['search']['start'] > 0) {
         $options['page'] = ceil($data['search']['start']/$data['search']['list_limit']) + 1;
      }
      $searchopt = &Search::getOptions('PluginMonitoringCommand');
      // Manage sort
      $options['sort'] = $searchopt[$data['search']['sort']]["field"];
      if ($data['search']['order'] == 'DESC') {
         $options['sort'] = "-".$options['sort'];
      }

      $back_data = $this->abc->get($this->resource, $options);
      $data['data']['totalcount'] = $back_data['_meta']['total'];
      $data['data']['count'] = $back_data['_meta']['total'];
      $data['data']['begin'] = 0;
      $data['data']['end'] = 1;
      $data['data']['cols'] = array();
      $num       = 0;

      foreach ($data['toview'] as $key => $val) {
         $data['data']['cols'][$num] = array();

         $data['data']['cols'][$num]['itemtype']  = $data['itemtype'];
         $data['data']['cols'][$num]['id']        = $val;
         $data['data']['cols'][$num]['name']      = $searchopt[$val]["name"];
         $data['data']['cols'][$num]['field']     = $searchopt[$val]["field"];
         if (isset($searchopt[$val]["datatype"])) {
            $data['data']['cols'][$num]['datatype']  = $searchopt[$val]["datatype"];
         } else {
            $data['data']['cols'][$num]['datatype'] = 'string';
         }
         $data['data']['cols'][$num]['meta']      = 0;
         $data['data']['cols'][$num]['searchopt'] = $searchopt[$val];
         $num++;
      }

      $data['data']['rows'] = array();
      $i = 0;
      foreach ($back_data['_items'] as $values) {
         $data['data']['rows'][$i] = array(
             'raw' => array(),
             'id'  => $values['_id']
         );
         foreach ($data['data']['cols'] as $num=>$vals) {
            $data['data']['rows'][$i][$num] = array(
                'count' => 1,
                'displayname' => $values[$vals['field']]
            );
            if ($vals['field'] == 'name') {
               $data['data']['rows'][$i][$num]['displayname'] = "<a href='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/command.form.php?id=".$values['_id']."'>".$values[$vals['field']]."</a>";
            }
            if ($vals['datatype'] == 'bool') {
               $data['data']['rows'][$i][$num]['displayname'] = (int)$values[$vals['field']];
            }
         }
         $i++;
      }

   }



   function getID($id) {
      return $this->abc->get($this->resource.'/'.$id);
   }


   function addItem($data) {
      if (isset($data['add'])) {
         unset($data['add']);
      }
      if (isset($data['_glpi_csrf_token'])) {
         unset($data['_glpi_csrf_token']);
      }

      //$fields = $this->abc->get('docs/spec.json');
//      $thisdomain = $fields['domains'][$this->resource]['/'.$this->resource]['POST']['params'];
//      foreach ($thisdomain as $values) {
//         if (isset($values['ui'])
//                 && $values['name'] != 'ui'
//                 && isset($values['ui']['visible'])
//                 && $values['ui']['visible']
//                 && $values['type'] == 'boolean') {
//            $data[$values['name']] = (bool)$data[$values['name']];
//         }
//      }

      $resp = $this->abc->post($this->resource, $data);
      return $resp;
   }


   function delItem($id, $etag) {
      if ($id != '') {
         $resp = $this->abc->delete($this->resource."/".$id, array('If-Match' => $etag));
         print_r($resp);
      }
   }


   function showForm($itemtype, $items_id) {
      global $CFG_GLPI;

      // Get fields + information
      $thisdomain = $this->getPropertiesDefinition();
      foreach ($thisdomain as $values) {
         if ($values['name'] == 'ui') {
            $ui = $values;
         }
      }

      $currentResource = array();
      if ($items_id != '') {
         $currentResource = $this->abc->get($this->resource."/".$items_id);
      }

      echo "<form name='form' method='post' action='".$CFG_GLPI['root_doc']."/plugins/monitoring/front/command.form.php' enctype=\"multipart/form-data\">";
      echo "<table class='tab_cadre_fixe' id='mainformtable'>";

      echo "<tr class='headerRow'><th colspan='2'>";
      printf($ui['ui']['page_title'], __('New item'));
      echo "</th><th colspan='2'>";
      echo "</th></tr>\n";

      $col = 0;
      foreach ($thisdomain as $values) {
         if (isset($values['ui'])
                 && $values['name'] != 'ui'
                 && isset($values['ui']['visible'])
                 && $values['ui']['visible']) {
            if (isset($currentResource[$values['name']])) {
               $values['default'] = $currentResource[$values['name']];
            }
            if ($col == 0) {
               echo "<tr class='tab_bg_1'>";
            }
            echo "<td>";
            $this->displayFieldForm($values);
            echo "</td>";
            $col++;
            if ($col == 2) {
               echo "</tr>";
               $col = 0;
            }
         }
      }
      if ($col == 1) {
         echo "<td></td>";
         echo "</tr>";
      }
      echo "<tr class='tab_bg_2'>";
      echo "<td class='center' colspan='4'>\n";
      if (isset($currentResource['_id'])) {
         echo Html::hidden('_etag', array('value' => $currentResource['_etag']));
         echo Html::hidden('_id', array('value' => $currentResource['_id']));
         echo Html::submit(_x('button','Save'), array('name' => 'update'));
         echo "</td>";
         echo "</tr>";
         echo "<tr class='tab_bg_2'>\n";
         echo "<td class='right' colspan='4' >\n";
         echo Html::submit(_x('button','Delete permanently'),
                           array('name'    => 'purge',
                                 'confirm' => __('Confirm the final deletion?')));
      } else {
         echo Html::submit(_x('button','Add'), array('name' => 'add'));
      }
      echo "</td></tr>\n";
      echo "</table>";
      Html::closeForm();
   }


   function displayFieldForm($data) {
      echo $data['ui']['title'].'</td><td>';
      switch ($data['type']) {

         case 'string':
            if (!isset($data['default'])) {
               $data['default'] = '';
            }
            echo '<input type="text" name="'.$data['name'].'" value="'.$data['default'].'" />';
            break;

         case 'integer':
            Dropdown::showNumber($data['name'], array('value' => $data['default']));
            break;

         case 'boolean':
            Dropdown::showYesNo($data['name'], $data['default']);
            break;

         case "objectid":
            // Get data of this object
            $obj = $this->abc->get($data['data_relation']['resource']);
            $elements = array();
            foreach ($obj['_items'] as $obj_values) {
               $elements[$obj_values[$data['data_relation']['field']]] = $obj_values['name'];
            }
            Dropdown::showFromArray($data['name'], $elements);
            break;

         case "list":

            break;

      }
   }

   function getVisibleFields($data) {
      $return = array();
      foreach ($data as $values) {
         if (isset($values['ui'])
                 && $values['name'] != 'ui'
                 && isset($values['ui']['visible'])
                 && $values['ui']['visible']) {
            $return[] = $values;
         }
      }
      return $return;
   }


   function getPropertiesDefinition($visible='all') {
//      $fields = $this->abc->get('docs/spec.json');
//      $thisdomain = $fields['domains'][$this->resource]['/'.$this->resource]['POST']['params'];
//      if ($visible == 'only') {
//         $thisdomain = $this->getVisibleFields($thisdomain);
//      }
//      return $thisdomain;
   }
}

?>