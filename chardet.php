<meta charset="UTF-8">
<?php
#Back-end initialization
require_once 'functions.php';
require_once 'config.php';
misdircreate();
$curtime=time();
$fcranks=json_decode(file_get_contents('fcranks.json'), true);
$memberstats = json_decode(file_get_contents('cache/members.json'), true);

#Check if ID was provided and exists
if (empty($_GET['id']) && empty($id)) {
	exit;
} else {
	if (empty($_GET['id']) ) {
		echo "<head>
			<link rel=\"stylesheet\" type=\"text/css\" href=\"style.css\">
			</head><title>Wrong ID</title>ID not provided.<br><a href=\"index.php\">Return</a>";
		exit;
	} else {
		$id=$_GET['id'];
	}
}

if (is_null($memberstats[$id])) {
	echo "<head>
		<link rel=\"stylesheet\" type=\"text/css\" href=\"style.css\">
		</head><title>Wrong ID</title>ID not found.<br><a href=\"index.php\">Return</a>";
	exit;
} 

#Generate member details
$member = $memberstats[$id];
$charpage = "<head>
		<link rel=\"stylesheet\" type=\"text/css\" href=\"style.css\">
		</head><title>".$member['bio']['name']."</title>";

#Copy of some of the JS from main page for consistency
$charpage = $charpage . "
<script>
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
</script>
";
#Output main table
$charpage = $charpage . "<table width=\"872px\" class=\"memberstbl\"><tr><td>";

#Output levels details for the member
$charpage = $charpage . "
<table style=\"height: 436px;\" class=\"levelstbl\">
<tr><td class=\"dpsclass\" style=\"border-left: 1px solid black; border-top: 1px solid black\" rowspan=\"3\" title=\"Total: ".($member['levels']['curr']['Pugilist'] + $member['levels']['curr']['Rogue'] + $member['levels']['curr']['Lancer'])."\">M<br>E<br>L<br>E<br>E</td><td style=\"border-top: 1px solid black\"><img width=\"12px\" height=\"12px\" title=\"Pugilist\" src=\"img/jobs/Pugilist.png\"></td><td class=\"dpsclass\" style=\"border-top: 1px solid black;border-right: 1px solid black\">".$member['levels']['curr']['Pugilist']."</td><td class=\"tankclass\" style=\"border-left: 1px solid black;border-top: 1px solid black\" title=\"Total: ".$member['levels']['curr']['totaltank']."\" rowspan=\"3\"><span>T<br>A<br>N<br>K<br>S</span></td><td style=\"border-top: 1px solid black\"><img width=\"12px\" height=\"12px\" title=\"Gladiator\" src=\"img/jobs/Gladiator.png\"></td><td class=\"tankclass\" style=\"border-top: 1px solid black;border-right: 1px solid black\">".$member['levels']['curr']['Gladiator']."</td></tr>
<tr><td><img width=\"12px\" height=\"12px\" title=\"Rogue\" src=\"img/jobs/Rogue.png\"></td><td class=\"dpsclass\" style=\"border-right: 1px solid black\">".$member['levels']['curr']['Rogue']."</td><td><img width=\"12px\" height=\"12px\" title=\"Marauder\" src=\"img/jobs/Marauder.png\"></td><td class=\"tankclass\" style=\"border-right: 1px solid black\">".$member['levels']['curr']['Marauder']."</td></tr>
<tr><td><img width=\"12px\" height=\"12px\" title=\"Lancer\" src=\"img/jobs/Lancer.png\"></td><td class=\"dpsclass\" style=\"border-right: 1px solid black\">".$member['levels']['curr']['Lancer']."</td><td><img width=\"12px\" height=\"12px\" title=\"Dark Knight\" src=\"img/jobs/DarkKnight.png\"></td><td class=\"tankclass\" style=\"border-right: 1px solid black\">".$member['levels']['curr']['Dark Knight']."</td></tr>
<tr><td class=\"dpsclass\" style=\"border-left: 1px solid black;border-bottom: 1px solid black\" rowspan=\"3\" title=\"Total: ".($member['levels']['curr']['Archer'] + $member['levels']['curr']['Machinist'] + $member['levels']['curr']['Thaumaturge'])."\">R<br>A<br>N<br>G<br>E<br>D</td><td><img width=\"12px\" height=\"12px\" title=\"Archer\" src=\"img/jobs/Archer.png\"></td><td class=\"dpsclass\" style=\"border-right: 1px solid black\">".$member['levels']['curr']['Archer']."</td><td class=\"healclass\" style=\"border-left: 1px solid black;border-bottom: 1px solid black\" title=\"Total: ".$member['levels']['curr']['totalheal']."\" rowspan=\"3\"><span>H<br>E<br>A<br>L<br>E<br>R<br>S</span></td><td><img width=\"12px\" height=\"12px\" title=\"Conjurer\" src=\"img/jobs/Conjurer.png\"></td><td class=\"healclass\" style=\"border-right: 1px solid black\">".$member['levels']['curr']['Conjurer']."</td></tr>
<tr><td><img width=\"12px\" height=\"12px\" title=\"Machinist\" src=\"img/jobs/Machinist.png\"></td><td class=\"dpsclass\" style=\"border-right: 1px solid black\">".$member['levels']['curr']['Machinist']."</td><td><img width=\"12px\" height=\"12px\" title=\"Arcanist\" src=\"img/jobs/Arcanist.png\"></td><td class=\"healclass\" style=\"border-right: 1px solid black\">".$member['levels']['curr']['Arcanist']."</td></tr>
<tr><td style=\"border-bottom: 1px solid black\"><img width=\"12px\" height=\"12px\" title=\"Thaumaturge\" src=\"img/jobs/Thaumaturge.png\"></td><td class=\"dpsclass\" style=\"border-right: 1px solid black;border-bottom: 1px solid black\">".$member['levels']['curr']['Thaumaturge']."</td><td style=\"border-bottom: 1px solid black\"><img width=\"12px\" height=\"12px\" title=\"Astrologian\" src=\"img/jobs/Astrologian.png\"></td><td class=\"healclass\" style=\"border-bottom: 1px solid black;border-right: 1px solid black\">".$member['levels']['curr']['Astrologian']."</td></tr>
<tr><td class=\"craftclass\" style=\"border-left: 1px solid black;border-top: 1px solid black;border-bottom: 1px solid black\" title=\"Total: ".$member['levels']['curr']['totalcraft']."\" rowspan=\"4\"><span>C<br>R<br>A<br>F<br>T<br>E<br>R<br>S</td><td style=\"border-top: 1px solid black\"><img width=\"12px\" height=\"12px\" title=\"Armorer\" src=\"img/jobs/Armorer.png\"></td><td class=\"craftclass\" style=\"border-top: 1px solid black\">".$member['levels']['curr']['Armorer']."</td><td style=\"border-bottom: 1px solid black;bottom-top: 1px solid black\" rowspan=\"4\"></td><td style=\"border-top: 1px solid black\"><img width=\"12px\" height=\"12px\" title=\"Blacksmith\" src=\"img/jobs/Blacksmith.png\"></td><td class=\"craftclass\" style=\"border-right: 1px solid black;border-top: 1px solid black\">".$member['levels']['curr']['Blacksmith']."</td></tr>
<tr><td><img width=\"12px\" height=\"12px\" title=\"Leatherworker\" src=\"img/jobs/Leatherworker.png\"></td><td class=\"craftclass\">".$member['levels']['curr']['Leatherworker']."</td><td><img width=\"12px\" height=\"12px\" title=\"Weaver\" src=\"img/jobs/Weaver.png\"></td><td class=\"craftclass\" style=\"border-right: 1px solid black\">".$member['levels']['curr']['Weaver']."</td></tr>
<tr><td><img width=\"12px\" height=\"12px\" title=\"Carpenter\" src=\"img/jobs/Carpenter.png\"></td><td class=\"craftclass\">".$member['levels']['curr']['Carpenter']."</td><td><img width=\"12px\" height=\"12px\" title=\"Goldsmith\" src=\"img/jobs/Goldsmith.png\"></td><td class=\"craftclass\" style=\"border-right: 1px solid black\">".$member['levels']['curr']['Goldsmith']."</td></tr>
<tr><td style=\"border-bottom: 1px solid black\"><img width=\"12px\" height=\"12px\" title=\"Alchemist\" src=\"img/jobs/Alchemist.png\"></td><td class=\"craftclass\" style=\"border-bottom: 1px solid black\">".$member['levels']['curr']['Alchemist']."</td><td style=\"border-bottom: 1px solid black\"><img width=\"12px\" height=\"12px\" title=\"Culinarian\" src=\"img/jobs/Culinarian.png\"></td><td class=\"craftclass\" style=\"border-right: 1px solid black;border-bottom: 1px solid black\">".$member['levels']['curr']['Culinarian']."</td></tr>
<tr><td class=\"gathclass\" style=\"border-right: 1px solid black;border-top: 1px solid black;border-left: 1px solid black\" title=\"Total: ".$member['levels']['curr']['totalgath']."\" colspan=\"6\">GATHERERS</td></tr>
<tr><td style=\"border-left: 1px solid black;border-bottom: 1px solid black\"><img width=\"12px\" height=\"12px\" title=\"Miner\" src=\"img/jobs/Miner.png\"></td><td class=\"gathclass\" style=\"border-bottom: 1px solid black\">".$member['levels']['curr']['Miner']."</td><td style=\"border-bottom: 1px solid black\"><img width=\"12px\" height=\"12px\" title=\"Botanist\" src=\"img/jobs/Botanist.png\"></td><td class=\"gathclass\" style=\"border-bottom: 1px solid black\">".$member['levels']['curr']['Botanist']."</td><td style=\"border-bottom: 1px solid black\"><img width=\"12px\" height=\"12px\" title=\"Fisher\" src=\"img/jobs/Fisher.png\"></td><td class=\"gathclass\" style=\"border-right: 1px solid black;border-bottom: 1px solid black\">".$member['levels']['curr']['Fisher']."</td></tr>
<tr><td class=\"totalclass\" colspan=\"6\">Total: ".$member['levels']['curr']['totallvl']."</td><tr></table></td>";

#Output member image with overlay images (FC rank, GC rank, .etc)
$charpage = $charpage . "
<td><span style=\"display: inline-block;position: relative;width: 320px;height: 436px;\">
<img style=\"position: absolute; top: 0; left: 0;\" width=\"320\" height=\"436\" src=\"".$member['bio']['portrait']."\">
<img onmouseover=\"shadowlnks('guard');\" onmouseout=\"shadowlnkh('guard');\" id=\"lnkguardimg\" style=\"position: absolute; top: 5; left: 5;\" title=\"".$member['bio']['guardian']."\" src=\"./img/guardians/".imgnamesane($member['bio']['guardian']).".png\">
<img onmouseover=\"shadowlnks('city');\" onmouseout=\"shadowlnkh('city');\" id=\"lnkcityimg\" style=\"position: absolute; top: 5; left: 283;\" src=\"./img/cities/".imgnamesane($member['bio']['city']).".png\" title=\"".$member['bio']['city']."\">
";
if ($member['gc']['name'] != "") {
	$charpage = $charpage . "<img onmouseover=\"shadowlnks('gc');\" onmouseout=\"shadowlnkh('gc');\" id=\"lnkgcimg\" style=\"position: absolute; top: 399; left: 5;\" src=\"./img/grandcompany/".imgnamesane($member['gc']['rank']).".png\" title=\"".$member['gc']['rank']." of ".$member['gc']['name']."\">";
}
$charpage = $charpage . "<img onmouseover=\"shadowlnks('rank');\" onmouseout=\"shadowlnkh('rank');\" id=\"lnkrankimg\" style=\"position: absolute; top: 411; left: 295;\" src=\"./cache/ranks/".imgnamesane($member['fc']['rank']).".png\" title=\"".$member['fc']['rank']."\">
<span>
</td>";

#Output information in a type of a dossier
$charpage = $charpage . "
<td>
This ";
if (($curtime - $member['joined']) / 86400 <= $newbie) {
	$charpage = $charpage . "newly recruited ";
}
$charpage = $charpage .$member['bio']['gender']." ".$member['bio']['race']." of ".$member['bio']['clan']." clan, <a href=\"http://eu.finalfantasyxiv.com/lodestone/character/".$member['id']."/\">".$member['bio']['name']."</a>";
if ($member['bio']['name'] != $member['bio']['prevname']) {
	$charpage = $charpage." (a.k.a. <a href=\"http://eu.finalfantasyxiv.com/lodestone/character/".$member['id']."/\">".$member['bio']['prevname']."</a>)";
}
$charpage = $charpage . ", came to us on ".date("d F Y" ,$member['joined']).".
 From information provided to us, ".sexcheck($member['bio']['gender'])[0]." was born on ".$member['bio']['nameday']."
 and has <span onmouseover=\"shadowlnks('guard');\" onmouseout=\"shadowlnkh('guard');\" id=\"lnkguardtext\">".$member['bio']['guardian']."</span> as ".sexcheck($member['bio']['gender'])[2]." Guardian.
 Last known city of residence is <span onmouseover=\"shadowlnks('city');\" onmouseout=\"shadowlnkh('city');\" id=\"lnkcitytext\">".$member['bio']['city']."</span>.";
if ($member['gc']['name'] != "") {
	$charpage = $charpage . " It's public knowledge, that ".sexcheck($member['bio']['gender'])[0]." is enlisted in <span onmouseover=\"shadowlnks('gc');\" onmouseout=\"shadowlnkh('gc');\" id=\"lnkgctext\">".$member['gc']['name']." as ".$member['gc']['rank']."</span>.";
}
if ($member['bio']['title'] != "") {
	$charpage = $charpage . " Among ".sexcheck($member['bio']['gender'])[2]." many alliases ".sexcheck($member['bio']['gender'])[0]." seems to prefer <i>".$member['bio']['title']."</i>.";
}

#Output rank checks and suggestions
if ($member['fc']['ranklvl'] == $lazy) {
	$charpage = $charpage . "<br>This <span id=\"lnkprevrank\" onclick=\"showtip('".$member['fc']['rankprev']."');\" style=\"cursor:help;\">".$member['fc']['rankprev']."</span> has been a <span onclick=\"showtip('".$member['fc']['rank']."');\" style=\"cursor:help;\" onmouseover=\"shadowlnks('rank');\" onmouseout=\"shadowlnkh('rank');\" id=\"lnkranktext\">".$member['fc']['rank']."</span> for ".intval((($curtime-$member['fc']['ranklvlupd'])/86400))." days";
	if (($curtime - $member['fc']['ranklvlupd']) / 86400 > $lazytime) {
		$charpage = $charpage . " and it's time to kick ".sexcheck($member['bio']['gender'])[1]." out";
	}
	$charpage = $charpage . ".";
} else {
	if ($member['fc']['wronprom'] == true && $member['fc']['rankover'] == false) {
		$charpage = $charpage . "<br>By some mistake ".sexcheck($member['bio']['gender'])[0]." was assigned the rank of <span onclick=\"showtip('".$member['fc']['rank']."');\" style=\"cursor:help;\" onmouseover=\"shadowlnks('rank');\" onmouseout=\"shadowlnkh('rank');\" id=\"lnkranktext\">".$member['fc']['rank']."</span> and this needs to be addressed.";
		$charpage = $charpage . "<br>".$member['fc']['wronreas'];
	}
	if ($member['fc']['ranklvl'] != $member['fc']['ranklvlprev'] && ($curtime - $member['fc']['ranklvlupd']) / 86400 <= $rankotime) {
		$charpage = $charpage . "<br>From the rank of <span id=\"lnkprevrank\" onclick=\"showtip('".$member['fc']['rankprev']."');\" style=\"cursor:help;\">".$member['fc']['rankprev']."</span> ".sexcheck($member['bio']['gender'])[0]." was ";
		if ($member['fc']['ranklvl'] > $member['fc']['ranklvlprev']) {
			$charpage = $charpage . "demoted ";
		} elseif ($member['fc']['ranklvl'] < $member['fc']['ranklvlprev']) {
			$charpage = $charpage . "promoted ";
		}
		$charpage = $charpage . " to <span onclick=\"showtip('".$member['fc']['rank']."');\" style=\"cursor:help;\" onmouseover=\"shadowlnks('rank');\" onmouseout=\"shadowlnkh('rank');\" id=\"lnkranktext\">".$member['fc']['rank']."</span> on ".date("d F Y" ,$member['fc']['ranklvlupd']).".";
		if ($member['fc']['nextprom'] != "" && $member['fc']['wronprom'] == true && $member['fc']['rankover'] == false) {
			$charpage = $charpage . "<br>Possible next rank is: ";
			$split = explode(",", $member['fc']['nextprom']);
			foreach ($split as $splitrank) {
				if ($splitrank == $split[count($split)-1]) {
					$charpage = $charpage . "<span id=\"lnk".$splitrank."\" onclick=\"showtip('".$splitrank."');\" style=\"cursor:help;\">".$splitrank."</span>.";
				} else {
					$charpage = $charpage . "<span id=\"lnk".$splitrank."\" onclick=\"showtip('".$splitrank."');\" style=\"cursor:help;\">".$splitrank."</span> or ";
				}
			}
		}
	} else {
		if (($member['fc']['wronprom'] == true && $member['fc']['rankover'] == true) || $member['fc']['wronprom'] == false) {
			$charpage = $charpage . "<br>". ucfirst(sexcheck($member['bio']['gender'])[0])." currently ranks as <span onclick=\"showtip('".$member['fc']['rank']."');\" onmouseover=\"shadowlnks('rank');\" onmouseout=\"shadowlnkh('rank');\" id=\"lnkranktext\" style=\"cursor:help;\">".$member['fc']['rank']."</span>.";
		}
		if ($member['fc']['recoprom'] != "") {
			$charpage = $charpage . "<br>Recommended next rank is: ";
			$split = explode(",", $member['fc']['recoprom']);
			foreach ($split as $splitrank) {
				if ($splitrank == $split[count($split)-1]) {
					$charpage = $charpage . "<span id=\"lnk".$splitrank."\" onclick=\"showtip('".$splitrank."');\" style=\"cursor:help;\">".$splitrank."</span>.";
				} else {
					$charpage = $charpage . "<span id=\"lnk".$splitrank."\" onclick=\"showtip('".$splitrank."');\" style=\"cursor:help;\">".$splitrank."</span> or ";
				}
			}
		}
		if ($member['fc']['nextprom'] != "") {
			$charpage = $charpage . "<br>Possible next rank is: ";
			$split = explode(",", $member['fc']['nextprom']);
			foreach ($split as $splitrank) {
				if ($splitrank == $split[count($split)-1]) {
					$charpage = $charpage . "<span id=\"lnk".$splitrank."\" onclick=\"showtip('".$splitrank."');\" style=\"cursor:help;\">".$splitrank."</span>.";
				} else {
					$charpage = $charpage . "<span id=\"lnk".$splitrank."\" onclick=\"showtip('".$splitrank."');\" style=\"cursor:help;\">".$splitrank."</span> or ";
				}
			}
		}
		if ($member['fc']['altprom'] != "") {
			$charpage = $charpage . "<br>Alternative rank is: ";
			$split = explode(",", $member['fc']['altprom']);
			foreach ($split as $splitrank) {
				if ($splitrank == $split[count($split)-1]) {
					$charpage = $charpage . "<span id=\"lnk".$splitrank."\" onclick=\"showtip('".$splitrank."');\" style=\"cursor:help;\">".$splitrank."</span>.";
				} else {
					$charpage = $charpage . "<span id=\"lnk".$splitrank."\" onclick=\"showtip('".$splitrank."');\" style=\"cursor:help;\">".$splitrank."</span> or ";
				}
			}
		}
	}
}
$charpage = $charpage . "</td></table><div id=\"fcranktip\" style=\"display: none;\"></div>";
echo $charpage;
?>