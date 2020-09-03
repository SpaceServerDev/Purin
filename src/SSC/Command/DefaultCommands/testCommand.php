<?php


namespace SSC\Command\DefaultCommands;


use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\entity\Ageable;
use pocketmine\entity\behavior\FindAttackableTargetBehavior;
use pocketmine\entity\behavior\FloatBehavior;
use pocketmine\entity\behavior\LookAtPlayerBehavior;
use pocketmine\entity\behavior\MeleeAttackBehavior;
use pocketmine\entity\behavior\RandomLookAroundBehavior;
use pocketmine\entity\behavior\RandomStrollBehavior;
use pocketmine\entity\Entity;
use pocketmine\entity\hostile\Zombie;
use pocketmine\entity\Human;
use pocketmine\entity\Monster;
use pocketmine\entity\object\Fireworks;
use pocketmine\entity\Skin;
use pocketmine\entity\Smite;
use pocketmine\entity\utils\FireworksUtils;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\form\Form;
use pocketmine\form\FormValidationException;
use pocketmine\item\Item;
use pocketmine\level\Explosion;
use pocketmine\level\Level;
use pocketmine\level\particle\DustParticle;
use pocketmine\level\particle\HeartParticle;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\metadata\MetadataValue;
use pocketmine\nbt\tag\ByteArrayTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\network\mcpe\protocol\ActorEventPacket;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;
use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\utils\Config;
use SSC\Gun\Bombing\BombingEvent;
use SSC\Gun\Bombing\BombingTask;
use SSC\Item\Item_AK47;
use SSC\Item\Item_AWM;
use SSC\Item\Item_UZI;
use SSC\main;

class testCommand extends VanillaCommand {

	public function __construct() {
		parent::__construct("test","","");
	}

	/**
	 * @param Player $sender
	 * @param string $commandLabel
	 * @param string[] $args
	 *
	 * @return mixed
	 * @throws \ReflectionException
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if($sender->isOp()) {
			$x = $sender->x;
			$y = $sender->y;
			$z = $sender->z;
			$level = $sender->getPlayer()->getLevel();

			var_dump($sender->getInventory()->getItemInHand()->getNamedTag());
			$cls = new BombingEvent();
			main::getMain()->getScheduler()->scheduleDelayedTask(new BombingTask($sender, $cls), 20);
			Server::getInstance()->broadcastTip($sender->getName() . "が砲撃支援を要請した！");

			$item=Item_AK47::get();
			$sender->getInventory()->addItem($item);

			$item = Item_UZI::get();
			$sender->getInventory()->addItem($item);

			$item=Item_AWM::get();
			$sender->getInventory()->addItem($item);


			$sender->getInventory()->addItem($item);
			//main::getMain()->getScheduler()->scheduleRepeatingTask(new TestTask($this->Bombing($x, $y, $z, $sender, $level)), 2);
		}

		return true;
	}

	public function test($x,$y,$z,$sender,Level $level): \Generator {
		$sender->sendMessage("おめでとう！ yurisi と Cookietattchan と VillagerMeyason が 結婚しました！");
		for ($i = 0; $i < 360; $i+=10) {
			yield;
			$pos = new Vector3($x + sin(deg2rad($i))**3 * 2 * 1, $y+2+  (1 * cos(deg2rad($i)) - cos(deg2rad($i))**4)*2, $z );
			$level->addParticle(new HeartParticle($pos));
			$pos = new Vector3($x , $y+2+  (1 * cos(deg2rad($i)) - cos(deg2rad($i))**4)*2, $z + sin(deg2rad($i))**3 * 2 * 1);
			$level->addParticle(new HeartParticle($pos));
			//$y += 0.1;

		}
	}
}
class TestTask extends Task {
	/**
	 * Actions to execute when run
	 *
	 * @return void
	 */
		public function __construct(\Generator $generator) {
			$this->generator = $generator;
		}

		public function onRun(int $currentTick) {
			if ($this->generator->valid()) {//yieldされていたら
            $this->generator->next();//進む
        } else {//yieldされなくなったら(一番下に行った=forを抜けたら)
            $this->getHandler()->cancel();//タスクをキャンセル(終了)する
        }
			//main::getMain()->getScheduler()->scheduleRepeatingTask(new particletask(), 1200);
		}
}






