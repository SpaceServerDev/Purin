<?php


namespace SSC\Command\DefaultCommands;


use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\Player;
use SSC\main;

class tppCommand extends VanillaCommand {

	/**
	 * @var main
	 */
	private $main;

	public function __construct(main $main) {
		parent::__construct("tpp","テレポート申請を送ります","/tpp [player](名前は省略可能)");
		$this->main=$main;
	}

	/**
	 * @param CommandSender $sender
	 * @param string $commandLabel
	 * @param string[] $args
	 *
	 * @return mixed
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if($sender instanceof Player) {
			if (isset($args[0])) {
				$name = $sender->getName();
				$bname = $sender->getName();
				if ($target = $sender->getServer()->getPlayer($args[0])) {
					$targetname = $sender->getServer()->getPlayer($args[0])->getName();
					$btargetname = $sender->getServer()->getPlayer($args[0])->getName();
					if ($targetname === $sender->getName()) {
						$sender->sendMessage("[転送用AI]§a自分にリクエストを送信することはできません。");
						return true;
					}
					if (!isset($this->main->tpplayer[$targetname])) {
						$this->main->getServer()->getPlayer($args[0])->sendMessage("[転送用AI]§d" . $bname . " §bはあなたにテレポートしたいようです.");
						$this->main->getServer()->getPlayer($args[0])->sendMessage("[転送用AI] /tpagree§dとチャット欄に打てば承認することができます");
						$this->main->getServer()->getPlayer($args[0])->sendMessage("[転送用AI] /tpdis §dとチャット欄に打てば拒否することができます");
						$sender->sendMessage("[転送用AI] §r§bリクエストを §a" . $btargetname . "§bに送信しました");
						$this->main->tpplayer[$targetname] = $name;
						return true;
					} else {
						$sender->sendMessage("[転送用AI] §a他のリクエストがあるようなのでできません！");
						return true;
					}
				} else {
					$sender->sendMessage("[転送用AI] §aそのプレイヤーはオフラインです");
					return true;
				}
			} else {
				return false;
			}
		}
		return true;
	}
}