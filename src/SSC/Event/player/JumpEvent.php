<?php


namespace SSC\Event\player;


use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJumpEvent;
use SSC\main;

class JumpEvent implements Listener {

	public function onJump(PlayerJumpEvent $event) {
		$player = $event->getPlayer();
		$pe=main::getPlayerData($player->getName());
		if($pe->getShitDownNow()){
			ShitDownEvent::StandUp($player);
		}
		if ($player->getLevel()->getFolderName() == "Neptune") {
			$armor = $player->getArmorInventory();
			$item = $armor->getBoots();
			if ($item->getId() === 305) {
				if ($item->getCustomName() === "§b重力制御装置") {
					return false;
				}
			}
			$pos = $player->getLocation()->multiply(0, 0.05, 0);
			$pos->y = 1.3;
			$player->setMotion($pos);
		} elseif ($player->getLevel()->getFolderName() == "mars") {
			$armor = $player->getArmorInventory();
			$item = $armor->getBoots();
			if ($item->getId() === 305) {
				if ($item->getCustomName() === "§b重力制御装置") {
					return false;
				}
			}
			$pos = $player->getLocation()->multiply(0, 0.05, 0);
			$pos->y = 0.6;
			$player->setMotion($pos);
		}
		return true;
	}

}