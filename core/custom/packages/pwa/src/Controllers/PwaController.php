<?php

namespace EvolutionCMS\Pwa\Controllers;

use EvolutionCMS\Models\SiteContent;
use Illuminate\Support\Facades\File;

class PwaController
{
    public $evo;

    public function __construct()
    {
        $this->evo = evo();
    }

    public function manifest()
    {
        $manifest = config('pwa');
        unset($manifest['serviceWorkerSettings']);
        return \Response::json($manifest);
    }

    public function serviceworker()
    {
        $config = config('pwa')['serviceWorkerSettings'];
        $config['startPage'] =  \UrlProcessor::makeUrl(intval($config['startPageId']));
        $config['offlinePage'] = \UrlProcessor::makeUrl(intval($config['offlinePageId']));
        $config['cacheDocs'] = '';
        if ($config['cacheDocsIds'] != '') {
            $ids = explode(',', $config['cacheDocsIds']);
            foreach ($ids as $docid) {
                $config['cacheDocs'] .= "'/" . $this->evo->makeUrl($docid) . "',";
            }
        }

        $config['cacheFilesRendered'] = '';
        if (is_array($config['cacheFiles'])) {
            foreach ($config['cacheFiles'] as $file) {
                if (File::isFile(MODX_BASE_PATH . $file)) {
                    $config['cacheFilesRendered'] .= "'" . $file . "',";
                } else {
                    if (File::isDirectory(MODX_BASE_PATH . $file)) {
                        $dir = new \RecursiveDirectoryIterator(MODX_BASE_PATH . $file);
                        foreach (new \RecursiveIteratorIterator($dir) as $filePath => $obj) {
                            $fileName = pathinfo($filePath, PATHINFO_BASENAME);
                            if ($fileName != "." && $fileName != "..") {
                                $filePath = str_replace(MODX_BASE_PATH, "", $filePath);
                                $config['cacheFilesRendered'] .= "'" . $filePath . "',";
                            }
                        }
                    }
                }
            }
        }

        $doc_version = SiteContent::query()->orderBy('editedon', 'DESC')->first()->editedon;
//        $file_version = config('file_version');
        $serviceWorker = "'use strict';
            const cacheName = '" . md5($doc_version) ."'; 
            const startPage = '" . $config['startPage'] . "';
            const offlinePage = '" . $config['offlinePage'] . "';
            const filesToCache = [
                startPage, 
                offlinePage,
                " . $config['cacheDocs'] . "
                " . $config['cacheFilesRendered'] . "
            ];
          const neverCacheUrls = [/\/manager/];
            
            // Install
            self.addEventListener('install', function(e) {
                console.log('EvoPWA service worker installation');
                e.waitUntil(
                    caches.open(cacheName).then(function(cache) {
                        console.log('EvoPWA service worker caching dependencies');
                        filesToCache.map(function(url) {
                            return cache.add(url).catch(function (reason) {
                                return console.log('EvoPWA: ' + String(reason) + ' ' + url);
                            });
                        });
                    })
                );
            });
            
            // Activate
            self.addEventListener('activate', function(e) {
                console.log('EvoPWA service worker activation');
                e.waitUntil(
                    caches.keys().then(function(keyList) {
                        return Promise.all(keyList.map(function(key) {
                            if ( key !== cacheName ) {
                                console.log('EvoPWA old cache removed', key);
                                return caches.delete(key);
                            }
                        }));
                    })
                );
                return self.clients.claim();
            });
            
                       // Fetch
            self.addEventListener('fetch', function(e) {
                
                // Return if the current request url is in the never cache list
                if ( ! neverCacheUrls.every(checkNeverCacheList, e.request.url) ) {
                  console.log( 'EvoPWA: Current request is excluded from cache.' );
                  return;
                }
                
                // Return if request url protocal isn't http or https
                if ( ! e.request.url.match(/^(http|https):\/\//i) )
                    return;
                
                // Return if request url is from an external domain.
                if ( new URL(e.request.url).origin !== location.origin )
                    return;
                
                // For POST requests, do not use the cache. Serve offline page if offline.
                if ( e.request.method !== 'GET' ) {
                    e.respondWith(
                        fetch(e.request).catch( function() {
                            return caches.match(offlinePage);
                        })
                    );
                    return;
                }
                
                // Revving strategy
                if ( e.request.mode === 'navigate' && navigator.onLine ) {
                    e.respondWith(
                        fetch(e.request).then(function(response) {
                            return caches.open(cacheName).then(function(cache) {
                                cache.put(e.request, response.clone());
                                return response;
                            });  
                        })
                    );
                    return;
                }
            
                e.respondWith(
                    caches.match(e.request).then(function(response) {
                        return response || fetch(e.request).then(function(response) {
                            return caches.open(cacheName).then(function(cache) {
                                cache.put(e.request, response.clone());
                                return response;
                            });  
                        });
                    }).catch(function() {
                        return caches.match(offlinePage);
                    })
                );
            });
            
            // Check if current url is in the neverCacheUrls list
            function checkNeverCacheList(url) {
                if ( this.match(url) ) {
                    return false;
                }
                return true;
            }";
        return \Response::make($serviceWorker, '200', ['Content-Type' => 'application/javascript', 'Service-Worker-Allowed' => '/']);
    }

}