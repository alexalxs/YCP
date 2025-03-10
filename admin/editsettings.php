<?php
//Debug information
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', 1);
//End of debug information
require_once __DIR__.'/../settings.php';
require_once 'password.php';
check_password();

$startdate=isset($_GET['startdate'])?DateTime::createFromFormat('d.m.y', $_GET['startdate']):new DateTime();
$enddate=isset($_GET['enddate'])?DateTime::createFromFormat('d.m.y', $_GET['enddate']):new DateTime();

$date_str='';
if (isset($_GET['startdate'])&& isset($_GET['enddate'])) {
    $startstr = $_GET['startdate'];
    $endstr = $_GET['enddate'];
    $date_str="&startdate={$startstr}&enddate={$endstr}";
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Binomo Cloaker by Yellow Web</title>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>

    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- favicon
		============================================ -->
    <link rel="shortcut icon" type="image/x-icon" href="img/favicon.png" />
    <!-- Google Fonts
		============================================ -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,700,900" rel="stylesheet" />
    <!-- nalika Icon CSS
		============================================ -->
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/nalika-icon.css" />
    <!-- main CSS
		============================================ -->
    <link rel="stylesheet" href="css/main.css" />
    <!-- metisMenu CSS
		============================================ -->
    <link rel="stylesheet" href="css/metisMenu/metisMenu.min.css" />
    <link rel="stylesheet" href="css/metisMenu/metisMenu-vertical.css" />
    <!-- style CSS
		============================================ -->
    <link rel="stylesheet" href="css/style.css" />
</head>

<body>
    <div class="left-sidebar-pro">
        <nav id="sidebar" class="">
            <div class="sidebar-header">
                <a href="/admin/index.php?password=<?=$_GET['password']?><?=$date_str!==''?$date_str:''?>"><img class="main-logo" src="img/logo/logo.png" alt="" /></a>
                <strong><img src="img/favicon.png" alt="" style="width:50px"/></strong>
            </div>
			<div class="nalika-profile">
				<div class="profile-dtl">
					<a href="https://t.me/yellow_web"><img src="img/notification/4.jpg" alt="" /></a>
                    <?php include "version.php" ?>
				</div>
			</div>
            <div class="left-custom-menu-adp-wrap comment-scrollbar">
                <nav class="sidebar-nav left-sidebar-menu-pro">
                  <ul class="metismenu" id="menu1">
                        <li class="active">
                            <a class="has-arrow" href="index.php?password=<?=$_GET['password']?><?=$date_str!==''?$date_str:''?>" aria-expanded="false"><i class="icon nalika-bar-chart icon-wrap"></i> <span class="mini-click-non">Traffic</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li><a title="Stats" href="statistics.php?password=<?=$_GET['password']?><?=$date_str!==''?$date_str:''?>"><span class="mini-sub-pro">Statistics</span></a></li>
                                <li><a title="Allowed" href="index.php?password=<?=$_GET['password']?><?=$date_str!==''?$date_str:''?>"><span class="mini-sub-pro">Allowed</span></a></li>
                                <li><a title="Leads" href="index.php?filter=leads&password=<?=$_GET['password']?><?=$date_str!==''?$date_str:''?>"><span class="mini-sub-pro">Leads</span></a></li>
                                <li><a title="Blocked" href="index.php?filter=blocked&password=<?=$_GET['password']?><?=$date_str!==''?$date_str:''?>"><span class="mini-sub-pro">Blocked</span></a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="editsettings.php?password=<?=$_GET['password']?><?=$date_str!==''?$date_str:''?>" aria-expanded="false"><i class="icon nalika-table icon-wrap"></i> <span class="mini-click-non">Settings</span></a>
                        </li>
                  </ul>
                </nav>
            </div>
        </nav>
    </div>
    <!-- Start Welcome area -->
    <div class="all-content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="logo-pro">
                        <a href="index.html"><img class="main-logo" src="img/logo/logo.png" alt="" /></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="header-advance-area">
            <div class="header-top-area">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="header-top-wraper">
                                <div class="row">
                                    <div class="col-lg-1 col-md-0 col-sm-1 col-xs-12">
                                        <div class="menu-switcher-pro">
                                            <button type="button" id="sidebarCollapse" class="btn bar-button-pro header-drl-controller-btn btn-info navbar-btn">
													<i class="icon nalika-menu-task"></i>
												</button>
                                        </div>
                                    </div>

                                    <div class="col-lg-11 col-md-1 col-sm-12 col-xs-12">
                                        <div class="header-right-info">
                                            <ul class="nav navbar-nav mai-top-nav header-right-menu">
                                                <li class="nav-item dropdown">
                                                <li class="nav-item">
                                                    <a class="nav-link" href="" onClick="location.reload()">Refresh</a>
                                                </li>
                                                </i>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <a name="top"></a>

    <form action="savesettings.php?password=<?=$log_password?>" method="post">
        <div class="basic-form-area mg-tb-15">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="sparkline12-list">
                            <div class="sparkline12-graph">
                                <div class="basic-login-form-ad">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="all-form-element-inner">
                                                <hr>
<h4>#1 White Page Setup</h4>
<div class="form-group-inner">
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <label class="login2 pull-left pull-left-pro">Select method: </label>
        </div>
        <div class="col-lg-9 col-md-6 col-sm-6 col-xs-12">
            <div class="bt-df-checkbox pull-left">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="i-checks pull-left">
                            <label>
                                    <input type="radio" <?=$white_action==='folder'?'checked':''?> value="folder" name="white.action" onclick="(document.getElementById('b_2').style.display='block'); (document.getElementById('b_3').style.display='none'); (document.getElementById('b_4').style.display='none'); (document.getElementById('b_5').style.display='none')"> Local white page from folder </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="i-checks pull-left">
                            <label>
                                    <input type="radio" <?=$white_action==='redirect'?'checked':''?> value="redirect" name="white.action" onclick="(document.getElementById('b_2').style.display='none'); (document.getElementById('b_3').style.display='block'); (document.getElementById('b_4').style.display='none'); (document.getElementById('b_5').style.display='none')"> Redirect </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="i-checks pull-left">
                            <label>
                                    <input type="radio" <?=$white_action==='curl'?'checked':''?> value="curl" name="white.action" onclick="(document.getElementById('b_2').style.display='none'); (document.getElementById('b_3').style.display='none'); (document.getElementById('b_4').style.display='block'); (document.getElementById('b_5').style.display='none')"> Load external site via curl </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="i-checks pull-left">
                            <label>
                                    <input type="radio" <?=$white_action==='error'?'checked':''?> value="error" name="white.action" onclick="(document.getElementById('b_2').style.display='none'); (document.getElementById('b_3').style.display='none'); (document.getElementById('b_4').style.display='none'); (document.getElementById('b_5').style.display='block')"> Return HTTP code <small>(e.g., error 404 or just 200)</small> </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="b_2" style="display:<?=$white_action==='folder'?'block':'none'?>;">
    <div class="form-group-inner">
        <div class="row">
            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                <label class="login2 pull-left pull-left-pro">White page folder: </label>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <div class="input-group custom-go-button">
                    <input type="text" class="form-control" placeholder="white" name="white.folder.names" value="<?=is_array($white_folder_names) ? implode(',',$white_folder_names) : $white_folder_names?>">
                </div>
            </div>
        </div>
    </div>
</div>
<div id="b_3" style="display:<?=$white_action==='redirect'?'block':'none'?>;">
<div class="form-group-inner">
    <div class="row">
        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
            <label class="login2 pull-left pull-left-pro">Redirect URL: </label>
        </div>
        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
            <div class="input-group custom-go-button">
                <input type="text" class="form-control" placeholder="https://ya.ru" name="white.redirect.urls" value="<?=is_array($white_redirect_urls) ? implode(',',$white_redirect_urls) : $white_redirect_urls?>">
            </div>
        </div>
    </div>
</div>

<div class="form-group-inner">
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <label class="login2 pull-left pull-left-pro">Select redirect code: </label>
        </div>
        <div class="col-lg-9 col-md-6 col-sm-6 col-xs-12">
            <div class="bt-df-checkbox pull-left">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="i-checks pull-left">
                            <label>
                                    <input type="radio" <?=$white_redirect_type==='301'?'checked':''?> value="301" name="white.redirect.type"> 301 </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="i-checks pull-left">
                            <label>
                                    <input type="radio" <?=$white_redirect_type==='302'?'checked':''?> value="302" name="white.redirect.type"> 302 </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="i-checks pull-left">
                            <label>
                                    <input type="radio" <?=$white_redirect_type==='303'?'checked':''?> value="303" name="white.redirect.type"> 303 </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="i-checks pull-left">
                            <label>
                                    <input type="radio" <?=$white_redirect_type==='307'?'checked':''?> value="307" name="white.redirect.type">  307 </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<div id="b_4" style="display:<?=$white_action==='curl'?'block':'none'?>;">
<div class="form-group-inner">
    <div class="row">
        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
            <label class="login2 pull-left pull-left-pro">URL to load via curl: </label>
        </div>
        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
            <div class="input-group custom-go-button">
                <input type="text" class="form-control" placeholder="https://ya.ru" name="white.curl.urls" value="<?=is_array($white_curl_urls) ? implode(',',$white_curl_urls) : $white_curl_urls?>">
            </div>
        </div>
    </div>
</div>
</div>

<div id="b_5" style="display:<?=$white_action==='error'?'block':'none'?>;">
<div class="form-group-inner">
    <div class="row">
        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
            <label class="login2 pull-left pull-left-pro">HTTP code to return instead of white page: </label>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
            <div class="input-group custom-go-button">
                <input type="text" class="form-control" placeholder="404" name="white.error.codes" value="<?=is_array($white_error_codes) ? implode(',',$white_error_codes) : $white_error_codes?>">
            </div>
        </div>
    </div>
</div>
</div>

<div class="form-group-inner">
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <label class="login2 pull-left pull-left-pro">Show individual white page for each domain? </label>
        </div>
        <div class="col-lg-9 col-md-6 col-sm-6 col-xs-12">
            <div class="bt-df-checkbox pull-left">

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="i-checks pull-left">
                            <label>
                                    <input type="radio" <?=$white_use_domain_specific===false?'checked':''?> value="false" name="white.domainfilter.use" onclick="(document.getElementById('b_6').style.display='none')"> No </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="i-checks pull-left">
                            <label>
                                    <input type="radio" <?=$white_use_domain_specific===true?'checked':''?> value="true" name="white.domainfilter.use" onclick="(document.getElementById('b_6').style.display='block')"> Yes, show </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="b_6" style="display:<?=$white_use_domain_specific===true?'block':'none'?>;">
<div id="white_domainspecific">
<?php for($j=0;$j<count($white_domain_specific);$j++){ ?>
<div class="form-group-inner white">
    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
            <label class="login2 pull-left pull-left-pro">Domain => Method:Direction</label>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
             <div class="input-group">
                <input type="text" class="form-control" placeholder="xxx.yyy.com" value="<?=$white_domain_specific[$j]["name"]?>" name="white.domainfilter.domains[<?=$j?>][name]">
            </div>
        </div>
        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
             <p>=></p>  
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="site:white" value="<?=$white_domain_specific[$j]["action"]?>" name="white.domainfilter.domains[<?=$j?>][action]">
            </div>
        </div>
        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
            <a href="javascript:void(0)" class="remove-domain-item btn btn-sm btn-primary">Remove</a>
        </div>
    </div>
</div>
<?php } ?>
</div>
<a id="add-domain-item" class="btn btn-sm btn-primary" href="javascript:;">Add</a>
</div>

<div class="form-group-inner">
<div class="row">
<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
<label class="login2 pull-left pull-left-pro">Use JS check? 
    <small>
If JS check is enabled, the user will always land on white, and only if the checks pass, then they will see black.
    </small> 
</div>
<div class="col-lg-9 col-md-6 col-sm-6 col-xs-12">
<div class="bt-df-checkbox pull-left">

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="i-checks pull-left">
                <label>
                        <input type="radio" <?=$use_js_checks===false?'checked="checked"':''?> value="false" name="white.jschecks.enabled" onclick="(document.getElementById('jscheckssettings').style.display = 'none')"> No, do not use </label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="i-checks pull-left">
                <label>
                        <input type="radio" value="true" <?=$use_js_checks===true?'checked="checked"':''?> name="white.jschecks.enabled" onclick="(document.getElementById('jscheckssettings').style.display = 'block')"> Yes, use </label>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>

<div id="jscheckssettings" style="display:<?=$use_js_checks===true?'block':'none'?>;">
<div class="form-group-inner">
    <div class="row">
        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
            <label class="login2 pull-left pull-left-pro">Test time in milliseconds: </label>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
            <div class="input-group custom-go-button">
                <input type="text" class="form-control" placeholder="10000" name="white.jschecks.timeout" value="<?=$js_timeout?>">
            </div>
        </div>
    </div>
</div>
<div class="form-group-inner">
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <label class="login2 pull-left pull-left-pro">What to check? </label>
        </div>
        <div class="col-lg-9 col-md-6 col-sm-6 col-xs-12">
            <div class="bt-df-checkbox pull-left">

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="i-checks pull-left">
                            <label>
                                    <input type="checkbox" name="white.jschecks.events[]" value="mousemove" <?=in_array('mousemove',$js_checks)?'checked':''?>> Mouse movement </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="i-checks pull-left">
                            <label>
                                    <input type="checkbox" name="white.jschecks.events[]" value="keydown" <?=in_array('keydown',$js_checks)?'checked':''?>> Key presses </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="i-checks pull-left">
                            <label>
                                    <input type="checkbox" name="white.jschecks.events[]" value="scroll" <?=in_array('scroll',$js_checks)?'checked':''?>> Scrolling </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="i-checks pull-left">
                            <label>
                                    <input type="checkbox" name="white.jschecks.events[]" value="devicemotion" <?=in_array('devicemotion',$js_checks)?'checked':''?>> Device motion (only for Android) </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="i-checks pull-left">
                            <label>
                                    <input type="checkbox" name="white.jschecks.events[]" value="deviceorientation" <?=in_array('deviceorientation',$js_checks)?'checked':''?>> Device orientation in space (only for Android) </label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="i-checks pull-left">
                            <label>
                                    <input type="checkbox" name="white.jschecks.events[]" value="audiocontext" <?=in_array('audiocontext',$js_checks)?'checked':''?>> Audio context in browser </label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="i-checks pull-left">
                            <label>
                                    <input id="tzcheck" type="checkbox" name="white.jschecks.events[]" value="timezone" <?=in_array('timezone',$js_checks)?'checked':''?> onchange="(document.getElementById('jscheckstz').style.display = this.checked?'block':'none')"> Time zone </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="jscheckstz" class="form-group-inner" style="display:<?=in_array('timezone',$js_checks)?'block':'none'?>;">
    <div class="row">
        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
            <label class="login2 pull-left pull-left-pro">Minimum acceptable time zone</label>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
            <div class="input-group custom-go-button">
                <input type="text" class="form-control" placeholder="-3" name="white.jschecks.tzstart" value="<?=$js_tzstart?>">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
            <label class="login2 pull-left pull-left-pro">Maximum acceptable time zone</label>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
            <div class="input-group custom-go-button">
                <input type="text" class="form-control" placeholder="3" name="white.jschecks.tzend" value="<?=$js_tzend?>">
            </div>
        </div>
    </div>
</div>
<div class="form-group-inner">
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <label class="login2 pull-left pull-left-pro">Mask JS check code? </label>
        </div>
        <div class="col-lg-9 col-md-6 col-sm-6 col-xs-12">
            <div class="bt-df-checkbox pull-left">

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="i-checks pull-left">
                            <label>
                                    <input type="radio" value="true" <?=$js_obfuscate===true?'checked="checked"':''?> name="white.jschecks.obfuscate"> Mask </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="i-checks pull-left">
                            <label>
                                    <input type="radio" value="false" <?=$js_obfuscate===false?'checked="checked"':''?> name="white.jschecks.obfuscate"> No, do not mask </label>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
</div>
<br>
<hr>
<h4>#2 Black Page Setup</h4>
<div class="form-group-inner">
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <label class="login2 pull-left pull-left-pro">Select method to load black pages: </label>
        </div>
        <div class="col-lg-9 col-md-6 col-sm-6 col-xs-12">
            <div class="bt-df-checkbox pull-left">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="i-checks pull-left">
                            <label>
                                    <input type="radio" <?=$black_preland_action==='none'?'checked':''?> value="none" name="black.prelanding.action" onclick="(document.getElementById('b_8').style.display='none')"> Do not use prelanding </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="i-checks pull-left">
                            <label>
                                    <input type="radio" <?=$black_preland_action==='folder'?'checked':''?> value="folder" name="black.prelanding.action" onclick="(document.getElementById('b_8').style.display='block')"> Local prelanding from folder </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="b_8" style="display:<?=$black_preland_action==='folder'?'block':'none'?>;">
    <div class="form-group-inner">
        <div class="row">
            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                <label class="login2 pull-left pull-left-pro">Folders where prelandings are located </label>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <div class="input-group custom-go-button">
                    <input type="text" class="form-control" placeholder="p1,p2" name="black.prelanding.folders" value="<?=is_array($black_preland_folder_names) ? implode(',',$black_preland_folder_names) : $black_preland_folder_names?>">
                </div>
            </div>
        </div>
    </div>

</div>

<div class="form-group-inner">
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <label class="login2 pull-left pull-left-pro">Select method to load landing pages: </label>
        </div>
        <div class="col-lg-9 col-md-6 col-sm-6 col-xs-12">
            <div class="bt-df-checkbox pull-left">

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="i-checks pull-left">
                            <label>
                                    <input type="radio" <?=$black_land_action==='folder'?'checked':''?> value="folder" name="black.landing.action" onclick="(document.getElementById('b_landings_redirect').style.display='none'); (document.getElementById('b_landings_folder').style.display='block')"> Local landing from folder </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="i-checks pull-left">
                            <label>
                                    <input type="radio" <?=$black_land_action==='redirect'?'checked':''?> value="redirect" name="black.landing.action" onclick="(document.getElementById('b_landings_redirect').style.display='block'); (document.getElementById('b_landings_folder').style.display='none')"> Redirect </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="b_landings_folder" style="display:<?=$black_land_action==='folder'?'block':'none'?>;">
<div class="form-group-inner">
    <div class="row">
        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
            <label class="login2 pull-left pull-left-pro">Folders where landing pages are located </label>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
            <div class="input-group custom-go-button">
                <input type="text" class="form-control" placeholder="l1,l2" name="black.landing.folder.names" value="<?=is_array($black_land_folder_names) ? implode(',',$black_land_folder_names) : $black_land_folder_names?>">
            </div>
        </div>
    </div>
</div>

<div class="form-group-inner">
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <label class="login2 pull-left pull-left-pro">Use thank you page: </label>
        </div>
        <div class="col-lg-9 col-md-6 col-sm-6 col-xs-12">
            <div class="bt-df-checkbox pull-left">

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="i-checks pull-left">
                            <label>
                                    <input type="radio" <?=$black_land_use_custom_thankyou_page===true?'checked':''?> value="true" name="black.landing.folder.customthankyoupage.use" onclick="(document.getElementById('ctpage').style.display = 'block'); (document.getElementById('pppage').style.display = 'none')"> Custom, on the klo side </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="i-checks pull-left">
                            <label>
                                    <input type="radio" <?=$black_land_use_custom_thankyou_page===false?'checked':''?> value="false" name="black.landing.folder.customthankyoupage.use" onclick="(document.getElementById('ctpage').style.display = 'none'); (document.getElementById('pppage').style.display = 'block')"> Regular, on the PP side </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="ctpage" class="form-group-inner" style="display:<?=$black_land_use_custom_thankyou_page===true?'block':'none'?>">
    <div class="row">
        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
            <label class="login2 pull-left pull-left-pro">Language to show thank you page on Klo: </label>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
            <div class="input-group custom-go-button">
                <input type="text" class="form-control" placeholder="EN" name="black.landing.folder.customthankyoupage.language" value="<?=$black_land_thankyou_page_language?>">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
            <label class="login2 pull-left pull-left-pro"> Path from root of landing to script to send form data:</label>
        </div>
        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
            <div class="input-group custom-go-button">
                <input type="text" class="form-control" placeholder="order.php" name="black.landing.folder.conversions.script" value="<?=$black_land_conversion_script?>">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <label class="login2 pull-left pull-left-pro">Use upsell on thank you page: </label>
        </div>
        <div class="col-lg-9 col-md-6 col-sm-6 col-xs-12">
            <div class="bt-df-checkbox pull-left">

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="i-checks pull-left">
                            <label>
                                    <input type="radio" <?=$thankyou_upsell===true?'checked':''?> value="true" name="black.landing.folder.customthankyoupage.upsell.use" onclick="document.getElementById('thankupsell').style.display = 'block'"> Yes</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="i-checks pull-left">
                            <label>
                                    <input type="radio" <?=$thankyou_upsell===false?'checked':''?> value="false" name="black.landing.folder.customthankyoupage.upsell.use" onclick="document.getElementById('thankupsell').style.display = 'none'">No</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="thankupsell" class="form-group-inner" style="display:<?=$thankyou_upsell===true?'block':'none'?>">
    <div class="row">
        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
            <label class="login2 pull-left pull-left-pro">Upsell header:</label>
        </div>
        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
            <div class="input-group custom-go-button">
                <input type="text" class="form-control" placeholder="This is a header" name="black.landing.folder.customthankyoupage.upsell.header" value="<?=$thankyou_upsell_header?>">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
            <label class="login2 pull-left pull-left-pro">Upsell text:</label>
        </div>
        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
            <div class="input-group custom-go-button">
                <input type="text" class="form-control" placeholder="This is a text" name="black.landing.folder.customthankyoupage.upsell.text" value="<?=$thankyou_upsell_text?>">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
            <label class="login2 pull-left pull-left-pro">Full URL of landing upsell:</label>
        </div>
        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
            <div class="input-group custom-go-button">
                <input type="text" class="form-control" placeholder="https://ya.ru" name="black.landing.folder.customthankyoupage.upsell.url" value="<?=$thankyou_upsell_url?>">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
            <label class="login2 pull-left pull-left-pro">Folder name for thank you page images <small>folder must be created in thankyou/upsell</small></label>
        </div>
        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
            <div class="input-group custom-go-button">
                <input type="text" class="form-control" placeholder="img" name="black.landing.folder.customthankyoupage.upsell.imgdir" value="<?=$thankyou_upsell_imgdir?>">
            </div>
        </div>
    </div>
</div>
<div id="pppage" class="form-group-inner" style="display:<?=$black_land_use_custom_thankyou_page===false?'block':'none'?>">
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <label class="login2 pull-left pull-left-pro">Add to button click handler to count conversions on landing page? </label>
        </div>
        <div class="col-lg-9 col-md-6 col-sm-6 col-xs-12">
            <div class="bt-df-checkbox pull-left">

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="i-checks pull-left">
                            <label>
                                    <input type="radio" <?=$black_land_log_conversions_on_button_click===false?'checked':''?> value="false" name="black.landing.folder.conversions.logonbuttonclick"> No <small>(conversion will be counted on CUSTOM thank you page)</small> </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="i-checks pull-left">
                            <label>
                                    <input type="radio" <?=$black_land_log_conversions_on_button_click===true?'checked':''?> value="true" name="black.landing.folder.conversions.logonbuttonclick"> Yes </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <label class="login2 pull-left pull-left-pro">Where to track conversion in Facebook? </label>
        </div>
        <div class="col-lg-9 col-md-6 col-sm-6 col-xs-12">
            <div class="bt-df-checkbox pull-left">

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="i-checks pull-left">
                            <label>
                                    <input type="radio" <?=$fb_add_button_pixel===false?'checked':''?> value="false" name="pixels.fb.conversion.fireonbutton"> From thank you page <small>(if you don't use custom thank you, enter pixel code!)</small></label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="i-checks pull-left">
                            <label>
                                    <input type="radio" <?=$fb_add_button_pixel===true?'checked':''?> value="true" name="pixels.fb.conversion.fireonbutton"> From button on landing </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <label class="login2 pull-left pull-left-pro">Where to track conversion in TikTok? </label>
        </div>
        <div class="col-lg-9 col-md-6 col-sm-6 col-xs-12">
            <div class="bt-df-checkbox pull-left">

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="i-checks pull-left">
                            <label>
                                    <input type="radio" <?=$tt_add_button_pixel===false?'checked':''?> value="false" name="pixels.tt.conversion.fireonbutton"> From thank you page <small>(if you don't use custom thank you, enter pixel code!)</small></label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="i-checks pull-left">
                            <label>
                                    <input type="radio" <?=$tt_add_button_pixel===true?'checked':''?> value="true" name="pixels.tt.conversion.fireonbutton"> From button on landing </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<div id="b_landings_redirect" style="display:<?=$black_land_action==='redirect'?'block':'none'?>;">
<div class="form-group-inner">
    <div class="row">
        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
            <label class="login2 pull-left pull-left-pro">Redirect URLs: <small>(can be multiple, separated by commas)</small> </label>
        </div>
        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
            <div class="input-group custom-go-button">
                <input type="text" class="form-control" placeholder="https://ya.ru,https://google.com" name="black.landing.redirect.urls" value="<?=is_array($black_land_redirect_urls) ? implode(',',$black_land_redirect_urls) : $black_land_redirect_urls?>">
            </div>
        </div>
    </div>
</div>

<div class="form-group-inner">
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <label class="login2 pull-left pull-left-pro">Select redirect code: </label>
        </div>
        <div class="col-lg-9 col-md-6 col-sm-6 col-xs-12">
            <div class="bt-df-checkbox pull-left">

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="i-checks pull-left">
                            <label>
                                    <input type="radio" <?=$black_land_redirect_type==='301'?'checked':''?> value="301" name="black.landing.redirect.type"> 301 </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="i-checks pull-left">
                            <label>
                                    <input type="radio" <?=$black_land_redirect_type==='302'?'checked':''?> value="302" name="black.landing.redirect.type"> 302 </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="i-checks pull-left">
                            <label>
                                    <input type="radio" <?=$black_land_redirect_type==='303'?'checked':''?> value="303" name="black.landing.redirect.type">  303 </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="i-checks pull-left">
                            <label>
                                    <input type="radio" <?=$black_land_redirect_type==='307'?'checked':''?> value="307" name="black.landing.redirect.type">  307 </label>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
</div>
<div class="form-group-inner">
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <label class="login2 pull-left pull-left-pro">Action when connecting to klo via Javascript (for builders) <small>If black is selected as redirect, then redirect will always be from the builder. If black is local, then only: replacement, iframe are possible</small> </label>
        </div>
        <div class="col-lg-9 col-md-6 col-sm-6 col-xs-12">
            <div class="bt-df-checkbox pull-left">

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="i-checks pull-left">
                            <label>
                                    <input type="radio" <?=$black_jsconnect_action==='redirect'?'checked':''?> value="redirect" name="black.jsconnect"> Redirect </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="i-checks pull-left">
                            <label>
                                    <input type="radio" <?=$black_jsconnect_action==='replace'?'checked':''?> value="replace" name="black.jsconnect"> Replacement </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="i-checks pull-left">
                            <label>
                                    <input type="radio" <?=$black_jsconnect_action==='iframe'?'checked':''?> value="iframe" name="black.jsconnect"> IFrame </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<br>
<hr>
<h4>#3 Metrics and Pixels</h4>
<div class="form-group-inner">
    <div class="row">
        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
            <label class="login2 pull-left pull-left-pro">Google Tag Manager ID: </label>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
            <div class="input-group custom-go-button">
                <input type="text" class="form-control" placeholder=" " name="pixels.gtm.id" value="<?=$gtm_id?>">
            </div>
        </div>
    </div>
</div>
<div class="form-group-inner">
    <div class="row">
        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
            <label class="login2 pull-left pull-left-pro">Yandex.Metrika ID: </label>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
            <div class="input-group custom-go-button">
                <input type="text" class="form-control" placeholder="" name="pixels.ya.id" value="<?=$ya_id?>">
            </div>
        </div>
    </div>
</div>

<h5>#3.1 Facebook Pixel</h5>
<div class="form-group-inner">
    <div class="row">
        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
            <label class="login2 pull-left pull-left-pro">Name of parameter containing Facebook Pixel ID: </label>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
            <div class="input-group custom-go-button">
                <input type="text" class="form-control" placeholder="px" name="pixels.fb.subname" value="<?=$fbpixel_subname?>">
            </div>
        </div>
    </div>
</div>

<div class="form-group-inner">
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <label class="login2 pull-left pull-left-pro">Add PageView event to white pages? </label>
        </div>
        <div class="col-lg-9 col-md-6 col-sm-6 col-xs-12">
            <div class="bt-df-checkbox pull-left">

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="i-checks pull-left">
                            <label>
                                    <input type="radio" <?=$fb_use_pageview===false?'checked':''?> value="false" name="pixels.fb.pageview"> No </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="i-checks pull-left">
                            <label>
                                    <input type="radio" <?=$fb_use_pageview===true?'checked':''?> value="true" name="pixels.fb.pageview"> Yes, add </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="form-group-inner">
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <label class="login2 pull-left pull-left-pro">Add ViewContent event after viewing page within the specified time? </label>
        </div>
        <div class="col-lg-9 col-md-6 col-sm-6 col-xs-12">
            <div class="bt-df-checkbox pull-left">

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="i-checks pull-left">
                            <label>
                                    <input type="radio" <?=$fb_use_viewcontent===false?'checked':''?> value="false" name="pixels.fb.viewcontent.use" onclick="(document.getElementById('b_8-2').style.display='none')"> No </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="i-checks pull-left">
                            <label>
                                    <input type="radio" <?=$fb_use_viewcontent===true?'checked':''?> value="true" name="pixels.fb.viewcontent.use" onclick="(document.getElementById('b_8-2').style.display='block')"> Yes, add </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="b_8-2" style="display:<?=$fb_use_viewcontent===true?'block':'none'?>;">
<div class="form-group-inner">
    <div class="row">
        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
            <label class="login2 pull-left pull-left-pro">Time in seconds after which ViewContent is sent:<br><small>if 0, event will not be triggered</small> </label>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
            <div class="input-group custom-go-button">
                <input type="text" class="form-control" placeholder="30" name="pixels.fb.viewcontent.time" value="<?=$fb_view_content_time?>">
            </div>
        </div>
    </div>
</div>

<div class="form-group-inner">
    <div class="row">
        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
            <label class="login2 pull-left pull-left-pro">Percentage of page scrolling before ViewContent event:<br><small>if 0, event will not be triggered</small> </label>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
            <div class="input-group custom-go-button">
                <input type="text" class="form-control" placeholder="75" name="pixels.fb.viewcontent.percent" value="<?=$fb_view_content_percent?>">
            </div>
        </div>
    </div>
</div>
</div>
<div class="form-group-inner">
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <label class="login2 pull-left pull-left-pro">What event will we use for conversion in Facebook? <small>e.g., Lead or Purchase</small></label>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
            <div class="input-group custom-go-button">
                <input type="text" class="form-control" placeholder="Lead" name="pixels.fb.conversion.event" value="<?=$fb_thankyou_event?>">
            </div>
        </div>
    </div>
</div>
<h5>#3.2 TikTok Pixel</h5>
<div class="form-group-inner">
    <div class="row">
        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
            <label class="login2 pull-left pull-left-pro">Name of parameter containing TikTok Pixel ID: </label>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
            <div class="input-group custom-go-button">
                <input type="text" class="form-control" placeholder="tpx" name="pixels.tt.subname" value="<?=$ttpixel_subname?>">
            </div>
        </div>
    </div>
</div>

<div class="form-group-inner">
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <label class="login2 pull-left pull-left-pro">Add PageView event to white pages? </label>
        </div>
        <div class="col-lg-9 col-md-6 col-sm-6 col-xs-12">
            <div class="bt-df-checkbox pull-left">

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="i-checks pull-left">
                            <label>
                                    <input type="radio" <?=$tt_use_pageview===false?'checked':''?> value="false" name="pixels.tt.pageview"> No </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="i-checks pull-left">
                            <label>
                                    <input type="radio" <?=$tt_use_pageview===true?'checked':''?> value="true" name="pixels.tt.pageview"> Yes, add </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="form-group-inner">
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <label class="login2 pull-left pull-left-pro">Add ViewContent event after viewing page within the specified time? </label>
        </div>
        <div class="col-lg-9 col-md-6 col-sm-6 col-xs-12">
            <div class="bt-df-checkbox pull-left">

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="i-checks pull-left">
                            <label>
                                    <input type="radio" <?=$tt_use_viewcontent===false?'checked':''?> value="false" name="pixels.tt.viewcontent.use" onclick="(document.getElementById('tt_8-2').style.display='none')"> No </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="i-checks pull-left">
                            <label>
                                    <input type="radio" <?=$tt_use_viewcontent===true?'checked':''?> value="true" name="pixels.tt.viewcontent.use" onclick="(document.getElementById('tt_8-2').style.display='block')"> Yes, add </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="tt_8-2" style="display:<?=$tt_use_viewcontent===true?'block':'none'?>;">
<div class="form-group-inner">
    <div class="row">
        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
            <label class="login2 pull-left pull-left-pro">Time in seconds after which ViewContent is sent:<br><small>if 0, event will not be triggered</small> </label>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
            <div class="input-group custom-go-button">
                <input type="text" class="form-control" placeholder="30" name="pixels.tt.viewcontent.time" value="<?=$tt_view_content_time?>">
            </div>
        </div>
    </div>
</div>

<div class="form-group-inner">
    <div class="row">
        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
            <label class="login2 pull-left pull-left-pro">Percentage of page scrolling before ViewContent event:<br><small>if 0, event will not be triggered</small> </label>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
            <div class="input-group custom-go-button">
                <input type="text" class="form-control" placeholder="75" name="pixels.tt.viewcontent.percent" value="<?=$tt_view_content_percent?>">
            </div>
        </div>
    </div>
</div>
</div>
<div class="form-group-inner">
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <label class="login2 pull-left pull-left-pro">What event will we use for conversion in TikTok? <small>e.g., CompletePayment or AddPaymentInfo</small></label>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
            <div class="input-group custom-go-button">
                <input type="text" class="form-control" placeholder="Lead" name="pixels.tt.conversion.event" value="<?=$tt_thankyou_event?>">
            </div>
        </div>
    </div>
</div>
<br>
<hr>
<h4>#4 TDS</h4>
<div class="form-group-inner">
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <label class="login2 pull-left pull-left-pro">TDS mode:</label>
        </div>
        <div class="col-lg-9 col-md-6 col-sm-6 col-xs-12">
            <div class="bt-df-checkbox pull-left">

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="i-checks pull-left">
                            <label>
                                    <input type="radio" <?=$tds_mode==='on'?'checked':''?> value="on" name="tds.mode"> Regular </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="i-checks pull-left">
                            <label>
                                    <input type="radio" <?=$tds_mode==='full'?'checked':''?> value="full" name="tds.mode"> Send all to white </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="i-checks pull-left">
                            <label>
                                    <input type="radio" <?=$tds_mode==='off'?'checked':''?> value="off" name="tds.mode"> Send all to black (TDS disabled) </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="form-group-inner">
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <label class="login2 pull-left pull-left-pro">Send the same user to the same white pages?</label>
        </div>
        <div class="col-lg-9 col-md-6 col-sm-6 col-xs-12">
            <div class="bt-df-checkbox pull-left">

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="i-checks pull-left">
                            <label>
                                    <input type="radio" <?=$save_user_flow===false?'checked':''?> value="false" name="tds.saveuserflow"> No </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="i-checks pull-left">
                            <label>
                                    <input type="radio" <?=$save_user_flow===true?'checked':''?> value="true" name="tds.saveuserflow"> Yes, send </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<br>
<hr>
<h4>#5 Filters</h4>
<div class="form-group-inner">
    <div class="row">
        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
            <label class="login2 pull-left pull-left-pro">List of allowed OS:</label>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="input-group custom-go-button">
                <input type="text" name="tds.filters.allowed.os" class="form-control" placeholder="Android,iOS,Windows,OS X" value="<?=is_array($os_white) ? implode(',',$os_white) : $os_white?>">
            </div>
        </div>
    </div>
</div>

<div class="form-group-inner">
    <div class="row">
        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
            <label class="login2 pull-left pull-left-pro">List of allowed countries: <small>(WW or empty for all)</small></label>
        </div>
        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
            <div class="input-group custom-go-button">
                <input type="text" name="tds.filters.allowed.countries" class="form-control" placeholder="RU,UA" value="<?=is_array($country_white) ? implode(',',$country_white) : $country_white?>">
            </div>
        </div>
    </div>
</div>

<div class="form-group-inner">
    <div class="row">
        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
            <label class="login2 pull-left pull-left-pro">List of allowed languages: <small>(any or empty for all)</small></label>
        </div>
        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
            <div class="input-group custom-go-button">
                <input type="text" name="tds.filters.allowed.languages" class="form-control" placeholder="en,ru,de" value="<?=is_array($lang_white) ? implode(',',$lang_white) : $lang_white?>">
            </div>
        </div>
    </div>
</div>

<div class="form-group-inner">
    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
            <label class="login2 pull-left pull-left-pro">Name of additional blacklist IP file <small>file must be in bases folder</small></label>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <div class="input-group custom-go-button">
                <input type="text" name="tds.filters.blocked.ips.filename" class="form-control" placeholder="blackbase.txt" value="<?=$ip_black_filename?>">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <label class="login2 pull-left pull-left-pro">Additional blacklist IP in CIDR format?</label>
        </div>
        <div class="col-lg-9 col-md-6 col-sm-6 col-xs-12">
            <div class="bt-df-checkbox pull-left">

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="i-checks pull-left">
                            <label>
                                    <input type="radio" <?=$ip_black_cidr===false?'checked':''?> value="false" name="tds.filters.blocked.ips.cidrformat"> No </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="i-checks pull-left">
                            <label>
                                    <input type="radio" <?=$ip_black_cidr===true?'checked':''?> value="true" name="tds.filters.blocked.ips.cidrformat"> Yes </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="form-group-inner">
    <div class="row">
        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
            <label class="login2 pull-left pull-left-pro">Palavras que devem aparecer na URL</label>
        </div>
        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
            <div class="input-group custom-go-button">
                <span class="input-group-btn">
                    <select name="tds.filters.allowed.inurl.mode" class="form-control" style="border-radius: 3px 0 0 3px;">
                        <option value="all" <?=$url_mode === 'all' ? 'selected' : ''?>>Todas as palavras</option>
                        <option value="any" <?=$url_mode === 'any' ? 'selected' : ''?>>Qualquer palavra</option>
                    </select>
                </span>
                <input type="text" name="tds.filters.allowed.inurl" class="form-control" placeholder="key=1,chave=3" value="<?=is_array($url_should_contain) ? implode(',',$url_should_contain) : $url_should_contain?>">
            </div>
        </div>
    </div>
</div>

<div class="form-group-inner">
    <div class="row">
        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
            <label class="login2 pull-left pull-left-pro">Comma-separated words that appear in UserAgent, user will be sent to whitepage</label>
        </div>
        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
            <div class="input-group custom-go-button">
                <input type="text" class="form-control" placeholder="facebook,Facebot,curl,gce-spider,yandex.com/bots,OdklBot" name="tds.filters.blocked.useragents" value="<?=is_array($ua_black) ? implode(',',$ua_black) : $ua_black?>">
            </div>
        </div>
    </div>
</div>

<div class="form-group-inner">
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <label class="login2 pull-left pull-left-pro">Block by ISP (e.g., facebook,google)</label>
        </div>
        <div class="col-lg-9 col-md-6 col-sm-6 col-xs-12">
            <div class="input-group custom-go-button">
                <input type="text" name="tds.filters.blocked.isps" class="form-control" placeholder="facebook,google,yandex,amazon,azure,digitalocean" value="<?=is_array($isp_black) ? implode(',',$isp_black) : $isp_black?>">
            </div>
        </div>
    </div>
</div>

<div class="form-group-inner">
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <label class="login2 pull-left pull-left-pro">Send all requests without referer to whitepage?</label>
        </div>
        <div class="col-lg-9 col-md-6 col-sm-6 col-xs-12">
            <div class="bt-df-checkbox pull-left">

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="i-checks pull-left">
                            <label>
                                    <input type="radio" <?=$block_without_referer===false?'checked':''?> value="false" name="tds.filters.blocked.referer.empty"> No </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="i-checks pull-left">
                            <label>
                                    <input type="radio" <?=$block_without_referer===true?'checked':''?> value="true" name="tds.filters.blocked.referer.empty"> Yes </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="form-group-inner">
    <div class="row">
        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
            <label class="login2 pull-left pull-left-pro">Comma-separated words that appear in referer, user will be sent to whitepage</label>
        </div>
        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
            <div class="input-group custom-go-button">
                <input type="text" name="tds.filters.blocked.referer.stopwords" class="form-control" placeholder="adheart" value="<?=is_array($referer_stopwords) ? implode(',',$referer_stopwords) : $referer_stopwords?>">
            </div>
        </div>
    </div>
</div>

<div class="form-group-inner">
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <label class="login2 pull-left pull-left-pro">Send all users using VPN and Tor to white?</label>
        </div>
        <div class="col-lg-9 col-md-6 col-sm-6 col-xs-12">
            <div class="bt-df-checkbox pull-left">

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="i-checks pull-left">
                            <label>
                                    <input type="radio" <?=$block_vpnandtor===false?'checked':''?> value="false" name="tds.filters.blocked.vpntor"> No </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="i-checks pull-left">
                            <label>
                                    <input type="radio" <?=$block_vpnandtor===true?'checked':''?> value="true" name="tds.filters.blocked.vpntor"> Yes </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<br>
<hr>
<h4>#6 Additional Scripts</h4>
<div class="form-group-inner">
<div class="row">
<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
<label class="login2 pull-left pull-left-pro">What to do with the Back button?</label>
</div>
<div class="col-lg-9 col-md-6 col-sm-6 col-xs-12">
<div class="bt-df-checkbox pull-left">

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="i-checks pull-left">
                <label>
                        <input type="radio" <?=$back_button_action==='off'?'checked':''?> value="off" name="scripts.back.action" onclick="(document.getElementById('b_9').style.display='none')"> Leave default </label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="i-checks pull-left">
                <label>
                        <input type="radio" <?=$back_button_action==='disable'?'checked':''?> value="disable" name="scripts.back.action" onclick="(document.getElementById('b_9').style.display='none')"> Disable (button no longer works)</label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="i-checks pull-left">
                <label>
                        <input type="radio" <?=$back_button_action==='replace'?'checked':''?> value="replace" name="scripts.back.action" onclick="(document.getElementById('b_9').style.display='block')"> Attach redirect to URL</label>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
<div id="b_9" style="display:<?=$back_button_action==='replace'?'block':'none'?>;">
<div class="form-group-inner">
<div class="row">
<div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
<label class="login2 pull-left pull-left-pro">Where to redirect when Back button is clicked?</label>
</div>
<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
<div class="input-group custom-go-button">
    <input type="text" name="scripts.back.value" class="form-control" placeholder="http://ya.ru?pixel={px}&subid={subid}&prelanding={prelanding}" value="<?=$replace_back_address?>">
</div>
</div>
</div>
</div>
</div>
<div class="form-group-inner">
<div class="row">
<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
<label class="login2 pull-left pull-left-pro">Prevent user from selecting and saving text with Ctrl+S, removing context menu?</label>
</div>
<div class="col-lg-9 col-md-6 col-sm-6 col-xs-12">
<div class="bt-df-checkbox pull-left">

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="i-checks pull-left">
                <label>
                        <input type="radio" <?=$disable_text_copy===false?'checked':''?> value="false" name="scripts.disabletextcopy"> No </label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="i-checks pull-left">
                <label>
                        <input type="radio" <?=$disable_text_copy===true?'checked':''?> value="true" name="scripts.disabletextcopy"> Yes, prevent </label>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>

<div class="form-group-inner">
<div class="row">
<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
<label class="login2 pull-left pull-left-pro">Open links on landing page in new window with URL below?</label>
</div>
<div class="col-lg-9 col-md-6 col-sm-6 col-xs-12">
<div class="bt-df-checkbox pull-left">

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="i-checks pull-left">
                <label>
                        <input type="radio" <?=$replace_prelanding===false?'checked':''?> value="false" name="scripts.prelandingreplace.use" onclick="(document.getElementById('b_10').style.display='none')"> No </label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="i-checks pull-left">
                <label>
                        <input type="radio" <?=$replace_prelanding===true?'checked':''?> value="true" name="scripts.prelandingreplace.use" onclick="(document.getElementById('b_10').style.display='block')"> Yes, open  </label>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
<div id="b_10" style="display:<?=$replace_prelanding===true?'block':'none'?>;">
<div class="form-group-inner">
<div class="row">
<div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
<label class="login2 pull-left pull-left-pro">URL that will open in old window:</label>
</div>
<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
<div class="input-group custom-go-button">
    <input type="text" name="scripts.prelandingreplace.url" class="form-control" placeholder="http://ya.ru?pixel={px}&subid={subid}&prelanding={prelanding}" value="<?=$replace_prelanding_address?>">
</div>
</div>
</div>
</div>
</div>


<div class="form-group-inner">
<div class="row">
<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
<label class="login2 pull-left pull-left-pro">Open thank you page of landing in new window with URL below?</label>
</div>
<div class="col-lg-9 col-md-6 col-sm-6 col-xs-12">
<div class="bt-df-checkbox pull-left">

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="i-checks pull-left">
                <label>
                        <input type="radio" <?=$replace_landing===false?'checked':''?> value="false" name="scripts.landingreplace.use" onclick="(document.getElementById('b_1010').style.display='none')"> No </label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="i-checks pull-left">
                <label>
                        <input type="radio" <?=$replace_landing===true?'checked':''?> value="true" name="scripts.landingreplace.use" onclick="(document.getElementById('b_1010').style.display='block')"> Yes, open  </label>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
<div id="b_1010" style="display:<?=$replace_landing===true?'block':'none'?>;">
<div class="form-group-inner">
<div class="row">
<div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
<label class="login2 pull-left pull-left-pro">URL that will open in old window:</label>
</div>
<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
<div class="input-group custom-go-button">
    <input type="text" name="scripts.landingreplace.url" class="form-control" placeholder="http://ya.ru?pixel={px}&subid={subid}&prelanding={prelanding}" value="<?=$replace_landing_address?>">
</div>
</div>
</div>
</div>
</div>

<div class="form-group-inner">
<div class="row">
<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
<label class="login2 pull-left pull-left-pro">Add mask to phone input on LANDING?</label>
</div>
<div class="col-lg-9 col-md-6 col-sm-6 col-xs-12">
<div class="bt-df-checkbox pull-left">

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="i-checks pull-left">
                <label>
                        <input type="radio" <?=$black_land_use_phone_mask===false?'checked':''?> value="false" name="scripts.phonemask.use" onclick="(document.getElementById('b_11').style.display='none')"> No </label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="i-checks pull-left">
                <label>
                        <input type="radio" <?=$black_land_use_phone_mask===true?'checked':''?> value="true" name="scripts.phonemask.use" onclick="(document.getElementById('b_11').style.display='block')"> Yes, add mask </label>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>

<div id="b_11" style="display:<?=$black_land_use_phone_mask===true?'block':'none'?>;">
<div class="form-group-inner">
<div class="row">
<div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
<label class="login2 pull-left pull-left-pro">Enter mask:</label>
</div>
<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
<div class="input-group custom-go-button">
<input type="text" name="scripts.phonemask.mask" class="form-control" placeholder="+421 999 999 999" value="<?=$black_land_phone_mask?>">
</div>
</div>
</div>
</div>
</div>
<div class="form-group-inner">
<div class="row">
<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
<label class="login2 pull-left pull-left-pro">Enable Comebacker script?</label>
</div>
<div class="col-lg-9 col-md-6 col-sm-6 col-xs-12">
<div class="bt-df-checkbox pull-left">

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="i-checks pull-left">
            <label>
                    <input type="radio" <?=$comebacker===false?'checked':''?> value="false" name="scripts.comebacker"> No </label>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="i-checks pull-left">
            <label>
                    <input type="radio" <?=$comebacker===true?'checked':''?> value="true" name="scripts.comebacker"> Yes</label>
        </div>
    </div>
</div>
</div>
</div>
</div>
</div>
<div class="form-group-inner">
<div class="row">
<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
<label class="login2 pull-left pull-left-pro">Enable Callbacker script?</label>
</div>
<div class="col-lg-9 col-md-6 col-sm-6 col-xs-12">
<div class="bt-df-checkbox pull-left">

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="i-checks pull-left">
            <label>
                    <input type="radio" <?=$callbacker===false?'checked':''?> value="false" name="scripts.callbacker"> No </label>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="i-checks pull-left">
            <label>
                    <input type="radio" <?=$callbacker===true?'checked':''?> value="true" name="scripts.callbacker"> Yes</label>
        </div>
    </div>
</div>
</div>
</div>
</div>
</div>
<div class="form-group-inner">
<div class="row">
<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
<label class="login2 pull-left pull-left-pro">Enable script to show pop-up messages about someone purchasing a product?</label>
</div>
<div class="col-lg-9 col-md-6 col-sm-6 col-xs-12">
<div class="bt-df-checkbox pull-left">

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="i-checks pull-left">
            <label>
                    <input type="radio" <?=$addedtocart===false?'checked':''?> value="false" name="scripts.addedtocart"> No </label>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="i-checks pull-left">
            <label>
                    <input type="radio" <?=$addedtocart===true?'checked':''?> value="true" name="scripts.addedtocart"> Yes</label>
        </div>
    </div>
</div>
</div>
</div>
</div>
</div>
<div class="form-group-inner">
<div class="row">
<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
<label class="login2 pull-left pull-left-pro">Use lazy loading (lazy loading) for landing pages/landing pages?</label>
</div>
<div class="col-lg-9 col-md-6 col-sm-6 col-xs-12">
<div class="bt-df-checkbox pull-left">

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="i-checks pull-left">
            <label>
                    <input type="radio" <?=$images_lazy_load===false?'checked':''?> value="false" name="scripts.imageslazyload"> No </label>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="i-checks pull-left">
            <label>
                    <input type="radio" <?=$images_lazy_load===true?'checked':''?> value="true" name="scripts.imageslazyload"> Yes</label>
        </div>
    </div>
</div>
</div>
</div>
</div>
</div>
<br>
<hr>
<h4>#7 Sub-meters</h4>
<p>Klo takes the submeters from the URL string and:<br>
1. If you have a local landing, klo writes the values of the tags into each form on the landing in fields with names to the right<br>
2. If you have a landing in PP, klo appends the values of the tags to the link in PP with names to the right<br>
This way we pass the submeter values to PP so that the correct information appears in the PP article <br>
Plus, this is necessary to pass subid for postback<br>
There are 3 "built-in" tags: <br>
- subid - unique identifier of the user, created when the user lands on black, stored in cookies<br>
- prelanding - name of the prelanding folder<br>
- landing - name of the landing folder<br><br />
Example: <br>
your URL string was http://xxx.com?cn=MyCampaign<br>
you wrote in settings: cn => utm_campaign <br />
a <pre>&lt;input type="hidden" name="utm_campaign" value="MyCampaign"/&gt;</pre> will be added to the form on the landing
</p>
<div id="subs_container">
<?php  for ($i=0;$i<count($sub_ids);$i++){?>
<div class="form-group-inner subs">
<div class="row">
<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
     <div class="input-group">
        <input type="text" class="form-control" placeholder="subid" value="<?=$sub_ids[$i]["name"]?>" name="subids[<?=$i?>][name]">
    </div>
</div>
<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
    <p>=></p>
</div>
<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
    <div class="input-group custom-go-button">
        <input type="text" class="form-control" placeholder="sub_id" value="<?=$sub_ids[$i]["rewrite"]?>" name="subids[<?=$i?>][rewrite]">
    </div>
</div>
<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
    <a href="javascript:void(0)" class="remove-sub-item btn btn-sm btn-primary">Remove</a>
</div>
</div>
</div>
<?php }?>
</div>
<a id="add-sub-item" class="btn btn-sm btn-primary" href="javascript:;">Add</a>

<br>
<hr>
<h4>#8 Statistics</h4>
<div class="form-group-inner">
<div class="row">
<div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
<label class="login2 pull-left pull-left-pro">Password for admin panel: <br><small>add as: /admin?password=xxxxx</small></label>
</div>
<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
<div class="input-group custom-go-button">
<input type="password" name="statistics.password" class="form-control" placeholder="12345" value="<?=$log_password?>">
</div>
</div>
</div>
</div>
<div class="form-group-inner">
<div class="row">
<div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
<label class="login2 pull-left pull-left-pro">Time zone for displaying stats</label>
</div>
<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
<div class="input-group custom-go-button">
<?=select_timezone('statistics.timezone',$stats_timezone) ?>
</div>
</div>
</div>
</div>
<br/>
<div class="form-group-inner">
<div class="row">
<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
<label class="login2 pull-left pull-left-pro">Settings for displaying tables by submeters in stats:</label>
<br/>
<br/>
<p>Left side is the name of the tag that klo will take from the URL.</p>
<p>Right side is the name of the table in English where all values of the selected tag and their stats will be shown: clicks, conversions</p>
</div>
</div>

<div id="stats_subs_container">
<?php  for ($i=0;$i<count($stats_sub_names);$i++){?>
<div class="form-group-inner stats_subs">
<div class="row">
<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
     <div class="input-group">
        <input type="text" class="form-control" placeholder="camp" value="<?=$stats_sub_names[$i]["name"]?>" name="statistics.subnames[<?=$i?>][name]">
    </div>
</div>
<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
    <p>=></p>
</div>
<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
    <div class="input-group custom-go-button">
        <input type="text" class="form-control" placeholder="Campaigns" value="<?=$stats_sub_names[$i]["value"]?>" name="statistics.subnames[<?=$i?>][value]">
    </div>
</div>
<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
    <a href="javascript:void(0)" class="remove-stats-sub-item btn btn-sm btn-primary">Remove</a>
</div>
</div>
</div>
<?php }?>
</div>
<a id="add-stats-sub-item" class="btn btn-sm btn-primary" href="javascript:;">Add</a>
</div>
<br>
<hr>
<h4>#9 Postbacks</h4>
<div class="form-group-inner">
<div class="row">
<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
<label class="login2 pull-left pull-left-pro">Here you need to write the statuses of leads as they are sent to you by the PP postback:</label>
</div>
</div>
</div>
<div class="form-group-inner">
<div class="row">
<div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
<label class="login2 pull-left pull-left-pro">Lead</label>
</div>
<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
<div class="input-group custom-go-button">
<input type="text" name="postback.lead" class="form-control" placeholder="Lead" value="<?=$lead_status_name?>">
</div>
</div>
</div>
</div>

<div class="form-group-inner">
<div class="row">
<div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
<label class="login2 pull-left pull-left-pro">Purchase</label>
</div>
<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
<div class="input-group custom-go-button">
<input type="text" name="postback.purchase" class="form-control" placeholder="Purchase" value="<?=$purchase_status_name?>">
</div>
</div>
</div>
</div>

<div class="form-group-inner">
<div class="row">
<div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
<label class="login2 pull-left pull-left-pro">Reject</label>
</div>
<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
<div class="input-group custom-go-button">
<input type="text" name="postback.reject" class="form-control" placeholder="Reject" value="<?=$reject_status_name?>">
</div>
</div>
</div>
</div>

<div class="form-group-inner">
<div class="row">
<div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
<label class="login2 pull-left pull-left-pro">Trash</label>
</div>
<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
<div class="input-group custom-go-button">
<input type="text" name="postback.trash" class="form-control" placeholder="Trash" value="<?=$trash_status_name?>">
</div>
</div>
</div>
</div>
<div class="form-group-inner">
<div class="row">
<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
<label class="login2 pull-left pull-left-pro">S2S postback settings:</label>
<br/>
</div>
</div>

<div id="s2s_container">
<?php  for ($i=0;$i<count($s2s_postbacks);$i++){?>
<div class="form-group-inner s2s">
<div class="row">
<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
    <label class="login2 pull-left pull-left-pro">URL:</label>
    <br/><br/>
<p>Inside the postback URL, you can use the following macros:
{subid}, {prelanding}, {landing}, {px}, {domain}, {status}</p>
</div>
<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
     <div class="input-group">
        <input type="text" class="form-control" placeholder="https://s2s-postback.com" value="<?=$s2s_postbacks[$i]["url"]?>" name="postback.s2s[<?=$i?>][url]">
    </div>
</div>
<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
    <a href="javascript:void(0)" class="remove-s2s-item btn btn-sm btn-primary">Remove</a>
</div>
</div>
<div class="row">
<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
    <label class="login2 pull-left pull-left-pro">Method of sending postback:</label>
</div>
<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
     <div class="input-group">
        <select class="form-control" name="postback.s2s[<?=$i?>][method]">
            <option value="GET" <?=($s2s_postbacks[$i]["method"]==="GET"?' selected':'')?>>GET</option>
            <option value="POST"<?=($s2s_postbacks[$i]["method"]==="POST"?' selected':'')?>>POST</option>
        </select>
    </div>
</div>
</div>
<div class="row">
<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
    <label class="login2 pull-left pull-left-pro">Events for which postback will be sent:</label>
</div>
<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
    <br/>
    <br/>
        <?php
            function s2s_postback_contains($conv_event,$s2s_postback){
                if (!array_key_exists("events",$s2s_postback)) return false;
                return in_array($conv_event,$s2s_postback["events"]);
            }
        ?>
         <label class="form-check-input">
        <input type="checkbox" class="form-check-input" name="postback.s2s[<?=$i?>][events][]" value="Lead"<?=(s2s_postback_contains("Lead",$s2s_postbacks[$i])?' checked':'')?>>Lead</label>&nbsp;&nbsp;
         <label class="form-check-input">
        <input type="checkbox" class="form-check-input" name="postback.s2s[<?=$i?>][events][]" value="Purchase"<?=(s2s_postback_contains("Purchase",$s2s_postbacks[$i])?' checked':'')?>>Purchase</label>&nbsp;&nbsp;
         <label class="form-check-input">
        <input type="checkbox" class="form-check-input" name="postback.s2s[<?=$i?>][events][]" value="Reject"<?=(s2s_postback_contains("Reject",$s2s_postbacks[$i])?' checked':'')?>>Reject</label>&nbsp;&nbsp;
         <label class="form-check-input">
        <input type="checkbox" class="form-check-input" name="postback.s2s[<?=$i?>][events][]" value="Trash"<?=(s2s_postback_contains("Trash",$s2s_postbacks[$i])?' checked':'')?>>Trash
</label>
</div>
</div>
<?php }?>
</div>
<a id="add-s2s-item" class="btn btn-sm btn-primary" href="javascript:;">Add</a>
</div>

<hr>
<div class="form-group-inner">
<div class="login-btn-inner">
<div class="row">
<div class="col-lg-3"></div>
<div class="col-lg-9">
<div class="login-horizental cancel-wp pull-left">
    <button class="btn btn-sm btn-primary" type="submit"><strong>Save settings</strong></button>
</div>
</div>
</div>
</div>
</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</form>

</div>
<!-- jquery
    ============================================ -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<!-- bootstrap JS
    ============================================ -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!--cloneData-->
<script src="js/cloneData.js"></script>
<script>
$('#add-domain-item').cloneData({
    mainContainerId: 'white_domainspecific',
    cloneContainer: 'white',
    removeButtonClass: 'remove-domain-item',
    maxLimit: 5,
    minLimit: 1,
    removeConfirm: false
});

$('#add-sub-item').cloneData({
    mainContainerId: 'subs_container',
    cloneContainer: 'subs',
    removeButtonClass: 'remove-sub-item',
    maxLimit: 10,
    minLimit: 1,
    removeConfirm: false
});

$('#add-stats-sub-item').cloneData({
    mainContainerId: 'stats_subs_container',
    cloneContainer: 'stats_subs',
    removeButtonClass: 'remove-stats-sub-item',
    maxLimit: 10,
    minLimit: 1,
    removeConfirm: false
});

$('#add-s2s-item').cloneData({
    mainContainerId: 's2s_container',
    cloneContainer: 's2s',
    removeButtonClass: 'remove-s2s-item',
    maxLimit: 5,
    minLimit: 1,
    removeConfirm: false
});
</script>
<!-- meanmenu JS
    ============================================ -->
<script src="js/jquery.meanmenu.js"></script>
<!-- sticky JS
    ============================================ -->
<script src="js/jquery.sticky.js"></script>
<!-- metisMenu JS
    ============================================ -->
<script src="js/metisMenu/metisMenu.min.js"></script>
<script src="js/metisMenu/metisMenu-active.js"></script>
<!-- plugins JS
    ============================================ -->
<script src="js/plugins.js"></script>
<!-- main JS
    ============================================ -->
<script src="js/main.js"></script>
</body>

<?php
function select_timezone($selectname,$selected = '') {
$zones = timezone_identifiers_list();
$select= "<select name='".$selectname."' class='form-control'>";
foreach($zones as $zone)
{
    $tz=new DateTimeZone($zone);
    $offset=$tz->getOffset(new DateTime)/3600;
    $select .='<option value="'.$zone.'"';
    $select .= ($zone == $selected ? ' selected' : '');
    $select .= '>'.$zone.' '.$offset.'</option>';
}  
$select.='</select>';
return $select;
}
?>

</html>
