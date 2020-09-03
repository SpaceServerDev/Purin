<?php


namespace SSC\Event\player;


use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use SSC\main;

class CommandPreProcessEvent implements Listener {
	public function onPCPE(PlayerCommandPreprocessEvent $event) {
		$player = $event->getPlayer();
		$m = $event->getMessage();
		if ($m[0] == "/") {
			if (main::getMain()->blacklist->exists($player->getName())) {
				$event->setCancelled();
			}
			if (main::getPlayerData($player->getName())->getShitDownNow()) {
				ShitDownEvent::StandUp($player);
			}
		}

	}

}