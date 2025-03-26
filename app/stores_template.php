<?php
    $class = "list-group-item d-flex justify-content-between align-items-center ";
    if (isset($page_id) && $page_id != 'store') {
        $class = "";
        $message = "(Google & Microsoft need full rollout to show the latest version)";
    }
?>

<?php if(! isset($page_id) || $page_id != 'store'): ?>
                <ul class="list-group">
                    <li class="list-group-item card-header list-group-item-primary">Firefox in Stores <sup class="fw-normal fst-italic text-secondary"><?=$message?></sup></li>
                    <li class="<?=$class?> mobile">
                        Google <span class="<?=$play_status['release']?>"><?=$play_store_release?></span>
                    </li>
                    <li class="<?=$class?> mobile">
                        Samsung <span class="<?=$samsung_firefox_status?>"><?=$samsung_firefox?></span>
                    </li>
                    <li class="<?=$class?> laptop">
                        Flathub <span class="<?=$flathub_status?>"><?=$flathub_release?></span>
                    </li>
                    <li class="<?=$class?> laptop">
                        Snapcraft <span class="<?=$snap_status['release']?>"><?=$snapcraft["release"]?></span>
                    </li>
                    <li class="<?=$class?> laptop">
                        Microsoft <span class="<?=$microsoft_store_status?>"><?=$microsoft_store_release?></span>
                    </li>
                    <li class="<?=$class?> mobile">
                        Apple <span class="text-secondary"><?=$apple_store_firefox_release?></span>
                    </li>
                    <li class="<?=$class?> mobile">
                        Google Beta <span class="<?=$play_status['beta']?>"><?=$play_store_beta?></span>
                    </li>
                    <li class="<?=$class?> laptop">
                        Snapcraft Beta <span class="<?=$snap_status['beta']?>"><?=$snapcraft["beta"]?></span>
                    </li>
                    <li class="<?=$class?> laptop">
                        Snapcraft ESR <span class="<?=$snap_status['esr']?>"><?=$snapcraft["esr"]?></span>
                    </li>
                    <li class="<?=$class?> mobile">
                        Google Focus <span class="<?=$play_status['focus']?>"><?=$play_store_focus_release?></span>
                    </li>
                    <li class="<?=$class?> mobile">
                        Google klar <span class="<?=$play_status['klar']?>"><?=$play_store_klar_release?></span>
                    </li>
                    <li class="<?=$class?> mobile">
                        Samsung Focus <span class="<?=$samsung_focus_status?>"><?=$samsung_focus?></span>
                    </li>
                    <li class="<?=$class?> mobile">
                        Apple Focus <span class="text-secondary"><?=$apple_store_focus_release?></span>
                    </li>
                    <li class="<?=$class?> mobile">
                        Apple Klar <span class="text-secondary"><?=$apple_store_klar_release?></span>
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
                        <td class="<?=$play_status['release']?>"><?=$play_store_release?></td>
                        <td class="<?=$play_status['beta']?>"><?=$play_store_beta?></td>
                        <td>
                            Focus&nbsp;<span class="<?=$play_status['focus']?>"><?=$play_store_focus_release?></span>
                            <span class="text-black-50 px-2">·</span>
                            Klar&nbsp;<span class="<?=$play_status['klar']?>"><?=$play_store_klar_release?></span>
                        </td>
                    </tr>
                    <tr class="mobile">
                        <th scope="row" class="fw-bolder">Samsung</th>
                        <td class="<?=$samsung_firefox_status?>"><?=$samsung_firefox?></td>
                        <td class="text-secondary"><small class="fst-italic text-secondary">N/A</small></td>
                        <td>Focus <span class="<?=$samsung_focus_status?>"><?=$samsung_focus?></span></td>
                    </tr>
                    <tr class="mobile">
                        <th scope="row">Apple iOS</th>
                        <td class="text-secondary"><?=$apple_store_firefox_release?></td>
                        <td class="text-secondary"><small class="fst-italic text-secondary">N/A</small></td>
                        <td>
                            Focus&nbsp;<span class="text-secondary"><?=$apple_store_focus_release?></span>
                            <span class="text-black-50 px-2">·</span>
                            Klar&nbsp;<span class="text-secondary"><?=$apple_store_klar_release?></span>
                        </td>
                    </tr>
                    <tr class="laptop">
                        <th scope="row">Flathub</th>
                        <td class="<?=$flathub_status?>"><?=$flathub_release?></td>
                        <td colspan="2" class="text-secondary"><small class="fst-italic text-secondary">N/A</small></td>
                    </tr>
                    <tr class="laptop">
                        <th scope="row">Snapcraft</th>
                        <td class="<?=$snap_status['release']?>"><?=$snapcraft["release"]?></td>
                        <td class="<?=$snap_status['beta']?>"><?=$snapcraft["beta"]?></td>
                        <td>ESR <span class="<?=$snap_status['esr']?>"><?=$snapcraft["esr"]?></span></td>
                    </li>
                    <tr class="laptop">
                        <th scope="row">Microsoft</th>
                        <td class="<?=$microsoft_store_status?>"><?=$microsoft_store_release?></td>
                        <td colspan="2"><small class="fst-italic text-secondary">N/A</small></td>
                    </tr>
                    <tr class="laptop">
                        <th scope="row">Apple macOS</th>
                        <td colspan="3">
                            <a href="https://docs.google.com/document/d/1cLVEXAN2_AycqWA7XO6YddA0AFvHlLvVKMVvTBwunrE/edit">Not currently available</a>
                        </td>
                    </tbody>
                </table>
<?php endif; ?>