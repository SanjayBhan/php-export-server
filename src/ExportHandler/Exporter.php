<?php

namespace FCExportHandler;

use FCExportHandler\Services\EmbedderService;
use FCExportHandler\Services\LoggerService;
use FCExportHandler\Services\HeaderService;

class Exporter
{
    protected $defaultConfig = [
        'server' => [],
        'logger' => [],
        'headers' => [],
    ];

    protected $config;

    protected $embedderService;

    protected $loggerService;

    protected $headerService;

    public function __construct($userConfig = [])
    {
        $this->makeConfig($userConfig);

        $this->setServerConfig($this->config['server']);

        $this->embedderService = new EmbedderService();

        $this->loggerService = new LoggerService($this->config['logger']);

        $this->headerService = new HeaderService($this->config['headers']);
    }

    public function serve()
    {
        global $notices;
        global $exportData;

        global $embedderService;

        global $loggerService;

        global $headerService;

        $embedderService = $this->embedderService;

        $loggerService = $this->loggerService;

        $headerService = $this->headerService;

        include(dirname(__FILE__) . '/entry.php');
    }

    private function makeConfig($userConfig)
    {
        $this->config = array_merge($this->defaultConfig, $userConfig);
    }

    private function setServerConfig($userConfig)
    {
        $defaultConfig = [
            'save_path' => dirname(__FILE__) . '/ExportedImages/',
            'allow_save' => true
        ];

        $config = array_merge($defaultConfig, $userConfig);

        define('SAVE_PATH', $config['save_path']);
        define('ALLOW_SAVE', $config['allow_save']);
    }
}
