<?php

namespace App\DataFixtures;

use App\Entity\CondicionIVA;
use App\Entity\ProductoServicio;
use App\Entity\Rubro;
use App\Entity\UnidadDeMedida;
use DateInterval;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Random\RandomException;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $this->loadRubros($manager);
        $this->loadUnidadesDeMedida($manager);
        $this->loadCondiciones($manager);
        $this->loadProductos($manager);
    }

    private function loadRubros(ObjectManager $manager)
    {
        $file = file_get_contents(__DIR__ . '/../../public/data/ACTIVIDADES_ECONOMICAS_F883.txt');

        $rubros = [];

        foreach (explode("\n", $file) as $key => $line) {
            if ($line === '') {
                continue;
            }
            if ($key === 0) {
                continue;
            }

            [, $rubro] = explode(';', $line);
            $rubros[] = new Rubro()->setRubro(mb_strcut($rubro, 0, 50));
        }

        foreach ($rubros as $rubro) {
            $manager->persist($rubro);
        }

        $manager->flush();
    }

    private function loadUnidadesDeMedida(ObjectManager $manager)
    {
        $unidades = (array)require_once __DIR__ . '/../../public/data/unidades_de_medida.php';

        foreach ($unidades as $key => $unidad) {
            $u = new UnidadDeMedida()->setUnidadMedida(mb_strcut($unidad, 0, 50))->setCodigo($key);
            $manager->persist($u);
        }

        $manager->flush();
    }

    /**
     * @throws RandomException
     */
    private function loadCondiciones(ObjectManager $manager)
    {
        $condiciones = [
            1 => 'IVA Responsable Inscripto',
            4 => 'IVA Sujeto Exento',
            5 => 'Consumidor Final',
            6 => 'Responsable Monotributo',
            7 => 'Sujeto No Categorizado',
            8 => 'Proveedor del Exterior',
            9 => 'Cliente del Exterior',
            10 => 'IVA Liberado - Ley Nº 19.640',
            13 => 'Monotributista Social',
            15 => 'IVA No Alcanzado',
            16 => 'Monotributista Trabajador Independiente Promovido',
        ];

        foreach ($condiciones as $key => $condicion) {
            $c = new CondicionIVA()->setCondicionIVA($condicion)->setCodigo($key)->setAlicuota(random_int(0, 50));
            $manager->persist($c);
        }

        $manager->flush();
    }

    /**
     * @throws \DateInvalidOperationException
     * @throws RandomException
     */
    private function loadProductos(ObjectManager $manager)
    {
        $rubros = $manager->getRepository(Rubro::class)->findAll();
        $unidades = $manager->getRepository(UnidadDeMedida::class)->findAll();
        $condiciones = $manager->getRepository(CondicionIVA::class)->findAll();

        for ($i = 0; $i < 1000; $i++) {
            $item = new ProductoServicio();
            $item->setTipo(random_int(0, 1) ? 'P' : 'S');
            $item->setCodigo(random_int(1000000000, 9999999999));
            $item->setProductoServicio('Producto no existente ' . random_int(1000000000, 9999999999));
            $item->setPrecioProductoUnitario(random_int(100, 1000000));
            $item->setRubro($rubros[random_int(0, count($rubros) - 1)]);
            $item->setUnidadMedida($unidades[random_int(0, count($unidades) - 1)]);
            $item->setCondicionIva($condiciones[random_int(0, count($condiciones) - 1)]);

            $threeYearsAgo = new DateTime()->sub(new DateInterval('P3Y'))->getTimestamp();
            $now = new DateTime()->getTimestamp();

            $item->setCreatedAt(
                new DateTime()->setTimestamp(random_int($threeYearsAgo, $now)),
            );

            $manager->persist($item);
        }

        $manager->flush();
    }
}
