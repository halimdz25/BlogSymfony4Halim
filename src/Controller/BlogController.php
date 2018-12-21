<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use DateTime;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index()
    {
        $repo = $this->getDoctrine()->getRepository(Article::class);
        $articles = $repo->findAll();
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/",name="home")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function home()
    {
        return $this->render('blog/home.html.twig',
            ['title' => 'Binevenue']);
    }




    /**
     * @Route("/about",name="a propos")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function about()
    {
        return $this->render('blog/about.html.twig',
            ['title' => 'A propos de moi']);
    }



    /**
     * @Route("/blog/edit/{id}", name = "blog_edit", defaults={"id":0})
     * @Route("/blog/new", name = "blog_create")
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function form (?Article $article=null, Request $request, ObjectManager $manager)
    {
        if(!$article) {
            $article = new Article();
        }
       /* $form = $this->createFormBuilder($article)
            ->add('title', textType::class,[
                'attr'=>[
                'placeholder'=>"Titre de l'article"]
            ])
            ->add('content', textType::class,[
                'attr'=>[
                    'placeholder'=>"Contenu de l'article de l'article"]
            ])
            ->add('image', textType::class,[
                'attr'=>[
                    'placeholder'=>"Image de l'article"]
            ])
            ->getForm(); */
       $form = $this->createForm(ArticleType::class,$article);

        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid()){
            if(!$article->getId()) {
                $article->setCreatedAt(new DateTime());
            }

            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('blog_show',['id'=>$article->getId()]);
        }
        return $this->render('blog/create.html.twig',[
            'formArticle'=>$form->createView(),
            'editMode' =>$article->getId() !== null
        ]);
    }

    /**
     * @route("blog/{id}",name="blog_show")
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function show($id)
    {
        $repo = $this->getDoctrine()->getRepository(article::class);
        $article = $repo->find($id);
        return $this->render('blog/show.html.twig',
            ['article' => $article
            ]);

    }

}






