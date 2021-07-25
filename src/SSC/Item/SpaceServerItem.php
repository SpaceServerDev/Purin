<?php


namespace SSC\Item;


use pocketmine\item\Item;

interface SpaceServerItem {

	public static function get(int $amount=1):Item;

}