<?php


namespace SSC\Event\Altay;


use onebone\economyapi\EconomyAPI;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerFishEvent;
use pocketmine\plugin\PluginDescription;
use pocketmine\plugin\PluginLoader;
use pocketmine\Server;
use SSC\main;
use SSC\PlayerEvent;

class FishEvent implements Listener{


	/**
	 * Altay only event.
	 *
	 * @param PlayerFishEvent $event
	 */
	public function onFishing(PlayerFishEvent $event){
		if($event->getState()===1){
			$player=$event->getPlayer();
			main::getMain()->addEXP($player,50);
			$pd=main::getPlayerData($player->getName());
			if($pd->getJob()==="漁師") {
				EconomyAPI::getInstance()->addMoney($player->getName(), 20);
			}
			$pd->addFishing();
		}
	}
}