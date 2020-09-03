<?php


namespace SSC\Command\DefaultCommands;


use onebone\economyapi\EconomyAPI;
use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use SSC\Form\SlotForm\SlotFirstForm;

class slotCommand extends VanillaCommand {

	public function __construct() {
		parent::__construct("slot","1回たった500円でパチンカスになろう！","/slot");
	}

	/**
	 * @param string[] $args
	 *
	 * @return mixed
	 * @throws CommandException
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		$sender->sendForm(new SlotFirstForm(EconomyAPI::getInstance()->myMoney($sender->getName())));
		return true;
	}
}