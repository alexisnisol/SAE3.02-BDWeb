<?php
/**
 * Classe représentant une case double contenant des cours.
 * Hérite de la classe `cases`.
 */
class caseDouble extends cases {
    public function getDuration() {
        return 2; // Durée fixe pour une case double
    }

    public function __repr__() {
        $html = "<div class='case-double' style='grid-row: span 2;'>"; // Span sur 2 lignes
        foreach ($this->cours as $cours) {
            $html .= $cours->__repr__();
        }
        $html .= "</div>";
        return $html;
    }
}
?>
