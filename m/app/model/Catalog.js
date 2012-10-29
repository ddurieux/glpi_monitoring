Ext.define('GS.model.Catalog', {
    extend: 'Ext.data.Model',

    config: {
        fields: [
           'title',
           'state',
           'ressources',
           'content',
           {name: 'leaf', defaultValue: true}
        ]

    }
});
