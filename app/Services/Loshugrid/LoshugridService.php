<?php

namespace App\Services\Loshugrid;

use App\Repositories\LoshugridRepository;

class LoshugridService
{
    public $loshugridRepo;

    function __construct(LoshugridRepository $loshugridRepository)
    {
        $this->loshugridRepo = $loshugridRepository;
    }
    function createService(array $data)
    {
        return $this->loshugridRepo->create($data);
    }

    function updateService(int $id, array $data)
    {
        return $this->loshugridRepo->update($id, $data);
    }

    function destroyService(int $id)
    {
        return $this->loshugridRepo->delete($id);
    }
}
