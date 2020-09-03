<?php


namespace SSC\Event\player;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerRespawnEvent;

class RespawnEvent implements Listener {
   public function onRespawn(PlayerRespawnEvent $event) {
	   $player = $event->getPlayer();
	   if ($event->getRespawnPosition()->getLevel()->getFolderName() == "space") {
		   if ($player->getGamemode() == 0) {
			   $player->getPlayer()->setAllowFlight(true);
			   $player->setFlying(true);
		   }
	   } else {
		   if ($player->getGamemode() == 0) {
			   $player->getPlayer()->setAllowFlight(false);
			   $player->setFlying(false);
		   }
	   }
   }
}