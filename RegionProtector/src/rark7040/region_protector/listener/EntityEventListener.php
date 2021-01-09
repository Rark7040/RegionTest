<?php

declare(strict_types = 1);


namespace rark7040\region_protector;

use pocketmine\event\Listener;
use pocketmine\event\entity\{
	ExplosionPrimeEvent,
	EntityDamageEvent,
	EntityDamageByEntityEvent
};
use rark7040\region_protector\form\simple\MenuForm;

class EntityEventListener implements Listener{

	public function onExplosionPrime(ExplosionPrimeEvent $event):void{
		$manager = Main::getRegionManager();
		$region = $manager->getRegion($event->getEntity());

		if(is_null($region)){
			return;
		}

		if($region->isProtected()){
			$event->setBlockBreaking(false);
		}
	}

	public function onDamage(EntityDamageEvent $event):void{
		$entity = $event->getEntity();

		if(!$entity instanceof RegionCrystal){
			return;
		}

		if($event instanceof EntityDamageByEntityEvent){
			$damager = $event->getDamager();

			if($damager instanceof Player){
				$damager->sendForm(new MenuForm($damager, $entity->region));
			}
		}
		$event->setCancelled();
	}


}