<?php

namespace FCExportHandler\Services;

class HeaderService
{
    protected $defaultConfig = [
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Allow-Headers' => '*',
        'Access-Control-Allow-Methods' => ['GET', 'POST'],
    ];

    protected $config;

    public function __construct($userConfig = [])
    {
        $this->makeConfig($userConfig);
    }

    public function getConfig()
    {
        return $this->config;
    }

    private function makeConfig($userConfig)
    {
        $this->config = array_merge($this->defaultConfig, $userConfig);
    }
}
