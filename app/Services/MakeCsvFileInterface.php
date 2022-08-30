<?php 

namespace App\Services;
 
interface MakeCsvFileInterface 
{
 
    public static function getFile(int $id);

    public static function sendFile(int $id);
 
}