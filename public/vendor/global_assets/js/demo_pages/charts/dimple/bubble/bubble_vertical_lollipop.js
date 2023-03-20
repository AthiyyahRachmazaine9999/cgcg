/* ------------------------------------------------------------------------------
 *
 *  # Dimple.js - vertical lollipop
 *
 *  Demo of vertical lollipop chart. Data stored in .tsv file format
 *
 * ---------------------------------------------------------------------------- */


// Setup module
// ------------------------------

var DimpleBubbleVerticalLollipop = function() {


    //
    // Setup module components
    //

    // Chart
    var _bubbleVerticalLollipop = function() {
        if (typeof dimple == 'undefined') {
            console.warn('Warning - dimple.min.js is not loaded.');
            return;
        }

        // Main variables
        var element = document.getElementById('dimple-bubble-lollipop-vertical');


        // Initialize chart only if element exsists in the DOM
        if(element) {

            // Construct chart
            var svg = dimple.newSvg(element, "100%", 500);


            // Chart setup
            // ------------------------------

            d3.tsv("../../../../global_assets/demo_data/dimple/demo_data.tsv", function (data) {

                // Filter data
                data = dimple.filterData(data, "Date", [
                    "01/07/2011", "01/08/2011", "01/09/2011",
                    "01/10/2011", "01/11/2011", "01/12/2011"
                ]);


                // Create chart
                // ------------------------------

                // Define chart
                var myChart = new dimple.chart(svg, data);

                // Set bounds
                myChart.setBounds(0, 0, "100%", "100%");

                // Set margins
                myChart.setMargins(55, 35, 10, 45);


                // Create axes
                // ------------------------------

                // Horizontal
                var x = myChart.addCategoryAxis("x", "Month");
                    x.addOrderRule("Date");

                // Vertical
                var y = myChart.addMeasureAxis("y", "Unit Sales");

                // Other axes
                myChart.addMeasureAxis("z", "Operating Profit");


                // Construct layout
                // ------------------------------

                // Add bubbles
                myChart.addSeries("Channel", dimple.plot.bubble);


                // Add legend
                // ------------------------------

                var myLegend = myChart.addLegend(0, 5, "100%", 0, "left");


                // Add styles
                // ------------------------------

                // Font size
                x.fontSize = "12";
                y.fontSize = "12";

                // Font family
                x.fontFamily = "Roboto";
                y.fontFamily = "Roboto";

                // Legend font style
                myLegend.fontSize = "12";
                myLegend.fontFamily = "Roboto";


                //
                // Draw chart
                //

                // Draw
                myChart.draw();

                // Position legend text
                myLegend.shapes.selectAll("text").attr("dy", "1");


                // Resize chart
                // ------------------------------

                // Add a method to draw the chart on resize of the window
                $(window).on('resize', resize);
                $('.sidebar-control').on('click', resize);

                // Resize function
                function resize() {

                    // Redraw chart
                    myChart.draw(0, true);

                    // Position legend text
                    myLegend.shapes.selectAll("text").attr("dy", "1");
                }
            });
        }
    };


    //
    // Return objects assigned to module
    //

    return {
        init: function() {
            _bubbleVerticalLollipop();
        }
    }
}();


// Initialize module
// ------------------------------

document.addEventListener('DOMContentLoaded', function() {
    DimpleBubbleVerticalLollipop.init();
});
