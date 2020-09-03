<?php


namespace SSC\Event\player;


use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\Player;
use pocketmine\Server;

class WarpPlayerEvent {
	public function execute(Player $player,String $level,bool $fly=false) {
		$player->teleport(Server::getInstance()->getLevelByName($level)->getSafeSpawn());
		if ($player->getGamemode() == 0) {
			$player->getPlayer()->setAllowFlight($fly);
			$player->setFlying($fly);
			$pos = new Vector3($player->x, $player->y, $player->z);
			$player->getLevel()->broadcastLevelSoundEvent($pos, LevelSoundEventPacket::SOUND_TELEPORT);
		}
	}
}