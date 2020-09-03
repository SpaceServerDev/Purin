<?php


namespace SSC\Command\DefaultCommands;


use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\Player;
use SSC\Form\GachaListForm;

class gachaCommand extends VanillaCommand {

	public function __construct() {
		parent::__construct("gacha","ガチャ一覧を表示します","/gacha");
	}

	/**
	 * @param string[] $args
	 *
	 * @return mixed
	 * @throws CommandException
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if($sender instanceof Player){
			$sender->sendForm(new GachaListForm($sender));
		}
		return true;
	}
}