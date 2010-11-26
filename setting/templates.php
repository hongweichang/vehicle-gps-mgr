
<TABLE   cellSpacing=0   cellPadding=0   width=100   border=0>   
<input type="hidden" name="hi_color{{selectedValue}}" id="hi_color{{selectedValue}}" value="{{d_value}}">
      <TBODY>   

      <TR>   

          <TD   id=selectLength{{selectLength}}     

          style="BORDER-RIGHT:   0px;   PADDING-RIGHT:   0px;   BORDER-TOP:   #404040   2px   inset;   PADDING-LEFT:   0px;   FONT-SIZE:   9pt;   PADDING-BOTTOM:   0px;   BORDER-LEFT:   #404040   2px   inset;   PADDING-TOP:   0px;   BORDER-BOTTOM:   #d4d0c8   1px   solid;   HEIGHT:   20px"     

          width="100%">   

              <DIV   id=selectedValue{{selectedValue}}     

              style="BORDER-RIGHT:   0px;   PADDING-RIGHT:   2px;   BORDER-TOP:   0px;   PADDING-LEFT:   2px;   FONT-SIZE:   9pt;   PADDING-BOTTOM:   2px;   VERTICAL-ALIGN:   bottom;   BORDER-LEFT:   0px;   WIDTH:   100%;   PADDING-TOP:   2px;   BORDER-BOTTOM:   0px;   HEIGHT:   20px">{{default_color_selected}}</DIV></TD>   

          <TD     

          style="BORDER-RIGHT:   #d4d0c8   1px   solid;   PADDING-RIGHT:   0px;   BORDER-TOP:   #404040   2px   inset;   PADDING-LEFT:   0px;   FONT-SIZE:   9pt;   PADDING-BOTTOM:   0px;   BORDER-LEFT:   0px;   PADDING-TOP:   0px;   BORDER-BOTTOM:   #d4d0c8   1px   solid;   HEIGHT:   20px"     

          ><IMG   id=mm   onclick=mm_Click_{{func}}()  

              src="images/down.gif"  height='18'  align=absMiddle   border=0>     

      </TD></TR></TBODY></TABLE>   

  <DIV   id=dropdownOption{{dropdownOption}}     
onclick="mm_Click_{{func}}()"
  style="BORDER-RIGHT:   #080808   1px   solid;   BORDER-TOP:   #080808   1px   solid;   Z-INDEX:   1000;   VISIBILITY:   hidden;   BORDER-LEFT:   #080808   1px   solid;   WIDTH:   60;   BORDER-BOTTOM:   #080808   1px   solid;   POSITION:   absolute">   

  <TABLE   class=optionForSel   cellSpacing=1   cellPadding=0   width="100%"     

  bgColor=white>   

      <TBODY>   

     {{default_color}}
			  
			  
		</TBODY>
	</TABLE>
			  
</DIV>


  <SCRIPT>   

	var hi_value;

  function mm_Click_{{func}}()
  {
	  if(document.all.dropdownOption{{dropdownOption}}.style.visibility   ==   'visible')
	  {
	  	document.all.dropdownOption{{dropdownOption}}.style.visibility='hidden' ;
	  }
	  else
	  {
	  	document.all.dropdownOption{{dropdownOption}}.style.visibility='visible';
	  }   
	}
  function init_{{func}}()
  {

	  document.all.dropdownOption{{dropdownOption}}.style.width   =   document.all.selectLength{{selectLength}}.clientWidth   +   22;   
	  document.all.selectedValue{{selectedValue}}.contentEditable   =   true;   
	  var   strTop   =   0;   
	  var   strLeft   =   0;   
	  var   e1   =   document.all.selectLength{{selectLength}};   
	  while(e1.tagName   !=   "BODY")   
	  {   
		  strTop   +=   e1.offsetTop ; 
		  strLeft   +=   e1.offsetLeft ;  
		  e1   =   e1.offsetParent   ;
	  }   
	  document.all.dropdownOption{{dropdownOption}}.style.top   =   String(strTop   +   24)   +   "px";   
	  document.all.dropdownOption{{dropdownOption}}.style.left   =   String(strLeft)   +   "px";   
  }
  function do_click_{{func}}(el)
  {
  	//alert(el.innerHTML);
	//给隐藏域赋值
//	alert($("#"+el.id).attr("value")+"==="+"hi_color{{selectedValue}}");
	document.getElementById("hi_color{{selectedValue}}").value = $("#"+el.id).attr("value");
  	document.all.selectedValue{{selectedValue}}.innerHTML=el.innerHTML;
  }

  window.onload   =   init_{{func}};
  </SCRIPT>   
