<?php


namespace SSC\Form\SlotForm;


use onebone\economyapi\EconomyAPI;
use pocketmine\form\Form;
use pocketmine\form\FormValidationException;
use pocketmine\Player;
use SSC\main;

class RaffleSlotForm implements Form {

	/**
	 * Handles a form response from a player.
	 *
	 * @param mixed $data
	 *
	 * @throws FormValidationException if the data could not be processed
	 */
	public function handleResponse(Player $player, $data): void {
		if (!is_numeric($data)) {
			return;
		}
		EconomyAPI::getInstance()->reduceMoney($player->getName(), 500);
		$gm = 0;
		$a = mt_rand(1, 6);
		$b = mt_rand(1, 7);
		$c = mt_rand(1, 6);
		$d = mt_rand(1, 7);
		$e = mt_rand(1, 6);
		$f = mt_rand(1, 7);
		$g = mt_rand(1, 6);
		$h = mt_rand(1, 6);
		$i = mt_rand(1, 6);
		if ($a == $b && $b == $c) {
			$gm = $gm + 1500;
		}
		if ($d == $e && $e == $f) {
			$gm = $gm + 1500;
		}
		if ($g == $h && $h == $i) {
			$gm = $gm + 1500;
		}
		if ($a == $e && $e == $i) {
			$gm = $gm + 1500;
		}
		if ($c == $e && $e == $g) {
			$gm = $gm + 1500;
		}
		if ($a == $d && $d == $g) {
			$gm = $gm + 1500;
		}
		if ($b == $e && $e == $h) {
			$gm = $gm + 1500;
		}
		if ($c == $f && $f == $i) {
			$gm = $gm + 1500;
		}
		if ($gm === 0) {
			$msg = "ハズレ...";
		} else {
			$msg = "§aあたり！{$gm}円ゲット！§e";
			EconomyAPI::getInstance()->addMoney($player->getName(), $gm);
		}
		main::getPlayerData($player->getName())->addVar("SLOT");
		$player->sendForm(new EndSlotForm($msg,$gm));
	}

	/**
	 * Specify data which should be serialized to JSON
	 * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 * @since 5.4.0
	 */
	public function jsonSerialize() {
		$buttons[] = [
			'text' => "ストップ"
		];
		return [
			'type' => 'form',
			'title' => 'slot',
			'content' => "\n\n\n             [ §ka§r ] [ §kb§r ] [ §kc§r ] \n             [ §kd§r ] [ §ke§r ] [ §kf§r ] \n             [ §kg§r ] [ §kh§r ] [ §kf§r ] \n\n",
			'buttons' => $buttons
		];
	}
}