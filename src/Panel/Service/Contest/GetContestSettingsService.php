<?php

namespace YourFrog\Panel\Service\Contest;

use Exception;
use YourFrog\Panel\Entity;
use YourFrog\Panel\Repository;
use Doctrine\ORM\EntityManagerInterface;

/**
 *  Serwis wykorzystywany do pobierania ustawień serwisu
 *
 * @package YourFrog\Panel\Service\Contest
 */
class GetContestSettingsService
{
    /**
     * @var Repository\Contest\SettingsRepository
     */
    private $repository;

    /**
     *  Konstruktor
     *
     * @param Repository\Contest\SettingsRepository $repository
     */
    public function __construct(Repository\Contest\SettingsRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     *  Pobranie ustawień zgłoszenia konkursowego
     *
     * @return Entity\Contest\Settings
     */
    public function getSettings(): Entity\Contest\Settings
    {
        $items = $this->repository->findAll();

        if( count($items) > 1 ) {
            throw new Exception("Too much contest settings");
        }

        return $items[0];
    }
}