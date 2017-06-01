<?php

namespace FCExportHandler;

use PhpAmqpLib\Connection\AMQPStreamConnection;

class LogListener
{
    protected $defaultConfig = [
        'host' => 'localhost',
        'port' => 5672,
        'username' => 'guest',
        'password' => 'guest',

        'exchange_name' => 'export_logger_exchange',
        'exchange_type' => 'topic',
        'queue_name' => 'export_logger_queue',
        'binding_key' => 'log.info',
    ];

    protected $config;

    protected $connection;

    protected $channel;

    public function __construct($userConfig = [])
    {
        $this->config = $this->makeConfig($this->defaultConfig, $userConfig);

        $this->connection = new AMQPStreamConnection($this->config['host'], $this->config['port'], $this->config['username'], $this->config['password']);

        $this->channel = $this->connection->channel();

        $this->channel->exchange_declare($this->config['exchange_name'], $this->config['exchange_type'], false, false, false);
    }

    public function consume($callback)
    {
        $this->channel->queue_declare($this->config['queue_name'], false, false, true, false);

        $this->channel->queue_bind($this->config['queue_name'], $this->config['exchange_name'], $this->config['binding_key']);

        $this->channel->basic_consume($this->config['queue_name'], '', false, true, false, false, function ($msg) use ($callback) {
            $callback(json_decode($msg->body));
        });

        return $this;
    }

    public function listen()
    {
        while (count($this->channel->callbacks)) {
            $this->channel->wait();
        }

        $this->close();

        return $this;
    }

    private function close()
    {
        $this->channel->close();
        $this->connection->close();
    }

    private function makeConfig($defaultConfig, $userConfig)
    {
        return array_merge($defaultConfig, $userConfig);
    }
}
