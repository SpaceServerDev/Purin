<?php


namespace SSC\Event\player;


use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJumpEvent;
use pocketmine\math\Vector3;
use SSC\main;

class JumpEvent implements Listener {

	public function onJump(PlayerJumpEvent $event) {
		$player = $event->getPlayer();
		$pe=main::getPlayerData($player->getName());
		if($pe->getShitDownNow()){
			ShitDownEvent::StandUp($player);
			return true;
		}

		if($player->getLevel()->getBlockAt($player->getFloorX(),$player->getFloorY(),$player->getFloorZ())->getId()===147) {
			$count=0;
			$now=1;
			for($i=0;$i<128;$i++){
				if ($player->getLevel()->getBlockAt($player->getFloorX(), $i, $player->getFloorZ())->getId() === 147) {
					$count++;
				}
			}
			for ($i = $player->getFloorY(); $i > 0; $i--) {
				if ($player->getLevel()->getBlockAt($player->getFloorX(), $i, $player->getFloorZ())->getId() === 147) {
					$now++;
				}
			}
			for ($i = $player->getFloorY()+1; $i < 128; $i++) {
				if ($player->getLevel()->getBlockAt($player->getFloorX(), $i, $player->getFloorZ())->getId() === 147) {
					$player->sendTip("{$count}階中{$now}階です");
					$player->teleport(new Vector3($player->getX(), $i, $player->getZ()));
					return true;
				}
			}
			$player->sendMessage("上へのエレベーターは存在しません。");
		}

		if ($player->getLevel()->getFolderName() == "Neptune") {
			$armor = $player->getArmorInventory();
			$item = $armor->getBoots();
			if ($item->getId() === 305) {
				if ($item->getCustomName() === "§b重力制御装置") {
					return false;
				}
			}
			$pos = $player->getLocation()->multiply(0,0.5,0);
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
			$pos = $player->getLocation()->multiply(0,0.5,0);
			$pos->y = 0.6;
			$player->setMotion($pos);
		} elseif ($player->getLevel()->getFolderName() == "moon") {
			$armor = $player->getArmorInventory();
			$item = $armor->getBoots();
			if ($item->getId() === 305) {
				if ($item->getCustomName() === "§b重力制御装置") {
					return false;
				}
			}
			$pos = $player->getLocation()->multiply(0,0.5,0);
			$pos->y = 1;
			$player->setMotion($pos);
		}

		return true;
	}

}