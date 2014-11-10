function deposit()
	{
		if($("#deposit_bitcoin").is(":visible"))
		{	
			$("#deposit_bitcoin").css('display', 'none');
			$("#deposit_perfectmoney").css('display', '');
		}
		else if($("#deposit_perfectmoney").is(":visible")){
			$("#deposit_perfectmoney").css('display', 'none');
			$("#deposit_bitcoin").css('display', '');
		}
	
	
}

function returnfrWebmoney()
	{

	}
function returnfrPerfect()
	{

		var returnvlue = 'no'
			
		if(document.frPerfect.EV_NUMBER.value.match( /^[A-Za-z][\w]*$/))
		{
			document.frPerfect.EV_NUMBER.focus();
			returnvlue ='yes';
		}
		else if(document.frPerfect.EV_NUMBER.value.length <=5)
		{
			document.frPerfect.EV_NUMBER.focus();
			returnvlue ='yes';
		}
		else if(document.frPerfect.EV_CODE.value.match( /^[A-Za-z][\w]*$/))
		{
			document.frPerfect.EV_CODE.focus();
			returnvlue ='yes';
		}
		else if(document.frPerfect.EV_CODE.value.length <=5)
		{
			document.frPerfect.EV_CODE.focus();
			returnvlue ='yes';
		}
		if(returnvlue == 'no')
		{
			return true;
		}
		else
		{
			return false;
		}
}