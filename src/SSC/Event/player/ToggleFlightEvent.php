<?php


namespace SSC\Event\player;


use pocketmine\event\Listener;
use pocketmine\event\player\PlayerToggleFlightEvent;
use SSC\main;

class ToggleFlightEvent implements Listener {

	public function ToggleFlightEvent(PlayerToggleFlightEvent $event) {
		if ($event->getPlayer()->isOp()) return true;
		if($event->getPlayer()->getLevel()->getName()=="space" or $event->getPlayer()->getLevel()->getName()=="Blackhole") return true;
		if (main::getMain()->kakin->exists($event->getPlayer()->getName())) return true;
		if (main::getPlayerData($event->getPlayer()->getName())->getNumberPerm() >= 3) return true;

		$event->getPlayer()->kick("不正な飛行を検知しました。", false);
		return true;
	}

}