<core-submenu id="sidebarSubmenuUser" icon="settings" label="Usermanagement"></core-submenu>
<?php
echo $this->Html->scriptStart(array('inline' => true));
?>
    document.querySelector('#sidebarSubmenuUser').addEventListener('tap', function(e) {
        $( "#content" ).load( "<?php echo $this->webroot;?>users");
    });
<?php
echo $this->Html->scriptEnd();
?>