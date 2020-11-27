<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width,initial-scale=1.0">
	<link rel="icon" href="{{ asset('favicon.ico') }}">
    <link href="{{ mix('css/App.css') }}" rel="stylesheet">
	<title>Sigma Vue</title>
</head>
    <body>
        <div id="app">
            <app :app-name="appName"></app>
            <div class="splash-screen">
                <div class="splash-container">
                    <div class="splash-double-bounce1"></div>
                    <div class="splash-double-bounce2"></div>
                </div>
            </div>
        </div>
        <script src="{{ mix('js/app.js') }}"></script>
    </body>
</html>
