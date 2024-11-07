<?php

namespace App\Controller;

use App\Entity\Empresa;
use App\Repository\EmpresaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/empresa')]
class EmpresaController extends AbstractController
{
    #[Route('/', name: 'app_empresa_index', methods: ['GET'])]
    public function index(EmpresaRepository $empresaRepository): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $empresas = $empresaRepository->findAll();

        $data = array_map(function ($empresa) {
            return [
                'id' => $empresa->getId(),
                'nome' => $empresa->getNome(),
                'cnpj' => $empresa->getCnpj(),
                'data_criacao' => $empresa->getDataCriacao()->format('Y-m-d H:i:s'),
            ];
        }, $empresas);

        return new JsonResponse($data);
    }

    #[Route('/new', name: 'app_empresa_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $data = json_decode($request->getContent(), true);

        if (!isset($data['nome']) || !isset($data['cnpj'])) {
            return new JsonResponse(['error' => 'Campos nome e cnpj são obrigatórios'], 400);
        }

        $empresa = new Empresa();
        $empresa->setNome($data['nome']);
        $empresa->setCnpj($data['cnpj']);
        $empresa->setDataCriacao(new \DateTime());

        $entityManager->persist($empresa);
        $entityManager->flush();

        return new JsonResponse([
            'id' => $empresa->getId(),
            'nome' => $empresa->getNome(),
            'cnpj' => $empresa->getCnpj(),
            'data_criacao' => $empresa->getDataCriacao()->format('Y-m-d H:i:s'),
        ], 201);
    }

    #[Route('/{id}', name: 'app_empresa_show', methods: ['GET'])]
    public function show(Empresa $empresa): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return new JsonResponse([
            'id' => $empresa->getId(),
            'nome' => $empresa->getNome(),
            'cnpj' => $empresa->getCnpj(),
            'data_criacao' => $empresa->getDataCriacao()->format('Y-m-d H:i:s'),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_empresa_edit', methods: ['PUT'])]
    public function edit(Request $request, Empresa $empresa, EntityManagerInterface $entityManager): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $data = json_decode($request->getContent(), true);

        if (isset($data['nome'])) {
            $empresa->setNome($data['nome']);
        }
        if (isset($data['cnpj'])) {
            $empresa->setCnpj($data['cnpj']);
        }

        $entityManager->flush();

        return new JsonResponse([
            'id' => $empresa->getId(),
            'nome' => $empresa->getNome(),
            'cnpj' => $empresa->getCnpj(),
            'data_criacao' => $empresa->getDataCriacao()->format('Y-m-d H:i:s'),
        ]);
    }

    #[Route('/{id}', name: 'app_empresa_delete', methods: ['DELETE'])]
    public function delete(Empresa $empresa, EntityManagerInterface $entityManager): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $entityManager->remove($empresa);
        $entityManager->flush();

        return new JsonResponse(['status' => 'Empresa deletada com sucesso']);
    }
}
