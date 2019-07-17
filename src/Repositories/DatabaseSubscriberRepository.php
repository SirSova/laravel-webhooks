<?php


namespace SirSova\Webhooks\Repositories;

use Illuminate\Contracts\Validation\Factory as ValidatorFactory;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Query\Builder;
use Illuminate\Validation\ValidationException;
use SirSova\Webhooks\Contracts\SubscriberRepository;
use SirSova\Webhooks\Exceptions\UnableToSaveException;
use SirSova\Webhooks\Subscribers\DatabaseSubscriber;
use SirSova\Webhooks\Subscribers\SubscribersCollection;

class DatabaseSubscriberRepository implements SubscriberRepository
{
    
    /**
     * @var ConnectionInterface
     */
    private $connection;
    /**
     * @var string
     */
    private $table;
    /**
     * @var ValidatorFactory
     */
    private $validatorFactory;

    public function __construct(ConnectionInterface $connection, ValidatorFactory $validatorFactory, string $table)
    {
        $this->connection = $connection;
        $this->table = $table;
        $this->validatorFactory = $validatorFactory;
    }

    /**
     * @param $id
     *
     * @return DatabaseSubscriber|null
     */
    public function find($id): ?DatabaseSubscriber
    {
        $object = $this->query()->where('id', '=', $id)->first();
        
        return $object === null ? null : $this->createSubscriber($object);
    }

    /**
     * @param string $event
     * @param bool   $enabledOnly
     *
     * @return SubscribersCollection
     */
    public function findAllByEvent(string $event, bool $enabledOnly = false): SubscribersCollection
    {
        $query = $this->query()->where('event', '=', $event);

        if ($enabledOnly) {
            $query->where('enabled', '=', true);
        }

        $result = $query->get()->map(function ($object) {
            return $this->createSubscriber($object);
        });

        return new SubscribersCollection($result);
    }

    /**
     * @param $data
     *
     * @return DatabaseSubscriber
     * @throws ValidationException
     */
    public function create($data): DatabaseSubscriber
    {
        $validator = $this->makeCreationValidator($data);
        
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
        
        $id = $this->query()->insertGetId($data);
        $subscriber = $this->find($id);
        
        if ($subscriber !== null) {
            return $subscriber;
        }
        
        throw new UnableToSaveException('Something going wrong, subscriber was not saved to Database');
    }

    /**
     * @param $id
     *
     * @return bool
     */
    public function remove($id): bool
    {
        return (bool)$this->query()->delete($id);
    }

    /**
     * @param object|array $object
     *
     * @return DatabaseSubscriber
     */
    private function createSubscriber($object): DatabaseSubscriber
    {
        return DatabaseSubscriber::create($object->id, $object->event, $object->url, $object->enabled, $object->created_at, $object->updated_at);
    }

    /**
     * @return Builder
     */
    private function query(): Builder
    {
        return $this->connection->table($this->table);
    }

    /**
     * @param array $data
     *
     * @return Validator
     */
    private function makeCreationValidator(array $data): Validator
    {
        return $this->validatorFactory->make($data, [
            'event'   => 'required|string',
            'url'     => 'required|url',
            'enabled' => 'boolean'
        ]);
    }
}
