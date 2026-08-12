<?php

function renderSetujuQuestion($index, $question) {
    ?>
    <div class="survey-question">
        <p><?php echo $question; ?></p>
        <label><input type="radio" name="setuju_<?php echo $index; ?>" value="tidak_setuju" required> Tidak Setuju</label>
        <label><input type="radio" name="setuju_<?php echo $index; ?>" value="setuju" required> Setuju</label>
        <label><input type="radio" name="setuju_<?php echo $index; ?>" value="ragu" required> Ragu-ragu</label>
        <label><input type="radio" name="setuju_<?php echo $index; ?>" value="tidak_bisa_menjawab" required> Saya tidak bisa menjawab</label>
    </div>
    <?php
}

function renderKepuasanQuestion($name, $question) {
    ?>
    <div class="survey-question">
        <p><?php echo $question; ?></p>
        <label><input type="radio" name="<?php echo $name; ?>" value="tidak_puas" required> Tidak puas</label>
        <label><input type="radio" name="<?php echo $name; ?>" value="puas" required> Puas</label>
        <label><input type="radio" name="<?php echo $name; ?>" value="ragu" required> Ragu-ragu</label>
        <label><input type="radio" name="<?php echo $name; ?>" value="tidak_bisa_menjawab" required> Saya tidak bisa menjawab</label>
    </div>
    <?php
}

function renderKomentarQuestion($index, $question) {
    ?>
    <h2 class="survey-title"><?php echo $question; ?></h2>
    <div class="survey-question">
        <textarea id="komentar" class="survey-textarea" name="komentar_<?php echo $index; ?>" placeholder="Tulis komentar Anda..." required></textarea>
    </div>
    <?php
}

function renderRatingQuestion($name, $question, $min_label, $max_label) {
    ?>
    <h2 class="survey-title"><?php echo $question; ?></h2>
    <div class="survey-question">
        <div class="rating-container">
            <span class="rating-label"><?php echo $min_label; ?></span>
            <div class="rating-options">
                <label><input type="radio" name="<?php echo $name; ?>" value="1" required> 1</label>
                <label><input type="radio" name="<?php echo $name; ?>" value="2" required> 2</label>
                <label><input type="radio" name="<?php echo $name; ?>" value="3" required> 3</label>
                <label><input type="radio" name="<?php echo $name; ?>" value="4" required> 4</label>
                <label><input type="radio" name="<?php echo $name; ?>" value="5" required> 5</label>
            </div>
            <span class="rating-label"><?php echo $max_label; ?></span>
        </div>
    </div>
    <?php
}

function renderYesNoQuestion($name, $question, $id_yes, $id_no) {
    ?>
    <h2 class="survey-title"><?php echo $question; ?></h2>
    <div class="survey-question">
        <label><input type="radio" name="<?php echo $name; ?>" id="<?php echo $id_yes; ?>" value="ya" required> Ya</label>
        <label><input type="radio" name="<?php echo $name; ?>" id="<?php echo $id_no; ?>" value="tidak" required> Tidak</label>
    </div>
    <?php
}

function renderMBKMQuestion($name, $question) {
    ?>
    <h2 class="survey-title"><?php echo $question; ?></h2>
    <div class="survey-question">
        <label><input type="radio" name="<?php echo $name; ?>" value="perma" required> Perma</label>
        <label><input type="radio" name="<?php echo $name; ?>" value="non_perma" required> Non-Perma</label>
    </div>
    <?php
}
?>