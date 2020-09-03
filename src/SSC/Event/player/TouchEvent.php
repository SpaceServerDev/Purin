<?php

namespace SSC\Event\player;

use pocketmine\block\Stair;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\level\particle\DustParticle;
use pocketmine\level\particle\FlameParticle;
use pocketmine\level\particle\HeartParticle;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\InteractPacket;
use pocketmine\network\mcpe\protocol\MovePlayerPacket;
use pocketmine\Player;
use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\Item;
use pocketmine\level\Position;
use pocketmine\tile\Sign;
use SSC\Form\SecretaryForm\SecretaryForm;
use SSC\Gacha\GachaEvent;
use SSC\main;
use SSC\PlayerEvent;
use SSC\Task\EventGenerater;

class TouchEvent implements Listener {

	/**
	 * @var main
	 */
	private $main;

	private $ak47;

	public function __construct(main $main) {
		$this->main = $main;
	}

	public function onTouch(PlayerInteractEvent $event) {
		$player = $event->getPlayer();
		$lava = Item::get(325, 10);
		$water = Item::get(325, 8);
		$item = $event->getItem();


		if($player->getGamemode()===3) {
			if ($event->getBlock()->getId() === 199) {
				$event->setCancelled();
				return true;
			}
		}
		if ($player->getLevel()->getFolderName() != "pvp") {
			if ($item->getId() == 261) {
				if ($event->getBlock()->getId() !== 199) {
					$event->setCancelled();
				}
			}
		}
		/**
		 * @var $pe PlayerEvent
		 */
		$pe = main::getPlayerData($player->getName());
		if ($event->getPlayer()->getInventory()->getItemInHand()->getId() === 280) {
			if ($event->getPlayer()->getInventory()->getItemInHand()->getName() === "§dマジカル☆ステッキ") {
				if (!$pe->getWait()) {
					Server::getInstance()->dispatchCommand($player, "land heree");
					$pe->setWait(true);
					return false;
				}
			}
		}

		if($item->getId()===426){
			if(!$pe->getWait()){
				$player->sendForm(new SecretaryForm());
				$pe->setWait(true);
			}
		}
		if ($item->getId() === 339) {
			$tag = $item->getNamedTag();
			if ($tag->offsetExists("EventGacha")) {
				if (!$pe->getWait()) {
					if ($player->getInventory()->canAddItem(Item::get(1, 1, 1))) {
						$class = new GachaEvent($pe);
						$class->turn();
						$item->setCount(1);
						$player->getInventory()->removeItem($item);
						$pe->setWait(true);
					}
				}
			}
		}

		if ($item->getId() == 329) {
			if ($event->getPlayer()->getLevel()->getFolderName() != "pvp") {
				if ($event->getPlayer()->getPitch() >= 70) {
					$block = $event->getBlock();
					if ($block instanceof Stair) {
						if ($block->getDamage() <= 3) {
							ShitDownEvent::SeatDown($player, $block->getFloorX(), $block->getFloorY(), $block->getFloorZ());
						}
					}
				}
			}
		}
		if ($item->getId() === 401) {
			if ($event->getPlayer()->getLevel()->getFolderName() === "pvp" or $event->getPlayer()->getLevel()->getFolderName() === "world") {
				$event->setCancelled();
				$player->sendMessage("[管理AI]ここでは使えません。");
			}
		}

		if ($event->getBlock()->getID() == 323 || $event->getBlock()->getID() == 63 || $event->getBlock()->getID() == 68) {
			$sign = $event->getPlayer()->getLevel()->getTile($event->getBlock());
			if (!($sign instanceof Sign)) {
				return true;
			}
			if ($pe->getWait()) {
				return true;
			}
			$sign = $sign->getText();
			if ($sign[0] == '§b地球') {
				$cmd = "earth";
				Server::getInstance()->dispatchCommand($player, $cmd);
			}
			if ($sign[0] == '§5ロビーの惑星') {
				$cmd = "spawn";
				Server::getInstance()->dispatchCommand($player, $cmd);
			}
			if ($sign[0] == '§aルールの惑星') {
				$cmd = "rule";
				Server::getInstance()->dispatchCommand($player, $cmd);
			}
			if ($sign[0] == '§1宇宙へ') {
				$cmd = "space 1";
				Server::getInstance()->dispatchCommand($player, $cmd);
			}
			if ($sign[0] == "§a人工惑星1") {
				$cmd = "flat";
				Server::getInstance()->dispatchCommand($player, $cmd);
			}
			if ($sign[0] == "§aくじら座τ星e") {
				$cmd = "taucetuse";
				Server::getInstance()->dispatchCommand($player, $cmd);
			}
			if ($sign[0] == "[COMMAND]") {
				Server::getInstance()->dispatchCommand($player, $sign[2]);
			}
			if ($sign[0] == "cmd") {
				Server::getInstance()->dispatchCommand($player, $sign[1]);
			}
			if ($sign[0] == '§4観戦エリア1へ') {
				if ($player->getGamemode() == 0) {
					$player->getPlayer()->setAllowFlight(false);
					$player->setFlying(false);
				}
				$sun = Server::getInstance()->getLevelByName("pvp");
				$pos = new Position(255, 28, 255, $sun);
				$player->teleport($pos);
			}
			if ($sign[0] == '§4観戦エリア2へ') {
				if ($player->getGamemode() == 0) {
					$player->getPlayer()->setAllowFlight(false);
					$player->setFlying(false);
				}
				$sun = Server::getInstance()->getLevelByName("pvp");
				$pos = new Position(997, 56, 1004, $sun);
				$player->teleport($pos);
			}
		$pe->setWait(true);

		} else if ($event->getBlock()->getID() == 247) {
			$cm = "gacha";
			Server::getInstance()->dispatchCommand($player, $cm);
		} else if ($event->getBlock()->getID() == 10 || $event->getBlock()->getID() == 11 || $event->getBlock()->getID() == 8 || $event->getBlock()->getID() == 9 || $event->getBlock()->getID() == 46 || $event->getBlock()->getID() == 79 || $event->getBlock()->getID() == 51 || $event->getBlock()->getID() == 259) {
			if (!$player->isOp()) {
				$event->setCancelled();
				$player->sendMessage("[管理AI]このアイテムは使えません。");
				return false;
			} else {
				return false;
			}
		} else if ($event->getBlock()->getID() == 325) {
			if (!$player->isOp()) {
				$event->setCancelled();
				$player->sendMessage("[管理AI]このアイテムは使えません。");
				return false;
			} else {
				return false;
			}
		} else if ($event->getItem() === $water) {
			if (!$player->isOp()) {
				$event->setCancelled();
				$player->sendMessage("[管理AI]このアイテムは使えません。");
				return false;
			} else {
				return false;
			}
		} else if ($event->getItem() === $lava) {
			if (!$player->isOp()) {
				$event->setCancelled();
				$player->sendMessage("[管理AI]このアイテムは使えません。");
				return false;
			} else if ($event->getBlock()->getId() === 116) {
				$event->setCancelled();
				return false;
			} else if ($event->getBlock()->getId() === 145) {
				$event->setCancelled();
				return false;
			}

		}
		return true;
	}




}