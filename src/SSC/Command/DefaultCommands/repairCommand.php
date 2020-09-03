<?php


namespace SSC\Command\DefaultCommands;


use onebone\economyapi\EconomyAPI;
use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\item\Durable;
use pocketmine\item\Item;
use pocketmine\Player;

class repairCommand extends VanillaCommand {

	public function __construct() {
		parent::__construct("repair", "80円×ダメージ値で修復します", "/repair");
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
			/*foreach ($sender->getInventory()->getContents() as $itm) {
				$id_t = $itm->getId();
				$itema=null;
				if ($id_t === 378) {
					if ($itm->getCustomName() == "§d修復クリーム") {
						if(!$itm->getNamedTag()->offsetExists("Fish")) {
							$count += 1;
							$itema = $itm;
						}
					}
				}
			}*/
			/*if ($count == 0 or $itema==null) {
				$sender->sendMessage("[工務AI]修復クリームがありません");
				return true;
			} else {*/
				if ($item instanceof Durable) {
						if ($item->getDamage() > 0) {
							if(EconomyAPI::getInstance()->myMoney($sender->getName())>$item->getDamage()*40){
								EconomyAPI::getInstance()->reduceMoney($sender->getName(),$item->getDamage()*40);
								$sender->getInventory()->setItemInHand($item->setDamage(0));
								$sender->sendMessage("[工務AI]§a§l修復クリームを使って修復しました");
							}else{
								$sender->sendMessage("[工務AI]§aお金が足りません");
							}
							/*
							$sender->getInventory()->removeItem(Item::get(Item::MAGMA_CREAM));
							$sender->getInventory()->setItemInHand($item->setDamage(0));
							$sender->sendMessage("[工務AI]§a§l修復クリームを使って修復しました");
							*/
						} else {
							$sender->sendMessage("[工務AI]§4修復の必要がありません");
						}
					return true;
				}
				$sender->sendMessage("[工務AI]§4修復できるアイテムではないです");

			}

		//}
		return true;
	}
}