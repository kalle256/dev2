
    $(function(){
      $.contextMenu({
        selector: 'img',
          callback: function (key, options) {
            var m = "clicked: " + key;
            if (key == "EiKaytossa") 
            {  
              var m = "klikattu EiKaytossa menuvalikossa : " + key + " tiedosto polkuineen -> " + $(this).attr('id');
              window.console && console.log(m) || alert(m); 
            }
            if (key == "Kuvateksti") 
            {  
              KuvalleUusiKuvaTeksti($(this).attr('id'));
            }
          },
          items: {
            "EiKaytossa"     : { name: "Ei Käytössä" },
            "Kuvateksti"     : { name: "Kirjoita kuvalle kuvateksti", "icon": "edit"},
                }
          });
    });
        
    $(function(){
      $.contextMenu({
        selector: 'a',
          callback: function (key, options) {
            var m = "clicked: " + key;
            if (key == "EiKaytossa") 
            {  
              var m = "klikattu EiKaytossa menuvalikossa : " + key + " hakemisto polkuineen -> " + $(this).attr('id');
              window.console && console.log(m) || alert(m); 
            }
            if (key == "Hakemistoteksti") 
            {  
              HakemistolleUusiTeksti( $(this).attr('id') );
            }
          },
          items: {
            "EiKaytossa"       : { name: "Ei Käytössä" },
            "Hakemistoteksti"  : { name: "Kirjoita hakemistolle kuvaus" },
          }
        });
    });
        
