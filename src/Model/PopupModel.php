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

namespace Netzmacht\Contao\Leaflet\Model;

/**
 * Class PopupModel.
 *
 * @property mixed|null offset
 *
 * @package Netzmacht\Contao\Leaflet\Model
 */
class PopupModel extends AbstractActiveModel
{
    /**
     * The table name.
     *
     * @var string
     */
    protected static $strTable = 'tl_leaflet_popup';
}
