<?php


namespace SirSova\Webhooks\Console;


use Illuminate\Console\Command;
use Illuminate\Validation\ValidationException;
use SirSova\Webhooks\Contracts\SubscriberRepository;

class CreateSubscriber extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'webhooks:subscriber
                            {event : Subscriber name}
                            {url : Webhook target url}
                            {--disabled : Set status `enabled` false}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new webhook subscriber';
    
    /**
     * @var SubscriberRepository
     */
    protected $subscriberRepository;

    public function __construct(SubscriberRepository $subscriberRepository)
    {
        parent::__construct();
        
        $this->subscriberRepository = $subscriberRepository;
    }

    /**
     * @return int
     */
    public function handle(): int 
    {
        $data = [
            'event'   => $this->argument('event'),
            'url'     => $this->argument('url'),
            'enabled' => !(bool)$this->option('disabled')
        ];
        
        try {
            $this->subscriberRepository->create($data);
        } catch (ValidationException $e) {
            foreach ($e->errors() as $message) {
                $this->output->error($message);
            }
            
            return 1;
        }
        
        return 0;
    }
}