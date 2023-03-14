<!doctype html>
<html lang="en">

<head>

	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta content="Promoting the enjoyment, understanding and conservation of the flora of Shropshire" name="description" />
	<!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('css/' . config('core.siteId') .'-style.css') }}">
    <script src="/js/bootstrap.js"></script>
	<!-- Custom styles for this template -->
	<link rel="manifest" href="/manifest.webmanifest">
	<!-- Mapping -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js" integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=" crossorigin=""></script>
	<script type="text/javascript" src="/js/proj4.js"></script>
	<script type="text/javascript" src="/js/Leaflet.MetricGrid.js"></script>
	<script type="text/javascript" src="/js/leaflet.wms.js"></script>
	<script type="text/javascript" src="/js/BasicMap.js"></script>

	<script>
		if (navigator && navigator.serviceWorker)
		{
  			navigator.serviceWorker.register('/sw.js');
		}

        var element = document.getElementById('back-link');

        if (element)
        {
            // Provide a standard href to facilitate standard browser features such as
            //  - Hover to see link
            //  - Right click and copy link
            //  - Right click and open in new tab
            element.setAttribute('href', document.referrer);

            // We can't let the browser use the above href for navigation. If it does,
            // the browser will think that it is a regular link, and place the current
            // page on the browser history, so that if the user clicks "back" again,
            // it'll actually return to this page. We need to perform a native back to
            // integrate properly into the browser's history behavior
            element.onclick = function() {
            history.back();
            return false;
        }
}
	</script>

	<title>
    {{ $title ?? 'WildSearch' }}
	</title>
</head>

<body>
<div class="container-fluid p-2 pt-0 mt-2">
@include('components/header')

@isset($results->errorMessage)
<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <?= $results->errorMessage ?>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endisset

<div class="container-fluid content-inner p-3">
{{ $slot }}
</div>
<footer class="page-footer footer-fluid">
    <div class="mx-auto mt-2 text-center">
        <span class="small">Supported by
            <a href="https://registry.nbnatlas.org/public/show/dp120" target="_blank">National Biodiversity Network</a>
        </span>
    </div>
</footer>
</div>
</body>
</html>
