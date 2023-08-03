<?php
function DrawPopulationManager($workers, $defenders, $scholars) {
?>
    <ul>
        <li class="item">Workers: <?=$workers?>% -- <button class="popBtn" hx-post="view/component/population_mgmt.php?do=add&item=workers">+</button> <button class="popBtn" hx-post="view/component/population_mgmt.php?do=sub&item=workers">-</button></li>
        <li class="item">Defenders: <?=$defenders?>% -- <button class="popBtn" hx-post="view/component/population_mgmt.php?do=add&item=defenders">+</button> <button class="popBtn" hx-post="view/component/population_mgmt.php?do=sub&item=defenders">-</button></li>
        <li class="item">Scholars: <?=$scholars?>% -- <button class="popBtn" hx-post="view/component/population_mgmt.php?do=add&item=scholars">+</button> <button class="popBtn" hx-post="view/component/population_mgmt.php?do=sub&item=scholars">-</button></li>
    </ul>
<?php
}
?>