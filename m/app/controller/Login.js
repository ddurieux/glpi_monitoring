Ext.define('GS.controller.Login',{
   extend: 'Ext.app.Controller',
   
   init: function(){
      this.control({
         'button[action=submitLogin]': {
            tap: 'getCsrf'
         }
      });
   },
   getCsrf: function() {
//      var serverurl = Ext.ComponentQuery.query('#serverurl')[0].getValue();
      var serverurl = document.URL;
      serverurl = serverurl.replace("plugins/monitoring/m/", "");
      
      Ext.Ajax.request({

          url: serverurl,
          method: 'POST',
          callbackKey: 'callback',
          timeout: 10000,
          
          success: function(result) {
            //console.log(result.responseText);
            var match = /name='_glpi_csrf_token' value='(.*)'><\/form>/i.exec(result.responseText)
            //console.log(match[1]);
            if (match[1]) {
               
               
                  Ext.Ajax.request({

                      url: serverurl+'login.php',
                      method: 'POST',
                      callbackKey: 'callback',
                      timeout: 10000,
                      params: {
                         "login_name":Ext.ComponentQuery.query('#username')[0].getValue(), 
                         "login_password":Ext.ComponentQuery.query('#password')[0].getValue(), 
                         "_glpi_csrf_token": match[1], 
                         'submit':'Post'
                      },

                      success: function(result) {
                        //console.log(result.responseText);
                        var strr = result.responseText;
                        if (strr.search("NomNav = navigator.appName") != -1) {
                           Ext.Msg.alert("Successful");
                           
                           // Load store
                           Ext.create('GS.store.ServiceRed');
                           Ext.create('GS.store.ServiceOrange');
                           Ext.create('GS.store.ServiceGreen');
                           
                           var paneltab = Ext.create('GS.view.Main');
                           Ext.getCmp('LoginForm').destroy();
                           Ext.Viewport.add(paneltab);

                        } else {
                           Ext.Msg.alert("Authencication failed!");
                        }
                      },         
                      failure: function(result){       
                        Ext.Msg.alert("Authencication failed!");        
                      }
                  });
            }
          },         
          failure: function(result){       
            Ext.Msg.alert("Connection impossible!");        
          }

      });
   }  

}); 