<?php

/** @var \App\Model\MusicScore $score */
/** @var \App\Service\Router $router */

$title = 'Create Score';
$bodyClass = "edit";

ob_start(); ?>
    <h1>Create a Music Score</h1>
    <form action="<?= $router->generatePath('musicscore-create') ?>" method="post" class="edit-form">
        <?php require __DIR__ . DIRECTORY_SEPARATOR . '_form.html.php'; ?>
        <input type="hidden" name="action" value="musicscore-create">
    </form>

    <a href="<?= $router->generatePath('musicscore-index') ?>">Back to list</a>
<?php $main = ob_get_clean();

include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'base.html.php';
