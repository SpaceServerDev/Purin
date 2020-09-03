<?php


namespace SSC\Command\DefaultCommands;


use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use SSC\Form\InformationForm;
use SSC\main;

class infoCommand extends VanillaCommand {

	public function __construct() {
		parent::__construct("info","サーバー内の情報を確認する","/info");
	}



	/**
	 * @param string[] $args
	 *
	 * @return mixed
	 * @throws CommandException
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		$sender->sendForm(new InformationForm());
		return true;
	}
}