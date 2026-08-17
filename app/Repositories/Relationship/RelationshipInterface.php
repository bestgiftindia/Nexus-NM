<?php

namespace App\Repositories\Relationship;

interface RelationshipInterface
{
    public function create(array $array);
    public function update(array $array, int $id);
    public function delete(int $id);
    public function find(int $id);
    public function all();
}
