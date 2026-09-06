<?php
$slides = $slides ?? [
    [
        "imagen" => "assets/images/imagen_instituto.png",
        "texto" => "Reservá tu aula fácilmente"
    ],
];
?>

<section class="slider">
    <?php foreach ($slides as $index => $slide): ?>
        <div class="slide <?= $index === 0 ? 'active' : '' ?>">
            <img
                src="<?= BASE_URL . '/' . htmlspecialchars($slide['imagen']) ?>"
                alt="<?= htmlspecialchars($slide['texto']) ?>"
            >

            <div class="slide-overlay">
                <h2><?= htmlspecialchars($slide['texto']) ?></h2>
            </div>
        </div>
    <?php endforeach; ?>

    <?php if (count($slides) > 1): ?>
        <button class="slider-button previous" type="button">
            &#10094;
        </button>

        <button class="slider-button next" type="button">
            &#10095;
        </button>

        <div class="slider-dots">
            <?php foreach ($slides as $index => $slide): ?>
                <button
                    class="dot <?= $index === 0 ? 'active' : '' ?>"
                    type="button"
                    data-slide="<?= $index ?>"
                ></button>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>