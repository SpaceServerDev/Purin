<?php


namespace SSC\Level\Particle;


use pocketmine\level\Level;
use pocketmine\level\particle\DustParticle;
use pocketmine\math\Vector3;

class SlashEffectParticle implements SpaceServerParticle {

	public static function add($x, $y, $z, Level $level, int $direction = 0) {
		if ($direction === 0 or $direction === 2) {
			for ($i = 0.0; $i < 2.0; $i = $i + 0.1) {
				$y2 = $i ** 2;
				$pos = new Vector3($x, $y + 1 + $y2, $z + $i);
				$level->addParticle(new DustParticle($pos, 255, 0, 0));
			}
			for ($i = 0.0; $i > -1.5; $i = $i - 0.1) {
				$y2 = $i ** 2;
				$pos = new Vector3($x, $y + 1.5 + $y2, $z + 1.2 + $i);
				$level->addParticle(new DustParticle($pos, 255, 0, 0));
			}
		} else {
			for ($i = 0.0; $i < 2.0; $i = $i + 0.1) {
				$y2 = $i ** 2;
				$pos = new Vector3($x + $i, $y + 1 + $y2, $z );
				$level->addParticle(new DustParticle($pos, 255, 0, 0));
			}
			for ($i = 0.0; $i > -1.5; $i = $i - 0.1) {
				$y2 = $i ** 2;
				$pos = new Vector3($x + 1.2 + $i, $y + 1.5 + $y2, $z);
				$level->addParticle(new DustParticle($pos, 255, 0, 0));
			}
		}
	}
}
