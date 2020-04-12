<?php

require_once __DIR__.'/impact.php';

require_once __DIR__.'/severeImpact.php';

$data = array(
  'region' => [
    'name' => 'Africa',
    'avgAge' => 19.7,
    'avgDailyIncomeInUSD' => 5,
    'avgDailyIncomePopulation' => 0.71
  ],
  'periodType' => "days",
  'timeToElapse' => 30,
  'reportedCases' =>50,
  'population' => 50000,
  'totalHospitalBeds' =>45545
);

function covid19ImpactEstimator($data)
{

  $impact = new Impact($data);

  $severeImpact = new SevereImpact($data);


  if ($data['periodType'] == "days") {
    $numberOfDays = $data['timeToElapse'];
  } 
  else if ($data['periodType'] == "weeks") {
    $numberOfDays = 7 * $data['timeToElapse'];
  }
  else if ($data['periodType'] == "months") {
    $numberOfDays = 30 * $data['timeToElapse'];
  }
  else{
    return 'period types include days,weeks and months';
  }

  $impact->currentlyInfected = $data['reportedCases'] * 10;

  $severeImpact->currentlyInfected = $data['reportedCases'] * 50;

  $ipower = $numberOfDays / 3;

  $spower = $numberOfDays / 3;

  $impact->infectionsByRequestedTime = $impact->currentlyInfected * (2**$ipower);

  $severeImpact->infectionsByRequestedTime = $severeImpact->currentlyInfected * (2**$spower);

  $impact->severeCasesByRequestedTime = 0.15 * $impact->infectionsByRequestedTime;

  $severeImpact->severeCasesByRequestedTime = 0.15 * $severeImpact->infectionsByRequestedTime;

  $bedAvailable = 0.35 * $data['totalHospitalBeds'];

  $impact->hospitalBedsByRequestedTime = intval($bedAvailable - $impact->severeCasesByRequestedTime);

  $severeImpact->hospitalBedsByRequestedTime = intval($bedAvailable - $severeImpact->severeCasesByRequestedTime);

  $impact->casesForICUByRequestedTime = 0.05 * $impact->infectionsByRequestedTime;

  $severeImpact->casesForICUByRequestedTime = 0.05 * $severeImpact->infectionsByRequestedTime;

  $impact->casesForVentilatorsByRequestedTime = 0.02 * $impact->infectionsByRequestedTime;

  $severeImpact->casesForVentilatorsByRequestedTime = 0.02 * $severeImpact->infectionsByRequestedTime;

  $impact->dollarsInFlight = ($impact->infectionsByRequestedTime * 0.65) * 1.5 * $numberOfDays;

  $severeImpact->dollarsInFlight = ($severeImpact->infectionsByRequestedTime * 0.65) * 1.5 * $numberOfDays;

}