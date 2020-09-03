<?php


namespace SSC\Command\DefaultCommands;


use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\Player;
use SSC\main;

class advancemodeCommand extends VanillaCommand {

	public function __construct() {
		parent::__construct("advancemode","レベルをアドバンスモードに変える(ノーマルレベル501以上)","/advancemode");
	}

	/**
	 * @param string[] $args
	 *
	 * @return mixed
	 * @throws CommandException
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if ($sender instanceof Player) {
			$playerdata=main::getPlayerData($sender->getName());
			if ($playerdata->isAdvanceLevel()) {
				$sender->sendMessage("[管理AI]もうあなたは既にアドバンスです");
				return true;
			}
			if ($playerdata->isExpertLevel()) {
				$sender->sendMessage("[管理AI]もうあなたは既にアドバンスの壁を突破しましたよね...?");
				return true;
			}
			if ($playerdata->getLevel() < 501) {
				$sender->sendMessage("[管理AI]レベル501でないとアドバンスになれません");
				return true;
			}
			$playerdata->changeAdvanceMode();
		}
		return true;
	}
}