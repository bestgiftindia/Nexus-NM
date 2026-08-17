<?php

namespace App\Repositories\Relationship;

use App\Models\Relationship\Relationship;
use App\Repositories\Relationship\RelationshipInterface;

class RelationshipRepo implements RelationshipInterface
{
    public function create(array $data)
    {
        return Relationship::create($data);
    }
    public function update(array $data, int $id)
    {
        $recordData = $this->find($id);
        return $recordData->update($data);
    }
    public function delete(int $id)
    {
        $recordData = $this->find($id);
        return $recordData->delete();
    }
    public function find(int $id)
    {
        return Relationship::find($id);
    }
    public function all()
    {
        $records = Relationship::latest()->get();
        return $records;
    }
}
