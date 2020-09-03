<?php


namespace SSC\Gun\Bombing;


use pocketmine\level\Explosion;
use pocketmine\level\Level;
use pocketmine\level\particle\DustParticle;
use pocketmine\level\particle\SmokeParticle;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\Server;

class BombingEvent {

	private $count=0;

	public function addTask(){
		$this->count++;
	}

	public function isTask(){
		return $this->count<10;
	}

	public function dropBom($x,$y,$z,Level $level){
		//var_dump($this->onGround($x,$y,$z,$level));
		$y=$this->onGround($x,$y,$z,$level);
		$y2 = $y + 20;
		$this->sound("music.missile1", $x, $y, $z, $level);
			for ($i = 0; $i < 20; $i += 1) {
				yield;
				$pos = new Vector3($x, $y2 - $i, $z);
				$level->addParticle(new SmokeParticle($pos, 1000));
				for ($j=0;$j<360;$j+=20){
					$pos = new Vector3($x, $y+0.5, $z);
					$level->addParticle(new DustParticle($pos, 255,0,0));
					$pos = new Vector3($x+sin(deg2rad($j))*2, $y+0.5, $z+cos(deg2rad($j))*2);
					$level->addParticle(new DustParticle($pos, 255,0,0));
				}
			}
			$explosion = new Explosion(new Position($x, $y, $z, $level), 1);
			$explosion->explodeB();
			$this->sound("music.bomb2", $x, $y, $z, $level);

	}

	public function sound(string $name,$x,$y,$z,Level $level,$vol=0.5){
		$pk2 = new PlaySoundPacket;
		$pk2->soundName = $name;
		$pk2->x = $x;
		$pk2->y = $y;
		$pk2->z = $z;
		$pk2->volume = $vol;
		$pk2->pitch = 1;
		Server::getInstance()->broadcastPacket($level->getPlayers(),$pk2);
	}

	public function onGround($x,$y,$z,Level $level) {
		for ($i = $y; $i > 0; $i--) {
			if ($level->getBlockAt($x, $i, $z)->getId() != 0) {
				return ceil($i);
			}
		}
		return 0;
	}

}