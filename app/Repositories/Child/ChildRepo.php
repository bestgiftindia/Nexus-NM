<?php

namespace App\Repositories\Child;

use App\Models\Child\Child;
use App\Repositories\Child\ChildInterface;

class ChildRepo implements ChildInterface
{
    public function create(array $data)
    {
        return Child::create($data);
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
        return Child::find($id);
    }
    public function all(array $options)
    {
        $query = Child::latest();

        if (!empty($options['where'])) {
            $query->where($options['where']);
        }

        $query = $query->get();
        
        return $query;
    }
}
