<?php


namespace SSC\Gun\Bombing;


use pocketmine\level\Explosion;
use pocketmine\level\Level;
use pocketmine\level\particle\DustParticle;
use pocketmine\level\particle\SmokeParticle;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\scheduler\Task;
use SSC\main;
use SSC\Task\EventGenerater;

class BombingTask extends Task {

	/**
	 * @var Player
	 */
	private $player;
	/**
	 * @var int
	 */
	private $count;
	/**
	 * @var BombingEvent
	 */
	private $event;


	public function __construct(Player $player, BombingEvent $event, int $count=0) {
		$this->player=$player;
		$this->count=$count;
		$this->event=$event;
	}

	/**
	 * Actions to execute when run
	 *
	 * @return void
	 */
	public function onRun(int $currentTick) {
			for ($i = 0; $i < 2; $i++) {
				$randx = mt_rand(-20, 20);
				$randz = mt_rand(-20, 20);
				main::getMain()->getScheduler()->scheduleRepeatingTask(new EventGenerater($this->event->dropBom($this->player->getX() + $randx, $this->player->getY(), $this->player->getZ() + $randz, $this->player->getLevel())), 1.3);
			}

		$this->getHandler()->cancel();
		return;
	}

	public function onCancel() {
		if($this->event->isTask()) {
			main::getMain()->getScheduler()->scheduleDelayedTask(new BombingTask($this->player, $this->event), 10);
			$this->event->addTask();
		}

	}


}