<?php
use Illuminate\Support\Facades\Route;

Route::get('evo-manifest.json', [EvolutionCMS\Pwa\Controllers\PwaController::class, 'manifest']);
Route::get('evo-serviceworker.js', [EvolutionCMS\Pwa\Controllers\PwaController::class, 'serviceworker']);
