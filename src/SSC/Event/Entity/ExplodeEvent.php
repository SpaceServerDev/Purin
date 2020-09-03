<?php


namespace SSC\Event\Entity;


use pocketmine\event\entity\EntityExplodeEvent;
use pocketmine\event\Listener;

class ExplodeEvent implements Listener {

	public function onExplode(EntityExplodeEvent $event){
		 $event->setCancelled();
	}

}