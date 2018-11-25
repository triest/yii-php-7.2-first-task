<?php

namespace app\models;

use Yii;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property string $title
 * @property string $content
 * @property string $tags
 * @property int $status
 * @property int $author_id
 * @property int $article_id
 * @property string $create_time
 * @property string $update_time
 *
 * @property Comment[] $comments
 * @property User $author
 * @property PostTag[] $postTags
 */
class Post extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'post';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'content'], 'required'],
            [['status', 'author_id', 'article_id'], 'integer'],
            [['status'], 'in', 'range' => [1, 2, 3]],
            [['status'], 'default', 'value' => 1],
            [['create_time', 'update_time'], 'date', 'format' => 'php:Y-m-d-h-mm-s'],
            [['create_time'], 'default', 'value' => date('Y-m-d H:i:s.u ')],
            [['title', 'content', 'tags'], 'string', 'max' => 255],
            [
                ['author_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::className(),
                'targetAttribute' => ['author_id' => 'id']
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'content' => 'Content',
            'tags' => 'Tags',
            'status' => 'Status',
            'author_id' => 'Author ID',
            'article_id' => 'Article ID',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'author_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostTags()
    {
        return $this->hasMany(PostTag::className(), ['post_id' => 'id']);
    }

    public function getItems()
    {
        return $this->hasMany(\phpDocumentor\Reflection\DocBlock\Tag::className(), ['id' => 'tag_id'])
            ->viaTable('post_tag', ['post_id' => 'id']);
    }

    public function getTags()
    {
        return $this->hasMany(Tag::className(), ['id' => 'tag_id'])
            ->viaTable('post_tag', ['post_id' => 'id']);
    }

    public function getTags2($id)
    {
        $tags = null;
        return $tags;
    }

    /*select tags for set tags in admin module*/
    public function getSelectedTags()
    {
        $selectedIds = $this->getTags()->select(['id', 'name'])->asArray()->all();
        return ArrayHelper::getColumn($selectedIds, 'id');
    }

    public function getSelectedComments()
    {
        $selectedIds = $this->getComments()->select([
            'id',
            'content',
            'status',
            'post_id'
        ])->where(['status' => 1])->all();
        return $selectedIds;
    }

    /*select tags for set tags for view */
    public function getSelectedTagsForPost()
    {
        $selectedIds = $this->getTags()->select(['id', 'name'])->all();
        return ArrayHelper::getColumn($selectedIds, 'name');
    }

    public function saveTags($tags)
    {
        if (is_array($tags)) {
            $this->clearCurrentTags();
            foreach ($tags as $tag_id) {
                $tag = Tag::findOne($tag_id);
                $this->link('tags', $tag);
            }
        }
    }

    public function saveStatus($status)
    {
        $this->status = $status;
        return $this->save(false);
    }

    public function getDate()
    {
        return Yii::$app->formatter->asDatetime($this->create_time);
    }

    public static function getAll($pagination = 5)
    {
        $query = Post::find();
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $pagination]);
        return $post = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        $date['post'] = $post;
        return $date;
    }

    public function clearCurrentTags()
    {
        PostTag::deleteAll(['post_id' => $this->id]);
    }

    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['post_id' => 'id']);
    }

    public function getArticleComments()
    {
        return $this->getComments()->where(['status' => 2])->all();
    }

    /*get preview for main page*/
    public function getPreview()
    {
        $text_cut = mb_substr($this->getContent(), 0, 100);
        return $text_cut;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Post
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $content
     * @return Post
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return int
     */
    public function getAuthorId()
    {
        return $this->author_id;
    }

    /**
     * @param int $author_id
     */
    public function setAuthorId($author_id)
    {
        $this->author_id = $author_id;
    }

    /**
     * @return string
     */
    public function getCreateTime()
    {
        return $this->create_time;
    }

    /**
     * @param string $create_time
     */
    public function setCreateTime($create_time)
    {
        $this->create_time = $create_time;
    }

    /**
     * @return string
     */
    public function getUpdateTime()
    {
        return $this->update_time;
    }

    /**
     * @param string $update_time
     */
    public function setUpdateTime($update_time)
    {
        $this->update_time = $update_time;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }
}