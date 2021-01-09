<?php

declare(strict_types = 1);


namespace rark7040\region_protector;

use pocketmine\event\Listener;
use pocketmine\event\entity\{
	ExplosionPrimeEvent,
	EntityDamageEvent,
	EntityDamageByEntityEvent
};
use rark7040\region_protector\form\list\MenuForm;

class EntityEventListener implements Listener{

	public function onExplosionPrime(ExplosionPrimeEvent $event):void{
		$manager = Main::getRegionManager();
		$region = $manager->getRegion($event->getEntity());

		if(!is_null($region)){
			return;

		}elseif($region->isProtected()){
			$event->setBlockBreaking(false);
			return;
		}
	}

	public function onDamage(EntityDamageEvent $event):void{

		if($event->getEntity() instanceof RegionCrystal){

			if($event instanceof EntityDamageByEntityEvent){
				$damager = $event->getDamager();

				if($damager instanceof Player){
					$damager->sendForm(new MenuForm($damager));
				}
			}
			$event->setCancelled();
			return;
		}
		return;
	}


}