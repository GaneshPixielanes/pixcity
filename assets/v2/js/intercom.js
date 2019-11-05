$(document).ready(function()
{

	/* Initialize Intercom parameters */
	function intercomInit()
	{
		if(getPlatformType() == 'Desktop')
		{
			if(typeof $('#intercom-config-box').attr('data-email') != 'undefined')
			{
				launchIntercomForUsers();
			}
			else
			{
				launchIntercomForVisitors();	
			}

		}
	}

	/* Pop intercom up for logged in users */
	function launchIntercomForUsers()
	{
		var user = {
			app_id : $('#intercom-config-box').attr('data-app-id'),
			email : $('#intercom-config-box').attr('data-email'),
			first_name : $('#intercom-config-box').attr('data-first-name'),
			last_name : $('#intercom-config-box').attr('data-last-name'),
			type : $('#intercom-config-box').attr('data-type'),
			id : $('#intercom-config-box').attr('data-user-id')
		};

		settings = {
			    app_id: 'iswx8vq4',  
			    email: user.email,
			    first_name: user.first_name,
			    last_name: user.last_name,
			    user_type: user.type,
			    user_id: user.id,
			    name: user.first_name+' '+user.last_name,
			    hide_default_launcher: false
		};

		Intercom('boot',settings);
	}

	/* Pop intercom for regular users who are not registered/logged in */
	function launchIntercomForVisitors()
	{
		settings = {
			    app_id: 'iswx8vq4',  
			    hide_default_launcher: false
		};

		Intercom('boot',settings);

	}

	/* Get the hardware type */

	function getPlatformType() {
		if(navigator.userAgent.match(/mobile/i)) {
			return 'Mobile';
		} else if (navigator.userAgent.match(/iPad|Android|Touch/i)) {
			return 'Tablet';
		} else {
			return 'Desktop';
		}
	}

	intercomInit();



});