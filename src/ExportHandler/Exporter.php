<?php

namespace FCExportHandler;

use FCExportHandler\Services\EmbedderService;
use FCExportHandler\Services\LoggerService;
use FCExportHandler\Services\HeaderService;
use FCExportHandler\Services\GeoLocatorService;

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

    protected $geoLocatorService;

    public function __construct($userConfig = [])
    {
        $this->makeConfig($userConfig);

        $this->setServerConfig($this->config['server']);

        $this->embedderService = new EmbedderService();

        $this->loggerService = new LoggerService($this->config['logger']);

        $this->headerService = new HeaderService($this->config['headers']);

        $this->geoLocatorService = new GeoLocatorService();
    }

    public function serve()
    {
        global $notices;
        global $exportData;

        global $embedderService;

        global $loggerService;

        global $headerService;

        global $geoLocatorService;

        $embedderService = $this->embedderService;

        $loggerService = $this->loggerService;

        $headerService = $this->headerService;

        $geoLocatorService = $this->geoLocatorService;

        include(dirname(__FILE__) . '/entry.php');
    }

    private function makeConfig($userConfig)
    {
        $this->config = array_merge($this->defaultConfig, $userConfig);
    }

    private function setServerConfig($userConfig)
    {
        $defaultConfig = [
            'inkscape_path' => '/usr/local/bin/inkscape',
            'convert_path' => '/usr/local/bin/convert',
            'save_path' => dirname(__FILE__) . '/ExportedImages/',
            'allow_save' => true
        ];

        $config = array_merge($defaultConfig, $userConfig);

        define('INKSCAPE_PATH', $config['inkscape_path']);
        define('CONVERT_PATH', $config['convert_path']);
        define('SAVE_PATH', $config['save_path']);
        define('ALLOW_SAVE', $config['allow_save']);
    }
}
