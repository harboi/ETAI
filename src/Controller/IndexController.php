<?php
/**
 * Created by PhpStorm.
 * User: HBN
 * Date: 01/11/2017
 * Time: 20:06
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

use App\Repository\TransmissionRepository;
use App\Entity\Transmission;


/**
 * @Route("/")
 */
class IndexController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function indexAction()
    {
        return $this->redirectToRoute('admin');
    }
}
