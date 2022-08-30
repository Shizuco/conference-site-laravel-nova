<?php 
declare (strict_types = 1);

namespace App\Services;
 
interface MakeCsvFileInterface 
{

    public static function getFile(int $id);

    public static function sendFile(int $id);
 
}