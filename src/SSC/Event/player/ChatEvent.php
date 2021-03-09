<?php


namespace SSC\Event\player;


use bboyyu51\pmdiscord\Sender;
use bboyyu51\pmdiscord\structure\Content;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use SSC\Form\LoginForm\CheckPasswordForm;
use SSC\Form\LoginForm\RegisterForm;
use SSC\Form\LoginForm\ReloginForm;
use SSC\main;

class ChatEvent implements Listener {

	public function onPCE(PlayerChatEvent $event) {
		 $player = $event->getPlayer();
		 $name = $player->getName();
		 main::getMain()->playerlist->reload();
		 if (!main::getMain()->playerlist->exists($name)) {
			 $player->sendForm(new CheckPasswordForm());
			 $player->setImmobile(true);
			 $event->setCancelled();
		 }
		 $msg=$event->getMessage();
		 $message=str_replace("@","あっとまあく ",$msg);
		 $message_t=str_replace("＠","あっとまあく ",$message);
	 	 $webhook = Sender::create("https://discordapp.com/api/webhooks/670915313071816704/5CWGaxC5YLlsNHWGygFwobs7DS3T3PPWWvvJN3VIxlF3UkqhkmhJpWomdhBDXiNVnRB0");
		 $content = new Content();
		 $content->setText("[".date("G:i:s")."]".$message_t);
		 $webhook->add($content);
		 $webhook->setCustomName($name);
		 Sender::sendAsync($webhook);
	 }


}