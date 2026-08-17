<?php

namespace App\Repositories;

use App\Models\Loshugrid\LoshuGrid;
use App\Repositories\Interfaces\LoshugridInterface;
use App\Models\Loshugrid\LoshugridModel;

class LoshugridRepository implements LoshugridInterface
{

    public function getAll() {}
    public function findById(int $id) {
        return LoshuGrid::find($id);
    }
    public function create(array $data)
    {
        return LoshuGrid::create($data);
    }
    public function update(int $id, array $data) {
        $find = $this->findById($id);
        $find->update($data);
        return $find;
    }
    public function delete(int $id) {
        $find = $this->findById($id);
        return $find->delete();
    }
}
