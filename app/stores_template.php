<?php
    $class = 'list-group-item d-flex justify-content-between align-items-center ';
    $message = '(Google & Microsoft need full rollout to show the latest version)';
    if (isset($page_id) && $page_id == 'store') {
        $class = '';
        $message = <<<TEXT
        Google & Microsoft need 100% rollout to serve the latest version number to new users<br>
        Keep in mind that stores can take up to 48h to review and accept our submissions.
        TEXT;
    }
?>

<?php if(! isset($page_id) || $page_id != 'store'): ?>
                <ul class="list-group">
                    <li class="list-group-item card-header list-group-item-primary">Firefox in <a href="/stores" class="d-inline">Stores</a> <sup class="fw-normal fst-italic text-secondary"><?=$message?></sup></li>
                    <li class="<?=$class?> mobile">
                        Google <span class="text-<?=$play_status['release']?>"><?=$play_store_release?></span>
                    </li>
                    <li class="<?=$class?> mobile">
                        Samsung <span class="text-<?=$samsung_firefox_status?>"><?=$samsung_firefox?></span>
                    </li>
                    <li class="<?=$class?> laptop">
                        Flathub <span class="text-<?=$flathub_status?>"><?=$flathub_release?></span>
                    </li>
                    <li class="<?=$class?> laptop">
                        Snapcraft <span class="text-<?=$snap_status['release']?>"><?=$snapcraft["release"]?></span>
                    </li>
                    <li class="<?=$class?> laptop">
                        Microsoft <span class="text-<?=$microsoft_store_status?>"><?=$microsoft_store_release?></span>
                    </li>
                    <li class="<?=$class?> mobile">
                        Apple <span class="text-<?=$default_link_status?>"><?=$apple_store_firefox_release?></span>
                    </li>
                    <li class="<?=$class?> mobile">
                        Google Beta <span class="text-<?=$play_status['beta']?>"><?=$play_store_beta?></span>
                    </li>
                    <li class="<?=$class?> laptop">
                        Snapcraft Beta <span class="text-<?=$snap_status['beta']?>"><?=$snapcraft["beta"]?></span>
                    </li>
                    <li class="<?=$class?> laptop">
                        Snapcraft ESR <span class="text-<?=$snap_status['esr']?>"><?=$snapcraft["esr"]?></span>
                    </li>
                    <li class="<?=$class?> mobile">
                        Google Focus <span class="text-<?=$play_status['focus']?>"><?=$play_store_focus_release?></span>
                    </li>
                    <li class="<?=$class?> mobile">
                        Google klar <span class="text-<?=$play_status['klar']?>"><?=$play_store_klar_release?></span>
                    </li>
                    <li class="<?=$class?> mobile">
                        Samsung Focus <span class="text-<?=$samsung_focus_status?>"><?=$samsung_focus?></span>
                    </li>
                    <li class="<?=$class?> mobile">
                        Apple Focus <span class="text-<?=$default_link_status?>"><?=$apple_store_focus_release?></span>
                    </li>
                    <li class="<?=$class?> mobile">
                        Apple Klar <span class="text-<?=$default_link_status?>"><?=$apple_store_klar_release?></span>
                    </li>
                    <li class="<?=$class?> mobile">
                        Huawei Firefox <span class="text-<?=$default_link_status?>"><?=$huawei_store_firefox_release?></span>
                    </li>
                    <li class="<?=$class?> mobile">
                        Xiaomi Firefox <span class="text-<?=$xiaomi_firefox_status?>"><?=$xiaomi_store_firefox_release?></span>
                    </li>
                </ul>
<?php else: ?>
                <table class="table">
                    <thead>
                    <tr>
                        <th colspan="4" class="fs-2">Firefox in Stores <sup class="fw-normal fst-italic text-secondary"><?=$message?></sup></th>
                    </tr>
                    <tr>
                        <th>Store</th>
                        <th>Release</th>
                        <th>Beta</th>
                        <th>Other</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="mobile">
                        <th scope="row">Google</th>
                        <td class="<?=$play_status['release']?>"><a href="<?=StoreRelease::Google->url()?>" class="link-<?=$play_status['release']?>"><?=$play_store_release?></a></td>
                        <td class="text-<?=$play_status['beta']?>"><span class="text-<?=$play_status['beta']?>"><?=$play_store_beta?></span></td>
                        <td>
                            Focus&nbsp;<span class="text-<?=$play_status['focus']?>"><?=$play_store_focus_release?></span>
                            <span class="text-black-50 px-2">·</span>
                            Klar&nbsp;<span class="text-<?=$play_status['klar']?>"><?=$play_store_klar_release?></span>
                        </td>
                    </tr>
                    <tr class="mobile">
                        <th scope="row" class="fw-bolder">Samsung</th>
                        <td class="text-<?=$samsung_firefox_status?>"><a href="<?=StoreRelease::Samsung->url()?>" class="link-<?=$samsung_firefox_status?>"><?=$samsung_firefox?></a></td>
                        <td class="text-secondary"><small class="fst-italic text-secondary">N/A</small></td>
                        <td>Focus <span class="text-<?=$samsung_focus_status?>"><?=$samsung_focus?></span></td>
                    </tr>
                    <tr class="mobile">
                        <th scope="row">Apple iOS</th>
                        <td class="text-secondary"><a href="<?=StoreRelease::Apple->url()?>" class="link-<?=$default_link_status?>"><?=$apple_store_firefox_release?></a></td>
                        <td class="text-secondary"><small class="fst-italic text-secondary">N/A</small></td>
                        <td>
                            Focus&nbsp;<span class="text-<?=$default_link_status?>"><?=$apple_store_focus_release?></span>
                            <span class="text-black-50 px-2">·</span>
                            Klar&nbsp;<span class="text-<?=$default_link_status?>"><?=$apple_store_klar_release?></span>
                        </td>
                    </tr>
                    <tr class="mobile">
                        <th scope="row">Huawei</th>
                            <td colspan="3">
                                <a href="<?=StoreRelease::Huawei->url()?>" class="link-<?=$default_link_status?>">&rarr; AppGallery</a>
                                <small class="btn btn-outline-warning btn-sm text-dark" title="Not available in some regions such as the US">geo restrictions</small>
                            </td>
                    </tr>
                    <tr class="mobile">
                        <th scope="row">Xiaomi</th>
                            <td class="text-<?=$xiaomi_firefox_status?>">
                                <a href="<?=StoreRelease::Xiaomi->url()?>" class="link-<?=$xiaomi_firefox_status?>"><?=$xiaomi_store_firefox_release?></a>
                            </td>
                            <td colspan="2" class="text-secondary"><small class="fst-italic text-secondary">N/A</small></td>

                    </tr>
                    <tr class="laptop">
                        <th scope="row">FlatHub</th>
                        <td class="text-<?=$flathub_status?>"><a href="<?=StoreRelease::Flathub->url()?>" class="link-<?=$flathub_status?>"><?=$flathub_release?></a></td>
                        <td colspan="2" class="text-secondary"><small class="fst-italic text-secondary">N/A</small></td>
                    </tr>
                    <tr class="laptop">
                        <th scope="row">Snapcraft</th>
                        <td class="text-<?=$snap_status['release']?>"><a href="<?=StoreRelease::Snapcraft->url()?>" class="link-<?=$snap_status['release']?>"><?=$snapcraft["release"]?></a></td>
                        <td class="text-<?=$snap_status['beta']?>"><span class="text-<?=$snap_status['beta']?>"><?=$snapcraft["beta"]?></span></td>
                        <td>ESR <span class="text-<?=$snap_status['esr']?>"><?=$snapcraft["esr"]?></span></td>
                    </li>
                    <tr class="laptop">
                        <th scope="row">Microsoft</th>
                        <td class="text-<?=$microsoft_store_status?>"><a href="<?=StoreRelease::Microsoft->url()?>" class="link-<?=$microsoft_store_status?>"><?=$microsoft_store_release?></a></td>
                        <td colspan="2"><small class="fst-italic text-secondary">N/A</small></td>
                    </tr>
                    <tr class="laptop">
                        <th scope="row">Apple macOS</th>
                        <td colspan="3">
                            <a href="https://docs.google.com/document/d/1cLVEXAN2_AycqWA7XO6YddA0AFvHlLvVKMVvTBwunrE/edit" class="text-<?=$default_link_status?>">Not currently available</a>
                        </td>
                    </tbody>
                </table>
<?php endif; ?>