Ext.define("GS.view.Main", {
    extend: 'Ext.TabPanel',
    requires: [
        'Ext.TitleBar',
        'GS.view.Ressource'
    ],
    config: {
       tabBar: {
          docked: 'bottom',
          layout: {
             pack: 'center'
          }
       },


        items: [
            {
                title: 'Welcome',
                iconCls: 'home',

                styleHtmlContent: true,
                scrollable: true,

                items: {
                    docked: 'top',
                    xtype: 'titlebar',
                    title: 'GLPI - Monitoring'
                },

                html: [
                    "Welcome in monitoring application based on GLPI and Shinken!<br/>",
                    "You can see all informations of dashboard in this app..."
                ].join("")
            },
            {
                title: 'Catalogue de services',
                iconCls: 'data',

                styleHtmlContent: true,
                scrollable: true,

                items: {
                    docked: 'top',
                    xtype: 'titlebar',
                    title: 'Catalogue de services'
                },

                html: [
                    "Not yet coded..."
                ].join("")
            },
            { xtype: 'ressourcecard' }
        ]
    }
});
