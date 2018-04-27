function includeHTML() {
  var z, i, elmnt, file, xhttp;
  /*loop through a collection of all HTML elements:*/
  z = document.getElementsByTagName("*");
  for (i = 0; i < z.length; i++) {
    elmnt = z[i];
    /*search for elements with a certain atrribute:*/
    file = elmnt.getAttribute("layout");
    if (file) {
      /*make an HTTP request using the attribute value as the file name:*/
      xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (this.readyState == 4) {
          if (this.status == 200) {elmnt.innerHTML = this.responseText;}
          if (this.status == 404) {elmnt.innerHTML = "Page not found.";}
          /*remove the attribute, and call this function once more:*/
          elmnt.removeAttribute("layout");
          includeHTML();
        }
      } 
      xhttp.open("GET", file, true);
      xhttp.send();
      /*exit the function:*/
      return;
    }
  }
}

			
	
			/**
				* Function that gets the data of the profile in case
	* thar it has already saved in localstorage. Only the
	* UI will be update in case that all data is available
	*
	* A not existing key in localstorage return null
	*
	*/
			function getLocalProfile(callback){
				var profileImgSrc      = localStorage.getItem("PROFILE_IMG_SRC");
				var profileName        = localStorage.getItem("PROFILE_NAME");
				var profileReAuthEmail = localStorage.getItem("PROFILE_REAUTH_EMAIL");
	
				if(profileName !== null
					&& profileReAuthEmail !== null
					&& profileImgSrc !== null) {
				callback(profileImgSrc, profileName, profileReAuthEmail);
			}
		}	

	/**
	* Main function that load the profile if exists	
	* in localstorage
	*/
			function loadProfile() {
				if(!supportsHTML5Storage()) { return false; }
				// we have to provide to the callback the basic
				// information to set the profile
				getLocalProfile(function(profileImgSrc, profileName, profileReAuthEmail) {
				//changes in the UI
					$("#profile-img").attr("src",profileImgSrc);
					$("#profile-name").html(profileName);
					$("#reauth-email").html(profileReAuthEmail);
					$("#inputEmail").hide();
					$("#remember").hide();
				});
			}

	/**
	* function that checks if the browser supports HTML5
	* local storage
	*
	* @returns {boolean}
	*/
			function supportsHTML5Storage() {
				try {
					return 'localStorage' in window && window['localStorage'] !== null;
				} catch (e) {
					return false;
				}
			}

	/**
	* Test data. This data will be safe by the web app
	* in the first successful login of a auth user.
	* To Test the scripts, delete the localstorage data
	* and comment this call.
	*
	* @returns {boolean}
	*/
			function testLocalStorageData() {
				if(!supportsHTML5Storage()) { return false; }
				localStorage.setItem("PROFILE_IMG_SRC", "//lh3.googleusercontent.com/-6V8xOA6M7BA/AAAAAAAAAAI/AAAAAAAAAAA/rzlHcD0KYwo/photo.jpg?sz=120" );
				localStorage.setItem("PROFILE_NAME", "César Izquierdo Tello");
				localStorage.setItem("PROFILE_REAUTH_EMAIL", "oneaccount@gmail.com");
			}
