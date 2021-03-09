<?php


namespace SSC\Event\player;


use pocketmine\event\Listener;
use pocketmine\event\player\PlayerToggleSneakEvent;
use pocketmine\math\Vector3;

class ToggleSneakEvent implements Listener {

	public function onSneakToggleEvent(PlayerToggleSneakEvent $event) {
		$player = $event->getPlayer();
		if (!$event->isSneaking()) {
			if ($player->getLevel()->getBlockAt($player->getFloorX(), $player->getFloorY(), $player->getFloorZ())->getId() === 147) {
				$count = 0;
				$now = -1;
				for ($i = 0; $i < 128; $i++) {
					if ($player->getLevel()->getBlockAt($player->getFloorX(), $i, $player->getFloorZ())->getId() === 147) {
						$count++;
					}
				}
				for ($i = $player->getFloorY(); $i > 0; $i--) {
					if ($player->getLevel()->getBlockAt($player->getFloorX(), $i, $player->getFloorZ())->getId() === 147) {
						$now++;
					}
				}
				for ($i = $player->getFloorY() - 1; $i > 0; $i--) {
					if ($player->getLevel()->getBlockAt($player->getFloorX(), $i, $player->getFloorZ())->getId() === 147) {
						$player->sendTip("{$count}階中{$now}階です");
						$player->teleport(new Vector3($player->getX(), $i, $player->getZ()));
						return;
					}
				}
				$player->sendMessage("下へのエレベーターは存在しません。");
				return;
			}
		}
	}
}