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
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
        <?php $this->beginBody() ?>
        
        <script>
            var countryUkraine = "<?php echo Yii::t('app', 'Украина'); ?>";
            var currentCategory = "";
            var currentCategoryTitle = "";
        </script>

        <div class="wrap">
            <?php
            NavBar::begin([
                'brandLabel' => '<img src="/img/logo.png" height="49" />',
                'brandUrl' => Yii::$app->homeUrl,
                'brandOptions' => ['style' => 'padding: 1px 0 0 0;'],
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);
            if (Yii::$app->user->isGuest)
                $items = [
                    ['label' => Yii::t('app', 'Вход'), 'url' => ['/auth/login']],
                    ['label' => Yii::t('app', 'Регистрация'), 'url' => ['/auth/register']]
                ];
            else
            {
                $items = [];
                if (Yii::$app->user->identity->role == \app\models\User::ROLE_ADMIN)
                {
                    $items[] = ['label' => Yii::t('app', 'Администрирование'), 'items' => [
                            ['label' => Yii::t('app', 'Категории'), 'url' => ['/admin/category/index']],
                            ['label' => Yii::t('app', 'Пользователи'), 'url' => ['/admin/user/index']],
                            ['label' => Yii::t('app', 'Жалобы'), 'url' => ['/admin/complain/index']]
                    ]];
                }
                $items[] = ['label' => Yii::t('app', 'Искать'), 'url' => ['/site/index'], 'options' => ['class' => (Yii::$app->controller->id == 'site' && Yii::$app->controller->action->id == 'index') ? 'hidable' : '']];
                $items[] = ['label' => Yii::t('app', 'Спросить'), 'url' => ['/'], 'options' => ['id' => 'popup-create-request-show', 'class' => (Yii::$app->controller->id == 'site' && Yii::$app->controller->action->id == 'index') ? 'hidable' : '']];
                $items[] = ['label' => Yii::t('app', 'Шарить'), 'url' => ['/'], 'options' => ['id' => 'popup-create-contact-show', 'class' => (Yii::$app->controller->id == 'site' && Yii::$app->controller->action->id == 'index') ? 'hidable' : '']];
                $items[] = ['label' => Yii::t('app', 'Мой профиль'), 'url' => ['/profile/index']];
                $items[] = ['label' => Yii::t('app', 'X'), 'url' => ['/auth/logout'], 'linkOptions' => ['data-method' => 'post']];
            }
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => $items
            ]);
            NavBar::end();
            ?>

            <div class="container">
                <?php
                if (!Yii::$app->user->isGuest &&
                        Yii::$app->user->identity->isAccountExists('facebook') &&
                        !empty(Yii::$app->user->identity->last_fb_update) &&
                        strtotime(Yii::$app->user->identity->last_fb_update) < time() - Yii::$app->params['friendUpdateInterval']):
                    ?>
                    <div class="alert alert-warning" role="alert">
                    <?= Yii::t('app', 'Что бы обновить список Ваших друзей с Facebook вы можете войти с использованием Facebook аккаунта'); ?>
                    </div>
                <?php endif; ?>
                <?= $content ?>
            </div>
        </div>

        <footer class="footer">
            <div class="container">
                <p class="text-center">&copy; SMISS <?= date('Y') ?></p>
            </div>
        </footer>

        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
