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


$LANG['plugin_monitoring']['businessrule'][0]="Règles métier";
$LANG['plugin_monitoring']['businessrule'][10]="10 sur";
$LANG['plugin_monitoring']['businessrule'][11]="Groupe";
$LANG['plugin_monitoring']['businessrule'][2]="2 sur";
$LANG['plugin_monitoring']['businessrule'][3]=" sur";
$LANG['plugin_monitoring']['businessrule'][4]="4 sur";
$LANG['plugin_monitoring']['businessrule'][5]="5 sur";
$LANG['plugin_monitoring']['businessrule'][6]="6 sur";
$LANG['plugin_monitoring']['businessrule'][7]="7 sur";
$LANG['plugin_monitoring']['businessrule'][8]="8 sur";
$LANG['plugin_monitoring']['businessrule'][9]="9 sur";

$LANG['plugin_monitoring']['check'][0]="Définition d'un contrôle";
$LANG['plugin_monitoring']['check'][1]="Tentatives de contrôles maximum";
$LANG['plugin_monitoring']['check'][2]="Temps en minutes entre 2 contrôles";
$LANG['plugin_monitoring']['check'][3]="Temps en minutes entre 2 essais";

$LANG['plugin_monitoring']['command'][0]="Commandes";
$LANG['plugin_monitoring']['command'][1]="Commande de contrôle";
$LANG['plugin_monitoring']['command'][2]="Nom de commande";
$LANG['plugin_monitoring']['command'][3]="Ligne de commande";
$LANG['plugin_monitoring']['command'][4]="Description des arguments";
$LANG['plugin_monitoring']['command'][5]="Expression rationnelle (pour les perf_data)";
$LANG['plugin_monitoring']['command'][6]="Légende (pour les perf_data)";

$LANG['plugin_monitoring']['component'][0]="Elements de contrôle";
$LANG['plugin_monitoring']['component'][1]="Ajouter un nouvel élément de contrôle";
$LANG['plugin_monitoring']['component'][2]="Elements de contrôle associés";
$LANG['plugin_monitoring']['component'][3]="Hôtes statiques";
$LANG['plugin_monitoring']['component'][4]="Hôtes dynamiques";

$LANG['plugin_monitoring']['componentscatalog'][0]="Catalogue d'éléments de contrôle";

$LANG['plugin_monitoring']['config'][0]="Fuseau horaire (pour les graphiques)";
$LANG['plugin_monitoring']['config'][1]="Configuration";

$LANG['plugin_monitoring']['contact'][0]="Contact";
$LANG['plugin_monitoring']['contact'][10]="Notifier quand l'état des services est UNKNOWN";
$LANG['plugin_monitoring']['contact'][11]="Notifier quand l'hôte est de nouveau disponible (état UP)";
$LANG['plugin_monitoring']['contact'][12]="Notifier quand l'état des services est CRITICAL";
$LANG['plugin_monitoring']['contact'][13]="Notifier quand les hôtes commencent et arrêtent d'osciller";
$LANG['plugin_monitoring']['contact'][14]="Notifier quand leservice est de nouveau disponible (état OK)";
$LANG['plugin_monitoring']['contact'][15]="Notifier quand un hôte ou service est planifié pour un démarrage ou un arrêt";
$LANG['plugin_monitoring']['contact'][16]="Notifier quand un service comment et d'arrête d'osciller";
$LANG['plugin_monitoring']['contact'][17]="Le contact ne recevra aucun type de notifications hôte";
$LANG['plugin_monitoring']['contact'][18]="Le contact ne recevra aucun type de notifications service";
$LANG['plugin_monitoring']['contact'][19]="Commande de notification";
$LANG['plugin_monitoring']['contact'][1]="Gérer cet utilisateur pour le système de monitoring";
$LANG['plugin_monitoring']['contact'][20]="Contacts";
$LANG['plugin_monitoring']['contact'][2]="Récepteur (pager)";
$LANG['plugin_monitoring']['contact'][3]="Hôtes";
$LANG['plugin_monitoring']['contact'][4]="Services";
$LANG['plugin_monitoring']['contact'][5]="Notifications";
$LANG['plugin_monitoring']['contact'][6]="Période";
$LANG['plugin_monitoring']['contact'][7]="Notifier quand l'état des hôtes est DOWN";
$LANG['plugin_monitoring']['contact'][8]="Notifier quand l'état des services est WARNING";
$LANG['plugin_monitoring']['contact'][9]="Notifier quand l'état des hôtes est UNREACHABLE";

$LANG['plugin_monitoring']['contacttemplate'][0]="Gabarit de contact";
$LANG['plugin_monitoring']['contacttemplate'][1]="Gabarit par défaut";

$LANG['plugin_monitoring']['grouphost'][0]="Groupes d'hôtes";
$LANG['plugin_monitoring']['grouphost'][1]="Groupe d'hôtes";

$LANG['plugin_monitoring']['host'][0]="Hôtes";
$LANG['plugin_monitoring']['host'][10]="Mois dernier";
$LANG['plugin_monitoring']['host'][11]="Derniers 6 mois";
$LANG['plugin_monitoring']['host'][12]="L'année écoulée";
$LANG['plugin_monitoring']['host'][13]="Etat ok";
$LANG['plugin_monitoring']['host'][14]="Etat critique";
$LANG['plugin_monitoring']['host'][15]="Dernier jour";
$LANG['plugin_monitoring']['host'][16]="Dernière semaine";
$LANG['plugin_monitoring']['host'][17]="temps";
$LANG['plugin_monitoring']['host'][18]="Ajouter ces hôtes à monitorer";
$LANG['plugin_monitoring']['host'][19]="Matériels";
$LANG['plugin_monitoring']['host'][1]="Dépendances";
$LANG['plugin_monitoring']['host'][2]="Gestion dynamique";
$LANG['plugin_monitoring']['host'][3]="gestion statique";
$LANG['plugin_monitoring']['host'][4]="Dépendances des hôtes";
$LANG['plugin_monitoring']['host'][5]="Contrôles actifs";
$LANG['plugin_monitoring']['host'][6]="Contrôles passifs";
$LANG['plugin_monitoring']['host'][7]="Les dépendances dynamiques sont";
$LANG['plugin_monitoring']['host'][8]="Hôte";
$LANG['plugin_monitoring']['host'][9]="Période de contrôle";

$LANG['plugin_monitoring']['service'][0]="Ressources";
$LANG['plugin_monitoring']['service'][10]="Utilisation d'arguments (seulement pour NRPE)";
$LANG['plugin_monitoring']['service'][11]="L'alias de commande si requis (seulement pour NRPE)";
$LANG['plugin_monitoring']['service'][12]="Gabarit (pour a génération des graphiques)";
$LANG['plugin_monitoring']['service'][13]="Argument ([text:text] est utilisé pour avoir les valeurs dynamiquement)";
$LANG['plugin_monitoring']['service'][14]="Argument";
$LANG['plugin_monitoring']['service'][15]="Ajouter cet hôte à monitorer";
$LANG['plugin_monitoring']['service'][16]="Contrôle de hôte";
$LANG['plugin_monitoring']['service'][17]="Configuration complète";
$LANG['plugin_monitoring']['service'][18]="Dernier contrôle";
$LANG['plugin_monitoring']['service'][19]="Type d'état";
$LANG['plugin_monitoring']['service'][1]="Criticité";
$LANG['plugin_monitoring']['service'][20]="Ressource";
$LANG['plugin_monitoring']['service'][21]="Toutes les ressources";
$LANG['plugin_monitoring']['service'][22]="Temps en ms";
$LANG['plugin_monitoring']['service'][2]="Ajouter un service";
$LANG['plugin_monitoring']['service'][3]="ou/et définir ces valeurs";
$LANG['plugin_monitoring']['service'][4]="Arguments";
$LANG['plugin_monitoring']['service'][5]="Commande";
$LANG['plugin_monitoring']['service'][6]="Contrôle actif";
$LANG['plugin_monitoring']['service'][7]="Contrôle passif";
$LANG['plugin_monitoring']['service'][8]="Contrôle à distance";
$LANG['plugin_monitoring']['service'][9]="Système utilisé pour le contrôle à distance";

$LANG['plugin_monitoring']['servicescatalog'][0]="Catalogue de services";
$LANG['plugin_monitoring']['servicescatalog'][1]="Mode dégradé";

$LANG['plugin_monitoring']['servicesuggest'][0]="Suggestions";

$LANG['plugin_monitoring']['title'][0]="Monitoring";
?>
