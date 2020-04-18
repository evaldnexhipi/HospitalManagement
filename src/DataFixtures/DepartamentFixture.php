<?php

namespace App\DataFixtures;

use App\Entity\Departament;
use Doctrine\Persistence\ObjectManager;


class DepartamentFixture extends BaseFixture
{
    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(Departament::class,10,function(Departament $departament, $count){
            $departament->setName('Departamenti_'.$count);
            $departament->setDescription($this->faker->text(256));
        });

        $manager->flush();
    }
}
