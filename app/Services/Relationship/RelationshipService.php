<?php

namespace App\Services\Relationship;


use App\Repositories\Relationship\RelationshipRepo;

class RelationshipService
{

    protected RelationshipRepo $relationshipRepo;

    function __construct(RelationshipRepo $relationship)
    {
        $this->relationshipRepo = $relationship;
    }

    function createService(array $data)
    {
        return $this->relationshipRepo->create($data);
    }

    function updateService(array $data, int $id)
    {
        return $this->relationshipRepo->update($data, $id);
    }

    function deleteService(int $id)
    {
        return $this->relationshipRepo->delete($id);
    }

    function findService(int $id)
    {
        return $this->relationshipRepo->find($id);
    }

    function allService()
    {
        return $this->relationshipRepo->all();
    }
}
