$(document).ready(function(){
    $('.btn-add-fil').click(function(e){
        e.preventDefault();
        $(this).before("<div><input type=\"file\" name=\"filenames[]\" class=\"myfrm form-control\" ><input type='button' value='Supprimer' onclick=removeFile(this);></div><br/>");
    });
});

function removeFile(id)
{
    $(id).parent().remove();
}