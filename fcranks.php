<meta charset="UTF-8">
<?php
#Back-end initialization
require_once 'functions.php';
require_once 'config.php';
misdircreate();
$curtime=time();
$fcranks=json_decode(file_get_contents('./fcranks.json'), true);

#Checks if rank name was provided
echo "<head>
		<link rel=\"stylesheet\" type=\"text/css\" href=\"style.css\">
		</head>";
if (empty($_GET['fcname'])) {
	$fcname = "";
	$fcpage = $fcpage . "<title>Full Ranks List</title>";
} else {
	$fcname = $_GET['fcname'];
	$fcpage = $fcpage . "<title>Rank Details: ".$fcname."</title>";
	if (!(array_key_exists($fcname, $fcranks))) {
		echo $fcpage;
		if ($modrw == true) {
			echo "Wrong rank selected. <a target=\"_blank\" href=\"./rank\">Full list</a>";
		} else {
			echo "Wrong rank selected. <a target=\"_blank\" href=\"./fcranks.php\">Full list</a>";
		}
		exit;
	}
}

#Output the table static data (row names)
$fcpage = $fcpage . "<table width=\"872px\" border=1 class=\"memberstbl\">";
$rankname="<tr><td colspan=\"4\">Rank Name";
if ($fcname != "") {
	if ($modrw == true) {
		$rankname=$rankname." <a target=\"_blank\" href=\"./rank\">Full list</a>";
	} else {
		$rankname=$rankname." <a target=\"_blank\" href=\"./fcranks.php\">Full list</a>";
	}
}
$rankname=$rankname."</td>";
$ranklvl="<tr><td colspan=\"4\">Rank Level</td>";
$minage="<tr><td rowspan=\"11\" colspan=\"3\">R<br>
E<br>
Q<br>
U<br>
I<br>
R<br>
E<br>
M<br>
E<br>
N<br>
T<br>
S</td><td>Days in FC</td>";
$minfclvl="<tr><td>Min. Rank Level</td>";
$recrank="<tr><td>Recommended Rank</td>";
$classtypes="<tr><td>Class Types</td>";
$mintotlvl="<tr><td>Total Level</td>";
$mindpslvl="<tr><td>Min. DPS Level</td>";
$mintanklvl="<tr><td>Min. Tank Level</td>";
$minheallvl="<tr><td>Min. Heal Level</td>";
$mincraftlvl="<tr><td>Min. Crafter Level</td>";
$mingathlvl="<tr><td>Min. Gatherer Level</td>";
$howto="<tr><td>Special</td>";

$CompanyProfile = "<tr><td rowspan=\"64\">P<br>
E<br>
R<br>
M<br>
I<br>
S<br>
S<br>
I<br>
O<br>
N<br>
S</td><td rowspan=\"12\">G<br>
E<br>
N<br>
E<br>
R<br>
A<br>
L</td><td rowspan=\"3\">Settings</td><td>Company Profile</td>";
$RankSettings = "<tr><td>Rank Settings</td>";
$CrestDesign = "<tr><td>Crest Design</td>";
$Invitations = "<tr><td rowspan=\"4\">Members</td><td>Invitations</td>";
$MemberDismissal = "<tr><td>Member Dismissal</td>";
$PromotionDemotion = "<tr><td>Promotion\Demotion</td>";
$Applications = "<tr><td>Applications</td>";
$CompanyBoard = "<tr><td rowspan=\"2\">Community</td><td>Company Board</td>";
$ShortMessage = "<tr><td>Short Message</td>";
$CompanyCredits = "<tr><td rowspan=\"3\">Actions</td><td>Company Credits</td>";
$ExecutingActions = "<tr><td>Executing Actions</td>";
$DiscardingActions = "<tr><td>Discarding Actions</td>";
$Items1 = "<tr><td rowspan=\"5\" colspan=\"2\">Chest Access</td><td>Items 1</td>";
$Items2 = "<tr><td>Items 2</td>";
$Items3 = "<tr><td>Items 3</td>";
$Crystals = "<tr><td>Crystals</td>";
$Gil = "<tr><td>Gil</td>";
$EstateHallAccess = "<tr><td rowspan=\"11\">H<br>
O<br>
U<br>
S<br>
I<br>
N<br>
G<br><br> 
1</td><td rowspan=\"4\">Settings</td><td>Estate Hall Access</td>";
$EstateRenaming = "<tr><td>Estate Renaming</td>";
$GreetingsCustomization = "<tr><td>Greetings Customization</td>";
$GuestAccessSettings = "<tr><td>Guest Access Settings</td>";
$PurchaseLand = "<tr><td rowspan=\"2\">Estate</td><td>Purchase\Relinquish Land</td>";
$HallConstruction = "<tr><td>Hall Construction\Removal</td>";
$HallRemodeling = "<tr><td rowspan=\"5\">Customization</td><td>Hall Remodeling</td>";
$Paint = "<tr><td>Paint Fixtures\Furnishings</td>";
$FurnishingPrivileges = "<tr><td>Furnishing Privileges</td>";
$RetainerDispatchment = "<tr><td>Retainer Dispatchment</td>";
$AetherialWheelUse = "<tr><td>Aetherial Wheel Use</td>";
$Planting = "<tr><td rowspan=\"6\">H<br>
O<br>
U<br>
S<br>
I<br>
N<br>
G<br><br> 
2</td><td rowspan=\"3\">Garden</td><td>Planting</td>";
$Harvesting = "<tr><td>Harvesting</td>";
$CropDisposal = "<tr><td>Crop Disposal</td>";
$StablingChocobos = "<tr><td rowspan=\"2\">Chocobos</td><td>Stabling Chocobos</td>";
$TrainingMembersChocobos = "<tr><td>Training Members' Chocobos</td>";
$OrchestrionOperation = "<tr><td>Orchestrion</td><td>Orchestrion Operation</td>";
$WorkshopConstruction = "<tr><td rowspan=\"13\">W<br>
O<br>
R<br>
K<br>
S<br>
H<br>
O<br>
P</td><td>Workshop</td><td>Workshop Construction\Removal</td>";
$ProjectCommencement = "<tr><td rowspan=\"5\">Company Projects</td><td>Project Commencement\Continuation</td>";
$ProjectMaterialDelivery = "<tr><td>Project Material Delivery</td>";
$ProjectProgression = "<tr><td>Project Progression</td>";
$ProjectItemCollection = "<tr><td>Project Item Collection</td>";
$PrototypeCreation = "<tr><td>Prototype Creation</td>";
$AirshipRegistration = "<tr><td rowspan=\"7\">Airships</td><td>Airship Registration\Dismantling</td>";
$AirshipOutfitting = "<tr><td>Airships Outfitting</td>";
$AirshipColor = "<tr><td>Airship Color Customization</td>";
$AirshipRenaming = "<tr><td>Airship Renaming</td>";
$ExploratoryVoyageDeployment = "<tr><td>Exploratory Voyage Deployment\Recall</td>";
$ExploratoryVoyageFinalization = "<tr><td>Exploratory Voyage Finalization</td>";
$ExploratoryMissionEmbarkation = "<tr><td>Exploratory Mission Embarkation</td>";

$rankcount = 0;
foreach($fcranks as $key=>$rank) {
	$lastranklvl = $rank['level'];
	break;
}

#SHow columns with actual data
foreach($fcranks as $key=>$rank) {
	if (strtolower ($fcname) == strtolower ($key) || $fcname == "") {
		$rankname = $rankname . "<td>".$key."</td>";
		if ($fcname != "") {
			$ranklvl = $ranklvl . "<td style=\"text-align:center\">".$rank['level']."</td>";
		} else {
			if ($rank['level'] != $lastranklvl) {
				$ranklvl = $ranklvl . "<td style=\"text-align:center\" colspan=\"".$rankcount."\">".$lastranklvl."</td>";
				$rankcount = 0;
				$lastranklvl = $rank['level'];
			}
			$rankcount++;
		}
		if ($rank['requirements']['minage'] != 0) {
			$minage = $minage . "<td>".intval($rank['requirements']['minage']/86400)."</td>";
		} else {
			$minage = $minage . "<td></td>";
		}
		if ($rank['requirements']['minfclvl'] != 0) {
			$minfclvl = $minfclvl . "<td>".$rank['requirements']['minfclvl']."</td>";
		} else {
			$minfclvl = $minfclvl . "<td></td>";
		}
		if (!is_null($rank['requirements']['recrank'])) {
			$recrank = $recrank . "<td>".$rank['requirements']['recrank']."</td>";
		} else {
			$recrank = $recrank . "<td></td>";
		}
		if ($rank['requirements']['classtypes'] != 0) {
			$classtypes = $classtypes . "<td>".$rank['requirements']['classtypes']."</td>";
		} else {
			$classtypes = $classtypes . "<td></td>";
		}
		if ($rank['requirements']['mintotlvl'] != 0) {
			$mintotlvl = $mintotlvl . "<td>".$rank['requirements']['mintotlvl']."</td>";
		} else {
			$mintotlvl = $mintotlvl . "<td></td>";
		}
		if ($rank['requirements']['mindpslvl'] != 0) {
			$mindpslvl = $mindpslvl . "<td>".$rank['requirements']['mindpslvl']."</td>";
		} else {
			$mindpslvl = $mindpslvl . "<td></td>";
		}
		if ($rank['requirements']['mintanklvl'] != 0) {
			$mintanklvl = $mintanklvl . "<td>".$rank['requirements']['mintanklvl']."</td>";
		} else {
			$mintanklvl = $mintanklvl . "<td></td>";
		}
		if ($rank['requirements']['minheallvl'] != 0) {
			$minheallvl = $minheallvl . "<td>".$rank['requirements']['minheallvl']."</td>";
		} else {
			$minheallvl = $minheallvl . "<td></td>";
		}
		if ($rank['requirements']['mincraftlvl'] != 0) {
			$mincraftlvl = $mincraftlvl . "<td>".$rank['requirements']['mincraftlvl']."</td>";
		} else {
			$mincraftlvl = $mincraftlvl . "<td></td>";
		}
		if ($rank['requirements']['mingathlvl'] != 0) {
			$mingathlvl = $mingathlvl . "<td>".$rank['requirements']['mingathlvl']."</td>";
		} else {
			$mingathlvl = $mingathlvl . "<td></td>";
		}
		if ($rank['howto'] == "") {
			$howto = $howto . "<td></td>";
		} else {
			$howto = $howto . "<td style=\"text-align:center\"><span title=\"".$rank['howto']."\"><a href=\"#\">[?]</a></span></td>";
		}
	
		if ($rank['generalsettings']['Settings']['CompanyProfile'] == true) {
			$CompanyProfile = $CompanyProfile . "<td style=\"text-align:center;background-color:#b2d8b2\">+</td>";
		} else {
			$CompanyProfile = $CompanyProfile . "<td style=\"text-align:center;background-color:#ffcccc\">-</td>";
		}
		if ($rank['generalsettings']['Settings']['RankSettings'] == true) {
			$RankSettings = $RankSettings . "<td style=\"text-align:center;background-color:#b2d8b2\">+</td>";
		} else {
			$RankSettings = $RankSettings . "<td style=\"text-align:center;background-color:#ffcccc\">-</td>";
		}
		if ($rank['generalsettings']['Settings']['CrestDesign'] == true) {
			$CrestDesign = $CrestDesign . "<td style=\"text-align:center;background-color:#b2d8b2\">+</td>";
		} else {
			$CrestDesign = $CrestDesign . "<td style=\"text-align:center;background-color:#ffcccc\">-</td>";
		}
		if ($rank['generalsettings']['Members']['Invitations'] == true) {
			$Invitations = $Invitations . "<td style=\"text-align:center;background-color:#b2d8b2\">+</td>";
		} else {
			$Invitations = $Invitations . "<td style=\"text-align:center;background-color:#ffcccc\">-</td>";
		}
		if ($rank['generalsettings']['Members']['MemberDismissal'] == true) {
			$MemberDismissal = $MemberDismissal . "<td style=\"text-align:center;background-color:#b2d8b2\">+</td>";
		} else {
			$MemberDismissal = $MemberDismissal . "<td style=\"text-align:center;background-color:#ffcccc\">-</td>";
		}
		if ($rank['generalsettings']['Members']['PromotionDemotion'] == true) {
			$PromotionDemotion = $PromotionDemotion . "<td style=\"text-align:center;background-color:#b2d8b2\">+</td>";
		} else {
			$PromotionDemotion = $PromotionDemotion . "<td style=\"text-align:center;background-color:#ffcccc\">-</td>";
		}
		if ($rank['generalsettings']['Members']['Applications'] == true) {
			$Applications = $Applications . "<td style=\"text-align:center;background-color:#b2d8b2\">+</td>";
		} else {
			$Applications = $Applications . "<td style=\"text-align:center;background-color:#ffcccc\">-</td>";
		}
		if ($rank['generalsettings']['Community']['CompanyBoard'] == true) {
			$CompanyBoard = $CompanyBoard . "<td style=\"text-align:center;background-color:#b2d8b2\">+</td>";
		} else {
			$CompanyBoard = $CompanyBoard . "<td style=\"text-align:center;background-color:#ffcccc\">-</td>";
		}
		if ($rank['generalsettings']['Community']['ShortMessage'] == true) {
			$ShortMessage = $ShortMessage . "<td style=\"text-align:center;background-color:#b2d8b2\">+</td>";
		} else {
			$ShortMessage = $ShortMessage . "<td style=\"text-align:center;background-color:#ffcccc\">-</td>";
		}
		if ($rank['generalsettings']['Actions']['CompanyCredits'] == true) {
			$CompanyCredits = $CompanyCredits . "<td style=\"text-align:center;background-color:#b2d8b2\">+</td>";
		} else {
			$CompanyCredits = $CompanyCredits . "<td style=\"text-align:center;background-color:#ffcccc\">-</td>";
		}
		if ($rank['generalsettings']['Actions']['ExecutingActions'] == true) {
			$ExecutingActions = $ExecutingActions . "<td style=\"text-align:center;background-color:#b2d8b2\">+</td>";
		} else {
			$ExecutingActions = $ExecutingActions . "<td style=\"text-align:center;background-color:#ffcccc\">-</td>";
		}
		if ($rank['generalsettings']['Actions']['DiscardingActions'] == true) {
			$DiscardingActions = $DiscardingActions . "<td style=\"text-align:center;background-color:#b2d8b2\">+</td>";
		} else {
			$DiscardingActions = $DiscardingActions . "<td style=\"text-align:center;background-color:#ffcccc\">-</td>";
		}
	
		if ($rank['ChestAccess']['Items1'] == 0) {
			$Items1 = $Items1 . "<td>No Access</td>";
		} elseif ($rank['ChestAccess']['Items1'] == 1) {
			$Items1 = $Items1 . "<td>View Only</td>";
		} elseif ($rank['ChestAccess']['Items1'] == 2) {
			$Items1 = $Items1 . "<td>Deposit Only</td>";
		} elseif ($rank['ChestAccess']['Items1'] == 3) {
			$Items1 = $Items1 . "<td>Full Access</td>";
		}
		if ($rank['ChestAccess']['Items2'] == 0) {
			$Items2 = $Items2 . "<td>No Access</td>";
		} elseif ($rank['ChestAccess']['Items2'] == 1) {
			$Items2 = $Items2 . "<td>View Only</td>";
		} elseif ($rank['ChestAccess']['Items2'] == 2) {
			$Items2 = $Items2 . "<td>Deposit Only</td>";
		} elseif ($rank['ChestAccess']['Items2'] == 3) {
			$Items2 = $Items2 . "<td>Full Access</td>";
		}
		if ($rank['ChestAccess']['Items3'] == 0) {
			$Items3 = $Items3 . "<td>No Access</td>";
		} elseif ($rank['ChestAccess']['Items3'] == 1) {
			$Items3 = $Items3 . "<td>View Only</td>";
		} elseif ($rank['ChestAccess']['Items3'] == 2) {
			$Items3 = $Items3 . "<td>Deposit Only</td>";
		} elseif ($rank['ChestAccess']['Items3'] == 3) {
			$Items3 = $Items3 . "<td>Full Access</td>";
		}
		if ($rank['ChestAccess']['Crystals'] == 0) {
			$Crystals = $Crystals . "<td>No Access</td>";
		} elseif ($rank['ChestAccess']['Crystals'] == 1) {
			$Crystals = $Crystals . "<td>View Only</td>";
		} elseif ($rank['ChestAccess']['Crystals'] == 2) {
			$Crystals = $Crystals . "<td>Deposit Only</td>";
		} elseif ($rank['ChestAccess']['Crystals'] == 3) {
			$Crystals = $Crystals . "<td>Full Access</td>";
		}
		if ($rank['ChestAccess']['Gil'] == 0) {
			$Gil = $Gil . "<td>No Access</td>";
		} elseif ($rank['ChestAccess']['Gil'] == 1) {
			$Gil = $Gil . "<td>View Only</td>";
		} elseif ($rank['ChestAccess']['Gil'] == 2) {
			$Gil = $Gil . "<td>Deposit Only</td>";
		} elseif ($rank['ChestAccess']['Gil'] == 3) {
			$Gil = $Gil . "<td>Full Access</td>";
		}
	
		if ($rank['Housing1']['Settings']['EstateHallAccess'] == true) {
			$EstateHallAccess = $EstateHallAccess . "<td style=\"text-align:center;background-color:#b2d8b2\">+</td>";
		} else {
			$EstateHallAccess = $EstateHallAccess . "<td style=\"text-align:center;background-color:#ffcccc\">-</td>";
		}
		if ($rank['Housing1']['Settings']['EstateRenaming'] == true) {
			$EstateRenaming = $EstateRenaming . "<td style=\"text-align:center;background-color:#b2d8b2\">+</td>";
		} else {
			$EstateRenaming = $EstateRenaming . "<td style=\"text-align:center;background-color:#ffcccc\">-</td>";
		}
		if ($rank['Housing1']['Settings']['GreetingsCustomization'] == true) {
			$GreetingsCustomization = $GreetingsCustomization . "<td style=\"text-align:center;background-color:#b2d8b2\">+</td>";
		} else {
			$GreetingsCustomization = $GreetingsCustomization . "<td style=\"text-align:center;background-color:#ffcccc\">-</td>";
		}
		if ($rank['Housing1']['Settings']['GuestAccessSettings'] == true) {
			$GuestAccessSettings = $GuestAccessSettings . "<td style=\"text-align:center;background-color:#b2d8b2\">+</td>";
		} else {
			$GuestAccessSettings = $GuestAccessSettings . "<td style=\"text-align:center;background-color:#ffcccc\">-</td>";
		}
		if ($rank['Housing1']['Estate']['PurchaseLand'] == true) {
			$PurchaseLand = $PurchaseLand . "<td style=\"text-align:center;background-color:#b2d8b2\">+</td>";
		} else {
			$PurchaseLand = $PurchaseLand . "<td style=\"text-align:center;background-color:#ffcccc\">-</td>";
		}
		if ($rank['Housing1']['Estate']['HallConstruction'] == true) {
			$HallConstruction = $HallConstruction . "<td style=\"text-align:center;background-color:#b2d8b2\">+</td>";
		} else {
			$HallConstruction = $HallConstruction . "<td style=\"text-align:center;background-color:#ffcccc\">-</td>";
		}
		if ($rank['Housing1']['Customization']['HallRemodeling'] == true) {
			$HallRemodeling = $HallRemodeling . "<td style=\"text-align:center;background-color:#b2d8b2\">+</td>";
		} else {
			$HallRemodeling = $HallRemodeling . "<td style=\"text-align:center;background-color:#ffcccc\">-</td>";
		}
		if ($rank['Housing1']['Customization']['Paint'] == true) {
			$Paint = $Paint . "<td style=\"text-align:center;background-color:#b2d8b2\">+</td>";
		} else {
			$Paint = $Paint . "<td style=\"text-align:center;background-color:#ffcccc\">-</td>";
		}
	
		if ($rank['Housing1']['Customization']['FurnishingPrivileges'] == 0) {
			$FurnishingPrivileges = $FurnishingPrivileges . "<td>No Privileges</td>";
		} elseif ($rank['Housing1']['Customization']['FurnishingPrivileges'] == 1) {
			$FurnishingPrivileges = $FurnishingPrivileges . "<td>Placement</td>";
		} elseif ($rank['Housing1']['Customization']['FurnishingPrivileges'] == 2) {
			$FurnishingPrivileges = $FurnishingPrivileges . "<td>Full Access</td>";
		}
	
		if ($rank['Housing1']['Customization']['RetainerDispatchment'] == true) {
			$RetainerDispatchment = $RetainerDispatchment . "<td style=\"text-align:center;background-color:#b2d8b2\">+</td>";
		} else {
			$RetainerDispatchment = $RetainerDispatchment . "<td style=\"text-align:center;background-color:#ffcccc\">-</td>";
		}
		if ($rank['Housing1']['Customization']['AetherialWheelUse'] == true) {
			$AetherialWheelUse = $AetherialWheelUse . "<td style=\"text-align:center;background-color:#b2d8b2\">+</td>";
		} else {
			$AetherialWheelUse = $AetherialWheelUse . "<td style=\"text-align:center;background-color:#ffcccc\">-</td>";
		}
	
		if ($rank['Housing2']['Garden']['Planting'] == true) {
			$Planting = $Planting . "<td style=\"text-align:center;background-color:#b2d8b2\">+</td>";
		} else {
			$Planting = $Planting . "<td style=\"text-align:center;background-color:#ffcccc\">-</td>";
		}
		if ($rank['Housing2']['Garden']['Harvesting'] == true) {
			$Harvesting = $Harvesting . "<td style=\"text-align:center;background-color:#b2d8b2\">+</td>";
		} else {
			$Harvesting = $Harvesting . "<td style=\"text-align:center;background-color:#ffcccc\">-</td>";
		}
		if ($rank['Housing2']['Garden']['CropDisposal'] == true) {
			$CropDisposal = $CropDisposal . "<td style=\"text-align:center;background-color:#b2d8b2\">+</td>";
		} else {
			$CropDisposal = $CropDisposal . "<td style=\"text-align:center;background-color:#ffcccc\">-</td>";
		}
		if ($rank['Housing2']['Chocobos']['StablingChocobos'] == true) {
			$StablingChocobos = $StablingChocobos . "<td style=\"text-align:center;background-color:#b2d8b2\">+</td>";
		} else {
			$StablingChocobos = $StablingChocobos . "<td style=\"text-align:center;background-color:#ffcccc\">-</td>";
		}
		if ($rank['Housing2']['Chocobos']['TrainingMembersChocobos'] == true) {
			$TrainingMembersChocobos = $TrainingMembersChocobos . "<td style=\"text-align:center;background-color:#b2d8b2\">+</td>";
		} else {
			$TrainingMembersChocobos = $TrainingMembersChocobos . "<td style=\"text-align:center;background-color:#ffcccc\">-</td>";
		}
		if ($rank['Housing2']['Orchestrion']['OrchestrionOperation'] == true) {
			$OrchestrionOperation = $OrchestrionOperation . "<td style=\"text-align:center;background-color:#b2d8b2\">+</td>";
		} else {
			$OrchestrionOperation = $OrchestrionOperation . "<td style=\"text-align:center;background-color:#ffcccc\">-</td>";
		}
	
		if ($rank['Workshop']['Workshop']['WorkshopConstruction'] == true) {
			$WorkshopConstruction = $WorkshopConstruction . "<td style=\"text-align:center;background-color:#b2d8b2\">+</td>";
		} else {
			$WorkshopConstruction = $WorkshopConstruction . "<td style=\"text-align:center;background-color:#ffcccc\">-</td>";
		}
		if ($rank['Workshop']['Company Projects']['ProjectCommencement'] == true) {
			$ProjectCommencement = $ProjectCommencement . "<td style=\"text-align:center;background-color:#b2d8b2\">+</td>";
		} else {
			$ProjectCommencement = $ProjectCommencement . "<td style=\"text-align:center;background-color:#ffcccc\">-</td>";
		}
		if ($rank['Workshop']['Company Projects']['ProjectMaterialDelivery'] == true) {
			$ProjectMaterialDelivery = $ProjectMaterialDelivery . "<td style=\"text-align:center;background-color:#b2d8b2\">+</td>";
		} else {
			$ProjectMaterialDelivery = $ProjectMaterialDelivery . "<td style=\"text-align:center;background-color:#ffcccc\">-</td>";
		}
		if ($rank['Workshop']['Company Projects']['ProjectProgression'] == true) {
			$ProjectProgression = $ProjectProgression . "<td style=\"text-align:center;background-color:#b2d8b2\">+</td>";
		} else {
			$ProjectProgression = $ProjectProgression . "<td style=\"text-align:center;background-color:#ffcccc\">-</td>";
		}
		if ($rank['Workshop']['Company Projects']['ProjectItemCollection'] == true) {
			$ProjectItemCollection = $ProjectItemCollection . "<td style=\"text-align:center;background-color:#b2d8b2\">+</td>";
		} else {
			$ProjectItemCollection = $ProjectItemCollection . "<td style=\"text-align:center;background-color:#ffcccc\">-</td>";
		}
		if ($rank['Workshop']['Company Projects']['PrototypeCreation'] == true) {
			$PrototypeCreation = $PrototypeCreation . "<td style=\"text-align:center;background-color:#b2d8b2\">+</td>";
		} else {
			$PrototypeCreation = $PrototypeCreation . "<td style=\"text-align:center;background-color:#ffcccc\">-</td>";
		}
		if ($rank['Workshop']['Airships']['AirshipRegistration'] == true) {
			$AirshipRegistration = $AirshipRegistration . "<td style=\"text-align:center;background-color:#b2d8b2\">+</td>";
		} else {
			$AirshipRegistration = $AirshipRegistration . "<td style=\"text-align:center;background-color:#ffcccc\">-</td>";
		}
		if ($rank['Workshop']['Airships']['AirshipOutfitting'] == true) {
			$AirshipOutfitting = $AirshipOutfitting . "<td style=\"text-align:center;background-color:#b2d8b2\">+</td>";
		} else {
			$AirshipOutfitting = $AirshipOutfitting . "<td style=\"text-align:center;background-color:#ffcccc\">-</td>";
		}
		if ($rank['Workshop']['Airships']['AirshipColor'] == true) {
			$AirshipColor = $AirshipColor . "<td style=\"text-align:center;background-color:#b2d8b2\">+</td>";
		} else {
			$AirshipColor = $AirshipColor . "<td style=\"text-align:center;background-color:#ffcccc\">-</td>";
		}
		if ($rank['Workshop']['Airships']['AirshipRenaming'] == true) {
			$AirshipRenaming = $AirshipRenaming . "<td style=\"text-align:center;background-color:#b2d8b2\">+</td>";
		} else {
			$AirshipRenaming = $AirshipRenaming . "<td style=\"text-align:center;background-color:#ffcccc\">-</td>";
		}
		if ($rank['Workshop']['Airships']['ExploratoryVoyageDeployment'] == true) {
			$ExploratoryVoyageDeployment = $ExploratoryVoyageDeployment . "<td style=\"text-align:center;background-color:#b2d8b2\">+</td>";
		} else {
			$ExploratoryVoyageDeployment = $ExploratoryVoyageDeployment . "<td style=\"text-align:center;background-color:#ffcccc\">-</td>";
		}
		if ($rank['Workshop']['Airships']['ExploratoryVoyageFinalization'] == true) {
			$ExploratoryVoyageFinalization = $ExploratoryVoyageFinalization . "<td style=\"text-align:center;background-color:#b2d8b2\">+</td>";
		} else {
			$ExploratoryVoyageFinalization = $ExploratoryVoyageFinalization . "<td style=\"text-align:center;background-color:#ffcccc\">-</td>";
		}
		if ($rank['Workshop']['Airships']['ExploratoryMissionEmbarkation'] == true) {
			$ExploratoryMissionEmbarkation = $ExploratoryMissionEmbarkation . "<td style=\"text-align:center;background-color:#b2d8b2\">+</td>";
		} else {
			$ExploratoryMissionEmbarkation = $ExploratoryMissionEmbarkation . "<td style=\"text-align:center;background-color:#ffcccc\">-</td>";
		}
	}
}
if ($fcname == "") {
	$ranklvl = $ranklvl . "<td style=\"text-align:center\" colspan=\"".$rankcount."\">".$lastranklvl."</td>";
}


$rankname = $rankname . "</tr>";
$ranklvl = $ranklvl . "</tr>";
$minage = $minage . "</tr>";
$minfclvl = $minfclvl . "</tr>";
$recrank = $recrank . "</tr>";
$classtypes = $classtypes . "</tr>";
$mintotlvl = $mintotlvl . "</tr>";
$mindpslvl = $mindpslvl . "</tr>";
$mintanklvl = $mintanklvl . "</tr>";
$minheallvl = $minheallvl . "</tr>";
$mincraftlvl = $mincraftlvl . "</tr>";
$mingathlvl = $mingathlvl . "</tr>";
$howto = $howto . "</tr>";

$CompanyProfile = $CompanyProfile . "</tr>";
$RankSettings = $RankSettings . "</tr>";
$CrestDesign = $CrestDesign . "</tr>";
$Invitations = $Invitations . "</tr>";
$MemberDismissal = $MemberDismissal . "</tr>";
$PromotionDemotion = $PromotionDemotion . "</tr>";
$Applications = $Applications . "</tr>";
$CompanyBoard = $CompanyBoard . "</tr>";
$ShortMessage = $ShortMessage . "</tr>";
$CompanyCredits = $CompanyCredits . "</tr>";
$ExecutingActions = $ExecutingActions . "</tr>";
$DiscardingActions = $DiscardingActions . "</tr>";
$Items1 = $Items1 . "</tr>";
$Items2 = $Items2 . "</tr>";
$Items3 = $Items3 . "</tr>";
$Crystals = $Crystals . "</tr>";
$Gil = $Gil . "</tr>";
$EstateHallAccess = $EstateHallAccess . "</tr>";
$EstateRenaming = $EstateRenaming . "</tr>";
$GreetingsCustomization = $GreetingsCustomization . "</tr>";
$GuestAccessSettings = $GuestAccessSettings . "</tr>";
$PurchaseLand = $PurchaseLand . "</tr>";
$HallConstruction = $HallConstruction . "</tr>";
$HallRemodeling = $HallRemodeling . "</tr>";
$Paint = $Paint . "</tr>";
$FurnishingPrivileges = $FurnishingPrivileges . "</tr>";
$RetainerDispatchment = $RetainerDispatchment . "</tr>";
$AetherialWheelUse = $AetherialWheelUse . "</tr>";
$Planting = $Planting . "</tr>";
$Harvesting = $Harvesting . "</tr>";
$CropDisposal = $CropDisposal . "</tr>";
$StablingChocobos = $StablingChocobos . "</tr>";
$TrainingMembersChocobos = $TrainingMembersChocobos . "</tr>";
$OrchestrionOperation = $OrchestrionOperation . "</tr>";
$WorkshopConstruction = $WorkshopConstruction . "</tr>";
$ProjectCommencement = $ProjectCommencement . "</tr>";
$ProjectMaterialDelivery = $ProjectMaterialDelivery . "</tr>";
$ProjectProgression = $ProjectProgression . "</tr>";
$ProjectItemCollection = $ProjectItemCollection . "</tr>";
$PrototypeCreation = $PrototypeCreation . "</tr>";
$AirshipRegistration = $AirshipRegistration . "</tr>";
$AirshipOutfitting = $AirshipOutfitting . "</tr>";
$AirshipColor = $AirshipColor . "</tr>";
$AirshipRenaming = $AirshipRenaming . "</tr>";
$ExploratoryVoyageDeployment = $ExploratoryVoyageDeployment . "</tr>";
$ExploratoryVoyageFinalization = $ExploratoryVoyageFinalization . "</tr>";
$ExploratoryMissionEmbarkation = $ExploratoryMissionEmbarkation . "</tr>";

#Prepare the whole page
$fcpage = $fcpage.$rankname.$ranklvl.$minage.$minfclvl.$recrank.$classtypes.$mintotlvl.$mindpslvl.$mintanklvl.$minheallvl.$mincraftlvl.$mingathlvl.$howto.
$CompanyProfile.
$RankSettings.
$CrestDesign.
$Invitations.
$MemberDismissal.
$PromotionDemotion.
$Applications.
$CompanyBoard.
$ShortMessage.
$CompanyCredits.
$ExecutingActions.
$DiscardingActions.
$Items1.
$Items2.
$Items3.
$Crystals.
$Gil.
$EstateHallAccess.
$EstateRenaming.
$GreetingsCustomization.
$GuestAccessSettings.
$PurchaseLand.
$HallConstruction.
$HallRemodeling.
$Paint.
$FurnishingPrivileges.
$RetainerDispatchment.
$AetherialWheelUse.
$Planting.
$Harvesting.
$CropDisposal.
$StablingChocobos.
$TrainingMembersChocobos.
$OrchestrionOperation.
$WorkshopConstruction.
$ProjectCommencement.
$ProjectMaterialDelivery.
$ProjectProgression.
$ProjectItemCollection.
$PrototypeCreation.
$AirshipRegistration.
$AirshipOutfitting.
$AirshipColor.
$AirshipRenaming.
$ExploratoryVoyageDeployment.
$ExploratoryVoyageFinalization.
$ExploratoryMissionEmbarkation.
"</table>";


echo $fcpage;
?>