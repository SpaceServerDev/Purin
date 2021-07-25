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
use pocketmine\network\mcpe\protocol\FilterTextPacket;
use pocketmine\network\mcpe\protocol\OnScreenTextureAnimationPacket;
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
use SSC\Item\Item_RPG7;
use SSC\Item\Item_UZI;
use SSC\Item\JimSniperV2;
use SSC\Item\RepairCream;
use SSC\Level\Particle\HeartCircleParticle;
use SSC\main;
use SSC\Task\EventGenerater;

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

			$cls = new BombingEvent();
			main::getMain()->getScheduler()->scheduleDelayedTask(new BombingTask($sender, $cls), 20);

			var_dump($sender->getInventory()->getItemInHand()->getNamedTag());

			$item=Item_AK47::get();
			$sender->getInventory()->addItem($item);

			$item = Item_UZI::get();
			$sender->getInventory()->addItem($item);

			$item=Item_AWM::get();
			$sender->getInventory()->addItem($item);

			$item=Item_RPG7::get();
			$sender->getInventory()->addItem($item);

			$item=RepairCream::get(10);
			$sender->getInventory()->addItem($item);

			$item=RepairCream::get(10);
			$item->getNamedTag()->setInt("Fish",1);
			$sender->getInventory()->addItem($item);

			$item=JimSniperV2::get();
			$sender->getInventory()->addItem($item);

			//main::getMain()->addEXP($sender, 500000);
			$pk=new OnScreenTextureAnimationPacket();
			$pk->effectId=27;
			$sender->sendDataPacket($pk);


		}


		$player=$sender;
		//main::getMain()->getScheduler()->scheduleRepeatingTask(new EventGenerater(HeartCircleParticle::addMoveParticle($player->x, $player->y + 1, $player->z, $player->getLevel())), 1);

		return true;
	}

}






