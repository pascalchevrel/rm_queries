<?php
/**
 * All the external sources of data
 */
enum External: string
{
    case PD_desktop       = 'https://product-details.mozilla.org/1.0/firefox_versions.json';
    case PD_android       = 'https://product-details.mozilla.org/1.0/mobile_versions.json';
    case Balrog           = 'https://aus-api.mozilla.org/api/v1/rules/';
    case Flatpak_release  = 'https://flathub.org/api/v2/appstream/org.mozilla.firefox';
    case Snap_release     = 'https://api.snapcraft.io/v2/snaps/info/firefox';
    case Play_Store       = 'https://play.google.com/store/apps/details?id=';
    case Samsung_release  = 'https://galaxystore.samsung.com/api/detail/org.mozilla.firefox';
    case Apple_release    = 'https://apps.apple.com/us/app/firefox-private-safe-browser/id989804926';
    case Maven_AS_nightly = 'https://maven.mozilla.org/maven2/org/mozilla/appservices/nightly/full-megazord/maven-metadata.xml';
    case Microsoft_Store  = 'https://displaycatalog.mp.microsoft.com/v7.0/products/lookup?fieldsTemplate=InstallAgent&market=US&languages=en-US,en,neutral&alternateId=PackageFamilyName&value=Mozilla.Firefox_n80bbvh6b1yt2';
}

$firefox_versions = getRemoteJson(
    External::PD_desktop->value,
    'firefox_versions_local.json',
    900
);

$fenix_versions = getRemoteJson(
    External::PD_android->value,
    'mobile_versions_local.json',
    900
);

define('ESR',               $firefox_versions["FIREFOX_ESR"]);
define('ESR_NEXT',          $firefox_versions["FIREFOX_ESR_NEXT"]);
define('ESR115',            $firefox_versions['FIREFOX_ESR115']);
define('FIREFOX_NIGHTLY',   $firefox_versions["FIREFOX_NIGHTLY"]);
define('DEV_EDITION',       $firefox_versions["FIREFOX_DEVEDITION"]);
define('FIREFOX_BETA',      $firefox_versions["LATEST_FIREFOX_RELEASED_DEVEL_VERSION"]);
define('FIREFOX_RELEASE',   $firefox_versions["LATEST_FIREFOX_VERSION"]);
define('FENIX_RELEASE',     $fenix_versions["version"]);

$main_nightly = (int) FIREFOX_NIGHTLY;
$main_beta    = (int) FIREFOX_BETA;
$main_release = (int) FIREFOX_RELEASE;
$main_esr     = (int) (ESR_NEXT != "" ? ESR_NEXT : ESR);
$old_esr      = (int) (ESR115 != '' ? ESR115 : (ESR_NEXT != '' ? ESR : ESR_NEXT));
$last_beta    = (int) str_replace($main_beta .'.0b', '', FIREFOX_BETA);

/**
 * Global array for debugging purposes
 */
$GLOBALS['urls'] = [];
