<?php


namespace SSC\Event\Tile;


use pocketmine\event\block\SignChangeEvent;
use pocketmine\event\Listener;
use pocketmine\Server;
use SSC\main;

class SignChange implements Listener {

	 public function onSignChange(SignChangeEvent $event){
		 $player = $event->getPlayer();
		 $name=$player->getName();
		 $strings = "1.".$event->getLine(0) ."\n2.". $event->getLine(1) ."\n3.". $event->getLine(2) ."\n4.". $event->getLine(3);
		 foreach (Server::getInstance()->getOnlinePlayers() as $ops) {
		 	if(main::getPlayerData($name)->getSign()){
				 if ($ops->isOp()) {
					 $ops->sendPopup("[管理AI] {$name}が{$strings}\nという看板を立てています");
				 }
			 }
		 }
	 }

}