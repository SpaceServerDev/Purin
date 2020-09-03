<?php


namespace SSC\Command\DefaultCommands;


use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\Player;
use SSC\Form\CustomNameForm;

class cnameCommand extends VanillaCommand {

	public function __construct() {
		parent::__construct("cname","今持っているアイテムの名前を名札を使用して変更します","/cname");
	}

	/**
	 * @param string[] $args
	 *
	 * @return mixed
	 * @throws CommandException
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if ($sender instanceof Player) {
			$count = 0;
			$item = $sender->getInventory()->getItemInHand();
			foreach ($sender->getInventory()->getContents() as $itm) {
				$id_t = $itm->getId();
				if ($id_t === 421) {
					$count += 1;
				}
			}
		}
		if ($item->getId() == 0) {
			$sender->sendMessage("[工務AI]手に持っているものがありません");
			return true;
		}
		switch ($item->getCustomName()) {
			case "§b水星のように重い斧(TRAPPIST-1e専用)":
			case "§b木星の力がみなぎる軽いツルハシ(TRAPPIST-1e専用)":
			case "§c火星の嵐の力(TRAPPIST-1e専用)":
			case "§8土星のガスと塵のツルハシ(TRAPPIST-1e専用)":
			case "§b地球を感じる奇跡のシャベル(TRAPPIST-1e専用)":
			case "§b海王星の氷のツルハシ(TRAPPIST-1e専用)":
			case "§4天王星の縦の環のシャベル(TRAPPIST-1e専用)":
			case "§8冥王星の如く小さめのシャベル(TRAPPIST-1e専用)":
			case "§8月のような静寂のツルハシ(TRAPPIST-1e専用)":
				$sender->sendMessage("§4[管理AI]名前を変えることができないアイテムです");
				return true;
		}
		if ($count == 0) {
			$sender->sendMessage("[工務AI]名札がありません");
			return true;
		} else {
			$sender->sendForm(new CustomNameForm($item));
		}
		return true;
	}
}