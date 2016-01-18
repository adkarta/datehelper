<?php
namespace Adkarta\DateHelper;

class Timezone
{

    /**
     * Get the offset time between from timezone and to timezone.
     * @param        string $from_timezone Timezone departure
     * @param        string $to_timezone   Timezone arrival
     * @return       seconds                Timezone offset
     */
    public function getOffset($from_timezone = "", $to_timezone = "")
    {
        if ($from_timezone === "" || $to_timezone === "") {
            return 0;
        }
        
        try {
            // Create two timezone objects, one for Singapore (Taiwan) and one for
            // Tokyo (Jakarta)
            $dateTimeFromZone = new \DateTimeZone($from_timezone);
            $dateTimeToZone = new \DateTimeZone($to_timezone);

            $date = (new \DateTime())->format('Y-m-d H:i:s');

            $datTimeFrom = new \DateTime($date, $dateTimeFromZone);
            $dateTimeTo = new \DateTime($date, $dateTimeToZone);

            // Calculate the GMT offset for the date/time contained in the $datTimeFrom
            // object, but using the timezone rules as defined for Tokyo
            // ($dateTimeToZone).
            $timeOffset = $dateTimeToZone->getOffset($datTimeFrom);
            $timeOffset2 = $dateTimeFromZone->getOffset($dateTimeTo);

            // Should show int(32400) (for dates after Sat Sep 8 01:00:00 1951 JST).
            $offset = ($timeOffset - $timeOffset2);

            return $offset;
        } catch (\Exception $e) {
            return 0;
        } 
    }

    /**
     * Calculate duration from and to timezone
     * @param        string $from_timezone Timezone departure
     * @param        string $from_datetime departure time
     * @param        string $to_timezone   Timezone arrival
     * @param        string $to_datetime   arrival time
     * @return       integer                duration between departure and arrival in seconds
     */
    public function getDuration($from_timezone = "", $from_datetime = "", $to_timezone = "", $to_datetime = "")
    {
        $start_date = new \DateTime($from_datetime);
        $since_start = $start_date->diff(new \DateTime($to_datetime));

        $minutes = $since_start->days * 24 * 60;
        $minutes += $since_start->h * 60;
        $minutes += $since_start->i;

        $seconds = $minutes * 60;

        $offset = self::getOffset($from_timezone, $to_timezone);

        if ($offset < 0) {
            $seconds = $seconds - $offset;
        } else {
            $seconds = $seconds - $offset;
        }

        return $seconds;
    }

}

?>