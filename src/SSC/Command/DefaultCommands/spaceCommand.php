<?php


namespace SSC\Command\DefaultCommands;


use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\level\Position;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\Player;
use pocketmine\Server;
use SSC\Event\player\WarpPlayerEvent;

class spaceCommand extends VanillaCommand {

	public function __construct(string $name) {
		parent::__construct("space","宇宙に移動します！","/space [1/2]");
	}

	/**
	 * @param CommandSender $sender
	 * @param string $commandLabel
	 * @param string[] $args
	 *
	 * @return mixed
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		$cls=new WarpPlayerEvent();
		if ($sender instanceof Player) {
			if (!isset($args[0])) {
				$cls->execute($sender, "space", true);
				$sender->sendMessage("[宇宙船]§a宇宙(area1)に移動しました！");
				return true;
			}
			if ($args[0] == "1") {
				$cls->execute($sender, "space", true);
				$sender->sendMessage("[宇宙船]§a宇宙(area1)に移動しました！");
			} else if ($args[0] == "2") {
				$world = Server::getInstance()->getLevelByName('space');
				$pos = new Position(695, 94, -113, $world);
				$sender->getPlayer()->teleport($pos);
				if ($sender->getGamemode() == 0) {
					$sender->getPlayer()->setAllowFlight(true);
				}
				$sender->getLevel()->broadcastLevelSoundEvent($pos, LevelSoundEventPacket::SOUND_TELEPORT);
				$sender->sendMessage("[宇宙船]§a宇宙(area2)に移動しました！");
			} else {
				$sender->sendMessage("[宇宙船]§a存在しないSPACESTATIONです。");
			}

		}
		return true;
	}
}