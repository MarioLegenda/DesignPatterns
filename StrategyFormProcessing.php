<?php

/*
 *     Napušten rad na Strategy patternu na primjeru formi jer jednostavno nije napravljen za to. Po dosadašnjoj praksi,
 *     napravljen je da služi kao pomoćni skup objekata nekoj drugoj implementaciji objekata kojima treba funkcionalnost
 *     iz ovog patterna.
 *
 *     npr. u primjeru u knjizi, TextQuestion objekt (što označava tekstualno pitanje) upotrebljava sve markere za odlučivanje
 *     odnosno obradu teksta koji se primi kao input u TextQuestion objekt. TQ objekt pri samoj inicijalizaciji objekta, već ima
 *     marker potreban za obavljanje njegovih zadataka. Također, pattern pretpostavlja druge objekte koji ga upotrebljavaju. Tu su
 *     i VideoQuestion ili neka druga klasa koja bi nasljeđivala od Question.
 *
 *     Kada bi primjenjivali u formu, efikasna primjena ovog patterna tražila bi za svaki od vrsti <input> polja poseban objekt,
 *     koji bi onda birao svoj marker iz Strategy patterna. To nije previše praktično jer bi npr za <input type=text> morao imati
 *     objekt koji bi obrađivao tekst što bi se efikasnije moglo učiniti u jednoj metodi npr. processText() ili slično. Tu se lako
 *     zamisli da bi onda svaki <input> morao prolaziti kroz funkcionalnost Strategy patterna (u mojem primjeru ValidateInput, SanitizeInput...)
 *     za svaki <input type=text> polje koje bi bilo u formi. Iako bi takva implementacija možda doprinijela čitljivosti cjelokupnog koda
 *     pa čak i pomogla budućim proširenjima, ne vjerujem da je to važnije od efikasnosti samog koda iako je primamljujuće.
 * */

$podaci = array(
    'ime' => 'Mario',
    'prezime' => 'Škrlec',
    'mail' => 'maslec.krlec10@gmail.com',
    'lozinka' => 'digital'
);

class ValidateInput
{

}

class SanitizeInput
{

}

class WriteUser
{

}

