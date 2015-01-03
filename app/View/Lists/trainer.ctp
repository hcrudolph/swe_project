<div id="trainer">
    <?php
    foreach ($trainers as $trainer) {
        $trname = $trainer['Person']['name'];
        $trsurname = $trainer['Person']['surname'];
        $tremail = $trainer['Person']['email'];
        $trid = $trainer['Person']['account_id'];
        ?>

        <div class="col-xs-3">
            <div class="popoverElement">
                <div class="thumbnail">
                    <img src="<?php echo $this->webroot; ?>img/Mitarbeiter/default.png" img-source="<?php echo $this->webroot; ?>img/Mitarbeiter/<?php echo $trid;?>.png"/>
                    <h4><?php echo $trsurname . "</br>" . $trname ?></h4>
                </div>
                <span class="popoverContent" style="display:none;">
                    <table class="table table-striped table-bordered">
                        <tbody>
                            <?php
                            if(count($trainer['Certificate']) > 0) {
                                foreach ($trainer['Certificate'] as $certificate) {
                                    echo '<tr><td>' . $certificate['name'] . '</td></tr>';
                                }
                            } else {
                                echo 'Keine Zertifikate';
                            }
                            ?>
                        </tbody>
                    </table>
                </span>
            </div>
        </div>
    <?php }
    ?>
    <?php echo $this->Html->scriptStart(array('inline' => true)); ?>
        $('.thumbnail img').each(function() {
            $(this).error(function() { $(this).attr('src', '<?php echo $this->webroot; ?>img/Mitarbeiter/default.png'); })
            .attr("src", $(this).attr('img-source'))
        });


        $('.popoverElement').each(function() {
            $(this).popover({
                html: true,
                container: $(this),
                trigger: 'hover',
                placement: 'left',
                content: function() {
                    return $(this).children('.popoverContent').html();
                }
            });
        });
    <?php echo $this->Html->scriptEnd(); ?>
</div>