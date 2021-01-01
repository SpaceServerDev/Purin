<?php

namespace SSC\Level\Particle;

use pocketmine\level\Level;

interface SpaceServerParticle {

	public static function add($x,$y,$z,Level $level,int $direction=0);

}