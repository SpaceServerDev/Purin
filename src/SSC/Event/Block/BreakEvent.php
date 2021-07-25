<?php


namespace SSC\Event\Block;


use onebone\economyapi\EconomyAPI;
use pocketmine\level\Level;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\block\Block;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use SSC\Async\LogSaveAsyncTask;
use SSC\Item\RepairCream;
use SSC\main;
use SSC\PlayerEvent;

class BreakEvent implements Listener {

	private $main;

	public function __construct(main $main) {
		$this->main = $main;
	}

	/**
	 * @priority MONITOR
	 * @ignoreCancelled
	 */
	public function onBreak(BlockBreakEvent $event) {

		$player = $event->getPlayer();
		$name = $player->getName();
		$id = $event->getBlock()->getId();
		$item = $player->getInventory()->getItemInHand();


		if ($player->getGamemode() === 3) {
			if ($event->getBlock()->getId() === 199) {
				$event->setCancelled();
				return true;
			}
		}

		if ($this->main->blacklist->exists($player->getName())) {
			$event->setCancelled();
		}
		/**@var $playerdata PlayerEvent */
		$playerdata = main::getPlayerData($name);
		if ($playerdata->getLog()) {
			$x = $event->getBlock()->getFloorX();
			$y = $event->getBlock()->getFloorY();
			$z = $event->getBlock()->getFloorZ();
			$world = $event->getBlock()->getLevel()->getName();
			$this->main->checklog($x, $y, $z, $world, $player);
			$event->setCancelled();
			return true;
		}

		$x = $event->getBlock()->getFloorX();
		$y = $event->getBlock()->getFloorY();
		$z = $event->getBlock()->getFloorZ();
		$world = $event->getBlock()->getLevel()->getName();
		$id = $event->getBlock()->getId();
		$damage = $event->getBlock()->getDamage();
		main::getMain()->registerlog($x, $y, $z, $world, $id, $damage, $player, "b");

		if ($player->getLevel()->getFolderName() === "pluto") {
			if (!$playerdata->isExpertLevel()) {
				$event->setCancelled();
				return true;
			}
		}

		/*
		 * 整地ワールド
		 */
		if ($player->getLevel()->getFolderName() === "trappist-1e") {
			$block = $event->getBlock();
			$x = $block->getX();
			$y = $block->getY() + 3;
			$z = $block->getZ();
			$block = Server::getInstance()->getLevelByName("trappist-1e")->getBlock(new Vector3($x, $y, $z));
			$id = $block->getID();


			if ($event->getBlock()->getId() === 17 or $event->getBlock()->getId() === 162 or $id === 0) {

				$block = $event->getBlock();
				$x = $block->getX();
				$y = $block->getY();
				$z = $block->getZ();
				switch ($item->getCustomName()) {

					case "§b海王星の氷のツルハシ(TRAPPIST-1e専用)":
					case "§4天王星の縦の環のシャベル(TRAPPIST-1e専用)":
					case "§c火星の嵐の力(TRAPPIST-1e専用)":
					case "§8土星のガスと塵のツルハシ(TRAPPIST-1e専用)":
						if ($y <= 0) {
							$event->setCancelled();
							break;
						}
						if ($player->isSneaking()) {
							break;
						}
						switch ($player->getDirection()) {
							case 0://+x
								$pos = [new Vector3($x, $y + 1, $z), new Vector3($x + 1, $y, $z), new Vector3($x + 1, $y + 1, $z), new Vector3($x, $y, $z + 1), new Vector3($x, $y + 1, $z + 1), new Vector3($x, $y, $z - 1), new Vector3($x, $y + 1, $z - 1), new Vector3($x + 1, $y, $z + 1), new Vector3($x + 1, $y + 1, $z + 1), new Vector3($x + 1, $y, $z - 1), new Vector3($x + 1, $y + 1, $z - 1)];
								foreach ($pos as $poss) {
									self::isAir($poss, $player->getLevel(), $playerdata, $player);
								}
								break;
							case 1://+z
								$pos = [new Vector3($x, $y + 1, $z), new Vector3($x, $y, $z + 1), new Vector3($x, $y + 1, $z + 1), new Vector3($x + 1, $y, $z), new Vector3($x + 1, $y + 1, $z), new Vector3($x - 1, $y, $z), new Vector3($x - 1, $y + 1, $z), new Vector3($x + 1, $y, $z + 1), new Vector3($x + 1, $y + 1, $z + 1), new Vector3($x - 1, $y, $z + 1), new Vector3($x - 1, $y + 1, $z + 1)];
								foreach ($pos as $poss) {
									self::isAir($poss, $player->getLevel(), $playerdata, $player);
								}
								break;
							case 2://-x
								$pos = [new Vector3($x, $y + 1, $z), new Vector3($x - 1, $y, $z), new Vector3($x - 1, $y + 1, $z), new Vector3($x, $y, $z + 1), new Vector3($x, $y + 1, $z + 1), new Vector3($x, $y, $z - 1), new Vector3($x, $y + 1, $z - 1), new Vector3($x - 1, $y, $z + 1), new Vector3($x - 1, $y + 1, $z + 1), new Vector3($x - 1, $y, $z - 1), new Vector3($x - 1, $y + 1, $z - 1)];
								foreach ($pos as $poss) {
									self::isAir($poss, $player->getLevel(), $playerdata, $player);
								}
								break;
							case 3://-z
								$pos = [new Vector3($x, $y + 1, $z), new Vector3($x, $y, $z - 1), new Vector3($x, $y + 1, $z - 1), new Vector3($x + 1, $y, $z), new Vector3($x + 1, $y + 1, $z), new Vector3($x - 1, $y, $z), new Vector3($x - 1, $y + 1, $z), new Vector3($x + 1, $y, $z - 1), new Vector3($x + 1, $y + 1, $z - 1), new Vector3($x - 1, $y, $z - 1), new Vector3($x - 1, $y + 1, $z - 1),];
								foreach ($pos as $poss) {
									self::isAir($poss, $player->getLevel(), $playerdata, $player);
								}
								break;

						}
						break;
					case "§8冥王星の如く小さめのシャベル(TRAPPIST-1e専用)":
					case "§8月のような静寂のツルハシ(TRAPPIST-1e専用)":
					case "§b地球を感じる奇跡のシャベル(TRAPPIST-1e専用)":
					case "§b木星の力がみなぎる軽いツルハシ(TRAPPIST-1e専用)":
						if ($y <= 0) {
							$event->setCancelled();
							break;
						}
						if ($player->isSneaking()) {
							break;
						}
						self::isAir(new Vector3($x, $y + 1, $z), $player->getLevel(), $playerdata, $player);
						break;
					case "§b水星のように重い斧(TRAPPIST-1e専用)":
						if ($y <= 0) {
							$event->setCancelled();
							break;
						}
						if ($event->getBlock()->getId() === 17 or $event->getBlock()->getId() === 162) {
							if ($block->getSide(Vector3::SIDE_DOWN)->getId() === $block->getId()) {
								$player->sendPopup("[管理AI]§a木は下から堀りましょう");
								$event->setCancelled();
								return true;
							} else {
								$n = 0;
								while (true) {
									$n++;
									if ($player->getLevel()->getBlock(new Vector3($x, $y + $n, $z))->getId() === $event->getBlock()->getId()) {
										$player->getLevel()->setBlock(new Vector3($x, $y + $n, $z), Block::get(0, 0));
										$playerdata->addVar("BREAK", 1);
										$playerdata->addVar("TRAPPIST", 1);
										if ($playerdata->getVar("TRAPPIST") === 250000 or $playerdata->getVar("TRAPPIST") === 50000) {
											$player->addTitle("実績を達成", "§aトラピストで{$playerdata->getVar("TRAPPIST")}個破壊した！", 30, 30, 20);
										}
									} else {
										$playerdata->removeVar("TRAPPIST");
										break;
									}
								}

							}
						}
						break;
				}
				$event->setDrops([]);
				switch (mt_rand(1, 150000)) {
					case 5000:
					case 2000:
					case 1000:
					case 3000:
					case 4000:
						$item = Item::get(386, 0, 1);
						if ($player->getInventory()->canAddItem($item)) {
							$player->getInventory()->addItem($item);
							$player->sendMessage("§cほってたら埋もれた新品の本を見つけた");
						}
						break;
					case 8000:
						$item = Item::get(278, 0, 1);
						if ($player->getInventory()->canAddItem($item)) {
							$item->setCustomName("§d恒星の輝きを放つピッケル");
							$enchantment = Enchantment::getEnchantment(15);
							$item->addEnchantment(new EnchantmentInstance($enchantment, 4));
							$enchantment = Enchantment::getEnchantment(17);
							$item->addEnchantment(new EnchantmentInstance($enchantment, 3));
							$player->getInventory()->addItem($item);
							$player->sendMessage("§cなんだこれ。ほってたら使えなさそうなボロボロでサビサビのピッケルを見つけた");
						}
						break;
					case 10000:
					case 10001:
					case 10002:
					case 10003:
						$item = RepairCream::get();
						if ($player->getInventory()->canAddItem($item)) {
							$player->getInventory()->addItem($item);
							$player->sendMessage("§cうわ！きったない！修復クリームだ！");
						}
						break;
				}
				$playerdata->addVar("TRAPPIST", 1);
				if ($playerdata->getVar("TRAPPIST") === 250000 or $playerdata->getVar("TRAPPIST") === 50000) {
					$player->addTitle("実績を達成", "§aトラピストで{$playerdata->getVar("TRAPPIST")}個破壊した！", 30, 30, 20);
				}
			} else {
				$player = $event->getPlayer();
				$event->setCancelled();
				$player->sendPopup("[管理AI]§a上からきれいに掘りましょう");
				return true;
			}
		} else {
			switch ($item->getCustomName()) {
				case "§b水星のように重い斧(TRAPPIST-1e専用)":
				case "§b木星の力がみなぎる軽いツルハシ(TRAPPIST-1e専用)":
				case "§c火星の嵐の力(TRAPPIST-1e専用)":
				case "§8土星のガスと塵のツルハシ(TRAPPIST-1e専用)":
				case "§b地球を感じる奇跡のシャベル(TRAPPIST-1e専用)":
				case "§b海王星の氷のツルハシ(TRAPPIST-1e専用)":
				case "§4天王星の縦の環のシャベル(TRAPPIST-1e専用)":
				case "§8冥王星の如く小さめのシャベル(TRAPPIST-1e専用)":
				case "§8月のような静寂のツルハシ(TRAPPIST-1e専用)":
					$event->setCancelled();
					$player->sendPopup("[管理AI]§aこのワールドでは使用できないアイテムです");
					return true;
					break;
			}
		}


		if (!($event->isCancelled())) {
			if ($player->getGamemode() == 0) {
				if ($item->hasEnchantments()) {
					if ($item->getEnchantment(16)) {
						return true;
					}
				}

				switch ($id) {
					case 1:
						$this->main->addEXP($player, 1);
						if ($playerdata->getVar("DAIRY2") === 10) {
							$playerdata->addVar("DAIRYTASK2");
							if ($playerdata->getMaxDairy2() === $playerdata->getNowDairy2()) {
								$player->addTitle("§bデイリーボーナスを達成", "", 30, 30, 20);
							}
						}
						break;
					case 15:
						$playerdata->addVar("IRON");
						if ($playerdata->getVar("DAIRY2") === 8 or $playerdata->getVar("DAIRY2") === 9) {
							$playerdata->addVar("DAIRYTASK2");
							if ($playerdata->getMaxDairy2() === $playerdata->getNowDairy2()) {
								$player->addTitle("§bデイリーボーナスを達成", "", 30, 30, 20);
							}
						}
						if ($playerdata->getVar("IRON") === 1000 or $playerdata->getVar("IRON") === 500) {
							$player->addTitle("実績を達成", "§a鉄を{$playerdata->getVar("IRON")}個集めた！", 30, 30, 20);
						}
						main::isOres($player, $playerdata);
						break;
					case 14:
						$playerdata->addVar("GOLD");
						if ($playerdata->getVar("DAIRY1") === 4 or $playerdata->getVar("DAIRY1") === 5) {
							$playerdata->addVar("DAIRYTASK1");
							if ($playerdata->getMaxDairy1() === $playerdata->getNowDairy1()) {
								$player->addTitle("§bデイリーボーナスを達成", "", 30, 30, 20);
							}
						}
						if ($playerdata->getVar("DAIRY2") === 8 or $playerdata->getVar("DAIRY2") === 9) {
							$playerdata->addVar("DAIRYTASK2");
							if ($playerdata->getMaxDairy2() === $playerdata->getNowDairy2()) {
								$player->addTitle("§bデイリーボーナスを達成", "", 30, 30, 20);
							}
						}
						if ($playerdata->getVar("GOLD") === 100 or $playerdata->getVar("GOLD") === 500) {
							$player->addTitle("実績を達成", "§a金を{$playerdata->getVar("GOLD")}個集めた！", 30, 30, 20);
						}
						main::isOres($player, $playerdata);
						break;
					case 17:
					case 162:
						$this->main->addEXP($player, 2);//木
						$playerdata->addVar("WOOD");
						if ($playerdata->getVar("DAIRY1") === 1) {
							$playerdata->addVar("DAIRYTASK1");
							if ($playerdata->getMaxDairy1() === $playerdata->getNowDairy1()) {
								$player->addTitle("§bデイリーボーナスを達成", "", 30, 30, 20);
							}
						}
						if ($playerdata->getVar("WOOD") === 1000 or $playerdata->getVar("WOOD") === 500) {
							$player->addTitle("実績を達成", "§a木を{$playerdata->getVar("WOOD")}個集めた！", 30, 30, 20);
						}
						break;

					case 21:
						$this->main->addEXP($player, 30);//ラピス
						$playerdata->addTicket("RED");
						$playerdata->addVar("LAPIS");
						if ($playerdata->getVar("DAIRY2") === 8 or $playerdata->getVar("DAIRY2") === 9) {
							$playerdata->addVar("DAIRYTASK2");
							if ($playerdata->getMaxDairy2() === $playerdata->getNowDairy2()) {
								$player->addTitle("§bデイリーボーナスを達成", "", 30, 30, 20);
							}
						}
						if ($playerdata->getVar("LAPIS") === 50 or $playerdata->getVar("LAPIS") === 500) {
							$player->addTitle("実績を達成", "§aラピスラズリを{$playerdata->getVar("LAPIS")}個集めた！", 30, 30, 20);
						}
						main::isOres($player, $playerdata);
						break;

					case 56:
						$this->main->addEXP($player, 50);//ダイヤ
						$playerdata->addTicket("RED");
						$playerdata->addVar("DIAMOND");
						if ($playerdata->getVar("DAIRY2") === 6 or $playerdata->getVar("DAIRY2") === 7 or $playerdata->getVar("DAIRY2") === 8 or $playerdata->getVar("DAIRY2") === 9) {
							$playerdata->addVar("DAIRYTASK2");
							if ($playerdata->getMaxDairy2() === $playerdata->getNowDairy2()) {
								$player->addTitle("§bデイリーボーナスを達成", "", 30, 30, 20);
							}
						}
						if ($playerdata->getVar("DIAMOND") === 250 or $playerdata->getVar("DIAMOND") === 50) {
							$player->addTitle("実績を達成", "§aダイアモンドを{$playerdata->getVar("DIAMOND")}個集めた！", 30, 30, 20);
						}
						main::isOres($player, $playerdata);
						break;

					case 73:
					case 74:
						$this->main->addEXP($player, 10);//レッドストーン(光ってるやつも)
						$playerdata->addTicket("RED");
						$playerdata->addVar("REDSTONE");
						if ($playerdata->getVar("DAIRY2") === 8 or $playerdata->getVar("DAIRY2") === 9) {
							$playerdata->addVar("DAIRYTASK2");
							if ($playerdata->getMaxDairy2() === $playerdata->getNowDairy2()) {
								$player->addTitle("§bデイリーボーナスを達成", "", 30, 30, 20);
							}
						}
						if ($playerdata->getVar("REDSTONE") === 1000 or $playerdata->getVar("REDSTONE") === 7000) {
							$player->addTitle("実績を達成", "§レッドストーンを{$playerdata->getVar("REDSTONE")}個集めた！", 30, 30, 20);
						}
						main::isOres($player, $playerdata);
						break;

					case 129:
						$this->main->addEXP($player, 20);//エメラルド
						$playerdata->addTicket("RED");
						$playerdata->addVar("EMERALD");
						if ($playerdata->getVar("DAIRY2") === 8 or $playerdata->getVar("DAIRY2") === 9) {
							$playerdata->addVar("DAIRYTASK2");
							if ($playerdata->getMaxDairy2() === $playerdata->getNowDairy2()) {
								$player->addTitle("§bデイリーボーナスを達成", "", 30, 30, 20);
							}
						}
						if ($playerdata->getVar("EMERALD") === 10 or $playerdata->getVar("EMERALD") === 50) {
							$player->addTitle("実績を達成", "§aエメラルドを{$playerdata->getVar("EMERALD")}個集めた！", 30, 30, 20);
						}
						main::isOres($player, $playerdata);
						break;

					case 16:
						$this->main->addEXP($player, 10);//石炭
						$playerdata->addTicket("RED");
						$playerdata->addVar("COAL");
						if ($playerdata->getVar("DAIRY1") === 2 or $playerdata->getVar("DAIRY1") === 3) {
							$playerdata->addVar("DAIRYTASK1");
							if ($playerdata->getMaxDairy1() === $playerdata->getNowDairy1()) {
								$player->addTitle("§bデイリーボーナスを達成", "", 30, 30, 20);
							}
						}
						if ($playerdata->getVar("DAIRY2") === 8 or $playerdata->getVar("DAIRY2") === 9) {
							$playerdata->addVar("DAIRYTASK2");
							if ($playerdata->getMaxDairy2() === $playerdata->getNowDairy2()) {
								$player->addTitle("§bデイリーボーナスを達成", "", 30, 30, 20);
							}
						}
						if ($playerdata->getVar("COAL") === 1000 or $playerdata->getVar("COAL") === 5000) {
							$player->addTitle("実績を達成", "§a石炭を{$playerdata->getVar("COAL")}個集めた！", 30, 30, 20);
						}
						main::isOres($player, $playerdata);
						break;

					case 87:
						if ($player->getLevel()->getFolderName() === "sun") {
							$this->main->addEXP($player, 5);//ネザーラック
						} else {
							$this->main->addEXP($player, 1);
						}
						break;
					case 168:
						if ($player->getLevel()->getFolderName() === "pluto") {
							$this->main->addEXP($player, 10);//青いやつ
						} else {
							$this->main->addEXP($player, 2);
						}
						break;
					default:
						$this->main->addEXP($player, 1);
						break;
				}

				if ($playerdata->getJob() === "木こり") {

					if ($event->getBlock()->getId() === 17 || $event->getBlock()->getId() === 162) {
						economyAPI::getInstance()->addMoney($name, 10);
					}
				} else if ($playerdata->getJob() === "採掘業") {
					switch (true) {
						case $event->getBlock()->getId() === 1:
							economyAPI::getInstance()->addMoney($name, 4);
							break;
						case $event->getBlock()->getId() === 16:
							economyAPI::getInstance()->addMoney($name, 12);
							break;
						case $event->getBlock()->getId() === 129:
							economyAPI::getInstance()->addMoney($name, 15);
							break;
						case $event->getBlock()->getId() === 73 || $event->getBlock()->getId() === 74:
							economyAPI::getInstance()->addMoney($name, 20);
							break;
						case $event->getBlock()->getId() === 56:
							economyAPI::getInstance()->addMoney($name, 100);
							break;
						case $event->getBlock()->getId() === 21:
							economyAPI::getInstance()->addMoney($name, 40);
							break;
						case $event->getBlock()->getId() === 121:
							economyAPI::getInstance()->addMoney($name, 6);
					}

				} else if ($playerdata->getJob() === "整地師") {
					if ($event->getBlock()->getId() === 2 || $event->getBlock()->getId() === 3) {
						economyAPI::getInstance()->addMoney($name, 5);
					}
					if ($event->getBlock()->getId() === 12) {
						economyAPI::getInstance()->addMoney($name, 5);
					}
				} else if ($playerdata->getJob() === "高度整地師") {
					if ($player->getLevel()->getFolderName() === "sun") {
						if ($event->getBlock()->getId() === 87) {
							economyAPI::getInstance()->addMoney($name, 6);
						}
						if ($event->getBlock()->getId() === 168) {
							economyAPI::getInstance()->addMoney($name, 10);
						}
					}
				} else if ($playerdata->getJob() === "農家") {
					$item = Block::get(59, 7);
					if ($event->getBlock()->getId() === 59) {
						if ($event->getBlock()->getDamage() >= 7) {
							economyAPI::getInstance()->addMoney($name, 10);
						}
					}
				}
			}
		}
		$playerdata->addVar("BREAK", 1);
		if (main::getMain()->isDrops($player)) {
			if ($id !== 218) {
				$event->setDrops([]);
			}
		}


		if ($playerdata->getVar("BREAK") === 1000000 or $playerdata->getVar("BREAK") === 100000) {
			$player->addTitle("実績を達成", "§aブロックを{$playerdata->getVar("BREAK")}個破壊した！", 30, 30, 20);
		}

		return true;
	}


	public static function isAir(Vector3 $pos, Level $level, PlayerEvent $playerdata, Player $player) {
		if ($level->getBlock($pos)->getId() != 0) {
			$level->setBlock($pos, Block::get(0, 0));
			$playerdata->addVar("BREAK", 1);
			$playerdata->addVar("TRAPPIST", 1);
			if ($playerdata->getVar("TRAPPIST") === 250000 or $playerdata->getVar("TRAPPIST") === 50000) {
				$player->addTitle("実績を達成", "§aトラピストで{$playerdata->getVar("TRAPPIST")}個破壊した！", 30, 30, 20);
			}
		}
	}

}