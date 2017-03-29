<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\ProjectCategory;
use AppBundle\Form\ProjectCategoryType;

/**
 * ProjectCategory controller.
 *
 * @Route("/project_category")
 */
class ProjectCategoryController extends Controller {

    /**
     * Lists all ProjectCategory entities.
     *
     * @Route("/", name="project_category_index")
     * @Method("GET")
     * @Template()
     * @param Request $request
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT e FROM AppBundle:ProjectCategory e ORDER BY e.id';
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $projectCategories = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'projectCategories' => $projectCategories,
        );
    }

    /**
     * Creates a new ProjectCategory entity.
     *
     * @Route("/new", name="project_category_new")
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     */
    public function newAction(Request $request) {
        $projectCategory = new ProjectCategory();
        $form = $this->createForm('AppBundle\Form\ProjectCategoryType', $projectCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($projectCategory);
            $em->flush();

            $this->addFlash('success', 'The new projectCategory was created.');
            return $this->redirectToRoute('project_category_show', array('id' => $projectCategory->getId()));
        }

        return array(
            'projectCategory' => $projectCategory,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a ProjectCategory entity.
     *
     * @Route("/{id}", name="project_category_show")
     * @Method("GET")
     * @Template()
     * @param ProjectCategory $projectCategory
     */
    public function showAction(ProjectCategory $projectCategory) {

        return array(
            'projectCategory' => $projectCategory,
        );
    }

    /**
     * Displays a form to edit an existing ProjectCategory entity.
     *
     * @Route("/{id}/edit", name="project_category_edit")
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     * @param ProjectCategory $projectCategory
     */
    public function editAction(Request $request, ProjectCategory $projectCategory) {
        $editForm = $this->createForm('AppBundle\Form\ProjectCategoryType', $projectCategory);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The projectCategory has been updated.');
            return $this->redirectToRoute('project_category_show', array('id' => $projectCategory->getId()));
        }

        return array(
            'projectCategory' => $projectCategory,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a ProjectCategory entity.
     *
     * @Route("/{id}/delete", name="project_category_delete")
     * @Method("GET")
     * @param Request $request
     * @param ProjectCategory $projectCategory
     */
    public function deleteAction(Request $request, ProjectCategory $projectCategory) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($projectCategory);
        $em->flush();
        $this->addFlash('success', 'The projectCategory was deleted.');

        return $this->redirectToRoute('project_category_index');
    }

}
