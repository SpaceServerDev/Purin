<?php

namespace SSC\Task;


use pocketmine\Player;
use pocketmine\scheduler\Task;
use onebone\economyapi\EconomyAPI;
use pocketmine\network\mcpe\protocol\RemoveObjectivePacket;
use pocketmine\network\mcpe\protocol\SetDisplayObjectivePacket;
use pocketmine\network\mcpe\protocol\SetScorePacket;
use pocketmine\network\mcpe\protocol\types\ScorePacketEntry;
use SSC\main;
use SSC\PlayerEvent;

class Sendtask extends Task{

	private $main;

	private $player;

	public function __construct(main $main,Player $player){
		$this->main = $main;
		$this->player=$player;
	}
	public function onRun($tick) {
		$player = $this->player;
		$armor = $player->getArmorInventory();
		$name = $player->getName();
		/*** @var $playerdata PlayerEvent */
		$playerdata = $this->main->getPlayerData($name);
		if (!$player->isOp()) {
			if ($armor->getHelmet()->getCustomName() != "§aエフェクト防具頭：暗視") {
				if ($player->hasEffect(16)) {
					$player->removeEffect(16);
				}
			}
			if ($armor->getChestplate()->getCustomName() != "§a伝説の宝具 所有者:" . $name) {
				if ($player->hasEffect(3)) {
					$player->removeEffect(3);
				}
			}
			if ($armor->getChestplate()->getCustomName() != "§aエフェクト防具胴：火炎耐性") {
				if ($armor->getLeggings()->getCustomName() != "§aエフェクト防具腰：火炎耐性") {
					if ($player->hasEffect(12)) {
						$player->removeEffect(12);
					}
				}
			}
			if ($armor->getLeggings()->getCustomName() != "§aエフェクト防具腰：跳躍力上昇") {
				if ($player->hasEffect(8)) {
					$player->removeEffect(8);
				}
			}
			if ($armor->getBoots()->getCustomName() != "§aエフェクト防具足：移動速度上昇") {
				if ($armor->getBoots()->getCustomName() != "§bヘルメスの靴") {
					if ($player->hasEffect(1)) {
						$player->removeEffect(1);
					}
				}
			}
			if ($armor->getBoots()->getCustomName() == "§bヘルメスの靴") {
				if ($player->getLevel()->getFolderName() != "pvp") {
					if ($player->hasEffect(1)) {
						$player->removeEffect(1);
					}
				}
			}
		}

		if ($playerdata->getWait()) {
			$playerdata->setWait(false);
		}

		if ($playerdata->getEnableInfo()) {
			$mymoney = EconomyAPI::getInstance()->myMoney($name);
			$x = $player->getfloorX();
			$y = $player->getfloorY();
			$z = $player->getfloorZ();
			$world = $player->getLevel()->getFolderName();
			$onlp = count($player->getServer()->getOnlinePlayers());
			$fullp = $player->getServer()->getMaxPlayers();
			$item = $player->getInventory()->getItemInHand();
			$id = $item->getId();
			$damage = $item->getDamage();
			$time = date("G:i:s");
			$souji = $this->main->seconds;
			$times = main::getMain()->getServerReloadTick();
			$hours = floor($times / 3600);
			$minutes = floor(($times / 60) % 60);
			$seconds = $times % 60;
			$job = $playerdata->getjob();
			if ($job === "高度整地師") {
				$job = "高整";
			}
			$kouseki = $playerdata->getNowOre();
			$mokuteki = $playerdata->getMaxOre();
			$kpn = 0;
			$d1 = $playerdata->getDairy1();
			$d2 = $playerdata->getDairy2();
			$d3 = $playerdata->getDairy3();
			$dn1 = $playerdata->getNowDairy1();
			$dn2 = $playerdata->getNowDairy2();
			$dn3 = $playerdata->getNowDairy3();
			if ($player->getLevel()->getFolderName() == "pvp") {
				foreach ($player->getServer()->getOnlinePlayers() as $kps) {
					if ($kps->getLevel()->getFolderName() == "pvp") {
						++$kpn;
					}
				}
				$minutes = floor($times / 60);
				$seconds = $times % 60;
				$ks = $playerdata->getKillst();

				$this->RemoveData($player);
				$this->setupData($player);
				$this->sendData($player, "§4PVPモード", 0);
				$this->sendData($player, "§5再起動まで: {$minutes}:{$seconds}", 1);
				$this->sendData($player, "§cキルストリーク: {$ks}", 2);
				$this->sendData($player, "§cエリア内: {$kpn}人", 3);
				$this->sendDairyData($player, $d1, $dn1, 21);
				$this->sendDairyData($player, $d2, $dn2, 22);
				$this->sendDairyData($player, $d3, $dn3, 23);
			} else {
				if ($player->getLevel()->getFolderName() == "moon") {
					foreach ($player->getServer()->getOnlinePlayers() as $kps) {
						if ($kps->getLevel()->getFolderName() == "moon") {
							++$kpn;
						}
					}
				}
				$this->RemoveData($player);
				$this->setupData($player);
				$this->sendData($player, "§e所持金: {$mymoney}￥", 0);
				$this->sendData($player, "§b座標: {$x},{$y},{$z}", 1);
				$this->sendData($player, "§bワールド: {$world}", 2);
				$this->sendData($player, "§c現在時刻: {$time}", 3);
				$this->sendData($player, "§6持ってるid: {$id}:{$damage}", 4);
				$this->sendData($player, "§6オンライン人数: {$onlp}/{$fullp}", 5);
				$this->sendData($player, "§6現在のJOB: {$job}", 6);
				$this->sendData($player, "§c掘った鉱石: {$kouseki}/{$mokuteki}", 7);
				$this->sendData($player, "§5次の掃除: {$souji}秒後", 8);
				$this->sendData($player, "§5再起動まで: {$hours}:{$minutes}:{$seconds}", 9);
				$this->sendDairyData($player, $d1, $dn1, 21);
				$this->sendDairyData($player, $d2, $dn2, 22);
				$this->sendDairyData($player, $d3, $dn3, 23);

				if (main::getMain()->isDrops($player)) $this->sendData($player, "§4ノードロップ", 10);

				if ($player->getLevel()->getFolderName() === "moon") $this->sendData($player, "§cエリア内: {$kpn}人", 11);


			}


			if ($player->getInventory()->getItemInHand()->getId() === 346) {
				switch ($this->getFishFeed($player)) {
					case 0:
						$feed = "なし";
						break;
					case 1:
						$feed = "ダイヤ";
						break;
					case 2:
						$feed = "金";
						break;
					case 3:
						$feed = "鉄";
						break;
					case 4:
						$feed = "ミミズ";
						break;
					case 5:
						$feed = "パン";
						break;
					case 6:
						$feed = "薬";
						break;
				}
				$this->sendData($player, "§a餌:{$feed}", 13);
			}
		} else {
			$this->RemoveData($player);
		}

		if ($playerdata->getLevelDisplay()) {
			$this->sendBossbarData($player);
		} else {
			$bar = $playerdata->getBossbar();
			$bar->removePlayer($player);
			if (!$playerdata->getLevelDisplay()) {
				$level = $playerdata->getLevel();
				$zero = $playerdata->getTotalExp();
				$exp = $playerdata->getExp();
				if ($playerdata->isNormalLevel()) {
					$lt = floor($level * 1.1);
					$leveltable = (30 * $lt) + $zero;
				} else if ($playerdata->isAdvanceLevel()) {
					$lt = floor($level * 1.3);
					$leveltable = (120 * $lt) + $zero;
				} else if ($playerdata->isExpertLevel()) {
					$lt = floor($level * 1.5);
					$leveltable = (250 * $lt) + $zero;
				}
				$exp=(int)$leveltable - (int)$exp;
				$this->sendData($player, "次のレベルまで：" .$exp, 12);
			}
		}
	}

	function sendBossbarData(Player $player){
		$name=$player->getName();
		$playerdata = $this->main->getPlayerData($name);
		$level=$playerdata->getLevel();
		$zero=$playerdata->getTotalExp();
		$exp=$playerdata->getExp();
			if($playerdata->isNormalLevel()) {
				$lt = floor($level * 1.1);
				$leveltable = (30 * $lt) + $zero;
			}else if($playerdata->isAdvanceLevel()){
				$lt = floor($level * 1.3);
				$leveltable = (120 * $lt) + $zero;
			}else if($playerdata->isExpertLevel()){
				$lt = floor($level * 1.5);
				$leveltable = (250 * $lt) + $zero;
			}
			$a = $exp - $zero;
			$b = $leveltable - $zero;
			$keiken = $a / $b;
			$bar = $playerdata->getBossbar();
			$bar->removePlayer($player);
			$bar->setTitle("経験値 {$exp} / {$leveltable}")->setSubTitle("名前:{$name} レベル:{$level}")->setPercentage($keiken)->addPlayer($player);
		}

	private function setupData(Player $player){
		$pk = new SetDisplayObjectivePacket();
		$pk->displaySlot = "sidebar";
		$pk->objectiveName = "sidebar";
		$pk->displayName = "§e★§b宇宙サーバー§e★";
		$pk->criteriaName = "dummy";
		$pk->sortOrder = 0;
		$player->sendDataPacket($pk);

		$pk=new SetDisplayObjectivePacket();
		$pk->displaySlot = "list";
		$pk->objectiveName = "list";
		$pk->displayName = "デイリーミッション";
		$pk->sortOrder = 0;
		$pk->criteriaName = "dummy";
		$player->sendDataPacket($pk);

	}

	private function sendDairyData(Player $player,String $data,int $score,int $id){
		$displaySlot = "list";
		$entry = new ScorePacketEntry();
		$entry->objectiveName = $displaySlot;
		$entry->type = $entry::TYPE_FAKE_PLAYER;
		$entry->customName = $data;
		$entry->score = $score;
		$entry->scoreboardId = $id + 11;
		$pk = new SetScorePacket();
		$pk->type = $pk::TYPE_CHANGE;
		$pk->entries[] = $entry;
		$player->sendDataPacket($pk);
	}

	private function sendData(Player $player,String $data,int $id) {
		$displaySlot = "sidebar";
		$entry = new ScorePacketEntry();
		$entry->objectiveName = $displaySlot;
		$entry->type = $entry::TYPE_FAKE_PLAYER;
		$entry->customName = $data;
		$entry->score = $id;
		$entry->scoreboardId = $id + 11;
		$pk = new SetScorePacket();
		$pk->type = $pk::TYPE_CHANGE;
		$pk->entries[] = $entry;
		$player->sendDataPacket($pk);
	}

	function RemoveData(Player $player){
		$pk = new RemoveObjectivePacket();
		$pk->objectiveName = "sidebar";
		$player->sendDataPacket($pk);

		$pk = new RemoveObjectivePacket();
		$pk->objectiveName = "list";
		$player->sendDataPacket($pk);
	}

	private function getFishFeed(Player $player):int{
		$tag=$player->namedtag;
		if(!$tag->offsetExists("FishFeed")){
			return 0;
		}
		return ($tag->getInt("FishFeed"));
	}
}