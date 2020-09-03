<?php


namespace SSC\Form;


use pocketmine\form\Form;
use pocketmine\form\FormValidationException;
use pocketmine\item\Item;
use pocketmine\Player;
use SSC\main;
use SSC\PlayerEvent;

class ApologyForm implements Form {

	/**
	 * @var PlayerEvent
	 */
	private $pe;

	/**
	 * @var main
	 */
	private $main;

	/**
	 * @var string
	 */
	private $content;

	/**
	 * ApologyForm constructor.
	 * @param main $main
	 * @param PlayerEvent $pe
	 * @param string $content
	 */
	public function __construct(main $main,PlayerEvent $pe,string $content="") {
		$this->pe=$pe;
		$this->main=$main;
		$this->content=$content;
	}

	/**
	 * Handles a form response from a player.
	 *
	 * @param Player $player
	 * @param mixed $data
	 *
	 * @throws FormValidationException if the data could not be processed
	 */
	public function handleResponse(Player $player, $data): void {
		if (!is_numeric($data)) {
			return;
		}
		switch ($data) {
			case 0:
					$player->sendForm(new ApologyForm($this->main,$this->pe,"\n\n受け取り期間が終了しました。"));
			break;
		}
	}

	/**
	 * Specify data which should be serialized to JSON
	 * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 * @since 5.4.0
	 */
	public function jsonSerialize() {
		return [
			"type" => "form",
			"title" => "§dお詫びボックス",
			"content" => "今受け取れるお詫びです。".$this->content,
			"buttons" => [
							['text'=>"R20/04/05 TANBO弓のお詫び\n§8受け取り終了"],
						 ],
		];
	}
}