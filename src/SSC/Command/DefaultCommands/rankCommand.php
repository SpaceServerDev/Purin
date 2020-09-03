<?php


namespace SSC\Command\DefaultCommands;


use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use SSC\Form\RankForm;
use SSC\main;

class rankCommand extends VanillaCommand {
	public function __construct() {
		parent::__construct("rank");
	}

	/**
	 * @param string[] $args
	 *
	 * @return mixed
	 * @throws CommandException
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		$sender->sendForm(new RankForm(main::getPlayerData($sender->getName())));
		return true;
	}
}