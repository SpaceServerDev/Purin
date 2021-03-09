<?php


namespace SSC\Command\DefaultCommands;


use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\Player;
use SSC\Form\OptionForm;

class optionCommand extends VanillaCommand {

	public function __construct() {
		parent::__construct("option","設定を変える","option");
	}

	/**
	 * @param string[] $args
	 *
	 * @return mixed
	 * @throws CommandException
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if($sender instanceof Player) $sender->sendForm(new OptionForm());
		return true;
	}
}