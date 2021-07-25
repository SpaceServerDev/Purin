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
				$v1=new Vector3($x+3,$y+3,$z);
				for($i=0;$i<360;$i+=10) {
					$v3 = new Vector3($x + 3, $y + 3 + sin(deg2rad($i)) * 2, $z + cos(deg2rad($i)) * 2);
					$level->addParticle(new HeartParticle($v3));
					$v3 = new Vector3($x + 3, $y + 3 + sin(deg2rad($i)) * 3, $z + cos(deg2rad($i)) * 3);
					$level->addParticle(new HeartParticle($v3));
				}
				for($i=1;$i<8;$i++){
					$v3 = new Vector3($x + 3, $y + 3 + sin(deg2rad(45)) * $i, $z + cos(deg2rad(45)) * $i);
					$level->addParticle(new HeartParticle($v3));
					$v3 = new Vector3($x + 3, $y + 3 + sin(deg2rad(135)) * $i, $z + cos(deg2rad(135)) * $i);
					$level->addParticle(new HeartParticle($v3));
					$v3 = new Vector3($x + 3, $y + 3 + sin(deg2rad(225)) * $i, $z + cos(deg2rad(225)) * $i);
					$level->addParticle(new HeartParticle($v3));
					$v3 = new Vector3($x + 3, $y + 3 + sin(deg2rad(315)) * $i, $z + cos(deg2rad(315)) * $i);
					$level->addParticle(new HeartParticle($v3));
				}
			break;
			case 1:
				$v1=new Vector3($x,$y+3,$z+3);
				for($i=0;$i<360;$i+=10) {
					$v3 = new Vector3($x + sin(deg2rad($i)) * 2, $y + 3 + cos(deg2rad($i)) * 2, $z + 3);
					$level->addParticle(new HeartParticle($v3));
					$v3 = new Vector3($x + sin(deg2rad($i)) * 3, $y + 3 + cos(deg2rad($i)) * 3, $z + 3);
					$level->addParticle(new HeartParticle($v3));
				}
				for($i=1;$i<8;$i++) {
						$v3 = new Vector3($x + cos(deg2rad(45)) * $i, $y + 3 + sin(deg2rad(45)) * $i, $z + 3);
						$level->addParticle(new HeartParticle($v3));
						$v3 = new Vector3($x + cos(deg2rad(135)) * $i, $y + 3 + sin(deg2rad(135)) * $i, $z + 3);
						$level->addParticle(new HeartParticle($v3));
						$v3 = new Vector3($x + cos(deg2rad(225)) * $i, $y + 3 + sin(deg2rad(225)) * $i, $z + 3);
						$level->addParticle(new HeartParticle($v3));
						$v3 = new Vector3($x + cos(deg2rad(315)) * $i, $y + 3 + sin(deg2rad(315)) * $i, $z + 3);
						$level->addParticle(new HeartParticle($v3));
					}
			break;
			case 2:
				$v1=new Vector3($x-3,$y+3,$z);
				for($i=0;$i<360;$i+=10) {
					$v3 = new Vector3($x - 3, $y + 3 + sin(deg2rad($i)) * 2, $z + cos(deg2rad($i)) * 2);
					$level->addParticle(new HeartParticle($v3));
					$v3 = new Vector3($x - 3, $y + 3 + sin(deg2rad($i)) * 3, $z + cos(deg2rad($i)) * 3);
					$level->addParticle(new HeartParticle($v3));
				}
				for($i=1;$i<8;$i++){
					$v3 = new Vector3($x - 3, $y + 3 + sin(deg2rad(45)) * $i, $z + cos(deg2rad(45)) * $i);
					$level->addParticle(new HeartParticle($v3));
					$v3 = new Vector3($x - 3, $y + 3 + sin(deg2rad(135)) * $i, $z + cos(deg2rad(135)) * $i);
					$level->addParticle(new HeartParticle($v3));
					$v3 = new Vector3($x - 3, $y + 3 + sin(deg2rad(225)) * $i, $z + cos(deg2rad(225)) * $i);
					$level->addParticle(new HeartParticle($v3));
					$v3 = new Vector3($x - 3, $y + 3 + sin(deg2rad(315)) * $i, $z + cos(deg2rad(315)) * $i);
					$level->addParticle(new HeartParticle($v3));
				}
			break;
			default:
				$v1=new Vector3($x,$y+4,$z-3);
				for($i=0;$i<360;$i+=10) {
					$v3 = new Vector3($x + sin(deg2rad($i)) * 2, $y + 3 + cos(deg2rad($i)) * 2, $z - 3);
					$level->addParticle(new HeartParticle($v3));
					$v3 = new Vector3($x + sin(deg2rad($i)) * 3, $y + 3 + cos(deg2rad($i)) * 3, $z - 3);
					$level->addParticle(new HeartParticle($v3));
				}
				for($i=1;$i<8;$i++) {
						$v3 = new Vector3($x + cos(deg2rad(45)) * $i, $y + 3 + sin(deg2rad(45)) * $i, $z - 3);
						$level->addParticle(new HeartParticle($v3));
						$v3 = new Vector3($x + cos(deg2rad(135)) * $i, $y + 3 + sin(deg2rad(135)) * $i, $z - 3);
						$level->addParticle(new HeartParticle($v3));
						$v3 = new Vector3($x + cos(deg2rad(225)) * $i, $y + 3 + sin(deg2rad(225)) * $i, $z - 3);
						$level->addParticle(new HeartParticle($v3));
						$v3 = new Vector3($x + cos(deg2rad(315)) * $i, $y + 3 + sin(deg2rad(315)) * $i, $z - 3);
						$level->addParticle(new HeartParticle($v3));
					}
			break;
		}
		$level->addParticle(new HeartParticle($v1));

	}
}