<?php

namespace App\Repositories\Pronology;

use App\Models\Pronology\Pronology;
use App\Repositories\Pronology\PronologyInterface;

class PronologyRepo implements PronologyInterface
{
    public function create(array $data)
    {
        return Pronology::create($data);
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
        return Pronology::find($id);
    }
    public function all()
    {
        $records = Pronology::latest()->get();
        return $records;
    }
}
