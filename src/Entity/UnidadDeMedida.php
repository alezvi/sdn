<?php

namespace App\Entity;

use App\Repository\UnidadDeMedidaRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Unique;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: UnidadDeMedidaRepository::class)]
#[Broadcast]
#[ORM\Table(name: 'unidades_de_medida')]
#[UniqueEntity(fields: 'codigo', message: 'El código ya existe')]
class UnidadDeMedida
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id_unidad_de_medida;

    #[ORM\Column(length: 5)]
    #[Unique(message: 'El código ya existe')]
    #[NotNull(message: 'El código es requerido')]
    #[Assert\Regex('/\d+[a-zA-Z]+/', message: 'Solo numeros y letras')]
    private string $codigo;

    #[ORM\Column(length: 50)]
    #[NotNull(message: 'La unidad de medida es requerida')]
    private string $unidad_medida;

    public function getId(): ?int
    {
        return $this->id_unidad_de_medida;
    }

    public function getIdUnidadDeMedida(): ?int
    {
        return $this->id_unidad_de_medida;
    }

    public function setIdUnidadDeMedida(int $id_unidad_de_medida): static
    {
        $this->id_unidad_de_medida = $id_unidad_de_medida;

        return $this;
    }

    public function getCodigo(): ?string
    {
        return $this->codigo;
    }

    public function setCodigo(string $codigo): static
    {
        $this->codigo = $codigo;

        return $this;
    }

    public function getUnidadMedida(): ?string
    {
        return $this->unidad_medida;
    }

    public function setUnidadMedida(string $unidad_medida): static
    {
        $this->unidad_medida = $unidad_medida;

        return $this;
    }
}
