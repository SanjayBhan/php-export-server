<?php

namespace FCExportServer\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class LoggerService
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

    protected $data;

    protected $config;

    protected $connection;

    protected $channel;

    public function __construct($userConfig = [])
    {
        $this->makeConfig($userConfig);

        $this->connection = new AMQPStreamConnection($this->config['host'], $this->config['port'], $this->config['username'], $this->config['password']);

        $this->channel = $this->connection->channel();

        $this->channel->exchange_declare($this->config['exchange_name'], $this->config['exchange_type'], false, false, false);
    }

    public function send()
    {
        $msg = new AMQPMessage(json_encode($this->data));

        $this->channel->basic_publish($msg, $this->config['exchange_name'], $this->config['binding_key']);

        $this->close();

        return $this;
    }

    public function setData($key, $value)
    {
        $this->data[$key] = $value;
    }

    private function close()
    {
        $this->channel->close();
        $this->connection->close();
    }

    private function makeConfig($userConfig)
    {
        $this->config = array_merge($this->defaultConfig, $userConfig);
    }
}
