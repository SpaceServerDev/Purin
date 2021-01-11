<?php


namespace SSC\Command\DefaultCommands;


use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\Server;
use SSC\main;
use SSC\PlayerEvent;

class tpagreeCommand extends VanillaCommand {


	public function __construct() {
		parent::__construct("tpagree", "テレポートリクエストを承認します", "/tpagree");
	}

	/**
	 * @param string[] $args
	 *
	 * @return mixed
	 * @throws CommandException
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if (!$sender instanceof Player) return true;
		$name = $sender->getName();
		$playerdata = main::getPlayerData($name);
		$target = Server::getInstance()->getPlayer($playerdata->getSentTppRequest());

		if ($playerdata->getSentTppRequest() === "") {
			$sender->sendMessage("[転送用AI] §cリクエストはありません。");
			return true;
		}

		if ($target === null) {
			$sender->sendMessage("[転送用AI] §aそのプレイヤーはオフラインです");
			$playerdata->resetSentTppRequest();
			return true;
		}

		$targetdata = main::getPlayerData($target->getName());
		$levelname=$sender->getLevel()->getFolderName();

		switch ($levelname){

			case "sun":
				if ($targetdata->isSun()) {
					$this->tp($sender, $target);
				} else {
					$target->sendMessage("[転送用AI] §b" . $sender->getName() . " は太陽にいるためワープできません");
					$sender->sendMessage("[転送用AI] §a相手側はまだ太陽を開放していないようです。 ");
				}
			break;

			case "pluto":
				if ($targetdata->isExpertLevel()) {
					$this->tp($sender, $target);
				} else {
					$target->sendMessage("[転送用AI] §b" . $sender->getName() . " は冥王星にいるためワープできません");
					$sender->sendMessage("[転送用AI] §a相手側はまだ冥王星を開放していないようです。 ");
				}
			break;

			case "moon":
				if ($targetdata->getSpaceShipSize() < 20) {
					$this->tp($sender, $target);
					$target->addEffect(new EffectInstance(Effect::getEffect(11), 20 * 20, 100, true));
					$target->addEffect(new EffectInstance(Effect::getEffect(18), 20 * 20, 100, true));
					$target->sendMessage("[§4⚠WARNING⚠§r] 未開拓地域です。大切なアイテムはしまって他人からの強奪に注意してください。");
					$target->sendMessage("[§aルール§r] リスキル禁止。ハック禁止。雑堀可能。アイテムドロップあり。キル可能。同クランへの攻撃は不可");
				} else {
					$target->sendMessage("[転送用AI] §b" . $sender->getName() . " は月にいるためワープできません");
					$sender->sendMessage("[転送用AI] §a相手側はまだ月を開放していないようです。 ");
				}
			break;

			default:
				$this->tp($sender,$target);
				$sender->sendMessage("[転送用AI] §bテレポートの承認が完了しました。");
			break;
		}
		$playerdata->resetSentTppRequest();
		$targetdata->resetSendTppRequest();
		return true;
	}

	private function fly(Player $player) {
		if ($player->getGamemode() == 0) {
			$player->getPlayer()->setAllowFlight(false);
			$player->setFlying(false);
		}
	}

	private function tp(Player $sender, Player $target) {
		$target->teleport(new Position($sender->getX(), $sender->getY(), $sender->getZ(), $sender->getLevel()));
		$target->sendMessage("[転送用AI] §b" . $sender->getName() . " がテレポートを承認しました。テレポートしています。");
		$this->fly($target);
		$sender->sendMessage("[転送用AI] §bテレポートの承認が完了しました。");
	}

}