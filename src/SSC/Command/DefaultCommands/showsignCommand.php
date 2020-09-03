<?php


namespace SSC\Command\DefaultCommands;


use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\Player;
use SSC\main;

class showsignCommand extends VanillaCommand {


	public function __construct() {
		parent::__construct("showsign","看板の中身を覗きます","/showsign");
	}

	/**
	 * @param string[] $args
	 *
	 * @return mixed
	 * @throws CommandException
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if ($sender instanceof Player) {
			if ($sender->isOp()) {
				$playerdata = main::getPlayerData($sender->getName());
				$playerdata->setSign();
				$msg = $playerdata->getSign() ? "ON" : "OFF";
				$sender->sendMessage("[管理AI]§a" . $msg . "にいたしました。");
			}
		}
		return true;
	}
}