<?php

require_once __DIR__ . "/Library/Loader/Autoloader.php";
$autoloader = new Autoloader;
$autoloader->register();

$adapter = new PdoAdapter("mysql:dbname=test", "myfancyusername",
    "myhardtoguesspassword");

/*
 *    UnitOfWork prihvaća AbstractDataMapper objekt koji implementira DataMapperInterface. UserMapper nasljeđuje od AbstractDataMapper
 *    UserMapper (odnosno konstruktor AbstractDataMapper) prihvaća PDO objekt (u biti). EntityCollection je u biti, kolekcija koja implementira
 *    EntityCollectionInterface. Dva parametra koja prihvaća UserMapper se spremaju u njegove instancne varijable. Treći parametar je ObjectStorage
 *    koji nasljeđuje od \SplObjectStorage te implementira Countable, Iterator i ArrayAccess sučelja. Ta sučelja primjenjuje ObjectStorageInterface a ne
 *    ObjectStorage klasa.
 *
 *    Važno je spomenuti da u ovom trenutku su samo objekti inicijalizirani te da konstruktori ovih objekata ne pozivaju nijednu svoju metodu.
 *
 *     Nakon toga se načini novi User objekt koji nasljeđuje od AbstractEntity. On također nema svog konstruktora već se inicijalizira pomoću konstruktora
 *     svoje AbstractEntity abstraktne klase. Ova klasa također se inicijalizira sa konstruktorom bazne klase s tim da je polje dopuštenih vrijednosti u polju
 *     definirano u User klasi kao varijabla $this->allowedFields koje onda konstruktor AbstractEntity popunjava sa stvarnim vrijednostima. Ova klasa sadrži sve
 *     metode potrebne za dohvaćanje i setiranje polja tipa name ili email. To je u biti DomainModel klasa koja se popunjava sa vrijdnostima uzetima iz baze.
 *
 *     Nakon inicijaliziranja User objekta (npr. nakon što se novi korisnik prijavio na stranicu), registrirase se u UnitOfWork klasi sa metodom registerNew($user1)
 *     Dakle, registrira ga kao novog objekta koji se treba spremiti u ObjectStorage. Mogu to zamisliti kao spremanje DomainModel objekta u cache. Još jednom,
 *     DomainModel odnosno User (AbstractEntity) sadrži sve podatke koji su pokupljeni sa baze prilikom prijavljivanja korisnika na web stranicu.
 *
 *     Ako se sjećamo, UnitOfWork je inicijaliziran za objektom ObjectStorage u koji se spremaju novi objekti. registerNew(User) poziva metodu
 *     registerEntity(User, STATE_NEW) koji se onda sprema u ObjectStorage koji, ponovno, nasljeđuje od SplObjectStorage putem attach() metode koja
 *     je definirana u ObjectStorageInterface. Kao napomena, attach(), detach() i clear() nisu metode svojstvene SplObjectStorage već se definiraju
 *     u ObjectStorageInterface kao metode s kojima se spremaju objekti u SplObjectStorage. To je vrlo važno za zapamtiti jer podsjeća na Observer pattern.
 *
 *     NAPOMENA: UnitOfWork sadrži konstante STATE_NEW, STATE_CLEAN... koje se šalju kao drugi parametar koji opet prihvaća attach() metoda u SplObjectStorage.
 *     U manualu piše da je drugi parametar attach() metodu, podatak koji se asocira uz ovaj objekt.
 *
 *     UnitOfWork sadrži fetchById() metodu s kojom se opet može dohvatiti podaci iz baze te napravi novi AbstractEntity objekt odnosno User objekt. Prije
 *     nego što se pozove metoda registerDirty(), fetchById() poziva metodu registerClean() koja sprema ovaj podatak (koji može biti null ili User objekt) što
 *     znači da ga sprema u ObjectStorage bez obzira bio to objekt ili ne. U daljnjem tekstu, pretpostavljam da je taj podataka null. On tada setira ime tog
 *     podatka na 'Joe'. Sjeti se da ovaj podatak može biti objekt ili null tako da je moguće da je null podataka ima ime 'Joe'. Nakon toga se poziva metoda
 *     registerDirty() koja sprema ovaj podatak u ObjectStorage sa STATE_DIRTY. Ovdje je nejasno da li se ovaj podatak sprema dvaput (jer se unutrašnje poziva
 *     metoda registerClean i sprema u ObjectStorage te opet nakon poziva registerDirty) ili se jedan podatak prepisuje preko drugog.
 * */
$unitOfWork = new UnitOfWork(new UserMapper($adapter, new EntityCollection), new ObjectStorage);

$user1 = new User(array("name" => "John Doe", "email" => "john@example.com"));
$unitOfWork->registerNew($user1);

$user2 = $unitOfWork->fetchById(1);
$user2->name = "Joe";
$unitOfWork->registerDirty($user2);

$user3 = $unitOfWork->fetchById(2);
$unitOfWork->registerDeleted($user3);

$user4 = $unitOfWork->fetchById(3);
$user4->name = "Julie";

$unitOfWork->commit();