Console Application with Symfony Components
===========================================

When you develop a PHP application to run system scripts or cron jobs or AMQP consumers or to crawl APIs, you definitely don't need a HTTP fullstack framework like Symfony. But you could use its components: Console, Dependency Injection, Config for example can be very usefull.

Here is an example to bootstrap a simple console application with these components:

- Symfony Console: useful to add cli commands that can handle arguments and options
- Symfony Dependency Injection and Config: to build a container and its services, loaded from config files, compiled and cached
- OldSound RabbitMq Bundle: to add RabbitMq producers and consumers
- Symfony Event Dispatcher: to dispatch and listen events, useful to write message consumers. It helps to make the code more extensible too.
- Monolog: to handle logs
