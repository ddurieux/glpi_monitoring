Ext.define('GS.view.Ressource', {
   extend: 'Ext.TabPanel',
   xtype: 'ressourcecard',
   id: 'ressourcecard',
   requires: [
        'Ext.TitleBar',
        'Ext.dataview.NestedList',
        'GS.store.ServiceRed',
        'GS.store.ServiceOrange',
        'GS.store.ServiceGreen',
        'Ext.util.DelayedTask'
    ],
    initialize: function () {
       this.callParent(arguments);
       var refreshList = function() {
         var task = Ext.create('Ext.util.DelayedTask', function() { 
            
            var critStore = Ext.getCmp('ressourceCriticalList').getStore();
            critStore.load();            
            critStore.on({
                'load':{
                    fn: function(store, records, options){
                       var countitems = Ext.getCmp('ressourceCriticalList').getStore().getCount();
                       Ext.getCmp('ressourcecard').getTabBar().getComponent(0).setBadgeText(countitems);
                    },
                    scope:this
                }
            });
            
            var warnStore = Ext.getCmp('ressourceWarningList').getStore();     
            warnStore.load();
            warnStore.on({
                'load':{
                    fn: function(store, records, options){
                        Ext.getCmp('ressourcecard').getTabBar().getComponent(1).setBadgeText(Ext.getCmp('ressourceWarningList').getStore().getCount());
                    },
                    scope:this
                }
            });
            
            var okStore = Ext.getCmp('ressourceOkList').getStore();
            okStore.load();
            okStore.on({
                'load':{
                    fn: function(store, records, options){
                        Ext.getCmp('ressourcecard').getTabBar().getComponent(2).setBadgeText(Ext.getCmp('ressourceOkList').getStore().getCount());
                    },
                    scope:this
                }
            });

            
            refreshList.call(this);
         });  
         task.delay(120000);
      };
      refreshList();
      
      var counters = function() {
         var critStore = Ext.getCmp('ressourceCriticalList').getStore();         
         critStore.load();            
         critStore.on({
             'load':{
                 fn: function(store, records, options){
                    var countitems = Ext.getCmp('ressourceCriticalList').getStore().getCount();
                    Ext.getCmp('ressourcecard').getTabBar().getComponent(0).setBadgeText(countitems);
                 },
                 scope:this
             }
         });

         var warnStore = Ext.getCmp('ressourceWarningList').getStore();     
         warnStore.load();
         warnStore.on({
             'load':{
                 fn: function(store, records, options){
                     Ext.getCmp('ressourcecard').getTabBar().getComponent(1).setBadgeText(Ext.getCmp('ressourceWarningList').getStore().getCount());
                 },
                 scope:this
             }
         });

         var okStore = Ext.getCmp('ressourceOkList').getStore();
         okStore.load();
         okStore.on({
             'load':{
                 fn: function(store, records, options){
                     Ext.getCmp('ressourcecard').getTabBar().getComponent(2).setBadgeText(Ext.getCmp('ressourceOkList').getStore().getCount());
                 },
                 scope:this
             }
         });
                  
         Ext.getCmp('ressourcecard').getTabBar().getComponent(0).addCls('crit-badge');
         Ext.getCmp('ressourcecard').getTabBar().getComponent(1).addCls('warn-badge');
         Ext.getCmp('ressourcecard').getTabBar().getComponent(2).addCls('ok-badge');
      }
      counters();

    },
   config: {
      tabBar: {
          docked: 'bottom',
          layout: {
             pack: 'center'
          }
       },
       title: 'Ressources',
       iconCls: 'data',
            
      items: [
         {
            xtype: 'nestedlist',
            title: 'Critical',
            iconCls: 'delete_black1',            
            //badgeCls  : 'x-badge crit-badge',
            id: 'ressourceCriticalList',
            displayField : 'title',
            store: 'servicered',
            
            detailCard: {               
               xtype: 'panel',
               scrollable: true,
               styleHtmlContent: true
            },

            listeners: {
               itemtap: function(nestedList, list, index, element, post) {
                  console.log('te');
                  this.getDetailCard().setHtml(
                  "<table>"+
                     
                     "<tr>"+
                        "<th>Date (last event) :</th>"+
                     "</tr>"+
                     "<tr>"+
                        "<td>"+
                           post.get('date')+
                        "</td>"+
                     "</tr>"+
                     
                     "<tr>"+
                        "<th>Event :</th>"+
                     "</tr>"+
                     "<tr>"+
                        "<td>"+
                           post.get('event')+
                        "</td>"+
                     "</tr>"+
                     
                     "<tr>"+
                        "<th>Chart :</th>"+
                     "</tr>"+
                     "<tr>"+
                        "<td>"+
                           post.get('content')+
                        "</td>"+
                     "</tr>"+
                  "</table>"); 
                             
               }
            }
         },
         {
            xtype: 'nestedlist',
            title: 'Warning',
            iconCls: 'warning_black',
            id: 'ressourceWarningList',
            displayField: 'title',
            store: 'serviceorange', 
            
            detailCard: {               
               xtype: 'panel',
               scrollable: true,
               styleHtmlContent: true
            },

            listeners: {
               itemtap: function(nestedList, list, index, element, post) {
                  console.log('te');
                  this.getDetailCard().setHtml(
                  "<table>"+
                     
                     "<tr>"+
                        "<th>Date (last event) :</th>"+
                     "</tr>"+
                     "<tr>"+
                        "<td>"+
                           post.get('date')+
                        "</td>"+
                     "</tr>"+
                     
                     "<tr>"+
                        "<th>Event :</th>"+
                     "</tr>"+
                     "<tr>"+
                        "<td>"+
                           post.get('event')+
                        "</td>"+
                     "</tr>"+
                     
                     "<tr>"+
                        "<th>Chart :</th>"+
                     "</tr>"+
                     "<tr>"+
                        "<td>"+
                           post.get('content')+
                        "</td>"+
                     "</tr>"+
                  "</table>"); 
                             
               }
            }
         },
         {
            xtype: 'nestedlist',
            title: 'Ok',
            iconCls: 'check_black2',
            id: 'ressourceOkList',
            displayField: 'title',
            store: 'servicegreen', 
            
            detailCard: {               
               xtype: 'panel',
               scrollable: true,
               styleHtmlContent: true
            },

            listeners: {
               itemtap: function(nestedList, list, index, element, post) {
                  console.log('te');
                  this.getDetailCard().setHtml(
                  "<table>"+
                     
                     "<tr>"+
                        "<th>Date (last event) :</th>"+
                     "</tr>"+
                     "<tr>"+
                        "<td>"+
                           post.get('date')+
                        "</td>"+
                     "</tr>"+
                     
                     "<tr>"+
                        "<th>Event :</th>"+
                     "</tr>"+
                     "<tr>"+
                        "<td>"+
                           post.get('event')+
                        "</td>"+
                     "</tr>"+
                     
                     "<tr>"+
                        "<th>Chart :</th>"+
                     "</tr>"+
                     "<tr>"+
                        "<td>"+
                           post.get('content')+
                        "</td>"+
                     "</tr>"+
                  "</table>"); 
                             
               }
            }
         }
      ]
   }
});
