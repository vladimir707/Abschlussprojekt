<div class="sidebar">
    <form class="filter-form" action="#" method="post">
        <div>
            <h5>Ort</h5>
            <input placeholder="Ort" type="text" name="stadt" id="city-input" value="<?php echo $stadt; ?>">
        </div>
        <div>
            <h5>Kaltmiete</h5>
            <input name="min-kaltmiete" type="number" placeholder="Min." value="<?php echo $min_kaltmiete; ?>">
            <input name="max-kaltmiete" type="number" placeholder="Max." value="<?php echo $max_kaltmiete; ?>">
        </div>
        <div>
            <h5>Wohnfläche</h5>
            <input name="min-wohnflaeche" type="number" placeholder="Min." value="<?php echo $min_wohnflaeche; ?>">
            <input name="max-wohnflaeche" type="number" placeholder="Max." value="<?php echo $max_wohnflaeche; ?>">
        </div>
        <div>
            <h5>Zimmerzahl</h5>
            <input name="min-zim-zahl" type="number" placeholder="Min." value="<?php echo $min_zim_zahl; ?>">
            <input name="max-zim-zahl" type="number" placeholder="Max." value="<?php echo $max_zim_zahl; ?>">
        </div>
        <input type="submit" value="Filter anwenden" class="submit-filter btn">
    </form>

    <form id="clear-filter-form" action="./">
        <input type="hidden" value="clear">
        <button type="submit" value="clear" class="clear-filter btn"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                <path d="m592-481-57-57 143-182H353l-80-80h487q25 0 36 22t-4 42L592-481ZM791-56 560-287v87q0 17-11.5 28.5T520-160h-80q-17 0-28.5-11.5T400-200v-247L56-791l56-57 736 736-57 56ZM535-538Z" />
            </svg></button>
    </form>
</div>