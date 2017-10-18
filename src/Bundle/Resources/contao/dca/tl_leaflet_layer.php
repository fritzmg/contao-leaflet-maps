<?php

/**
 * Leaflet maps for Contao CMS.
 *
 * @package    contao-leaflet-maps
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014-2017 netzmacht David Molineus. All rights reserved.
 * @license    LGPL-3.0 https://github.com/netzmacht/contao-leaflet-maps/blob/master/LICENSE
 * @filesource
 */

$GLOBALS['TL_DCA']['tl_leaflet_layer'] = [
    'config' => [
        'dataContainer'     => 'Table',
        'enableVersioning'  => true,
        'ctable'            => ['tl_leaflet_vector', 'tl_leaflet_marker'],
        'ondelete_callback' => [
            ['netzmacht.contao_leaflet.listeners.dca.layer', 'deleteRelations'],
        ],
        'sql'               => [
            'keys' => [
                'id'    => 'primary',
                'pid'   => 'index',
                'alias' => 'unique',
            ],
        ],
        'onload_callback'   => [
            ['netzmacht.contao_leaflet.listeners.dca.leaflet', 'loadLanguageFile'],
        ],
        'onsubmit_callback' => [
            ['netzmacht.contao_leaflet.listeners.dca.leaflet', 'clearCache'],
        ],
    ],
    'list'   => [
        'sorting'           => [
            'mode'                  => 5,
            'fields'                => ['title'],
            'flag'                  => 1,
            'icon'                  => 'bundles/netzmachtcontaoleaflet/img/layers.png',
            'panelLayout'           => 'filter;search,limit',
            'paste_button_callback' => ['netzmacht.contao_leaflet.listeners.dca.layer', 'getPasteButtons'],
        ],
        'label'             => [
            'fields'         => ['title'],
            'format'         => '%s',
            'label_callback' => ['netzmacht.contao_leaflet.listeners.dca.layer', 'generateRow'],
        ],
        'global_operations' => [
            'styles' => [
                'label'      => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['styles'],
                'href'       => 'table=tl_leaflet_style',
                'icon'       => 'bundles/netzmachtcontaoleaflet/img/style.png',
                'attributes' => 'onclick="Backend.getScrollOffset();"',
            ],
            'icons'  => [
                'label'      => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['icons'],
                'href'       => 'table=tl_leaflet_icon',
                'icon'       => 'bundles/netzmachtcontaoleaflet/img/icons.png',
                'attributes' => 'onclick="Backend.getScrollOffset();"',
            ],
            'popups' => [
                'label'      => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['popups'],
                'href'       => 'table=tl_leaflet_popup',
                'icon'       => 'bundles/netzmachtcontaoleaflet/img/popup.png',
                'attributes' => 'onclick="Backend.getScrollOffset();"',
            ],
            'all'    => [
                'label'      => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'       => 'act=select',
                'class'      => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset();"',
            ],
        ],
        'operations'        => [
            'markers' => [
                'label'           => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['markers'],
                'href'            => 'table=tl_leaflet_marker',
                'icon'            => 'edit.gif',
                'button_callback' => ['netzmacht.contao_leaflet.listeners.dca.layer', 'generateMarkersButton'],
            ],
            'vectors' => [
                'label'           => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['vectors'],
                'href'            => 'table=tl_leaflet_vector',
                'icon'            => 'edit.gif',
                'button_callback' => ['netzmacht.contao_leaflet.listeners.dca.layer', 'generateVectorsButton'],
            ],
            'edit'    => [
                'label' => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['edit'],
                'href'  => 'act=edit',
                'icon'  => 'header.gif',
            ],
            'copy'    => [
                'label' => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['copy'],
                'href'  => 'act=copy',
                'icon'  => 'copy.gif',
            ],
            'cut'     => [
                'label'      => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['cut'],
                'href'       => 'act=paste&amp;mode=cut',
                'icon'       => 'cut.gif',
                'attributes' => 'onclick="Backend.getScrollOffset()"',
            ],
            'delete'  => [
                'label'      => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['delete'],
                'href'       => 'act=delete',
                'icon'       => 'delete.gif',
                'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm']
                    . '\'))return false;Backend.getScrollOffset()"',
            ],
            'toggle'  => [
                'label'           => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['toggle'],
                'icon'            => 'visible.gif',
                'attributes'      => 'onclick="Backend.getScrollOffset(); 
                    return ContaoLeafletAjaxRequest.toggleVisibility(this,%s)"',
                'button_callback' => [
                    'netzmacht.contao_toolkit.dca.listeners.state_button_callback',
                    'handleButtonCallback',
                ],
                'toolkit'         => [
                    'state_button' => [
                        'stateColumn' => 'active',
                    ],
                ],
            ],
            'show'    => [
                'label' => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['show'],
                'href'  => 'act=show',
                'icon'  => 'show.gif',
            ],
        ],
    ],

    'palettes' => [
        '__selector__' => ['type'],
    ],

    'metapalettes' => [
        'default'                       => [
            'title'  => ['title', 'alias', 'type'],
            'config' => [],
            'style'  => [],
            'expert' => [':hide'],
            'active' => ['active'],
        ],
        'markers extends default'       => [
            '+expert' => ['pointToLayer'],
            '+config' => ['boundsMode', 'deferred'],
        ],
        'group extends default'         => [
            '+title'  => ['groupType'],
            '+active' => ['boundsMode'],
        ],
        'vectors extends default'       => [
            '+expert' => ['onEachFeature', 'pointToLayer'],
            '+config' => ['boundsMode', 'deferred'],
        ],
        'reference extends default'     => [
            '+title' => ['reference', 'standalone'],
        ],
        'markercluster extends default' => [
            'config'  => [
                'showCoverageOnHover',
                'zoomToBoundsOnClick',
                'removeOutsideVisibleBounds',
                'animateAddingMarkers',
                'spiderfyOnMaxZoom',
                'disableClusteringAtZoom',
                'maxClusterRadius',
                'singleMarkerMode',
            ],
            '+expert' => [
                'polygonOptions',
                'iconCreateFunction',
                'disableDefaultStyle',
            ],
        ],
        'tile extends default'          => [
            'config'  => [
                'tileUrl',
                'subdomains',
                'attribution',
                'minZoom',
                'maxZoom',
            ],
            '+expert' => [
                'errorTileUrl',
                'tileSize',
                'tms',
                'continuousWorld',
                'noWrap',
                'zoomReverse',
                'zoomOffset',
                'maxNativeZoom',
                'opacity',
                'zIndex',
                'unloadvisibleTiles',
                'updateWhenIdle',
                'detectRetina',
                'reuseTiles',
                'bounds',
            ],
        ],
        'overpass extends default'      => [
            'config'  => [
                'overpassQuery',
                'boundsMode',
                'minZoom',
                'overpassEndpoint',
                'overpassPopup',
            ],
            'style'   => [
                'amenityIcons',
            ],
            '+expert' => [
                'onEachFeature',
                'pointToLayer',
            ],
        ],

        'file extends default' => [
            '+config' => ['boundsMode', 'fileFormat'],
            '+expert' => [
                'onEachFeature',
                'pointToLayer',
            ],
        ],
    ],

    'metasubselectpalettes' => [
        'type'          => [
            'provider' => ['tile_provider', 'tile_provider_variant'],
        ],
        'tile_provider' => [
            'MapBox' => ['tile_provider_key'],
            'HERE'   => ['tile_provider_key', 'tile_provider_code'],
        ],
        'fileFormat' => [
            '!' => ['file']
        ]
    ],

    'metasubpalettes' => [
        'spiderfyOnMaxZoom' => ['spiderfyDistanceMultiplier'],
        'deferred'          => ['cache'],
        'cache'             => ['cacheLifeTime'],
    ],

    'fields' => [
        'id'                             => [
            'sql' => 'int(10) unsigned NOT NULL auto_increment',
        ],
        'pid'                            => [
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'sorting'                        => [
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'tstamp'                         => [
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'title'                          => [
            'label'     => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['title'],
            'exclude'   => true,
            'inputType' => 'text',
            'search'    => true,
            'eval'      => ['mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w50'],
            'sql'       => "varchar(255) NOT NULL default ''",
        ],
        'alias'                          => [
            'label'         => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['alias'],
            'exclude'       => true,
            'inputType'     => 'text',
            'search'        => true,
            'save_callback' => [
                ['netzmacht.contao_toolkit.dca.listeners.alias_generator', 'handleSaveCallback'],
                ['netzmacht.contao_leaflet.listeners.dca.validator', 'validateAlias'],
            ],
            'eval'          => ['mandatory' => false, 'maxlength' => 255, 'tl_class' => 'w50', 'unique' => true],
            'toolkit'       => [
                'alias_generator' => [
                    'factory' => 'netzmacht.contao_leaflet.definition.alias_generator.factory_default',
                    'fields'  => ['title'],
                ],
            ],
            'sql'           => 'varchar(255) NULL',
        ],
        'type'                           => [
            'label'            => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['type'],
            'exclude'          => true,
            'inputType'        => 'select',
            'filter'           => true,
            'eval'             => [
                'mandatory'          => true,
                'tl_class'           => 'w50',
                'includeBlankOption' => true,
                'submitOnChange'     => true,
                'chosen'             => true,
                'helpwizard'         => true,
            ],
            'options_callback' => ['netzmacht.contao_leaflet.listeners.dca.layer', 'getLayerOptions'],
            'reference'        => &$GLOBALS['TL_LANG']['leaflet_layer'],
            'sql'              => "varchar(32) NOT NULL default ''",
        ],
        'active'                         => [
            'label'         => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['active'],
            'exclude'       => true,
            'inputType'     => 'checkbox',
            'filter'        => true,
            'eval'          => ['tl_class' => 'w50'],
            'sql'           => "char(1) NOT NULL default ''",
            'save_callback' => [
                ['netzmacht.contao_leaflet.listeners.dca.leaflet', 'clearCache'],
            ],
        ],
        'tile_provider'                  => [
            'label'            => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['tile_provider'],
            'exclude'          => true,
            'inputType'        => 'select',
            'eval'             => [
                'mandatory'          => true,
                'tl_class'           => 'w50 clr',
                'includeBlankOption' => true,
                'submitOnChange'     => true,
                'chosen'             => true,
            ],
            'options_callback' => ['netzmacht.contao_leaflet.listeners.dca.layer', 'getProviderOptions'],
            'sql'              => "varchar(32) NOT NULL default ''",
        ],
        'tile_provider_variant'          => [
            'label'            => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['tile_provider_variant'],
            'exclude'          => true,
            'inputType'        => 'select',
            'eval'             => [
                'mandatory'      => false,
                'tl_class'       => 'w50',
                'submitOnChange' => true,
                'chosen'         => false,
            ],
            'options_callback' => ['netzmacht.contao_leaflet.listeners.dca.layer', 'getVariants'],
            'sql'              => "varchar(32) NOT NULL default ''",
        ],
        'tile_provider_key'              => [
            'label'     => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['tile_provider_key'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => ['mandatory' => true, 'maxlength' => 255, 'tl_class' => 'clr w50'],
            'sql'       => "varchar(255) NOT NULL default ''",
        ],
        'tile_provider_code'             => [
            'label'     => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['tile_provider_code'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => ['mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w50'],
            'sql'       => "varchar(255) NOT NULL default ''",
        ],
        'deferred'                       => [
            'label'     => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['deferred'],
            'exclude'   => true,
            'inputType' => 'checkbox',
            'default'   => true,
            'eval'      => ['tl_class' => 'w50 m12', 'submitOnChange' => true, 'isBoolean' => true],
            'sql'       => "char(1) NOT NULL default '1'",
        ],
        'groupType'                      => [
            'label'     => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['groupType'],
            'exclude'   => true,
            'inputType' => 'select',
            'eval'      => [
                'mandatory'      => true,
                'tl_class'       => 'w50',
                'submitOnChange' => true,
                'helpwizard'     => true,
            ],
            'default'   => 'layer',
            'options'   => ['layer', 'feature'],
            'reference' => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['groupTypes'],
            'sql'       => "varchar(32) NOT NULL default ''",
        ],
        'reference'                      => [
            'label'            => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['reference'],
            'exclude'          => true,
            'inputType'        => 'select',
            'options_callback' => ['netzmacht.contao_leaflet.listeners.dca.layer', 'getLayers'],
            'eval'             => [
                'mandatory'          => true,
                'tl_class'           => 'w50',
                'chosen'             => true,
                'includeBlankOption' => true,
            ],
            'sql'              => "int(10) unsigned NOT NULL default '0'",
        ],
        'standalone'                     => [
            'label'     => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['standalone'],
            'exclude'   => true,
            'inputType' => 'checkbox',
            'default'   => false,
            'eval'      => ['tl_class' => 'w50', 'submitOnChange' => false, 'isBoolean' => true],
            'sql'       => "char(1) NOT NULL default ''",
        ],
        'onEachFeature'                  => [
            'label'     => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['onEachFeature'],
            'exclude'   => true,
            'inputType' => 'textarea',
            'eval'      => [
                'preserveTags'   => true,
                'decodeEntities' => true,
                'allowHtml'      => true,
                'rte'            => 'ace|javascript',
                'tl_class'       => 'clr',
            ],
            'sql'       => 'mediumtext NULL',
        ],
        'pointToLayer'                   => [
            'label'     => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['pointToLayer'],
            'exclude'   => true,
            'inputType' => 'textarea',
            'eval'      => [
                'preserveTags'   => true,
                'decodeEntities' => true,
                'allowHtml'      => true,
                'rte'            => 'ace|javascript',
                'tl_class'       => 'clr',
            ],
            'sql'       => 'mediumtext NULL',
        ],
        'showCoverageOnHover'            => [
            'label'     => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['showCoverageOnHover'],
            'exclude'   => true,
            'inputType' => 'checkbox',
            'default'   => true,
            'eval'      => ['tl_class' => 'w50', 'submitOnChange' => false, 'isBoolean' => true],
            'sql'       => "char(1) NOT NULL default '1'",
        ],
        'zoomToBoundsOnClick'            => [
            'label'     => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['zoomToBoundsOnClick'],
            'exclude'   => true,
            'inputType' => 'checkbox',
            'default'   => true,
            'eval'      => ['tl_class' => 'w50', 'submitOnChange' => false, 'isBoolean' => true],
            'sql'       => "char(1) NOT NULL default '1'",
        ],
        'spiderfyOnMaxZoom'              => [
            'label'     => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['spiderfyOnMaxZoom'],
            'exclude'   => true,
            'inputType' => 'checkbox',
            'default'   => true,
            'eval'      => ['tl_class' => 'w50 m12', 'submitOnChange' => true, 'isBoolean' => true],
            'sql'       => "char(1) NOT NULL default '1'",
        ],
        'removeOutsideVisibleBounds'     => [
            'label'     => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['removeOutsideVisibleBounds'],
            'exclude'   => true,
            'inputType' => 'checkbox',
            'default'   => true,
            'eval'      => ['tl_class' => 'w50', 'submitOnChange' => false, 'isBoolean' => true],
            'sql'       => "char(1) NOT NULL default '1'",
        ],
        'animateAddingMarkers'           => [
            'label'     => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['animateAddingMarkers'],
            'exclude'   => true,
            'inputType' => 'checkbox',
            'default'   => false,
            'eval'      => ['tl_class' => 'w50', 'submitOnChange' => false, 'isBoolean' => true],
            'sql'       => "char(1) NOT NULL default ''",
        ],
        'disableClusteringAtZoom'        => [
            'label'            => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['disableClusteringAtZoom'],
            'exclude'          => true,
            'inputType'        => 'select',
            'options_callback' => ['netzmacht.contao_leaflet.listeners.dca.leaflet', 'getZoomLevels'],
            'default'          => null,
            'eval'             => [
                'maxlength'          => 4,
                'rgxp'               => 'digit',
                'tl_class'           => 'w50',
                'includeBlankOption' => true,
                'nullIfEmpty'        => true,
            ],
            'sql'              => 'int(4) NULL',
        ],
        'maxClusterRadius'               => [
            'label'     => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['maxClusterRadius'],
            'exclude'   => true,
            'inputType' => 'text',
            'default'   => null,
            'eval'      => ['maxlength' => 5, 'rgxp' => 'digit', 'tl_class' => 'w50', 'nullIfEmpty' => true],
            'sql'       => 'int(5) NULL',
        ],
        'singleMarkerMode'               => [
            'label'     => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['singleMarkerMode'],
            'exclude'   => true,
            'inputType' => 'checkbox',
            'default'   => false,
            'eval'      => ['tl_class' => 'w50 m12', 'submitOnChange' => false, 'isBoolean' => true],
            'sql'       => "char(1) NOT NULL default ''",
        ],
        'polygonOptions'                 => [
            'label'     => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['polygonOptions'],
            'exclude'   => true,
            'inputType' => 'textarea',
            'eval'      => [
                'preserveTags'   => true,
                'decodeEntities' => true,
                'allowHtml'      => true,
                'rte'            => 'ace|json',
                'tl_class'       => 'clr',
            ],
            'sql'       => 'mediumtext NULL',
        ],
        'spiderfyDistanceMultiplier'     => [
            'label'     => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['spiderfyDistanceMultiplier'],
            'exclude'   => true,
            'inputType' => 'text',
            'default'   => null,
            'eval'      => ['maxlength' => 5, 'rgxp' => 'digit', 'tl_class' => 'w50', 'nullIfEmpty' => true],
            'sql'       => 'int(5) NULL',
        ],
        'iconCreateFunction'             => [
            'label'     => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['iconCreateFunction'],
            'exclude'   => true,
            'inputType' => 'textarea',
            'eval'      => [
                'preserveTags'   => true,
                'decodeEntities' => true,
                'allowHtml'      => true,
                'rte'            => 'ace|javascript',
                'tl_class'       => 'clr',
            ],
            'sql'       => 'mediumtext NULL',
        ],
        'disableDefaultStyle'            => [
            'label'     => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['disableDefaultStyle'],
            'exclude'   => true,
            'inputType' => 'checkbox',
            'default'   => false,
            'eval'      => ['tl_class' => 'w50', 'submitOnChange' => false, 'isBoolean' => true],
            'sql'       => "char(1) NOT NULL default ''",
        ],
        'boundsMode'                     => [
            'label'            => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['boundsMode'],
            'exclude'          => true,
            'inputType'        => 'select',
            'options_callback' => ['netzmacht.contao_leaflet.listeners.dca.layer', 'getBoundsModes'],
            'eval'             => ['tl_class' => 'w50', 'includeBlankOption' => true],
            'sql'              => "varchar(6) NOT NULL default ''",
        ],
        'tileUrl'                        => [
            'label'     => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['tileUrl'],
            'exclude'   => true,
            'inputType' => 'text',
            'default'   => '',
            'eval'      => ['maxlength' => 255, 'tl_class' => 'w50', 'mandatory' => true],
            'sql'       => "varchar(255) NOT NULL default ''",
        ],
        'minZoom'                        => [
            'label'            => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['minZoom'],
            'exclude'          => true,
            'inputType'        => 'select',
            'options_callback' => ['netzmacht.contao_leaflet.listeners.dca.leaflet', 'getZoomLevels'],
            'eval'             => [
                'maxlength'          => 4,
                'rgxp'               => 'digit',
                'tl_class'           => 'w50',
                'includeBlankOption' => true,
                'nullIfEmpty'        => true,
            ],
            'sql'              => 'int(4) NULL',
        ],
        'maxZoom'                        => [
            'label'            => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['maxZoom'],
            'exclude'          => true,
            'inputType'        => 'select',
            'options_callback' => ['netzmacht.contao_leaflet.listeners.dca.leaflet', 'getZoomLevels'],
            'eval'             => [
                'maxlength'          => 4,
                'rgxp'               => 'digit',
                'tl_class'           => 'w50',
                'includeBlankOption' => true,
                'nullIfEmpty'        => true,
            ],
            'sql'              => 'int(4) NULL',
        ],
        'maxNativeZoom'                  => [
            'label'            => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['maxNativeZoom'],
            'exclude'          => true,
            'inputType'        => 'select',
            'options_callback' => ['netzmacht.contao_leaflet.listeners.dca.leaflet', 'getZoomLevels'],
            'eval'             => [
                'maxlength'          => 4,
                'rgxp'               => 'digit',
                'tl_class'           => 'w50',
                'includeBlankOption' => true,
                'nullIfEmpty'        => true,
            ],
            'sql'              => 'int(4) NULL',
        ],
        'tileSize'                       => [
            'label'     => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['tileSize'],
            'exclude'   => true,
            'inputType' => 'text',
            'default'   => null,
            'eval'      => ['maxlength' => 5, 'rgxp' => 'digit', 'tl_class' => 'w50', 'nullIfEmpty' => true],
            'sql'       => 'int(5) NULL',
        ],
        'subdomains'                     => [
            'label'     => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['subdomains'],
            'exclude'   => true,
            'inputType' => 'text',
            'default'   => '',
            'eval'      => ['maxlength' => 16, 'tl_class' => 'w50'],
            'sql'       => "varchar(16) NOT NULL default ''",
        ],
        'errorTileUrl'                   => [
            'label'     => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['errorTileUrl'],
            'exclude'   => true,
            'inputType' => 'text',
            'default'   => '',
            'eval'      => ['maxlength' => 255, 'tl_class' => 'w50'],
            'sql'       => "varchar(255) NOT NULL default ''",
        ],
        'attribution'                    => [
            'label'     => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['attribution'],
            'exclude'   => true,
            'inputType' => 'text',
            'default'   => '',
            'eval'      => ['maxlength' => 255, 'tl_class' => 'long', 'allowHtml' => true],
            'sql'       => "varchar(255) NOT NULL default ''",
        ],
        'tms'                            => [
            'label'     => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['tms'],
            'exclude'   => true,
            'inputType' => 'checkbox',
            'default'   => false,
            'eval'      => ['tl_class' => 'w50'],
            'sql'       => "char(1) NOT NULL default ''",
        ],
        'continuousWorld'                => [
            'label'     => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['continuousWorld'],
            'exclude'   => true,
            'inputType' => 'checkbox',
            'default'   => false,
            'eval'      => ['tl_class' => 'w50'],
            'sql'       => "char(1) NOT NULL default ''",
        ],
        'noWrap'                         => [
            'label'     => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['noWrap'],
            'exclude'   => true,
            'inputType' => 'checkbox',
            'default'   => false,
            'eval'      => ['tl_class' => 'w50'],
            'sql'       => "char(1) NOT NULL default ''",
        ],
        'zoomOffset'                     => [
            'label'     => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['zoomOffset'],
            'exclude'   => true,
            'inputType' => 'text',
            'default'   => null,
            'eval'      => ['maxlength' => 5, 'rgxp' => 'digit', 'tl_class' => 'w50', 'nullIfEmpty' => true],
            'sql'       => 'int(5) NULL',
        ],
        'zoomReverse'                    => [
            'label'     => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['zoomReverse'],
            'exclude'   => true,
            'inputType' => 'checkbox',
            'default'   => false,
            'eval'      => ['tl_class' => 'w50'],
            'sql'       => "char(1) NOT NULL default ''",
        ],
        'opacity'                        => [
            'label'     => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['opacity'],
            'exclude'   => true,
            'inputType' => 'text',
            'default'   => '1.0',
            'eval'      => ['mandatory' => false, 'maxlength' => 4, 'rgxp' => 'digit', 'tl_class' => 'w50 clr'],
            'sql'       => "varchar(4) NOT NULL default ''",
        ],
        'zIndex'                         => [
            'label'     => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['zIndex'],
            'exclude'   => true,
            'inputType' => 'text',
            'default'   => null,
            'eval'      => ['maxlength' => 5, 'rgxp' => 'digit', 'tl_class' => 'w50', 'nullIfEmpty' => true],
            'sql'       => 'int(5) NULL',
        ],
        'unloadvisibleTiles'             => [
            'label'     => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['unloadvisibleTiles'],
            'exclude'   => true,
            'inputType' => 'checkbox',
            'default'   => false,
            'eval'      => ['tl_class' => 'w50'],
            'sql'       => "char(1) NOT NULL default ''",
        ],
        'updateWhenIdle'                 => [
            'label'     => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['updateWhenIdle'],
            'exclude'   => true,
            'inputType' => 'checkbox',
            'default'   => false,
            'eval'      => ['tl_class' => 'w50'],
            'sql'       => "char(1) NOT NULL default ''",
        ],
        'detectRetina'                   => [
            'label'     => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['detectRetina'],
            'exclude'   => true,
            'inputType' => 'checkbox',
            'default'   => false,
            'eval'      => ['tl_class' => 'w50'],
            'sql'       => "char(1) NOT NULL default ''",
        ],
        'reuseTiles'                     => [
            'label'     => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['reuseTiles'],
            'exclude'   => true,
            'inputType' => 'checkbox',
            'default'   => false,
            'eval'      => ['tl_class' => 'w50'],
            'sql'       => "char(1) NOT NULL default ''",
        ],
        'bounds'                         => [
            'label'         => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['bounds'],
            'exclude'       => true,
            'inputType'     => 'text',
            'save_callback' => [
            ],
            'eval'          => [
                'maxlength'   => 255,
                'multiple'    => true,
                'size'        => 2,
                'tl_class'    => 'long clr',
                'nullIfEmpty' => true,
            ],
            'sql'           => 'mediumblob NULL',
        ],
        'cache'                          => [
            'label'     => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['cache'],
            'exclude'   => true,
            'inputType' => 'checkbox',
            'default'   => false,
            'eval'      => ['tl_class' => 'w50 m12', 'submitOnChange' => true],
            'sql'       => "char(1) NOT NULL default ''",
        ],
        'cacheLifeTime'                  => [
            'label'     => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['cacheLifeTime'],
            'exclude'   => true,
            'inputType' => 'text',
            'default'   => 0,
            'eval'      => ['maxlength' => 5, 'rgxp' => 'digit', 'tl_class' => 'w50'],
            'sql'       => "int(10) unsigned NOT NULL default '0'",
        ],
        'overpassQuery'                  => [
            'label'     => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['overpassQuery'],
            'exclude'   => true,
            'inputType' => 'textarea',
            'eval'      => [
                'preserveTags'   => true,
                'decodeEntities' => true,
                'allowHtml'      => true,
                'rte'            => 'ace',
                'tl_class'       => 'clr',
            ],
            'sql'       => 'mediumtext NULL',
        ],
        'overpassEndpoint'               => [
            'label'     => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['overpassEndpoint'],
            'exclude'   => true,
            'inputType' => 'text',
            'default'   => '',
            'eval'      => ['tl_class' => 'w50'],
            'sql'       => "varchar(255) NOT NULL default ''",
        ],
        'overpassCallback'               => [
            'label'     => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['overpassCallback'],
            'exclude'   => true,
            'inputType' => 'textarea',
            'eval'      => [
                'preserveTags'   => true,
                'decodeEntities' => true,
                'allowHtml'      => true,
                'rte'            => 'ace|javascript',
                'tl_class'       => 'clr',
            ],
            'sql'       => 'mediumtext NULL',
        ],
        'minZoomIndicatorPosition'       => [
            'label'     => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['minZoomIndicatorPosition'],
            'exclude'   => true,
            'inputType' => 'select',
            'filter'    => true,
            'sorting'   => true,
            'options'   => ['topleft', 'topright', 'bottomleft', 'bottomright'],
            'reference' => &$GLOBALS['TL_LANG']['tl_leaflet_layer'],
            'eval'      => ['mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w50', 'helpwizard' => true],
            'sql'       => "varchar(255) NOT NULL default ''",
        ],
        'minZoomIndicatorMessage'        => [
            'label'     => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['minZoomIndicatorMessage'],
            'exclude'   => true,
            'inputType' => 'text',
            'default'   => '',
            'eval'      => ['tl_class' => 'clr w50'],
            'sql'       => "varchar(255) NOT NULL default ''",
        ],
        'minZoomIndicatorMessageNoLayer' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['minZoomIndicatorMessageNoLayer'],
            'exclude'   => true,
            'inputType' => 'text',
            'default'   => '',
            'eval'      => ['tl_class' => 'w50'],
            'sql'       => "varchar(255) NOT NULL default ''",
        ],
        'debug'                          => [
            'label'     => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['debug'],
            'exclude'   => true,
            'inputType' => 'checkbox',
            'default'   => false,
            'eval'      => ['tl_class' => 'w50 m12'],
            'sql'       => "char(1) NOT NULL default ''",
        ],
        'amenityIcons'                   => [
            'label'            => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['amenityIcons'],
            'exclude'          => true,
            'inputType'        => 'multiColumnWizard',
            'options_callback' => ['netzmacht.contao_leaflet.listeners.dca.layer', 'getIcons'],
            'eval'             => [
                'tl_class'     => 'leaflet-mcw leaflet-mcw-amenity-icons',
                'columnFields' => [
                    'amenity' => [
                        'label'            => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['amenity'],
                        'exclude'          => true,
                        'inputType'        => 'select',
                        'options_callback' => ['netzmacht.contao_leaflet.listeners.dca.layer', 'getAmenities'],
                        'eval'             => [
                            'mandatory' => true,
                            'tl_class'  => 'w50',
                            'style'     => 'width: 200px',
                            'chosen'    => true,
                        ],
                    ],
                    'icon'    => [
                        'label'            => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['amenityIcon'],
                        'exclude'          => true,
                        'inputType'        => 'select',
                        'options_callback' => ['netzmacht.contao_leaflet.listeners.dca.layer', 'getIcons'],
                        'eval'             => [
                            'mandatory' => true,
                            'tl_class'  => 'w50',
                            'style'     => 'width: 200px',
                            'chosen'    => true,
                        ],
                    ],
                ],
            ],
            'sql'              => 'blob NULL',
        ],
        'overpassPopup'                  => [
            'label'     => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['overpassPopup'],
            'exclude'   => true,
            'inputType' => 'textarea',
            'eval'      => [
                'preserveTags'   => true,
                'decodeEntities' => true,
                'allowHtml'      => true,
                'rte'            => 'ace|javascript',
                'tl_class'       => 'clr',
            ],
            'sql'       => 'mediumtext NULL',
        ],
        'fileFormat' => [
            'label'            => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['fileFormat'],
            'exclude'          => true,
            'inputType'        => 'select',
            'filter'           => true,
            'eval'             => [
                'mandatory'          => true,
                'tl_class'           => 'w50',
                'includeBlankOption' => true,
                'submitOnChange'     => true,
                'chosen'             => true,
                'helpwizard'         => true,
            ],
            'options_callback' => ['netzmacht.contao_leaflet.listeners.dca.layer', 'getFileFormats'],
            'reference'        => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['fileFormats'],
            'sql'              => "varchar(32) NOT NULL default ''",
        ],
        'file'                           => [
            'label'     => &$GLOBALS['TL_LANG']['tl_leaflet_layer']['file'],
            'exclude'   => true,
            'inputType' => 'fileTree',
            'load_callback' => [
                ['netzmacht.contao_leaflet.listeners.dca.layer', 'prepareFileWidget'],
            ],
            'eval'      => [
                'filesOnly'  => true,
                'fieldType'  => 'radio',
                'mandatory'  => true,
                'tl_class'   => 'clr',
            ],
            'sql'       => 'binary(16) NULL',
        ],
    ],
];
