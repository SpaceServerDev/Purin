<?php


namespace SSC\Task;


use pocketmine\scheduler\Task;

class EventGenerater extends Task {

	/**
	 * @var \Generator
	 */
	private $generator;

	/**
	 * Actions to execute when run
	 *
	 * @param \Generator $generator
	 */
	public function __construct(\Generator $generator) {
		$this->generator = $generator;
	}

	public function onRun(int $currentTick) {
		if ($this->generator->valid()) {
			$this->generator->next();
		} else {
			$this->getHandler()->cancel();
		}

	}
}