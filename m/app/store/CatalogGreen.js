Ext.define('GS.store.CatalogGreen', {
    extend: 'Ext.data.TreeStore',
    requires: [
       'GS.model.Catalog',
       'Ext.data.proxy.Rest'
    ],
   id: 'cataloggreen',
   xtype: 'cataloggreen',
    config: {
        autoLoad :true,
        model: 'GS.model.Catalog',
        clearOnPageLoad:false,
        proxy: {
             type: 'rest',
             url: document.URL+'app/store/getCatalogs.php?state=green',
             reader: {
                 type: 'json',
                 rootProperty: 'catalogs'
             }
         },
         root: {
            leaf: false
         }         
    }
});