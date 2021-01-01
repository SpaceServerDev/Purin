<?php

namespace SSC\Event;

use pocketmine\nbt\tag\IntTag;
use pocketmine\network\mcpe\protocol\EmotePacket;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\plugin\PluginBase;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;

use pocketmine\level\Position;
use pocketmine\level\Level;
use pocketmine\level\particle\DustParticle;

use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;

use pocketmine\block\Block;

use pocketmine\math\Vector3;

use pocketmine\tile\Sign;

use pocketmine\event\server\DataPacketReceiveEvent;

use pocketmine\entity\Entity;
use pocketmine\entity\EffectInstance;
use pocketmine\entity\Effect;

use pocketmine\utils\Config;
use pocketmine\utils\Random;

use onebone\economyapi\EconomyAPI;
use pocketmine\event\Listener;

use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;
use pocketmine\network\mcpe\protocol\ModalFormResponsePacket;

use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;


use SSC\Command\LevelCommandReturn;
use SSC\Command\ShopCommandReturn;

use SSC\Form\ApologyForm;
use SSC\Form\DailyForm;
use SSC\Form\RepeatLoginbonusForm;
use SSC\main;
use SSC\PlayerEvent;

class FormEvent implements Listener{

	private $Main;

	public function __construct(Main $main){
		$this->Main= $main;
	}

	public function onDataPacketReceive(DataPacketReceiveEvent $event) {
		$player = $event->getPlayer();
		$name = $player->getName();
		$pk = $event->getPacket();
		if ($pk instanceof EmotePacket) {
			$player = $event->getPlayer();
			$newPacket = EmotePacket::create($player->getId(), $pk->getEmoteId(), 1);
			Server::getInstance()->broadcastPacket($player->getViewers(), $newPacket);
		}

		if ($pk->getName() == "ModalFormResponsePacket") {
			$mymoney = $this->Main->economyAPI->myMoney($player->getName());
			$data = $pk->formData;
			$result = json_decode($data, true);
			/*
			   * その他
			   */
			if ($data != "null\n") {
				/**@var $playerdata PlayerEvent */
				$playerdata = $this->Main->getPlayerData($name);
				switch ($pk->formId) {
					case 30718:
					case 99774:
					case 99775:
					case 99776:
					case 99991:
					case 97643:
					case 64435:
					case 23456:
						new ShopCommandReturn($this->Main, $pk->formId, $player, $pk->formData, $result);
						break;//shop


					case 32123:
						$this->Main->itiji[$name] = null;
						$this->Main->itemid[$name] = null;
						$uf = $this->Main->getDataFolder() . "Mywarp/" . $name . ".yml";
						$mywarp = new Config($uf, Config::YAML);
						if ($data == 0) {
							if (!empty($mywarp->getAll())) {
								$formdata = [
									["type" => "label",
										"text" => "ワープポイントにワープ",],
									["type" => "dropdown",
										"text" => "ワープポイントを選んでください",],
								];
								foreach ($mywarp->getAll(true) as $wn) {
									$this->Main->itiji[$name][] = $wn;
									$formdata[1]["options"][] = $wn;
								}
								$playerdata->customForm("§dmywarp", $formdata, 32121);
							} else {
								$player->sendMessage("[管理AI]ワープポイントが存在しません");
								return false;
							}
						} else if ($data == 1) {
							$formdata = [
								["type" => "label",
									"text" => "ワープポイントを今いる場所にセットします\n\n",],
								["type" => "input",
									"text" => "ワープポイントの名前",],
								["type" => "toggle",
									"text" => "他の人に公開",],
							];
							$playerdata->customForm("§dmywarp", $formdata, 32122);
						} else if ($data == 2) {
							if (!empty($mywarp->getAll())) {
								$formdata = [
									["type" => "label",
										"text" => "ワープポイントの削除",],
									["type" => "dropdown",
										"text" => "ワープポイントを選んでください",],
								];
								foreach ($mywarp->getAll(true) as $wn) {
									$this->Main->itiji[$name][] = $wn;
									$formdata[1]["options"][] = $wn;
								}
								$playerdata->customForm("§dmywarp", $formdata, 32120);
								return false;
							} else {
								$player->sendMessage("[管理AI]ワープポイントが存在しません");
								return false;
							}
						} else {
							$formdata = [
								["type" => "label",
									"text" => "他人のマイワープにワープできます！",],
								["type" => "input",
									"text" => "名前",],
							];
							$playerdata->customForm("§dmywarp", $formdata, 32124);
							return false;
						}
						return false;
						break;//マイワープ

					case 32122:
						if ($result[1] === null) {
							$player->sendMessage("[管理AI]§4名前が入力されていません");
							return false;
						}
						if ($result[1] === "") {
							$player->sendMessage("[管理AI]§4名前が入力されていません");
							return false;
						}
						if (is_numeric($result[1]) == true) {
							$player->sendMessage("[管理AI]§4数字のみの登録はできません");
							return false;
						}
						$uf = $this->Main->getDataFolder() . "Mywarp/" . $name . ".yml";
						$yourwarp = new Config($uf, Config::YAML);

						$x = $player->getFloorX();
						$y = $player->getFloorY();
						$z = $player->getFloorZ();
						$level = $player->getLevel()->getFolderName();
						if ($level == "pvp") {
							$player->sendMessage("[管理AI]§4pvpの登録はできません");
							return false;
						}
						$nm = $result[1];

						$yourwarp->set($nm, array(
							"x1" => $x,
							"y1" => $y,
							"z1" => $z,
							"level" => $level,
							"public" => $result[2]
						));
						$yourwarp->save();
						if (!$yourwarp->exists($result[1])) {
							$player->sendMessage("[管理AI] §a上書きが完了しました");
						}
						$player->sendMessage("[管理AI] §a登録が完了しました");
						break;//マイワープ登録

					case 32120:
						$uf = $this->Main->getDataFolder() . "Mywarp/" . $name . ".yml";
						$yourwarp = new Config($uf, Config::YAML);
						$warpname = $this->Main->itiji[$name][$result[1]];
						$player->sendMessage("[管理AI] §a{$warpname}を削除しました!");
						$yourwarp->remove($warpname);
						$yourwarp->save();
						$this->Main->itiji[$name] = null;
						break; //マイワープ削除

					case 32121:
						$uf = $this->Main->getDataFolder() . "Mywarp/" . $name . ".yml";
						$yourwarp = new Config($uf, Config::YAML);
						$warpname = $this->Main->itiji[$name][$result[1]];
						if ($yourwarp->get($warpname)["level"] == "pvp") {
							$player->sendMessage("[管理AI]§4pvpのワープはできません。削除しました。");
							$yourwarp->remove($warpname);
							$yourwarp->save();
							return false;
						}
						$pos = new Position($yourwarp->get($warpname)["x1"], $yourwarp->get($warpname)["y1"], $yourwarp->get($warpname)["z1"], $this->Main->getServer()->getLevelByName($yourwarp->get($warpname)["level"]));
						if ($player->getGamemode() == 0) {
							$player->setAllowFlight(false);
							$player->setFlying(false);
						}
						$player->sendMessage("[管理AI] §a{$warpname}にワープしました!");
						$player->teleport($pos);
						$this->Main->itiji[$name] = null;
						break;//マイワープワープ

					case 32124:
						if ($result[1] == "" || $result[1] == null) {
							$player->sendMessage("[管理AI]§4プレイヤー名を入力してください");
							return false;
						}
						$uf = $this->Main->getDataFolder() . "Mywarp/" . $result[1] . ".yml";
						$yourwarp = new Config($uf, Config::YAML);
						if (empty($yourwarp->getAll())) {
							$player->sendMessage("[管理AI]§4プレイヤーが存在していないかマイワープのデータがありません");
							unlink($uf);
							return true;
						}
						$formdata = [
							["type" => "label",
								"text" => "ワープポイントにワープ",],
							["type" => "dropdown",
								"text" => "{$result[1]}のワープポイントを選んでください",],
						];
						foreach ($yourwarp->getAll(true) as $wn) {
							if ($yourwarp->get($wn)["public"] == true) {
								$this->Main->itiji[$name][] = $wn;
								$formdata[1]["options"][] = $wn;
							}
						}
						if (empty($formdata[1]["options"]) == true) {
							$player->sendMessage("[管理AI]§4マイワープが公開されていません");
							return false;
						}
						$this->Main->itemid[$name] = $result[1];
						$playerdata->customForm("§dmywarp", $formdata, 32125);

						break;//他人ワープ1

					case 32125:
						$uf = $this->Main->getDataFolder() . "Mywarp/" . $this->Main->itemid[$name] . ".yml";
						$yourwarp = new Config($uf, Config::YAML);
						$warpname = $this->Main->itiji[$name][$result[1]];
						if ($yourwarp->get($warpname)["level"] == "sun") {
							if (!$playerdata->isSun()) {
								$player->sendMessage("[管理AI]§4開放されていないワールドです");
								return false;
							}
						}
						$pos = new Position($yourwarp->get($warpname)["x1"], $yourwarp->get($warpname)["y1"], $yourwarp->get($warpname)["z1"], $this->Main->getServer()->getLevelByName($yourwarp->get($warpname)["level"]));
						if ($player->getGamemode() == 0) {
							$player->setAllowFlight(false);
							$player->setFlying(false);
						}
						$player->sendMessage("[管理AI] §a{$this->Main->itemid[$name]}の{$warpname}にワープしました!");
						$player->teleport($pos);
						$this->Main->itiji[$name] = null;
						$this->Main->itemid[$name] = null;
						break;//他人ワープ2

					case 10004:
						$aitename = $this->Main->itiji[$name]["name"][$result[0]];
						$aite = $this->Main->getServer()->getPlayer($aitename);
						$aitedata = $this->Main->getPlayerData($aitename);
						if($aitedata->isExpertLevel()) $aitename="§d".$aitename;
						$aitedata->setDisplayName($aitename);
						$aite->sendMessage("§6[管理AI]称号がリセットされました");
						$player->sendMessage("§6[管理AI]{$aite->getName()}の称号をリセットしました");
						break;//称号リセット

					case 33334:
						$aitename = $this->Main->itiji[$name]["name"][$result[0]];
						$aite = $this->Main->getServer()->getPlayer($aitename);
						$aitedata = $this->Main->getPlayerData($aitename);
						$aitedata->setTagName($aitename);
						$aite->sendMessage("§6[管理AI]あたまがリセットされました");
						$player->sendMessage("§6[管理AI]{$aite->getName()}のあたまのうえをリセットしました");
						break;//あたまリセット

					case 11111:
						$this->Main->itiji[$name] = null;
						if ($data == 0) {
							if ($player->isOp()) {
								$formdata = [
									["type" => "dropdown",
										"text" => "相手さん",],
									["type" => "input",
										"text" => "称号。枠と称号のみ入力してください",],
								];
								foreach ($this->Main->getServer()->getOnlinePlayers() as $plyr) {
									$nme = $plyr->getName();
									$this->Main->itiji[$name]["name"][] = $nme;
									$formdata[0]["options"][] = $nme;
								}
							} else {
								$formdata = [
									["type" => "label",
										"text" => "称号を1000￥で変えれます\n§e現在の所持金 → $mymoney ￥",],
									["type" => "input",
										"text" => "称号のみ入力してください",],
								];
							}
							$playerdata->customForm("§d称号", $formdata, 10001);

						} else if ($data == 1) {
							if (!$player->isOp()) {
								if($playerdata->isExpertLevel()) $name="§d".$name;
								$playerdata->setDisplayName($name);
								$player->sendMessage("§6[管理AI]称号がリセットされました");
								return false;
							} else {
								$formdata = [
									["type" => "dropdown",
										"text" => "相手さん",],
								];
								foreach ($this->Main->getServer()->getOnlinePlayers() as $plyr) {
									$nme = $plyr->getName();
									$this->Main->itiji[$name]["name"][] = $nme;
									$formdata[0]["options"][] = $nme;
								}
								$playerdata->customForm("§d称号", $formdata, 10004);
							}
						}
						break;//称号

					case 33333:
						$this->Main->itiji = null;
						if ($data == 0) {
							$formdata = [
								["type" => "dropdown",
									"text" => "相手さん",],
								["type" => "input",
									"text" => "あたま。枠とあたまのみ入力してください",],
							];
							foreach ($this->Main->getServer()->getOnlinePlayers() as $plyr) {
								$nme = $plyr->getName();
								$this->Main->itiji[$name]["name"][] = $nme;
								$formdata[0]["options"][] = $nme;
							}
							$playerdata->customForm("§dあたま", $formdata, 33332);
						} else if ($data == 1) {
							$formdata = [
								["type" => "dropdown",
									"text" => "相手さん",],
							];
							foreach ($this->Main->getServer()->getOnlinePlayers() as $plyr) {
								$nme = $plyr->getName();
								$this->Main->itiji[$name]["name"][] = $nme;
								$formdata[0]["options"][] = $nme;
							}
							$playerdata->customForm("§dあたま", $formdata, 33334);
						}
						break;//あたま

					case 33332:
						if ($result[1] === null) {
							return false;
						}
						if ($result[1] === "") {
							$player->sendMessage("[管理AI]§4あたまが入力されていません");
							return false;
						}
						$aitename = $this->Main->itiji[$name]["name"][$result[0]];
						$aite = $this->Main->getServer()->getPlayer($aitename);
						$aitedata = $this->Main->getPlayerData($aitename);
						if ($aite instanceof Player) {
							$aitedata->setTagName($result[1] . $aitename);
						} else {
							$player->sendMessage("そのようなプレイヤーは存在しません");
						}
						break;//あたまセット

					case 10001:
						if ($result[1] === null) {
							return false;
						}
						if ($result[1] === "") {
							$player->sendMessage("[管理AI]§4称号が入力されていません");
							return false;
						}
						if ($player->isOp()) {
							$aitename = $this->Main->itiji[$name]["name"][$result[0]];
							$aite = $this->Main->getServer()->getPlayer($aitename);
							$aitedata = $this->Main->getPlayerData($aitename);
							if ($aite instanceof Player) {
								$display= $result[1]  . $aite->getName();
								if($aitedata->isExpertLevel()) $display= $result[1] . " §d" . $aite->getName()."§r";
								$aitedata->setDisplayName($display);
								$aite->sendMessage("§6[管理AI]称号が{$result[1]}§f{$aite->getName()}§6に変更されました");
								$player->sendMessage("§6[管理AI]{$aite->getName()}の称号を{$display}§6に変更しました");
							} else {
								$player->sendMessage("そのようなプレイヤーは存在しません");
							}
						} else {
							$nagasa = mb_strlen($result[1]);
							if ($nagasa <= 15) {
								if ($mymoney > 1000) {
									$this->Main->economyAPI->reduceMoney($name, 1000);
									$display="[" . $result[1] . "§r]" . $player->getName();
									if($playerdata->isExpertLevel()) $display="[" . $result[1] . "§r] §d" . $player->getName()."§r";
									$playerdata->setDisplayName($display);
									$player->sendMessage("§6[管理AI]{$player->getName()}の称号を§f{$display}§6に変更しました");
								} else {
									$player->sendMessage("[管理AI]§4お金が足りていません");
								}
							} else {
								$player->sendMessage("[管理AI]§4称号の長さは色コード含み15文字以内です");
							}
						}
						break;//称号セット


					case 78787:
						if (!isset($result[0])) {
							$player->sendMessage("[管理AI]§4名前が入力されていません");
							return false;
						}
						if ($result[0] === null) {
							return false;
						}
						if ($result[0] === "") {
							$player->sendMessage("[管理AI]§4名前が入力されていません");
							return false;
						}

						$uf = $this->Main->getDataFolder() . "Player/" . $result[0] . ".yml";
						$this->Main->users = new Config($uf, Config::YAML);
						$file = $this->Main->getDataFolder() . "Player/" . $result[0] . ".yml";
						if ($this->Main->users->exists("3")) {
							if (!$this->Main->users->exists("3")) {
								$player->sendMessage("[管理AI]§4プレイヤーが存在していません");
								unlink($file);
								return true;
							} else {
								$buttons[] = [
									'text' => "§c§lおわる",
								];
								$nm = $result[0];
								$ip = $this->Main->users->get("3")['IP'];
								$prm = $this->Main->users->get("3")['KENGEN'];
								$htkt = $this->Main->users->get("3")['HITOKOTO'];
								$kikk = $this->Main->users->get("3")["KEIKOKU"];
								$ll = date("Y-m-d H:i:s", $this->Main->users->get("3")['LASTLOGIN']);
								$syougou = $this->Main->users->get("3")["SYOUGOU"];
								$atama = $this->Main->users->get("3")['ATAMA'];
								$gg = $this->Main->users->get("3")["GENGO"];
								$xuid = $this->Main->users->get("3")['XUID'];
								$kill = $this->Main->users->get("3")["KILL"];
								$death = $this->Main->users->get("3")["DEATH"];
								$gold = $this->Main->users->get("3")["NORMALMAXEXP"];
								$level = $this->Main->users->get("3")["LEVEL"];
								$exp = $this->Main->users->get("3")["EXP"];
								$exp = $exp + $gold;
								if ($death == 0) {
									$rate = $kill;
								} else {
									$rate = $kill / $death;
									$rate = round($rate, 3);
								}
								$pk = new ModalFormRequestPacket();
								$id = 11223;
								$pk->formId = $id;
								if (!$player->isOP()) {
									$formdata = [
										"type" => "form",
										"title" => "§d§lProfile {$nm}",
										"content" => "§l§a{$nm}§b さんのプロフィール\n§4          警告回数:{$kikk}\n\n§eレベル : {$level}\n§e経験値 : {$exp}\n\n§d権限 : {$prm} \n\n§a称号 §f: {$syougou}\n\n§a頭の上 : §f{$atama}\n\n§cキル : {$kill}\n§cデス : {$death}\n§cキルデスレシオ : {$rate}\n\n§6最終ログイン日時 : {$ll}\n\n§a一言 : §r{$htkt}\n\n\n\n",
										"buttons" => $buttons
									];
								} else {
									$formdata = [
										"type" => "form",
										"title" => "§a§lSPACESERVER INFO",
										"content" => "§l§a{$nm}§b さんのプロフィール\n§4          警告回数:{$kikk}\n§4IPアドレス : {$ip}\n\n§eレベル : {$level}\n§e経験値 : {$exp}\n\n§d権限 : {$prm} \n\n§a称号 §f: {$syougou}\n\n§a頭の上 : §f{$atama}§e\n\n§cキル : {$kill}\n§cデス : {$death}\n§cキルデスレシオ : {$rate}\n\n§6最終ログイン日時 : {$ll}\n\n§4ローカル : §r{$gg}\n\n§4XUID : §r{$xuid}\n\n§a一言 : §r{$htkt}\n\n\n\n",
										"buttons" => $buttons
									];
								}
								$pk->formData = json_encode($formdata, JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING | JSON_UNESCAPED_UNICODE);
								$player->dataPacket($pk);
								return true;
							}
						}
						if (isset($this->Main->users->getAll()[$result[0]]["JOB"]) == true) {
							$nm = $this->Main->users->get($result[0])['NAME'];
							$ip = $this->Main->users->get($result[0])['IP'];
							$prm = $this->Main->users->get($result[0])['KENGEN'];
							$htkt = $this->Main->users->get($result[0])['HITOKOTO'];
							$MNY = $this->Main->users->get($result[0])['MONEY'];
							$kikk = $this->Main->users->get($result[0])["KEIKOKU"];
							$ll = $this->Main->users->get($result[0])['LASTLOGIN'];
							$syougou = $this->Main->users->get($result[0])["SYOUGOU"];
							$atama = $this->Main->users->get($result[0])['ATAMA'];
							$gg = $this->Main->users->get($result[0])["GENGO"];
							$xuid = $this->Main->users->get($result[0])['XUID'];
							$kill = $this->Main->users->get($result[0])["KILL"];
							$death = $this->Main->users->get($result[0])["DEATH"];
							$gold = $this->Main->users->get($result[0])["GOLD"];
							$level = $this->Main->users->get($result[0])["LEVEL"];
							$exp = $this->Main->users->get($result[0])["EXP"];
							$exp = $exp + $gold;
							if ($death == 0) {
								$rate = $kill;
							} else {
								$rate = $kill / $death;
								$rate = round($rate, 3);
							}
							if (!$this->Main->users->exists($result[0])) {
								$player->sendMessage("[管理AI]§4プレイヤーが存在していません");
								unlink($file);
								return true;
							} else {
								$buttons[] = [
									'text' => "§c§lおわる",
								];

								$pk = new ModalFormRequestPacket();
								$id = 11223;
								$pk->formId = $id;
								if (!$player->isOP()) {
									$formdata = [
										"type" => "form",
										"title" => "§d§lProfile {$nm}",
										"content" => "§l§a{$nm}§b さんのプロフィール\n§4          警告回数:{$kikk}\n\n§eレベル : {$level}\n§e経験値 : {$exp}\n\n§d権限 : {$prm} \n\n§a称号 §f: {$syougou}\n\n§a頭の上 : §f{$atama}\n\n§eお金 : {$MNY} ￥\n\n§cキル : {$kill}\n§cデス : {$death}\n§cキルデスレシオ : {$rate}\n\n§6最終ログイン日時 : {$ll}\n\n§a一言 : §r{$htkt}\n\n\n\n",
										"buttons" => $buttons
									];
								} else {
									$formdata = [
										"type" => "form",
										"title" => "§a§lSPACESERVER INFO",
										"content" => "§l§a{$nm}§b さんのプロフィール\n§4          警告回数:{$kikk}\n§4IPアドレス : {$ip}\n\n§eレベル : {$level}\n§e経験値 : {$exp}\n\n§d権限 : {$prm} \n\n§a称号 §f: {$syougou}\n\n§a頭の上 : §f{$atama}§e\n\nお金 : {$MNY} ￥\n§cキル : {$kill}\n§cデス : {$death}\n§cキルデスレシオ : {$rate}\n\n§6最終ログイン日時 : {$ll}\n\n§4ローカル : §r{$gg}\n\n§4XUID : §r{$xuid}\n\n§a一言 : §r{$htkt}\n\n\n\n",
										"buttons" => $buttons
									];
								}
								$pk->formData = json_encode($formdata, JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING | JSON_UNESCAPED_UNICODE);
								$player->dataPacket($pk);

								break;
							}
						} else {
							$nm = $this->Main->users->get($result[0])['NAME'];
							$ip = $this->Main->users->get($result[0])['IP'];
							$prm = $this->Main->users->get($result[0])['KENGEN'];
							$htkt = $this->Main->users->get($result[0])['HITOKOTO'];
							$MNY = $this->Main->users->get($result[0])['MONEY'];
							$kikk = $this->Main->users->get($result[0])["KEIKOKU"];
							$ll = $this->Main->users->get($result[0])['LASTLOGIN'];
							$syougou = $this->Main->users->get($result[0])["SYOUGOU"];
							$atama = $this->Main->users->get($result[0])['ATAMA'];
							$gg = $this->Main->users->get($result[0])["GENGO"];
							$xuid = $this->Main->users->get($result[0])['XUID'];

							if (!$this->Main->users->exists($result[0])) {
								$player->sendMessage("[管理AI]§4プレイヤーが存在していません");
								unlink($file);
								return true;
							} else {
								$buttons[] = [
									'text' => "§c§lおわる",
								];
								$level = 0;
								$exp = 0;
								$kill = 0;
								$death = 0;
								$rate = "No Data";
								$pk = new ModalFormRequestPacket();
								$id = 11223;
								$pk->formId = $id;
								if (!$player->isOP()) {
									$formdata = [
										"type" => "form",
										"title" => "§d§lProfile {$nm}",
										"content" => "§l§a{$nm}§b さんのプロフィール\n\n§eレベル : {$level}\n§e経験値 : {$exp}\n\n§d権限 : {$prm} \n\n§a称号 §f: {$syougou}\n\n§a頭の上 : §f{$atama}\n\n§eお金 : {$MNY} ￥\n\n§cキル : {$kill}\n§cデス : {$death}\n§cキルデスレシオ : {$rate}\n\n§6最終ログイン日時 : {$ll}\n\n§a一言 : §r{$htkt}\n\n\n\n",
										"buttons" => $buttons
									];
								} else {
									$formdata = [
										"type" => "form",
										"title" => "§a§lSPACESERVER INFO",
										"content" => "§l§a{$nm}§b さんのプロフィール\n§4          警告回数:{$kikk}\n§4IPアドレス : {$ip}\n\n§eレベル : {$level}\n§e経験値 : {$exp}\n\n§d権限 : {$prm} \n\n§a称号 §f: {$syougou}\n\n§a頭の上 : §f{$atama}§e\n\nお金 : {$MNY} ￥\n§cキル : {$kill}\n§cデス : {$death}\n§cキルデスレシオ : {$rate}\n\n§6最終ログイン日時 : {$ll}\n\n§4ローカル : §r{$gg}\n\n§4XUID : §r{$xuid}\n\n§a一言 : §r{$htkt}\n\n\n\n",
										"buttons" => $buttons
									];
								}
								$pk->formData = json_encode($formdata, JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING | JSON_UNESCAPED_UNICODE);
								$player->dataPacket($pk);

								break;
							}
						}
						break;//profile

					case 78788:
						if ($result[0] === null) {
							return false;
						}
						if ($result[0] === "") {
							$player->sendMessage("[管理AI]§4一言メッセージが入力されていません");
							return false;
						}
						$playerdata->setHitokoto($result[0]);
						$playerdata->save();
						$player->sendMessage("[管理AI]§a一言メッセージを更新しました！");
						break;//hitokoto

					case 78789:
						if ($result[0] === null) {
							return false;
						}
						if ($result[0] === "") {
							$player->sendMessage("[管理AI]§4プレイヤーが入力されていません");
							return false;
						}
						switch ($result[1]) {
							case 0:
								$permission = "鯖民";
								break;
							case 1:
								$permission = "警察";
								break;
							case 2:
								$permission = "警察庁長官";
								break;
							case 3:
								$permission = "VIP";
								break;
							case 4:
								$permission = "VIPplus";
								break;
							case 5:
								$permission = "OP";
								break;
							case 6:
								$permission = "オーナー";
								break;

						}
						if ($player->getServer()->getPlayer($result[0])) {
							$aitedata = $this->Main->getPlayerData($result[0]);
							$aitedata->setPerm($permission);
							$player->sendMessage("[管理AI]§a権限の設定を完了しました");
							$player->getServer()->getPlayer($result[0])->kick("権限が新たに設定されましたのでリログお願いします");
						} else {
							$player->sendMessage("[管理AI]§aプレイヤーが見つかりません");
						}
						return true;
						break;//parm

					case 78912:
						if ($result[1] == 0) {
							$playerdata->setJob("なし");
						} else if ($result[1] == 1) {
							$playerdata->setJob("木こり");
						} else if ($result[1] == 2) {
							$playerdata->setJob("採掘業");
						} else if ($result[1] == 3) {
							$playerdata->setJob("整地師");
						} else if ($result[1] == 4) {
							$playerdata->setJob("暗殺者");
						} else if ($result[1] == 5) {
							$playerdata->setJob("建築士");
						} else if ($result[1] == 6) {
							$playerdata->setJob("高度整地師");
						} else if ($result[1] == 7) {
							$playerdata->setJob("農家");
						} else if ($result[1] == 8) {
							$playerdata->setJob("漁師");
						}
						break;//job

					case 29913:
						switch ($data) {
							case 0:
								if ($playerdata->isSun()) {
									$player->sendMessage("[管理AI]§a交換の必要はありません！");
									return false;
								} else {
									if ($playerdata->getTicket("RED") < 30) {
										$player->sendMessage("[管理AI]§a赤いチケットが足りません");
										return false;
									} else {
										$this->normalchange($player, 30, "sun");
										$pos = new Vector3($player->x, $player->y, $player->z);
										$player->getLevel()->broadcastLevelSoundEvent($pos, LevelSoundEventPacket::SOUND_POP);
										$player->sendMessage("[管理AI]§a交換に成功しました！");
										$this->normalform($player);
									}
								}
								break;
							case 1:
								if ($playerdata->getTicket("RED") < 50) {
									$player->sendMessage("[管理AI]§a赤いチケットが足りません");
									return false;
								} else {
									$item = Item::get(397, 5, 1);
									if ($player->getInventory()->canAddItem($item)) {
										$this->normalchange($player, 50);
										$player->getInventory()->addItem($item);
										$pos = new Vector3($player->x, $player->y, $player->z);
										$player->getLevel()->broadcastLevelSoundEvent($pos, LevelSoundEventPacket::SOUND_POP);
										$player->sendMessage("[管理AI]§a交換に成功しました！");
										$this->normalform($player);
									} else {
										$player->sendMessage("[管理AI]§aインベントリがいっぱいです");
										return true;
									}
								}
								break;
							case 2:
								if ($playerdata->getTicket("RED") < 10) {
									$player->sendMessage("[管理AI]§a赤いチケットが足りません");
									return false;
								} else {
									$this->normalchange($player, 10, "event");
									$pos = new Vector3($player->x, $player->y, $player->z);
									$player->getLevel()->broadcastLevelSoundEvent($pos, LevelSoundEventPacket::SOUND_POP);
									$player->sendMessage("[管理AI]§a交換に成功しました！");
									$this->normalform($player);
								}
								break;
							case 3:
								if ($playerdata->getTicket("RED") < 3) {
									$player->sendMessage("[管理AI]§a赤いチケットが足りません");
									return false;
								} else {
									$item = Item::get(305, 0, 1);
									$item->setCustomName("§b重力制御装置");
									$enchantment = Enchantment::getEnchantment(9);
									$item->addEnchantment(new EnchantmentInstance($enchantment, 1));
									if ($player->getInventory()->canAddItem($item)) {
										$this->normalchange($player, 3);
										$pos = new Vector3($player->x, $player->y, $player->z);
										$player->getLevel()->broadcastLevelSoundEvent($pos, LevelSoundEventPacket::SOUND_POP);
										$player->getInventory()->addItem($item);
										$player->sendMessage("[管理AI]§a交換に成功しました！");
										$this->normalform($player);
									} else {
										$player->sendMessage("[管理AI]§aインベントリがいっぱいです");
										return true;
									}
								}
								break;
						}
					case 29914:
						switch ($data) {
							case 0:
								if ($playerdata->getTicket("EVENT") < 2) {
									$player->sendMessage("[管理AI]§aイベントチケットが足りません");
									return false;
								} else {
									$item = Item::get(1, 0, 128);
									if ($player->getInventory()->canAddItem($item)) {
										$this->eventchange($player, 2);
										$pos = new Vector3($player->x, $player->y, $player->z);
										$player->getLevel()->broadcastLevelSoundEvent($pos, LevelSoundEventPacket::SOUND_POP);
										$rnd = mt_rand(0, 10);
										switch ($rnd) {
											case 0:
												$item = Item::get(357, 0, 16);
												$item->setCustomName("§d一生懸命作った本命チョコレートクッキー");
												$item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(10), 1));
												$player->getInventory()->addItem($item);
												break;
											case $rnd > 0:
												$item = Item::get(357, 0, 16);
												$item->setCustomName("§dバレンタインチョコ");
												$item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(5), 1));
												$player->getInventory()->addItem($item);
												break;
										}
										$player->sendMessage("[管理AI]§a交換に成功しました！");
										$this->eventform($player);

									} else {
										$player->sendMessage("インベントリに空きがありません。");
									}
								}
								break;
							case 1:
								if ($playerdata->getTicket("EVENT") < 2) {
									$player->sendMessage("[管理AI]§aイベントチケットが足りません");
									return false;
								} else {
									$item = Item::get(357, 0, 16);
									$item->setCustomName("§bちょっと不器用なチョコレートクッキー");
									$item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(10), 1));
									if ($player->getInventory()->canAddItem($item)) {
										$player->getInventory()->addItem($item);
										$this->eventchange($player, 2);
										$pos = new Vector3($player->x, $player->y, $player->z);
										$player->getLevel()->broadcastLevelSoundEvent($pos, LevelSoundEventPacket::SOUND_POP);
										$player->sendMessage("[管理AI]§a交換に成功しました！");
										$this->eventform($player);
									} else {
										$player->sendMessage("インベントリに空きがありません。");
									}
								}
								break;
							case 2:
								if ($playerdata->getTicket("EVENT") < 5) {
									$player->sendMessage("[管理AI]§aイベントチケットが足りません");
									return false;
								} else {
									$item = Item::get(218, 12, 1);
									if ($player->getInventory()->canAddItem($item)) {
										$player->getInventory()->addItem($item);
										$this->eventchange($player, 5);
										$pos = new Vector3($player->x, $player->y, $player->z);
										$player->getLevel()->broadcastLevelSoundEvent($pos, LevelSoundEventPacket::SOUND_POP);
										$player->sendMessage("[管理AI]§a交換に成功しました！");
										$this->eventform($player);
									} else {
										$player->sendMessage("インベントリに空きがありません。");
									}
								}
								break;
							case 3:
								if ($playerdata->getTicket("EVENT") < 7) {
									$player->sendMessage("[管理AI]§aイベントチケットが足りません");
									return false;
								} else {
									$item = Item::get(261, 0, 1);
									$item->setCustomName("§d♡キューピットの弓♡");
									$enchantment = Enchantment::getEnchantment(5);
									$item->addEnchantment(new EnchantmentInstance($enchantment, 1));
									$enchantment = Enchantment::getEnchantment(20);
									$item->addEnchantment(new EnchantmentInstance($enchantment, 1));
									if ($player->getInventory()->canAddItem($item)) {
										$this->eventchange($player, 7);
										$pos = new Vector3($player->x, $player->y, $player->z);
										$player->getLevel()->broadcastLevelSoundEvent($pos, LevelSoundEventPacket::SOUND_POP);
										$player->getInventory()->addItem($item);
										$player->sendMessage("[管理AI]§a交換に成功しました！");
										$this->eventform($player);
									} else {
										$player->sendMessage("[管理AI]§aインベントリがいっぱいです");
										return true;
									}
								}
								break;
							case 4:
								if ($playerdata->getTicket("EVENT") < 1) {
									$player->sendMessage("[管理AI]§aイベントチケットが足りません");
									return false;
								} else {
									$item = Item::get(366, 0, 32);
									$item->setCustomName("§b油淋鶏");
									$item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(10), 1));
									if ($player->getInventory()->canAddItem($item)) {
										$player->getInventory()->addItem($item);
										$this->eventchange($player, 1);
										$pos = new Vector3($player->x, $player->y, $player->z);
										$player->getLevel()->broadcastLevelSoundEvent($pos, LevelSoundEventPacket::SOUND_POP);
										$player->sendMessage("[管理AI]§a交換に成功しました！");
									} else {
										$player->sendMessage("インベントリに空きがありません。");
									}
								}
								break;
							case 5:
								if ($playerdata->getTicket("EVENT") < 8) {
									$player->sendMessage("[管理AI]§aイベントチケットが足りません");
									return false;
								} else {
									$item = Item::get(378, 0, 1);
									$item->setCustomName("§d修復クリーム");
									if ($player->getInventory()->canAddItem($item)) {
										$player->getInventory()->addItem($item);
										$this->eventchange($player, 8);
										$pos = new Vector3($player->x, $player->y, $player->z);
										$player->getLevel()->broadcastLevelSoundEvent($pos, LevelSoundEventPacket::SOUND_POP);
										$player->sendMessage("[管理AI]§a交換に成功しました！");
									} else {
										$player->sendMessage("インベントリに空きがありません。");
									}
								}
								break;
							case 6:
								$this->normalform($player);
								break;
						}
						break;


					case 01201:
						switch ($data) {
							case 0:
								$content = "赤いチケットとは\n鉱石を右側の情報欄の一定数彫り続けてると入手できます\n一定数貯まることで限定アイテムや\n特定のワールドの入場権限が入手できます\n\n関連コマンド===\n/change\n\n";
								break;
							case 1:
								$content = "挨拶は必ずしましょう！\n\n関連コマンド===\n/k 入室時の挨拶\n/oti 退出時の挨拶\n/otu 誰かが退出する際の挨拶\n\n";
								break;
							case 2:
								$content = "ワープはコマンドが多いですが1つにまとまった\nコマンドがございますのでそちらを使うといいと思います！\n\n関連コマンド===\n/warp:ワープ一覧からワープ ←初心者さんにおすすめ！\n/rule : ルール部屋に移動します\n/flat:人工惑星へ移動\n/space : 宇宙へ移動\nほかはルール部屋のコマンド一覧を御覧ください。\n\n";
								break;
							case 3:
								$content = "他人のプロフィールは最終ログイン日時などがみれます\n一言メッセージは自由に変えることができますので\n個性的なプロフィールにしてみましょう！\n\n関連コマンド==\n/profile:他人のプロフィールを確認\n//hitokoto [message]:一言メッセージを変えます。\n\n";
								break;
							case 4:
								$content = "集中するときなどに使うといいと思います\n\n関連コマンド===\n/joho 周りの情報欄を消します\n\n";
								break;
							case 5:
								$content = "最新の情報/infoで確認できます。\n更に先の情報などを見たい場合はdiscordに入ってください\n\n関連コマンド===\n/info 最新情報を確認する";
								break;
							case 6:
								$content = "マイワープは自分で作れるワープポイントです。\n自分で作ったマイワープは人に共有もできます。\n店の宣伝等にもお使いいただけます\n\n関連コマンド===\n/mywarp:マイワープFORMを呼び出す\n/mw playername pointname:/mywarpの簡略版\n\n";
								break;
							case 7:
								$content = "アイテムの捨て方は/dustで全捨てできます。\nもし個々でアイテムを捨てたい場合、sellか公設のゴミ箱をお使いください\n\n関連コマンド===\n/dust 2回実行でインベントリ全消去する\n\n";
								break;
							case 8:
								$content = "土地保護に関してです。\n土地保護は自分の建物の縦横1マスぐらい余裕を持って保護しましょう\n建物の右端で/startp\nその対角で/endp\n値段が良い感じならば/land buyで土地購入が完了します\n\n関連コマンド===\n/startp 始点の設定\n/endp 終点の設定\n/land buy 始点と終点の間を土地保護する\n\n";
								break;
							case 9:
								$content = "ガチャを引きたいときは/gachaコマンドから引けます\n看板に/gacコマンドを記述すれば連続で引けます\n\n関連コマンド===\n/gacha ガチャを引きます\n/gac gachaname ガチャをコマンドのみで引きます\n\n";
								break;
							case 10:
								$content = "ツールの耐久値の回復は/repairコマンドで可能です。\nリペアはダメージ値*10の計算で必要金額がわかります\n\n関連コマンド===\n/repair 武器や防具を修復する\n\n";
								break;
							case 11:
								$content = "ショップでは購入/売却ができます。\n売却は現在id:155までできます！\n\n関連コマンド===\n/shop ショップを開く\n\n";
								break;
							case 12:
								$content = "額縁は土地保護してても取り外せてしまいます\n/fg(frameguard)で額縁を保護しましょう\n保護を外すときは/unfg(UnFrameGuard)で外せます\n\n関連コマンド===\n/fg 額縁保護をする\n/unfg 額縁保護を外す\n\n";
								break;
							case 13:
								$content = "自殺は/su (suicideの略称)でできます\nお腹空いて死にそうなときとか使ってください\n\n関連コマンド===\n/su 自殺する\n\n";
								break;
							/*							 case 14:
															 $content="特定の座標にワープする際は/";
															break;*/
							default:
								$content = "まだ執筆できてません...ごめんなさい";
								break;
						}
						$buttons = [
							['text' => "ほかを見る",],
							['text' => "おわる",],
						];
						$playerdata->normalForm("§d§lCOMMANDHELPER", $content, $buttons, 01202);
						break;

					case 01202:
						if ($data == 0) {
							$this->Main->getServer()->dispatchCommand($player, "cmd");
						}
						break;

					case 48241:
						$level = $playerdata->getLevel();
						switch ($data) {
							case 0:
								if ($level < 10) {
									$player->sendMessage("[管理AI]必要レベルに達していません");
									return true;
								}
								break;
							case 1:
								if ($level < 50) {
									$player->sendMessage("[管理AI]必要レベルに達していません");
									return true;
								}
								break;
							case 2:
								if ($level < 80) {
									$player->sendMessage("[管理AI]必要レベルに達していません");
									return true;
								}
								break;
							case 3:
								$player->sendMessage("[管理AI]まだ準備ができていません。もう少しお待ち下さい");
								return true;
								break;
							case 4:
								if ($level < 150) {
									$player->sendMessage("[管理AI]必要レベルに達していません");
									return true;
								}
								break;
							case 5:
								if ($level < 200) {
									$player->sendMessage("[管理AI]必要レベルに達していません");
									return true;
								}
								break;
							case 6:
								if ($level < 250) {
									$player->sendMessage("[管理AI]必要レベルに達していません");
									return true;
								}
								break;
							case 7:
								if ($level < 300) {
									$player->sendMessage("[管理AI]必要レベルに達していません");
									return true;
								}
								break;
							case 8:
								if ($level < 350) {
									$player->sendMessage("[管理AI]必要レベルに達していません");
									return true;
								}
								break;
							case 9:
								if ($level < 400) {
									$player->sendMessage("[管理AI]必要レベルに達していません");
									return true;
								}
								break;
							case 10:
								if ($level < 450) {
									$player->sendMessage("[管理AI]必要レベルに達していません");
									return true;
								}
								break;
							case 11:
								if ($level < 500) {
									$player->sendMessage("[管理AI]必要レベルに達していません");
									return true;
								}
								break;
						}
						new LevelCommandReturn($this->Main, $player, $data);
						break;



				}
			}

		}
	}

	 private function normalform(Player $player){
		 $name=$player->getName();
		 /**@var $playerdata PlayerEvent */
		 $playerdata=$this->Main->getPlayerData($name);
		 $red=$playerdata->getTicket("RED");
		 $event=$playerdata->getTicket("EVENT");
		 $buttons = [
			 ['text' => "§4太陽探索の権限をもらう\n(赤いチケット:30枚)",],
		 	 ['text' => "§4ドラゴンの頭もらう\n(赤いチケット:50枚)",],
		 	 ['text' => "§aイベントチケットに交換！\n(赤いチケット:10枚)",],
			 ['text' => "§a重力制御装置\n(赤いチケット:3枚)",],
			 //['text' => "§d§lイベント交換所へ！",],
		 ];
		 $playerdata->normalForm("§a§lEXCHANGE Center","§a交換所です！\n\n赤いチケット所持枚数 §c{$red} §a枚\nイベントチケット所持枚数 §c{$event} §a枚\n\n",$buttons,29913);
	 }
	 private function eventform(Player $player){
		 $name=$player->getName();
		 /**@var $playerdata PlayerEvent */
		 $playerdata=$this->Main->getPlayerData($name);
		 $event=$playerdata->getTicket("EVENT");
		 $buttons = [
			 ['text' => "(イベントチケット:2枚)§d女子限定☆\n§bバレンタインのチョコチップクッキー！",],
			 ['text' => "(イベントチケット:2枚)§b男子限定☆\n§aホワイトデーのチョコチップクッキー！",],
			 ['text' => "(イベントチケット:5枚)§6限定色(茶色)のシュルカーボックス",],
		 	 ['text' => "(イベントチケット:7枚)§b♡キューピットの弓♡\n幸せと愛を分け合おう！",],
			 ['text' => "(イベントチケット:1枚)§b油淋鶏\nこれ。プレゼントに最適らしいよ",],
			 ['text' => "(イベントチケット:8枚)§b修復クリーム\nわお！高級チョコ！",],
		 	 ['text' => "いつもの交換所へ",]
			 ];
		 $playerdata->normalForm("§a§lEXCHANGE Center","§aイベント交換所です！\n現在バレンタイン/ホワイトデーイベント開催中！\nイベントチケット所持枚数 §c{$event} §a枚\n\n",$buttons,29914);
	 }

	private function normalchange(Player $player , int $amount,string $data = "none"){
		$name=$player->getName();
		/**@var $playerdata PlayerEvent */
		$playerdata=$this->Main->getPlayerData($name);
		if($data==="event"){
			$playerdata->changeTicket("EVENT");
		}
		if($data==="sun"){
			$playerdata->changeSun(true);
		}
		$playerdata->removeTicket("RED",$amount);
	}

	 private function eventchange(Player $player , int $amount){
		 $name=$player->getName();
		 /**@var $playerdata PlayerEvent */
		 $playerdata=$this->Main->getPlayerData($name);
		 $playerdata->removeTicket("EVENT",$amount);
	 }
}