
  
  
  
  function fnLeftToRight(getdropdown)
  {
    getdropdown.style.direction = "ltr";
  }

  function fnRightToLeft(getdropdown)
  {
    getdropdown.style.direction = "rtl";
  }
  function FindKeyCode(e)
  {
	
    if(e.which)
    {
    keycode=e.which;  //NetscapeFirefoxChrome
    }
    else
    {
    keycode=e.keyCode; //Internet Explorer
    }

    return keycode;
  }

  function FindKeyChar(e)
  {
    keycode = FindKeyCode(e);
    if((keycode==8)||(keycode==127))
    {
    character="backspace"
    }
    else if((keycode==46))
    {
    character="delete"
    }
    else
    {
    character=String.fromCharCode(keycode);
    }

    return character;
  }

  var vEditableOptionIndex_A = 0;
  var vEditableOptionText_A = "----";
  var vPreviousSelectIndex_A = 0;
  var vSelectIndex_A = 0;
  var vSelectChange_A = 'MANUAL_CLICK';
  function fnSanityCheck_A(getdropdown)
  {
    if(vEditableOptionIndex_A>(getdropdown.options.length-1))
    {
    alert("PROGRAMMING ERROR: The value of variable vEditableOptionIndex_... cannot be greater than (length of dropdown - 1)");
    return false;
    }
  }

  function fnKeyDownHandler_A(getdropdown, e)
  {
    fnSanityCheck_A(getdropdown);

    var vEventKeyCode = FindKeyCode(e);
    if(vEventKeyCode == 37)
    {
      fnLeftToRight(getdropdown);
    }
    if(vEventKeyCode == 39)
    {
      fnRightToLeft(getdropdown);
    }
    if(vEventKeyCode == 46)
    {
      if(getdropdown.options.length != 0)

      {
        if (getdropdown.options.selectedIndex == vEditableOptionIndex_A)

        {
          getdropdown.options[getdropdown.options.selectedIndex].text = '';
         // getdropdown.options[getdropdown.options.selectedIndex].value = '';
	 
        }
      }
    }
    if(vEventKeyCode == 8 || vEventKeyCode == 127)
    {
      if(getdropdown.options.length != 0)
      {
        if (getdropdown.options.selectedIndex == vEditableOptionIndex_A)
        {
           if (getdropdown[vEditableOptionIndex_A].text == vEditableOptionText_A)//||(getdropdown[vEditableOptionIndex_A].value == vEditableOptionText_A))
           {
             getdropdown.options[getdropdown.options.selectedIndex].text = '';
            // getdropdown.options[getdropdown.options.selectedIndex].value = '';
           }
           else
           {
             getdropdown.options[getdropdown.options.selectedIndex].text = getdropdown.options[getdropdown.options.selectedIndex].text.slice(0,-1);
           //  getdropdown.options[getdropdown.options.selectedIndex].value = getdropdown.options[getdropdown.options.selectedIndex].value.slice(0,-1);
           }
        }
      }
      if(e.which) //NetscapeFirefoxChrome
      {
        e.which = '';
      }
      else //Internet Explorer
      {
        e.keyCode = '';
      }
      if (e.cancelBubble)	  //Internet Explorer
      {
        e.cancelBubble = true;
        e.returnValue = false;
      }
      if (e.stopPropagation)	 //NetscapeFirefoxChrome
      {
          e.stopPropagation();
      }
      if (e.preventDefault)	 //NetscapeFirefoxChrome
      {
      	e.preventDefault();
      }
    }
  }

  function fnChangeHandler_A(getdropdown)
  {
  // alert(getdropdown.value);
    fnSanityCheck_A(getdropdown);

    vPreviousSelectIndex_A = vSelectIndex_A;
    // Contains the Previously Selected Index

    vSelectIndex_A = getdropdown.options.selectedIndex;
    // Contains the Currently Selected Index

    if ((vPreviousSelectIndex_A == (vEditableOptionIndex_A)) && (vSelectIndex_A != (vEditableOptionIndex_A))&&(vSelectChange_A != 'MANUAL_CLICK'))
    // To Set value of Index variables - Subrata Chakrabarty
    {
      getdropdown[(vEditableOptionIndex_A)].selected=true;
      vPreviousSelectIndex_A = vSelectIndex_A;
      vSelectIndex_A = getdropdown.options.selectedIndex;
      vSelectChange_A = 'MANUAL_CLICK';
      // Indicates that the Change in dropdown selected
      // option was due to a Manual Click
    }
   
  }
  
  function isInArray(value, array) {
		
	  return $.inArray(value, array) != -1;
	}


  function fnKeySegmentPressHandler_A(getdropdown, e)
  {
    fnSanityCheck_A(getdropdown);

    keycode = FindKeyCode(e);
    keychar = FindKeyChar(e);
	
	
    var notAllowedLevelArr = [126,33,64,35,94,38,42,40,41,63];

	if(isInArray(keycode, notAllowedLevelArr)){
		return false;
	}
	
    if ((keycode>30 && keycode<59)||(keycode>62 && keycode<127) ||(keycode==32))
    {
      
      var vAllowableCharacter = "yes";
    }
    else
    {
      var vAllowableCharacter = "no";
    }



    if(getdropdown.options.length != 0)

      if (getdropdown.options.selectedIndex == (vEditableOptionIndex_A))

      {
       // var vEditString = getdropdown[vEditableOptionIndex_A].value;
          var vEditString = getdropdown[vEditableOptionIndex_A].text;


        if(vAllowableCharacter == "yes")
        {
          if (getdropdown[vEditableOptionIndex_A].text == vEditableOptionText_A)//||(getdropdown[vEditableOptionIndex_A].value == vEditableOptionText_A))
            vEditString = "";
        }

        if (vAllowableCharacter == "yes")

        {
          vEditString+=String.fromCharCode(keycode);
          var i=0;
          var vEnteredChar = String.fromCharCode(keycode);
          var vUpperCaseEnteredChar = vEnteredChar;
          var vLowerCaseEnteredChar = vEnteredChar;


          if(((keycode)>=97)&&((keycode)<=122))

            vUpperCaseEnteredChar = String.fromCharCode(keycode - 32);



          if(((keycode)>=65)&&((keycode)<=90))

            vLowerCaseEnteredChar = String.fromCharCode(keycode + 32);
          if(e.which)
          {
            for (i=0;i<=(getdropdown.options.length-1);i++)
            {
              if(i!=vEditableOptionIndex_A)
              {
               // var vReadOnlyString = getdropdown[i].value;
	            var vReadOnlyString = getdropdown[i].value;
                var vFirstChar = vReadOnlyString.substring(0,1);
                if((vFirstChar == vUpperCaseEnteredChar)||(vFirstChar == vLowerCaseEnteredChar))
                {
                  vSelectChange_A = 'AUTO_SYSTEM';

                  break;
                }
                else
                {
                  vSelectChange_A = 'MANUAL_CLICK';

                }
              }
            }
          }
        }


        getdropdown.options[vEditableOptionIndex_A].text = vEditString;
      //  getdropdown.options[vEditableOptionIndex_A].value = vEditString;

        return false;
      }
    return true;
  }

  function fnKeyPressHandler_A(getdropdown, e)
  {
    fnSanityCheck_A(getdropdown);

    keycode = FindKeyCode(e);
    keychar = FindKeyChar(e);

    if ((keycode>30 && keycode<59)||(keycode>62 && keycode<127) ||(keycode==32))
    {
      
      var vAllowableCharacter = "yes";
    }
    else
    {
      var vAllowableCharacter = "no";
    }



    if(getdropdown.options.length != 0)

      if (getdropdown.options.selectedIndex == (vEditableOptionIndex_A))

      {
       // var vEditString = getdropdown[vEditableOptionIndex_A].value;
          var vEditString = getdropdown[vEditableOptionIndex_A].text;


        if(vAllowableCharacter == "yes")
        {
          if (getdropdown[vEditableOptionIndex_A].text == vEditableOptionText_A)//||(getdropdown[vEditableOptionIndex_A].value == vEditableOptionText_A))
            vEditString = "";
        }

        if (vAllowableCharacter == "yes")

        {
          vEditString+=String.fromCharCode(keycode);
          var i=0;
          var vEnteredChar = String.fromCharCode(keycode);
          var vUpperCaseEnteredChar = vEnteredChar;
          var vLowerCaseEnteredChar = vEnteredChar;


          if(((keycode)>=97)&&((keycode)<=122))

            vUpperCaseEnteredChar = String.fromCharCode(keycode - 32);



          if(((keycode)>=65)&&((keycode)<=90))

            vLowerCaseEnteredChar = String.fromCharCode(keycode + 32);
          if(e.which)
          {
            for (i=0;i<=(getdropdown.options.length-1);i++)
            {
              if(i!=vEditableOptionIndex_A)
              {
               // var vReadOnlyString = getdropdown[i].value;
	            var vReadOnlyString = getdropdown[i].value;
                var vFirstChar = vReadOnlyString.substring(0,1);
                if((vFirstChar == vUpperCaseEnteredChar)||(vFirstChar == vLowerCaseEnteredChar))
                {
                  vSelectChange_A = 'AUTO_SYSTEM';

                  break;
                }
                else
                {
                  vSelectChange_A = 'MANUAL_CLICK';

                }
              }
            }
          }
        }


        getdropdown.options[vEditableOptionIndex_A].text = vEditString;
      //  getdropdown.options[vEditableOptionIndex_A].value = vEditString;

        return false;
      }
    return true;
  }

  function fnKeySegmentUpHandler_A(getdropdown, e)
  {
    if(FindKeyCode(e) == 32){
      console.log('enter here')
      console.log(getdropdown)
      var ddId = getdropdown.id;
      var nu = ddId.split('cGDis');
      console.log(nu);
      var optionTxt = document.getElementById('cGDisCust'+nu[1]).innerHTML;
      console.log(optionTxt);
      document.getElementById('cGDisCust'+nu[1]).innerHTML = optionTxt +'&nbsp;';
    }
	
    fnSanityCheck_A(getdropdown);

    if(e.which)
    {
      if(vSelectChange_A == 'AUTO_SYSTEM')
      {

        getdropdown[(vEditableOptionIndex_A)].selected=true;
      }

      var vEventKeyCode = FindKeyCode(e);

      if((vEventKeyCode == 37)||(vEventKeyCode == 39))
      {
        getdropdown[vEditableOptionIndex_A].selected=true;
      }
    }
  }

  function fnKeyUpHandler_A(getdropdown, e)
  {
    if(FindKeyCode(e) == 32){
      console.log('enter here')
      console.log(getdropdown)
      var ddId = getdropdown.id;
      var nu = ddId.split('cGDis');
      console.log(nu);
      var optionTxt = document.getElementById('cGDisCust'+nu[1]).innerHTML;
      console.log(optionTxt);
      document.getElementById('cGDisCust'+nu[1]).innerHTML = optionTxt +'&nbsp;';
    }

    fnSanityCheck_A(getdropdown);

    if(e.which)
    {
      if(vSelectChange_A == 'AUTO_SYSTEM')
      {

        getdropdown[(vEditableOptionIndex_A)].selected=true;
      }

      var vEventKeyCode = FindKeyCode(e);

      if((vEventKeyCode == 37)||(vEventKeyCode == 39))
      {
        getdropdown[vEditableOptionIndex_A].selected=true;
      }
    }
  }



