<script type="text/javascript">

    /***********************************************
     * Conveyor belt slideshow script- © Dynamic Drive DHTML code library (www.dynamicdrive.com)
     * This notice MUST stay intact for legal use
     * Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
     ***********************************************/


            //Specify the slider's width (in pixels)
    var sliderwidth = "300px"
    //Specify the slider's height
    var sliderheight = "150px"
    //Specify the slider's slide speed (larger is faster 1-10)
    var slidespeed = 3
    //configure background color:
    slidebgcolor = "#EAEAEA"

    //Specify the slider's images
    var leftrightslide = new Array()
    var finalslide = ''
    leftrightslide[0] = '<a href="http://"><img src="dynamicbook1.gif" border=1></a>'
    leftrightslide[1] = '<a href="http://"><img src="dynamicbook2.gif" border=1></a>'
    leftrightslide[2] = '<a href="http://"><img src="dynamicbook3.gif" border=1></a>'
    leftrightslide[3] = '<a href="http://"><img src="dynamicbook4.gif" border=1></a>'
    leftrightslide[4] = '<a href="http://"><img src="dynamicbook5.gif" border=1></a>'

    //Specify gap between each image (use HTML):
    var imagegap = " "

    //Specify pixels gap between each slideshow rotation (use integer):
    var slideshowgap = 5


    ////NO NEED TO EDIT BELOW THIS LINE////////////

    var copyspeed = slidespeed
    leftrightslide = '<nobr>' + leftrightslide.join(imagegap) + '</nobr>'
    var iedom = document.all || document.getElementById
    if (iedom)
        document.write('<span id="temp" style="visibility:hidden;position:absolute;top:-100px;left:-9000px;">' + leftrightslide + '</span>')
    var actualwidth = ''
    var cross_slide, ns_slide

    function fillup() {
        if (iedom) {
            cross_slide = document.getElementById ? document.getElementById("test2") : document.all.test2
            cross_slide2 = document.getElementById ? document.getElementById("test3") : document.all.test3
            cross_slide.innerHTML = cross_slide2.innerHTML = leftrightslide
            actualwidth = document.all ? cross_slide.offsetWidth : document.getElementById("temp").offsetWidth
            cross_slide2.style.left = actualwidth + slideshowgap + "px"
        }
        else if (document.layers) {
            ns_slide = document.ns_slidemenu.document.ns_slidemenu2
            ns_slide2 = document.ns_slidemenu.document.ns_slidemenu3
            ns_slide.document.write(leftrightslide)
            ns_slide.document.close()
            actualwidth = ns_slide.document.width
            ns_slide2.left = actualwidth + slideshowgap
            ns_slide2.document.write(leftrightslide)
            ns_slide2.document.close()
        }
        lefttime = setInterval("slideleft()", 30)
    }
    window.onload = fillup

    function slideleft() {
        if (iedom) {
            if (parseInt(cross_slide.style.left) > (actualwidth * (-1) + 8))
                cross_slide.style.left = parseInt(cross_slide.style.left) - copyspeed + "px"
            else
                cross_slide.style.left = parseInt(cross_slide2.style.left) + actualwidth + slideshowgap + "px"

            if (parseInt(cross_slide2.style.left) > (actualwidth * (-1) + 8))
                cross_slide2.style.left = parseInt(cross_slide2.style.left) - copyspeed + "px"
            else
                cross_slide2.style.left = parseInt(cross_slide.style.left) + actualwidth + slideshowgap + "px"

        }
        else if (document.layers) {
            if (ns_slide.left > (actualwidth * (-1) + 8))
                ns_slide.left -= copyspeed
            else
                ns_slide.left = ns_slide2.left + actualwidth + slideshowgap

            if (ns_slide2.left > (actualwidth * (-1) + 8))
                ns_slide2.left -= copyspeed
            else
                ns_slide2.left = ns_slide.left + actualwidth + slideshowgap
        }
    }


    if (iedom || document.layers) {
        with (document) {
            document.write('<table border="0" cellspacing="0" cellpadding="0"><td>')
            if (iedom) {
                write('<div style="position:relative;width:' + sliderwidth + ';height:' + sliderheight + ';overflow:hidden;">')
                write('<div style="position:absolute;width:' + sliderwidth + ';height:' + sliderheight + ';background-color:' + slidebgcolor + ';" onMouseover="copyspeed=0" onMouseout="copyspeed=slidespeed;">')
                write('<div id="test2" style="position:absolute;left:0px;top:0px;"></div>')
                write('<div id="test3" style="position:absolute;left:-1000px;top:0px;"></div>')
                write('</div></div>')
            }
            else if (document.layers) {
                write('<ilayer width=' + sliderwidth + ' height=' + sliderheight + ' name="ns_slidemenu" bgColor=' + slidebgcolor + '>')
                write('<layer name="ns_slidemenu2" left=0 top=0 onMouseover="copyspeed=0" onMouseout="copyspeed=slidespeed"></layer>')
                write('<layer name="ns_slidemenu3" left=0 top=0 onMouseover="copyspeed=0" onMouseout="copyspeed=slidespeed"></layer>')
                write('</ilayer>')
            }
            document.write('</td></table>')
        }
    }
</script>

<p align="center">
    <span style="font-family: Arial, sans-serif; font-size: 70%; ">Free DHTML scripts provided by<br>
        <a href="http://dynamicdrive.com">Dynamic Drive</a></span></p>
<table cellspacing="0" style="padding-bottom: 5px;">
    <tr>
        <td>
            <{if $block.fadeImage != ""}> <{literal}>
                <script type="text/javascript">
                    <!--
                    nereidFadeObjects = new Object();
                    nereidFadeTimers = new Object();
                    function nereidFade(object, destOp, rate, delta) {
                        if (!document.all) {
                            return;
                        }
                        if (object != '[object]') {
                            setTimeout('nereidFade(' + object + ',' + destOp + ',' + rate + ',' + delta + ')', 0);
                            return;
                        }
                        clearTimeout(nereidFadeTimers[object.sourceIndex]);
                        diff = destOp - object.filters.alpha.opacity;
                        direction = 1;
                        if (object.filters.alpha.opacity > destOp) {
                            direction = -1;
                        }
                        delta = Math.min(direction * diff, delta);
                        object.filters.alpha.opacity += direction * delta;

                        if (object.filters.alpha.opacity != destOp) {
                            nereidFadeObjects[object.sourceIndex] = object;
                            nereidFadeTimers[object.sourceIndex] = setTimeout('nereidFade(nereidFadeObjects[' + object.sourceIndex + '],' + destOp + ',' + rate + ',' + delta + ')', rate);
                        }
                    }
                    //-->
                </script>
            <{/literal}> <{/if}>
            <div align='center'>
                <br>

                <{$partner.urllink}> <{if $partner.image != ""}>
                    <img src="<{$partner.image}>" <{$partner.img_attr}> border="0" alt="<{$partner.title}>" <{$block.fadeImage}> />
                    <br>
                <{/if}> <{$partner.title}></a><{if $block.insertBr != ""}>
            <br><br> <{/if}>

            </div>
        </td>
    </tr>
    <{if $block.see_all}>
        <tr align="center">
            <td><a href="<{$block.smartpartner_url}>"><{$block.lang_see_all}></a>
            </td>
        </tr>
    <{/if}>
</table>
