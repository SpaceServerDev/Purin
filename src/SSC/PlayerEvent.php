<?php


namespace SSC;

use pocketmine\Player;

use pocketmine\scheduler\Task;
use pocketmine\utils\Config;

use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;

use pocketmine\math\Vector3;
use SSC\Data\FishConfig;
use SSC\Data\PlayerConfigManager;
use SSC\Data\VirtualStorageConfig;
use SSC\Event\Cheat\XRay;
use xenialdan\apibossbar\BossBar;

class PlayerEvent {

	/**
	 * @var Player
	 */
	private $player;

	/**
	 * @var main
	 */
	private $main;

	/**
	 * @var bool
	 */
	private $inv = false;

	/**
	 * @var array
	 */
	private $data = [];

	/**
	 * @var bool
	 */
	private $information = true;

	/**
	 * @var BossBar
	 */
	private $bar;

	/**
	 * @var int
	 */
	private $killst = 0;

	/**
	 * @var $move Vector3
	 */
	private $move;

	/**
	 * @var bool
	 */
	private $load=false;

	/**
	 * @var int
	 */
	private $time;

	/**
	 * @var int
	 */
	private $stay;

	/**
	 * @var int
	 */
	private $gachacount=0;

	/**
	 * @var bool
	 */
	private $log=false;

	/**
	 * @var bool
	 */
	private $showsign=false;


	private $shitdown;


	/**
	 * @var FishConfig
	 */
	private $fish;


	/**
	 * @var int
	 */
	private $nuke_count=0;

	/**
	 * @var int
	 */
	private $xray_count=0;

	/**
	 * @var Config
	 */
	private $config;

	/**
	 * @var boolean
	 */
	private $wait=false;

	/**
	 * @var VirtualStorageConfig
	 */
	private $vs;

	/**
	 * PlayerEvent constructor.
	 * @param main $main
	 * @param Player $player
	 */
	public function __construct(main $main, Player $player) {
		$this->player = $player;
		$this->main = $main;
		$this->config = new Config($this->main->getDataFolder() . "Player" . "/".$player->getName().".yml", Config::YAML);
		//$this->vs=new VirtualStorageConfig($player->getName());
	}

	/**
	 * @return Player
	 */
	public function getPlayer() {
		return $this->player;
	}

	/**
	 * @return mixed
	 */
	public function getName() {
		return $this->player->getName();
	}

	public function getLog():bool{
		return $this->log;
	}

	public function changeLog(){
		$this->log ? $this->log=false : $this->log=true;
	}

	public function getLoad(){
		return $this->load;
	}

	/**
	 * @param FishConfig $fishConfig
	 */
	public function registerFish(FishConfig $fishConfig){
		$this->fish=$fishConfig;
	}

	/**
	 * @return FishConfig
	 */
	public function getFish():FishConfig{
		return $this->fish;
	}

	/**
	 * @param BossBar $bossBar
	 */
	public function registerBossbar(BossBar $bossBar) {
		$this->bar = $bossBar;
	}


	/**
	 * @return BossBar
	 */
	public function getBossbar(): BossBar {
		return $this->bar;
	}

	/*public function getVirtualStorage(){
		return $this->vs;
	}*/

	public function getSpaceShipSize():int{
		return $this->data["SPACESHIP_SIZE"];
	}

	public function addSpaceShipSize(){
		$this->data["SPACESHIP_SIZE"]++;
	}

	public function setSpaceShipSize(int $size){
		$this->data["SPACESHIP_SIZE"]=$size;
	}

	public function getSpaceShipLevel():int{
		return $this->data["SPACESHIP_LEVEL"];
	}



	public function setSpaceShipLevel(int $level){
		$this->data["SPACESHIP_LEVEL"]=$level;
	}

	/**
	 * @return mixed
	 */
	public function getLevel() {
		return $this->data["LEVEL"];
	}

	/**
	 * @return mixed
	 */
	public function getExp() {
		return $this->data["EXP"];
	}

	public function getSign():bool{
		return $this->showsign;
	}

	public function setSign(){
		$this->showsign ? $this->showsign=false : $this->showsign=true;
	}

	public function getIP(){
		return $this->data["IP"];
	}

	public function setIP(){
		$this->data["IP"]=$this->getPlayer()->getAddress();
	}

	/**
	 * @return mixed
	 */
	public function getTotalExp() {
		return $this->data["OLDTABLEEXP"];
	}

	public function setTime(){
		$this->time=time();
		$this->stay=$this->data["STAY"];
	}

	public function startShitDown($unique){
		$this->shitdown=$unique;
	}

	public function getShitDown(){
		return $this->shitdown;
	}

	public function getShitDownNow():bool{
		return isset($this->shitdown);
	}

	public function endShitDown(){
		unset($this->shitdown);
	}

	/**
	 * @return boolean
	 */
	public function isSun(): bool {
		return $this->data["Sun"];
	}

	/**
	 * @param bool $change
	 */
	public function changeSun(bool $change) {
		$this->data["Sun"] = $change;
	}

	/**
	 * @param bool $response
	 */
	public function clearInventoryResponse(bool $response) {
		$this->inv = $response;
	}

	/**
	 * @return mixed
	 */
	public function clearInventory() {
		return $this->inv;
	}

	public function getNukeCount():int{
		return $this->nuke_count;
	}

	public function addNukeCount(){
		$this->nuke_count++;
	}

	public function resetNukeCount(){
		$this->nuke_count=0;
	}

	public function getXrayCount():int{
		return $this->xray_count;
	}

	public function addXrayCount(){
		$this->xray_count++;
	}

	public function resetXrayCount(){
		$this->xray_count=0;
	}

	public function getWait():bool{
		return $this->wait;
	}

	public function setWait(bool $bool){
		$this->wait=$bool;
	}

	/**
	 * @return mixed
	 */
	public function isNormalLevel() {
		return $this->data["LEVELMODE"] === 0 ? true : false;
	}

	public function isAdvanceLevel(){
		return $this->data["LEVELMODE"] === 1 ? true : false;
	}

	public function isExpertLevel(){
		return $this->data["LEVELMODE"] === 2 ? true : false;
	}

	public function changeAdvanceMode() {
		$this->data["NORMALMAXEXP"] = $this->data["EXP"];
		$this->data["EXP"] = 0;
		$this->data["LEVELMODE"] = 1;
		$this->data["OLDTABLEEXP"] = 0;
		$this->data["LEVEL"] = 1;
		$this->data["MAXTABLEEXP"] = 0;
		$this->setDisplayName($this->getDisplayName());
	}

	public function changeExpertMode() {
		$this->data["ADVANCEMAXEXP"] = $this->data["EXP"];
		$this->data["EXP"] = 0;
		$this->data["LEVELMODE"] = 2;
		$this->data["OLDTABLEEXP"] = 0;
		$this->data["LEVEL"] = 1;
		$this->data["MAXTABLEEXP"] = 0;
		$this->setDisplayName($this->getDisplayName());
	}

	public function getHitokoto() {
		return $this->data["HITOKOTO"];
	}

	public function setHitokoto(String $param) {
		$this->data["HITOKOTO"] = $param;
	}

	public function getGachaCount():int{
		return $this->gachacount;
	}

	public function addGachaCount(){
		++$this->gachacount;
	}

	public function resetGachaCount(){
		$this->gachacount=0;
	}

	/**
	 * check warn count
	 */
	public function addWarn() {
		++$this->data["KEIKOKU"];
	}

	/**
	 * @return mixed
	 */
	public function getWarn() {
		return $this->data["KEIKOKU"];
	}


	/**
	 * @return mixed
	 */
	public function getPerm() {
		return $this->data["KENGEN"];
	}

	/**
	 * @return int
	 */
	public function getNumberPerm():int{
		if($this->getPerm()==="警察"){
			return 1;
		}
		if($this->getPerm()==="警察庁長官"){
			return 2;
		}
		if($this->getPerm()==="VIP"){
			return 3;
		}
		if($this->getPerm()==="VIPplus"){
			return 4;
		}
		if($this->getPerm()==="OP"){
			return 5;
		}
		if($this->getPerm()==="オーナー"){
			return 6;
		}
		return 0;
	}

	public function setPerm(String $perm) {
		$this->data["KENGEN"] = $perm;
	}

	/**
	 * @return mixed
	 */
	public function getDisplayName() {
		return $this->data["SYOUGOU"];
	}

	public function setMove(Vector3 $mv){
		$this->move=$mv;
	}

	public function setClan(string $clan){
		$this->data["CLAN"]=$clan;
	}

	public function getClan(){
		return $this->data["CLAN"];
	}

	public function getMove():Vector3{
		return $this->move;
	}

	public function addWalk(){
		++$this->data["WALK"];
	}

	public function setTagName(String $tag) {
		$this->data["ATAMA"] = $tag;
		if($this->data["CLAN"]===""){
			$this->player->setNameTag($tag);
			return;
		}
		$this->player->setNameTag("-".$this->data["CLAN"]."§r-".$tag);
	}

	/**
	 * @param String $display
	 */
	public function setDisplayName(String $display) {
		$this->data["SYOUGOU"] = $display;
		$perm = $this->data["KENGEN"];
		$leve = $this->getLevel();
		if ($this->data["LEVELMODE"] === 0) {
			if ($this->getLevel() == 501) {
				$leve = "§l☆MAX☆§r";
			}
		}
		if ($this->data["LEVELMODE"] === 1) {
			if ($this->getLevel() == 1000) {
				$leve = "§l☆MAX☆§r";
			}
		}
		if ($perm == "オーナー") {
			$leve = "§d∞";
			$pm = "§dOWNER§f";
		} else if ($perm == "副主") {
			$leve = "§a∞";
			$pm = "§aSubOWNER§f";
		} else if ($perm == "OP") {
			$leve = "§e∞";
			$pm = "§eStaff§f";
		} else if ($perm == "VIPplus") {
			$pm = "§cV+§f";
		} else if ($perm == "VIP") {
			$pm = "§cVIP§f";
		} else if ($perm == "警察庁長官") {
			$pm = "§6警長§f";
		} else if ($perm == "警察") {
			$pm = "§b警察§f";
		} else {
			if ($this->data["LEVELMODE"] === 0) {
				$this->player->setDisplayName("<§eLV." . $leve . "§f>" . $display);
			} else if ($this->data["LEVELMODE"] === 1) {
				$this->player->setDisplayName("<§cLV." . $leve . "§f>" . $display);
			}else if($this->data["LEVELMODE"]===2){
				$this->player->setDisplayName("§e♛☆*.[§aL§bV§c.§e§l" . $leve . "§r§e].*☆♛ §r" . $display."§r");
			}
			return;
		}
		if ($this->data["LEVELMODE"] === 0) {
			$this->player->setDisplayName("<§eLV." . $leve . "§f>“" . $pm . "”" . $display);
		} else if ($this->data["LEVELMODE"] === 1) {
			$this->player->setDisplayName("<§cLV." . $leve . "§f>“" . $pm . "”" . $display);
		}else if($this->data["LEVELMODE"]===2){
				$this->player->setDisplayName("§e♛☆*.[§aL§bV§c.§e§l" . $leve . "§r§e].*☆♛ §r“" . $pm . "”" . $display."§r");
		}

	}

	/**
	 * @return mixed
	 */
	public function getTagName() {
		return $this->data["ATAMA"];
	}

	/**
	 * @return mixed
	 */
	public function getJob() {
		return $this->data["JOB"];
	}

	/**
	 * @param string $job
	 */
	public function setJob(string $job) {
		$this->data["JOB"] = $job;
	}

	public function addKill() {
		++$this->data["KILL"];
		++$this->killst;
		if($this->killst>$this->data["MAXKILLSTREAK"]){
			$this->data["MAXKILLSTREAK"]=$this->killst;
		}
	}

	public function addDeath() {
		++$this->data["DEATH"];
		$this->killst = 0;
	}

	/**
	 * @return mixed
	 */
	public function getMaxOre() {
		return $this->data["MOKUTEKI"];
	}

	public function addVar(String $data,int $amount=1){
		if($this->data[$data]===null){
			return;
		}
		if(is_int($this->data[$data])){
			$this->data[$data]=$this->data[$data]+$amount;
		}
	}

	/**
	 * @param String $data
	 * @param int $amount
	 */
	public function removeVar(String $data,int $amount=1){
		if($this->data[$data]===null){
			return;
		}
		if(is_int($this->data[$data])){
			$this->data[$data]=$this->data[$data]-$amount;
		}
	}

	/**
	 * @param string $data
	 * @return bool|mixed
	 */
	public function getVar(string $data){
		if($this->data[$data]===null){
			return false;
		}
		return $this->data[$data];
	}

	/**
	 * @return mixed
	 */
	public function getNowOre() {
		return $this->data["KOUSEKI"];
	}

	/**
	 * @return int
	 */
	public function getKillst() {
		return $this->killst;
	}

	/**
	 * @return bool
	 */
	public function getEnableInfo(): bool {
		return $this->information;
	}

	/**
	 * @param bool $on
	 */
	public function setInfo(bool $on) {
		$this->information = $on;
	}

	/**
	 * @param String $ticket
	 * @return int
	 */
	public function getTicket(String $ticket) {
		if ($this->data[$ticket] === null) {
			return 0;
		}
		return $this->data[$ticket];

	}

	/**
	 * @param String $ticket
	 * @param int $amount
	 */
	public function removeTicket(String $ticket, int $amount = 1) {
		if ($this->data[$ticket] !== null) {
			$this->data[$ticket] = $this->data[$ticket] - $amount;
		}
	}

	/**
	 * @param String $ticket
	 * @param int $amount
	 */
	public function changeTicket(String $ticket, int $amount = 1) {
		if ($this->data[$ticket] !== null) {
			$this->data[$ticket] = $this->data[$ticket] + $amount;
		}
	}

	public function addFishing(){
		$this->data["FISH"]++;
	}

	/**
	 * @return int
	 */
	public function getFishing():int{
		return $this->data["FISH"];
	}

	public function getInventoryObject(){
		return $this->data["INVENTORY_OBJECT"];
	}

	/**
	 * @param String $title
	 * @param String $content
	 * @param array $button
	 * @param int $id
	 */
	public function normalForm(String $title, String $content, array $button, int $id) {
		$pk = new ModalFormRequestPacket();
		$pk->formId = $id;
		$data = [
			"type" => "form",
			"title" => $title,
			"content" => $content,
			"buttons" => $button
		];
		$pk->formData = json_encode($data);
		$this->getPlayer()->dataPacket($pk);
	}

	/**
	 * @param String $title
	 * @param array $content
	 * @param int $id
	 */
	public function customForm(String $title, array $content, int $id) {
		$pk = new ModalFormRequestPacket();
		$pk->formId = $id;
		$data = [
			"type" => "custom_form",
			"title" => $title,
			"content" => $content,
		];
		$pk->formData = json_encode($data);
		$this->getPlayer()->dataPacket($pk);
	}

	/**
	 * @param string $ticket
	 */
	public function addTicket(String $ticket) {
		++$this->data["KOUSEKI"];
		if ($this->data["KOUSEKI"] === $this->data["MOKUTEKI"]) {
			++$this->data["RED"];
			$this->data["KOUSEKI"] = 0;
			$this->data["MOKUTEKI"] = mt_rand(5, 60);
			$this->getPlayer()->sendMessage("§c§l赤いチケット§rがひらひら降ってきた");
			$pos = new Vector3($this->getPlayer()->x, $this->getPlayer()->y, $this->getPlayer()->z);
			$this->getPlayer()->getLevel()->broadcastLevelSoundEvent($pos, LevelSoundEventPacket::SOUND_PORTAL);
		}
	}

	public function changeLoginBonus(){
		$this->data["LOGINBONUS"]=mt_rand(1,9);
	}

	public function checkLastLogin(){
		$timestamp=$this->data["LASTLOGIN"] + (24*60*60);
		if(date('Y-m-d')===date('Y-m-d',$timestamp)) {
			++$this->data["REPEAT"];
			if ($this->data["REPEAT"] > $this->data["MAXREPEAT"]) {
				$this->data["MAXREPEAT"] = $this->data["REPEAT"];
			}
		} else {
			$this->data["REPEAT"] = 1;
		}
	}

	public function canChangeRod(){
		return $this->data["FISHING_ROD"]==0;
	}

	public function changeRod(){
		$this->data["FISHING_ROD"]=1;
	}
	/**
	 * @return mixed
	 */
	public function getLoginBonus(){
		return $this->data["LOGINBONUS"];
	}

	public function changeDay(){
		$this->changeLoginBonus();
		$this->addLoginDay();
		$this->changeDairy();
		$this->setBonus(false);
		$this->checkLastLogin();
		$this->setRepeatBonus(false);
	}


	public function addLoginDay(){
		++$this->data["DAY"];
	}

	/**
	 * @return bool
	 */
	public function getBonus():bool{
		return $this->data["GETLOGINBONUS"];
	}

	/**
	 * @return mixed
	 */
	public function getStayTime(){
		$name=$this->player->getName();
		$config = new Config($this->main->getDataFolder() . "Player" . "/".$name.".yml", Config::YAML);
		return $config->get("3")["STAY"];
	}

	/**
	 * @param bool $change
	 */
	public function setBonus(bool $change){
		$this->data["GETLOGINBONUS"]=$change;
	}

	public function changeDairy(){
		$this->data["DAIRY1"] = mt_rand(1,5);
		$this->data["DAIRY2"] = mt_rand(6,10);
		$this->data["DAIRY3"] = mt_rand(11,15);
		$this->data["DAIRYTASK1"]=0;
		$this->data["DAIRYTASK2"]=0;
		$this->data["DAIRYTASK3"]=0;
		$this->data["DAIRYGET1"]=false;
		$this->data["DAIRYGET2"]=false;
		$this->data["DAIRYGET3"]=false;
	}

	public function setDairy1(){
		$this->data["DAIRYGET1"]=true;
	}

	public function setDairy2(){
		$this->data["DAIRYGET2"]=true;
	}

	public function setDairy3(){
		$this->data["DAIRYGET3"]=true;
	}

	public function getDairy1():string {
		switch ($this->getVar("DAIRY1")){
			case 1:
				return "木を30個切る";
			case 2:
				return "石炭を30個採掘する";
			case 3:
				return "石炭を50個採掘する";
			case 4:
				return "金を5個採掘する";
			case 5:
				return "金を10個採掘する";
		}
		return "0";
	}

	public function getDairy2():string {
		switch ($this->getVar("DAIRY2")){
			case 6:
				return "ダイヤを3つ採掘する";
			case 7:
				return "ダイヤを6つ採掘する";
			case 8:
				return "鉱石を25個採掘する";
			case 9:
				return "鉱石を100個採掘する";
			case 10:
				return "石を128個採掘する";
		}
		return "0";
	}

	public function getDairy3():string {
		switch ($this->getVar("DAIRY3")){
			case 11:
				return "木材を100個以上使って建築する";
			case 12:
				return "ガチャを5回引く";
			case 13:
				return "ガチャを10回引く";
			case 14:
				return "プレイヤーを3回キルする";
			case 15:
				return "プレイヤーを10回キルする";
		}
		return "0";
	}

	public function getMaxDairy1():int{
		switch ($this->getVar("DAIRY1")){
			case 1:
			case 2:
				return 30;
			case 3:
				return 50;
			case 4:
				return 5;
			case 5:
				return 10;
		}
		return 0;
	}

	public function getMaxDairy2():int{
		switch ($this->getVar("DAIRY2")){
			case 6:
				return 3;
			case 7:
				return 6;
			case 8:
				return 25;
			case 9:
				return 100;
			case 10:
				return 128;
		}
		return 0;
	}

	public function getMaxDairy3():int{
		switch ($this->getVar("DAIRY3")){
			case 11:
				return 100;
			case 12:
				return 5;
			case 13:
			case 15:
				return 10;
			case 14:
				return 3;
		}
		return 0;
	}

	public function getNowDairy1(){
		return $this->data["DAIRYTASK1"];
	}

	/**
	 * @return mixed
	 */
	public function getNowDairy2(){
		return $this->data["DAIRYTASK2"];
	}

	/**
	 * @return mixed
	 */
	public function getNowDairy3(){
		return $this->data["DAIRYTASK3"];
	}

	public function setRepeatBonus(bool $bool=true){
		$this->data["REPEATBONUS"]=$bool;
	}

	/**
	 * @return bool
	 */
	public function getRepeatBonus():bool{
		return $this->data["REPEATBONUS"];
	}

	public function getSpaceShipEffect():int{
		return $this->data["SPACESHIP_EFFECT"];
	}

	public function setSpaceShipEffect(int $id){
		$this->data["SPACESHIP_EFFECT"]=$id;
	}

	public function getJumpEffect():int{
		return $this->data["JUMP_EFFECT"];
	}

	public function setJumpEffect(int $id){
		$this->data["SPACESHIP_EFFECT"]=$id;
	}

	public function getSpaceShipOreCount(string $ore):int{
		return $this->data["SPACESHIP_".$ore];
	}

	public function addSpaceShipOreCount(string $ore,int $count=1){
		$this->data["SPACESHIP_".$ore]+=$count;
	}



	/**
	 * @param int $number
	 * @return int
	 */
	public function isLevelup(int $number){
		if($this->main->isKakinDay($this->getPlayer())){
			$number=$number*2;
		}

		if(idate("j")%5===0) $number=$number*2;

		if($this->isNormalLevel()){
			$lt=floor($this->data["LEVEL"]*1.1);
			$leveltable=30*$lt;
			$re=0;
			for ($n = 1; $n <= $number; $n++) {
				$nmbr = 1;
				$this->data["MAXTABLEEXP"]  = $this->data["MAXTABLEEXP"] + $nmbr;
				$this->data["EXP"] = $this->data["EXP"] + $nmbr;
				if($this->data["LEVEL"]<501) {
					if ($this->data["MAXTABLEEXP"] >= $leveltable) {
						$this->data["OLDTABLEEXP"] = $this->data["OLDTABLEEXP"] + $this->data["MAXTABLEEXP"];
						$this->data["MAXTABLEEXP"] = 0;
						++$this->data["LEVEL"];
						$re = 4;
						if ($this->data["LEVEL"] == 500) {
							$re = 3;
						}
						if ($this->data["LEVEL"] % 100 == 0) {
							$re = 2;
						}
						if ($this->data["LEVEL"] % 10 == 0) {
							$re = 1;
						}
					}
				}
			}
			return $re;
		}

		if($this->isAdvanceLevel()) {
			$lt = floor($this->data["LEVEL"] * 1.3);
			$leveltable = 120 * $lt;
			$re = 0;
			for ($n = 1; $n <= $number; $n++) {
				$nmbr = 1;
				$this->data["MAXTABLEEXP"] = $this->data["MAXTABLEEXP"] + $nmbr;
				$this->data["EXP"] = $this->data["EXP"] + $nmbr;
				if ($this->data["LEVEL"] < 1000) {
					if ($this->data["MAXTABLEEXP"] >= $leveltable) {
						$this->data["OLDTABLEEXP"] = $this->data["OLDTABLEEXP"] + $this->data["MAXTABLEEXP"];
						$this->data["MAXTABLEEXP"] = 0;
						++$this->data["LEVEL"];
						$re = 4;
						if ($this->data["LEVEL"] % 100 == 0) {
							$re = 2;
						}
						if ($this->data["LEVEL"] % 10 == 0) {
							$re = 1;
						}
					}
				}
			}
			return $re;
		}

		if($this->isExpertLevel()){
			$lt = floor($this->data["LEVEL"] * 1.5);
			$leveltable = 250 * $lt;
			$re=0;
			for ($n = 1; $n <= $number; $n++) {
				$nmbr = 1;
				$this->data["MAXTABLEEXP"] = $this->data["MAXTABLEEXP"] + $nmbr;
				$this->data["EXP"] = $this->data["EXP"] + $nmbr;
				if ($this->data["LEVEL"] < 500) {
					if ($this->data["MAXTABLEEXP"] >= $leveltable) {
						$this->data["OLDTABLEEXP"] = $this->data["OLDTABLEEXP"] + $this->data["MAXTABLEEXP"];
						$this->data["MAXTABLEEXP"] = 0;
						++$this->data["LEVEL"];
						$re = 4;
						if ($this->data["LEVEL"] % 100 == 0) {
							$re = 2;
						}
						if ($this->data["LEVEL"] % 10 == 0) {
							$re = 1;
						}
					}
				}
			}
			return $re;
		}

		return 0;
	}

	public function setAll(){
		$this->data=PlayerConfigManager::set($this->config);
		$this->load=true;
	}

	/**
	 * @param int $money
	 */
	public function save(int $money=0){
		$name=$this->getName();
		if(main::getMain()->loginbonus->exists($name)) {
			if(main::getMain()->loginbonus->get($name) !== date("Y/m/d")) {
				$this->changeDay();
				main::getMain()->loginbonus->set($name, date("Y/m/d"));
				main::getMain()->loginbonus->save();
			}
		} else {
			$this->changeLoginBonus();
			$this->addLoginDay();
			$this->changeDairy();
			$this->setBonus(false);
			main::getMain()->loginbonus->set($name, date("Y/m/d"));
			main::getMain()->loginbonus->save();
		}
		$ts=$this->time;
		$nts=time();
		$lasttime=$nts-$ts;

		PlayerConfigManager::save($this->config,$this->data,$this->getPlayer()->getAddress(),$this->stay+$lasttime);
	}

	public function configVersion(){
		return PlayerConfigManager::getVersion($this->config);
	}

	public function updateConfig(){
		PlayerConfigManager::update($this->getName());
	}

}