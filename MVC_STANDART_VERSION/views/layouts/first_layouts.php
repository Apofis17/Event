<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	<link rel="stylesheet" type="text/css" href="/css/main.css" />
	<title>Блог</title>
</head>

<body>
	<div class="main_conteiner">
		<div class="menu">
			<ul>
				<li><a href="/">Главная</a></li>
				<li><a href="/category/?id=1">Категория 1</a></li>
				<li><a href="/category/?id=2">Категория 2</a></li>
				<li><a href="/category/?id=3">Категория 3</a></li>
			</ul>
		</div>
		
		<?php
			include ($contentPage);
		?>
		
		<div class="menu">
			<ul>
				<li><a href="/">Главная</a></li>
				<li><a href="/category/?id=1">Категория 1</a></li>
				<li><a href="/category/?id=2">Категория 2</a></li>
				<li><a href="/category/?id=3">Категория 3</a></li>
			</ul>
		</div>
	</div>
</body>
</html>