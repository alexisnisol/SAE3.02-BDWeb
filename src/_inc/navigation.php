<?php
setlocale(LC_TIME, 'fr_FR.UTF-8', 'fr_FR'); 

$prevWeek = $week - 1;
$nextWeek = $week + 1;
$prevYear = $year;
$nextYear = $year;

// Ajustement pour passer d'une année à l'autre
if ($prevWeek < 1) {
    $prevWeek = 52;
    $prevYear--;
} elseif ($nextWeek > 52) {
    $nextWeek = 1;
    $nextYear++;
}

$dateCourante = new DateTime();
$dateCourante->setISODate($year, $week);

$dateLundi = strftime('%d %B %Y', $dateCourante->getTimestamp());
?>

<div class="week-navigation">
    <a class="arrow-btn" href="?week=<?= $prevWeek ?>&year=<?= $prevYear ?>">◀ Semaine précédente</a>
    <span id="current-week">Semaine <?= $week ?>, <?= $dateLundi ?></span>
    <a class="arrow-btn" href="?week=<?= $nextWeek ?>&year=<?= $nextYear ?>">Semaine suivante ▶</a>
</div>

</div>
<link rel="stylesheet" href="/static/css/navigation.css">
