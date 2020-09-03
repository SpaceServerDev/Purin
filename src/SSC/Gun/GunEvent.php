<?php


namespace SSC\Gun;


use pocketmine\level\Explosion;
use pocketmine\level\Level;
use pocketmine\level\particle\DustParticle;
use pocketmine\level\particle\SmokeParticle;
use pocketmine\level\Position;
use pocketmine\Server;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\level\particle\FlameParticle;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\MovePlayerPacket;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\Player;
use SSC\Gun\Task\GunShootTask;
use SSC\Gun\Task\ReloadTask;
use SSC\main;
use SSC\Task\EventGenerater;

class GunEvent implements Listener {

	public function onTouch(PlayerInteractEvent $event) {
		$player = $event->getPlayer();
		$gunmanager = main::getMain()->getGunManager();
		if ($player->getInventory()->getItemInHand()->getNamedTag()->offsetExists("gun")) {
			if (!$player->getInventory()->getItemInHand()->getNamedTag()->offsetExists("serial")) {
				$player->sendPopup("[管理AI] テスト版の銃ですので使用できません");
				return true;
			}
			if (!$player->getInventory()->getItemInHand()->getNamedTag()->offsetExists("fullauto")) {
				$player->sendPopup("[管理AI] テスト版の銃ですので使用できません");
				return true;
			}

			$gun = $player->getInventory()->getItemInHand()->getNamedTag()->getString("gun");
			$serial = $player->getInventory()->getItemInHand()->getNamedTag()->getString("serial");
			$auto = $player->getInventory()->getItemInHand()->getNamedTag()->getString("fullauto");
			$gundata = $gunmanager->getGunData($gun, $serial);

			if (!$gundata->isShootNow()) {
				if ($gundata->getAmmo() > 0) {
					if ($player->isSneaking()) {
						if ($gundata->getAmmo() !== $gundata->getMaxAmmo()) {
							$this->reloadEvent($player, $gundata, $gun, $serial);
							$this->sound("music.machinegun-magazine-remove1", $player->getFloorX(), $player->getFloorY(), $player->getFloorZ(), $player->getLevel());
							return true;
						}
					}
				}
			}

			/*full auto*/
			if ($auto == "yes") {
				if (!$gundata->getCoolDown()) {
					if (!$gundata->isShootNow()) {
						if ($gundata->getAmmo() > 0) {
							$task = new GunShootTask($player, $gundata, $this);
							main::getMain()->getScheduler()->scheduleDelayedRepeatingTask($task, $gundata->getDelayTick(), $gundata->getPeriodTick());
							$gundata->startShoot($task->getTaskId());
							return true;
						} else {
							$this->reloadEvent($player, $gundata, $gun, $serial);
							$this->sound("music.machinegun-magazine-remove1", $player->getFloorX(), $player->getFloorY(), $player->getFloorZ(), $player->getLevel());
							return true;
						}
					} else {
						main::getMain()->getScheduler()->cancelTask($gundata->getTaskId());
						$gundata->endShoot();
						$this->sound("music.cartridge1", $player->getFloorX(), $player->getFloorY(), $player->getFloorZ(), $player->getLevel());
						return true;
					}
				}
			}
			/*semi auto*/
			if ($auto == "sniper" or $auto == "rocket") {
				if (!$gundata->getCoolDown()) {
					if (!$gundata->isShootNow()) {
						if ($gundata->getAmmo() > 0) {
							$gundata->removeAmmo();
							if($auto == "sniper") {
								$this->noTaskShootEvent($player,$gundata);
								$this->sound("music.largerifle-firing1", $player->getFloorX(), $player->getFloorY(), $player->getFloorZ(), $player->getLevel());
							}else{
								main::getMain()->getScheduler()->scheduleRepeatingTask(new EventGenerater($this->RocketShootEvent($player, $gundata)), 0.2);
								$this->sound("music.missile1", $player->getFloorX(), $player->getFloorY(), $player->getFloorZ(), $player->getLevel());
							}
							$motion = $player->getDirectionVector()->multiply(-$gundata->getRecoil());
							$player->setMotion($motion);

							$pk = new MovePlayerPacket();
							$pk->entityRuntimeId = $player->getId();
							$pk->position = $player->getPosition();
							$pk->yaw = $player->getYaw();
							$pk->pitch = $player->getPitch() - 2;
							$pk->headYaw = $player->getYaw() - 2;
							$pk->mode = MovePlayerPacket::MODE_PITCH;
							$pk->onGround = $player->isOnGround();
							$pk->entityRuntimeId = $player->getId();
							$player->sendDataPacket($pk);
							$player->resetFallDistance();
							$player->setForceMovementUpdate();
							$ammo = "";
							$now = $gundata->getAmmo();
							for ($i = 0; $i < $now; $i++) {
								$ammo = $ammo . "||";
							}
							for ($i = 0; $i < $gundata->getMaxAmmo() - $now; $i++) {
								$ammo = $ammo . " ";
							}
							$player->sendPopup("§c" . $ammo . "§e({$now})");
							$gundata->startCoolDown();
							main::getMain()->getScheduler()->scheduleDelayedTask(new ReloadTask($player, $this, $gun, $serial, false), $gundata->getCoolDownTick());
							if($auto == "sniper") {
								$this->sound("music.sniperrifle-boltaction1", $player->getFloorX(), $player->getFloorY(), $player->getFloorZ(), $player->getLevel());
							}

							return true;
						} else {
							$this->reloadEvent($player, $gundata, $gun, $serial);
							return true;
						}
					}
				}
			}
		}
		return true;
	}

	private function reloadEvent(Player $player,Gun $gundata,string $gun,string $serial) {
		$player->sendPopup("Reloaded");
		$gundata->reload();
		$gundata->startCoolDown();
		main::getMain()->getScheduler()->scheduleDelayedTask(new ReloadTask($player,$this, $gun, $serial), $gundata->getReloadTick());
	}

	public function ShootEvent(Player $entity,Gun $gundata) {
		$particle = new FlameParticle(new Vector3($entity->x, $entity->y + 1.5, $entity->z));
		$particle->setComponents($entity->x, $entity->y + 1.5, $entity->z);

		$increase = $entity->getDirectionVector()->normalize();
		for ($i = 0; $i < $gundata->getDistance(); $i++) {
			yield;
			$pos = $particle->add($increase);
			if (!$entity->level->getBlock($pos)->canBeFlowedInto()){
				foreach ($entity->level->getPlayers() as $player) {
					if ($player->distance($pos) < 8.0 && $entity !== $player) {
						$this->playerSound("music.bullets-bounce1",$player, 0.6);
					}
				}
				break;
			}
			$particle->setComponents($pos->x, $pos->y, $pos->z);
			$entity->level->addParticle($particle);
			foreach ($entity->level->getPlayers() as $player) {
				if ($player->distance($pos) < 1.5 && $entity !== $player) {
					$event = new EntityDamageByEntityEvent($entity, $player, EntityDamageEvent::CAUSE_PROJECTILE, $gundata->getDamage(), [], $gundata->getKnockBack());
					$this->sound("music.attack",$entity->getFloorX(),$entity->getFloorY(),$entity->getFloorZ(),$entity->getLevel());
					$player->attack($event);
					break 2;
				}
			}
		}
	}

		public function noTaskShootEvent(Player $entity,Gun $gundata) {
		$particle = new FlameParticle(new Vector3($entity->x, $entity->y + 1.5, $entity->z));
		$particle->setComponents($entity->x, $entity->y + 1.5, $entity->z);

		$increase = $entity->getDirectionVector()->normalize();
		for ($i = 0; $i < $gundata->getDistance(); $i++) {
			$pos = $particle->add($increase);
			if (!$entity->level->getBlock($pos)->canBeFlowedInto()){
				foreach ($entity->level->getPlayers() as $player) {
					if ($player->distance($pos) < 8.0 && $entity !== $player) {
						$this->playerSound("music.bullets-bounce1",$player, 0.6);
					}
				}
				break;
			}
			$particle->setComponents($pos->x, $pos->y, $pos->z);
			$entity->level->addParticle($particle);
			foreach ($entity->level->getPlayers() as $player) {
				if ($player->distance($pos) < 1.5 && $entity !== $player) {
					$event = new EntityDamageByEntityEvent($entity, $player, EntityDamageEvent::CAUSE_PROJECTILE, $gundata->getDamage(), [], $gundata->getKnockBack());
					$this->sound("music.attack",$entity->getFloorX(),$entity->getFloorY(),$entity->getFloorZ(),$entity->getLevel());
					$player->attack($event);
					break 2;
				}
			}
		}
	}

	public function RocketShootEvent(Player $entity,Gun $gundata) {
		$pos=new Vector3($entity->x, $entity->y + 1.5, $entity->z);
		$particle = new SmokeParticle($pos,1000);
		$particle->setComponents($entity->x, $entity->y + 1.5, $entity->z);

		$increase = $entity->getDirectionVector()->normalize();
		for ($i = 0; $i < $gundata->getDistance(); $i++) {
			yield;
			$pos = $particle->add($increase);
			if (!$entity->level->getBlock($pos)->canBeFlowedInto()){
				$explosion = new Explosion(new Position($pos->x,$pos->y,$pos->z,$entity->getLevel()), 1);
				$explosion->explodeB();
				$this->sound("music.bomb2",$entity->getFloorX(),$entity->getFloorY(),$entity->getFloorZ(),$entity->getLevel(),0.7);
				break;
			}
			$particle->setComponents($pos->x, $pos->y, $pos->z);
			$entity->level->addParticle($particle);
			foreach ($entity->level->getPlayers() as $player) {
				if ($player->distance($pos) < 1.5 && $entity !== $player) {
					$explosion = new Explosion(new Position($pos->x,$pos->y,$pos->z,$entity->getLevel()), 1);
					$explosion->explodeB();
					$this->sound("music.bomb2",$entity->getFloorX(),$entity->getFloorY(),$entity->getFloorZ(),$entity->getLevel(),0.7);
					break 2;
				}
			}
		}
		$explosion = new Explosion(new Position($pos->x,$pos->y,$pos->z,$entity->getLevel()), 1);
		$explosion->explodeB();
		$this->sound("music.bomb2",$entity->getFloorX(),$entity->getFloorY(),$entity->getFloorZ(),$entity->getLevel(),0.7);
	}

	public function sound(string $name,$x,$y,$z,Level $level,$vol=0.5){
		$pk2 = new PlaySoundPacket;
		$pk2->soundName = $name;
		$pk2->x = $x;
		$pk2->y = $y;
		$pk2->z = $z;
		$pk2->volume = $vol;
		$pk2->pitch = 1;
		Server::getInstance()->broadcastPacket($level->getPlayers(),$pk2);
	}

	public function playerSound(string $name,Player $player,$vol=0.5){
		$pk2 = new PlaySoundPacket;
		$pk2->soundName = $name;
		$pk2->x = $player->x;
		$pk2->y = $player->y;
		$pk2->z = $player->z;
		$pk2->volume = $vol;
		$pk2->pitch = 1;
		$player->dataPacket($pk2);
	}


}