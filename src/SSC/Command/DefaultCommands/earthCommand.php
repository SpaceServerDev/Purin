<?php


namespace SSC\Command\DefaultCommands;


use pocketmine\Server;
use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\level\Position;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\Player;
use SSC\Event\player\WarpPlayerEvent;

class earthCommand extends VanillaCommand {

	public function __construct(string $name) {
		parent::__construct($name, "地球へ移動します！","/earth [1/2]");
	}

	/**
	 * @param string[] $args
	 *
	 * @return mixed
	 * @throws CommandException
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		$cls=new WarpPlayerEvent();
		if ($sender instanceof Player) {
			if(!isset($args[0])){
				$cls->execute($sender, "earth");
				$sender->sendMessage("[宇宙船]§a地球に移動しました！");
			} else {
				if ($args[0] == 1) {
					$cls->execute($sender, "earth");
					$sender->sendMessage("[宇宙船]§a地球に移動しました！");
				} else if ($args[0] == 2) {
					$earth = Server::getInstance()->getLevelByName("earth");
					if ($sender->getGamemode() == 0) {
						$sender->getPlayer()->setAllowFlight(false);
						$sender->setFlying(false);
					}
					$pos = new Position(9998, 70, 10002, $earth);
					$sender->getPlayer()->teleport($pos);
					$sender->getLevel()->broadcastLevelSoundEvent($pos, LevelSoundEventPacket::SOUND_TELEPORT);
					$sender->sendMessage("[宇宙船]§a地球第2拠点に移動しました！");
				} else {
					$sender->sendMessage("[宇宙船]§a存在しない拠点です。");
				}
			}
		}
		return true;
	}
}