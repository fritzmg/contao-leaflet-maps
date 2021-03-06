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

use Contao\Model;
use Contao\Model\Collection;

/**
 * Class AbstractActiveModel is the base model for models with an active field.
 *
 * @package Netzmacht\Contao\Leaflet\Model
 */
abstract class AbstractActiveModel extends Model
{
    /**
     * Find an active model by its model id.
     *
     * @param int   $modelId The model id.
     * @param array $options The query options.
     *
     * @return \Model|null
     */
    public static function findActiveByPK($modelId, $options = [])
    {
        return static::findOneBy('active=1 AND id', $modelId, $options);
    }

    /**
     * Find active models by a defined column.
     *
     * @param string|array $column  The query columns.
     * @param mixed        $value   The column value.
     * @param array        $options The options.
     *
     * @return Collection|null
     */
    public static function findActiveBy($column, $value, $options = [])
    {
        if (is_array($column)) {
            $column[] = 'active=1';
        } else {
            $column = 'active=1 AND ' . $column;
        }

        return static::findBy($column, $value, $options);
    }

    /**
     * Find collection activated models.
     *
     * @param array $options The query options.
     *
     * @return Collection|null
     */
    public static function findActives($options = [])
    {
        return static::findBy('active', '1', $options);
    }
}
