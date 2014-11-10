var UI = {

config: {
facebox: {
loadingImage: '/buyer/templates/default/files/assets/facebox/loading.gif',
closeImage:   '/buyer/templates/default/files/assets/facebox/closelabel.png',
},
},

closeMessage: function(e) {
var $this = $(this).parent();

$this.fadeOut(function() {
$this.remove();
});

e.preventDefault();
},

toggleMenu: function(e) {
var $this = $(this).parent();

UI.clearMenus($this);

$this.not('.js') && $this.addClass('js');
$this.toggleClass('active');

e.stopPropagation();
e.preventDefault();
},

clearMenus: function($element) {
var $dropdown = $('.dropdown');

if ($element) {
$dropdown = $dropdown.not($element);
}

// Search for dynamic acticated menus
$dropdown.removeClass('active');
},

showTooltip: function() {
var $this   = $(this);
var gravity = $this.data('gravity');

$this.tipsy({
gravity: (gravity == null ? 's' : gravity),
opacity: 1,
});

$this.tipsy('show');
},

hideTooltip: function() {
var $this = $(this).tipsy('hide');
},

toggleBlock: function() {
var $parent = $(this).parent();
$parent.toggleClass('closed');
},

selectAll: function() {
var $parent = $(this).parents('.table');
var $checkbox = $(':checkbox', $parent).prop('checked', this.checked)
$.fn.uniform && $.uniform.update($checkbox);
},

}

$(document).ready(function() {
$('#select', '.table.selectable').click(UI.selectAll);
$('a.close', '.msg').click(UI.closeMessage);
$('.dropdown > a').click(UI.toggleMenu);
$('.block > h2').click(UI.toggleBlock);
$(document).click(UI.clearMenus);
$(document).bind('reveal.facebox', function() {
$.fn.uniform && $('select, input:checkbox, input:radio, input:file', '#facebox').uniform();
UI.clearMenus();
});

// Activate attached plugins
$.fn.tablesorter && $('.table.sortable').tablesorter({ sortList: [[1,0]] });
$.fn.tipsy && $('[title]').hover(UI.showTooltip, UI.hideTooltip);
$.fn.uniform && $('select, input:checkbox, input:radio, input:file').uniform();
$.fn.facebox && $('a[rel*="modal"]').facebox(UI.config.facebox);
$.browser.msie && $('body').addClass('ie ie' + $.browser.version.substr(0, 1));
window.prettyPrint && prettyPrint();

});