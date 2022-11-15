var count=0
function dothis()
{
	setTimeout("document.wheel.wheel2.selectedIndex =1000",100)
	setTimeout("document.wheel.wheel2.selectedIndex =count",200)
	setTimeout("document.wheel.wheel2.selectedIndex =1000",300)
	setTimeout("document.wheel.wheel2.selectedIndex =count",400)
	setTimeout("document.wheel.wheel2.selectedIndex =1000",500)
	setTimeout("document.wheel.wheel2.selectedIndex =count",600)
	setTimeout("window.location=document.wheel.wheel2.options[document.wheel.wheel2.selectedIndex].value",800)
}

function spinthewheel()
{
	var countfinal=Math.round(Math.random()*(document.wheel.wheel2.length-1))
	document.wheel.wheel2.selectedIndex =count
	if( count == countfinal )
	{
		dothis()
		return
	}

	if( count < document.wheel.wheel2.length )
		count++

	else
		count = 0
		setTimeout("spinthewheel()",50)
}