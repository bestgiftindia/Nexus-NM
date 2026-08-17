<?php

namespace App\Services;

class flashService
{
    function successService(string $message)
    {
        flash()
            ->use('theme.amazon')
            ->option('timeout', 5000)
            ->option('position', 'top-center')
            ->success($message);
    }

    function errorService(string $message)
    {
        flash()
            ->use('theme.amazon')
            ->option('timeout', 5000)
            ->option('position', 'top-center')
            ->error($message);
    }

    function infoService(string $message)
    {
        flash()
            ->use('theme.amazon')
            ->option('timeout', 5000)
            ->option('position', 'top-center')
            ->info($message);
    }

    function warningService(string $message)
    {
        flash()
            ->use('theme.amazon')
            ->option('timeout', 5000)
            ->option('position', 'top-center')
            ->warning($message);
    }
}
