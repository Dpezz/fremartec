/* menu one */
$('#one').hover(function(){
	unselect()
    $('#menu-one').attr('class','active')
})

$('#menu-one').click(function(){
	unselect()
	$('#menu-one').attr('class','active')
})
/* menu two */
$('#two').hover(function(){
	unselect()
    $('#menu-two').attr('class','active')
})

$('#menu-two').click(function(){
	unselect()
	$('#menu-two').attr('class','active')
})
/* menu three */
$('#three').hover(function(){
	unselect()
    $('#menu-three').attr('class','active')
})

$('#menu-three').click(function(){
	unselect()
	$('#menu-three').attr('class','active')
})

/* menu four */
$('#four').hover(function(){
	unselect()
    $('#menu-four').attr('class','active')
})

$('#menu-four').click(function(){
	unselect()
	$('#menu-four').attr('class','active')
})

/* menu five */
$('#five').hover(function(){
	unselect()
    $('#menu-five').attr('class','active')
})

$('#menu-five').click(function(){
	unselect()
	$('#menu-five').attr('class','active')
})
/* menu six */
$('#six').hover(function(){
	unselect()
    $('#menu-six').attr('class','active')
})

$('#menu-six').click(function(){
	unselect()
	$('#menu-six').attr('class','active')
})
function unselect(){
	$('#menu-one').removeAttr('class','active')
	$('#menu-two').removeAttr('class','active')
	$('#menu-three').removeAttr('class','active')
	$('#menu-four').removeAttr('class','active')
	$('#menu-five').removeAttr('class','active')
	$('#menu-six').removeAttr('class','active')
}

/* Tooltip */
$('a').tooltip({
    'selector': '',
    'placement': 'bottom',
    'container':'body'
});
