<?php


namespace SSC\Command\DefaultCommands;


use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\Player;
use SSC\main;

class logCommand extends VanillaCommand {

	public function __construct() {
		parent::__construct("log", "ログを確認する", "/log ([x] [y] [z] [name])");
	}

	/**
	 * @param string[] $args
	 *
	 * @return mixed
	 * @throws CommandException
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if($sender instanceof Player) {
			if (main::getPlayerData($sender->getName())->getNumberPerm() >= 3) {
				$playerdata = main::getPlayerData($sender->getName());
				if (!isset($args[0])) {
					$playerdata->changeLog();
					$bool = $playerdata->getLog() ? "オン" : "オフ";
					$sender->sendMessage("ログ表示を" . $bool . "にしました");
					return true;
				}
				if (isset($args[0]) && isset($args[1]) && isset($args[2]) && isset($args[3])) {
					if (is_numeric($args[0]) && is_numeric($args[1]) && is_numeric($args[2])) {
						main::getMain()->checklog($args[0], $args[1], $args[2], $args[3], $sender);
					} else {
						$sender->sendMessage("/log x y z worldname");
					}
				} else {
					$sender->sendMessage("/log x y z worldname");
				}
			}
		}
		return true;
	}

}