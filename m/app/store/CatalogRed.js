Ext.define('GS.store.CatalogRed', {
    extend: 'Ext.data.TreeStore',
    requires: [
       'GS.model.Catalog',
       'Ext.data.proxy.Rest'
    ],
   id: 'catalogred',
   xtype: 'catalogred',
    config: {
        autoLoad :true,
        model: 'GS.model.Catalog',
        clearOnPageLoad:false,
        proxy: {
             type: 'rest',
             url: document.URL+'app/store/getCatalogs.php?state=red',
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