<?php


namespace UserBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\Rating;
use UserBundle\Entity\Produit;
use UserBundle\Form\RatingType;

class RatingController extends Controller
{
    public function afficher_ratingAction(){
        $rating=$this->getDoctrine()->getRepository(Rating::class)->findAll();

        return $this->render('@User/Rating/afficherrating.html.twig',array("rating"=>$rating));
    }

    public function rating_chasseAction(Request $request,$id){

        $produit=$this->getDoctrine()->getRepository(Produit::class)->findBy(array('id'=>$id));
        $rating = new rating();
        $prod=$produit[0];
        var_dump($prod->getrating());
$somme=0;
$moyenne=0.0;
        $currentUser = $this->container->get('security.token_storage')->getToken()->getUser();
        $rating->setUser($currentUser);
        $rating->setProduit($prod);


        $form = $this->createForm(RatingType::class, $rating);
        $form = $form->handleRequest($request);


        if ($form->isSubmitted()) {
            $prod->setnbrrating($prod->getnbrrating()+1);

            if ($rating->getdegre() == "Très Satisfait")

            {
                $somme = $somme+100;
            }
            if ($rating->getdegre() == "Satisfait") {
                $somme += 75;
            }

            if ($rating->getdegre() == "Passable") {
                $somme += 50;
            }
            if ($rating->getdegre() == "InSatisfait") {
                $somme += 25;
            }
            if ($rating->getdegre() == "Trés InSatisfait") {
                $somme += 0;
            }

            $moyenne = $somme/(100*$rating->getProduit()->getnbrrating());
            $prod->setrating($moyenne);
            $em = $this->getDoctrine()->getManager();
            $em->persist($prod);
            $em->persist($rating);
            $em->flush();
            return $this->redirectToRoute('produit');
        }


        return $this->render('@User/Rating/addratingchasse.html.twig',array("produit"=>$produit,'form' => $form->createView()));


    } public function rating_pecheAction(Request $request,$id){
    $rating = new rating();
        $produit=$this->getDoctrine()->getRepository(Produit::class)->findBy(array('id'=>$id));
    $prod=$produit[0];
    $somme=0;
    $moyenne=0.0;

        $currentUser = $this->container->get('security.token_storage')->getToken()->getUser();
        $rating->setUser($currentUser);
        $rating->setProduit($prod);

        $form = $this->createForm(RatingType::class, $rating);
        $form = $form->handleRequest($request);


    if ($form->isSubmitted()) {

        $prod->setnbrrating($prod->getnbrrating()+1);

        if ($rating->getdegre() == "Très Satisfait")

        {
            $somme = $prod->getsommerating()+100;
        }
        if ($rating->getdegre() == "Satisfait") {
            $somme = $prod->getsommerating()+75;
        }

        if ($rating->getdegre() == "Passable") {
            $somme = $prod->getsommerating()+50;
        }
        if ($rating->getdegre() == "Insatisfait") {
            $somme = $prod->getsommerating()+25;
        }
        if ($rating->getdegre() == "Très InSatisfait") {
            $somme = $prod->getsommerating()+0;
        }
        $prod->setsommerating($somme);
        $moyenne = $prod->getsommerating()/(100*$prod->getnbrrating());

        $prod->setrating($moyenne);
        $em = $this->getDoctrine()->getManager();
        $em->persist($prod);
        $em->persist($rating);
        $em->flush();
        return $this->redirectToRoute('peche');
        }


        return $this->render('@User/Rating/addratingpeche.html.twig',array("produit"=>$produit,'form' => $form->createView()));


    }

}
