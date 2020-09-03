<?php
namespace SSC\Form;

use pocketmine\form\FormValidationException;
use SSC\PlayerEvent;
use pocketmine\form\Form;
use pocketmine\Player;

class DailyForm implements Form {
	/**
	 * @var PlayerEvent
	 */
	private $pd;

	/**
	 * @var String
	 */
	private $content;

	public function __construct(PlayerEvent $playerdata,string $content="デイリーミッションです！\n") {
		$this->pd=$playerdata;
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
		if(!is_numeric($data)){
			return;
		}
		$n=0;
		switch ($data) {
			case 0:
				if (!$this->pd->getVar("DAIRYGET1")) {
					if ($this->pd->getNowDairy1() >= $this->pd->getMaxDairy1()) {
						$this->pd->addVar("DAIRY");
						++$n;
						if ($this->pd->getVar("DAIRY1") === 3 or $this->pd->getVar("DAIRY1") === 5) {
							++$n;
							$this->pd->addVar("DAIRY");
						}
						$player->sendForm(new DailyForm($this->pd, "デイリーチケットを{$n}枚受け取りました!\n"));
						$this->pd->setDairy1();
					} else {
						$player->sendForm(new DailyForm($this->pd, "目的の枚数を達成していません\n"));
					}
				}else {
					$player->sendForm(new DailyForm($this->pd, "本日分はすでに受け取ってます\n"));
				}
				break;
			case 1:
				if (!$this->pd->getVar("DAIRYGET2")) {
					if ($this->pd->getNowDairy2() >= $this->pd->getMaxDairy2()) {
						$this->pd->addVar("DAIRY");
						++$n;
						if ($this->pd->getVar("DAIRY2") === 7 or $this->pd->getVar("DAIRY2") === 9) {
							++$n;
							$this->pd->addVar("DAIRY");
						}
						$player->sendForm(new DailyForm($this->pd, "デイリーチケットを{$n}枚受け取りました!\n"));
						$this->pd->setDairy2();
					} else {
						$player->sendForm(new DailyForm($this->pd, "目的の枚数を達成していません\n"));
					}
				}else {
					$player->sendForm(new DailyForm($this->pd, "本日分はすでに受け取ってます\n"));
				}
				break;
			case 2:
				if (!$this->pd->getVar("DAIRYGET3")) {
					if ($this->pd->getNowDairy3() >= $this->pd->getMaxDairy3()) {
						$this->pd->addVar("DAIRY");
						++$n;
						if($this->pd->getVar("DAIRY3")===13or$this->pd->getVar("DAIRY3")===15){
							++$n;
							$this->pd->addVar("DAIRY");
						}
						$player->sendForm(new DailyForm($this->pd,"デイリーチケットを{$n}枚受け取りました!\n"));
						$this->pd->setDairy3();
					}else{
						$player->sendForm(new DailyForm($this->pd,"目的の枚数を達成していません\n"));
					}
				}else{
					$player->sendForm(new DailyForm($this->pd,"本日分はすでに受け取ってます\n"));
				}
				break;
			case 3:
				$player->sendForm(new DailyChangeForm($this->pd));
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
		$msg1=$this->pd->getDairy1();
		$msg2=$this->pd->getDairy2();
		$msg3=$this->pd->getDairy3();
		$dai1=$this->pd->getMaxDairy1();
		$dai2=$this->pd->getMaxDairy2();
		$dai3=$this->pd->getMAXDairy3();
		$now1=$this->pd->getVar("DAIRYTASK1");
		$now2=$this->pd->getVar("DAIRYTASK2");
		$now3=$this->pd->getVar("DAIRYTASK3");


		return [
			"type" => "form",
			"title" => "§dデイリーミッション",
			"content" => $this->content,
			"buttons" => [
				[
					'text' => $msg1."\n".$now1."/".$dai1,
				],
				[
					'text' => $msg2."\n".$now2."/".$dai2,
				],
				[
					'text' => $msg3."\n".$now3."/".$dai3,
				],
				[
					'text'=> "チケット交換所へ",
				],
			],
		];
	}
}