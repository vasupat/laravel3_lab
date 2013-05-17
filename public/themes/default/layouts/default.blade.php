<!DOCTYPE html>
<html>
	<title>{{ $theme_data['title'] }}</title>
  <!-- Stylesheets -->
  {{ $theme_data['styles'] }}

  <!-- HTML5 Support for IE -->
  {{ $theme_data['header']['scripts'] }}

</html>
<body>
	<!-- <h1>Header</h1> -->
	{{ theme_partial('header') }}

	<!-- <h1>Content</h1> -->
	<div id="content_container" class='container-fluid'>
	    {{ $theme_content }}
	</div>
	
	<!-- <h1>Footer</h1> -->
	{{ theme_partial('footer') }}

<!-- JS -->
{{ $theme_data['footer']['scripts'] }}

</body>
</html>