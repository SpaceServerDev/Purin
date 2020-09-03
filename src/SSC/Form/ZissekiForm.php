<?php

namespace SSC\Form;

use SSC\PlayerEvent;
use pocketmine\form\Form;
use pocketmine\Player;

class ZissekiForm implements Form{

	/**
	 * @var $playerdata PlayerEvent
	 */
	private $pd;

	const GOLD="textures/blocks/gold_block.png";
	const SILVER="textures/blocks/iron_block.png";
	const NORMAL="textures/blocks/glass.png";

	public function __construct(PlayerEvent $playerdata) {
		$this->pd=$playerdata;
	}

	/**
	 * Handles a form response from a player.
	 *
	 * @param Player $player
	 * @param mixed $data
	 *
	 * @throws \pocketmine\form\FormValidationException if the data could not be processed
	 */
	public function handleResponse(Player $player, $data): void {
		if(!is_numeric($data)){
			return;
		}
		$player->getServer()->dispatchCommand($player,"zisseki");
	}

	/**
	 * Specify data which should be serialized to JSON
	 * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 * @since 5.4.0
	 */
	public function jsonSerialize() {
		/**
		 * @var $pd PlayerEvent
		 */
		list($staynow, $stayimage, $stayg) = $this->isStay();
		$staynowh = floor($staynow / 3600);
		$staynowm = floor(($staynow / 60) % 60);
		$stayg = $stayg / 60 / 60;
		list($daynow, $dayimage, $dayg) = $this->isDay();
		list($breaknow, $breakimage, $breakg) = $this->isBreak();
		list($peacenow, $peaceimage, $peaceg) = $this->isPeace();
		list($trappistnow, $trappistimage, $trappistg) = $this->isTrappist();
		list($walknow, $walkimage, $walkg) = $this->isWalk();
		list($flowernow, $flowerimage, $flowerg) = $this->isFlower();
		list($orenow, $oreimage, $oreg) = $this->isOre();
		list($woodnow, $woodimage, $woodg) = $this->isWood();
		list($coalnow, $coalimage, $coalg) = $this->isCoal();
		list($lapisnow, $lapisimage, $lapisg) = $this->isLapis();
		list($ironnow, $ironimage, $irong) = $this->isIron();
		list($goldnow, $goldimage, $goldg) = $this->isGold();
		list($redstonenow, $redstoneimage, $redstoneg) = $this->isRedstone();
		list($diamondnow, $diamondimage, $diamondg) = $this->isDiamond();
		list($emeraldnow, $emeraldimage, $emeraldg) = $this->isEmerald();
		list($gachanow, $gachaimage, $gachag) = $this->isGacha();
		list($shopnow, $shopimage, $shopg) = $this->isShopping();
		list($slotnow, $slotimage, $slotg) = $this->isSlot();
		list($killnow, $killimage, $killg) = $this->isKill();
		list($repeatnow, $repeatimage, $repeatg) = $this->isRepeat();
		list($killstnow, $killstimage, $killstg) = $this->isKillst();
		list($fishnow, $fishimage, $fishg) = $this->isFishing();
		return [
			"type" => "form",
			"title" => "§d実績",
			"content" => "",
			"buttons" => [
				[
					'text' => "サーバーログイン日数\n" . $daynow . "日/" . $dayg . "日",
					'image' => [
						'type' => 'path',
						'data' => $dayimage,
					],
				],
				[
					'text' => "サーバー滞在時間\n" . $staynowh . "時間" . $staynowm . "分/" . $stayg . "時間",
					'image' => [
						'type' => 'path',
						'data' => $stayimage,
					],
				],
				[
					'text' => "連続ログイン回数\n" . $repeatnow . "日/" . $repeatg . "日",
					'image' => [
						'type' => 'path',
						'data' => $repeatimage,
					]
				],
				[
					'text' => "ブロック破壊数\n" . $breaknow . "個/" . $breakg . "個",
					'image' => [
						'type' => 'path',
						'data' => $breakimage,
					],
				],
				[
					'text' => "ブロック設置数\n" . $peacenow . "個/" . $peaceg . "個",
					'image' => [
						'type' => 'path',
						'data' => $peaceimage,
					],
				],
				[
					'text' => "トラピストでの整地数\n" . $trappistnow . "個/" . $trappistg . "個",
					'image' => [
						'type' => 'path',
						'data' => $trappistimage,
					],

				],
				[
					'text' => "歩数\n" . $walknow . "歩/" . $walkg . "歩",
					'image' => [
						'type' => 'path',
						'data' => $walkimage,
					],
				],
				[
					'text' => "花を植えた数\n" . $flowernow . "輪/" . $flowerg . "輪",
					'image' => [
						'type' => 'path',
						'data' => $flowerimage,
					],
				],
				[
					'text' => "鉱石破壊数\n" . $orenow . "個/" . $oreg . "個",
					'image' => [
						'type' => 'path',
						'data' => $oreimage,
					],
				],
				[
					'text' => "原木破壊数\n" . $woodnow . "個/" . $woodg . "個",
					'image' => [
						'type' => 'path',
						'data' => $woodimage,
					],
				],
				[
					'text' => "石炭:原石破壊数\n" . $coalnow . "個/" . $coalg . "個",
					'image' => [
						'type' => 'path',
						'data' => $coalimage,
					],
				],
				[
					'text' => "ラピス:原石破壊数\n" . $lapisnow . "個/" . $lapisg . "個",
					'image' => [
						'type' => 'path',
						'data' => $lapisimage,
					],
				],
				[
					'text' => "鉄:原石破壊数\n" . $ironnow . "個/" . $irong . "個",
					'image' => [
						'type' => 'path',
						'data' => $ironimage,
					],
				],
				[
					'text' => "金:原石破壊数\n" . $goldnow . "個/" . $goldg . "個",
					'image' => [
						'type' => 'path',
						'data' => $goldimage,
					],
				],
				[
					'text' => "赤石:原石破壊数\n" . $redstonenow . "個/" . $redstoneg . "個",
					'image' => [
						'type' => 'path',
						'data' => $redstoneimage,
					],
				],
				[
					'text' => "ダイヤ:原石破壊数\n" . $diamondnow . "個/" . $diamondg . "個",
					'image' => [
						'type' => 'path',
						'data' => $diamondimage,
					],
				],
				[
					'text' => "エメラルド:原石破壊数\n" . $emeraldnow . "個/" . $emeraldg . "個",
					'image' => [
						'type' => 'path',
						'data' => $emeraldimage,
					],
				],
				[
					'text' => "ガチャ引いた回数\n" . $gachanow . "回/" . $gachag . "回",
					'image' => [
						'type' => 'path',
						'data' => $gachaimage,
					],
				],
				[
					'text' => "買い物した回数\n" . $shopnow . "回/" . $shopg . "回",
					'image' => [
						'type' => 'path',
						'data' => $shopimage,
					],
				],
				[
					'text' => "スロットした回数\n" . $slotnow . "回/" . $slotg . "回",
					'image' => [
						'type' => 'path',
						'data' => $slotimage,
					],
				],
				[
					'text' => "キルした回数\n" . $killnow . "人/" . $killg . "人",
					'image' => [
						'type' => 'path',
						'data' => $killimage,
					],
				],
				[
					'text' => "キルストリーク回数\n" . $killstnow . "人/" . $killstg . "人",
					'image' => [
						'type' => 'path',
						'data' => $killstimage,
					],
				],
				[
					'text' => "釣りをした回数\n" . $fishnow . "人/" . $fishg . "人",
					'image' => [
						'type' => 'path',
						'data' => $fishimage,
					],
				],
			]
		];
	}

	private function isStay(){
		if($this->pd->getStayTime()>=1800000){
			return array ($this->pd->getStayTime(),self::GOLD,1800000);
		}elseif($this->pd->getStayTime()>=360000){
			return array ($this->pd->getStayTime(),self::SILVER,1800000);
		}else{
			return array ($this->pd->getStayTime(),self::NORMAL,360000);
		}
	}

	private function isDay(){
		if($this->pd->getVar("DAY")>=31){
			return array ($this->pd->getVar("DAY"),self::GOLD,31);
		}elseif($this->pd->getVar("DAY")>=10){
			return array ($this->pd->getVar("DAY"),self::SILVER,31);
		}else{
			return array ($this->pd->getVar("DAY"),self::NORMAL,10);
		}
	}

	private function isBreak(){
		if($this->pd->getVar("BREAK")>=1000000){
			return array ($this->pd->getVar("BREAK"),self::GOLD,1000000);
		}elseif($this->pd->getVar("BREAK")>=100000){
			return array ($this->pd->getVar("BREAK"),self::SILVER,1000000);
		}else{
			return array ($this->pd->getVar("BREAK"),self::NORMAL,100000);
		}
	}

	private function isPeace(){
		if($this->pd->getVar("PEACE")>=250000){
			return array ($this->pd->getVar("PEACE"),self::GOLD,250000);
		}elseif($this->pd->getVar("PEACE")>=50000){
			return array ($this->pd->getVar("PEACE"),self::SILVER,250000);
		}else{
			return array ($this->pd->getVar("PEACE"),self::NORMAL,50000);
		}
	}

	private function isTrappist(){
		if($this->pd->getVar("TRAPPIST")>=250000){
			return array ($this->pd->getVar("TRAPPIST"),self::GOLD,250000);
		}elseif($this->pd->getVar("TRAPPIST")>=50000){
			return array ($this->pd->getVar("TRAPPIST"),self::SILVER,250000);
		}else{
			return array ($this->pd->getVar("TRAPPIST"),self::NORMAL,50000);
		}
	}

	private function isWalk(){
		if($this->pd->getVar("WALK")>=1000000){
			return array ($this->pd->getVar("WALK"),self::GOLD,1000000);
		}elseif($this->pd->getVar("WALK")>=100000){
			return array ($this->pd->getVar("WALK"),self::SILVER,1000000);
		}else{
			return array ($this->pd->getVar("WALK"),self::NORMAL,100000);
		}
	}

	private function isFlower(){
		if($this->pd->getVar("FLOWER")>=100){
			return array ($this->pd->getVar("FLOWER"),self::GOLD,100);
		}elseif($this->pd->getVar("FLOWER")>=25){
			return array ($this->pd->getVar("FLOWER"),self::SILVER,100);
		}else{
			return array ($this->pd->getVar("FLOWER"),self::NORMAL,25);
		}
	}

	private function isWood(){
		if($this->pd->getVar("WOOD")>=1000){
			return array ($this->pd->getVar("WOOD"),self::GOLD,1000);
		}elseif($this->pd->getVar("WOOD")>=500){
			return array ($this->pd->getVar("WOOD"),self::SILVER,1000);
		}else{
			return array ($this->pd->getVar("WOOD"),self::NORMAL,500);
		}
	}

	private function isOre(){
		$ores=$this->pd->getVar("COAL")+$this->pd->getVar("LAPIS")+$this->pd->getVar("IRON")+$this->pd->getVar("REDSTONE")+$this->pd->getVar("GOLD")+$this->pd->getVar("DIAMOND")+$this->pd->getVar("EMERALD");
		if($ores>=50000){
			return array ($ores,self::GOLD,50000);
		}elseif($ores>=10000){
			return array ($ores,self::SILVER,50000);
		}else{
			return array ($ores,self::NORMAL,10000);
		}
	}

	private function isCoal(){
		if($this->pd->getVar("COAL")>=5000){
			return array ($this->pd->getVar("COAL"),self::GOLD,5000);
		}elseif($this->pd->getVar("COAL")>=1000){
			return array ($this->pd->getVar("COAL"),self::SILVER,5000);
		}else{
			return array ($this->pd->getVar("COAL"),self::NORMAL,1000);
		}
	}

	private function isLapis(){
		if($this->pd->getVar("LAPIS")>=500){
			return array ($this->pd->getVar("LAPIS"),self::GOLD,500);
		}elseif($this->pd->getVar("LAPIS")>=50){
			return array ($this->pd->getVar("LAPIS"),self::SILVER,500);
		}else{
			return array ($this->pd->getVar("LAPIS"),self::NORMAL,50);
		}
	}

	private function isIron(){
		if($this->pd->getVar("IRON")>=1000){
			return array ($this->pd->getVar("IRON"),self::GOLD,1000);
		}elseif($this->pd->getVar("IRON")>=500){
			return array ($this->pd->getVar("IRON"),self::SILVER,1000);
		}else{
			return array ($this->pd->getVar("IRON"),self::NORMAL,500);
		}
	}

	private function isRedstone(){
		if($this->pd->getVar("REDSTONE")>=7000){
			return array ($this->pd->getVar("REDSTONE"),self::GOLD,7000);
		}elseif($this->pd->getVar("REDSTONE")>=1000){
			return array ($this->pd->getVar("REDSTONE"),self::SILVER,7000);
		}else{
			return array ($this->pd->getVar("REDSTONE"),self::NORMAL,1000);
		}
	}

	private function isGold(){
		if($this->pd->getVar("GOLD")>=500){
			return array ($this->pd->getVar("GOLD"),self::GOLD,500);
		}elseif($this->pd->getVar("GOLD")>=100){
			return array ($this->pd->getVar("GOLD"),self::SILVER,500);
		}else{
			return array ($this->pd->getVar("GOLD"),self::NORMAL,100);
		}
	}

	private function isDiamond(){
		if($this->pd->getVar("DIAMOND")>=250){
			return array ($this->pd->getVar("DIAMOND"),self::GOLD,250);
		}elseif($this->pd->getVar("DIAMOND")>=50){
			return array ($this->pd->getVar("DIAMOND"),self::SILVER,250);
		}else{
			return array ($this->pd->getVar("DIAMOND"),self::NORMAL,50);
		}
	}

	private function isEmerald(){
		if($this->pd->getVar("EMERALD")>=50){
			return array ($this->pd->getVar("EMERALD"),self::GOLD,50);
		}elseif($this->pd->getVar("EMERALD")>=10){
			return array ($this->pd->getVar("EMERALD"),self::SILVER,50);
		}else{
			return array ($this->pd->getVar("EMERALD"),self::NORMAL,10);
		}
	}

	private function isGacha(){
		if($this->pd->getVar("GACHA")>=250){
			return array ($this->pd->getVar("GACHA"),self::GOLD,250);
		}elseif($this->pd->getVar("GACHA")>=50){
			return array ($this->pd->getVar("GACHA"),self::SILVER,250);
		}else{
			return array ($this->pd->getVar("GACHA"),self::NORMAL,50);
		}
	}

	private function isShopping(){
		if($this->pd->getVar("SHOPPING")>=250){
			return array ($this->pd->getVar("SHOPPING"),self::GOLD,250);
		}elseif($this->pd->getVar("SHOPPING")>=100){
			return array ($this->pd->getVar("SHOPPING"),self::SILVER,250);
		}else{
			return array ($this->pd->getVar("SHOPPING"),self::NORMAL,100);
		}
	}

	private function isKill(){
		if($this->pd->getVar("KILL")>=1000){
			return array ($this->pd->getVar("KILL"),self::GOLD,1000);
		}elseif($this->pd->getVar("KILL")>=250){
			return array ($this->pd->getVar("KILL"),self::SILVER,1000);
		}else{
			return array ($this->pd->getVar("KILL"),self::NORMAL,250);
		}
	}

	private function isSlot(){
		if($this->pd->getVar("SLOT")>=100){
			return array ($this->pd->getVar("SLOT"),self::GOLD,100);
		}elseif($this->pd->getVar("SLOT")>=50){
			return array ($this->pd->getVar("SLOT"),self::SILVER,100);
		}else{
			return array ($this->pd->getVar("SLOT"),self::NORMAL,50);
		}
	}

	private function isRepeat(){
		if($this->pd->getVar("MAXREPEAT")>=31){
			return array ($this->pd->getVar("MAXREPEAT"),self::GOLD,31);
		}elseif($this->pd->getVar("MAXREPEAT")>=10){
			return array ($this->pd->getVar("MAXREPEAT"),self::SILVER,31);
		}else{
			return array ($this->pd->getVar("MAXREPEAT"),self::NORMAL,10);
		}
	}

	private function isKillst(){
		if($this->pd->getVar("MAXKILLSTREAK")>=5){
			return array ($this->pd->getVar("MAXKILLSTREAK"),self::GOLD,5);
		}elseif($this->pd->getVar("MAXKILLSTREAK")>=3){
			return array ($this->pd->getVar("MAXKILLSTREAK"),self::SILVER,5);
		}else{
			return array ($this->pd->getVar("MAXKILLSTREAK"),self::NORMAL,3);
		}
	}

	private function isFishing(){
		if($this->pd->getFishing()>=100){
			return array ($this->pd->getFishing(),self::GOLD,100);
		}elseif($this->pd->getFishing()>=30){
			return array ($this->pd->getFishing(),self::SILVER,100);
		}else{
			return array ($this->pd->getFishing(),self::NORMAL,30);
		}
	}
}