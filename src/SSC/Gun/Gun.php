<?php


namespace SSC\Gun;


interface Gun {


	public function getName():string;

	public function getAmmo():int;

	public function removeAmmo();

	public function reload();

	public function getMaxAmmo():int;

	public function startCoolDown();

	public function endCoolDown();

	public function getCoolDown();

	public function getReloadTick():int;

	public function isShootNow():bool;

	public function startShoot(int $taskid);

	public function endShoot();

	public function getTaskId();

	public function getDelayTick():float;

	public function getPeriodTick():float;

	public function getCoolDownTick():int;

	public function getRecoil():float;

	public function getDamage();

	public function getKnockBack();

	public function getDistance();


}