<?php

function covid19ImpactEstimator($data)
{

  class impact{
      public $iimpact = "";
  }


  class severeImpact{

      public $ssevereImpact = "";
  }


  if ($data->periodType == "days") {
    $numberOfDays = $data->timeToElapse;
  } 
  else if ($data->periodType == "weeks") {
    $numberOfDays = 7 * $data->timeToElapse;
  }
  if ($data->periodType == "months") {
    $numberOfDays = 30 * $data->timeToElapse;
  }

  $iimpact->currentlyInfected = $data->reportedCases * 10;

  $ssevereImpact->currentlyInfected = $data->reportedCases * 50;

  $ipower = $numberOfDays / 3;

  $spower = $numberOfDays / 3;

  $iimpact->infectionsByRequestedTime = $data->currentlyInfected * (2**$ipower);

  $ssevereImpact->infectionsByRequestedTime = $data->currentlyInfected * (2**$spower);

  $iimpact->severeCasesByRequestedTime = 0.15 * $iimpact->infectionsByRequestedTime;

  $ssevereImpact->severeCasesByRequestedTime = 0.15 * $ssevereImpact->infectionsByRequestedTime;

  $bedAvailable = 0.35 * $data->totalHospitalBeds;

  $iimpact->hospitalBedsByRequestedTime = intval($bedAvailable - $iimpact->severeCasesByRequestedTime);

  $ssevereImpact->hospitalBedsByRequestedTime = intval($bedAvailable - $ssevereImpact->severeCasesByRequestedTime);

  $iimpact->casesForICUByRequestedTime = 0.05 * $iimpact->infectionsByRequestedTime;

  $ssevereImpact->casesForICUByRequestedTime = 0.05 * $ssevereImpact->infectionsByRequestedTime;

  $iimpact->casesForVentilatorsByRequestedTime = 0.02 * $iimpact->infectionsByRequestedTime;

  $ssevereImpact->casesForVentilatorsByRequestedTime = 0.02 * $ssevereImpact->infectionsByRequestedTime;

  $iimpact->dollarsInFlight = ($iimpact->infectionsByRequestedTime * 0.65) * 1.5 * $numberOfDays;

  $ssevereImpact->dollarsInFlight = ($ssevereImpact->infectionsByRequestedTime * 0.65) * 1.5 * $numberOfDays;

  return $data;
}