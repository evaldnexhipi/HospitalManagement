<?php

namespace App\DataFixtures;

use App\Entity\Service;
use Doctrine\Persistence\ObjectManager;

class ServiceFixture extends BaseFixture
{

    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(Service::class,7,function(Service $service, $count) use ($manager){
            $service->setName('Sherbimi_'.$count);
            $service->setDepartament($manager->getRepository('App:Departament')->findOneBy(['name'=>'Departamenti_'.$count]));
            $service->setDescription($this->faker->text);
            $service->setCost(1000*$count);
        });
        $manager->flush();
    }
}
