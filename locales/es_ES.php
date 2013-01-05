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
$LANG['plugin_monitoring']['availability'][2]="Último mes";
$LANG['plugin_monitoring']['availability'][3]="Current year";

$LANG['plugin_monitoring']['businessrule'][0]="Reglas de negocio";
$LANG['plugin_monitoring']['businessrule'][10]="10 de";
$LANG['plugin_monitoring']['businessrule'][11]="Grupo";
$LANG['plugin_monitoring']['businessrule'][12]="Grupos";
$LANG['plugin_monitoring']['businessrule'][2]="2 de";
$LANG['plugin_monitoring']['businessrule'][3]="3 de";
$LANG['plugin_monitoring']['businessrule'][4]="4 de";
$LANG['plugin_monitoring']['businessrule'][5]="5 de";
$LANG['plugin_monitoring']['businessrule'][6]="6 de";
$LANG['plugin_monitoring']['businessrule'][7]="7 de";
$LANG['plugin_monitoring']['businessrule'][8]="8 de";
$LANG['plugin_monitoring']['businessrule'][9]="9 de";

$LANG['plugin_monitoring']['check'][0]="Definición de la comprobación";
$LANG['plugin_monitoring']['check'][1]="Número máximo de intentos de la comprobación";
$LANG['plugin_monitoring']['check'][2]="Tiempo en minutos entre 2 comprobaciones";
$LANG['plugin_monitoring']['check'][3]="Tiempo en minutos entre 2 intentos";

$LANG['plugin_monitoring']['command'][0]="Comandos";
$LANG['plugin_monitoring']['command'][1]="Comando de comprobación";
$LANG['plugin_monitoring']['command'][2]="Nombre del comando";
$LANG['plugin_monitoring']['command'][3]="Línea del comando";
$LANG['plugin_monitoring']['command'][4]="Descripción de los argumentos";

$LANG['plugin_monitoring']['component'][0]="Componentes";
$LANG['plugin_monitoring']['component'][10]="SNMP community of network equipment or printer";
$LANG['plugin_monitoring']['component'][11]="List of tags available";
$LANG['plugin_monitoring']['component'][12]="Network port number";
$LANG['plugin_monitoring']['component'][13]="Network port name";
$LANG['plugin_monitoring']['component'][14]="Component arguments";
$LANG['plugin_monitoring']['component'][15]="Example";
$LANG['plugin_monitoring']['component'][1]="Añadir un nuevo componente";
$LANG['plugin_monitoring']['component'][2]="Componentes asociados";
$LANG['plugin_monitoring']['component'][3]="Hosts estáticos";
$LANG['plugin_monitoring']['component'][4]="Dynamic hosts";
$LANG['plugin_monitoring']['component'][5]="Los campos con asterisco (*) son requeridos";
$LANG['plugin_monitoring']['component'][6]="Component";
$LANG['plugin_monitoring']['component'][7]="Hostname of the device";
$LANG['plugin_monitoring']['component'][8]="Network port ifDescr of networking devices";
$LANG['plugin_monitoring']['component'][9]="SNMP version of network equipment or printer";

$LANG['plugin_monitoring']['componentscatalog'][0]="Catálogo de componentes";
$LANG['plugin_monitoring']['componentscatalog'][1]="Interval between 2 notifications (in minutes)";

$LANG['plugin_monitoring']['config'][0]="Zonas horarias (para gráfico)";
$LANG['plugin_monitoring']['config'][1]="Configuración";
$LANG['plugin_monitoring']['config'][2]="Path and bin name of RRDtool";
$LANG['plugin_monitoring']['config'][3]="Logs retention (in days)";
$LANG['plugin_monitoring']['config'][4]="No events found in last minutes, so Shinken seems stopped";
$LANG['plugin_monitoring']['config'][5]="Shinken Server";
$LANG['plugin_monitoring']['config'][6]="Path and bin name of php";

$LANG['plugin_monitoring']['contact'][0]="Contacto";
$LANG['plugin_monitoring']['contact'][10]="Notificar sobre estados de servicio DESCONOCIDO";
$LANG['plugin_monitoring']['contact'][11]="Notificar sobre restablecimientos de host (estados OPERATIVO)";
$LANG['plugin_monitoring']['contact'][12]="Notificar sobre estados de servicio CRÍTICO";
$LANG['plugin_monitoring']['contact'][13]="Noficar cuando el host comience y finalice de dar sacudidas (flapping)";
$LANG['plugin_monitoring']['contact'][14]="Notificar sobre restablecimientos de servicio (estados CORRECTO)";
$LANG['plugin_monitoring']['contact'][15]="Enviar notificaciones cuando comience y finalice el tiempo planificado de inactividad de un host o servicio";
$LANG['plugin_monitoring']['contact'][16]="Notificar cuando el servicio comience y finalice de dar sacudidas (flapping)";
$LANG['plugin_monitoring']['contact'][17]="El contacto no recibirá ningún tipo de notificación de host";
$LANG['plugin_monitoring']['contact'][18]="El contacto no recibirá ningún tipo de notificación de servicio";
$LANG['plugin_monitoring']['contact'][19]="Comando de notificación";
$LANG['plugin_monitoring']['contact'][1]="Emplear este usuario para el sistema de supervisión";
$LANG['plugin_monitoring']['contact'][20]="Contactos";
$LANG['plugin_monitoring']['contact'][2]="Localizador";
$LANG['plugin_monitoring']['contact'][3]="Hosts";
$LANG['plugin_monitoring']['contact'][4]="Servicios";
$LANG['plugin_monitoring']['contact'][5]="Notificaciones";
$LANG['plugin_monitoring']['contact'][6]="Periodo";
$LANG['plugin_monitoring']['contact'][7]="Notificar sobre estados de host INACTIVO";
$LANG['plugin_monitoring']['contact'][8]="Notificar sobre estados de servicio ADVERTENCIA";
$LANG['plugin_monitoring']['contact'][9]="Notificar sobre estados de host INALCANZABLE";

$LANG['plugin_monitoring']['contacttemplate'][0]="Plantilla de contacto";
$LANG['plugin_monitoring']['contacttemplate'][1]="Plantilla por defecto";

$LANG['plugin_monitoring']['display'][0]="Cuadro de mandos";
$LANG['plugin_monitoring']['display'][1]="Refresco de página (en segundos)";
$LANG['plugin_monitoring']['display'][2]="Crítico";
$LANG['plugin_monitoring']['display'][3]="Advertencia";
$LANG['plugin_monitoring']['display'][4]="Correcto";

$LANG['plugin_monitoring']['displayview'][0]="Views";
$LANG['plugin_monitoring']['displayview'][1]="Header counter (critical/warning/ok)";
$LANG['plugin_monitoring']['displayview'][2]="Display in GLPI home page";
$LANG['plugin_monitoring']['displayview'][3]="Element to display";
$LANG['plugin_monitoring']['displayview'][4]="Views in GLPI home page";
$LANG['plugin_monitoring']['displayview'][5]="% of the width of the frame";

$LANG['plugin_monitoring']['entity'][0]="Etiqueta";
$LANG['plugin_monitoring']['entity'][1]="Set tag to link entity with a specific Shinken server";

$LANG['plugin_monitoring']['grouphost'][0]="host groups";
$LANG['plugin_monitoring']['grouphost'][1]="host group";

$LANG['plugin_monitoring']['host'][0]="hosts";
$LANG['plugin_monitoring']['host'][11]="Últimos 6 meses";
$LANG['plugin_monitoring']['host'][12]="Último año";
$LANG['plugin_monitoring']['host'][13]="Estado correcto";
$LANG['plugin_monitoring']['host'][14]="Estado crítico";
$LANG['plugin_monitoring']['host'][15]="Último día";
$LANG['plugin_monitoring']['host'][16]="Última semana";
$LANG['plugin_monitoring']['host'][18]="Añadir estos hosts a la supervisión";
$LANG['plugin_monitoring']['host'][19]="Equipos";
$LANG['plugin_monitoring']['host'][1]="Dependencias";
$LANG['plugin_monitoring']['host'][20]="Vista previa";
$LANG['plugin_monitoring']['host'][2]="Gestión dinámica";
$LANG['plugin_monitoring']['host'][3]="Gestión estática";
$LANG['plugin_monitoring']['host'][4]="Host dependency";
$LANG['plugin_monitoring']['host'][5]="Comprobaciones activas";
$LANG['plugin_monitoring']['host'][6]="Comprobaciones pasivas";
$LANG['plugin_monitoring']['host'][7]="Dependencias dinámicas son";
$LANG['plugin_monitoring']['host'][8]="Host";
$LANG['plugin_monitoring']['host'][9]="Periodo de comprobación";

$LANG['plugin_monitoring']['hostconfig'][0]="Configuración de hosts";

$LANG['plugin_monitoring']['log'][0]="Logs";
$LANG['plugin_monitoring']['log'][1]="The configuration has changed";
$LANG['plugin_monitoring']['log'][2]="resources added";
$LANG['plugin_monitoring']['log'][3]="resources deleted";
$LANG['plugin_monitoring']['log'][4]="Restart Shinken to reload this new configuration";

$LANG['plugin_monitoring']['networkport'][0]="Network ports of networking devices";

$LANG['plugin_monitoring']['realms'][0]="Reamls";
$LANG['plugin_monitoring']['realms'][1]="Reaml";

$LANG['plugin_monitoring']['rrdtemplates'][0]="RRDtool templates";
$LANG['plugin_monitoring']['rrdtemplates'][1]="Cargar ficheros con estadísticas de ejecución y gráficos";
$LANG['plugin_monitoring']['rrdtemplates'][2]="Buscar ficheros en";

$LANG['plugin_monitoring']['service'][0]="Recursos";
$LANG['plugin_monitoring']['service'][10]="Use arguments (NRPE only)";
$LANG['plugin_monitoring']['service'][11]="Alias command if required (NRPE only)";
$LANG['plugin_monitoring']['service'][12]="Plantilla (para la generación de gráficos)";
$LANG['plugin_monitoring']['service'][13]="Argumento ([texto:texto] es empleado para obtener valores dinámicamente)";
$LANG['plugin_monitoring']['service'][14]="Argumento";
$LANG['plugin_monitoring']['service'][15]="Add this host to monitoring";
$LANG['plugin_monitoring']['service'][16]="Comprobar Host";
$LANG['plugin_monitoring']['service'][17]="Configuración completa";
$LANG['plugin_monitoring']['service'][18]="Última comprobación";
$LANG['plugin_monitoring']['service'][19]="Tipo de estado";
$LANG['plugin_monitoring']['service'][1]="Criticidad";
$LANG['plugin_monitoring']['service'][20]="Recurso";
$LANG['plugin_monitoring']['service'][21]="Todos los recursos";
$LANG['plugin_monitoring']['service'][22]="Tiempo (en milisegundos)";
$LANG['plugin_monitoring']['service'][23]="Resource deleted";
$LANG['plugin_monitoring']['service'][24]="Custom arguments for this resource (empty : inherit)";
$LANG['plugin_monitoring']['service'][25]="Configure";
$LANG['plugin_monitoring']['service'][2]="Añadir un recurso";
$LANG['plugin_monitoring']['service'][3]="o definir estos valores";
$LANG['plugin_monitoring']['service'][4]="Argumentos";
$LANG['plugin_monitoring']['service'][5]="Comando";
$LANG['plugin_monitoring']['service'][6]="Comprobación activa";
$LANG['plugin_monitoring']['service'][7]="Comprobación pasiva";
$LANG['plugin_monitoring']['service'][8]="Comprobación remota";
$LANG['plugin_monitoring']['service'][9]="Utilidad empleada para comprobación remota";

$LANG['plugin_monitoring']['servicescatalog'][0]="Catálogo de servicios";
$LANG['plugin_monitoring']['servicescatalog'][1]="Modo degradado";
$LANG['plugin_monitoring']['servicescatalog'][2]="Services catalog with resources not available";

$LANG['plugin_monitoring']['servicesuggest'][0]="Sugerencias";

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
