<?php

namespace FCExportHandler;

class LogDataBuilder
{
    public function build($params, $headers)
    {
        date_default_timezone_set('UTC');

        $data = [
            'chartType' => @$params['chartType'],
            'chartCaption' => @$params['chartCaption'],
            'chartSubCaption' => @$params['chartSubCaption'],
            'isSingleExport' => @$params['isSingleExport'],
            'exportFileName' => @$params['exportFileName'],
            'exportFormat' => @$params['exportFormat'],
            'chartOriginUrl' => $headers['origin'][0],
            'userAgent' => $headers['user-agent'][0],
            'isFullVersion' => @$params['isFullVersion'],
            'userTimeZone' => @$params['userTimeZone'],
            'userIPAddress' => $_SERVER['REMOTE_ADDR'],
            'userCountry' => 'India',
            'chartIdentifier' => 'Hash',
            'serverDateTime' => date('Y-m-d H:i:s'),
            'exportAction' => @$params['exportAction'],
            'version' => @$params['version'],
        ];

        $data = (object) $data;

        return $data;
    }
}
