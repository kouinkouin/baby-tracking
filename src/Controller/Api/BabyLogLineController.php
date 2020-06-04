<?php

namespace App\Controller\Api;

use App\Entity\Baby;
use App\Entity\BabyLogLine;
use App\Repository\BabyRepository;
use App\Services\Helper\UserHelper;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

class BabyLogLineController extends AbstractController
{
    private EntityManagerInterface $em;

    private LoggerInterface $logger;

    private BabyRepository $babyRepository;

    private ValidatorInterface $validator;

    private SerializerInterface $serializer;

    private UserHelper $userHelper;

    public function __construct(
        EntityManagerInterface $em,
        LoggerInterface $logger,
        BabyRepository $babyRepository,
        ValidatorInterface $validator,
        SerializerInterface $serializer,
        UserHelper $userHelper
    ) {
        $this->em = $em;
        $this->logger = $logger;
        $this->babyRepository = $babyRepository;
        $this->validator = $validator;
        $this->serializer = $serializer;
        $this->userHelper = $userHelper;
    }

    /**
     * @Route("/api/baby/{baby}/logline", name="api_baby_logline_list", methods={"GET"})
     */
    public function list(Baby $baby)
    {
        return $this->json($baby->getLogLines());
    }

    /**
     * @Route("/api/baby/{baby}/logline/{babyLogLine}", name="api_baby_logline_show", methods={"GET"})
     */
    public function show(Baby $baby, BabyLogLine $babyLogLine)
    {
        if ($babyLogLine->getBaby() !== $baby) {
            return new Response('', Response::HTTP_FORBIDDEN);
        }

        return $this->json($babyLogLine);
    }

    /**
     * @Route("/api/baby/{baby}/logline", name="api_baby_logline_add", methods={"POST"})
     */
    public function add(Baby $baby, Request $request)
    {
        if (!$user = $this->userHelper->getUserFromRequest($request)) {
            return $this->json(['errors' => ['you are not authenticated']], Response::HTTP_UNAUTHORIZED);
        }
        if (!$user->getBabies()->contains($baby)) {
            return $this->json(['errors' => ['it is not your baby']], Response::HTTP_FORBIDDEN);
        }
        try {
            /** @var BabyLogLine $babyLogLine */
            $babyLogLine = $this->serializer->deserialize($request->getContent(), BabyLogLine::class, 'json');

            $babyLogLine->setBaby($baby);

            $constraintViolationList = $this->validator->validate($babyLogLine);

            if ($constraintViolationList->count()) {
                $errors = [];
                /** @var ConstraintViolationInterface $violation */
                foreach ($constraintViolationList as $violation) {
                    $errors[] = $violation->getMessage();
                }

                return $this->json(['errors' => $errors], Response::HTTP_BAD_REQUEST);
            }
            $this->em->persist($babyLogLine);
            $this->em->flush();

            return $this->json($babyLogLine);
        } catch (Throwable $e) {
            $context = [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ];
            $this->logger->error($e->getMessage(), $context);

            return $this->json($context, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
