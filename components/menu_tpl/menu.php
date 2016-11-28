<li>
    <a href="<?php echo \yii\helpers\Url::to(['category/view', 'id' => $category['id']])?>">
        <?php echo $category['name']?>
        <?php if(isset($category['childs'])){ ?>
            <span class="badge pull-right"><i class="fa fa-plus"></i></span>
        <?php } ?>
    </a>
    <?php if(isset($category['childs'])){ ?>
        <ul>
            <?= $this->getMenuHtml($category['childs'])?>
        </ul>
    <?php } ?>
</li>

