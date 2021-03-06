<div id="listsTabbar" role="tabpanel">
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#studio" data-url="<?php echo $this->webroot; ?>Lists/studio">Info</a>
        </li>
        <li role="presentation"><a href="#studioTrainer"
                                   data-url="<?php echo $this->webroot; ?>Lists/trainer/">Trainer</a>
        </li>
        <li role="presentation"><a href="#studioMitarbeiter" data-url="<?php echo $this->webroot; ?>Lists/mitarbeiter/">Mitarbeiter</a>
        </li>
        <li role="presentation"><a href="#studioKurse" data-url="<?php echo $this->webroot; ?>Lists/kurse/">Kurse</a>
        </li>
        <li role="presentation"><a href="#studioTarife" data-url="<?php echo $this->webroot; ?>Lists/tarife">Preise</a>
        </li>

    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade in active" id="studio"></div>
        <div role="tabpanel" class="tab-pane fade" id="studioTrainer"></div>
        <div role="tabpanel" class="tab-pane fade" id="studioMitarbeiter"></div>
        <div role="tabpanel" class="tab-pane fade" id="studioKurse"></div>
        <div role="tabpanel" class="tab-pane fade" id="studioTarife"></div>
    </div>
</div>



<?php echo $this->Html->scriptStart(array('inline' => true)); ?>
$('#listsTabbar > .nav-tabs a').click(function (e) {
e.preventDefault();

var url = $(this).attr("data-url");
var href = this.hash;
var pane = $(this);

// ajax load from data-url
$(href).load(url,function(result){
pane.tab('show');
});
});

// Content für angezeigten Tab
$('#listsTabbar > .tab-content > #studio').load('<?php echo $this->webroot; ?>Lists/studio/',function(result){
$('#listsTabbar > .active a').tab('show');
});
<?php echo $this->Html->scriptEnd(); ?>