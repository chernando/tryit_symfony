<?php

namespace TryIt\BlogBundle\Controller;

use TryIt\BlogBundle\Entity\Comment;
use TryIt\BlogBundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class PostController extends Controller
{
    public function indexAction()
    {
        $posts = $this->getDoctrine()
            ->getRepository('TryItBlogBundle:Post')
            ->findBy(array(), array('id' => 'desc'), 7);

        return $this->render('TryItBlogBundle:Post:index.html.twig', array('posts' => $posts));
    }

    public function createAction(Request $request)
    {
        $post = new Post();

        $form = $this->createFormBuilder($post)
            ->add('title', 'text')
            ->add('content', 'textarea')
            ->getForm();

        if ($request->isMethod('POST')) {
            $form->bind($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($post);
                $em->flush();

                return $this->redirect($this->generateUrl('try_it_blog_post_detail', array('post_id' => $post->getId())));
            }
        }

        return $this->render('TryItBlogBundle:Post:create.html.twig', array('form' => $form->createView()));
    }

    public function detailAction($post_id)
    {
        $post = $this->getDoctrine()
            ->getRepository('TryItBlogBundle:Post')
            ->find($post_id);

        if (!$post) {
            throw $this->createNotFoundException();
        }

        $comments = $post->getComments();

        $form = $this->createFormBuilder()
            ->add('content', 'textarea')
            ->getForm()
            ->createView();

        return $this->render('TryItBlogBundle:Post:detail.html.twig',
            array('post' => $post, 'comments'=> $comments, 'form' => $form));
    }
}
