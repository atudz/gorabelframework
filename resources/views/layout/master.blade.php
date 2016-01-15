<!DOCTYPE html>
<html data-ng-app="app">
<head>
	@include('layout.assets.meta')
 	<title>Gorabel</title>
    @include('layout.assets.css')
</head>
<body>
	@include('layout.header')	
	
	<div class="container">			
		 @yield('content')
	</div>
	
	@include('layout.footer')	
	@include('layout.assets.js')
</body>

</html>