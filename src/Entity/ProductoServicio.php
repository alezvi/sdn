<?php

namespace App\Entity;

use App\Enum\TiposDeItems;
use App\Repository\ProductoServicioRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: ProductoServicioRepository::class)]
#[UniqueEntity(fields: 'codigo')]
#[Broadcast]
class ProductoServicio
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id_producto_servicio;

    #[ORM\ManyToOne(targetEntity: Rubro::class)]
    #[ORM\JoinColumn(name: 'id_rubro', referencedColumnName: 'id_rubro', nullable: false)]
    #[Assert\NotNull(message: 'El rubro es requerido')]
    private Rubro $rubro;

    #[ORM\Column(length: 1)]
    #[Assert\NotBlank(message: 'El tipo es requerido')]
    #[Assert\Choice(
        choices: [TiposDeItems::PRODUCTO->value, TiposDeItems::SERVICIO->value],
        message: 'El tipo debe ser producto o servicio'
    )]
    private string $tipo;

    #[ORM\ManyToOne(targetEntity: UnidadDeMedida::class)]
    #[ORM\JoinColumn(name: 'id_unidad_medida', referencedColumnName: 'id_unidad_de_medida', nullable: false)]
    #[Assert\NotNull(message: 'La unidad de medida es requerida')]
    private UnidadDeMedida $unidadMedida;

    #[ORM\Column(length: 20, unique: true)]
    #[Assert\NotBlank(message: 'El código es requerido')]
    #[Assert\Length(
        min: 1,
        max: 20,
        normalizer: 'trim',
        minMessage: 'El código debe tener al menos {{ limit }} caracter',
        maxMessage: 'El código debe tener hasta {{ limit }} caracteres',
    )]
    private string $codigo;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'El producto o servicio es requerido')]
    #[Assert\Length(
        min: 1,
        max: 255,
        normalizer: 'trim',
        minMessage: 'El nombre debe tener al menos {{ limit }} caracter',
        maxMessage: 'El nombre debe tener hasta {{ limit }} caracteres',
    )]
    private string $producto_servicio;

    #[ORM\ManyToOne(targetEntity: CondicionIVA::class)]
    #[ORM\JoinColumn(name: 'id_condicion_iva', referencedColumnName: 'id_condicion_iva', nullable: false)]
    #[Assert\NotNull(message: 'La condición IVA es requerida')]
    private CondicionIVA $condicionIva;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    #[Assert\NotNull(message: 'El precio es requerido')]
    #[Assert\PositiveOrZero(message: 'El precio debe ser mayor o igual a cero')]
    private int $precio_producto_unitario;

    #[ORM\Column(type: 'datetime')]
    private DateTimeInterface $created_at;

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->created_at = new DateTime();
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(DateTimeInterface $created_at): static
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function getIdProductoServicio(): int
    {
        return $this->id_producto_servicio;
    }

    public function setIdProductoServicio(int $id_producto_servicio): static
    {
        $this->id_producto_servicio = $id_producto_servicio;
        return $this;
    }

    public function getRubro(): Rubro
    {
        return $this->rubro;
    }

    public function setRubro(Rubro $rubro): static
    {
        $this->rubro = $rubro;
        return $this;
    }

    public function getTipo(): string
    {
        return $this->tipo;
    }

    public function setTipo(string $tipo): static
    {
        $this->tipo = $tipo;
        return $this;
    }

    public function getUnidadMedida(): UnidadDeMedida
    {
        return $this->unidadMedida;
    }

    public function setUnidadMedida(UnidadDeMedida $unidadMedida): static
    {
        $this->unidadMedida = $unidadMedida;
        return $this;
    }

    public function getCodigo(): string
    {
        return $this->codigo;
    }

    public function setCodigo(string $codigo): static
    {
        $this->codigo = $codigo;
        return $this;
    }

    public function getProductoServicio(): string
    {
        return $this->producto_servicio;
    }

    public function setProductoServicio(string $producto_servicio): static
    {
        $this->producto_servicio = $producto_servicio;
        return $this;
    }

    public function getCondicionIva(): CondicionIVA
    {
        return $this->condicionIva;
    }

    public function setCondicionIva(CondicionIVA $condicionIva): static
    {
        $this->condicionIva = $condicionIva;
        return $this;
    }

    public function getPrecioProductoUnitario(): float
    {
        return $this->precio_producto_unitario;
    }

    public function setPrecioProductoUnitario(float $precio_producto_unitario): static
    {
        $this->precio_producto_unitario = $precio_producto_unitario;
        return $this;
    }
}
