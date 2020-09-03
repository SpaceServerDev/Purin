<?php


namespace SSC\Event\player;


use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\AddActorPacket;
use pocketmine\network\mcpe\protocol\PlayerActionPacket;
use pocketmine\network\mcpe\protocol\RemoveActorPacket;
use pocketmine\network\mcpe\protocol\RemoveEntityPacket;
use pocketmine\network\mcpe\protocol\SetActorLinkPacket;
use pocketmine\network\mcpe\protocol\types\EntityLink;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\entity\Entity;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\AddEntityPacket;
use SSC\main;
use SSC\PlayerEvent;

class ShitDownEvent {

	public static function SeatDown(Player $player,int $x,int $y,int $z){
		$pk = new AddActorPacket();
		$entityRuntimeId=$player->getId()+10000;
		$pe=main::getPlayerData($player->getName());
		$pe->startShitDown($entityRuntimeId);
		$pk->entityRuntimeId=$entityRuntimeId;
		$pk->type=84;
		$pk->position=new Vector3($x + 0.5, $y + 1.5, $z + 0.5);
		$pk->motion=new Vector3(0,0,0);
		$flags=((1 << Entity::DATA_FLAG_IMMOBILE) | (1 << Entity::DATA_FLAG_INVISIBLE));
 		$pk->metadata=[
 			Entity::DATA_FLAGS => [Entity::DATA_TYPE_LONG, $flags],
 		];
 		$setEntity = new SetActorLinkPacket();
        $entLink = new EntityLink( $entityRuntimeId,$player->getId(),EntityLink::TYPE_RIDER,true,true);
        $setEntity->link = $entLink;
		Server::getInstance()->broadcastPacket(Server::getInstance()->getOnlinePlayers(), $pk);
		Server::getInstance()->broadcastPacket(Server::getInstance()->getOnlinePlayers(), $setEntity);
	}

	public static function StandUp(Player $player){
		$pe=main::getPlayerData($player->getName());
		$removepk=new RemoveActorPacket();
		$removepk->entityUniqueId=$pe->getShitDown();
		Server::getInstance()->broadcastPacket (Server::getInstance()->getOnlinePlayers(), $removepk);
		$pe->endShitDown();
	}
}

