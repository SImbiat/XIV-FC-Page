# XIV Free Company Page Script
A small script to present some information about Free Company (guild) from [Final Fantasy XIV](http://eu.finalfantasyxiv.com)

It uses XIVSync by [@viion](https://github.com/viion/XIVPads-LodestoneAPI) to grab information from Lodestone.

## What it looks like?
At first glance it's just showing you the same stuff that Lodestone does: name of the Company, list of activities the Company participates in, plot information, number of members (and there avatars)

## What it really is?
It's way more than that, though. It's a tracking page. It tracks 2 main things: company weekly ranking and its members’ information.

Company ranking is showed as a small graph when hovering over the question mark near current ranking. Nothing fancy.

Main feature is tracking of members' stats. It tracks the following:
- Name change
- Free Company rank change
- Levels of all classes (not actively used in current version)
- Levels of all classes on time of joining
- Time of joining
- Time of level update
- Time of rank update

But even that is not all: this page suggests new ranks to existing members based on rank description you provide in a special file. It also provides description of each rank permissions.

## How to use
Main settings contain of 2 things:
- `$fcid` parameter in `config.php` which stands for the ID of your Free Company. For example, my Company is listed in Lodestone like this `http://eu.finalfantasyxiv.com/lodestone/freecompany/9234631035923213559/`. This means, that the ID is `9234631035923213559`
- Careful setup of `fcranks.json` file

`config.php` also has some extra styling options for you.

### fcranks.json
For script to properly work you need to carefully setup list of ranks actually present in-game. Here's how you set it up.
```
{
	"King": {
```
Name of the rank as seen in-game.
```
		"level": 0,
		"howto": "Create Free Company or get assigned by previous King",
```
Level of the rank, where `0` is the highest rank. For Mog Street Kids the lowest one is 6, which is used for those who has been inactive for too long. `howto` is used for _special_ requirements on acquiring the rank. If it's empty it will be based on the following array.
```
		"requirements": {
				"assign": false,
				"minage": 0,
				"minfclvl": 0,
				"recrank": null,
				"mintotlvl": 0,
				"mingathlvl": 0,
				"mincraftlvl": 0,
				"mindpslvl": 0,
				"minheallvl": 0,
				"mintanklvl": 0,
				"classtypes": 0
		},
```
`assign` is used to determine whether the rank should be used for next promotion suggestions. Set it to `false` to skip.

`minage` is for setting minimum number of dates to be present in company to be suggested this particular rank.

`minfclvl` is mimimum __current__ rank level to be suggested this particular rank. Set to `0` to disable.

`recrank` is a name of a rank which should be considered the direct predecessor of this one. For example, we have 2 DPS-based ranks with different permissions. Essentially, if a member has the lowest of them he will be suggested for the higher rank on priority.

`mintotlvl` is minimum Total Level required. Total Level stands for cumulative level for all classes. Set to `0` to disable.

`mingathlvl`, `mincraftlvl`, `mindpslvl`, `minheallvl`, `mintanklvl` are mimimum levels of gather, crafter, DPS, healer and tank classes required. For example, if you set `mintanklvl` to 60, it will mean, that to get this rank a member needs to have at least 1 tank class at level 60.

`classtypes` sets number of different classes that have to be of at least level 2. This is checked before all class specific levels, because you may want to promote some people only if they have, for example, both DPS, healer and tank.

All parameters below are for Free Company permissions, that should represent the same settings that you have in-game.
```
		"generalsettings": {
			"Settings": {
				"CompanyProfile": true,
				"RankSettings": true,
				"CrestDesign": true
			},
			"Members": {
				"Invitations": true,
				"MemberDismissal": true,
				"PromotionDemotion": true,
				"Applications": true
			},
			"Community": {
				"CompanyBoard": true,
				"ShortMessage": true
			},
			"Actions": {
				"CompanyCredits": true,
				"ExecutingActions": true,
				"DiscardingActions": true
			}
		},
		"ChestAccess": {
			"Items1": 3,
			"Items2": 3,
			"Items3": 3,
			"Crystals": 3,
			"Gil": 3
		},
```
For Chest Access, `0` means `No Access`, `1` means `View Only`, `2` means `Deposit Only`, `3` means `Full Access`.
```
		"Housing1": {
			"Settings": {
				"EstateHallAccess": true,
				"EstateRenaming": true,
				"GreetingsCustomization": true,
				"GuestAccessSettings": true
			},
			"Estate": {
				"PurchaseLand": true,
				"HallConstruction": true
			},
			"Customization": {
				"HallRemodeling": true,
				"Paint": true,
				"FurnishingPrivileges": 2,
```
For FurnishingPrivileges `0` means `No Access`, `1` means `Place`, `2` means `Place And Remove`.
```
				"RetainerDispatchment": true,
				"AetherialWheelUse": true
			}
		},
		"Housing2": {
			"Garden": {
				"Planting": true,
				"Harvesting": true,
				"CropDisposal": true
			},
			"Chocobos": {
				"StablingChocobos": true,
				"TrainingMembersChocobos": true
			},
			"Orchestrion": {
				"OrchestrionOperation": true
			}
		},
		"Workshop": {
			"Workshop": {
				"WorkshopConstruction": true
			},
			"Company Projects": {
				"ProjectCommencement": true,
				"ProjectMaterialDelivery": true,
				"ProjectProgression": true,
				"ProjectItemCollection": true,
				"PrototypeCreation": true
			},
			"Airships": {
				"AirshipRegistration": true,
				"AirshipOutfitting": true,
				"AirshipColor": true,
				"AirshipRenaming": true,
				"ExploratoryVoyageDeployment": true,
				"ExploratoryVoyageFinalization": true,
				"ExploratoryMissionEmbarkation": true
			}
		}
	}
}
```

## How tracking works?
Tracking system uses sub-folder `cache`. It has 2 other subfolders for storing images:
- `emblem` stores emblem images (it consists of 3 images)
- `ranks` stores images of company ranks

These folders are created automatically, if they do not exist.
The main juice are the files in there.

### freecompany.json
This file contains general Free Company information grabbed by XIVSync script, including list of members used further during updates to grab detailed info for them.

### ranking.json
This file contains information on the weekly company ranking: date the rank changed and the new rank. Does not get updated unless the date difference between current date and the date in the list is less than 7 days.

### members.json
This file contains all the members' information. The one grabbed by XIVSync and calculated by the tracker, including suggested ranks. There is one special parameter there called `rankover` which should be used as an override. The tracker also checks for correct assignments and in some cases you may want to ignore the _wrongly_ assigned ones. Generally, when you had a set of members __before__ running the tracker.
