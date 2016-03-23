<!DOCTYPE html>
<html data-ng-app="app">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<title>Gorabel - @yield('title')</title>
	
    @include('layouts.assets.css')
</head>
<body>
	@include('layouts.header')	
	
	<div class="container">			
		 @yield('content')
	</div>
	
	@include('layouts.footer')	
	@include('layouts.assets.js')
</body>

</html>