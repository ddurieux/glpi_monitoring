Ext.define('GS.model.Service', {
    extend: 'Ext.data.Model',

    config: {
        fields: [
           'title',
           'content',
           'state',
           'date',
           'event',
           {name: 'leaf', defaultValue: true}
        ]

    }
});
