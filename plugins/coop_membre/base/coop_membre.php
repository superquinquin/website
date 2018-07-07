<?php
if (!defined("_ECRIRE_INC_VERSION")) return;

/*
Format actuel de l'export membres odoo :
barcode,sex,surname,name,email,city,is_deceased,is_member,is_worker_member,is_associated_people
0420001000005,m, Max,DUR,max@gmail.com,Lille,False,True,True,False

*/
function coop_membre_declarer_champs_extras($champs = array()) {



    $champs['spip_auteurs']['barcode'] = array(
        'saisie' => 'input',//Type du champ (voir plugin Saisies)
        'options' => array(
            'nom' => 'barcode',
            'label' => 'Code Barre',
            'sql' => "text NOT NULL DEFAULT ''",
            'defaut' => ''
        )
    );


    $champs['spip_auteurs']['sex'] = array(
        'saisie' => 'input',//Type du champ (voir plugin Saisies)
        'options' => array(
            'nom' => 'sex',
            'label' => 'Genre',
            'sql' => "text NOT NULL DEFAULT ''",
            'defaut' => ''
        )
    );

    $champs['spip_auteurs']['surname'] = array(
        'saisie' => 'input',//Type du champ (voir plugin Saisies)
        'options' => array(
            'nom' => 'surname',
            'label' => 'Prénom',
            'sql' => "text NOT NULL DEFAULT ''",
            'defaut' => ''
        )
    );

    $champs['spip_auteurs']['name'] = array(
        'saisie' => 'input',//Type du champ (voir plugin Saisies)
        'options' => array(
            'nom' => 'name',
            'label' => 'Nom',
            'sql' => "text NOT NULL DEFAULT ''",
            'defaut' => ''
        )
    );

    $champs['spip_auteurs']['city'] = array(
        'saisie' => 'input',//Type du champ (voir plugin Saisies)
        'options' => array(
            'nom' => 'city',
            'label' => 'Ville',
            'sql' => "text NOT NULL DEFAULT ''",
            'defaut' => ''
        )
    );

    $champs['spip_auteurs']['is_deceased'] = array(
        'saisie' => 'oui_non',//Type du champ (voir plugin Saisies)
        'options' => array(
            'nom' => 'is_deceased',
            'label' => 'Décédée',
            'sql' => "int(11) NOT NULL DEFAULT '0' ",
            'valeur_oui' => 1,
            'valeur_non' => 0,
            'defaut' => 0
        ),
        'verifier' => array()
    );


    $champs['spip_auteurs']['is_worker_member'] = array(
        'saisie' => 'oui_non',//Type du champ (voir plugin Saisies)
        'options' => array(
            'nom' => 'is_worker_member',
            'label' => 'Travailleur',
            'sql' => "int(11) NOT NULL DEFAULT '0' ",
            'valeur_oui' => 1,
            'valeur_non' => 0,
            'defaut' => 0
        ),
        'verifier' => array()
    );


    $champs['spip_auteurs']['is_associated_people'] = array(
        'saisie' => 'oui_non',
        'options' => array(
            'nom' => 'is_associated_people',
            'label' => 'Adulte associé',
            'sql' => "int(11) NOT NULL DEFAULT '0' ",
            'valeur_oui' => 1,
            'valeur_non' => 0,
            'defaut' => 0
        ),
        'verifier' => array()
    );


    return $champs;

}