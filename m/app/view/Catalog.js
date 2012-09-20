Ext.define('GS.view.Catalog', {
   extend: 'Ext.TabPanel',
   xtype: 'catalogcard',
   id: 'catalogcard',
   requires: [
        'Ext.TitleBar',
        'Ext.dataview.NestedList',
        'GS.store.CatalogRed',
        'GS.store.CatalogOrange',
        'GS.store.CatalogGreen',
        'Ext.util.DelayedTask'
    ],
    initialize: function () {
       this.callParent(arguments);
       var refreshList = function() {
         var task = Ext.create('Ext.util.DelayedTask', function() { 
            
            Ext.getCmp('maincard').getTabBar().getComponent(2).setBadgeText(null);
            
            var critStore = Ext.getCmp('catalogCriticalList').getStore();
            critStore.load();            
            critStore.on({
                'load':{
                    fn: function(store, records, options){
                       var countitems = Ext.getCmp('catalogCriticalList').getStore().getCount();
                       Ext.getCmp('catalogcard').getTabBar().getComponent(0).setBadgeText(countitems);
                       if (Ext.getCmp('maincard').getTabBar().getComponent(2).getBadgeText() === null) {
                          Ext.getCmp('maincard').getTabBar().getComponent(2).setBadgeText(countitems);
                       }
                    },
                    scope:this
                }
            });
            
            var warnStore = Ext.getCmp('catalogWarningList').getStore();     
            warnStore.load();
            warnStore.on({
                'load':{
                    fn: function(store, records, options){
                        Ext.getCmp('catalogcard').getTabBar().getComponent(1).setBadgeText(Ext.getCmp('catalogWarningList').getStore().getCount());
                    },
                    scope:this
                }
            });
            
            var okStore = Ext.getCmp('catalogOkList').getStore();
            okStore.load();
            okStore.on({
                'load':{
                    fn: function(store, records, options){
                        Ext.getCmp('catalogcard').getTabBar().getComponent(2).setBadgeText(Ext.getCmp('catalogOkList').getStore().getCount());
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
         var critStore = Ext.getCmp('catalogCriticalList').getStore();         
         critStore.load();            
         critStore.on({
             'load':{
                 fn: function(store, records, options){
                    var countitems = Ext.getCmp('catalogCriticalList').getStore().getCount();
                    Ext.getCmp('catalogcard').getTabBar().getComponent(0).setBadgeText(countitems);
                    if (Ext.getCmp('maincard').getTabBar().getComponent(2).getBadgeText() === null) {
                       Ext.getCmp('maincard').getTabBar().getComponent(2).setBadgeText(countitems);
                    }
                 },
                 scope:this
             }
         });

         var warnStore = Ext.getCmp('catalogWarningList').getStore();     
         warnStore.load();
         warnStore.on({
             'load':{
                 fn: function(store, records, options){
                     Ext.getCmp('catalogcard').getTabBar().getComponent(1).setBadgeText(Ext.getCmp('catalogWarningList').getStore().getCount());
                 },
                 scope:this
             }
         });

         var okStore = Ext.getCmp('catalogOkList').getStore();
         okStore.load();
         okStore.on({
             'load':{
                 fn: function(store, records, options){
                     Ext.getCmp('catalogcard').getTabBar().getComponent(2).setBadgeText(Ext.getCmp('catalogOkList').getStore().getCount());
                 },
                 scope:this
             }
         });
                  
         Ext.getCmp('catalogcard').getTabBar().getComponent(0).addCls('crit-badge');
         Ext.getCmp('catalogcard').getTabBar().getComponent(1).addCls('warn-badge');
         Ext.getCmp('catalogcard').getTabBar().getComponent(2).addCls('ok-badge');
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
       title: 'Components catalog',
       iconCls: 'data',
            
      items: [
         {
            xtype: 'nestedlist',
            title: 'Critical',
            iconCls: 'delete_black1',         
            id: 'catalogCriticalList',
            displayField : 'title',
            store: 'catalogred',
            
            detailCard: {               
               xtype: 'panel',
               scrollable: true,
               styleHtmlContent: true,
               Html: ""
            }
         },
         {
            xtype: 'nestedlist',
            title: 'Warning',
            iconCls: 'warning_black',
            id: 'catalogWarningList',
            displayField: 'title',
            store: 'catalogorange', 
            
            detailCard: {               
               xtype: 'panel',
               scrollable: true,
               styleHtmlContent: true,
               Html: ""
            }
         },
         {
            xtype: 'nestedlist',
            title: 'Ok',
            iconCls: 'check_black2',
            id: 'catalogOkList',
            displayField: 'title',
            store: 'cataloggreen', 
            
            detailCard: {               
               xtype: 'panel',
               scrollable: true,
               styleHtmlContent: true,
               Html: ""            
            }
         }
      ]
   }
});
