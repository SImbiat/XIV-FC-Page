<?php
#Back-end initialization
require_once 'functions.php';
require_once 'api-autoloader.php';
require_once 'config.php';
if (empty($_GET['fcid'])) {
	#Function for Company search
	$fcpage = "<head><link rel=\"stylesheet\" type=\"text/css\" href=\"./defaults/style.css\"></head><title>FFXIV Free Company Tracker</title>
	<script src=\"jquery-3.1.0.min.js\"></script>
	<script>
		$(document).ready ( function () {
			$(document).on ('click', '.intlink', function () {
				return false;
			});
			$(\"#search_input\").on('change paste input', function(){
				var search = document.getElementById('search_input');
				var elements = Array.from(document.getElementsByTagName('span'));
				elements.forEach(function(entry) {
					var entryid = entry.id.toUpperCase();
					if (entryid.includes(search.value.toUpperCase()) && search.value != \"\") {
						entry.style.textShadow = \"".$avashadow."\";
						entry.style.zIndex = \"10\";
						entry.style.transform = \"scale(1.2)\";
					} else {
						entry.style.textShadow = \"\";
						entry.style.zIndex = \"\";
						entry.style.transform = \"scale(1)\";
					}
				});
			});
			$(\"#search_input\").keyup(function (e) {
		   		if (e.keyCode == 13) {
		        		newcompany();
		    		}
			});
		});
		function newcompany() {
			var field = document.getElementById('search_input');
			var id = field.value;
			if (id != \"\" && id.match(/^[0-9]+$/) != null) {
				window.location.href = \"";
				if ($modrw == true) {
					$fcpage = $fcpage . "./\" + id";
				} else {
					$fcpage = $fcpage . "./index.php?fcid=\" + id";
				}
				$fcpage = $fcpage . ";
			} else {
				alert(\"Only numbers (IDs) are accepted!\");
			}
		};
	</script>
	<div style=\"text-align:center\">";
	#Company Search field
	$fcpage = $fcpage . "<input autofocus alt=\"Search\" id=\"search_input\" placeholder=\"Type Name or ID to highlight a Free Company. Press Enter to open Company page or register it.\" size=\"95px\"><br><br><input id=\"Register\ type=\"button\" value=\"Register\" onclick=\"newcompany();\" style=\"background:#aaa; border:0 none; cursor:pointer; -webkit-border-radius: 5px; border-radius: 5px;\"/><br><br>";
	#Showing Free Companies, if any
	if (!is_dir("./cache")) {
		$fcpage = $fcpage . "No Free Companes are registered";
	} else {
		$fcs = scandir("./cache");
		if (!is_null($fcs[2])) {
			$fcpage = $fcpage . "Registered Free Companies:<br>";
			foreach ($fcs as $fcdir) {
				if ($fcdir != "." && $fcdir != ".." && file_exists("./cache/".$fcdir."/members.json") && file_exists("./cache/".$fcdir."/fc.json")) {
					$fcdirname=json_decode(file_get_contents("./cache/".$fcdir."/fc.json"), true);
					$fcpage = $fcpage . "<span id=\"".$fcdirname['name']." ".$fcdir."\">";
					if ($modrw == true) {
						$fcpage = $fcpage . "<a href=\"./".$fcdir;
					} else {
						$fcpage = $fcpage . "<a href=\"./index.php?fcid=".$fcdir;
					}
					$fcpage = $fcpage . "\"><span style=\"display: inline-block;position: relative;width: 16px;height: 16px;\">
								<img style=\"position: absolute; top: 0; left: 0;\" src=\"".$fcdirname['emblum'][0]."\" height=\"16\" width=\"16\">
								<img style=\"position: absolute; top: 0; left: 0;\" src=\"".$fcdirname['emblum'][1]."\" height=\"16\" width=\"16\">
								<img style=\"position: absolute; top: 0; left: 0;\" src=\"".$fcdirname['emblum'][2]."\" height=\"16\" width=\"16\">
							</span> ".$fcdirname['name']."</a> (Updated on ".date("d F Y H:i" ,filemtime("./cache/".$fcdir."/fc.json")).")</span><br>";
				}
				
			}
		} else {
			$fcpage = $fcpage . "No Free Companes are registered";
		}
	}
	$fcpage = $fcpage . "</div>";
	echo $fcpage;
	exit;
} else {
	$fcid = $_GET['fcid'];
	misdircreate($fcid);
	$fcconfig=json_decode(file_get_contents("./cache/".$fcid."/config.json"), true);
}
use Viion\Lodestone\LodestoneAPI;
$curtime=time();
$api = new LodestoneAPI();
if (isset($_GET['basic'])){
	$api->useBasicParsing();
}
$maint = false;
$refreshpage = false;

#Check if fcranks exists
if (!file_exists("./cache/".$fcid."/fcranks.json")) {
	Echo "Free Company Ranks description is missing. Can't continue without it";
	exit;
}

#Get the file with FC ranks descriptions
$fcranks=json_decode(file_get_contents("./cache/".$fcid."/fcranks.json"), true);

#Checking company cache. If it's missing - request it. If not - load existing data
$cacheage=$curtime;
if (!file_exists("./cache/".$fcid."/fc.json") or !file_exists("./cache/".$fcid."/members.json")) {
	if ($modrw == true) {
		echo "<head>
<link rel=\"stylesheet\" type=\"text/css\" href=\"./defaults/style.css\">
</head>
<title>FFXIV Free Company Tracker</title>No cache exists. Need to grab data<br><iframe src=\"./update/".$fcid."\" width=\"400px\" height=\"20px\" frameborder=\"0\" allowtransparency seamless scrolling=\"auto\">You do not like iframes? =(</iframe>";
	} else {
		echo "<head>
<link rel=\"stylesheet\" type=\"text/css\" href=\"./defaults/style.css\">
</head>
<title>FFXIV Free Company Tracker</title>No cache exists. Need to grab data<br><iframe src=\"./update.php?fcid=".$fcid."\" width=\"400px\" height=\"20px\" frameborder=\"0\" allowtransparency seamless scrolling=\"auto\">You do not like iframes? =(</iframe>";
	}
	exit;
} else {
	$cacheage=filemtime("./cache/".$fcid."/fc.json");
}

#Setting main variables
$fcdata = json_decode(file_get_contents("./cache/".$fcid."/fc.json"), true);
$members = $fcdata['members'];
$roles = $fcdata['roles'];
$focus = $fcdata['focus'];
$memberstats = json_decode(file_get_contents("./cache/".$fcid."/members.json"), true);

#Sorting members by FC rank, join date and total level
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
<link rel=\"stylesheet\" type=\"text/css\" href=\"./cache/".$fcid."/style.css\">
</head>
<title>".$fcdata['name']."</title>
<meta property=\"og:type\"   content=\"website\" /> 
<meta property=\"og:url\"    content=\"http://".$_SERVER['SERVER_NAME']."/mogst/\" />
<meta property=\"og:title\"  content=\"".$fcdata['name']."\" />
<meta property=\"og:description\"  content=\"Tracker page for ".$fcdata['name']." free company\" />
<meta property=\"og:image\"  content=\"http://".$_SERVER['SERVER_NAME']."/mogst/cache/emblem/".$fcid."-2.png\" />
<script src=\"Chart.js\"></script>
<script src=\"jquery-3.1.0.min.js\"></script>
<script>
$(document).ready ( function () {
	$(document).on ('click', '.intlink', function () {
		return false;
	});";

#JavaScript to highlight members on search
$fcpage = $fcpage . "
	$(\"#search_input\").on('change paste input', function(){
		var search = document.getElementById('search_input');
		var elements = Array.from(document.getElementsByTagName('img'));
		elements.forEach(function(entry) {
	    		if (entry.classList.contains(\"membertd\")) {
				var entryid = entry.id.toUpperCase();
				if (entryid.includes(search.value.toUpperCase()) && search.value != \"\") {
					entry.classList.add(\"avamark\");
				} else {
					entry.classList.remove(\"avamark\");
				}
			}
			if (entry.classList.contains(\"membertdover\")) {
				var entryid = entry.id.toUpperCase();
				if (entryid.includes(search.value.toUpperCase()) && search.value != \"\") {
					entry.classList.add(\"avamark2\");
				} else {
					entry.classList.remove(\"avamark2\");
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
}";

#Add pulse effect to some of the elements
$fcpage = $fcpage . "
function shadowlnks(search) {
	var e = document.getElementById('lnk' + search + 'img');
	e.className = \"hvr-pulse\";
	var e = document.getElementById('lnk' + search + 'text');
	e.className = \"hovershadow\";
}
function shadowlnkh(search) {
	var e = document.getElementById('lnk' + search + 'img');
	e.className = \"\";
	var e = document.getElementById('lnk' + search + 'text');
	e.className = \"\";
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
}";

#JS to show rank description
$fcpage = $fcpage . "
function showtipload(rank) {";
	if ($modrw == true) {
		$fcpage = $fcpage . "loadFile('./rank/".$fcid."/' + rank, showtipcb);";
	} else {
		$fcpage = $fcpage . "loadFile('./fcranks.php?fcid=".$fcid."&fcname=' + rank, showtipcb);";
	}
$fcpage = $fcpage . "
}
function showtipcb() {
	var e = document.getElementById('fcranktip');
	e.innerHTML = this.responseText;
}";

#JS to show member details
$fcpage = $fcpage . "
function showchar(memberid) {
	var e = document.getElementById('chardetail');
	e.innerHTML = '<table width=\"872px\" class=\"memberstbl\"><tr><td><img width=\"252\" height=\"252\" src=\"./img/loading.gif\"></td></tr></table>';
	if (e.style.display == \"none\") {
		e.style.display = \"\";
	}";
	if ($modrw == true) {
		$fcpage = $fcpage . "loadFile('./member/".$fcid."/' + memberid, showcharcb);";
	} else {
		$fcpage = $fcpage . "loadFile('./chardet.php?fcid=".$fcid."&id=' + memberid, showcharcb);";
	}
$fcpage = $fcpage . "	
}
function showcharcb() {
	var e = document.getElementById('chardetail');
	e.innerHTML = this.responseText;
	var e = document.getElementById('fcranktip');
}
</script>";

#Prepare general FC info
$fcpage = $fcpage . "
<div name=\"main\" style=\"margin: auto;width: 100%;text-align:center;\">
<div style=\"align:center;\">
<span style=\"display: inline-block;position: relative;text-align:right;vertical-align:top;\">We are</span>
<a href=\"http://eu.finalfantasyxiv.com/lodestone/freecompany/".$fcid."/\"><span style=\"display: inline-block;position: relative;margin-left: -65px;margin-top:15px;width: 68px;height: 68px;\">
<img style=\"position: absolute; top: 0; left: 0;\" src=\"".$fcdata['emblum'][0]."\" height=\"64\" width=\"64\">
<img style=\"position: absolute; top: 0; left: 0;\" src=\"".$fcdata['emblum'][1]."\" height=\"64\" width=\"64\">
<img style=\"position: absolute; top: 0; left: 0;\" src=\"".$fcdata['emblum'][2]."\" height=\"64\" width=\"64\">
</span>
<span class=\"fcnamecss\" style=\"display: inline-block;position: relative;height: 68px;text-align:center;vertical-align:middle;margin-left:-10px;margin-right:-90px;margin-top:-70px;margin-bottom:20px;\">".$fcdata['name']."</span></a>
<span style=\"height: 68px;text-align:right;vertical-align:bottom;\">from <span class=\"serverncss\">".$fcdata['server']."</span>
</div>
<div style=\"align:center;\"><i>".$fcdata['slogan']."</i></div>
<div style=\"align:center;\"><br><br>";

if (!empty($focus) || !empty($roles)) {
	#Prepare headers
	$fcpage = $fcpage . "<table style=\"border: 0px;\" class=\"memberstbl\"><tr>";
	if (!empty($focus)) {
		$fcpage = $fcpage . "<td style=\"border: 0px;padding-right:5px;\">We participate in</td>";
	}
	if (!empty($roles)) {
		$fcpage = $fcpage . "<td style=\"border: 0px;padding-left:5px;\">We are looking for</td>";
	}
	$fcpage = $fcpage . "</tr><tr>";
	
	#Show all activities the Company is interested in
	if (!empty($focus)) {
		$fcpage = $fcpage . "<td style=\"border: 0px;padding-right:5px;\">";
		foreach($focus as $interest) {
			if ($interest['active'] == 1) {
				$fcpage = $fcpage . "<span><img height=\"32\" width=\"32\" src=\"./img/focus/".imgnamesane($interest['name']).".png\" title=\"".$interest['name']."\"></span>";
			}
		}
		$fcpage = $fcpage . "</td>";
	}
	
	#Show all roles the Company is looking for
	if (!empty($roles)) {
		$fcpage = $fcpage . "<td style=\"border: 0px;padding-left:5px;\">";
		foreach($roles as $role) {
			if ($role['active'] == 1) {
				$fcpage = $fcpage . "<span><img height=\"32\" width=\"32\" src=\"./img/roles/".imgnamesane($role['name']).".png\" title=\"".$role['name']."\"></span>";
			}
		}
		$fcpage = $fcpage . "</td>";
	}
	
	$fcpage = $fcpage . "</tr></table>";
}

$fcpage = $fcpage . "</div><div style=\"align:center;\"><br>We were found on <span class=\"formeddate\">".date("d F Y" ,$fcdata['formed'])."</span> as affiliate of <span class=\"";

#Show Company affiliation
if (strtolower($fcdata['company']) == strtolower("Order of the Twin Adder")) {
	$fcpage = $fcpage . "gctwinadder";
} elseif (strtolower($fcdata['company']) == strtolower("Maelstrom")) {
	$fcpage = $fcpage . "gcmaelstorm";
} elseif (strtolower($fcdata['company']) == strtolower("Immortal Flames")) {
	$fcpage = $fcpage . "gcimmortalflames";
}

#Get last 10 Company ranks
$lastranks=getlastranks($fcdata['ranking']['weekly'], $fcid);

$fcpage = $fcpage . "\">".$fcdata['company']."</span><br><br>
We live in <span class=\"estatename\">".$fcdata['estate']['zone']."</span> <span class=\"estateaddress\">(".$fcdata['estate']['address'].")</span> and rank <span class=\"ranknum\" onmouseover=\"document.getElementById('ranking').style.display = 'inline-block';\" onmouseout=\"document.getElementById('ranking').style.display = 'none';\">".$fcdata['ranking']['weekly']."<sup>[?]</sup><div id=\"ranking\" style=\"position:absolute;z-index: 100;background-color:gray;display:none;width:300px;height:200px;\"><canvas id=\"myChart\"></canvas></div>
</span> among the companies <span class=\"rankmax\">(".maxValueInArray($lastranks, "rank")." min, ".minValueInArray($lastranks, "rank")." max)</span>
<br><br>
We have <b><span class=\"membercount\">".$fcdata['memberCount']."</span></b> members and counting. Want to join? Search for those with <span class=\"membertag\">".$fcdata['tag']."</span> tag on them.<br>
</div>";

#Prepare ranking chart
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

#Load updater in iframe, so that update can be done in background
if ($modrw == true) {
	$fcpage = $fcpage . "<iframe src=\"./update/".$fcid."\" width=\"400px\" height=\"20px\" frameborder=\"0\" allowtransparency seamless scrolling=\"auto\">You do not like iframes? =(</iframe>";
} else {
	$fcpage = $fcpage . "<iframe src=\"./update.php?fcid=".$fcid."\" width=\"400px\" height=\"20px\" frameborder=\"0\" allowtransparency seamless scrolling=\"auto\">You do not like iframes? =(</iframe>";
}

#Add search fieild
$fcpage = $fcpage . "<div><input autofocus alt=\"Search\" id=\"search_input\" placeholder=\"Type Name or ID to highlight a member\" size=\"40px\"><br><br></div><div id=\"newtable\"><div style=\"display: none;\" id=\"chardetail\"></div>";

#Output members in a nice table way, maximum of  in one line
$fcpage = $fcpage . "<table class=\"memberstbl\">";
$tdnum=1;
foreach ($memberstats as $memberid=>$member) {
	if (!is_null($member['bio']['name'])) {
		$id = $member['id'];
		if ($tdnum == 1) {
			$fcpage = $fcpage . "<tr>";
		}
		#Overlay FC rank image and images corresponding to rank up\down, whether member should be removed, can be promoted or has a wrong rank assigned
		$fcpage = $fcpage . "<td><span onclick=\"showchar(".$member['id'].")\" title=\"".$member['fc']['rank']." ".$member['bio']['name']."\" style=\"display: inline-block;position: relative;width: 64px;height: 64px;cursor:pointer;\">";
		if ($modrw == true) {
			$fcpage = $fcpage . "<a class=\"intlink\" href=\"member/".$fcid."/".$member['id']."\">";
		} else {
			$fcpage = $fcpage . "<a class=\"intlink\" href=\"chardet.php?fcid=".$fcid."&id=".$member['id']."\">";
		}
		$fcpage = $fcpage . "<img class=\"membertd\" id=\"".$member['bio']['name']." ".$member['id']."\" style=\"position: absolute; top: 0; left: 0;\" width=\"64px\" height=\"64px\" src=\"".$member['bio']['avatar']."\">";
		if ($member['fc']['altprom'] != "") {
				$fcpage = $fcpage . "<img class=\"membertdover\" style=\"position: absolute; top: 50; left: 25;\" id=\"".$member['bio']['name']." ".$member['id']."\" src=\"./img/altav.png\">";
		}
		$fcpage = $fcpage . "<img class=\"membertdover\" style=\"position: absolute; top: 45; left: 45;\" id=\"".$member['bio']['name']." ".$member['id']."\" src=\"./img/fcranks/".$member['fc']['rankid'].".png\">";
					#Check if
					if ($member['fc']['rankid'] == $fcconfig['lazy'] && ($curtime - $member['fc']['ranklvlupd']) / 86400 > $fcconfig['lazytime']) {
						$fcpage = $fcpage . "<img class=\"membertdover\" id=\"".$member['bio']['name']." ".$member['id']."\" style=\"position: absolute; top: 0; left: 0; opacity: 0.5; filter: alpha(opacity=50);\" width=\"64px\" height=\"64px\" src=\"img/delete.png\">";
					} else {
						if ($member['fc']['wronprom'] == true && $member['fc']['rankover'] == false) {
							$fcpage = $fcpage . "<img class=\"membertdover\" id=\"".$member['bio']['name']." ".$member['id']."\" style=\"position: absolute; top: 0; left: 0; opacity: 0.5; filter: alpha(opacity=50);\" width=\"64px\" height=\"64px\" src=\"img/attention.png\">";
						} else {
							if ($member['fc']['nextprom'] != "") {
								$fcpage = $fcpage . "<img class=\"membertdover\" id=\"".$member['bio']['name']." ".$member['id']."\" style=\"position: absolute; top: 0; left: 0; opacity: 0.5; filter: alpha(opacity=50);\" width=\"64px\" height=\"64px\" src=\"img/rankup.png\">";
							} else {
								#Show rank up\down only for a set period of time
								if ($member['fc']['ranklvl'] > $member['fc']['ranklvlprev'] && ($curtime - $member['fc']['ranklvlupd']) / 86400 < $fcconfig['rankotime']) {
									$fcpage = $fcpage . "<img class=\"membertdover\" id=\"".$member['bio']['name']." ".$member['id']."\" style=\"position: absolute; top: 0; left: 0; opacity: 0.5; filter: alpha(opacity=50);\" width=\"64px\" height=\"64px\" src=\"img/lvldown.png\">";
								} elseif ($member['fc']['ranklvl'] < $member['fc']['ranklvlprev'] && ($curtime - $member['fc']['ranklvlupd']) / 86400 < $fcconfig['rankotime']) {
									$fcpage = $fcpage . "<img class=\"membertdover\" id=\"".$member['bio']['name']." ".$member['id']."\" style=\"position: absolute; top: 0; left: 0; opacity: 0.5; filter: alpha(opacity=50);\" width=\"64px\" height=\"64px\" src=\"img/lvlup.png\">";
								} elseif (($curtime - $member['joined']) / 86400 <= $fcconfig['newbie']) {
									$fcpage = $fcpage . "<img class=\"membertdover\" id=\"".$member['bio']['name']." ".$member['id']."\" style=\"position: absolute; top: 0; left: 0; opacity: 0.5; filter: alpha(opacity=50);\" width=\"64px\" height=\"64px\" src=\"img/new.png\">";
								}
							}
						}
					}
					$fcpage = $fcpage . "</a></span></td>";
		if ($tdnum == $fcconfig['memonline']) {
			$fcpage = $fcpage . "</tr>";
			$tdnum = 0;
		}
		$tdnum++;
	}
}

$fcpage = $fcpage . "</table></div><div style=\"font-size:xx-small;\"><br><div style=\"font-size:xx-small;\">Source code of the page can be downloaded <a target=\"_blank\" href=\"";
if ($modrw == true) {
	$fcpage = $fcpage . "zip";
} else {
	$fcpage = $fcpage . "zip.php";
}
$fcpage = $fcpage . "\">here</a> or on <a href=\"https://github.com/Simbiat/XIV-FC-Page\" target=\"_blank\">GitHub</a></div><div style=\"font-size:xx-small;\">Coded by &copy; <a href=\"http://simbiat.net\" target=\"_blank\">Simbiat</a> with use of &copy; <a href=\"https://github.com/viion/XIVPads-LodestoneAPI\" target=\"_blank\">XIVSync</a></div></div>";
unset($api);

echo $fcpage;
?>