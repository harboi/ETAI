<?php
/**
 * Created by PhpStorm.
 * User: HBN
 * Date: 06/11/2017
 * Time: 22:34
 */

namespace App\Controller;

use App\Entity\Transmission;
use App\Repository\MaisonneeRepository;
use App\Repository\ResidentRepository;
use App\Repository\TransmissionRepository;
use App\Repository\UserRepository;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;
use JavierEguiluz\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use JavierEguiluz\Bundle\EasyAdminBundle\Exception\EntityRemoveException;
use JavierEguiluz\Bundle\EasyAdminBundle\Exception\ForbiddenActionException;
use JavierEguiluz\Bundle\EasyAdminBundle\Exception\NoEntitiesConfiguredException;
use JavierEguiluz\Bundle\EasyAdminBundle\Exception\UndefinedEntityException;
use Symfony\Component\HttpFoundation\JsonResponse;
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

    /**
     * @Route("/calendrier", name="calendar")
     */
    public function calendrierAction()
    {
        try {
            /** @var  ResidentRepository $residentRepo */
            $residentRepo = $this->getDoctrine()->getManager()->getRepository('\App\Entity\Resident');
            $residentList = $residentRepo->getList();
        } catch (\Exception $e) {
            $residentList = null;
        }

        try {
            /** @var  UserRepository $userRepo */
            $userRepo = $this->getDoctrine()->getManager()->getRepository('\App\Entity\User');
            $userList = $userRepo->getList();

        } catch (\Exception $e) {
            $userList = null;
        }

        try {
            /** @var  MaisonneeRepository $maisonneeRepo */
            $maisonneeRepo = $this->getDoctrine()->getManager()->getRepository('\App\Entity\Maisonnee');
            $maisonneeList = $maisonneeRepo->getList();
        } catch (\Exception $e) {
            $maisonneeList = null;
        }

        return $this->render('calendar.html.twig', ['maisonneeList' => $maisonneeList, 'userList' => $userList, 'residentList' => $residentList ]);
    }

    /**
     * @Route("/calendar.json", name="CalendarJson")
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     */
    public function jsonAction(Request $request)
    {
        $startDate = $request->query->get('start');
        $endDate = $request->query->get('end');
        $maisonneeId = $request->query->get('maisonnee');
        $residentId = $request->query->get('resident');
        $personnelId = $request->query->get('personnel');

        try {
            /** @var  TransmissionRepository $repo */
            $repo = $this->getDoctrine()->getManager()->getRepository('\App\Entity\Transmission');
            $transmissionList = (array)$repo->getListFromParameters($startDate, $endDate, $residentId, $personnelId, $maisonneeId);
        } catch (\Exception $e) {
            //return $this->redirect($this->generateUrl('easyadmin'));
            return new JsonResponse([]);
        }

        //map array of transmission to prepare a JSON for Calendar events
        $listForCalendar = array();
        /** @var  Transmission $transmission */
        foreach ($transmissionList as $transmission) {
            $type = $transmission->getType() ? 'Educative':'Soin';
            $url = "./?action=show&entity=$type&id=".$transmission->getId();
            $mappedTransmission = [
                'start' => $transmission->getCreatedAt()->format('Y-m-d H:i:s'),
                'title' => $transmission->getResident()->__toString(),
                'url' => $url,
                'color' => ($type == 'Educative') ? '#16a085':'#d35400'
            ];
            if($type == 'Educative' && !empty($transmission->getAlerteSoin())) {
                $mappedTransmission['borderColor'] = 'red';
            }
            array_push($listForCalendar, $mappedTransmission);
        }

        return new JsonResponse($listForCalendar);
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
            $entity->setOldPassword($encodedPassword);
        }
    }

    public function preUpdatePersonnelEntity($entity)
    {
        if (method_exists($entity, 'setPassword') &&
            method_exists($entity, 'getPassword')) {
            $isAdmin = $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN');
            if (!$isAdmin || empty($entity->getPassword())) {
                $entity->setPassword($entity->getOldPassword());
            } else {
                $encoder = $this->get('security.password_encoder');
                $encodedPassword = $encoder->encodePassword($entity, $entity->getPassword());
                $entity->setPassword($encodedPassword);
                $entity->setOldPassword($encodedPassword);
            }


        }
    }


    /**
     * The method that is executed when the user performs a 'delete' action to
     * remove any entity.
     *
     * @return RedirectResponse
     */
    protected function deleteResidents_actuelsAction()
    {
        $this->dispatch(EasyAdminEvents::PRE_DELETE);

        if ('DELETE' !== $this->request->getMethod()) {
            return $this->redirect($this->generateUrl('easyadmin', array('action' => 'list', 'entity' => $this->entity['name'])));
        }

        $id = $this->request->query->get('id');
        $form = $this->createDeleteForm($this->entity['name'], $id);
        $form->handleRequest($this->request);

        if ($form->isSubmitted() && $form->isValid()) {
            $easyadmin = $this->request->attributes->get('easyadmin');
            $entity = $easyadmin['item'];

            $this->dispatch(EasyAdminEvents::PRE_REMOVE, array('entity' => $entity));

            $this->executeDynamicMethod('preRemove<EntityName>Entity', array($entity));

            try {
                $entity->setIsActive(0);
                $this->em->persist($entity);
                $this->em->flush();
            } catch (ForeignKeyConstraintViolationException $e) {
                throw new EntityRemoveException(array('entity_name' => $this->entity['name'], 'message' => $e->getMessage()));
            }

            $this->dispatch(EasyAdminEvents::POST_REMOVE, array('entity' => $entity));
        }

        $this->dispatch(EasyAdminEvents::POST_DELETE);

        return $this->redirectToReferrer();
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