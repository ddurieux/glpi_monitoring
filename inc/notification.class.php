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

class PluginMonitoringNotification {
   
   
   static function test() {
      echo "<script language='Javascript'>
         
Ext.ux.NotificationMgr = {
    positions: []
};

Ext.ux.Notification = Ext.extend(Ext.Window, {
    initComponent: function(){
          Ext.apply(this, {
            iconCls: this.iconCls || 'x-icon-information',
            width: 200,
            autoHeight: true,
            closable: false,
            plain: false,
            draggable: false,
            bodyStyle: 'text-align:center;padding:1em;'            
          });
          if(this.autoDestroy) {
            this.task = new Ext.util.DelayedTask(this.hide, this);
          } else {
              this.closable = true;
          }
        Ext.ux.Notification.superclass.initComponent.call(this);
    },
    setMessage: function(msg){
        this.body.update(msg);
    },
    setTitle: function(title, iconCls){
        Ext.ux.Notification.superclass.setTitle.call(this, title, iconCls||this.iconCls);
    },
    onRender:function(ct, position) {
        Ext.ux.Notification.superclass.onRender.call(this, ct, position);
    },
    onDestroy: function(){
        Ext.ux.NotificationMgr.positions.remove(this.pos);
        Ext.ux.Notification.superclass.onDestroy.call(this);
    },
    afterShow: function(){
        Ext.ux.Notification.superclass.afterShow.call(this);
        this.on('move', function(){
               Ext.ux.NotificationMgr.positions.remove(this.pos);
               if(this.autoDestroy) {
                this.task.cancel();
               }
        }, this);
        if(this.autoDestroy) {
            this.task.delay(this.hideDelay || 5000);
       }
    },
    animShow: function(){
        this.pos = 0;
        while(Ext.ux.NotificationMgr.positions.indexOf(this.pos)>-1)
            this.pos++;
        Ext.ux.NotificationMgr.positions.push(this.pos);
        this.setSize(200,100);
        this.el.alignTo(document, 'br-br', [ -20, -5-((this.getSize().height+10)*this.pos) ]);
        this.el.slideIn('b', {
            duration: 1,
            callback: this.afterShow,
            scope: this
        });
    },
    animHide: function(){
           Ext.ux.NotificationMgr.positions.remove(this.pos);
           this.el.disableShadow();
        this.el.ghost('b', {
            duration: 1,
            remove: true
        });
    },
    focus: Ext.emptyFn
});   

  
function toastAlert( the_title, the_message ) {
    // Set defaults for the toast window title and icon
    the_title = typeof(the_title) != 'undefined' ? the_title : 'Notice';
    
    // Create the toast window
new Ext.ux.Notification({
                iconCls:    'x-icon-error',
                title:      the_title,
                html:       the_message,
                autoDestroy: true,
                hideDelay:  5000
            }).show(document); 
} // eo function toastAlert

toastAlert('Critical!', 'Apache on server xxx is down...');


</script>";
      
      
      
   }
   
   
}

?>