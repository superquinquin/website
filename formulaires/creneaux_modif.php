<?php

if (!defined("_ECRIRE_INC_VERSION")) return;
include_spip('inc/actions');
include_spip('inc/editer');

/**
 * Chargement des valeurs
 * @return array
 */
function formulaires_creneaux_modif_charger_dist($id_sqq_creneaux)
{

    $result = sql_select(array('bdm_remarques','bdm_ok','nom','prenom','email','type_creneau','creneau1','semaine1','creneau2','semaine2','creneau3','semaine3','creneau_volant','creneau_volant_raison','commission','commission_autre','remarques' ), 'spip_sqq_creneaux' , 'id_sqq_creneaux='.intval($id_sqq_creneaux) );
    while ($row = sql_fetch($result)) {
        $valeurs = array(
            'bdm_remarques' => $row['bdm_remarques'] ,
            'bdm_ok' => $row['bdm_ok'] ,
            'nom' => $row['nom'],
            'prenom' => $row['prenom'],
            'email' => $row['email'],
            'type_creneau' => $row['type_creneau'],
            'creneau1' => $row['creneau1'],
            'semaine1' =>$row['semaine1'],
            'creneau2' => $row['creneau2'],
            'semaine2' =>$row['semaine2'],
            'creneau3' => $row['creneau3'],
            'semaine3' =>$row['semaine3'],
            'creneau_volant'=>$row['creneau_volant'],
            "creneau_volant_raison" =>$row['creneau_volant_raison'],
            "commission" =>$row['commission'],
            "commission_autre" =>$row['commission_autre'],
            'remarques' =>$row['remarques']
        );

    }


    return $valeurs;
}

/**
 * Verifier la saisie
 * on simule des erreurs si on a clique sur annuler
 * @return array
 */
function formulaires_creneaux_modif_verifier_dist($id_creneaux)
{

    $erreurs = array();


    return $erreurs;
}


/**
 * Traitement de la saisie
 */
function formulaires_creneaux_modif_traiter_dist($id_sqq_creneaux)
{


    $res = array();
    $res['editable'] = false;

    $tableau = array(
        'bdm_remarques' => _request('bdm_remarques') ,
        'bdm_ok' => _request('bdm_ok') ,
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
        'remarques' =>_request('remarques'),
	'ladate' => date('Y-m-d H:i:s')
    );


    $resultat_requete = sql_updateq('spip_sqq_creneaux', $tableau,'id_sqq_creneaux='.sql_quote($id_sqq_creneaux));
    if ($resultat_requete) {
        $res['message_ok'] = "Ok modif effectuée " ;
        $res['redirect']= 'spip.php?page=creneaux_liste&triinverse=ladate&var_mode=recalcul';
    }
    else
    {
        $res['message_erreur'] = $res['message_erreur'] . "Problème lors de la mise à jour, merci de réessayer ou de nous contacter par email ou par téléphone. 
(erreur ligne:" . __LINE__ . ")";
    }


    return $res;
}

?>