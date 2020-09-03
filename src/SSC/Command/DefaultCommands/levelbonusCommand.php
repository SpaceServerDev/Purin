<?php


namespace SSC\Command\DefaultCommands;


use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use SSC\Form\LevelBonusForm;
use SSC\main;

class levelbonusCommand extends VanillaCommand {


	public function __construct() {
		parent::__construct("levelbonus","レベルボーナスを確認します。","/levelbonus");
	}

	/**
	 * @param string[] $args
	 *
	 * @return mixed
	 * @throws CommandException
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		$sender->sendForm(new LevelBonusForm(main::getPlayerData($sender->getName()),main::getMain()));
		return true;
	}
}