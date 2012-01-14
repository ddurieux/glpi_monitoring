<?php

/*
   ----------------------------------------------------------------------
   Monitoring plugin for GLPI
   Copyright (C) 2010-2011 by the GLPI plugin monitoring Team.

   https://forge.indepnet.net/projects/monitoring/
   ----------------------------------------------------------------------

   LICENSE

   This file is part of Monitoring plugin for GLPI.

   Monitoring plugin for GLPI is free software: you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation, either version 2 of the License, or
   any later version.

   Monitoring plugin for GLPI is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with Monitoring plugin for GLPI.  If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------
   Original Author of file: David DURIEUX
   Co-authors of file:
   Purpose of file:
   ----------------------------------------------------------------------
 */

$title="Monitoring";

$LANG['plugin_monitoring']['title'][0]="$title";

$LANG['plugin_monitoring']['host'][0]="hosts";
$LANG['plugin_monitoring']['host'][1]="Dependencies";
$LANG['plugin_monitoring']['host'][2]="Dynamic management";
$LANG['plugin_monitoring']['host'][3]="Static management";
$LANG['plugin_monitoring']['host'][4]="Dependency host";
$LANG['plugin_monitoring']['host'][5]="Active checks";
$LANG['plugin_monitoring']['host'][6]="Passive checks";
$LANG['plugin_monitoring']['host'][7]="Dynamic dependencies are";
$LANG['plugin_monitoring']['host'][8]="Host";
$LANG['plugin_monitoring']['host'][9]="Check period";
$LANG['plugin_monitoring']['host'][10]="Last month";
$LANG['plugin_monitoring']['host'][11]="Last 6 months";
$LANG['plugin_monitoring']['host'][12]="Last year";
$LANG['plugin_monitoring']['host'][13]="State ok";
$LANG['plugin_monitoring']['host'][14]="State critical";
$LANG['plugin_monitoring']['host'][15]="Last day";
$LANG['plugin_monitoring']['host'][16]="Last week";
$LANG['plugin_monitoring']['host'][17]="temps";
$LANG['plugin_monitoring']['host'][18]="Add these hosts to monitoring";
$LANG['plugin_monitoring']['host'][19]="Equipments";

$LANG['plugin_monitoring']['grouphost'][0]="hosts groups";
$LANG['plugin_monitoring']['grouphost'][1]="hosts group";

$LANG['plugin_monitoring']['command'][0]="Commands";
$LANG['plugin_monitoring']['command'][1]="Command check";
$LANG['plugin_monitoring']['command'][2]="Command name";
$LANG['plugin_monitoring']['command'][3]="Command line";
$LANG['plugin_monitoring']['command'][4]="Arguments description";
$LANG['plugin_monitoring']['command'][5]="Regex (for perf_data)";
$LANG['plugin_monitoring']['command'][6]="Legend (for perf_data graph)";

$LANG['plugin_monitoring']['check'][0]="Check definition";
$LANG['plugin_monitoring']['check'][1]="Max check attempts";
$LANG['plugin_monitoring']['check'][2]="Time in minutes between 2 checks";
$LANG['plugin_monitoring']['check'][3]="Time in minutes between 2 retry";

$LANG['plugin_monitoring']['contact'][0]="Contact";
$LANG['plugin_monitoring']['contact'][1]="Manage this user for monitoring system";
$LANG['plugin_monitoring']['contact'][2]="Pager";
$LANG['plugin_monitoring']['contact'][3]="Hosts";
$LANG['plugin_monitoring']['contact'][4]="Services";
$LANG['plugin_monitoring']['contact'][5]="Notifications";
$LANG['plugin_monitoring']['contact'][6]="Period";
$LANG['plugin_monitoring']['contact'][7]="Notify on DOWN host states";
$LANG['plugin_monitoring']['contact'][8]="Notify on WARNING service states";
$LANG['plugin_monitoring']['contact'][9]="Notify on UNREACHABLE host states";
$LANG['plugin_monitoring']['contact'][10]="Notify on UNKNOWN service states";
$LANG['plugin_monitoring']['contact'][11]="Notify on host recoveries (UP states)";
$LANG['plugin_monitoring']['contact'][12]="Notify on CRITICAL service states";
$LANG['plugin_monitoring']['contact'][13]="Notify when the host starts and stops flapping";
$LANG['plugin_monitoring']['contact'][14]="Notify on service recoveries (OK states)";
$LANG['plugin_monitoring']['contact'][15]="Send notifications when host or service scheduled downtime starts and ends";
$LANG['plugin_monitoring']['contact'][16]="Notify when the service starts and stops flapping";
$LANG['plugin_monitoring']['contact'][17]="The contact will not receive any type of host notifications";
$LANG['plugin_monitoring']['contact'][18]="The contact will not receive any type of service notifications";
$LANG['plugin_monitoring']['contact'][19]="Notification command";
$LANG['plugin_monitoring']['contact'][20]="Contacts";

$LANG['plugin_monitoring']['contacttemplate'][0]="Contact templates";
$LANG['plugin_monitoring']['contacttemplate'][1]="Default template";

$LANG['plugin_monitoring']['service'][0]="Resources";
$LANG['plugin_monitoring']['service'][1]="Criticity";
$LANG['plugin_monitoring']['service'][2]="Add a service";
$LANG['plugin_monitoring']['service'][3]="or/and define these values";
$LANG['plugin_monitoring']['service'][4]="Arguments";
$LANG['plugin_monitoring']['service'][5]="Command";
$LANG['plugin_monitoring']['service'][6]="Active check";
$LANG['plugin_monitoring']['service'][7]="Passive check";
$LANG['plugin_monitoring']['service'][8]="Remote check";
$LANG['plugin_monitoring']['service'][9]="Utility used for remote check";
$LANG['plugin_monitoring']['service'][10]="Use arguments (Only for NRPE)";
$LANG['plugin_monitoring']['service'][11]="Alias command if required (Only for NRPE)";
$LANG['plugin_monitoring']['service'][12]="Template (for graphs generation)";
$LANG['plugin_monitoring']['service'][13]="Argument ([text:text] is used to get values dynamically)";
$LANG['plugin_monitoring']['service'][14]="Argument";
$LANG['plugin_monitoring']['service'][15]="Add this host to be monitored";
$LANG['plugin_monitoring']['service'][16]="Check Host";
$LANG['plugin_monitoring']['service'][17]="Configuration complete";
$LANG['plugin_monitoring']['service'][18]="Last check";
$LANG['plugin_monitoring']['service'][19]="State type";
$LANG['plugin_monitoring']['service'][20]="Resource";
$LANG['plugin_monitoring']['service'][21]="All resources";
$LANG['plugin_monitoring']['service'][22]="Time in ms";

$LANG['plugin_monitoring']['servicesuggest'][0]="Suggests";

$LANG['plugin_monitoring']['servicescatalog'][0]="Services catalog";
$LANG['plugin_monitoring']['servicescatalog'][1]="Degraded mode";

$LANG['plugin_monitoring']['businessrule'][0]="Business rules";
$LANG['plugin_monitoring']['businessrule'][2]="2 of";
$LANG['plugin_monitoring']['businessrule'][3]="3 of";
$LANG['plugin_monitoring']['businessrule'][4]="4 of";
$LANG['plugin_monitoring']['businessrule'][5]="5 of";
$LANG['plugin_monitoring']['businessrule'][6]="6 of";
$LANG['plugin_monitoring']['businessrule'][7]="7 of";
$LANG['plugin_monitoring']['businessrule'][8]="8 of";
$LANG['plugin_monitoring']['businessrule'][9]="9 of";
$LANG['plugin_monitoring']['businessrule'][10]="10 of";
$LANG['plugin_monitoring']['businessrule'][11]="Group";
$LANG['plugin_monitoring']['businessrule'][12]="";
$LANG['plugin_monitoring']['businessrule'][13]="";

$LANG['plugin_monitoring']['businessruledefinition'][0] = $LANG['plugin_monitoring']['businessrule'][0];

$LANG['plugin_monitoring']['component'][0]="Components";
$LANG['plugin_monitoring']['component'][1]="Add a new component";
$LANG['plugin_monitoring']['component'][2]="Associated components";

$LANG['plugin_monitoring']['componentscatalog'][0]="Components catalog";

$LANG['plugin_monitoring']['rule'][0]="Rules";
?>