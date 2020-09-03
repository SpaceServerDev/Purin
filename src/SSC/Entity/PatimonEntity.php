<?php


namespace SSC\Entity;


use pocketmine\entity\Human;
use pocketmine\entity\Skin;
use pocketmine\level\Level;

use pocketmine\nbt\tag\CompoundTag;
use SSC\main;


class PatimonEntity extends Human {



	public function __construct(Level $level, CompoundTag $nbt) {
		$skinData = unserialize(file_get_contents(main::getMain()->getFile() . 'resources/patimon.skin'));
		$this->skin=new Skin($skinData->getSkinId(),$skinData->getSkinData(),$skinData->getCapeData(),$skinData->getGeometryName(),$skinData->getGeometryData());
		parent::__construct($level, $nbt);
	}


}