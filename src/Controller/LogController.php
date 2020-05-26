<?php

namespace App\Controller;

use App\Entity\Baby;
use App\Entity\BabyLogLine;
use App\Repository\BabyLogLineRepository;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LogController extends AbstractController
{
    private const FORMAT_DATETIME_LOCAL = 'Y-m-d\TH:i';

    private UserRepository $userRepository;

    private BabyLogLineRepository $babyLogLineRepository;

    private EntityManagerInterface $em;

    public function __construct(
        EntityManagerInterface $em,
        UserRepository $userRepository,
        BabyLogLineRepository $babyLogLineRepository
    ) {
        $this->userRepository = $userRepository;
        $this->babyLogLineRepository = $babyLogLineRepository;
        $this->em = $em;
    }

    /**
     * @Route("/log/add", name="log_add")
     */
    public function add(Request $request)
    {
        $username = $request->getUser();
        $user = $this->userRepository->findOneByUsername($username);
        $babies = $user->getBabies();
        if ($babies->isEmpty()) {
            return new Response('You need almost one baby registered (and there is not URL for that yet!)', 500);
        }

        if ($request->getMethod() === 'POST') {
            return $this->treatAdd($request);
        }

        $lastBabyLogLine = $this->babyLogLineRepository->findOneLastByUser($user);

        $preselectedBabyId = $lastBabyLogLine ? $lastBabyLogLine->getBaby()->getId() : $babies->first()->getId();

        $logTypes = [
            ['id' => 1, 'name' => 'Poids', 'icon' => 'fas fa-weight'],
            ['id' => 2, 'name' => 'Taille', 'icon' => 'fas fa-ruler-combined'],
            ['id' => 3, 'name' => 'Température', 'icon' => 'fas fa-thermometer'],
            ['id' => 4, 'name' => 'Tétée', 'icon' => 'fas fa-lemon'],
        ];
        $preselectedLogTypeId = $lastBabyLogLine ? $lastBabyLogLine->getTypeId() : 1;

        $now = (new DateTimeImmutable)->format(self::FORMAT_DATETIME_LOCAL);

        return $this->render(
            'log/add.html.twig',
            [
                'babies' => $babies,
                'preselected_baby_id' => $preselectedBabyId,
                'log_types' => $logTypes,
                'preselected_log_type_id' => $preselectedLogTypeId,
                'now' => $now,
            ]
        );
    }

    private function treatAdd(Request $request)
    {
        $babyId = $request->request->get('baby');
        /** @var Baby $baby */
        $baby = $this->em->getPartialReference(Baby::class, $babyId);

        $when = DateTimeImmutable::createFromFormat(
            self::FORMAT_DATETIME_LOCAL,
            $request->request->get('datetime')
        ) ?: null;

        $logLine = (new BabyLogLine)
            ->setBaby($baby)
            ->setCreationDatetime($when)
            ->setTypeId($request->request->getInt('log_type'))
            ->setData(['value' => $request->request->get('data')])
        ;

        $this->em->persist($logLine);
        $this->em->flush();

        return $this->redirectToRoute('log_add');
    }
}
