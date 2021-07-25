<?php


namespace SSC\Data;


use pocketmine\utils\Config;
use SSC\main;

class PlayerConfigManager {


	const UPDATE_VERSION = "4";

	public static function update(string $name){
		$config = new Config(main::getMain()->getDataFolder() . "Player" . "/".$name.".yml", Config::YAML);
		if(self::isLatestVersion($config)){
			return;
		}
		if(self::getVersion($config)==3){
			self::Ver3toLatestVer($config,$name);
			return;
		}
		self::Ver2toLatestVer($config,$name);
		return;

	}

	public static function getVersion(Config $config){
		$config->exists("4")?$a=4:$config->exists("3")?$a=3:$a=2;
		return $a;
	}

	private static function isLatestVersion(Config $config){
		return $config->exists(self::UPDATE_VERSION);
	}

	private static function Ver2toLatestVer(Config $config,string $name){
		$data["IP"]=$config->get($name)["IP"];
		$data["KENGEN"]=$config->get($name)["KENGEN"];
		$data["HITOKOTO"]=$config->get($name)["HITOKOTO"];
		$data["LASTLOGIN"]=$config->get($name)["LASTLOGIN"];
		$data["KEIKOKU"]=$config->get($name)["KEIKOKU"];
		$data["SYOUGOU"]=$config->get($name)["SYOUGOU"];
		$data["ATAMA"]=$config->get($name)["ATAMA"];
		$data["GENGO"]=$config->get($name)["GENGO"];
		$data["XUID"]=$config->get($name)["XUID"];
		$data["JOB"]=$config->get($name)["JOB"];
		$data["KILL"]=$config->get($name)["KILL"];
		$data["DEATH"]=$config->get($name)["DEATH"];
		$data["RED"]=$config->get($name)["RED"];
		$data["GOLD"]=$config->get($name)["GOLD"];//アドバンスレベル変更時の累計経験値
		$data["SPECIAL"]=$config->get($name)["SPECIAL"];//レベルモード判定
		$data["ZERO"]=$config->get($name)["ZERO"];//レベル上る前の累計経験値
		$data["EVENT"]=$config->get($name)["EVENT"];
		$data["Sun"]=$config->get($name)["Sun"];
		$data["KOUSEKI"]=$config->get($name)["KOUSEKI"];
		$data["MOKUTEKI"]=$config->get($name)["MOKUTEKI"];
		$data["EXP"]=$config->get($name)["EXP"];//現在の経験値
		$data["LEVEL"]=$config->get($name)["LEVEL"];//レベル
		$data["EXPLEVEL"]=$config->get($name)["EXPLEVEL"];//レベルテーブルに達するまでの経験値
		$config->set(self::UPDATE_VERSION, array(
			"FIRST_IP"=>$data["IP"],
			"IP" => $data["IP"],
			"KENGEN" => $data["KENGEN"],
			"HITOKOTO" => $data["HITOKOTO"],
			"LASTLOGIN" => strtotime('now'),
			"KEIKOKU" => $data["KEIKOKU"],
			"SYOUGOU" => $data["SYOUGOU"],
			"ATAMA" => $data["ATAMA"],
			"GENGO" => $data["GENGO"],
			"XUID" => $data["XUID"],
			"JOB"=>$data["JOB"],
			"KILL"=>$data["KILL"],
			"DEATH"=>$data["DEATH"],
			"RED"=>$data["RED"],
			"EVENT"=>$data["EVENT"],
			"DAIRY"=>0,
			"Sun"=>$data["Sun"],
			"KOUSEKI"=>$data["KOUSEKI"],
			"MOKUTEKI"=>$data["MOKUTEKI"],
			"EXP"=>$data["EXP"],
			"LEVEL"=>$data["LEVEL"],
			"MAXTABLEEXP"=>$data["EXPLEVEL"],
			"OLDTABLEEXP"=>$data["ZERO"],
			"NORMALMAXEXP"=>$data["GOLD"],
			"ADVANCEMAXEXP"=>0,
			"LEVELMODE"=>$data["SPECIAL"],
			"CLAN"=>"",
			"LOGINBONUS"=>1,
			"DAIRY1"=>1,
			"DAIRY2"=>6,
			"DAIRY3"=>11,
			"DAIRYTASK1"=>0,
			"DAIRYTASK2"=>0,
			"DAIRYTASK3"=>0,
			"DAIRYGET1"=>false,
			"DAIRYGET2"=>false,
			"DAIRYGET3"=>false,
			"COAL"=>0,
			"LAPIS"=>0,
			"IRON"=>0,
			"REDSTONE"=>0,
			"GOLD"=>0,
			"DIAMOND"=>0,
			"EMERALD"=>0,
			"FLOWER"=>0,
			"BREAK"=>0,
			"PEACE"=>0,
			"WOOD"=>0,
			"STAY"=>0,
			"DAY"=>0,
			"WALK"=>0,
			"GACHA"=>0,
			"SLOT"=>0,
			"SHOPPING"=>0,
			"TRAPPIST"=>0,
			"GETLOGINBONUS"=>false,
			"REPEAT"=>1,
			"MAXREPEAT"=>1,
			"REPEATBONUS"=>false,
			"MAXKILLSTREAK"=>0,
			"FISH"=>0,
			"FISHING_ROD"=>false,
			"INVENTORY"=>false,
			"INVENTORY_OBJECT"=>10,
			"SPACESHIP_SIZE"=>1,
			"SPACESHIP_LEVEL"=>0,
			"SPACESHIP_COAL"=>0,
			"SPACESHIP_LAPIS"=>0,
			"SPACESHIP_IRON"=>0,
			"SPACESHIP_REDSTONE"=>0,
			"SPACESHIP_GOLD"=>0,
			"SPACESHIP_DIAMOND"=>0,
			"SPACESHIP_EMERALD"=>0,
			"SPACESHIP_EFFECT"=>0,
			"JUMP_EFFECT"=>0,
			"WARP_EFFECT"=>0,
			"SPARE1"=>0,
			"SPARE2"=>0,
			"SPARE3"=>0,
			"SPARE4"=>0,
			"SPARE5"=>0,
			"SPARE6"=>0,
			"SPARE7"=>0,
			"SPARE8"=>0,
			"SPARE9"=>0,
			"SPARE10"=>0,
			"SPARE11"=>0,
			"SPARE12"=>0,
			"SPARE13"=>0,
			"SPARE14"=>0,
			"SPARE15"=>0,
			"SPARE16"=>0,
			"SPARE17"=>0,
		));
		$config->remove($name);
		$config->save();
		$config->reload();
	}

	private static function Ver3toLatestVer(Config $config,string $name){
		$data["IP"]=$config->get("3")["IP"];
		$data["KENGEN"]=$config->get("3")["KENGEN"];
		$data["HITOKOTO"]=$config->get("3")["HITOKOTO"];
		$data["LASTLOGIN"]=$config->get("3")["LASTLOGIN"];
		$data["KEIKOKU"]=$config->get("3")["KEIKOKU"];
		$data["SYOUGOU"]=$config->get("3")["SYOUGOU"];
		$data["ATAMA"]=$config->get("3")["ATAMA"];
		$data["GENGO"]=$config->get("3")["GENGO"];
		$data["XUID"]=$config->get("3")["XUID"];
		$data["JOB"]=$config->get("3")["JOB"];
		$data["KILL"]=$config->get("3")["KILL"];
		$data["DEATH"]=$config->get("3")["DEATH"];
		$data["RED"]=$config->get("3")["RED"];
		$data["DAIRY"]=$config->get("3")["DAIRY"];
		$data["NORMALMAXEXP"]=$config->get("3")["NORMALMAXEXP"];//アドバンスレベル変更時の累計経験値
		$data["LEVELMODE"]=$config->get("3")["LEVELMODE"];//レベルモード判定
		$data["OLDTABLEEXP"]=$config->get("3")["OLDTABLEEXP"];//レベル上る前の累計経験値
		$data["MAXTABLEEXP"]=$config->get("3")["MAXTABLEEXP"];
		$data["EVENT"]=$config->get("3")["EVENT"];
		$data["Sun"]=$config->get("3")["Sun"];
		$data["KOUSEKI"]=$config->get("3")["KOUSEKI"];
		$data["MOKUTEKI"]=$config->get("3")["MOKUTEKI"];
		$data["EXP"]=$config->get("3")["EXP"];
		$data["LEVEL"]=$config->get("3")["LEVEL"];
		$data["CLAN"]=$config->get("3")["CLAN"];
		$data["LOGINBONUS"]=$config->get("3")["LOGINBONUS"];
		$data["DAIRY1"]=$config->get("3")["DAIRY1"];
		$data["DAIRY2"]=$config->get("3")["DAIRY2"];
		$data["DAIRY3"]=$config->get("3")["DAIRY3"];
		$data["DAIRYTASK1"]=$config->get("3")["DAIRYTASK1"];
		$data["DAIRYTASK2"]=$config->get("3")["DAIRYTASK2"];
		$data["DAIRYTASK3"]=$config->get("3")["DAIRYTASK3"];
		$data["DAIRYGET1"]=$config->get("3")["DAIRYGET1"];
		$data["DAIRYGET2"]=$config->get("3")["DAIRYGET2"];
		$data["DAIRYGET3"]=$config->get("3")["DAIRYGET3"];
		$data["COAL"]=$config->get("3")["COAL"];
		$data["LAPIS"]=$config->get("3")["LAPIS"];
		$data["IRON"]=$config->get("3")["IRON"];
		$data["REDSTONE"]=$config->get("3")["REDSTONE"];
		$data["GOLD"]=$config->get("3")["GOLD"];
		$data["DIAMOND"]=$config->get("3")["DIAMOND"];
		$data["EMERALD"]=$config->get("3")["EMERALD"];
		$data["FLOWER"]=$config->get("3")["FLOWER"];
		$data["BREAK"]=$config->get("3")["BREAK"];
		$data["PEACE"]=$config->get("3")["PEACE"];
		$data["WOOD"]=$config->get("3")["WOOD"];
		$data["STAY"]=$config->get("3")["STAY"];
		$data["DAY"]=$config->get("3")["DAY"];
		$data["WALK"]=$config->get("3")["WALK"];
		$data["GACHA"]=$config->get("3")["GACHA"];
		$data["SLOT"]=$config->get("3")["SLOT"];
		$data["SHOPPING"]=$config->get("3")["SHOPPING"];
		$data["TRAPPIST"]=$config->get("3")["TRAPPIST"];
		$data["GETLOGINBONUS"]=$config->get("3")["GETLOGINBONUS"];
		$data["REPEAT"]=$config->get("3")["REPEAT"];
		$data["MAXREPEAT"]=$config->get("3")["MAXREPEAT"];
		$data["MAXKILLSTREAK"]=$config->get("3")["MAXKILLSTREAK"];
		$data["REPEATBONUS"]=$config->get("3")["REPEATBONUS"];
		$data["SPARE1"]=$config->get("3")["SPARE1"];
		$data["SPARE2"]=$config->get("3")["SPARE2"];
		$config->set(self::UPDATE_VERSION, array(
			"FIRST_IP"=>$data["IP"],//追加
			"IP" => $data["IP"],
			"KENGEN" => $data["KENGEN"],
			"HITOKOTO" => $data["HITOKOTO"],
			"LASTLOGIN" => strtotime('now'),
			"KEIKOKU" => $data["KEIKOKU"],
			"SYOUGOU" => $data["SYOUGOU"],
			"ATAMA" => $data["ATAMA"],
			"GENGO" => $data["GENGO"],
			"XUID" => $data["XUID"],
			"JOB"=>$data["JOB"],
			"KILL"=>$data["KILL"],
			"DEATH"=>$data["DEATH"],
			"RED"=>$data["RED"],
			"EVENT"=>$data["EVENT"],
			"DAIRY"=>$data["DAIRY"],
			"Sun"=>$data["Sun"],
			"KOUSEKI"=>$data["KOUSEKI"],
			"MOKUTEKI"=>$data["MOKUTEKI"],
			"EXP"=>$data["EXP"],
			"LEVEL"=>$data["LEVEL"],
			"MAXTABLEEXP"=>$data["MAXTABLEEXP"],
			"OLDTABLEEXP"=>$data["OLDTABLEEXP"],
			"NORMALMAXEXP"=>$data["NORMALMAXEXP"],
			"ADVANCEMAXEXP"=>0,//追加
			"LEVELMODE"=>$data["LEVELMODE"],
			"CLAN"=>$data["CLAN"],
			"LOGINBONUS"=>$data["LOGINBONUS"],
			"DAIRY1"=>$data["DAIRY1"],
			"DAIRY2"=>$data["DAIRY2"],
			"DAIRY3"=>$data["DAIRY3"],
			"DAIRYTASK1"=>$data["DAIRYTASK1"],
			"DAIRYTASK2"=>$data["DAIRYTASK2"],
			"DAIRYTASK3"=>$data["DAIRYTASK3"],
			"DAIRYGET1"=>$data["DAIRYGET1"],
			"DAIRYGET2"=>$data["DAIRYGET2"],
			"DAIRYGET3"=>$data["DAIRYGET3"],
			"COAL"=>$data["COAL"],
			"LAPIS"=>$data["LAPIS"],
			"IRON"=>$data["IRON"],
			"REDSTONE"=>$data["REDSTONE"],
			"GOLD"=>$data["GOLD"],
			"DIAMOND"=>$data["DIAMOND"],
			"EMERALD"=>$data["EMERALD"],
			"FLOWER"=>$data["FLOWER"],
			"BREAK"=>$data["BREAK"],
			"PEACE"=>$data["PEACE"],
			"WOOD"=>$data["WOOD"],
			"STAY"=>$data["STAY"],
			"DAY"=>$data["DAY"],
			"WALK"=>$data["WALK"],
			"GACHA"=>$data["GACHA"],
			"SLOT"=>$data["SLOT"],
			"SHOPPING"=>$data["SHOPPING"],
			"TRAPPIST"=>$data["TRAPPIST"],
			"GETLOGINBONUS"=>$data["GETLOGINBONUS"],
			"REPEAT"=>$data["REPEAT"],
			"MAXREPEAT"=>$data["MAXREPEAT"],
			"REPEATBONUS"=>$data["REPEATBONUS"],
			"MAXKILLSTREAK"=>$data["MAXKILLSTREAK"],
			//ここから追加
			"FISH"=>$data["SPARE1"],
			"FISHING_ROD"=>$data["SPARE2"],
			"INVENTORY"=>false,
			"INVENTORY_OBJECT"=>10,
			"SPACESHIP_SIZE"=>1,
			"SPACESHIP_LEVEL"=>0,
			"SPACESHIP_COAL"=>0,
			"SPACESHIP_LAPIS"=>0,
			"SPACESHIP_IRON"=>0,
			"SPACESHIP_REDSTONE"=>0,
			"SPACESHIP_GOLD"=>0,
			"SPACESHIP_DIAMOND"=>0,
			"SPACESHIP_EMERALD"=>0,
			"SPACESHIP_EFFECT"=>0,
			"JUMP_EFFECT"=>0,
			"WARP_EFFECT"=>0,
			"SPARE1"=>0,
			"SPARE2"=>0,
			"SPARE3"=>0,
			"SPARE4"=>0,
			"SPARE5"=>0,
			"SPARE6"=>0,
			"SPARE7"=>0,
			"SPARE8"=>0,
			"SPARE9"=>0,
			"SPARE10"=>0,
			"SPARE11"=>0,
			"SPARE12"=>0,
			"SPARE13"=>0,
			"SPARE14"=>0,
			"SPARE15"=>0,
			"SPARE16"=>0,
			"SPARE17"=>0,
		));
		$config->remove("3");
		$config->save();
		$config->reload();
	}

	public static function save(Config $config,array $data,string $ip,int $stay){
		$config->reload();
		$config->set(self::UPDATE_VERSION, array(
			"FIRST_IP"=>$data["FIRST_IP"],//追加
			"IP" => $ip,
			"KENGEN" => $data["KENGEN"],
			"HITOKOTO" => $data["HITOKOTO"],
			"LASTLOGIN" => time(),
			"KEIKOKU" => $data["KEIKOKU"],
			"SYOUGOU" => $data["SYOUGOU"],
			"ATAMA" => $data["ATAMA"],
			"GENGO" => $data["GENGO"],
			"XUID" => $data["XUID"],
			"JOB"=>$data["JOB"],
			"KILL"=>$data["KILL"],
			"DEATH"=>$data["DEATH"],
			"RED"=>$data["RED"],
			"EVENT"=>$data["EVENT"],
			"DAIRY"=>$data["DAIRY"],
			"Sun"=>$data["Sun"],
			"KOUSEKI"=>$data["KOUSEKI"],
			"MOKUTEKI"=>$data["MOKUTEKI"],
			"EXP"=>$data["EXP"],
			"LEVEL"=>$data["LEVEL"],
			"MAXTABLEEXP"=>$data["MAXTABLEEXP"],
			"OLDTABLEEXP"=>$data["OLDTABLEEXP"],
			"NORMALMAXEXP"=>$data["NORMALMAXEXP"],
			"ADVANCEMAXEXP"=>$data["ADVANCEMAXEXP"],
			"LEVELMODE"=>$data["LEVELMODE"],
			"CLAN"=>$data["CLAN"],
			"LOGINBONUS"=>$data["LOGINBONUS"],
			"DAIRY1"=>$data["DAIRY1"],
			"DAIRY2"=>$data["DAIRY2"],
			"DAIRY3"=>$data["DAIRY3"],
			"DAIRYTASK1"=>$data["DAIRYTASK1"],
			"DAIRYTASK2"=>$data["DAIRYTASK2"],
			"DAIRYTASK3"=>$data["DAIRYTASK3"],
			"DAIRYGET1"=>$data["DAIRYGET1"],
			"DAIRYGET2"=>$data["DAIRYGET2"],
			"DAIRYGET3"=>$data["DAIRYGET3"],
			"COAL"=>$data["COAL"],
			"LAPIS"=>$data["LAPIS"],
			"IRON"=>$data["IRON"],
			"REDSTONE"=>$data["REDSTONE"],
			"GOLD"=>$data["GOLD"],
			"DIAMOND"=>$data["DIAMOND"],
			"EMERALD"=>$data["EMERALD"],
			"FLOWER"=>$data["FLOWER"],
			"BREAK"=>$data["BREAK"],
			"PEACE"=>$data["PEACE"],
			"WOOD"=>$data["WOOD"],
			"STAY"=>$stay,
			"DAY"=>$data["DAY"],
			"WALK"=>$data["WALK"],
			"GACHA"=>$data["GACHA"],
			"SLOT"=>$data["SLOT"],
			"SHOPPING"=>$data["SHOPPING"],
			"TRAPPIST"=>$data["TRAPPIST"],
			"GETLOGINBONUS"=>$data["GETLOGINBONUS"],
			"REPEAT"=>$data["REPEAT"],
			"MAXREPEAT"=>$data["MAXREPEAT"],
			"REPEATBONUS"=>$data["REPEATBONUS"],
			"MAXKILLSTREAK"=>$data["MAXKILLSTREAK"],
			//ここから追加
			"FISH"=>$data["FISH"],
			"FISHING_ROD"=>$data["FISHING_ROD"],
			"INVENTORY"=>$data["INVENTORY"],
			"INVENTORY_OBJECT"=>$data["INVENTORY_OBJECT"],
			"SPACESHIP_SIZE"=>$data["SPACESHIP_SIZE"],
			"SPACESHIP_LEVEL"=>$data["SPACESHIP_LEVEL"],
			"SPACESHIP_COAL"=>$data["SPACESHIP_COAL"],
			"SPACESHIP_LAPIS"=>$data["SPACESHIP_LAPIS"],
			"SPACESHIP_IRON"=>$data["SPACESHIP_IRON"],
			"SPACESHIP_REDSTONE"=>$data["SPACESHIP_REDSTONE"],
			"SPACESHIP_GOLD"=>$data["SPACESHIP_GOLD"],
			"SPACESHIP_DIAMOND"=>$data["SPACESHIP_DIAMOND"],
			"SPACESHIP_EMERALD"=>$data["SPACESHIP_EMERALD"],
			"SPACESHIP_EFFECT"=>$data["SPACESHIP_EFFECT"],
			"JUMP_EFFECT"=>$data["JUMP_EFFECT"],
			"SPARE1"=>0,
			"SPARE2"=>0,
			"SPARE3"=>0,
			"SPARE4"=>0,
			"SPARE5"=>0,
			"SPARE6"=>0,
			"SPARE7"=>0,
			"SPARE8"=>0,
			"SPARE9"=>0,
			"SPARE10"=>0,
			"SPARE11"=>0,
			"SPARE12"=>0,
			"SPARE13"=>0,
			"SPARE14"=>0,
			"SPARE15"=>0,
			"SPARE16"=>0,
			"SPARE17"=>0,
		));
		$config->save();
	}

	public static function set(Config $config):array{
		$config->reload();
		$data["FIRST_IP"]=$config->get(self::UPDATE_VERSION)["FIRST_IP"];
		$data["IP"]=$config->get(self::UPDATE_VERSION)["IP"];
		$data["KENGEN"]=$config->get(self::UPDATE_VERSION)["KENGEN"];
		$data["HITOKOTO"]=$config->get(self::UPDATE_VERSION)["HITOKOTO"];
		$data["LASTLOGIN"]=$config->get(self::UPDATE_VERSION)["LASTLOGIN"];
		$data["KEIKOKU"]=$config->get(self::UPDATE_VERSION)["KEIKOKU"];
		$data["SYOUGOU"]=$config->get(self::UPDATE_VERSION)["SYOUGOU"];
		$data["ATAMA"]=$config->get(self::UPDATE_VERSION)["ATAMA"];
		$data["GENGO"]=$config->get(self::UPDATE_VERSION)["GENGO"];
		$data["XUID"]=$config->get(self::UPDATE_VERSION)["XUID"];
		$data["JOB"]=$config->get(self::UPDATE_VERSION)["JOB"];
		$data["KILL"]=$config->get(self::UPDATE_VERSION)["KILL"];
		$data["DEATH"]=$config->get(self::UPDATE_VERSION)["DEATH"];
		$data["RED"]=$config->get(self::UPDATE_VERSION)["RED"];
		$data["EVENT"]=$config->get(self::UPDATE_VERSION)["EVENT"];
		$data["DAIRY"]=$config->get(self::UPDATE_VERSION)["DAIRY"];
		$data["Sun"]=$config->get(self::UPDATE_VERSION)["Sun"];
		$data["KOUSEKI"]=$config->get(self::UPDATE_VERSION)["KOUSEKI"];
		$data["MOKUTEKI"]=$config->get(self::UPDATE_VERSION)["MOKUTEKI"];
		$data["EXP"]=$config->get(self::UPDATE_VERSION)["EXP"];//現在の経験値
		$data["LEVEL"]=$config->get(self::UPDATE_VERSION)["LEVEL"];//レベル
		$data["MAXTABLEEXP"]=$config->get(self::UPDATE_VERSION)["MAXTABLEEXP"];//レベルテーブルに達するまでの経験値
		$data["OLDTABLEEXP"]=$config->get(self::UPDATE_VERSION)["OLDTABLEEXP"];//レベル上る前の累計経験値
		$data["NORMALMAXEXP"]=$config->get(self::UPDATE_VERSION)["NORMALMAXEXP"];//アドバンスレベル変更時の累計経験値
		$data["ADVANCEMAXEXP"]=$config->get(self::UPDATE_VERSION)["ADVANCEMAXEXP"];
		$data["LEVELMODE"]=$config->get(self::UPDATE_VERSION)["LEVELMODE"];//レベルモード判定
		$data["CLAN"]=$config->get(self::UPDATE_VERSION)["CLAN"];
		$data["LOGINBONUS"]=$config->get(self::UPDATE_VERSION)["LOGINBONUS"];
		$data["DAIRY1"]=$config->get(self::UPDATE_VERSION)["DAIRY1"];
		$data["DAIRY2"]=$config->get(self::UPDATE_VERSION)["DAIRY2"];
		$data["DAIRY3"]=$config->get(self::UPDATE_VERSION)["DAIRY3"];
		$data["DAIRYTASK1"]=$config->get(self::UPDATE_VERSION)["DAIRYTASK1"];
		$data["DAIRYTASK2"]=$config->get(self::UPDATE_VERSION)["DAIRYTASK2"];
		$data["DAIRYTASK3"]=$config->get(self::UPDATE_VERSION)["DAIRYTASK3"];
		$data["DAIRYGET1"]=$config->get(self::UPDATE_VERSION)["DAIRYGET1"];
		$data["DAIRYGET2"]=$config->get(self::UPDATE_VERSION)["DAIRYGET2"];
		$data["DAIRYGET3"]=$config->get(self::UPDATE_VERSION)["DAIRYGET3"];
		$data["COAL"]=$config->get(self::UPDATE_VERSION)["COAL"];
		$data["LAPIS"]=$config->get(self::UPDATE_VERSION)["LAPIS"];
		$data["IRON"]=$config->get(self::UPDATE_VERSION)["IRON"];
		$data["REDSTONE"]=$config->get(self::UPDATE_VERSION)["REDSTONE"];
		$data["GOLD"]=$config->get(self::UPDATE_VERSION)["GOLD"];
		$data["DIAMOND"]=$config->get(self::UPDATE_VERSION)["DIAMOND"];
		$data["EMERALD"]=$config->get(self::UPDATE_VERSION)["EMERALD"];
		$data["FLOWER"]=$config->get(self::UPDATE_VERSION)["FLOWER"];
		$data["BREAK"]=$config->get(self::UPDATE_VERSION)["BREAK"];
		$data["PEACE"]=$config->get(self::UPDATE_VERSION)["PEACE"];
		$data["WOOD"]=$config->get(self::UPDATE_VERSION)["WOOD"];
		$data["STAY"]=$config->get(self::UPDATE_VERSION)["STAY"];
		$data["DAY"]=$config->get(self::UPDATE_VERSION)["DAY"];
		$data["WALK"]=$config->get(self::UPDATE_VERSION)["WALK"];
		$data["GACHA"]=$config->get(self::UPDATE_VERSION)["GACHA"];
		$data["SLOT"]=$config->get(self::UPDATE_VERSION)["SLOT"];
		$data["SHOPPING"]=$config->get(self::UPDATE_VERSION)["SHOPPING"];
		$data["TRAPPIST"]=$config->get(self::UPDATE_VERSION)["TRAPPIST"];
		$data["GETLOGINBONUS"]=$config->get(self::UPDATE_VERSION)["GETLOGINBONUS"];
		$data["REPEAT"]=$config->get(self::UPDATE_VERSION)["REPEAT"];
		$data["MAXREPEAT"]=$config->get(self::UPDATE_VERSION)["MAXREPEAT"];
		$data["MAXKILLSTREAK"]=$config->get(self::UPDATE_VERSION)["MAXKILLSTREAK"];
		$data["REPEATBONUS"]=$config->get(self::UPDATE_VERSION)["REPEATBONUS"];
		$data["FISH"]=$config->get(self::UPDATE_VERSION)["FISH"];//魚を釣った数
		$data["FISHING_ROD"]=$config->get(self::UPDATE_VERSION)["FISHING_ROD"];//釣り竿を受け取ったか
		$data["INVENTORY"]=$config->get(self::UPDATE_VERSION)["INVENTORY"];
		$data["INVENTORY_OBJECT"]=$config->get(self::UPDATE_VERSION)["INVENTORY_OBJECT"];
		$data["SPACESHIP_SIZE"]=$config->get(self::UPDATE_VERSION)["SPACESHIP_SIZE"];
		$data["SPACESHIP_LEVEL"]=$config->get(self::UPDATE_VERSION)["SPACESHIP_LEVEL"];
		$data["SPACESHIP_COAL"]=$config->get(self::UPDATE_VERSION)["SPACESHIP_COAL"];
		$data["SPACESHIP_LAPIS"]=$config->get(self::UPDATE_VERSION)["SPACESHIP_LAPIS"];
		$data["SPACESHIP_IRON"]=$config->get(self::UPDATE_VERSION)["SPACESHIP_IRON"];
		$data["SPACESHIP_REDSTONE"]=$config->get(self::UPDATE_VERSION)["SPACESHIP_REDSTONE"];
		$data["SPACESHIP_GOLD"]=$config->get(self::UPDATE_VERSION)["SPACESHIP_GOLD"];
		$data["SPACESHIP_DIAMOND"]=$config->get(self::UPDATE_VERSION)["SPACESHIP_DIAMOND"];
		$data["SPACESHIP_EMERALD"]=$config->get(self::UPDATE_VERSION)["SPACESHIP_EMERALD"];
		$data["SPACESHIP_EFFECT"]=$config->get(self::UPDATE_VERSION)["SPACESHIP_EFFECT"];
		$data["JUMP_EFFECT"]=$config->get(self::UPDATE_VERSION)["JUMP_EFFECT"];

		return $data;
	}


}