<?php 

namespace Models;
use \DateTimeImmutable;
use \DateTimeZone;

class Month {

    const MONTH_NAME_FR = ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"];
    const DAY_NAME_FR = ["Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche"];

    private $monthName;
    private $year;
    private $first;
    private $last;

    public function __construct(int $num, int $year) {

        
        $this->setMonthName($num);
        $this->setYear($year);
        $this->setFirst($num);
        $this->setLast();
    }

    public function getMonthName(): string {
        return $this->monthName;
    }

    public function setMonthName(int $num) {
        $num = (($num % 12) === 0)? 12 : $num % 12;
        $this->monthName = Month::MONTH_NAME_FR[abs($num) - 1];
    }

    public function getYear(): int {
        return $this->year;
    }

    public function setYear(int $year) {
        $now = (int) (new DateTimeImmutable)->format("Y");
        $this->year = ($year < $now - 10 || $year > $now + 10)? $now : $year ;
    }

    public function getFirst(): DateTimeImmutable {
        return $this->first;
    }

    public function setFirst(int $num) {
        $num = (($num % 12) === 0)? 12 : $num % 12;
        $this->first = new DateTimeImmutable("{$this->year}-$num-01", new DateTimeZone("europe/paris"));
    }

    public function getLast(): DateTimeImmutable {
        return $this->last;
    }

    public function setLast() {
        $this->last = $this->first->modify('last day of');
    }

    public function getNbWeeks(): int {
        $first = (int) $this->first->format("W");
        $last = (int) $this->last->format("W");
        $last = ($last === 1 && (int) $this->last->format("m") === 12)? 53 : $last;
        return $last - $first + 1;
    }

    public function getFirstMonday(): DateTimeImmutable {
        $first_monday = $this->first->modify('first monday of');
        return ((int) $first_monday->format('d') === 01)? $first_monday : $first_monday->modify('last monday');
    }










}