<?php

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use \app\models\Page;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody();

$guestNavbars = [
    ['label' => 'Home', 'url' => ['/site/index']],
    ['label' => 'Login', 'url' => ['/site/login']],
];

if ( !yii::$app->user->isGuest){

    $pages = Page::find()->all();
    if (!empty($pages)) {
        $items = [];
        foreach ($pages as $page) {
            $items[] = ['label' => $page->title, 'url' => ['page/view', 'id' => $page->id]];
        }
    } else {
        $items[] = ['label' => 'You have no pages'];
    }
    $items = [];
    foreach ($pages as $page) {
        $items[] = ['label' => $page->title, 'url' => ['page/view', 'id' => $page->id]];
    }
    $userNavbars  = [
        ['label' => 'Home', 'url' => ['/site/index']],
        ['label' => 'Page admin', 'url' => ['/page/index']],
        ['label' => 'Pages', 'items' => $items],
        ['label' => 'Profile', 'url' => ['/user/view', 'id' => Yii::$app->user->id]],
        ['label' => 'Admins', 'url' => ['/user/index']],
        '<li>' . Html::beginForm(['/site/logout'],'post') . Html::submitButton('Logout (' . Yii::$app->user->identity->email . ')',['class' => 'btn btn-link logout']). Html::endForm() . '</li>'
    ];
}

?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'My Company',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'ko',
        ],
    ]);
        if( yii::$app->user->isGuest){
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => $guestNavbars,
            ]);

        }else{
                echo Nav::widget([
                    'options' => ['class' => 'navbar-nav navbar-right'],
                    'items' => $userNavbars,
                ]);
        }
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?php if (Yii::$app->session->hasFlash('error')): ?>
            <div class="alert alert-danger alert-dismissable"><?= Yii::$app->session->getFlash('error'); ?></div>
        <?php endif; ?>
        <?php if (Yii::$app->session->hasFlash('success')): ?>
            <div class="alert alert-success alert-dismissable"><?= Yii::$app->session->getFlash('success'); ?></div>
        <?php endif; ?>
        <?= $content ?>
    </div>
</div>
<p></p><p></p>
<footer class="footer">

           <?= Yii::$app->user->isGuest ?
               '<div class="footlink">
                <a href="/site/login">LOGIN</a></div> '
               : ''?>



        <p class="pull-right"><?= Yii::powered() ?></p>

</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
