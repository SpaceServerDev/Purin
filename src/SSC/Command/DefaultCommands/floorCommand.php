<?php


namespace SSC\Command\DefaultCommands;


use onebone\economyapi\EconomyAPI;
use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\Player;

class floorCommand extends VanillaCommand {

	public function __construct() {
		parent::__construct("floor","小数になった所持金を切り捨てます","/floor");
	}

	/**
	 * @param string[] $args
	 *
	 * @return mixed
	 * @throws CommandException
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if ($sender instanceof Player) {
			$mymoney = EconomyAPI::getInstance()->myMoney($sender->getName());
			$mny = floor($mymoney);
			EconomyAPI::getInstance()->setMoney($sender->getName(), $mny);
			$sender->sendMessage("[管理AI] §a切り捨てました！");
		}
		return true;
	}
}