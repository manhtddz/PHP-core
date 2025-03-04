<?php
interface IRepository
{
    public function getAll();
    public function findById(int $id);
    public function search(array $data);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function existedEmail(string $email);
}
?>