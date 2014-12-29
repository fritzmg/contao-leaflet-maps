<?php

use Netzmacht\Contao\Leaflet\Boot;
use Netzmacht\Contao\Leaflet\Mapper\DefinitionMapper;
use Netzmacht\Contao\Leaflet\MapService;
use Netzmacht\Javascript\Builder;
use Netzmacht\Javascript\Encoder;
use Netzmacht\LeafletPHP\Leaflet;
use Symfony\Component\EventDispatcher\EventDispatcher;

/** @var \Pimple $container */
global $container;


/*
 * Leaflet map service is a simply api entry to to get the leaflet map from the database.
 */
$container['leaflet.map.service'] = $container->share(function ($container) {
    return new MapService(
        $container['leaflet.definition.mapper'],
        $container['leaflet.definition.builder'],
        $container['event-dispatcher']
    );
});


/*
 * The leaflet boot.
 */
$container['leaflet.boot'] = $container->share(function ($container) {
    return new Boot($container['event-dispatcher']);
});


/*
 * The definition mapper.
 */
$container['leaflet.definition.mapper'] = $container->share(function ($container) {
    /** @var Boot $boot */
    $boot   = $container['leaflet.boot'];
    $mapper = new DefinitionMapper($container['event-dispatcher']);

    return $boot->initializeDefinitionMapper($mapper);
});


/*
 * The local event dispatcher is used for the leaflet javascript encoding system.
 */
$container['leaflet.definition.builder.event-dispatcher'] = $container->share(function ($container) {
    /** @var Boot $boot */
    $boot       = $container['leaflet.boot'];
    $dispatcher = new EventDispatcher();

    return $boot->initializeEventDispatcher($dispatcher);
});


/*
 * The leaflet builder transforms the definition to javascript.
 */
$container['leaflet.definition.builder'] = $container->share(function($container) {
    /** @var Boot $boot */
    $boot       = $container['leaflet.boot'];
    $dispatcher = $container['leaflet.definition.builder.event-dispatcher'];

    $encoder = new Encoder($dispatcher);
    $builder = new Builder($encoder, $dispatcher);
    $leaflet = new Leaflet($builder);

    return $boot->initializeLeafletBuilder($leaflet);
});