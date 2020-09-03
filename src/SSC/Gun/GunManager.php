<?php


namespace SSC\Gun;


use pocketmine\Server;
use SSC\Gun\Guns\AK47;
use SSC\Gun\Guns\AWM;
use SSC\Gun\Guns\RPG7;
use SSC\Gun\Guns\UZI;

class GunManager {

	public $gundata=[];

	private static $GunManager;

	public function __construct() {
		self::$GunManager=$this;
	}

	public function getInstance():GunManager{
		return self::$GunManager;
	}

	public function registerGun(string $gun,string $serial){
		switch ($gun){
			case "AK47":
				$this->gundata[$gun][$serial]=new AK47();
			break;
			case "UZI":
				$this->gundata[$gun][$serial]=new UZI();
			break;
			case "AWM":
				$this->gundata[$gun][$serial]=new AWM();
			break;
			case "RPG7":
				$this->gundata[$gun][$serial]=new RPG7();
			break;
		}
	}

	public function getGunData(string $gun,string $serial):Gun{
		if(!$this->isGunData($gun,$serial)){
			$this->registerGun($gun,$serial);
		}

		return $this->gundata[$gun][$serial];
	}

	public function isGunData(string $gun,string $serial):bool {
		return !empty($this->gundata[$gun][$serial]);
	}

	public static function getSerial(){
		return time().Server::getInstance()->getTick();
	}



}