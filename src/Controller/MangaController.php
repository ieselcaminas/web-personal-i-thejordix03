<?php

namespace App\Controller;

use App\Entity\Manga;
use App\Form\MangaFormType;
use App\Repository\MangaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/manga')]
class MangaController extends AbstractController
{
    #[Route('/', name: 'manga_index')]
public function index(Request $request, MangaRepository $mangaRepository): Response
{
    $search = $request->query->get('search');

    if ($search) {
        $mangas = $mangaRepository->findByTitulo($search);
    } else {
        $mangas = $mangaRepository->findAll();
    }

    return $this->render('manga/index.html.twig', [
        'mangas' => $mangas,
        'search' => $search
    ]);
}


    #[Route('/nuevo', name: 'manga_nuevo')]
    public function nuevo(Request $request, EntityManagerInterface $em): Response
    {
        $manga = new Manga();
        $form = $this->createForm(MangaFormType::class, $manga);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($manga);
            $em->flush();

            $this->addFlash('success', 'Manga creado correctamente ✅');
            return $this->redirectToRoute('manga_index');
        }

        return $this->render('manga/nuevo.html.twig', [
            'formulario' => $form->createView(),
        ]);
    }

    #[Route('/editar/{id}', name: 'manga_editar')]
    public function editar(Request $request, int $id, MangaRepository $repo, EntityManagerInterface $em): Response
    {
        $manga = $repo->find($id);

        if (!$manga) {
            throw $this->createNotFoundException("❌ Manga no encontrado");
        }

        $form = $this->createForm(MangaFormType::class, $manga);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Manga actualizado correctamente ✏️');
            return $this->redirectToRoute('manga_index');
        }

        return $this->render('manga/nuevo.html.twig', [
            'formulario' => $form->createView(), // ✅ Fijo aquí
        ]);
    }

    #[Route('/eliminar/{id}', name: 'manga_eliminar')]
public function eliminar(int $id, MangaRepository $repo, EntityManagerInterface $em): Response
{
    $manga = $repo->find($id);

    if (!$manga) {
        throw $this->createNotFoundException("❌ Manga no encontrado");
    }

    $em->remove($manga);
    $em->flush();

    $this->addFlash('warning', 'Manga eliminado correctamente 🗑️');
    return $this->redirectToRoute('manga_index');
}

}
