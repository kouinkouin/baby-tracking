<?php

namespace App\Controller\Api;

use App\Entity\Baby;
use App\Entity\BabyLogLine;
use App\Repository\BabyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

class BabyLogLineController extends AbstractController
{
    private EntityManagerInterface $em;

    private BabyRepository $babyRepository;

    private ValidatorInterface $validator;

    private SerializerInterface $serializer;

    public function __construct(
        EntityManagerInterface $em,
        BabyRepository $babyRepository,
        ValidatorInterface $validator,
        SerializerInterface $serializer
    ) {
        $this->em = $em;
        $this->babyRepository = $babyRepository;
        $this->validator = $validator;
        $this->serializer = $serializer;
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
        try {
            /** @var BabyLogLine $babyLogLine */
            $babyLogLine = $this->serializer->deserialize($request->getContent(), BabyLogLine::class, 'json');

            $babyLogLine->setBaby($baby);

            $errors = $this->validator->validate($babyLogLine);

            if (count($errors) > 0) {
                return $this->json($errors, Response::HTTP_BAD_REQUEST);
            }
            $this->em->persist($babyLogLine);
            $this->em->flush();

            return $this->json($babyLogLine);
        } catch (Throwable $e) {
            return $this->json(
                [
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
