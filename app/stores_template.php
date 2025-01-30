<?php
    if (! isset($class)) {
        $class = "list-group-item d-flex justify-content-between align-items-center ";
    }

    if (! isset($message)) {
        $message = "(Google & Microsoft need full rollout to show the latest version)";
    }
?>
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