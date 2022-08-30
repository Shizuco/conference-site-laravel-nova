<?php
declare (strict_types = 1);

namespace App\Services;

use App\Models\Comment;
use App\Services\MakeCsvFileInterface;

class MakeCommentSvcFile implements MakeCsvFileInterface
{
    public static function getFile (int $id){
        $fileName = 'comments.csv';
        $comments = Comment::with('users')->where('report_id', $id)->get();
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0",
        );

        $columns = array('user', 'created_at', 'content');

        $callback = function () use ($comments, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($comments as $comment) {

                $row['user'] = $comment->users['name'] . ' ' . $comment->users['surname'];
                $row['created_at'] = $comment->created_at;
                $row['content'] = $comment->comment;
                fputcsv($file, array($row['user'], $row['created_at'], $row['content']));
            }

            fclose($file);
        };
        $response = [$callback, 200, $headers];
        return $response;
    }

    public static function sendFile(int $id)
    {
        $file = MakeCommentSvcFile::getFile($id);
        return response()->stream($file[0], $file[1], $file[2]);
    }
}