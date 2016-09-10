<?php

#Back-end initialization
require_once 'functions.php';
require_once 'api-autoloader.php';
if (empty($_GET['fcid'])) {
	header("Location: ./index.php");
	die();
} else {
	$fcid = $_GET['fcid'];
	misdircreate($fcid);
	$fcconfig=json_decode(file_get_contents("./cache/".$fcid."/config.json"), true);
}
use Viion\Lodestone\LodestoneAPI;
misdircreate($fcid);
$curtime=time();
$api = new LodestoneAPI();
if (isset($_GET['basic'])){
	$api->useBasicParsing();
}
$maint = false;
$refreshpage = false;
$fcranks=json_decode(file_get_contents("./cache/".$fcid."/fcranks.json"), true);

#Checking if update is required
$cacheage=$curtime;
if (!file_exists("./cache/".$fcid."/fc.json") or (file_exists("./cache/".$fcid."/fc.json") and $curtime-filemtime("./cache/".$fcid."/fc.json") > $fcconfig['cachelife']) or !file_exists("./cache/".$fcid."/members.json") or (file_exists("./cache/".$fcid."/members.json") and $curtime-filemtime("./cache/".$fcid."/members.json") > $fcconfig['cachelife'])) {
	$fcdatatmp = $api->Search->FreeCompany($fcid, true);
	if (is_null($fcdatatmp->name)) {
		$maint = true;
	}
	if ($maint == true and (!file_exists("./cache/".$fcid."/fc.json") or !file_exists("./cache/".$fcid."/members.json"))) {
		if (!file_exists("./cache/".$fcid."/fc.json") or !file_exists("./cache/".$fcid."/members.json")) {
			@unlink("./cache/".$fcid."/fcranks.json");
			@unlink("./cache/".$fcid."/config.json");
			@unlink("./cache/".$fcid."/fc.json");
			@unlink("./cache/".$fcid."/members.json");
			@unlink("./cache/".$fcid."/ranking.json");
			@unlink("./cache/".$fcid."/style.css");
			@rmdir("./cache/".$fcid);
		}
		echo "<head>
<link rel=\"stylesheet\" type=\"text/css\" href=\"./defaults/style.css\">
</head>
<title>FXIV Free Company Tracker</title>Lodestone is under maintenance or wrong free company ID and the site has no cache saved.<br>Unable to load data";
		exit;
	} else {
		$cacheage=filemtime("./cache/".$fcid."/fc.json");
		$refreshpage = true;
	}
} else {
	$cacheage=filemtime("./cache/".$fcid."/fc.json");
	echo "<div style=\"font-size:xx-small;text-align:center\" id=\"contents\">Data presented is dated ".date("d F Y H:i" ,$cacheage)."</div>";
}

//ignore_user_abort(true);
#Checking age of Free Company data cache and grabbing it if required. Preparing to reload page if missing or old
if ($refreshpage == true) {
	echo "<div style=\"font-size:xx-small;text-align:center\" id=\"contents\">";
	echo "Last data grab was on ".date("d F Y H:i" ,$cacheage).", need to update information<br><script type=\"text/javascript\">document.body.scrollTop = document.body.scrollHeight - document.body.clientHeight;</script>";
	echo "Getting info for ".$fcdatatmp->name." company...<br><script type=\"text/javascript\">document.body.scrollTop = document.body.scrollHeight - document.body.clientHeight;</script>";
	ob_flush();
	flush();
	//$fcdatatmp = $api->Search->FreeCompany($fcid, true);
	file_put_contents("./cache/".$fcid."/fc.json", json_encode($fcdatatmp, JSON_PRETTY_PRINT));
	$refreshpage = true;	
}
$cacheage=$curtime;

#Prepare working data from Free Company cache
$fcdata = json_decode(file_get_contents("./cache/".$fcid."/fc.json"), true);
$members = $fcdata['members'];
$roles = $fcdata['roles'];
$focus = $fcdata['focus'];

#Push Company name to page title if we are planning to refresh the page
if ($refreshpage == true) {
	echo "<head>
		<link rel=\"stylesheet\" type=\"text/css\" href=\"./cache/".$fcid."/style.css\">
		</head>
		<title>".$fcdata['name']."</title>";
	ob_flush();
	flush();
}

#Checking age of members' data and grabbing it if required. If Free Company data needs to be updated update members as well
$memberstats=[];
$i = 1;
$len = count($members);
$preverrors=error_reporting();
if ($refreshpage == true) {
	#Push Company name to page title since we are planning to refresh the page. Do only if Free Company data check did not trigger this
	if ($refreshpage == false) {
		echo "<head>
			<link rel=\"stylesheet\" type=\"text/css\" href=\"./cache/".$fcid."/style.css\">
			</head>
			<title>".$fcdata['name']."</title>";
		echo "<div id=\"contents\">";
		echo "First run in a while, need to update information<br><script type=\"text/javascript\">document.body.scrollTop = document.body.scrollHeight - document.body.clientHeight;</script>";
		echo "Getting info for company...<br><script type=\"text/javascript\">document.body.scrollTop = document.body.scrollHeight - document.body.clientHeight;</script>";
		ob_flush();
		flush();
		$refreshpage = true;
	}
	#Disable notices, which may happen if API will return empty characters (those that were deleted from the game, but not cleaned properly yet)
	error_reporting(0);
	foreach($members as $member) {
		$charid = $member['id'];
		echo "Getting info for member ".$charid." (".$i." out of ".$len.")...<br><script type=\"text/javascript\">document.body.scrollTop = document.body.scrollHeight - document.body.clientHeight;</script>";
		ob_flush();
		flush();
		#Trying to get data for the member
		$charstat = $api->Search->Character($charid);
		if (is_null($charstat->name)) {
			echo "Failed to get info for member ".$charid." in 1 try. 2<sup>nd</sup> retry in 5 seconds...<br><script type=\"text/javascript\">document.body.scrollTop = document.body.scrollHeight - document.body.clientHeight;</script>";
			ob_flush();
			flush();
			sleep(5);
			$charstat = $api->Search->Character($charid);
			if (is_null($charstat->name)) {
				echo "Failed to get info for member ".$charid." in 2 tries. 3<sup>rd</sup> retry in 5 seconds...<br><script type=\"text/javascript\">document.body.scrollTop = document.body.scrollHeight - document.body.clientHeight;</script>";
				ob_flush();
				flush();
				sleep(5);
				$charstat = $api->Search->Character($charid);
				if (is_null($charstat->name)) {
					echo "Failed to get info for member ".$charid." in 3 tries. 4<sup>th</sup> retry in 5 seconds...<br><script type=\"text/javascript\">document.body.scrollTop = document.body.scrollHeight - document.body.clientHeight;</script>";
					ob_flush();
					flush();
					sleep(5);
					$charstat = $api->Search->Character($charid);
					if (is_null($charstat->name)) {
						echo "Failed to get info for member ".$charid." in 4 tries. Last retry in 5 seconds...<br><script type=\"text/javascript\">document.body.scrollTop = document.body.scrollHeight - document.body.clientHeight;</script>";
						ob_flush();
						flush();
						sleep(5);
						$charstat = $api->Search->Character($charid);
						if (is_null($charstat->name)) {
							echo "Failed to get info for member ".$charid." in 5 tries. Assuming banned user and using cache if possible...<br><script type=\"text/javascript\">document.body.scrollTop = document.body.scrollHeight - document.body.clientHeight;</script>";
							ob_flush();
							flush();
						}
					}
				}
			}
		}
		#Setting defaults based on the data grab
		$fc = [];
		$fc['rank'] = $fc['rankprev'] = $member['rank']['title'];
		$fc['rankid'] = $fc['rankidprev'] = str_replace(".png", "", str_replace("http://img.finalfantasyxiv.com/lds/pc/global/images/freecompany/ic/class/", "", $member['rank']['icon']));
		foreach($fcranks as $fcranktag=>$fcrankval){
			if ($fcrankval['id'] == $fc['rankid']) {
				if ($fcranktag != $fc['rank']) {
					$fcranks[$fc['rank']] = $fcranks[$fcranktag];
					unset($fcranks[$fcranktag]);
				}
			}
		}
		$fc['ranklvl'] = $fc['ranklvlprev'] = $fcranks[$fc['rank']]['level'];
		$fc['ranklvlupd'] = $curtime;
		$fc['rankover'] = false;
		$fc['nextprom'] = "";
		$fc['altprom'] = "";
		$fc['recoprom'] = "";
		$fc['wronprom'] = false;
		$fc['wronreas'] = "";
		$bio = [];
		$bio['name'] = $bio['prevname'] = $charstat->name;
		$bio['title'] = $charstat->title;
		$bio['avatar'] = $charstat->avatar;
		$bio['portrait'] = $charstat->portrait;
		if (strpos($bio['avatar'], "?")) {
			$bio['avatar'] = substr($bio['avatar'], 0, strpos($bio['avatar'], "?"));
		}
		if (strpos($bio['portrait'], "?")) {
			$bio['portrait'] = substr($bio['portrait'], 0, strpos($bio['portrait'], "?"));
		}
		$bio['race'] = $charstat->race;
		$bio['clan'] = $charstat->clan;
		$bio['gender'] = $charstat->gender;
		$bio['nameday'] = $charstat->nameday;
		$bio['guardian'] = $charstat->guardian;
		$bio['city'] = $charstat->city;
		$gc = [];
		$gc['name'] = $charstat->grandCompany;
		$gc['rank'] = $charstat->grandCompanyRank;
		$levels = [];
		$levels['curr'] = [];
		$levels['prev'] = [];
		$levels['init'] = [];
		$tempclasses = $charstat->classjobs;
		$totallvl = 0;
		foreach($tempclasses as $class) {
			$levels['curr'][$class['name']] = $levels['prev'][$class['name']] = $levels['init'][$class['name']] = $class['level'];
			$totallvl = $totallvl + $class['level'];
		}
		$levels['curr']['totallvl'] = $levels['prev']['totallvl'] = $levels['init']['totallvl'] = $totallvl;
		$totaltank = $tempclasses['0']['level'] + $tempclasses['2']['level'] + $tempclasses['9']['level'];
		$totalheal = $tempclasses['6']['level'] + $tempclasses['8']['level'] + $tempclasses['11']['level'];
		$totaldps = $tempclasses['1']['level'] + $tempclasses['3']['level'] + $tempclasses['4']['level'] + $tempclasses['5']['level'] + $tempclasses['7']['level'] + $tempclasses['10']['level'];
		$totalgath = $tempclasses['20']['level'] + $tempclasses['21']['level'] + $tempclasses['22']['level'];
		$totalcraft = $tempclasses['12']['level'] + $tempclasses['13']['level'] + $tempclasses['14']['level'] + $tempclasses['15']['level'] + $tempclasses['16']['level'] + $tempclasses['17']['level'] + $tempclasses['18']['level'] + $tempclasses['19']['level'];
		$maxtank=max($tempclasses['0']['level'], $tempclasses['2']['level'], $tempclasses['9']['level']);
		$maxheal=max($tempclasses['6']['level'], $tempclasses['8']['level'], $tempclasses['11']['level']);
		$maxdps=max($tempclasses['1']['level'], $tempclasses['3']['level'], $tempclasses['4']['level'], $tempclasses['5']['level'], $tempclasses['7']['level'], $tempclasses['10']['level']);
		$maxgath=max($tempclasses['20']['level'], $tempclasses['21']['level'], $tempclasses['22']['level']);
		$maxcraft=max($tempclasses['12']['level'], $tempclasses['13']['level'], $tempclasses['14']['level'], $tempclasses['15']['level'], $tempclasses['16']['level'], $tempclasses['17']['level'], $tempclasses['18']['level'], $tempclasses['19']['level']);
		$levels['curr']['totaltank'] = $levels['prev']['totaltank'] = $levels['init']['totaltank'] = $totaltank;
		$levels['curr']['totalheal'] = $levels['prev']['totalheal'] = $levels['init']['totalheal'] = $totalheal;
		$levels['curr']['totaldps'] = $levels['prev']['totaldps'] = $levels['init']['totaldps'] = $totaldps;
		$levels['curr']['totaldpsm'] = $levels['prev']['totaldpsm'] = $levels['init']['totaldpsm'] = $tempclasses['1']['level'] + $tempclasses['3']['level'] + $tempclasses['5']['level'];
		$levels['curr']['totaldpsr'] = $levels['prev']['totaldpsr'] = $levels['init']['totaldpsr'] = $tempclasses['4']['level'] + $tempclasses['7']['level'] + $tempclasses['10']['level'];
		$levels['curr']['totalgath'] = $levels['prev']['totalgath'] = $levels['init']['totalgath'] = $totalgath;
		$levels['curr']['totalcraft'] = $levels['prev']['totalcraft'] = $levels['init']['totalcraft'] = $totalcraft;
		$levels['curr']['maxtank'] = $levels['prev']['maxtank'] = $levels['init']['maxtank'] = $maxtank;
		$levels['curr']['maxheal'] = $levels['prev']['maxheal'] = $levels['init']['maxheal'] = $maxheal;
		$levels['curr']['maxdps'] = $levels['prev']['maxdps'] = $levels['init']['maxdps'] = $maxdps;
		$levels['curr']['maxgath'] = $levels['prev']['maxgath'] = $levels['init']['maxgath'] = $maxgath;
		$levels['curr']['maxcraft'] = $levels['prev']['maxcraft'] = $levels['init']['maxcraft'] = $maxcraft;
		$memberstats[$charid] = array("id"=>$charid, "bio"=>$bio, "fc"=>$fc, "gc"=>$gc, "levels"=>$levels, "joined"=>$curtime, "lvlupdate"=>$curtime);
		$i++;
		set_time_limit(180);
	}
	file_put_contents("./cache/".$fcid."/fcranks.json", json_encode($fcranks, JSON_PRETTY_PRINT));
	#If members list existed before get trackable data (joined date, previous total level, .etc)
	if (file_exists("./cache/".$fcid."/members.json")) {
		$old_members=json_decode(file_get_contents("./cache/".$fcid."/members.json"), true);
		foreach($old_members as $key=>$member) {
			#Check if member is an old one
			if (array_key_exists($key, $memberstats)) {
				if (is_null($memberstats[$key]['bio']['name'])) {
					$memberstats[$key] = $old_members[$key];
				} else {
					#Checking for name change
					if (array_key_exists('prevname', $old_members[$key]['bio'])) {
						if ($memberstats[$key]['bio']['name'] != $old_members[$key]['bio']['name']) {
							$memberstats[$key]['bio']['prevname'] = $old_members[$key]['bio']['name'];
						} else {
							$memberstats[$key]['bio']['prevname'] = $old_members[$key]['bio']['prevname'];
						}
					}
					#Checking for FC rank change
					if (array_key_exists('ranklvl', $old_members[$key]['fc'])) {
						if ($memberstats[$key]['fc']['ranklvl'] != $old_members[$key]['fc']['ranklvl']) {
							if (!is_null($old_members[$key]['fc']['ranklvl'])) {
								$memberstats[$key]['fc']['ranklvlprev'] = $old_members[$key]['fc']['ranklvl'];
								if (array_key_exists('rankidprev', $old_members[$key]['fc'])) {
									$memberstats[$key]['fc']['rankidprev'] = $old_members[$key]['fc']['rankid'];
								} else {
									$memberstats[$key]['fc']['rankidprev'] = $old_members[$key]['fc']['ranklvl'];
								}
							}
							$memberstats[$key]['fc']['rankprev'] = $old_members[$key]['fc']['rank'];
						} else {
							if (array_key_exists('rankidprev', $old_members[$key]['fc'])) {
								$memberstats[$key]['fc']['rankidprev'] = $old_members[$key]['fc']['rankidprev'];
							} else {
								$memberstats[$key]['fc']['rankidprev'] = $old_members[$key]['fc']['ranklvlprev'];
							}
							$memberstats[$key]['fc']['ranklvlprev'] = $old_members[$key]['fc']['ranklvlprev'];
							$memberstats[$key]['fc']['rankprev'] = $old_members[$key]['fc']['rankprev'];
							$memberstats[$key]['fc']['ranklvlupd'] = $old_members[$key]['fc']['ranklvlupd'];
						}
					}
					#Checking if override rank is set
					if (array_key_exists('rankover', $old_members[$key]['fc'])) {
						if ($old_members[$key]['fc']['rankover'] == true) {
							$memberstats[$key]['fc']['rankover'] = true;
						}
					}
					#Check for lvl changes
					$lvlwasupd = false;
					foreach($memberstats[$key]['levels']['curr'] as $lvlkey=>$level) {
						if (array_key_exists($lvlkey, $old_members[$key]['levels']['init'])) {
							$memberstats[$key]['levels']['init'][$lvlkey] = $old_members[$key]['levels']['init'][$lvlkey];
						}
						if (array_key_exists($lvlkey, $old_members[$key]['levels']['curr'])) {
							if (($memberstats[$key]['lvlupdate'] - $old_members[$key]['lvlupdate']) / 86400 > $fcconfig['lvltrack']) {
								if ($memberstats[$key]['levels']['curr'][$lvlkey] != $old_members[$key]['levels']['curr'][$lvlkey]) {
									$lvlwasupd = true;
									$memberstats[$key]['levels']['prev'][$lvlkey] = $old_members[$key]['levels']['curr'][$lvlkey];
								} else {
									$memberstats[$key]['levels']['prev'][$lvlkey] = $old_members[$key]['levels']['prev'][$lvlkey];
								}
							} else {
								$memberstats[$key]['levels']['prev'][$lvlkey] = $old_members[$key]['levels']['prev'][$lvlkey];
							}
						}
					}
					if ($lvlwasupd == false) {
						$memberstats[$key]['lvlupdate'] = $old_members[$key]['lvlupdate'];
					}
					$memberstats[$key]['levels']['prev']['totaldpsm'] = $memberstats[$key]['levels']['prev']['Puglist'] + $memberstats[$key]['levels']['prev']['Rogue'] + $memberstats[$key]['levels']['prev']['Lancer'];
					$memberstats[$key]['levels']['prev']['totaldpsr'] = $memberstats[$key]['levels']['prev']['Archer'] + $memberstats[$key]['levels']['prev']['Machinist'] + $memberstats[$key]['levels']['prev']['Thaumaturge'];
					$memberstats[$key]['levels']['init']['totaldpsm'] = $memberstats[$key]['levels']['init']['Puglist'] + $memberstats[$key]['levels']['init']['Rogue'] + $memberstats[$key]['levels']['init']['Lancer'];
					$memberstats[$key]['levels']['init']['totaldpsr'] = $memberstats[$key]['levels']['init']['Archer'] + $memberstats[$key]['levels']['init']['Machinist'] + $memberstats[$key]['levels']['init']['Thaumaturge'];
					#Checking if joined date is known already
					if (array_key_exists('joined', $old_members[$key])) {
						$memberstats[$key]['joined'] = $old_members[$key]['joined'];
					}
					#Suggest ranks
					foreach ($fcranks as $rankkey=>$posrank) {
						#Check if rank is to assignable
						if ($posrank['requirements']['assign'] == true) {
							#Check if member is lazy or was recently lazy
							if ($memberstats[$key]['fc']['rankid'] != $fcconfig['lazy'] && ($memberstats[$key]['fc']['rankidprev'] != $fcconfig['lazy'] || ($memberstats[$key]['fc']['rankidprev'] == $fcconfig['lazy'] && ($curtime - $memberstats[$key]['fc']['ranklvlupd']) / 86400 >= $fcconfig['lazyover']))) {
								#Check if current rank is high enough to get promoted
								if ($memberstats[$key]['fc']['ranklvl'] <= $posrank['requirements']['minfclvl']) {
	      								$skiprank = false;
									#Check if total level is enough
	      								if (!($memberstats[$key]['levels']['curr']['totallvl'] >= $posrank['requirements']['mintotlvl'])) {
										#Check if rank is wrongly assigned
	      									if ($memberstats[$key]['fc']['rank'] == $rankkey && $memberstats[$key]['fc']['rankover'] == false) {
	      										$memberstats[$key]['fc']['wronreas'] = "Total level (".$memberstats[$key]['levels']['curr']['totallvl'].") is lower than required (".$posrank['requirements']['mintotlvl'].").";
	      										$memberstats[$key]['fc']['wronprom'] = true;
	      									} else {
	      										$skiprank = true;
	      									}
	      								}
									#Check if member has enough class diversity
	      								if (!(classdivcheck($memberstats[$key]['levels']['curr']['maxtank'], $memberstats[$key]['levels']['curr']['maxdps'], $memberstats[$key]['levels']['curr']['maxheal'], $memberstats[$key]['levels']['curr']['maxgath'], $memberstats[$key]['levels']['curr']['maxcraft'], $posrank['requirements']['classtypes']))) {
										#Check if rank is wrongly assigned
	      									if ($memberstats[$key]['fc']['rank'] == $rankkey && $memberstats[$key]['fc']['rankover'] == false) {
	      										$memberstats[$key]['fc']['wronreas'] = $memberstats[$key]['fc']['wronreas'] . "Class types number is lower than required (".$posrank['requirements']['classtypes'].").";
	      										$memberstats[$key]['fc']['wronprom'] = true;
	      									} else {
	      										$skiprank = true;
	      									}
	      								}
									#Check if max DPS level is enough
	      								if (!($memberstats[$key]['levels']['curr']['maxdps'] >= $posrank['requirements']['mindpslvl'])) {
										#Check if rank is wrongly assigned
	      									if ($memberstats[$key]['fc']['rank'] == $rankkey && $memberstats[$key]['fc']['rankover'] == false) {
	      										$memberstats[$key]['fc']['wronreas'] = $memberstats[$key]['fc']['wronreas'] . "Maximum DPS class level (".$memberstats[$key]['levels']['curr']['maxdps'].") is lower than required (".$posrank['requirements']['mindpslvl'].").";
	      										$memberstats[$key]['fc']['wronprom'] = true;
	      									} else {
	      										$skiprank = true;
	      									}
	      								}
									#Check if max Tank level is enough
	      								if (!($memberstats[$key]['levels']['curr']['maxtank'] >= $posrank['requirements']['mintanklvl'])) {
										#Check if rank is wrongly assigned
	      									if ($memberstats[$key]['fc']['rank'] == $rankkey && $memberstats[$key]['fc']['rankover'] == false) {
	      										$memberstats[$key]['fc']['wronreas'] = $memberstats[$key]['fc']['wronreas'] . "Maximum Tank class level (".$memberstats[$key]['levels']['curr']['maxtank'].") is lower than required (".$posrank['requirements']['mintanklvl'].").";
	      										$memberstats[$key]['fc']['wronprom'] = true;
	      									} else {
	      										$skiprank = true;
	      									}
	      								}
									#Check if max Healer level is enough
	      								if (!($memberstats[$key]['levels']['curr']['maxheal'] >= $posrank['requirements']['minheallvl'])) {
										#Check if rank is wrongly assigned
	      									if ($memberstats[$key]['fc']['rank'] == $rankkey && $memberstats[$key]['fc']['rankover'] == false) {
	      										$memberstats[$key]['fc']['wronreas'] = $memberstats[$key]['fc']['wronreas'] . "Maximum Healer class level (".$memberstats[$key]['levels']['curr']['maxheal'].") is lower than required (".$posrank['requirements']['minheallvl'].").";
	      										$memberstats[$key]['fc']['wronprom'] = true;
	      									} else {
	      										$skiprank = true;
	      									}
	      								}
									#Check if Crafter level is enough
	      								if (!($memberstats[$key]['levels']['curr']['maxcraft'] >= $posrank['requirements']['mincraftlvl'])) {
										#Check if rank is wrongly assigned
	      									if ($memberstats[$key]['fc']['rank'] == $rankkey && $memberstats[$key]['fc']['rankover'] == false) {
	      										$memberstats[$key]['fc']['wronreas'] = $memberstats[$key]['fc']['wronreas'] . "Maximum Crafter class level (".$memberstats[$key]['levels']['curr']['maxcraft'].") is lower than required (".$posrank['requirements']['mincraftlvl'].").";
	      										$memberstats[$key]['fc']['wronprom'] = true;
	      									} else {
	      										$skiprank = true;
	      									}
	      								}
									#Check if Gatherer level is enough
	      								if (!($memberstats[$key]['levels']['curr']['maxgath'] >= $posrank['requirements']['mingathlvl'])) {
										#Check if rank is wrongly assigned
	      									if ($memberstats[$key]['fc']['rank'] == $rankkey && $memberstats[$key]['fc']['rankover'] == false) {
	      										$memberstats[$key]['fc']['wronreas'] = $memberstats[$key]['fc']['wronreas'] . "Maximum Gatherer class level (".$memberstats[$key]['levels']['curr']['maxgath'].") is lower than required (".$posrank['requirements']['mingathlvl'].").";
	      										$memberstats[$key]['fc']['wronprom'] = true;
	      									} else {
	      										$skiprank = true;
	      									}
	      								}
									#Check if member has been in the Company long enough
	      								if (!($curtime - $memberstats[$key]['joined'] >= $posrank['requirements']['minage'])) {
										#Check if rank is wrongly assigned
	      									if ($memberstats[$key]['fc']['rank'] == $rankkey && $memberstats[$key]['fc']['rankover'] == false) {
	      										$memberstats[$key]['fc']['wronreas'] = $memberstats[$key]['fc']['wronreas'] . "Number of days in company (".intval(($curtime - $memberstats[$key]['joined'])/86400).") is lower than required (".intval($posrank['requirements']['minage']/86400).").";
	      										$memberstats[$key]['fc']['wronprom'] = true;
	      									} else {
	      										$skiprank = true;
	      									}
	      								}
									#If the rank is not current (and wrongly assigned) and we are not skipping it for one reason or another, check if current rank is higher and then skip it, if it is
									if ($memberstats[$key]['fc']['wronprom'] == false && $skiprank == false) {
										if ($memberstats[$key]['fc']['ranklvl'] < $posrank['level']) {
											$skiprank = true;
										}
									}
	      								if ($skiprank == false) {
										#If we are not skipping, check if there was no rank change recently or if we have a case of wrong assignment
	      									if (($curtime - $memberstats[$key]['fc']['ranklvlupd']) / 86400 >= $fcconfig['rankchage'] || $memberstats[$key]['fc']['wronprom'] == true) {
											#Skip rank if member already has it
	      										if ($memberstats[$key]['fc']['rank'] !== $rankkey) {
												#Populate list of possible or alternative promotions
	      											if ($memberstats[$key]['fc']['ranklvl'] != $posrank['level']) {
	      												if (is_null($posrank['requirements']['recrank'])) {
	      													$memberstats[$key]['fc']['nextprom'] = $memberstats[$key]['fc']['nextprom'].",".$rankkey;
	      												} else {
	      													if ($posrank['requirements']['recrank'] == $memberstats[$key]['fc']['rank']) {
	      														$memberstats[$key]['fc']['recoprom'] = $memberstats[$key]['fc']['recoprom'].",".$rankkey;
	      													} else {
	      														$memberstats[$key]['fc']['nextprom'] = $memberstats[$key]['fc']['nextprom'].",".$rankkey;
	      													}
	      												}
	      											} else {
	      												$memberstats[$key]['fc']['altprom'] = $memberstats[$key]['fc']['altprom'].",".$rankkey;
	      											}
	      										}
	      									}
	      								}
								}
							}
						}
					}
					#Cleaning up the promotions
					if ($memberstats[$key]['fc']['nextprom'] != "") {
						$memberstats[$key]['fc']['nextprom'] = ltrim($memberstats[$key]['fc']['nextprom'], ",");
					} else {
						if ($memberstats[$key]['fc']['wronprom'] == true) {
							foreach ($fcranks as $rankkey=>$posrank) {
								#If no ranks were suggested, suggest lowest one
								if ($posrank['level'] == $fcconfig['defrank']) {
									$memberstats[$key]['fc']['nextprom'] = $memberstats[$key]['fc']['nextprom'].",".$rankkey;
								}
							}
							if ($memberstats[$key]['fc']['nextprom'] != "") {
								$memberstats[$key]['fc']['nextprom'] = ltrim($memberstats[$key]['fc']['nextprom'], ",");
							}
						}
					}
					if ($memberstats[$key]['fc']['recoprom'] != "") {
						$memberstats[$key]['fc']['recoprom'] = ltrim($memberstats[$key]['fc']['recoprom'], ",");
					}
					if ($memberstats[$key]['fc']['altprom'] != "") {
						$memberstats[$key]['fc']['altprom'] = ltrim($memberstats[$key]['fc']['altprom'], ",");
					}
				}
			}
		}
		#Write the data to the file
		file_put_contents("./cache/".$fcid."/members.json", json_encode($memberstats, JSON_PRETTY_PRINT));
	} else {
		file_put_contents("./cache/".$fcid."/members.json", json_encode($memberstats, JSON_PRETTY_PRINT));
	}
	error_reporting($preverrors);
} else {
	$memberstats = json_decode(file_get_contents("./cache/".$fcid."/members.json"), true);
}
unset($api);

#Refresh main page if required
if ($refreshpage == true) {
//<script type=\"text/javascript\">document.body.scrollTop = document.body.scrollHeight - document.body.clientHeight;</script>
	Echo "Refreshing page in 5 seconds...<br><script type=\"text/javascript\">document.body.scrollTop = document.body.scrollHeight - document.body.clientHeight;</script>";
	ob_flush();
	flush();
	sleep(1);
	Echo "Refreshing page in 4 seconds...<br><script type=\"text/javascript\">document.body.scrollTop = document.body.scrollHeight - document.body.clientHeight;</script>";
	ob_flush();
	flush();
	sleep(1);
	Echo "Refreshing page in 3 seconds...<br><script type=\"text/javascript\">document.body.scrollTop = document.body.scrollHeight - document.body.clientHeight;</script>";
	ob_flush();
	flush();
	sleep(1);
	Echo "Refreshing page in 2 seconds...<br><script type=\"text/javascript\">document.body.scrollTop = document.body.scrollHeight - document.body.clientHeight;</script>";
	ob_flush();
	flush();
	sleep(1);
	Echo "Refreshing page in 1 second...<script type=\"text/javascript\">document.body.scrollTop = document.body.scrollHeight - document.body.clientHeight;</script>";
	ob_flush();
	flush();
	sleep(1);
	echo "</div>";
	echo "<script type=\"text/javascript\">window.top.location.reload(true);</script>";
} else {
	echo $fcpage;
}
?>