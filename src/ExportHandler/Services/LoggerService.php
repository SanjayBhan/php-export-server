<?php

namespace FCExportHandler\Services;

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

    protected $isEnabled;

    public function __construct($userConfig = [])
    {
        $this->config = $this->makeConfig($this->defaultConfig, $userConfig);

        try {

            $this->connection = new AMQPStreamConnection($this->config['host'], $this->config['port'], $this->config['username'], $this->config['password']);

            $this->channel = $this->connection->channel();

            $this->channel->exchange_declare($this->config['exchange_name'], $this->config['exchange_type'], false, false, false);

        } catch (\Exception $e) {

            $this->connection = NULL;

        }
    }

    public function send()
    {
        if ($this->connection && $this->isEnabled) {

            $msg = new AMQPMessage(json_encode($this->data));

            $this->channel->basic_publish($msg, $this->config['exchange_name'], $this->config['binding_key']);

            header('LOG_INITIATED: true');

            $this->close();

        }

        return $this;
    }

    public function setData($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function setEnabled($value)
    {
        $this->isEnabled = $value;
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
