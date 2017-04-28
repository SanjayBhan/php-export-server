<?php

namespace FCExportServer;

class Exporter
{
    public function serve()
    {
        include(dirname(__FILE__) . '/index.php');
    }
}
