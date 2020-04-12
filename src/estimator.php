<?php

function covid19ImpactEstimator($data)
{

  class Impact{
    protected $currentlyInfected = '';
    protected $infectionsByRequestedTime = '';
    protected $severeCasesByRequestedTime = '';
    protected $hospitalBedsByRequestedTime = '';
    protected $casesForICUByRequestedTime = '';
    protected $casesForVentilatorsByRequestedTime = '';
    protected $dollarsInFlight = '';
  }


  class SevereImpact{
    protected $currentlyInfected = '';
    protected $infectionsByRequestedTime = '';
    protected $severeCasesByRequestedTime = '';
    protected $hospitalBedsByRequestedTime = '';
    protected $casesForICUByRequestedTime = '';
    protected $casesForVentilatorsByRequestedTime = '';
    protected $dollarsInFlight = '';
  }

  $impact = new Impact();

  $severeImpact = new SevereImpact();


  if ("$data->periodType" == "days") {
    $numberOfDays = $data->timeToElapse;
  } 
  else if ("$data->periodType" == "weeks") {
    $numberOfDays = 7 * $data->timeToElapse;
  }
  else if ("$data->periodType" == "months") {
    $numberOfDays = 30 * $data->timeToElapse;
  }
  else{
    return 'period types include days,weeks and months';
  }

  $impact->currentlyInfected = $data->reportedCases * 10;

  $severeImpact->currentlyInfected = $data->reportedCases * 50;

  $ipower = $numberOfDays / 3;

  $spower = $numberOfDays / 3;

  $impact->infectionsByRequestedTime = $data->currentlyInfected * (2**$ipower);

  $severeImpact->infectionsByRequestedTime = $data->currentlyInfected * (2**$spower);

  $impact->severeCasesByRequestedTime = 0.15 * $impact->infectionsByRequestedTime;

  $severeImpact->severeCasesByRequestedTime = 0.15 * $severeImpact->infectionsByRequestedTime;

  $bedAvailable = 0.35 * $data->totalHospitalBeds;

  $impact->hospitalBedsByRequestedTime = intval($bedAvailable - $impact->severeCasesByRequestedTime);

  $severeImpact->hospitalBedsByRequestedTime = intval($bedAvailable - $severeImpact->severeCasesByRequestedTime);

  $impact->casesForICUByRequestedTime = 0.05 * $impact->infectionsByRequestedTime;

  $severeImpact->casesForICUByRequestedTime = 0.05 * $severeImpact->infectionsByRequestedTime;

  $impact->casesForVentilatorsByRequestedTime = 0.02 * $impact->infectionsByRequestedTime;

  $severeImpact->casesForVentilatorsByRequestedTime = 0.02 * $severeImpact->infectionsByRequestedTime;

  $impact->dollarsInFlight = ($impact->infectionsByRequestedTime * 0.65) * 1.5 * $numberOfDays;

  $severeImpact->dollarsInFlight = ($severeImpact->infectionsByRequestedTime * 0.65) * 1.5 * $numberOfDays;

  return $data;
}