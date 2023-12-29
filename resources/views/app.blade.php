<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>

    @isset($home)
        <link rel="stylesheet" href="/external/home.css">
    @endisset

    @routes
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @inertiaHead
</head>
<body class="hold-transition layout-top-nav">

<div id="preloader-custom">
    <div class="spinner-custom"></div>
</div>

@inertia

@isset($home)
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/5.2.1/js/swiper.min.js" integrity="sha512-y3xBRnPcYKl5rPeXr8jFALTW+vpeqXVqhNACy573tW3YBqocRygpJ042ukRPKxA2pbWp3YrvfWmWUXcEOgDIrw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endisset

</body>
</html>
