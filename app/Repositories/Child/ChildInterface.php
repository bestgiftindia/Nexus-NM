<?php

namespace App\Repositories\Child;

interface ChildInterface
{
    public function create(array $array);
    public function update(array $array, int $id);
    public function delete(int $id);
    public function find(int $id);
    public function all(array $options);
}
