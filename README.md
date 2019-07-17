# Laravel webhooks

This package implements the standard logic for sending webhooks using laravel queues, and also allows you to create event subscribers for deferred sending to each of them 

[![Latest Version on Packagist](https://img.shields.io/packagist/v/sirsova/laravel-webhooks.svg?style=flat-square)](https://packagist.org/packages/sirsova/laravel-webhooks)
[![Build Status](https://travis-ci.com/SirSova/laravel-webhooks.svg?branch=master)](https://travis-ci.com/SirSova/laravel-webhooks)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![StyleCI](https://styleci.io/repos/196221813/shield)](https://styleci.io/repos/196221813)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/SirSova/laravel-webhooks/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/SirSova/laravel-webhooks/?branch=master)

# Contents
- [Installation](#installation)
    - [Setting up the Webhook service](#setting-up-the-webhook-service)
- [Usage](#usage)
- [Changelog](#changelog)
- [Testing](#testing)
- [License](#license)

# Installation
Install package via composer : 
```bash
$ composer require sirsova/laravel-webhooks
```
### Setting up the Webhook service
After installation you need to register service provider in `config/app.php`:
```php
'providers' => [
    ...
    SirSova\Webhooks\ServiceProvider::class,
]
```
Also for using package configs you need to execute script, which publish `config/webhooks.php` to root config directory:
```bash
$ php artisan vendor:publish --tag=webhooks_config
```
For coping package migrations to root path of migration : 
```bash
$ php artisan vendor:publish --tag=webhooks-migrations
```

# Usage
### Available jobs
- `ProcessMessage`: Dispatch a message to the message processor, which queues the webhooks to all subscribers by event name
- `ProcessWebhook`: Dispatch a webhook to the webhook channel, which send message on the given url (subscriber url)

### Code example
```php
use Illuminate\Contracts\Bus\QueueingDispatcher;
use SirSova\Webhooks\Jobs\ProcessMessage;
use SirSova\Webhooks\Message;
...

$busDispatcher = app(QueueingDispatcher::class);
$message = new Message('event-type', ['foo' => 'bar']);
$job = new ProcessMessage($message);

//queued message example
$busDispatcher->dispatchToQueue($job->onQueue('webhooks'));

//dispatching in time message
$busDispatcher->dispatch($job);

//queued webhook example
$webhook = new Webhook($message, 'https://example.com');
$job = new ProcessWebhook($webhook);
$busDispatcher->dispatchToQueue($job->onQueue('webhooks'));
```
Each message would be processed by event specific subscribers.  
`MessageProcessor` will create a new Webhook for each subscriber and dispatch them to queue(see [Config](config/webhooks.php)).  
You can easily manipulate with self written solution by overriding binding for `WebhookChannel` and `MessageProcessor` in DI container.  

# Testing
```bash
$ composer test
```
# Changelog
Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

# License
The MIT License (MIT). Please see [License File](LICENSE) for more information.