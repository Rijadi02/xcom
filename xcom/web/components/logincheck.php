<?php

error_reporting(0);
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true)
{
  header("location: ../../index.php");
  exit();
}

$error = "";

function err($err,$type = 0,$bold = "")
{
  global $error;
  if($type == 0)
  {
    $error .= "<span style=\"opacity:90%\" class=\"btn btn-danger btn-circle btn-sm\"><i class=\"fa fa-exclamation\"></i></span>⠀";
    $error .= "<b>$bold</b>";
    $error .= " $err";
    $error .="</br></br>";
  }
  elseif($type == 1)
  {
    $error .= "<span style=\"opacity:90%\" class=\"btn btn-success btn-circle btn-sm\"><i class=\"fa fa-check\"></i></span>⠀";
    $error .= "<b>$bold</b>";
    $error .= " $err";
    $error .="</br></br>";
  }
  else
  {
    $error .= "<span style=\"opacity:90%\" class=\"btn btn-warning btn-circle btn-sm\"><i class=\"fa fa-exclamation-triangle\"></i></span>⠀";
    $error .= "<b>$bold</b>";
    $error .= " $err";
    $error .="</br></br>";
  }

}
?>