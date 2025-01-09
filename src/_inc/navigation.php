<?php
setlocale(LC_TIME, 'fr_FR.UTF-8', 'fr_FR');

/**
 * Classe pour gérer la navigation entre les semaines.
 */
class WeekNavigator
{
    private int $week;
    private int $year;

    public function __construct(int $week, int $year)
    {
        $this->week = $week;
        $this->year = $year;
    }

    public function getPreviousWeek(): array
    {
        $prevWeek = $this->week - 1;
        $prevYear = $this->year;

        if ($prevWeek < 1) {
            $prevWeek = 52;
            $prevYear--;
        }

        return ['week' => $prevWeek, 'year' => $prevYear];
    }

    public function getNextWeek(): array
    {
        $nextWeek = $this->week + 1;
        $nextYear = $this->year;

        if ($nextWeek > 52) {
            $nextWeek = 1;
            $nextYear++;
        }

        return ['week' => $nextWeek, 'year' => $nextYear];
    }

    public function getCurrentMondayDate(): string
    {
        $dateCourante = new DateTime();
        $dateCourante->setISODate($this->year, $this->week);

        return strftime('%d %B %Y', $dateCourante->getTimestamp());
    }

    public function renderNavigation(): string
    {
        $prev = $this->getPreviousWeek();
        $next = $this->getNextWeek();
        $currentDate = $this->getCurrentMondayDate();

        ob_start();
        ?>
        <div class="week-navigation">
            <a class="arrow-btn" href="?week=<?= $prev['week'] ?>&year=<?= $prev['year'] ?>">◀ Semaine précédente</a>
            <span id="current-week">Semaine <?= $this->week ?>, <?= $currentDate ?></span>
            <a class="arrow-btn" href="?week=<?= $next['week'] ?>&year=<?= $next['year'] ?>">Semaine suivante ▶</a>
        </div>
        <?php
        return ob_get_clean();
    }
}