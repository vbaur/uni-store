<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Specs\Property;
use AppBundle\Form\Type\Specs\PropertyType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PropertyController extends Controller
{
    /**
     * @Route("/properties", name="property_list")
     */
    public function getPropertiesAction()
    {
        $doc  = $this->getDoctrine();
        $repo = $doc->getRepository('AppBundle:Specs\Property');

        $properties = $repo->findAll();

        return $this->render(
            'AppBundle:properties:property_list.html.twig',
            compact('properties')
        );
    }

    /**
     * @Route("/properties/new", name="property_new")
     */
    public function createNewPropertyAction(Request $request)
    {
        $property = new Property();
        $property->setName('Nauja savybė');

        return $this->genPropertyForm($request, $property);
    }

    /**
     * @Route("/properties/{property}", name="property_edit")
     */
    public function editPropertyAction(Request $request, Property $property)
    {
        return $this->genPropertyForm($request, $property);
    }

    protected function genPropertyForm(Request $request, Property $property)
    {
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($property);
            $em->flush();

            return $this->redirectToRoute('property_list');
        }

        return $this->render('AppBundle:properties:property_form.html.twig', array(
            'form' => $form->createView(),
            'property_name' => $property->getName(),
        ));
    }


    /**
     * @Route("/properties/{property}/remove", name="property_remove")
     */
    public function removePropertyAction(Request $request, Property $property)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($property);
        $em->flush();

        return $this->redirectToRoute('property_list');
    }
}