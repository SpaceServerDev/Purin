<?php


namespace SSC\Command\DefaultCommands;


use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\utils\CommandException;
use SSC\main;

class SendFormCommand extends VanillaCommand {

	/**
	 * @var string
	 */
	private $cls;

	/**
	 * SendFormCommand constructor.
	 * @param string $name
	 * @param string $description
	 * @param string|null $usageMessage
	 * @param string $cls
	 */
	public function __construct(string $name, string $description = "", string $usageMessage = null,  $cls = "") {
		parent::__construct($name, $description, $usageMessage);
		$this->cls = $cls;
	}

	/**
	 * @param string[] $args
	 *
	 * @return mixed
	 * @throws CommandException
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if ($this->cls != null) {
			main::getPlayerData($sender->getName())->save();
			$cls = "\SSC\Form\\".$this->cls;
			$cls = new $cls(main::getPlayerData($sender->getName()));
			$sender->sendForm($cls);
		}
		return true;
	}
}
