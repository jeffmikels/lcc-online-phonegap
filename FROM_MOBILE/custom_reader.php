<?php header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + 600)); ?>
<?php header('Cache-Control: max-age=600'); ?>
<?php

function debug($s)
{
	print "<!-- DEBUG -->";
	print "<[!CDATA[";
	print_r ($s);
	print "]]>";
}

$display='all';
$cachebreaker = "11";

if (isset($_GET['category']))
{
	$cat = urlencode($_GET['category']);
	$url = 'http://lafayettecc.org/news/category/' . $cat . '/feed/?json&lcconlineapp&cachebreaker=' . $cachebreaker;
}
if (isset($_GET['podcast']))
{
	$is_podcast = true;
	$format = urlencode($_GET['podcast']);
	$url = 'http://lafayettecc.org/news/feed/podcast?format=' . $format . '&json&lcconlineapp&cachebreaker=' . $cachebreaker;
}
if (isset($_GET['single']))
{
	$display = 'single';
	$url = $_GET['single'] . '?json&lcconlineapp&cachebreaker=' . $cachebreaker;
}
$json = file_get_contents($url);
$feed = json_decode($json, true);

?>
<!DOCTYPE html>
<html>
<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="mobile-web-app-capable" content="yes">
		<meta name="description" content="">
		<meta name="author" content="">

		<!-- CODE FOR INSTALLING AS A WEB APP -->
		<link rel="apple-touch-icon-precomposed" href="/icon.png"/>
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black" />

		<title>LCC Online</title>

		<!-- BOOTSTRAP CSS -->
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
		<!-- Optional theme -->
		<link rel="stylesheet" href="bootstrap/css/bootstrap-theme.min.css">

		<!-- webfont -->
		<!--<link href='http://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic,700italic' rel='stylesheet' type='text/css'>-->
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,700italic,400,700,300' rel='stylesheet' type='text/css'>


		<link rel="stylesheet" href="style.css?cachebreaker=3">

		<script type="text/javascript">
		function show_player(type, id, url)
		{
			elid = 'media-player-' + id;
			el = document.getElementById(elid);
			player_html = new Array();
			player_html['audio'] = "<audio class=\"audio audio-" + id + "\" src=\""+url+"\" controls=controls preload=metadata autoplay=autoplay></audio>";
			player_html['video'] = "<video class=\"video video-" + id + "\" src=\""+url+"\" controls=controls preload=metadata autoplay=autoplay></video>";
			el.innerHTML = player_html[type];
		}
		</script>
</head>
<body>
	<div id="header"><a href="index.html?cachebreaker=3"><img src="logo-header.png" /></a></div>
	<div id="content">

		<?php if ($display == 'all'): ?>

		<div class="all">

		<?php foreach($feed['posts'] as $item): ?>
			<?php $single_url = urlencode($item['url']); ?>
			<div class="item">
				<div class="postmeta"><?php print $item['date']; ?></div>
				<h2><a href="?single=<?php print urlencode($item['url']); ?><?php if (isset($format)) print "&podcast=" . $format; ?>"><?php print $item['title']; ?></a></h2>
				<p><?php print substr($item['excerpt'],0,80);?>...</p>
			</div>
		<?php endforeach; ?>

		</div>

		<?php else: ?>

		<div class="single">


			<?php foreach($feed['posts'] as $item): ?>

				<div class="item">
					<div class="postmeta"><?php print $item['date']; ?></div>
					<h2><?php print $item['title']; ?> &nbsp; <a class="btn btn-sm btn-success" href="<?php print $item['url']; ?>">View on Website</a></h2>

					<?php if ($item['enclosures'] && isset($format)): ?>
						<?php $final_enclosure = Array(); ?>
						<?php foreach($item['enclosures'] as $enclosure) : ?>
							<?php
								//$encdata = preg_replace("/\r\n|\r/", "\n")
								$encdata = preg_split("/\r\n|\n|\r/", $enclosure);
								$podcast_data = unserialize($encdata[3]);
								if ($podcast_data['format'] != $format) continue;
								$final_enclosure['url'] = $encdata[0];
								$final_enclosure['mime'] = $encdata[2];
							?>
						<?php endforeach; ?>
					<?php endif; ?>

					<?php if ($final_enclosure) : ?>

						<!-- SHOW MEDIA PLAYER -->

						<?php $player_type = 'video'; ?>
						<?php if (preg_match('/^audio/', $final_enclosure['mime'])) $player_type = 'audio'; ?>

						<div id="media-player-<?php print $item['id']?>" class="media-div">

						<?php if ($display != 'single'): ?>

							<button class="btn btn-success btn-sm btn-block" onclick="show_player('<?php print $player_type; ?>' , <?php print $item['id']?>,'<?php print $final_enclosure['url']; ?>')"><span class="glyphicon glyphicon-play"></span>&nbsp;&nbsp;Play</button>

						<?php else: ?>

							<?php if ($player_type == 'audio'): ?>

							<audio class="video video-<?php print $item['id']; ?>" src="<?php print $final_enclosure['url']; ?>" preload=metadata controls=controls autoplay=autoplay></audio>

							<?php else: ?>

							<video class="video video-<?php print $item['id']; ?>" src="<?php print $final_enclosure['url']; ?>" preload=metadata controls=controls autoplay=autoplay></video>

							<?php endif; ?>

						<?php endif; ?>

						</div>

					<!-- END MEDIA PLAYER -->

					<?php endif; ?>


					<p><?php print $item['filtered']; ?></p>


					<?php /*
					<?php if($item['enclosures']): ?>

						<hr />
						<p>Media Files:
						<?php foreach($item['enclosures'] as $enclosure) : ?>
							<?php
								//$encdata = preg_replace("/\r\n|\r/", "\n")
								$encdata = preg_split("/\r\n|\n|\r/", $enclosure);
								$url = $encdata[0];
								$mime = $encdata[2];
								$podcast_data = unserialize($encdata[3]);
								//debug($podcast_data);
								$format = $podcast_data['format'];
							?>

						<a class="btn btn-sm btn-success" href="<?php print $url; ?>"><?php print $format; ?></a>
						<?php endforeach; ?>

						</p>

					<?php endif; ?>
					*/ ?>

				</div>

			<?php endforeach; ?>

		</div>

		<?php endif; ?>

	</div>
</body>
</html>