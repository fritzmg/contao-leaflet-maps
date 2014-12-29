<?php

/**
 * @package    dev
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Contao\Leaflet\Event;

use Netzmacht\LeafletPHP\Definition;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class BuildDefinitionEvent is emitted when the mapper maps between the model and the definition.
 *
 * @package Netzmacht\Contao\Leaflet\Event
 */
class BuildDefinitionEvent extends Event
{
    const NAME = 'leaflet.mapper.definition';

    /**
     * The leaflet object definition.
     *
     * @var Definition
     */
    private $definition;

    /**
     * The model.
     *
     * @var \Model
     */
    private $model;

    /**
     * Construct.
     *
     * @param Definition $definition The leaflet definition.
     * @param \Model     $model      The definition model.
     */
    public function __construct(Definition $definition, \Model $model)
    {
        $this->definition = $definition;
        $this->model = $model;
    }

    /**
     * Get the definition.
     *
     * @return Definition
     */
    public function getDefinition()
    {
        return $this->definition;
    }

    /**
     * Get the model.
     *
     * @return \Model
     */
    public function getModel()
    {
        return $this->model;
    }
}