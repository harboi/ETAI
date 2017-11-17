<?php
/**
 * Created by PhpStorm.
 * User: HBN
 * Date: 06/11/2017
 * Time: 22:34
 */

namespace App\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;
use JavierEguiluz\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use JavierEguiluz\Bundle\EasyAdminBundle\Exception\ForbiddenActionException;
use JavierEguiluz\Bundle\EasyAdminBundle\Exception\NoEntitiesConfiguredException;
use JavierEguiluz\Bundle\EasyAdminBundle\Exception\UndefinedEntityException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/admin")
 */
class AdminController extends BaseAdminController
{
    /**
     * @Route("/accueil", name="accueil")
     */
    public function accueilAction()
    {
        return $this->render('accueil.html.twig');
    }

    public function prePersistEducativeEntity($entity)
    {
        if (method_exists($entity, 'setType')) {
            // transmission educative = 0
            $entity->setType(false);
        }
    }

    public function prePersistSoinEntity($entity)
    {
        if (method_exists($entity, 'setType')) {
            // transmission soin = 0
            $entity->setType(true);
        }
    }

    public function prePersistPersonnelEntity($entity)
    {
        if (method_exists($entity, 'setPassword') &&
            method_exists($entity, 'getPassword')) {
            $password = $entity->getPassword();
            $encoder = $this->get('security.password_encoder');
            $encodedPassword = $encoder->encodePassword($entity, $password);
            $entity->setPassword($encodedPassword);
        }
    }

    public function preUpdatePersonnelEntity($entity)
    {
        if (method_exists($entity, 'setPassword') &&
            method_exists($entity, 'getPassword')) {
            $isAdmin = $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN');
            if (!$isAdmin || empty($entity->getPassword())) {
                $id = $this->request->query->get('id');
                $newEm = $this->get('doctrine.orm.entity_manager');
                // not working, doctrine replaces $entity values by $user values...
                // duplicate password field and copy from duplicated into original password field...
                $newEm->clear();
                $user = $newEm->getRepository('AppBundle:User')->findById($id);
                $entity->setPassword($user->getPassword());
            } else {
                $encoder = $this->get('security.password_encoder');
                $encodedPassword = $encoder->encodePassword($entity, $entity->getPassword());
                $entity->setPassword($encodedPassword);
            }


        }
    }

    public function preUpdateResidents_actuelsEntity($entity)
    {

    }

    /**
     * @Route("/", name="easyadmin")
     * @Route("/", name="admin")
     *
     * The 'admin' route is deprecated since version 1.8.0 and it will be removed in 2.0.
     *
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function indexAction(Request $request)
    {
        $this->initialize($request);


        if (null === $request->query->get('entity')) {
            return $this->redirectToBackendHomepage();
        }

        $action = $request->query->get('action', 'list');
        if (!$this->isActionAllowed($action)) {
            throw new ForbiddenActionException(array('action' => $action, 'entity_name' => $this->entity['name']));
        }

        return $this->executeDynamicMethod($action . '<EntityName>Action');
    }

    /**
     * Utility method which initializes the configuration of the entity on which
     * the user is performing the action.
     *
     * @param Request $request
     */
    protected function initialize(Request $request)
    {
        $this->dispatch(EasyAdminEvents::PRE_INITIALIZE);

        $this->config = $this->get('easyadmin.config.manager')->getBackendConfig();

        if (0 === count($this->config['entities'])) {
            throw new NoEntitiesConfiguredException();
        }

        // this condition happens when accessing the backend homepage and before
        // redirecting to the default page set as the homepage
        if (null === $entityName = $request->query->get('entity')) {
            return;
        }

        if (!array_key_exists($entityName, $this->config['entities'])) {
            throw new UndefinedEntityException(array('entity_name' => $entityName));
        }

        $this->entity = $this->get('easyadmin.config.manager')->getEntityConfiguration($entityName);

        $action = $request->query->get('action', 'list');
        if (!$request->query->has('sortField')) {
            $sortField = isset($this->entity[$action]['sort']['field']) ? $this->entity[$action]['sort']['field'] : $this->entity['primary_key_field_name'];
            $request->query->set('sortField', $sortField);
        }
        if (!$request->query->has('sortDirection')) {
            $sortDirection = isset($this->entity[$action]['sort']['direction']) ? $this->entity[$action]['sort']['direction'] : 'DESC';
            $request->query->set('sortDirection', $sortDirection);
        }

        $this->em = $this->getDoctrine()->getManagerForClass($this->entity['class']);
        $this->request = $request;

        $this->dispatch(EasyAdminEvents::POST_INITIALIZE);
    }

}