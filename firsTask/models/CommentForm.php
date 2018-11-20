<?php
namespace app\models;
use Yii;
use yii\base\Model;
class CommentForm extends Model
{
    public $comment;

    public function rules()
    {
        return [
            [['comment'], 'required'],
            [['comment'], 'string', 'length' => [3,250]]

        ];
    }
    public function saveComment($article_id)
    {
        $comment = new Comment;
        $comment->content = $this->comment;
        $comment->post_id = $article_id;
        $comment->status = 1;
        return $comment->save();
    }
}