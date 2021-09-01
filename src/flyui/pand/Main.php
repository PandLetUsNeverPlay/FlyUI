<?php

namespace flyui\pand;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use jojoe77777\FormAPI\SimpleForm;

class Main extends PluginBase {

	public function onEnable() {
		@mkdir($this->getDataFolder());
		$this->saveResource("config.yml");
		$this->saveDefaultConfig();
		$this->getLogger()->info(TextFormat::GREEN . "FlyUI was enabled!");
	}
	
	public function onDisable() {
		$this->getLogger()->info(TextFormat::RED . "FlyUI was disabled!");
	}
	
	public function onCommand(CommandSender $s, Command $cmd, string $label, array $args) : bool {
		switch($cmd->getName()) {
		
			case "fly":
				if($s instanceof Player) {
					if($s->hasPermission("flyui.open")) {
						$this->flyui($s);
					} else {
						$s->sendMessage($this->getConfig()->get("no-permission"));
					}
				} else {
					$s->sendMessage(TextFormat::RED . "Use this command In-Game!");
				}
				break;
		}
	return true;
	}
	
	public function flyui(Player $p) {
		$form = new SimpleForm(function (Player $p, int $data = null) {
			if($data === null) {
				return false;
			}
			if($data == 0) {
				$p->setFlying(true);
				$p->setAllowFlight(true);
				$p->sendMessage($this->getConfig()->get("fly-enabled"));
			}
			if($data == 1) {
				$p->setFlying(false);
				$p->setAllowFlight(false);
				$p->sendMessage($this->getConfig()->get("fly-disabled"));
			}
		});
		$form->setTitle($this->getConfig()->get("title"));
		$form->setContent($this->getConfig()->get("content"));
		$form->addButton($this->getConfig()->get("enable-button"));
		$form->addButton($this->getConfig()->get("disable-button"));
		$p->sendForm($form);
		return $form;
	}
}