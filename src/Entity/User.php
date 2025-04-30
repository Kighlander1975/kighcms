<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    /**
     * @var Collection<int, Settings>
     */
    #[ORM\ManyToMany(targetEntity: Settings::class)]
    #[ORM\JoinTable(
        name: "user_settings_roles",
        joinColumns: [new ORM\JoinColumn(name: "user_id", referencedColumnName: "id")],
        inverseJoinColumns: [new ORM\JoinColumn(name: "settings_id", referencedColumnName: "id")]
    )]
    private Collection $settingsRoles;

    public function __construct()
    {
        $this->settingsRoles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        // Standardrollen aus User-Objekt
        $roles = $this->roles;

        // Dynamische Rollen aus den zugeordneten Settings (Präfix "roles_")
        foreach ($this->settingsRoles as $setting) {
            if (
                $setting->getName() !== null
                && str_starts_with($setting->getName(), 'roles_')
            ) {
                $roles[] = $setting->getValue();
            }
        }

        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @return Collection<int, Settings>
     */
    public function getSettingsRoles(): Collection
    {
        return $this->settingsRoles;
    }

    public function addSettingsRole(Settings $settingsRole): static
    {
        if (!$this->settingsRoles->contains($settingsRole)) {
            $this->settingsRoles[] = $settingsRole;
        }
        return $this;
    }

    public function removeSettingsRole(Settings $settingsRole): static
    {
        $this->settingsRoles->removeElement($settingsRole);
        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}