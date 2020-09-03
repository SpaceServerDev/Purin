<?php


namespace SSC\Command\DefaultCommands;


use onebone\economyapi\EconomyAPI;
use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\item\Item;
use pocketmine\Player;
use SSC\Gacha\GachaEvent;
use SSC\main;

class gacCommand extends VanillaCommand {



	public function __construct() {
		parent::__construct("gac","ガチャを引きます(看板用)","/gac [normal/event]");
	}

	/**
	 * @param string[] $args
	 *
	 * @return mixed
	 * @throws CommandException
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if ($sender instanceof Player) {
			if (!isset($args[0])) {
				$sender->sendMessage("/gac (normal(1500円)/event(2500円))");
				$sender->sendMessage("§a現在星イベントが開催されてます。");
				$sender->sendMessage("§aピックアップ武器は§dlobi,discord§aを御覧ください");
				return false;
			}
			$item = Item::get(1, 0, 64);
			$mymoney = EconomyAPI::getInstance()->myMoney($sender->getName());
			$playerdata = main::getPlayerData($sender->getName());
			if ($args[0] === "normal") {
				if (!$sender->getInventory()->canAddItem($item)) {
					$sender->sendMessage("インベントリに空きがありません");
					return true;
				}
				if ($mymoney > 1500) {
					EconomyAPI::getInstance()->reduceMoney($sender->getName(), 1500);
					$cls = new GachaEvent($playerdata, "normal");
					$cls->turn();
				} else {
					$sender->sendMessage("[ガチャAI] お金が足りません");
				}
			} else if ($args[0] === "event") {
				if (!$sender->getInventory()->canAddItem($item)) {
					$sender->sendMessage("インベントリに空きがありません");
					return true;
				}
				if ($mymoney > 2500) {
					EconomyAPI::getInstance()->reduceMoney($sender->getName(), 2500);
					$cls = new GachaEvent($playerdata);
					$cls->turn();
				} else {
					$sender->sendMessage("[ガチャAI] お金が足りません");
				}
			} else {
				$sender->sendMessage("§e========================");
				$sender->sendMessage("/gac (normal(1500円)/event(2500円))");
				$sender->sendMessage("§a現在星イベントが開催されてます。");
				$sender->sendMessage("§aピックアップ武器は§dlobi,discord§aを御覧ください");
				$sender->sendMessage("§e========================");
				return false;
			}
		}
		return true;
	}
}