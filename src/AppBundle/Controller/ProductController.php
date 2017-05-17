<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Entity\Specs\Value;
use AppBundle\Form\Type\Filters\FilterType;
use AppBundle\Form\Type\Filters\SpecFiltersType;
use AppBundle\Form\Type\ProductType;
use AppBundle\Form\Type\Specs\ValueType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ProductController extends Controller
{
    /**
     * @Route("/products", name="product_list")
     */
    public function getProductsAction(Request $request)
    {
        $doc        = $this->getDoctrine();

        $productRepo    = $doc->getRepository('AppBundle:Product');
        $valueRepo      = $doc->getRepository('AppBundle:Specs\Property');
        $categoryRepo   = $doc->getRepository('AppBundle:ProductCategory');

        $submittedFilters = $request->query->all();
        $availableFilters   = $valueRepo->getSpecFilters();

        $products = $productRepo->getAll($submittedFilters);

        $categories = $categoryRepo->findAll();

        $max_price = $productRepo->getMaxPrice();

        return $this->render(
            'AppBundle:products:product_list.html.twig',
            compact(
                'products',
                'availableFilters',
                'submittedFilters',
                'categories',
                'max_price'
            )
        );
    }

    /**
     * @Route("/products/new", name="product_new")
     */
    public function createNewProductAction(Request $request)
    {
        $product = new Product();
        $product->setName('Nauja prekė');
        $product->setDescription('Sukurkite naują prekę');

        return $this->getProductForm($product, $request);
    }

    /**
     * @Route("/products/{product}", name="product_edit")
     */
    public function editProductAction(Product $product, Request $request)
    {
        return $this->getProductForm($product, $request);
    }

    /**
     * @Route("/products/{product}/remove", name="product_remove")
     */
    public function removeProductAction(Product $product, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();

        return $this->redirectToRoute('product_list');
    }

    protected function getProductForm(Product $product, Request $request)
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('product_list');
        }

        return $this->render(
            'AppBundle:products:product_form.html.twig',
            array(
                'form' => $form->createView(),
                'product_name' => $product ? $product->getName() : 'nauja prekė',
                'product_description' => $product ? $product->getDescription() : 'sukurkite naują prekę'
            )
        );
    }

    /**
     * @Route("/products/{product}/specs/new", name="product_spec_new")
     */
    public function addSpecAction(Request $request, Product $product)
    {
        $spec = new Value();
        $spec->setProduct($product);

        return $this->genProductSpecForm($request, $spec);
    }

    /**
     * @Route("/products/{product}/specs/{spec}/edit", name="product_spec_remove")
     */
    public function editSpecAction(Request $request, Product $product ,Value $spec)
    {
        return $this->genProductSpecForm($request, $spec);
    }

    protected function genProductSpecForm(Request $request, Value $value)
    {
        $form = $this->createForm(ValueType::class, $value);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($value);
            $em->flush();

            return $this->redirectToRoute('product_edit', array(
                'product' => $value->getProduct()->getId()
            ));
        }

        return $this->render('AppBundle:products/specs:spec_form.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/products/{product}/specs/{spec}/remove", name="product_spec_remove")
     */
    public function removeSpecAction(Product $product ,Value $spec)
    {
        $em = $this->getDoctrine()->getManager();

        if($product->getId() !== $spec->getProduct()->getId()) {
            throw new \Exception("Spec does not belong to this product!");
        }

        $em->remove($spec);
        $em->flush();

        return $this->redirect('product_edit', array(
            'product' => $product->getId()
        ));
    }
}