<?php

namespace App\DataFixtures;

use App\Entity\CondicionIVA;
use App\Entity\Rubro;
use App\Entity\UnidadDeMedida;
use App\Entity\User;
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
            $c = new CondicionIVA()->setCondicionIVA($condicion)->setCodigo($key)->setAlicuota(random_int(0,50));
            $manager->persist($c);
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

    private function loadRubros(ObjectManager $manager)
    {
        $file = file_get_contents(__DIR__ . '/../../public/data/ACTIVIDADES_ECONOMICAS_F883.txt');

        $rubros = [];

        foreach (explode("\n", $file) as $key => $line) {
            if ($line === '') continue;
            if ($key === 0) continue;

            [, $rubro] = explode(';', $line);
            $rubros[] = new Rubro()->setRubro(mb_strcut($rubro, 0, 50));
        }

        foreach ($rubros as $rubro) {
            $manager->persist($rubro);
        }

        $manager->flush();
    }
}
