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


$LANG['plugin_monitoring']['availability'][0]="Disponibilité";
$LANG['plugin_monitoring']['availability'][1]="Mois en cours";
$LANG['plugin_monitoring']['availability'][2]="Dernier mois";
$LANG['plugin_monitoring']['availability'][3]="Année en cours";

$LANG['plugin_monitoring']['businessrule'][0]="Règles métier";
$LANG['plugin_monitoring']['businessrule'][10]="10 sur";
$LANG['plugin_monitoring']['businessrule'][11]="Groupe";
$LANG['plugin_monitoring']['businessrule'][12]="Groupes";
$LANG['plugin_monitoring']['businessrule'][2]="2 sur";
$LANG['plugin_monitoring']['businessrule'][3]="3 sur";
$LANG['plugin_monitoring']['businessrule'][4]=" 4 sur";
$LANG['plugin_monitoring']['businessrule'][5]="5 sur";
$LANG['plugin_monitoring']['businessrule'][6]="6 sur";
$LANG['plugin_monitoring']['businessrule'][7]="7 sur";
$LANG['plugin_monitoring']['businessrule'][8]="8 sur";
$LANG['plugin_monitoring']['businessrule'][9]="9 sur";

$LANG['plugin_monitoring']['check'][0]="Définition d'un contrôle";
$LANG['plugin_monitoring']['check'][1]="Nombre  maximum de tentatives (nombre d'essais)";
$LANG['plugin_monitoring']['check'][2]="Temps en minutes entre 2 contrôles";
$LANG['plugin_monitoring']['check'][3]="Temps en minutes entre 2 essais";

$LANG['plugin_monitoring']['command'][0]="Commandes";
$LANG['plugin_monitoring']['command'][1]="Commande de contrôle";
$LANG['plugin_monitoring']['command'][2]="Nom de commande";
$LANG['plugin_monitoring']['command'][3]="Ligne de commande";
$LANG['plugin_monitoring']['command'][4]="Description des arguments";

$LANG['plugin_monitoring']['component'][0]="Composants";
$LANG['plugin_monitoring']['component'][10]="Communauté SNMP de l'équipement réseau ou imprimante";
$LANG['plugin_monitoring']['component'][11]="Liste des tags disponibles";
$LANG['plugin_monitoring']['component'][12]="Numéro de port réseau";
$LANG['plugin_monitoring']['component'][13]="Nom de port réseau";
$LANG['plugin_monitoring']['component'][14]="Arguments du composant";
$LANG['plugin_monitoring']['component'][15]="Exemple";
$LANG['plugin_monitoring']['component'][1]="Ajout d'un nouveau composant";
$LANG['plugin_monitoring']['component'][2]="Composants associés";
$LANG['plugin_monitoring']['component'][3]="Hôtes statiques";
$LANG['plugin_monitoring']['component'][4]="Hôtes dynamiques";
$LANG['plugin_monitoring']['component'][5]="Les champs avec un astérisque sont obligatoires";
$LANG['plugin_monitoring']['component'][6]="Composant";
$LANG['plugin_monitoring']['component'][7]="Nom du matériel";
$LANG['plugin_monitoring']['component'][8]="Port réseau ifDescr des matériels réseaux";
$LANG['plugin_monitoring']['component'][9]="Version SNMP de l'équipement réseau ou imprimante";

$LANG['plugin_monitoring']['componentscatalog'][0]="Catalogue de composants";
$LANG['plugin_monitoring']['componentscatalog'][1]="Intervalle entre 2 notifications(en minutes)";

$LANG['plugin_monitoring']['config'][0]="Zones de temps (pour les graphiques)";
$LANG['plugin_monitoring']['config'][1]="Configuration";
$LANG['plugin_monitoring']['config'][2]="Chemin de l'exécutable RRDtool";
$LANG['plugin_monitoring']['config'][3]="Rétention des logs (en jours)";
$LANG['plugin_monitoring']['config'][4]="Pas d'évènements trouvés dans les dernières minutes, donc Shinken semble arrêté";
$LANG['plugin_monitoring']['config'][5]="Serveur Shinken";
$LANG['plugin_monitoring']['config'][6]="Chemin et nom de l'exécutable php";

$LANG['plugin_monitoring']['contact'][0]="Contact";
$LANG['plugin_monitoring']['contact'][10]="Notifier quant l'état d'un service devient UNKNOWN";
$LANG['plugin_monitoring']['contact'][11]="Notifier quant un hôte redevient normal (état OK)";
$LANG['plugin_monitoring']['contact'][12]="Notifier quant l'état d'un service devient CRITICAL";
$LANG['plugin_monitoring']['contact'][13]="Notifier quand l'état des hôtes commencent et arrêtent d'osciller";
$LANG['plugin_monitoring']['contact'][14]="Notifier quand le service redevient normal (état OK)";
$LANG['plugin_monitoring']['contact'][15]="Envoi de notifications quand l'arrêt programmé d'un hôte ou d'un service programmé démarre et s'arrête";
$LANG['plugin_monitoring']['contact'][16]="Notifier quand l'état des services commencent et arrêtent d'osciller";
$LANG['plugin_monitoring']['contact'][17]="l'utilisateur ne va pas recevoir de notifications de type hôte";
$LANG['plugin_monitoring']['contact'][18]="l'utilisateur ne va pas recevoir de notifications de type service";
$LANG['plugin_monitoring']['contact'][19]="Commande de notification";
$LANG['plugin_monitoring']['contact'][1]="Gérer cet utilisateur dans le système de monitoring";
$LANG['plugin_monitoring']['contact'][20]="Contacts";
$LANG['plugin_monitoring']['contact'][2]="Pager";
$LANG['plugin_monitoring']['contact'][3]="Hôtes";
$LANG['plugin_monitoring']['contact'][4]="Services";
$LANG['plugin_monitoring']['contact'][5]="Notifications";
$LANG['plugin_monitoring']['contact'][6]="Période";
$LANG['plugin_monitoring']['contact'][7]="Notifier quant l'état d'un hôte devient DOWN";
$LANG['plugin_monitoring']['contact'][8]="Notifier quant l'état de service devient WARNING";
$LANG['plugin_monitoring']['contact'][9]="Notifier quant l'état d'un hôte devient UNREACHABLE";

$LANG['plugin_monitoring']['contacttemplate'][0]="Gabarits de contact";
$LANG['plugin_monitoring']['contacttemplate'][1]="Gabarit par défaut";

$LANG['plugin_monitoring']['display'][0]="Dashboard";
$LANG['plugin_monitoring']['display'][1]="Rechargement de la page (en secondes)";
$LANG['plugin_monitoring']['display'][2]="Critique";
$LANG['plugin_monitoring']['display'][3]="Avertissement";
$LANG['plugin_monitoring']['display'][4]="OK";
$LANG['plugin_monitoring']['display'][5]="Avertissement (données)";
$LANG['plugin_monitoring']['display'][6]="Avertissement (connexion)";

$LANG['plugin_monitoring']['displayview'][0]="Vues";
$LANG['plugin_monitoring']['displayview'][1]="Compteurs en titre (critique/warning/ok)";
$LANG['plugin_monitoring']['displayview'][2]="Afficher sur la page d'accueil de GLPI";
$LANG['plugin_monitoring']['displayview'][3]="Element à afficher";
$LANG['plugin_monitoring']['displayview'][4]="Vues sur la page d'accueil de GLPI";
$LANG['plugin_monitoring']['displayview'][5]="% de la largeur de la page";

$LANG['plugin_monitoring']['entity'][0]="Etiquette";
$LANG['plugin_monitoring']['entity'][1]="Définir l'étiquette pour lier une entité avec un serveur Shinken";

$LANG['plugin_monitoring']['grouphost'][0]="Groupes de hôtes";
$LANG['plugin_monitoring']['grouphost'][1]="Groupe de hôtes";


$LANG['plugin_monitoring']['host'][10]="Dernier mois";
$LANG['plugin_monitoring']['host'][0]="hôtes";
$LANG['plugin_monitoring']['host'][11]="6 derniers mois";
$LANG['plugin_monitoring']['host'][12]="Dernière année";
$LANG['plugin_monitoring']['host'][13]="Etat ok";
$LANG['plugin_monitoring']['host'][14]="Etat critique";
$LANG['plugin_monitoring']['host'][15]="Dernier jour";
$LANG['plugin_monitoring']['host'][16]="Dernière semaine";
$LANG['plugin_monitoring']['host'][18]="Ajouter des hôtes à monitorer";
$LANG['plugin_monitoring']['host'][19]="Equipements";
$LANG['plugin_monitoring']['host'][1]="Dépendances";
$LANG['plugin_monitoring']['host'][20]="Prévisualisation";
$LANG['plugin_monitoring']['host'][2]="Gestion dynamique";
$LANG['plugin_monitoring']['host'][3]="Gestion statique";
$LANG['plugin_monitoring']['host'][4]="Dépendance de hôte";
$LANG['plugin_monitoring']['host'][5]="Contrôles actifs";
$LANG['plugin_monitoring']['host'][6]="Contrôle passif";
$LANG['plugin_monitoring']['host'][7]="Les dépendances dynamiques sont";
$LANG['plugin_monitoring']['host'][8]="Hôte";
$LANG['plugin_monitoring']['host'][9]="Période de contrôle";

$LANG['plugin_monitoring']['hostconfig'][0]="Configuration des hôtes";

$LANG['plugin_monitoring']['log'][0]="Logs";
$LANG['plugin_monitoring']['log'][1]="La configuration a changée";
$LANG['plugin_monitoring']['log'][2]="ressources ajoutées";
$LANG['plugin_monitoring']['log'][3]="ressources supprimées";
$LANG['plugin_monitoring']['log'][4]="Redémarrer Shinken pour recharger cette nouvelle configuration";

$LANG['plugin_monitoring']['networkport'][0]="Ports réseaux des équipements réseaux";

$LANG['plugin_monitoring']['realms'][0]="Royaumes (realms)";
$LANG['plugin_monitoring']['realms'][1]="Royaume (reaml)";

$LANG['plugin_monitoring']['rrdtemplates'][0]="Gabarits RRDtool";
$LANG['plugin_monitoring']['rrdtemplates'][1]="Charger les fichiers perfdata et graph";
$LANG['plugin_monitoring']['rrdtemplates'][2]="Vous trouverez les fichier sur";

$LANG['plugin_monitoring']['service'][0]="Ressources";
$LANG['plugin_monitoring']['service'][10]="Utilisation d'arguments (seulement pour NRPE)";
$LANG['plugin_monitoring']['service'][11]="L'alias de commande si requis (seulement pour NRPE)";
$LANG['plugin_monitoring']['service'][12]="Gabarit (pour a génération des graphiques)";
$LANG['plugin_monitoring']['service'][13]="Argument ([text:text] est utilisé pour récupérer les valeurs automatiquement)";
$LANG['plugin_monitoring']['service'][14]="Argument";
$LANG['plugin_monitoring']['service'][15]="Ajouter cet hôte à monitorer";
$LANG['plugin_monitoring']['service'][16]="Contrôle de hôte";
$LANG['plugin_monitoring']['service'][17]="Configuration complète";
$LANG['plugin_monitoring']['service'][18]="Dernier contrôle";
$LANG['plugin_monitoring']['service'][19]="Etat d'un type";
$LANG['plugin_monitoring']['service'][1]="Criticité";
$LANG['plugin_monitoring']['service'][20]="Ressource";
$LANG['plugin_monitoring']['service'][21]="Toutes les ressources";
$LANG['plugin_monitoring']['service'][22]="Temps en ms";
$LANG['plugin_monitoring']['service'][23]="Ressource ajoutée";
$LANG['plugin_monitoring']['service'][24]="Arguments personalisés pour cette ressource (vide = hérité)";
$LANG['plugin_monitoring']['service'][25]="Configurer";
$LANG['plugin_monitoring']['service'][26]="Afficher le formulaire de recherche";
$LANG['plugin_monitoring']['service'][2]="Ajout d'une ressource";
$LANG['plugin_monitoring']['service'][3]="ou/et dinifi ces valeurs";
$LANG['plugin_monitoring']['service'][4]="Arguments";
$LANG['plugin_monitoring']['service'][5]="Commande";
$LANG['plugin_monitoring']['service'][6]="Contrôle actif";
$LANG['plugin_monitoring']['service'][7]="Contrôle passif";
$LANG['plugin_monitoring']['service'][8]="Contrôle distant";
$LANG['plugin_monitoring']['service'][9]="Système utilisé pour le contrôle à distance";

$LANG['plugin_monitoring']['servicescatalog'][0]="Catalogue de services";
$LANG['plugin_monitoring']['servicescatalog'][1]="Mode dégradé";
$LANG['plugin_monitoring']['servicescatalog'][2]="Catalogue de services ayant des ressources non disponibles";

$LANG['plugin_monitoring']['servicesuggest'][0]="Suggestions";

$LANG['plugin_monitoring']['title'][0]="Monitoring";

$LANG['plugin_monitoring']['weathermap'][0]="Weathermap";
$LANG['plugin_monitoring']['weathermap'][10]="Supprimer un noeud";
$LANG['plugin_monitoring']['weathermap'][11]="Ajouter un lien";
$LANG['plugin_monitoring']['weathermap'][12]="Modifier un lien";
$LANG['plugin_monitoring']['weathermap'][13]="Supprimer un lien";
$LANG['plugin_monitoring']['weathermap'][14]="Source";
$LANG['plugin_monitoring']['weathermap'][15]="Destination";
$LANG['plugin_monitoring']['weathermap'][16]="Bande passante entrante (in)";
$LANG['plugin_monitoring']['weathermap'][17]="Bande passante sortante (out)";
$LANG['plugin_monitoring']['weathermap'][18]="Regex passante entrante (in)";
$LANG['plugin_monitoring']['weathermap'][19]="Regex passante sortante (out)";
$LANG['plugin_monitoring']['weathermap'][1]="Utiliser ce composant pour Weathermap";
$LANG['plugin_monitoring']['weathermap'][2]="Expression régulière";
$LANG['plugin_monitoring']['weathermap'][3]="Longueur";
$LANG['plugin_monitoring']['weathermap'][4]="Hauteur";
$LANG['plugin_monitoring']['weathermap'][5]="Image de fond";
$LANG['plugin_monitoring']['weathermap'][6]="Noeud et liens";
$LANG['plugin_monitoring']['weathermap'][7]="Noeud";
$LANG['plugin_monitoring']['weathermap'][8]="Ajouter un noeud";
$LANG['plugin_monitoring']['weathermap'][9]="Modifier un noeud";

?>
