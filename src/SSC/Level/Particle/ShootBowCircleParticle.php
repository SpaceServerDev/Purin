<?php


namespace SSC\Level\Particle;


use pocketmine\level\Level;
use pocketmine\level\particle\HeartParticle;
use pocketmine\math\Vector3;
use SSC\PlayerEvent;

class ShootBowCircleParticle implements SpaceServerParticle {

	public static function add($x, $y, $z, Level $level, int $direction = 0) {
		switch ($direction){
			case 0:
				$v1=new Vector3($x+1,$y+1,$z);
				for($i=0;$i<360;$i+=10) {
					$v3 = new Vector3($x + 3, $y + 2 + sin(deg2rad($i)) * 2, $z + cos(deg2rad($i)) * 2);
					$level->addParticle(new HeartParticle($v3));
					$v3 = new Vector3($x + 3, $y + 2 + sin(deg2rad($i)) * 3, $z + cos(deg2rad($i)) * 3);
					$level->addParticle(new HeartParticle($v3));
				}
			break;
			case 1:
				$v1=new Vector3($x,$y,$z+1);
				for($i=0;$i<360;$i+=10) {
					$v3 = new Vector3($x + sin(deg2rad($i)) * 2, $y + 2 + cos(deg2rad($i)) * 2, $z + 3);
					$level->addParticle(new HeartParticle($v3));
					$v3 = new Vector3($x + sin(deg2rad($i)) * 3, $y + 2 + cos(deg2rad($i)) * 3, $z + 3);
					$level->addParticle(new HeartParticle($v3));

				}
			break;
			case 2:
				$v1=new Vector3($x-1,$y+1,$z);
				for($i=0;$i<360;$i+=10) {
					$v3 = new Vector3($x - 3, $y + 1 + sin(deg2rad($i)) * 2, $z + cos(deg2rad($i)) * 2);
					$level->addParticle(new HeartParticle($v3));
					$v3 = new Vector3($x - 3, $y + 1 + sin(deg2rad($i)) * 3, $z + cos(deg2rad($i)) * 3);
					$level->addParticle(new HeartParticle($v3));
				}
			break;
			default:
				$v1=new Vector3($x,$y,$z+1);
				for($i=0;$i<360;$i+=10) {
					$v3 = new Vector3($x + sin(deg2rad($i)) * 2, $y + 1 + cos(deg2rad($i)) * 2, $z - 3);
					$level->addParticle(new HeartParticle($v3));
					$v3 = new Vector3($x + sin(deg2rad($i)) * 3, $y + 1 + cos(deg2rad($i)) * 3, $z - 3);
					$level->addParticle(new HeartParticle($v3));

				}
			break;
		}
		$level->addParticle(new HeartParticle($v1));

	}
}