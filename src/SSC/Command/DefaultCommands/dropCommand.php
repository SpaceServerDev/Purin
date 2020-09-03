<?php


namespace SSC\Command\DefaultCommands;


use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\Player;
use SSC\main;

class dropCommand extends VanillaCommand {

	public function __construct() {
		parent::__construct("drop","ドロップしないモードに変更/解除する","/drop");
	}

	/**
	 * @param string[] $args
	 *
	 * @return mixed
	 * @throws CommandException
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if ($sender instanceof Player) {
			$tag = $sender->namedtag;
			if (!main::getMain()->isDrops($sender)) {
				$tag->setInt("bb", 0);
				$sender->sendMessage("[アイテムAI]§aONにいたしました。");
				return true;
			} else {
				$tag->setInt("bb", 1);
				$sender->sendMessage("[アイテムAI]§aOFFにしました。");
				return true;
			}
		} else {
			return false;
		}
	}
}