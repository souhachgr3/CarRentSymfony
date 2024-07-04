<?php
namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Role;
use App\Entity\Permission;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Création de rôles
        $adminRole = new Role();
        $adminRole->setName('ROLE_ADMIN');
        $adminRole->setDescription('Administrator role');

        $userRole = new Role();
        $userRole->setName('ROLE_USER');
        $userRole->setDescription('Regular user role');

        // Création de permissions
        $readPermission = new Permission();
        $readPermission->setName('PERMISSION_READ');
        $readPermission->setDescription('Permission to read content');

        $writePermission = new Permission();
        $writePermission->setName('PERMISSION_WRITE');
        $writePermission->setDescription('Permission to write content');

        // Assignation des permissions aux rôles
        $adminRole->addPermission($readPermission);
        $adminRole->addPermission($writePermission);

        $userRole->addPermission($readPermission);

        // Persist et flush des entités
        $manager->persist($adminRole);
        $manager->persist($userRole);
        $manager->persist($readPermission);
        $manager->persist($writePermission);

        // Création d'un utilisateur avec un rôle
        $adminUser = new User();
        $adminUser->setUsername('admin');
        $adminUser->setPassword(password_hash('adminpass', PASSWORD_BCRYPT));
        $adminUser->addRole($adminRole);

        $regularUser = new User();
        $regularUser->setUsername('user');
        $regularUser->setPassword(password_hash('userpass', PASSWORD_BCRYPT));
        $regularUser->addRole($userRole);
        $manager->persist($adminUser);
        $manager->persist($regularUser);

        $manager->flush();
    }
}
