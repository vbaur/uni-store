<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ProductCategory;
use AppBundle\Form\Type\ProductCategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends Controller
{
    /**
     * @Route("/categories", name="category_list")
     */
    public function getCategoriesAction()
    {
        $doc  = $this->getDoctrine();
        $repo = $doc->getRepository('AppBundle:ProductCategory');

        $categories = $repo->findAll();

        return $this->render(
            'AppBundle:categories:category_list.html.twig',
            compact('categories')
        );
    }

    /**
     * @Route("/categories/new", name="category_new")
     */
    public function createNewCategoryAction(Request $request)
    {
        $category = new ProductCategory();
        $category->setName('Nauja kategorija');
        $category->setDescription('Sukurkite naują kategoriją');

        return $this->genCategoryForm($request, $category);
    }

    /**
     * @Route("/categories/{category}", name="category_edit")
     */
    public function editCategoryAction(Request $request, ProductCategory $category)
    {
        return $this->genCategoryForm($request, $category);
    }

    protected function genCategoryForm(Request $request, ProductCategory $category)
    {
        $form = $this->createForm(ProductCategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('category_list');
        }

        return $this->render('AppBundle:categories:category_form.html.twig', array(
            'form' => $form->createView(),
            'category_name' => $category->getName(),
            'category_description' => $category->getDescription()
        ));
    }


    /**
     * @Route("/categories/{category}/remove", name="category_remove")
     */
    public function removeCategoryAction(Request $request, ProductCategory $category)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();

        return $this->redirectToRoute('category_list');
    }
}