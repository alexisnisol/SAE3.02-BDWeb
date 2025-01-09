<?php
require ROOT . '/_inc/db.php';
require ROOT . '/_inc/cours.php';
require ROOT . '/_inc/ConteneurCours.php';
require ROOT . '/_inc/caseDouble.php';
require ROOT . '/_inc/caseSimple.php';

class Planning
{
    private int $week;
    private int $year;
    private DateTime $dateDebutSemaine;
    private DateTime $dateFinSemaine;
    private array $planning = [];

    public function __construct(int $week, int $year)
    {
        $this->week = $week;
        $this->year = $year;
        $this->initializeDates();
        create_tables();
    }

    private function initializeDates(): void
    {
        $dateCourante = new DateTime();
        $dateCourante->setISODate($this->year, $this->week);
        $this->dateDebutSemaine = (clone $dateCourante)->modify('monday this week');
        $this->dateFinSemaine = (clone $dateCourante)->modify('+6 days');
    }

    public function generatePlanning(): void
    {
        $schedule = get_weekly_schedule();


        foreach ($schedule as $coursData) {
            $dateCours = new DateTime($coursData['dateR']);
            if ($dateCours >= $this->dateDebutSemaine && $dateCours <= $this->dateFinSemaine) {
                $cours = new Cours(
                    $coursData['jour'],
                    $coursData['heure'],
                    $coursData['duree'],
                    $coursData['nom_cours'],
                    $coursData['niveau'],
                    $coursData['nb_personnes_max'],
                    $coursData['nom_moniteur'],
                    $coursData['dateR'],
                    $coursData['id_cp']
                    
                );

                $this->addCoursToPlanning($cours);
            }
        }
    }

    private function addCoursToPlanning(Cours $cours): void
    {
        $jour = $cours->getJour();
        $heure = $cours->getHeure();
        $duree = $cours->getDuree();

        if (!in_array($jour, ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'])) {
            return;
        }
        if ($duree !== 1 && $duree !== 2) {
            return;
        }


        if ($this->planning[$jour][$heure] ?? false) {
            return;
        }

        if ($duree === 2 && $this->planning[$jour][$heure + 1] ?? false) {
            return;
        }


        $case = ($duree === 1) ? new CaseSimple() : new CaseDouble();
        $case->addCours($cours);

        if (!isset($this->planning[$jour])) {
            $this->planning[$jour] = [];
        }

        $this->planning[$jour][$heure] = $case;
    }

    public function renderPlanning(): string
    {
        $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
        $planningHtml = '';

        foreach ($this->planning as $jour => $cases) {
            foreach ($cases as $heure => $case) {
                $jourIndex = array_search($jour, $jours) + 2;
                $startRow = $heure - 7;
                $rowSpan = $case->getDuration();

                $planningHtml .= "<div class='course-case' style='grid-column: $jourIndex; grid-row: $startRow / span $rowSpan'>";
                $planningHtml .= $case->__repr__();
                $planningHtml .= "</div>";
            }
        }

        return $planningHtml;
    }
}
?>
