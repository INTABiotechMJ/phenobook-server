/*
 * Translated default messages for the jQuery validation plugin.
 * Locale: ES (Spanish; Español)
 * Region: AR (Argentina)
 */
(function($) {
	$.extend($.validator.messages, {
		required: "Este campo es obligatorio.",
		remote: "Completá este campo.",
		email: "Escriba un email válido.",
		url: "Escriba una URL válida.",
		date: "Escriba una fecha válida.",
		dateISO: "Escriba una fecha (ISO) válida.",
		number: "Escriba un número entero válido.",
		digits: "Escriba sólo dígitos.",
		creditcard: "Escriba un número de tarjeta válido.",
		equalTo: "Escriba el mismo valor de nuevo.",
		extension: "Escriba un valor con una extensión aceptada.",
		maxlength: $.validator.format("No escribas más de {0} caracteres."),
		minlength: $.validator.format("No escribas menos de {0} caracteres."),
		rangelength: $.validator.format("Escriba un valor entre {0} y {1} caracteres."),
		range: $.validator.format("Escriba un valor entre {0} y {1}."),
		max: $.validator.format("Escriba un valor menor o igual a {0}."),
		min: $.validator.format("Escriba un valor mayor o igual a {0}."),
		nifES: "Escriba un NIF válido.",
		nieES: "Escriba un NIE válido.",
		cifES: "Escriba un CIF válido."
	});
}(jQuery));
