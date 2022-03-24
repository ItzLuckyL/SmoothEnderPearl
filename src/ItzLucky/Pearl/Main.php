<?php

namespace ItzLucky\Pearl;

use pocketmine\plugin\PluginBase;

use pocketmine\player\Player;

use pocketmine\event\Listener;

use pocketmine\event\entity\ProjectileHitEvent;

use pocketmine\entity\projectile\EnderPearl;

class Main extends PluginBase implements Listener
{

    public function onEnable() : void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onProjectileHit(ProjectileHitEvent $event) {
        $projectile = $event->getEntity();
        $entity = $projectile->getOwningEntity();
        if ($projectile instanceof EnderPearl and $entity instanceof Player) {
            $setPosition = new \ReflectionMethod($entity, 'setPosition');
            $setPosition->setAccessible(true);
            $setPosition->invoke($entity, $event->getRayTraceResult()->getHitVector());
            $location = $entity->getLocation();
            $entity->getNetworkSession()->syncMovement($location, $location->yaw, $location->pitch);
            $projectile->setOwningEntity(null);
        }
    }
}
