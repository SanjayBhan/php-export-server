<?php

namespace FCExportServer;

class LogDataBuilder
{
    public function build($params, $headers)
    {
        date_default_timezone_set('UTC');

        $data = [
            'chartType' => $params['charttype'],
            'chartCaption' => $params['chart_caption'],
            'chartSubCaption' => $params['chart_sub_caption'],
            'isSingleExport' => $params['is_single_export'],
            'exportFileName' => $params['exportfilename'],
            'exportFormat' => $params['exportformat'],
            'chartOriginUrl' => $headers['origin'][0],
            'userAgent' => $headers['user-agent'][0],
            'isFullVersion' => $params['is_full_version'],
            'userTimeZone' => $params['user_time_zone'],
            'userIPAddress' => $headers['user-agent'][0],
            'userCountry' => 'India',
            'chartIdentifier' => 'Hash',
            'exportAction' => $params['exportactionnew'],
        ];

        $data = (object) $data;

        return $data;
    }
}
