<?php 
declare (strict_types = 1);

namespace App\Services;
 
interface MakeCsvFileInterface 
{

    public function getFile(int $id);

    public function sendFile(int $id);
 
}