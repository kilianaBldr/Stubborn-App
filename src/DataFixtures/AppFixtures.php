<?php

namespace App\DataFixtures;

use App\Entity\Sweatshirt;
use App\Entity\User; 
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface; 

class AppFixtures extends Fixture 
{ 
    private $passwordHasher; 
    public function __construct(UserPasswordHasherInterface $passwordHasher) 
    { 
        $this->passwordHasher = $passwordHasher; 
    } 
    public function load(ObjectManager $manager): void 
    { 
        // Création admin
        $admin = new User(); 
        $admin->setEmail('admin@test.com');
        $admin->setDeliveryAddress('0 RUE dev 00000 Paris');  
        $admin->setRoles(['ROLE_ADMIN']); 
        $admin->setName('admin'); $admin->setIsVerified(true); 
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin'));
        $admin->setCreatedAt(new \DateTimeImmutable()); 
        $admin->setUpdatedAt(new \DateTimeImmutable());
        $manager->persist($admin); 

        // Création de l'utilisateur 
        $user = new User(); 
        $user->setEmail('user@test.com');
        $user->setDeliveryAddress('0 RUE user 00000 Paris');  
        $user->setRoles(['ROLE_USER']); 
        $user->setName('user'); 
        $user->setIsVerified(true); 
        $user->setPassword($this->passwordHasher->hashPassword($user, 'user'));
        $user->setCreatedAt(new \DateTimeImmutable()); 
        $user->setUpdatedAt(new \DateTimeImmutable());  
        $manager->persist($user); 

        //Création de Sweatshirts
        $sweatshirts = [
            ['BlackBelt', '1.jpeg', 29.90],
            ['Pokeball', '4.jpeg', 45],
            ['BorninUsa', '9.jpeg', 59.90],
        ];

        // Création de trois produits (sweatshirt) 
        foreach ($sweatshirts as $sweat) {
        
            $sweatshirt = new Sweatshirt();
            $sweatshirt->setIsFeatured(true);  
            $sweatshirt->setName($sweat[0]);
            $sweatshirt->setImageName($sweat[1]); 
            $sweatshirt->setPrice($sweat[2]); 
            $sweatshirt->setStockXS(8); 
            $sweatshirt->setStockS(8); 
            $sweatshirt->setStockM(8); 
            $sweatshirt->setStockL(8); 
            $sweatshirt->setStockXL(8);
            $manager->persist($sweatshirt);
        }
        
        $manager->flush(); 
    } 
}