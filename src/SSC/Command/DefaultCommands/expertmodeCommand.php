<?php


namespace SSC\Command\DefaultCommands;


use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\Player;
use SSC\main;

class expertmodeCommand extends VanillaCommand {


	public function __construct() {
		parent::__construct("expertmode","レベルをアドバンスモードに変える(アドバンスレベル1000以上)","/expertmode");
	}

	/**
	 * @param string[] $args
	 *
	 * @return mixed
	 * @throws CommandException
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if ($sender instanceof Player) {
			$playerdata = main::getPlayerData($sender->getName());
			if ($playerdata->isExpertLevel()) {
				$sender->sendMessage("[管理AI]もうあなたは既にエキスパートです");
				return true;
			}
			if ($playerdata->isAdvanceLevel()) {
				if ($playerdata->getLevel() < 1000) {
					$sender->sendMessage("[管理AI]レベル1000以上でないとエキスパートになれません");
					return true;
				}
				$sender->sendMessage("[管理AI]地獄へようこそ");
				$playerdata->changeExpertMode();
			}
		}
		return true;
	}

}