<?php


namespace SSC\Gun\Guns;


use SSC\Gun\Gun;

class AWM implements Gun {

	const MAX_AMMO=5;

	private $CoolDown=false;

	private $shootnow=false;

	private $ammo = self::MAX_AMMO;

	private $taskid;

	public function getName(): string {
		return "AWM";
	}

	public function getAmmo(): int {
		return $this->ammo;
	}

	public function removeAmmo() {
		$this->ammo--;
	}

	public function reload() {
		$this->ammo=self::MAX_AMMO;
	}

	public function getMaxAmmo(): int {
		return self::MAX_AMMO;
	}

	public function startCoolDown() {
		$this->CoolDown=true;
	}

	public function endCoolDown() {
		$this->CoolDown=false;
	}

	public function getCoolDown():bool{
		return $this->CoolDown;
	}

	public function getReloadTick(): int {
		return 80;
	}

	public function isShootNow():bool{
		return $this->shootnow;
	}

	public function startShoot(int $taskid){
		$this->shootnow=true;
		$this->taskid=$taskid;
	}

	public function endShoot(){
		$this->shootnow=false;
		$this->taskid=null;
	}

	public function getTaskId(){
		if(!$this->isShootNow()){
			return false;
		}
		return $this->taskid;
	}

	public function getCoolDownTick(): int {
		return 40;
	}

	public function getRecoil():float {
		return 1;
	}

	public function getDelayTick(): float {
		return 2;
	}

	public function getPeriodTick(): float {
		return 2;
	}

	public function getDamage() {
		return 10;
	}

	public function getKnockBack() {
		return 0.5;
	}

	public function getDistance() {
		return 50;
	}}