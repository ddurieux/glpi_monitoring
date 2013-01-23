<?php

/*
   ------------------------------------------------------------------------
   Plugin Monitoring for GLPI
   Copyright (C) 2011-2013 by the Plugin Monitoring for GLPI Development Team.

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
   along with Monitoring. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   Plugin Monitoring for GLPI
   @author    David Durieux
   @co-author 
   @comment   Not translate this file, use https://www.transifex.net/projects/p/GLPI_monitoring/
   @copyright Copyright (c) 2011-2013 Plugin Monitoring for GLPI team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      https://forge.indepnet.net/projects/monitoring/
   @since     2011
 
   ------------------------------------------------------------------------
 */


$LANG['plugin_monitoring']['availability'][0]="Availability";
$LANG['plugin_monitoring']['availability'][1]="Current month";
$LANG['plugin_monitoring']['availability'][2]="Letzter Monat";
$LANG['plugin_monitoring']['availability'][3]="Current year";

$LANG['plugin_monitoring']['businessrule'][0]="Business rules";
$LANG['plugin_monitoring']['businessrule'][10]="10 von";
$LANG['plugin_monitoring']['businessrule'][11]="Gruppe";
$LANG['plugin_monitoring']['businessrule'][12]="Gruppen";
$LANG['plugin_monitoring']['businessrule'][2]="2 von";
$LANG['plugin_monitoring']['businessrule'][3]="3 von";
$LANG['plugin_monitoring']['businessrule'][4]="4 von";
$LANG['plugin_monitoring']['businessrule'][5]="5 von";
$LANG['plugin_monitoring']['businessrule'][6]="6 von";
$LANG['plugin_monitoring']['businessrule'][7]="7 von";
$LANG['plugin_monitoring']['businessrule'][8]="8 von";
$LANG['plugin_monitoring']['businessrule'][9]="9 von";

$LANG['plugin_monitoring']['check'][0]="Check-Definition";
$LANG['plugin_monitoring']['check'][1]="Max. Anzahl Check-Wiederholungen";
$LANG['plugin_monitoring']['check'][2]="Zeit in Minuten zwischen 2 Checks";
$LANG['plugin_monitoring']['check'][3]="Zeit in Minuten zwischen 2 (erneuten) Versuchen";

$LANG['plugin_monitoring']['command'][0]="Befehle";
$LANG['plugin_monitoring']['command'][1]="Check mit Befehl";
$LANG['plugin_monitoring']['command'][2]="Befehlsname";
$LANG['plugin_monitoring']['command'][3]="Befehlszeile";
$LANG['plugin_monitoring']['command'][4]="Beschreibung des Arguments/Parameter";

$LANG['plugin_monitoring']['component'][0]="Komponenten";
$LANG['plugin_monitoring']['component'][10]="SNMP community of network equipment or printer";
$LANG['plugin_monitoring']['component'][11]="List of tags available";
$LANG['plugin_monitoring']['component'][12]="Network port number";
$LANG['plugin_monitoring']['component'][13]="Network port name";
$LANG['plugin_monitoring']['component'][14]="Component arguments";
$LANG['plugin_monitoring']['component'][15]="Example";
$LANG['plugin_monitoring']['component'][1]="Neue Komponente hinzufügen";
$LANG['plugin_monitoring']['component'][2]="Verknüpfte Komponenten";
$LANG['plugin_monitoring']['component'][3]="Manuelle Hosts";
$LANG['plugin_monitoring']['component'][4]="Dynamic hosts";
$LANG['plugin_monitoring']['component'][5]="Felder mit Stern (*) benötigt";
$LANG['plugin_monitoring']['component'][6]="Component";
$LANG['plugin_monitoring']['component'][7]="Hostname of the device";
$LANG['plugin_monitoring']['component'][8]="Network port ifDescr of networking devices";
$LANG['plugin_monitoring']['component'][9]="SNMP version of network equipment or printer";

$LANG['plugin_monitoring']['componentscatalog'][0]="Komponentenkatalog";
$LANG['plugin_monitoring']['componentscatalog'][1]="Interval between 2 notifications (in minutes)";

$LANG['plugin_monitoring']['config'][0]="Zeitzonen (für Graphen)";
$LANG['plugin_monitoring']['config'][1]="Konfiguration";
$LANG['plugin_monitoring']['config'][2]="Path and bin name of RRDtool";
$LANG['plugin_monitoring']['config'][3]="Logs retention (in days)";
$LANG['plugin_monitoring']['config'][4]="No events found in last minutes, so Shinken seems stopped";
$LANG['plugin_monitoring']['config'][5]="Shinken Server";
$LANG['plugin_monitoring']['config'][6]="Path and bin name of php";

$LANG['plugin_monitoring']['contact'][0]="Kontakt";
$LANG['plugin_monitoring']['contact'][10]="Benachrichtigen wenn Dienst UNBEKANNT";
$LANG['plugin_monitoring']['contact'][11]="Benachrichtigen wenn Host wieder ok (UP)";
$LANG['plugin_monitoring']['contact'][12]="Benachrichtigen wenn Dienst kritisch";
$LANG['plugin_monitoring']['contact'][13]="Benachrichtigen bei Host-fllapping (Start & Ende) ";
$LANG['plugin_monitoring']['contact'][14]="Benachrichtigung bei Wiederherstellung von Diensten (Status OK)";
$LANG['plugin_monitoring']['contact'][15]="Benachrichtigen geplanter Downtime von Hosts und Diensten";
$LANG['plugin_monitoring']['contact'][16]="Benachrichtigen bei Dienst-fllapping (Start & Ende) ";
$LANG['plugin_monitoring']['contact'][17]="Kontaktperson erhält keine Hostbenachrichtigungen";
$LANG['plugin_monitoring']['contact'][18]="Kontaktperson erhält keine Dienstbenachrichtigungen";
$LANG['plugin_monitoring']['contact'][19]="Benachrichtigungsbefehl";
$LANG['plugin_monitoring']['contact'][1]="Benutzer für Monitoring verwalten/zulassen";
$LANG['plugin_monitoring']['contact'][20]="Kontakte";
$LANG['plugin_monitoring']['contact'][2]="Pager";
$LANG['plugin_monitoring']['contact'][3]="Hosts";
$LANG['plugin_monitoring']['contact'][4]="Dienste";
$LANG['plugin_monitoring']['contact'][5]="Benachritigungen";
$LANG['plugin_monitoring']['contact'][6]="Periode";
$LANG['plugin_monitoring']['contact'][7]="Benachrichtigen wenn Hosts DOWN";
$LANG['plugin_monitoring']['contact'][8]="Benachrichtigen wenn Dienststatus WARNUNG";
$LANG['plugin_monitoring']['contact'][9]="Benachrichtigen wenn Dienst NICHT ERREICHBAR";

$LANG['plugin_monitoring']['contacttemplate'][0]="Kontaktvorlagen";
$LANG['plugin_monitoring']['contacttemplate'][1]="Standardvorlage";

$LANG['plugin_monitoring']['display'][0]="Dashboard";
$LANG['plugin_monitoring']['display'][1]="Seite autom. neu laden (in Sekunden)";
$LANG['plugin_monitoring']['display'][2]="Kritisch";
$LANG['plugin_monitoring']['display'][3]="Warnung";
$LANG['plugin_monitoring']['display'][4]="OK";
$LANG['plugin_monitoring']['display'][5]="Warnung (data)";
$LANG['plugin_monitoring']['display'][6]="Warnung (connection)";

$LANG['plugin_monitoring']['displayview'][0]="Views";
$LANG['plugin_monitoring']['displayview'][1]="Header counter (critical/warning/ok)";
$LANG['plugin_monitoring']['displayview'][2]="Display in GLPI home page";
$LANG['plugin_monitoring']['displayview'][3]="Element to display";
$LANG['plugin_monitoring']['displayview'][4]="Views in GLPI home page";
$LANG['plugin_monitoring']['displayview'][5]="% of the width of the frame";

$LANG['plugin_monitoring']['entity'][0]="Tag";
$LANG['plugin_monitoring']['entity'][1]="Set tag to link entity with a specific Shinken server";

$LANG['plugin_monitoring']['grouphost'][0]="host groups";
$LANG['plugin_monitoring']['grouphost'][1]="host group";

$LANG['plugin_monitoring']['host'][0]="Hosts";
$LANG['plugin_monitoring']['host'][11]="Letzte 6 Monate";
$LANG['plugin_monitoring']['host'][12]="Letztes Jahr";
$LANG['plugin_monitoring']['host'][13]="Status OK";
$LANG['plugin_monitoring']['host'][14]="Status kritisch";
$LANG['plugin_monitoring']['host'][15]="Letzter tag";
$LANG['plugin_monitoring']['host'][16]="Letzte Woche";
$LANG['plugin_monitoring']['host'][18]="Hosts überwachen";
$LANG['plugin_monitoring']['host'][19]="Zubehör";
$LANG['plugin_monitoring']['host'][1]="Abhängigkeiten";
$LANG['plugin_monitoring']['host'][20]="Vorschau";
$LANG['plugin_monitoring']['host'][2]="Dynamische Verwaltung";
$LANG['plugin_monitoring']['host'][3]="Statischer Verwaltung";
$LANG['plugin_monitoring']['host'][4]="Host dependency";
$LANG['plugin_monitoring']['host'][5]="Aktive Checks";
$LANG['plugin_monitoring']['host'][6]="Passive Checks";
$LANG['plugin_monitoring']['host'][7]="Dynamische Abhängigkeiten sind";
$LANG['plugin_monitoring']['host'][8]="Host";
$LANG['plugin_monitoring']['host'][9]="Prüfperiode";

$LANG['plugin_monitoring']['hostconfig'][0]="Hostkonfiguration";

$LANG['plugin_monitoring']['log'][0]="Logs";
$LANG['plugin_monitoring']['log'][1]="The configuration has changed";
$LANG['plugin_monitoring']['log'][2]="resources added";
$LANG['plugin_monitoring']['log'][3]="resources deleted";
$LANG['plugin_monitoring']['log'][4]="Restart Shinken to reload this new configuration";

$LANG['plugin_monitoring']['networkport'][0]="Network ports of networking devices";

$LANG['plugin_monitoring']['realms'][0]="Reamls";
$LANG['plugin_monitoring']['realms'][1]="Reaml";

$LANG['plugin_monitoring']['rrdtemplates'][0]="RRDtool templates";
$LANG['plugin_monitoring']['rrdtemplates'][1]="Performancedaten und Graphdateien heraufladen";
$LANG['plugin_monitoring']['rrdtemplates'][2]="Dateien finden auf";

$LANG['plugin_monitoring']['service'][0]="Ressourcen";
$LANG['plugin_monitoring']['service'][10]="Use arguments (NRPE only)";
$LANG['plugin_monitoring']['service'][11]="Alias command if required (NRPE only)";
$LANG['plugin_monitoring']['service'][12]="Vorlage (für Graphenerstellung)";
$LANG['plugin_monitoring']['service'][13]="Argument ([text:text] wird benutzt um Werte dynamisch zu holen)";
$LANG['plugin_monitoring']['service'][14]="Argument";
$LANG['plugin_monitoring']['service'][15]="Add this host to monitoring";
$LANG['plugin_monitoring']['service'][16]="Host prüfen";
$LANG['plugin_monitoring']['service'][17]="Kofiguration vollständig";
$LANG['plugin_monitoring']['service'][18]="Letzter Check";
$LANG['plugin_monitoring']['service'][19]="Statustyp";
$LANG['plugin_monitoring']['service'][1]="Kritizität";
$LANG['plugin_monitoring']['service'][20]="Ressource";
$LANG['plugin_monitoring']['service'][21]="Alle Ressourcen";
$LANG['plugin_monitoring']['service'][22]="Zeit in ms";
$LANG['plugin_monitoring']['service'][23]="Resource deleted";
$LANG['plugin_monitoring']['service'][24]="Custom arguments for this resource (empty : inherit)";
$LANG['plugin_monitoring']['service'][25]="Configure";
$LANG['plugin_monitoring']['service'][26]="Display search form";
$LANG['plugin_monitoring']['service'][2]="Ressource hinzufügen";
$LANG['plugin_monitoring']['service'][3]="und / oder diese Werte definieren";
$LANG['plugin_monitoring']['service'][4]="Argumente";
$LANG['plugin_monitoring']['service'][5]="Befehle";
$LANG['plugin_monitoring']['service'][6]="Aktiver Check";
$LANG['plugin_monitoring']['service'][7]="Passiver Check";
$LANG['plugin_monitoring']['service'][8]="Remotecheck";
$LANG['plugin_monitoring']['service'][9]="Tool für remote check";

$LANG['plugin_monitoring']['servicescatalog'][0]="Dienstkatalog";
$LANG['plugin_monitoring']['servicescatalog'][1]="Verschlechtert";
$LANG['plugin_monitoring']['servicescatalog'][2]="Services catalog with resources not available";

$LANG['plugin_monitoring']['servicesuggest'][0]="Vorschläge";

$LANG['plugin_monitoring']['title'][0]="Monitoring";

$LANG['plugin_monitoring']['weathermap'][0]="Weathermap";
$LANG['plugin_monitoring']['weathermap'][10]="Delete a node";
$LANG['plugin_monitoring']['weathermap'][11]="Add a link";
$LANG['plugin_monitoring']['weathermap'][12]="Edit a link";
$LANG['plugin_monitoring']['weathermap'][13]="Delete a link";
$LANG['plugin_monitoring']['weathermap'][14]="Source";
$LANG['plugin_monitoring']['weathermap'][15]="Destination";
$LANG['plugin_monitoring']['weathermap'][16]="Max bandwidth input";
$LANG['plugin_monitoring']['weathermap'][17]="Max bandwidth output";
$LANG['plugin_monitoring']['weathermap'][18]="Regex bandwidth input";
$LANG['plugin_monitoring']['weathermap'][19]="Regex bandwidth output";
$LANG['plugin_monitoring']['weathermap'][1]="Use this component for Weathermap";
$LANG['plugin_monitoring']['weathermap'][2]="Regex";
$LANG['plugin_monitoring']['weathermap'][3]="Width";
$LANG['plugin_monitoring']['weathermap'][4]="Height";
$LANG['plugin_monitoring']['weathermap'][5]="Background image";
$LANG['plugin_monitoring']['weathermap'][6]="Nodes and links";
$LANG['plugin_monitoring']['weathermap'][7]="Node";
$LANG['plugin_monitoring']['weathermap'][8]="Add a node";
$LANG['plugin_monitoring']['weathermap'][9]="Edit a node";

?>
