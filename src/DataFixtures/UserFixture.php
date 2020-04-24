<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends BaseFixture
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    protected function loadData(ObjectManager $manager)
    {


        $this->createMany(User::class,9,function(User $user, $count){
            if($count>=0 && $count<3) {
                $user->setEmail(sprintf('user%d@megaspital.com', $count));
                $user->setFirstName($this->faker->firstName);
                $user->setLastName($this->faker->lastName);
                $user->setPassword($this->passwordEncoder->encodePassword(
                    $user,
                    'user123'
                ));
            }
            else if ($count>=3 && $count<6){
                $user->setEmail(sprintf('doc%d@megaspital.com', $count));
                $user->setFirstName($this->faker->firstName);
                $user->setLastName($this->faker->lastName);
                $user->addRole('ROLE_DOC');
                $user->setPassword($this->passwordEncoder->encodePassword(
                    $user,
                    'doc123'
                ));
            }
            else{
                $user->setEmail(sprintf('admin%d@megaspital.com', $count));
                $user->setFirstName($this->faker->firstName);
                $user->setLastName($this->faker->lastName);
                $user->addRole('ROLE_ADMIN');
                $user->setPassword($this->passwordEncoder->encodePassword(
                    $user,
                    'admin123'
                ));
            }
        });




        $manager->flush();
    }
}
