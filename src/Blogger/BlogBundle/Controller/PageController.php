<?php
// src/Blogger/BlogBundle/Controller/PageController.php

namespace Blogger\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Blogger\BlogBundle\Entity\Enquiry;
use Blogger\BlogBundle\Form\EnquiryType;
use Symfony\Component\HttpFoundation\Request;

class PageController extends Controller
{
	public function indexAction(){
 		$em = $this->getDoctrine()->getManager();
 	    //Forma alternativa
       /* $blogs = $em->createQueryBuilder()
                    ->select('b')
                    ->from('BloggerBlogBundle:Blog',  'b')
                    ->addOrderBy('b.created', 'DESC')
                    ->getQuery()
                    ->getResult();
		*/
        /*$query = $em->createQuery(
            'SELECT p
            FROM BloggerBlogBundle:Blog p
            ORDER BY p.created DESC'
        );*/
        $blogs = $em->getRepository('BloggerBlogBundle:Blog')->getLatestBlogs();
        //$blogs = $query->getResult();
        return $this->render('BloggerBlogBundle:Page:index.html.twig', array('blogs' => $blogs));
    }

    public function aboutAction()
    {
        return $this->render('BloggerBlogBundle:Page:about.html.twig');
    }

    public function contactAction(Request $request)
	{
		$enquiry = new Enquiry();
	    $form = $this->createForm(EnquiryType::class, $enquiry);
	    if ($request->getMethod() == 'POST') {
	        $form->handleRequest($request);
	        if ($form->isValid()) {
	            /*$message = \Swift_Message::newInstance()
	            ->setSubject('Contact enquiry from symblog')
	            ->setFrom('enquiries@symblog.co.uk')
	            ->setTo($this->container->getParameter('blogger_blog.emails.contact_email'))
	            ->setBody($this->renderView('BloggerBlogBundle:Page:contactEmail.txt.twig', array('enquiry' => $enquiry)));
        	$this->get('mailer')->send($message);*/
        	$myfile = fopen(dirname(__DIR__)."/contactLog.txt", "a");
        	fwrite($myfile, $enquiry->getName()." ".$enquiry->getEmail()."\n");
        	fclose($myfile);
        	
        	$this->get('session')->getFlashBag()->add('blogger-notice', 'Your contact enquiry was successfully sent. Thank you '.$enquiry->getName());
	        return $this->redirect($this->generateUrl('blogger_blog_contact'));
	        }
	    }
	    return $this->render('BloggerBlogBundle:Page:contact.html.twig', array(
	        'form' => $form->createView()
	    ));
	}

    public function sidebarAction()
    {
        $em = $this->getDoctrine()
                   ->getManager();
        $tags = $em->getRepository('BloggerBlogBundle:Blog')
                   ->getTags();
        $tagWeights = $em->getRepository('BloggerBlogBundle:Blog')
                         ->getTagWeights($tags);
        $commentLimit   = $this->container
                           ->getParameter('blogger_blog.comments.latest_comment_limit');
                           
        $latestComments = $em->getRepository('BloggerBlogBundle:Comment')->getLatestComments($commentLimit);
        return $this->render('BloggerBlogBundle:Page:sidebar.html.twig', array(
            'tags' => $tagWeights,
            'latestComments'    => $latestComments
        ));
    }
}