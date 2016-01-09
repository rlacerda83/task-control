function photoFormatter(value, row, index) {
	return [
		'<img src="img/usuarios/'+ row.usr_foto +'" title="'+ row.usr_nome +'">',
		'<a href="'+ row.edit +'" class="user-link">'+ row.usr_nome +'</a>',
        '<span class="user-subhead">'+ row.per_nome +'</span>'
	].join('');
}

function emailFormatter(value, row, index) {
	return [
		'<a href="mailto:'+ row.usr_email +'">'+ row.usr_email +'</a>'
	].join('');
}

function statusFormatter(value, row, index) {
	return [
		'<span class="label label-'+ row.status_class +'">'+ row.status_label +'</span>'
	].join('');
}

function tipoAcessoFormatter(value, row, index) {
	return [
		'<span class="label label-'+ (value == "Entrada" ? "success" : "danger") +'">'+ value +'</span>'
	].join('');
}

function modoAcessoFormatter(value, row, index) {
	return [
		'<span class="label label-'+ (value.indexOf("RFID") == -1 ? "info" : "primary") +' ">'+ value +'</span>'
	].join('');
}

/**
 * Loading das p�ginas
 */
function block(message) {
    $.blockUI({
        message: (message) ? message : 'Carregando...'
    });
}

/**
 * Remove loading
 */
function unblock() {
    $.unblockUI();
}
