<?php


namespace SirSova\Webhooks;


use GuzzleHttp\Client;
use Illuminate\Contracts\Bus\Dispatcher as BusDispatcher;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Database\Connection;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use SirSova\Webhooks\Contracts\Channel;
use SirSova\Webhooks\Contracts\MessageProcessor as MessageProcessorContract;
use SirSova\Webhooks\Contracts\SubscriberRepository;
use SirSova\Webhooks\Repositories\DatabaseSubscriberRepository;

class ServiceProvider extends BaseServiceProvider
{
    
    public function register(): void 
    {
        $this->app->bind(SubscriberRepository::class, function (Container $container) {
            return new DatabaseSubscriberRepository(
                $container->get(Connection::class),
                $container->get(ValidationFactory::class),
                config('webhooks.subscribers_table', 'webhook_subscribers')
            );
        });
        
        $this->app->bind(Channel::class, function () {
            return new WebhookChannel(new Client());
        });
        
        $this->app->bind(MessageProcessorContract::class, function (Container $container) {
            return new MessageProcessor(
                $container->get(SubscriberRepository::class),
                $container->get(BusDispatcher::class),
                config('webhooks.webhook-queue')
            );
        });
    }
    
    public function boot(): void 
    {
        if ($this->app->runningInConsole()) {
            //config
            $this->publishes([
                __DIR__ . '/../config/webhooks.php' => config_path('webhooks.php'),
            ], 'webhooks-config');
            
            //migrations
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
            
            $this->publishes([
                __DIR__ . '/../database/migrations' => database_path('migrations'),
            ], 'webhooks-migrations');
            
            //console commands
            $this->commands([
                Console\CreateSubscriber::class
            ]);
        }
    }
}