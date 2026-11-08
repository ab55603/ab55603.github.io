<?php

/** @var \App\Model\MusicScore $score */
/** @var \App\Service\Router $router */

$title = "Edit Score {$score->getTitle()} ({$score->getId()})";
$bodyClass = "edit";

ob_start(); ?>
    <h1><?= $title ?></h1>
    <form action="<?= $router->generatePath('musicscore-edit') ?>" method="post" class="edit-form">
        <?php require __DIR__ . DIRECTORY_SEPARATOR . '_form.html.php'; ?>
        <input type="hidden" name="action" value="musicscore-edit">
        <input type="hidden" name="id" value="<?= $score->getId() ?>">
    </form>

    <ul class="action-list">
        <li>
            <a href="<?= $router->generatePath('musicscore-index') ?>">Back to list</a></li>
        <li>
            <form action="<?= $router->generatePath('musicscore-delete') ?>" method="post">
                <input type="submit" value="Delete" onclick="return confirm('Are you sure?')">
                <input type="hidden" name="action" value="musicscore-delete">
                <input type="hidden" name="id" value="<?= $score->getId() ?>">
            </form>
        </li>
    </ul>

<?php $main = ob_get_clean();

include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'base.html.php';
