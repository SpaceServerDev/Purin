<?php

namespace SSC;

use pocketmine\entity\Entity;

use pocketmine\level\Level;
use pocketmine\level\particle\FloatingTextParticle;
use pocketmine\nbt\tag\CompoundTag;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\plugin\PluginBase;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use pocketmine\event\Listener;

use pocketmine\level\Position;

use pocketmine\item\Item;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;

use pocketmine\math\Vector3;

use pocketmine\utils\Config;

use pocketmine\nbt\tag\IntTag;

use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;

use onebone\economyapi\EconomyAPI;

use SSC\Command\BaseCommandMap;
use SSC\Core\version;
use SSC\Data\EXShop;
use SSC\Data\FishSizeConfig;
use SSC\Data\RankConfig;
use SSC\Entity\PatimonEntity;
use SSC\Event\Altay\FishEvent;
use SSC\Event\Block\BreakEvent;
use SSC\Event\Block\PlaceEvent;
use SSC\Event\Cheat\Nuker;
use SSC\Event\Cheat\XRay;
use SSC\Event\Entity\ArmorChangeEvent;
use SSC\Event\Entity\ExplodeEvent;
use SSC\Event\player\ChatEvent;
use SSC\Event\player\CommandPreProcessEvent;
use SSC\Event\player\JoinEvent;
use SSC\Event\player\JumpEvent;
use SSC\Event\player\KillDeathEvent;
use SSC\Event\player\LoginEvent;
use SSC\Event\player\PreLoginEvent;
use SSC\Event\player\QuitEvent;
use SSC\Event\player\RespawnEvent;
use SSC\Event\player\ToggleFlightEvent;
use SSC\Event\player\ToggleSneakEvent;
use SSC\Event\player\TouchEvent;
use SSC\Event\Tile\SignChange;
use bboyyu51\pmdiscord\Sender;
use bboyyu51\pmdiscord\structure\Content;

use SSC\Command\AllCommands;

use SSC\Data\ItemData;
use SSC\Gun\GunEvent;
use SSC\Gun\GunManager;
use SSC\Task\RebootTask;

use SSC\Event\FormEvent;

class main extends PluginBase implements Listener {

	/*
	 * shop,sell,eshop
	 */
	public $itemid;
	public $itemname;
	public $itemprice;
	public $itemdamage;
	public $itemamount;

	public $seconds = 30;

	public $itiji;

	public $login;

	private $reload=21600;

	/*
	 * tpp
	*/
	public $tpplayer;

	/**
	 * @var $playerlog Config
	 */
	public $playerlog;

	public $playerdata=[];

	public $id = [];

	public $data;

	/**
	 * @var EconomyAPI
	 */
	public $economyAPI;

	/**
	 * @var $log \SQLite3
	 */
	public $log;

	/**
	 * @var self
	 */

	private static $main;

	/**
	 * @var $blacklist Config
	 */
	public $blacklist;

	/**
	 * @var Config
	 */
	public $update;

	/**
	 * @var Config
	 */
	public $kakin;
	/**
	 * @var Config
	 */
	public $dkakin;

	/**
	 * @var Config
	 */
	public $loginbonus;

	/**
	 * @var Config
	 */
	public $levelbonus;

	/**
	 * @var Config
	 */
	public $banlist;

	/**
	 * @var Config
	 */
	public $playerlist;

	/**
	 * @var Config
	 */
	public $password;

	/**
	 * @var Entity
	 */
	public $patimon;

	/**
	 * @var Config
	 */
	private $npc;

	/**
	 * @var Config
	 */
	public $fishsize;

	/**
	 * @var GunManager
	 */
	private $GunManager;

	/**
	 * @var \SQLite3
	 */
	private $vs;

	/**
	 * @var Config
	 */
	public $otosidama;

	private $floatingSetting=false;

	private $token=[];

	private static $exshop;



	public function onEnable() {
		$this->registerEvents();
		BaseCommandMap::init($this);
		$this->loadLevels();
		$this::setTime();

		self::$main = $this;

		$this->economyAPI = EconomyAPI::getInstance();
		$this->GunManager = new GunManager();

		$this->setConfig();
		$this->sendAA();
		$this->getScheduler()->scheduleRepeatingTask(new RebootTask(), 20);
		//self::$exshop=new EXShop();
		//self::$exshop->init();
		$this->sendDiscord("OPEN","宇宙サーバーが開いたよ！");
		Entity::registerEntity(PatimonEntity::class, true);
		if(!$this->npc->exists("spawned")) {
			$this->spawnEntity();
			$this->npc->set("spawned",true);
			$this->npc->save();
		}
	}

	private function loadLevels(){
		foreach ($this->getLevels() as $level) Server::getInstance()->loadLevel($level);
	}

	public function getLevels():array{
		return ["world","earth","sun","pvp","space","flatworld","TauCetusE","TauCetusF","Neptune","Blackhole","trappist-1e","mars","moon","pluto"];
	}

	private static function setTime(){
	 	$level = Server::getInstance()->getLevelByName("pvp");
		$level->setTime(25000);
		$level->stopTime();
		$level = Server::getInstance()->getLevelByName("world");
		$level->setTime(15000);
		$level->stopTime();
		$level = Server::getInstance()->getLevelByName("space");
		$level->setTime(21000);
		$level->stopTime();
		$level = Server::getInstance()->getLevelByName("trappist-1e");
		$level->setTime(10000);
		$level->stopTime();
	}

	private function registerEvents(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getServer()->getPluginManager()->registerEvents(new KillDeathEvent($this), $this);
		$this->getServer()->getPluginManager()->registerEvents(new FormEvent($this), $this);
		$this->getServer()->getPluginManager()->registerEvents(new PlaceEvent($this),$this);
		$this->getServer()->getPluginManager()->registerEvents(new BreakEvent($this),$this);
		$this->getServer()->getPluginManager()->registerEvents(new TouchEvent($this),$this);
		$this->getServer()->getPluginManager()->registerEvents(new FishEvent(),$this);
		$this->getServer()->getPluginManager()->registerEvents(new RespawnEvent(),$this);
		$this->getServer()->getPluginManager()->registerEvents(new PreLoginEvent(),$this);
		$this->getServer()->getPluginManager()->registerEvents(new ExplodeEvent(),$this);
		$this->getServer()->getPluginManager()->registerEvents(new LoginEvent(),$this);
		$this->getServer()->getPluginManager()->registerEvents(new JoinEvent(),$this);
		$this->getServer()->getPluginManager()->registerEvents(new SignChange(),$this);
		$this->getServer()->getPluginManager()->registerEvents(new QuitEvent(),$this);
		$this->getServer()->getPluginManager()->registerEvents(new JumpEvent(),$this);
		$this->getServer()->getPluginManager()->registerEvents(new ArmorChangeEvent(),$this);
		$this->getServer()->getPluginManager()->registerEvents(new ChatEvent(),$this);
		$this->getServer()->getPluginManager()->registerEvents(new CommandPreProcessEvent(),$this);
		$this->getServer()->getPluginManager()->registerEvents(new ToggleFlightEvent(),$this);
		$this->getServer()->getPluginManager()->registerEvents(new Nuker(),$this);
		//$this->getServer()->getPluginManager()->registerEvents(new XRay(),$this);
		$this->getServer()->getPluginManager()->registerEvents(new GunEvent(),$this);
		$this->getServer()->getPluginManager()->registerEvents(new ToggleSneakEvent(),$this);
	}

	private function sendAA(){
		echo "\n";
		version::DEV_VERSION?$dev="β":$dev="";
		$this->getLogger()->info(version::NAME." ver. ".version::VERSION_INFO."を動作します");
		$this->getLogger()->info(version::AA_1);
		$this->getLogger()->info(version::AA_2);
		$this->getLogger()->info(version::AA_3);
		$this->getLogger()->info(version::AA_4);
		$this->getLogger()->info(version::AA_5);
		$this->getLogger()->info(version::AA_6);
		$this->getLogger()->info(version::AA_7);
		$this->getLogger()->info(" ver".version::VERSION_INFO.$dev);

		echo "\n";
	}

	private function setConfig() {
		if (!(file_exists($this->getDataFolder()))) @mkdir($this->getDataFolder(), 0777);
		if (!(file_exists($this->getDataFolder() . "LevelBonus"))) @mkdir($this->getDataFolder() . "LevelBonus", 0755, true);
		if (!(file_exists($this->getDataFolder() . "Fish"))) @mkdir($this->getDataFolder() . "Fish", 0755, true);
		if (!(file_exists($this->getDataFolder() . "Rank"))) @mkdir($this->getDataFolder() . "Rank", 0755, true);
		if (!(file_exists($this->getDataFolder() . "VS"))) @mkdir($this->getDataFolder() . "VS", 0755, true);
		$this->playerlist = new Config($this->getDataFolder() . "player.yml", Config::YAML, array());
		$this->banlist = new Config($this->getDataFolder() . "cban.yml", Config::YAML, array());
		$this->blacklist = new Config($this->getDataFolder() . "ban.yml", Config::YAML);
		$this->update = new Config($this->getDataFolder() . "update.yml", Config::YAML, array());
		$this->kakin = new Config($this->getDataFolder() . "kakin.yml", Config::YAML, array());
		$this->dkakin = new Config($this->getDataFolder() . "kakinday.yml", Config::YAML, array());
		$this->loginbonus = new Config($this->getDataFolder() . "bonus.yml", Config::YAML, array());
		$this->log = new Config($this->getDataFolder() . "log.yml", Config::YAML, array());
		$this->password = new Config($this->getDataFolder() . "password.yml", Config::YAML, array());
		$this->levelbonus = new Config($this->getDataFolder() . "LevelBonus" . "/levelbonus.yml", Config::YAML, array("10" => array(), "50" => array(), "80" => array(), "100" => array(), "150" => array(), "200" => array(), "250" => array(), "300" => array(), "350" => array(), "400" => array(), "450" => array(), "500" => array(),));
		$this->fishsize = new FishSizeConfig();
		$this->npc = new Config($this->getDataFolder() . "spawn.yml", Config::YAML);
		$this->otosidama = new Config($this->getDataFolder() . "2021.yml", Config::YAML);
		if(!file_exists($this->getDataFolder() . "log.db")){
			$this->log = new \SQLite3($this->getDataFolder() . "log.db", SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE);
		}else{
			$this->log = new \SQLite3($this->getDataFolder() . "log.db", SQLITE3_OPEN_READWRITE);
		}
		$this->log->query("CREATE TABLE IF NOT EXISTS logdata (xyz TEXT PRIMARY KEY, who TEXT , action TEXT, time TEXT, id INT,meta INT)");

		/*if(!file_exists(main::getMain()->getDataFolder() . "VS.db")){
			$this->vs = new \SQLite3(main::getMain()->getDataFolder()  . "VS.db", SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE);
		}else{
			$this->vs = new \SQLite3(main::getMain()->getDataFolder()  . "VS.db", SQLITE3_OPEN_READWRITE);
		}*/

	}

	private function spawnEntity(){
		$nbt = Entity::createBaseNBT(new Position(214, 4, 293, Server::getInstance()->getLevelByName("world")), null, 180);
		$tag = new CompoundTag("form");
		$tag->setString("FishForm", "FishForm");
		$nbt->setTag($tag);
		$entity = Entity::createEntity("PatimonEntity", Server::getInstance()->getLevelByName("world"), $nbt);
		$entity->setNameTag("博物館のフクロウ\nここで魚の納品ができます");
		$entity->setNameTagVisible(true);
		$entity->saveNBT();
		$entity->spawnToAll();
		$this->patimon=$entity;
	}




	public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
		new AllCommands($this,$sender,$command->getName(),$args);
		return true;
	}

	public static function getPlayerData(String $name):PlayerEvent{
		return self::getMain()->playerdata[$name];
	}

	public static function getMain():main{
		return self::$main;
	}

	public function getGunManager():GunManager{
		return $this->GunManager;
	}

	public function getFile(): string {
		return parent::getFile();
	}

	public function sendDiscord(string $name, string $message){
	 	$webhook = Sender::create("https://discordapp.com/api/webhooks/670915313071816704/5CWGaxC5YLlsNHWGygFwobs7DS3T3PPWWvvJN3VIxlF3UkqhkmhJpWomdhBDXiNVnRB0");
		$content = new Content();
		$content->setText($message);
		$webhook->add($content);
		$webhook->setCustomName($name);
		Sender::sendAsync($webhook);
	}

	public function isCBan($cid) : bool{
		$this->banlist->reload();
		return $this->banlist->exists($cid);
	}

	public function addCban($cid, $reason) {
		if ($this->isCBan($cid)) return;
		$this->banlist->reload();
		$this->banlist->set(strval($cid),$reason);
		$this->banlist->save();
	}

	public function removeCBan($cid) {
		if (!$this->isCBan($cid)) return;
		$this->banlist->reload();
		$this->banlist->remove($cid);
		$this->banlist->save();
	}

	public static function getContains(){
		$clas=new ItemData();
		$playerdata=new \ReflectionClass($clas);
		return $playerdata->getConstants();
	}

	public function isKakinDay(Player $player){
	 	if($this->dkakin->exists($player->getName())){
	 		if(date('Y-m-d',$this->dkakin->get($player->getName()))<=date('Y-m-d',$this->dkakin->get($player->getName())+(31*24*60*60))){
	 			return true;
			}
		}
	 	return false;
	}

	public static function isOres(Player $player,PlayerEvent $playerdata){
	 	 $ores=$playerdata->getVar("COAL")+$playerdata->getVar("LAPIS")+$playerdata->getVar("IRON")+$playerdata->getVar("REDSTONE")+$playerdata->getVar("GOLD")+$playerdata->getVar("DIAMOND")+$playerdata->getVar("EMERALD");
		 if($ores===10000or$ores===50000){
		 	$player->sendTitle("実績を達成", "§a鉱石を{$playerdata->getVar("ORE")}個集めた！", 30, 30, 20);
		 }
	}

	public function addEXP(Player $player, int $number) {
		$name = $player->getName();
		$playerdata=self::getPlayerData($name);
		$lu=$playerdata->isLevelup($number);
		switch ($lu) {
			case 0:
				return;
			case 1:
				$player->sendMessage("[管理AI]§aレベルが切りよくなったので1000￥プレゼント!");
				$this->economyAPI->addMoney($name, 1000);
				break;
			case 2:
				$dia = Item::get(264, 0, 5);
				$player->sendMessage("[管理AI]§aレベルが切りよくなったのでダイヤ5個プレゼント!");
				if ($player->getInventory()->canAddItem($dia)) {
					$player->getInventory()->addItem($dia);
				} else {
					$player->sendMessage("[管理AI]§4空きがなくて渡せませんでした...");
				}
				break;
			case 3:
				$player->sendMessage("[管理AI]§aレベルが500になったので宝具プレゼント!");
				$item = Item::get(299, 0, 1);
				$item->setCustomName("§a伝説の宝具 所有者:" . $name);
				$enchantment = Enchantment::getEnchantment(17);
				$item->addEnchantment(new EnchantmentInstance($enchantment, 20));
				$colorcode = 0xfff8dc;
				$item->setNamedTagEntry(new IntTag("customColor", $colorcode));
				if ($player->getInventory()->canAddItem($item)) {
					$player->getInventory()->addItem($item);
				} else {
					$player->sendMessage("[管理AI]§4空きがなくて渡せませんでした...");
				}
				break;
			case 4:
				break;
		}
		$player->sendTip("§e§lLEVEL UP!");
		$pos = new Vector3($player->x, $player->y, $player->z);
		$player->getLevel()->broadcastLevelSoundEvent($pos, LevelSoundEventPacket::SOUND_BEACON_ACTIVATE);
		$playerdata->setDisplayName($playerdata->getDisplayName());

	}

	public function rate(Player $killer,Player $deather) {
		if ($killer->getName() === $deather->getName()) {
			$playerdata = self::getPlayerData($deather->getName());
			$playerdata->addDeath();
			return;
		}
		$playerdata = self::getPlayerData($killer->getName());
		$playerdata->addKill();
		if ($playerdata->getVar("DAIRY3") === 14 or $playerdata->getVar("DAIRY3") === 15) {
			$playerdata->addVar("DAIRYTASK3");
			if ($playerdata->getMaxDairy3() === $playerdata->getNowDairy3()) {
				$killer->addTitle("デイリーボーナスを達成", "", 30, 30, 20);
			}
		}

		$this->addEXP($killer, 15);
		if ($playerdata->getJob() == "暗殺者") {
			$this->economyAPI->addMoney($killer->getName(), 30);
		}
		$playerdata = self::getPlayerData($deather->getName());
		$playerdata->addDeath();
	}

	public function registerlog($x, $y, $z,String $level,Int $id,Int $meta,Player $player,String $eventname){
		$xyz =""."x"."$x"."y"."$y"."z"."$z"."w"."$level"."";
		$who = $player->getName();
		$time = date("Y/m/d-H:i:s", time());
		$this->log->query("INSERT OR REPLACE INTO logdata VALUES(\"$xyz\",   \"$who\",  \"$eventname\", \"$time\", \"$id\",\"$meta\")");
	}

	public function checklog($x, $y, $z,String $level,Player $player){
		$xyz =""."x"."$x"."y"."$y"."z"."$z"."w"."$level"."";
		$result = $this->log->query("SELECT who , action, id,meta, time FROM logdata WHERE xyz = \"$xyz\"");
		$results = $result->fetchArray(SQLITE3_ASSOC);
		if($results['who'] == null){
			$player->sendMessage("[管理AI]".$x.",".$y.",".$z.",".$level." ここにログは存在していません");
		}elseif($result){
			if($results['action']==="b"){
				$pb="破壊";
			}else{
				$pb="設置";
			}
			$itemname=Item::get($results['id'],$results['meta'],1)->getName();
			$player->sendPopup("§c[座標] ".$x.",".$y.",".$z.",".$level."\n[日時] ". $results['time']."\n[行動者] ". $results['who']."\n[行動]". $pb."\n[物] ". $results['id'].":".$results['meta']." ".$itemname);
		}
	}

	public function isLog($x, $y, $z,String $level):bool{
		$xyz =""."x"."$x"."y"."$y"."z"."$z"."w"."$level"."";
		$result = $this->log->query("SELECT who , action, id,meta, time FROM logdata WHERE xyz = \"$xyz\"");
		$results = $result->fetchArray(SQLITE3_ASSOC);
		if($results['who'] == null){
			return false;
		}elseif($result) {
			return true;
		}

		return false;
	}

	public function isDrops($player){
		$tag = $player->namedtag;
		if ($tag->offsetExists("bb")) {
			return $tag->getInt("bb") == 0;
		}
			return false;
	}

	public function getShop():array{
		return $this->shop;
	}



	public static function isBanItem(int $id){
		switch ($id){
			case 10:
			case 11:
			case 8:
			case 9:
			case 46:
			case 79:
			case 51:
			case 259:
			case 325:
				return true;
		}
		return false;
	}

	public function isFloatingSetting():bool{
		return $this->floatingSetting;
	}

	public function changeFloatingSetting(){
		$this->floatingSetting=true;
	}

	public function getServerReloadTick():int{
		return $this->reload;
	}

	public function CountDownServerReloadTick(){
		$this->reload--;
	}

	public static function getEXShop(){
		return self::$exshop;
	}


	public function registerRanking(PlayerEvent $pe){
		$configname=["stay","login","repeat","break","peace","trappist","flower","wood","gacha","shopping","slot","kill","killst","fishing","coal","lapis","iron","redstone","gold","diamond","emerald","level","spaceshipsize"];
		$var=["STAY","DAY","MAXREPEAT","BREAK","PEACE","TRAPPIST","FLOWER","WOOD","GACHA","SHOPPING","SLOT","KILL","MAXKILLSTREAK","FISH","COAL","LAPIS","IRON","REDSTONE","GOLD","DIAMOND","EMERALD","LEVEL","SPACESHIP_SIZE"];
		for($i=0;$i<count($configname);$i++){
			$stay=new RankConfig($this->getDataFolder()."Rank/{$configname[$i]}.yml");
			$stay->set($pe->getName(),$pe->getVar($var[$i]));
			$stay->save();
		}
		$login=new RankConfig($this->getDataFolder()."Rank/money.yml");
		$login->set($pe->getName(),EconomyAPI::getInstance()->myMoney($pe->getName()));
		$login->save();
	}

	public function getVirtualStorage():\SQLite3{
		return $this->vs;
	}

	public function addToken($token){
		$this->token[]=$token;
		//var_dump($this->token);
	}

	public function removeToken($token){
		$array=array_diff($this->token,array($token));
		$this->token=array_values($array);
		//var_dump($this->token);
	}

	public function existsToken($token){
		return in_array($token,$this->token);
	}

	public function onDisable() {
		foreach (Server::getInstance()->getOnlinePlayers() as $p) {
			if (!$this->getPlayerData($p->getName())) {
				continue;
			}
			$playerdata = $this->getPlayerData($p->getName());
			if ($playerdata->getLoad()) {
				$playerdata->save($this->economyAPI->myMoney($p->getName()));
			}
			$p->save();
		}
		$this->sendDiscord("CLOSE","宇宙サーバーが停止しました");
	}
}