<?php

namespace App\Entity;

use App\Repository\CondicionIVARepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: CondicionIVARepository::class)]
#[ORM\Table(name: 'condicion_iva')]
#[Broadcast]
#[UniqueEntity(fields: ['codigo'], message: 'El codigo ya existe')]
class CondicionIVA
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id_condicion_iva;

    #[ORM\Column(type: 'string', length: 50)]
    #[Assert\Length(
        min: 1,
        max: 50,
        normalizer: 'trim',
        minMessage: 'El nombre debe tener al menos {{ limit }} caracter',
        maxMessage: 'El nombre debe tener hasta {{ limit }} caracteres',
    )]
    #[Assert\NotBlank(message: 'La condición es requerida')]
    private string $condicion_iva;

    #[ORM\Column(type: 'smallint')]
    private int $codigo;

    #[ORM\Column(type: 'float', precision: 10, scale: 2)]
    #[Assert\NotBlank(message: 'La alicuota es requerida')]
    private float $alicuota;

    public function getId(): ?int
    {
        return $this->id_condicion_iva;
    }

    public function getIdCondicionIva(): ?int
    {
        return $this->id_condicion_iva;
    }

    public function setIdCondicionIva(int $id_condicion_iva): static
    {
        $this->id_condicion_iva = $id_condicion_iva;

        return $this;
    }

    public function getCondicionIva(): ?string
    {
        return $this->condicion_iva;
    }

    public function setCondicionIva(string $condicion_iva): static
    {
        $this->condicion_iva = $condicion_iva;

        return $this;
    }

    public function getCodigo(): ?int
    {
        return $this->codigo;
    }

    public function setCodigo(int $codigo): static
    {
        $this->codigo = $codigo;

        return $this;
    }

    public function getAlicuota(): ?int
    {
        return $this->alicuota;
    }

    public function setAlicuota(int $alicuota): static
    {
        $this->alicuota = $alicuota;

        return $this;
    }
}
