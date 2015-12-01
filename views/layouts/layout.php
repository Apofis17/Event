<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title></title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <link href="/bootstrap/css/bootstrap.css" rel="stylesheet">
    <script src="/bootstrap/js/bootstrap.min.js"></script>
    <link href="/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link href='https://fonts.googleapis.com/css?family=Noto+Sans&subset=latin,cyrillic' rel='stylesheet'
          type='text/css'>
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
</head>
<body>
<nav class="navbar">
    <div class="container">
        <div class="navbar-header navbar_title">
            <a class="navbar-brand" href="/">Gatsbu</a>
        </div>
        <div class="menu pull-right">
            <? foreach ($menu as $key => $value) { ?>
                <button class="btn my_btn btn-sm btn_navigation" <?= $value ?>><?= $key ?></button>
            <? } ?>
        </div>
    </div>
</nav>

<div class="container base_block">
    <? include($contentPage) ?>
</div>
</body>
</html>

