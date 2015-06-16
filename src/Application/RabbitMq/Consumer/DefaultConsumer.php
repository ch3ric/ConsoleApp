<?php

namespace Application\RabbitMq\Consumer;

use Psr\Log\LoggerInterface;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use OldSound\RabbitMqBundle\RabbitMq\Producer;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * Consume the rabbitMQ messages and dispatch the event to the different message listeners
 */
class DefaultConsumer implements ConsumerInterface
{
    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \OldSound\RabbitMqBundle\RabbitMq\Producer
     */
    protected $errorProducer;

    /**
     * Constructor
     *
     * @param EventDispatcherInterface $dispatcher
     * @param LoggerInterface          $logger
     * @param Producer                 $errorProducer
     */
    public function __construct(EventDispatcherInterface $dispatcher, LoggerInterface $logger, Producer $errorProducer)
    {
        $this->dispatcher    = $dispatcher;
        $this->logger        = $logger;
        $this->errorProducer = $errorProducer;
    }

    /**
     * Analyze and route a message to the right listener
     *
     * @param AMQPMessage $msg
     */
    public function execute(AMQPMessage $msg)
    {
        try {
            $body = json_decode($msg->body, true);

            if (!isset($body['type'])) {
                throw new \InvalidArgumentException(
                    "The message should have a 'type' property, so that we could know which listener to route this message to."
                );
            }

            $this->dispatcher->dispatch('rabbitmq.message.' . $body['type'], new GenericEvent($body));
        } catch (\Exception $e) {

            $this->logger->critical(sprintf(
                'Exception "%s" while consuming the following message: %s.',
                $e->getMessage(),
                $msg->body
            ), [
                'error' => get_class($e) . ': ' . $e->getMessage(),
                'originalMessage' => $msg->body,
            ]);

            $this->errorProducer->publish(json_encode([
                'datetime'        => (new \DateTime())->format('Y-m-d H:i:s'),
                'error'           => $e->getMessage(),
                'originalMessage' => $msg->body,
            ]));
        }
    }
}
