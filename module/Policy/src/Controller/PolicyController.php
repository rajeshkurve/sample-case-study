<?php
namespace Policy\Controller;

use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Policy\Model\PolicyTable;
use Policy\Form\PolicyForm;
use Policy\Model\Policy;

class PolicyController extends AbstractActionController
{
    private $table;

    public function __construct(PolicyTable $table)
    {
        $this->table = $table;
    }

    /**
     * Listing of polices
     *
     * @return ViewModel
     */

    public function indexAction()
    {
        $paginator = $this->table->fetchAll(true);
        $page = (int) $this->params()->fromQuery('page', 1);
        $page = ($page < 1) ? 1 : $page;
        $paginator->setCurrentPageNumber($page);

        // Set the number of items per page to 10:
        $paginator->setItemCountPerPage(5);

        return new ViewModel(['paginator' => $paginator]);
    }

    /**
     * Add new policy
     *
     * @return PolicyForm|void
     */
    public function addAction()
    {
        $form = new PolicyForm();
        $form->get('submit')->setValue('Save');

        $request = $this->getRequest();

        if (! $request->isPost()) {
            return ['form' => $form];
        }

        $policy = new Policy();
        $form->setData($request->getPost());

        $inputFilters = $policy->getInputFilter();
        $inputFilters->add([
                'name' => 'end_date',
                'validators' => [
                    [
                        'name' => Policy\Validator\PolicyDateValidator::class,
                        'options' => [ 'date' => $form->get('start_date')->getValue() ],
                    ]
                ],
            ]
        );

        $form->setInputFilter($inputFilters);

        if (!$form->isValid( ) ) {
            return ['form' => $form];
        }

        $policy->exchangeArray($form->getData());
        $this->table->savePolicy($policy);

        $this->flashMessenger()->addSuccessMessage(
            'Policy record added successfully.'
        );

        $this->redirect()->toRoute('policy');
    }


    /**
     * Update a policy record
     *
     * @return array|Response|void
     */
    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('policy', ['action' => 'add']);
        }

        // try to fetch policy with given id
        try {
            $policy = $this->table->getPolicy($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('policy', ['action' => 'index']);
        }

        $form = new PolicyForm();
        $form->bind($policy);
        $form->get('submit')->setAttribute('value', 'Save');

        $request = $this->getRequest();
        $viewData = ['id' => $id, 'form' => $form];

        // not a save action, display edit form
        if (! $request->isPost()) {
            return $viewData;
        }

        $inputFilters = $policy->getInputFilter();
        $inputFilters->add([
                'name' => 'end_date',
                'validators' => [
                    [
                        'name' => Policy\Validator\PolicyDateValidator::class,
                        'options' => [ 'date' => $form->get('start_date')->getValue() ],
                    ]
                ],
            ]
        );

        $form->setInputFilter($inputFilters);
        $form->setData($request->getPost());

        // validate data
        if (! $form->isValid()) {
            return $viewData;
        }

        try {
            $this->table->savePolicy($policy);
            $this->flashMessenger()->addSuccessMessage(
                'Policy updated successfully.'
            );

        } catch (\Exception $e) {
            $this->flashMessenger()->addSuccessMessage(
                "Error updating record: " . $e->getMessage()
            );
        }

        // Redirect to policy list after saving
        $this->redirect()->toRoute('policy', ['action' => 'index']);
    }

    /**
     * Delete a policy record
     *
     * @return array|Response
     */
    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->redirect()->toRoute('policy');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->table->deletePolicy($id);
                $this->flashMessenger()->addSuccessMessage(
                    'Policy record deleted successfully.'
                );
            }

            // Redirect to list of policy's
            return $this->redirect()->toRoute('policy');
        }

        return [
            'id'     => $id,
            'policy' => $this->table->getPolicy($id),
        ];
    }
}