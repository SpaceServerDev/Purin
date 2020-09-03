<?php


namespace SSC\Command\DefaultCommands;


use onebone\economyapi\EconomyAPI;
use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;
use pocketmine\Player;
use SSC\Form\ShopForm\FirstShopForm;

class shopCommand extends VanillaCommand {
	public function __construct() {
		parent::__construct("shop", "ショップを表示します", "/shop");
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

			$buttons[] = [
				'text' => "ものを買う",
			];
			$buttons[] = [
				'text' => "ものを売る",
			];

			$pk = new ModalFormRequestPacket();
			$id = 30718;
			$pk->formId = $id;
			$data = [
				"type" => "form",
				"title" => "§d§lITEMSHOP.com",
				"content" => "物の売買ができます\n§a現在の所持金 → §e{$mymoney}￥\n\n\n",
				"buttons" => $buttons
			];
			$pk->formData = json_encode($data, JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING | JSON_UNESCAPED_UNICODE);
			$sender->dataPacket($pk);

		}
		return true;
	}
}