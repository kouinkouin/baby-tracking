<?php

namespace App\Controller\Api;

use App\Repository\BabyLogLineRepository;
use App\Services\Helper\LogTypeHelper;
use App\Services\Helper\UserHelper;
use DateTimeImmutable;
use DateTimeZone;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use function array_key_first;

class LogController extends AbstractController
{
    private const FORMAT_DATETIME_LOCAL = 'Y-m-d\TH:i';

    private UserHelper $userHelper;

    private BabyLogLineRepository $babyLogLineRepository;

    private LogTypeHelper $logTypeHelper;

    public function __construct(
        UserHelper $userHelper,
        BabyLogLineRepository $babyLogLineRepository,
        LogTypeHelper $logTypeHelper
    ) {
        $this->userHelper = $userHelper;
        $this->babyLogLineRepository = $babyLogLineRepository;
        $this->logTypeHelper = $logTypeHelper;
    }

    /**
     * @Route("/api/log/add/fields", name="api_log_fields", methods={"GET"})
     */
    public function fieldsForAdd(Request $request)
    {
        if (!$user = $this->userHelper->getUserFromRequest($request)) {
            return $this->json(['errors' => ['you are not authenticated']], Response::HTTP_UNAUTHORIZED);
        }
        $babies = [];
        foreach ($user->getBabies() as $baby) {
            $babies[$baby->getId()] = [
                'value' => $baby->getId(),
                'text' => $baby->getName(),
            ];
        }
        if (!$babies) {
            return $this->json(
                ['errors' => ['You need almost one baby registered (and there is not URL for that yet!)']],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        $lastBabyLogLine = $this->babyLogLineRepository->findOneLastByUser($user);

        $selectedBabyId = $lastBabyLogLine ? $lastBabyLogLine->getBaby()->getId() : array_key_first($babies);

        $selectedLogTypeId = $lastBabyLogLine ? $lastBabyLogLine->getTypeId() : 1;

        $timezone = new DateTimeZone('Europe/Brussels');
        $when = (new DateTimeImmutable('now', $timezone))->format(self::FORMAT_DATETIME_LOCAL);

        $lastUpdatesLines = $this->babyLogLineRepository->findLastOnesGroupedByBabyAndTypeId($user);

        $lastUpdates = [];
        foreach ($lastUpdatesLines as $lastUpdatesLine) {
            /** @var DateTimeImmutable $when */
            $when = $lastUpdatesLine['when'];
            $lastUpdates[$lastUpdatesLine['baby_id']][$lastUpdatesLine['typeId']] = [
                'when' => $when->format('d/m/Y H:i'),
                'inputs' => $lastUpdatesLine['data'],
            ];
        }

        return $this->json(
            [
                'babies' => $babies,
                'babyId' => $selectedBabyId,
                'types' => $this->logTypeHelper->getAll(),
                'typeId' => $selectedLogTypeId,
                'when' => $when,
                'inputs' => $this->logTypeHelper->getInputs(),
                'lastUpdates' => $lastUpdates,
            ]
        );
    }
}
