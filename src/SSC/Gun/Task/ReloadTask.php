<?php


namespace SSC\Gun\Task;


use pocketmine\network\mcpe\protocol\TextPacket;
use pocketmine\Player;
use pocketmine\scheduler\Task;
use SSC\Gun\GunEvent;
use SSC\Gun\GunManager;
use SSC\main;

class ReloadTask extends Task {

	/**
	 * @var Player
	 */
	private $player;

	/**
	 * @var string
	 */
	private $gun;

	/**
	 * @var string
	 */
	private $serial;
	/**
	 * @var bool
	 */
	private $reload;
	/**
	 * @var GunEvent
	 */
	private $event;


	public function __construct(Player $player,GunEvent $event, string $gun, string $serial,bool $reload=true) {
		$this->player=$player;
		$this->event=$event;
		$this->gun=$gun;
		$this->serial=$serial;
		$this->reload=$reload;
	}

	/**
	 * Actions to execute when run
	 *
	 * @return void
	 */
	public function onRun(int $currentTick) {
		main::getMain()->getGunManager()->getGunData($this->gun,$this->serial)->endCoolDown();
		if($this->reload) {
			$pk = new TextPacket();
			$pk->type = 4;
			$pk->message = $this->gun . " Reload Complete";
			$this->player->dataPacket($pk);
			$this->event->sound("music.machinegun-magazine-set1",$this->player->getFloorX(),$this->player->getFloorY(),$this->player->getFloorZ(),$this->player->getLevel());
		}
		$this->getHandler()->cancel();
		return;
	}
}