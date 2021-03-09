<?php


namespace SSC\Event\player;

use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\Player;
use pocketmine\Server;

class WarpPlayerEvent {
	public function execute(Player $player,String $level,bool $fly=false) {
		$player->teleport(Server::getInstance()->getLevelByName($level)->getSafeSpawn());
		if ($player->getGamemode() === 0) {
			$player->setAllowFlight($fly);
			$player->setFlying($fly);
			$player->getLevel()->broadcastLevelSoundEvent(Server::getInstance()->getLevelByName($level)->getSafeSpawn(), LevelSoundEventPacket::SOUND_TELEPORT);
		}
	}
}