<?php
$controllerIndex = array(
    'lists' => 0,
    'posts' => 1,
    'calendar' => 2,
    'studio' => 3,
    'users' => 4,
    'courses' => 5
);
$sidebarIndex = ((array_key_exists($this->params['controller'], $controllerIndex))?$controllerIndex[$this->params['controller']]:0);
?>

<div id="sidebar">
    <ul class="nav nav-pills nav-stacked">
        <li role="presentation" class="<?php echo (($sidebarIndex == 0)?'active':''); ?>"><a href="#content" data-url="<?php echo $this->webroot;?>lists"><span class="glyphicon glyphicon-th" aria-hidden="true"></span> Unser Studio</a></li>
        <li role="presentation" class="<?php echo (($sidebarIndex == 1)?'active':''); ?>"><a href="#content" data-url="<?php echo $this->webroot;?>posts"><span class="glyphicon glyphicon-star" aria-hidden="true"></span> News</a></li>
        <li role="presentation" class="<?php echo (($sidebarIndex == 2)?'active':''); ?>"><a href="#content" data-url="<?php echo $this->webroot;?>calendar/init"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span> Kalender</a></li>

        <?php
        if(!empty($user)){
        ?>
        <?php echo (($user['role'] > 0)?'<li role="presentation"><a href="#content" data-url="'.$this->webroot.'studio"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> Studiomanagement</a></li>':'');?>
        <li role="presentation"><a href="#content" data-url="<?php echo $this->webroot;?>users/listing/<?php echo $user['id']; ?>"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span> Usermanagement</a></li>
        <li role="presentation"><a href="#content" data-url="<?php echo $this->webroot;?>Courses"><span class="glyphicon glyphicon-cloud" aria-hidden="true"></span> Kursmanagement</a></li>
        <?php } ?>
    </ul>
</div>


<?php echo $this->Html->scriptStart(array('inline' => true)); ?>
    $('#sidebar > .nav-pills a').click(function (e) {
        e.preventDefault();

        var url = $(this).attr("data-url");
        var href = this.hash;
        var pane = $(this);

        // ajax load from data-url
        $(href).load(url,function(result){
            pane.tab('show');
        });
    });

    //Laden der aktiven Seite
    $('#content').load($('#sidebar > .nav > .active a').attr('data-url'), function() {
        $('#sidebar > .nav > .active a').tab('show');
    });
<?php echo $this->Html->scriptEnd(); ?>