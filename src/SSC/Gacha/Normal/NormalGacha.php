<?php

namespace SSC\Gacha\Normal;

use SSC\PlayerEvent;

class NormalGacha {

	const GACHA="[ガチャAI]";

	/**
	 * @var PlayerEvent
	 */
	private $pe;

	/**
	 * EventGacha constructor.
	 * @param PlayerEvent $playerEvent
	 */
	public function __construct(PlayerEvent $playerEvent) {
		$this->pe = $playerEvent;
	}

	public function turn(){
		$rnd=mt_rand(1,100);
		switch ($rnd){
			case $rnd>0&&$rnd<=8://UR
				$class=new URNormalGacha();
			break;
			case $rnd>8&&$rnd<=30://R
				$class=new RNormalGacha();
			break;
			default:
				$class=new NNormalGacha();
			break;

		}
		list($item,$rare,$itemname)=$class->turn();
		$this->pe->getPlayer()->getInventory()->addItem($item);
		if($rare===3){
			$this->pe->addGachaCount();
			$this->pe->getPlayer()->getServer()->broadcastPopup(self::GACHA."§a".$this->pe->getName()."§fが§4ウルトラレア §e- ".$itemname." §e- §fを当選しました。(".$this->pe->getGachaCount()."回目)");
			$this->pe->getPlayer()->sendMessage(self::GACHA."§4ウルトラレア §e- ".$itemname." §e- §fを当選しました。");
			$this->pe->resetGachaCount();
		}elseif($rare===2){
				$this->pe->getPlayer()->sendMessage(self::GACHA."§bレア §e- ".$itemname." §e- §fを当選しました。");
				$this->pe->addGachaCount();
		}else{
				$this->pe->getPlayer()->sendMessage(self::GACHA."§aノーマル §e- ".$itemname." §e- §fを当選しました。");
				$this->pe->addGachaCount();
		}
	}

}