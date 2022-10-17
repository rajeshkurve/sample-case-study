<?php
namespace Policy\Model;

use RuntimeException;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\Sql\Select;
use Laminas\Db\TableGateway\TableGatewayInterface;
use Laminas\Paginator\Adapter\DbSelect;
use Laminas\Paginator\Paginator;

class PolicyTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll($paginated = false)
    {
        if ($paginated) {
            return $this->fetchPaginatedResults();
        }

        return $this->tableGateway->select();
    }

    private function fetchPaginatedResults()
    {
        // Create a new Select object for the table:
        $select = new Select($this->tableGateway->getTable());

        // Create a new result set based on the Policy entity:
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new Policy());

        // Create a new pagination adapter object:
        $paginatorAdapter = new DbSelect(
            $select,
            $this->tableGateway->getAdapter(),
            $resultSetPrototype
        );

        return new Paginator($paginatorAdapter);
    }

    public function getPolicy($id)
    {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(['id' => $id]);
        $row = $rowset->current();
        if (! $row) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $id
            ));
        }

        return $row;
    }

    public function savePolicy(Policy $policy)
    {
        $data = [
            'first_name'    => $policy->firstName,
            'last_name'     => $policy->lastName,
            'policy_number' => $policy->policyNumber,
            'start_date'    => $policy->startDate,
            'end_date'      => $policy->endDate,
            'premium'       => $policy->premium,
        ];

        $id = (int) $policy->id;

        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        try {
            $this->getPolicy($id);
        } catch (RuntimeException $e) {
            throw new RuntimeException(sprintf(
                'Cannot update policy with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }

    public function deletePolicy($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }
}