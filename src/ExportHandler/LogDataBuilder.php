<?php

namespace FCExportHandler;

use FCExportHandler\Services\GeoLocatorService;

class LogDataBuilder
{
    protected $geoLocatorService;

    public function __construct()
    {
        $this->geoLocatorService = new GeoLocatorService();
    }

    public function build($params, $headers)
    {
        date_default_timezone_set('UTC');

        $chartOriginUrl = $this->getOriginUrl($headers);
        $userIPAddress = $this->getRemoteAddr($headers);
        $userCountry = $this->geoLocatorService->locateIP($userIPAddress, 'country');

        $data = [
            'chartType' => @$params['chartType'],
            'chartCaption' => @$params['chartCaption'],
            'chartSubCaption' => @$params['chartSubCaption'],
            'isSingleExport' => @$params['isSingleExport'],
            'exportFileName' => @$params['exportFileName'],
            'exportFormat' => @$params['exportFormat'],
            'chartOriginUrl' => $chartOriginUrl,
            'userAgent' => @$headers['user-agent'][0],
            'isFullVersion' => @$params['isFullVersion'],
            'userTimeZone' => @$params['userTimeZone'],
            'userIPAddress' => $userIPAddress,
            'userCountry' => $userCountry,
            'chartIdentifier' => '',
            'serverDateTime' => date('Y-m-d H:i:s'),
            'exportAction' => @$params['exportAction'],
            'version' => @$params['version'],
        ];

        $data = (object) $data;

        return $data;
    }

    private function getOriginUrl($headers)
    {
        if (array_key_exists('origin', $headers)) {
            return $headers['origin'][0];
        }

        if (array_key_exists('referer', $headers)) {
            return $headers['referer'][0];
        }

        return '';
    }

    private function getRemoteAddr($headers)
    {
        if (array_key_exists('x-forwarded-for', $headers)) {
            return $headers['x-forwarded-for'][0];
        }

        if (array_key_exists('REMOTE_ADDR', $_SERVER)) {
            return $_SERVER['REMOTE_ADDR'];
        }

        return '';
    }
}
