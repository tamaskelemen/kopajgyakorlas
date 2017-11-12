<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

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
    ['label' => 'Register', 'url' => ['/site/register']],
    ['label' => 'Login', 'url' => ['/site/login']],
];

if ( !yii::$app->user->isGuest){

    $adminNavbars = [
        ['label' => 'Home', 'url' => ['/site/index']],
        ['label' => 'Account (Admin)', 'url' => ['/site/view']],
        ['label' => "Users ", 'url' => ['/admin/users']],
        ['label' => 'Events', 'url' => ['/event/events']],
        '<li>' . Html::beginForm(['/site/logout'],'post') . Html::submitButton('Logout (' . Yii::$app->user->identity->email . ')',['class' => 'btn btn-link logout']). Html::endForm() . '</li>'

    ];

    $userNavbars  = [
        ['label' => 'Home', 'url' => ['/site/index']],
        ['label' => 'Account (User)', 'url' => ['/site/view']],
        ['label' => 'Events', 'url' => ['/event/events']],
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
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
        if( yii::$app->user->isGuest){
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => $guestNavbars,
            ]);

        }else{
            if (yii::$app->user->identity->permission == 1){
                echo Nav::widget([
                    'options' => ['class' => 'navbar-nav navbar-right'],
                    'items' => $adminNavbars,
                ]);
            }else{
                echo Nav::widget([
                    'options' => ['class' => 'navbar-nav navbar-right'],
                    'items' => $userNavbars,
                ]);
            }
        }
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <div class="danger"><?= Yii::$app->session->getFlash('error'); ?></div>
        <div class="row green"><?= Yii::$app->session->getFlash('success'); ?></div>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>