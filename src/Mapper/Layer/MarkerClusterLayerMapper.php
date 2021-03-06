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

namespace Netzmacht\Contao\Leaflet\Mapper\Layer;

use Contao\Model;
use Netzmacht\Contao\Leaflet\Encoder\ContaoAssets;
use Netzmacht\Contao\Leaflet\Mapper\DefinitionMapper;
use Netzmacht\Contao\Leaflet\Mapper\Request;
use Netzmacht\Contao\Leaflet\Model\LayerModel;
use Netzmacht\Contao\Toolkit\Data\Model\RepositoryManager;
use Netzmacht\JavascriptBuilder\Type\AnonymousFunction;
use Netzmacht\JavascriptBuilder\Type\Expression;
use Netzmacht\LeafletPHP\Definition;
use Netzmacht\LeafletPHP\Definition\Layer;
use Netzmacht\LeafletPHP\Plugins\MarkerCluster\MarkerClusterGroup;
use Netzmacht\LeafletPHP\Plugins\Omnivore\OmnivoreLayer;

/**
 * Class MarkerClusterLayerMapper maps the layer database model to the marker cluster definition.
 *
 * @package Netzmacht\Contao\Leaflet\Mapper\Layer
 */
class MarkerClusterLayerMapper extends AbstractLayerMapper
{
    /**
     * Class of the definition being created.
     *
     * @var string
     */
    protected static $definitionClass = MarkerClusterGroup::class;

    /**
     * Layer type.
     *
     * @var string
     */
    protected static $type = 'markercluster';

    /**
     * Assets manager.
     *
     * @var ContaoAssets
     */
    private $assets;

    /**
     * Repository manager.
     *
     * @var RepositoryManager
     */
    private $repositoryManager;

    /**
     * Construct.
     *
     * @param ContaoAssets      $assets            Assets manager.
     * @param RepositoryManager $repositoryManager Repository manager.
     */
    public function __construct(ContaoAssets $assets, RepositoryManager $repositoryManager)
    {
        $this->repositoryManager = $repositoryManager;
        $this->assets            = $assets;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function initialize()
    {
        parent::initialize();

        $this->optionsBuilder
            ->addOptions('showCoverageOnHover', 'zoomToBoundsOnClick', 'spiderfyOnMaxZoom')
            ->addOption('removeOutsideVisibleBounds')
            ->addConditionalOption('maxClusterRadius')
            ->addConditionalOption('singleMarkerMode')
            ->addConditionalOption('animateAddingMarkers')
            ->addConditionalOption('disableClusteringAtZoom')
            ->addConditionalOption('spiderfyDistanceMultiplier');
    }

    /**
     * {@inheritdoc}
     */
    protected function build(
        Definition $definition,
        Model $model,
        DefinitionMapper $mapper,
        Request $request = null,
        Definition $parent = null
    ) {
        parent::build($definition, $model, $mapper, $request, $parent);

        /** @var MarkerClusterGroup $definition */

        if ($model->iconCreateFunction) {
            $definition->setIconCreateFunction(new Expression($model->iconCreateFunction));
        }

        if ($model->polygonOptions) {
            $definition->setPolygonOptions((array) json_decode($model->polygonOptions, true));
        }

        if (!$model->disableDefaultStyle) {
            $this->assets->addStylesheet('assets/leaflet/libs/leaflet-markercluster/MarkerCluster.Default.css');
        }

        $repository = $this->repositoryManager->getRepository(LayerModel::class);
        $collection = $repository->findBy(
            ['pid=?', 'active=1'],
            [$model->id],
            ['order' => 'sorting']
        );

        if ($collection) {
            foreach ($collection as $layerModel) {
                $layer = $mapper->handle($layerModel);

                if ($layer instanceof Layer) {
                    $definition->addLayer($layer);

                    if ($layer instanceof OmnivoreLayer) {
                        $callback = new AnonymousFunction();
                        $callback->addLine('layers.' . $definition->getId() . '.addLayers(this.getLayers())');

                        $layer->on('ready', $callback);
                    }
                }
            }
        }
    }
}
