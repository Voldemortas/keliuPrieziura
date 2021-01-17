<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Metric;
use App\Entity\RoadType;
use App\Entity\Road;
use App\Entity\Section;
use App\Entity\Cipher;
use App\Entity\Job;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $metrics = ['km', 'm'];
        $roadTypes = ['Asfaltas', 'Žvyras'];
        $roads = [
            ['A1', 'Vilnius–Kaunas–Klaipėda', 1, 0],
            ['A2', 'Panevėžys', 1, 0],
            ['A12', 'Ryga*–Šiauliai–Tauragė–Kaliningradas*', 2, 0],
        ];
        $sections = [
            [
                10, 70, 129.5, 190, 250, 306.234
            ],
            [
                9.276, 70.576, 128.39, 132.55
            ],
            [
                0, 17, 35.35, 49.27, 49.738, null, 59.49,
                67.07, 68.666, 77.056, 120.053, 185.969
            ],
        ];
        $ciphers = [
            ['KP10-1-1', 'Takų su kietomis dangomis valymas rankiniu būdu', null],
            ['KP2-1-28-1', 'Žolės pjovimas', null],
            ['KP10-1-10', 'Kelio barstymas frikcinėmis medžiagomis, esant slidumams', null],
            ['KP10-1-11', 'Takų barstymas frikcinėmis medžiagomis, esant slidumams', null],
            ['KP10-1-12', 'Sniego užpustytose vietose nuvalymas mechanizuotu būdu', null],
            ['KP10-1-2', 'Takų su žvyro dangomis valymas', null],
            ['KP10-1-3', 'Takų su asfalto dangomis valymas', null],
            ['KP10-1-4', 'Sniego valymas nuo asfalto', 0],
            ['KP10-1-5', 'Sniego valymas nuo žvyro', 1],
        ];
        $jobs = [
            [2, '0.0', 4.051],
            [2, '0.1', 4.017],
            [2, '0.2', 4.085],
            [2, '0.3', 4.051],
            [2, '0.4', 3.796],
        ];
        /**
         * @var Metric[]
         */
        $metric = [];
        /**
         * @var RoadType[]
         */
        $roadType = [];
        /**
         * @var Road[]
         */
        $road = [];
        /**
         * @var Section[]
         */
        $section = [];
        /**
         * @var Section[]
         */
        $cipher = [];
        /**
         * @var Job[]
         */
        $job = [];

        for ($i = 0; $i < count($metrics); $i++) {
            $metric[] = new Metric();
            $metric[$i]->setName($metrics[$i]);
            $manager->persist($metric[$i]);
        }
        for ($i = 0; $i < count($roadTypes); $i++) {
            $roadType[] = new RoadType();
            $roadType[$i]->setName($roadTypes[$i]);
            $manager->persist($roadType[$i]);
        }
        for ($i = 0; $i < count($roads); $i++) {
            $road[] = new Road();
            $road[$i]->setNumber($roads[$i][0]);
            $road[$i]->setName($roads[$i][1]);
            $road[$i]->setLevel($roads[$i][2]);
            $road[$i]->setType($roadType[$roads[$i][3]]);
            $manager->persist($road[$i]);
        }
        for ($i = 0; $i < count($sections); $i++) {
            for ($j = 1; $j < count($sections[$i]); $j++) {
                if ($sections[$i][$j] === null) {
                    continue;
                }
                $section[$i . '.' . ($j - 1)] = new Section();
                $section[$i . '.' .  ($j - 1)]->setRoad($road[$i]);
                $section[$i . '.' .  ($j - 1)]->setStart((float) $sections[$i][$j - 1]);
                $section[$i . '.' . ($j - 1)]->setFinish((float) $sections[$i][$j]);
                $manager->persist($section[$i . '.' . ($j - 1)]);
            }
        }
        for ($i = 0; $i < count($ciphers); $i++) {
            $cipher[] = new Cipher();
            $cipher[$i]->setCipher($ciphers[$i][0]);
            $cipher[$i]->setName($ciphers[$i][1]);
            $cipher[$i]->setType($ciphers[$i][2] === null ? null : $roadType[$ciphers[$i][2]]);
            $cipher[$i]->setMetric($metric[0]);
            $manager->persist($cipher[$i]);
        }
        for ($i = 0; $i < count($jobs); $i++) {
            $job[] = new Job();
            $job[$i]->setCipher($cipher[$jobs[$i][0]]);
            $job[$i]->setSection($section[$jobs[$i][1]]);
            $job[$i]->setDistance($jobs[$i][2]);
            $manager->persist($job[$i]);
        }


        $manager->flush();
    }
}
