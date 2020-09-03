<?php


namespace SSC\Command\DefaultCommands;


use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use SSC\main;

class dustCommand extends VanillaCommand {

	/**
	 * @var main
	 */
	private $main;

	public function __construct(main $main) {
		parent::__construct("dust","インベントリのアイテムをすべて消します","/dust");
		$this->main=$main;
	}

	/**
	 * @param string[] $args
	 *
	 * @return mixed
	 * @throws CommandException
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		$playerdata=main::getPlayerData($sender->getName());
		if($playerdata->clearInventory()) {
			$playerdata->clearInventoryResponse(false);
			$sender->getInventory()->clearAll();
			$sender->sendMessage("[管理AI]§c削除いたしました。");
		} else {
			$playerdata->clearInventoryResponse(true);
			$sender->sendMessage("[管理AI]§4このコマンドはインベントリをすべて消します");
			$sender->sendMessage("[管理AI]§4良いなら再度実行してください");
		}
		return true;
	}
}