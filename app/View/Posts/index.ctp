<?php
if(!empty($user) AND $user['role'] > 0) { ?>
    <button type="button" id="userAddOpenButton" class="btn btn-default" onclick="postsAddButtonClick();"><i class="glyphicon glyphicon-plus"></i>Hinzufügen</button>
<?php } ?>

<div id="postEntries">
    <?php
    foreach ($posts as $post)
    {
        echo $this->element('post', array('post' => $post));
    }
    ?>
</div>
<nav>
    <ul class="pagination">
        <li class="disabled"><a href="#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
        <?php
        for($i=0;$i<$postCount;$i=$i+$limit)
        {
            echo '<li '.(($page==$i/$limit)?'class="active"':'').'><a href="javascript:void(0);" onclick="loadPage('.($i/$limit).')">'.(($i/$limit)+1).'<span class="sr-only">(current)</span></a></li>';
        }
        ?>
        <li <?php echo (($i<$limit)?'class="disabled"':''); ?>>
            <a href="#" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    </ul>
</nav>
<?php echo $this->Html->scriptStart(array('inline' => true)); ?>

function loadPage(page) {
    $('#content').load('<?php echo $this->webroot."posts/index/";?>'+page);
}

var addId = 0;
function postsAddButtonClick()
{
    $.get('<?php echo $this->webroot."posts/add/";?>'+addId, function( data ) {
        $('#postEntries').before(data);
    });
addId++;
}


function postEntryDelete(postId)
{
    var del = confirm("Post #" + postId + " löschen?");
    if (del == true) {
        $.post('<?php echo $this->webroot."posts/delete/"?>'+postId,function(json) {
            if(json.success == true)
            {
                notificateUser(json.message, 'success');
                $('#postEntry'+postId).remove();
            } else
            {
                notificateUser(json.message);
            }
        }, 'json');
    }
}

function postEntryEdit(postId)
{
    $.get('<?php echo $this->webroot."posts/edit/"?>'+postId, function( data ) {
        $('#postEntry'+postId).replaceWith(data);
    });
}

function postAddFormClose(addId)
{
    $('#postAddForm'+addId).remove();
}

function postAddFormAddDatepicker(addId)
{
    $("#postAddForm"+addId+" > .Post > .visiblebegin > .date > .form-control").datepicker({
        format: 'dd.mm.yyyy',
        language: 'de'
    });
    $("#postAddForm"+addId+" > .Post > .visibleend > .date > .form-control").datepicker({
        format: 'dd.mm.yyyy',
        language: 'de'
    });
}

function postAddFormAddSubmitEvent(addId)
{
    var addForm = '#postAddForm'+addId;
    $(addForm).submit(function(event) {
        $.post('<?php echo $this->webroot;?>posts/add/', $(addForm).serialize(), function(json) {
            if(json.success == true) {
                notificateUser(json.message, 'success');
                $.get('<?php echo $this->webroot;?>posts/view/'+json.id, function( view ) {
                    $('#postEntries').prepend(view);
                    postAddFormClose(addId);
                });
            } else {
                notificateUser(json.message);

                //delete old errors
                $(addForm).children().each(function() {
                    $(this).children().each(function() {
                        $(this).removeClass('has-error has-feedback');
                        $(this).children('.control-label').remove();
                    });
                })

                for(var controller in json.errors) {
                    for(var key in json.errors[controller]) {
                        if(json.errors[controller].hasOwnProperty(key)) {
                            notificateUser(json.errors[controller][key]);
                            var ele = $(addForm+' > .'+controller+' > .'+key);
                            ele.addClass('has-error has-feedback');
                            ele.append('<label class="control-label">'+json.errors[controller][key]+'</label>');
                        }
                    }
                }
            }
        }, 'json');
        event.preventDefault();
    });
}


function postEditFormAddDatepicker(postId)
{
    $("#postEditForm"+postId+" > .Post > .visiblebegin > .date > .form-control").datepicker({
        format: 'dd.mm.yyyy',
        language: 'de'
    });
    $("#postEditForm"+postId+" > .Post > .visibleend > .date > .form-control").datepicker({
        format: 'dd.mm.yyyy',
        language: 'de'
    });
}

function postEditFormAddSubmitEvent(postId)
{
    var editForm = '#postEditForm'+postId;
    $(editForm).submit(function(event) {
        $.post('<?php echo $this->webroot;?>posts/edit/'+postId, $(editForm).serialize(), function(json) {
            if(json.success == true) {
                notificateUser(json.message, 'success');
                $.get('<?php echo $this->webroot;?>posts/view/'+postId, function( view ) {
                    $(editForm).replaceWith(view);
                });
            } else {
            notificateUser(json.message);

            //delete old errors
            $(editForm).children().each(function() {
                $(this).children().each(function() {
                    $(this).removeClass('has-error has-feedback');
                    $(this).children('.control-label').remove();
                });
            })

            for(var controller in json.errors) {
                for(var key in json.errors[controller]) {
                    if(json.errors[controller].hasOwnProperty(key)) {
                        notificateUser(json.errors[controller][key]);
                        var ele = $(editForm+' > .'+controller+' > .'+key);
                        ele.addClass('has-error has-feedback');
                        ele.append('<label class="control-label">'+json.errors[controller][key]+'</label>');
                    }
                }
            }
        }
    }, 'json');
    event.preventDefault();
    });
}




function postEditFormClose(postId)
{
    $.get('<?php echo $this->webroot."posts/view/"?>'+postId, function( data ) {
        $('#postEditForm'+postId).replaceWith(data);
    });
}
<?php echo $this->Html->scriptEnd();?>
