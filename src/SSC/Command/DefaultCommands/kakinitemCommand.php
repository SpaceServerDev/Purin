<?php


namespace SSC\Command\DefaultCommands;


use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;

class kakinitemCommand extends VanillaCommand {

	public function __construct() {
		parent::__construct("kakinitem");
	}

	/**
	 * @param string[] $args
	 *
	 * @return mixed
	 * @throws CommandException
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if($sender->isOp()) {
			$item = Item::get(278, 0, 1);
			$enchantment = Enchantment::getEnchantment(15);
			$item->addEnchantment(new EnchantmentInstance($enchantment, 5));
			$enchantment = Enchantment::getEnchantment(17);
			$item->addEnchantment(new EnchantmentInstance($enchantment, 10));
			if ($sender->getInventory()->canAddItem($item)) {
				$sender->getInventory()->addItem($item);
			}
		}
		return true;
	}
}