/**
 * tc-charts theme for Highcharts JS
 * @author Amy Carlson
 */

// Load the fonts
Highcharts.createElement('link', {
	href: '//fonts.googleapis.com/css?family=Lato:400,700',
	rel: 'stylesheet',
	type: 'text/css'
}, null, document.getElementsByTagName('head')[0]);



Highcharts.theme = {
	colors: ["#55BF3B", "#DF5353", "#7798BF", "#aaeeee","#a4b357", "#e0b24e", "#f85032", "#3a8dbc", "#dce3ec", "#ff0066", "#eeaaee"],
	chart: {
		backgroundColor: null,
		style: {
			fontFamily: "Lato, serif"
		}
	},
	title: {
		style: {
			color: 'black',
			fontSize: '16px',
			fontWeight: 'bold'
		}
	},
	subtitle: {
		style: {
			color: 'black'
		}
	},
	tooltip: {
		borderWidth: 0
	},
	legend: {
		itemStyle: {
			fontWeight: 'bold',
			fontSize: '13px'
		}
	},
	xAxis: {
		labels: {
			style: {
				color: '#6e6e70'
			}
		}
	},
	yAxis: {
		labels: {
			style: {
				color: '#6e6e70'
			}
		}
	},
	plotOptions: {
		series: {
			shadow: true
		},
		candlestick: {
			lineColor: '#404048'
		},
		map: {
			shadow: false
		}
	},

	// Highstock specific
	navigator: {
		xAxis: {
			gridLineColor: '#D0D0D8'
		}
	},
	rangeSelector: {
		buttonTheme: {
			fill: 'white',
			stroke: '#C0C0C8',
			'stroke-width': 1,
			states: {
				select: {
					fill: '#D0D0D8'
				}
			}
		}
	},
	scrollbar: {
		trackBorderColor: '#C0C0C8'
	},

	// General
	background2: '#E0E0E8'

};

// Apply the theme
Highcharts.setOptions(Highcharts.theme);
