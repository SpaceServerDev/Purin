<?php


namespace SSC\Command\DefaultCommands;


use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\Player;
use SSC\Form\LoginBonusForm;
use SSC\main;

class loginbonusCommand extends VanillaCommand {

	public function __construct() {
		parent::__construct("loginbonus","ログインボーナスを受け取る","/loginbonus");
	}

	/**
	 * @param string[] $args
	 *
	 * @return mixed
	 * @throws CommandException
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if($sender instanceof Player) {
			$playerdata=main::getPlayerData($sender->getName());
			if (main::getMain()->loginbonus->exists($sender->getName())) {
				if ($playerdata->getBonus()) {
					$sender->sendMessage("[ログボAI]きょうは受け取りましたね！");
					return true;
				}
			}
		}
		$sender->sendForm(new LoginBonusForm(main::getPlayerData($sender->getName())));
		return true;
	}
}