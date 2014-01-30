<?php
/**
 * Use to:
 * - Metode Locale proiectului
 * - metode care suprscriu metode din core
 * - metode experimentale
 *
 * daca se considera ca metodele vor fi general necesare
 * se trec in Cunstable => Ccore sau in clasa din care fac parte
 *
 */
class CLcore extends Ccore
{

    public function Set_lastURL() {
        $_SESSION['lastURL'] = $_SESSION['lastURL'] == '/' ?: Toolbox::curURL();
    }

}
