<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiProperty;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity("email")]
#[ApiResource(
    order: ['id' => 'DESC'],
    normalizationContext: ['groups' => ['users:item', 'users:list'], 'subresource_operation_name' => ''],
    denormalizationContext: ['groups' => ['users:create']],
    paginationEnabled: true,
)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['users:item', 'users:list'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['users:item', 'users:list', 'users:create'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['users:item', 'users:list', 'users:create'])]
    #[Assert\Email]
    private ?string $email = null;

    #[ORM\ManyToOne(inversedBy: 'users', cascade: ['persist'])]
    #[ORM\JoinColumn("group_id", "id", false, true, "SET NULL")]
    #[Groups(['users:item', 'users:list', 'users:create'])]
    private ?UserGroup $userGroup = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUserGroup(): ?UserGroup
    {
        return $this->userGroup;
    }

    public function setUserGroup(?UserGroup $userGroup): self
    {
        $this->userGroup = $userGroup;

        return $this;
    }
}
