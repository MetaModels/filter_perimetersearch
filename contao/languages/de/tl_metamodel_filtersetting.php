<?php

/**
 * The MetaModels extension allows the creation of multiple collections of custom items,
 * each with its own unique set of selectable attributes, with attribute extendability.
 * The Front-End modules allow you to build powerful listing and filtering of the
 * data in each collection.
 *
 * PHP version 5
 *
 * @package    MetaModels
 * @subpackage FilterPerimetersearch
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Stefan Heimes <stefan_heimes@hotmail.com>
 * @copyright  The MetaModels team.
 * @license    LGPL-3.0+
 * @filesource
 */

/**
 * Legend
 */
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['geolocation_legend'] = 'Geolocation Einstellungen';
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['range_legend']       = 'Umkreis Einstellungen';
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['fefilter_legend']    = 'Frontendfilter Einstellungen';

/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['datamode']        = array(
    'Datenquelle',
    'Hier die Auswahl treffen, ob die Geokoordinaten aus einem Attribut oder aus zwei Attributen kommen.'
);
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['single_attr_id']  = array(
    'Attribute',
    'Wähle das Attribut mit dem Wert für Latitude und Longitude.'
);
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['first_attr_id']   = array(
    'Attribut - Latitude',
    'Wähle das Attribut mit dem Wert für die Latitude.'
);
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['second_attr_id']  = array(
    'Attribut - Longitude',
    'Wähle das Attribut mit dem Wert für die Longitude.'
);
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['rangemode']       = array(
    'Umkreis',
    'Wähle hier den Umkreis-Typ der angezeigt werden soll.'
);
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['range_preset']    = array(
    'Umkreis Voreinstellung',
    'Wähle hier die Voreinstellung des Umkreises.'
);
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['range_selection'] = array(
    'Umkreisauswahl',
    'Füge eine weitere Umkreisauswahl hinzu.'
);
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['lookupservice']   = array(
    'LookUp Services',
    'Wähle hier die den Lookup-Service zur Auflösung von Adressdaten.'
);
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['range_label']     = array(
    'Umkreisbezeichnung (Label)',
    'Zeige die Umkreisbezeichnug anstelle des Attributnamens.'
);
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['range_template']  = array(
    'Umkrreissuche Template',
    'Template für das Filterelement Umkreissuche. Standard: form widget.'
);
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['countrymode']     = array(
    'Sprachmodus',
    'Wähle hier die verwendete Sprache.'
);
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['country_preset']  = array(
    'Sprachauswahl',
    'Füge eine weitere Sprache hinzu.'
);
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['country_get']     = array(
    'Länder-GET-Parameter',
    'Füge einen GET-Parameter hinzu.'
);

/**
 * Options
 */
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['datamode_options']['single']     = 'Single Mode - ein Attribut';
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['datamode_options']['multi']      = 'Multi Mode - zwei Attribute';
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['rangemode_options']['free']      = 'Freie Eingabe';
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['rangemode_options']['preset']    = 'Vorauswahl durch das System';
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['rangemode_options']['selection'] = 'Auswahl-Modus';
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['countrymode_options']['none']    = 'Nichts';
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['countrymode_options']['preset']  = 'Auswahl durch das System';
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['countrymode_options']['get']     = 'verwende GET-Parameter';

/**
 * Filter types
 */
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['typenames']['perimetersearch'] = 'Umkreissuche';

/**
 * Lookup names
 */
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['perimetersearch']['google_maps']      = 'GoogleMaps Lookup';
$GLOBALS['TL_LANG']['tl_metamodel_filtersetting']['perimetersearch']['open_street_maps'] = 'OpenStreetMap Lookup';
