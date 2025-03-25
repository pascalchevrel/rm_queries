<?php
    if (! isset($class)) {
        $class = "";
    }

    if (! isset($message)) {
        $message = "(Google & Microsoft need full rollout to show the latest version)";
    }
?>
                <table class="table">
                    <thead>
                    <tr>
                        <td colspan="3">Firefox in Stores <sup class="fw-normal fst-italic text-secondary"><?=$message?></sup></td>
                    </tr>
                    <tr>
                        <td>Store</td>
                        <td>Release</td>
                        <td>Beta</td>
                        <td>Other</td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="mobile">
                        <th scope="row">Google</th>
                        <td class="<?=$play_status['release']?>"><?=$play_store_release?></td>
                        <td class="<?=$play_status['beta']?>"><?=$play_store_beta?></td>
                        <td>
                            Focus <span class="<?=$play_status['focus']?>"><?=$play_store_focus_release?></span>;
                            Klar <span class="<?=$play_status['klar']?>"><?=$play_store_klar_release?></span>
                        </td>
                    </tr>
                    <tr class="mobile">
                        <th scope="row">Samsung</th>
                        <td class="<?=$samsung_firefox_status?>"><?=$samsung_firefox?></td>
                        <td>N/A</td>
                        <td>Focus <span class="<?=$samsung_focus_status?>"><?=$samsung_focus?></span></td>
                    </tr>
                    <tr class="mobile">
                        <th scope="row">Apple iOS</th>
                        <td class="text-secondary"><?=$apple_store_firefox_release?></td>
                        <td>N/A</td>
                        <td>
                            Focus <span class="text-secondary"><?=$apple_store_focus_release?></span>;
                            Klar <span class="text-secondary"><?=$apple_store_klar_release?></span>
                        </td>
                    </tr>
                    <tr class="laptop">
                        <th scope="row">Flathub</th>
                        <td class="<?=$flathub_status?>"><?=$flathub_release?></td>
                        <td colspan="2">N/A</td>
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
                        <td colspan="2">N/A</td>
                    </tr>
                    <tr class="laptop">
                        <th scope="row">Apple macOS</th>
                        <td colspan="3">
                            <a href="https://docs.google.com/document/d/1cLVEXAN2_AycqWA7XO6YddA0AFvHlLvVKMVvTBwunrE/edit">Not currently available.</a>
                        </td>
                    </tbody>
                </table>