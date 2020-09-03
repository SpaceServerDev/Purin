<?php

namespace SSC\Level\Particle;

use pocketmine\level\Level;
use pocketmine\level\particle\HeartParticle;
use pocketmine\math\Vector3;

class HeartCircleParticle implements SpaceServerParticle {

	public static function add($x,$y,$z,Level $level){
		for ($i = 0; $i < 360; $i+=10) {
			$pos = new Vector3($x + sin(deg2rad($i))**3 * 2 * 1, $y+2+  (1 * cos(deg2rad($i)) - cos(deg2rad($i))**4)*2, $z );
			$level->addParticle(new HeartParticle($pos));
			$pos = new Vector3($x , $y+2+  (1 * cos(deg2rad($i)) - cos(deg2rad($i))**4)*2, $z + sin(deg2rad($i))**3 * 2 * 1);
			$level->addParticle(new HeartParticle($pos));
		}
	}

	public static function addMoveParticle($x,$y,$z,Level $level){
		for ($i = 0; $i < 360; $i+=10) {
			yield;
			$pos = new Vector3($x + sin(deg2rad($i))**3 * 2 * 1, $y+2+  (1 * cos(deg2rad($i)) - cos(deg2rad($i))**4)*2, $z );
			$level->addParticle(new HeartParticle($pos));
			$pos = new Vector3($x , $y+2+  (1 * cos(deg2rad($i)) - cos(deg2rad($i))**4)*2, $z + sin(deg2rad($i))**3 * 2 * 1);
			$level->addParticle(new HeartParticle($pos));
		}
	}

}