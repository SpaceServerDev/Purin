<?php


namespace SSC\Command\DefaultCommands;


use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\Player;
use SSC\main;

class flyCommand extends VanillaCommand {

	public function __construct() {
		parent::__construct("fly","飛べます！(課金者専用)","/fly [on|off]");
	}

	/**
	 * @param string[] $args
	 *
	 * @return mixed
	 * @throws CommandException
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if ($sender instanceof Player) {
			if (!isset($args[0])) {
				$sender->sendMessage($this->getUsage());
				return true;
			}
			if ($args[0] === "on") {
				if ($sender->getLevel()->getFolderName() == "pvp" || $sender->getLevel()->getFolderName() == "world") {
					$sender->sendMessage("[管理AI]§aこのワールドでは飛ぶことができません");
					return true;
				}
				if (!$sender->isOp()) {
					if (main::getMain()->kakin->exists($sender->getName())) {
						if (main::getMain()->kakin->get($sender->getName()) > 9999) {
							$sender->getPlayer()->setAllowFlight(true);
							$sender->sendMessage("[管理AI]§a現在のワールドで飛べるようになりました!");
							return true;
						}
					}
					switch ($sender->getName()) {
						case "rillgungun":
						case "Clllown":
							$sender->getPlayer()->setAllowFlight(true);
							$sender->sendMessage("[管理AI]§a現在のワールドで飛べるようになりました!");
							return true;
						default:
							$sender->sendMessage("[管理AI]§4権限がありません");
							return true;
					}
				}
				$sender->getPlayer()->setAllowFlight(true);
				$sender->sendMessage("[管理AI]§a現在のワールドで飛べるようになりました!");
				return true;
			}elseif($args[0] === "off"){
				$sender->getPlayer()->setAllowFlight(false);
				$sender->sendMessage("[管理AI]§aflyモードを解除しました");
				return true;
			}
		}
		return false;
	}
}