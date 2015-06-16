<?php

namespace Ftven\Application\RabbitMq\Listener;

use Symfony\Component\EventDispatcher\GenericEvent;
use Guzzle\Service\ClientInterface;

/**
 * Listener that handles messages from RabbitMq
 */
class DoSomethingListener
{
    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Do somehting: send data from the message to an API for example
     *
     * @param GenericEvent $event
     */
    public function onMessage(GenericEvent $event)
    {
        $data = $event->getSubject();

        return $this->client
            ->post('/action', null, json_encode($data))
            ->send();
    }
}
