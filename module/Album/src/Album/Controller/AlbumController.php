<?php
namespace Album\Controller;

use Album\Entity\Album;
use Album\Form\AlbumForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AlbumController extends AbstractActionController
{
    /** @var \Doctrine\ORM\EntityManager */
    protected $entityManager;

    /** @var \Album\Entity\AlbumRepository */
    protected $albumRepository;


    /**
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getEntityManager()
    {
        if (is_null($this->entityManager)) {
            $this->entityManager = $this->getServiceLocator()->get('\Doctrine\ORM\EntityManager');
        }

        return $this->entityManager;
    }


    /**
     * @return \Album\Entity\AlbumRepository
     */
    protected function getAlbumRepository()
    {
        if (is_null($this->albumRepository)) {
            $this->albumRepository = $this->getEntityManager()->getRepository('\Album\Entity\Album');
        }

        return $this->albumRepository;
    }


    /**
     * @return ViewModel
     */
    public function indexAction()
    {
        $albums = $this->getAlbumRepository()->findAll();

        return new ViewModel(array(
            'albums' => $albums
        ));
    }


    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function addAction()
    {
        $form = new AlbumForm();
        $form->get('submit')->setValue('Add');

        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();

        if ($request->isPost()) {
            $response = $this->executeAddIfValid($form);
            if ($response !== false) {
                return $response;
            }
        }

        return new ViewModel(array(
            'form' => $form
        ));
    }


    /**
     * @param \Album\Form\AlbumForm $form
     * @return bool|\Zend\Http\Response
     */
    protected function executeAddIfValid($form)
    {
        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();

        $album = new Album();

        $form->setHydrator($this->getAlbumRepository()->getHydrator());
        $form->bind($album);
        $form->setData($request->getPost());

        if ($form->isValid()) {
            $this->getEntityManager()->persist($album);
            $this->getEntityManager()->flush();

            // Redirect to list of albums
            return $this->redirect()->toRoute('album');
        }

        return false;
    }


    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function editAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->redirect()->toRoute(
                'album',
                array(
                    'action' => 'add'
                )
            );
        }

        $album = $this->getAlbumRepository()->find($id);

        if (!($album instanceof Album)) {
            return $this->redirect()->toRoute(
                'album',
                array(
                    'action' => 'add'
                )
            );
        }

        $form = new AlbumForm();
        $form->get('submit')->setAttribute('value', 'Edit');

        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();

        if ($request->isPost()) {
            $valid = $this->executeEditIfValid($album, $form);
            if ($valid !== false) {
                return $valid;
            }
        }

        return new ViewModel(array(
            'id' => $id,
            'form' => $form,
        ));
    }


    /**
     * @param \Album\Entity\Album $album
     * @param \Album\Form\AlbumForm $form
     * @return bool|\Zend\Http\Response
     */
    protected function executeEditIfValid($album, $form)
    {
        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();

        $form->setHydrator($this->getAlbumRepository()->getHydrator());
        $form->bind($album);
        $form->setData($request->getPost());

        if ($form->isValid()) {
            $this->getEntityManager()->persist($album);
            $this->getEntityManager()->flush();

            // Redirect to list of albums
            return $this->redirect()->toRoute('album');
        }

        return false;
    }


    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function deleteAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->redirect()->toRoute('album');
        }

        $album = $this->getAlbumRepository()->find($id);

        if (!($album instanceof Album)) {
            return $this->redirect()->toRoute('album');
        }

        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();

        if ($request->isPost()) {
            return $this->executeDeleteIfValid();
        }

        return new ViewModel(array(
            'id' => $id,
            'album' => $album
        ));
    }


    /**
     * @return \Zend\Http\Response
     */
    protected function executeDeleteIfValid()
    {
        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();

        $del = $request->getPost('del', 'No');

        if ($del === 'Yes') {
            $id = (int)$request->getPost('id');
            $album = $this->getAlbumRepository()->find($id);

            if (!($album instanceof Album)) {
                return $this->redirect()->toRoute('album');
            }

            $this->getEntityManager()->remove($album);
            $this->getEntityManager()->flush();
        }

        // Redirect to list of albums
        return $this->redirect()->toRoute('album');
    }
}