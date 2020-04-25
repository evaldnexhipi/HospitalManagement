<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\Departament;
use App\Entity\Hall;
use App\Entity\MedicalStaff;
use App\Entity\Speciality;
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
        $this->createMany(User::class,9,function(User $user, $count) use ($manager){
            if($count>=0 && $count<3) {
                $user->setEmail(sprintf('user%d@megaspital.com', $count));
                $user->setFirstName($this->faker->firstName);
                $user->setLastName($this->faker->lastName);
                $user->setPassword($this->passwordEncoder->encodePassword(
                    $user,
                    'user123'
                ));
                $user->setGender('M');
                $user->setBirthday(new \DateTime());
                $client = new Client();
                $client->setUser($user);
                $user->setClient($client);
                $manager->persist($client);
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
                $user->setGender('F');
                $user->setBirthday(new \DateTime());

                $doc = new MedicalStaff();

                $hall = new Hall();
                $dept = new Departament();
                $dept->setName('Dermatologji');
                $dept->addHall($hall);
                $manager->persist($dept);

                $hall->setName('Hall'.$count);
                $hall->setDepartament($dept);
                $manager->persist($hall);
                $doc->setHall($hall);

                $speciality = new Speciality();
                $speciality->setTitle('Spec'.$count);
                $speciality->addMedicalStaff($doc);
                $manager->persist($speciality);

                $doc->setSpeciality($speciality);
                $doc->setStatus('true');

                $service = $manager->getRepository('App:Service')->findOneBy(['name'=>'Sherbimi_'.($count%7)]);
                $doc->addService($service);
                $service->addMedicalStaff($doc);

                $manager->persist($doc);
                $user->setMedicalStaff($doc);
                $doc->setUser($user);
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
                $user->setGender('M');
                $user->setBirthday(new \DateTime());
            }
        });
        $manager->flush();
    }
}
