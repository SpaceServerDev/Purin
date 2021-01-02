<?php

namespace SSC\Event\player;

use pocketmine\entity\Human;
use pocketmine\entity\Tamable;
use pocketmine\inventory\BaseInventory;
use pocketmine\level\Explosion;
use pocketmine\level\Level;
use pocketmine\level\particle\AngryVillagerParticle;
use pocketmine\level\particle\DustParticle;
use pocketmine\level\particle\FlameParticle;
use pocketmine\level\particle\SmokeParticle;
use pocketmine\level\particle\DripHoneyParticle;
use pocketmine\level\particle\DragonBreathFireParticle;
use pocketmine\level\Position;
use pocketmine\network\mcpe\protocol\TextPacket;
use pocketmine\Player;
use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\plugin\PluginBase;

use pocketmine\math\Vector3;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\entity\EntityShootBowEvent;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;

use pocketmine\event\entity\ProjectileHitEntityEvent;
use pocketmine\event\entity\ProjectileHitEvent;

use pocketmine\entity\utils\FireworksUtils;

use pocketmine\entity\projectile\Snowball;
use pocketmine\entity\projectile\Egg;
use pocketmine\entity\projectile\EnderPearl;
use pocketmine\entity\EffectInstance;
use pocketmine\entity\Effect;

use pocketmine\item\Item;


use pocketmine\level\particle\Particle;
use pocketmine\level\particle\HeartParticle;

use pocketmine\utils\Config;
use pocketmine\utils\UUID;

use pocketmine\entity\Entity;

use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\AddActorPacket;

use pocketmine\entity\projectile\Arrow;

use pocketmine\event\Listener;

use SSC\Form\FishForm;
use SSC\Gun\Bombing\BombingEvent;
use SSC\Gun\Bombing\BombingTask;
use SSC\Level\Particle\HeartCircleParticle;
use SSC\Level\Particle\ShootBowCircleParticle;
use SSC\Level\Particle\SlashEffectParticle;
use SSC\main;
use SSC\Task\EventGenerater;
use xenialdan\apibossbar\BossBar;

class KillDeathEvent implements Listener {

	private $Main;

	/*
	 * キルしたときのアイテム
	 * */
	private $ki;

	/*
	 * キルの理由表示
	 */
	private $kc;

	/*
	 * キルしたプレイヤーのプレイヤーネーム
	 */
	private $kpn;

	/*
 * キルしたプレイヤーのディスプレイネーム
 */
	private $kp;
	/*
 	* キルしたプレイヤーのディスプレイネーム
 	*/
	private $otikp;

	private $hanabi;

	private $heart;

	private $namakemono;

	private $bbo;

	public function __construct(Main $main) {
		$this->Main = $main;
	}

	public function onPlayerkill(EntityDamageByEntityEvent $event) {
		if ($event->getEntity() instanceof Player && $event->getDamager() instanceof Player) {
			$this->kp = $event->getDamager()->getDisplayName();
			$this->otikp[$event->getEntity()->getName()] = $event->getDamager()->getName();
			$this->kpn = $event->getDamager()->getName();
			if ($event->getDamager() instanceof Player) {
				$this->ki = $event->getDamager()->getInventory()->getItemInHand()->getName();
			} else {
				$this->ki = null;
			}
		}
	}

	public function onKC(EntityDamageEvent $event) {
		$this->kc = $event->getCause();
		$entity = $event->getEntity();

		if ($event->getCause() === 4) {
			if ($entity->getLevel()->getFolderName() != "pvp") {
				$event->setCancelled();
			}
		}

		if ($event->getCause() === 6) {
			if ($entity->getLevel()->getFolderName() != "pvp" and $entity->getLevel()->getFolderName() != "moon") {
				$event->setCancelled();
			}
		}

		if ($event->getCause() === 9) {
			if ($entity->getLevel()->getFolderName() != "pvp" and $entity->getLevel()->getFolderName() != "moon" ) {
				$event->setCancelled();
			}
		}

		if ($event->getCause() === 10) {
			if ($entity->getLevel()->getFolderName() != "pvp" and $entity->getLevel()->getFolderName() != "moon") {
				$event->setCancelled();
			}
		}

		if ($event instanceof EntityDamageByEntityEvent) {
			$damager = $event->getDamager();
			/*if ($event->getEntity() instanceof Human) {
				if ($event->getCause() === 1) {
					$name = $damager->getInventory()->getItemInHand()->getName();
					if ($name == "§bKill棒") {
						$entity->kill();
						return true;
					}
				}
			}*/

			if($event->getEntity() instanceof Tamable){
				if($damager instanceof Player){
					if(!$damager->isOp()){
						if($entity->namedtag->offsetExists("pet")){
							if($entity->namedtag->getString("pet")!=$damager->getName()){
								if($entity->getLevel()->getFolderName()!=="pvp" and $entity->getLevel()->getFolderName()!=="moon") {
									$event->setCancelled();
									$damager->sendMessage("[{$entity->namedtag->getString("pet")}のぺっと]やめてくだひゃい>< いたいめぅ");
									return true;
								}
							}
						}
					}
				}
			}

			if ($event->getEntity() instanceof Human) {
				if ($event->getDamager() instanceof Player) {
					if ($event->getEntity()->namedtag->offsetExists("form")) {
						foreach ($event->getEntity()->namedtag->getCompoundTag("form") as $tag) {
							if ($tag->getValue() == "FishForm") {
								$damager->sendForm(new FishForm(main::getPlayerData($damager->getName())));
							}
							$event->setCancelled();
						}
					}
				}
			}

			if ($event->getEntity() instanceof Player && $event->getDamager() instanceof Player) {
				$map = $event->getEntity()->getLevel()->getFolderName();
				if ($map != "pvp" and $map != "moon") {
					$event->setCancelled();
					$this->otikp[$event->getEntity()->getName()] = null;
					return true;
				}

				if($map==="moon") {
					$entdata = main::getPlayerData($event->getEntity()->getName());
					$dmgdata = main::getPlayerData($event->getDamager()->getName());
					if ($entdata->getClan() === $dmgdata->getClan()) {
						if($entdata->getClan()!="") {
							$event->setCancelled();
							return true;
						}
					}
				}

				/** @var  $hand Item*/
				$hand = $event->getDamager()->getInventory()->getItemInHand();
				$tag = $hand->getNamedTag();
				$name = $hand->getCustomName();
				if ($name === "§eエクスカリバー") {
					$pk = new AddActorPacket();
					$pk->type = 93;
					$pk->entityRuntimeId = Entity::$entityCount++;
					$x = $event->getEntity()->x;
					$y = $event->getEntity()->y;
					$z = $event->getEntity()->z;
					$pk->position = new Vector3($x, $y, $z);
					$this->Main->getServer()->broadcastPacket($event->getEntity()->getLevel()->getPlayers(), $pk);
					$pk2 = new PlaySoundPacket;
					$pk2->soundName = "random.explode";
					$pk2->x = $event->getEntity()->x;
					$pk2->y = $event->getEntity()->y;
					$pk2->z = $event->getEntity()->z;
					$pk2->volume = 0.5;
					$pk2->pitch = 1;
					$this->Main->getServer()->broadcastPacket($event->getEntity()->getLevel()->getPlayers(), $pk2);
					$event->getEntity()->attack(new EntityDamageEvent($event->getEntity(), EntityDamageEvent::CAUSE_ENTITY_ATTACK, 2));
				}
				if ($tag->offsetExists("Saisana")) {
					switch (mt_rand(1, 9)) {
						case 1:
							main::getMain()->getScheduler()->scheduleRepeatingTask(new EventGenerater($this->saisana($event->getEntity()->x, $event->getEntity()->y, $event->getEntity()->z, $entity, $entity->getLevel())), 1.5);
							break;
						case 2:
							main::getMain()->getScheduler()->scheduleRepeatingTask(new EventGenerater($this->saisana2($event->getEntity()->x, $event->getEntity()->y, $event->getEntity()->z, $entity, $entity->getLevel())), 1.5);
							break;
						case 3:
						case 4:
							$damager->addEffect(new EffectInstance(Effect::getEffect(2), 60, 1, true));
							$damager->addEffect(new EffectInstance(Effect::getEffect(15), 60, 10, true));
							break;

					}

				}
				if($tag->offsetExists("DevilSword")){
					SlashEffectParticle::add($event->getEntity()->x,$event->getEntity()->y,$event->getEntity()->z,$event->getEntity()->getLevel(),$damager->getDirection());
				}

			}
		}
		return true;
	}

	public function onPlayerDeath(PlayerDeathEvent $event) {
		$playername = $event->getEntity()->getDisplayName();
		$player = $event->getPlayer();
		$name = $player->getName();
		$event->setKeepInventory(true);
		if($event->getPlayer()->getLevel()->getName()==="moon"){
			$event->setKeepInventory(false);
		}

		if ($player->getGamemode() == 0) {
			$player->setAllowFlight(false);
		}
		if ($event->getEntity() instanceof Player and $event->getPlayer() instanceof Player) {
			switch ($this->kc) {
				case 1:

					$item = $this->ki;
					$killer = $this->kp;
					$kil = $this->kpn;
					$this->otikp[$name] = null;
					$event->setDeathMessage("");
					if ($kil == $name) {
						$event->setDeathMessage("§a§l[戦闘型AI] §c{$killer}§a が 自殺しました。");
						$ki = $this->Main->getServer()->getPlayer($kil);
						$this->Main->rate($ki, $player);
						return false;
					} else {
						$killer = $this->kp;
						if (Server::getInstance()->getPlayer($kil)) {
							$playerdata = $this->Main->getPlayerData($kil);
							$ki = $this->Main->getServer()->getPlayer($kil);
							$this->Main->rate($ki, $player);
							if ($playerdata->getKillst() % 10==0) {
								if(Server::getInstance()->getPlayer($kil)->getInventory()->getItemInHand()->getNamedTag()->offsetExists("gun")) {
									$cls = new BombingEvent();
									main::getMain()->getScheduler()->scheduleDelayedTask(new BombingTask(Server::getInstance()->getPlayer($kil), $cls), 20);
									Server::getInstance()->broadcastTip($kil . "が砲撃支援を要請した！");
								}
							}
						}
						foreach (Server::getInstance()->getOnlinePlayers() as $player) {
							if ($player->getLevel()->getFolderName() === "pvp" or $player->getLevel()->getFolderName() === "moon" or $player->getName() == $name) {
								switch (mt_rand(1, 4)) {
									case 1:
										$player->sendMessage("§a§l[戦闘型AI] §c{$killer}§a が §b{$playername}§a を -§d{$item}§a- で殺害しました §e({$playerdata->getKillst()}キルストリーク)");
										break;
									case 2:
										$player->sendMessage("§a§l[戦闘型AI] §c{$killer}§a が §b{$playername}§a を -§d{$item}§a- で刺し殺しました §e({$playerdata->getKillst()}キルストリーク)");
										break;
									case 3:
										$player->sendMessage("§a§l[戦闘型AI] §c{$killer}§a が §b{$playername}§a を -§d{$item}§a- で殴り倒しました §e({$playerdata->getKillst()}キルストリーク)");
										break;
									case 4:
										$player->sendMessage("§a§l[戦闘型AI] §c{$killer}§a が §b{$playername}§a を -§d{$item}§a- でばらばらにしました §e({$playerdata->getKillst()}キルストリーク)");
										break;
								}

							}
						}

					}
					return false;
				case 2:
					$item = $this->ki;
					$killer = $this->kp;
					$kil = $this->kpn;
					$this->otikp[$name] = null;
					if ($kil == $name) {
						$event->setDeathMessage("§a§l[戦闘型AI] §c{$killer}§a が 自殺しました。");
						if (Server::getInstance()->getPlayer($kil)) {

							$ki = $this->Main->getServer()->getPlayer($kil);
							$this->Main->rate($ki, $player);

						}
						return false;
					} else {
						if (Server::getInstance()->getPlayer($kil)) {
							$playerdata = $this->Main->getPlayerData($kil);
							$ki = $this->Main->getServer()->getPlayer($kil);
							$this->Main->rate($ki, $player);
							if ($playerdata->getKillst() % 10 == 0) {
								if (Server::getInstance()->getPlayer($kil)->getInventory()->getItemInHand()->getNamedTag()->offsetExists("gun")) {
									$cls = new BombingEvent();
									main::getMain()->getScheduler()->scheduleDelayedTask(new BombingTask(Server::getInstance()->getPlayer($kil), $cls), 20);
									Server::getInstance()->broadcastTip($kil . "が砲撃支援を要請した！");
								}
							}
						}
						foreach (Server::getInstance()->getOnlinePlayers() as $player) {
							if ($player->getLevel()->getFolderName() === "pvp" or $player->getLevel()->getFolderName() === "moon" or $player->getName() == $name) {
								switch (mt_rand(1, 4)) {
									case 1:
										$player->sendMessage("§a§l[戦闘型AI] §c{$killer}§a が §b{$playername}§a を -§d{$item}§a- で射抜きました §e({$playerdata->getKillst()}キルストリーク)");
										break;
									case 2:
										$player->sendMessage("§a§l[戦闘型AI]§c {$killer}§a が §b{$playername}§a に -§d{$item}§a- で矢を心臓に刺しました §e({$playerdata->getKillst()}キルストリーク)");
										break;
									case 3:
										$player->sendMessage("§a§l[戦闘型AI] §c{$killer}§a が §b{$playername}§a を -§d{$item}§a- によって生命活動を停止させました §e({$playerdata->getKillst()}キルストリーク)");
										break;
									case 4:
										$player->sendMessage("§a§l[戦闘型AI] §c{$killer}§a が §b{$playername}§a を -§d{$item}§a- の矢が脳を貫きました §e({$playerdata->getKillst()}キルストリーク)");
										break;
								}
							}
						}
						return false;
					}

					break;
				case 3:
					switch (mt_rand(1, 4)) {
						case 1:
							$event->setDeathMessage("§a§l[死亡管理AI] §b{$playername} §aは窒息しました");
							break;
						case 2:
							$event->setDeathMessage("§a§l[死亡管理AI] §b{$playername}§a は押しつぶされました");
							break;
						case 3:
							$event->setDeathMessage("§a§l[死亡管理AI] §b{$playername}§a は押しつぶされてぐちゃぐちゃになりました");
							break;
						case 4:
							$event->setDeathMessage("§a§l[死亡管理AI] §b{$playername}§a は押しつぶされて息の根が止まりました");
							break;
					}
					break;
				case 4:
					switch (mt_rand(1, 4)) {
						case 1:
							$event->setDeathMessage("§a§l[死亡管理AI] §b{$playername}§a は落下してぐちゃぐちゃになった");
							break;
						case 2:
							$event->setDeathMessage("§a§l[死亡管理AI] §b{$playername}§a は空中浮遊を試みたが落下しました");
							break;
						case 3:
							$event->setDeathMessage("§a§l[死亡管理AI] §b{$playername}§a は落下で心中を試みました");
							break;
						case 4:
							$event->setDeathMessage("§a§l[死亡管理AI]§b {$playername} §aは床が外れて落下しました");
							break;
					}
					break;
				case 5:
					switch (mt_rand(1, 4)) {
						case 1:
							$event->setDeathMessage("§a§l[死亡管理AI]§b {$playername} §aは灰になった");
							break;
						case 2:
							$event->setDeathMessage("§a§l[死亡管理AI]§b {$playername} §aは炎を歩こうとして大やけどしました");
							break;
						case 3:
							$event->setDeathMessage("§a§l[死亡管理AI]§b {$playername} §aは炎に飛び込みました");
							break;
						case 4:
							$event->setDeathMessage("§a§l[死亡管理AI]§b {$playername} §aは炎によってちりになりました");
							break;
					}
					break;
				case 6:
					switch (mt_rand(1, 2)) {
						case 1:
							$event->setDeathMessage("§a§l[死亡管理AI]§b {$playername}§a はケツに火がついた");
							break;
						case 2:
							$event->setDeathMessage("§a§l[死亡管理AI]§b {$playername}§a は火だるまになった");
							break;
					}
					break;
				case 7:
					switch (mt_rand(1, 4)) {
						case 1:
							$event->setDeathMessage("§a§l[死亡管理AI]§b {$playername}§a は溶岩の中を泳ごうとした");
							break;
						case 2:
							$event->setDeathMessage("§a§l[死亡管理AI]§b {$playername}§a は溶けた");
							break;
						case 3:
							$event->setDeathMessage("§a§l[死亡管理AI]§b {$playername}§a はどろどろになりました");
							break;
						case 4:
							$event->setDeathMessage("§a§l[死亡管理AI]§b {$playername}§a は存在がなくなりました");
							break;
					}
					break;
				case 8:
					switch (mt_rand(1, 4)) {
						case 1:
							$event->setDeathMessage("§a§l[死亡管理AI]§b {$playername} §aは魚たちと眠りについた");
							break;
						case 2:
							$event->setDeathMessage("§a§l[死亡管理AI]§b {$playername}§a は内臓が水で満たされた");
							break;
						case 3:
							$event->setDeathMessage("§a§l[死亡管理AI]§b {$playername}§a は溺死しました");
							break;
						case 4:
							$event->setDeathMessage("§a§l[死亡管理AI]§b {$playername}§a は息ができなくなりました");
							break;
					}
					break;
				case 9:
				case 10:
					switch (mt_rand(1, 4)) {
						case 1:
							$event->setDeathMessage("§a§l[死亡管理AI]§b {$playername}§a は爆発した");
							break;
						case 2:
							$event->setDeathMessage("§a§l[死亡管理AI]§b {$playername}§a は爆発の反動でばらばらになった");
							break;
						case 3:
							$event->setDeathMessage("§a§l[死亡管理AI]§b {$playername}§a は爆発により生命活動が停止した");
							break;
						case 4:
							$event->setDeathMessage("§a§l[死亡管理AI]§b {$playername}§a は爆発により骨になった");
							break;
					}
					break;
				case 11:
					if (isset($this->otikp[$name])) {
						$kil = $this->otikp[$name];
						if ($this->Main->getServer()->getPlayer($kil)) {
							$killer = $this->Main->getServer()->getPlayer($kil);
							$this->Main->rate($killer, $player);
						}
						$this->otikp[$name] = null;
						switch (mt_rand(1, 4)) {
							case 1:
								$event->setDeathMessage("§a[死亡管理AI] §b{$playername}§a は §b{$kil}§a によって消滅した");
								break;
							case 2:
								$event->setDeathMessage("§a[死亡管理AI] §b{$playername} §aは §b{$kil}§a によって存在がなくなった");
								break;
							case 3:
								$event->setDeathMessage("§a[死亡管理AI] §b{$playername} §aは §b{$kil}§a によってちりになった");
								break;
							case 4:
								$event->setDeathMessage("§a[死亡管理AI] §b{$playername}§a は §b{$kil}§a によって星になった");
								break;
						}
					} else {
						switch (mt_rand(1, 4)) {
							case 1:
								$event->setDeathMessage("§a§l[死亡管理AI] §b{$playername}§a は消滅した");
								break;
							case 2:
								$event->setDeathMessage("§a§l[死亡管理AI] §b{$playername} §aは存在がなくなった");
								break;
							case 3:
								$event->setDeathMessage("§a§l[死亡管理AI] §b{$playername} §aはちりになった");
								break;
							case 4:
								$event->setDeathMessage("§a§l[死亡管理AI] §b{$playername}§a は星になった");
								break;
						}
					}
					break;
				case 12:
					switch (mt_rand(1, 4)) {
						case 1:
							$event->setDeathMessage("§a§l[死亡管理AI] §b{$playername}§a は消滅した");
							break;
						case 2:
							$event->setDeathMessage("§a§l[死亡管理AI] §b{$playername} §aは存在がなくなった");
							break;
						case 3:
							$event->setDeathMessage("§a§l[死亡管理AI] §b{$playername} §aはちりになった");
							break;
						case 4:
							$event->setDeathMessage("§a§l[死亡管理AI] §b{$playername}§a は星になった");
							break;
					}
					break;
				case 13:
					switch (mt_rand(1, 4)) {
						case 1:
							$event->setDeathMessage("§a§l[死亡管理AI] §b{$playername}§a はなぞのダメージにより死んだ");
							break;
						case 2:
							$event->setDeathMessage("§a§l[死亡管理AI] §b{$playername}§a はなにかの力によって死亡した");
							break;
						case 3:
							$event->setDeathMessage("§a§l[死亡管理AI] §b{$playername}§a はなにかの圧力によって死亡した");
							break;
						case 4:
							$event->setDeathMessage("§a§l[死亡管理AI] §b{$playername}§a は神の力によって消滅した");
							break;
					}
					break;
				case 14:
				case 15:
					return true;
					break;


			}

			return false;
		}
	}

	public function onShoot(EntityShootBowEvent $event) {
		if($event->getEntity() instanceof Player) {
			$pworld = $event->getEntity()->getLevel()->getFolderName();
			$world = $this->Main->getServer()->getLevelByName("pvp")->getFolderName();
			$world2 = $this->Main->getServer()->getLevelByName("moon")->getFolderName();
			if ($pworld !== $world and $pworld !== $world2) {
				$event->setCancelled();
			} else {
				$tag = $event->getBow()->getNamedTag();
				if ($tag->offsetExists("Yurisi_Love")) {
					if ($event->getEntity() instanceof Player) {
						if ($event->getForce() == 3) {
							main::getMain()->getScheduler()->scheduleRepeatingTask(new EventGenerater($this->yurisiBeam($event->getEntity())), 1);
						} else {
							$pk = new TextPacket();
							$pk->type = 4;
							$pk->message = "愛が足りない！マックスまで引いて！";
							$event->getEntity()->dataPacket($pk);
						}
						$event->setCancelled();
					}
				}
				if ($tag->offsetExists("Namakemono")) {
					$this->namakemono[$event->getEntity()->getName()] = 1;
				}
				if ($tag->offsetExists("bbo")) {
					$event->getEntity()->addEffect(new EffectInstance(Effect::getEffect(15), 40, 1, true));
				}
				if ($event->getEntity()->getInventory()->getItemInHand()->getCustomName() == "§bジムのスナイパーライフルv2") {
					$this->hanabi[$event->getEntity()->getName()] = 1;
					$nbt = Entity::createBaseNBT(
						$event->getEntity()->add(0, $event->getEntity()->getEyeHeight(), 0),
						$event->getEntity()->getDirectionVector(),
						($event->getEntity()->yaw > 180 ? 360 : 0) - $event->getEntity()->yaw,
						-$event->getEntity()->pitch
					);
					$diff = $event->getEntity()->getItemUseDuration();
					$p = $diff / 20;
					$baseForce = min((($p ** 2) + $p * 2) / 3, 1);
					$entity = Entity::createEntity("Egg", $event->getEntity()->getLevel(), $nbt, $event->getEntity(), $baseForce >= 1);
					$entity->setMotion($entity->getMotion()->multiply(0.6));
					$event->setProjectile($entity);

					/*}elseif($event->getEntity()->getInventory()->getItemInHand()->getCustomName() == "§aTANBOの第三の目"){
						$nbt = Entity::createBaseNBT(
							$event->getEntity()->add(0, $event->getEntity()->getEyeHeight(), 0),
							$event->getEntity()->getDirectionVector(),
							($event->getEntity()->yaw > 180 ? 360 : 0) - $event->getEntity()->yaw,
							-$event->getEntity()->pitch
						);
						$diff = $event->getEntity()->getItemUseDuration();
						$p = $diff / 20;
						$baseForce = min((($p ** 2) + $p * 2) / 3, 1);
						$entity = Entity::createEntity("EnderPearl", $event->getEntity()->getLevel(), $nbt, $event->getEntity(), $baseForce >= 1);
						$entity->setMotion($entity->getMotion()->multiply(0.25));
						$event->setProjectile($entity);*/
				} elseif ($event->getEntity()->getInventory()->getItemInHand()->getCustomName() == "§d♡キューピットの弓♡") {
					$this->heart[$event->getEntity()->getName()] = 1;
				}
				if ($event->getEntity() instanceof Player) {
					if ($event->getForce() == 3) {
						if ($tag->offsetExists("DevilBow")) {
							ShootBowCircleParticle::add($event->getEntity()->x, $event->getEntity()->y, $event->getEntity()->z, $event->getEntity()->getLevel(), $event->getEntity()->getDirection());
						}
					}
				}
			}
		}
	}

	public function onHit(ProjectileHitEntityEvent $event) {
		$damager = $event->getEntityHit();
		$attacker = $event->getEntity()->getOwningEntity();
		if ($attacker instanceof Player && $damager instanceof Player) {
			$entity = $event->getEntity();
			if ($entity instanceof Egg) {

				if (!empty($this->hanabi[$attacker->getName()])) {
					if ($this->hanabi[$attacker->getName()] === 1) {
						if ($attacker->getInventory()->getItemInHand()->getCustomName() == "§bジムのスナイパーライフルv2") {
							$pk2 = new PlaySoundPacket;
							$pk2->soundName = "random.explode";
							$pk2->x = $event->getEntity()->x;
							$pk2->y = $event->getEntity()->y;
							$pk2->z = $event->getEntity()->z;
							$pk2->volume = 0.5;
							$pk2->pitch = 1;
							$this->Main->getServer()->broadcastPacket()($attacker->getLevel()->getPlayers(), $pk2);
							$event->getEntityHit()->attack(new EntityDamageEvent($damager, EntityDamageEvent::CAUSE_ENTITY_ATTACK, 5));
							$this->hanabi[$attacker->getName()] = 0;
						} else {
							$this->hanabi[$attacker->getName()] = 0;
						}
					}
				}
			}


			if ($entity instanceof Arrow) {

				$hand = $event->getEntity()->getOwningEntity()->getInventory()->getItemInHand();
				$tag = $hand->getNamedTag();
				$this->hitSound($attacker);
				if (!empty($this->heart[$attacker->getName()])) {
					if ($this->heart[$attacker->getName()] === 1) {
						if ($attacker->getInventory()->getItemInHand()->getCustomName() == "§d♡キューピットの弓♡") {
							for ($i = 0; $i <= 4; $i++) {
								$damager->getLevel()->addParticle(new HeartParticle(new Vector3($damager->x + mt_rand(-1, 1), $damager->y + mt_rand(0, 1), $damager->z + mt_rand(-1, 1)), 10));
							}
							if (!$damager->hasEffect(10)) {
								$damager->addEffect(new EffectInstance(Effect::getEffect(10), 100, 1, true));
							}
							$this->heart[$attacker->getName()] = 0;
						} else {
							$this->heart[$attacker->getName()] = 0;
						}
					}
				}
				if (!empty($this->namakemono[$attacker->getName()])) {
					if ($this->namakemono[$attacker->getName()] === 1) {
						if ($tag->offsetExists("Namakemono")) {
							$damager->addEffect(new EffectInstance(Effect::getEffect(2), 30, 0, true));
							$this->namakemono[$attacker->getName()] = 0;
						}
					}
				}
			}
		}
	}

	public function getblockfromprojectile(ProjectileHitEvent $event) {
		if ($event->getEntity()->getOwningEntity() instanceof Player) {
			if (!empty($this->hanabi[$event->getEntity()->getOwningEntity()->getName()])) {
				if ($this->hanabi[$event->getEntity()->getOwningEntity()->getName()] === 1) {
					$this->hanabi[$event->getEntity()->getOwningEntity()->getName()] = 0;
				}
			}
			if (!empty($this->heart[$event->getEntity()->getOwningEntity()->getName()])) {
				if ($this->heart[$event->getEntity()->getOwningEntity()->getName()] === 1) {
					$this->heart[$event->getEntity()->getOwningEntity()->getName()] = 0;
				}
			}
		}
	}

	public function saisana($x, $y, $z, $damager, Level $level): \Generator {
		$y2=$y;
		$y3=$y+11;
		for ($i = 0; $i < 360; $i += 15) {
			yield;
			$pos = new Vector3($x + sin(deg2rad($i)), $y , $z+cos(deg2rad($i)));
			$level->addParticle(new DragonBreathFireParticle($pos));
			$y += 0.2;
		}
		for ($i = 0; $i < 11; $i += 1) {
			yield;
			$pos = new Vector3($x , $y3-$i , $z);
			$level->addParticle(new SmokeParticle($pos,1000));
		}
		$explosion = new Explosion(new Position($x,$y2,$z,$level), 1);
		$explosion->explodeB();

	}

	public function saisana2($x, $y, $z, $damager, Level $level): \Generator {
		$y2 = $y;
		$y3 = $y + 11;
		for ($a = 0; $a < 3; $a += 1) {
			yield;
			for ($i = 0; $i < 11; $i += 1) {
				yield;
				$pos = new Vector3($x, $y3 - $i, $z);
				$level->addParticle(new SmokeParticle($pos, 1000));
			}
			$explosion = new Explosion(new Position($x, $y2, $z, $level), 1);
			$explosion->explodeB();
		}

	}

	public function yurisiBeam(Player $entity) {
		$particle = new HeartParticle(new Vector3($entity->x, $entity->y + 1, $entity->z));
		$particle->setComponents($entity->x, $entity->y + 1, $entity->z);

		$increase = $entity->getDirectionVector()->normalize();
		for ($i = 0; $i < 30; $i++) {
			yield;
			$pos = $particle->add($increase);
			if (!$entity->level->getBlock($pos)->canBeFlowedInto()) break;
			$particle->setComponents($pos->x, $pos->y, $pos->z);
			$entity->level->addParticle($particle);
			foreach ($entity->level->getPlayers() as $player) {
				if ($player->distance($pos) < 1.5 && $entity !== $player) {
					$event = new EntityDamageByEntityEvent($entity, $player, EntityDamageEvent::CAUSE_PROJECTILE, 3, [], 0.5);
					$player->attack($event);
					$this->hitSound($entity);
					main::getMain()->getScheduler()->scheduleRepeatingTask(new EventGenerater(HeartCircleParticle::addMoveParticle($player->x, $player->y + 1, $player->z, $player->getLevel())), 1);
					break 2;
				}
			}
		}
	}


	public function hitSound(Player $player){
		$pk2 = new PlaySoundPacket;
		$pk2->soundName = "random.anvil_land";
		$pk2->x = $player->x;
		$pk2->y = $player->y;
		$pk2->z = $player->z;
		$pk2->volume = 0.5;
		$pk2->pitch = 1;
		$player->sendDataPacket($pk2);
	}
}


