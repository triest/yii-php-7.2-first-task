<?php
/**
 * Created by PhpStorm.
 * User: triest
 * Date: 06.11.2018
 * Time: 21:13
 */

namespace app\models;


class PostForm  extends Model
{
    public $title;
    public $content;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['title', 'content'], 'required'],
            ['rememberMe', 'boolean'],
        ];
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

}