<?php

namespace App\Command\Tools;

use App\Entity\User;
use App\Entity\UserRole;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Id\AssignedGenerator;
use Doctrine\ORM\Mapping\ClassMetadata;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserImporter
{

    private array $tables = [UserRole::class, User::class];

    public function __construct(
        #[Autowire(service: 'doctrine.dbal.legacy_connection')]
        private readonly Connection $connection,
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $passwordHasher,
    )
    {
    }

    public function import(SymfonyStyle $io, bool $truncate = false): void
    {
        if ($truncate === true)
        {
            $this->truncate();
        }

        $this->importUserRoles($io);
        $this->importUsers($io);
    }

    private function importUserRoles(SymfonyStyle $io): void
    {
        $io->title("Import des roles d'utilisateur");

        $result = $this->connection->executeQuery("SELECT * FROM fonction")->fetchAllAssociative();

        foreach ($result as $row)
        {
            $userRole = new UserRole()
                ->setId($row["fon_numero"])
                ->setName($row["fon_libelle"])
            ;
            $this->disableAutoIncrement($userRole);
            $this->entityManager->persist($userRole);
        }

        $this->entityManager->flush();
        $io->success(sprintf('Import de %d roles', count($result)));
    }

    private function importUsers(SymfonyStyle $io): void
    {
        $io->title("Import des utilisateurs");

        $result = $this->connection->executeQuery("SELECT * FROM utilisateur")->fetchAllAssociative();

        foreach ($result as $row)
        {
            $user = new User()
                ->setId($row["uti_numero"])
                ->setUsername($row["uti_login"])
                ->setEmail($row["uti_mail"])
                ->setAge(0)
                ->setCreatedAt(new \DateTimeImmutable())
                ->setUpdatedAt(new \DateTimeImmutable())
            ;
            $user->setPassword($this->passwordHasher->hashPassword($user, $row["uti_pass"]));
            $this->disableAutoIncrement($user);
            $this->entityManager->persist($user);
        }

        $this->entityManager->flush();
        $io->success(sprintf('Import de %d utilisateur', count($result)));
    }

    private function truncate(): void
    {
        $connection = $this->entityManager->getConnection();

        foreach ($this->tables as $table)
        {
            $classMetadata = $this->entityManager->getClassMetadata($table);
            $tableName = $classMetadata->getTableName();

            $connection->executeStatement('SET FOREIGN_KEY_CHECKS = 0');
            $connection->executeStatement('TRUNCATE TABLE ' . $tableName);
            $connection->executeStatement('SET FOREIGN_KEY_CHECKS = 1');
        }
    }

    private function disableAutoIncrement(object $entity): void
    {
        $metadata = $this->entityManager->getClassMetaData(get_class($entity));
        $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);
        $metadata->setIdGenerator(new AssignedGenerator());
    }

}
