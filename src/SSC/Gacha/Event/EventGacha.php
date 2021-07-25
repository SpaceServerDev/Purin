<?php


namespace SSC\Gacha\Event;


use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use SSC\PlayerEvent;

class EventGacha{

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
			case $rnd==1://アルティメット
				$class=new DevilEventGacha();
			break;
			case $rnd>1&&$rnd<=3://シクレ
				switch (mt_rand(1,3)) {
					case 1:
						$class=new LegendEventGacha("SEQ");
					break;
					case 2:
						$class=new StarEventGacha("SEQ");
					break;
					case 3:
						$class=new ComradeGacha("SEQ");
					break;
				}
			break;
			case $rnd>3&&$rnd<=13://UR
				switch (mt_rand(1,6)){
					case 1:
						$class=new OPEventGacha("UR");
					break;
					case 2:
						$class=new JapanEventGacha("UR");
					break;
					case 3:
						$class=new LegendEventGacha("UR");
					break;
					case 4:
						$class=new StarEventGacha("UR");
					break;
					case 5:
						$class=new ComradeGacha("UR");
					break;
					case 6:
						$class=new GunGacha();
					break;
				}
			break;
			case $rnd>13&&$rnd<=40://R
				$class=new RareEventGacha();
			break;
			default:
				$class=new NormalRareEventGacha();
			break;
		}
		list($item,$rare,$itemname)=$class->turn();
		$this->pe->getPlayer()->getInventory()->addItem($item);
		if($rare===5){
			$this->pe->addGachaCount();
			$this->pe->getPlayer()->sendTitle("§4！！！アルティメット！！！");
			$pk = new PlaySoundPacket;
			$pk->soundName = "entity.cat.ambient";
			$pk->x = $this->pe->getPlayer()->x;
			$pk->y = $this->pe->getPlayer()->y;
			$pk->z = $this->pe->getPlayer()->z;
			$pk->volume = 0.3;
			$pk->pitch = 1;
			$this->pe->getPlayer()->sendDataPacket($pk);
			$this->pe->getPlayer()->getServer()->broadcastPopup(self::GACHA."§a".$this->pe->getName()."§fが§4アルティメットレア §e- ".$itemname." §e- §fを当選しました。(".$this->pe->getGachaCount()."回目)");
			$this->pe->getPlayer()->sendMessage(self::GACHA."§4アルティメットレア §e- ".$itemname." §e- §fを当選しました。(".$this->pe->getGachaCount()."回目)");
			$this->pe->resetGachaCount();
		}elseif($rare===4) {
			$this->pe->addGachaCount();
			$this->pe->getPlayer()->getServer()->broadcastPopup(self::GACHA."§a".$this->pe->getName()."§fが§dシークレットレア §e- ".$itemname." §e- §fを当選しました。(".$this->pe->getGachaCount()."回目)");
			$this->pe->getPlayer()->sendMessage(self::GACHA."§dシークレットレア §e- ".$itemname." §e- §fを当選しました。(".$this->pe->getGachaCount()."回目)");
			$this->pe->resetGachaCount();
		}elseif($rare===3){
			$this->pe->addGachaCount();
			if($itemname=="§aReef§ePickaxe"){
				$this->pe->getPlayer()->sendMessage("§b{$this->pe->getName()}さんが§aReef§ePickaxe§bを引きました({$this->pe->getGachaCount()})\n§e超大当たり");
			}else{
				$this->pe->getPlayer()->sendMessage(self::GACHA."§4ウルトラレア §e- ".$itemname." §e- §fを当選しました。(".$this->pe->getGachaCount()."回目)");
			}
			$this->pe->getPlayer()->getServer()->broadcastPopup(self::GACHA."§a".$this->pe->getName()."§fが§4ウルトラレア §e- ".$itemname." §e- §fを当選しました。(".$this->pe->getGachaCount()."回目)");
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

