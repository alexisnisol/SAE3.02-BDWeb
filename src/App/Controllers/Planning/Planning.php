<?php

namespace App\Controllers\Planning;

use DateTime;
use App\Controllers\Planning\Cours\CaseSimple;
use App\Controllers\Planning\Cours\CaseDouble;
use App\Controllers\Planning\Cours\Cours;
use App\Controllers\Planning\PlanningDB;

class Planning
{
    private int $week;
    private int $year;
    private DateTime $dateDebutSemaine;
    private DateTime $dateFinSemaine;
    private array $planning = [];
    private $id_client;

    public function __construct(int $week, int $year, $id_client)
    {
        $this->week = $week;
        $this->year = $year;
        $this->id_client = $id_client;
        $this->initializeDates();
    }

    /**
     * @throws \DateMalformedStringException
     */
    private function initializeDates(): void
    {
        $dateCourante = new DateTime();
        $dateCourante->setISODate($this->year, $this->week);
        $this->dateDebutSemaine = (clone $dateCourante)->modify('monday this week');
        $this->dateFinSemaine = (clone $dateCourante)->modify('+6 days');
    }

    /**
     * @throws \DateMalformedStringException
     */
    public function generatePlanning(): void
    {
        if($this->id_client){

            $schedule = PlanningDB::getWeeklyScheduleForClient($this->id_client);
        }else{
        $schedule = PlanningDB::getWeeklySchedule();
        }


        foreach ($schedule as $coursData) {
            $dateCours = new DateTime($coursData['dateR']);
            if ($dateCours >= $this->dateDebutSemaine && $dateCours <= $this->dateFinSemaine) {
                $moniteur = $coursData['prenom_moniteur'] . ' ' . $coursData['nom_moniteur'];
                $cours = new Cours(
                    $coursData['jour'],
                    $coursData['heure'],
                    $coursData['duree'],
                    $coursData['nom_cours'],
                    $coursData['niveau'],
                    $coursData['nb_personnes_max'],
                    $moniteur,
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
        $heure = (int)substr($heure, 0, 2);
        $duree = $cours->getDuree();

        if (!in_array($jour, ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'])) {
            return;
        }
        if ($duree !== 1 && $duree !== 2) {
            return;
        }

        if (!isset($this->planning[$jour])) {
            $this->planning[$jour] = [];
        }

        if ($this->planning[$jour][$heure] ?? false) {
            return;
        }

        if ($duree === 2 && isset($this->planning[$jour][$heure+1]) && $this->planning[$jour][$heure + 1] ?? false) {
            return;
        }

        $case = ($duree === 1) ? new CaseSimple() : new CaseDouble();
        $case->addCours($cours);

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
