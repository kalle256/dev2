
  // This is called with the results from from FB.getLoginStatus().
  function statusChangeCallback(response) {
    console.log('statusChangeCallback');
    console.log(response);
    // The response object is returned with a status field that lets the
    // app know the current login status of the person.
    // Full docs on the response object can be found in the documentation
    // for FB.getLoginStatus().
    if (response.status === 'connected') {
      // Kayttaja kirjattuna heijastus.fi sivuille ja FB:hen
      testAPI();
    } else if (response.status === 'not_authorized') {
      // The person is logged into Facebook, but not your app.
      
        alert ( 'K‰ytt‰j‰ on jo kirjautuneen FB:ss‰ mutta ei heijastus.fi sivuilla FB:n tunnistamana' );
    } else {
      // The person is not logged into Facebook, so we're not sure if
      // they are logged into this app or not.
      document.getElementById('status').innerHTML = 'Kirjaudu heijastus.fi sivuille ' +
        'Facebook tunnuksillasi.';
    }
  }

  // This function is called when someone finishes with the Login
  // Button.  See the onlogin handler attached to it in the sample
  // code below.
  function LogoutFBaccount() {
  FB.logout(function(response) {

        // Person is now logged out
    });
//    alert ( 'FB:n uloskirjaus suoritettu' );
  }

  // This function is called when someone finishes with the Login
  // Button.  See the onlogin handler attached to it in the sample
  // code below.
  function checkLoginState() {
    FB.getLoginStatus(function(response) {
      statusChangeCallback(response);
    });
  }

  window.fbAsyncInit = function() {
  FB.init({
    appId      : 'PlaceYOURappIDhere',
    cookie     : true,  // enable cookies to allow the server to access 
                        // the session
    xfbml      : true,  // parse social plugins on this page
    version    : 'v2.0' // use version 2.0
  });

  // Now that we've initialized the JavaScript SDK, we call 
  // FB.getLoginStatus().  This function gets the state of the
  // person visiting this page and can return one of three states to
  // the callback you provide.  They can be:
  //
  // 1. Logged into your app ('connected')
  // 2. Logged into Facebook, but not your app ('not_authorized')
  // 3. Not logged into Facebook and can't tell if they are logged into
  //    your app or not.
  //
  // These three cases are handled in the callback function.

/*  FB.getLoginStatus(function(response) {
    statusChangeCallback(response);
  });
*/

  };

  // Load the SDK asynchronously

  (function(d, s, id){
         var js, fjs = d.getElementsByTagName(s)[0];
         if (d.getElementById(id)) return;
         js = d.createElement(s); js.id = id;
         js.src = "//connect.facebook.net/fi_FI/sdk.js";
         fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));


  // Here we run a very simple test of the Graph API after login is
  // successful.  See statusChangeCallback() for when this call is made.
  // https://developers.facebook.com/docs/graph-api/reference/v2.1/user/
  
  function testAPI() {
    console.log('Tervetuloa !  Haetaan k‰ytt‰j‰kohtainen informaatio FB:n tietokannoista ');
    FB.api('/me', function(response) {
      console.log('Onnistunut kirjautuminen k‰ytt‰j‰lle : ' + response.name);
//      alert ('Tervetuloa heijastus.fi sivustoille, ' + response.first_name + ' - ' + response.last_name + response.id );
      
        $.post(
          "http://www.heijastus.fi/keh/index.php",
          { FBKayttajaEtuNimi: response.first_name, FBKayttajaSukuNimi: response.last_name, FBKayttajaID: response.id },
          function(data) {

            var VastauksenTulkitsija = data.indexOf("FBSISAANKIRJAUTUMINENEPAONNISTUI");

            if ( VastauksenTulkitsija == 2 ) {
              document.getElementById("SisaanKirjautuminenEpaonnistuiTeksti").innerHTML = "Sis‰‰nkirjautuminen sivustoille heijastus.fi tunnuksellasi " + 
              response.first_name + " " + response.last_name + " Facebookin kautta ep‰onnistui"
              NaytaSisaanKirjautuminenEpaonnistuiDialog();
            }
            else {
              alert("11111111");
              window.open("http://heijastus.fi/keh/index.php", "_self");
            }              
          }
        );

/* make the API call */
    FB.api(
    "/me/groups",
    function (response) {
      if (response && !response.error) {
        alert("1212");
      }

    }
    );

      $( "#KirjautumisDialog" ).dialog( "close" );                        

    });
  }

