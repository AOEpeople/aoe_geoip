<?php

if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['Aoe\\GeoIp\\Task\\UpdateMaxMindDBTask'] = array(
    'extension' => $_EXTKEY,
    'title' => 'LLL:EXT:aoe_geoip/Resources/Private/Language/Backend/locallang.xlf:update_maxmind_db_title',
    'description' => 'LLL:EXT:aoe_geoip/Resources/Private/Language/Backend/locallang.xlf:update_maxmind_db_description'
);