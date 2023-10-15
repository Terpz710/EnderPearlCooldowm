<?php

namespace Terpz710\EnderPearlCoolDown;

use pocketmine\event\Listener;
use pocketmine\event\entity\ProjectileLaunchEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\player\Player;

class Main extends PluginBase implements Listener {
    private $cooldowns = [];

    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onProjectileLaunch(ProjectileLaunchEvent $event) {
        $projectile = $event->getEntity();
        if ($projectile instanceof \pocketmine\entity\projectile\EnderPearl) {
            $player = $projectile->getOwningEntity();
            if ($player !== null) {
                $playerName = $player->getName();
                if (!isset($this->cooldowns[$playerName]) || $this->cooldowns[$playerName] <= microtime(true)) {
                    $this->cooldowns[$playerName] = microtime(true) + 10;
                } else {
                    $remainingTime = ceil($this->cooldowns[$playerName] - microtime(true));
                    $player->sendMessage("§eYou must wait §c{$remainingTime}§e seconds before using another ender pearl.");
                    $projectile->kill();
                    $event->cancel();
                }
            }
        }
    }
}
