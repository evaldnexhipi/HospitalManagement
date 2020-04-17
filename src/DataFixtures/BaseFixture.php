<?php


namespace App\DataFixtures;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

abstract class BaseFixture extends Fixture
{
    /** @var ObjectManager */
    private $manager;

    /** @var Generator */
    protected $faker;

    abstract protected function loadData(ObjectManager $em);

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $this->faker = Factory::create();
        $this->loadData($manager);
    }

    protected function createMany(string $className, int $count, callable $factory)
    {
        for ($i = 0; $i < $count; $i++) {
            $entity = new $className();
            $factory($entity, $i);
            $this->manager->persist($entity);
            // store for usage later as App\Entity\ClassName_#COUNT#
            $this->addReference($className . '_' . $i, $entity);
        }
    }

    //How to use it?
    /*
    public function loadData(ObjectManager $manager)
    {
        $this->createMany(Article::class, 10, function (Article $article, $count) {
            $article->setTitle('Why Asteroids Taste Like Bacon')
                ->setSlug('why-asteroids-taste-like-bacon-' . $count)
                ->setContent(<<<EOF
Spicy **jalapeno bacon** ipsum
EOF
                );
// publish most articles
            if ($this->faker->boolean(70)) {
                $article->setPublishedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
            }
            $article->setAuthor('Mike Ferengi')
                ->setHeartCount($this->faker->numberBetween(5,100))
                ->setImageFilename('asteroid.jpeg');
        });
        $manager->flush();
    }
    */
}