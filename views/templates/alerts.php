<div class="alerts" id="alerts">
    <?php
        foreach ($alerts as $key => $alert):
            foreach ( $alert as $message ):
    ?>
                <div class="alert <?php echo $key; ?>">
                    <?php echo $message; ?>
                </div>
    <?php
            endforeach;
        endforeach;
    ?>
</div>