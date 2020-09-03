<?php


namespace SSC\Data;


use pocketmine\utils\Config;
use SSC\main;

class ShopData extends Config {

	public function __construct() {
		parent::__construct(main::getMain()->getDataFolder()."ItemData.json");
	}

	public function getValue(int $id,int $meta=0):int{
		if ($this->exists($id)) {
			if ($this->get($id)["damage"]) {
				if(isset($this->getAll()["damage"][$meta])) {
					return $this->get($id)[$meta]["value"];
				}
				return 0;
			}
			return $this->get($id)["value"];
		}
		return 0;
	}

	public function getName(int $id,int $meta=0){

	}

}