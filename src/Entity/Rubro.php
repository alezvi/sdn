<?php

namespace App\Entity;

use App\Repository\RubroRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: RubroRepository::class)]
#[Broadcast]
class Rubro
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id_rubro;

    #[ORM\Column(length: 50)]
    #[Assert\Length(
        min: 1,
        max: 50,
        normalizer: 'trim',
        minMessage: 'El nombre debe tener al menos 1 caracter',
        maxMessage: 'El nombre debe tener hasta 50 caracteres',
    )]
    #[Assert\NotBlank(message: 'El nombre del rubro es requerido')]
    private string $rubro;

    public function getIdRubro(): ?int
    {
        return $this->id_rubro;
    }

    public function setIdRubro(int $id_rubro): static
    {
        $this->id_rubro = $id_rubro;

        return $this;
    }

    public function getRubro(): ?string
    {
        return $this->rubro;
    }

    public function setRubro(string $rubro): static
    {
        $this->rubro = $rubro;

        return $this;
    }
}
