<?php
use yii\helpers\Url;
?>
<div class="col-md-4" data-sticky_column>
    <div class="primary-sidebar">

        <aside class="widget">
            <h5 class="widget-title text-uppercase text-center">Облако тегов</h5>

          <?   $tags=$this->context->getPoluparTags(); ?>
            <?php foreach($tags as $tag):?>
                <a href="<?= Url::toRoute(['site/findtag', 'tagname'=>$tag->name]);?>"> <?= $tag->name ?> </a>
            <?php endforeach; ?>

        </aside>

        <aside class="widget border pos-padding">
            <h5 class="widget-title text-uppercase text-center">Недавние комментарии:</h5>
            <ul>
                <?   $comments=$this->context->getLastComments(); ?>
                <?php foreach($comments as $comment): ?>
                    <?= $comment->content ?> <br>
                <?php endforeach; ?>
            </ul>
        </aside>
    </div>
</div>