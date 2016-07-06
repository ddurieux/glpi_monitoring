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

/*
 * Global variables for host/service states
 */
var g_hosts_states = {
   'up': {
      'color': 'rgba(39, 174, 96, 1)',
      'background': 'rgba(39, 174, 96, 0.2)',
      'label': 'Up'
   },
   'unreachable': {
      'color': 'rgba(230, 126, 34, 1)',
      'background': 'rgba(230, 126, 34, 0.2)',
      'label': 'Unreachable'
   },
   'down': {
      'color': 'rgba(231, 76, 60, 1)',
      'background': 'rgba(231, 76, 60, 0.2)',
      'label': 'Down'
   },
   'unknown': {
      'color': 'rgba(41, 128, 185, 1)',
      'background': 'rgba(41, 128, 185, 0.2)',
      'label': 'Unknown'
   },
   'acknowledged': {
      'color': 'rgba(149, 165, 166, 1)',
      'background': 'rgba(149, 165, 166, 0.2)',
      'label': 'Ack'
   },
   'in_downtime': {
      'color': 'rgba(155, 89, 182, 1)',
      'background': 'rgba(155, 89, 182, 0.2)',
      'label': 'Downtime'
   }
};
var g_services_states = {
   'ok': {
      'color': 'rgba(39, 174, 96, 1)',
      'background': 'rgba(39, 174, 96, 0.2)',
      'label': 'Ok'
   },
   'warning': {
      'color': 'rgba(230, 126, 34, 1)',
      'background': 'rgba(230, 126, 34, 0.2)',
      'label': 'Warning'
   },
   'critical': {
      'color': 'rgba(231, 76, 60, 1)',
      'background': 'rgba(231, 76, 60, 0.2)',
      'label': 'Critical'
   },
   'unknown': {
      'color': 'rgba(41, 128, 185, 1)',
      'background': 'rgba(41, 128, 185, 0.2)',
      'label': 'Unknown'
   },
   'acknowledged': {
      'color': 'rgba(149, 165, 166, 1)',
      'background': 'rgba(149, 165, 166, 0.2)',
      'label': 'Ack'
   },
   'in_downtime': {
      'color': 'rgba(155, 89, 182, 1)',
      'background': 'rgba(155, 89, 182, 0.2)',
      'label': 'Downtime'
   }
};
var g_hoverBackgroundColor = "rgba(255,99,132,0.4)";
var g_hoverBorderColor = "rgba(255,99,132,1)";

