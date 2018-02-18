<?php

if (!defined("_ECRIRE_INC_VERSION")) return;
include_spip('inc/actions');
include_spip('inc/editer');


/**
 *
CREATE TABLE `spip_sqq_creneaux` (
`id_sqq_creneaux` bigint(21) NOT NULL,
`nom` text NOT NULL,
`prenom` text NOT NULL,
`email` text NOT NULL,
`type_creneau` int(11) NOT NULL DEFAULT '0',
`creneau1` int(11) NOT NULL DEFAULT '0',
`semaine1` int(11) NOT NULL DEFAULT '0',
`creneau2` int(11) NOT NULL DEFAULT '0',
`semaine2` int(11) NOT NULL DEFAULT '0',
`creneau3` int(11) NOT NULL DEFAULT '0',
`semaine3` int(11) NOT NULL DEFAULT '0',
`creneau_volant` int(11) NOT NULL DEFAULT '0',
`creneau_volant_raison` text NOT NULL,
`commission` int(11) NOT NULL DEFAULT '0',
`commission_autre` text NOT NULL,
`remarques` text NOT NULL,
`ladate` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `spip_sqq_creneaux`
ADD PRIMARY KEY (`id_sqq_creneaux`);

ALTER TABLE `spip_sqq_creneaux`
MODIFY `id_sqq_creneaux` bigint(21) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

 */

/**
 * Chargement des valeurs
 * @return array
 */
function formulaires_creneaux_charger_dist()
{

    $valeurs = array(
        'nom' => "",
        'prenom' => '',
        'email' => '',
        'type_creneau' => 1,
        'creneau1' => 0,
        'semaine1' =>0,
        'creneau2' => 0,
        'semaine2' =>0,
        'creneau3' => 0,
        'semaine3' =>0,
        'creneau_volant'=>0,
        "creneau_volant_raison" =>"",
        "commission" =>"",
        "commission_autre" =>"",
        'remarques' =>"",
        '_etapes' => 3
    );

    return $valeurs;
}

/**
 * Verifier la saisie
 * on simule des erreurs si on a clique sur annuler
 * @return array
 */
function formulaires_creneaux_verifier_1_dist()
{

    $erreurs = array();

    if (!_request('nom') ) {
        $erreurs['nom'] = 'Merci de préciser votre nom';
    }
    if (!_request('prenom') ) {
        $erreurs['prenom'] = 'Merci de préciser votre prénom';
    }
    if ( _request('type_creneau')==0 ) {
        $erreurs['type_creneau'] = 'Merci de choisir un type de créneau';
    }

    return $erreurs;
}

function formulaires_creneaux_verifier_2_dist()
{

    $erreurs = array();

    // Test sur les créneaux fixes
    if ( _request('type_creneau')==1 ) {
        if (! _request('creneau1')) {
            $erreurs['creneau1'] = 'Merci de choisir votre premier choix de créneau';
        }
        if (! _request('creneau2')) {
            $erreurs['creneau2'] = 'Merci de choisir votre second choix de créneau';
        }
        if (! _request('semaine1')) {
            $erreurs['semaine1'] = "Si vous n'avez pas de préférence pour la semaine, choisissez l'option 'peu importe'"   ;
        }
        if (! _request('semaine2')) {
            $erreurs['semaine2'] = "Si vous n'avez pas de préférence pour la semaine, choisissez l'option 'peu importe'"   ;
        }
        if ( _request('creneau1') ==  _request('creneau2')) {
            $erreurs['creneau2'] = "Nous allons faire tout notre possible pour prendre en compte votre premier choix. Merci de choisir pour le 2eme choix un créneau horaire différent du premier"   ;
        }

    }

    // Test sur les créneaux volant
    if ( _request('type_creneau')==2 ) {
        if ( !_request('creneau_volant')) {
            $erreurs['creneau_volant'] = "Merci de choisir une raison";
        }
        else {
            if (_request('creneau_volant') == 4 and strlen(_request('creneau_volant_raison')) == 0) {
                $erreurs['creneau_volant_raison'] = "Merci de nous en dire un peu plus sur ce autre";
            }
        }
    }



    return $erreurs;
}

function formulaires_creneaux_verifier_3_dist()
{

    $erreurs = array();



    return $erreurs;
}

/**
 * Traitement de la saisie
 */
function formulaires_creneaux_traiter_dist()
{


    $res = array();
    $res['editable'] = false;

    $tableau = array(
        'nom' => _request('nom') ,
        'prenom' => _request('prenom'),
        'email' => _request('email'),
        'type_creneau' => _request('type_creneau'),
        'creneau1' => _request('creneau1'),
        'semaine1' =>_request('semaine1'),
        'creneau2' => _request('creneau2'),
        'semaine2' =>_request('semaine2'),
        'creneau3' => _request('creneau3'),
        'semaine3' =>_request('semaine3'),
        'creneau_volant'=>_request('creneau_volant'),
        "creneau_volant_raison" =>_request('creneau_volant_raison'),
        "commission" =>_request('commission'),
        "commission_autre" =>_request('commission_autre'),
        'remarques' =>_request('remarques')
    );

    // insertion dans la table form9
    $resultat_requete = sql_insertq('spip_sqq_creneaux', $tableau);
    if ($resultat_requete) {
        $res['message_ok'] = "Merci ! L'équipe du Bureau des Membres va pouvoir maintenant s'atteler au casse tête des plannings. Nous respecterons au maximum le choix que vous avez fait." ;

    }
    else
    {
        $res['message_erreur'] = $res['message_erreur'] . "Problème lors de la mise à jour, merci de réessayer ou de nous contacter par email ou par téléphone. 
(erreur ligne:" . __LINE__ . ")";
    }


    return $res;
}

?>