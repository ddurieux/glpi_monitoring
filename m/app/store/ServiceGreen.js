Ext.define('GS.store.ServiceGreen', {
    extend: 'Ext.data.TreeStore',
    requires: [
       'GS.model.Service',
       'Ext.data.proxy.Rest'
    ],
   id: 'servicegreen',
   xtype: 'servicegreen',
    config: {
        autoLoad :true,
        model: 'GS.model.Service',
        clearOnPageLoad:false,
        proxy: {
             type: 'rest',
             url: document.URL+'app/store/getServices.php?type=PluginMonitoringComponentscatalog&state=green',
             reader: {
                 type: 'json',
                 rootProperty: 'services'
             }
         },
         root: {
            leaf: false
         } 
         
    }
});