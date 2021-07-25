<?php


namespace SSC\Command\DefaultCommands;


use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\level\Position;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\Player;
use pocketmine\Server;
use SSC\main;

class moonCommand extends VanillaCommand {

	public function __construct() {
		parent::__construct("moon","月へ行く。 宇宙船レベル20から開放","moon");
	}

	/**
	 * @param string[] $args
	 *
	 * @return mixed
	 * @throws CommandException
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if(!$sender instanceof Player) return false;
		$playerdata=main::getPlayerData($sender->getName());
		if(!$playerdata->getPlayer()->isOp()) {
			if ($playerdata->getSpaceShipSize() < 20) {
				$sender->getPlayer()->sendMessage("[管理AI] 宇宙船レベルが20に達すると行くことができます！");
				return true;
			}
		}
		$pos=new Position(mt_rand(-100,100),100,mt_rand(-100,100),Server::getInstance()->getLevelByName("moon"));
		$sender->teleport($pos);
		if ($sender->getGamemode() == 0) {
			$sender->getPlayer()->setAllowFlight(false);
			$sender->setFlying(false);
			$sender->getLevel()->broadcastLevelSoundEvent($pos, LevelSoundEventPacket::SOUND_TELEPORT);
		}
		$sender->addEffect(new EffectInstance(Effect::getEffect(11), 20 * 10, 100, true));
		$sender->getPlayer()->sendMessage("[§4⚠WARNING⚠§r] 未開拓地域です。大切なアイテムはしまって他人からの強奪に注意してください。");
		$sender->getPlayer()->sendMessage("[§aルール§r] リスキル禁止。ハック禁止。雑堀可能。アイテムドロップあり。キル可能。同クランへの攻撃は不可");
		return true;
	}
}