<?php

namespace FCExportServer;

class LogDataBuilder
{
    public function build($params, $headers)
    {
        date_default_timezone_set('UTC');

        $data = [
            'exportedImage' => $params['fileFullName'],
            'chartIdentifierHash' => $params['chartType'],
            'chartTitle' => $params['chartCaption'],
            'chartOriginUrl' => $headers['origin'][0],
            'serverDateTime' => date('Y-m-d H:i:s'),
            'userTimeZone' => $params['userTimeZone'],
            'userIP' => $headers['origin'][0],
            'userCountry' => 'IND',
            'userAgent' => $headers['user-agent'][0],
            'languageIdentifier' => 'PHP',
            'licenseInfo' => 'MIT',
            'exportType' => $params['exportType'],
        ];

        $data = (object) $data;

        return $data;
    }
}
