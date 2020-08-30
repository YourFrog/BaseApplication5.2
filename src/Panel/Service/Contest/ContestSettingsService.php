<?php

namespace YourFrog\Panel\Service\Contest;

use YourFrog\Panel\Exceptions\Contest\SettingsNotFoundException;
use YourFrog\Panel\Entity;
use YourFrog\Panel\Repository;

/**
 *  Serwis sprawdzający czy istnieje konfiguracja konkursu
 *
 * @package YourFrog\Panel\Service\Contest
 */
class ContestSettingsService
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
     *
     * @throws SettingsNotFoundException
     */
    public function get(): Entity\Contest\Settings
    {
        $items = $this->repository->findAll();

        if( count($items) == 0 ) {
            throw new SettingsNotFoundException("Settings not found");
        }

        if( count($items) > 1 ) {
            throw new SettingsNotFoundException("Too much contest settings");
        }

        return $items[0];
    }

    /**
     *  Sprawdzenie czy istnieje konfiguracja konkursu
     *
     * @return bool
     */
    public function has(): bool
    {
        $settings = $this->repository->findAll();
        return count($settings) > 0;
    }
}