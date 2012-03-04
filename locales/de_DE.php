<?php

/*
   ------------------------------------------------------------------------
   Plugin Monitoring for GLPI
   Copyright (C) 2011-2012 by the Plugin Monitoring for GLPI Development Team.

   https://forge.indepnet.net/projects/monitoring/
   ------------------------------------------------------------------------

   LICENSE

   This file is part of Plugin Monitoring project.

   Plugin Monitoring for GLPI is free software: you can redistribute it and/or modify
   it under the terms of the GNU Affero General Public License as published by
   the Free Software Foundation, either version 3 of the License, or
   (at your option) any later version.

   Plugin Monitoring for GLPI is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
   GNU Affero General Public License for more details.

   You should have received a copy of the GNU Affero General Public License
   along with Behaviors. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   Plugin Monitoring for GLPI
   @author    David Durieux
   @co-author 
   @comment   Not translate this file, use https://www.transifex.net/projects/p/GLPI_monitoring/
   @copyright Copyright (c) 2011-2012 Plugin Monitoring for GLPI team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      https://forge.indepnet.net/projects/monitoring/
   @since     2011
 
   ------------------------------------------------------------------------
 */


$LANG['plugin_monitoring']['businessrule'][0]="Business rules";
$LANG['plugin_monitoring']['businessrule'][10]="10 of";
$LANG['plugin_monitoring']['businessrule'][11]="Group";
$LANG['plugin_monitoring']['businessrule'][12]="Groups";
$LANG['plugin_monitoring']['businessrule'][2]="2 of";
$LANG['plugin_monitoring']['businessrule'][3]="3 of";
$LANG['plugin_monitoring']['businessrule'][4]="4 of";
$LANG['plugin_monitoring']['businessrule'][5]="5 of";
$LANG['plugin_monitoring']['businessrule'][6]="6 of";
$LANG['plugin_monitoring']['businessrule'][7]="7 of";
$LANG['plugin_monitoring']['businessrule'][8]="8 of";
$LANG['plugin_monitoring']['businessrule'][9]="9 of";

$LANG['plugin_monitoring']['check'][0]="Check-Definition";
$LANG['plugin_monitoring']['check'][1]="Max. Anzahl Check-Wiederholungen";
$LANG['plugin_monitoring']['check'][2]="Zeit in Minuten zwischen 2 Checks";
$LANG['plugin_monitoring']['check'][3]="Time in minutes between 2 retries";

$LANG['plugin_monitoring']['command'][0]="Befehle";
$LANG['plugin_monitoring']['command'][1]="Command check";
$LANG['plugin_monitoring']['command'][2]="Befehlsname";
$LANG['plugin_monitoring']['command'][3]="Befehlszeile";
$LANG['plugin_monitoring']['command'][4]="Arguments description";

$LANG['plugin_monitoring']['component'][0]="Komponenten";
$LANG['plugin_monitoring']['component'][1]="Add a new component";
$LANG['plugin_monitoring']['component'][2]="Associated components";
$LANG['plugin_monitoring']['component'][3]="Manuelle Hosts";
$LANG['plugin_monitoring']['component'][4]="Dynmic hosts";
$LANG['plugin_monitoring']['component'][5]="Fields with asterisk are required";

$LANG['plugin_monitoring']['componentscatalog'][0]="Komponentenkatalog";

$LANG['plugin_monitoring']['config'][0]="Timezones (for graph)";
$LANG['plugin_monitoring']['config'][1]="Konfiguration";
$LANG['plugin_monitoring']['config'][2]="Path of RRDTOOL";

$LANG['plugin_monitoring']['contact'][0]="Contact";
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
$LANG['plugin_monitoring']['contact'][1]="Manage this user for monitoring system";
$LANG['plugin_monitoring']['contact'][20]="Contacts";
$LANG['plugin_monitoring']['contact'][2]="Pager";
$LANG['plugin_monitoring']['contact'][3]="Hosts";
$LANG['plugin_monitoring']['contact'][4]="Services";
$LANG['plugin_monitoring']['contact'][5]="Notifications";
$LANG['plugin_monitoring']['contact'][6]="Period";
$LANG['plugin_monitoring']['contact'][7]="Notify on DOWN host states";
$LANG['plugin_monitoring']['contact'][8]="Notify on WARNING service states";
$LANG['plugin_monitoring']['contact'][9]="Notify on UNREACHABLE host states";

$LANG['plugin_monitoring']['contacttemplate'][0]="Contact templates";
$LANG['plugin_monitoring']['contacttemplate'][1]="Default template";

$LANG['plugin_monitoring']['display'][0]="Dashboard";
$LANG['plugin_monitoring']['display'][1]="Page refresh (in seconds)";
$LANG['plugin_monitoring']['display'][2]="Critical";
$LANG['plugin_monitoring']['display'][3]="Warning";
$LANG['plugin_monitoring']['display'][4]="OK";

$LANG['plugin_monitoring']['entity'][0]="Tag";
$LANG['plugin_monitoring']['entity'][1]="Set tag to link entity with a specific shinken server";

$LANG['plugin_monitoring']['grouphost'][0]="hosts groups";
$LANG['plugin_monitoring']['grouphost'][1]="hosts group";

$LANG['plugin_monitoring']['host'][0]="hosts";
$LANG['plugin_monitoring']['host'][10]="Last month";
$LANG['plugin_monitoring']['host'][11]="Last 6 months";
$LANG['plugin_monitoring']['host'][12]="Last year";
$LANG['plugin_monitoring']['host'][13]="State ok";
$LANG['plugin_monitoring']['host'][14]="State critical";
$LANG['plugin_monitoring']['host'][15]="Last day";
$LANG['plugin_monitoring']['host'][16]="Last week";
$LANG['plugin_monitoring']['host'][18]="Add these hosts to monitoring";
$LANG['plugin_monitoring']['host'][19]="Equipments";
$LANG['plugin_monitoring']['host'][1]="Dependencies";
$LANG['plugin_monitoring']['host'][20]="Preview";
$LANG['plugin_monitoring']['host'][2]="Dynamic management";
$LANG['plugin_monitoring']['host'][3]="Static management";
$LANG['plugin_monitoring']['host'][4]="Dependency host";
$LANG['plugin_monitoring']['host'][5]="Aktive Checks";
$LANG['plugin_monitoring']['host'][6]="Passive checks";
$LANG['plugin_monitoring']['host'][7]="Dynamic dependencies are";
$LANG['plugin_monitoring']['host'][8]="Host";
$LANG['plugin_monitoring']['host'][9]="Prüfperiode";

$LANG['plugin_monitoring']['hostconfig'][0]="Hosts configuration";

$LANG['plugin_monitoring']['rrdtemplates'][0]="RRDTOOL templates";
$LANG['plugin_monitoring']['rrdtemplates'][1]="Upload perfdata and graph files";
$LANG['plugin_monitoring']['rrdtemplates'][2]="Find files on";

$LANG['plugin_monitoring']['service'][0]="Resources";
$LANG['plugin_monitoring']['service'][10]="Parameter (für NRPE)";
$LANG['plugin_monitoring']['service'][11]="Alias Kommando falls nötig (NRPE)";
$LANG['plugin_monitoring']['service'][12]="Vorlage (für Graphenerstellung)";
$LANG['plugin_monitoring']['service'][13]="Argument ([text:text] is used to get values dynamically)";
$LANG['plugin_monitoring']['service'][14]="Argument";
$LANG['plugin_monitoring']['service'][15]="Add this host to be monitored";
$LANG['plugin_monitoring']['service'][16]="Check Host";
$LANG['plugin_monitoring']['service'][17]="Configuration complete";
$LANG['plugin_monitoring']['service'][18]="Last check";
$LANG['plugin_monitoring']['service'][19]="State type";
$LANG['plugin_monitoring']['service'][1]="Criticity";
$LANG['plugin_monitoring']['service'][20]="Resource";
$LANG['plugin_monitoring']['service'][21]="Alle Ressourcen";
$LANG['plugin_monitoring']['service'][22]="Time in ms";
$LANG['plugin_monitoring']['service'][2]="Add a resource";
$LANG['plugin_monitoring']['service'][3]="or/and define these values";
$LANG['plugin_monitoring']['service'][4]="Arguments";
$LANG['plugin_monitoring']['service'][5]="Befehle";
$LANG['plugin_monitoring']['service'][6]="Active check";
$LANG['plugin_monitoring']['service'][7]="Passiver Check";
$LANG['plugin_monitoring']['service'][8]="Remote check";
$LANG['plugin_monitoring']['service'][9]="Tool für remote check";

$LANG['plugin_monitoring']['servicescatalog'][0]="Services catalog";
$LANG['plugin_monitoring']['servicescatalog'][1]="Degraded mode";

$LANG['plugin_monitoring']['servicesuggest'][0]="Suggests";

$LANG['plugin_monitoring']['title'][0]="Monitoring";
?>
