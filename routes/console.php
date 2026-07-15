<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('uch:health', function () {
    $this->info('HUMELIX LIMITED platform is ready.');
});
