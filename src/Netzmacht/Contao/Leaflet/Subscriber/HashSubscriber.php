<?php

/**
 * @package    dev
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2015 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Contao\Leaflet\Subscriber;


use Netzmacht\Contao\Leaflet\Event\GetHashEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class HashSubscriber implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            GetHashEvent::NAME => array(
                array('getModelHash'),
                array('getFallback', -100)
            )
        );
    }

    /**
     * Get hash for a model object.
     *
     * @param GetHashEvent $event
     */
    public function getModelHash(GetHashEvent $event)
    {
        $data = $event->getData();

        if ($data instanceof \Model) {
            $event->setHash($data->getTable() . '.' . $data->{$data->getPk()});
        }
    }

    /**
     * Get hash fallback if no hash was created so far.
     *
     * @param GetHashEvent $event
     */
    public function getFallback(GetHashEvent $event)
    {
        if ($event->getHash()) {
            return;
        }

        $data = $event->getData();

        if (is_object($data)) {
            $event->setHash(spl_object_hash($data));
        } else {
            $event->setHash(md5(json_encode($data)));
        }
    }
}
