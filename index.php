<?php
#Back-end initialization
require_once 'functions.php';
require_once 'api-autoloader.php';
require_once 'config.php';
use Viion\Lodestone\LodestoneAPI;
misdircreate();
$curtime=time();
$api = new LodestoneAPI();
if (isset($_GET['basic'])){
	$api->useBasicParsing();
}
$maint = false;
$refreshpage = false;
$fcranks=json_decode(file_get_contents('fcranks.json'), true);
#Checking age of HTML cache and removing it if it's old enough
$cacheage=$curtime;

if (!file_exists("./cache/freecompany.json") or !file_exists('cache/members.json')) {
	echo "No cache exists. Need to grab data<br><iframe src=\"./update.php\" width=\"400px\" height=\"20px\" frameborder=\"0\" allowtransparency seamless scrolling=\"auto\">You do not like iframes? =(</iframe>";
} else {
	$cacheage=filemtime("./cache/freecompany.json");
}

$fcdata = json_decode(file_get_contents('cache/freecompany.json'), true);
$members = $fcdata['members'];
$roles = $fcdata['roles'];
$focus = $fcdata['focus'];

$memberstats = json_decode(file_get_contents('cache/members.json'), true);
foreach ($memberstats as $key => $row) {
    $fclvlsort[$key]  = $row['fc']['ranklvl'];
    $joinedsort[$key] = date("Ymd", $row['joined']);
    $totallvlsort[$key] = $row['levels']['curr']['totallvl'];
}
array_multisort($fclvlsort, SORT_ASC, $joinedsort, SORT_ASC, $totallvlsort, SORT_DESC, $memberstats);


#Preparing HTML output of basic Free Company information
if ($refreshpage == true) {
	echo "Generating general HTML...<br>";
	ob_flush();
	flush();
}
$fcpage = $fcpage . "
<head>
<link rel=\"stylesheet\" type=\"text/css\" href=\"style.css\">
</head>
<title>".$fcdata['name']."</title>
<meta property=\"og:type\"   content=\"website\" /> 
<meta property=\"og:url\"    content=\"http://".$_SERVER['SERVER_NAME']."/mogst/\" />
<meta property=\"og:title\"  content=\"".$fcdata['name']."\" />
<meta property=\"og:description\"  content=\"Tracker page for ".$fcdata['name']." free company\" />
<meta property=\"og:image\"  content=\"http://".$_SERVER['SERVER_NAME']."/mogst/cache/emblem/3.png\" />
<script src=\"Chart.js\"></script>
<script src=\"jquery-3.1.0.min.js\"></script>
<script>
$(document).ready ( function () {
	$(document).on ('click', '.intlink', function () {
		return false;
	});
	$(\"#search_input\").on('change paste input', function(){
		var search = document.getElementById('search_input');
		var elements = Array.from(document.getElementsByTagName('img'));
		elements.forEach(function(entry) {
	    		if (entry.className == \"membertd\") {
				var entryid = entry.id.toUpperCase();
				if (entryid.includes(search.value.toUpperCase()) && search.value != \"\") {
					entry.style.boxShadow = \"".$avashadow."\";
					entry.style.zIndex = \"10\";
					entry.style.transform = \"scale(1.2)\";
				} else {
					entry.style.boxShadow = \"\";
					entry.style.zIndex = \"\";
					entry.style.transform = \"scale(1)\";
				}
			}
			if (entry.className == \"membertdover\") {
				var entryid = entry.id.toUpperCase();
				if (entryid.includes(search.value.toUpperCase()) && search.value != \"\") {
					entry.style.zIndex = \"10\";
					entry.style.transform = \"scale(1.2)\";
				} else {
					entry.style.zIndex = \"\";
					entry.style.transform = \"scale(1)\";
				}
			}
		});
	});
});
function xhrSuccess () { this.callback.apply(this, this.arguments); }
function xhrError () { console.error(this.statusText); }
function loadFile (sURL, fCallback /*, argumentToPass1, argumentToPass2, etc. */) {
  var oReq = new XMLHttpRequest();
  oReq.callback = fCallback;
  oReq.arguments = Array.prototype.slice.call(arguments, 2);
  oReq.onload = xhrSuccess;
  oReq.onerror = xhrError;
  oReq.open(\"get\", sURL, true);
  oReq.send(null);
}
function shadowlnks(search) {
	var e = document.getElementById('lnk' + search + 'img');
	e.className = \"hvr-pulse\";
	var e = document.getElementById('lnk' + search + 'text');
	e.style.textShadow = \"".$hovershadow."\";
}
function shadowlnkh(search) {
	var e = document.getElementById('lnk' + search + 'img');
	e.className = \"\";
	var e = document.getElementById('lnk' + search + 'text');
	e.style.textShadow = \"\";
}
function showtip(rank) {
	var e = document.getElementById('fcranktip');
	if (e.style.display == \"none\") {
		e.style.display = \"\";
		e.innerHTML = '<img width=\"252\" height=\"252\" src=\"./img/loading.gif\">';
		showtipload(rank);
	} else {
		e.innerHTML = \"\";
		e.style.display = \"none\";
	}
}
function showtipload(rank) {
	loadFile('./fcranks.php?fcname=' + rank, showtipcb);
}
function showtipcb() {
	var e = document.getElementById('fcranktip');
	e.innerHTML = this.responseText;
}
function showchar(memberid) {
	var e = document.getElementById('chardetail');
	e.innerHTML = '<table width=\"872px\" class=\"memberstbl\"><tr><td><img width=\"252\" height=\"252\" src=\"./img/loading.gif\"></td></tr></table>';
	if (e.style.display == \"none\") {
		e.style.display = \"\";
	}
	loadFile('./chardet.php?id=' + memberid, showcharcb);
}
function showcharcb() {
	var e = document.getElementById('chardetail');
	e.innerHTML = this.responseText;
	var e = document.getElementById('fcranktip');
}
</script>
<div name=\"main\" style=\"margin: auto;width: 100%;text-align:center;\">
<div style=\"align:center;\">
<span style=\"display: inline-block;position: relative;text-align:right;vertical-align:top;\">We are</span>
<a href=\"http://eu.finalfantasyxiv.com/lodestone/freecompany/".$fcid."/\"><span style=\"display: inline-block;position: relative;margin-left: -65px;margin-top:15px;width: 68px;height: 68px;\">
<img style=\"position: absolute; top: 0; left: 0;\" src=\"".imgcaching($fcdata['emblum'][0], "emblem/0", $emblemupd)."\" height=\"64\" width=\"64\">
<img style=\"position: absolute; top: 0; left: 0;\" src=\"".imgcaching($fcdata['emblum'][1], "emblem/1", $emblemupd)."\" height=\"64\" width=\"64\">
<img style=\"position: absolute; top: 0; left: 0;\" src=\"".imgcaching($fcdata['emblum'][2], "emblem/2", $emblemupd)."\" height=\"64\" width=\"64\">
</span>
<span style=\"display: inline-block;position: relative;height: 68px;text-align:center;vertical-align:middle;margin-left:-10px;margin-right:-90px;margin-top:-70px;margin-bottom:20px;".$fcnamecss."\">".$fcdata['name']."</span></a>
<span style=\"height: 68px;text-align:right;vertical-align:bottom;\">from <span style=\"".$serverncss."\">".$fcdata['server']."</span>
</div>
<div style=\"align:center;\"><i>".$fcdata['slogan']."</i></div>
<div style=\"align:center;\"><br><br><table style=\"border: 0px;\" class=\"memberstbl\"><tr><td style=\"border: 0px;padding-right:5px;\">We participate in</td><td style=\"border: 0px;padding-left:5px;\">We are looking for</td></tr><tr><td style=\"border: 0px;padding-right:5px;\">";
foreach($focus as $interest) {
	if ($interest['active'] == 1) {
		$fcpage = $fcpage . "<span><img height=\"32\" width=\"32\" src=\"./img/focus/".imgnamesane($interest['name']).".png\" title=\"".$interest['name']."\"></span>";
	}
}	
$fcpage = $fcpage . "</td><td style=\"border: 0px;padding-left:5px;\">";
foreach($roles as $role) {
	if ($role['active'] == 1) {
		$fcpage = $fcpage . "<span><img height=\"32\" width=\"32\" src=\"./img/roles/".imgnamesane($role['name']).".png\" title=\"".$role['name']."\"></span>";
	}
}
$fcpage = $fcpage . "</td></tr></table></div><div style=\"align:center;\"><br>We were found on <span style=\"".$formeddate."\">".date("d F Y" ,$fcdata['formed'])."</span> as affiliate of <span style=\"";

if (strtolower($fcdata['company']) == strtolower("Order of the Twin Adder")) {
	$fcpage = $fcpage . $gctwinadder;
} elseif (strtolower($fcdata['company']) == strtolower("Maelstrom")) {
	$fcpage = $fcpage . $gcmaelstorm;
} elseif (strtolower($fcdata['company']) == strtolower("Immortal Flames")) {
	$fcpage = $fcpage . $gcimmortalflames;
}
$lastranks=getlastranks($fcdata['ranking']['weekly']);

$fcpage = $fcpage . "\">".$fcdata['company']."</span><br><br>
We live in <span style=\"".$estatename."\">".$fcdata['estate']['zone']."</span> <span style=\"".$estateaddress."\">(".$fcdata['estate']['address'].")</span> and rank <span style=\"".$ranknum."\" onmouseover=\"document.getElementById('ranking').style.display = 'inline-block';\" onmouseout=\"document.getElementById('ranking').style.display = 'none';\">".$fcdata['ranking']['weekly']."<sup>[?]</sup><div id=\"ranking\" style=\"position:absolute;z-index: 100;background-color:gray;display:none;width:300px;height:200px;\"><canvas id=\"myChart\"></canvas></div>
</span> among the companies <span style=\"".$rankmax."\">(".maxValueInArray($lastranks, "rank")." min, ".minValueInArray($lastranks, "rank")." max)</span>
<br><br>
We have <b><span style=\"".$membercount."\">".$fcdata['memberCount']."</span></b> members and counting. Want to join? Search for those with <span style=\"".$membertag."\">".$fcdata['tag']."</span> tag on them.<br>
</div>";

$fcpage = $fcpage . "
<script>
var ctx = document.getElementById(\"myChart\");
var context = ctx.getContext('2d');
var data = {
    labels: [";

$i = 1;
$len = count($lastranks);
foreach($lastranks as $rank) {
	if ($i == $len) {
		$fcpage = $fcpage . "\"".date("d.m.Y" ,$rank['date'])."\"";
	} else {
		$fcpage = $fcpage . "\"".date("d.m.Y" ,$rank['date'])."\",";
	}
	$i++;
}

$fcpage = $fcpage . "],
    datasets: [
        {
            label: \"Ranking (ignore '-' sign)\",
            fill: false,
            lineTension: 0.1,
            backgroundColor: \"rgba(75,192,192,1)\",
            borderColor: \"rgba(75,192,192,1)\",
            borderCapStyle: 'butt',
            borderDash: [],
            borderDashOffset: 0.0,
            borderJoinStyle: 'miter',
            pointBorderColor: \"rgba(75,192,192,1)\",
            pointBackgroundColor: \"#fff\",
            pointBorderWidth: 1,
            pointHoverRadius: 5,
            pointHoverBackgroundColor: \"rgba(75,192,192,1)\",
            pointHoverBorderColor: \"rgba(220,220,220,1)\",
            pointHoverBorderWidth: 2,
            pointRadius: 1,
            pointHitRadius: 10,
            data: [";

$i = 1;
$len = count($lastranks);
foreach($lastranks as $rank) {
	if ($i == $len) {
		$fcpage = $fcpage . "\"-".$rank['rank']."\"";
	} else {
		$fcpage = $fcpage . "\"-".$rank['rank']."\",";
	}
	$i++;
}
$fcpage = $fcpage . "],
        }
    ]
};
var myChart = new Chart(ctx, {
    type: 'line',
    data: data,
    options: {
	showLines: true,
	stacked: true,
        xAxes: [{
            display: false
        }]
    }
});
</script>

";
$fcpage = $fcpage . "<iframe src=\"./update.php\" width=\"400px\" height=\"20px\" frameborder=\"0\" allowtransparency seamless scrolling=\"auto\">You do not like iframes? =(</iframe>";
$fcpage = $fcpage . "<div><input autofocus alt=\"Search\" id=\"search_input\" placeholder=\"Type Name or ID to highlight a member\" size=\"40px\"><br><br></div><div id=\"newtable\"><div style=\"display: none;\" id=\"chardetail\"></div>";


$fcpage = $fcpage . "<table class=\"memberstbl\">";
$tdnum=1;
foreach ($memberstats as $memberid=>$member) {
	if (!is_null($member['bio']['name'])) {
		$id = $member['id'];
		include("./chardet.php");
		if ($tdnum == 1) {
			$fcpage = $fcpage . "<tr>";
		}
		$fcpage = $fcpage . "<td><span onclick=\"showchar(".$member['id'].")\" title=\"".$member['fc']['rank']." ".$member['bio']['name']."\" style=\"display: inline-block;position: relative;width: 64px;height: 64px;cursor:pointer;\"><a class=\"intlink\" href=\"chardet.php?id=".$member['id']."\">
					<img class=\"membertd\" id=\"".$member['bio']['name']." ".$member['id']."\" style=\"position: absolute; top: 0; left: 0;\" width=\"64px\" height=\"64px\" src=\"".$member['bio']['avatar']."\">
					<img class=\"membertdover\" id=\"".$member['bio']['name']." ".$member['id']."\" style=\"position: absolute; top: 45; left: 45;\" src=\"./cache/ranks/".imgnamesane($member['fc']['rank']).".png\">";
					if ($member['fc']['ranklvl'] == 6 && $curtime - $member['fc']['ranklvlupd'] > 5184000) {
						$fcpage = $fcpage . "<img class=\"membertdover\" id=\"".$member['bio']['name']." ".$member['id']."\" style=\"position: absolute; top: 0; left: 0; opacity: 0.5; filter: alpha(opacity=50);\" width=\"64px\" height=\"64px\" src=\"img/delete.png\">";
					} else {
						if ($member['fc']['wronprom'] == true && $member['fc']['rankover'] == false) {
							$fcpage = $fcpage . "<img class=\"membertdover\" id=\"".$member['bio']['name']." ".$member['id']."\" style=\"position: absolute; top: 0; left: 0; opacity: 0.5; filter: alpha(opacity=50);\" width=\"64px\" height=\"64px\" src=\"img/attention.png\">";
						} else {
							if ($member['fc']['nextprom'] != "") {
								$fcpage = $fcpage . "<img class=\"membertdover\" id=\"".$member['bio']['name']." ".$member['id']."\" style=\"position: absolute; top: 0; left: 0; opacity: 0.5; filter: alpha(opacity=50);\" width=\"64px\" height=\"64px\" src=\"img/rankup.png\">";
							} else {
								if ($member['fc']['ranklvl'] > $member['fc']['ranklvlprev'] && $curtime - $member['fc']['ranklvlupd'] < 604799) {
									$fcpage = $fcpage . "<img class=\"membertdover\" id=\"".$member['bio']['name']." ".$member['id']."\" style=\"position: absolute; top: 0; left: 0; opacity: 0.5; filter: alpha(opacity=50);\" width=\"64px\" height=\"64px\" src=\"img/lvldown.png\">";
								} elseif ($member['fc']['ranklvl'] < $member['fc']['ranklvlprev'] && $curtime - $member['fc']['ranklvlupd'] < 604799) {
									$fcpage = $fcpage . "<img class=\"membertdover\" id=\"".$member['bio']['name']." ".$member['id']."\" style=\"position: absolute; top: 0; left: 0; opacity: 0.5; filter: alpha(opacity=50);\" width=\"64px\" height=\"64px\" src=\"img/lvlup.png\">";
								}
							}
						}
					}
					$fcpage = $fcpage . "</a></span></td>";
		if ($tdnum == 13) {
			$fcpage = $fcpage . "</tr>";
			$tdnum = 0;
		}
		$tdnum++;
	}
}

$fcpage = $fcpage . "</table></div><div style=\"font-size:xx-small;\"><br><div style=\"font-size:xx-small;\">Source code of the page can be downloaded <a target=\"_blank\" href=\"zip.php\">here</a></div><div style=\"font-size:xx-small;\">Coded by &copy; <a href=\"http://simbiat.ru\" target=\"_blank\">Simbiat</a> with use of &copy; <a href=\"https://github.com/viion/XIVPads-LodestoneAPI\" target=\"_blank\">XIVSync</a></div></div>";
//@file_put_contents("./cache/ffxiv_fcpage.txt", $fcpage);
unset($api);

echo $fcpage;
?>