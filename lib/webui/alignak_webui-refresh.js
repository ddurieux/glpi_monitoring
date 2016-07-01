/*
 * Copyright (c) 2015-2016:
 *   Frederic Mohier, frederic.mohier@gmail.com
 *
 * This file is part of (WebUI).
 *
 * (WebUI) is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * (WebUI) is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with (WebUI).  If not, see <http://www.gnu.org/licenses/>.
 */


var refresh_logs=false;

// By default, we set the page to reload each period defined in configuration
var refresh_timeout = app_refresh_period;
// Check refresh period (seconds)
var check_period = 1;
// Ping period (seconds) - Set this value to 0 to disable periodical server ping.
var ping_period = 0;
// Refresh required
var refresh_required=false;


if (refresh_logs) console.debug("Refresh period is :", refresh_timeout);
if (refresh_logs) console.debug("Check period is :", check_period);
if (refresh_logs) console.debug("Ping period is :", ping_period);

var nb_refresh_try = 0;
if (! sessionStorage.getItem("refresh_active")) {
   if (refresh_logs) console.debug("Refresh active storage does not exist");
   // Store default value ...
   sessionStorage.setItem("refresh_active", refresh_timeout==0 ? '0' : '1');
}
if (refresh_logs) console.debug("Refresh active is ", sessionStorage.getItem("refresh_active"));
if (sessionStorage.getItem("refresh_active") == '1') {
   $('#header_loading').removeClass('font-greyed');
} else {
   $('#header_loading').addClass('font-greyed');
}

/*
 * This function is called on each refresh of the current page.
 * ----------------------------------------------------------------------------
 *  It is to be noted that this function makes an Ajax call on the current URL
 * to get the new version of the current page. This is the most interesting
 * strategy for refreshing ... but the drawbacks are that it gets an entire
 * Html page including <head>, <body> and ... <script>!
 *
 *  If the current page declares an 'on_refresh' function, this function is
 * called, else the content of the #page-content element is replaced with the
 * newly received #page-content content.
 *
 *  As of it, all the other page elements in the layout are not refreshed.
 * This allows to avoid refreshing header, side bar menu, footer, ...
 *
 *  If the current page declares a 'no_default_page_refresh' variable, the
 * page content is not replaced with the new received page content. Thanks
 * to this feature, the page can provide its own content refresh ...
 *
 *  NOTE: Because of the new received Html inclusion method, the embedded
 * scripts are not executed ... this implies that the necessary scripts for
 * refresh management are to be included in this function in the always
 * Ajax promise!
 * ---------------------------------------------------------------------------
 */
var processing_refresh = false;
function do_refresh(forced){
   if (processing_refresh) {
      console.log("Avoid simultaneous refreshes ...");
      return;
   }
   if (refresh_logs) console.debug("Refreshing: ", document.URL);

   // Refresh starting indicator ...
   $('#header_loading').addClass('fa-spin');
   processing_refresh = true;

   $.ajax({
      url: document.URL,
      method: "get",
      dataType: "html"
   })
   .done(function(html, textStatus, jqXHR) {
      if (refresh_logs) console.debug('do_refresh, got: ', jqXHR, textStatus);

      /* This var declaration includes the response in the document body ... bad luck!
       * ------------------------------------------------------------------------------
       * In fact, each refresh do include all the received Html and then we filter
       * what we are interested in ... not really efficient and quite buggy !
       */
      // Each plugin may indicate if the default page content is to be refreshed or not ...
      if (typeof no_default_page_refresh !== 'undefined' && no_default_page_refresh) {
         if (refresh_logs) console.debug('Do not include default page refresh content...');
      } else {
         // Refresh all the id="page-content"
         var $response = $('<div id="refresh_temp"/>').html(html);

         // Refresh current page content ...
         $('#page-content').html($response.find('#page-content').html());

         // Clean the DOM after refresh update ...
         $response.remove();
      }

      // Each plugin may provide its on_page_refresh function that will be called here ...
      if (typeof on_page_refresh !== 'undefined' && $.isFunction(on_page_refresh)) {
         if (refresh_logs) console.debug('Calling page refresh function ...');
         on_page_refresh(forced);
      }

      // Look at the hash part of the URI. If it matches a nav name, go for it
      if (location.hash.length > 0) {
         if (refresh_logs) console.debug('Displaying tab: ', location.hash)
         $('.nav-tabs li a[href="' + location.hash + '"]').trigger('click');
      } else {
         $('.nav-tabs li a:first').trigger('click');
      }
   })
   .fail(function( jqXHR, textStatus, errorThrown ) {
      if (refresh_logs) console.error('do_refresh, failed: ', jqXHR, textStatus, errorThrown);
   })
   .always(function() {
      // Set refresh icon ...
      if (sessionStorage.getItem("refresh_active") == '1') {
         $('#header_loading').removeClass('font-greyed');
      } else {
         $('#header_loading').addClass('font-greyed');
      }
      if (refresh_logs) console.debug("Refresh active is ", sessionStorage.getItem("refresh_active"));

      // Refresh is finished
      $('#header_loading').removeClass('fa-spin');
      processing_refresh = false;
      refresh_required = false;
   });
}


/* Try to see if the UI is not in restating mode, and so
   don't have enough data to refresh the page as it should ... */
function check_UI_backend(){
   // If a refresh is required ... skip this one.
   if (refresh_required) {
      if (refresh_logs) console.debug("Postpone heartbeat due to a refresh required by the server processing...");
      return;
   }

   if (sessionStorage.getItem("refresh_active") == '1') {
      $.get({
         url: '/heartbeat',
         dataType: "json"
      })
      .done(function(data, textStatus, jqXHR) {
         if (data.status == 'ok') {
            if (data.message == 'Session expired') {
               // Force page reloading
               location.reload();
            } else {
               if (sessionStorage.getItem("refresh_active") == '1') {
                  // Go Refresh
                  do_refresh();
               }
            }
         }
      })
      .fail(function(jqXHR, textStatus, errorThrown) {
         if (refresh_logs) console.error('UI backend is not available, retrying later ...');
         if (refresh_logs) console.error(textStatus, errorThrown);
         if (jqXHR.status == 401) {
            // Session expired, force page reloading
            location.reload();
         } else {
            postpone_refresh();
         }
      });
   }

   reinit_refresh();
}


/*
 * Each second, send a ping to the server
 * If the server requires a refresh:
 * - force an immediate refresh
 */
function check_refresh(){
   refresh_timeout = refresh_timeout - check_period;

   // If a refresh is required ... skip this one.
   if (refresh_required && processing_refresh) {
      if (refresh_logs) console.debug("Postpone ping due to a refresh required by the server processing...");
      return;
   }
   if (refresh_required && ! processing_refresh) {
      // Force immediate refresh
      do_refresh();
      return;
   }

   if (refresh_timeout < 0){
      if (refresh_logs) console.debug("check_refresh is calling check_UI_backend...");
      // check if the backend is available or not, and then refresh ...
      check_UI_backend();
      return;
   }
   if ((ping_period != 0) && (refresh_timeout % ping_period == 0)) {
      if (refresh_logs) console.debug("check_refresh is pinging the server...");

      // Ping server ...
      $.get({
         url: '/ping',
         dataType: "json"
      })
      .done(function(data, textStatus, jqXHR) {
         if (data.status == 'ok') {
            if (data.message == 'refresh') {
               if (refresh_logs) console.debug("Refresh required by the server");
               $.get({
                  url: '/ping?action=done',
                  dataType: "json"
               })
               .always(function(data, textStatus, jqXHR) {
                  if (refresh_logs) console.debug("Refresh confirmed to the server");
                  refresh_required = true;
               });
            }
         } else {
            if (refresh_logs) console.error("Ping response is ", data.message);
         }
      });
   }
   return;
}


/*
 * Reinitialize the refresh period so the user will have time to do something ...
 */
function reinit_refresh(){
   if (refresh_logs) console.debug("Refresh period restarted: " + app_refresh_period + " seconds");
   refresh_timeout = app_refresh_period;
}


/*
 * Start/stop the refresh process...
 */
function stop_refresh() {
   if (refresh_logs) console.debug("Stop refresh");
   $('#header_loading').addClass('font-greyed');
   sessionStorage.setItem("refresh_active", '0');
}


function start_refresh() {
   if (refresh_logs) console.debug("Start refresh");
   $('#header_loading').removeClass('font-greyed');
   sessionStorage.setItem("refresh_active", '1');
}


function postpone_refresh(){
   // If we are not in our first try, warn the user
   if (nb_refresh_try > 0){
      raise_message_ko("The Web UI backend is not available");
   }
   nb_refresh_try += 1;

   // Start a new loop before retrying...
   reinit_refresh();
}


$(document).ready(function(){
   // Start refresh periodical check ... every check_period second!
   setInterval("check_refresh();", check_period*1000);

   if (sessionStorage.getItem("refresh_active") == '1') {
      $('#header_loading').removeClass('font-greyed');
   } else {
      $('#header_loading').addClass('font-greyed');
   }

   // Toggle refresh ...
   $('body').on("click", '[data-action="toggle-page-refresh"]', function (e, data) {
      if (sessionStorage.getItem("refresh_active") == '1') {
         stop_refresh();
      } else {
         start_refresh();
      }
      if (refresh_logs) console.debug("Refresh active is ", sessionStorage.getItem("refresh_active"));
   });
});
