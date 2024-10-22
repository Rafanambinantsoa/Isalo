<?php

use Illuminate\Support\Facades\Schedule;

// Artisan::command('inspire', function () {
//     $this->comment(Inspiring::quote());
// })->purpose('Display an inspiring quote')->hourly();

// Artisan::command('app:mise-ajour-conger-employee-status', function () {
//     $this->comment('Mise a jour automatique  de l\'attributs   est_en_conger ( )  ,  une fois la date depasseer  ( conger  ) ');
// })->everyMinute();

Schedule::command('app:mise-ajour-conger-employee-status')->daily();
