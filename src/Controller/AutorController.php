<?php

namespace App\Controller;

use App\Entity\Autor;
use App\Form\AutorFormType;
use App\Repository\AutorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/autor')]
class AutorController extends AbstractController
{
    #[Route('/', name: 'autor_index')]
public function index(Request $request, AutorRepository $autorRepository): Response
{
    $search = $request->query->get('search'); // ?search=algo

    if ($search) {
        $autores = $autorRepository->findByNombre($search);
    } else {
        $autores = $autorRepository->findAll();
    }

    return $this->render('autor/index.html.twig', [
        'autores' => $autores,
        'search' => $search
    ]);
}


    #[Route('/nuevo', name: 'autor_nuevo')]
    public function nuevo(Request $request, EntityManagerInterface $em): Response
    {
        $autor = new Autor();
        $form = $this->createForm(AutorFormType::class, $autor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($autor);
            $em->flush();

            $this->addFlash('success', 'Autor creado correctamente ✅');
            return $this->redirectToRoute('autor_index');
        }

        return $this->render('autor/nuevo.html.twig', [
            'formulario' => $form->createView(), // ✅ ARREGLADO
        ]);
    }

   #[Route('/editar/{id}', name: 'autor_editar')]
public function editar(Request $request, int $id, AutorRepository $repo, EntityManagerInterface $em): Response
{
    $autor = $repo->find($id);

    if (!$autor) {
        throw $this->createNotFoundException("❌ Autor no encontrado");
    }

    $form = $this->createForm(AutorFormType::class, $autor);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em->flush();
        $this->addFlash('success', 'Autor actualizado correctamente ✏️');
        return $this->redirectToRoute('autor_index');
    }

    return $this->render('autor/nuevo.html.twig', [
        'formulario' => $form->createView(),
    ]);
}


    #[Route('/eliminar/{id}', name: 'autor_eliminar')]
public function eliminar(int $id, AutorRepository $repo, EntityManagerInterface $em): Response
{
    $autor = $repo->find($id);

    if (!$autor) {
        throw $this->createNotFoundException("❌ Autor no encontrado");
    }

    $em->remove($autor);
    $em->flush();

    $this->addFlash('warning', 'Autor eliminado correctamente 🗑️');
    return $this->redirectToRoute('autor_index');
}

}
