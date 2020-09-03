<?php


namespace SSC\Event\player;


use pocketmine\event\Listener;
use pocketmine\event\player\PlayerPreLoginEvent;
use SSC\main;

class PreLoginEvent implements Listener {

   public function onPreLogin(PlayerPreLoginEvent $event) {
	   $player = $event->getPlayer();
	   $name = strtolower($player->getName());
	   $cid = $player->getUniqueId()->toString();
	   if (main::getMain()->isCBan($cid)) {
		   $reason = main::getMain()->banlist->get($cid);
		   $event->setCancelled();
		   $event->setKickMessage("§cあなたは{$reason}でBANされています");
	   } else {
		   if (main::getMain()->playerlist->exists($cid)) {
			   if (main::getMain()->playerlist->get($cid) != $name) {
				   $event->setCancelled();
				   $event->setKickMessage("§cアカウントは2つ以上持てません");
			   }
		   }
	   }
	   return true;
   }
}