(function($)
{
    $.fn.hoverup=function(k)
    {
        var k=$.extend(
            {
            }
            ,$.fn.hoverup.defaults,k);
        this.each(function()
            {
                var o=$.metadata?$.extend(
                    {
                    }
                    ,k,$(this).metadata()):k;
                var a=$(this);
                var b=a.children(':first');
                var c=a.children(':last');
                if(a.css('position')=='static')
                {
                    a.css('position','relative')
                }
                a.css('overflow','hidden');
                if(b.css('display')=='inline')
                {
                    b.css('display','block')
                }
                c.css(
                    {
                        position:'absolute',left:0,right:0,margin:0
                    }
                );
                var d=c.outerHeight();
                c.css(o.position,-d);
                if(o.fade)
                {
                    c.css('opacity',0)
                }
                a.css('height',a.height());
                var e=
                {
                };
                e[o.position]=0;
                e.opacity=1;
                var f=
                {
                };
                f.marginTop=(o.shiftContent=='push')?d:Number(o.shiftContent);
                if(o.position=='bottom')
                {
                    f.marginTop=-f.marginTop
                }
                var g=function()
                {
                    c.stop(true).animate(e,o.speedIn,o.easingIn);
                    if(o.shiftContent)
                    {
                        b.stop(true).animate(f,o.speedIn,o.easingIn)
                    }
                };
                var h=
                {
                };
                h[o.position]=-d;
                h.opacity=Number(!o.fade);
                var i=
                {
                    marginTop:0
                };
                var j=function()
                {
                    c.stop(true).animate(h,o.speedOut,o.easingOut);
                    if(o.shiftContent)
                    {
                        b.stop(true).animate(i,o.speedOut,o.easingOut)
                    }
                };
                if(o.reverse)
                {
                    c.css(e);
                    b.css(f);
                    a.hover(j,g)
                }
                else
                {
                    a.hover(g,j)
                }
            }
        );
        return this
    };
    $.fn.hoverup.defaults=
    {
        easingIn:'swing',easingOut:'swing',fade:true,position:'bottom',reverse:false,shiftContent:false,speedIn:'fast',speedOut:'normal'
    }
}
    )(jQuery);
