<?php 
declare (strict_types = 1);

namespace App\Services\Messages;
 
interface SendMessageInterface
{
 
    public static function sendMessage($request, int $id, $report);
 
}