<?php
if (!defined("_ECRIRE_INC_VERSION")) return;

include_spip('inc/cextras');
include_spip('base/coop_membre');

function coop_membre_upgrade($nom_meta_base_version,$version_cible) {

    $maj = array();

    cextras_api_upgrade(coop_membre_declarer_champs_extras(), $maj['create']);

    cextras_api_upgrade(coop_membre_declarer_champs_extras(), $maj['1.1.0']);

    cextras_api_upgrade(coop_membre_declarer_champs_extras(), $maj['1.2.0']);

    cextras_api_upgrade(coop_membre_declarer_champs_extras(), $maj['1.3.0']);

    cextras_api_upgrade(coop_membre_declarer_champs_extras(), $maj['1.4.0']);

    cextras_api_upgrade(coop_membre_declarer_champs_extras(), $maj['1.5.0']);

    cextras_api_upgrade(coop_membre_declarer_champs_extras(), $maj['1.6.0']);

    cextras_api_upgrade(coop_membre_declarer_champs_extras(), $maj['1.7.0']);

    cextras_api_upgrade(coop_membre_declarer_champs_extras(), $maj['1.8.0']);

    cextras_api_upgrade(coop_membre_declarer_champs_extras(), $maj['1.9.0']);

    cextras_api_upgrade(coop_membre_declarer_champs_extras(), $maj['1.1.0']);

    include_spip('base/upgrade');
    maj_plugin($nom_meta_base_version, $version_cible, $maj);

}

function coop_membre_vider_tables($nom_meta_base_version) {
    cextras_api_vider_tables(coop_membre_declarer_champs_extras());
    effacer_meta($nom_meta_base_version);
}
