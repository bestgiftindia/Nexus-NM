<?php

namespace App\Services\Pronology;


use App\Repositories\Pronology\PronologyRepo;

class PronologyService
{

    protected PronologyRepo $pronologyRepo;

    function __construct(PronologyRepo $pronology)
    {
        $this->pronologyRepo = $pronology;
    }

    function createService(array $data)
    {
        return $this->pronologyRepo->create($data);
    }

    function updateService(array $data, int $id)
    {
        return $this->pronologyRepo->update($data, $id);
    }

    function deleteService(int $id)
    {
        return $this->pronologyRepo->delete($id);
    }

    function findService(int $id)
    {
        return $this->pronologyRepo->find($id);
    }

    function allService()
    {
        return $this->pronologyRepo->all();
    }
}
