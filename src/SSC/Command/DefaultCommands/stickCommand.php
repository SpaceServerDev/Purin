<?php


namespace SSC\Command\DefaultCommands;


use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;

class stickCommand extends VanillaCommand {

	public function __construct() {
		parent::__construct("stick","マジカルステッキをインベントリに召喚します。","/stick");
	}

	/**
	 * @param string[] $args
	 *
	 * @return mixed
	 * @throws CommandException
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		$item = Item::get(280, 0, 1);
		$item->setCustomName("§dマジカル☆ステッキ");
		$enchantment = Enchantment::getEnchantment(17);
		$item->addEnchantment(new EnchantmentInstance($enchantment, 1));
		if ($sender->getInventory()->canAddItem($item) == true) {
			if (!$sender->getInventory()->contains($item) == true) {
				$sender->getInventory()->addItem($item);
				$sender->sendMessage("[管理AI]§aインベントリに追加しました！");
				return true;
			} else {
				$sender->sendMessage("[管理AI]§aすでに持っています");
				return true;
			}
		} else {
			$sender->sendMessage("[管理AI]§aアイテムを追加できません。");
			return true;
		}
	}
}