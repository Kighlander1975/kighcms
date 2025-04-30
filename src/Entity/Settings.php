<?php

namespace App\Entity;

use App\Repository\SettingsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SettingsRepository::class)]
#[UniqueEntity(fields: ['name'], message: 'Der Name existiert bereits.')]
class Settings
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message: "Der Name darf nicht leer sein.")]
    #[Assert\Length(max: 50, maxMessage: "Der Name darf maximal 50 Zeichen lang sein.")]
    #[ORM\Column(length: 255, unique: true)]
    private ?string $name = null;

    #[Assert\NotBlank(message: "Wert darf nicht leer sein.")]
    #[Assert\Length(max: 250, maxMessage: "Der Wert darf maximal 250 Zeichen lang sein.")]
    #[ORM\Column(length: 255)]
    private ?string $value = null;

    #[Assert\NotBlank(message: "Präfix darf nicht leer sein.")]
    private ?string $pre = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getPre(): ?string
    {
        return $this->pre;
    }

    public function setPre(?string $pre): void
    {
        $this->pre = $pre;
    }

}
