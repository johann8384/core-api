<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title><?= isset($page_title) ? $page_title : 'OpsAPI' ?></title>
<!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
<!--[if lt IE 9]>
<script src="/static/js/html5.js"></script>
<![endif]-->

<!-- Le styles -->
<link rel="stylesheet" href="<?= $this->config->base_url("css/bootstrap.min.css") ?>">
<style type="text/css">
body {
	padding-top: 60px;
}
</style>
<script src="<?= $this->config->base_url("js/jquery-1.5.2.min.js") ?>"></script>

<!-- Le fav and touch icons -->
<link rel="shortcut icon" href="/favicon.ico">
</head>

<body>

<div class="topbar">
<div class="fill">
<div class="container">
<a class="brand"><?= isset($page_title) ? $page_title : 'API Base' ?></a>

<ul class="nav">
<? if(isset($nav_links)): ?>
<? foreach($nav_links as $nav_link): ?>
<li class="<?= isset($nav_link['active']) && $nav_link['active'] == 1 ? 'active' : 'inactive' ?>"><a href="<?= $nav_link['url'] ?>"><?= $nav_link['label'] ?></a></li>
<? endforeach; ?>
<? else: ?>
<li class="active"><a href="<?= $this->config->base_url() ?>">Home</a></li>
<!-- <li class="inactive"><a href="decomissioned_hosts.php">Decommed Hosts</a></li> -->
<? endif; ?>
</ul>
</div>
</div>
</div>

<? if(isset($top_message)): ?>
<div class="row">
<div class="span-two-third">
<!-- Main hero unit for a primary marketing message or call to action -->
<div class="hero-unit">
<?= $top_message ?>
</div>
</div>
</div>
<? endif; ?>

<!-- <div class="container"> -->
