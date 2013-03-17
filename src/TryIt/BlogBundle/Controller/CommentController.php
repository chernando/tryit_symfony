<?php

namespace TryIt\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use TryIt\BlogBundle\Entity\Comment;
use TryIt\BlogBundle\Entity\Post;


class CommentController extends Controller
{
    public function createAction(Request $request, $post_id)
    {
        $comment = new Comment();
        $post = $this->getDoctrine()
            ->getRepository("TryItBlogBundle:Post")
            ->find($post_id);

        $form = $this->createFormBuilder($comment)
            ->add('content', 'textarea')
            ->getForm();

        $form->bind($request);

        if ($request->isMethod('POST')) {
            if ($form->isValid()) {
                $comment->setPost($post);
                $em = $this->getDoctrine()->getManager();
                $em->persist($comment);
                $em->flush();

                return $this->redirect($this->generateUrl('try_it_blog_post_detail', array('post_id' => $post->getId())));
            }
        }

        return $this->render('TryItBlogBundle:Post:detail.html.twig', array('post' => $post, 'form' => $form->createView()));
    }
}
