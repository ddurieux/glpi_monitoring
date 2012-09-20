Ext.define('GS.store.CatalogOrange', {
    extend: 'Ext.data.TreeStore',
    requires: [
       'GS.model.Catalog',
       'Ext.data.proxy.Rest'
    ],
   id: 'catalogorange',
   xtype: 'catalogorange',
    config: {
        autoLoad :true,
        model: 'GS.model.Catalog',
        clearOnPageLoad:false,
        proxy: {
             type: 'rest',
             url: document.URL+'app/store/getCatalogs.php?state=orange',
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