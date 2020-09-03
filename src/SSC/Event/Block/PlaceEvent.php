<?php

namespace SSC\Event\Block;

use onebone\economyapi\EconomyAPI;
use pocketmine\Server;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;
use pocketmine\utils\Config;
use SSC\Async\LogSaveAsyncTask;
use SSC\main;
use SSC\PlayerEvent;

class PlaceEvent implements Listener {

	private $main;

	public function __construct(Main $main) {
		$this->main = $main;
	}

	/**
	 * @priority MONITOR
	 * @param BlockPlaceEvent $event
	 * @return bool
	 */
	 public function onPlace(BlockPlaceEvent $event) {
		 $player = $event->getPlayer();
		 $name = $player->getName();
		 $x = $event->getBlock()->getFloorX();
		 $y = $event->getBlock()->getFloorY();
		 $z = $event->getBlock()->getFloorZ();
		 $world = $event->getBlock()->getLevel()->getName();
		 /** @var $playerdata PlayerEvent */
		 $playerdata = main::getPlayerData($name);

		 if($player->getGamemode()===3) {
			if ($event->getBlock()->getId() === 199) {
				$event->setCancelled();
				return true;
			}
		}

		 if ($playerdata->getLog()) {
			 $world = $event->getBlock()->getLevel()->getName();
			 $this->main->checklog($x, $y, $z, $world, $player);
			 $event->setCancelled();
			 return true;
		 }

		 if ($event->getPlayer()->getLevel()->getFolderName() === "trappist-1e") {
			 if (!$player->isOp()) {
				 if (!$player->isSneaking()) {
					 $event->setCancelled();
					 $player->sendPopup("[管理AI]スニークしながらおいてください");
					 return true;
				 }
			 }
		 }

		 $id = $event->getBlock()->getId();
		 $damage = $event->getBlock()->getDamage();

		 if ($this->main->blacklist->exists($player->getName())) {
			 $event->setCancelled();
		 }

		 if (main::isBanItem($id)) {
			 if (!$player->isOp()) {
				 $event->setCancelled();
				 $player->sendMessage("[管理AI]このアイテムは使えません。");
				 return false;
			 }
		 }

		 main::getMain()->registerlog($x,$y,$z,$world,$id,$damage,$player,"p");

		 if (!($event->isCancelled())) {
			 if ($player->getGamemode() == 0) {
				 switch ($id) {
				 	 case 5:
				 	 	if($playerdata->getVar("DAIRY3")===11){
						 	$playerdata->addVar("DAIRYTASK3");
						 	if($playerdata->getMaxDairy3()===$playerdata->getNowDairy3()) {
								$player->addTitle("デイリーボーナスを達成", "", 30, 30, 20);
							}
						 }
					 case 17:
					 case 324:
					 case 428:
					 case 429:
					 case 430:
					 case 431:
					 case 158:
					 case 85:
					 case 44:
					 case 45:
					 case 98:
						 $this->main->addEXP($player, 2);
						 if ($playerdata->getJob() == "建築士") {
							 EconomyAPI::getInstance()->addMoney($name, 2);
						 }
						 break;
					 case 37:
					 case 38:
					 case 175:
					 $playerdata->addVar("FLOWER");
					 	if($playerdata->getVar("FLOWER")===100or$playerdata->getVar("FLOWER")===25){
		 					$player->addTitle("実績を達成", "§aお花を{$playerdata->getVar("FLOWER")}個植えた！", 30, 30, 20);
		 				}
					 break;

				 }
				 $playerdata->addVar("PEACE");
			 }

		 }
		 if($playerdata->getVar("PEACE")===50000or$playerdata->getVar("PEACE")===250000){
		 	$player->addTitle("実績を達成", "§aブロックを{$playerdata->getVar("PEACE")}個設置した！", 30, 30, 20);
		 }

		 return true;
	 }
}