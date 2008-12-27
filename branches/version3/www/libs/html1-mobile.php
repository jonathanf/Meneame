<?php
// The source code packaged with this file is Free Software, Copyright (C) 2005 by
// Ricardo Galli <gallir at uib dot es>.
// It's licensed under the AFFERO GENERAL PUBLIC LICENSE unless stated otherwise.
// You can get copies of the licenses here:
// 		http://www.affero.org/oagpl.html
// AFFERO GENERAL PUBLIC LICENSE is also included in the file called "COPYING".


@include mnminclude.'ads-credits-functions.php';

include_once(mnminclude.'post.php');

// Warning, it redirects to the content of the variable
if (!empty($globals['lounge'])) {
	header('Location: http://'.get_server_name().$globals['base_url'].$globals['lounge']);
	die;
}

$globals['start_time'] = microtime(true);

header('Content-type: text/html; charset=utf-8');
if ($current_user->user_id) {
	header('Cache-Control: private');
}

function do_tabs($tab_name, $tab_selected = false, $extra_tab = false) {
	global $globals;

	$reload_text = _('recargar');
	$active = ' class="tabmain-this"';

	if ($tab_name == "main" ) {
		echo '<ul class="tabmain">';

		// url with parameters?
		if (!empty($_SERVER['QUERY_STRING']))
			$query = "?".htmlentities($_SERVER['QUERY_STRING']);

		// START STANDARD TABS
		// First the standard and always present tabs
		// published tab
		if ($tab_selected == 'published') {
			echo '<li '.$active.'><a href="'.$globals['base_url'].'" title="'.$reload_text.'">'._('portada').'</a></li>';
		} else {
			echo '<li><a  href="'.$globals['base_url'].'">'._('portada').'</a></li>';
		}


		// Most voted
		if ($tab_selected == 'popular') {
			echo '<li '.$active.'><a href="'.$globals['base_url'].'topstories.php" title="'.$reload_text.'">'._('populares').'</a></li>';
		} else {
			echo '<li><a href="'.$globals['base_url'].'topstories.php">'._('populares').'</a></li>';
		}

		// shake it
		if ($tab_selected == 'shakeit') {
			echo '<li '.$active.'><a href="'.$globals['base_url'].'shakeit.php" title="'.$reload_text.'">'._('pendientes').'</a></li>';
		} else {
			echo '<li><a href="'.$globals['base_url'].'shakeit.php">'._('pendientes').'</a></li>';
		}
		// END STANDARD TABS

		//Extra tab
		if ($extra_tab) {
			if ($globals['link_permalink']) $url = $globals['link_permalink'];
			else $url = htmlentities($_SERVER['REQUEST_URI']);
			echo '<li '.$active.'><a href="'.$url.'" title="'.$reload_text.'">'.$tab_selected.'</a></li>';
		}
		echo '</ul>' . "\n";
	}
}

function do_header($title, $id='home') {
	global $current_user, $dblang, $globals, $greetings;

	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' . "\n";
	echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="'.$dblang.'" lang="'.$dblang.'">' . "\n";
	echo '<head>' . "\n";
	echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />' . "\n";
	echo '<meta name="ROBOTS" content="NOARCHIVE" />'."\n";
	echo "<title>$title</title>\n";

	do_css_includes();

	echo '<meta name="generator" content="meneame" />' . "\n";
	if (!empty($globals['noindex'])) {
		echo '<meta name="robots" content="noindex,follow"/>' . "\n";
	}
	if (!empty($globals['tags'])) {
		echo '<meta name="keywords" content="'.$globals['tags'].'" />' . "\n";
	}
	if (empty($globals['favicon'])) $globals['favicon'] = 'img/favicons/favicon4.ico';
	echo '<link rel="icon" href="'.$globals['base_url'].$globals['favicon'].'" type="image/x-icon"/>' . "\n";
	echo '<link rel="apple-touch-icon" href="'.$globals['base_url'].'img/favicons/apple-touch-icon.png"/>' . "\n";

	do_js_includes();

	if ($globals['extra_head']) echo $globals['extra_head'];

	echo '</head>' . "\n";
	echo "<body id=\"$id\" ". $globals['body_args']. ">\n";

	echo '<div id="header">' . "\n";
	echo '<a href="'.$globals['base_url'].'" title="'._('inicio').'" id="logo">'._("menéame").'</a>'."\n";
	echo '<ul id="headtools">';

	if($current_user->authenticated) {
    	$randhello = array_rand($greetings, 1);
 		echo '<li><a href="'.get_user_uri($current_user->user_login).'" title="'.$current_user->user_login.'"><img src="'.get_avatar_url($current_user->user_id, $current_user->user_avatar, 20).'" width="15" height="15" alt="'.$current_user->user_login.'"/></a></li>';
  		echo '<li class="noborder"><a href="'.$globals['base_url'].'login.php?op=logout&amp;return='.urlencode($_SERVER['REQUEST_URI']).'">'. _('Logout').'</a></li>';
	} else {
  		echo '<li class="noborder"><a href="'.$globals['base_url'].'login.php?return='.urlencode($_SERVER['REQUEST_URI']).'">'. _('Login').'</a></li>';
	}


	echo '</ul>' . "\n";
	echo '<span class="header-left">&nbsp;</span>' . "\n";
	echo '</div>' . "\n";
	echo '<div id="container">'."\n";
}

function do_css_includes() {
	global $globals;

	if ($globals['css_main']) {
		echo '<link rel="stylesheet" type="text/css" media="screen" href="'.$globals['base_url'].$globals['css_main'].'" />' . "\n";
	}
	if ($globals['css_color']) {
		echo '<link rel="stylesheet" type="text/css" media="screen" href="'.$globals['base_url'].$globals['css_color'].'" />' . "\n";
	}
	foreach ($globals['extra_css'] as $css) {
		echo '<link rel="stylesheet" type="text/css" media="screen" href="'.$globals['base_url'].'css/'.$css.'" />' . "\n";
	}
}

function do_js_includes() {
	global $globals;

	echo '<script type="text/javascript">var base_url="'.$globals['base_url'].'";</script>'."\n";
	echo '<script src="'.$globals['base_url'].'js/jquery.pack.js" type="text/javascript"></script>' . "\n";
	echo '<script src="'.$globals['base_url'].'js/general05.js" type="text/javascript"></script>' . "\n";
	do_js_from_array($globals['extra_js']);
	echo '<script type="text/javascript">if(top.location != self.location)top.location = self.location;'."\n";
	if ($globals['extra_js_text']) {
		 echo $globals['extra_js_text']."\n";
	}
	echo '</script>'."\n";
}

function do_js_from_array($array) {
	global $globals;

	foreach ($array as $js) {
		if (preg_match('/^http|^\//', $js)) {
			echo '<script src="'.$js.'" type="text/javascript"></script>' . "\n";
		} else {
			echo '<script src="'.$globals['base_url'].'js/'.$js.'" type="text/javascript"></script>' . "\n";
		}
	}
}

function do_footer($credits = true) {
	global $globals;

	echo "</div>\n";
	if($credits) @do_credits_mobile();
	do_js_from_array($globals['post_js']);

	// warn warn warn 
	// dont do stats of password recovering pages
	@include('ads/stats.inc');
	printf("\n<!--Generated in %4.3f seconds-->\n", microtime(true) - $globals['start_time']);
	echo "</body></html>\n";
}

function do_footer_menu() {
	global $globals, $current_user;

}

function force_authentication() {
	global $current_user;

	if(!$current_user->authenticated) {
		header('Location: '.$globals['base_url'].'login.php?return='.$_SERVER['REQUEST_URI']);
		die;
	}
	return true;
}

function do_pages($total, $page_size=25, $margin = true) {
	global $db;

	if ($total < $page_size) return;

	$index_limit = 3;

	$query=preg_replace('/page=[0-9]+/', '', $_SERVER['QUERY_STRING']);
	$query=preg_replace('/^&*(.*)&*$/', "$1", $query);
	if(!empty($query)) {
		$query = htmlspecialchars($query);
		$query = "&amp;$query";
	}
	
	$current = get_current_page();
	$total_pages=ceil($total/$page_size);
	$start=max($current-intval($index_limit/2), 1);
	$end=$start+$index_limit-1;
	
	if ($margin) {
		echo '<div class="pages-margin">';
	} else {
		echo '<div class="pages">';
	}

	if($current==1) {
		echo '<span class="nextprev">&#171; '._('anterior'). '</span>';
	} else {
		$i = $current-1;
		echo '<a href="?page='.$i.$query.'">&#171; '._('anterior').'</a>';
	}

	if($start>1) {
		$i = 1;
		echo '<a href="?page='.$i.$query.'" title="'._('ir a página')." $i".'">'.$i.'</a>';
		echo '<span>...</span>';
	}
	for ($i=$start;$i<=$end && $i<= $total_pages;$i++) {
		if($i==$current) {
			echo '<span class="current">'.$i.'</span>';
		} else {
			echo '<a href="?page='.$i.$query.'" title="'._('ir a página')." $i".'">'.$i.'</a>';
		}
	}
	if($total_pages>$end) {
		$i = $total_pages;
		echo '<span>...</span>';
		echo '<a href="?page='.$i.$query.'" title="'._('ir a página')." $i".'">'.$i.'</a>';
	}
	if($current<$total_pages) {
		$i = $current+1;
		echo '<a href="?page='.$i.$query.'">&#187; '._('siguiente').'</a>';
	} else {
		echo '<span class="nextprev">&#187; '._('siguiente'). '</span>';
	}
	echo "</div><!--html1:do_pages-->\n";

}

?>