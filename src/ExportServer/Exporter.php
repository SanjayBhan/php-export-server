<?php

namespace FCExportServer;

use FCExportServer\Services\LoggerService;
use FCExportServer\Services\HeaderService;

class Exporter
{
    protected $defaultConfig = [
        'server' => [],
        'logger' => [],
        'headers' => [],
    ];

    protected $config;

    protected $loggerService;

    protected $headerService;

    public function __construct($userConfig = [])
    {
        $this->makeConfig($userConfig);

        $this->setServerConfig($this->config['server']);

        $this->loggerService = new LoggerService($this->config['logger']);

        $this->headerService = new HeaderService($this->config['headers']);
    }

    public function serve()
    {
        global $notices;
        global $exportData;

        global $loggerService;
        global $logData;

        global $headerService;

        $loggerService = $this->loggerService;

        $headerService = $this->headerService;

        include(dirname(__FILE__) . '/index.php');
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
