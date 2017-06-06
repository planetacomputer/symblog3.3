<?php
// src/Blogger/BlogBundle/Controller/BlogController.php

namespace Blogger\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Blogger\BlogBundle\Entity\Blog;

/**
 * Controlador del Blog.
 */
class BlogController extends Controller
{
    /**
     * Muestra una entrada del blog
     */
    public function showAction($id, $slug)
    {
        $em = $this->get('doctrine')->getManager();
        /**
        * Exemple d'insercio
        
        $blog1 = new Blog();
        $blog1->setTitle('A day in paradise - A day with Symfony2');
        $blog1->setBlog('Lorem ipsum dolor sit d us imperdiet justo scelerisque. Nulla consectetur...');
        $blog1->setImage('beach.jpg');
        $blog1->setAuthor('dsyph3r');
        $blog1->setTags('symfony2, php, paradise, symblog');
        $em->persist($blog1);
        $em->flush();
        **/

        $blog = $em->getRepository('BloggerBlogBundle:Blog')->find($id);

        if (!$blog) {
            throw $this->createNotFoundException('Unable to find Blog post');
        }

        $comments = $em->getRepository('BloggerBlogBundle:Comment')
                   ->getCommentsForBlog($blog->getId());

        return $this->render('BloggerBlogBundle:Blog:show.html.twig', array(
            'blog'      => $blog,
        'comments'  => $comments
        ));
    }
}