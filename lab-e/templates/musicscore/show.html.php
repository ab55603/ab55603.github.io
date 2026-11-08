<?php

/** @var \App\Model\MusicScore $score */
/** @var \App\Service\Router $router */

$title = "{$score->getComposer()} ({$score->getId()})";
$bodyClass = 'show';

ob_start(); ?>
    <h1><?= $score->getComposer() ?></h1>
    <article>
        <h2><?= htmlspecialchars($score->getTitle()) ?></h2>
        <ul>
            <?php if ($score->getTempo() !== null && $score->getTempo() !== ''): ?>
                <li>
                    <strong>Tempo:</strong>
                    <?= htmlspecialchars((string) $score->getTempo()) ?>
                </li>
            <?php endif; ?>
            <?php if ($score->getTempoModifier() !== null && $score->getTempoModifier() !== ''): ?>
                <li>
                    <strong>Tempo modifier:</strong>
                    <?= htmlspecialchars($score->getTempoModifier()) ?>
                </li>
            <?php endif; ?>
            <?php if ($score->getCatalogueNumber() !== null && $score->getCatalogueNumber() !== ''): ?>
                <li>
                    <strong>Catalogue number:</strong>
                    <?= htmlspecialchars($score->getCatalogueNumber()) ?>
                </li>
            <?php endif; ?>
            <?php if ($score->getInstrument() !== null && $score->getInstrument() !== ''): ?>
                <li>
                    <strong>Instrument:</strong>
                    <?= htmlspecialchars($score->getInstrument()) ?>
                </li>
            <?php endif; ?>
        </ul>
    </article>

    <ul class="action-list">
        <li> <a href="<?= $router->generatePath('musicscore-index') ?>">Back to list</a></li>
        <li><a href="<?= $router->generatePath('musicscore-edit', ['id'=> $score->getId()]) ?>">Edit</a></li>
    </ul>
<?php $main = ob_get_clean();

include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'base.html.php';
