/* Flot plugin that adds some extra symbols for plotting points.

Copyright (c) 2007-2014 IOLA and Ole Laursen.
Licensed under the MIT license.

The symbols are accessed as strings through the standard symbol options:

	series: {
		points: {
			symbol: "square" // or "diamond", "triangle", "cross"
		}
	}

*/

(function ($) {
    function processRawData(plot, series, datapoints) {
        // we normalize the area of each symbol so it is approximately the
        // same as a circle of the given radius
       // console.log(series);

        var handlers = {
            square: function (ctx, x, y, radius, shadow) {
                // pi * r^2 = (2s)^2  =>  s = r * sqrt(pi)/2
                var size = radius * Math.sqrt(Math.PI) / 2;
                ctx.rect(x - size, y - size, size + size, size + size);
            },
            diamond: function (ctx, x, y, radius, shadow) {
                // pi * r^2 = 2s^2  =>  s = r * sqrt(pi/2)
                var size = radius * Math.sqrt(Math.PI / 2);
                ctx.moveTo(x - size, y);
                ctx.lineTo(x, y - size);
                ctx.lineTo(x + size, y);
                ctx.lineTo(x, y + size);
                ctx.lineTo(x - size, y);
            },
            triangle: function (ctx, x, y, radius, shadow) {
                // pi * r^2 = 1/2 * s^2 * sin (pi / 3)  =>  s = r * sqrt(2 * pi / sin(pi / 3))
                var size = radius * Math.sqrt(2 * Math.PI / Math.sin(Math.PI / 3));
                var height = size * Math.sin(Math.PI / 3);
                ctx.moveTo(x - size/2, y + height/2);
                ctx.lineTo(x + size/2, y + height/2);
                if (!shadow) {
                    ctx.lineTo(x, y - height/2);
                    ctx.lineTo(x - size/2, y + height/2);
                }
            },
            cross: function (ctx, x, y, radius, shadow) {
          
                // pi * r^2 = (2s)^2  =>  s = r * sqrt(pi)/2
                var size = radius * Math.sqrt(Math.PI) / 2;
                ctx.moveTo(x - size, y - size);
                ctx.lineTo(x + size, y + size);
                ctx.moveTo(x - size, y + size);
                ctx.lineTo(x + size, y - size);
            },

            esquerda: function (ctx, x, y, radius, shadow) {
                //console.log('esquerda');
                // pi * r^2 = (2s)^2  =>  s = r * sqrt(pi)/2
                var size = radius * Math.sqrt(Math.PI) / 2;
                ctx.moveTo(x - size, y);
                ctx.lineTo(x, y - size);
                /*ctx.lineTo(x + size, y);*/
                ctx.lineTo(x, y + size);
                /*ctx.lineTo(x - size, y);*/
            },

            direita: function (ctx, x, y, radius, shadow) {
                //console.log('direita');
                // pi * r^2 = (2s)^2  =>  s = r * sqrt(pi)/2
                var size = radius * Math.sqrt(Math.PI) / 2;
                /*ctx.moveTo(x - size, y);*/
                ctx.lineTo(x, y - size);
                ctx.lineTo(x + size, y);
                ctx.lineTo(x, y + size);
                /*ctx.lineTo(x - size, y);*/
            },

            circulo_or_triangulo: function(ctx, x, y, radius, shadow){
                console.log(this.numCalls);
                if(this.numCalls == undefined){
                    this.numCalls = -1;
                }
                this.numCalls++;
                if(this.numCalls >= series.data.length){
                   this.numCalls = this.numCalls - series.data.length;                   
                }

                if(series.data[this.numCalls][2] == true){
                    //show triangulo    
                    var size = radius * Math.sqrt(2 * Math.PI / Math.sin(Math.PI / 3));
                    var height = size * Math.sin(Math.PI / 3);
                    ctx.moveTo(x - size/2, y + height/2);
                    ctx.lineTo(x + size/2, y + height/2);
                    if (!shadow) {
                        ctx.lineTo(x, y - height/2);
                        ctx.lineTo(x - size/2, y + height/2);
                    }
                }else{
                    var size = radius * Math.sqrt(Math.PI / 2);
                    ctx.moveTo(x - size, y);
                    ctx.lineTo(x, y - size);
                    ctx.lineTo(x + size, y);
                    ctx.lineTo(x, y + size);
                    ctx.lineTo(x - size, y);

                }
            }



        };

      
           
            var s = series.points.symbol;
            //console.log(series);
           // console.log(series.data.length);
            if (handlers[s])
            { 
               series.points.symbol = handlers[s];
                
            }
       

    }
    
    function init(plot) {
        plot.hooks.processDatapoints.push(processRawData);
    }
    
    $.plot.plugins.push({
        init: init,
        name: 'symbols',
        version: '1.0'
    });
})(jQuery);
