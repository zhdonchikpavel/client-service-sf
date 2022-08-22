<?php

namespace App\Entity;

use App\Repository\UserGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: UserGroupRepository::class)]
#[UniqueEntity("name")]
#[ApiResource(
    order: ['name' => 'ASC'],
    paginationEnabled: true,
    normalizationContext: ['groups' => ['users:item', 'users:list']],
    denormalizationContext: ['groups' => ['groups:create', 'groups:update']],
)]
class UserGroup
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['users:item', 'users:create', 'groups:update'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['users:item', 'users:list', 'groups:create', 'groups:update', 'users:create'])]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'userGroup', targetEntity: User::class)]
    private Collection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setUserGroup($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getUserGroup() === $this) {
                $user->setUserGroup(null);
            }
        }

        return $this;
    }
}
