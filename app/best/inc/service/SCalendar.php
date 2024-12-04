<?php

use Google\Client;
use Google\Service\Calendar;
use Google\Service\Calendar\AclRule;
use Google\Service\Calendar\AclRuleScope;
use Google\Service\Calendar\Event;

require_once __DIR__.'/../../conf/config.php';
require_once __DIR__ . '/../../../vendor/autoload.php';

class SCalendar {

    private $client;
    private $calendarService;
    private $calendarId;
    /**
     * @var mixed|string
     */
    private $endHour;
    /**
     * @var mixed|string
     */
    private $startHour;
    /**
     * @var mixed|string
     */
    private $startMinute;
    /**
     * @var mixed|string
     */
    private $endMinute;

    private $holidaysStartTime;
    private $holidaysEndTime;

    /**
     * @throws \Google\Exception
     */
    public function __construct() {

        $this->calendarId = CALENDARID;
        $credentialsPath = __DIR__ . '/../../conf/service_account.json';

        $this->client = new Client();
        $this->client->setApplicationName('Google Calendar API PHP');
        $this->client->setScopes(Calendar::CALENDAR);
        $this->client->setAuthConfig($credentialsPath);

        $this->calendarService = new Calendar($this->client);

        list($startHour, $startMinute) = explode(':', STARTTIME);
        list($endHour, $endMinute) = explode(':', ENDTIME);
        $this->startHour = $startHour;
        $this->startMinute = $startMinute;
        $this->endHour = $endHour;
        $this->endMinute = $endMinute;;
        $this->holidaysStartTime = HOLIDAYS_STARTTIME;
        $this->holidaysEndTime = HOLIDAYS_ENDTIME;

    }

    // Fonction pour créer ou mettre à jour un événement si déjà existant, avec gestion des récurrences et des exceptions

    /**
     * @throws DateMalformedStringException
     * @throws \Google\Service\Exception
     */
    public function createEvent($title, $location, $description, $startDate, $isRecurring = false, $exDates = []): string {
        $startDateTime = (new \DateTime($startDate))->setTime($this->startHour, $this->startMinute)->format(DateTimeInterface::RFC3339);
        $endDateTime = (new \DateTime($startDate))->setTime($this->endHour, $this->endMinute)->format(DateTimeInterface::RFC3339);

        $existingEvents = $this->searchInstance($startDateTime, $endDateTime);

        if (!empty($existingEvents)) {
            foreach ($existingEvents as $event) {
                if ($event->getSummary() === $title) {
                    $event->setSummary($title);
                    $event->setLocation($location);
                    $event->setDescription($description);
                    $updatedEvent = $this->calendarService->events->update($this->calendarId, $event->getId(), $event);
                    return $updatedEvent->htmlLink;
                }
            }
        }

        $event = new Event([
            'summary' => $title,
            'location' => $location,
            'description' => $description,
            'start' => [
                'dateTime' => $startDateTime,
                'timeZone' => 'Europe/Paris',
            ],
            'end' => [
                'dateTime' => $endDateTime,
                'timeZone' => 'Europe/Paris',
            ],
        ]);

        if ($isRecurring) {
            $event->setRecurrence(['RRULE:FREQ=WEEKLY;BYDAY=WE']);
        }

        if (!empty($exDates)) {
            $formattedExDates = array_map(function($date) {
                return (new \DateTime($date))->setTime($this->startHour, $this->startMinute)->format('Ymd\THis');
            }, $exDates);
            $event->setRecurrence(array_merge($event->getRecurrence(), ['EXDATE;TZID=Europe/Paris:' . implode(',', $formattedExDates)]));
        }

        $event = $this->calendarService->events->insert($this->calendarId, $event, ['sendUpdates' => 'all']);
        return $event->htmlLink;
    }

    /**
     * @throws \Google\Service\Exception
     * @throws DateMalformedStringException
     */
    public function deleteEventInstance($startDate, $endDate): bool {
        // Convert input dates to the correct format (Y-m-d)
        $startDate = (new DateTime($startDate))->format('Y-m-d');
        $endDate = (new DateTime($endDate))->format('Y-m-d');

        // Search for events using only the date
        $events = $this->searchInstance($startDate, $endDate);

        if (empty($events)) {
            return false;
        }
        $event = $events[0];

        try {
            $this->calendarService->events->delete($this->calendarId, $event->getId(), [
                'sendUpdates' => 'all'
            ]);
            return true;
        } catch (Exception $exception) {
            return false;
        }
    }


    /**
     * @throws \Google\Service\Exception
     * @throws DateMalformedStringException
     */
    public function searchInstance($startDate, $endDate): array {
        $timeMin = (new \DateTime($startDate))->setTime($this->startHour, $this->startMinute)->format(DateTimeInterface::RFC3339);
        $timeMax = (new \DateTime($endDate))->setTime($this->endHour, $this->endMinute)->format(DateTimeInterface::RFC3339);

        // Set search parameters
        $optParams = [
            'timeMin' => $timeMin,
            'timeMax' => $timeMax,
            'singleEvents' => true,
            'orderBy' => 'startTime'
        ];

        $events = $this->calendarService->events->listEvents($this->calendarId, $optParams);

        return $events->getItems();
    }

    /**
     * @throws \Google\Service\Exception
     */
    public function shareCalendar($email, $role = 'reader'): string {
        $rule = new AclRule();
        $scope = new AclRuleScope();
        $scope->setType('user');
        $scope->setValue($email);
        $rule->setScope($scope);
        $rule->setRole($role);

        $this->calendarService->acl->insert($this->calendarId, $rule);

        return "Calendrier partagé avec $email en tant que $role.";
    }

    /**
     * @throws DateMalformedStringException
     */
    public function isDateInRangeRFC3339($date, $dateRanges): bool {
        // Si la date donnée ne contient pas d'heure, on ajoute l'heure par défaut
        if (strpos($date, 'T') === false) {
            $date = (new \DateTime($date))
                ->setTime((int)$this->startHour, (int)$this->startMinute)
                ->format(DateTimeInterface::RFC3339);
        }

        $inputDate = \DateTime::createFromFormat(DateTimeInterface::RFC3339, $date);

        foreach ($dateRanges as $range) {
            // Ajouter les heures de jours fériés si elles ne sont pas dans les ranges
            $rangeStart = strpos($range[0], 'T') === false
                ? (new \DateTime($range[0]))->setTime((int)$this->holidaysStartTime, 0)->format(DateTimeInterface::RFC3339)
                : $range[0];

            $rangeEnd = strpos($range[1], 'T') === false
                ? (new \DateTime($range[1]))->setTime((int)$this->holidaysEndTime, 0)->format(DateTimeInterface::RFC3339)
                : $range[1];

            $startDate = \DateTime::createFromFormat(DateTimeInterface::RFC3339, $rangeStart);
            $endDate = \DateTime::createFromFormat(DateTimeInterface::RFC3339, $rangeEnd);

            if ($inputDate >= $startDate && $inputDate <= $endDate) {
                return true;
            }
        }
        return false;
    }



    /**
     * @throws DateMalformedStringException
     * @throws \Google\Service\Exception
     */
    public function createEventEveryWeekInScheduler($nextWednesday, $holidays): bool {
        if(!$this->isDateInRangeRFC3339($nextWednesday, $holidays)) {
            $formattedDate = (new DateTime($nextWednesday))->format('d/m/Y');
            $title = "Mercredi Croissant";
            $location = "Besançon";
            $description = "Mercredi croissant du " . $formattedDate;

            $this->createEvent($title, $location, $description, $nextWednesday, false);
            return true;
        }
        return false;
    }
}
