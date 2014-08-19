
    $(function(){
      $.contextMenu({
        selector: 'img',
          callback: function (key, options) {
            var m = "clicked: " + key;
            
            if (key == "Kuvateksti") 
              KuvalleUusiKuvaTeksti($(this).attr('id'));
            else {              
              var m = "klikattu menuvalikossa : " + key + " tiedosto polkuineen -> " + $(this).attr('id');
              window.console && console.log(m) || alert(m); 
            }              
            
          },
          items: {
            "TestiToiminne"     : { "name": "Testitoiminne" },
            "Kuvateksti"        : { name: "Kirjoita kuvalle kuvateksti", icon: "edit"}

                }
          });
    });
        
    $(function(){
      
      $.contextMenu({
        selector: 'a',
          callback: function (key, options) {

            if (key == "Hakemistoteksti") 
              HakemistolleUusiTeksti( $(this).attr('id') );
            if (key == "HakemistoOikeudet")  {
              NaytaKayttajaryhmienLukuOikeudetDialog( $(this).attr('id') );
//              window.console && console.log(m) || alert(m);
              }

          },
          // malli http://medialize.github.io/jQuery-contextMenu/demo/input.html
          items:  {
            "TestiToiminne"     : { "name": "Testitoiminne" },
            "Hakemistoteksti"   : { "name": "Kirjoita hakemistolle kuvaus", "icon": "edit"},
            "Erotin"              :  "---------",
            "HakemistoOikeudet" : { "name": "Asettele käyttäjäryhmien lukuoikeudet" },
                  }
      });                  
    });
        
