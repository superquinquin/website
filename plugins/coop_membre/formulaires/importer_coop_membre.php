<?php

if (!defined('_ECRIRE_INC_VERSION')) return;

include_spip('inc/session');

/**
 * Declarer les champs postes et y integrer les valeurs par defaut
 */
function formulaires_importer_coop_membre_charger_dist()
{
    $valeurs = array(
        'file_import' => '',
    );


    return $valeurs;
}

/**
 * Verifier les champs postes et signaler d'eventuelles erreurs
 */
function formulaires_importer_coop_membre_verifier_dist()
{
    $erreurs = array();
    $filename = '';
    if (_request('go')) {
        $filename = session_get('importer_coop_membre::tmpfilename');
    } else {
        $files = importer_coop_membre_file();
        if (is_string($files)) // erreur
        {
            $erreurs['file_import'] = $files;
        } else {
            $files = reset($files);
            $filename = _DIR_TMP . basename($files['tmp_name']);
            move_uploaded_file($files['tmp_name'], $filename);
            session_set('importer_coop_membre::tmpfilename', $filename);
            session_set('importer_coop_membre::filename', $files['name']);
        }
    }

    if (!$filename) {
        $erreurs['file_import'] = _T('info_obligatoire');
    } elseif (!_request('go')) {
        $importer_csv = charger_fonction("importer_csv", "inc");
        $test = importer_coop_membre_data($filename);
        $head = array_keys(reset($test));

        $erreurs['test'] = "\n";
        $erreurs['test'] .= "|{{" . implode("}}|{{", $head) . "}}|\n";
        $nbmax = 10;
        $count = count($test);
        while ($row = array_shift($test) AND $nbmax--) {
            $erreurs['test'] .= "|" . implode("|", $row) . "|\n";
        }
        $erreurs['test'] .= "\n\n";
        $erreurs['test'] .= "<p class='explication'>"._T('coop_membre:nb_total')." : {$count}</p>";
        $erreurs['message_erreur'] = '';
    }

    return $erreurs;
}

/**
 * Traiter les champs postes
 */
function formulaires_importer_coop_membre_traiter_dist()
{
    refuser_traiter_formulaire_ajax();// pour recharger toute la page


    $res = array('editable' => true);

    $filename = session_get('importer_coop_membre::tmpfilename');
    $r = importer_coop_membre_importe($filename);

    $message =
        sinon(
            singulier_ou_pluriel($r['count'], 'mailsubscriber:info_1_mailsubscriber',
                'mailsubscriber:info_nb_mailsubscribers'),
            _T('mailsubscriber:info_aucun_mailsubscriber')
        );
    if (count($r['erreurs'])) {
        $message .= "<p>Erreurs : <br />" . implode("<br />", $r['erreurs']) . "</p>";
        $res['message_erreur'] = $message;
    } else {
        $res['message_ok'] = _T('coop_membre:nb_total') . " : ". $r['count_total']."<br/>";
        $res['message_ok'] .= _T('coop_membre:nb_deja') .  " : ". $r['count_deja']."<br/>";
        $res['message_ok'] .= _T('coop_membre:nb_pasdemail') .  " : ". $r['count_pasdemail']."<br/>";
        $res['message_ok'] .= _T('coop_membre:nb_ok') .  " : ". $r['count_ok']."<br/>";
    }


    return $res;
}


function importer_coop_membre_file()
{
    static $files = array();
    // on est appele deux fois dans un hit, resservir ce qu'on a trouve a la verif
    // lorsqu'on est appelle au traitement

    if (count($files)) {
        return $files;
    }

    $post = isset($_FILES) ? $_FILES : $GLOBALS['HTTP_POST_FILES'];
    $files = array();
    if (is_array($post)) {
        include_spip('action/ajouter_documents');
        include_spip('inc/joindre_document');

        foreach ($post as $file) {
            if (is_array($file['name'])) {
                while (count($file['name'])) {
                    $test = array(
                        'error' => array_shift($file['error']),
                        'name' => array_shift($file['name']),
                        'tmp_name' => array_shift($file['tmp_name']),
                        'type' => array_shift($file['type']),
                    );
                    if (!($test['error'] == 4)) {
                        if (is_string($err = joindre_upload_error($test['error']))) {
                            return $err;
                        } // un erreur upload
                        if (!is_array(verifier_upload_autorise($test['name']))) {
                            return _T('medias:erreur_upload_type_interdit', array('nom' => $test['name']));
                        }
                        $files[] = $test;
                    }
                }
            } else {
                //UPLOAD_ERR_NO_FILE
                if (!($file['error'] == 4)) {
                    if (is_string($err = joindre_upload_error($file['error']))) {
                        return $err;
                    } // un erreur upload
                    if (!is_array(verifier_upload_autorise($file['name']))) {
                        return _T('medias:erreur_upload_type_interdit', array('nom' => $file['name']));
                    }
                    $files[] = $file;
                }
            }
        }
        if (!count($files)) {
            return _T('medias:erreur_indiquez_un_fichier');
        }
    }

    return $files;
}

/*
 *
 * Exemple d'un fichier :
 * barcode,sex,surname,name,email,city,is_deceased,is_member,is_worker_member,is_associated_people
 * 0420001000005,m, Maxime,DURAND,maximedur@gmail.com,Hellemmes Lille,False,True,True,False
 */
function importer_coop_membre_data($filename)
{

    $header = true;
    $importer_csv = charger_fonction("importer_csv", "inc");

    // lire la premiere ligne et voir si elle contient 'email' pour decider si entete ou non
    if ($handle = @fopen($filename, "r")) {
        $line = fgets($handle, 4096);
        if (!$line OR stripos($line, 'email') === false) {
            $header = false;
        }
        @fclose($handle);
    }

    $data_raw = $importer_csv($filename, $header, ",", '"', null);
    // verifier qu'on a pas affaire a un fichier avec des fins de lignes Windows mal interpretes
    // corrige le cas du fichier texte 1 colonne, c'est mieux que rien
    if (count($data_raw) == 1
        AND count(reset($data_raw)) == 1
    ) {
        $d = reset($data_raw);
        $d = reset($d);
        $d = explode("\r", $d);
        $d = array_map('trim', $d);
        $d = array_filter($d);
        if (count($d) > 1) {
            $data_raw = array();
            foreach ($d as $v) {
                $data_raw[] = array($v);
            }
        }
    }
//	email	amount	currency	date	periodic

    // colonner : si colonne email on prend toutes les colonnes
    // sinon on ne prend que la premiere colonne, comme un email
    $data = array();
    $cpt = 0;
    while ($data_raw AND count($data_raw)) {

        $row = array_shift($data_raw);
        $row = array_combine(array_map('strtolower', array_keys($row)), array_values($row));

        $d = array();
        foreach (array('barcode', 'sex', 'surname', 'name', 'email', 'city', 'is_deceased', 'is_member', 'is_worker_member', 'is_associated_people') as $k) {
            if (isset($row[$k])) {
                $d[$k] = $row[$k];
            }
        }

        // Date coop_membre de la forme : Wed, 29 Nov 2017 07:27:18 +0100
        if (isset($row['date'])) {
            $maDate = new DateTime($row['date']);
            $d['date'] = $maDate->format('Y-m-d h:i:s');

        }

        $data[] = $d;
        $cpt++;
        if ($cpt == 5654654) {
            break;
        }
    }
    return $data;
}

function random($universal_key) {

    $string = "";

    $user_ramdom_key = "(aLABbC0cEd1[eDf2FghR3ij4kYXQl5Um-OPn6pVq7rJs8*tuW9I+vGw@xHTy&#)K]Z%§!M_S";
    srand((double)microtime()*time());
    for($i=0; $i<$universal_key; $i++) {
        $string .= $user_ramdom_key[rand()%strlen($user_ramdom_key)];
    }
    return $string;
}


/**
 *
 * @param string $filename
 * @param array $options
 *   statut
 *   listes
 * @return array
 */
function importer_coop_membre_importe($filename)
{
    $res = array(
        'count_total'=>0,
        'count_deja' => 0,
        'count_pasdemail' => 0,
        'count_ok' => 0,
        'erreurs' => array()
    );

    $data = importer_coop_membre_data($filename);
    include_spip('inc/filtres'); // email_valide

    foreach ($data as $d) {
        $res['count_total']++;
        $email = trim($d['email']);

        // La clef est l'email ... ce qui n'est pas un problème.
        // Si un coopérateur n'a pas d'email, alors pas d'espace membre
        if ($email AND email_valide($email)) {

            // On ne fait pas d'update pour l'instant, on ignore si l'auteur existe déjà
            if ($row = sql_fetsel("id_auteur", "spip_auteurs", "email=" . sql_quote(trim($email)))) {
                $res['count_deja']++;
            }
            else
            {

                //On commence par créer l'auteur
                include_spip('action/editer_auteur');
                $id_auteur = auteur_inserer();


                include_spip('inc/acces');
                include_spip('auth/sha256.inc');


                $pass=random(25);
                $alea_actuel= creer_uniqid();

                $set = array(
                    'nom' => $d['surname']." ".$d['name']  ,
                    'email' => $email,
                    'login' => $email,
                    'htpass' => generer_htpass($pass),
                    'alea_actuel' => $alea_actuel ,
                    'alea_futur' => creer_uniqid(),
                    'pass' => _nano_sha256($alea_actuel .$pass),
                    'low_sec' =>'',
                    'statut' => '6forum'
                );


                // remplir les champs ou les maj

                autoriser_exception('modifier','auteur',$id_auteur);
                autoriser_exception('instituer','auteur',$id_auteur);
                auteur_modifier($id_auteur,$set);

                $ret = auteur_modifier($id_auteur, $set);
                autoriser_exception('modifier','auteur',$id_auteur,false);
                autoriser_exception('instituer','auteur',$id_auteur,false);
                $res['count_ok']++;
            }
        } else {
            // pas d'email on compte quand même
            $res['count_pasdemail']++;

        }
    }


    return $res;
}
