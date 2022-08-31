<?php
declare (strict_types = 1);

namespace App\Services\CsvFileContent;

use App\Models\Comment;

class CommentContent
{
    public function get(int $id)
    {
        $comments = Comment::with('users')->where('report_id', $id)->get();
        $columns = ['user', 'created_at', 'content'];
        $file = fopen('php://output', 'w');
        fputcsv($file, $columns);

        foreach ($comments as $comment) {

            $row['user'] = $comment->users['name'] . ' ' . $comment->users['surname'];
            $row['created_at'] = $comment->created_at;
            $row['content'] = $comment->comment;
            fputcsv($file, array($row['user'], $row['created_at'], $row['content']));
        }
        fclose($file);
    }
}
