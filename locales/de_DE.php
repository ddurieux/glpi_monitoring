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
   @co-author Mathieu Simon
   @comment   Not translate this file, use https://www.transifex.net/projects/p/GLPI_monitoring/
   @copyright Copyright (c) 2011-2012 Plugin Monitoring for GLPI team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      https://forge.indepnet.net/projects/monitoring/
   @since     2011
 
   ------------------------------------------------------------------------
 */


$LANG['plugin_monitoring']['businessrule'][0]="Business rules";
$LANG['plugin_monitoring']['businessrule'][10]="10 von";
$LANG['plugin_monitoring']['businessrule'][11]="Gruppe";
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
$LANG['plugin_monitoring']['check'][3]="Zeit in Min. zwischen 2 Wiederholungen";

$LANG['plugin_monitoring']['command'][0]="Befehle";
$LANG['plugin_monitoring']['command'][1]="Befehls-Check";
$LANG['plugin_monitoring']['command'][2]="Befehlsname";
$LANG['plugin_monitoring']['command'][3]="Befehlszeile";
$LANG['plugin_monitoring']['command'][4]="Parameterbeschreibung";

$LANG['plugin_monitoring']['component'][0]="Komponenten";
$LANG['plugin_monitoring']['component'][1]="Neue Komponente hinzufügen";
$LANG['plugin_monitoring']['component'][2]="Verknüpfte Komponenten";
$LANG['plugin_monitoring']['component'][3]="Manuelle Hosts";
$LANG['plugin_monitoring']['component'][4]="Automatische Hosts";

$LANG['plugin_monitoring']['componentscatalog'][0]="Komponentenkatalog";

$LANG['plugin_monitoring']['config'][0]="Zeitzonen (für die Graphen)";
$LANG['plugin_monitoring']['config'][1]="Konfiguration";
$LANG['plugin_monitoring']['config'][2]="Pfad zu rrdtool";

$LANG['plugin_monitoring']['contact'][0]="Kontakt";
$LANG['plugin_monitoring']['contact'][10]="Benachrichtigen wenn Dienststatus UNBEKANNT";
$LANG['plugin_monitoring']['contact'][11]="Benachrichtigen bei Wiederherstellung von Hosts (Status UP)";
$LANG['plugin_monitoring']['contact'][12]="Benachrichtigen wenn Dienst KRITISCH";
$LANG['plugin_monitoring']['contact'][13]="Benachrichtigen wenn Host flattert (Start & Ende)";
$LANG['plugin_monitoring']['contact'][14]="Benachrichten wenn Dienststatus WIEDER OK";
$LANG['plugin_monitoring']['contact'][15]="Benachrichtigen bei Dienst- und Host-Wartungsfenstern";
$LANG['plugin_monitoring']['contact'][16]="Benachrichtigen wenn Dienststatus flattert (start & stop)";
$LANG['plugin_monitoring']['contact'][17]="Kontaktperson erhält keine Hostbenachrichtigungen";
$LANG['plugin_monitoring']['contact'][18]="Kontaktperson erhält keine Dienstbenachrichtigungen";
$LANG['plugin_monitoring']['contact'][19]="Kommando für Benachrichtigung";
$LANG['plugin_monitoring']['contact'][1]="Benutzer fürs Monitoring verwalten";
$LANG['plugin_monitoring']['contact'][20]="Kontakte";
$LANG['plugin_monitoring']['contact'][2]="Pager";
$LANG['plugin_monitoring']['contact'][3]="Hosts";
$LANG['plugin_monitoring']['contact'][4]="Dienste";
$LANG['plugin_monitoring']['contact'][5]="Benachrichtigungen";
$LANG['plugin_monitoring']['contact'][6]="Periode";
$LANG['plugin_monitoring']['contact'][7]="Benachrichtigen wenn Host DOWN";
$LANG['plugin_monitoring']['contact'][8]="Benachrichtigen wenn Dienststatus WARNUNG";
$LANG['plugin_monitoring']['contact'][9]="Benachrichtigen wenn Hoststatus NICHT ERREICHBAR";

$LANG['plugin_monitoring']['contacttemplate'][0]="Kontaktvorlage";
$LANG['plugin_monitoring']['contacttemplate'][1]="Standardvorlage";

$LANG['plugin_monitoring']['display'][0]="Dasboard";

$LANG['plugin_monitoring']['grouphost'][0]="Hostgruppen";
$LANG['plugin_monitoring']['grouphost'][1]="Hostgruppe";

$LANG['plugin_monitoring']['host'][0]="Hosts";
$LANG['plugin_monitoring']['host'][10]="Letzter Monat";
$LANG['plugin_monitoring']['host'][11]="Letzte 6 Monate";
$LANG['plugin_monitoring']['host'][12]="Letztes Jahr";
$LANG['plugin_monitoring']['host'][13]="Status OK";
$LANG['plugin_monitoring']['host'][14]="Status kritisch";
$LANG['plugin_monitoring']['host'][15]="Letzter Tag";
$LANG['plugin_monitoring']['host'][16]="Letzte Woche";
$LANG['plugin_monitoring']['host'][18]="Diese Hosts zum Monitoring hinzufügen";
$LANG['plugin_monitoring']['host'][19]="Geräte";
$LANG['plugin_monitoring']['host'][1]="Abhängigkeiten";
$LANG['plugin_monitoring']['host'][20]="Vorschau";
$LANG['plugin_monitoring']['host'][2]="Autom. Verwaltung";
$LANG['plugin_monitoring']['host'][3]="Man. Verwaltung";
$LANG['plugin_monitoring']['host'][4]="Automatischer Host";
$LANG['plugin_monitoring']['host'][5]="Aktive Checks";
$LANG['plugin_monitoring']['host'][6]="Passive Checks";
$LANG['plugin_monitoring']['host'][7]="Dynamische Abhängigkeiten:";
$LANG['plugin_monitoring']['host'][8]="Host";
$LANG['plugin_monitoring']['host'][9]="Prüfperiode";

$LANG['plugin_monitoring']['service'][0]="Ressourcen";
$LANG['plugin_monitoring']['service'][10]="Parameter (für NRPE)";
$LANG['plugin_monitoring']['service'][11]="Alias Kommando falls nötig (NRPE)";
$LANG['plugin_monitoring']['service'][12]="Vorlage (für Graphenerstellung)";
$LANG['plugin_monitoring']['service'][13]="Parameter ([text:text] wird benutzt um dynamische Werte zu holen)";
$LANG['plugin_monitoring']['service'][14]="Parameter";
$LANG['plugin_monitoring']['service'][15]="Diesen Host überwachen";
$LANG['plugin_monitoring']['service'][16]="Host prüfen";
$LANG['plugin_monitoring']['service'][17]="Konfiguration vollständig";
$LANG['plugin_monitoring']['service'][18]="Letzter Check";
$LANG['plugin_monitoring']['service'][19]="Status-Typ";
$LANG['plugin_monitoring']['service'][1]="Kritikalität";
$LANG['plugin_monitoring']['service'][20]="Ressource";
$LANG['plugin_monitoring']['service'][21]="Alle Ressourcen";
$LANG['plugin_monitoring']['service'][22]="Zeit in ms";
$LANG['plugin_monitoring']['service'][2]="Ressource hinzufügen";
$LANG['plugin_monitoring']['service'][3]="und/oder ";
$LANG['plugin_monitoring']['service'][4]="Parameter";
$LANG['plugin_monitoring']['service'][5]="Befehle";
$LANG['plugin_monitoring']['service'][6]="Aktiver Check";
$LANG['plugin_monitoring']['service'][7]="Passiver Check";
$LANG['plugin_monitoring']['service'][8]="Remote Check";
$LANG['plugin_monitoring']['service'][9]="Tool für remote check";

$LANG['plugin_monitoring']['servicescatalog'][0]="Dienstkatalog";
$LANG['plugin_monitoring']['servicescatalog'][1]="Verschlechterter Zustand";

$LANG['plugin_monitoring']['servicesuggest'][0]="Vorschläge";

$LANG['plugin_monitoring']['title'][0]="Monitoring";
?>
