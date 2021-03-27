<?php


namespace SSC\Form\SlotForm;


use onebone\economyapi\EconomyAPI;
use pocketmine\form\Form;
use pocketmine\form\FormValidationException;
use pocketmine\Player;
use SSC\main;

class SlotForm implements Form {

	private $money;

	public function __construct($money) {
		$this->money=$money;
	}

	/**
	 * Handles a form response from a player.
	 *
	 * @param mixed $data
	 *
	 * @throws FormValidationException if the data could not be processed
	 */
	public function handleResponse(Player $player, $data): void {
		if(!is_numeric($data)){
			return;
		}
		if(EconomyAPI::getInstance()->myMoney($player->getName())<120){
			$player->sendMessage("お金が足りません");
			return;
		}
		$player->sendForm(new LotterySlotForm());
	}

	/**
	 * Specify data which should be serialized to JSON
	 * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 * @since 5.4.0
	 */
	public function jsonSerialize() {
		$red7=Emoji::Red7;
		$blue7=Emoji::Blue7;
		$bar=Emoji::Bar;
		$replay=Emoji::Replay;
		$star=Emoji::Star;
		$water=Emoji::WaterMelon;
		$cherry=Emoji::Cherry;
		$buttons[] = [
			'text' => "コインを入れてレバーを叩く",
		];
		return [
			"type" => "form",
			"title" => "SLOT GOGO!NEWSPACE!",
			"content" => "スロットを引けます!\n一度フォームを閉じると台が変わっちゃいます。\n1枚40円\n3枚がけ（1回120円）\n§e現在のお金 : {$this->money}￥\n\n\n役\n異色BIG {$blue7}{$red7}{$blue7} 約525枚\nBIG {$red7}{$red7}{$red7}\n    {$blue7}{$blue7}{$blue7}\n    {$bar}{$bar}{$bar} 約375枚\nREG {$red7}{$red7}{$bar} 約225枚\nREPLAY {$replay}{$replay}{$replay} ボーナス重複あり\nSTAR {$star}{$star}{$star} 3枚役 ボーナス重複あり\nMelon(Meat) {$water}{$water}{$water} 8枚役\nCherry(coin) {$cherry}[ANY][ANY] 4枚役 ボーナス重複あり\n15枚役 {$red7}{$cherry}{$cherry}",
			"buttons" => $buttons
		];
	}
}

class LotterySlotForm implements Form {

	/**
	 * @var int
	 */
	private $allCount;
	private $count;
	private $earning;

	/**
	 * @var bool
	 */
	private $replay;

	/**
	 * @var bool
	 */
	private $bonus;


	public function __construct(int $allCount=0, int $count=0, int $earning=0,bool $replay=false,$bonus=false) {
		$this->allCount=$allCount;
		$this->count=$count;
		$this->earning=$earning;
		$this->replay=$replay;
		$this->bonus=$bonus;
	}

	public function handleResponse(Player $player, $data): void {
		if (!is_numeric($data)) {
			return;
		}
		$earning=$this->earning;
		if(!$this->replay) {
			if (EconomyAPI::getInstance()->myMoney($player->getName()) < 120) {
				$player->sendMessage("お金が足りません");
				return;
			}
			EconomyAPI::getInstance()->reduceMoney($player->getName(), 120);
			$earning-=3;
		}
		$allCount=$this->allCount+1;
		$count=$this->count+1;
		main::getPlayerData($player->getName())->addVar("SLOT");


		switch ($actor=$this->actor()){
			case 0:
				$player->sendForm(new LostForm($allCount,$count,$earning));
			return;
			default:
				$player->sendForm(new ActorForm($actor,$allCount,$count,$earning));
			return;
		}
	}

	public function jsonSerialize() {
		$this->replay?$replay="REPLAY\n":$replay="\n";
		$this->bonus?$bonus="§aG§bO§cG§dO§e!BONUS!?§f\n":$bonus="\n";
		$buttons[] = [
			'text' => "ストップ"
		];
		return [
			'type' => 'form',
			'title' => 'SLOT GOGO!NEWSPACE!',
			'content' => "{$replay}{$bonus}coin:{$this->earning}\n\n               [ §kd§r ] [ §ke§r ] [ §kf§r ] \n\ncount:{$this->count}\nAllCount:{$this->allCount}",
			'buttons' => $buttons
		];
	}

	private function actor():int{
		$this->bonus?$counter=mt_rand(0,373):$counter=mt_rand(0,65535);
		switch ($counter){
			case $counter>0&&$counter<=3:
				// 1/8192 異色big
				return 1;
			case $counter>3&&$counter<=229:
				// 1/287.4 big
				return 1+mt_rand(1,3);
				//2 赤big
				//3 青big
				//4 白big
				break;
			case $counter>229&&$counter<=373:
				// 1/455.1 reg
				return 5;
			case $counter>373&&$counter<=9350:
				// 1/7.1replay
				return 6;
			case $counter>9350&&$counter<=15903:
				// 1/10 9枚役
				return 7;
			case $counter>20338&&$counter<=20398:
				//星 1/1092
				return 8;
			case $counter>20338&&$counter<=22350:
				//スイカ 1/33.5
				return 9;
			case $counter>22350&&$counter<=24302:
				//チェリー 1/33.5
				return 10;
			case $counter>24302&&$counter<=24362:
				//スイカハズレ 1/1092
				return 11;
		}
		//ハズレ
		return 0;
	}
}

class BonusForm implements Form{

	/**
	 * @var int
	 */
	private $BonusCount;

	/**
	 * @var int
	 */
	private $allCount;

	/**
	 * @var int
	 */
	private $earning;

	/**
	 * @var int
	 */
	private $MaxBonusCount;
	/**
	 * @var string
	 */
	private $bonusMode;


	public function __construct(int $BonusCount,int $MaxBonusCount,string $bonusMode, int $allCount, int $earning) {
		$this->BonusCount=$BonusCount;
		$this->MaxBonusCount=$MaxBonusCount;
		$this->bonusMode=$bonusMode;
		$this->allCount=$allCount;
		$this->earning=$earning;
	}

	public function handleResponse(Player $player, $data): void {
		if (!is_numeric($data)) {
			return;
		}
		if (EconomyAPI::getInstance()->myMoney($player->getName()) < 120) {
			$player->sendMessage("お金が足りません");
			return;
		}
		EconomyAPI::getInstance()->reduceMoney($player->getName(), 120);
		$earning = $this->earning - 3;

		$this->BonusCount === $this->MaxBonusCount ? $last = true : $last = false;
		$player->sendForm(new BonusStopForm($this->bonusMode, $this->BonusCount, $this->MaxBonusCount, $this->allCount, $earning, $last));
	}

	public function jsonSerialize() {
		$buttons[] = [
			'text' => "ストップ"
		];
		return [
			'type' => 'form',
			'title' => 'SLOT GOGO!NEWSPACE!',
			'content' => "{$this->bonusMode}\n{$this->BonusCount}/{$this->MaxBonusCount}\ncount:{$this->earning}-3\n\n               [ §kd§r ] [ §ke§r ] [ §kf§r ] \n\ncount:0\nAllCount:{$this->allCount}",
			'buttons' => $buttons
		];
	}
}

class BonusStopForm implements Form{

	/**
	 * @var String
	 */
	private $bonus;
	/**
	 * @var int
	 */
	private $BonusCount;
	/**
	 * @var int
	 */
	private $allCount;
	/**
	 * @var int
	 */
	private $earning;
	/**
	 * @var bool
	 */
	private $last;
	/**
	 * @var int
	 */
	private $MaxBonusCount;



	public function __construct(String $bonus, int $BonusCount, int $MaxBonusCount,int $allCount, int $earning, bool $last) {
		$this->bonus=$bonus;
		$this->BonusCount=$BonusCount;
		$this->allCount=$allCount;
		$this->MaxBonusCount=$MaxBonusCount;
		$this->earning=$earning;
		$this->last=$last;
	}

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
		$earning=$this->earning+15;
		EconomyAPI::getInstance()->addMoney($player->getName(), 600);
		$allcount=$this->allCount+1;
		$count=$this->BonusCount+1;
		if($this->last){
			$player->sendForm(new LotterySlotForm($this->allCount,0,$this->earning));
			return;
		}
		$player->sendForm(new BonusForm($count,$this->MaxBonusCount,$this->bonus,$allcount,$earning));
	}

	/**
	 * Specify data which should be serialized to JSON
	 * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 * @since 5.4.0
	 */
	public function jsonSerialize() {
		$red7=Emoji::Red7;
		$cherry=Emoji::Cherry;
		$this->last?$last="LAST! ":$last="";
		$buttons[] = [
			'text' => "レバーを押す"
		];
		return [
			'type' => 'form',
			'title' => 'SLOT GOGO!NEWSPACE!',
			'content' => "{$last}{$this->bonus}\n{$this->BonusCount}/{$this->MaxBonusCount}\ncount:{$this->earning}+15\n\n               [ $red7 ] [ $cherry ] [ $cherry ] \n\ncount:0\nAllCount:{$this->allCount}",
			'buttons' => $buttons
		];
	}
}

class ActorForm implements Form{

	/**
	 * @var int
	 */
	private $actor;
	private $allCount;
	private $count;
	private $earning;


	public function __construct(int $actor, int $allCount, int $count, int $earning) {
		$this->actor=$actor;
		$this->allCount=$allCount;
		$this->count=$count;
		$this->earning=$earning;
	}

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
		switch ($this->actor){
			case $this->actor===1:
				$player->sendForm(new BonusForm(1,35,"§b!!§cBIGBONUS§b!!§f",$this->allCount,$this->earning));
				return;
			case $this->actor>1&&$this->actor<=4:
				$player->sendForm(new BonusForm(1,25,"§a!§bB§cI§dG§eBONUS§a!§f",$this->allCount,$this->earning));
				return;
			case $this->actor===5:
				$player->sendForm(new BonusForm(1,15,"§cREGULARBONUS§f",$this->allCount,$this->earning));
				return;
			case $this->actor===6:
				mt_rand(0,120)===30?$bool=true:$bool=false;
				$player->sendForm(new LotterySlotForm($this->allCount,$this->count,$this->earning,true,$bool));
				return;
			case $this->actor===7:
				$player->sendForm(new LotterySlotForm($this->allCount,$this->count,$this->earning+9));
				EconomyAPI::getInstance()->addMoney($player->getName(), 360);
				return;
			case $this->actor===8:
				$player->sendForm(new LotterySlotForm($this->allCount,$this->count,$this->earning+3,false,true));
				EconomyAPI::getInstance()->addMoney($player->getName(), 120);
				return;
			case $this->actor===9:
				$player->sendForm(new LotterySlotForm($this->allCount,$this->count,$this->earning+8));
				EconomyAPI::getInstance()->addMoney($player->getName(), 320);
				return;
			case $this->actor===10:
				mt_rand(0,90)===30?$bool=true:$bool=false;
				$player->sendForm(new LotterySlotForm($this->allCount,$this->count,$this->earning+4,false,$bool));
				EconomyAPI::getInstance()->addMoney($player->getName(), 160);
				return;
			case $this->actor===11:
				$player->sendForm(new LotterySlotForm($this->allCount,$this->count,$this->earning,false,true));
				return;
		}
	}


	public function jsonSerialize() {
		$red7=Emoji::Red7;
		$blue7=Emoji::Blue7;
		$bar=Emoji::Bar;
		$replay=Emoji::Replay;
		$star=Emoji::Star;
		$water=Emoji::WaterMelon;
		$cherry=Emoji::Cherry;
		$all=[$red7,$blue7,$bar,$star,$water,$cherry];
		$bonus=[$red7,$blue7,$bar];
		$nomelon=[$red7,$blue7,$bar,$star,$cherry];
		switch ($this->actor){
			case $this->actor===1:
				$message="異色BIGBONUS!!!!!!!";
				$actor="[ {$blue7}] [ {$red7} ] [ {$blue7} ]";
				break;
			case $this->actor===2:
				$message="赤BIGBONUS!!";
				$actor="[ {$red7} ] [ {$red7} ] [ {$red7} ]";
				break;
			case $this->actor===3:
				$message="青BIGBONUS!!";
				$actor="[ {$blue7} ] [ {$blue7} ] [ {$blue7} ]";
				break;
			case $this->actor===4:
				$message="白BIGBONUS!!";
				$actor="[ {$bar} ] [ {$bar} ] [ {$bar} ]";
				break;
			case $this->actor===5:
				$message="REGULARBONUS!!";
				$actor="[ {$red7} ] [ {$red7} ] [ {$bar} ]";
				break;
			case $this->actor===6:
				$message="リプレイ";
				$actor="[ {$replay} ] [ {$replay} ] [ {$replay} ]";
				break;
			case $this->actor===7:
				$message="9枚役";
				$actor="[ {$bonus[mt_rand(0,2)]} ] [ {$star} ] [ {$star} ]";
				break;
			case $this->actor===8:
				$message="☆ 3枚役";
				$actor="[ {$star} ] [ {$star} ] [ {$star} ]";
				break;
			case $this->actor===9:
				$message="肉 8枚役";
				$actor="[ {$water} ] [ {$water} ] [ {$water} ]";
				break;
			case $this->actor===10:
				$message="コイン 4枚役";
				$actor="[ {$cherry} ] [ {$all[mt_rand(0,5)]} ] [ {$all[mt_rand(0,5)]} ]";
				break;
			case $this->actor===11:
				$message="ハズレ...";
				$actor="[ {$water} ] [ {$nomelon[mt_rand(0,4)]} ] [ {$water} ]";
				break;
		}
		$buttons[] = [
			'text' => "レバーを叩く"
		];
		return [
			'type' => 'form',
			'title' => 'SLOT GOGO!NEWSPACE!',
			'content' => "{$message}\n\ncoin:{$this->earning}\n\n               $actor \n\ncount:{$this->count}\nAllCount:{$this->allCount}",
			'buttons' => $buttons
		];
	}
}

class LostForm implements Form{

	private $allCount;
	private $count;
	private $earning;

	public function __construct(int $allCount, int $count, int $earning) {
		$this->allCount=$allCount;
		$this->count=$count;
		$this->earning=$earning;
	}

	public function handleResponse(Player $player, $data): void {
		if (!is_numeric($data)) {
			return;
		}
		$player->sendForm(new LotterySlotForm($this->allCount,$this->count,$this->earning));
	}

	public function jsonSerialize() {
		$bar=Emoji::Bar;
		$replay=Emoji::Replay;
		$star=Emoji::Star;
		$water=Emoji::WaterMelon;
		$cherry=Emoji::Cherry;
		$small=[$bar,$star,$water,$cherry];
		$buttons[] = [
			'text' => "レバーを叩く"
		];
		return [
			'type' => 'form',
			'title' => 'SLOT GOGO!NEWSPACE!',
			'content' => "ハズレ\n\ncoin:{$this->earning}\n\n               [ {$replay}§r ] [ {$small[mt_rand(0,3)]}§r ] [ {$replay}§r ] \n\ncount:{$this->count}\nAllCount:{$this->allCount}",
			'buttons' => $buttons
		];
	}
}

class Emoji{
	const Red7="§c7§f";
	const Blue7="§b7§f";
	const Bar="§f－§f";
	const Replay="";
	const Star="§e☆§f";
	const WaterMelon="";
	const Cherry="";
}