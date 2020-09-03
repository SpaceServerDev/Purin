<?php

namespace SSC\Gun\Task;

use pocketmine\network\mcpe\protocol\MovePlayerPacket;
use pocketmine\Player;
use pocketmine\scheduler\Task;
use SSC\Gun\Gun;
use SSC\Gun\GunEvent;
use SSC\main;
use SSC\Task\EventGenerater;

class GunShootTask extends Task {

	private $player;

	/**
	 * @var GunEvent
	 */
	private $event;

	private $gun;

	private $space = "";

	public function __construct(Player $player, Gun $gun,GunEvent $event) {
		$this->player = $player;
		$this->gun = $gun;
		$this->event=$event;

	}

	/**
	 * Actions to execute when run
	 *
	 * @param int $currentTick
	 * @return void
	 */
	public function onRun(int $currentTick) {
		if (!$this->player->getInventory()->getItemInHand()->getNamedTag()->offsetExists("gun")) {
			$this->getHandler()->cancel();
			$this->gun->endShoot();
			$this->event->sound("music.cartridge1",$this->player->getFloorX(),$this->player->getFloorY(),$this->player->getFloorZ(),$this->player->getLevel());
			return;
		}
		if ($this->gun->getAmmo() > 0) {
			if ($this->player->getInventory()->getItemInHand()->getNamedTag()->getString("gun") == $this->gun->getName()) {
				$this->gun->removeAmmo();
				$this->event->sound("music.sniperrifle--firing1",$this->player->getFloorX(),$this->player->getFloorY(),$this->player->getFloorZ(),$this->player->getLevel());
				main::getMain()->getScheduler()->scheduleRepeatingTask(new EventGenerater($this->event->ShootEvent($this->player, $this->gun)), 0.2);

				$motion = $this->player->getDirectionVector()->multiply(-$this->gun->getRecoil());
				$this->player->setMotion($motion);

				$pk = new MovePlayerPacket();
				$pk->entityRuntimeId = $this->player->getId();
				$pk->position = $this->player->getPosition();
				$pk->yaw = $this->player->getYaw();
				$pk->pitch = $this->player->getPitch() - 2;
				$pk->headYaw = $this->player->getYaw() - 2;
				$pk->mode = MovePlayerPacket::MODE_PITCH;
				$pk->onGround = $this->player->isOnGround();
				$pk->entityRuntimeId = $this->player->getId();
				$this->player->sendDataPacket($pk);
				$this->player->resetFallDistance();
				$this->player->setForceMovementUpdate();
				$ammo = "";
				$now = $this->gun->getAmmo();
				for ($i = 0; $i < $now; $i++) {
					$ammo = $ammo . "||";
				}
				for ($i = 0; $i < $this->gun->getMaxAmmo() - $now; $i++) {
					$ammo = $ammo . " ";
				}
				$this->player->sendPopup("§c" . $ammo . "§e({$now})");


			} else {
				$this->getHandler()->cancel();
				$this->gun->endShoot();
				$this->event->sound("music.cartridge1",$this->player->getFloorX(),$this->player->getFloorY(),$this->player->getFloorZ(),$this->player->getLevel());

				return;
			}
		} else {
			$this->getHandler()->cancel();
			$this->gun->endShoot();
			$this->event->sound("music.cartridge1",$this->player->getFloorX(),$this->player->getFloorY(),$this->player->getFloorZ(),$this->player->getLevel());
			return;
		}
	}
}