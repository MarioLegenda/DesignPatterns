
<?php

abstract class CommsManager
{
   abstract function getHeaderText();  // return String
   abstract function getApptEncoder();  // return ApptEncoder
   abstract function getFooterText();  // return String
}

class MegaCommsManager extends CommsManager
{
   public function getHeaderText() {
      echo '<br>Mega Header String<br>';
   }
   
   public function getFooterText() {
      echo '<br>Mega Footer String<br>';
   }
   
   public function getApptEncoder() {
      return new MegaApptEncoder();
   }
}

class BlogsCommsManager extends CommsManager
{
   public function getHeaderText() {
      echo '<br>Blogs Header String<br>';
   }
   
   public function getFooterText() {
      echo '<br>Blogs Footer String<br>';
   }
   
   public function getApptEncoder() {
      return new BlogsApptEncoder();
   }
}




abstract class ApptEncoder
{
   abstract function encode();
}

class MegaApptEncoder extends ApptEncoder
{
   public function encode() {
      echo '<br>MegaAppt Encoded and ready to use<br>';
   }
}

class BlogsApptEncoder extends ApptEncoder
{
   public function encode() {
      echo '<br>BlogsAppt Encoded and ready to use<br>';
   }
}

$communicator = new BlogsCommsManager();
$appt_encoder = $communicator->getApptEncoder();
$appt_encoder->encode();

$communicator->getHeaderText();

/*

Knjiga je postavila problem u kojem se objekt za komunikaciju u mojoj firmi mora prilagodit odnosno enkodirat objektu komunikacije neke druge firme s kojom surađujem. 
Komunikacijski manager moje firme je Appointment te ga trebam enkodirati u BlogsApp ili u MegaApp, ovisno s kojom tvrtkom dogovaram sastanke. Za tu svrhu imam dvije implementacije:
- implementacija koja instancira manager za enkodiranje odnosno manager koji koristi tvrtka sa kojom se surađuje 
- implementacija odvojenih klasa (odvojenih u smislu da ne nasljeđuju od BlogsCommsManager ili MegaComsManager) koje se pozivaju
  od strane komunikacijskih managera u svrhu enkodiranja podataka. 
  
Dakle, kada želim dogovoriti sastanak sa Facebookom koji koristi MegaComms, tada instanciram instancu MegaCommsManager. U njoj se nalazi funkcija getApptEncoder() koja uzima
odgovarajući enkoder s obzizom na to o kojem se manageru radi. Nakon što ga instancira i enkodira, može se nastaviti sa komunikacijom.

*/


?>

<!DOCTYPE HTML>
<html>
<head>
<title></title>
<meta charset="utf-8">

<link rel='stylesheet' type='text/css' href='css/reset.css'>

<script src="js/jquery-1.9.1.min.js"></script>


<style type="text/css">

*
{
   -moz-box-sizing:border-box;
   -webkit-box-sizing:border-box;
   box-sizing:border-box;
   
   font-size:16px;
   font-size:100%;
}

#container
{
   width:95%;
   padding:	.625em	1.0548523%	1.5em;
   margin:0 auto;
}

.main
{
   display:table-cell;
   width:65.8227848%;
   float:left;
}

aside
{
   display:table-cell;
   width:300px;
   border:1px solid red;
}

aside img, .main img
{
   width:100%;
   max-width:100%;
}




.more-stories
{
}

.main h1
{
   font-size:1.5em;
}

.main p
{
   padding:0.5%;
   font-size:1.1em;
}

</style>

</head>
<body id='top'>
</body>

</html>

<?php



?>