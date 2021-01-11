<?php


namespace SSC\Command\DefaultCommands;


use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use pocketmine\level\Position;
use pocketmine\Player;
use SSC\main;

class rouyaCommand extends VanillaCommand {

	/**
	 * @var
	 */
	private $main;

	public function __construct(main $main) {
		parent::__construct("rouya","悪い鯖民を牢屋にぶっこみます","/rouya [playername]");
		$this->main=$main;
	}

	/**
	 * @param string[] $args
	 *
	 * @return mixed
	 * @throws CommandException
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
			if ($sender->isOp() or !$sender instanceof Player) {
				if (!isset($args[0])) return false;
				$target = $this->main->getServer()->getPlayer($args[0]);
				if ($target instanceof Player) {
					$targetname = $target->getName();
					if (!$this->main->blacklist->exists($targetname)) {
						$this->main->blacklist->set($targetname, true);
						$this->main->blacklist->save();
						$world = $this->main->getServer()->getLevelByName('space');
						$pos = new Position(226, 107, 390, $world);
						$target->teleport($pos);
						$this->main->getServer()->broadcastMessage($sender->getName() . "が" . $targetname . "を牢屋に入れました");
						$target->sendMessage("[管理AI]あなたは牢屋に入れられました。");
						$target->setImmobile(true);
					} else {
						$this->main->getServer()->broadcastMessage($sender->getName() . "が" . $targetname . "を釈放しました");
						$target->sendMessage("[管理AI]あなたは釈放されました。");
						$this->main->blacklist->remove($targetname);
						$this->main->blacklist->save();
						$target->setImmobile(false);
						$this->main->getServer()->dispatchCommand($target, "spawn");
					}
				}
			}
		return true;
	}
}