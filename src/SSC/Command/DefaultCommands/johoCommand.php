<?php


namespace SSC\Command\DefaultCommands;


use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use SSC\main;

class johoCommand extends VanillaCommand {

	public function __construct() {
		parent::__construct("joho","情報表示のON/OFFを切り替える","/joho");
	}

	/**
	 * @param string[] $args
	 *
	 * @return mixed
	 * @throws CommandException
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		$playerdata=main::getPlayerData($sender->getName());
		if ($playerdata->getEnableInfo()) {
			$playerdata->setInfo(false);
			$sender->sendMessage("[管理AI]§aOFFにしました。");
			$bar = $playerdata->getBossbar();
			$bar->removePlayer($sender);
			return false;
		} else {
			$playerdata->setInfo(true);
			$sender->sendMessage("[管理AI]§aONにいたしました。");
			if ($playerdata->isNormalLevel()) {
				$lt = floor($playerdata->getLevel() * 1.1);
				$leveltable = (30 * $lt) + $playerdata->getTotalExp();
			}else if($playerdata->isAdvanceLevel()){
				$lt = floor($playerdata->getLevel() * 1.3);
				$leveltable = (120 * $lt) + $playerdata->getTotalExp();
			}else if($playerdata->isExpertLevel()){
				$lt = floor($playerdata->getLevel() * 1.5);
				$leveltable = (250 * $lt) + $playerdata->getTotalExp();
			}
			if ($playerdata->getExp() == 0) {
				$keiken = 0;
			} else {
				$a = $playerdata->getExp() - $playerdata->getTotalExp();
				$b = $leveltable - $playerdata->getTotalExp();
				$keiken = $a / $b;
			}
			$bar = $playerdata->getBossbar();
			$bar->setTitle("経験値 {$playerdata->getExp()} / {$leveltable}")->setSubTitle("名前:{$sender->getName()} レベル:{$playerdata->getLevel()}")->setPercentage($keiken)->addPlayer($sender);
			return false;
		}
	}
}