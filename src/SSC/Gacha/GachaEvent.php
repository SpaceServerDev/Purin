<?php

namespace SSC\Gacha;

use pocketmine\item\Item;

use SSC\Gacha\Event\EventGacha;
use SSC\Gacha\Normal\NormalGacha;
use SSC\PlayerEvent;

class GachaEvent {

	/**
	 * @var PlayerEvent
	 */
	private $pe;

	/**
	 * @var string
	 */
	private $type;

	public function __construct(PlayerEvent $pe, string $type = "event") {
		$this->pe = $pe;
		$this->type = $type;
	}

	public function turn() {
		$item = Item::get(1, 0, 64);
		if (!$this->pe->getPlayer()->getInventory()->canAddItem($item)) {
			$this->pe->getPlayer()->sendMessage("インベントリに空きがありません");
			return true;
		}

		$this->pe->addVar("GACHA");

		if ($this->pe->getVar("DAIRY3") === 12 or $this->pe->getVar("DAIRY3") === 13) {
			$this->pe->addVar("DAIRYTASK3");
			if ($this->pe->getMaxDairy3() === $this->pe->getNowDairy3()) {
				$this->pe->getPlayer()->addTitle("デイリーボーナスを達成", "", 30, 30, 20);
			}
		}

		switch ($this->type) {
			case "event":
				$class = new EventGacha($this->pe);
				break;
			default:
				$class = new NormalGacha($this->pe);
				break;
		}
		$class->turn();

		return true;
	}
}