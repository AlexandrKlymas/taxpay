<link rel="manifest" href="/evo-manifest.json">
<meta name="theme-color" content="{{ config('pwa.theme_color') }}">
<link rel="apple-touch-icon" href="{{ config('pwa.apple-touch-icon') }}">
<script type="text/javascript">
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/evo-serviceworker.js', {
            scope: '.'
        }).then(function (registration) {
            console.log('ServiceWorker registration successful with scope: ', registration.scope)
        }, function (err) {
            console.log('ServiceWorker registration failed: ', err)
        });
    }
</script>