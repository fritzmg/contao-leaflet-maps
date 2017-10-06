<?php

/**
 * Leaflet maps for Contao CMS.
 *
 * @package    contao-leaflet-maps
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2016-2017 netzmacht David Molineus. All rights reserved.
 * @license    LGPL-3.0 https://github.com/netzmacht/contao-leaflet-maps/blob/master/LICENSE
 * @filesource
 */

$GLOBALS['TL_DCA']['tl_leaflet_icon'] = array
(
    'config' => array(
        'dataContainer'    => 'Table',
        'enableVersioning' => true,
        'sql'              => array
        (
            'keys' => array
            (
                'id'    => 'primary',
                'alias' => 'unique',
            )
        ),
        'onsubmit_callback' => [
            ['netzmacht.contao_leaflet_maps.listeners.dca.leaflet', 'clearCache'],
        ],
    ),

    'list' => array
    (
        'sorting' => array
        (
            'mode'                    => 1,
            'fields'                  => array('title'),
            'flag'                    => 1,
            'panelLayout'             => 'limit',
            'headerFields'            => array('title', 'type'),
        ),
        'label' => array
        (
            'fields'                  => array('title', 'type'),
            'format'                  => '%s <span class="tl_gray">[%s]</span>',
        ),
        'global_operations' => array
        (
            'layers' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_leaflet_icon']['layersBtn'],
                'href'                => 'table=tl_leaflet_layer',
                'icon'                => 'bundles/netzmachtcontaoleaflet/img/layers.png',
                'attributes'          => 'onclick="Backend.getScrollOffset();" accesskey="e"'
            ),
            'styles' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_leaflet_icon']['styles'],
                'href'                => 'table=tl_leaflet_style',
                'icon'                => 'bundles/netzmachtcontaoleaflet/img/style.png',
                'attributes'          => 'onclick="Backend.getScrollOffset();"'
            ),
            'popups' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_leaflet_icon']['popups'],
                'href'                => 'table=tl_leaflet_popup',
                'icon'                => 'bundles/netzmachtcontaoleaflet/img/popup.png',
                'attributes'          => 'onclick="Backend.getScrollOffset();"'
            ),
            'all' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'                => 'act=select',
                'class'               => 'header_edit_all',
                'attributes'          => 'onclick="Backend.getScrollOffset();" accesskey="e"'
            ),
        ),
        'operations' => array
        (
            'edit' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_leaflet_icon']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.gif'
            ),
            'copy' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_leaflet_icon']['copy'],
                'href'                => 'act=copy',
                'icon'                => 'copy.gif'
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_leaflet_icon']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.gif',
                'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm']
                    . '\'))return false;Backend.getScrollOffset()"'
            ),
            'toggle' => array
            (
                'label'           => &$GLOBALS['TL_LANG']['tl_leaflet_icon']['toggle'],
                'icon'            => 'visible.gif',
                'attributes'      => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
                'button_callback' => [
                    'netzmacht.contao_toolkit.dca.listeners.state_button_callback',
                    'handleButtonCallback'
                ],
                'toolkit' => [
                    'state_button' => [
                        'stateColumn' => ['active']
                    ]
                ],
            ),
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_leaflet_icon']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.gif'
            )
        )
    ),

    'palettes' => array(
        '__selector__' => array('type')
    ),

    'metapalettes'    => array(
        'default' => array(
            'title'   => array('title', 'alias', 'type'),
        ),
        'image extends default' => array(
            'config'  => array(
                'iconImage',
                'iconRetinaImage',
                'iconAnchor',
                'popupAnchor',
                'className',
            ),
            'shadow'  => array(
                'shadowImage',
                'shadowRetinaImage',
                'shadowAnchor',
            ),
            'active' => array(
                'active'
            )
        ),

        'div extends default' => array(
            'config' => array(
                'html',
                'iconSize',
                'iconAnchor',
                'popupAnchor',
                'className',
            ),
            'active' => array(
                'active'
            )
        ),

        'extra extends default' => array(
            'config' => array(
                'icon',
                'prefix',
                'shape',
                'markerColor',
                'number',
                'iconColor',
            ),
            'active' => array(
                'active'
            )
        ),
    ),

    'fields' => array
    (
        'id'           => array
        (
            'sql' => 'int(10) unsigned NOT NULL auto_increment'
        ),
        'tstamp'       => array
        (
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ),
        'title'        => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_leaflet_icon']['title'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => array('mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w50'),
            'sql'       => "varchar(255) NOT NULL default ''"
        ),
        'alias'        => array
        (
            'label'         => &$GLOBALS['TL_LANG']['tl_leaflet_icon']['alias'],
            'exclude'       => true,
            'inputType'     => 'text',
            'save_callback' => array(
                ['netzmacht.contao_toolkit.dca.listeners.alias_generator', 'handleSaveCallback'],
                ['netzmacht.contao_leaflet_maps.listeners.dca.validator', 'validateAlias'],
            ),
            'eval'          => array('mandatory' => false, 'maxlength' => 255, 'tl_class' => 'w50', 'unique' => true),
            'toolkit' => [
                'alias_generator' => [
                    'factory' => 'netzmacht.contao_leaflet_maps.definition.alias_generator.factory_default',
                    'fields'  => ['title']
                ],
            ],
            'sql'           => 'varchar(255) NULL'
        ),
        'type'                  => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_leaflet_icon']['type'],
            'exclude'   => true,
            'inputType' => 'select',
            'eval'      => array(
                'mandatory'          => true,
                'tl_class'           => 'w50',
                'includeBlankOption' => true,
                'submitOnChange'     => true,
                'chosen'             => true,
            ),
            'options'   => &$GLOBALS['LEAFLET_ICONS'],
            'reference' => &$GLOBALS['TL_LANG']['leaflet_icon'],
            'sql'       => "varchar(32) NOT NULL default ''"
        ),
        'active'                => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_leaflet_icon']['active'],
            'exclude'   => true,
            'inputType' => 'checkbox',
            'eval'      => array('tl_class' => 'w50'),
            'sql'       => "char(1) NOT NULL default ''",
            'save_callback' => [
                ['netzmacht.contao_leaflet_maps.listeners.dca.leaflet', 'clearCache'],
            ],
        ),
        'iconImage'         => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_leaflet_icon']['iconImage'],
            'exclude'   => true,
            'inputType' => 'fileTree',
            'eval'      => array(
                'filesOnly'  => true,
                 'fieldType'  => 'radio',
                 'mandatory'  => true,
                 'tl_class'   => 'clr',
                 'extensions' => 'gif,png,svg,jpg'
            ),
            'sql'       => 'binary(16) NULL',
        ),
        'iconRetinaImage'   => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_leaflet_icon']['iconRetinaImage'],
            'exclude'   => true,
            'inputType' => 'fileTree',
            'eval'      => array(
                 'filesOnly'  => true,
                 'fieldType'  => 'radio',
                 'mandatory'  => false,
                 'tl_class'   => 'clr',
                 'extensions' => 'gif,png,svg,jpg'
            ),
            'sql'       => 'binary(16) NULL',
        ),
        'shadowImage'         => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_leaflet_icon']['shadowImage'],
            'exclude'   => true,
            'inputType' => 'fileTree',
            'eval'      => array(
                'filesOnly'  => true,
                'fieldType'  => 'radio',
                'mandatory'  => false,
                'tl_class'   => 'clr',
                'extensions' => 'gif,png,svg,jpg'
            ),
            'sql'       => 'binary(16) NULL',
        ),
        'shadowRetinaImage'   => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_leaflet_icon']['shadowRetinaImage'],
            'exclude'   => true,
            'inputType' => 'fileTree',
            'eval'      => array(
                'filesOnly'  => true,
                'fieldType'  => 'radio',
                'mandatory'  => false,
                'tl_class'   => 'clr',
                'extensions' => 'gif,png,svg,jpg'
            ),
            'sql'       => 'binary(16) NULL',
        ),
        'iconAnchor'  => array
        (
            'label'         => &$GLOBALS['TL_LANG']['tl_leaflet_icon']['iconAnchor'],
            'exclude'       => true,
            'inputType'     => 'text',
            'save_callback' => array(
                ['netzmacht.contao_leaflet_maps.listeners.dca.validator', 'validateCoordinates']
            ),
            'eval'          => array(
                'maxlength'   => 255,
                'tl_class'    => 'w50',
                'nullIfEmpty' => true,
            ),
            'sql'           => 'varchar(255) NULL'
        ),
        'shadowAnchor'  => array
        (
            'label'         => &$GLOBALS['TL_LANG']['tl_leaflet_icon']['shadowAnchor'],
            'exclude'       => true,
            'inputType'     => 'text',
            'save_callback' => array(
                ['netzmacht.contao_leaflet_maps.listeners.dca.validator', 'validateCoordinates']
            ),
            'eval'          => array(
                'maxlength'   => 255,
                'tl_class'    => 'w50',
                'nullIfEmpty' => true,
            ),
            'sql'           => 'varchar(255) NULL'
        ),
        'popupAnchor'  => array
        (
            'label'         => &$GLOBALS['TL_LANG']['tl_leaflet_icon']['popupAnchor'],
            'exclude'       => true,
            'inputType'     => 'text',
            'save_callback' => array(
                ['netzmacht.contao_leaflet_maps.listeners.dca.validator', 'validateCoordinates']
            ),
            'eval'          => array(
                'maxlength'   => 255,
                'tl_class'    => 'w50',
                'nullIfEmpty' => true,
            ),
            'sql'           => 'varchar(255) NULL'
        ),
        'className'          => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_leaflet_icon']['className'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => array('mandatory' => false, 'maxlength' => 64, 'tl_class' => 'w50'),
            'sql'       => "varchar(64) NOT NULL default ''"
        ),
        'iconSize'  => array
        (
            'label'         => &$GLOBALS['TL_LANG']['tl_leaflet_icon']['iconSize'],
            'exclude'       => true,
            'inputType'     => 'text',
            'eval'          => array(
                'maxlength'   => 64,
                'tl_class'    => 'w50',
                'nullIfEmpty' => true,
            ),
            'sql'           => 'varchar(64) NULL'
        ),
        'html'  => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_leaflet_control']['html'],
            'exclude'   => true,
            'inputType' => 'textarea',
            'eval'      => array(
                'style'          => 'height:60px',
                'preserveTags'   => true,
                'decodeEntities' => true,
                'allowHtml'      => true,
                'rte'            => 'ace|html',
                'tl_class'       => 'clr'
            ),
            'sql'       => 'mediumtext NULL'
        ),
        'icon'  => array
        (
            'label'         => &$GLOBALS['TL_LANG']['tl_leaflet_icon']['icon'],
            'exclude'       => true,
            'inputType'     => 'text',
            'eval'          => array(
                'maxlength'   => 64,
                'tl_class'    => 'w50',
                'nullIfEmpty' => true,
            ),
            'sql'           => 'varchar(64) NULL'
        ),
        'prefix'  => array
        (
            'label'         => &$GLOBALS['TL_LANG']['tl_leaflet_icon']['prefix'],
            'exclude'       => true,
            'inputType'     => 'text',
            'eval'          => array(
                'maxlength'   => 64,
                'tl_class'    => 'w50',
                'nullIfEmpty' => true,
            ),
            'sql'           => 'varchar(64) NULL'
        ),
        'shape'  => array
        (
            'label'         => &$GLOBALS['TL_LANG']['tl_leaflet_icon']['shape'],
            'exclude'       => true,
            'inputType'     => 'select',
            'default'       => 'circle',
            'options'       => ['circle', 'square', 'star', 'penta'],
            'eval'          => array(
                'tl_class'    => 'w50',
            ),
            'sql'           => 'varchar(64) NULL'
        ),
        'iconColor'  => array
        (
            'label'         => &$GLOBALS['TL_LANG']['tl_leaflet_icon']['iconColor'],
            'exclude'       => true,
            'inputType'     => 'text',
            'wizard'    => array(
                ['netzmacht.contao_toolkit.dca.listeners.color_picker', 'handleWizardCallback'],
            ),
            'eval'          => array(
                'maxlength'   => 64,
                'tl_class'    => 'w50 wizard',
                'nullIfEmpty' => true,
            ),
            'sql'           => 'varchar(16) NULL'
        ),
        'markerColor'  => array
        (
            'label'         => &$GLOBALS['TL_LANG']['tl_leaflet_icon']['markerColor'],
            'exclude'       => true,
            'inputType'     => 'select',
            'default'       => 'circle',
            'options'       => [
                'blue',
                'red',
                'orange-dark',
                'orange',
                'yellow',
                'blue-dark',
                'cyan',
                'purple',
                'violet',
                'pink',
                'green-dark',
                'green',
                'green-light',
                'black',
                'white'
            ],
            'eval'          => array(
                'tl_class'    => 'w50',
                'nullIfEmpty' => true,
            ),
            'sql'           => 'varchar(16) NULL'
        ),
    ),
);
