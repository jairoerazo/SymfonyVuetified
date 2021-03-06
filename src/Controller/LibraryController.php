<?php
declare(strict_types=1);

namespace App\Controller;

use App\Datatable\LibraryDatatable;
use App\Entity\Library;
use App\Form\LibraryType;
use App\Security\UserVoter;
use App\Datatable\UserDatatable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/library")
 */
class LibraryController extends AbstractController
{
    use CrudControllerTrait;

    /**
     * @Route("/", name="library_index")
     */
    public function index(LibraryDatatable $datatable): Response
    {
        $this->denyAccessUnlessGranted(UserVoter::INDEX);

        return $this->render('library/index.vue.twig', [
            'datatable' => $datatable,
        ]);
    }

    /**
     * @Route("/result", name="library_result")
     */
    public function result(UserDatatable $datatable): Response
    {
        return JsonResponse::create($datatable);
    }

    /**
     * @Route("/{id}", name="library_show", requirements={"id":"\d+"})
     */
    public function show(Library $library): Response
    {
        return $this->render('library/show.vue.twig', [
            'library' => $library,
        ]);
    }

    /**
     * @Route("/new", name="library_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $library = new Library();
        $form = $this->createForm(LibraryType::class, $library);

        if ($this->handleForm($form, $request)) {
            return $this->redirectToRoute('library_index');
        }

        return $this->render('library/new.vue.twig', [
            'library' => $library,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="library_edit",  methods="GET|POST")
     */
    public function edit(Request $request, Library $library): Response
    {
        $this->denyAccessUnlessGranted(UserVoter::EDIT, $library);

        $form = $this->createForm(LibraryType::class, $library);
        if ($this->handleForm($form, $request)) {
            return $this->redirectToRoute('library_show', $library);
//            return $this->redirectToRoute('library_index');
        }

        return $this->render('library/edit.vue.twig', [
            'library' => $library,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="library_delete",  methods="GET|DELETE")
     */
    public function delete(Request $request, Library $library): Response
    {
        $this->denyAccessUnlessGranted(UserVoter::DELETE, $library);

        $form = $this->createDeleteForm($library);
        if ($this->handleDeleteForm($form, $request)) {
            return $this->redirectToRoute('library_index');
        }
        return $this->render('library/delete.vue.twig', [
            'library' => $library,
            'form' => $form->createView(),
        ]);
    }
}